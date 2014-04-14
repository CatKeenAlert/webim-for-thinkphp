//custom
(function(webim) {
	var path = _IMC.path;
	webim.extend(webim.setting.defaults.data, _IMC.setting);
    var cookie_key = "_webim_cookie_";
	if( _IMC.is_visitor ) { cookie_key = "_webim_v_cookie_"; }
    if( _IMC.user != "" ) { cookie_key = cookie_key + _IMC.user.id; }

	webim.route( {
		online: path + "Api/online",
		offline: path + "Api/offline",
		deactivate: path + "Api/refresh",
		message: path + "Api/message",
		presence: path + "Api/presence",
		status: path + "Api/status",
		setting: path + "Api/setting",
		history: path + "Api/history",
		clear: path + "Api/clear_history",
		download: path + "Api/download_history",
		members: path + "Api/members",
		join: path + "Api/join",
		leave: path + "Api/leave",
		buddies: path + "Api/buddies",
		//upload: path + "static/images/upload.php",
		notifications: path + "Api/notifications"
	} );

	webim.ui.emot.init({"dir": path + "/static/images/emot/default"});
	var soundUrls = {
		lib: path + "/static/assets/sound.swf",
		msg: path + "/static/assets/sound/msg.mp3"
	};
	var ui = new webim.ui(document.body, {
		imOptions: {
			jsonp: _IMC.jsonp
		},
		soundUrls: soundUrls,
		buddyChatOptions: {
            downloadHistory: !_IMC.is_visitor,
			//simple: _IMC.is_visitor,
			upload: _IMC.upload && !_IMC.is_visitor
		},
		roomChatOptions: {
            downloadHistory: !_IMC.is_visitor,
			upload: _IMC.upload
		}
	}), im = ui.im;
    //全局化
    window.webimUI = ui;

	if( _IMC.user ) im.setUser( _IMC.user );
	if( _IMC.menu ) ui.addApp("menu", { "data": _IMC.menu } );
	if( _IMC.enable_shortcut ) ui.layout.addShortcut( _IMC.menu );

	ui.addApp("buddy", {
		showUnavailable: _IMC.show_unavailable,
		is_login: _IMC['is_login'],
		disable_login: true,
		collapse: false,
		//disable_user: _IMC.is_visitor,
        //simple: _IMC.is_visitor,
        online_group: false,
		loginOptions: _IMC['login_options']
	} );
    if(!_IMC.is_visitor) {
        if(_IMC.enable_room )ui.addApp("room", { discussion: true });
        if(_IMC.enable_noti )ui.addApp("notification");
    }
    if(_IMC.enable_chatlink) ui.addApp("chatbtn");
    ui.addApp("setting", {"data": webim.setting.defaults.data});
	ui.render();
	_IMC['is_login'] && im.autoOnline() && im.online();
})(webim);

//window.webimUI.layout.addChat('buddy', '20');


