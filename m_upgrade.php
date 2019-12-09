<?php 
pdo_query("CREATE TABLE IF NOT EXISTS `ims_wx_school_address` (
`id` int(10) NOT NULL  AUTO_INCREMENT,
`weid` int(11) NOT NULL,
`schoolid` int(11) NOT NULL,
`openid` varchar(30) NOT NULL,
`name` varchar(50) NOT NULL,
`phone` varchar(30) NOT NULL,
`province` varchar(40) NOT NULL,
`city` varchar(40) NOT NULL,
`county` varchar(40) NOT NULL,
`address` varchar(300) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_allcamera` (
`id` int(10) NOT NULL  AUTO_INCREMENT,
`weid` int(10) NOT NULL,
`schoolid` int(10) NOT NULL,
`kcid` int(10) NOT NULL,
`name` varchar(50) NOT NULL   COMMENT '画面名称',
`videopic` varchar(1000) NOT NULL   COMMENT '监控地址',
`videourl` varchar(1000) NOT NULL   COMMENT '监控地址',
`starttime1` varchar(50) NOT NULL,
`endtime1` varchar(50) NOT NULL,
`createtime` int(10) NOT NULL,
`conet` text()    COMMENT '说明',
`allowpy` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1'  COMMENT '1允许2拒绝',
`videotype` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1'  COMMENT '1公共2指定班级',
`bj_id` text()    COMMENT '关联班级组',
`type` tinyint(1) NOT NULL   COMMENT '1监控2课程直播',
`click` int(10) NOT NULL   COMMENT '查看量',
`ssort` int(10) NOT NULL   COMMENT '排序',
`is_pay` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '2'  COMMENT '单独付费与否',
`price_one` float() NOT NULL,
`price_one_cun` float() NOT NULL,
`price_all` float() NOT NULL,
`price_all_cun` float() NOT NULL,
`days` int(11)  DEFAULT NULL DEFAULT '10',
`is_try` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1'  COMMENT '是否允许试看',
`try_time` int(11)  DEFAULT NULL DEFAULT '30'  COMMENT '试看时间',
`payweid` int(11)    COMMENT '收款公众号',
`starttime2` varchar(50) NOT NULL,
`starttime3` varchar(50) NOT NULL,
`endtime2` varchar(50) NOT NULL,
`endtime3` varchar(50) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_ans_remark` (
`id` int(11) NOT NULL  AUTO_INCREMENT,
`weid` int(11) NOT NULL,
`schoolid` int(11) NOT NULL,
`userid` int(11) NOT NULL,
`tid` int(11) NOT NULL,
`tname` varchar(30) NOT NULL,
`sid` int(11) NOT NULL,
`zyid` int(11) NOT NULL,
`tmid` int(11) NOT NULL,
`type` int(3) NOT NULL   COMMENT '1是电脑创建的作业2是手机创建的作业',
`content` varchar(500) NOT NULL,
`createtime` int(11) NOT NULL,
`audio` varchar(1000) NOT NULL,
`audiotime` varchar(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_answers` (
`id` int(10) NOT NULL  AUTO_INCREMENT,
`weid` int(10) NOT NULL,
`schoolid` int(10) NOT NULL,
`zyid` int(10) NOT NULL   COMMENT '问题id',
`sid` int(10) NOT NULL,
`tid` int(11) NOT NULL,
`userid` int(11) NOT NULL,
`tmid` int(10) NOT NULL,
`type` tinyint(1) NOT NULL   COMMENT '1回答2单选3多选4图片5语音6视频',
`MyAnswer` varchar(2000) NOT NULL,
`createtime` varchar(13) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_apartment` (
`id` int(11) NOT NULL  AUTO_INCREMENT,
`schoolid` int(11) NOT NULL,
`weid` int(11) NOT NULL,
`name` varchar(50) NOT NULL,
`ssort` int(11) NOT NULL,
`tid` int(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_aproom` (
`id` int(11) NOT NULL  AUTO_INCREMENT,
`schoolid` int(11) NOT NULL,
`weid` int(11) NOT NULL,
`name` varchar(50) NOT NULL,
`apid` int(11) NOT NULL   COMMENT '楼栋id',
`noon_start` varchar(20) NOT NULL,
`noon_end` varchar(20) NOT NULL,
`night_start` varchar(20) NOT NULL,
`night_end` varchar(20) NOT NULL,
`ssort` int(11) NOT NULL,
`floornum` int(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_area` (
`id` int(10) NOT NULL  AUTO_INCREMENT,
`weid` int(10) NOT NULL   COMMENT '所属帐号',
`name` varchar(50) NOT NULL   COMMENT '区域名称',
`parentid` int(10) NOT NULL   COMMENT '上级分类ID,0为第一级',
`ssort` tinyint(3) NOT NULL   COMMENT '排序',
`type` char(20) NOT NULL,
`status` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1'  COMMENT '显示状态',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_banners` (
`id` int(11) NOT NULL  AUTO_INCREMENT,
`weid` int(11),
`uniacid` int(10) NOT NULL,
`schoolid` int(11),
`bannername` varchar(50),
`link` varchar(255),
`thumb` varchar(5000) NOT NULL,
`begintime` int(11) NOT NULL,
`endtime` int(11) NOT NULL,
`displayorder` int(11),
`enabled` int(11),
`leixing` int(1) NOT NULL   COMMENT '0学校,1平台',
`arr` text()    COMMENT '列表信息',
`click` varchar(1000)    COMMENT '点击量',
`place` tinyint(1) NOT NULL   COMMENT '位置',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_bjq` (
`id` int(10) NOT NULL  AUTO_INCREMENT,
`weid` int(10) NOT NULL,
`schoolid` int(10) NOT NULL   COMMENT '学校ID',
`content` text() NOT NULL   COMMENT '详细内容或评价',
`uid` int(10) NOT NULL   COMMENT '发布者UID',
`wx` varchar(30) NOT NULL,
`bj_id1` int(10) NOT NULL   COMMENT '班级ID1',
`bj_id2` int(10) NOT NULL   COMMENT '班级ID2',
`bj_id3` int(10) NOT NULL   COMMENT '班级ID3',
`sherid` int(10) NOT NULL   COMMENT '所属图文id',
`shername` varchar(50)    COMMENT '分享者名字',
`openid` varchar(30) NOT NULL   COMMENT '帖子所属openid',
`isopen` tinyint(1) NOT NULL   COMMENT '是否显示',
`type` tinyint(1) NOT NULL   COMMENT '类型0为班级圈1为评论',
`createtime` int(10) NOT NULL   COMMENT '创建时间',
`msgtype` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1'  COMMENT '1文字图片2语音3视频',
`userid` int(10) NOT NULL   COMMENT '发布者用户ID',
`video` varchar(1000),
`videoimg` varchar(1000),
`plid` int(10) NOT NULL,
`is_private` varchar(3) NOT NULL DEFAULT NULL DEFAULT 'N'  COMMENT '禁止评论',
`audio` varchar(1000)    COMMENT '音频地址',
`audiotime` int(10) NOT NULL   COMMENT '音频时间',
`link` varchar(1000)    COMMENT '外链地址',
`linkdesc` varchar(200)    COMMENT '外链标题',
`hftoname` varchar(100),
`kc_id` int(11) NOT NULL,
`ali_vod_id` varchar(100),
`is_all` tinyint(3)    COMMENT '是否全校可见',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_booksborrow` (
`id` int(11) NOT NULL  AUTO_INCREMENT,
`schoolid` int(11) NOT NULL,
`weid` int(11) NOT NULL,
`sid` int(11) NOT NULL,
`bookname` varchar(200) NOT NULL,
`worth` varchar(30) NOT NULL,
`borrowtime` int(11) NOT NULL,
`status` int(3) NOT NULL,
`returntime` int(11) NOT NULL,
`createtime` int(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_busgps` (
`id` int(10) NOT NULL  AUTO_INCREMENT,
`weid` int(10) NOT NULL,
`schoolid` int(10) NOT NULL,
`macid` varchar(200) NOT NULL,
`lat` decimal(18,10) NOT NULL DEFAULT NULL DEFAULT '0.0000000000'  COMMENT '经度',
`lon` decimal(18,10) NOT NULL DEFAULT NULL DEFAULT '0.0000000000'  COMMENT '纬度',
`type` tinyint(1) NOT NULL,
`createtime` int(10) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_buzhulog` (
`id` int(11) NOT NULL  AUTO_INCREMENT,
`schoolid` int(11) NOT NULL,
`weid` int(11) NOT NULL,
`sid` int(11) NOT NULL,
`start_yue` float(8,2) NOT NULL,
`now_yue` float(8,2) NOT NULL,
`starttime` int(11) NOT NULL,
`endtime` int(11) NOT NULL,
`createtime` int(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_camerapl` (
`id` int(10) NOT NULL  AUTO_INCREMENT,
`weid` int(10) NOT NULL,
`schoolid` int(10) NOT NULL,
`carmeraid` int(10) NOT NULL   COMMENT '画面ID',
`userid` int(10) NOT NULL   COMMENT '用户ID',
`conet` text()    COMMENT '内容',
`bj_id` int(10) NOT NULL   COMMENT '班级ID',
`type` tinyint(1) NOT NULL   COMMENT '1点赞2评论',
`createtime` int(10) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_camerask` (
`id` int(10) NOT NULL  AUTO_INCREMENT,
`weid` int(10) NOT NULL,
`schoolid` int(10) NOT NULL,
`carmeraid` int(10) NOT NULL   COMMENT '画面ID',
`userid` int(10) NOT NULL   COMMENT '用户ID',
`type` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1'  COMMENT '1视频试看',
`createtime` int(10) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_checkdatedetail` (
`id` int(11) NOT NULL  AUTO_INCREMENT,
`schoolid` int(11) NOT NULL,
`weid` int(11) NOT NULL,
`year` int(10) NOT NULL,
`sum_start` varchar(20) NOT NULL,
`sum_end` varchar(20) NOT NULL,
`win_start` varchar(20) NOT NULL,
`win_end` varchar(20) NOT NULL,
`holiday` varchar(1000) NOT NULL,
`checkdatesetid` int(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_checkdateset` (
`id` int(11) NOT NULL  AUTO_INCREMENT,
`schoolid` int(10) NOT NULL,
`weid` int(10) NOT NULL,
`name` varchar(500) NOT NULL,
`friday` tinyint(3) NOT NULL,
`saturday` tinyint(3) NOT NULL,
`sunday` tinyint(3) NOT NULL,
`holiday` varchar(1000) NOT NULL,
`bj_id` varchar(1000) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_checklog` (
`id` int(10) NOT NULL  AUTO_INCREMENT,
`weid` int(10) NOT NULL,
`schoolid` int(10) NOT NULL,
`macid` int(10) NOT NULL,
`cardid` varchar(200) NOT NULL   COMMENT '卡号',
`sid` int(10) NOT NULL,
`tid` int(10) NOT NULL,
`bj_id` int(10) NOT NULL,
`lat` decimal(18,10) NOT NULL DEFAULT NULL DEFAULT '0.0000000000'  COMMENT '经度',
`lon` decimal(18,10) NOT NULL DEFAULT NULL DEFAULT '0.0000000000'  COMMENT '纬度',
`temperature` varchar(10),
`pic` varchar(255)    COMMENT '图片',
`pic2` varchar(255)    COMMENT '图片2',
`type` varchar(50)    COMMENT '进校类型',
`leixing` tinyint(1) NOT NULL   COMMENT '1进校2离校3迟到4早退',
`isread` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1'  COMMENT '1已读2未读',
`pard` tinyint(2) NOT NULL   COMMENT '1本人2母亲3父亲4爷爷5奶奶6外公7外婆8叔叔9阿姨10其他11老师',
`qdtid` int(11) NOT NULL   COMMENT '代签老师ID',
`checktype` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1'  COMMENT '1刷卡2微信',
`isconfirm` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1'  COMMENT '1确认2拒绝',
`createtime` int(10) NOT NULL,
`sc_ap` tinyint(3) NOT NULL   COMMENT '0普通考勤1寝室考勤',
`apid` int(11) NOT NULL,
`roomid` int(11) NOT NULL,
`ap_type` tinyint(3) NOT NULL   COMMENT '1进寝2离寝',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_checkmac` (
`id` int(10) NOT NULL  AUTO_INCREMENT,
`weid` int(10) NOT NULL,
`schoolid` int(10) NOT NULL,
`macname` varchar(50) NOT NULL,
`name` varchar(50) NOT NULL,
`macid` varchar(200) NOT NULL   COMMENT '设备编号',
`banner` varchar(2000),
`macset` varchar(2000),
`is_on` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1'  COMMENT '1启用2不启用',
`type` tinyint(1) NOT NULL   COMMENT '1进校2离校',
`createtime` int(10) NOT NULL,
`twmac` varchar(200) NOT NULL DEFAULT NULL DEFAULT '-1',
`cardtype` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '2'  COMMENT '1IC2ID',
`is_bobao` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1'  COMMENT '播报',
`is_master` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1'  COMMENT '是否全校',
`bj_id` int(10)    COMMENT '绑定班级ID',
`js_id` int(10)    COMMENT '教室ID',
`areaid` int(10) NOT NULL,
`model_type` tinyint(1) NOT NULL,
`qh_id` int(10) NOT NULL,
`exam_plan` varchar(1000) NOT NULL,
`exam_room_name` varchar(200) NOT NULL,
`cityname` varchar(50) NOT NULL,
`apid` int(11) NOT NULL,
`stu1` int(10),
`stu2` int(10),
`stu3` int(10),
`lastedittime` int(11)    COMMENT '最近一次修改时间',
`is_heartbeat` tinyint(3)    COMMENT '是否接收心跳任务',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_checkmac_remote` (
`id` int(11) NOT NULL  AUTO_INCREMENT,
`weid` int(11) NOT NULL,
`schoolid` int(11) NOT NULL,
`pid` varchar(100) NOT NULL,
`deviceId` varchar(100) NOT NULL,
`passType` int(11) NOT NULL,
`passDeviceId` varchar(255) NOT NULL,
`cameras` varchar(255) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_checktimeset` (
`id` int(11) NOT NULL  AUTO_INCREMENT,
`schoolid` int(11) NOT NULL,
`weid` int(11) NOT NULL,
`start` varchar(20) NOT NULL,
`end` varchar(20) NOT NULL,
`type` tinyint(3) NOT NULL   COMMENT '1工作日2周五3周六4周日5特殊上6特殊休',
`year` int(11) NOT NULL,
`date` varchar(20) NOT NULL,
`checkdatesetid` int(11) NOT NULL,
`out_in` tinyint(1),
`s_type` tinyint(1),
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_chongzhi` (
`id` int(11) NOT NULL  AUTO_INCREMENT,
`weid` int(11) NOT NULL,
`schoolid` int(11) NOT NULL,
`cost` float() NOT NULL,
`chongzhi` int(11) NOT NULL,
`createtime` int(11) NOT NULL,
`ssort` int(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_classify` (
`sid` int(11) NOT NULL  AUTO_INCREMENT,
`schoolid` int(10) NOT NULL   COMMENT '分校id',
`sname` varchar(50) NOT NULL,
`pname` varchar(50) NOT NULL,
`ssort` int(5) NOT NULL,
`weid` int(10) NOT NULL,
`type` char(20) NOT NULL,
`carmeraid` text()    COMMENT '画面ID组',
`erwei` varchar(200) NOT NULL   COMMENT '群二维码',
`qun` varchar(200) NOT NULL   COMMENT 'QQ群链接',
`video` varchar(1000) NOT NULL   COMMENT '教室监控地址',
`video1` varchar(1000) NOT NULL   COMMENT '教室监控地址1',
`videostart` varchar(50) NOT NULL,
`videoend` varchar(50) NOT NULL,
`allowpy` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1'  COMMENT '1允许2拒绝',
`cost` varchar(11) NOT NULL   COMMENT '报名费用',
`videoclick` varchar(11) NOT NULL   COMMENT '视频点击量',
`tid` int(11) NOT NULL   COMMENT '班级主任userid',
`parentid` int(10) NOT NULL   COMMENT '上级分类ID,0为第一级',
`icon` varchar(500)    COMMENT '图标',
`start` varchar(1000)    COMMENT '班级之星',
`star` varchar(1000)    COMMENT '班级之星',
`qh_bjlist` varchar(1000)    COMMENT '期号对应班级',
`qhtype` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1',
`is_bjzx` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1'  COMMENT '班级之星',
`sd_start` int(11) NOT NULL,
`sd_end` int(11) NOT NULL,
`js_id` int(10) NOT NULL,
`is_over` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1',
`datesetid` int(11) NOT NULL,
`class_device` varchar(100) NOT NULL   COMMENT '分班播报id',
`is_print` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '2'  COMMENT '是否启用打印机',
`printarr` varchar(100) NOT NULL   COMMENT '打印机',
`tidarr` varchar(500) NOT NULL,
`fzid` int(11) NOT NULL,
`is_review` tinyint(3) NOT NULL,
`addedinfo` text() NOT NULL   COMMENT '附加设置信息-以后所有不索引的附加信息都在这里，不用再加字段',
`lastedittime` int(11)    COMMENT '最近一次修改时间',
`checksendset` text()    COMMENT '考勤记录推送对象',
PRIMARY KEY (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_cookbook` (
`id` int(11) NOT NULL  AUTO_INCREMENT,
`schoolid` int(10) NOT NULL   COMMENT '分校id',
`weid` int(10) NOT NULL,
`keyword` varchar(50) NOT NULL,
`title` varchar(50) NOT NULL,
`begintime` int(11) NOT NULL,
`endtime` int(11) NOT NULL,
`monday` text() NOT NULL,
`tuesday` text() NOT NULL,
`wednesday` text() NOT NULL,
`thursday` text() NOT NULL,
`friday` text() NOT NULL,
`saturday` text() NOT NULL,
`sunday` text() NOT NULL,
`ishow` int(1) NOT NULL DEFAULT NULL DEFAULT '1'  COMMENT '1:显示,2隐藏,默认1',
`sort` int(11) NOT NULL DEFAULT NULL DEFAULT '1',
`type` varchar(15) NOT NULL,
`headpic` varchar(200) NOT NULL,
`infos` varchar(500) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_cost` (
`id` int(10) NOT NULL  AUTO_INCREMENT,
`weid` int(10) NOT NULL,
`schoolid` int(10) NOT NULL,
`cost` decimal(18,2) NOT NULL DEFAULT NULL DEFAULT '0.00',
`bj_id` text()    COMMENT '关联班级组',
`name` varchar(100) NOT NULL,
`icon` varchar(255) NOT NULL,
`description` text() NOT NULL   COMMENT '缴费说明',
`about` int(10) NOT NULL,
`displayorder` tinyint(3) NOT NULL,
`is_sys` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1'  COMMENT '1关联缴费，2不关联',
`is_time` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1'  COMMENT '1有时间限制，2不限制',
`is_on` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1'  COMMENT '1启用，2不启用',
`createtime` int(10) NOT NULL,
`starttime` int(10) NOT NULL,
`endtime` int(10) NOT NULL,
`dataline` int(10) NOT NULL,
`payweid` int(10) NOT NULL   COMMENT '支付公众号',
`is_print` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '2'  COMMENT '是否启用打印机',
`printarr` varchar(100) NOT NULL   COMMENT '打印机',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_courseTable` (
`id` int(11) NOT NULL  AUTO_INCREMENT,
`schoolid` int(10) NOT NULL   COMMENT '分校id',
`weid` int(10) NOT NULL,
`title` varchar(50) NOT NULL,
`ishow` int(1) NOT NULL DEFAULT NULL DEFAULT '1'  COMMENT '1:显示,2隐藏,默认1',
`sort` int(11) NOT NULL DEFAULT NULL DEFAULT '1',
`type` varchar(15) NOT NULL,
`headpic` varchar(200) NOT NULL,
`infos` varchar(500) NOT NULL,
`xq_id` int(11) NOT NULL   COMMENT '学期id',
`bj_id` int(11) NOT NULL   COMMENT '班级id',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_coursebuy` (
`id` int(11) NOT NULL  AUTO_INCREMENT,
`weid` int(11) NOT NULL,
`schoolid` int(11) NOT NULL,
`userid` int(11) NOT NULL,
`sid` int(11) NOT NULL,
`kcid` int(11) NOT NULL,
`ksnum` int(11) NOT NULL,
`createtime` int(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_courseorder` (
`id` int(11) NOT NULL  AUTO_INCREMENT,
`weid` int(11) NOT NULL,
`schoolid` int(11) NOT NULL,
`kcid` int(11) NOT NULL,
`name` varchar(50) NOT NULL,
`tel` varchar(30) NOT NULL,
`beizhu` varchar(200) NOT NULL,
`tid` int(11) NOT NULL,
`createtime` int(11) NOT NULL,
`type` int(3) NOT NULL   COMMENT '类型，0为预约',
`totid` int(11) NOT NULL,
`fromuserid` int(11) NOT NULL,
`huifu` varchar(500) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_coursetable` (
`id` int(11) NOT NULL  AUTO_INCREMENT,
`schoolid` int(10) NOT NULL   COMMENT '分校id',
`weid` int(10) NOT NULL,
`title` varchar(50) NOT NULL,
`ishow` int(1) NOT NULL DEFAULT NULL DEFAULT '1'  COMMENT '1:显示,2隐藏,默认1',
`sort` int(11) NOT NULL DEFAULT NULL DEFAULT '1',
`type` varchar(15) NOT NULL,
`headpic` varchar(200) NOT NULL,
`infos` varchar(500) NOT NULL,
`xq_id` int(11) NOT NULL   COMMENT '学期id',
`bj_id` int(11) NOT NULL   COMMENT '班级id',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_cyybeizhu_teacher` (
`id` int(11) NOT NULL  AUTO_INCREMENT,
`weid` int(11) NOT NULL,
`schoolid` int(11) NOT NULL,
`tid` int(11) NOT NULL,
`beizhu` varchar(200) NOT NULL,
`cyyid` int(11) NOT NULL,
`createtime` int(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_dianzan` (
`id` int(10) NOT NULL  AUTO_INCREMENT,
`weid` int(10) NOT NULL,
`schoolid` int(10) NOT NULL   COMMENT '学校ID',
`uid` int(10) NOT NULL   COMMENT '发布者UID',
`sherid` int(10) NOT NULL   COMMENT '所属图文id',
`zname` varchar(50)    COMMENT '点赞人名字',
`order` int(10) NOT NULL   COMMENT '排序',
`createtime` int(10) NOT NULL   COMMENT '创建时间',
`hmxianlaohu` varchar(30) NOT NULL   COMMENT '图片路径',
`userid` int(10)    COMMENT 'userid',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_email` (
`id` int(10) NOT NULL  AUTO_INCREMENT,
`weid` int(10) NOT NULL,
`schoolid` int(10) NOT NULL,
`sid` int(10) NOT NULL,
`userid` int(10) NOT NULL,
`bj_id` int(10) NOT NULL,
`pard` tinyint(1) NOT NULL   COMMENT '1本人2母亲3父亲4爷爷5奶奶6外公7外婆8叔叔9阿姨10其他',
`suggesd` varchar(1000),
`emailid` int(10) NOT NULL,
`isread` tinyint(1) NOT NULL,
`is_how` tinyint(1) NOT NULL,
`ssort` int(10) NOT NULL   COMMENT '排序',
`createtime` int(10) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_fans_group` (
`id` int(10) NOT NULL  AUTO_INCREMENT,
`weid` int(10) NOT NULL,
`schoolid` int(10) NOT NULL,
`count` int(10) NOT NULL,
`group_id` int(10) NOT NULL,
`name` varchar(50) NOT NULL,
`group_desc` varchar(50) NOT NULL,
`ssort` int(10) NOT NULL   COMMENT '排序',
`type` int(1) NOT NULL   COMMENT '二维码状态',
`createtime` int(10) NOT NULL   COMMENT '生成时间',
`is_zhu` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '2'  COMMENT '是否本校主二维码',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_fzqx` (
`id` int(11) NOT NULL  AUTO_INCREMENT,
`schoolid` int(11) NOT NULL,
`weid` int(11) NOT NULL,
`fzid` int(11) NOT NULL,
`qxid` int(11) NOT NULL,
`type` int(3) NOT NULL   COMMENT '1后台2前端',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_gkkpj` (
`id` int(11) NOT NULL  AUTO_INCREMENT,
`weid` int(11) NOT NULL,
`schoolid` int(11) NOT NULL,
`gkkid` int(11) NOT NULL,
`tid` int(11) NOT NULL,
`userid` int(11) NOT NULL,
`iconid` int(11) NOT NULL,
`iconlevel` int(11) NOT NULL,
`content` varchar(1000) NOT NULL,
`torjz` int(1) NOT NULL   COMMENT '来自老师2还是家长1',
`createtime` int(11) NOT NULL,
`type` int(1) NOT NULL   COMMENT '评语1还是等级2',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_gkkpjbz` (
`id` int(11) NOT NULL  AUTO_INCREMENT,
`weid` int(11) NOT NULL,
`schoolid` int(1) NOT NULL,
`title` varchar(50) NOT NULL,
`ssort` int(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_gkkpjk` (
`id` int(10) NOT NULL  AUTO_INCREMENT,
`weid` int(10) NOT NULL,
`schoolid` int(10) NOT NULL,
`bzid` int(11) NOT NULL,
`title` varchar(300) NOT NULL,
`icon1title` varchar(10) NOT NULL,
`icon2title` varchar(10) NOT NULL,
`icon3title` varchar(10) NOT NULL,
`icon4title` varchar(10) NOT NULL,
`icon5title` varchar(10) NOT NULL,
`icon1` varchar(1000) NOT NULL,
`icon2` varchar(1000) NOT NULL,
`icon3` varchar(1000) NOT NULL,
`icon4` varchar(1000) NOT NULL,
`icon5` varchar(1000) NOT NULL,
`type` int(1) NOT NULL,
`ssort` int(10) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_gongkaike` (
`id` int(11) NOT NULL  AUTO_INCREMENT,
`weid` int(11) NOT NULL,
`schoolid` int(11) NOT NULL,
`ssort` int(3) NOT NULL,
`bzid` int(11) NOT NULL,
`tid` int(11) NOT NULL,
`name` varchar(100) NOT NULL,
`starttime` int(11) NOT NULL,
`endtime` int(11) NOT NULL,
`addr` varchar(100) NOT NULL,
`km_id` int(11) NOT NULL,
`bj_id` int(11) NOT NULL,
`dagang` text() NOT NULL,
`ticket` varchar(255) NOT NULL,
`qrid` int(11) NOT NULL,
`xq_id` int(11) NOT NULL,
`is_pj` int(1) NOT NULL,
`createtime` int(11) NOT NULL,
`createtid` int(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_groupactivity` (
`id` int(11) NOT NULL  AUTO_INCREMENT,
`schoolid` int(11) NOT NULL,
`weid` int(11) NOT NULL,
`title` varchar(200) NOT NULL,
`thumb` varchar(500) NOT NULL   COMMENT '缩略图',
`banner` varchar(2000) NOT NULL   COMMENT '幻灯片',
`content` varchar(2000) NOT NULL   COMMENT '活动描述',
`bjarray` varchar(1000) NOT NULL   COMMENT '班级组',
`cost` float() NOT NULL   COMMENT '报名费',
`starttime` int(11) NOT NULL,
`endtime` int(11) NOT NULL,
`type` int(3) NOT NULL   COMMENT '1活动2家政3家教',
`ssort` int(3) NOT NULL   COMMENT '排序',
`createtime` int(11) NOT NULL,
`isall` int(2) NOT NULL   COMMENT '是否全校可报',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_groupsign` (
`id` int(11) NOT NULL  AUTO_INCREMENT,
`weid` int(11) NOT NULL,
`schoolid` int(11) NOT NULL,
`userid` int(11) NOT NULL,
`gaid` int(11) NOT NULL,
`type` int(3) NOT NULL   COMMENT '1集体活动2家政3家教',
`createtime` int(11) NOT NULL,
`servetime` int(11) NOT NULL,
`sid` int(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_helps` (
`id` int(10) NOT NULL  AUTO_INCREMENT,
`schoolid` varchar(50) NOT NULL,
`title` varchar(100) NOT NULL,
`author` varchar(50) NOT NULL,
`content` mediumtext() NOT NULL,
`createtime` int(10) NOT NULL,
`lasttime` int(10) NOT NULL,
`click` int(10) NOT NULL,
`is_share` tinyint(1) NOT NULL,
`share_id` tinyint(1) NOT NULL,
`type` int(10) NOT NULL   COMMENT '分类',
`displayorder` int(10) NOT NULL,
`could_id` int(10) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_icon` (
`id` int(10) NOT NULL  AUTO_INCREMENT,
`weid` int(10) NOT NULL   COMMENT '公众号',
`schoolid` int(10) NOT NULL   COMMENT '分校id',
`name` varchar(50) NOT NULL   COMMENT '按钮名称',
`icon` varchar(1000) NOT NULL   COMMENT '按钮图标',
`icon2` varchar(1000) NOT NULL,
`beizhu` varchar(50) NOT NULL   COMMENT '备注或小字',
`color` varchar(50) NOT NULL   COMMENT '颜色',
`url` varchar(1000) NOT NULL   COMMENT '链接url',
`do` varchar(100) NOT NULL,
`place` tinyint(1) NOT NULL   COMMENT '1首页菜单2底部菜单',
`ssort` tinyint(3) NOT NULL   COMMENT '排序',
`status` tinyint(1) NOT NULL   COMMENT '显示状态',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_idcard` (
`id` int(10) NOT NULL  AUTO_INCREMENT,
`weid` int(10) NOT NULL,
`schoolid` int(10) NOT NULL,
`sid` int(10) NOT NULL,
`tid` int(10) NOT NULL,
`pname` varchar(200) NOT NULL,
`bj_id` int(10) NOT NULL,
`idcard` varchar(200) NOT NULL   COMMENT '卡号',
`orderid` int(10) NOT NULL,
`spic` varchar(1000) NOT NULL,
`tpic` varchar(1000) NOT NULL,
`pard` tinyint(1) NOT NULL   COMMENT '1本人2母亲3父亲4爷爷5奶奶6外公7外婆8叔叔9阿姨10其他',
`createtime` int(10) NOT NULL,
`severend` int(10) NOT NULL,
`is_on` int(1) NOT NULL   COMMENT '1:使用,2未用,默认0',
`usertype` int(1) NOT NULL   COMMENT '1:老师,学生0',
`is_frist` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1'  COMMENT '1:首次,2非首次',
`cardtype` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1',
`lastedittime` int(11),
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_index` (
`id` int(10) NOT NULL  AUTO_INCREMENT,
`uid` int(10) NOT NULL   COMMENT '账户ID',
`weid` int(10) NOT NULL   COMMENT '公众号id',
`areaid` int(10) NOT NULL   COMMENT '区域id',
`title` varchar(50) NOT NULL   COMMENT '名称',
`logo` varchar(1000) NOT NULL   COMMENT '学校logo',
`thumb` varchar(200) NOT NULL   COMMENT '图文消息缩略图',
`qroce` varchar(200) NOT NULL   COMMENT '二维码',
`info` varchar(1000) NOT NULL   COMMENT '简短描述',
`content` text() NOT NULL   COMMENT '简介',
`zhaosheng` text() NOT NULL   COMMENT '招生简章',
`tel` varchar(20) NOT NULL   COMMENT '联系电话',
`location_p` varchar(100) NOT NULL   COMMENT '省',
`location_c` varchar(100) NOT NULL   COMMENT '市',
`location_a` varchar(100) NOT NULL   COMMENT '区',
`address` varchar(200) NOT NULL   COMMENT '地址',
`place` varchar(200) NOT NULL,
`lat` decimal(18,10) NOT NULL DEFAULT NULL DEFAULT '0.0000000000'  COMMENT '经度',
`lng` decimal(18,10) NOT NULL DEFAULT NULL DEFAULT '0.0000000000'  COMMENT '纬度',
`copyright` varchar(100) NOT NULL   COMMENT '版权',
`is_stuewcode` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1'  COMMENT '1开启2关闭',
`recharging_password` varchar(20) NOT NULL   COMMENT '充值密码',
`thumb_url` varchar(1000),
`is_show` tinyint(1) NOT NULL   COMMENT '是否在手机端显示',
`ssort` tinyint(3) NOT NULL,
`is_sms` tinyint(1) NOT NULL,
`dateline` int(10) NOT NULL,
`is_hot` tinyint(1) NOT NULL   COMMENT '搜索页显示',
`is_showew` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1'  COMMENT '页面显示二维码开关',
`is_showad` int(10) NOT NULL   COMMENT '是否显示广告',
`is_comload` int(10) NOT NULL   COMMENT '广告ID',
`is_recordmac` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '2'  COMMENT '1启用2不启用',
`is_cardpay` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '2'  COMMENT '1启用2不启用',
`is_cardlist` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '2'  COMMENT '1启用2不启用',
`is_cost` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '2'  COMMENT '1启用2不启用',
`is_video` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '2'  COMMENT '1启用2不启用',
`is_sign` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1'  COMMENT '1启用2不启用',
`is_zjh` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1'  COMMENT '1启用2不启用周计划',
`is_wxsign` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1'  COMMENT '1启用2不启用微信签到',
`is_openht` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1'  COMMENT '1启用2不启用独立后台',
`is_signneedcomfim` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1'  COMMENT '手机签到是否需确认1是2否',
`shoucename` varchar(200) NOT NULL   COMMENT '手册名称',
`videoname` varchar(200) NOT NULL   COMMENT '监控名称',
`wqgroupid` int(10) NOT NULL   COMMENT '微擎默认用户组',
`videopic` varchar(1000) NOT NULL   COMMENT '监控封面',
`manger` varchar(200) NOT NULL   COMMENT '模版名称1',
`isopen` tinyint(1) NOT NULL   COMMENT '0显示1不',
`issale` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '5'  COMMENT '5种状态',
`gonggao` varchar(1000) NOT NULL   COMMENT '通知',
`is_rest` tinyint(1) NOT NULL,
`signset` varchar(200) NOT NULL   COMMENT '报名设置',
`cardset` varchar(500) NOT NULL   COMMENT '刷卡设置',
`typeid` int(10) NOT NULL   COMMENT '学校类型',
`cityid` int(10) NOT NULL   COMMENT '城市ID',
`spic` varchar(200) NOT NULL   COMMENT '默认学生头像',
`tpic` varchar(200) NOT NULL   COMMENT '默认教师头像',
`jxstart` varchar(50),
`jxend` varchar(50),
`lxstart` varchar(50),
`lxend` varchar(50),
`jxstart1` varchar(50),
`jxend1` varchar(50),
`lxstart1` varchar(50),
`lxend1` varchar(50),
`jxstart2` varchar(50),
`jxend2` varchar(50),
`lxstart2` varchar(50),
`lxend2` varchar(50) NOT NULL,
`style1` varchar(200) NOT NULL   COMMENT '模版名称',
`style2` varchar(200) NOT NULL   COMMENT '模版名称2',
`style3` varchar(200) NOT NULL   COMMENT '模版名称3',
`userstyle` varchar(50) NOT NULL   COMMENT '家长学生中心模板',
`bjqstyle` varchar(50) NOT NULL DEFAULT NULL DEFAULT 'old',
`sms_set` varchar(1000) NOT NULL   COMMENT '短信设置',
`is_kb` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1'  COMMENT '1启用2不启公立课表',
`send_overtime` int(10) NOT NULL DEFAULT NULL DEFAULT '-1'  COMMENT '延迟发送',
`sms_use_times` int(10) NOT NULL   COMMENT '短信调用次数',
`sms_rest_times` int(10) NOT NULL   COMMENT '可用短信条数',
`is_fbvocie` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '2'  COMMENT '1启用2不启语音',
`is_fbnew` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '2'  COMMENT '1启用2不启用语音和视频',
`txid` varchar(100) NOT NULL   COMMENT '腾讯云APPID',
`txms` varchar(100) NOT NULL   COMMENT '腾讯云密钥',
`bd_type` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1'  COMMENT '1名手2名码3名学4名手码5名手学6名学码7名手学码7名手学码',
`headcolor` varchar(20) NOT NULL DEFAULT NULL DEFAULT '#06c1ae'  COMMENT '头部颜色',
`mallsetinfo` varchar(500),
`wxsignrange` int(11) NOT NULL,
`yzxxtid` int(11) NOT NULL,
`comtid` int(11) NOT NULL,
`Cost2Point` int(11) NOT NULL   COMMENT '一元换多少积分',
`Is_point` int(3) NOT NULL   COMMENT '是否开启积分抵用',
`is_star` int(3) NOT NULL   COMMENT '是否星级1是0否',
`is_chongzhi` int(3) NOT NULL,
`chongzhiweid` int(11) NOT NULL,
`is_shoufei` int(3) NOT NULL DEFAULT NULL DEFAULT '1',
`is_picarr` int(3) NOT NULL   COMMENT '是否图片组',
`picarrset` varchar(500) NOT NULL   COMMENT '图片组设置',
`is_textarr` int(3) NOT NULL   COMMENT '是否文字组',
`textarrset` varchar(2000) NOT NULL   COMMENT '文字组设置',
`is_qx` int(3) NOT NULL DEFAULT NULL DEFAULT '1',
`savevideoto` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1',
`shareset` varchar(500) NOT NULL,
`is_printer` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1'  COMMENT '是否启用打印机',
`sh_teacherids` varchar(1000)    COMMENT '校园圈模式审核人',
`chargesetinfo` text() NOT NULL   COMMENT '充电桩设置',
`is_buzhu` tinyint(3) NOT NULL   COMMENT '是否启用学生补助余额',
`is_ap` tinyint(3) NOT NULL,
`is_book` tinyint(3) NOT NULL,
`fxlocation` text() NOT NULL,
`checksendset` text()    COMMENT '考勤记录推送对象',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_kcbiao` (
`id` int(11) NOT NULL  AUTO_INCREMENT,
`schoolid` int(10) NOT NULL   COMMENT '分校id',
`weid` int(10) NOT NULL,
`tid` int(11) NOT NULL   COMMENT '所属教师ID',
`kcid` int(11) NOT NULL   COMMENT '所属课程ID',
`nub` int(11) NOT NULL   COMMENT '第几堂课或第几讲',
`bj_id` int(11) NOT NULL,
`km_id` int(11) NOT NULL,
`xq_id` int(11) NOT NULL,
`sd_id` int(11) NOT NULL,
`isxiangqing` tinyint(1) NOT NULL   COMMENT '内容显示开关',
`content` text() NOT NULL   COMMENT '课程内容',
`date` int(10) NOT NULL   COMMENT '开课日期',
`is_remind` int(3) NOT NULL   COMMENT '是否已提醒',
`addr_id` int(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_kcpingjia` (
`id` int(11) NOT NULL  AUTO_INCREMENT,
`weid` int(11) NOT NULL,
`schoolid` int(11) NOT NULL,
`kcid` int(11) NOT NULL,
`tid` int(11) NOT NULL,
`sid` int(11) NOT NULL,
`userid` int(11) NOT NULL,
`type` int(3) NOT NULL   COMMENT '评分1留言2',
`content` varchar(1000) NOT NULL,
`star` int(3) NOT NULL,
`createtime` int(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_kcsign` (
`id` int(11) NOT NULL  AUTO_INCREMENT,
`weid` int(11) NOT NULL,
`schoolid` int(11) NOT NULL,
`kcid` int(11) NOT NULL   COMMENT '课程id',
`ksid` int(11) NOT NULL   COMMENT '课时id',
`sid` int(11) NOT NULL,
`tid` int(11) NOT NULL,
`createtime` int(11) NOT NULL,
`signtime` int(11) NOT NULL   COMMENT '签哪天的到',
`status` int(3) NOT NULL,
`type` int(3) NOT NULL   COMMENT '自由or固定',
`qrtid` int(11) NOT NULL,
`kcname` varchar(200) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_language` (
`id` int(10) NOT NULL  AUTO_INCREMENT,
`weid` int(10) NOT NULL   COMMENT '公众号',
`schoolid` int(10) NOT NULL   COMMENT '分校id',
`is_on` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1'  COMMENT '是否启用',
`lanset` text() NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_leave` (
`id` int(10) NOT NULL  AUTO_INCREMENT,
`leaveid` int(10) NOT NULL,
`weid` int(10) NOT NULL,
`schoolid` int(10) NOT NULL   COMMENT '学校ID',
`uid` int(10) NOT NULL   COMMENT '微擎UID',
`tuid` int(10) NOT NULL   COMMENT '老师微擎UID',
`userid` int(10) NOT NULL   COMMENT '发送者id',
`touserid` int(10) NOT NULL   COMMENT '接收者id',
`openid` varchar(200)    COMMENT 'openid',
`sid` int(10) NOT NULL   COMMENT '学生ID',
`tid` int(10) NOT NULL   COMMENT '教师ID',
`type` varchar(10)    COMMENT '请假类型',
`startime` varchar(200)    COMMENT '开始时间',
`endtime` varchar(200)    COMMENT '结束时间',
`startime1` int(10) NOT NULL   COMMENT '开始时间',
`endtime1` int(10) NOT NULL   COMMENT '结束时间',
`conet` varchar(200)    COMMENT '详细内容',
`reconet` varchar(200)    COMMENT '详细内容',
`createtime` int(10) NOT NULL   COMMENT '创建时间',
`cltime` int(10) NOT NULL   COMMENT '处理时间',
`cltid` int(10) NOT NULL   COMMENT '老师id',
`status` tinyint(1) NOT NULL   COMMENT '审核状态',
`bj_id` int(10) NOT NULL   COMMENT '班级ID',
`teacherid` int(11),
`isfrist` tinyint(1) NOT NULL   COMMENT '1是0否',
`isliuyan` tinyint(1) NOT NULL   COMMENT '是否留言',
`isread` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1'  COMMENT '1未读2已读',
`audio` varchar(1000),
`tonjzrtid` int(11) NOT NULL   COMMENT '年级主任tid',
`toxztid` int(11) NOT NULL   COMMENT '校长tid',
`njzryj` varchar(200) NOT NULL   COMMENT '年级主任审批意见',
`njzrcltime` int(11) NOT NULL,
`picurl` varchar(1000) NOT NULL,
`tktype` int(3) NOT NULL   COMMENT '调课类型',
`ksnum` int(11) NOT NULL,
`classid` int(11) NOT NULL,
`more_less` tinyint(3) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_mall` (
`id` int(10) NOT NULL  AUTO_INCREMENT,
`weid` int(11) NOT NULL,
`schoolid` int(11) NOT NULL,
`title` varchar(100) NOT NULL,
`thumb` varchar(1000) NOT NULL,
`content` text() NOT NULL,
`type` varchar(500) NOT NULL,
`fenlei` varchar(500) NOT NULL,
`sort` int(10) NOT NULL,
`old_price` float() NOT NULL,
`new_price` float() NOT NULL,
`points` int(10) NOT NULL,
`qty` int(10) NOT NULL,
`sold` int(10) NOT NULL,
`cop` int(11) NOT NULL   COMMENT '1纯积分2纯金额3混合',
`xsxg` int(3) NOT NULL   COMMENT '学生限购数量.0为不限购',
`showtype` int(3) NOT NULL   COMMENT '家长端1/教师端2/两者0',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_mallorder` (
`id` int(10) NOT NULL  AUTO_INCREMENT,
`weid` int(11) NOT NULL,
`schoolid` int(11) NOT NULL,
`tid` int(11) NOT NULL,
`goodsid` int(11) NOT NULL,
`addressid` int(11) NOT NULL,
`torderid` int(11) NOT NULL,
`tname` varchar(50) NOT NULL,
`tphone` varchar(15) NOT NULL,
`taddress` varchar(500) NOT NULL,
`count` int(10) NOT NULL,
`allcash` float() NOT NULL,
`allpoint` int(11) NOT NULL,
`beizhu` varchar(500) NOT NULL,
`cop` int(11) NOT NULL   COMMENT '1纯积分2纯金额3混合',
`status` int(3) NOT NULL,
`fahuo` int(3) NOT NULL,
`createtime` int(10) NOT NULL,
`sid` int(11) NOT NULL   COMMENT '学生id',
`userid` int(11) NOT NULL   COMMENT '购买者userid（学生用）',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_media` (
`id` int(10) NOT NULL  AUTO_INCREMENT,
`weid` int(10) NOT NULL,
`schoolid` int(10) NOT NULL   COMMENT '学校ID',
`uid` int(10) NOT NULL   COMMENT '发布者UID',
`sid` int(10) NOT NULL   COMMENT '学生SID',
`picurl` varchar(255)    COMMENT '图片',
`fmpicurl` varchar(255)    COMMENT '封面图片',
`bj_id1` int(10) NOT NULL   COMMENT '班级ID1',
`bj_id2` int(10) NOT NULL   COMMENT '班级ID2',
`bj_id3` int(10) NOT NULL   COMMENT '班级ID3',
`order` int(10) NOT NULL   COMMENT '排序',
`sherid` int(10) NOT NULL   COMMENT '所属图文id',
`createtime` int(10) NOT NULL   COMMENT '创建时间',
`type` tinyint(1) NOT NULL   COMMENT '0班级圈1学生相册',
`isfm` tinyint(1) NOT NULL   COMMENT '1是0否',
`kc_id` int(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_news` (
`id` int(10) NOT NULL  AUTO_INCREMENT,
`weid` int(10) NOT NULL,
`schoolid` int(10) NOT NULL,
`cateid` int(10) NOT NULL,
`title` varchar(100) NOT NULL,
`type` varchar(50) NOT NULL,
`content` mediumtext() NOT NULL,
`thumb` varchar(255) NOT NULL,
`description` varchar(255) NOT NULL,
`author` varchar(50) NOT NULL,
`picarr` text()    COMMENT '图片组',
`displayorder` int(10) NOT NULL   COMMENT '排序',
`is_display` tinyint(3) NOT NULL,
`is_show_home` tinyint(3) NOT NULL,
`createtime` int(10) NOT NULL,
`click` int(10) NOT NULL,
`dianzan` int(10) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_notice` (
`id` int(10) NOT NULL  AUTO_INCREMENT,
`weid` int(10) NOT NULL,
`schoolid` int(10) NOT NULL   COMMENT '学校ID',
`tid` int(10) NOT NULL   COMMENT '教师ID',
`tname` varchar(10)    COMMENT '发布老师名字',
`title` varchar(50)    COMMENT '文章名称',
`content` text() NOT NULL   COMMENT '详细内容',
`outurl` varchar(500)    COMMENT '外部链接',
`picarr` text()    COMMENT '图片组',
`createtime` int(10) NOT NULL   COMMENT '创建时间',
`bj_id` int(10) NOT NULL   COMMENT '班级ID',
`km_id` int(10) NOT NULL   COMMENT '科目ID',
`type` tinyint(1) NOT NULL   COMMENT '是否班级通知',
`ismobile` tinyint(1) NOT NULL   COMMENT '0手机端1电脑端',
`groupid` tinyint(1) NOT NULL   COMMENT '1为全体师生2为全体教师3为全体家长和学生',
`video` varchar(2000) NOT NULL   COMMENT '视频地址',
`videopic` varchar(1000) NOT NULL   COMMENT '视频封面',
`audio` varchar(100)    COMMENT '音频',
`audiotime` int(10) NOT NULL   COMMENT '音频时长',
`anstype` varchar(200) NOT NULL,
`kc_id` int(11) NOT NULL,
`usertype` varchar(100)    COMMENT '接收用户',
`userdatas` varchar(1000)    COMMENT '用户数据',
`is_research` tinyint(3) NOT NULL,
`ali_vod_id` varchar(100)    COMMENT '视频画面ID',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_object` (
`id` int(10) NOT NULL  AUTO_INCREMENT,
`item` int(10) NOT NULL,
`type` varchar(50) NOT NULL,
`displayorder` varchar(50) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_online` (
`id` int(10) NOT NULL  AUTO_INCREMENT,
`weid` int(10) NOT NULL,
`schoolid` int(10) NOT NULL,
`macid` int(10) NOT NULL,
`commond` int(10) NOT NULL,
`result` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '2',
`isread` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '2',
`createtime` int(10) NOT NULL   COMMENT '生成时间',
`dotime` int(10) NOT NULL   COMMENT '执行时间',
`lastedittime` int(11)    COMMENT '任务对应的最近一次修改时间',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_order` (
`id` int(10) NOT NULL  AUTO_INCREMENT,
`weid` int(10) NOT NULL,
`schoolid` int(10) NOT NULL   COMMENT '学校ID',
`orderid` int(10) NOT NULL   COMMENT '订单ID',
`uid` int(10) NOT NULL   COMMENT '发布者UID',
`userid` int(10) NOT NULL   COMMENT '发布者UID',
`sid` int(10) NOT NULL   COMMENT '学生id',
`kcid` int(10) NOT NULL   COMMENT '课程ID',
`costid` int(10) NOT NULL   COMMENT '项目ID',
`lastorderid` int(10) NOT NULL   COMMENT '继承订单,用于续费',
`signid` int(10) NOT NULL   COMMENT '报名ID',
`bdcardid` int(10) NOT NULL   COMMENT '帮卡ID',
`obid` int(10) NOT NULL   COMMENT '功能ID',
`cose` decimal(18,2) NOT NULL DEFAULT NULL DEFAULT '0.00'  COMMENT '价格',
`status` tinyint(1) NOT NULL   COMMENT '1未支付2为已支付3为已退款',
`type` tinyint(1) NOT NULL   COMMENT '1课程2项目3功能4报名',
`createtime` int(10) NOT NULL   COMMENT '创建时间',
`hmxianlaohu` varchar(30) NOT NULL   COMMENT '支付LOGO',
`paytime` int(10) NOT NULL   COMMENT '支付时间',
`paytype` tinyint(1) NOT NULL   COMMENT '1线上2现金',
`pay_type` varchar(100)    COMMENT '支付方式',
`tuitime` int(10) NOT NULL   COMMENT '退费时间',
`xufeitype` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '2'  COMMENT '1已续费2未续费',
`payweid` int(10) NOT NULL   COMMENT '支付公众号',
`uniontid` varchar(1000)    COMMENT '微信或支付宝返回的订单号',
`vodid` int(10)    COMMENT '视频ID',
`vodtype` varchar(30) NOT NULL   COMMENT '视频课程购买类型',
`morderid` int(11) NOT NULL,
`ksnum` int(11) NOT NULL,
`spoint` int(11) NOT NULL   COMMENT '学生积分',
`tempsid` int(11) NOT NULL,
`tempopenid` varchar(50) NOT NULL,
`tid` varchar(100) NOT NULL,
`taocanid` int(11) NOT NULL,
`shareuserid` int(11) NOT NULL,
`print_nums` int(11) NOT NULL,
`refundid` int(10) NOT NULL,
`wxpayid` int(10) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_points` (
`id` int(11) NOT NULL  AUTO_INCREMENT,
`weid` int(11) NOT NULL,
`schoolid` int(11) NOT NULL,
`op` varchar(30) NOT NULL,
`name` varchar(50) NOT NULL,
`dailytime` int(11) NOT NULL,
`adpoint` int(11) NOT NULL,
`is_on` int(1) NOT NULL   COMMENT '1开启2关闭',
`type` int(3) NOT NULL   COMMENT '1规则2任务',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_pointsrecord` (
`id` int(11) NOT NULL  AUTO_INCREMENT,
`weid` int(11) NOT NULL,
`schoolid` int(11) NOT NULL,
`tid` int(11) NOT NULL,
`pid` int(11) NOT NULL,
`createtime` int(10) NOT NULL,
`type` int(3) NOT NULL,
`mcount` int(3) NOT NULL   COMMENT '任务已完成次数',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_print_log` (
`id` int(10) NOT NULL  AUTO_INCREMENT,
`weid` int(10) NOT NULL,
`schoolid` int(10) NOT NULL,
`pid` tinyint(3) NOT NULL,
`oid` int(10) NOT NULL,
`foid` varchar(50) NOT NULL,
`status` tinyint(3) NOT NULL DEFAULT NULL DEFAULT '2'  COMMENT '1:打印成功,2:打印未成功',
`printer_type` varchar(20) NOT NULL DEFAULT NULL DEFAULT 'feie',
`createtime` int(10) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_printer` (
`id` int(10) NOT NULL  AUTO_INCREMENT,
`weid` int(10) NOT NULL,
`schoolid` int(10) NOT NULL,
`name` varchar(20) NOT NULL,
`type` varchar(20) NOT NULL DEFAULT NULL DEFAULT 'feie',
`print_no` varchar(30) NOT NULL,
`member_code` varchar(50) NOT NULL   COMMENT '飞蛾打印机机器号',
`key` varchar(30) NOT NULL,
`api_key` varchar(100) NOT NULL   COMMENT '易联云打印机api_key',
`print_nums` tinyint(3) NOT NULL DEFAULT NULL DEFAULT '1',
`qrcode_link` varchar(100) NOT NULL,
`print_header` varchar(50) NOT NULL,
`print_footer` varchar(50) NOT NULL,
`status` tinyint(3) NOT NULL DEFAULT NULL DEFAULT '1',
`delivery_type` int(10) NOT NULL,
`createtime` int(10) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_printset` (
`id` int(10) NOT NULL  AUTO_INCREMENT,
`weid` int(10) NOT NULL,
`schoolid` int(10) NOT NULL,
`ordertype` varchar(20) NOT NULL   COMMENT '缴费类型',
`status` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1',
`printarr` varchar(30) NOT NULL,
`print_nums` int(10) NOT NULL DEFAULT NULL DEFAULT '1',
`print_header` varchar(50) NOT NULL,
`print_footer` varchar(50) NOT NULL,
`qrcode_link` varchar(1000) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_qrcode_info` (
`id` int(10) NOT NULL  AUTO_INCREMENT,
`weid` int(10) NOT NULL,
`qrcid` int(10) NOT NULL   COMMENT '二维码场景ID',
`gpid` int(10) NOT NULL,
`name` varchar(50) NOT NULL   COMMENT '场景名称',
`keyword` varchar(100) NOT NULL   COMMENT '关联关键字',
`model` tinyint(1) NOT NULL   COMMENT '模式，1临时，2为永久',
`ticket` varchar(250) NOT NULL   COMMENT '标识',
`show_url` varchar(550) NOT NULL   COMMENT '图片地址',
`expire` int(10) NOT NULL   COMMENT '过期时间',
`subnum` int(10) NOT NULL   COMMENT '关注扫描次数',
`createtime` int(10) NOT NULL   COMMENT '生成时间',
`status` tinyint(1) NOT NULL   COMMENT '0为未启用，1为启用',
`group_id` int(3) NOT NULL,
`rid` int(3) NOT NULL,
`schoolid` int(10)    COMMENT '学校ID',
`qr_url` varchar(300) NOT NULL,
`type` int(11) NOT NULL DEFAULT NULL DEFAULT '1',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_qrcode_set` (
`id` int(10) NOT NULL  AUTO_INCREMENT,
`bg` int(10) NOT NULL,
`qrleft` int(10) NOT NULL,
`qrtop` int(10) NOT NULL,
`qrwidth` int(10) NOT NULL,
`qrheight` int(10) NOT NULL,
`model` int(10) NOT NULL DEFAULT NULL DEFAULT '1',
`logoheight` int(10) NOT NULL,
`logowidth` int(10) NOT NULL,
`logoqrheight` int(10) NOT NULL,
`logoqrwidth` int(10) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_qrcode_statinfo` (
`id` int(10) NOT NULL  AUTO_INCREMENT,
`weid` int(10) NOT NULL,
`qid` int(10) NOT NULL,
`openid` varchar(150) NOT NULL   COMMENT '用户的唯一身份ID',
`type` tinyint(1) NOT NULL   COMMENT '是否发生在订阅时',
`qrcid` int(10) NOT NULL   COMMENT '二维码场景ID',
`name` varchar(50) NOT NULL   COMMENT '场景名称',
`createtime` int(10) NOT NULL   COMMENT '生成时间',
`group_id` int(3) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_questions` (
`id` int(10) NOT NULL  AUTO_INCREMENT,
`weid` int(10) NOT NULL,
`schoolid` int(10) NOT NULL,
`zyid` int(10) NOT NULL   COMMENT '作业id',
`tid` int(10) NOT NULL,
`type` tinyint(1) NOT NULL   COMMENT '1单选2多选3提问4图片5语音6视频',
`title` varchar(1000) NOT NULL,
`qorder` int(10) NOT NULL   COMMENT '排序',
`content` varchar(1000) NOT NULL,
`AnsType` varchar(200) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_record` (
`id` int(10) NOT NULL  AUTO_INCREMENT,
`weid` int(10) NOT NULL,
`schoolid` int(10) NOT NULL,
`noticeid` int(10) NOT NULL,
`userid` int(10) NOT NULL,
`tid` int(10) NOT NULL,
`sid` int(10) NOT NULL,
`openid` varchar(30) NOT NULL   COMMENT 'openid',
`type` int(1) NOT NULL   COMMENT '类型1通知2作业',
`createtime` int(10) NOT NULL,
`readtime` int(10) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_reply` (
`id` int(10) NOT NULL  AUTO_INCREMENT,
`rid` int(10) NOT NULL,
`schoolid` int(10) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_scforxs` (
`id` int(10) NOT NULL  AUTO_INCREMENT,
`weid` int(10) NOT NULL,
`schoolid` int(10) NOT NULL,
`scid` int(10) NOT NULL,
`setid` int(10) NOT NULL,
`tid` int(10) NOT NULL,
`sid` int(10) NOT NULL,
`userid` int(10) NOT NULL,
`iconsetid` int(10) NOT NULL   COMMENT '评价id',
`iconlevel` int(10) NOT NULL   COMMENT '本评价等级',
`tword` varchar(1000)    COMMENT '老师评语',
`jzword` varchar(1000)    COMMENT '家长评语',
`dianzan` varchar(1000)    COMMENT '点赞数',
`dianzopenid` varchar(500)    COMMENT '点赞人openid',
`fromto` tinyint(1) NOT NULL   COMMENT '1来自老师2来自家长',
`type` tinyint(1) NOT NULL   COMMENT '1文字2表现评价3点赞',
`createtime` int(10) NOT NULL,
`ssort` int(10) NOT NULL   COMMENT '排序',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_schoolset` (
`id` int(11) NOT NULL  AUTO_INCREMENT,
`alivodappid` varchar(100) NOT NULL,
`alivodkey` varchar(100) NOT NULL,
`alivodcate` int(10) NOT NULL,
`schoolid` int(11) NOT NULL,
`weid` int(11) NOT NULL,
`is_bigdata` tinyint(3) NOT NULL,
`pwd` varchar(64) NOT NULL,
`short_url` varchar(32) NOT NULL,
`bgtitle` varchar(100) NOT NULL,
`refund` tinyint(1) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_score` (
`id` int(11) NOT NULL  AUTO_INCREMENT,
`weid` int(10) NOT NULL,
`schoolid` int(10) NOT NULL   COMMENT '分校id',
`xq_id` int(11) NOT NULL,
`bj_id` int(11) NOT NULL,
`qh_id` int(11) NOT NULL,
`km_id` int(11) NOT NULL,
`sid` int(11) NOT NULL,
`my_score` varchar(50) NOT NULL,
`info` varchar(1000) NOT NULL   COMMENT '教师评价',
`createtime` int(10) NOT NULL,
`is_absent` tinyint(3)    COMMENT '1缺考0未缺考',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_set` (
`id` int(10) NOT NULL  AUTO_INCREMENT,
`weid` int(10) NOT NULL,
`istplnotice` tinyint(1) NOT NULL   COMMENT '是否模版通知',
`guanli` tinyint(1) NOT NULL   COMMENT '管理方式',
`xsqingjia` varchar(200)    COMMENT '学生请假申请ID',
`xsqjsh` varchar(200)    COMMENT '学生请假审核通知ID',
`jsqingjia` varchar(200)    COMMENT '教员请假申请体提醒ID',
`jsqjsh` varchar(200)    COMMENT '教员请假审核通知ID',
`xxtongzhi` varchar(200)    COMMENT '学校通知ID',
`liuyan` varchar(200)    COMMENT '家长留言ID',
`liuyanhf` varchar(200)    COMMENT '教师回复家长留言ID',
`zuoye` varchar(200)    COMMENT '发布作业提醒ID',
`bjtz` varchar(200)    COMMENT '班级通知ID',
`bjqshjg` varchar(200)    COMMENT '班级圈审核结果',
`bjqshtz` varchar(200)    COMMENT '班级圈审核提醒',
`jxlxtx` varchar(200)    COMMENT '进校提醒',
`jfjgtz` varchar(200)    COMMENT '缴费结果通知',
`bd_set` varchar(1000),
`sms_acss` varchar(1000),
`sms_use_times` int(10) NOT NULL   COMMENT '短信调用次数',
`htname` varchar(200)    COMMENT '后台系统名称',
`bgcolor` varchar(20)    COMMENT '后台系统背景颜色',
`bgimg` varchar(200),
`banner1` varchar(200),
`banner2` varchar(200),
`banner3` varchar(200),
`banner4` varchar(200),
`sykstx` varchar(300) NOT NULL,
`kcyytx` varchar(300) NOT NULL,
`kcqdtx` varchar(300) NOT NULL,
`sktxls` varchar(300) NOT NULL,
`newcenteriocn` varchar(1000) NOT NULL,
`is_new` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1'  COMMENT '新旧风格',
`banquan` varchar(200) NOT NULL   COMMENT '版权',
`sensitive_word` mediumtext() NOT NULL   COMMENT '敏感词库',
`school_max` int(10) NOT NULL,
`baidumapapi` varchar(200),
`fkyytx` varchar(300)    COMMENT '访客消息推送模板ID',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_shouce` (
`id` int(10) NOT NULL  AUTO_INCREMENT,
`weid` int(10) NOT NULL,
`schoolid` int(10) NOT NULL,
`bj_id` int(10) NOT NULL,
`xq_id` int(10) NOT NULL,
`tid` int(10) NOT NULL,
`title` varchar(1000),
`setid` int(10) NOT NULL   COMMENT '设置ID',
`kcid` int(10) NOT NULL   COMMENT '课程ID',
`ksid` int(10) NOT NULL   COMMENT '课时ID',
`starttime` int(10) NOT NULL,
`endtime` int(10) NOT NULL,
`createtime` int(10) NOT NULL,
`sendtype` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1'  COMMENT '1未发送2部分发送3全部发送',
`ssort` int(10) NOT NULL   COMMENT '排序',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_shoucepyk` (
`id` int(10) NOT NULL  AUTO_INCREMENT,
`weid` int(10) NOT NULL,
`schoolid` int(10) NOT NULL,
`bj_id` int(10) NOT NULL,
`tid` int(10) NOT NULL,
`title` text()    COMMENT '内容',
`createtime` int(10) NOT NULL,
`ssort` int(10) NOT NULL   COMMENT '排序',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_shouceset` (
`id` int(10) NOT NULL  AUTO_INCREMENT,
`weid` int(10) NOT NULL,
`schoolid` int(10) NOT NULL,
`title` varchar(7),
`bottext` varchar(7),
`boturl` varchar(1000),
`lasttxet` varchar(7),
`nj_id` int(10) NOT NULL,
`icon` varchar(1000),
`bg1` varchar(1000),
`bg2` varchar(1000),
`bg3` varchar(1000),
`bg4` varchar(1000),
`bg5` varchar(1000),
`bg6` varchar(1000),
`bgm` varchar(1000),
`top1` varchar(1000),
`top2` varchar(1000),
`top3` varchar(1000),
`top4` varchar(1000),
`top5` varchar(1000),
`guidword1` varchar(20),
`guidword2` varchar(20),
`guidurl` varchar(1000),
`createtime` int(10) NOT NULL,
`allowshare` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1'  COMMENT '1允许2禁止',
`ssort` int(10) NOT NULL   COMMENT '排序',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_shouceseticon` (
`id` int(10) NOT NULL  AUTO_INCREMENT,
`weid` int(10) NOT NULL,
`schoolid` int(10) NOT NULL,
`setid` int(10) NOT NULL   COMMENT '设置ID',
`title` varchar(7),
`icon1title` varchar(10),
`icon2title` varchar(10),
`icon3title` varchar(10),
`icon4title` varchar(10),
`icon5title` varchar(10),
`icon1` varchar(1000),
`icon2` varchar(1000),
`icon3` varchar(1000),
`icon4` varchar(1000),
`icon5` varchar(1000),
`type` tinyint(1) NOT NULL   COMMENT '1教师使用2家长',
`ssort` int(10) NOT NULL   COMMENT '排序',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_signup` (
`id` int(10) NOT NULL  AUTO_INCREMENT,
`weid` int(10) NOT NULL,
`schoolid` int(10) NOT NULL,
`icon` varchar(255) NOT NULL,
`name` varchar(50) NOT NULL,
`numberid` int(11),
`sex` int(1) NOT NULL,
`mobile` char(11) NOT NULL,
`nj_id` int(10) NOT NULL   COMMENT '年级ID',
`bj_id` int(10) NOT NULL   COMMENT '班级ID',
`idcard` varchar(18) NOT NULL,
`cost` varchar(10) NOT NULL   COMMENT '报名费用',
`birthday` int(10) NOT NULL,
`createtime` int(10) NOT NULL,
`passtime` int(10) NOT NULL,
`lasttime` int(10) NOT NULL,
`uid` int(10) NOT NULL   COMMENT '发布者UID',
`orderid` int(10) NOT NULL,
`openid` varchar(30) NOT NULL   COMMENT 'openid',
`pard` tinyint(1) NOT NULL   COMMENT '关系',
`status` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1'  COMMENT '1审核中2审核通过3不通过',
`sid` int(11) NOT NULL,
`picarr1` varchar(1000) NOT NULL,
`picarr2` varchar(1000) NOT NULL,
`picarr3` varchar(1000) NOT NULL,
`picarr4` varchar(1000) NOT NULL,
`picarr5` varchar(1000) NOT NULL,
`textarr1` varchar(1000) NOT NULL,
`textarr2` varchar(1000) NOT NULL,
`textarr3` varchar(1000) NOT NULL,
`textarr4` varchar(1000) NOT NULL,
`textarr5` varchar(1000) NOT NULL,
`textarr6` varchar(1000) NOT NULL,
`textarr7` varchar(1000) NOT NULL,
`textarr8` varchar(1000) NOT NULL,
`textarr9` varchar(1000) NOT NULL,
`textarr10` varchar(1000) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_sms_log` (
`id` int(10) NOT NULL  AUTO_INCREMENT,
`weid` int(10) NOT NULL,
`schoolid` int(10) NOT NULL,
`type` varchar(100) NOT NULL,
`mobile` varchar(15) NOT NULL,
`sendtime` int(10) NOT NULL   COMMENT '生成时间',
`msg` varchar(1000) NOT NULL   COMMENT '返回消息',
`status` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1'  COMMENT '默认成功1',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_students` (
`id` int(11) NOT NULL  AUTO_INCREMENT,
`weid` int(10) NOT NULL,
`schoolid` int(10) NOT NULL   COMMENT '分校id',
`icon` varchar(255) NOT NULL,
`numberid` varchar(18)    COMMENT '学号',
`xq_id` int(11) NOT NULL,
`area_addr` varchar(200) NOT NULL,
`ck_id` int(11) NOT NULL,
`bj_id` int(11) NOT NULL,
`birthdate` int(10) NOT NULL,
`sex` int(1) NOT NULL,
`createdate` int(10) NOT NULL,
`seffectivetime` int(10) NOT NULL,
`stheendtime` int(10) NOT NULL,
`jf_statu` int(11),
`mobile` char(11) NOT NULL,
`homephone` char(16) NOT NULL,
`s_name` varchar(50) NOT NULL,
`localdate_id` char(20) NOT NULL,
`note` varchar(50) NOT NULL,
`amount` int(11) NOT NULL,
`area` varchar(50) NOT NULL,
`own` varchar(30) NOT NULL   COMMENT '本人微信info',
`mom` varchar(30) NOT NULL   COMMENT '母亲微信info',
`dad` varchar(30) NOT NULL   COMMENT '父亲微信info',
`other` varchar(30) NOT NULL   COMMENT '其他家长微信info',
`ouserid` int(11) NOT NULL   COMMENT '用户ID',
`muserid` int(11) NOT NULL   COMMENT '用户ID',
`duserid` int(11) NOT NULL   COMMENT '用户ID',
`otheruserid` int(11) NOT NULL   COMMENT '用户ID',
`ouid` int(10) NOT NULL   COMMENT '微擎系统memberID',
`muid` int(10) NOT NULL   COMMENT '微擎系统memberID',
`duid` int(10) NOT NULL   COMMENT '微擎系统memberID',
`otheruid` int(10) NOT NULL   COMMENT '微擎系统memberID',
`xjid` int(11) NOT NULL   COMMENT '学籍信息',
`code` varchar(18)    COMMENT '绑定码',
`keyid` int(11),
`qrcode_id` int(10)    COMMENT '二维码ID',
`points` int(11) NOT NULL   COMMENT '学生积分',
`chongzhi` float(10,2) NOT NULL   COMMENT '余额',
`s_type` tinyint(3) NOT NULL   COMMENT '走读住校',
`infocard` text() NOT NULL,
`roomid` int(11) NOT NULL,
`chargenum` int(11) NOT NULL   COMMENT '充电桩剩余次数',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_task` (
`id` int(10) NOT NULL  AUTO_INCREMENT,
`weid` int(11) NOT NULL,
`schoolid` varchar(50) NOT NULL,
`kcid` int(10) NOT NULL,
`status` tinyint(1) NOT NULL,
`type` tinyint(1) NOT NULL   COMMENT '分类',
`createtime` int(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_task_list` (
`id` int(10) NOT NULL  AUTO_INCREMENT,
`weid` int(11) NOT NULL,
`schoolid` varchar(50) NOT NULL,
`ksid` int(10) NOT NULL,
`type` tinyint(1) NOT NULL   COMMENT '分类',
`createtime` int(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_tcourse` (
`id` int(11) NOT NULL  AUTO_INCREMENT,
`schoolid` int(10) NOT NULL   COMMENT '分校id',
`weid` int(10) NOT NULL,
`tid` varchar(50) NOT NULL,
`name` varchar(50) NOT NULL   COMMENT '课程名称',
`dagang` text() NOT NULL   COMMENT '课程大纲',
`start` int(10) NOT NULL   COMMENT '开始时间',
`end` int(10) NOT NULL   COMMENT '结束时间',
`minge` int(11) NOT NULL   COMMENT '名额限制',
`yibao` int(11) NOT NULL   COMMENT '已报人数',
`cose` decimal(18,2) NOT NULL DEFAULT NULL DEFAULT '0.00'  COMMENT '价格',
`adrr` varchar(100) NOT NULL   COMMENT '授课地址或教室',
`km_id` int(11) NOT NULL,
`bj_id` int(11) NOT NULL,
`xq_id` int(11) NOT NULL,
`sd_id` int(11) NOT NULL,
`is_hot` tinyint(1) NOT NULL   COMMENT '是否推荐',
`is_show` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1'  COMMENT '1显示,2否',
`payweid` int(10) NOT NULL   COMMENT '支付公众号',
`ssort` tinyint(3) NOT NULL,
`signTime` int(5) NOT NULL   COMMENT '签到时间',
`isSign` int(3) NOT NULL   COMMENT '是否可签到',
`OldOrNew` int(2) NOT NULL   COMMENT '固定课时or自由课程',
`Ctype` int(3) NOT NULL   COMMENT '课程类型',
`FirstNum` int(3) NOT NULL   COMMENT '首次包含多少课时',
`RePrice` decimal(18,2) NOT NULL   COMMENT '续费价格/课时',
`ReNum` int(3) NOT NULL   COMMENT '起续课时数',
`AllNum` int(3) NOT NULL   COMMENT '总共多少课时',
`thumb` varchar(1000) NOT NULL,
`maintid` int(11) NOT NULL   COMMENT '主讲老师',
`Point2Cost` int(11) NOT NULL   COMMENT '多少积分抵一元',
`MinPoint` int(11) NOT NULL   COMMENT '最低使用下限',
`MaxPoint` int(11) NOT NULL   COMMENT '最高使用上限',
`yytid` int(11) NOT NULL   COMMENT '预约负责老师',
`is_remind_pj` int(2) NOT NULL,
`is_tuijian` int(3) NOT NULL   COMMENT '是否推荐课程',
`is_tx` tinyint(1) NOT NULL   COMMENT '提醒开关',
`txtime` int(10) NOT NULL   COMMENT '提前分钟',
`is_print` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '2'  COMMENT '是否启用打印机',
`printarr` varchar(100) NOT NULL   COMMENT '打印机',
`bigimg` text()    COMMENT '幻灯片',
`is_dm` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1'  COMMENT '弹幕',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_teachers` (
`id` int(11) NOT NULL  AUTO_INCREMENT,
`schoolid` int(10) NOT NULL   COMMENT '分校id',
`weid` int(10) NOT NULL,
`tname` varchar(50) NOT NULL,
`birthdate` int(10) NOT NULL,
`tel` varchar(20) NOT NULL,
`mobile` char(11) NOT NULL,
`email` char(50) NOT NULL,
`sex` int(1) NOT NULL,
`km_id1` int(11) NOT NULL   COMMENT '授课科目1',
`km_id2` int(11) NOT NULL   COMMENT '授课科目2',
`km_id3` int(11) NOT NULL   COMMENT '授课科目3',
`bj_id1` int(11) NOT NULL   COMMENT '授课班级1',
`bj_id2` int(11) NOT NULL   COMMENT '授课班级2',
`bj_id3` int(11) NOT NULL   COMMENT '授课班级3',
`xq_id1` int(11) NOT NULL   COMMENT '授课年级1',
`xq_id2` int(11) NOT NULL   COMMENT '授课年级2',
`xq_id3` int(11) NOT NULL   COMMENT '授课年级3',
`com` varchar(30) NOT NULL,
`fz_id` int(11) NOT NULL   COMMENT '所属分组',
`jiontime` int(10) NOT NULL,
`info` text() NOT NULL   COMMENT '教学成果',
`jinyan` text() NOT NULL   COMMENT '教学经验',
`headinfo` text() NOT NULL   COMMENT '教学特点',
`thumb` varchar(200) NOT NULL,
`status` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1',
`sort` int(11),
`code` int(11) NOT NULL   COMMENT '绑定码',
`openid` varchar(30) NOT NULL   COMMENT '教师微信',
`uid` int(10) NOT NULL   COMMENT '微擎系统memberID',
`userid` int(11) NOT NULL   COMMENT '用户ID',
`is_show` tinyint(1) NOT NULL   COMMENT '是否显示',
`point` int(10) NOT NULL,
`star` float() NOT NULL   COMMENT '平均星级',
`idcard` varchar(20) NOT NULL,
`jiguan` varchar(80) NOT NULL,
`minzu` varchar(20) NOT NULL,
`zzmianmao` varchar(30) NOT NULL,
`address` varchar(300) NOT NULL,
`otherinfo` text() NOT NULL,
`plate_num` varchar(15)    COMMENT '教师车牌号',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_teascore` (
`id` int(11) NOT NULL  AUTO_INCREMENT,
`schoolid` int(11) NOT NULL,
`weid` int(11) NOT NULL,
`tid` int(11) NOT NULL,
`score` float(5,2) NOT NULL,
`fromfzid` int(11) NOT NULL   COMMENT '评分人分组',
`fromtid` varchar(30) NOT NULL   COMMENT '评分人tid',
`scoretime` int(11) NOT NULL   COMMENT '评分时间',
`createtime` int(11) NOT NULL   COMMENT '创建时间',
`obid` int(11) NOT NULL,
`parentobid` int(11) NOT NULL,
`sid` int(11) NOT NULL,
`type` tinyint(3) NOT NULL,
`bj_id` int(11) NOT NULL,
`nj_id` int(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_teasencefiles` (
`id` int(11) NOT NULL  AUTO_INCREMENT,
`schoolid` int(11) NOT NULL,
`weid` int(11) NOT NULL,
`tid` int(11) NOT NULL,
`senceid` int(11) NOT NULL,
`up_word` varchar(500) NOT NULL,
`up_imgs` varchar(5000) NOT NULL,
`up_audio` varchar(1000) NOT NULL,
`audiotime` int(11) NOT NULL,
`up_video` varchar(1000) NOT NULL,
`videoimg` varchar(500) NOT NULL,
`createtime` int(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_tempstudent` (
`id` int(11) NOT NULL  AUTO_INCREMENT,
`weid` int(11) NOT NULL,
`schoolid` int(11) NOT NULL,
`sname` varchar(50) NOT NULL,
`mobile` varchar(15) NOT NULL,
`sex` int(3) NOT NULL,
`addr` varchar(200) NOT NULL,
`nj_id` int(11) NOT NULL,
`bj_id` int(11) NOT NULL,
`pard` varchar(3) NOT NULL,
`openid` varchar(50) NOT NULL,
`uid` int(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_timetable` (
`id` int(11) NOT NULL  AUTO_INCREMENT,
`schoolid` int(10) NOT NULL   COMMENT '分校id',
`weid` int(10) NOT NULL,
`bj_id` int(10) NOT NULL,
`title` varchar(50) NOT NULL,
`begintime` int(11) NOT NULL,
`endtime` int(11) NOT NULL,
`monday` text() NOT NULL,
`tuesday` text() NOT NULL,
`wednesday` text() NOT NULL,
`thursday` text() NOT NULL,
`friday` text() NOT NULL,
`saturday` text() NOT NULL,
`sunday` text() NOT NULL,
`ishow` int(1) NOT NULL DEFAULT NULL DEFAULT '1'  COMMENT '1:显示,2隐藏,默认1',
`sort` int(11) NOT NULL DEFAULT NULL DEFAULT '1',
`type` varchar(15) NOT NULL,
`headpic` varchar(200) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_todo` (
`id` int(11) NOT NULL  AUTO_INCREMENT,
`weid` int(11) NOT NULL,
`schoolid` int(11) NOT NULL,
`fsid` int(11) NOT NULL   COMMENT '发布者id',
`jsid` int(11) NOT NULL   COMMENT '接收者id',
`zjid` int(11) NOT NULL   COMMENT '转交者id',
`todoname` varchar(100) NOT NULL   COMMENT '任务名称',
`content` varchar(2000) NOT NULL,
`starttime` int(11) NOT NULL,
`endtime` int(11) NOT NULL,
`createtime` int(11) NOT NULL,
`acttime` int(11) NOT NULL,
`status` int(3) NOT NULL   COMMENT '状态（7种）',
`zjbeizhu` varchar(100) NOT NULL   COMMENT '转交备注',
`jjbeizhu1` varchar(100) NOT NULL   COMMENT '第一人拒绝备注',
`jjbeizhu2` varchar(100) NOT NULL   COMMENT '第二人拒绝备注',
`picurls` varchar(5000) NOT NULL,
`audio` varchar(1000) NOT NULL,
`audiotime` varchar(300) NOT NULL,
`videoimg` varchar(1000) NOT NULL,
`video` varchar(2000) NOT NULL,
`ali_vod_id` varchar(100)    COMMENT '视频画面ID',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_type` (
`id` int(10) NOT NULL  AUTO_INCREMENT,
`weid` int(10) NOT NULL   COMMENT '所属帐号',
`name` varchar(50) NOT NULL   COMMENT '类型名称',
`parentid` int(10) NOT NULL   COMMENT '上级分类ID,0为第一级',
`ssort` tinyint(3) NOT NULL   COMMENT '排序',
`status` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1'  COMMENT '显示状态',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_upsence` (
`id` int(11) NOT NULL  AUTO_INCREMENT,
`schoolid` int(11) NOT NULL,
`weid` int(11) NOT NULL,
`name` varchar(500) NOT NULL,
`sencetime` int(11) NOT NULL,
`qxfzid` int(11) NOT NULL,
`createtime` int(11) NOT NULL,
`ali_vod_id` varchar(100)    COMMENT '视频画面ID',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_user` (
`id` int(10) NOT NULL  AUTO_INCREMENT,
`sid` int(10) NOT NULL   COMMENT '学生ID',
`tid` int(10) NOT NULL   COMMENT '老师ID',
`weid` int(10) NOT NULL   COMMENT '公众号ID',
`schoolid` int(10) NOT NULL   COMMENT '学校ID',
`uid` int(10) NOT NULL   COMMENT '微擎系统memberID',
`openid` varchar(30) NOT NULL   COMMENT 'openid',
`userinfo` text()    COMMENT '用户信息',
`pard` int(1) NOT NULL   COMMENT '关系',
`status` tinyint(1) NOT NULL   COMMENT '用户状态',
`is_allowmsg` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1'  COMMENT '私聊信息接收语法',
`is_frist` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1'  COMMENT '1首次2不是',
`createtime` int(10) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_user_class` (
`id` int(10) NOT NULL  AUTO_INCREMENT,
`weid` int(10) NOT NULL,
`schoolid` int(10) NOT NULL,
`tid` int(10) NOT NULL,
`sid` int(10) NOT NULL,
`bj_id` int(10) NOT NULL,
`km_id` int(10) NOT NULL,
`type` tinyint(1) NOT NULL   COMMENT '1老师2学生',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_wxpay` (
`id` int(10) NOT NULL  AUTO_INCREMENT,
`hmxianlaohu` varchar(30) NOT NULL   COMMENT '订单ID',
`weid` int(10) NOT NULL,
`schoolid` int(10) NOT NULL   COMMENT '学校ID',
`orderid` int(10) NOT NULL   COMMENT '返回订单ID',
`od1` int(10) NOT NULL   COMMENT '1',
`od2` int(10) NOT NULL   COMMENT '2',
`od3` int(10) NOT NULL   COMMENT '3',
`od4` int(10) NOT NULL   COMMENT '4',
`od5` int(10) NOT NULL   COMMENT '5',
`cose` decimal(18,2) NOT NULL DEFAULT NULL DEFAULT '0.00'  COMMENT '价格',
`payweid` int(10) NOT NULL   COMMENT '支付公众号',
`openid` varchar(30) NOT NULL   COMMENT 'openid',
`status` tinyint(1) NOT NULL   COMMENT '1未支付2为未支付3为已退款',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_yuecostlog` (
`id` int(11) NOT NULL  AUTO_INCREMENT,
`schoolid` int(11) NOT NULL,
`weid` int(11) NOT NULL,
`sid` int(11) NOT NULL,
`yue_type` tinyint(3) NOT NULL   COMMENT '1补助余额2普通余额3充电桩',
`cost` float(8,2) NOT NULL,
`costtime` int(11) NOT NULL,
`orderid` int(11) NOT NULL,
`cost_type` tinyint(3) NOT NULL   COMMENT '1收入2消费',
`macid` varchar(100) NOT NULL,
`on_offline` tinyint(3) NOT NULL   COMMENT '1线上2线下',
`createtime` int(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_zjh` (
`id` int(10) NOT NULL  AUTO_INCREMENT,
`is_on` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1',
`picrul` varchar(1000) NOT NULL,
`weid` int(10) NOT NULL,
`schoolid` int(10) NOT NULL,
`planuid` varchar(37) NOT NULL,
`tid` int(10) NOT NULL,
`bj_id` int(10) NOT NULL,
`type` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1'  COMMENT '1图片2文字',
`start` int(10) NOT NULL,
`end` int(10) NOT NULL,
`ssort` int(10) NOT NULL   COMMENT '排序',
`createtime` int(10) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_zjhdetail` (
`id` int(10) NOT NULL  AUTO_INCREMENT,
`weid` int(10) NOT NULL,
`schoolid` int(10) NOT NULL,
`planuid` varchar(37) NOT NULL,
`curactivename` varchar(100) NOT NULL,
`detailuid` varchar(37) NOT NULL,
`curactiveid` varchar(100) NOT NULL,
`activedesc` text()    COMMENT '内容',
`week` tinyint(1) NOT NULL   COMMENT '1-5',
`ssort` int(10) NOT NULL   COMMENT '排序',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_zjhset` (
`id` int(10) NOT NULL  AUTO_INCREMENT,
`weid` int(10) NOT NULL,
`schoolid` int(10) NOT NULL,
`planuid` varchar(37) NOT NULL,
`activetypeid` varchar(100) NOT NULL,
`curactiveid` varchar(100) NOT NULL,
`activetypename` varchar(30)    COMMENT '名称',
`type` varchar(2)    COMMENT 'AM,PM',
`ssort` int(10) NOT NULL   COMMENT '排序',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
");
if(pdo_tableexists('wx_school_address')) {
 if(!pdo_fieldexists('wx_school_address',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_address')." ADD `id` int(10) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_address')) {
 if(!pdo_fieldexists('wx_school_address',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_address')." ADD `weid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_address')) {
 if(!pdo_fieldexists('wx_school_address',  'schoolid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_address')." ADD `schoolid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_address')) {
 if(!pdo_fieldexists('wx_school_address',  'openid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_address')." ADD `openid` varchar(30) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_address')) {
 if(!pdo_fieldexists('wx_school_address',  'name')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_address')." ADD `name` varchar(50) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_address')) {
 if(!pdo_fieldexists('wx_school_address',  'phone')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_address')." ADD `phone` varchar(30) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_address')) {
 if(!pdo_fieldexists('wx_school_address',  'province')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_address')." ADD `province` varchar(40) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_address')) {
 if(!pdo_fieldexists('wx_school_address',  'city')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_address')." ADD `city` varchar(40) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_address')) {
 if(!pdo_fieldexists('wx_school_address',  'county')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_address')." ADD `county` varchar(40) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_address')) {
 if(!pdo_fieldexists('wx_school_address',  'address')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_address')." ADD `address` varchar(300) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_allcamera')) {
 if(!pdo_fieldexists('wx_school_allcamera',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_allcamera')." ADD `id` int(10) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_allcamera')) {
 if(!pdo_fieldexists('wx_school_allcamera',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_allcamera')." ADD `weid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_allcamera')) {
 if(!pdo_fieldexists('wx_school_allcamera',  'schoolid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_allcamera')." ADD `schoolid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_allcamera')) {
 if(!pdo_fieldexists('wx_school_allcamera',  'kcid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_allcamera')." ADD `kcid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_allcamera')) {
 if(!pdo_fieldexists('wx_school_allcamera',  'name')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_allcamera')." ADD `name` varchar(50) NOT NULL   COMMENT '画面名称';");
 }
}
if(pdo_tableexists('wx_school_allcamera')) {
 if(!pdo_fieldexists('wx_school_allcamera',  'videopic')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_allcamera')." ADD `videopic` varchar(1000) NOT NULL   COMMENT '监控地址';");
 }
}
if(pdo_tableexists('wx_school_allcamera')) {
 if(!pdo_fieldexists('wx_school_allcamera',  'videourl')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_allcamera')." ADD `videourl` varchar(1000) NOT NULL   COMMENT '监控地址';");
 }
}
if(pdo_tableexists('wx_school_allcamera')) {
 if(!pdo_fieldexists('wx_school_allcamera',  'starttime1')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_allcamera')." ADD `starttime1` varchar(50) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_allcamera')) {
 if(!pdo_fieldexists('wx_school_allcamera',  'endtime1')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_allcamera')." ADD `endtime1` varchar(50) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_allcamera')) {
 if(!pdo_fieldexists('wx_school_allcamera',  'createtime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_allcamera')." ADD `createtime` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_allcamera')) {
 if(!pdo_fieldexists('wx_school_allcamera',  'conet')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_allcamera')." ADD `conet` text()    COMMENT '说明';");
 }
}
if(pdo_tableexists('wx_school_allcamera')) {
 if(!pdo_fieldexists('wx_school_allcamera',  'allowpy')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_allcamera')." ADD `allowpy` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1'  COMMENT '1允许2拒绝';");
 }
}
if(pdo_tableexists('wx_school_allcamera')) {
 if(!pdo_fieldexists('wx_school_allcamera',  'videotype')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_allcamera')." ADD `videotype` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1'  COMMENT '1公共2指定班级';");
 }
}
if(pdo_tableexists('wx_school_allcamera')) {
 if(!pdo_fieldexists('wx_school_allcamera',  'bj_id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_allcamera')." ADD `bj_id` text()    COMMENT '关联班级组';");
 }
}
if(pdo_tableexists('wx_school_allcamera')) {
 if(!pdo_fieldexists('wx_school_allcamera',  'type')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_allcamera')." ADD `type` tinyint(1) NOT NULL   COMMENT '1监控2课程直播';");
 }
}
if(pdo_tableexists('wx_school_allcamera')) {
 if(!pdo_fieldexists('wx_school_allcamera',  'click')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_allcamera')." ADD `click` int(10) NOT NULL   COMMENT '查看量';");
 }
}
if(pdo_tableexists('wx_school_allcamera')) {
 if(!pdo_fieldexists('wx_school_allcamera',  'ssort')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_allcamera')." ADD `ssort` int(10) NOT NULL   COMMENT '排序';");
 }
}
if(pdo_tableexists('wx_school_allcamera')) {
 if(!pdo_fieldexists('wx_school_allcamera',  'is_pay')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_allcamera')." ADD `is_pay` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '2'  COMMENT '单独付费与否';");
 }
}
if(pdo_tableexists('wx_school_allcamera')) {
 if(!pdo_fieldexists('wx_school_allcamera',  'price_one')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_allcamera')." ADD `price_one` float() NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_allcamera')) {
 if(!pdo_fieldexists('wx_school_allcamera',  'price_one_cun')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_allcamera')." ADD `price_one_cun` float() NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_allcamera')) {
 if(!pdo_fieldexists('wx_school_allcamera',  'price_all')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_allcamera')." ADD `price_all` float() NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_allcamera')) {
 if(!pdo_fieldexists('wx_school_allcamera',  'price_all_cun')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_allcamera')." ADD `price_all_cun` float() NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_allcamera')) {
 if(!pdo_fieldexists('wx_school_allcamera',  'days')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_allcamera')." ADD `days` int(11)  DEFAULT NULL DEFAULT '10';");
 }
}
if(pdo_tableexists('wx_school_allcamera')) {
 if(!pdo_fieldexists('wx_school_allcamera',  'is_try')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_allcamera')." ADD `is_try` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1'  COMMENT '是否允许试看';");
 }
}
if(pdo_tableexists('wx_school_allcamera')) {
 if(!pdo_fieldexists('wx_school_allcamera',  'try_time')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_allcamera')." ADD `try_time` int(11)  DEFAULT NULL DEFAULT '30'  COMMENT '试看时间';");
 }
}
if(pdo_tableexists('wx_school_allcamera')) {
 if(!pdo_fieldexists('wx_school_allcamera',  'payweid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_allcamera')." ADD `payweid` int(11)    COMMENT '收款公众号';");
 }
}
if(pdo_tableexists('wx_school_allcamera')) {
 if(!pdo_fieldexists('wx_school_allcamera',  'starttime2')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_allcamera')." ADD `starttime2` varchar(50) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_allcamera')) {
 if(!pdo_fieldexists('wx_school_allcamera',  'starttime3')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_allcamera')." ADD `starttime3` varchar(50) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_allcamera')) {
 if(!pdo_fieldexists('wx_school_allcamera',  'endtime2')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_allcamera')." ADD `endtime2` varchar(50) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_allcamera')) {
 if(!pdo_fieldexists('wx_school_allcamera',  'endtime3')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_allcamera')." ADD `endtime3` varchar(50) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_ans_remark')) {
 if(!pdo_fieldexists('wx_school_ans_remark',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_ans_remark')." ADD `id` int(11) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_ans_remark')) {
 if(!pdo_fieldexists('wx_school_ans_remark',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_ans_remark')." ADD `weid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_ans_remark')) {
 if(!pdo_fieldexists('wx_school_ans_remark',  'schoolid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_ans_remark')." ADD `schoolid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_ans_remark')) {
 if(!pdo_fieldexists('wx_school_ans_remark',  'userid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_ans_remark')." ADD `userid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_ans_remark')) {
 if(!pdo_fieldexists('wx_school_ans_remark',  'tid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_ans_remark')." ADD `tid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_ans_remark')) {
 if(!pdo_fieldexists('wx_school_ans_remark',  'tname')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_ans_remark')." ADD `tname` varchar(30) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_ans_remark')) {
 if(!pdo_fieldexists('wx_school_ans_remark',  'sid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_ans_remark')." ADD `sid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_ans_remark')) {
 if(!pdo_fieldexists('wx_school_ans_remark',  'zyid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_ans_remark')." ADD `zyid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_ans_remark')) {
 if(!pdo_fieldexists('wx_school_ans_remark',  'tmid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_ans_remark')." ADD `tmid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_ans_remark')) {
 if(!pdo_fieldexists('wx_school_ans_remark',  'type')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_ans_remark')." ADD `type` int(3) NOT NULL   COMMENT '1是电脑创建的作业2是手机创建的作业';");
 }
}
if(pdo_tableexists('wx_school_ans_remark')) {
 if(!pdo_fieldexists('wx_school_ans_remark',  'content')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_ans_remark')." ADD `content` varchar(500) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_ans_remark')) {
 if(!pdo_fieldexists('wx_school_ans_remark',  'createtime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_ans_remark')." ADD `createtime` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_ans_remark')) {
 if(!pdo_fieldexists('wx_school_ans_remark',  'audio')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_ans_remark')." ADD `audio` varchar(1000) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_ans_remark')) {
 if(!pdo_fieldexists('wx_school_ans_remark',  'audiotime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_ans_remark')." ADD `audiotime` varchar(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_answers')) {
 if(!pdo_fieldexists('wx_school_answers',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_answers')." ADD `id` int(10) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_answers')) {
 if(!pdo_fieldexists('wx_school_answers',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_answers')." ADD `weid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_answers')) {
 if(!pdo_fieldexists('wx_school_answers',  'schoolid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_answers')." ADD `schoolid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_answers')) {
 if(!pdo_fieldexists('wx_school_answers',  'zyid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_answers')." ADD `zyid` int(10) NOT NULL   COMMENT '问题id';");
 }
}
if(pdo_tableexists('wx_school_answers')) {
 if(!pdo_fieldexists('wx_school_answers',  'sid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_answers')." ADD `sid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_answers')) {
 if(!pdo_fieldexists('wx_school_answers',  'tid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_answers')." ADD `tid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_answers')) {
 if(!pdo_fieldexists('wx_school_answers',  'userid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_answers')." ADD `userid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_answers')) {
 if(!pdo_fieldexists('wx_school_answers',  'tmid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_answers')." ADD `tmid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_answers')) {
 if(!pdo_fieldexists('wx_school_answers',  'type')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_answers')." ADD `type` tinyint(1) NOT NULL   COMMENT '1回答2单选3多选4图片5语音6视频';");
 }
}
if(pdo_tableexists('wx_school_answers')) {
 if(!pdo_fieldexists('wx_school_answers',  'MyAnswer')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_answers')." ADD `MyAnswer` varchar(2000) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_answers')) {
 if(!pdo_fieldexists('wx_school_answers',  'createtime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_answers')." ADD `createtime` varchar(13) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_apartment')) {
 if(!pdo_fieldexists('wx_school_apartment',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_apartment')." ADD `id` int(11) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_apartment')) {
 if(!pdo_fieldexists('wx_school_apartment',  'schoolid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_apartment')." ADD `schoolid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_apartment')) {
 if(!pdo_fieldexists('wx_school_apartment',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_apartment')." ADD `weid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_apartment')) {
 if(!pdo_fieldexists('wx_school_apartment',  'name')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_apartment')." ADD `name` varchar(50) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_apartment')) {
 if(!pdo_fieldexists('wx_school_apartment',  'ssort')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_apartment')." ADD `ssort` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_apartment')) {
 if(!pdo_fieldexists('wx_school_apartment',  'tid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_apartment')." ADD `tid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_aproom')) {
 if(!pdo_fieldexists('wx_school_aproom',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_aproom')." ADD `id` int(11) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_aproom')) {
 if(!pdo_fieldexists('wx_school_aproom',  'schoolid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_aproom')." ADD `schoolid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_aproom')) {
 if(!pdo_fieldexists('wx_school_aproom',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_aproom')." ADD `weid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_aproom')) {
 if(!pdo_fieldexists('wx_school_aproom',  'name')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_aproom')." ADD `name` varchar(50) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_aproom')) {
 if(!pdo_fieldexists('wx_school_aproom',  'apid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_aproom')." ADD `apid` int(11) NOT NULL   COMMENT '楼栋id';");
 }
}
if(pdo_tableexists('wx_school_aproom')) {
 if(!pdo_fieldexists('wx_school_aproom',  'noon_start')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_aproom')." ADD `noon_start` varchar(20) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_aproom')) {
 if(!pdo_fieldexists('wx_school_aproom',  'noon_end')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_aproom')." ADD `noon_end` varchar(20) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_aproom')) {
 if(!pdo_fieldexists('wx_school_aproom',  'night_start')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_aproom')." ADD `night_start` varchar(20) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_aproom')) {
 if(!pdo_fieldexists('wx_school_aproom',  'night_end')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_aproom')." ADD `night_end` varchar(20) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_aproom')) {
 if(!pdo_fieldexists('wx_school_aproom',  'ssort')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_aproom')." ADD `ssort` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_aproom')) {
 if(!pdo_fieldexists('wx_school_aproom',  'floornum')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_aproom')." ADD `floornum` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_area')) {
 if(!pdo_fieldexists('wx_school_area',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_area')." ADD `id` int(10) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_area')) {
 if(!pdo_fieldexists('wx_school_area',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_area')." ADD `weid` int(10) NOT NULL   COMMENT '所属帐号';");
 }
}
if(pdo_tableexists('wx_school_area')) {
 if(!pdo_fieldexists('wx_school_area',  'name')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_area')." ADD `name` varchar(50) NOT NULL   COMMENT '区域名称';");
 }
}
if(pdo_tableexists('wx_school_area')) {
 if(!pdo_fieldexists('wx_school_area',  'parentid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_area')." ADD `parentid` int(10) NOT NULL   COMMENT '上级分类ID,0为第一级';");
 }
}
if(pdo_tableexists('wx_school_area')) {
 if(!pdo_fieldexists('wx_school_area',  'ssort')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_area')." ADD `ssort` tinyint(3) NOT NULL   COMMENT '排序';");
 }
}
if(pdo_tableexists('wx_school_area')) {
 if(!pdo_fieldexists('wx_school_area',  'type')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_area')." ADD `type` char(20) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_area')) {
 if(!pdo_fieldexists('wx_school_area',  'status')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_area')." ADD `status` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1'  COMMENT '显示状态';");
 }
}
if(pdo_tableexists('wx_school_banners')) {
 if(!pdo_fieldexists('wx_school_banners',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_banners')." ADD `id` int(11) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_banners')) {
 if(!pdo_fieldexists('wx_school_banners',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_banners')." ADD `weid` int(11);");
 }
}
if(pdo_tableexists('wx_school_banners')) {
 if(!pdo_fieldexists('wx_school_banners',  'uniacid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_banners')." ADD `uniacid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_banners')) {
 if(!pdo_fieldexists('wx_school_banners',  'schoolid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_banners')." ADD `schoolid` int(11);");
 }
}
if(pdo_tableexists('wx_school_banners')) {
 if(!pdo_fieldexists('wx_school_banners',  'bannername')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_banners')." ADD `bannername` varchar(50);");
 }
}
if(pdo_tableexists('wx_school_banners')) {
 if(!pdo_fieldexists('wx_school_banners',  'link')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_banners')." ADD `link` varchar(255);");
 }
}
if(pdo_tableexists('wx_school_banners')) {
 if(!pdo_fieldexists('wx_school_banners',  'thumb')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_banners')." ADD `thumb` varchar(5000) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_banners')) {
 if(!pdo_fieldexists('wx_school_banners',  'begintime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_banners')." ADD `begintime` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_banners')) {
 if(!pdo_fieldexists('wx_school_banners',  'endtime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_banners')." ADD `endtime` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_banners')) {
 if(!pdo_fieldexists('wx_school_banners',  'displayorder')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_banners')." ADD `displayorder` int(11);");
 }
}
if(pdo_tableexists('wx_school_banners')) {
 if(!pdo_fieldexists('wx_school_banners',  'enabled')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_banners')." ADD `enabled` int(11);");
 }
}
if(pdo_tableexists('wx_school_banners')) {
 if(!pdo_fieldexists('wx_school_banners',  'leixing')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_banners')." ADD `leixing` int(1) NOT NULL   COMMENT '0学校,1平台';");
 }
}
if(pdo_tableexists('wx_school_banners')) {
 if(!pdo_fieldexists('wx_school_banners',  'arr')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_banners')." ADD `arr` text()    COMMENT '列表信息';");
 }
}
if(pdo_tableexists('wx_school_banners')) {
 if(!pdo_fieldexists('wx_school_banners',  'click')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_banners')." ADD `click` varchar(1000)    COMMENT '点击量';");
 }
}
if(pdo_tableexists('wx_school_banners')) {
 if(!pdo_fieldexists('wx_school_banners',  'place')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_banners')." ADD `place` tinyint(1) NOT NULL   COMMENT '位置';");
 }
}
if(pdo_tableexists('wx_school_bjq')) {
 if(!pdo_fieldexists('wx_school_bjq',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_bjq')." ADD `id` int(10) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_bjq')) {
 if(!pdo_fieldexists('wx_school_bjq',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_bjq')." ADD `weid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_bjq')) {
 if(!pdo_fieldexists('wx_school_bjq',  'schoolid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_bjq')." ADD `schoolid` int(10) NOT NULL   COMMENT '学校ID';");
 }
}
if(pdo_tableexists('wx_school_bjq')) {
 if(!pdo_fieldexists('wx_school_bjq',  'content')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_bjq')." ADD `content` text() NOT NULL   COMMENT '详细内容或评价';");
 }
}
if(pdo_tableexists('wx_school_bjq')) {
 if(!pdo_fieldexists('wx_school_bjq',  'uid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_bjq')." ADD `uid` int(10) NOT NULL   COMMENT '发布者UID';");
 }
}
if(pdo_tableexists('wx_school_bjq')) {
 if(!pdo_fieldexists('wx_school_bjq',  'wx')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_bjq')." ADD `wx` varchar(30) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_bjq')) {
 if(!pdo_fieldexists('wx_school_bjq',  'bj_id1')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_bjq')." ADD `bj_id1` int(10) NOT NULL   COMMENT '班级ID1';");
 }
}
if(pdo_tableexists('wx_school_bjq')) {
 if(!pdo_fieldexists('wx_school_bjq',  'bj_id2')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_bjq')." ADD `bj_id2` int(10) NOT NULL   COMMENT '班级ID2';");
 }
}
if(pdo_tableexists('wx_school_bjq')) {
 if(!pdo_fieldexists('wx_school_bjq',  'bj_id3')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_bjq')." ADD `bj_id3` int(10) NOT NULL   COMMENT '班级ID3';");
 }
}
if(pdo_tableexists('wx_school_bjq')) {
 if(!pdo_fieldexists('wx_school_bjq',  'sherid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_bjq')." ADD `sherid` int(10) NOT NULL   COMMENT '所属图文id';");
 }
}
if(pdo_tableexists('wx_school_bjq')) {
 if(!pdo_fieldexists('wx_school_bjq',  'shername')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_bjq')." ADD `shername` varchar(50)    COMMENT '分享者名字';");
 }
}
if(pdo_tableexists('wx_school_bjq')) {
 if(!pdo_fieldexists('wx_school_bjq',  'openid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_bjq')." ADD `openid` varchar(30) NOT NULL   COMMENT '帖子所属openid';");
 }
}
if(pdo_tableexists('wx_school_bjq')) {
 if(!pdo_fieldexists('wx_school_bjq',  'isopen')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_bjq')." ADD `isopen` tinyint(1) NOT NULL   COMMENT '是否显示';");
 }
}
if(pdo_tableexists('wx_school_bjq')) {
 if(!pdo_fieldexists('wx_school_bjq',  'type')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_bjq')." ADD `type` tinyint(1) NOT NULL   COMMENT '类型0为班级圈1为评论';");
 }
}
if(pdo_tableexists('wx_school_bjq')) {
 if(!pdo_fieldexists('wx_school_bjq',  'createtime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_bjq')." ADD `createtime` int(10) NOT NULL   COMMENT '创建时间';");
 }
}
if(pdo_tableexists('wx_school_bjq')) {
 if(!pdo_fieldexists('wx_school_bjq',  'msgtype')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_bjq')." ADD `msgtype` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1'  COMMENT '1文字图片2语音3视频';");
 }
}
if(pdo_tableexists('wx_school_bjq')) {
 if(!pdo_fieldexists('wx_school_bjq',  'userid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_bjq')." ADD `userid` int(10) NOT NULL   COMMENT '发布者用户ID';");
 }
}
if(pdo_tableexists('wx_school_bjq')) {
 if(!pdo_fieldexists('wx_school_bjq',  'video')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_bjq')." ADD `video` varchar(1000);");
 }
}
if(pdo_tableexists('wx_school_bjq')) {
 if(!pdo_fieldexists('wx_school_bjq',  'videoimg')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_bjq')." ADD `videoimg` varchar(1000);");
 }
}
if(pdo_tableexists('wx_school_bjq')) {
 if(!pdo_fieldexists('wx_school_bjq',  'plid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_bjq')." ADD `plid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_bjq')) {
 if(!pdo_fieldexists('wx_school_bjq',  'is_private')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_bjq')." ADD `is_private` varchar(3) NOT NULL DEFAULT NULL DEFAULT 'N'  COMMENT '禁止评论';");
 }
}
if(pdo_tableexists('wx_school_bjq')) {
 if(!pdo_fieldexists('wx_school_bjq',  'audio')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_bjq')." ADD `audio` varchar(1000)    COMMENT '音频地址';");
 }
}
if(pdo_tableexists('wx_school_bjq')) {
 if(!pdo_fieldexists('wx_school_bjq',  'audiotime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_bjq')." ADD `audiotime` int(10) NOT NULL   COMMENT '音频时间';");
 }
}
if(pdo_tableexists('wx_school_bjq')) {
 if(!pdo_fieldexists('wx_school_bjq',  'link')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_bjq')." ADD `link` varchar(1000)    COMMENT '外链地址';");
 }
}
if(pdo_tableexists('wx_school_bjq')) {
 if(!pdo_fieldexists('wx_school_bjq',  'linkdesc')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_bjq')." ADD `linkdesc` varchar(200)    COMMENT '外链标题';");
 }
}
if(pdo_tableexists('wx_school_bjq')) {
 if(!pdo_fieldexists('wx_school_bjq',  'hftoname')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_bjq')." ADD `hftoname` varchar(100);");
 }
}
if(pdo_tableexists('wx_school_bjq')) {
 if(!pdo_fieldexists('wx_school_bjq',  'kc_id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_bjq')." ADD `kc_id` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_bjq')) {
 if(!pdo_fieldexists('wx_school_bjq',  'ali_vod_id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_bjq')." ADD `ali_vod_id` varchar(100);");
 }
}
if(pdo_tableexists('wx_school_bjq')) {
 if(!pdo_fieldexists('wx_school_bjq',  'is_all')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_bjq')." ADD `is_all` tinyint(3)    COMMENT '是否全校可见';");
 }
}
if(pdo_tableexists('wx_school_booksborrow')) {
 if(!pdo_fieldexists('wx_school_booksborrow',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_booksborrow')." ADD `id` int(11) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_booksborrow')) {
 if(!pdo_fieldexists('wx_school_booksborrow',  'schoolid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_booksborrow')." ADD `schoolid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_booksborrow')) {
 if(!pdo_fieldexists('wx_school_booksborrow',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_booksborrow')." ADD `weid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_booksborrow')) {
 if(!pdo_fieldexists('wx_school_booksborrow',  'sid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_booksborrow')." ADD `sid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_booksborrow')) {
 if(!pdo_fieldexists('wx_school_booksborrow',  'bookname')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_booksborrow')." ADD `bookname` varchar(200) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_booksborrow')) {
 if(!pdo_fieldexists('wx_school_booksborrow',  'worth')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_booksborrow')." ADD `worth` varchar(30) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_booksborrow')) {
 if(!pdo_fieldexists('wx_school_booksborrow',  'borrowtime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_booksborrow')." ADD `borrowtime` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_booksborrow')) {
 if(!pdo_fieldexists('wx_school_booksborrow',  'status')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_booksborrow')." ADD `status` int(3) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_booksborrow')) {
 if(!pdo_fieldexists('wx_school_booksborrow',  'returntime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_booksborrow')." ADD `returntime` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_booksborrow')) {
 if(!pdo_fieldexists('wx_school_booksborrow',  'createtime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_booksborrow')." ADD `createtime` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_busgps')) {
 if(!pdo_fieldexists('wx_school_busgps',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_busgps')." ADD `id` int(10) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_busgps')) {
 if(!pdo_fieldexists('wx_school_busgps',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_busgps')." ADD `weid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_busgps')) {
 if(!pdo_fieldexists('wx_school_busgps',  'schoolid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_busgps')." ADD `schoolid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_busgps')) {
 if(!pdo_fieldexists('wx_school_busgps',  'macid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_busgps')." ADD `macid` varchar(200) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_busgps')) {
 if(!pdo_fieldexists('wx_school_busgps',  'lat')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_busgps')." ADD `lat` decimal(18,10) NOT NULL DEFAULT NULL DEFAULT '0.0000000000'  COMMENT '经度';");
 }
}
if(pdo_tableexists('wx_school_busgps')) {
 if(!pdo_fieldexists('wx_school_busgps',  'lon')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_busgps')." ADD `lon` decimal(18,10) NOT NULL DEFAULT NULL DEFAULT '0.0000000000'  COMMENT '纬度';");
 }
}
if(pdo_tableexists('wx_school_busgps')) {
 if(!pdo_fieldexists('wx_school_busgps',  'type')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_busgps')." ADD `type` tinyint(1) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_busgps')) {
 if(!pdo_fieldexists('wx_school_busgps',  'createtime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_busgps')." ADD `createtime` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_buzhulog')) {
 if(!pdo_fieldexists('wx_school_buzhulog',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_buzhulog')." ADD `id` int(11) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_buzhulog')) {
 if(!pdo_fieldexists('wx_school_buzhulog',  'schoolid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_buzhulog')." ADD `schoolid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_buzhulog')) {
 if(!pdo_fieldexists('wx_school_buzhulog',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_buzhulog')." ADD `weid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_buzhulog')) {
 if(!pdo_fieldexists('wx_school_buzhulog',  'sid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_buzhulog')." ADD `sid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_buzhulog')) {
 if(!pdo_fieldexists('wx_school_buzhulog',  'start_yue')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_buzhulog')." ADD `start_yue` float(8,2) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_buzhulog')) {
 if(!pdo_fieldexists('wx_school_buzhulog',  'now_yue')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_buzhulog')." ADD `now_yue` float(8,2) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_buzhulog')) {
 if(!pdo_fieldexists('wx_school_buzhulog',  'starttime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_buzhulog')." ADD `starttime` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_buzhulog')) {
 if(!pdo_fieldexists('wx_school_buzhulog',  'endtime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_buzhulog')." ADD `endtime` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_buzhulog')) {
 if(!pdo_fieldexists('wx_school_buzhulog',  'createtime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_buzhulog')." ADD `createtime` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_camerapl')) {
 if(!pdo_fieldexists('wx_school_camerapl',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_camerapl')." ADD `id` int(10) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_camerapl')) {
 if(!pdo_fieldexists('wx_school_camerapl',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_camerapl')." ADD `weid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_camerapl')) {
 if(!pdo_fieldexists('wx_school_camerapl',  'schoolid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_camerapl')." ADD `schoolid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_camerapl')) {
 if(!pdo_fieldexists('wx_school_camerapl',  'carmeraid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_camerapl')." ADD `carmeraid` int(10) NOT NULL   COMMENT '画面ID';");
 }
}
if(pdo_tableexists('wx_school_camerapl')) {
 if(!pdo_fieldexists('wx_school_camerapl',  'userid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_camerapl')." ADD `userid` int(10) NOT NULL   COMMENT '用户ID';");
 }
}
if(pdo_tableexists('wx_school_camerapl')) {
 if(!pdo_fieldexists('wx_school_camerapl',  'conet')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_camerapl')." ADD `conet` text()    COMMENT '内容';");
 }
}
if(pdo_tableexists('wx_school_camerapl')) {
 if(!pdo_fieldexists('wx_school_camerapl',  'bj_id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_camerapl')." ADD `bj_id` int(10) NOT NULL   COMMENT '班级ID';");
 }
}
if(pdo_tableexists('wx_school_camerapl')) {
 if(!pdo_fieldexists('wx_school_camerapl',  'type')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_camerapl')." ADD `type` tinyint(1) NOT NULL   COMMENT '1点赞2评论';");
 }
}
if(pdo_tableexists('wx_school_camerapl')) {
 if(!pdo_fieldexists('wx_school_camerapl',  'createtime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_camerapl')." ADD `createtime` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_camerask')) {
 if(!pdo_fieldexists('wx_school_camerask',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_camerask')." ADD `id` int(10) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_camerask')) {
 if(!pdo_fieldexists('wx_school_camerask',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_camerask')." ADD `weid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_camerask')) {
 if(!pdo_fieldexists('wx_school_camerask',  'schoolid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_camerask')." ADD `schoolid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_camerask')) {
 if(!pdo_fieldexists('wx_school_camerask',  'carmeraid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_camerask')." ADD `carmeraid` int(10) NOT NULL   COMMENT '画面ID';");
 }
}
if(pdo_tableexists('wx_school_camerask')) {
 if(!pdo_fieldexists('wx_school_camerask',  'userid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_camerask')." ADD `userid` int(10) NOT NULL   COMMENT '用户ID';");
 }
}
if(pdo_tableexists('wx_school_camerask')) {
 if(!pdo_fieldexists('wx_school_camerask',  'type')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_camerask')." ADD `type` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1'  COMMENT '1视频试看';");
 }
}
if(pdo_tableexists('wx_school_camerask')) {
 if(!pdo_fieldexists('wx_school_camerask',  'createtime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_camerask')." ADD `createtime` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_checkdatedetail')) {
 if(!pdo_fieldexists('wx_school_checkdatedetail',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checkdatedetail')." ADD `id` int(11) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_checkdatedetail')) {
 if(!pdo_fieldexists('wx_school_checkdatedetail',  'schoolid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checkdatedetail')." ADD `schoolid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_checkdatedetail')) {
 if(!pdo_fieldexists('wx_school_checkdatedetail',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checkdatedetail')." ADD `weid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_checkdatedetail')) {
 if(!pdo_fieldexists('wx_school_checkdatedetail',  'year')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checkdatedetail')." ADD `year` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_checkdatedetail')) {
 if(!pdo_fieldexists('wx_school_checkdatedetail',  'sum_start')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checkdatedetail')." ADD `sum_start` varchar(20) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_checkdatedetail')) {
 if(!pdo_fieldexists('wx_school_checkdatedetail',  'sum_end')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checkdatedetail')." ADD `sum_end` varchar(20) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_checkdatedetail')) {
 if(!pdo_fieldexists('wx_school_checkdatedetail',  'win_start')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checkdatedetail')." ADD `win_start` varchar(20) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_checkdatedetail')) {
 if(!pdo_fieldexists('wx_school_checkdatedetail',  'win_end')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checkdatedetail')." ADD `win_end` varchar(20) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_checkdatedetail')) {
 if(!pdo_fieldexists('wx_school_checkdatedetail',  'holiday')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checkdatedetail')." ADD `holiday` varchar(1000) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_checkdatedetail')) {
 if(!pdo_fieldexists('wx_school_checkdatedetail',  'checkdatesetid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checkdatedetail')." ADD `checkdatesetid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_checkdateset')) {
 if(!pdo_fieldexists('wx_school_checkdateset',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checkdateset')." ADD `id` int(11) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_checkdateset')) {
 if(!pdo_fieldexists('wx_school_checkdateset',  'schoolid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checkdateset')." ADD `schoolid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_checkdateset')) {
 if(!pdo_fieldexists('wx_school_checkdateset',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checkdateset')." ADD `weid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_checkdateset')) {
 if(!pdo_fieldexists('wx_school_checkdateset',  'name')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checkdateset')." ADD `name` varchar(500) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_checkdateset')) {
 if(!pdo_fieldexists('wx_school_checkdateset',  'friday')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checkdateset')." ADD `friday` tinyint(3) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_checkdateset')) {
 if(!pdo_fieldexists('wx_school_checkdateset',  'saturday')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checkdateset')." ADD `saturday` tinyint(3) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_checkdateset')) {
 if(!pdo_fieldexists('wx_school_checkdateset',  'sunday')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checkdateset')." ADD `sunday` tinyint(3) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_checkdateset')) {
 if(!pdo_fieldexists('wx_school_checkdateset',  'holiday')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checkdateset')." ADD `holiday` varchar(1000) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_checkdateset')) {
 if(!pdo_fieldexists('wx_school_checkdateset',  'bj_id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checkdateset')." ADD `bj_id` varchar(1000) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_checklog')) {
 if(!pdo_fieldexists('wx_school_checklog',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checklog')." ADD `id` int(10) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_checklog')) {
 if(!pdo_fieldexists('wx_school_checklog',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checklog')." ADD `weid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_checklog')) {
 if(!pdo_fieldexists('wx_school_checklog',  'schoolid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checklog')." ADD `schoolid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_checklog')) {
 if(!pdo_fieldexists('wx_school_checklog',  'macid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checklog')." ADD `macid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_checklog')) {
 if(!pdo_fieldexists('wx_school_checklog',  'cardid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checklog')." ADD `cardid` varchar(200) NOT NULL   COMMENT '卡号';");
 }
}
if(pdo_tableexists('wx_school_checklog')) {
 if(!pdo_fieldexists('wx_school_checklog',  'sid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checklog')." ADD `sid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_checklog')) {
 if(!pdo_fieldexists('wx_school_checklog',  'tid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checklog')." ADD `tid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_checklog')) {
 if(!pdo_fieldexists('wx_school_checklog',  'bj_id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checklog')." ADD `bj_id` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_checklog')) {
 if(!pdo_fieldexists('wx_school_checklog',  'lat')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checklog')." ADD `lat` decimal(18,10) NOT NULL DEFAULT NULL DEFAULT '0.0000000000'  COMMENT '经度';");
 }
}
if(pdo_tableexists('wx_school_checklog')) {
 if(!pdo_fieldexists('wx_school_checklog',  'lon')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checklog')." ADD `lon` decimal(18,10) NOT NULL DEFAULT NULL DEFAULT '0.0000000000'  COMMENT '纬度';");
 }
}
if(pdo_tableexists('wx_school_checklog')) {
 if(!pdo_fieldexists('wx_school_checklog',  'temperature')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checklog')." ADD `temperature` varchar(10);");
 }
}
if(pdo_tableexists('wx_school_checklog')) {
 if(!pdo_fieldexists('wx_school_checklog',  'pic')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checklog')." ADD `pic` varchar(255)    COMMENT '图片';");
 }
}
if(pdo_tableexists('wx_school_checklog')) {
 if(!pdo_fieldexists('wx_school_checklog',  'pic2')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checklog')." ADD `pic2` varchar(255)    COMMENT '图片2';");
 }
}
if(pdo_tableexists('wx_school_checklog')) {
 if(!pdo_fieldexists('wx_school_checklog',  'type')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checklog')." ADD `type` varchar(50)    COMMENT '进校类型';");
 }
}
if(pdo_tableexists('wx_school_checklog')) {
 if(!pdo_fieldexists('wx_school_checklog',  'leixing')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checklog')." ADD `leixing` tinyint(1) NOT NULL   COMMENT '1进校2离校3迟到4早退';");
 }
}
if(pdo_tableexists('wx_school_checklog')) {
 if(!pdo_fieldexists('wx_school_checklog',  'isread')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checklog')." ADD `isread` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1'  COMMENT '1已读2未读';");
 }
}
if(pdo_tableexists('wx_school_checklog')) {
 if(!pdo_fieldexists('wx_school_checklog',  'pard')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checklog')." ADD `pard` tinyint(2) NOT NULL   COMMENT '1本人2母亲3父亲4爷爷5奶奶6外公7外婆8叔叔9阿姨10其他11老师';");
 }
}
if(pdo_tableexists('wx_school_checklog')) {
 if(!pdo_fieldexists('wx_school_checklog',  'qdtid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checklog')." ADD `qdtid` int(11) NOT NULL   COMMENT '代签老师ID';");
 }
}
if(pdo_tableexists('wx_school_checklog')) {
 if(!pdo_fieldexists('wx_school_checklog',  'checktype')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checklog')." ADD `checktype` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1'  COMMENT '1刷卡2微信';");
 }
}
if(pdo_tableexists('wx_school_checklog')) {
 if(!pdo_fieldexists('wx_school_checklog',  'isconfirm')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checklog')." ADD `isconfirm` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1'  COMMENT '1确认2拒绝';");
 }
}
if(pdo_tableexists('wx_school_checklog')) {
 if(!pdo_fieldexists('wx_school_checklog',  'createtime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checklog')." ADD `createtime` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_checklog')) {
 if(!pdo_fieldexists('wx_school_checklog',  'sc_ap')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checklog')." ADD `sc_ap` tinyint(3) NOT NULL   COMMENT '0普通考勤1寝室考勤';");
 }
}
if(pdo_tableexists('wx_school_checklog')) {
 if(!pdo_fieldexists('wx_school_checklog',  'apid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checklog')." ADD `apid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_checklog')) {
 if(!pdo_fieldexists('wx_school_checklog',  'roomid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checklog')." ADD `roomid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_checklog')) {
 if(!pdo_fieldexists('wx_school_checklog',  'ap_type')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checklog')." ADD `ap_type` tinyint(3) NOT NULL   COMMENT '1进寝2离寝';");
 }
}
if(pdo_tableexists('wx_school_checkmac')) {
 if(!pdo_fieldexists('wx_school_checkmac',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checkmac')." ADD `id` int(10) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_checkmac')) {
 if(!pdo_fieldexists('wx_school_checkmac',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checkmac')." ADD `weid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_checkmac')) {
 if(!pdo_fieldexists('wx_school_checkmac',  'schoolid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checkmac')." ADD `schoolid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_checkmac')) {
 if(!pdo_fieldexists('wx_school_checkmac',  'macname')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checkmac')." ADD `macname` varchar(50) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_checkmac')) {
 if(!pdo_fieldexists('wx_school_checkmac',  'name')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checkmac')." ADD `name` varchar(50) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_checkmac')) {
 if(!pdo_fieldexists('wx_school_checkmac',  'macid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checkmac')." ADD `macid` varchar(200) NOT NULL   COMMENT '设备编号';");
 }
}
if(pdo_tableexists('wx_school_checkmac')) {
 if(!pdo_fieldexists('wx_school_checkmac',  'banner')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checkmac')." ADD `banner` varchar(2000);");
 }
}
if(pdo_tableexists('wx_school_checkmac')) {
 if(!pdo_fieldexists('wx_school_checkmac',  'macset')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checkmac')." ADD `macset` varchar(2000);");
 }
}
if(pdo_tableexists('wx_school_checkmac')) {
 if(!pdo_fieldexists('wx_school_checkmac',  'is_on')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checkmac')." ADD `is_on` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1'  COMMENT '1启用2不启用';");
 }
}
if(pdo_tableexists('wx_school_checkmac')) {
 if(!pdo_fieldexists('wx_school_checkmac',  'type')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checkmac')." ADD `type` tinyint(1) NOT NULL   COMMENT '1进校2离校';");
 }
}
if(pdo_tableexists('wx_school_checkmac')) {
 if(!pdo_fieldexists('wx_school_checkmac',  'createtime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checkmac')." ADD `createtime` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_checkmac')) {
 if(!pdo_fieldexists('wx_school_checkmac',  'twmac')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checkmac')." ADD `twmac` varchar(200) NOT NULL DEFAULT NULL DEFAULT '-1';");
 }
}
if(pdo_tableexists('wx_school_checkmac')) {
 if(!pdo_fieldexists('wx_school_checkmac',  'cardtype')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checkmac')." ADD `cardtype` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '2'  COMMENT '1IC2ID';");
 }
}
if(pdo_tableexists('wx_school_checkmac')) {
 if(!pdo_fieldexists('wx_school_checkmac',  'is_bobao')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checkmac')." ADD `is_bobao` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1'  COMMENT '播报';");
 }
}
if(pdo_tableexists('wx_school_checkmac')) {
 if(!pdo_fieldexists('wx_school_checkmac',  'is_master')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checkmac')." ADD `is_master` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1'  COMMENT '是否全校';");
 }
}
if(pdo_tableexists('wx_school_checkmac')) {
 if(!pdo_fieldexists('wx_school_checkmac',  'bj_id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checkmac')." ADD `bj_id` int(10)    COMMENT '绑定班级ID';");
 }
}
if(pdo_tableexists('wx_school_checkmac')) {
 if(!pdo_fieldexists('wx_school_checkmac',  'js_id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checkmac')." ADD `js_id` int(10)    COMMENT '教室ID';");
 }
}
if(pdo_tableexists('wx_school_checkmac')) {
 if(!pdo_fieldexists('wx_school_checkmac',  'areaid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checkmac')." ADD `areaid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_checkmac')) {
 if(!pdo_fieldexists('wx_school_checkmac',  'model_type')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checkmac')." ADD `model_type` tinyint(1) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_checkmac')) {
 if(!pdo_fieldexists('wx_school_checkmac',  'qh_id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checkmac')." ADD `qh_id` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_checkmac')) {
 if(!pdo_fieldexists('wx_school_checkmac',  'exam_plan')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checkmac')." ADD `exam_plan` varchar(1000) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_checkmac')) {
 if(!pdo_fieldexists('wx_school_checkmac',  'exam_room_name')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checkmac')." ADD `exam_room_name` varchar(200) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_checkmac')) {
 if(!pdo_fieldexists('wx_school_checkmac',  'cityname')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checkmac')." ADD `cityname` varchar(50) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_checkmac')) {
 if(!pdo_fieldexists('wx_school_checkmac',  'apid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checkmac')." ADD `apid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_checkmac')) {
 if(!pdo_fieldexists('wx_school_checkmac',  'stu1')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checkmac')." ADD `stu1` int(10);");
 }
}
if(pdo_tableexists('wx_school_checkmac')) {
 if(!pdo_fieldexists('wx_school_checkmac',  'stu2')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checkmac')." ADD `stu2` int(10);");
 }
}
if(pdo_tableexists('wx_school_checkmac')) {
 if(!pdo_fieldexists('wx_school_checkmac',  'stu3')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checkmac')." ADD `stu3` int(10);");
 }
}
if(pdo_tableexists('wx_school_checkmac')) {
 if(!pdo_fieldexists('wx_school_checkmac',  'lastedittime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checkmac')." ADD `lastedittime` int(11)    COMMENT '最近一次修改时间';");
 }
}
if(pdo_tableexists('wx_school_checkmac')) {
 if(!pdo_fieldexists('wx_school_checkmac',  'is_heartbeat')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checkmac')." ADD `is_heartbeat` tinyint(3)    COMMENT '是否接收心跳任务';");
 }
}
if(pdo_tableexists('wx_school_checkmac_remote')) {
 if(!pdo_fieldexists('wx_school_checkmac_remote',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checkmac_remote')." ADD `id` int(11) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_checkmac_remote')) {
 if(!pdo_fieldexists('wx_school_checkmac_remote',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checkmac_remote')." ADD `weid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_checkmac_remote')) {
 if(!pdo_fieldexists('wx_school_checkmac_remote',  'schoolid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checkmac_remote')." ADD `schoolid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_checkmac_remote')) {
 if(!pdo_fieldexists('wx_school_checkmac_remote',  'pid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checkmac_remote')." ADD `pid` varchar(100) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_checkmac_remote')) {
 if(!pdo_fieldexists('wx_school_checkmac_remote',  'deviceId')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checkmac_remote')." ADD `deviceId` varchar(100) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_checkmac_remote')) {
 if(!pdo_fieldexists('wx_school_checkmac_remote',  'passType')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checkmac_remote')." ADD `passType` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_checkmac_remote')) {
 if(!pdo_fieldexists('wx_school_checkmac_remote',  'passDeviceId')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checkmac_remote')." ADD `passDeviceId` varchar(255) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_checkmac_remote')) {
 if(!pdo_fieldexists('wx_school_checkmac_remote',  'cameras')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checkmac_remote')." ADD `cameras` varchar(255) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_checktimeset')) {
 if(!pdo_fieldexists('wx_school_checktimeset',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checktimeset')." ADD `id` int(11) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_checktimeset')) {
 if(!pdo_fieldexists('wx_school_checktimeset',  'schoolid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checktimeset')." ADD `schoolid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_checktimeset')) {
 if(!pdo_fieldexists('wx_school_checktimeset',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checktimeset')." ADD `weid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_checktimeset')) {
 if(!pdo_fieldexists('wx_school_checktimeset',  'start')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checktimeset')." ADD `start` varchar(20) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_checktimeset')) {
 if(!pdo_fieldexists('wx_school_checktimeset',  'end')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checktimeset')." ADD `end` varchar(20) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_checktimeset')) {
 if(!pdo_fieldexists('wx_school_checktimeset',  'type')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checktimeset')." ADD `type` tinyint(3) NOT NULL   COMMENT '1工作日2周五3周六4周日5特殊上6特殊休';");
 }
}
if(pdo_tableexists('wx_school_checktimeset')) {
 if(!pdo_fieldexists('wx_school_checktimeset',  'year')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checktimeset')." ADD `year` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_checktimeset')) {
 if(!pdo_fieldexists('wx_school_checktimeset',  'date')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checktimeset')." ADD `date` varchar(20) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_checktimeset')) {
 if(!pdo_fieldexists('wx_school_checktimeset',  'checkdatesetid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checktimeset')." ADD `checkdatesetid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_checktimeset')) {
 if(!pdo_fieldexists('wx_school_checktimeset',  'out_in')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checktimeset')." ADD `out_in` tinyint(1);");
 }
}
if(pdo_tableexists('wx_school_checktimeset')) {
 if(!pdo_fieldexists('wx_school_checktimeset',  's_type')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_checktimeset')." ADD `s_type` tinyint(1);");
 }
}
if(pdo_tableexists('wx_school_chongzhi')) {
 if(!pdo_fieldexists('wx_school_chongzhi',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_chongzhi')." ADD `id` int(11) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_chongzhi')) {
 if(!pdo_fieldexists('wx_school_chongzhi',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_chongzhi')." ADD `weid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_chongzhi')) {
 if(!pdo_fieldexists('wx_school_chongzhi',  'schoolid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_chongzhi')." ADD `schoolid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_chongzhi')) {
 if(!pdo_fieldexists('wx_school_chongzhi',  'cost')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_chongzhi')." ADD `cost` float() NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_chongzhi')) {
 if(!pdo_fieldexists('wx_school_chongzhi',  'chongzhi')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_chongzhi')." ADD `chongzhi` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_chongzhi')) {
 if(!pdo_fieldexists('wx_school_chongzhi',  'createtime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_chongzhi')." ADD `createtime` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_chongzhi')) {
 if(!pdo_fieldexists('wx_school_chongzhi',  'ssort')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_chongzhi')." ADD `ssort` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_classify')) {
 if(!pdo_fieldexists('wx_school_classify',  'sid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_classify')." ADD `sid` int(11) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_classify')) {
 if(!pdo_fieldexists('wx_school_classify',  'schoolid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_classify')." ADD `schoolid` int(10) NOT NULL   COMMENT '分校id';");
 }
}
if(pdo_tableexists('wx_school_classify')) {
 if(!pdo_fieldexists('wx_school_classify',  'sname')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_classify')." ADD `sname` varchar(50) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_classify')) {
 if(!pdo_fieldexists('wx_school_classify',  'pname')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_classify')." ADD `pname` varchar(50) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_classify')) {
 if(!pdo_fieldexists('wx_school_classify',  'ssort')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_classify')." ADD `ssort` int(5) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_classify')) {
 if(!pdo_fieldexists('wx_school_classify',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_classify')." ADD `weid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_classify')) {
 if(!pdo_fieldexists('wx_school_classify',  'type')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_classify')." ADD `type` char(20) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_classify')) {
 if(!pdo_fieldexists('wx_school_classify',  'carmeraid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_classify')." ADD `carmeraid` text()    COMMENT '画面ID组';");
 }
}
if(pdo_tableexists('wx_school_classify')) {
 if(!pdo_fieldexists('wx_school_classify',  'erwei')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_classify')." ADD `erwei` varchar(200) NOT NULL   COMMENT '群二维码';");
 }
}
if(pdo_tableexists('wx_school_classify')) {
 if(!pdo_fieldexists('wx_school_classify',  'qun')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_classify')." ADD `qun` varchar(200) NOT NULL   COMMENT 'QQ群链接';");
 }
}
if(pdo_tableexists('wx_school_classify')) {
 if(!pdo_fieldexists('wx_school_classify',  'video')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_classify')." ADD `video` varchar(1000) NOT NULL   COMMENT '教室监控地址';");
 }
}
if(pdo_tableexists('wx_school_classify')) {
 if(!pdo_fieldexists('wx_school_classify',  'video1')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_classify')." ADD `video1` varchar(1000) NOT NULL   COMMENT '教室监控地址1';");
 }
}
if(pdo_tableexists('wx_school_classify')) {
 if(!pdo_fieldexists('wx_school_classify',  'videostart')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_classify')." ADD `videostart` varchar(50) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_classify')) {
 if(!pdo_fieldexists('wx_school_classify',  'videoend')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_classify')." ADD `videoend` varchar(50) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_classify')) {
 if(!pdo_fieldexists('wx_school_classify',  'allowpy')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_classify')." ADD `allowpy` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1'  COMMENT '1允许2拒绝';");
 }
}
if(pdo_tableexists('wx_school_classify')) {
 if(!pdo_fieldexists('wx_school_classify',  'cost')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_classify')." ADD `cost` varchar(11) NOT NULL   COMMENT '报名费用';");
 }
}
if(pdo_tableexists('wx_school_classify')) {
 if(!pdo_fieldexists('wx_school_classify',  'videoclick')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_classify')." ADD `videoclick` varchar(11) NOT NULL   COMMENT '视频点击量';");
 }
}
if(pdo_tableexists('wx_school_classify')) {
 if(!pdo_fieldexists('wx_school_classify',  'tid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_classify')." ADD `tid` int(11) NOT NULL   COMMENT '班级主任userid';");
 }
}
if(pdo_tableexists('wx_school_classify')) {
 if(!pdo_fieldexists('wx_school_classify',  'parentid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_classify')." ADD `parentid` int(10) NOT NULL   COMMENT '上级分类ID,0为第一级';");
 }
}
if(pdo_tableexists('wx_school_classify')) {
 if(!pdo_fieldexists('wx_school_classify',  'icon')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_classify')." ADD `icon` varchar(500)    COMMENT '图标';");
 }
}
if(pdo_tableexists('wx_school_classify')) {
 if(!pdo_fieldexists('wx_school_classify',  'start')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_classify')." ADD `start` varchar(1000)    COMMENT '班级之星';");
 }
}
if(pdo_tableexists('wx_school_classify')) {
 if(!pdo_fieldexists('wx_school_classify',  'star')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_classify')." ADD `star` varchar(1000)    COMMENT '班级之星';");
 }
}
if(pdo_tableexists('wx_school_classify')) {
 if(!pdo_fieldexists('wx_school_classify',  'qh_bjlist')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_classify')." ADD `qh_bjlist` varchar(1000)    COMMENT '期号对应班级';");
 }
}
if(pdo_tableexists('wx_school_classify')) {
 if(!pdo_fieldexists('wx_school_classify',  'qhtype')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_classify')." ADD `qhtype` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1';");
 }
}
if(pdo_tableexists('wx_school_classify')) {
 if(!pdo_fieldexists('wx_school_classify',  'is_bjzx')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_classify')." ADD `is_bjzx` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1'  COMMENT '班级之星';");
 }
}
if(pdo_tableexists('wx_school_classify')) {
 if(!pdo_fieldexists('wx_school_classify',  'sd_start')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_classify')." ADD `sd_start` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_classify')) {
 if(!pdo_fieldexists('wx_school_classify',  'sd_end')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_classify')." ADD `sd_end` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_classify')) {
 if(!pdo_fieldexists('wx_school_classify',  'js_id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_classify')." ADD `js_id` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_classify')) {
 if(!pdo_fieldexists('wx_school_classify',  'is_over')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_classify')." ADD `is_over` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1';");
 }
}
if(pdo_tableexists('wx_school_classify')) {
 if(!pdo_fieldexists('wx_school_classify',  'datesetid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_classify')." ADD `datesetid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_classify')) {
 if(!pdo_fieldexists('wx_school_classify',  'class_device')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_classify')." ADD `class_device` varchar(100) NOT NULL   COMMENT '分班播报id';");
 }
}
if(pdo_tableexists('wx_school_classify')) {
 if(!pdo_fieldexists('wx_school_classify',  'is_print')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_classify')." ADD `is_print` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '2'  COMMENT '是否启用打印机';");
 }
}
if(pdo_tableexists('wx_school_classify')) {
 if(!pdo_fieldexists('wx_school_classify',  'printarr')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_classify')." ADD `printarr` varchar(100) NOT NULL   COMMENT '打印机';");
 }
}
if(pdo_tableexists('wx_school_classify')) {
 if(!pdo_fieldexists('wx_school_classify',  'tidarr')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_classify')." ADD `tidarr` varchar(500) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_classify')) {
 if(!pdo_fieldexists('wx_school_classify',  'fzid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_classify')." ADD `fzid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_classify')) {
 if(!pdo_fieldexists('wx_school_classify',  'is_review')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_classify')." ADD `is_review` tinyint(3) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_classify')) {
 if(!pdo_fieldexists('wx_school_classify',  'addedinfo')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_classify')." ADD `addedinfo` text() NOT NULL   COMMENT '附加设置信息-以后所有不索引的附加信息都在这里，不用再加字段';");
 }
}
if(pdo_tableexists('wx_school_classify')) {
 if(!pdo_fieldexists('wx_school_classify',  'lastedittime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_classify')." ADD `lastedittime` int(11)    COMMENT '最近一次修改时间';");
 }
}
if(pdo_tableexists('wx_school_classify')) {
 if(!pdo_fieldexists('wx_school_classify',  'checksendset')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_classify')." ADD `checksendset` text()    COMMENT '考勤记录推送对象';");
 }
}
if(pdo_tableexists('wx_school_cookbook')) {
 if(!pdo_fieldexists('wx_school_cookbook',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_cookbook')." ADD `id` int(11) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_cookbook')) {
 if(!pdo_fieldexists('wx_school_cookbook',  'schoolid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_cookbook')." ADD `schoolid` int(10) NOT NULL   COMMENT '分校id';");
 }
}
if(pdo_tableexists('wx_school_cookbook')) {
 if(!pdo_fieldexists('wx_school_cookbook',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_cookbook')." ADD `weid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_cookbook')) {
 if(!pdo_fieldexists('wx_school_cookbook',  'keyword')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_cookbook')." ADD `keyword` varchar(50) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_cookbook')) {
 if(!pdo_fieldexists('wx_school_cookbook',  'title')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_cookbook')." ADD `title` varchar(50) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_cookbook')) {
 if(!pdo_fieldexists('wx_school_cookbook',  'begintime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_cookbook')." ADD `begintime` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_cookbook')) {
 if(!pdo_fieldexists('wx_school_cookbook',  'endtime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_cookbook')." ADD `endtime` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_cookbook')) {
 if(!pdo_fieldexists('wx_school_cookbook',  'monday')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_cookbook')." ADD `monday` text() NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_cookbook')) {
 if(!pdo_fieldexists('wx_school_cookbook',  'tuesday')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_cookbook')." ADD `tuesday` text() NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_cookbook')) {
 if(!pdo_fieldexists('wx_school_cookbook',  'wednesday')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_cookbook')." ADD `wednesday` text() NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_cookbook')) {
 if(!pdo_fieldexists('wx_school_cookbook',  'thursday')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_cookbook')." ADD `thursday` text() NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_cookbook')) {
 if(!pdo_fieldexists('wx_school_cookbook',  'friday')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_cookbook')." ADD `friday` text() NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_cookbook')) {
 if(!pdo_fieldexists('wx_school_cookbook',  'saturday')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_cookbook')." ADD `saturday` text() NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_cookbook')) {
 if(!pdo_fieldexists('wx_school_cookbook',  'sunday')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_cookbook')." ADD `sunday` text() NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_cookbook')) {
 if(!pdo_fieldexists('wx_school_cookbook',  'ishow')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_cookbook')." ADD `ishow` int(1) NOT NULL DEFAULT NULL DEFAULT '1'  COMMENT '1:显示,2隐藏,默认1';");
 }
}
if(pdo_tableexists('wx_school_cookbook')) {
 if(!pdo_fieldexists('wx_school_cookbook',  'sort')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_cookbook')." ADD `sort` int(11) NOT NULL DEFAULT NULL DEFAULT '1';");
 }
}
if(pdo_tableexists('wx_school_cookbook')) {
 if(!pdo_fieldexists('wx_school_cookbook',  'type')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_cookbook')." ADD `type` varchar(15) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_cookbook')) {
 if(!pdo_fieldexists('wx_school_cookbook',  'headpic')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_cookbook')." ADD `headpic` varchar(200) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_cookbook')) {
 if(!pdo_fieldexists('wx_school_cookbook',  'infos')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_cookbook')." ADD `infos` varchar(500) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_cost')) {
 if(!pdo_fieldexists('wx_school_cost',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_cost')." ADD `id` int(10) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_cost')) {
 if(!pdo_fieldexists('wx_school_cost',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_cost')." ADD `weid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_cost')) {
 if(!pdo_fieldexists('wx_school_cost',  'schoolid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_cost')." ADD `schoolid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_cost')) {
 if(!pdo_fieldexists('wx_school_cost',  'cost')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_cost')." ADD `cost` decimal(18,2) NOT NULL DEFAULT NULL DEFAULT '0.00';");
 }
}
if(pdo_tableexists('wx_school_cost')) {
 if(!pdo_fieldexists('wx_school_cost',  'bj_id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_cost')." ADD `bj_id` text()    COMMENT '关联班级组';");
 }
}
if(pdo_tableexists('wx_school_cost')) {
 if(!pdo_fieldexists('wx_school_cost',  'name')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_cost')." ADD `name` varchar(100) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_cost')) {
 if(!pdo_fieldexists('wx_school_cost',  'icon')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_cost')." ADD `icon` varchar(255) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_cost')) {
 if(!pdo_fieldexists('wx_school_cost',  'description')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_cost')." ADD `description` text() NOT NULL   COMMENT '缴费说明';");
 }
}
if(pdo_tableexists('wx_school_cost')) {
 if(!pdo_fieldexists('wx_school_cost',  'about')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_cost')." ADD `about` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_cost')) {
 if(!pdo_fieldexists('wx_school_cost',  'displayorder')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_cost')." ADD `displayorder` tinyint(3) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_cost')) {
 if(!pdo_fieldexists('wx_school_cost',  'is_sys')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_cost')." ADD `is_sys` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1'  COMMENT '1关联缴费，2不关联';");
 }
}
if(pdo_tableexists('wx_school_cost')) {
 if(!pdo_fieldexists('wx_school_cost',  'is_time')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_cost')." ADD `is_time` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1'  COMMENT '1有时间限制，2不限制';");
 }
}
if(pdo_tableexists('wx_school_cost')) {
 if(!pdo_fieldexists('wx_school_cost',  'is_on')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_cost')." ADD `is_on` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1'  COMMENT '1启用，2不启用';");
 }
}
if(pdo_tableexists('wx_school_cost')) {
 if(!pdo_fieldexists('wx_school_cost',  'createtime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_cost')." ADD `createtime` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_cost')) {
 if(!pdo_fieldexists('wx_school_cost',  'starttime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_cost')." ADD `starttime` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_cost')) {
 if(!pdo_fieldexists('wx_school_cost',  'endtime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_cost')." ADD `endtime` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_cost')) {
 if(!pdo_fieldexists('wx_school_cost',  'dataline')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_cost')." ADD `dataline` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_cost')) {
 if(!pdo_fieldexists('wx_school_cost',  'payweid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_cost')." ADD `payweid` int(10) NOT NULL   COMMENT '支付公众号';");
 }
}
if(pdo_tableexists('wx_school_cost')) {
 if(!pdo_fieldexists('wx_school_cost',  'is_print')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_cost')." ADD `is_print` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '2'  COMMENT '是否启用打印机';");
 }
}
if(pdo_tableexists('wx_school_cost')) {
 if(!pdo_fieldexists('wx_school_cost',  'printarr')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_cost')." ADD `printarr` varchar(100) NOT NULL   COMMENT '打印机';");
 }
}
if(pdo_tableexists('wx_school_courseTable')) {
 if(!pdo_fieldexists('wx_school_courseTable',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_courseTable')." ADD `id` int(11) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_courseTable')) {
 if(!pdo_fieldexists('wx_school_courseTable',  'schoolid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_courseTable')." ADD `schoolid` int(10) NOT NULL   COMMENT '分校id';");
 }
}
if(pdo_tableexists('wx_school_courseTable')) {
 if(!pdo_fieldexists('wx_school_courseTable',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_courseTable')." ADD `weid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_courseTable')) {
 if(!pdo_fieldexists('wx_school_courseTable',  'title')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_courseTable')." ADD `title` varchar(50) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_courseTable')) {
 if(!pdo_fieldexists('wx_school_courseTable',  'ishow')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_courseTable')." ADD `ishow` int(1) NOT NULL DEFAULT NULL DEFAULT '1'  COMMENT '1:显示,2隐藏,默认1';");
 }
}
if(pdo_tableexists('wx_school_courseTable')) {
 if(!pdo_fieldexists('wx_school_courseTable',  'sort')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_courseTable')." ADD `sort` int(11) NOT NULL DEFAULT NULL DEFAULT '1';");
 }
}
if(pdo_tableexists('wx_school_courseTable')) {
 if(!pdo_fieldexists('wx_school_courseTable',  'type')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_courseTable')." ADD `type` varchar(15) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_courseTable')) {
 if(!pdo_fieldexists('wx_school_courseTable',  'headpic')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_courseTable')." ADD `headpic` varchar(200) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_courseTable')) {
 if(!pdo_fieldexists('wx_school_courseTable',  'infos')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_courseTable')." ADD `infos` varchar(500) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_courseTable')) {
 if(!pdo_fieldexists('wx_school_courseTable',  'xq_id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_courseTable')." ADD `xq_id` int(11) NOT NULL   COMMENT '学期id';");
 }
}
if(pdo_tableexists('wx_school_courseTable')) {
 if(!pdo_fieldexists('wx_school_courseTable',  'bj_id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_courseTable')." ADD `bj_id` int(11) NOT NULL   COMMENT '班级id';");
 }
}
if(pdo_tableexists('wx_school_coursebuy')) {
 if(!pdo_fieldexists('wx_school_coursebuy',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_coursebuy')." ADD `id` int(11) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_coursebuy')) {
 if(!pdo_fieldexists('wx_school_coursebuy',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_coursebuy')." ADD `weid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_coursebuy')) {
 if(!pdo_fieldexists('wx_school_coursebuy',  'schoolid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_coursebuy')." ADD `schoolid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_coursebuy')) {
 if(!pdo_fieldexists('wx_school_coursebuy',  'userid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_coursebuy')." ADD `userid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_coursebuy')) {
 if(!pdo_fieldexists('wx_school_coursebuy',  'sid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_coursebuy')." ADD `sid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_coursebuy')) {
 if(!pdo_fieldexists('wx_school_coursebuy',  'kcid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_coursebuy')." ADD `kcid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_coursebuy')) {
 if(!pdo_fieldexists('wx_school_coursebuy',  'ksnum')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_coursebuy')." ADD `ksnum` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_coursebuy')) {
 if(!pdo_fieldexists('wx_school_coursebuy',  'createtime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_coursebuy')." ADD `createtime` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_courseorder')) {
 if(!pdo_fieldexists('wx_school_courseorder',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_courseorder')." ADD `id` int(11) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_courseorder')) {
 if(!pdo_fieldexists('wx_school_courseorder',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_courseorder')." ADD `weid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_courseorder')) {
 if(!pdo_fieldexists('wx_school_courseorder',  'schoolid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_courseorder')." ADD `schoolid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_courseorder')) {
 if(!pdo_fieldexists('wx_school_courseorder',  'kcid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_courseorder')." ADD `kcid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_courseorder')) {
 if(!pdo_fieldexists('wx_school_courseorder',  'name')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_courseorder')." ADD `name` varchar(50) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_courseorder')) {
 if(!pdo_fieldexists('wx_school_courseorder',  'tel')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_courseorder')." ADD `tel` varchar(30) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_courseorder')) {
 if(!pdo_fieldexists('wx_school_courseorder',  'beizhu')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_courseorder')." ADD `beizhu` varchar(200) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_courseorder')) {
 if(!pdo_fieldexists('wx_school_courseorder',  'tid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_courseorder')." ADD `tid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_courseorder')) {
 if(!pdo_fieldexists('wx_school_courseorder',  'createtime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_courseorder')." ADD `createtime` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_courseorder')) {
 if(!pdo_fieldexists('wx_school_courseorder',  'type')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_courseorder')." ADD `type` int(3) NOT NULL   COMMENT '类型，0为预约';");
 }
}
if(pdo_tableexists('wx_school_courseorder')) {
 if(!pdo_fieldexists('wx_school_courseorder',  'totid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_courseorder')." ADD `totid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_courseorder')) {
 if(!pdo_fieldexists('wx_school_courseorder',  'fromuserid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_courseorder')." ADD `fromuserid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_courseorder')) {
 if(!pdo_fieldexists('wx_school_courseorder',  'huifu')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_courseorder')." ADD `huifu` varchar(500) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_coursetable')) {
 if(!pdo_fieldexists('wx_school_coursetable',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_coursetable')." ADD `id` int(11) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_coursetable')) {
 if(!pdo_fieldexists('wx_school_coursetable',  'schoolid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_coursetable')." ADD `schoolid` int(10) NOT NULL   COMMENT '分校id';");
 }
}
if(pdo_tableexists('wx_school_coursetable')) {
 if(!pdo_fieldexists('wx_school_coursetable',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_coursetable')." ADD `weid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_coursetable')) {
 if(!pdo_fieldexists('wx_school_coursetable',  'title')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_coursetable')." ADD `title` varchar(50) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_coursetable')) {
 if(!pdo_fieldexists('wx_school_coursetable',  'ishow')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_coursetable')." ADD `ishow` int(1) NOT NULL DEFAULT NULL DEFAULT '1'  COMMENT '1:显示,2隐藏,默认1';");
 }
}
if(pdo_tableexists('wx_school_coursetable')) {
 if(!pdo_fieldexists('wx_school_coursetable',  'sort')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_coursetable')." ADD `sort` int(11) NOT NULL DEFAULT NULL DEFAULT '1';");
 }
}
if(pdo_tableexists('wx_school_coursetable')) {
 if(!pdo_fieldexists('wx_school_coursetable',  'type')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_coursetable')." ADD `type` varchar(15) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_coursetable')) {
 if(!pdo_fieldexists('wx_school_coursetable',  'headpic')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_coursetable')." ADD `headpic` varchar(200) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_coursetable')) {
 if(!pdo_fieldexists('wx_school_coursetable',  'infos')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_coursetable')." ADD `infos` varchar(500) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_coursetable')) {
 if(!pdo_fieldexists('wx_school_coursetable',  'xq_id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_coursetable')." ADD `xq_id` int(11) NOT NULL   COMMENT '学期id';");
 }
}
if(pdo_tableexists('wx_school_coursetable')) {
 if(!pdo_fieldexists('wx_school_coursetable',  'bj_id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_coursetable')." ADD `bj_id` int(11) NOT NULL   COMMENT '班级id';");
 }
}
if(pdo_tableexists('wx_school_cyybeizhu_teacher')) {
 if(!pdo_fieldexists('wx_school_cyybeizhu_teacher',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_cyybeizhu_teacher')." ADD `id` int(11) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_cyybeizhu_teacher')) {
 if(!pdo_fieldexists('wx_school_cyybeizhu_teacher',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_cyybeizhu_teacher')." ADD `weid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_cyybeizhu_teacher')) {
 if(!pdo_fieldexists('wx_school_cyybeizhu_teacher',  'schoolid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_cyybeizhu_teacher')." ADD `schoolid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_cyybeizhu_teacher')) {
 if(!pdo_fieldexists('wx_school_cyybeizhu_teacher',  'tid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_cyybeizhu_teacher')." ADD `tid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_cyybeizhu_teacher')) {
 if(!pdo_fieldexists('wx_school_cyybeizhu_teacher',  'beizhu')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_cyybeizhu_teacher')." ADD `beizhu` varchar(200) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_cyybeizhu_teacher')) {
 if(!pdo_fieldexists('wx_school_cyybeizhu_teacher',  'cyyid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_cyybeizhu_teacher')." ADD `cyyid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_cyybeizhu_teacher')) {
 if(!pdo_fieldexists('wx_school_cyybeizhu_teacher',  'createtime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_cyybeizhu_teacher')." ADD `createtime` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_dianzan')) {
 if(!pdo_fieldexists('wx_school_dianzan',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_dianzan')." ADD `id` int(10) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_dianzan')) {
 if(!pdo_fieldexists('wx_school_dianzan',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_dianzan')." ADD `weid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_dianzan')) {
 if(!pdo_fieldexists('wx_school_dianzan',  'schoolid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_dianzan')." ADD `schoolid` int(10) NOT NULL   COMMENT '学校ID';");
 }
}
if(pdo_tableexists('wx_school_dianzan')) {
 if(!pdo_fieldexists('wx_school_dianzan',  'uid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_dianzan')." ADD `uid` int(10) NOT NULL   COMMENT '发布者UID';");
 }
}
if(pdo_tableexists('wx_school_dianzan')) {
 if(!pdo_fieldexists('wx_school_dianzan',  'sherid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_dianzan')." ADD `sherid` int(10) NOT NULL   COMMENT '所属图文id';");
 }
}
if(pdo_tableexists('wx_school_dianzan')) {
 if(!pdo_fieldexists('wx_school_dianzan',  'zname')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_dianzan')." ADD `zname` varchar(50)    COMMENT '点赞人名字';");
 }
}
if(pdo_tableexists('wx_school_dianzan')) {
 if(!pdo_fieldexists('wx_school_dianzan',  'order')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_dianzan')." ADD `order` int(10) NOT NULL   COMMENT '排序';");
 }
}
if(pdo_tableexists('wx_school_dianzan')) {
 if(!pdo_fieldexists('wx_school_dianzan',  'createtime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_dianzan')." ADD `createtime` int(10) NOT NULL   COMMENT '创建时间';");
 }
}
if(pdo_tableexists('wx_school_dianzan')) {
 if(!pdo_fieldexists('wx_school_dianzan',  'hmxianlaohu')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_dianzan')." ADD `hmxianlaohu` varchar(30) NOT NULL   COMMENT '图片路径';");
 }
}
if(pdo_tableexists('wx_school_dianzan')) {
 if(!pdo_fieldexists('wx_school_dianzan',  'userid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_dianzan')." ADD `userid` int(10)    COMMENT 'userid';");
 }
}
if(pdo_tableexists('wx_school_email')) {
 if(!pdo_fieldexists('wx_school_email',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_email')." ADD `id` int(10) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_email')) {
 if(!pdo_fieldexists('wx_school_email',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_email')." ADD `weid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_email')) {
 if(!pdo_fieldexists('wx_school_email',  'schoolid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_email')." ADD `schoolid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_email')) {
 if(!pdo_fieldexists('wx_school_email',  'sid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_email')." ADD `sid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_email')) {
 if(!pdo_fieldexists('wx_school_email',  'userid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_email')." ADD `userid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_email')) {
 if(!pdo_fieldexists('wx_school_email',  'bj_id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_email')." ADD `bj_id` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_email')) {
 if(!pdo_fieldexists('wx_school_email',  'pard')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_email')." ADD `pard` tinyint(1) NOT NULL   COMMENT '1本人2母亲3父亲4爷爷5奶奶6外公7外婆8叔叔9阿姨10其他';");
 }
}
if(pdo_tableexists('wx_school_email')) {
 if(!pdo_fieldexists('wx_school_email',  'suggesd')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_email')." ADD `suggesd` varchar(1000);");
 }
}
if(pdo_tableexists('wx_school_email')) {
 if(!pdo_fieldexists('wx_school_email',  'emailid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_email')." ADD `emailid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_email')) {
 if(!pdo_fieldexists('wx_school_email',  'isread')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_email')." ADD `isread` tinyint(1) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_email')) {
 if(!pdo_fieldexists('wx_school_email',  'is_how')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_email')." ADD `is_how` tinyint(1) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_email')) {
 if(!pdo_fieldexists('wx_school_email',  'ssort')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_email')." ADD `ssort` int(10) NOT NULL   COMMENT '排序';");
 }
}
if(pdo_tableexists('wx_school_email')) {
 if(!pdo_fieldexists('wx_school_email',  'createtime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_email')." ADD `createtime` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_fans_group')) {
 if(!pdo_fieldexists('wx_school_fans_group',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_fans_group')." ADD `id` int(10) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_fans_group')) {
 if(!pdo_fieldexists('wx_school_fans_group',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_fans_group')." ADD `weid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_fans_group')) {
 if(!pdo_fieldexists('wx_school_fans_group',  'schoolid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_fans_group')." ADD `schoolid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_fans_group')) {
 if(!pdo_fieldexists('wx_school_fans_group',  'count')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_fans_group')." ADD `count` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_fans_group')) {
 if(!pdo_fieldexists('wx_school_fans_group',  'group_id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_fans_group')." ADD `group_id` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_fans_group')) {
 if(!pdo_fieldexists('wx_school_fans_group',  'name')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_fans_group')." ADD `name` varchar(50) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_fans_group')) {
 if(!pdo_fieldexists('wx_school_fans_group',  'group_desc')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_fans_group')." ADD `group_desc` varchar(50) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_fans_group')) {
 if(!pdo_fieldexists('wx_school_fans_group',  'ssort')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_fans_group')." ADD `ssort` int(10) NOT NULL   COMMENT '排序';");
 }
}
if(pdo_tableexists('wx_school_fans_group')) {
 if(!pdo_fieldexists('wx_school_fans_group',  'type')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_fans_group')." ADD `type` int(1) NOT NULL   COMMENT '二维码状态';");
 }
}
if(pdo_tableexists('wx_school_fans_group')) {
 if(!pdo_fieldexists('wx_school_fans_group',  'createtime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_fans_group')." ADD `createtime` int(10) NOT NULL   COMMENT '生成时间';");
 }
}
if(pdo_tableexists('wx_school_fans_group')) {
 if(!pdo_fieldexists('wx_school_fans_group',  'is_zhu')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_fans_group')." ADD `is_zhu` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '2'  COMMENT '是否本校主二维码';");
 }
}
if(pdo_tableexists('wx_school_fzqx')) {
 if(!pdo_fieldexists('wx_school_fzqx',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_fzqx')." ADD `id` int(11) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_fzqx')) {
 if(!pdo_fieldexists('wx_school_fzqx',  'schoolid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_fzqx')." ADD `schoolid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_fzqx')) {
 if(!pdo_fieldexists('wx_school_fzqx',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_fzqx')." ADD `weid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_fzqx')) {
 if(!pdo_fieldexists('wx_school_fzqx',  'fzid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_fzqx')." ADD `fzid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_fzqx')) {
 if(!pdo_fieldexists('wx_school_fzqx',  'qxid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_fzqx')." ADD `qxid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_fzqx')) {
 if(!pdo_fieldexists('wx_school_fzqx',  'type')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_fzqx')." ADD `type` int(3) NOT NULL   COMMENT '1后台2前端';");
 }
}
if(pdo_tableexists('wx_school_gkkpj')) {
 if(!pdo_fieldexists('wx_school_gkkpj',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_gkkpj')." ADD `id` int(11) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_gkkpj')) {
 if(!pdo_fieldexists('wx_school_gkkpj',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_gkkpj')." ADD `weid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_gkkpj')) {
 if(!pdo_fieldexists('wx_school_gkkpj',  'schoolid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_gkkpj')." ADD `schoolid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_gkkpj')) {
 if(!pdo_fieldexists('wx_school_gkkpj',  'gkkid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_gkkpj')." ADD `gkkid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_gkkpj')) {
 if(!pdo_fieldexists('wx_school_gkkpj',  'tid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_gkkpj')." ADD `tid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_gkkpj')) {
 if(!pdo_fieldexists('wx_school_gkkpj',  'userid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_gkkpj')." ADD `userid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_gkkpj')) {
 if(!pdo_fieldexists('wx_school_gkkpj',  'iconid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_gkkpj')." ADD `iconid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_gkkpj')) {
 if(!pdo_fieldexists('wx_school_gkkpj',  'iconlevel')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_gkkpj')." ADD `iconlevel` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_gkkpj')) {
 if(!pdo_fieldexists('wx_school_gkkpj',  'content')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_gkkpj')." ADD `content` varchar(1000) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_gkkpj')) {
 if(!pdo_fieldexists('wx_school_gkkpj',  'torjz')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_gkkpj')." ADD `torjz` int(1) NOT NULL   COMMENT '来自老师2还是家长1';");
 }
}
if(pdo_tableexists('wx_school_gkkpj')) {
 if(!pdo_fieldexists('wx_school_gkkpj',  'createtime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_gkkpj')." ADD `createtime` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_gkkpj')) {
 if(!pdo_fieldexists('wx_school_gkkpj',  'type')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_gkkpj')." ADD `type` int(1) NOT NULL   COMMENT '评语1还是等级2';");
 }
}
if(pdo_tableexists('wx_school_gkkpjbz')) {
 if(!pdo_fieldexists('wx_school_gkkpjbz',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_gkkpjbz')." ADD `id` int(11) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_gkkpjbz')) {
 if(!pdo_fieldexists('wx_school_gkkpjbz',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_gkkpjbz')." ADD `weid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_gkkpjbz')) {
 if(!pdo_fieldexists('wx_school_gkkpjbz',  'schoolid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_gkkpjbz')." ADD `schoolid` int(1) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_gkkpjbz')) {
 if(!pdo_fieldexists('wx_school_gkkpjbz',  'title')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_gkkpjbz')." ADD `title` varchar(50) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_gkkpjbz')) {
 if(!pdo_fieldexists('wx_school_gkkpjbz',  'ssort')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_gkkpjbz')." ADD `ssort` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_gkkpjk')) {
 if(!pdo_fieldexists('wx_school_gkkpjk',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_gkkpjk')." ADD `id` int(10) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_gkkpjk')) {
 if(!pdo_fieldexists('wx_school_gkkpjk',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_gkkpjk')." ADD `weid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_gkkpjk')) {
 if(!pdo_fieldexists('wx_school_gkkpjk',  'schoolid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_gkkpjk')." ADD `schoolid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_gkkpjk')) {
 if(!pdo_fieldexists('wx_school_gkkpjk',  'bzid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_gkkpjk')." ADD `bzid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_gkkpjk')) {
 if(!pdo_fieldexists('wx_school_gkkpjk',  'title')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_gkkpjk')." ADD `title` varchar(300) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_gkkpjk')) {
 if(!pdo_fieldexists('wx_school_gkkpjk',  'icon1title')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_gkkpjk')." ADD `icon1title` varchar(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_gkkpjk')) {
 if(!pdo_fieldexists('wx_school_gkkpjk',  'icon2title')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_gkkpjk')." ADD `icon2title` varchar(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_gkkpjk')) {
 if(!pdo_fieldexists('wx_school_gkkpjk',  'icon3title')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_gkkpjk')." ADD `icon3title` varchar(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_gkkpjk')) {
 if(!pdo_fieldexists('wx_school_gkkpjk',  'icon4title')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_gkkpjk')." ADD `icon4title` varchar(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_gkkpjk')) {
 if(!pdo_fieldexists('wx_school_gkkpjk',  'icon5title')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_gkkpjk')." ADD `icon5title` varchar(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_gkkpjk')) {
 if(!pdo_fieldexists('wx_school_gkkpjk',  'icon1')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_gkkpjk')." ADD `icon1` varchar(1000) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_gkkpjk')) {
 if(!pdo_fieldexists('wx_school_gkkpjk',  'icon2')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_gkkpjk')." ADD `icon2` varchar(1000) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_gkkpjk')) {
 if(!pdo_fieldexists('wx_school_gkkpjk',  'icon3')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_gkkpjk')." ADD `icon3` varchar(1000) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_gkkpjk')) {
 if(!pdo_fieldexists('wx_school_gkkpjk',  'icon4')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_gkkpjk')." ADD `icon4` varchar(1000) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_gkkpjk')) {
 if(!pdo_fieldexists('wx_school_gkkpjk',  'icon5')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_gkkpjk')." ADD `icon5` varchar(1000) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_gkkpjk')) {
 if(!pdo_fieldexists('wx_school_gkkpjk',  'type')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_gkkpjk')." ADD `type` int(1) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_gkkpjk')) {
 if(!pdo_fieldexists('wx_school_gkkpjk',  'ssort')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_gkkpjk')." ADD `ssort` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_gongkaike')) {
 if(!pdo_fieldexists('wx_school_gongkaike',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_gongkaike')." ADD `id` int(11) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_gongkaike')) {
 if(!pdo_fieldexists('wx_school_gongkaike',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_gongkaike')." ADD `weid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_gongkaike')) {
 if(!pdo_fieldexists('wx_school_gongkaike',  'schoolid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_gongkaike')." ADD `schoolid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_gongkaike')) {
 if(!pdo_fieldexists('wx_school_gongkaike',  'ssort')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_gongkaike')." ADD `ssort` int(3) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_gongkaike')) {
 if(!pdo_fieldexists('wx_school_gongkaike',  'bzid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_gongkaike')." ADD `bzid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_gongkaike')) {
 if(!pdo_fieldexists('wx_school_gongkaike',  'tid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_gongkaike')." ADD `tid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_gongkaike')) {
 if(!pdo_fieldexists('wx_school_gongkaike',  'name')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_gongkaike')." ADD `name` varchar(100) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_gongkaike')) {
 if(!pdo_fieldexists('wx_school_gongkaike',  'starttime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_gongkaike')." ADD `starttime` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_gongkaike')) {
 if(!pdo_fieldexists('wx_school_gongkaike',  'endtime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_gongkaike')." ADD `endtime` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_gongkaike')) {
 if(!pdo_fieldexists('wx_school_gongkaike',  'addr')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_gongkaike')." ADD `addr` varchar(100) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_gongkaike')) {
 if(!pdo_fieldexists('wx_school_gongkaike',  'km_id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_gongkaike')." ADD `km_id` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_gongkaike')) {
 if(!pdo_fieldexists('wx_school_gongkaike',  'bj_id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_gongkaike')." ADD `bj_id` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_gongkaike')) {
 if(!pdo_fieldexists('wx_school_gongkaike',  'dagang')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_gongkaike')." ADD `dagang` text() NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_gongkaike')) {
 if(!pdo_fieldexists('wx_school_gongkaike',  'ticket')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_gongkaike')." ADD `ticket` varchar(255) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_gongkaike')) {
 if(!pdo_fieldexists('wx_school_gongkaike',  'qrid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_gongkaike')." ADD `qrid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_gongkaike')) {
 if(!pdo_fieldexists('wx_school_gongkaike',  'xq_id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_gongkaike')." ADD `xq_id` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_gongkaike')) {
 if(!pdo_fieldexists('wx_school_gongkaike',  'is_pj')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_gongkaike')." ADD `is_pj` int(1) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_gongkaike')) {
 if(!pdo_fieldexists('wx_school_gongkaike',  'createtime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_gongkaike')." ADD `createtime` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_gongkaike')) {
 if(!pdo_fieldexists('wx_school_gongkaike',  'createtid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_gongkaike')." ADD `createtid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_groupactivity')) {
 if(!pdo_fieldexists('wx_school_groupactivity',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_groupactivity')." ADD `id` int(11) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_groupactivity')) {
 if(!pdo_fieldexists('wx_school_groupactivity',  'schoolid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_groupactivity')." ADD `schoolid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_groupactivity')) {
 if(!pdo_fieldexists('wx_school_groupactivity',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_groupactivity')." ADD `weid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_groupactivity')) {
 if(!pdo_fieldexists('wx_school_groupactivity',  'title')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_groupactivity')." ADD `title` varchar(200) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_groupactivity')) {
 if(!pdo_fieldexists('wx_school_groupactivity',  'thumb')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_groupactivity')." ADD `thumb` varchar(500) NOT NULL   COMMENT '缩略图';");
 }
}
if(pdo_tableexists('wx_school_groupactivity')) {
 if(!pdo_fieldexists('wx_school_groupactivity',  'banner')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_groupactivity')." ADD `banner` varchar(2000) NOT NULL   COMMENT '幻灯片';");
 }
}
if(pdo_tableexists('wx_school_groupactivity')) {
 if(!pdo_fieldexists('wx_school_groupactivity',  'content')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_groupactivity')." ADD `content` varchar(2000) NOT NULL   COMMENT '活动描述';");
 }
}
if(pdo_tableexists('wx_school_groupactivity')) {
 if(!pdo_fieldexists('wx_school_groupactivity',  'bjarray')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_groupactivity')." ADD `bjarray` varchar(1000) NOT NULL   COMMENT '班级组';");
 }
}
if(pdo_tableexists('wx_school_groupactivity')) {
 if(!pdo_fieldexists('wx_school_groupactivity',  'cost')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_groupactivity')." ADD `cost` float() NOT NULL   COMMENT '报名费';");
 }
}
if(pdo_tableexists('wx_school_groupactivity')) {
 if(!pdo_fieldexists('wx_school_groupactivity',  'starttime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_groupactivity')." ADD `starttime` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_groupactivity')) {
 if(!pdo_fieldexists('wx_school_groupactivity',  'endtime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_groupactivity')." ADD `endtime` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_groupactivity')) {
 if(!pdo_fieldexists('wx_school_groupactivity',  'type')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_groupactivity')." ADD `type` int(3) NOT NULL   COMMENT '1活动2家政3家教';");
 }
}
if(pdo_tableexists('wx_school_groupactivity')) {
 if(!pdo_fieldexists('wx_school_groupactivity',  'ssort')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_groupactivity')." ADD `ssort` int(3) NOT NULL   COMMENT '排序';");
 }
}
if(pdo_tableexists('wx_school_groupactivity')) {
 if(!pdo_fieldexists('wx_school_groupactivity',  'createtime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_groupactivity')." ADD `createtime` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_groupactivity')) {
 if(!pdo_fieldexists('wx_school_groupactivity',  'isall')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_groupactivity')." ADD `isall` int(2) NOT NULL   COMMENT '是否全校可报';");
 }
}
if(pdo_tableexists('wx_school_groupsign')) {
 if(!pdo_fieldexists('wx_school_groupsign',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_groupsign')." ADD `id` int(11) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_groupsign')) {
 if(!pdo_fieldexists('wx_school_groupsign',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_groupsign')." ADD `weid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_groupsign')) {
 if(!pdo_fieldexists('wx_school_groupsign',  'schoolid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_groupsign')." ADD `schoolid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_groupsign')) {
 if(!pdo_fieldexists('wx_school_groupsign',  'userid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_groupsign')." ADD `userid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_groupsign')) {
 if(!pdo_fieldexists('wx_school_groupsign',  'gaid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_groupsign')." ADD `gaid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_groupsign')) {
 if(!pdo_fieldexists('wx_school_groupsign',  'type')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_groupsign')." ADD `type` int(3) NOT NULL   COMMENT '1集体活动2家政3家教';");
 }
}
if(pdo_tableexists('wx_school_groupsign')) {
 if(!pdo_fieldexists('wx_school_groupsign',  'createtime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_groupsign')." ADD `createtime` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_groupsign')) {
 if(!pdo_fieldexists('wx_school_groupsign',  'servetime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_groupsign')." ADD `servetime` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_groupsign')) {
 if(!pdo_fieldexists('wx_school_groupsign',  'sid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_groupsign')." ADD `sid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_helps')) {
 if(!pdo_fieldexists('wx_school_helps',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_helps')." ADD `id` int(10) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_helps')) {
 if(!pdo_fieldexists('wx_school_helps',  'schoolid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_helps')." ADD `schoolid` varchar(50) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_helps')) {
 if(!pdo_fieldexists('wx_school_helps',  'title')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_helps')." ADD `title` varchar(100) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_helps')) {
 if(!pdo_fieldexists('wx_school_helps',  'author')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_helps')." ADD `author` varchar(50) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_helps')) {
 if(!pdo_fieldexists('wx_school_helps',  'content')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_helps')." ADD `content` mediumtext() NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_helps')) {
 if(!pdo_fieldexists('wx_school_helps',  'createtime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_helps')." ADD `createtime` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_helps')) {
 if(!pdo_fieldexists('wx_school_helps',  'lasttime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_helps')." ADD `lasttime` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_helps')) {
 if(!pdo_fieldexists('wx_school_helps',  'click')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_helps')." ADD `click` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_helps')) {
 if(!pdo_fieldexists('wx_school_helps',  'is_share')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_helps')." ADD `is_share` tinyint(1) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_helps')) {
 if(!pdo_fieldexists('wx_school_helps',  'share_id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_helps')." ADD `share_id` tinyint(1) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_helps')) {
 if(!pdo_fieldexists('wx_school_helps',  'type')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_helps')." ADD `type` int(10) NOT NULL   COMMENT '分类';");
 }
}
if(pdo_tableexists('wx_school_helps')) {
 if(!pdo_fieldexists('wx_school_helps',  'displayorder')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_helps')." ADD `displayorder` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_helps')) {
 if(!pdo_fieldexists('wx_school_helps',  'could_id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_helps')." ADD `could_id` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_icon')) {
 if(!pdo_fieldexists('wx_school_icon',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_icon')." ADD `id` int(10) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_icon')) {
 if(!pdo_fieldexists('wx_school_icon',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_icon')." ADD `weid` int(10) NOT NULL   COMMENT '公众号';");
 }
}
if(pdo_tableexists('wx_school_icon')) {
 if(!pdo_fieldexists('wx_school_icon',  'schoolid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_icon')." ADD `schoolid` int(10) NOT NULL   COMMENT '分校id';");
 }
}
if(pdo_tableexists('wx_school_icon')) {
 if(!pdo_fieldexists('wx_school_icon',  'name')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_icon')." ADD `name` varchar(50) NOT NULL   COMMENT '按钮名称';");
 }
}
if(pdo_tableexists('wx_school_icon')) {
 if(!pdo_fieldexists('wx_school_icon',  'icon')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_icon')." ADD `icon` varchar(1000) NOT NULL   COMMENT '按钮图标';");
 }
}
if(pdo_tableexists('wx_school_icon')) {
 if(!pdo_fieldexists('wx_school_icon',  'icon2')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_icon')." ADD `icon2` varchar(1000) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_icon')) {
 if(!pdo_fieldexists('wx_school_icon',  'beizhu')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_icon')." ADD `beizhu` varchar(50) NOT NULL   COMMENT '备注或小字';");
 }
}
if(pdo_tableexists('wx_school_icon')) {
 if(!pdo_fieldexists('wx_school_icon',  'color')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_icon')." ADD `color` varchar(50) NOT NULL   COMMENT '颜色';");
 }
}
if(pdo_tableexists('wx_school_icon')) {
 if(!pdo_fieldexists('wx_school_icon',  'url')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_icon')." ADD `url` varchar(1000) NOT NULL   COMMENT '链接url';");
 }
}
if(pdo_tableexists('wx_school_icon')) {
 if(!pdo_fieldexists('wx_school_icon',  'do')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_icon')." ADD `do` varchar(100) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_icon')) {
 if(!pdo_fieldexists('wx_school_icon',  'place')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_icon')." ADD `place` tinyint(1) NOT NULL   COMMENT '1首页菜单2底部菜单';");
 }
}
if(pdo_tableexists('wx_school_icon')) {
 if(!pdo_fieldexists('wx_school_icon',  'ssort')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_icon')." ADD `ssort` tinyint(3) NOT NULL   COMMENT '排序';");
 }
}
if(pdo_tableexists('wx_school_icon')) {
 if(!pdo_fieldexists('wx_school_icon',  'status')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_icon')." ADD `status` tinyint(1) NOT NULL   COMMENT '显示状态';");
 }
}
if(pdo_tableexists('wx_school_idcard')) {
 if(!pdo_fieldexists('wx_school_idcard',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_idcard')." ADD `id` int(10) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_idcard')) {
 if(!pdo_fieldexists('wx_school_idcard',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_idcard')." ADD `weid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_idcard')) {
 if(!pdo_fieldexists('wx_school_idcard',  'schoolid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_idcard')." ADD `schoolid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_idcard')) {
 if(!pdo_fieldexists('wx_school_idcard',  'sid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_idcard')." ADD `sid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_idcard')) {
 if(!pdo_fieldexists('wx_school_idcard',  'tid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_idcard')." ADD `tid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_idcard')) {
 if(!pdo_fieldexists('wx_school_idcard',  'pname')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_idcard')." ADD `pname` varchar(200) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_idcard')) {
 if(!pdo_fieldexists('wx_school_idcard',  'bj_id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_idcard')." ADD `bj_id` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_idcard')) {
 if(!pdo_fieldexists('wx_school_idcard',  'idcard')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_idcard')." ADD `idcard` varchar(200) NOT NULL   COMMENT '卡号';");
 }
}
if(pdo_tableexists('wx_school_idcard')) {
 if(!pdo_fieldexists('wx_school_idcard',  'orderid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_idcard')." ADD `orderid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_idcard')) {
 if(!pdo_fieldexists('wx_school_idcard',  'spic')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_idcard')." ADD `spic` varchar(1000) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_idcard')) {
 if(!pdo_fieldexists('wx_school_idcard',  'tpic')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_idcard')." ADD `tpic` varchar(1000) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_idcard')) {
 if(!pdo_fieldexists('wx_school_idcard',  'pard')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_idcard')." ADD `pard` tinyint(1) NOT NULL   COMMENT '1本人2母亲3父亲4爷爷5奶奶6外公7外婆8叔叔9阿姨10其他';");
 }
}
if(pdo_tableexists('wx_school_idcard')) {
 if(!pdo_fieldexists('wx_school_idcard',  'createtime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_idcard')." ADD `createtime` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_idcard')) {
 if(!pdo_fieldexists('wx_school_idcard',  'severend')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_idcard')." ADD `severend` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_idcard')) {
 if(!pdo_fieldexists('wx_school_idcard',  'is_on')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_idcard')." ADD `is_on` int(1) NOT NULL   COMMENT '1:使用,2未用,默认0';");
 }
}
if(pdo_tableexists('wx_school_idcard')) {
 if(!pdo_fieldexists('wx_school_idcard',  'usertype')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_idcard')." ADD `usertype` int(1) NOT NULL   COMMENT '1:老师,学生0';");
 }
}
if(pdo_tableexists('wx_school_idcard')) {
 if(!pdo_fieldexists('wx_school_idcard',  'is_frist')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_idcard')." ADD `is_frist` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1'  COMMENT '1:首次,2非首次';");
 }
}
if(pdo_tableexists('wx_school_idcard')) {
 if(!pdo_fieldexists('wx_school_idcard',  'cardtype')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_idcard')." ADD `cardtype` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1';");
 }
}
if(pdo_tableexists('wx_school_idcard')) {
 if(!pdo_fieldexists('wx_school_idcard',  'lastedittime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_idcard')." ADD `lastedittime` int(11);");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `id` int(10) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'uid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `uid` int(10) NOT NULL   COMMENT '账户ID';");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `weid` int(10) NOT NULL   COMMENT '公众号id';");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'areaid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `areaid` int(10) NOT NULL   COMMENT '区域id';");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'title')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `title` varchar(50) NOT NULL   COMMENT '名称';");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'logo')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `logo` varchar(1000) NOT NULL   COMMENT '学校logo';");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'thumb')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `thumb` varchar(200) NOT NULL   COMMENT '图文消息缩略图';");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'qroce')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `qroce` varchar(200) NOT NULL   COMMENT '二维码';");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'info')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `info` varchar(1000) NOT NULL   COMMENT '简短描述';");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'content')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `content` text() NOT NULL   COMMENT '简介';");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'zhaosheng')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `zhaosheng` text() NOT NULL   COMMENT '招生简章';");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'tel')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `tel` varchar(20) NOT NULL   COMMENT '联系电话';");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'location_p')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `location_p` varchar(100) NOT NULL   COMMENT '省';");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'location_c')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `location_c` varchar(100) NOT NULL   COMMENT '市';");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'location_a')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `location_a` varchar(100) NOT NULL   COMMENT '区';");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'address')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `address` varchar(200) NOT NULL   COMMENT '地址';");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'place')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `place` varchar(200) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'lat')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `lat` decimal(18,10) NOT NULL DEFAULT NULL DEFAULT '0.0000000000'  COMMENT '经度';");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'lng')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `lng` decimal(18,10) NOT NULL DEFAULT NULL DEFAULT '0.0000000000'  COMMENT '纬度';");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'copyright')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `copyright` varchar(100) NOT NULL   COMMENT '版权';");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'is_stuewcode')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `is_stuewcode` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1'  COMMENT '1开启2关闭';");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'recharging_password')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `recharging_password` varchar(20) NOT NULL   COMMENT '充值密码';");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'thumb_url')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `thumb_url` varchar(1000);");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'is_show')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `is_show` tinyint(1) NOT NULL   COMMENT '是否在手机端显示';");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'ssort')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `ssort` tinyint(3) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'is_sms')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `is_sms` tinyint(1) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'dateline')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `dateline` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'is_hot')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `is_hot` tinyint(1) NOT NULL   COMMENT '搜索页显示';");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'is_showew')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `is_showew` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1'  COMMENT '页面显示二维码开关';");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'is_showad')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `is_showad` int(10) NOT NULL   COMMENT '是否显示广告';");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'is_comload')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `is_comload` int(10) NOT NULL   COMMENT '广告ID';");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'is_recordmac')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `is_recordmac` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '2'  COMMENT '1启用2不启用';");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'is_cardpay')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `is_cardpay` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '2'  COMMENT '1启用2不启用';");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'is_cardlist')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `is_cardlist` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '2'  COMMENT '1启用2不启用';");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'is_cost')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `is_cost` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '2'  COMMENT '1启用2不启用';");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'is_video')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `is_video` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '2'  COMMENT '1启用2不启用';");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'is_sign')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `is_sign` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1'  COMMENT '1启用2不启用';");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'is_zjh')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `is_zjh` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1'  COMMENT '1启用2不启用周计划';");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'is_wxsign')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `is_wxsign` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1'  COMMENT '1启用2不启用微信签到';");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'is_openht')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `is_openht` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1'  COMMENT '1启用2不启用独立后台';");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'is_signneedcomfim')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `is_signneedcomfim` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1'  COMMENT '手机签到是否需确认1是2否';");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'shoucename')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `shoucename` varchar(200) NOT NULL   COMMENT '手册名称';");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'videoname')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `videoname` varchar(200) NOT NULL   COMMENT '监控名称';");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'wqgroupid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `wqgroupid` int(10) NOT NULL   COMMENT '微擎默认用户组';");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'videopic')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `videopic` varchar(1000) NOT NULL   COMMENT '监控封面';");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'manger')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `manger` varchar(200) NOT NULL   COMMENT '模版名称1';");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'isopen')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `isopen` tinyint(1) NOT NULL   COMMENT '0显示1不';");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'issale')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `issale` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '5'  COMMENT '5种状态';");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'gonggao')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `gonggao` varchar(1000) NOT NULL   COMMENT '通知';");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'is_rest')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `is_rest` tinyint(1) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'signset')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `signset` varchar(200) NOT NULL   COMMENT '报名设置';");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'cardset')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `cardset` varchar(500) NOT NULL   COMMENT '刷卡设置';");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'typeid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `typeid` int(10) NOT NULL   COMMENT '学校类型';");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'cityid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `cityid` int(10) NOT NULL   COMMENT '城市ID';");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'spic')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `spic` varchar(200) NOT NULL   COMMENT '默认学生头像';");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'tpic')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `tpic` varchar(200) NOT NULL   COMMENT '默认教师头像';");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'jxstart')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `jxstart` varchar(50);");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'jxend')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `jxend` varchar(50);");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'lxstart')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `lxstart` varchar(50);");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'lxend')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `lxend` varchar(50);");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'jxstart1')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `jxstart1` varchar(50);");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'jxend1')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `jxend1` varchar(50);");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'lxstart1')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `lxstart1` varchar(50);");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'lxend1')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `lxend1` varchar(50);");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'jxstart2')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `jxstart2` varchar(50);");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'jxend2')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `jxend2` varchar(50);");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'lxstart2')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `lxstart2` varchar(50);");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'lxend2')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `lxend2` varchar(50) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'style1')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `style1` varchar(200) NOT NULL   COMMENT '模版名称';");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'style2')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `style2` varchar(200) NOT NULL   COMMENT '模版名称2';");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'style3')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `style3` varchar(200) NOT NULL   COMMENT '模版名称3';");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'userstyle')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `userstyle` varchar(50) NOT NULL   COMMENT '家长学生中心模板';");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'bjqstyle')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `bjqstyle` varchar(50) NOT NULL DEFAULT NULL DEFAULT 'old';");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'sms_set')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `sms_set` varchar(1000) NOT NULL   COMMENT '短信设置';");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'is_kb')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `is_kb` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1'  COMMENT '1启用2不启公立课表';");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'send_overtime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `send_overtime` int(10) NOT NULL DEFAULT NULL DEFAULT '-1'  COMMENT '延迟发送';");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'sms_use_times')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `sms_use_times` int(10) NOT NULL   COMMENT '短信调用次数';");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'sms_rest_times')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `sms_rest_times` int(10) NOT NULL   COMMENT '可用短信条数';");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'is_fbvocie')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `is_fbvocie` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '2'  COMMENT '1启用2不启语音';");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'is_fbnew')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `is_fbnew` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '2'  COMMENT '1启用2不启用语音和视频';");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'txid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `txid` varchar(100) NOT NULL   COMMENT '腾讯云APPID';");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'txms')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `txms` varchar(100) NOT NULL   COMMENT '腾讯云密钥';");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'bd_type')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `bd_type` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1'  COMMENT '1名手2名码3名学4名手码5名手学6名学码7名手学码7名手学码';");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'headcolor')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `headcolor` varchar(20) NOT NULL DEFAULT NULL DEFAULT '#06c1ae'  COMMENT '头部颜色';");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'mallsetinfo')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `mallsetinfo` varchar(500);");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'wxsignrange')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `wxsignrange` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'yzxxtid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `yzxxtid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'comtid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `comtid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'Cost2Point')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `Cost2Point` int(11) NOT NULL   COMMENT '一元换多少积分';");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'Is_point')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `Is_point` int(3) NOT NULL   COMMENT '是否开启积分抵用';");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'is_star')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `is_star` int(3) NOT NULL   COMMENT '是否星级1是0否';");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'is_chongzhi')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `is_chongzhi` int(3) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'chongzhiweid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `chongzhiweid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'is_shoufei')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `is_shoufei` int(3) NOT NULL DEFAULT NULL DEFAULT '1';");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'is_picarr')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `is_picarr` int(3) NOT NULL   COMMENT '是否图片组';");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'picarrset')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `picarrset` varchar(500) NOT NULL   COMMENT '图片组设置';");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'is_textarr')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `is_textarr` int(3) NOT NULL   COMMENT '是否文字组';");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'textarrset')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `textarrset` varchar(2000) NOT NULL   COMMENT '文字组设置';");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'is_qx')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `is_qx` int(3) NOT NULL DEFAULT NULL DEFAULT '1';");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'savevideoto')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `savevideoto` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1';");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'shareset')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `shareset` varchar(500) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'is_printer')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `is_printer` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1'  COMMENT '是否启用打印机';");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'sh_teacherids')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `sh_teacherids` varchar(1000)    COMMENT '校园圈模式审核人';");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'chargesetinfo')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `chargesetinfo` text() NOT NULL   COMMENT '充电桩设置';");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'is_buzhu')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `is_buzhu` tinyint(3) NOT NULL   COMMENT '是否启用学生补助余额';");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'is_ap')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `is_ap` tinyint(3) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'is_book')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `is_book` tinyint(3) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'fxlocation')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `fxlocation` text() NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_index')) {
 if(!pdo_fieldexists('wx_school_index',  'checksendset')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `checksendset` text()    COMMENT '考勤记录推送对象';");
 }
}
if(pdo_tableexists('wx_school_kcbiao')) {
 if(!pdo_fieldexists('wx_school_kcbiao',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_kcbiao')." ADD `id` int(11) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_kcbiao')) {
 if(!pdo_fieldexists('wx_school_kcbiao',  'schoolid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_kcbiao')." ADD `schoolid` int(10) NOT NULL   COMMENT '分校id';");
 }
}
if(pdo_tableexists('wx_school_kcbiao')) {
 if(!pdo_fieldexists('wx_school_kcbiao',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_kcbiao')." ADD `weid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_kcbiao')) {
 if(!pdo_fieldexists('wx_school_kcbiao',  'tid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_kcbiao')." ADD `tid` int(11) NOT NULL   COMMENT '所属教师ID';");
 }
}
if(pdo_tableexists('wx_school_kcbiao')) {
 if(!pdo_fieldexists('wx_school_kcbiao',  'kcid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_kcbiao')." ADD `kcid` int(11) NOT NULL   COMMENT '所属课程ID';");
 }
}
if(pdo_tableexists('wx_school_kcbiao')) {
 if(!pdo_fieldexists('wx_school_kcbiao',  'nub')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_kcbiao')." ADD `nub` int(11) NOT NULL   COMMENT '第几堂课或第几讲';");
 }
}
if(pdo_tableexists('wx_school_kcbiao')) {
 if(!pdo_fieldexists('wx_school_kcbiao',  'bj_id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_kcbiao')." ADD `bj_id` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_kcbiao')) {
 if(!pdo_fieldexists('wx_school_kcbiao',  'km_id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_kcbiao')." ADD `km_id` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_kcbiao')) {
 if(!pdo_fieldexists('wx_school_kcbiao',  'xq_id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_kcbiao')." ADD `xq_id` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_kcbiao')) {
 if(!pdo_fieldexists('wx_school_kcbiao',  'sd_id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_kcbiao')." ADD `sd_id` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_kcbiao')) {
 if(!pdo_fieldexists('wx_school_kcbiao',  'isxiangqing')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_kcbiao')." ADD `isxiangqing` tinyint(1) NOT NULL   COMMENT '内容显示开关';");
 }
}
if(pdo_tableexists('wx_school_kcbiao')) {
 if(!pdo_fieldexists('wx_school_kcbiao',  'content')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_kcbiao')." ADD `content` text() NOT NULL   COMMENT '课程内容';");
 }
}
if(pdo_tableexists('wx_school_kcbiao')) {
 if(!pdo_fieldexists('wx_school_kcbiao',  'date')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_kcbiao')." ADD `date` int(10) NOT NULL   COMMENT '开课日期';");
 }
}
if(pdo_tableexists('wx_school_kcbiao')) {
 if(!pdo_fieldexists('wx_school_kcbiao',  'is_remind')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_kcbiao')." ADD `is_remind` int(3) NOT NULL   COMMENT '是否已提醒';");
 }
}
if(pdo_tableexists('wx_school_kcbiao')) {
 if(!pdo_fieldexists('wx_school_kcbiao',  'addr_id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_kcbiao')." ADD `addr_id` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_kcpingjia')) {
 if(!pdo_fieldexists('wx_school_kcpingjia',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_kcpingjia')." ADD `id` int(11) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_kcpingjia')) {
 if(!pdo_fieldexists('wx_school_kcpingjia',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_kcpingjia')." ADD `weid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_kcpingjia')) {
 if(!pdo_fieldexists('wx_school_kcpingjia',  'schoolid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_kcpingjia')." ADD `schoolid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_kcpingjia')) {
 if(!pdo_fieldexists('wx_school_kcpingjia',  'kcid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_kcpingjia')." ADD `kcid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_kcpingjia')) {
 if(!pdo_fieldexists('wx_school_kcpingjia',  'tid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_kcpingjia')." ADD `tid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_kcpingjia')) {
 if(!pdo_fieldexists('wx_school_kcpingjia',  'sid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_kcpingjia')." ADD `sid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_kcpingjia')) {
 if(!pdo_fieldexists('wx_school_kcpingjia',  'userid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_kcpingjia')." ADD `userid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_kcpingjia')) {
 if(!pdo_fieldexists('wx_school_kcpingjia',  'type')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_kcpingjia')." ADD `type` int(3) NOT NULL   COMMENT '评分1留言2';");
 }
}
if(pdo_tableexists('wx_school_kcpingjia')) {
 if(!pdo_fieldexists('wx_school_kcpingjia',  'content')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_kcpingjia')." ADD `content` varchar(1000) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_kcpingjia')) {
 if(!pdo_fieldexists('wx_school_kcpingjia',  'star')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_kcpingjia')." ADD `star` int(3) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_kcpingjia')) {
 if(!pdo_fieldexists('wx_school_kcpingjia',  'createtime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_kcpingjia')." ADD `createtime` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_kcsign')) {
 if(!pdo_fieldexists('wx_school_kcsign',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_kcsign')." ADD `id` int(11) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_kcsign')) {
 if(!pdo_fieldexists('wx_school_kcsign',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_kcsign')." ADD `weid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_kcsign')) {
 if(!pdo_fieldexists('wx_school_kcsign',  'schoolid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_kcsign')." ADD `schoolid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_kcsign')) {
 if(!pdo_fieldexists('wx_school_kcsign',  'kcid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_kcsign')." ADD `kcid` int(11) NOT NULL   COMMENT '课程id';");
 }
}
if(pdo_tableexists('wx_school_kcsign')) {
 if(!pdo_fieldexists('wx_school_kcsign',  'ksid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_kcsign')." ADD `ksid` int(11) NOT NULL   COMMENT '课时id';");
 }
}
if(pdo_tableexists('wx_school_kcsign')) {
 if(!pdo_fieldexists('wx_school_kcsign',  'sid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_kcsign')." ADD `sid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_kcsign')) {
 if(!pdo_fieldexists('wx_school_kcsign',  'tid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_kcsign')." ADD `tid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_kcsign')) {
 if(!pdo_fieldexists('wx_school_kcsign',  'createtime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_kcsign')." ADD `createtime` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_kcsign')) {
 if(!pdo_fieldexists('wx_school_kcsign',  'signtime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_kcsign')." ADD `signtime` int(11) NOT NULL   COMMENT '签哪天的到';");
 }
}
if(pdo_tableexists('wx_school_kcsign')) {
 if(!pdo_fieldexists('wx_school_kcsign',  'status')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_kcsign')." ADD `status` int(3) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_kcsign')) {
 if(!pdo_fieldexists('wx_school_kcsign',  'type')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_kcsign')." ADD `type` int(3) NOT NULL   COMMENT '自由or固定';");
 }
}
if(pdo_tableexists('wx_school_kcsign')) {
 if(!pdo_fieldexists('wx_school_kcsign',  'qrtid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_kcsign')." ADD `qrtid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_kcsign')) {
 if(!pdo_fieldexists('wx_school_kcsign',  'kcname')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_kcsign')." ADD `kcname` varchar(200) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_language')) {
 if(!pdo_fieldexists('wx_school_language',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_language')." ADD `id` int(10) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_language')) {
 if(!pdo_fieldexists('wx_school_language',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_language')." ADD `weid` int(10) NOT NULL   COMMENT '公众号';");
 }
}
if(pdo_tableexists('wx_school_language')) {
 if(!pdo_fieldexists('wx_school_language',  'schoolid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_language')." ADD `schoolid` int(10) NOT NULL   COMMENT '分校id';");
 }
}
if(pdo_tableexists('wx_school_language')) {
 if(!pdo_fieldexists('wx_school_language',  'is_on')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_language')." ADD `is_on` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1'  COMMENT '是否启用';");
 }
}
if(pdo_tableexists('wx_school_language')) {
 if(!pdo_fieldexists('wx_school_language',  'lanset')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_language')." ADD `lanset` text() NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_leave')) {
 if(!pdo_fieldexists('wx_school_leave',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_leave')." ADD `id` int(10) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_leave')) {
 if(!pdo_fieldexists('wx_school_leave',  'leaveid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_leave')." ADD `leaveid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_leave')) {
 if(!pdo_fieldexists('wx_school_leave',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_leave')." ADD `weid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_leave')) {
 if(!pdo_fieldexists('wx_school_leave',  'schoolid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_leave')." ADD `schoolid` int(10) NOT NULL   COMMENT '学校ID';");
 }
}
if(pdo_tableexists('wx_school_leave')) {
 if(!pdo_fieldexists('wx_school_leave',  'uid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_leave')." ADD `uid` int(10) NOT NULL   COMMENT '微擎UID';");
 }
}
if(pdo_tableexists('wx_school_leave')) {
 if(!pdo_fieldexists('wx_school_leave',  'tuid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_leave')." ADD `tuid` int(10) NOT NULL   COMMENT '老师微擎UID';");
 }
}
if(pdo_tableexists('wx_school_leave')) {
 if(!pdo_fieldexists('wx_school_leave',  'userid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_leave')." ADD `userid` int(10) NOT NULL   COMMENT '发送者id';");
 }
}
if(pdo_tableexists('wx_school_leave')) {
 if(!pdo_fieldexists('wx_school_leave',  'touserid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_leave')." ADD `touserid` int(10) NOT NULL   COMMENT '接收者id';");
 }
}
if(pdo_tableexists('wx_school_leave')) {
 if(!pdo_fieldexists('wx_school_leave',  'openid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_leave')." ADD `openid` varchar(200)    COMMENT 'openid';");
 }
}
if(pdo_tableexists('wx_school_leave')) {
 if(!pdo_fieldexists('wx_school_leave',  'sid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_leave')." ADD `sid` int(10) NOT NULL   COMMENT '学生ID';");
 }
}
if(pdo_tableexists('wx_school_leave')) {
 if(!pdo_fieldexists('wx_school_leave',  'tid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_leave')." ADD `tid` int(10) NOT NULL   COMMENT '教师ID';");
 }
}
if(pdo_tableexists('wx_school_leave')) {
 if(!pdo_fieldexists('wx_school_leave',  'type')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_leave')." ADD `type` varchar(10)    COMMENT '请假类型';");
 }
}
if(pdo_tableexists('wx_school_leave')) {
 if(!pdo_fieldexists('wx_school_leave',  'startime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_leave')." ADD `startime` varchar(200)    COMMENT '开始时间';");
 }
}
if(pdo_tableexists('wx_school_leave')) {
 if(!pdo_fieldexists('wx_school_leave',  'endtime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_leave')." ADD `endtime` varchar(200)    COMMENT '结束时间';");
 }
}
if(pdo_tableexists('wx_school_leave')) {
 if(!pdo_fieldexists('wx_school_leave',  'startime1')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_leave')." ADD `startime1` int(10) NOT NULL   COMMENT '开始时间';");
 }
}
if(pdo_tableexists('wx_school_leave')) {
 if(!pdo_fieldexists('wx_school_leave',  'endtime1')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_leave')." ADD `endtime1` int(10) NOT NULL   COMMENT '结束时间';");
 }
}
if(pdo_tableexists('wx_school_leave')) {
 if(!pdo_fieldexists('wx_school_leave',  'conet')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_leave')." ADD `conet` varchar(200)    COMMENT '详细内容';");
 }
}
if(pdo_tableexists('wx_school_leave')) {
 if(!pdo_fieldexists('wx_school_leave',  'reconet')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_leave')." ADD `reconet` varchar(200)    COMMENT '详细内容';");
 }
}
if(pdo_tableexists('wx_school_leave')) {
 if(!pdo_fieldexists('wx_school_leave',  'createtime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_leave')." ADD `createtime` int(10) NOT NULL   COMMENT '创建时间';");
 }
}
if(pdo_tableexists('wx_school_leave')) {
 if(!pdo_fieldexists('wx_school_leave',  'cltime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_leave')." ADD `cltime` int(10) NOT NULL   COMMENT '处理时间';");
 }
}
if(pdo_tableexists('wx_school_leave')) {
 if(!pdo_fieldexists('wx_school_leave',  'cltid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_leave')." ADD `cltid` int(10) NOT NULL   COMMENT '老师id';");
 }
}
if(pdo_tableexists('wx_school_leave')) {
 if(!pdo_fieldexists('wx_school_leave',  'status')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_leave')." ADD `status` tinyint(1) NOT NULL   COMMENT '审核状态';");
 }
}
if(pdo_tableexists('wx_school_leave')) {
 if(!pdo_fieldexists('wx_school_leave',  'bj_id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_leave')." ADD `bj_id` int(10) NOT NULL   COMMENT '班级ID';");
 }
}
if(pdo_tableexists('wx_school_leave')) {
 if(!pdo_fieldexists('wx_school_leave',  'teacherid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_leave')." ADD `teacherid` int(11);");
 }
}
if(pdo_tableexists('wx_school_leave')) {
 if(!pdo_fieldexists('wx_school_leave',  'isfrist')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_leave')." ADD `isfrist` tinyint(1) NOT NULL   COMMENT '1是0否';");
 }
}
if(pdo_tableexists('wx_school_leave')) {
 if(!pdo_fieldexists('wx_school_leave',  'isliuyan')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_leave')." ADD `isliuyan` tinyint(1) NOT NULL   COMMENT '是否留言';");
 }
}
if(pdo_tableexists('wx_school_leave')) {
 if(!pdo_fieldexists('wx_school_leave',  'isread')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_leave')." ADD `isread` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1'  COMMENT '1未读2已读';");
 }
}
if(pdo_tableexists('wx_school_leave')) {
 if(!pdo_fieldexists('wx_school_leave',  'audio')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_leave')." ADD `audio` varchar(1000);");
 }
}
if(pdo_tableexists('wx_school_leave')) {
 if(!pdo_fieldexists('wx_school_leave',  'tonjzrtid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_leave')." ADD `tonjzrtid` int(11) NOT NULL   COMMENT '年级主任tid';");
 }
}
if(pdo_tableexists('wx_school_leave')) {
 if(!pdo_fieldexists('wx_school_leave',  'toxztid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_leave')." ADD `toxztid` int(11) NOT NULL   COMMENT '校长tid';");
 }
}
if(pdo_tableexists('wx_school_leave')) {
 if(!pdo_fieldexists('wx_school_leave',  'njzryj')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_leave')." ADD `njzryj` varchar(200) NOT NULL   COMMENT '年级主任审批意见';");
 }
}
if(pdo_tableexists('wx_school_leave')) {
 if(!pdo_fieldexists('wx_school_leave',  'njzrcltime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_leave')." ADD `njzrcltime` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_leave')) {
 if(!pdo_fieldexists('wx_school_leave',  'picurl')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_leave')." ADD `picurl` varchar(1000) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_leave')) {
 if(!pdo_fieldexists('wx_school_leave',  'tktype')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_leave')." ADD `tktype` int(3) NOT NULL   COMMENT '调课类型';");
 }
}
if(pdo_tableexists('wx_school_leave')) {
 if(!pdo_fieldexists('wx_school_leave',  'ksnum')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_leave')." ADD `ksnum` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_leave')) {
 if(!pdo_fieldexists('wx_school_leave',  'classid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_leave')." ADD `classid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_leave')) {
 if(!pdo_fieldexists('wx_school_leave',  'more_less')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_leave')." ADD `more_less` tinyint(3) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_mall')) {
 if(!pdo_fieldexists('wx_school_mall',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_mall')." ADD `id` int(10) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_mall')) {
 if(!pdo_fieldexists('wx_school_mall',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_mall')." ADD `weid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_mall')) {
 if(!pdo_fieldexists('wx_school_mall',  'schoolid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_mall')." ADD `schoolid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_mall')) {
 if(!pdo_fieldexists('wx_school_mall',  'title')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_mall')." ADD `title` varchar(100) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_mall')) {
 if(!pdo_fieldexists('wx_school_mall',  'thumb')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_mall')." ADD `thumb` varchar(1000) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_mall')) {
 if(!pdo_fieldexists('wx_school_mall',  'content')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_mall')." ADD `content` text() NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_mall')) {
 if(!pdo_fieldexists('wx_school_mall',  'type')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_mall')." ADD `type` varchar(500) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_mall')) {
 if(!pdo_fieldexists('wx_school_mall',  'fenlei')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_mall')." ADD `fenlei` varchar(500) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_mall')) {
 if(!pdo_fieldexists('wx_school_mall',  'sort')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_mall')." ADD `sort` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_mall')) {
 if(!pdo_fieldexists('wx_school_mall',  'old_price')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_mall')." ADD `old_price` float() NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_mall')) {
 if(!pdo_fieldexists('wx_school_mall',  'new_price')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_mall')." ADD `new_price` float() NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_mall')) {
 if(!pdo_fieldexists('wx_school_mall',  'points')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_mall')." ADD `points` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_mall')) {
 if(!pdo_fieldexists('wx_school_mall',  'qty')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_mall')." ADD `qty` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_mall')) {
 if(!pdo_fieldexists('wx_school_mall',  'sold')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_mall')." ADD `sold` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_mall')) {
 if(!pdo_fieldexists('wx_school_mall',  'cop')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_mall')." ADD `cop` int(11) NOT NULL   COMMENT '1纯积分2纯金额3混合';");
 }
}
if(pdo_tableexists('wx_school_mall')) {
 if(!pdo_fieldexists('wx_school_mall',  'xsxg')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_mall')." ADD `xsxg` int(3) NOT NULL   COMMENT '学生限购数量.0为不限购';");
 }
}
if(pdo_tableexists('wx_school_mall')) {
 if(!pdo_fieldexists('wx_school_mall',  'showtype')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_mall')." ADD `showtype` int(3) NOT NULL   COMMENT '家长端1/教师端2/两者0';");
 }
}
if(pdo_tableexists('wx_school_mallorder')) {
 if(!pdo_fieldexists('wx_school_mallorder',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_mallorder')." ADD `id` int(10) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_mallorder')) {
 if(!pdo_fieldexists('wx_school_mallorder',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_mallorder')." ADD `weid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_mallorder')) {
 if(!pdo_fieldexists('wx_school_mallorder',  'schoolid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_mallorder')." ADD `schoolid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_mallorder')) {
 if(!pdo_fieldexists('wx_school_mallorder',  'tid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_mallorder')." ADD `tid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_mallorder')) {
 if(!pdo_fieldexists('wx_school_mallorder',  'goodsid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_mallorder')." ADD `goodsid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_mallorder')) {
 if(!pdo_fieldexists('wx_school_mallorder',  'addressid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_mallorder')." ADD `addressid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_mallorder')) {
 if(!pdo_fieldexists('wx_school_mallorder',  'torderid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_mallorder')." ADD `torderid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_mallorder')) {
 if(!pdo_fieldexists('wx_school_mallorder',  'tname')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_mallorder')." ADD `tname` varchar(50) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_mallorder')) {
 if(!pdo_fieldexists('wx_school_mallorder',  'tphone')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_mallorder')." ADD `tphone` varchar(15) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_mallorder')) {
 if(!pdo_fieldexists('wx_school_mallorder',  'taddress')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_mallorder')." ADD `taddress` varchar(500) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_mallorder')) {
 if(!pdo_fieldexists('wx_school_mallorder',  'count')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_mallorder')." ADD `count` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_mallorder')) {
 if(!pdo_fieldexists('wx_school_mallorder',  'allcash')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_mallorder')." ADD `allcash` float() NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_mallorder')) {
 if(!pdo_fieldexists('wx_school_mallorder',  'allpoint')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_mallorder')." ADD `allpoint` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_mallorder')) {
 if(!pdo_fieldexists('wx_school_mallorder',  'beizhu')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_mallorder')." ADD `beizhu` varchar(500) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_mallorder')) {
 if(!pdo_fieldexists('wx_school_mallorder',  'cop')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_mallorder')." ADD `cop` int(11) NOT NULL   COMMENT '1纯积分2纯金额3混合';");
 }
}
if(pdo_tableexists('wx_school_mallorder')) {
 if(!pdo_fieldexists('wx_school_mallorder',  'status')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_mallorder')." ADD `status` int(3) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_mallorder')) {
 if(!pdo_fieldexists('wx_school_mallorder',  'fahuo')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_mallorder')." ADD `fahuo` int(3) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_mallorder')) {
 if(!pdo_fieldexists('wx_school_mallorder',  'createtime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_mallorder')." ADD `createtime` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_mallorder')) {
 if(!pdo_fieldexists('wx_school_mallorder',  'sid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_mallorder')." ADD `sid` int(11) NOT NULL   COMMENT '学生id';");
 }
}
if(pdo_tableexists('wx_school_mallorder')) {
 if(!pdo_fieldexists('wx_school_mallorder',  'userid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_mallorder')." ADD `userid` int(11) NOT NULL   COMMENT '购买者userid（学生用）';");
 }
}
if(pdo_tableexists('wx_school_media')) {
 if(!pdo_fieldexists('wx_school_media',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_media')." ADD `id` int(10) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_media')) {
 if(!pdo_fieldexists('wx_school_media',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_media')." ADD `weid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_media')) {
 if(!pdo_fieldexists('wx_school_media',  'schoolid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_media')." ADD `schoolid` int(10) NOT NULL   COMMENT '学校ID';");
 }
}
if(pdo_tableexists('wx_school_media')) {
 if(!pdo_fieldexists('wx_school_media',  'uid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_media')." ADD `uid` int(10) NOT NULL   COMMENT '发布者UID';");
 }
}
if(pdo_tableexists('wx_school_media')) {
 if(!pdo_fieldexists('wx_school_media',  'sid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_media')." ADD `sid` int(10) NOT NULL   COMMENT '学生SID';");
 }
}
if(pdo_tableexists('wx_school_media')) {
 if(!pdo_fieldexists('wx_school_media',  'picurl')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_media')." ADD `picurl` varchar(255)    COMMENT '图片';");
 }
}
if(pdo_tableexists('wx_school_media')) {
 if(!pdo_fieldexists('wx_school_media',  'fmpicurl')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_media')." ADD `fmpicurl` varchar(255)    COMMENT '封面图片';");
 }
}
if(pdo_tableexists('wx_school_media')) {
 if(!pdo_fieldexists('wx_school_media',  'bj_id1')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_media')." ADD `bj_id1` int(10) NOT NULL   COMMENT '班级ID1';");
 }
}
if(pdo_tableexists('wx_school_media')) {
 if(!pdo_fieldexists('wx_school_media',  'bj_id2')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_media')." ADD `bj_id2` int(10) NOT NULL   COMMENT '班级ID2';");
 }
}
if(pdo_tableexists('wx_school_media')) {
 if(!pdo_fieldexists('wx_school_media',  'bj_id3')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_media')." ADD `bj_id3` int(10) NOT NULL   COMMENT '班级ID3';");
 }
}
if(pdo_tableexists('wx_school_media')) {
 if(!pdo_fieldexists('wx_school_media',  'order')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_media')." ADD `order` int(10) NOT NULL   COMMENT '排序';");
 }
}
if(pdo_tableexists('wx_school_media')) {
 if(!pdo_fieldexists('wx_school_media',  'sherid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_media')." ADD `sherid` int(10) NOT NULL   COMMENT '所属图文id';");
 }
}
if(pdo_tableexists('wx_school_media')) {
 if(!pdo_fieldexists('wx_school_media',  'createtime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_media')." ADD `createtime` int(10) NOT NULL   COMMENT '创建时间';");
 }
}
if(pdo_tableexists('wx_school_media')) {
 if(!pdo_fieldexists('wx_school_media',  'type')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_media')." ADD `type` tinyint(1) NOT NULL   COMMENT '0班级圈1学生相册';");
 }
}
if(pdo_tableexists('wx_school_media')) {
 if(!pdo_fieldexists('wx_school_media',  'isfm')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_media')." ADD `isfm` tinyint(1) NOT NULL   COMMENT '1是0否';");
 }
}
if(pdo_tableexists('wx_school_media')) {
 if(!pdo_fieldexists('wx_school_media',  'kc_id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_media')." ADD `kc_id` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_news')) {
 if(!pdo_fieldexists('wx_school_news',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_news')." ADD `id` int(10) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_news')) {
 if(!pdo_fieldexists('wx_school_news',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_news')." ADD `weid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_news')) {
 if(!pdo_fieldexists('wx_school_news',  'schoolid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_news')." ADD `schoolid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_news')) {
 if(!pdo_fieldexists('wx_school_news',  'cateid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_news')." ADD `cateid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_news')) {
 if(!pdo_fieldexists('wx_school_news',  'title')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_news')." ADD `title` varchar(100) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_news')) {
 if(!pdo_fieldexists('wx_school_news',  'type')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_news')." ADD `type` varchar(50) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_news')) {
 if(!pdo_fieldexists('wx_school_news',  'content')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_news')." ADD `content` mediumtext() NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_news')) {
 if(!pdo_fieldexists('wx_school_news',  'thumb')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_news')." ADD `thumb` varchar(255) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_news')) {
 if(!pdo_fieldexists('wx_school_news',  'description')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_news')." ADD `description` varchar(255) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_news')) {
 if(!pdo_fieldexists('wx_school_news',  'author')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_news')." ADD `author` varchar(50) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_news')) {
 if(!pdo_fieldexists('wx_school_news',  'picarr')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_news')." ADD `picarr` text()    COMMENT '图片组';");
 }
}
if(pdo_tableexists('wx_school_news')) {
 if(!pdo_fieldexists('wx_school_news',  'displayorder')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_news')." ADD `displayorder` int(10) NOT NULL   COMMENT '排序';");
 }
}
if(pdo_tableexists('wx_school_news')) {
 if(!pdo_fieldexists('wx_school_news',  'is_display')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_news')." ADD `is_display` tinyint(3) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_news')) {
 if(!pdo_fieldexists('wx_school_news',  'is_show_home')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_news')." ADD `is_show_home` tinyint(3) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_news')) {
 if(!pdo_fieldexists('wx_school_news',  'createtime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_news')." ADD `createtime` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_news')) {
 if(!pdo_fieldexists('wx_school_news',  'click')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_news')." ADD `click` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_news')) {
 if(!pdo_fieldexists('wx_school_news',  'dianzan')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_news')." ADD `dianzan` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_notice')) {
 if(!pdo_fieldexists('wx_school_notice',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_notice')." ADD `id` int(10) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_notice')) {
 if(!pdo_fieldexists('wx_school_notice',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_notice')." ADD `weid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_notice')) {
 if(!pdo_fieldexists('wx_school_notice',  'schoolid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_notice')." ADD `schoolid` int(10) NOT NULL   COMMENT '学校ID';");
 }
}
if(pdo_tableexists('wx_school_notice')) {
 if(!pdo_fieldexists('wx_school_notice',  'tid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_notice')." ADD `tid` int(10) NOT NULL   COMMENT '教师ID';");
 }
}
if(pdo_tableexists('wx_school_notice')) {
 if(!pdo_fieldexists('wx_school_notice',  'tname')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_notice')." ADD `tname` varchar(10)    COMMENT '发布老师名字';");
 }
}
if(pdo_tableexists('wx_school_notice')) {
 if(!pdo_fieldexists('wx_school_notice',  'title')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_notice')." ADD `title` varchar(50)    COMMENT '文章名称';");
 }
}
if(pdo_tableexists('wx_school_notice')) {
 if(!pdo_fieldexists('wx_school_notice',  'content')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_notice')." ADD `content` text() NOT NULL   COMMENT '详细内容';");
 }
}
if(pdo_tableexists('wx_school_notice')) {
 if(!pdo_fieldexists('wx_school_notice',  'outurl')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_notice')." ADD `outurl` varchar(500)    COMMENT '外部链接';");
 }
}
if(pdo_tableexists('wx_school_notice')) {
 if(!pdo_fieldexists('wx_school_notice',  'picarr')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_notice')." ADD `picarr` text()    COMMENT '图片组';");
 }
}
if(pdo_tableexists('wx_school_notice')) {
 if(!pdo_fieldexists('wx_school_notice',  'createtime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_notice')." ADD `createtime` int(10) NOT NULL   COMMENT '创建时间';");
 }
}
if(pdo_tableexists('wx_school_notice')) {
 if(!pdo_fieldexists('wx_school_notice',  'bj_id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_notice')." ADD `bj_id` int(10) NOT NULL   COMMENT '班级ID';");
 }
}
if(pdo_tableexists('wx_school_notice')) {
 if(!pdo_fieldexists('wx_school_notice',  'km_id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_notice')." ADD `km_id` int(10) NOT NULL   COMMENT '科目ID';");
 }
}
if(pdo_tableexists('wx_school_notice')) {
 if(!pdo_fieldexists('wx_school_notice',  'type')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_notice')." ADD `type` tinyint(1) NOT NULL   COMMENT '是否班级通知';");
 }
}
if(pdo_tableexists('wx_school_notice')) {
 if(!pdo_fieldexists('wx_school_notice',  'ismobile')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_notice')." ADD `ismobile` tinyint(1) NOT NULL   COMMENT '0手机端1电脑端';");
 }
}
if(pdo_tableexists('wx_school_notice')) {
 if(!pdo_fieldexists('wx_school_notice',  'groupid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_notice')." ADD `groupid` tinyint(1) NOT NULL   COMMENT '1为全体师生2为全体教师3为全体家长和学生';");
 }
}
if(pdo_tableexists('wx_school_notice')) {
 if(!pdo_fieldexists('wx_school_notice',  'video')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_notice')." ADD `video` varchar(2000) NOT NULL   COMMENT '视频地址';");
 }
}
if(pdo_tableexists('wx_school_notice')) {
 if(!pdo_fieldexists('wx_school_notice',  'videopic')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_notice')." ADD `videopic` varchar(1000) NOT NULL   COMMENT '视频封面';");
 }
}
if(pdo_tableexists('wx_school_notice')) {
 if(!pdo_fieldexists('wx_school_notice',  'audio')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_notice')." ADD `audio` varchar(100)    COMMENT '音频';");
 }
}
if(pdo_tableexists('wx_school_notice')) {
 if(!pdo_fieldexists('wx_school_notice',  'audiotime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_notice')." ADD `audiotime` int(10) NOT NULL   COMMENT '音频时长';");
 }
}
if(pdo_tableexists('wx_school_notice')) {
 if(!pdo_fieldexists('wx_school_notice',  'anstype')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_notice')." ADD `anstype` varchar(200) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_notice')) {
 if(!pdo_fieldexists('wx_school_notice',  'kc_id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_notice')." ADD `kc_id` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_notice')) {
 if(!pdo_fieldexists('wx_school_notice',  'usertype')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_notice')." ADD `usertype` varchar(100)    COMMENT '接收用户';");
 }
}
if(pdo_tableexists('wx_school_notice')) {
 if(!pdo_fieldexists('wx_school_notice',  'userdatas')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_notice')." ADD `userdatas` varchar(1000)    COMMENT '用户数据';");
 }
}
if(pdo_tableexists('wx_school_notice')) {
 if(!pdo_fieldexists('wx_school_notice',  'is_research')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_notice')." ADD `is_research` tinyint(3) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_notice')) {
 if(!pdo_fieldexists('wx_school_notice',  'ali_vod_id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_notice')." ADD `ali_vod_id` varchar(100)    COMMENT '视频画面ID';");
 }
}
if(pdo_tableexists('wx_school_object')) {
 if(!pdo_fieldexists('wx_school_object',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_object')." ADD `id` int(10) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_object')) {
 if(!pdo_fieldexists('wx_school_object',  'item')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_object')." ADD `item` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_object')) {
 if(!pdo_fieldexists('wx_school_object',  'type')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_object')." ADD `type` varchar(50) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_object')) {
 if(!pdo_fieldexists('wx_school_object',  'displayorder')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_object')." ADD `displayorder` varchar(50) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_online')) {
 if(!pdo_fieldexists('wx_school_online',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_online')." ADD `id` int(10) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_online')) {
 if(!pdo_fieldexists('wx_school_online',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_online')." ADD `weid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_online')) {
 if(!pdo_fieldexists('wx_school_online',  'schoolid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_online')." ADD `schoolid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_online')) {
 if(!pdo_fieldexists('wx_school_online',  'macid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_online')." ADD `macid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_online')) {
 if(!pdo_fieldexists('wx_school_online',  'commond')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_online')." ADD `commond` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_online')) {
 if(!pdo_fieldexists('wx_school_online',  'result')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_online')." ADD `result` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '2';");
 }
}
if(pdo_tableexists('wx_school_online')) {
 if(!pdo_fieldexists('wx_school_online',  'isread')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_online')." ADD `isread` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '2';");
 }
}
if(pdo_tableexists('wx_school_online')) {
 if(!pdo_fieldexists('wx_school_online',  'createtime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_online')." ADD `createtime` int(10) NOT NULL   COMMENT '生成时间';");
 }
}
if(pdo_tableexists('wx_school_online')) {
 if(!pdo_fieldexists('wx_school_online',  'dotime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_online')." ADD `dotime` int(10) NOT NULL   COMMENT '执行时间';");
 }
}
if(pdo_tableexists('wx_school_online')) {
 if(!pdo_fieldexists('wx_school_online',  'lastedittime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_online')." ADD `lastedittime` int(11)    COMMENT '任务对应的最近一次修改时间';");
 }
}
if(pdo_tableexists('wx_school_order')) {
 if(!pdo_fieldexists('wx_school_order',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_order')." ADD `id` int(10) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_order')) {
 if(!pdo_fieldexists('wx_school_order',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_order')." ADD `weid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_order')) {
 if(!pdo_fieldexists('wx_school_order',  'schoolid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_order')." ADD `schoolid` int(10) NOT NULL   COMMENT '学校ID';");
 }
}
if(pdo_tableexists('wx_school_order')) {
 if(!pdo_fieldexists('wx_school_order',  'orderid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_order')." ADD `orderid` int(10) NOT NULL   COMMENT '订单ID';");
 }
}
if(pdo_tableexists('wx_school_order')) {
 if(!pdo_fieldexists('wx_school_order',  'uid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_order')." ADD `uid` int(10) NOT NULL   COMMENT '发布者UID';");
 }
}
if(pdo_tableexists('wx_school_order')) {
 if(!pdo_fieldexists('wx_school_order',  'userid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_order')." ADD `userid` int(10) NOT NULL   COMMENT '发布者UID';");
 }
}
if(pdo_tableexists('wx_school_order')) {
 if(!pdo_fieldexists('wx_school_order',  'sid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_order')." ADD `sid` int(10) NOT NULL   COMMENT '学生id';");
 }
}
if(pdo_tableexists('wx_school_order')) {
 if(!pdo_fieldexists('wx_school_order',  'kcid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_order')." ADD `kcid` int(10) NOT NULL   COMMENT '课程ID';");
 }
}
if(pdo_tableexists('wx_school_order')) {
 if(!pdo_fieldexists('wx_school_order',  'costid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_order')." ADD `costid` int(10) NOT NULL   COMMENT '项目ID';");
 }
}
if(pdo_tableexists('wx_school_order')) {
 if(!pdo_fieldexists('wx_school_order',  'lastorderid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_order')." ADD `lastorderid` int(10) NOT NULL   COMMENT '继承订单,用于续费';");
 }
}
if(pdo_tableexists('wx_school_order')) {
 if(!pdo_fieldexists('wx_school_order',  'signid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_order')." ADD `signid` int(10) NOT NULL   COMMENT '报名ID';");
 }
}
if(pdo_tableexists('wx_school_order')) {
 if(!pdo_fieldexists('wx_school_order',  'bdcardid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_order')." ADD `bdcardid` int(10) NOT NULL   COMMENT '帮卡ID';");
 }
}
if(pdo_tableexists('wx_school_order')) {
 if(!pdo_fieldexists('wx_school_order',  'obid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_order')." ADD `obid` int(10) NOT NULL   COMMENT '功能ID';");
 }
}
if(pdo_tableexists('wx_school_order')) {
 if(!pdo_fieldexists('wx_school_order',  'cose')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_order')." ADD `cose` decimal(18,2) NOT NULL DEFAULT NULL DEFAULT '0.00'  COMMENT '价格';");
 }
}
if(pdo_tableexists('wx_school_order')) {
 if(!pdo_fieldexists('wx_school_order',  'status')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_order')." ADD `status` tinyint(1) NOT NULL   COMMENT '1未支付2为已支付3为已退款';");
 }
}
if(pdo_tableexists('wx_school_order')) {
 if(!pdo_fieldexists('wx_school_order',  'type')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_order')." ADD `type` tinyint(1) NOT NULL   COMMENT '1课程2项目3功能4报名';");
 }
}
if(pdo_tableexists('wx_school_order')) {
 if(!pdo_fieldexists('wx_school_order',  'createtime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_order')." ADD `createtime` int(10) NOT NULL   COMMENT '创建时间';");
 }
}
if(pdo_tableexists('wx_school_order')) {
 if(!pdo_fieldexists('wx_school_order',  'hmxianlaohu')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_order')." ADD `hmxianlaohu` varchar(30) NOT NULL   COMMENT '支付LOGO';");
 }
}
if(pdo_tableexists('wx_school_order')) {
 if(!pdo_fieldexists('wx_school_order',  'paytime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_order')." ADD `paytime` int(10) NOT NULL   COMMENT '支付时间';");
 }
}
if(pdo_tableexists('wx_school_order')) {
 if(!pdo_fieldexists('wx_school_order',  'paytype')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_order')." ADD `paytype` tinyint(1) NOT NULL   COMMENT '1线上2现金';");
 }
}
if(pdo_tableexists('wx_school_order')) {
 if(!pdo_fieldexists('wx_school_order',  'pay_type')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_order')." ADD `pay_type` varchar(100)    COMMENT '支付方式';");
 }
}
if(pdo_tableexists('wx_school_order')) {
 if(!pdo_fieldexists('wx_school_order',  'tuitime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_order')." ADD `tuitime` int(10) NOT NULL   COMMENT '退费时间';");
 }
}
if(pdo_tableexists('wx_school_order')) {
 if(!pdo_fieldexists('wx_school_order',  'xufeitype')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_order')." ADD `xufeitype` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '2'  COMMENT '1已续费2未续费';");
 }
}
if(pdo_tableexists('wx_school_order')) {
 if(!pdo_fieldexists('wx_school_order',  'payweid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_order')." ADD `payweid` int(10) NOT NULL   COMMENT '支付公众号';");
 }
}
if(pdo_tableexists('wx_school_order')) {
 if(!pdo_fieldexists('wx_school_order',  'uniontid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_order')." ADD `uniontid` varchar(1000)    COMMENT '微信或支付宝返回的订单号';");
 }
}
if(pdo_tableexists('wx_school_order')) {
 if(!pdo_fieldexists('wx_school_order',  'vodid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_order')." ADD `vodid` int(10)    COMMENT '视频ID';");
 }
}
if(pdo_tableexists('wx_school_order')) {
 if(!pdo_fieldexists('wx_school_order',  'vodtype')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_order')." ADD `vodtype` varchar(30) NOT NULL   COMMENT '视频课程购买类型';");
 }
}
if(pdo_tableexists('wx_school_order')) {
 if(!pdo_fieldexists('wx_school_order',  'morderid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_order')." ADD `morderid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_order')) {
 if(!pdo_fieldexists('wx_school_order',  'ksnum')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_order')." ADD `ksnum` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_order')) {
 if(!pdo_fieldexists('wx_school_order',  'spoint')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_order')." ADD `spoint` int(11) NOT NULL   COMMENT '学生积分';");
 }
}
if(pdo_tableexists('wx_school_order')) {
 if(!pdo_fieldexists('wx_school_order',  'tempsid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_order')." ADD `tempsid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_order')) {
 if(!pdo_fieldexists('wx_school_order',  'tempopenid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_order')." ADD `tempopenid` varchar(50) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_order')) {
 if(!pdo_fieldexists('wx_school_order',  'tid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_order')." ADD `tid` varchar(100) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_order')) {
 if(!pdo_fieldexists('wx_school_order',  'taocanid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_order')." ADD `taocanid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_order')) {
 if(!pdo_fieldexists('wx_school_order',  'shareuserid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_order')." ADD `shareuserid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_order')) {
 if(!pdo_fieldexists('wx_school_order',  'print_nums')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_order')." ADD `print_nums` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_order')) {
 if(!pdo_fieldexists('wx_school_order',  'refundid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_order')." ADD `refundid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_order')) {
 if(!pdo_fieldexists('wx_school_order',  'wxpayid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_order')." ADD `wxpayid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_points')) {
 if(!pdo_fieldexists('wx_school_points',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_points')." ADD `id` int(11) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_points')) {
 if(!pdo_fieldexists('wx_school_points',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_points')." ADD `weid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_points')) {
 if(!pdo_fieldexists('wx_school_points',  'schoolid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_points')." ADD `schoolid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_points')) {
 if(!pdo_fieldexists('wx_school_points',  'op')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_points')." ADD `op` varchar(30) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_points')) {
 if(!pdo_fieldexists('wx_school_points',  'name')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_points')." ADD `name` varchar(50) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_points')) {
 if(!pdo_fieldexists('wx_school_points',  'dailytime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_points')." ADD `dailytime` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_points')) {
 if(!pdo_fieldexists('wx_school_points',  'adpoint')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_points')." ADD `adpoint` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_points')) {
 if(!pdo_fieldexists('wx_school_points',  'is_on')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_points')." ADD `is_on` int(1) NOT NULL   COMMENT '1开启2关闭';");
 }
}
if(pdo_tableexists('wx_school_points')) {
 if(!pdo_fieldexists('wx_school_points',  'type')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_points')." ADD `type` int(3) NOT NULL   COMMENT '1规则2任务';");
 }
}
if(pdo_tableexists('wx_school_pointsrecord')) {
 if(!pdo_fieldexists('wx_school_pointsrecord',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_pointsrecord')." ADD `id` int(11) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_pointsrecord')) {
 if(!pdo_fieldexists('wx_school_pointsrecord',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_pointsrecord')." ADD `weid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_pointsrecord')) {
 if(!pdo_fieldexists('wx_school_pointsrecord',  'schoolid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_pointsrecord')." ADD `schoolid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_pointsrecord')) {
 if(!pdo_fieldexists('wx_school_pointsrecord',  'tid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_pointsrecord')." ADD `tid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_pointsrecord')) {
 if(!pdo_fieldexists('wx_school_pointsrecord',  'pid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_pointsrecord')." ADD `pid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_pointsrecord')) {
 if(!pdo_fieldexists('wx_school_pointsrecord',  'createtime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_pointsrecord')." ADD `createtime` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_pointsrecord')) {
 if(!pdo_fieldexists('wx_school_pointsrecord',  'type')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_pointsrecord')." ADD `type` int(3) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_pointsrecord')) {
 if(!pdo_fieldexists('wx_school_pointsrecord',  'mcount')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_pointsrecord')." ADD `mcount` int(3) NOT NULL   COMMENT '任务已完成次数';");
 }
}
if(pdo_tableexists('wx_school_print_log')) {
 if(!pdo_fieldexists('wx_school_print_log',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_print_log')." ADD `id` int(10) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_print_log')) {
 if(!pdo_fieldexists('wx_school_print_log',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_print_log')." ADD `weid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_print_log')) {
 if(!pdo_fieldexists('wx_school_print_log',  'schoolid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_print_log')." ADD `schoolid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_print_log')) {
 if(!pdo_fieldexists('wx_school_print_log',  'pid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_print_log')." ADD `pid` tinyint(3) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_print_log')) {
 if(!pdo_fieldexists('wx_school_print_log',  'oid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_print_log')." ADD `oid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_print_log')) {
 if(!pdo_fieldexists('wx_school_print_log',  'foid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_print_log')." ADD `foid` varchar(50) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_print_log')) {
 if(!pdo_fieldexists('wx_school_print_log',  'status')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_print_log')." ADD `status` tinyint(3) NOT NULL DEFAULT NULL DEFAULT '2'  COMMENT '1:打印成功,2:打印未成功';");
 }
}
if(pdo_tableexists('wx_school_print_log')) {
 if(!pdo_fieldexists('wx_school_print_log',  'printer_type')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_print_log')." ADD `printer_type` varchar(20) NOT NULL DEFAULT NULL DEFAULT 'feie';");
 }
}
if(pdo_tableexists('wx_school_print_log')) {
 if(!pdo_fieldexists('wx_school_print_log',  'createtime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_print_log')." ADD `createtime` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_printer')) {
 if(!pdo_fieldexists('wx_school_printer',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_printer')." ADD `id` int(10) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_printer')) {
 if(!pdo_fieldexists('wx_school_printer',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_printer')." ADD `weid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_printer')) {
 if(!pdo_fieldexists('wx_school_printer',  'schoolid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_printer')." ADD `schoolid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_printer')) {
 if(!pdo_fieldexists('wx_school_printer',  'name')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_printer')." ADD `name` varchar(20) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_printer')) {
 if(!pdo_fieldexists('wx_school_printer',  'type')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_printer')." ADD `type` varchar(20) NOT NULL DEFAULT NULL DEFAULT 'feie';");
 }
}
if(pdo_tableexists('wx_school_printer')) {
 if(!pdo_fieldexists('wx_school_printer',  'print_no')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_printer')." ADD `print_no` varchar(30) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_printer')) {
 if(!pdo_fieldexists('wx_school_printer',  'member_code')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_printer')." ADD `member_code` varchar(50) NOT NULL   COMMENT '飞蛾打印机机器号';");
 }
}
if(pdo_tableexists('wx_school_printer')) {
 if(!pdo_fieldexists('wx_school_printer',  'key')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_printer')." ADD `key` varchar(30) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_printer')) {
 if(!pdo_fieldexists('wx_school_printer',  'api_key')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_printer')." ADD `api_key` varchar(100) NOT NULL   COMMENT '易联云打印机api_key';");
 }
}
if(pdo_tableexists('wx_school_printer')) {
 if(!pdo_fieldexists('wx_school_printer',  'print_nums')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_printer')." ADD `print_nums` tinyint(3) NOT NULL DEFAULT NULL DEFAULT '1';");
 }
}
if(pdo_tableexists('wx_school_printer')) {
 if(!pdo_fieldexists('wx_school_printer',  'qrcode_link')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_printer')." ADD `qrcode_link` varchar(100) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_printer')) {
 if(!pdo_fieldexists('wx_school_printer',  'print_header')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_printer')." ADD `print_header` varchar(50) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_printer')) {
 if(!pdo_fieldexists('wx_school_printer',  'print_footer')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_printer')." ADD `print_footer` varchar(50) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_printer')) {
 if(!pdo_fieldexists('wx_school_printer',  'status')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_printer')." ADD `status` tinyint(3) NOT NULL DEFAULT NULL DEFAULT '1';");
 }
}
if(pdo_tableexists('wx_school_printer')) {
 if(!pdo_fieldexists('wx_school_printer',  'delivery_type')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_printer')." ADD `delivery_type` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_printer')) {
 if(!pdo_fieldexists('wx_school_printer',  'createtime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_printer')." ADD `createtime` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_printset')) {
 if(!pdo_fieldexists('wx_school_printset',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_printset')." ADD `id` int(10) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_printset')) {
 if(!pdo_fieldexists('wx_school_printset',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_printset')." ADD `weid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_printset')) {
 if(!pdo_fieldexists('wx_school_printset',  'schoolid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_printset')." ADD `schoolid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_printset')) {
 if(!pdo_fieldexists('wx_school_printset',  'ordertype')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_printset')." ADD `ordertype` varchar(20) NOT NULL   COMMENT '缴费类型';");
 }
}
if(pdo_tableexists('wx_school_printset')) {
 if(!pdo_fieldexists('wx_school_printset',  'status')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_printset')." ADD `status` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1';");
 }
}
if(pdo_tableexists('wx_school_printset')) {
 if(!pdo_fieldexists('wx_school_printset',  'printarr')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_printset')." ADD `printarr` varchar(30) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_printset')) {
 if(!pdo_fieldexists('wx_school_printset',  'print_nums')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_printset')." ADD `print_nums` int(10) NOT NULL DEFAULT NULL DEFAULT '1';");
 }
}
if(pdo_tableexists('wx_school_printset')) {
 if(!pdo_fieldexists('wx_school_printset',  'print_header')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_printset')." ADD `print_header` varchar(50) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_printset')) {
 if(!pdo_fieldexists('wx_school_printset',  'print_footer')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_printset')." ADD `print_footer` varchar(50) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_printset')) {
 if(!pdo_fieldexists('wx_school_printset',  'qrcode_link')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_printset')." ADD `qrcode_link` varchar(1000) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_qrcode_info')) {
 if(!pdo_fieldexists('wx_school_qrcode_info',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_qrcode_info')." ADD `id` int(10) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_qrcode_info')) {
 if(!pdo_fieldexists('wx_school_qrcode_info',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_qrcode_info')." ADD `weid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_qrcode_info')) {
 if(!pdo_fieldexists('wx_school_qrcode_info',  'qrcid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_qrcode_info')." ADD `qrcid` int(10) NOT NULL   COMMENT '二维码场景ID';");
 }
}
if(pdo_tableexists('wx_school_qrcode_info')) {
 if(!pdo_fieldexists('wx_school_qrcode_info',  'gpid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_qrcode_info')." ADD `gpid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_qrcode_info')) {
 if(!pdo_fieldexists('wx_school_qrcode_info',  'name')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_qrcode_info')." ADD `name` varchar(50) NOT NULL   COMMENT '场景名称';");
 }
}
if(pdo_tableexists('wx_school_qrcode_info')) {
 if(!pdo_fieldexists('wx_school_qrcode_info',  'keyword')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_qrcode_info')." ADD `keyword` varchar(100) NOT NULL   COMMENT '关联关键字';");
 }
}
if(pdo_tableexists('wx_school_qrcode_info')) {
 if(!pdo_fieldexists('wx_school_qrcode_info',  'model')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_qrcode_info')." ADD `model` tinyint(1) NOT NULL   COMMENT '模式，1临时，2为永久';");
 }
}
if(pdo_tableexists('wx_school_qrcode_info')) {
 if(!pdo_fieldexists('wx_school_qrcode_info',  'ticket')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_qrcode_info')." ADD `ticket` varchar(250) NOT NULL   COMMENT '标识';");
 }
}
if(pdo_tableexists('wx_school_qrcode_info')) {
 if(!pdo_fieldexists('wx_school_qrcode_info',  'show_url')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_qrcode_info')." ADD `show_url` varchar(550) NOT NULL   COMMENT '图片地址';");
 }
}
if(pdo_tableexists('wx_school_qrcode_info')) {
 if(!pdo_fieldexists('wx_school_qrcode_info',  'expire')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_qrcode_info')." ADD `expire` int(10) NOT NULL   COMMENT '过期时间';");
 }
}
if(pdo_tableexists('wx_school_qrcode_info')) {
 if(!pdo_fieldexists('wx_school_qrcode_info',  'subnum')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_qrcode_info')." ADD `subnum` int(10) NOT NULL   COMMENT '关注扫描次数';");
 }
}
if(pdo_tableexists('wx_school_qrcode_info')) {
 if(!pdo_fieldexists('wx_school_qrcode_info',  'createtime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_qrcode_info')." ADD `createtime` int(10) NOT NULL   COMMENT '生成时间';");
 }
}
if(pdo_tableexists('wx_school_qrcode_info')) {
 if(!pdo_fieldexists('wx_school_qrcode_info',  'status')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_qrcode_info')." ADD `status` tinyint(1) NOT NULL   COMMENT '0为未启用，1为启用';");
 }
}
if(pdo_tableexists('wx_school_qrcode_info')) {
 if(!pdo_fieldexists('wx_school_qrcode_info',  'group_id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_qrcode_info')." ADD `group_id` int(3) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_qrcode_info')) {
 if(!pdo_fieldexists('wx_school_qrcode_info',  'rid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_qrcode_info')." ADD `rid` int(3) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_qrcode_info')) {
 if(!pdo_fieldexists('wx_school_qrcode_info',  'schoolid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_qrcode_info')." ADD `schoolid` int(10)    COMMENT '学校ID';");
 }
}
if(pdo_tableexists('wx_school_qrcode_info')) {
 if(!pdo_fieldexists('wx_school_qrcode_info',  'qr_url')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_qrcode_info')." ADD `qr_url` varchar(300) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_qrcode_info')) {
 if(!pdo_fieldexists('wx_school_qrcode_info',  'type')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_qrcode_info')." ADD `type` int(11) NOT NULL DEFAULT NULL DEFAULT '1';");
 }
}
if(pdo_tableexists('wx_school_qrcode_set')) {
 if(!pdo_fieldexists('wx_school_qrcode_set',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_qrcode_set')." ADD `id` int(10) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_qrcode_set')) {
 if(!pdo_fieldexists('wx_school_qrcode_set',  'bg')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_qrcode_set')." ADD `bg` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_qrcode_set')) {
 if(!pdo_fieldexists('wx_school_qrcode_set',  'qrleft')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_qrcode_set')." ADD `qrleft` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_qrcode_set')) {
 if(!pdo_fieldexists('wx_school_qrcode_set',  'qrtop')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_qrcode_set')." ADD `qrtop` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_qrcode_set')) {
 if(!pdo_fieldexists('wx_school_qrcode_set',  'qrwidth')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_qrcode_set')." ADD `qrwidth` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_qrcode_set')) {
 if(!pdo_fieldexists('wx_school_qrcode_set',  'qrheight')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_qrcode_set')." ADD `qrheight` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_qrcode_set')) {
 if(!pdo_fieldexists('wx_school_qrcode_set',  'model')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_qrcode_set')." ADD `model` int(10) NOT NULL DEFAULT NULL DEFAULT '1';");
 }
}
if(pdo_tableexists('wx_school_qrcode_set')) {
 if(!pdo_fieldexists('wx_school_qrcode_set',  'logoheight')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_qrcode_set')." ADD `logoheight` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_qrcode_set')) {
 if(!pdo_fieldexists('wx_school_qrcode_set',  'logowidth')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_qrcode_set')." ADD `logowidth` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_qrcode_set')) {
 if(!pdo_fieldexists('wx_school_qrcode_set',  'logoqrheight')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_qrcode_set')." ADD `logoqrheight` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_qrcode_set')) {
 if(!pdo_fieldexists('wx_school_qrcode_set',  'logoqrwidth')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_qrcode_set')." ADD `logoqrwidth` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_qrcode_statinfo')) {
 if(!pdo_fieldexists('wx_school_qrcode_statinfo',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_qrcode_statinfo')." ADD `id` int(10) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_qrcode_statinfo')) {
 if(!pdo_fieldexists('wx_school_qrcode_statinfo',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_qrcode_statinfo')." ADD `weid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_qrcode_statinfo')) {
 if(!pdo_fieldexists('wx_school_qrcode_statinfo',  'qid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_qrcode_statinfo')." ADD `qid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_qrcode_statinfo')) {
 if(!pdo_fieldexists('wx_school_qrcode_statinfo',  'openid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_qrcode_statinfo')." ADD `openid` varchar(150) NOT NULL   COMMENT '用户的唯一身份ID';");
 }
}
if(pdo_tableexists('wx_school_qrcode_statinfo')) {
 if(!pdo_fieldexists('wx_school_qrcode_statinfo',  'type')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_qrcode_statinfo')." ADD `type` tinyint(1) NOT NULL   COMMENT '是否发生在订阅时';");
 }
}
if(pdo_tableexists('wx_school_qrcode_statinfo')) {
 if(!pdo_fieldexists('wx_school_qrcode_statinfo',  'qrcid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_qrcode_statinfo')." ADD `qrcid` int(10) NOT NULL   COMMENT '二维码场景ID';");
 }
}
if(pdo_tableexists('wx_school_qrcode_statinfo')) {
 if(!pdo_fieldexists('wx_school_qrcode_statinfo',  'name')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_qrcode_statinfo')." ADD `name` varchar(50) NOT NULL   COMMENT '场景名称';");
 }
}
if(pdo_tableexists('wx_school_qrcode_statinfo')) {
 if(!pdo_fieldexists('wx_school_qrcode_statinfo',  'createtime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_qrcode_statinfo')." ADD `createtime` int(10) NOT NULL   COMMENT '生成时间';");
 }
}
if(pdo_tableexists('wx_school_qrcode_statinfo')) {
 if(!pdo_fieldexists('wx_school_qrcode_statinfo',  'group_id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_qrcode_statinfo')." ADD `group_id` int(3) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_questions')) {
 if(!pdo_fieldexists('wx_school_questions',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_questions')." ADD `id` int(10) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_questions')) {
 if(!pdo_fieldexists('wx_school_questions',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_questions')." ADD `weid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_questions')) {
 if(!pdo_fieldexists('wx_school_questions',  'schoolid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_questions')." ADD `schoolid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_questions')) {
 if(!pdo_fieldexists('wx_school_questions',  'zyid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_questions')." ADD `zyid` int(10) NOT NULL   COMMENT '作业id';");
 }
}
if(pdo_tableexists('wx_school_questions')) {
 if(!pdo_fieldexists('wx_school_questions',  'tid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_questions')." ADD `tid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_questions')) {
 if(!pdo_fieldexists('wx_school_questions',  'type')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_questions')." ADD `type` tinyint(1) NOT NULL   COMMENT '1单选2多选3提问4图片5语音6视频';");
 }
}
if(pdo_tableexists('wx_school_questions')) {
 if(!pdo_fieldexists('wx_school_questions',  'title')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_questions')." ADD `title` varchar(1000) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_questions')) {
 if(!pdo_fieldexists('wx_school_questions',  'qorder')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_questions')." ADD `qorder` int(10) NOT NULL   COMMENT '排序';");
 }
}
if(pdo_tableexists('wx_school_questions')) {
 if(!pdo_fieldexists('wx_school_questions',  'content')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_questions')." ADD `content` varchar(1000) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_questions')) {
 if(!pdo_fieldexists('wx_school_questions',  'AnsType')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_questions')." ADD `AnsType` varchar(200) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_record')) {
 if(!pdo_fieldexists('wx_school_record',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_record')." ADD `id` int(10) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_record')) {
 if(!pdo_fieldexists('wx_school_record',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_record')." ADD `weid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_record')) {
 if(!pdo_fieldexists('wx_school_record',  'schoolid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_record')." ADD `schoolid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_record')) {
 if(!pdo_fieldexists('wx_school_record',  'noticeid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_record')." ADD `noticeid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_record')) {
 if(!pdo_fieldexists('wx_school_record',  'userid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_record')." ADD `userid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_record')) {
 if(!pdo_fieldexists('wx_school_record',  'tid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_record')." ADD `tid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_record')) {
 if(!pdo_fieldexists('wx_school_record',  'sid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_record')." ADD `sid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_record')) {
 if(!pdo_fieldexists('wx_school_record',  'openid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_record')." ADD `openid` varchar(30) NOT NULL   COMMENT 'openid';");
 }
}
if(pdo_tableexists('wx_school_record')) {
 if(!pdo_fieldexists('wx_school_record',  'type')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_record')." ADD `type` int(1) NOT NULL   COMMENT '类型1通知2作业';");
 }
}
if(pdo_tableexists('wx_school_record')) {
 if(!pdo_fieldexists('wx_school_record',  'createtime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_record')." ADD `createtime` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_record')) {
 if(!pdo_fieldexists('wx_school_record',  'readtime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_record')." ADD `readtime` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_reply')) {
 if(!pdo_fieldexists('wx_school_reply',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_reply')." ADD `id` int(10) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_reply')) {
 if(!pdo_fieldexists('wx_school_reply',  'rid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_reply')." ADD `rid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_reply')) {
 if(!pdo_fieldexists('wx_school_reply',  'schoolid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_reply')." ADD `schoolid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_scforxs')) {
 if(!pdo_fieldexists('wx_school_scforxs',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_scforxs')." ADD `id` int(10) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_scforxs')) {
 if(!pdo_fieldexists('wx_school_scforxs',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_scforxs')." ADD `weid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_scforxs')) {
 if(!pdo_fieldexists('wx_school_scforxs',  'schoolid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_scforxs')." ADD `schoolid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_scforxs')) {
 if(!pdo_fieldexists('wx_school_scforxs',  'scid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_scforxs')." ADD `scid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_scforxs')) {
 if(!pdo_fieldexists('wx_school_scforxs',  'setid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_scforxs')." ADD `setid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_scforxs')) {
 if(!pdo_fieldexists('wx_school_scforxs',  'tid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_scforxs')." ADD `tid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_scforxs')) {
 if(!pdo_fieldexists('wx_school_scforxs',  'sid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_scforxs')." ADD `sid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_scforxs')) {
 if(!pdo_fieldexists('wx_school_scforxs',  'userid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_scforxs')." ADD `userid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_scforxs')) {
 if(!pdo_fieldexists('wx_school_scforxs',  'iconsetid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_scforxs')." ADD `iconsetid` int(10) NOT NULL   COMMENT '评价id';");
 }
}
if(pdo_tableexists('wx_school_scforxs')) {
 if(!pdo_fieldexists('wx_school_scforxs',  'iconlevel')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_scforxs')." ADD `iconlevel` int(10) NOT NULL   COMMENT '本评价等级';");
 }
}
if(pdo_tableexists('wx_school_scforxs')) {
 if(!pdo_fieldexists('wx_school_scforxs',  'tword')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_scforxs')." ADD `tword` varchar(1000)    COMMENT '老师评语';");
 }
}
if(pdo_tableexists('wx_school_scforxs')) {
 if(!pdo_fieldexists('wx_school_scforxs',  'jzword')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_scforxs')." ADD `jzword` varchar(1000)    COMMENT '家长评语';");
 }
}
if(pdo_tableexists('wx_school_scforxs')) {
 if(!pdo_fieldexists('wx_school_scforxs',  'dianzan')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_scforxs')." ADD `dianzan` varchar(1000)    COMMENT '点赞数';");
 }
}
if(pdo_tableexists('wx_school_scforxs')) {
 if(!pdo_fieldexists('wx_school_scforxs',  'dianzopenid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_scforxs')." ADD `dianzopenid` varchar(500)    COMMENT '点赞人openid';");
 }
}
if(pdo_tableexists('wx_school_scforxs')) {
 if(!pdo_fieldexists('wx_school_scforxs',  'fromto')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_scforxs')." ADD `fromto` tinyint(1) NOT NULL   COMMENT '1来自老师2来自家长';");
 }
}
if(pdo_tableexists('wx_school_scforxs')) {
 if(!pdo_fieldexists('wx_school_scforxs',  'type')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_scforxs')." ADD `type` tinyint(1) NOT NULL   COMMENT '1文字2表现评价3点赞';");
 }
}
if(pdo_tableexists('wx_school_scforxs')) {
 if(!pdo_fieldexists('wx_school_scforxs',  'createtime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_scforxs')." ADD `createtime` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_scforxs')) {
 if(!pdo_fieldexists('wx_school_scforxs',  'ssort')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_scforxs')." ADD `ssort` int(10) NOT NULL   COMMENT '排序';");
 }
}
if(pdo_tableexists('wx_school_schoolset')) {
 if(!pdo_fieldexists('wx_school_schoolset',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_schoolset')." ADD `id` int(11) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_schoolset')) {
 if(!pdo_fieldexists('wx_school_schoolset',  'alivodappid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_schoolset')." ADD `alivodappid` varchar(100) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_schoolset')) {
 if(!pdo_fieldexists('wx_school_schoolset',  'alivodkey')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_schoolset')." ADD `alivodkey` varchar(100) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_schoolset')) {
 if(!pdo_fieldexists('wx_school_schoolset',  'alivodcate')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_schoolset')." ADD `alivodcate` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_schoolset')) {
 if(!pdo_fieldexists('wx_school_schoolset',  'schoolid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_schoolset')." ADD `schoolid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_schoolset')) {
 if(!pdo_fieldexists('wx_school_schoolset',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_schoolset')." ADD `weid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_schoolset')) {
 if(!pdo_fieldexists('wx_school_schoolset',  'is_bigdata')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_schoolset')." ADD `is_bigdata` tinyint(3) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_schoolset')) {
 if(!pdo_fieldexists('wx_school_schoolset',  'pwd')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_schoolset')." ADD `pwd` varchar(64) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_schoolset')) {
 if(!pdo_fieldexists('wx_school_schoolset',  'short_url')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_schoolset')." ADD `short_url` varchar(32) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_schoolset')) {
 if(!pdo_fieldexists('wx_school_schoolset',  'bgtitle')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_schoolset')." ADD `bgtitle` varchar(100) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_schoolset')) {
 if(!pdo_fieldexists('wx_school_schoolset',  'refund')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_schoolset')." ADD `refund` tinyint(1) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_score')) {
 if(!pdo_fieldexists('wx_school_score',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_score')." ADD `id` int(11) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_score')) {
 if(!pdo_fieldexists('wx_school_score',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_score')." ADD `weid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_score')) {
 if(!pdo_fieldexists('wx_school_score',  'schoolid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_score')." ADD `schoolid` int(10) NOT NULL   COMMENT '分校id';");
 }
}
if(pdo_tableexists('wx_school_score')) {
 if(!pdo_fieldexists('wx_school_score',  'xq_id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_score')." ADD `xq_id` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_score')) {
 if(!pdo_fieldexists('wx_school_score',  'bj_id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_score')." ADD `bj_id` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_score')) {
 if(!pdo_fieldexists('wx_school_score',  'qh_id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_score')." ADD `qh_id` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_score')) {
 if(!pdo_fieldexists('wx_school_score',  'km_id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_score')." ADD `km_id` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_score')) {
 if(!pdo_fieldexists('wx_school_score',  'sid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_score')." ADD `sid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_score')) {
 if(!pdo_fieldexists('wx_school_score',  'my_score')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_score')." ADD `my_score` varchar(50) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_score')) {
 if(!pdo_fieldexists('wx_school_score',  'info')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_score')." ADD `info` varchar(1000) NOT NULL   COMMENT '教师评价';");
 }
}
if(pdo_tableexists('wx_school_score')) {
 if(!pdo_fieldexists('wx_school_score',  'createtime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_score')." ADD `createtime` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_score')) {
 if(!pdo_fieldexists('wx_school_score',  'is_absent')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_score')." ADD `is_absent` tinyint(3)    COMMENT '1缺考0未缺考';");
 }
}
if(pdo_tableexists('wx_school_set')) {
 if(!pdo_fieldexists('wx_school_set',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_set')." ADD `id` int(10) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_set')) {
 if(!pdo_fieldexists('wx_school_set',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_set')." ADD `weid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_set')) {
 if(!pdo_fieldexists('wx_school_set',  'istplnotice')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_set')." ADD `istplnotice` tinyint(1) NOT NULL   COMMENT '是否模版通知';");
 }
}
if(pdo_tableexists('wx_school_set')) {
 if(!pdo_fieldexists('wx_school_set',  'guanli')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_set')." ADD `guanli` tinyint(1) NOT NULL   COMMENT '管理方式';");
 }
}
if(pdo_tableexists('wx_school_set')) {
 if(!pdo_fieldexists('wx_school_set',  'xsqingjia')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_set')." ADD `xsqingjia` varchar(200)    COMMENT '学生请假申请ID';");
 }
}
if(pdo_tableexists('wx_school_set')) {
 if(!pdo_fieldexists('wx_school_set',  'xsqjsh')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_set')." ADD `xsqjsh` varchar(200)    COMMENT '学生请假审核通知ID';");
 }
}
if(pdo_tableexists('wx_school_set')) {
 if(!pdo_fieldexists('wx_school_set',  'jsqingjia')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_set')." ADD `jsqingjia` varchar(200)    COMMENT '教员请假申请体提醒ID';");
 }
}
if(pdo_tableexists('wx_school_set')) {
 if(!pdo_fieldexists('wx_school_set',  'jsqjsh')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_set')." ADD `jsqjsh` varchar(200)    COMMENT '教员请假审核通知ID';");
 }
}
if(pdo_tableexists('wx_school_set')) {
 if(!pdo_fieldexists('wx_school_set',  'xxtongzhi')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_set')." ADD `xxtongzhi` varchar(200)    COMMENT '学校通知ID';");
 }
}
if(pdo_tableexists('wx_school_set')) {
 if(!pdo_fieldexists('wx_school_set',  'liuyan')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_set')." ADD `liuyan` varchar(200)    COMMENT '家长留言ID';");
 }
}
if(pdo_tableexists('wx_school_set')) {
 if(!pdo_fieldexists('wx_school_set',  'liuyanhf')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_set')." ADD `liuyanhf` varchar(200)    COMMENT '教师回复家长留言ID';");
 }
}
if(pdo_tableexists('wx_school_set')) {
 if(!pdo_fieldexists('wx_school_set',  'zuoye')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_set')." ADD `zuoye` varchar(200)    COMMENT '发布作业提醒ID';");
 }
}
if(pdo_tableexists('wx_school_set')) {
 if(!pdo_fieldexists('wx_school_set',  'bjtz')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_set')." ADD `bjtz` varchar(200)    COMMENT '班级通知ID';");
 }
}
if(pdo_tableexists('wx_school_set')) {
 if(!pdo_fieldexists('wx_school_set',  'bjqshjg')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_set')." ADD `bjqshjg` varchar(200)    COMMENT '班级圈审核结果';");
 }
}
if(pdo_tableexists('wx_school_set')) {
 if(!pdo_fieldexists('wx_school_set',  'bjqshtz')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_set')." ADD `bjqshtz` varchar(200)    COMMENT '班级圈审核提醒';");
 }
}
if(pdo_tableexists('wx_school_set')) {
 if(!pdo_fieldexists('wx_school_set',  'jxlxtx')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_set')." ADD `jxlxtx` varchar(200)    COMMENT '进校提醒';");
 }
}
if(pdo_tableexists('wx_school_set')) {
 if(!pdo_fieldexists('wx_school_set',  'jfjgtz')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_set')." ADD `jfjgtz` varchar(200)    COMMENT '缴费结果通知';");
 }
}
if(pdo_tableexists('wx_school_set')) {
 if(!pdo_fieldexists('wx_school_set',  'bd_set')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_set')." ADD `bd_set` varchar(1000);");
 }
}
if(pdo_tableexists('wx_school_set')) {
 if(!pdo_fieldexists('wx_school_set',  'sms_acss')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_set')." ADD `sms_acss` varchar(1000);");
 }
}
if(pdo_tableexists('wx_school_set')) {
 if(!pdo_fieldexists('wx_school_set',  'sms_use_times')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_set')." ADD `sms_use_times` int(10) NOT NULL   COMMENT '短信调用次数';");
 }
}
if(pdo_tableexists('wx_school_set')) {
 if(!pdo_fieldexists('wx_school_set',  'htname')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_set')." ADD `htname` varchar(200)    COMMENT '后台系统名称';");
 }
}
if(pdo_tableexists('wx_school_set')) {
 if(!pdo_fieldexists('wx_school_set',  'bgcolor')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_set')." ADD `bgcolor` varchar(20)    COMMENT '后台系统背景颜色';");
 }
}
if(pdo_tableexists('wx_school_set')) {
 if(!pdo_fieldexists('wx_school_set',  'bgimg')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_set')." ADD `bgimg` varchar(200);");
 }
}
if(pdo_tableexists('wx_school_set')) {
 if(!pdo_fieldexists('wx_school_set',  'banner1')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_set')." ADD `banner1` varchar(200);");
 }
}
if(pdo_tableexists('wx_school_set')) {
 if(!pdo_fieldexists('wx_school_set',  'banner2')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_set')." ADD `banner2` varchar(200);");
 }
}
if(pdo_tableexists('wx_school_set')) {
 if(!pdo_fieldexists('wx_school_set',  'banner3')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_set')." ADD `banner3` varchar(200);");
 }
}
if(pdo_tableexists('wx_school_set')) {
 if(!pdo_fieldexists('wx_school_set',  'banner4')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_set')." ADD `banner4` varchar(200);");
 }
}
if(pdo_tableexists('wx_school_set')) {
 if(!pdo_fieldexists('wx_school_set',  'sykstx')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_set')." ADD `sykstx` varchar(300) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_set')) {
 if(!pdo_fieldexists('wx_school_set',  'kcyytx')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_set')." ADD `kcyytx` varchar(300) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_set')) {
 if(!pdo_fieldexists('wx_school_set',  'kcqdtx')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_set')." ADD `kcqdtx` varchar(300) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_set')) {
 if(!pdo_fieldexists('wx_school_set',  'sktxls')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_set')." ADD `sktxls` varchar(300) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_set')) {
 if(!pdo_fieldexists('wx_school_set',  'newcenteriocn')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_set')." ADD `newcenteriocn` varchar(1000) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_set')) {
 if(!pdo_fieldexists('wx_school_set',  'is_new')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_set')." ADD `is_new` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1'  COMMENT '新旧风格';");
 }
}
if(pdo_tableexists('wx_school_set')) {
 if(!pdo_fieldexists('wx_school_set',  'banquan')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_set')." ADD `banquan` varchar(200) NOT NULL   COMMENT '版权';");
 }
}
if(pdo_tableexists('wx_school_set')) {
 if(!pdo_fieldexists('wx_school_set',  'sensitive_word')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_set')." ADD `sensitive_word` mediumtext() NOT NULL   COMMENT '敏感词库';");
 }
}
if(pdo_tableexists('wx_school_set')) {
 if(!pdo_fieldexists('wx_school_set',  'school_max')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_set')." ADD `school_max` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_set')) {
 if(!pdo_fieldexists('wx_school_set',  'baidumapapi')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_set')." ADD `baidumapapi` varchar(200);");
 }
}
if(pdo_tableexists('wx_school_set')) {
 if(!pdo_fieldexists('wx_school_set',  'fkyytx')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_set')." ADD `fkyytx` varchar(300)    COMMENT '访客消息推送模板ID';");
 }
}
if(pdo_tableexists('wx_school_shouce')) {
 if(!pdo_fieldexists('wx_school_shouce',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_shouce')." ADD `id` int(10) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_shouce')) {
 if(!pdo_fieldexists('wx_school_shouce',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_shouce')." ADD `weid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_shouce')) {
 if(!pdo_fieldexists('wx_school_shouce',  'schoolid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_shouce')." ADD `schoolid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_shouce')) {
 if(!pdo_fieldexists('wx_school_shouce',  'bj_id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_shouce')." ADD `bj_id` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_shouce')) {
 if(!pdo_fieldexists('wx_school_shouce',  'xq_id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_shouce')." ADD `xq_id` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_shouce')) {
 if(!pdo_fieldexists('wx_school_shouce',  'tid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_shouce')." ADD `tid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_shouce')) {
 if(!pdo_fieldexists('wx_school_shouce',  'title')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_shouce')." ADD `title` varchar(1000);");
 }
}
if(pdo_tableexists('wx_school_shouce')) {
 if(!pdo_fieldexists('wx_school_shouce',  'setid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_shouce')." ADD `setid` int(10) NOT NULL   COMMENT '设置ID';");
 }
}
if(pdo_tableexists('wx_school_shouce')) {
 if(!pdo_fieldexists('wx_school_shouce',  'kcid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_shouce')." ADD `kcid` int(10) NOT NULL   COMMENT '课程ID';");
 }
}
if(pdo_tableexists('wx_school_shouce')) {
 if(!pdo_fieldexists('wx_school_shouce',  'ksid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_shouce')." ADD `ksid` int(10) NOT NULL   COMMENT '课时ID';");
 }
}
if(pdo_tableexists('wx_school_shouce')) {
 if(!pdo_fieldexists('wx_school_shouce',  'starttime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_shouce')." ADD `starttime` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_shouce')) {
 if(!pdo_fieldexists('wx_school_shouce',  'endtime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_shouce')." ADD `endtime` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_shouce')) {
 if(!pdo_fieldexists('wx_school_shouce',  'createtime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_shouce')." ADD `createtime` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_shouce')) {
 if(!pdo_fieldexists('wx_school_shouce',  'sendtype')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_shouce')." ADD `sendtype` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1'  COMMENT '1未发送2部分发送3全部发送';");
 }
}
if(pdo_tableexists('wx_school_shouce')) {
 if(!pdo_fieldexists('wx_school_shouce',  'ssort')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_shouce')." ADD `ssort` int(10) NOT NULL   COMMENT '排序';");
 }
}
if(pdo_tableexists('wx_school_shoucepyk')) {
 if(!pdo_fieldexists('wx_school_shoucepyk',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_shoucepyk')." ADD `id` int(10) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_shoucepyk')) {
 if(!pdo_fieldexists('wx_school_shoucepyk',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_shoucepyk')." ADD `weid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_shoucepyk')) {
 if(!pdo_fieldexists('wx_school_shoucepyk',  'schoolid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_shoucepyk')." ADD `schoolid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_shoucepyk')) {
 if(!pdo_fieldexists('wx_school_shoucepyk',  'bj_id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_shoucepyk')." ADD `bj_id` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_shoucepyk')) {
 if(!pdo_fieldexists('wx_school_shoucepyk',  'tid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_shoucepyk')." ADD `tid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_shoucepyk')) {
 if(!pdo_fieldexists('wx_school_shoucepyk',  'title')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_shoucepyk')." ADD `title` text()    COMMENT '内容';");
 }
}
if(pdo_tableexists('wx_school_shoucepyk')) {
 if(!pdo_fieldexists('wx_school_shoucepyk',  'createtime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_shoucepyk')." ADD `createtime` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_shoucepyk')) {
 if(!pdo_fieldexists('wx_school_shoucepyk',  'ssort')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_shoucepyk')." ADD `ssort` int(10) NOT NULL   COMMENT '排序';");
 }
}
if(pdo_tableexists('wx_school_shouceset')) {
 if(!pdo_fieldexists('wx_school_shouceset',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_shouceset')." ADD `id` int(10) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_shouceset')) {
 if(!pdo_fieldexists('wx_school_shouceset',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_shouceset')." ADD `weid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_shouceset')) {
 if(!pdo_fieldexists('wx_school_shouceset',  'schoolid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_shouceset')." ADD `schoolid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_shouceset')) {
 if(!pdo_fieldexists('wx_school_shouceset',  'title')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_shouceset')." ADD `title` varchar(7);");
 }
}
if(pdo_tableexists('wx_school_shouceset')) {
 if(!pdo_fieldexists('wx_school_shouceset',  'bottext')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_shouceset')." ADD `bottext` varchar(7);");
 }
}
if(pdo_tableexists('wx_school_shouceset')) {
 if(!pdo_fieldexists('wx_school_shouceset',  'boturl')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_shouceset')." ADD `boturl` varchar(1000);");
 }
}
if(pdo_tableexists('wx_school_shouceset')) {
 if(!pdo_fieldexists('wx_school_shouceset',  'lasttxet')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_shouceset')." ADD `lasttxet` varchar(7);");
 }
}
if(pdo_tableexists('wx_school_shouceset')) {
 if(!pdo_fieldexists('wx_school_shouceset',  'nj_id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_shouceset')." ADD `nj_id` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_shouceset')) {
 if(!pdo_fieldexists('wx_school_shouceset',  'icon')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_shouceset')." ADD `icon` varchar(1000);");
 }
}
if(pdo_tableexists('wx_school_shouceset')) {
 if(!pdo_fieldexists('wx_school_shouceset',  'bg1')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_shouceset')." ADD `bg1` varchar(1000);");
 }
}
if(pdo_tableexists('wx_school_shouceset')) {
 if(!pdo_fieldexists('wx_school_shouceset',  'bg2')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_shouceset')." ADD `bg2` varchar(1000);");
 }
}
if(pdo_tableexists('wx_school_shouceset')) {
 if(!pdo_fieldexists('wx_school_shouceset',  'bg3')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_shouceset')." ADD `bg3` varchar(1000);");
 }
}
if(pdo_tableexists('wx_school_shouceset')) {
 if(!pdo_fieldexists('wx_school_shouceset',  'bg4')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_shouceset')." ADD `bg4` varchar(1000);");
 }
}
if(pdo_tableexists('wx_school_shouceset')) {
 if(!pdo_fieldexists('wx_school_shouceset',  'bg5')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_shouceset')." ADD `bg5` varchar(1000);");
 }
}
if(pdo_tableexists('wx_school_shouceset')) {
 if(!pdo_fieldexists('wx_school_shouceset',  'bg6')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_shouceset')." ADD `bg6` varchar(1000);");
 }
}
if(pdo_tableexists('wx_school_shouceset')) {
 if(!pdo_fieldexists('wx_school_shouceset',  'bgm')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_shouceset')." ADD `bgm` varchar(1000);");
 }
}
if(pdo_tableexists('wx_school_shouceset')) {
 if(!pdo_fieldexists('wx_school_shouceset',  'top1')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_shouceset')." ADD `top1` varchar(1000);");
 }
}
if(pdo_tableexists('wx_school_shouceset')) {
 if(!pdo_fieldexists('wx_school_shouceset',  'top2')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_shouceset')." ADD `top2` varchar(1000);");
 }
}
if(pdo_tableexists('wx_school_shouceset')) {
 if(!pdo_fieldexists('wx_school_shouceset',  'top3')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_shouceset')." ADD `top3` varchar(1000);");
 }
}
if(pdo_tableexists('wx_school_shouceset')) {
 if(!pdo_fieldexists('wx_school_shouceset',  'top4')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_shouceset')." ADD `top4` varchar(1000);");
 }
}
if(pdo_tableexists('wx_school_shouceset')) {
 if(!pdo_fieldexists('wx_school_shouceset',  'top5')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_shouceset')." ADD `top5` varchar(1000);");
 }
}
if(pdo_tableexists('wx_school_shouceset')) {
 if(!pdo_fieldexists('wx_school_shouceset',  'guidword1')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_shouceset')." ADD `guidword1` varchar(20);");
 }
}
if(pdo_tableexists('wx_school_shouceset')) {
 if(!pdo_fieldexists('wx_school_shouceset',  'guidword2')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_shouceset')." ADD `guidword2` varchar(20);");
 }
}
if(pdo_tableexists('wx_school_shouceset')) {
 if(!pdo_fieldexists('wx_school_shouceset',  'guidurl')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_shouceset')." ADD `guidurl` varchar(1000);");
 }
}
if(pdo_tableexists('wx_school_shouceset')) {
 if(!pdo_fieldexists('wx_school_shouceset',  'createtime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_shouceset')." ADD `createtime` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_shouceset')) {
 if(!pdo_fieldexists('wx_school_shouceset',  'allowshare')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_shouceset')." ADD `allowshare` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1'  COMMENT '1允许2禁止';");
 }
}
if(pdo_tableexists('wx_school_shouceset')) {
 if(!pdo_fieldexists('wx_school_shouceset',  'ssort')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_shouceset')." ADD `ssort` int(10) NOT NULL   COMMENT '排序';");
 }
}
if(pdo_tableexists('wx_school_shouceseticon')) {
 if(!pdo_fieldexists('wx_school_shouceseticon',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_shouceseticon')." ADD `id` int(10) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_shouceseticon')) {
 if(!pdo_fieldexists('wx_school_shouceseticon',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_shouceseticon')." ADD `weid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_shouceseticon')) {
 if(!pdo_fieldexists('wx_school_shouceseticon',  'schoolid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_shouceseticon')." ADD `schoolid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_shouceseticon')) {
 if(!pdo_fieldexists('wx_school_shouceseticon',  'setid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_shouceseticon')." ADD `setid` int(10) NOT NULL   COMMENT '设置ID';");
 }
}
if(pdo_tableexists('wx_school_shouceseticon')) {
 if(!pdo_fieldexists('wx_school_shouceseticon',  'title')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_shouceseticon')." ADD `title` varchar(7);");
 }
}
if(pdo_tableexists('wx_school_shouceseticon')) {
 if(!pdo_fieldexists('wx_school_shouceseticon',  'icon1title')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_shouceseticon')." ADD `icon1title` varchar(10);");
 }
}
if(pdo_tableexists('wx_school_shouceseticon')) {
 if(!pdo_fieldexists('wx_school_shouceseticon',  'icon2title')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_shouceseticon')." ADD `icon2title` varchar(10);");
 }
}
if(pdo_tableexists('wx_school_shouceseticon')) {
 if(!pdo_fieldexists('wx_school_shouceseticon',  'icon3title')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_shouceseticon')." ADD `icon3title` varchar(10);");
 }
}
if(pdo_tableexists('wx_school_shouceseticon')) {
 if(!pdo_fieldexists('wx_school_shouceseticon',  'icon4title')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_shouceseticon')." ADD `icon4title` varchar(10);");
 }
}
if(pdo_tableexists('wx_school_shouceseticon')) {
 if(!pdo_fieldexists('wx_school_shouceseticon',  'icon5title')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_shouceseticon')." ADD `icon5title` varchar(10);");
 }
}
if(pdo_tableexists('wx_school_shouceseticon')) {
 if(!pdo_fieldexists('wx_school_shouceseticon',  'icon1')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_shouceseticon')." ADD `icon1` varchar(1000);");
 }
}
if(pdo_tableexists('wx_school_shouceseticon')) {
 if(!pdo_fieldexists('wx_school_shouceseticon',  'icon2')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_shouceseticon')." ADD `icon2` varchar(1000);");
 }
}
if(pdo_tableexists('wx_school_shouceseticon')) {
 if(!pdo_fieldexists('wx_school_shouceseticon',  'icon3')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_shouceseticon')." ADD `icon3` varchar(1000);");
 }
}
if(pdo_tableexists('wx_school_shouceseticon')) {
 if(!pdo_fieldexists('wx_school_shouceseticon',  'icon4')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_shouceseticon')." ADD `icon4` varchar(1000);");
 }
}
if(pdo_tableexists('wx_school_shouceseticon')) {
 if(!pdo_fieldexists('wx_school_shouceseticon',  'icon5')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_shouceseticon')." ADD `icon5` varchar(1000);");
 }
}
if(pdo_tableexists('wx_school_shouceseticon')) {
 if(!pdo_fieldexists('wx_school_shouceseticon',  'type')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_shouceseticon')." ADD `type` tinyint(1) NOT NULL   COMMENT '1教师使用2家长';");
 }
}
if(pdo_tableexists('wx_school_shouceseticon')) {
 if(!pdo_fieldexists('wx_school_shouceseticon',  'ssort')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_shouceseticon')." ADD `ssort` int(10) NOT NULL   COMMENT '排序';");
 }
}
if(pdo_tableexists('wx_school_signup')) {
 if(!pdo_fieldexists('wx_school_signup',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_signup')." ADD `id` int(10) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_signup')) {
 if(!pdo_fieldexists('wx_school_signup',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_signup')." ADD `weid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_signup')) {
 if(!pdo_fieldexists('wx_school_signup',  'schoolid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_signup')." ADD `schoolid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_signup')) {
 if(!pdo_fieldexists('wx_school_signup',  'icon')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_signup')." ADD `icon` varchar(255) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_signup')) {
 if(!pdo_fieldexists('wx_school_signup',  'name')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_signup')." ADD `name` varchar(50) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_signup')) {
 if(!pdo_fieldexists('wx_school_signup',  'numberid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_signup')." ADD `numberid` int(11);");
 }
}
if(pdo_tableexists('wx_school_signup')) {
 if(!pdo_fieldexists('wx_school_signup',  'sex')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_signup')." ADD `sex` int(1) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_signup')) {
 if(!pdo_fieldexists('wx_school_signup',  'mobile')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_signup')." ADD `mobile` char(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_signup')) {
 if(!pdo_fieldexists('wx_school_signup',  'nj_id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_signup')." ADD `nj_id` int(10) NOT NULL   COMMENT '年级ID';");
 }
}
if(pdo_tableexists('wx_school_signup')) {
 if(!pdo_fieldexists('wx_school_signup',  'bj_id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_signup')." ADD `bj_id` int(10) NOT NULL   COMMENT '班级ID';");
 }
}
if(pdo_tableexists('wx_school_signup')) {
 if(!pdo_fieldexists('wx_school_signup',  'idcard')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_signup')." ADD `idcard` varchar(18) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_signup')) {
 if(!pdo_fieldexists('wx_school_signup',  'cost')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_signup')." ADD `cost` varchar(10) NOT NULL   COMMENT '报名费用';");
 }
}
if(pdo_tableexists('wx_school_signup')) {
 if(!pdo_fieldexists('wx_school_signup',  'birthday')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_signup')." ADD `birthday` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_signup')) {
 if(!pdo_fieldexists('wx_school_signup',  'createtime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_signup')." ADD `createtime` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_signup')) {
 if(!pdo_fieldexists('wx_school_signup',  'passtime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_signup')." ADD `passtime` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_signup')) {
 if(!pdo_fieldexists('wx_school_signup',  'lasttime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_signup')." ADD `lasttime` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_signup')) {
 if(!pdo_fieldexists('wx_school_signup',  'uid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_signup')." ADD `uid` int(10) NOT NULL   COMMENT '发布者UID';");
 }
}
if(pdo_tableexists('wx_school_signup')) {
 if(!pdo_fieldexists('wx_school_signup',  'orderid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_signup')." ADD `orderid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_signup')) {
 if(!pdo_fieldexists('wx_school_signup',  'openid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_signup')." ADD `openid` varchar(30) NOT NULL   COMMENT 'openid';");
 }
}
if(pdo_tableexists('wx_school_signup')) {
 if(!pdo_fieldexists('wx_school_signup',  'pard')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_signup')." ADD `pard` tinyint(1) NOT NULL   COMMENT '关系';");
 }
}
if(pdo_tableexists('wx_school_signup')) {
 if(!pdo_fieldexists('wx_school_signup',  'status')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_signup')." ADD `status` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1'  COMMENT '1审核中2审核通过3不通过';");
 }
}
if(pdo_tableexists('wx_school_signup')) {
 if(!pdo_fieldexists('wx_school_signup',  'sid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_signup')." ADD `sid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_signup')) {
 if(!pdo_fieldexists('wx_school_signup',  'picarr1')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_signup')." ADD `picarr1` varchar(1000) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_signup')) {
 if(!pdo_fieldexists('wx_school_signup',  'picarr2')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_signup')." ADD `picarr2` varchar(1000) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_signup')) {
 if(!pdo_fieldexists('wx_school_signup',  'picarr3')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_signup')." ADD `picarr3` varchar(1000) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_signup')) {
 if(!pdo_fieldexists('wx_school_signup',  'picarr4')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_signup')." ADD `picarr4` varchar(1000) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_signup')) {
 if(!pdo_fieldexists('wx_school_signup',  'picarr5')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_signup')." ADD `picarr5` varchar(1000) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_signup')) {
 if(!pdo_fieldexists('wx_school_signup',  'textarr1')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_signup')." ADD `textarr1` varchar(1000) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_signup')) {
 if(!pdo_fieldexists('wx_school_signup',  'textarr2')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_signup')." ADD `textarr2` varchar(1000) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_signup')) {
 if(!pdo_fieldexists('wx_school_signup',  'textarr3')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_signup')." ADD `textarr3` varchar(1000) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_signup')) {
 if(!pdo_fieldexists('wx_school_signup',  'textarr4')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_signup')." ADD `textarr4` varchar(1000) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_signup')) {
 if(!pdo_fieldexists('wx_school_signup',  'textarr5')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_signup')." ADD `textarr5` varchar(1000) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_signup')) {
 if(!pdo_fieldexists('wx_school_signup',  'textarr6')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_signup')." ADD `textarr6` varchar(1000) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_signup')) {
 if(!pdo_fieldexists('wx_school_signup',  'textarr7')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_signup')." ADD `textarr7` varchar(1000) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_signup')) {
 if(!pdo_fieldexists('wx_school_signup',  'textarr8')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_signup')." ADD `textarr8` varchar(1000) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_signup')) {
 if(!pdo_fieldexists('wx_school_signup',  'textarr9')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_signup')." ADD `textarr9` varchar(1000) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_signup')) {
 if(!pdo_fieldexists('wx_school_signup',  'textarr10')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_signup')." ADD `textarr10` varchar(1000) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_sms_log')) {
 if(!pdo_fieldexists('wx_school_sms_log',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_sms_log')." ADD `id` int(10) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_sms_log')) {
 if(!pdo_fieldexists('wx_school_sms_log',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_sms_log')." ADD `weid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_sms_log')) {
 if(!pdo_fieldexists('wx_school_sms_log',  'schoolid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_sms_log')." ADD `schoolid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_sms_log')) {
 if(!pdo_fieldexists('wx_school_sms_log',  'type')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_sms_log')." ADD `type` varchar(100) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_sms_log')) {
 if(!pdo_fieldexists('wx_school_sms_log',  'mobile')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_sms_log')." ADD `mobile` varchar(15) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_sms_log')) {
 if(!pdo_fieldexists('wx_school_sms_log',  'sendtime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_sms_log')." ADD `sendtime` int(10) NOT NULL   COMMENT '生成时间';");
 }
}
if(pdo_tableexists('wx_school_sms_log')) {
 if(!pdo_fieldexists('wx_school_sms_log',  'msg')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_sms_log')." ADD `msg` varchar(1000) NOT NULL   COMMENT '返回消息';");
 }
}
if(pdo_tableexists('wx_school_sms_log')) {
 if(!pdo_fieldexists('wx_school_sms_log',  'status')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_sms_log')." ADD `status` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1'  COMMENT '默认成功1';");
 }
}
if(pdo_tableexists('wx_school_students')) {
 if(!pdo_fieldexists('wx_school_students',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_students')." ADD `id` int(11) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_students')) {
 if(!pdo_fieldexists('wx_school_students',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_students')." ADD `weid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_students')) {
 if(!pdo_fieldexists('wx_school_students',  'schoolid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_students')." ADD `schoolid` int(10) NOT NULL   COMMENT '分校id';");
 }
}
if(pdo_tableexists('wx_school_students')) {
 if(!pdo_fieldexists('wx_school_students',  'icon')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_students')." ADD `icon` varchar(255) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_students')) {
 if(!pdo_fieldexists('wx_school_students',  'numberid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_students')." ADD `numberid` varchar(18)    COMMENT '学号';");
 }
}
if(pdo_tableexists('wx_school_students')) {
 if(!pdo_fieldexists('wx_school_students',  'xq_id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_students')." ADD `xq_id` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_students')) {
 if(!pdo_fieldexists('wx_school_students',  'area_addr')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_students')." ADD `area_addr` varchar(200) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_students')) {
 if(!pdo_fieldexists('wx_school_students',  'ck_id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_students')." ADD `ck_id` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_students')) {
 if(!pdo_fieldexists('wx_school_students',  'bj_id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_students')." ADD `bj_id` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_students')) {
 if(!pdo_fieldexists('wx_school_students',  'birthdate')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_students')." ADD `birthdate` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_students')) {
 if(!pdo_fieldexists('wx_school_students',  'sex')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_students')." ADD `sex` int(1) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_students')) {
 if(!pdo_fieldexists('wx_school_students',  'createdate')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_students')." ADD `createdate` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_students')) {
 if(!pdo_fieldexists('wx_school_students',  'seffectivetime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_students')." ADD `seffectivetime` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_students')) {
 if(!pdo_fieldexists('wx_school_students',  'stheendtime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_students')." ADD `stheendtime` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_students')) {
 if(!pdo_fieldexists('wx_school_students',  'jf_statu')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_students')." ADD `jf_statu` int(11);");
 }
}
if(pdo_tableexists('wx_school_students')) {
 if(!pdo_fieldexists('wx_school_students',  'mobile')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_students')." ADD `mobile` char(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_students')) {
 if(!pdo_fieldexists('wx_school_students',  'homephone')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_students')." ADD `homephone` char(16) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_students')) {
 if(!pdo_fieldexists('wx_school_students',  's_name')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_students')." ADD `s_name` varchar(50) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_students')) {
 if(!pdo_fieldexists('wx_school_students',  'localdate_id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_students')." ADD `localdate_id` char(20) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_students')) {
 if(!pdo_fieldexists('wx_school_students',  'note')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_students')." ADD `note` varchar(50) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_students')) {
 if(!pdo_fieldexists('wx_school_students',  'amount')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_students')." ADD `amount` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_students')) {
 if(!pdo_fieldexists('wx_school_students',  'area')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_students')." ADD `area` varchar(50) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_students')) {
 if(!pdo_fieldexists('wx_school_students',  'own')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_students')." ADD `own` varchar(30) NOT NULL   COMMENT '本人微信info';");
 }
}
if(pdo_tableexists('wx_school_students')) {
 if(!pdo_fieldexists('wx_school_students',  'mom')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_students')." ADD `mom` varchar(30) NOT NULL   COMMENT '母亲微信info';");
 }
}
if(pdo_tableexists('wx_school_students')) {
 if(!pdo_fieldexists('wx_school_students',  'dad')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_students')." ADD `dad` varchar(30) NOT NULL   COMMENT '父亲微信info';");
 }
}
if(pdo_tableexists('wx_school_students')) {
 if(!pdo_fieldexists('wx_school_students',  'other')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_students')." ADD `other` varchar(30) NOT NULL   COMMENT '其他家长微信info';");
 }
}
if(pdo_tableexists('wx_school_students')) {
 if(!pdo_fieldexists('wx_school_students',  'ouserid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_students')." ADD `ouserid` int(11) NOT NULL   COMMENT '用户ID';");
 }
}
if(pdo_tableexists('wx_school_students')) {
 if(!pdo_fieldexists('wx_school_students',  'muserid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_students')." ADD `muserid` int(11) NOT NULL   COMMENT '用户ID';");
 }
}
if(pdo_tableexists('wx_school_students')) {
 if(!pdo_fieldexists('wx_school_students',  'duserid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_students')." ADD `duserid` int(11) NOT NULL   COMMENT '用户ID';");
 }
}
if(pdo_tableexists('wx_school_students')) {
 if(!pdo_fieldexists('wx_school_students',  'otheruserid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_students')." ADD `otheruserid` int(11) NOT NULL   COMMENT '用户ID';");
 }
}
if(pdo_tableexists('wx_school_students')) {
 if(!pdo_fieldexists('wx_school_students',  'ouid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_students')." ADD `ouid` int(10) NOT NULL   COMMENT '微擎系统memberID';");
 }
}
if(pdo_tableexists('wx_school_students')) {
 if(!pdo_fieldexists('wx_school_students',  'muid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_students')." ADD `muid` int(10) NOT NULL   COMMENT '微擎系统memberID';");
 }
}
if(pdo_tableexists('wx_school_students')) {
 if(!pdo_fieldexists('wx_school_students',  'duid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_students')." ADD `duid` int(10) NOT NULL   COMMENT '微擎系统memberID';");
 }
}
if(pdo_tableexists('wx_school_students')) {
 if(!pdo_fieldexists('wx_school_students',  'otheruid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_students')." ADD `otheruid` int(10) NOT NULL   COMMENT '微擎系统memberID';");
 }
}
if(pdo_tableexists('wx_school_students')) {
 if(!pdo_fieldexists('wx_school_students',  'xjid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_students')." ADD `xjid` int(11) NOT NULL   COMMENT '学籍信息';");
 }
}
if(pdo_tableexists('wx_school_students')) {
 if(!pdo_fieldexists('wx_school_students',  'code')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_students')." ADD `code` varchar(18)    COMMENT '绑定码';");
 }
}
if(pdo_tableexists('wx_school_students')) {
 if(!pdo_fieldexists('wx_school_students',  'keyid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_students')." ADD `keyid` int(11);");
 }
}
if(pdo_tableexists('wx_school_students')) {
 if(!pdo_fieldexists('wx_school_students',  'qrcode_id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_students')." ADD `qrcode_id` int(10)    COMMENT '二维码ID';");
 }
}
if(pdo_tableexists('wx_school_students')) {
 if(!pdo_fieldexists('wx_school_students',  'points')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_students')." ADD `points` int(11) NOT NULL   COMMENT '学生积分';");
 }
}
if(pdo_tableexists('wx_school_students')) {
 if(!pdo_fieldexists('wx_school_students',  'chongzhi')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_students')." ADD `chongzhi` float(10,2) NOT NULL   COMMENT '余额';");
 }
}
if(pdo_tableexists('wx_school_students')) {
 if(!pdo_fieldexists('wx_school_students',  's_type')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_students')." ADD `s_type` tinyint(3) NOT NULL   COMMENT '走读住校';");
 }
}
if(pdo_tableexists('wx_school_students')) {
 if(!pdo_fieldexists('wx_school_students',  'infocard')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_students')." ADD `infocard` text() NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_students')) {
 if(!pdo_fieldexists('wx_school_students',  'roomid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_students')." ADD `roomid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_students')) {
 if(!pdo_fieldexists('wx_school_students',  'chargenum')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_students')." ADD `chargenum` int(11) NOT NULL   COMMENT '充电桩剩余次数';");
 }
}
if(pdo_tableexists('wx_school_task')) {
 if(!pdo_fieldexists('wx_school_task',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_task')." ADD `id` int(10) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_task')) {
 if(!pdo_fieldexists('wx_school_task',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_task')." ADD `weid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_task')) {
 if(!pdo_fieldexists('wx_school_task',  'schoolid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_task')." ADD `schoolid` varchar(50) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_task')) {
 if(!pdo_fieldexists('wx_school_task',  'kcid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_task')." ADD `kcid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_task')) {
 if(!pdo_fieldexists('wx_school_task',  'status')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_task')." ADD `status` tinyint(1) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_task')) {
 if(!pdo_fieldexists('wx_school_task',  'type')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_task')." ADD `type` tinyint(1) NOT NULL   COMMENT '分类';");
 }
}
if(pdo_tableexists('wx_school_task')) {
 if(!pdo_fieldexists('wx_school_task',  'createtime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_task')." ADD `createtime` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_task_list')) {
 if(!pdo_fieldexists('wx_school_task_list',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_task_list')." ADD `id` int(10) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_task_list')) {
 if(!pdo_fieldexists('wx_school_task_list',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_task_list')." ADD `weid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_task_list')) {
 if(!pdo_fieldexists('wx_school_task_list',  'schoolid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_task_list')." ADD `schoolid` varchar(50) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_task_list')) {
 if(!pdo_fieldexists('wx_school_task_list',  'ksid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_task_list')." ADD `ksid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_task_list')) {
 if(!pdo_fieldexists('wx_school_task_list',  'type')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_task_list')." ADD `type` tinyint(1) NOT NULL   COMMENT '分类';");
 }
}
if(pdo_tableexists('wx_school_task_list')) {
 if(!pdo_fieldexists('wx_school_task_list',  'createtime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_task_list')." ADD `createtime` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_tcourse')) {
 if(!pdo_fieldexists('wx_school_tcourse',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_tcourse')." ADD `id` int(11) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_tcourse')) {
 if(!pdo_fieldexists('wx_school_tcourse',  'schoolid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_tcourse')." ADD `schoolid` int(10) NOT NULL   COMMENT '分校id';");
 }
}
if(pdo_tableexists('wx_school_tcourse')) {
 if(!pdo_fieldexists('wx_school_tcourse',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_tcourse')." ADD `weid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_tcourse')) {
 if(!pdo_fieldexists('wx_school_tcourse',  'tid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_tcourse')." ADD `tid` varchar(50) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_tcourse')) {
 if(!pdo_fieldexists('wx_school_tcourse',  'name')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_tcourse')." ADD `name` varchar(50) NOT NULL   COMMENT '课程名称';");
 }
}
if(pdo_tableexists('wx_school_tcourse')) {
 if(!pdo_fieldexists('wx_school_tcourse',  'dagang')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_tcourse')." ADD `dagang` text() NOT NULL   COMMENT '课程大纲';");
 }
}
if(pdo_tableexists('wx_school_tcourse')) {
 if(!pdo_fieldexists('wx_school_tcourse',  'start')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_tcourse')." ADD `start` int(10) NOT NULL   COMMENT '开始时间';");
 }
}
if(pdo_tableexists('wx_school_tcourse')) {
 if(!pdo_fieldexists('wx_school_tcourse',  'end')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_tcourse')." ADD `end` int(10) NOT NULL   COMMENT '结束时间';");
 }
}
if(pdo_tableexists('wx_school_tcourse')) {
 if(!pdo_fieldexists('wx_school_tcourse',  'minge')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_tcourse')." ADD `minge` int(11) NOT NULL   COMMENT '名额限制';");
 }
}
if(pdo_tableexists('wx_school_tcourse')) {
 if(!pdo_fieldexists('wx_school_tcourse',  'yibao')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_tcourse')." ADD `yibao` int(11) NOT NULL   COMMENT '已报人数';");
 }
}
if(pdo_tableexists('wx_school_tcourse')) {
 if(!pdo_fieldexists('wx_school_tcourse',  'cose')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_tcourse')." ADD `cose` decimal(18,2) NOT NULL DEFAULT NULL DEFAULT '0.00'  COMMENT '价格';");
 }
}
if(pdo_tableexists('wx_school_tcourse')) {
 if(!pdo_fieldexists('wx_school_tcourse',  'adrr')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_tcourse')." ADD `adrr` varchar(100) NOT NULL   COMMENT '授课地址或教室';");
 }
}
if(pdo_tableexists('wx_school_tcourse')) {
 if(!pdo_fieldexists('wx_school_tcourse',  'km_id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_tcourse')." ADD `km_id` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_tcourse')) {
 if(!pdo_fieldexists('wx_school_tcourse',  'bj_id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_tcourse')." ADD `bj_id` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_tcourse')) {
 if(!pdo_fieldexists('wx_school_tcourse',  'xq_id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_tcourse')." ADD `xq_id` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_tcourse')) {
 if(!pdo_fieldexists('wx_school_tcourse',  'sd_id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_tcourse')." ADD `sd_id` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_tcourse')) {
 if(!pdo_fieldexists('wx_school_tcourse',  'is_hot')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_tcourse')." ADD `is_hot` tinyint(1) NOT NULL   COMMENT '是否推荐';");
 }
}
if(pdo_tableexists('wx_school_tcourse')) {
 if(!pdo_fieldexists('wx_school_tcourse',  'is_show')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_tcourse')." ADD `is_show` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1'  COMMENT '1显示,2否';");
 }
}
if(pdo_tableexists('wx_school_tcourse')) {
 if(!pdo_fieldexists('wx_school_tcourse',  'payweid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_tcourse')." ADD `payweid` int(10) NOT NULL   COMMENT '支付公众号';");
 }
}
if(pdo_tableexists('wx_school_tcourse')) {
 if(!pdo_fieldexists('wx_school_tcourse',  'ssort')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_tcourse')." ADD `ssort` tinyint(3) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_tcourse')) {
 if(!pdo_fieldexists('wx_school_tcourse',  'signTime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_tcourse')." ADD `signTime` int(5) NOT NULL   COMMENT '签到时间';");
 }
}
if(pdo_tableexists('wx_school_tcourse')) {
 if(!pdo_fieldexists('wx_school_tcourse',  'isSign')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_tcourse')." ADD `isSign` int(3) NOT NULL   COMMENT '是否可签到';");
 }
}
if(pdo_tableexists('wx_school_tcourse')) {
 if(!pdo_fieldexists('wx_school_tcourse',  'OldOrNew')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_tcourse')." ADD `OldOrNew` int(2) NOT NULL   COMMENT '固定课时or自由课程';");
 }
}
if(pdo_tableexists('wx_school_tcourse')) {
 if(!pdo_fieldexists('wx_school_tcourse',  'Ctype')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_tcourse')." ADD `Ctype` int(3) NOT NULL   COMMENT '课程类型';");
 }
}
if(pdo_tableexists('wx_school_tcourse')) {
 if(!pdo_fieldexists('wx_school_tcourse',  'FirstNum')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_tcourse')." ADD `FirstNum` int(3) NOT NULL   COMMENT '首次包含多少课时';");
 }
}
if(pdo_tableexists('wx_school_tcourse')) {
 if(!pdo_fieldexists('wx_school_tcourse',  'RePrice')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_tcourse')." ADD `RePrice` decimal(18,2) NOT NULL   COMMENT '续费价格/课时';");
 }
}
if(pdo_tableexists('wx_school_tcourse')) {
 if(!pdo_fieldexists('wx_school_tcourse',  'ReNum')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_tcourse')." ADD `ReNum` int(3) NOT NULL   COMMENT '起续课时数';");
 }
}
if(pdo_tableexists('wx_school_tcourse')) {
 if(!pdo_fieldexists('wx_school_tcourse',  'AllNum')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_tcourse')." ADD `AllNum` int(3) NOT NULL   COMMENT '总共多少课时';");
 }
}
if(pdo_tableexists('wx_school_tcourse')) {
 if(!pdo_fieldexists('wx_school_tcourse',  'thumb')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_tcourse')." ADD `thumb` varchar(1000) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_tcourse')) {
 if(!pdo_fieldexists('wx_school_tcourse',  'maintid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_tcourse')." ADD `maintid` int(11) NOT NULL   COMMENT '主讲老师';");
 }
}
if(pdo_tableexists('wx_school_tcourse')) {
 if(!pdo_fieldexists('wx_school_tcourse',  'Point2Cost')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_tcourse')." ADD `Point2Cost` int(11) NOT NULL   COMMENT '多少积分抵一元';");
 }
}
if(pdo_tableexists('wx_school_tcourse')) {
 if(!pdo_fieldexists('wx_school_tcourse',  'MinPoint')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_tcourse')." ADD `MinPoint` int(11) NOT NULL   COMMENT '最低使用下限';");
 }
}
if(pdo_tableexists('wx_school_tcourse')) {
 if(!pdo_fieldexists('wx_school_tcourse',  'MaxPoint')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_tcourse')." ADD `MaxPoint` int(11) NOT NULL   COMMENT '最高使用上限';");
 }
}
if(pdo_tableexists('wx_school_tcourse')) {
 if(!pdo_fieldexists('wx_school_tcourse',  'yytid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_tcourse')." ADD `yytid` int(11) NOT NULL   COMMENT '预约负责老师';");
 }
}
if(pdo_tableexists('wx_school_tcourse')) {
 if(!pdo_fieldexists('wx_school_tcourse',  'is_remind_pj')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_tcourse')." ADD `is_remind_pj` int(2) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_tcourse')) {
 if(!pdo_fieldexists('wx_school_tcourse',  'is_tuijian')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_tcourse')." ADD `is_tuijian` int(3) NOT NULL   COMMENT '是否推荐课程';");
 }
}
if(pdo_tableexists('wx_school_tcourse')) {
 if(!pdo_fieldexists('wx_school_tcourse',  'is_tx')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_tcourse')." ADD `is_tx` tinyint(1) NOT NULL   COMMENT '提醒开关';");
 }
}
if(pdo_tableexists('wx_school_tcourse')) {
 if(!pdo_fieldexists('wx_school_tcourse',  'txtime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_tcourse')." ADD `txtime` int(10) NOT NULL   COMMENT '提前分钟';");
 }
}
if(pdo_tableexists('wx_school_tcourse')) {
 if(!pdo_fieldexists('wx_school_tcourse',  'is_print')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_tcourse')." ADD `is_print` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '2'  COMMENT '是否启用打印机';");
 }
}
if(pdo_tableexists('wx_school_tcourse')) {
 if(!pdo_fieldexists('wx_school_tcourse',  'printarr')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_tcourse')." ADD `printarr` varchar(100) NOT NULL   COMMENT '打印机';");
 }
}
if(pdo_tableexists('wx_school_tcourse')) {
 if(!pdo_fieldexists('wx_school_tcourse',  'bigimg')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_tcourse')." ADD `bigimg` text()    COMMENT '幻灯片';");
 }
}
if(pdo_tableexists('wx_school_tcourse')) {
 if(!pdo_fieldexists('wx_school_tcourse',  'is_dm')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_tcourse')." ADD `is_dm` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1'  COMMENT '弹幕';");
 }
}
if(pdo_tableexists('wx_school_teachers')) {
 if(!pdo_fieldexists('wx_school_teachers',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_teachers')." ADD `id` int(11) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_teachers')) {
 if(!pdo_fieldexists('wx_school_teachers',  'schoolid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_teachers')." ADD `schoolid` int(10) NOT NULL   COMMENT '分校id';");
 }
}
if(pdo_tableexists('wx_school_teachers')) {
 if(!pdo_fieldexists('wx_school_teachers',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_teachers')." ADD `weid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_teachers')) {
 if(!pdo_fieldexists('wx_school_teachers',  'tname')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_teachers')." ADD `tname` varchar(50) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_teachers')) {
 if(!pdo_fieldexists('wx_school_teachers',  'birthdate')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_teachers')." ADD `birthdate` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_teachers')) {
 if(!pdo_fieldexists('wx_school_teachers',  'tel')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_teachers')." ADD `tel` varchar(20) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_teachers')) {
 if(!pdo_fieldexists('wx_school_teachers',  'mobile')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_teachers')." ADD `mobile` char(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_teachers')) {
 if(!pdo_fieldexists('wx_school_teachers',  'email')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_teachers')." ADD `email` char(50) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_teachers')) {
 if(!pdo_fieldexists('wx_school_teachers',  'sex')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_teachers')." ADD `sex` int(1) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_teachers')) {
 if(!pdo_fieldexists('wx_school_teachers',  'km_id1')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_teachers')." ADD `km_id1` int(11) NOT NULL   COMMENT '授课科目1';");
 }
}
if(pdo_tableexists('wx_school_teachers')) {
 if(!pdo_fieldexists('wx_school_teachers',  'km_id2')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_teachers')." ADD `km_id2` int(11) NOT NULL   COMMENT '授课科目2';");
 }
}
if(pdo_tableexists('wx_school_teachers')) {
 if(!pdo_fieldexists('wx_school_teachers',  'km_id3')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_teachers')." ADD `km_id3` int(11) NOT NULL   COMMENT '授课科目3';");
 }
}
if(pdo_tableexists('wx_school_teachers')) {
 if(!pdo_fieldexists('wx_school_teachers',  'bj_id1')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_teachers')." ADD `bj_id1` int(11) NOT NULL   COMMENT '授课班级1';");
 }
}
if(pdo_tableexists('wx_school_teachers')) {
 if(!pdo_fieldexists('wx_school_teachers',  'bj_id2')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_teachers')." ADD `bj_id2` int(11) NOT NULL   COMMENT '授课班级2';");
 }
}
if(pdo_tableexists('wx_school_teachers')) {
 if(!pdo_fieldexists('wx_school_teachers',  'bj_id3')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_teachers')." ADD `bj_id3` int(11) NOT NULL   COMMENT '授课班级3';");
 }
}
if(pdo_tableexists('wx_school_teachers')) {
 if(!pdo_fieldexists('wx_school_teachers',  'xq_id1')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_teachers')." ADD `xq_id1` int(11) NOT NULL   COMMENT '授课年级1';");
 }
}
if(pdo_tableexists('wx_school_teachers')) {
 if(!pdo_fieldexists('wx_school_teachers',  'xq_id2')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_teachers')." ADD `xq_id2` int(11) NOT NULL   COMMENT '授课年级2';");
 }
}
if(pdo_tableexists('wx_school_teachers')) {
 if(!pdo_fieldexists('wx_school_teachers',  'xq_id3')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_teachers')." ADD `xq_id3` int(11) NOT NULL   COMMENT '授课年级3';");
 }
}
if(pdo_tableexists('wx_school_teachers')) {
 if(!pdo_fieldexists('wx_school_teachers',  'com')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_teachers')." ADD `com` varchar(30) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_teachers')) {
 if(!pdo_fieldexists('wx_school_teachers',  'fz_id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_teachers')." ADD `fz_id` int(11) NOT NULL   COMMENT '所属分组';");
 }
}
if(pdo_tableexists('wx_school_teachers')) {
 if(!pdo_fieldexists('wx_school_teachers',  'jiontime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_teachers')." ADD `jiontime` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_teachers')) {
 if(!pdo_fieldexists('wx_school_teachers',  'info')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_teachers')." ADD `info` text() NOT NULL   COMMENT '教学成果';");
 }
}
if(pdo_tableexists('wx_school_teachers')) {
 if(!pdo_fieldexists('wx_school_teachers',  'jinyan')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_teachers')." ADD `jinyan` text() NOT NULL   COMMENT '教学经验';");
 }
}
if(pdo_tableexists('wx_school_teachers')) {
 if(!pdo_fieldexists('wx_school_teachers',  'headinfo')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_teachers')." ADD `headinfo` text() NOT NULL   COMMENT '教学特点';");
 }
}
if(pdo_tableexists('wx_school_teachers')) {
 if(!pdo_fieldexists('wx_school_teachers',  'thumb')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_teachers')." ADD `thumb` varchar(200) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_teachers')) {
 if(!pdo_fieldexists('wx_school_teachers',  'status')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_teachers')." ADD `status` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1';");
 }
}
if(pdo_tableexists('wx_school_teachers')) {
 if(!pdo_fieldexists('wx_school_teachers',  'sort')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_teachers')." ADD `sort` int(11);");
 }
}
if(pdo_tableexists('wx_school_teachers')) {
 if(!pdo_fieldexists('wx_school_teachers',  'code')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_teachers')." ADD `code` int(11) NOT NULL   COMMENT '绑定码';");
 }
}
if(pdo_tableexists('wx_school_teachers')) {
 if(!pdo_fieldexists('wx_school_teachers',  'openid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_teachers')." ADD `openid` varchar(30) NOT NULL   COMMENT '教师微信';");
 }
}
if(pdo_tableexists('wx_school_teachers')) {
 if(!pdo_fieldexists('wx_school_teachers',  'uid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_teachers')." ADD `uid` int(10) NOT NULL   COMMENT '微擎系统memberID';");
 }
}
if(pdo_tableexists('wx_school_teachers')) {
 if(!pdo_fieldexists('wx_school_teachers',  'userid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_teachers')." ADD `userid` int(11) NOT NULL   COMMENT '用户ID';");
 }
}
if(pdo_tableexists('wx_school_teachers')) {
 if(!pdo_fieldexists('wx_school_teachers',  'is_show')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_teachers')." ADD `is_show` tinyint(1) NOT NULL   COMMENT '是否显示';");
 }
}
if(pdo_tableexists('wx_school_teachers')) {
 if(!pdo_fieldexists('wx_school_teachers',  'point')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_teachers')." ADD `point` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_teachers')) {
 if(!pdo_fieldexists('wx_school_teachers',  'star')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_teachers')." ADD `star` float() NOT NULL   COMMENT '平均星级';");
 }
}
if(pdo_tableexists('wx_school_teachers')) {
 if(!pdo_fieldexists('wx_school_teachers',  'idcard')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_teachers')." ADD `idcard` varchar(20) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_teachers')) {
 if(!pdo_fieldexists('wx_school_teachers',  'jiguan')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_teachers')." ADD `jiguan` varchar(80) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_teachers')) {
 if(!pdo_fieldexists('wx_school_teachers',  'minzu')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_teachers')." ADD `minzu` varchar(20) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_teachers')) {
 if(!pdo_fieldexists('wx_school_teachers',  'zzmianmao')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_teachers')." ADD `zzmianmao` varchar(30) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_teachers')) {
 if(!pdo_fieldexists('wx_school_teachers',  'address')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_teachers')." ADD `address` varchar(300) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_teachers')) {
 if(!pdo_fieldexists('wx_school_teachers',  'otherinfo')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_teachers')." ADD `otherinfo` text() NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_teachers')) {
 if(!pdo_fieldexists('wx_school_teachers',  'plate_num')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_teachers')." ADD `plate_num` varchar(15)    COMMENT '教师车牌号';");
 }
}
if(pdo_tableexists('wx_school_teascore')) {
 if(!pdo_fieldexists('wx_school_teascore',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_teascore')." ADD `id` int(11) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_teascore')) {
 if(!pdo_fieldexists('wx_school_teascore',  'schoolid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_teascore')." ADD `schoolid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_teascore')) {
 if(!pdo_fieldexists('wx_school_teascore',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_teascore')." ADD `weid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_teascore')) {
 if(!pdo_fieldexists('wx_school_teascore',  'tid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_teascore')." ADD `tid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_teascore')) {
 if(!pdo_fieldexists('wx_school_teascore',  'score')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_teascore')." ADD `score` float(5,2) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_teascore')) {
 if(!pdo_fieldexists('wx_school_teascore',  'fromfzid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_teascore')." ADD `fromfzid` int(11) NOT NULL   COMMENT '评分人分组';");
 }
}
if(pdo_tableexists('wx_school_teascore')) {
 if(!pdo_fieldexists('wx_school_teascore',  'fromtid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_teascore')." ADD `fromtid` varchar(30) NOT NULL   COMMENT '评分人tid';");
 }
}
if(pdo_tableexists('wx_school_teascore')) {
 if(!pdo_fieldexists('wx_school_teascore',  'scoretime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_teascore')." ADD `scoretime` int(11) NOT NULL   COMMENT '评分时间';");
 }
}
if(pdo_tableexists('wx_school_teascore')) {
 if(!pdo_fieldexists('wx_school_teascore',  'createtime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_teascore')." ADD `createtime` int(11) NOT NULL   COMMENT '创建时间';");
 }
}
if(pdo_tableexists('wx_school_teascore')) {
 if(!pdo_fieldexists('wx_school_teascore',  'obid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_teascore')." ADD `obid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_teascore')) {
 if(!pdo_fieldexists('wx_school_teascore',  'parentobid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_teascore')." ADD `parentobid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_teascore')) {
 if(!pdo_fieldexists('wx_school_teascore',  'sid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_teascore')." ADD `sid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_teascore')) {
 if(!pdo_fieldexists('wx_school_teascore',  'type')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_teascore')." ADD `type` tinyint(3) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_teascore')) {
 if(!pdo_fieldexists('wx_school_teascore',  'bj_id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_teascore')." ADD `bj_id` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_teascore')) {
 if(!pdo_fieldexists('wx_school_teascore',  'nj_id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_teascore')." ADD `nj_id` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_teasencefiles')) {
 if(!pdo_fieldexists('wx_school_teasencefiles',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_teasencefiles')." ADD `id` int(11) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_teasencefiles')) {
 if(!pdo_fieldexists('wx_school_teasencefiles',  'schoolid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_teasencefiles')." ADD `schoolid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_teasencefiles')) {
 if(!pdo_fieldexists('wx_school_teasencefiles',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_teasencefiles')." ADD `weid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_teasencefiles')) {
 if(!pdo_fieldexists('wx_school_teasencefiles',  'tid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_teasencefiles')." ADD `tid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_teasencefiles')) {
 if(!pdo_fieldexists('wx_school_teasencefiles',  'senceid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_teasencefiles')." ADD `senceid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_teasencefiles')) {
 if(!pdo_fieldexists('wx_school_teasencefiles',  'up_word')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_teasencefiles')." ADD `up_word` varchar(500) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_teasencefiles')) {
 if(!pdo_fieldexists('wx_school_teasencefiles',  'up_imgs')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_teasencefiles')." ADD `up_imgs` varchar(5000) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_teasencefiles')) {
 if(!pdo_fieldexists('wx_school_teasencefiles',  'up_audio')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_teasencefiles')." ADD `up_audio` varchar(1000) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_teasencefiles')) {
 if(!pdo_fieldexists('wx_school_teasencefiles',  'audiotime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_teasencefiles')." ADD `audiotime` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_teasencefiles')) {
 if(!pdo_fieldexists('wx_school_teasencefiles',  'up_video')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_teasencefiles')." ADD `up_video` varchar(1000) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_teasencefiles')) {
 if(!pdo_fieldexists('wx_school_teasencefiles',  'videoimg')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_teasencefiles')." ADD `videoimg` varchar(500) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_teasencefiles')) {
 if(!pdo_fieldexists('wx_school_teasencefiles',  'createtime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_teasencefiles')." ADD `createtime` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_tempstudent')) {
 if(!pdo_fieldexists('wx_school_tempstudent',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_tempstudent')." ADD `id` int(11) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_tempstudent')) {
 if(!pdo_fieldexists('wx_school_tempstudent',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_tempstudent')." ADD `weid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_tempstudent')) {
 if(!pdo_fieldexists('wx_school_tempstudent',  'schoolid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_tempstudent')." ADD `schoolid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_tempstudent')) {
 if(!pdo_fieldexists('wx_school_tempstudent',  'sname')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_tempstudent')." ADD `sname` varchar(50) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_tempstudent')) {
 if(!pdo_fieldexists('wx_school_tempstudent',  'mobile')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_tempstudent')." ADD `mobile` varchar(15) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_tempstudent')) {
 if(!pdo_fieldexists('wx_school_tempstudent',  'sex')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_tempstudent')." ADD `sex` int(3) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_tempstudent')) {
 if(!pdo_fieldexists('wx_school_tempstudent',  'addr')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_tempstudent')." ADD `addr` varchar(200) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_tempstudent')) {
 if(!pdo_fieldexists('wx_school_tempstudent',  'nj_id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_tempstudent')." ADD `nj_id` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_tempstudent')) {
 if(!pdo_fieldexists('wx_school_tempstudent',  'bj_id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_tempstudent')." ADD `bj_id` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_tempstudent')) {
 if(!pdo_fieldexists('wx_school_tempstudent',  'pard')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_tempstudent')." ADD `pard` varchar(3) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_tempstudent')) {
 if(!pdo_fieldexists('wx_school_tempstudent',  'openid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_tempstudent')." ADD `openid` varchar(50) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_tempstudent')) {
 if(!pdo_fieldexists('wx_school_tempstudent',  'uid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_tempstudent')." ADD `uid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_timetable')) {
 if(!pdo_fieldexists('wx_school_timetable',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_timetable')." ADD `id` int(11) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_timetable')) {
 if(!pdo_fieldexists('wx_school_timetable',  'schoolid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_timetable')." ADD `schoolid` int(10) NOT NULL   COMMENT '分校id';");
 }
}
if(pdo_tableexists('wx_school_timetable')) {
 if(!pdo_fieldexists('wx_school_timetable',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_timetable')." ADD `weid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_timetable')) {
 if(!pdo_fieldexists('wx_school_timetable',  'bj_id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_timetable')." ADD `bj_id` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_timetable')) {
 if(!pdo_fieldexists('wx_school_timetable',  'title')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_timetable')." ADD `title` varchar(50) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_timetable')) {
 if(!pdo_fieldexists('wx_school_timetable',  'begintime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_timetable')." ADD `begintime` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_timetable')) {
 if(!pdo_fieldexists('wx_school_timetable',  'endtime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_timetable')." ADD `endtime` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_timetable')) {
 if(!pdo_fieldexists('wx_school_timetable',  'monday')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_timetable')." ADD `monday` text() NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_timetable')) {
 if(!pdo_fieldexists('wx_school_timetable',  'tuesday')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_timetable')." ADD `tuesday` text() NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_timetable')) {
 if(!pdo_fieldexists('wx_school_timetable',  'wednesday')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_timetable')." ADD `wednesday` text() NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_timetable')) {
 if(!pdo_fieldexists('wx_school_timetable',  'thursday')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_timetable')." ADD `thursday` text() NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_timetable')) {
 if(!pdo_fieldexists('wx_school_timetable',  'friday')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_timetable')." ADD `friday` text() NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_timetable')) {
 if(!pdo_fieldexists('wx_school_timetable',  'saturday')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_timetable')." ADD `saturday` text() NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_timetable')) {
 if(!pdo_fieldexists('wx_school_timetable',  'sunday')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_timetable')." ADD `sunday` text() NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_timetable')) {
 if(!pdo_fieldexists('wx_school_timetable',  'ishow')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_timetable')." ADD `ishow` int(1) NOT NULL DEFAULT NULL DEFAULT '1'  COMMENT '1:显示,2隐藏,默认1';");
 }
}
if(pdo_tableexists('wx_school_timetable')) {
 if(!pdo_fieldexists('wx_school_timetable',  'sort')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_timetable')." ADD `sort` int(11) NOT NULL DEFAULT NULL DEFAULT '1';");
 }
}
if(pdo_tableexists('wx_school_timetable')) {
 if(!pdo_fieldexists('wx_school_timetable',  'type')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_timetable')." ADD `type` varchar(15) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_timetable')) {
 if(!pdo_fieldexists('wx_school_timetable',  'headpic')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_timetable')." ADD `headpic` varchar(200) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_todo')) {
 if(!pdo_fieldexists('wx_school_todo',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_todo')." ADD `id` int(11) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_todo')) {
 if(!pdo_fieldexists('wx_school_todo',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_todo')." ADD `weid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_todo')) {
 if(!pdo_fieldexists('wx_school_todo',  'schoolid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_todo')." ADD `schoolid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_todo')) {
 if(!pdo_fieldexists('wx_school_todo',  'fsid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_todo')." ADD `fsid` int(11) NOT NULL   COMMENT '发布者id';");
 }
}
if(pdo_tableexists('wx_school_todo')) {
 if(!pdo_fieldexists('wx_school_todo',  'jsid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_todo')." ADD `jsid` int(11) NOT NULL   COMMENT '接收者id';");
 }
}
if(pdo_tableexists('wx_school_todo')) {
 if(!pdo_fieldexists('wx_school_todo',  'zjid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_todo')." ADD `zjid` int(11) NOT NULL   COMMENT '转交者id';");
 }
}
if(pdo_tableexists('wx_school_todo')) {
 if(!pdo_fieldexists('wx_school_todo',  'todoname')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_todo')." ADD `todoname` varchar(100) NOT NULL   COMMENT '任务名称';");
 }
}
if(pdo_tableexists('wx_school_todo')) {
 if(!pdo_fieldexists('wx_school_todo',  'content')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_todo')." ADD `content` varchar(2000) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_todo')) {
 if(!pdo_fieldexists('wx_school_todo',  'starttime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_todo')." ADD `starttime` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_todo')) {
 if(!pdo_fieldexists('wx_school_todo',  'endtime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_todo')." ADD `endtime` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_todo')) {
 if(!pdo_fieldexists('wx_school_todo',  'createtime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_todo')." ADD `createtime` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_todo')) {
 if(!pdo_fieldexists('wx_school_todo',  'acttime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_todo')." ADD `acttime` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_todo')) {
 if(!pdo_fieldexists('wx_school_todo',  'status')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_todo')." ADD `status` int(3) NOT NULL   COMMENT '状态（7种）';");
 }
}
if(pdo_tableexists('wx_school_todo')) {
 if(!pdo_fieldexists('wx_school_todo',  'zjbeizhu')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_todo')." ADD `zjbeizhu` varchar(100) NOT NULL   COMMENT '转交备注';");
 }
}
if(pdo_tableexists('wx_school_todo')) {
 if(!pdo_fieldexists('wx_school_todo',  'jjbeizhu1')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_todo')." ADD `jjbeizhu1` varchar(100) NOT NULL   COMMENT '第一人拒绝备注';");
 }
}
if(pdo_tableexists('wx_school_todo')) {
 if(!pdo_fieldexists('wx_school_todo',  'jjbeizhu2')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_todo')." ADD `jjbeizhu2` varchar(100) NOT NULL   COMMENT '第二人拒绝备注';");
 }
}
if(pdo_tableexists('wx_school_todo')) {
 if(!pdo_fieldexists('wx_school_todo',  'picurls')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_todo')." ADD `picurls` varchar(5000) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_todo')) {
 if(!pdo_fieldexists('wx_school_todo',  'audio')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_todo')." ADD `audio` varchar(1000) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_todo')) {
 if(!pdo_fieldexists('wx_school_todo',  'audiotime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_todo')." ADD `audiotime` varchar(300) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_todo')) {
 if(!pdo_fieldexists('wx_school_todo',  'videoimg')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_todo')." ADD `videoimg` varchar(1000) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_todo')) {
 if(!pdo_fieldexists('wx_school_todo',  'video')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_todo')." ADD `video` varchar(2000) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_todo')) {
 if(!pdo_fieldexists('wx_school_todo',  'ali_vod_id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_todo')." ADD `ali_vod_id` varchar(100)    COMMENT '视频画面ID';");
 }
}
if(pdo_tableexists('wx_school_type')) {
 if(!pdo_fieldexists('wx_school_type',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_type')." ADD `id` int(10) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_type')) {
 if(!pdo_fieldexists('wx_school_type',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_type')." ADD `weid` int(10) NOT NULL   COMMENT '所属帐号';");
 }
}
if(pdo_tableexists('wx_school_type')) {
 if(!pdo_fieldexists('wx_school_type',  'name')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_type')." ADD `name` varchar(50) NOT NULL   COMMENT '类型名称';");
 }
}
if(pdo_tableexists('wx_school_type')) {
 if(!pdo_fieldexists('wx_school_type',  'parentid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_type')." ADD `parentid` int(10) NOT NULL   COMMENT '上级分类ID,0为第一级';");
 }
}
if(pdo_tableexists('wx_school_type')) {
 if(!pdo_fieldexists('wx_school_type',  'ssort')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_type')." ADD `ssort` tinyint(3) NOT NULL   COMMENT '排序';");
 }
}
if(pdo_tableexists('wx_school_type')) {
 if(!pdo_fieldexists('wx_school_type',  'status')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_type')." ADD `status` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1'  COMMENT '显示状态';");
 }
}
if(pdo_tableexists('wx_school_upsence')) {
 if(!pdo_fieldexists('wx_school_upsence',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_upsence')." ADD `id` int(11) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_upsence')) {
 if(!pdo_fieldexists('wx_school_upsence',  'schoolid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_upsence')." ADD `schoolid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_upsence')) {
 if(!pdo_fieldexists('wx_school_upsence',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_upsence')." ADD `weid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_upsence')) {
 if(!pdo_fieldexists('wx_school_upsence',  'name')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_upsence')." ADD `name` varchar(500) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_upsence')) {
 if(!pdo_fieldexists('wx_school_upsence',  'sencetime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_upsence')." ADD `sencetime` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_upsence')) {
 if(!pdo_fieldexists('wx_school_upsence',  'qxfzid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_upsence')." ADD `qxfzid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_upsence')) {
 if(!pdo_fieldexists('wx_school_upsence',  'createtime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_upsence')." ADD `createtime` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_upsence')) {
 if(!pdo_fieldexists('wx_school_upsence',  'ali_vod_id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_upsence')." ADD `ali_vod_id` varchar(100)    COMMENT '视频画面ID';");
 }
}
if(pdo_tableexists('wx_school_user')) {
 if(!pdo_fieldexists('wx_school_user',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_user')." ADD `id` int(10) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_user')) {
 if(!pdo_fieldexists('wx_school_user',  'sid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_user')." ADD `sid` int(10) NOT NULL   COMMENT '学生ID';");
 }
}
if(pdo_tableexists('wx_school_user')) {
 if(!pdo_fieldexists('wx_school_user',  'tid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_user')." ADD `tid` int(10) NOT NULL   COMMENT '老师ID';");
 }
}
if(pdo_tableexists('wx_school_user')) {
 if(!pdo_fieldexists('wx_school_user',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_user')." ADD `weid` int(10) NOT NULL   COMMENT '公众号ID';");
 }
}
if(pdo_tableexists('wx_school_user')) {
 if(!pdo_fieldexists('wx_school_user',  'schoolid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_user')." ADD `schoolid` int(10) NOT NULL   COMMENT '学校ID';");
 }
}
if(pdo_tableexists('wx_school_user')) {
 if(!pdo_fieldexists('wx_school_user',  'uid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_user')." ADD `uid` int(10) NOT NULL   COMMENT '微擎系统memberID';");
 }
}
if(pdo_tableexists('wx_school_user')) {
 if(!pdo_fieldexists('wx_school_user',  'openid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_user')." ADD `openid` varchar(30) NOT NULL   COMMENT 'openid';");
 }
}
if(pdo_tableexists('wx_school_user')) {
 if(!pdo_fieldexists('wx_school_user',  'userinfo')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_user')." ADD `userinfo` text()    COMMENT '用户信息';");
 }
}
if(pdo_tableexists('wx_school_user')) {
 if(!pdo_fieldexists('wx_school_user',  'pard')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_user')." ADD `pard` int(1) NOT NULL   COMMENT '关系';");
 }
}
if(pdo_tableexists('wx_school_user')) {
 if(!pdo_fieldexists('wx_school_user',  'status')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_user')." ADD `status` tinyint(1) NOT NULL   COMMENT '用户状态';");
 }
}
if(pdo_tableexists('wx_school_user')) {
 if(!pdo_fieldexists('wx_school_user',  'is_allowmsg')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_user')." ADD `is_allowmsg` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1'  COMMENT '私聊信息接收语法';");
 }
}
if(pdo_tableexists('wx_school_user')) {
 if(!pdo_fieldexists('wx_school_user',  'is_frist')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_user')." ADD `is_frist` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1'  COMMENT '1首次2不是';");
 }
}
if(pdo_tableexists('wx_school_user')) {
 if(!pdo_fieldexists('wx_school_user',  'createtime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_user')." ADD `createtime` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_user_class')) {
 if(!pdo_fieldexists('wx_school_user_class',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_user_class')." ADD `id` int(10) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_user_class')) {
 if(!pdo_fieldexists('wx_school_user_class',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_user_class')." ADD `weid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_user_class')) {
 if(!pdo_fieldexists('wx_school_user_class',  'schoolid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_user_class')." ADD `schoolid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_user_class')) {
 if(!pdo_fieldexists('wx_school_user_class',  'tid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_user_class')." ADD `tid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_user_class')) {
 if(!pdo_fieldexists('wx_school_user_class',  'sid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_user_class')." ADD `sid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_user_class')) {
 if(!pdo_fieldexists('wx_school_user_class',  'bj_id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_user_class')." ADD `bj_id` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_user_class')) {
 if(!pdo_fieldexists('wx_school_user_class',  'km_id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_user_class')." ADD `km_id` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_user_class')) {
 if(!pdo_fieldexists('wx_school_user_class',  'type')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_user_class')." ADD `type` tinyint(1) NOT NULL   COMMENT '1老师2学生';");
 }
}
if(pdo_tableexists('wx_school_wxpay')) {
 if(!pdo_fieldexists('wx_school_wxpay',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_wxpay')." ADD `id` int(10) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_wxpay')) {
 if(!pdo_fieldexists('wx_school_wxpay',  'hmxianlaohu')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_wxpay')." ADD `hmxianlaohu` varchar(30) NOT NULL   COMMENT '订单ID';");
 }
}
if(pdo_tableexists('wx_school_wxpay')) {
 if(!pdo_fieldexists('wx_school_wxpay',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_wxpay')." ADD `weid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_wxpay')) {
 if(!pdo_fieldexists('wx_school_wxpay',  'schoolid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_wxpay')." ADD `schoolid` int(10) NOT NULL   COMMENT '学校ID';");
 }
}
if(pdo_tableexists('wx_school_wxpay')) {
 if(!pdo_fieldexists('wx_school_wxpay',  'orderid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_wxpay')." ADD `orderid` int(10) NOT NULL   COMMENT '返回订单ID';");
 }
}
if(pdo_tableexists('wx_school_wxpay')) {
 if(!pdo_fieldexists('wx_school_wxpay',  'od1')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_wxpay')." ADD `od1` int(10) NOT NULL   COMMENT '1';");
 }
}
if(pdo_tableexists('wx_school_wxpay')) {
 if(!pdo_fieldexists('wx_school_wxpay',  'od2')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_wxpay')." ADD `od2` int(10) NOT NULL   COMMENT '2';");
 }
}
if(pdo_tableexists('wx_school_wxpay')) {
 if(!pdo_fieldexists('wx_school_wxpay',  'od3')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_wxpay')." ADD `od3` int(10) NOT NULL   COMMENT '3';");
 }
}
if(pdo_tableexists('wx_school_wxpay')) {
 if(!pdo_fieldexists('wx_school_wxpay',  'od4')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_wxpay')." ADD `od4` int(10) NOT NULL   COMMENT '4';");
 }
}
if(pdo_tableexists('wx_school_wxpay')) {
 if(!pdo_fieldexists('wx_school_wxpay',  'od5')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_wxpay')." ADD `od5` int(10) NOT NULL   COMMENT '5';");
 }
}
if(pdo_tableexists('wx_school_wxpay')) {
 if(!pdo_fieldexists('wx_school_wxpay',  'cose')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_wxpay')." ADD `cose` decimal(18,2) NOT NULL DEFAULT NULL DEFAULT '0.00'  COMMENT '价格';");
 }
}
if(pdo_tableexists('wx_school_wxpay')) {
 if(!pdo_fieldexists('wx_school_wxpay',  'payweid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_wxpay')." ADD `payweid` int(10) NOT NULL   COMMENT '支付公众号';");
 }
}
if(pdo_tableexists('wx_school_wxpay')) {
 if(!pdo_fieldexists('wx_school_wxpay',  'openid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_wxpay')." ADD `openid` varchar(30) NOT NULL   COMMENT 'openid';");
 }
}
if(pdo_tableexists('wx_school_wxpay')) {
 if(!pdo_fieldexists('wx_school_wxpay',  'status')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_wxpay')." ADD `status` tinyint(1) NOT NULL   COMMENT '1未支付2为未支付3为已退款';");
 }
}
if(pdo_tableexists('wx_school_yuecostlog')) {
 if(!pdo_fieldexists('wx_school_yuecostlog',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_yuecostlog')." ADD `id` int(11) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_yuecostlog')) {
 if(!pdo_fieldexists('wx_school_yuecostlog',  'schoolid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_yuecostlog')." ADD `schoolid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_yuecostlog')) {
 if(!pdo_fieldexists('wx_school_yuecostlog',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_yuecostlog')." ADD `weid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_yuecostlog')) {
 if(!pdo_fieldexists('wx_school_yuecostlog',  'sid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_yuecostlog')." ADD `sid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_yuecostlog')) {
 if(!pdo_fieldexists('wx_school_yuecostlog',  'yue_type')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_yuecostlog')." ADD `yue_type` tinyint(3) NOT NULL   COMMENT '1补助余额2普通余额3充电桩';");
 }
}
if(pdo_tableexists('wx_school_yuecostlog')) {
 if(!pdo_fieldexists('wx_school_yuecostlog',  'cost')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_yuecostlog')." ADD `cost` float(8,2) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_yuecostlog')) {
 if(!pdo_fieldexists('wx_school_yuecostlog',  'costtime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_yuecostlog')." ADD `costtime` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_yuecostlog')) {
 if(!pdo_fieldexists('wx_school_yuecostlog',  'orderid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_yuecostlog')." ADD `orderid` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_yuecostlog')) {
 if(!pdo_fieldexists('wx_school_yuecostlog',  'cost_type')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_yuecostlog')." ADD `cost_type` tinyint(3) NOT NULL   COMMENT '1收入2消费';");
 }
}
if(pdo_tableexists('wx_school_yuecostlog')) {
 if(!pdo_fieldexists('wx_school_yuecostlog',  'macid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_yuecostlog')." ADD `macid` varchar(100) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_yuecostlog')) {
 if(!pdo_fieldexists('wx_school_yuecostlog',  'on_offline')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_yuecostlog')." ADD `on_offline` tinyint(3) NOT NULL   COMMENT '1线上2线下';");
 }
}
if(pdo_tableexists('wx_school_yuecostlog')) {
 if(!pdo_fieldexists('wx_school_yuecostlog',  'createtime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_yuecostlog')." ADD `createtime` int(11) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_zjh')) {
 if(!pdo_fieldexists('wx_school_zjh',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_zjh')." ADD `id` int(10) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_zjh')) {
 if(!pdo_fieldexists('wx_school_zjh',  'is_on')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_zjh')." ADD `is_on` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1';");
 }
}
if(pdo_tableexists('wx_school_zjh')) {
 if(!pdo_fieldexists('wx_school_zjh',  'picrul')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_zjh')." ADD `picrul` varchar(1000) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_zjh')) {
 if(!pdo_fieldexists('wx_school_zjh',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_zjh')." ADD `weid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_zjh')) {
 if(!pdo_fieldexists('wx_school_zjh',  'schoolid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_zjh')." ADD `schoolid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_zjh')) {
 if(!pdo_fieldexists('wx_school_zjh',  'planuid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_zjh')." ADD `planuid` varchar(37) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_zjh')) {
 if(!pdo_fieldexists('wx_school_zjh',  'tid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_zjh')." ADD `tid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_zjh')) {
 if(!pdo_fieldexists('wx_school_zjh',  'bj_id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_zjh')." ADD `bj_id` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_zjh')) {
 if(!pdo_fieldexists('wx_school_zjh',  'type')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_zjh')." ADD `type` tinyint(1) NOT NULL DEFAULT NULL DEFAULT '1'  COMMENT '1图片2文字';");
 }
}
if(pdo_tableexists('wx_school_zjh')) {
 if(!pdo_fieldexists('wx_school_zjh',  'start')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_zjh')." ADD `start` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_zjh')) {
 if(!pdo_fieldexists('wx_school_zjh',  'end')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_zjh')." ADD `end` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_zjh')) {
 if(!pdo_fieldexists('wx_school_zjh',  'ssort')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_zjh')." ADD `ssort` int(10) NOT NULL   COMMENT '排序';");
 }
}
if(pdo_tableexists('wx_school_zjh')) {
 if(!pdo_fieldexists('wx_school_zjh',  'createtime')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_zjh')." ADD `createtime` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_zjhdetail')) {
 if(!pdo_fieldexists('wx_school_zjhdetail',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_zjhdetail')." ADD `id` int(10) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_zjhdetail')) {
 if(!pdo_fieldexists('wx_school_zjhdetail',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_zjhdetail')." ADD `weid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_zjhdetail')) {
 if(!pdo_fieldexists('wx_school_zjhdetail',  'schoolid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_zjhdetail')." ADD `schoolid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_zjhdetail')) {
 if(!pdo_fieldexists('wx_school_zjhdetail',  'planuid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_zjhdetail')." ADD `planuid` varchar(37) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_zjhdetail')) {
 if(!pdo_fieldexists('wx_school_zjhdetail',  'curactivename')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_zjhdetail')." ADD `curactivename` varchar(100) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_zjhdetail')) {
 if(!pdo_fieldexists('wx_school_zjhdetail',  'detailuid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_zjhdetail')." ADD `detailuid` varchar(37) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_zjhdetail')) {
 if(!pdo_fieldexists('wx_school_zjhdetail',  'curactiveid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_zjhdetail')." ADD `curactiveid` varchar(100) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_zjhdetail')) {
 if(!pdo_fieldexists('wx_school_zjhdetail',  'activedesc')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_zjhdetail')." ADD `activedesc` text()    COMMENT '内容';");
 }
}
if(pdo_tableexists('wx_school_zjhdetail')) {
 if(!pdo_fieldexists('wx_school_zjhdetail',  'week')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_zjhdetail')." ADD `week` tinyint(1) NOT NULL   COMMENT '1-5';");
 }
}
if(pdo_tableexists('wx_school_zjhdetail')) {
 if(!pdo_fieldexists('wx_school_zjhdetail',  'ssort')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_zjhdetail')." ADD `ssort` int(10) NOT NULL   COMMENT '排序';");
 }
}
if(pdo_tableexists('wx_school_zjhset')) {
 if(!pdo_fieldexists('wx_school_zjhset',  'id')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_zjhset')." ADD `id` int(10) NOT NULL  AUTO_INCREMENT;");
 }
}
if(pdo_tableexists('wx_school_zjhset')) {
 if(!pdo_fieldexists('wx_school_zjhset',  'weid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_zjhset')." ADD `weid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_zjhset')) {
 if(!pdo_fieldexists('wx_school_zjhset',  'schoolid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_zjhset')." ADD `schoolid` int(10) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_zjhset')) {
 if(!pdo_fieldexists('wx_school_zjhset',  'planuid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_zjhset')." ADD `planuid` varchar(37) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_zjhset')) {
 if(!pdo_fieldexists('wx_school_zjhset',  'activetypeid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_zjhset')." ADD `activetypeid` varchar(100) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_zjhset')) {
 if(!pdo_fieldexists('wx_school_zjhset',  'curactiveid')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_zjhset')." ADD `curactiveid` varchar(100) NOT NULL;");
 }
}
if(pdo_tableexists('wx_school_zjhset')) {
 if(!pdo_fieldexists('wx_school_zjhset',  'activetypename')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_zjhset')." ADD `activetypename` varchar(30)    COMMENT '名称';");
 }
}
if(pdo_tableexists('wx_school_zjhset')) {
 if(!pdo_fieldexists('wx_school_zjhset',  'type')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_zjhset')." ADD `type` varchar(2)    COMMENT 'AM,PM';");
 }
}
if(pdo_tableexists('wx_school_zjhset')) {
 if(!pdo_fieldexists('wx_school_zjhset',  'ssort')) {
  pdo_query("ALTER TABLE ".tablename('wx_school_zjhset')." ADD `ssort` int(10) NOT NULL   COMMENT '排序';");
 }
}
