/*
	火柴梗数据的SQL 脚本
*/



--创建数据库  --iprojects
CREATE DATABASE  `iprojects` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;


--常用网站信息表
CREATE TABLE IF NOT EXISTS `websites`(
	`webid` int(11) NOT NULL  AUTO_INCREMENT COMMENT '编号',
	`webcode` int(11) NOT NULL COMMENT '网站编号',
	`webname` varchar(128) NOT NULL COMMENT '网站名称',
	`company` varchar(32) NOT NULL COMMENT '公司名称',
	`websites` varchar(32) NOT NULL COMMENT '网站地址',
	`extensionlink` varchar(256) NOT NULL COMMENT '推广链接',
	`ownertype` varchar(32) NOT NULL COMMENT '所属类别',
	`owntags` varchar(32) NOT NULL COMMENT '标签',
	`ismember` char(1) NOT NULL COMMENT '是否有账号',
	`clicktimes` int(11) NOT NULL COMMENT '点击次数',
	`innsertime` bigint NOT NULL COMMENT '创建时间',
	`remark` text NOT NULL COMMENT '备注信息',
	PRIMARY KEY (`webid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='常用网站信息表'



--网站排名信息
CREATE TABLE IF NOT EXISTS `webalexa`(
	`webaid` int(11) NOT NULL  AUTO_INCREMENT COMMENT '编号',
	`webcode` int(11) NOT NULL COMMENT '网站编号',
	`company` varchar(32) NOT NULL COMMENT '网站站长',
	`email` varchar(32) NOT NULL COMMENT '电子邮箱',
	`nowranking` varchar(16) NOT NULL COMMENT '综合排名',
	`chinaranking` varchar(16) NOT NULL COMMENT '中文排名',
	`nextranking` varchar(8) NOT NULL COMMENT '下期排名',
	`inserttime` varchar(16) NOT NULL COMMENT '收录时间',
	`country` varchar(8) NOT NULL COMMENT '所属国家',
	`codetype` varchar(8) NOT NULL COMMENT '编码方式',
	`speed` varchar(16) NOT NULL COMMENT '访问速度',
	`adultcontent` varchar(8) NOT NULL COMMENT '成人内容',
	`reverselinknum` varchar(16) NOT NULL COMMENT '反向链接数量',
	`phonenums` varchar(16) NOT NULL COMMENT '联系电话',
	`address` varchar(64) NOT NULL COMMENT '详细地址',
	`webinfos` varchar(64) NOT NULL COMMENT '网站介绍',
	`innsertime` bigint NOT NULL COMMENT '创建时间',
	`remark` text NOT NULL COMMENT '备注信息',
	PRIMARY KEY (`webaid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='常用网站信息表'



CREATE TABLE IF NOT EXISTS `tasks`(
	`tid` int(11) NOT NULL  AUTO_INCREMENT COMMENT '编号',
	`requireForm` varchar(128) NOT NULL COMMENT '任务来源',
	`requireType` varchar(32) NOT NULL COMMENT '任务类型',
	`requireUser` varchar(32) NOT NULL COMMENT '任务提出人',
	`taskDetail` text NOT NULL COMMENT '任务明细',
	`doUser` varchar(32) NOT NULL COMMENT '执行人',
	`planTime` varchar(16) NOT NULL COMMENT '预估时间',
	`taskStatus` varchar(32) NOT NULL COMMENT '状态',
	`startTime` bigint NOT NULL COMMENT '开始时间',
	`endTime` bigint NOT NULL COMMENT '结束时间',
	`verifyTime` bigint NOT NULL COMMENT '审核时间',
	`finishTime` bigint NOT NULL COMMENT '完成时间',
	`createTime` bigint NOT NULL COMMENT '创建时间',
	`updateTime` bigint NOT NULL COMMENT '更新时间',
	`remark` text NOT NULL COMMENT '备注信息',
	`fileUrl` varchar(256) NOT NULL COMMENT '参考文件',
	PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='任务管理信息表'

