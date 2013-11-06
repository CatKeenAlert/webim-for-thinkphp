<?php

return array(
	//DB配置
	'DB_TYPE'   => 'mysql', // 数据库类型
	'DB_HOST'   => '127.0.0.1', // 服务器地址
	'DB_NAME'   => 'thinkim', // 数据库名
	'DB_USER'   => 'root', // 用户名
	'DB_PWD'    => 'public', // 密码
	'DB_PORT'   => 3306, // 端口
	'DB_PREFIX' => 'webim_', // 数据库表前缀	

	//IM配置
	'IMC' => array(
		'VERSION'	=> '5.0',		//IM版本, 当前为5.0
		'ENABLE'	=> true,		//开启webim
		'DOMAIN' 	=> 'localhost',	//消息服务器通信域名
		'APIKEY'	=> 'public',	//消息服务器通信APIKEY
		'HOST'		=> 'nextalk.im',//im服务器
		'PORT'		=> 8000,		//服务端接口端口
		'THEME'		=> 'base',		//界面主题，根据webim/static/themes/目录内容选择
		'LOCAL'		=> 'zh-CN',		//本地语言，扩展请修改webim/static/i18n/内容
		'EMOT'		=> 'default',	//表情主题
		'OPACITY'	=> 80,			//TOOLBAR背景透明度设置
		'VISITOR'	=> 'true', 		//支持访客聊天(默认好友为站长),开启后通过im登录无效
		'SHOW_REALNAME'		=> 'false',	//是否显示好友真实姓名
		'SHOW_UNAVAILABLE'	=> 'false', //支持显示不在线用户
		'ENABLE_UPLOAD'		=> 'false',	//是否支持文件(图片)上传
		'ENABLE_LOGIN'		=> 'false',	//允许未登录时显示IM，并可从im登录
		'ENABLE_MENU'		=> 'false',		//隐藏工具条
		'ENABLE_ROOM'		=> 'true',		//禁止群组聊天
		'ENABLE_NOTI'		=> 'true',		//禁止通知
		'ENABLE_CHATLINK'	=> 'true',	//禁止页面名字旁边的聊天链接
		'ENABLE_SHORTCUT'	=> 'false',	//支持工具栏快捷方式
	),
);

?>
