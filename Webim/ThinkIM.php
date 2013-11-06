<?php

class ThinkIM {

	/*
	 * Current User or Visitor
	 */
	private $user = NULL;

	/*
	 * If is visitor
	 */
	private $is_visitor = false;

	/*
	 * If logined
	 */
	private $is_login = false;

	/*
	 * TODO: 初始化当前用户信息
	 */
	function __construct() {
		$imc = C('IMC');
		if($this->getUid()) {
			$imuser = $this->newUser();			
			$this->is_login = true;
		} else if($imc['VISITOR']) {
			$imuser = $this->newVisitor();
			$this->is_login = true;
			$this->is_visitor = true;	
		}
		$this->user = $imuser;
	}

	public function currentUid() {
		return $this->user->id;
	}

	public function currentUser() {
		return $this->user;	
	}

	public function isVisitor() {
		return $this->is_visitor;
	}

	public function isLogin() {
		return $this->is_login;	
	}

	public function getBuddies() {
		//根据当前用户id获取好友列表
		return array(clone $this->user);
	}

	public function getBuddiesByIds($ids = "", $strangers = "") {
		//根据id列表获取好友列表
		return array();	
	}
	
	public function getRooms() {
		//根据当前用户id获取群组列表
		$demoRoom = array(
			"id" => '1',
			"nick" => 'demoroom',
			"url" => "#",
			"pic_url" => WEBIM_PATH . "static/images/chat.png",
			"status" => "demo room",
			"count" => 0,
			"all_count" => 1,
			"blocked" => false,
		);
		return array( $demoRoom );	
	}

	public function getNotifications() {
		return array();	
	}

	public function getRoomsByIds($ids) {
		return array();	
	}

	private function getUid() {
		return $_SESSION['uid'];
	}
	/**
	 * 初始化当前用户对象，与站点用户集成.
	 */
	private function newUser() {
		$uid = $_SESSION['uid'];
		//NOTICE: This user should be read from thinkphp database.
		$imuser = (object)array();
		$imuser->uid = $uid;
		$imuser->id = $uid;
		$imuser->nick = "nick".$id; //TODO: 
		$imuser->pic_url = WEBIM_PATH . "static/images/chat.png"; //TODO:
		$imuser->show = "available";
		$imuser->url = "#";
		$imuser->status = "";
		return $imuser;
	}
	
	/**
	 * 创建访客对象，可根据实际需求修改.
	 */
	private function newVisitor() {
		$imvisitor = (object)array();
		if ( isset($_COOKIE['_webim_visitor_id']) ) {
			$id = $_COOKIE['_webim_visitor_id'];
		} else {
			$id = substr(uniqid(), 6);
			setcookie('_webim_visitor_id', $id, time() + 3600 * 24 * 30, "/", "");
		}
		$imuser->uid = $id;
		$imuser->id = $id;
		$imuser->nick = "v".$id;
		$imuser->pic_url = WEBIM_PATH . "static/images/chat.png";
		$imuser->show = "available";
		$imuser->url = "#";
		return $imuser;
	}

}
