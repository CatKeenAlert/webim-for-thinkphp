webim-for-thinkphp
==================

Webim application for ThinkPHP3.1

Usage
=====

1. Webim目录上传ThinkPHP项目根目录下;

2. 项目数据库创建Schema/webim.sql中的两张表;

3. 配置Webim/Conf/config.php

4. 配置index.php的WEBIM_PATH变量

5. 实现Webim/ThinkIM.php的项目集成接口，与项目用户、群组、通知集成.

ThinkIM.class.php
================

项目集成接口类, 用户参考示例代码，实现下述接口:

1. getUid() 获取当前登录用户UID

2. newUser() 创建Webim需要的用户对象

3. newVisitor() 如支持访客使用Webim，创建Visitor对象

4. getBuddies() 读取当前用户的在线好友列表

5. getBuddiesByIds($ids , $strangers) 根据ids列表读取好友列表

6. getRooms() 读取当前用户所属的群组，以支持群聊

7. getRoomsByIds() 根据id列表读取群组列表

8. getNotifications() 读取当前用户的通知信息

Lib/Action/ApiAction.class.php
==============================

浏览器AJAX接口

