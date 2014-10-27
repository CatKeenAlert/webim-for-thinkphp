# WebIM-for-ThinkPHP

WebIM Application for ThinkPHP3.1


## 简介

[NexTalk](http://nextalk.im)为PHP5项目提供的快速WebIM开发包。WebIM集成包以代码接口方式，与站点的用户体系、好友关系、数据库集成。

WebIM的前端界面，集成后直接嵌入站点右下角。并支持在站点页面的任意位置，添加聊天按钮:

![PHP5 Screenshot](http://nextalk.im/static/img/screenshots/thinkphp.png)

## NexTalk

***NexTalk***是基于WEB标准协议设计的，主要应用于WEB站点的，简单开放的即时消息系统。可快速为社区微博、电子商务、企业应用集成即时消息服务。

NexTalk架构上分解为：***WebIM业务服务器*** + ***消息路由服务器*** 两个独立部分，遵循 ***Open Close***的架构设计原则。WebIM插件方式与第三方的站点或应用的用户体系开放集成，独立的消息服务器负责稳定的连接管理、消息路由和消息推送。

![NexTalk Architecture](http://nextalk.im/static/img/design/WebimForThinkPHP.png)

## 环境要求
```
PHP > 5.3.10
PDO support
cURL support
ThinkPHP 3.1+
```

## 项目演示

1. Webim目录上传WEB服务器根目录

2. 访问http://localhost/Webim/Index/


## 使用指南


1. Webim目录上传ThinkPHP项目根目录下;

2. 项目数据库导入Webim/Install.sql中的库表;

3. 配置Webim/Conf/config.php

4. 配置Webim/env.php的变量

5. 实现Webim/ThinkPHP_Plugin.php的集成接口，与项目用户、群组、通知集成.

6. 项目需要显示WebIM的页面，footer嵌入:

```javascript
<script type="text/javascript" src="Webim/Index/boot"></script>
```


## 开发指南


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


WebIM API接口

## 配置参数

## 源码说明


Lib/Action/IndexAction.class.php


## 数据库表

WebIM自身需要创建几张数据库表，用于保存聊天记录、用户设置、临时讨论组、访客信息。MySQL数据库脚本在'webim/install.sql'文件:

数据库表 | 说明
--------- | ------
webim_histories |  历史聊天记录表
webim_settings | 用户个人WebIM设置表
webim_buddies | 好友关系表(注: 如果项目没有自身的好友关系，可以通过该表存储)
webim_visitors | 访客信息表
webim_rooms | 临时讨论组表(注: Plugin.php是集成项目的固定群组，webim_rooms表是存储WebIM自己的临时讨论组
webim_members | 临时讨论组成员表
webim_blocked | 群组是否block

## 开发者

公司: [NexTalk.IM](http://nextalk.im)

作者: [Feng Lee](mailto:feng.lee@nextalk.im) 

版本: 5.7.1 (2014/10/15)

