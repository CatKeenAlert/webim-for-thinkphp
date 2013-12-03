<?php

class ApiAction extends Action {

	/*
	 * Webim Ticket
	 */
	private $ticket;

	/*
	 * Webim Client
	 */
	private $client;

	/*
	 * 与ThinkPHP接口类实例
	 */
	private $thinkim;

	private $settingModel;

	private $historyModel;

	function _initialize() {
		$imc = C('IMC');

		//IM Ticket
		$imticket = $this->_param('ticket');
		if($imticket) $imticket = stripslashes($imticket);	
		$this->ticket = $imticket;

		//Initialize ThinkIM
		$this->thinkim = new ThinkIM();

		//IM Client
		$this->client = new WebimClient($this->thinkim->user(), 
			$this->ticket, $imc['DOMAIN'], $imc['APIKEY'], $imc['HOST'], $imc['PORT']);

		//IM Models
		$this->settingModel = D("Setting");
		$this->historyModel = D("History");
	}

	function run() {
		$imc = C('IMC');
		$webim_path = WEBIM_PATH;
		$setting = json_encode($this->settingModel->get($this->thinkim->uid()));
		$imuser = json_encode($this->thinkim->user());
		//TODO: FIXME Later
		$script = <<<EOF
var _IMC = {
	production_name: 'thinkphp',
	version: '5.0',
	path: '$webim_path',
	is_login: '1',
	login_options: {},
	user: $imuser,
	setting: $setting,
	enable_chatlink: {$imc['ENABLE_CHATLINK']},
	enable_shortcut: false,
	enable_menu: {$imc['ENABLE_MENU']},
	enable_room: {$imc['ENABLE_ROOM']},
	enable_noti: {$imc['ENABLE_NOTI']},
	theme: "{$imc['THEME']}",
	local: "{$imc['LOCAL']}",
	showUnavailable: {$imc['SHOW_UNAVAILABLE']},
	min: window.location.href.indexOf("webim_debug") != -1 ? "" : ".min"
};
_IMC.script = window.webim ? '' : ('<link href="' + _IMC.path + 'static/webim.' + _IMC.production_name + _IMC.min + '.css?' + _IMC.version + '" media="all" type="text/css" rel="stylesheet"/><link href="' + _IMC.path + 'static/themes/' + _IMC.theme + '/jquery.ui.theme.css?' + _IMC.version + '" media="all" type="text/css" rel="stylesheet"/><script src="' + _IMC.path + 'static/webim.' + _IMC.production_name + _IMC.min + '.js?' + _IMC.version + '" type="text/javascript"></script><script src="' + _IMC.path + 'static/i18n/webim-' + _IMC.local + '.js?' + _IMC.version + '" type="text/javascript"></script>');
_IMC.script += '<script src="' + _IMC.path + 'webim.js?' + _IMC.version + '" type="text/javascript"></script>';
document.write( _IMC.script );

EOF;
		header("Content-type: application/javascript");
		header("Cache-Control: no-cache");
		exit($script);
	}
			
