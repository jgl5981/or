/*
SQLyog Ultimate v10.42 
MySQL - 5.5.18-log : Database - or
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `or_authority` */

CREATE TABLE `or_authority` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '权限菜单编号',
  `menu_name` varchar(50) NOT NULL COMMENT '权限菜单名称',
  `parent_id` int(11) NOT NULL COMMENT '上级菜单编号',
  `path` varchar(500) NOT NULL COMMENT '菜单路径|以/开头，表示当前完整的层级关系',
  `is_enable_link` int(1) NOT NULL COMMENT '是否启用链接',
  `link` varchar(500) DEFAULT NULL COMMENT '菜单功能链接',
  `no` int(11) NOT NULL DEFAULT '1' COMMENT '菜单序号',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  `icon` varchar(50) DEFAULT NULL COMMENT '图标样式',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='or_authority - 权限菜单';

/*Data for the table `or_authority` */

insert  into `or_authority`(`id`,`menu_name`,`parent_id`,`path`,`is_enable_link`,`link`,`no`,`create_time`,`icon`) values (1,'权限管理',-1,'/1',0,NULL,1,'2016-03-09 20:15:06','fa fa-key'),(2,'用户管理',1,'/2',1,'admin/user/index',1,'2015-08-09 09:02:17','fa fa-user'),(3,'权限菜单',1,'/3',1,'admin/authority/index',2,'2015-08-09 09:03:04','fa fa-tree'),(4,'角色管理',1,'/4',1,'admin/role/index',3,'2015-08-09 09:03:43','fa fa-male'),(5,'角色分配',1,'/5',1,'admin/UserRole/index',4,'2015-08-09 09:04:22','fa fa-gavel'),(6,'1231',1,'/1/6',1,'12312',1,'2016-03-13 15:00:10','123123'),(7,'123111',1,'/1/7',1,'123123',1,'2016-03-13 15:00:42','1231');

/*Table structure for table `or_authority_grant` */

CREATE TABLE `or_authority_grant` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '权限授权编号',
  `role_id` int(11) NOT NULL COMMENT '角色编号,角色组(or_role)主键',
  `authority_id` int(11) NOT NULL COMMENT '权限菜单编号,权限菜单(or_authority)主键',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=290 DEFAULT CHARSET=utf8 COMMENT='or_authority_grant - 权限角色授权';

/*Data for the table `or_authority_grant` */

insert  into `or_authority_grant`(`id`,`role_id`,`authority_id`) values (248,19,4),(249,19,1),(250,19,2),(251,19,3),(252,19,5),(254,19,90),(288,20,2),(289,20,1);

/*Table structure for table `or_role` */

CREATE TABLE `or_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '角色编号',
  `name` varchar(50) NOT NULL COMMENT '角色名称',
  `descript` varchar(200) DEFAULT NULL COMMENT '角色描述',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COMMENT='or_role - 角色组';

/*Data for the table `or_role` */

insert  into `or_role`(`id`,`name`,`descript`,`create_time`) values (19,'管理员','管理员','2016-03-11 23:14:58'),(20,'新闻管理员','新闻管理员','2016-03-12 11:19:03');

/*Table structure for table `or_user` */

CREATE TABLE `or_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `name` varchar(50) NOT NULL COMMENT '用户名',
  `password` varchar(100) NOT NULL COMMENT '密码',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=129 DEFAULT CHARSET=utf8 COMMENT='or_user - 用户表';

/*Data for the table `or_user` */

insert  into `or_user`(`id`,`name`,`password`) values (119,'admin','c4ca4238a0b923820dcc509a6f75849b'),(122,'12321','c4ca4238a0b923820dcc509a6f75849b'),(123,'1232132','c4ca4238a0b923820dcc509a6f75849b'),(124,'1','c4ca4238a0b923820dcc509a6f75849b'),(126,'3','c4ca4238a0b923820dcc509a6f75849b'),(127,'4','c4ca4238a0b923820dcc509a6f75849b'),(128,'5','c4ca4238a0b923820dcc509a6f75849b');

/*Table structure for table `or_user_role` */

CREATE TABLE `or_user_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户角色授权编号',
  `user_id` int(11) NOT NULL COMMENT '管理员编号,管理员(or_user)主键',
  `role_id` int(11) NOT NULL COMMENT '角色编号,角色组(or_role)主键',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8 COMMENT='or_user_role - 用户角色授权';

/*Data for the table `or_user_role` */

insert  into `or_user_role`(`id`,`user_id`,`role_id`) values (19,120,20),(22,119,19),(29,127,20);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
