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

6. 项目需要显示Webim的页面，footer嵌入:

```
<script type="text/javascript" src="Webim/Api/boot"></script>
```



ThinkIM.class.php
================

项目集成接口类, 用户参考示例代码，实现下述接口:

1. uid() 获取当前登录用户UID

2. newUser() 创建Webim需要的用户对象

3. newVisitor() 如支持访客使用Webim，创建Visitor对象

4. buddies() 读取当前用户的在线好友列表

5. buddiesByIds($ids , $strangers) 根据ids列表读取好友列表

6. rooms() 读取当前用户所属的群组，以支持群聊

7. roomsByIds() 根据id列表读取群组列表

8. notifications() 读取当前用户的通知信息

Lib/Action/ApiAction.class.php
==============================

浏览器AJAX接口