	function online() {
		$IMC = C('IMC');
		$domain = $this->_param("domain");
		if ( !$this->thinkim->logined() ) {
			$this->ajaxReturn(array( 
				"success" => false, 
				"error_msg" => "Forbidden" ),
				'JSON');
		}
		$im_buddies = array(); //For online.
		$im_rooms = array(); //For online.
		$strangers = $this->idsArray( $this->_param('stranger_ids') );
		$cache_buddies = array();//For find.
		$cache_rooms = array();//For find.

		$active_buddies = $this->idsArray( $this->_param('buddy_ids') );
		$active_rooms = $this->idsArray( $this->_param('room_ids') );

		$new_messages = $this->historyModel->getOffline($this->thinkim->uid());
		$online_buddies = $this->thinkim->buddies();
		
		$buddies_with_info = array();//Buddy with info.
		//Active buddy who send a new message.
		$count = count($new_messages);
		for($i = 0; $i < $count; $i++){
			$active_buddies[] = $new_messages[$i]->from;
		}

		//Find im_buddies
		$all_buddies = array();
		foreach($online_buddies as $k => $v){
			$id = $v->id;
			$im_buddies[] = $id;
			$buddies_with_info[] = $id;
			$v->presence = "offline";
			$v->show = "unavailable";
			$cache_buddies[$id] = $v;
			$all_buddies[] = $id;
		}

		//Get active buddies info.
		$buddies_without_info = array();
		foreach($active_buddies as $k => $v){
			if(!in_array($v, $buddies_with_info)){
				$buddies_without_info[] = $v;
			}
		}
		if(!empty($buddies_without_info) || !empty($strangers)){
			//FIXME
			$bb = $this->thinkim->buddiesByIds(implode(",", $buddies_without_info), implode(",", $strangers));
			foreach( $bb as $k => $v){
				$id = $v->id;
				$im_buddies[] = $id;
				$v->presence = "offline";
				$v->show = "unavailable";
				$cache_buddies[$id] = $v;
			}
		}
		if(!$IMC['enable_room']){
			$rooms = $this->thinkim->rooms();
			$setting = $this->settingModel->get($this->thinkim->uid());
			$blocked_rooms = $setting && is_array($setting->blocked_rooms) ? $setting->blocked_rooms : array();
			//Find im_rooms 
			//Except blocked.
			foreach($rooms as $k => $v){
				$id = $v->id;
				if(in_array($id, $blocked_rooms)){
					$v->blocked = true;
				}else{
					$v->blocked = false;
					$im_rooms[] = $id;
				}
				$cache_rooms[$id] = $v;
			}
			//Add temporary rooms 
			$temp_rooms = $setting && is_array($setting->temporary_rooms) ? $setting->temporary_rooms : array();
			for ($i = 0; $i < count($temp_rooms); $i++) {
				$rr = $temp_rooms[$i];
				$rr->temporary = true;
				$rr->pic_url = (WEBIM_PATH . "static/images/chat.png");
				$rooms[] = $rr;
				$im_rooms[] = $rr->id;
				$cache_rooms[$rr->id] = $rr;
			}
		}else{
			$rooms = array();
		}

		//===============Online===============
		//

		$data = $this->client->online( implode(",", array_unique( $im_buddies ) ), implode(",", array_unique( $im_rooms ) ) );

		if( $data->success ){
			$data->new_messages = $new_messages;

			if(!$IMC['enable_room']){
				//Add room online member count.
				foreach ($data->rooms as $k => $v) {
					$id = $v->id;
					$cache_rooms[$id]->count = $v->count;
				}
				//Show all rooms.
			}
			$data->rooms = $rooms;

			$show_buddies = array();//For output.
			foreach($data->buddies as $k => $v){
				$id = $v->id;
				if(!isset($cache_buddies[$id])){
					$cache_buddies[$id] = (object)array(
						"id" => $id,
						"nick" => $id,
						"incomplete" => true,
					);
				}
				$b = $cache_buddies[$id];
				$b->presence = $v->presence;
				$b->show = $v->show;
				if( !empty($v->nick) )
					$b->nick = $v->nick;
				if( !empty($v->status) )
					$b->status = $v->status;
				#show online buddy
				$show_buddies[] = $id;
			}
			#show active buddy
			$show_buddies = array_unique(array_merge($show_buddies, $active_buddies, $all_buddies));
			$o = array();
			foreach($show_buddies as $id){
				//Some user maybe not exist.
				if(isset($cache_buddies[$id])){
					$o[] = $cache_buddies[$id];
				}
			}

			//Provide history for active buddies and rooms
			foreach($active_buddies as $id){
				if(isset($cache_buddies[$id])){
					$cache_buddies[$id]->history = $this->historyModel->get($id, "chat" );
				}
			}
			foreach($active_rooms as $id){
				if(isset($cache_rooms[$id])){
					$cache_rooms[$id]->history = $this->historyModel->get($id, "grpchat" );
				}
			}

			$show_buddies = $o;
			$data->buddies = $show_buddies;
			$this->historyModel->offlineReaded($this->thinkim->uid());
			$this->ajaxReturn($data, 'JSON');
		} else {
			$this->ajaxReturn(array( 
				"success" => false, 
				"error_msg" => empty( $data->error_msg ) ? "IM Server Not Found" : "IM Server Not Authorized", 
				"im_error_msg" => $data->error_msg), 'JSON'); 
		}
	}

	function offline() {
		$this->client->offline();
		$this->okReturn();
	}

