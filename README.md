WebIM-for-ThinkPHP
==================

WebIM Application for ThinkPHP3.1

Usage
=====

1. Webim目录上传ThinkPHP项目根目录下;

2. 项目数据库导入Webim/Install.sql中的库表;

3. 配置Webim/Conf/config.php

4. 配置Webim/env.php的WEBIM_PATH变量

5. 实现Webim/ThinkWebIM.php的项目集成接口，与项目用户、群组、通知集成.

6. 项目需要显示Webim的页面，footer嵌入:

```javascript
<script type="text/javascript" src="Webim/Index/boot"></script>
```

ThinkWebIM.php
================

ThinkPHP集成接口类, 参考示例代码，实现下述接口:

1. uid() 获取当前登录用户UID, 一般从SESSION读取

2. user($uid) 初始化Webim当前的用户对象,一般从SESSION和数据库读取

3. visitor() 如支持访客模式，初始化访客(Visitor)对象

4. buddies($uid) 读取当前用户的在线好友列表

5. buddiesByIds($ids) 根据ids列表读取好友列表

6. rooms($uid) 读取当前用户所属的群组，以支持群聊

7. roomsByIds($ids) 根据id列表读取群组列表

8. members($room) 根据群组Id，读取群组成员信息

9. notifications($uid) 读取当前用户的通知信息

Lib/Action/IndexAction.class.php
==============================

WebIM API接口

Author
======

http://nextalk.im

ery.lee at gmail.com

nextalk at qq.com

