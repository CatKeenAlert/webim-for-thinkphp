<?php

return array(
	//DB配置
	'DB_TYPE'   => 'mysql', // 数据库类型
	'DB_HOST'   => '127.0.0.1', // 服务器地址
	'DB_NAME'   => 'webim', // 数据库名
	'DB_USER'   => 'root', // 用户名
	'DB_PWD'    => 'public', // 密码
	'DB_PORT'   => 3306, // 端口
	'DB_PREFIX' => 'webim_', // 数据库表前缀	

	//IM配置
	'IMC' => array(
		'version'	=> '5.5',		//IM版本, 当前为5.4
		'debug'     => true,		//DEBUG模式
		'isopen'	=> true,		//开启webim
		'domain' 	=> 'localhost',	//消息服务器通信域名
		'apikey'	=> 'public',	//消息服务器通信APIKEY
		'server'    => array('t.nextalk.im:8000'),//IM服务器
		'theme'		=> 'base',		//界面主题，根据webim/static/themes/目录内容选择
		'local'		=> 'zh-CN',		//本地语言，扩展请修改webim/static/i18n/内容
		'emot'		=> 'default',	//表情主题
		'opacity'	=> 80,			//TOOLBAR背景透明度设置
		'visitor'	=> true, 		//支持访客聊天(默认好友为站长),开启后通过im登录无效
		'upload'	=> false,	//是否支持文件(图片)上传
		'show_realname'		=> false,	//是否显示好友真实姓名
		'show_unavailable'	=> true, //支持显示不在线用户
		'enable_login'		=> false,	//允许未登录时显示IM，并可从im登录
		'enable_menu'		=> false,	//隐藏工具条
		'enable_room'		=> true,	//禁止群组聊天
        'discussion'        => true,   //临时讨论组
		'enable_noti'		=> true,	//禁止通知
		'enable_chatlink'	=> true,	//禁止页面名字旁边的聊天链接
		'enable_shortcut'	=> false,	//支持工具栏快捷方式
	),
);

?>
