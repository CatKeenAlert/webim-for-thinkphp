1. 数据库创建表

DROP TABLE IF EXISTS `webim_buddies`;
CREATE TABLE `webim_buddies` (
      `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
      `uid` varchar(60) DEFAULT NULL COMMENT '用户UID',
      `vid` varchar(60) DEFAULT NULL COMMENT '访客VID',
      `created` datetime DEFAULT NULL,
      PRIMARY KEY (`id`),
      UNIQUE KEY `webim_buddy_uid_vid` (`uid`, `vid`)
)ENGINE=MyISAM AUTO_INCREMENT=10000 DEFAULT CHARSET=utf8;


2. Webim/Lib/Action/IndexAction.class.php message()函数

收到访客与用户聊天时, 向webim_budies表插入一条记录:

insert into webim_buddies(uid, vid, created) values($uid, $vid, NOW());

3. Webim/ThinkPHP_Plugin.php buddies($uid) 函数

从webim_buddies表读取最近聊天的访客:

select vid from webim_buddies where uid = $uid order by created desc limit 10;
