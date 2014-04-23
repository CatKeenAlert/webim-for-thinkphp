WebIM-for-ThinkPHP
==================

WebIM Application for ThinkPHP3.1

Usage
=====

1. Webim目录上传ThinkPHP项目根目录下;

2. 项目数据库导入Webim/Install.sql中的库表;

3. 配置Webim/Conf/config.php

4. 配置Webim/env.php的变量

5. 实现Webim/ThinkPHP_Plugin.php的集成接口，与项目用户、群组、通知集成.

6. 项目需要显示WebIM的页面，footer嵌入:

```javascript
<script type="text/javascript" src="Webim/Index/boot"></script>
```

ThinkPHP_Plugin.php
================

ThinkPHP集成接口类, 参考示例代码，实现下述接口:

1. user() 初始化WebIM当前的用户对象,一般从SESSION和数据库读取

2. buddies($uid) 读取当前用户的在线好友列表

3. buddiesByIds($uid, $ids) 根据ids列表读取好友列表

4. rooms($uid) 读取当前用户所属的群组，以支持群聊

5. roomsByIds($uid, $ids) 根据id列表读取群组列表

6. members($room) 根据群组Id，读取群组成员信息

7. notifications($uid) 读取当前用户的通知信息

8. menu($uid) 读取当前用户的菜单


Lib/Action/IndexAction.class.php
==============================

WebIM API接口


Install.sql
==============================


webim_settings
--------------

用户设置表，保存用户界面个性化设置


webim_histories
----------------

历史消息表，保存聊天历史消息


webim_rooms
----------------

临时讨论组表


webim_members
----------------

临时讨论组成员表

webim_visitors
--------------

访客表

Author
======

http://nextalk.im

ery.lee at gmail.com

nextalk at qq.com

