<?php

/**
 * 用户集成定制类
 */
class ThinkIM {

	/*
	 * 当前用户或者访客
	 */
	private $user = NULL;

	/*
	 * 是否管理员
	 */
	private $is_admin = false;

	/*
	 * 是否访客
	 */
	private $is_visitor = false;

	/*
	 * 是否登录
	 */
	private $is_login = false;

	/*
	 * 初始化当前用户信息
	 */
	function __construct() {
		global $IMC;
		if($this->uid()) {
			$this->setUser();			
			$this->is_login = true;
		} else if($imc['VISITOR']) {
			$this->setVisitor();
			$this->is_login = true;
			$this->is_visitor = true;	
		}
		$this->user = $imuser;
	}

	/*
	 * 接口函数: 集成项目的uid
	 */
	public function uid() {
		return $_SESSION['uid'];
	}

	public function user() {
		return $this->user;	
	}

	public function isAdmin() {
		return is_admin;
	}

	function isVisitor() {
		return $this->is_visitor;
	}

	public function logined() {
		return $this->is_login;	
	}

	/*
	 * 接口函数: 读取当前用户的好友在线好友列表
	 *
	 * Buddy对象属性:
	 *
	 * 	uid: 好友uid
	 * 	id:  同uid
	 *	nick: 好友昵称
	 *	pic_url: 头像图片
	 *	show: available | unavailable
	 *  url: 好友主页URL
	 *  status: 状态信息 
	 *  group: 所属组
	 *
	 */
	function buddies() {
		//根据当前用户id获取好友列表
		return array(clone $this->user);
	}

	/*
	 * 接口函数: 根据好友id列表、陌生人id列表读取用户, id列表为逗号分隔字符串
	 *
	 * 用户属性同上
	 */
	public function buddiesByIds($ids = "", $strangers = "") {
		//根据id列表获取好友列表
		return array();	
	}
	
	/*
	 * 接口函数：读取当前用户的Room列表
	 *
	 * Room对象属性:
	 *
	 *	id:		Room ID,
	 *	nick:	显示名称
	 *	url:	Room主页地址
	 *	pic_url: Room图片
	 *	status: Room状态信息
	 *	count:  0
	 *	all_count: 成员总计
	 *	blocked: true | false 是否block
	 */
	public function rooms() {
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

	/*
	 * 接口函数: 根据id列表读取rooms, id列表为逗号分隔字符串
	 *
	 * Room对象属性同上
	 */
	public function roomsByIds($ids = "") {
		return array();	
	}

	/*
	 * 接口函数: 当前用户通知列表
	 *
	 * Notification对象属性:
	 *
	 * 	text: 文本
	 * 	link: 链接
	 */	
	public function notifications() {
		return array();	
	}

	/*
	 * 接口函数: 初始化当前用户对象，与站点用户集成.
	 */
	private function setUser() {
		$uid = $_SESSION['uid'];
		//NOTICE: This user should be read from thinkphp database.
		$this->user = (object)array(
			'uid' => $uid,
			'id' => $uid,
			'nick' => "nick".$id, //TODO: 
			'pic_url' => WEBIM_PATH . "static/images/chat.png", //TODO:
			'show' => "available",
			'url' => "#",
			'status' => "",
		);
	}
	
	/*
	 * 接口函数: 创建访客对象，可根据实际需求修改.
	 */
	private function setVisitor() {
		if ( isset($_COOKIE['_webim_visitor_id']) ) {
			$id = $_COOKIE['_webim_visitor_id'];
		} else {
			$id = substr(uniqid(), 6);
			setcookie('_webim_visitor_id', $id, time() + 3600 * 24 * 30, "/", "");
		}
		$this->user = (object)array(
			'uid' => 'vid:'.$id,
			'id' => 'vid:'.$id,
			'nick' => "v".$id,
			'pic_url' => WEBIM_PATH  . "/static/images/chat.png",
			'show' => "available",
			'url' => "#",
			'status' => '网站访客',
		);
	}

}