	function message() {
		$type = $this->_param("type");
		$offline = $this->_param("offline");
		$to = $this->_param("to");
		$body = $this->_param("body");
		$style = $this->_param("style");
		$send = $offline == "true" || $offline == "1" ? 0 : 1;
		$timestamp = $this->microtimeFloat() * 1000;
		if( strpos($body, "webim-event:") !== 0 ) {
			$this->historyModel->insert($this->thinkim->user(), array(
				"send" => $send,
				"type" => $type,
				"to" => $to,
				"body" => $body,
				"style" => $style,
				"timestamp" => $timestamp,
			));
		}
		if($send == 1){
			$this->client->message($type, $to, $body, $style, $timestamp);
		}
		$this->okReturn();
	}

	function presence() {
		$show = $this->_param('show');
		$status = $this->_param('status');
		$this->client->presence($show, $status);
		$this->okReturn();
	}

	function history() {
		$uid = $this->thinkim->uid();
		$with = $this->_param('id');
		$type = $this->_param('type');
		$histories = $this->historyModel->get($uid, $with, $type);
		$this->ajaxReturn($histories, "JSON");
	}

	function status() {
		$to = $this->_param("to");
		$show = $this->_param("show");
		$this->client->status($to, $show);
		$this->okReturn();
	}

	function members() {
		$id = $this->_param('id');
		$re = $this->client->members( $id );
		if($re) {
			$this->ajaxReturn($re, "JSON");
		} else {
			$this->ajaxReturn("Not Found", "JSON");
		}
	}

	function join() {
		$id = $this->_param('id');
		$room = $this->thinkim->roomsByIds( $id );
		if( $room && count($room) ) {
			$room = $room[0];
		} else {
			$room = (object)array(
				"id" => $id,
				"nick" => $this->_param('nick'),
				"temporary" => true,
				"pic_url" => (WEBIM_PATH . "static/images/chat.png"),
			);
		}
		if($room){
			$re = $this->client->join($id);
			if($re){
				$room->count = $re->count;
				$this->ajaxReturn($room, "JSON");
			}else{
				header("HTTP/1.0 404 Not Found");
				exit("Can't join this room right now");
			}
		}else{
			header("HTTP/1.0 404 Not Found");
			exit("Can't found this room");
		}
	}

	function leave() {
		$id = $this->_param('id');
		$this->client->leave( $id );
		$this->okReturn();
	}

	function buddies() {
		$ids = $this->_param('ids');
		$this->ajaxReturn($this->thinkim->buddiesByIds($ids), 'JSON');
	}

	function rooms() {
		$ids = $this->_param("ids");
		$this->ajaxReturn($this->thinkim->roomsByIds($ids), "JSON");	
	}

	function refresh() {
		$this->client->offline();
		$this->okReturn();
	}

	function clear_history() {
		$id = $this->_param('id'); //$with
		$this->historyModel->clear($this->thinkim->uid(), $id);
		$this->okReturn();
	}

	function download_history() {
		$id = $this->_param('id');
		$type = $this->_param('type');
		$histories = $this->historyModel->get($id, $type, 1000 );
		$date = date( 'Y-m-d' );
		if($this->_param['date']) {
			$date = $this->_param('date');
		}
		header('Content-Disposition: attachment; filename="histories-'.$date.'.html"');
		$this->assign('date', $date);
		$this->assign('histories', $histories);
		$this->display();
	}

	function setting() {
		if(isset($_GET['data'])) {
			$data = $_GET['data'];
		} 
		if(isset($_POST['data'])) {
			$data = $_POST['data'];
		}
		$uid = $this->thinkim->uid();
		$this->settingModel->set($uid, $data);
		$this->okReturn();
	}

	function notifications() {
		$notifications = $this->thinkim->notifications();
		$this->ajaxReturn($notifications, 'JSON');
	}

	function openchat() {
		$grpid = $this->_param['group_id'];
		$nick = $this->param['nick'];
		$this->ajaxReturn($this->client->openchat($grpid, $nick), 'JSON');	
	}

	function closechat() {
		$grpid = $this->_param['group_id'];
		$buddy_id = $this->_param['buddy_id'];
		$this->ajaxReturn($this->client->closechat($grpid, $buddy_id), "JSON");
	}

	private function okReturn() {
		$this->ajaxReturn('ok', 'JSON');
	}

	private function idsArray( $ids ){
		return ($ids===NULL || $ids==="") ? array() : (is_array($ids) ? array_unique($ids) : array_unique(explode(",", $ids)));
	}

	private function microtimeFloat() {
		list($usec, $sec) = explode(" ", microtime());
		return ((float)$usec + (float)$sec);
	}

}

