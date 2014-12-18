# WebIM-for-ThinkPHP

WebIM Application for ThinkPHP3.1

## 概述

[NexTalk](http://nextalk.im)为ThinkPHP框架提供的快速WebIM开发包。可为ThinkPHP框架开发站点或应用提供立即可用的站内即时消息。

WebIM开发包以代码接口方式，与站点的用户体系、好友关系、数据库集成。

WebIM的前端界面，集成后直接嵌入站点右下角。并支持在站点页面的任意位置，添加聊天按钮:

![PHP5 Screenshot](http://nextalk.im/static/img/screenshots/thinkphp.png)

## NexTalk

***NexTalk***是基于WEB标准协议设计的，主要应用于WEB站点的，简单开放的即时消息系统。可快速为社区微博、电子商务、企业应用集成即时消息服务。

NexTalk架构上分解为：***WebIM业务服务器*** + ***消息路由服务器*** 两个独立部分，遵循 ***Open Close***的架构设计原则。WebIM插件方式与第三方的站点或应用的用户体系开放集成，独立的消息服务器负责稳定的连接管理、消息路由和消息推送。

![NexTalk Architecture](http://nextalk.im/static/img/design/WebimForThinkPHP.png)

## 环境要求
```
PHP 5.3＋
cURL support
ThinkPHP 3.1+
```

## 项目演示

1. Webim目录防到Web服务器根路径；

2. 访问http://localhost/Webim/Index/

## 使用指南

1. Webim目录上传ThinkPHP项目根目录下;

2. 项目数据库导入Webim/Install.sql中的库表;

3. 配置Webim/Conf/config.php

4. 配置Webim/env.php的变量

5. 编码Webim/ThinkPHP_Plugin.php，集成ThinkPHP项目的用户、群组、通知与数据库.

6. 在需要显示WebIM的页面，footer插入js链接:

```javascript
<script type="text/javascript" src="Webim/Index/boot"></script>
```

## 开发指南

WebIM通过'webim/src/ThinkPHP_Plugin.php'的接口函数，与ThinkPHP项目的用户体系、好友关系等数据集成。

ThinkPHP_Plugin.php的集成方法列表:

方法 | 参数 | 返回 | 说明
---- | ---- | ---- | ---- 
user() | | 当前登陆用户(属性详见代码注释) | 初始化WebIM当前的用户对象,一般从SESSION和数据库读取
buddies($uid) | $uid | 好友对象列表 | 根据当前用户uid，读取用户的好友列表
buddiesByIds($uid, $ids) | $uid: 用户id, $ids: 好友id列表 | 好友对象列表 | 根据ids列表读取好友列表
rooms($uid) | $uid: 用户id | 群组对象列表 | 读取当前用户所属的群组，以支持群聊
roomsByIds($uid, $ids) | $uid: 用户id, $ids: 群组id列表 | 群组对象列表 | 根据群组id列表读取群组对象列表
members($room) | $room: 群组id | 群组成员对象列表 | 根据群组Id，读取群组成员对象
notifications($uid) | $uid: 用户id | 通知对象列表 | 读取当前用户的通知信息
menu($uid)  | $uid: 用户id | 菜单列表 | 读取当前用户的菜单项(显示在底栏)

## 配置参数

Webim/Conf/config.php中WebIM相关的配置参数，详细说明如下:

参数 | 类型  | 默认 | 说明
---- | ---- |---- | -----
isopen | bool |  true | 是否开启WebIM
server | string  | t.nextalk.im:8000 | WebIM消息服务器列表,逗号分割列表支持集群
domain | string  | localhost | WebIM插件与消息服务器通信的认证域名
apikey | string  | public | WebIM插件与消息服务器通信的认证APIKEY
theme | string  | base | WebIM插件界面Theme
local | string  | zh-CN | WebIM插件本地语言
emot | string  | default | WebIM插件表情库: emot, qq
opacity | inteter | 80 | WebIM插件工具条透明度
enable_room | bool | true | WebIM插件是否支持群组聊天
enable_discussion | bool | true  | WebIM插件支持临时讨论组
enable_noti | bool | true   | WebIM插件显示通知按钮
enable_shortcut | bool | false  |  WebIM插件支持快捷工具栏
enable_chatlink | bool | true  |  WebIM插件支持聊天按钮
enable_menu | bool | false  |  WebIM插件显示菜单栏
show_unavailable | bool | true  |  WebIM插件显示不在线好友
visitor | bool | true  |  WebIM插件支持访客
upload | bool | false  |  WebIM插件支持文件上传
censor | bool | false |  是否开启敏感词过滤
robot | bool | true  |  WebIM插件是否支持机器人

## 源码说明

WebIM开发包默认按ThinkPHP框架组织的代码结构，主要目录和文件说明:

目录或文件 | 说明
--------- | ----
Conf/config.php | 配置文件
Install.sql | MySQL数据库脚本
Lib/Action | WebIM的控制器
Lib/Model | WebIM的模型类，详见下面模型类说明
index.php | index入口文件
static | WebIM前端静态资源文件
vendor | 第三方PHP库

## 模型类说明

Webim/Lib/Model目录下模型类:

类 | 数据库表 | 是否必须 | 说明
---- | ---- | ---- | ---- | 
HistoryModel | webim_histories | 是  | 历史聊天记录存储和查询
SettingModel | webim_settings | 是 | 用户WebIM设置存储和访问
RoomModel | webim_rooms | 否 | 临时讨论组(注: 如不支持讨论组，无需实现)
MemberModel | webim_members | 临时讨论组成员(注: 如不支持讨论组，无需实现) 
BuddyModel | webim_buddies | 否 | 好友关系存储和查询(注: 如站点自身有好友个关系，无需实现)
VisitorModel | webim_visitors | 否 | 访客存储和查询(注: 如不支持访客，无需实现)

## 数据库表

WebIM自身需要创建几张数据库表，用于保存聊天记录、用户设置、临时讨论组、访客信息。

MySQL数据库脚本在'Webim/Install.sql'文件:

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

版本: 5.8 (2014/12/20)

