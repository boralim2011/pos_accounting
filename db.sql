/*
SQLyog Enterprise - MySQL GUI v6.5
MySQL - 5.6.17 : Database - pos_accounting
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

create database if not exists `pos_accounting`;

USE `pos_accounting`;

/*Table structure for table `age_range` */

DROP TABLE IF EXISTS `age_range`;

CREATE TABLE `age_range` (
  `age_range_id` int(11) NOT NULL AUTO_INCREMENT,
  `age_range_name` varchar(50) NOT NULL,
  `age_range_name_kh` varchar(50) DEFAULT NULL,
  `is_deletable` tinyint(1) NOT NULL DEFAULT '1',
  `is_editable` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`age_range_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

/*Data for the table `age_range` */

insert  into `age_range`(`age_range_id`,`age_range_name`,`age_range_name_kh`,`is_deletable`,`is_editable`) values (1,'No Range','No Range',0,1),(2,'10-20','10-20',1,1),(3,'21-30','21-30',1,1),(4,'31-40','31-40',1,1),(5,'41-50','41-50',1,1),(6,'51-60','51-60',1,1),(7,'60 Up','60 Up',1,1);

/*Table structure for table `branch_user` */

DROP TABLE IF EXISTS `branch_user`;

CREATE TABLE `branch_user` (
  `branch_user_id` int(11) NOT NULL AUTO_INCREMENT,
  `branch_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`branch_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `branch_user` */

/*Table structure for table `card` */

DROP TABLE IF EXISTS `card`;

CREATE TABLE `card` (
  `card_id` int(11) NOT NULL AUTO_INCREMENT,
  `card_number` varchar(50) NOT NULL,
  `card_name` varchar(200) DEFAULT NULL,
  `card_name_kh` varchar(200) DEFAULT NULL,
  `card_type_id` int(11) DEFAULT NULL,
  `discount_rate` float NOT NULL DEFAULT '0',
  `contact_id` int(11) DEFAULT NULL,
  `phone_number` varchar(200) DEFAULT NULL,
  `address` varchar(500) DEFAULT NULL,
  `register_date` date DEFAULT NULL,
  `expired_date` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `age_range_id` int(11) DEFAULT NULL,
  `gender` varchar(1) NOT NULL COMMENT 'O:Other, F:Female, M:Male',
  `created_by` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `modified_date` datetime DEFAULT NULL,
  `is_deletable` tinyint(1) NOT NULL DEFAULT '1',
  `is_editable` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`card_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Data for the table `card` */

insert  into `card`(`card_id`,`card_number`,`card_name`,`card_name_kh`,`card_type_id`,`discount_rate`,`contact_id`,`phone_number`,`address`,`register_date`,`expired_date`,`is_active`,`age_range_id`,`gender`,`created_by`,`created_date`,`modified_by`,`modified_date`,`is_deletable`,`is_editable`) values (1,'NoCard','NoCard','NoCard',1,0,NULL,NULL,NULL,'2017-04-29','2117-04-29',1,1,'O',1,'2017-04-29 16:44:03',1,'2017-04-29 16:44:03',0,0),(5,'002','Vannak','Vannak',1,10,NULL,'010999',NULL,'2017-04-23','2018-04-23',1,1,'O',1,'2017-04-23 10:06:21',1,'2017-05-10 10:24:41',1,1),(6,'685054695435','bora','bora',1,20,NULL,'',NULL,'2017-04-29','2018-04-29',1,3,'M',1,'2017-04-29 16:44:03',1,'2017-05-15 10:40:18',1,1);

/*Table structure for table `card_history` */

DROP TABLE IF EXISTS `card_history`;

CREATE TABLE `card_history` (
  `history_id` int(11) NOT NULL AUTO_INCREMENT,
  `card_id` int(11) DEFAULT NULL,
  `history_date` datetime DEFAULT NULL,
  `amount` float NOT NULL DEFAULT '0',
  `amount_in_company_currency` float NOT NULL DEFAULT '0',
  `currency_id` int(11) DEFAULT NULL,
  `exchange_rate` float NOT NULL DEFAULT '1',
  `is_inverse` tinyint(1) NOT NULL DEFAULT '0',
  `is_deposit` tinyint(1) NOT NULL DEFAULT '1',
  `is_payment` tinyint(1) NOT NULL DEFAULT '0',
  `note` varchar(500) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `modified_date` datetime DEFAULT NULL,
  PRIMARY KEY (`history_id`),
  KEY `FK_card_history` (`card_id`),
  CONSTRAINT `FK_card_history` FOREIGN KEY (`card_id`) REFERENCES `card` (`card_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

/*Data for the table `card_history` */

insert  into `card_history`(`history_id`,`card_id`,`history_date`,`amount`,`amount_in_company_currency`,`currency_id`,`exchange_rate`,`is_inverse`,`is_deposit`,`is_payment`,`note`,`created_by`,`created_date`,`modified_by`,`modified_date`) values (1,5,'2017-04-23 00:00:00',10,10,1,1,0,1,0,NULL,1,NULL,1,NULL),(2,5,'2017-04-23 00:00:00',20,20,1,1,0,1,0,NULL,1,NULL,1,NULL),(3,5,'2017-04-23 00:00:00',5,5,1,1,0,0,0,NULL,1,NULL,1,NULL),(4,5,'2017-04-30 00:00:00',50,50,1,1,0,1,0,NULL,1,'2017-04-30 05:23:00',1,'2017-04-30 05:23:00'),(7,6,'2017-04-30 00:00:00',100,100,1,1,0,1,0,NULL,1,'2017-04-30 17:16:10',1,'2017-04-30 17:16:10'),(8,6,'2017-04-30 00:00:00',50,50,1,1,0,0,0,NULL,1,'2017-04-30 17:30:09',1,'2017-04-30 17:30:09');

/*Table structure for table `card_type` */

DROP TABLE IF EXISTS `card_type`;

CREATE TABLE `card_type` (
  `card_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `card_type_name` varchar(200) DEFAULT NULL,
  `card_type_name_kh` varchar(200) DEFAULT NULL,
  `is_deletable` tinyint(1) NOT NULL DEFAULT '1',
  `is_editable` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`card_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `card_type` */

insert  into `card_type`(`card_type_id`,`card_type_name`,`card_type_name_kh`,`is_deletable`,`is_editable`) values (1,'Normal','ធម្មតា',0,1);

/*Table structure for table `company` */

DROP TABLE IF EXISTS `company`;

CREATE TABLE `company` (
  `company_id` int(11) NOT NULL AUTO_INCREMENT,
  `company_code` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `company_name` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `company_name_kh` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `short_name` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `phone_number` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `vat_no` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `contact_address` varchar(500) CHARACTER SET utf8 DEFAULT NULL,
  `contact_address_kh` varchar(500) CHARACTER SET utf8 DEFAULT NULL,
  `email` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `website` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `logo` text CHARACTER SET utf8,
  `currency_id` int(11) DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `province_city_id` int(11) DEFAULT NULL,
  `district_khan_id` int(11) DEFAULT NULL,
  `commune_sangkat_id` int(11) DEFAULT NULL,
  `village_id` int(11) DEFAULT NULL,
  `region_id` int(11) DEFAULT NULL,
  `project_code` varchar(200) DEFAULT NULL,
  `license_code` varchar(200) DEFAULT NULL,
  `activate_code` varchar(200) DEFAULT NULL,
  `is_deletable` tinyint(1) NOT NULL DEFAULT '1',
  `is_editable` tinyint(1) NOT NULL DEFAULT '1',
  `created_date` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_date` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`company_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `company` */

insert  into `company`(`company_id`,`company_code`,`company_name`,`company_name_kh`,`short_name`,`phone_number`,`start_date`,`vat_no`,`contact_address`,`contact_address_kh`,`email`,`website`,`logo`,`currency_id`,`country_id`,`province_city_id`,`district_khan_id`,`commune_sangkat_id`,`village_id`,`region_id`,`project_code`,`license_code`,`activate_code`,`is_deletable`,`is_editable`,`created_date`,`created_by`,`modified_date`,`modified_by`) values (1,'BIS','BIS','BIS','BIS',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,1,NULL,NULL,NULL,NULL);

/*Table structure for table `contact` */

DROP TABLE IF EXISTS `contact`;

CREATE TABLE `contact` (
  `contact_id` int(11) NOT NULL AUTO_INCREMENT,
  `contact_code` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `contact_name` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `contact_name_kh` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `phone_number` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `phone_number_2` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `contact_address` varchar(500) DEFAULT NULL,
  `contact_address_kh` varchar(500) DEFAULT NULL,
  `email` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `website` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `photo` text CHARACTER SET utf8,
  `contact_type_id` int(11) DEFAULT NULL,
  `company_id` int(11) DEFAULT NULL,
  `nationality_id` int(11) DEFAULT NULL,
  `first_name` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `last_name` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `first_name_kh` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `last_name_kh` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `nick_name` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `gender` char(1) CHARACTER SET utf8 DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `marital_status` varchar(20) CHARACTER SET utf8 DEFAULT NULL COMMENT 'Single, Married, Seperated, Widow',
  `country_id` int(11) DEFAULT NULL,
  `province_city_id` int(11) DEFAULT NULL,
  `district_khan_id` int(11) DEFAULT NULL,
  `commune_sangkat_id` int(11) DEFAULT NULL,
  `village_id` int(11) DEFAULT NULL,
  `region_id` int(11) DEFAULT NULL,
  `currency_id` int(11) DEFAULT NULL,
  `is_deletable` tinyint(1) NOT NULL DEFAULT '1',
  `created_date` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `modified_date` datetime DEFAULT NULL,
  PRIMARY KEY (`contact_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `contact` */

/*Table structure for table `contact_type` */

DROP TABLE IF EXISTS `contact_type`;

CREATE TABLE `contact_type` (
  `contact_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `contact_type_name` varchar(200) DEFAULT NULL,
  `contact_type_name_kh` varchar(200) DEFAULT NULL,
  `is_parent` tinyint(1) NOT NULL DEFAULT '0',
  `parent_id` int(11) DEFAULT NULL,
  `is_deletable` tinyint(1) NOT NULL DEFAULT '1',
  `is_editable` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`contact_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Data for the table `contact_type` */

insert  into `contact_type`(`contact_type_id`,`contact_type_name`,`contact_type_name_kh`,`is_parent`,`parent_id`,`is_deletable`,`is_editable`) values (1,'Branch','សាខា',1,1,0,0),(2,'Supplier','អ្នកផ្គត់ផ្គង់',1,2,0,0),(3,'Customer','អតិថិជន',1,3,0,0),(4,'Employee','បុគ្គលិក',1,4,0,0);

/*Table structure for table `currency` */

DROP TABLE IF EXISTS `currency`;

CREATE TABLE `currency` (
  `currency_id` int(11) NOT NULL AUTO_INCREMENT,
  `currency_name` varchar(200) NOT NULL,
  `currency_name_kh` varchar(200) DEFAULT NULL,
  `currency_symbol` varchar(3) NOT NULL,
  `is_deletable` tinyint(1) NOT NULL DEFAULT '1',
  `is_editable` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`currency_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `currency` */

insert  into `currency`(`currency_id`,`currency_name`,`currency_name_kh`,`currency_symbol`,`is_deletable`,`is_editable`) values (1,'USD','ដុល្លារ','$',0,0),(2,'KHR','រៀល','៛',0,0),(3,'THB','បាត','BB',1,1);

/*Table structure for table `exchange_rate` */

DROP TABLE IF EXISTS `exchange_rate`;

CREATE TABLE `exchange_rate` (
  `exchange_rate_id` int(11) NOT NULL AUTO_INCREMENT,
  `from_currency_id` int(11) DEFAULT NULL,
  `to_currency_id` int(11) DEFAULT NULL,
  `is_inverse` tinyint(1) NOT NULL DEFAULT '0',
  `bit_rate` float NOT NULL DEFAULT '1',
  `ask_rate` float NOT NULL DEFAULT '1',
  `is_deletable` tinyint(1) NOT NULL DEFAULT '1',
  `is_editable` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `modified_date` datetime DEFAULT NULL,
  PRIMARY KEY (`exchange_rate_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `exchange_rate` */

insert  into `exchange_rate`(`exchange_rate_id`,`from_currency_id`,`to_currency_id`,`is_inverse`,`bit_rate`,`ask_rate`,`is_deletable`,`is_editable`,`created_by`,`created_date`,`modified_by`,`modified_date`) values (1,1,2,1,4000,4000,0,1,1,'2017-01-22 21:51:31',1,'2017-05-10 22:16:11'),(3,3,1,0,35,35,1,1,1,'2017-01-23 23:30:08',1,'2017-04-24 11:32:02');

/*Table structure for table `item` */

DROP TABLE IF EXISTS `item`;

CREATE TABLE `item` (
  `item_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_code` varchar(200) NOT NULL,
  `item_name` varchar(500) DEFAULT NULL,
  `item_name_kh` varchar(500) DEFAULT NULL,
  `barcode` varchar(200) DEFAULT NULL,
  `model` varchar(200) DEFAULT NULL,
  `image` varchar(200) DEFAULT NULL,
  `unit_id` int(11) DEFAULT NULL,
  `item_type_id` int(11) DEFAULT NULL,
  `item_group_id` int(11) DEFAULT NULL,
  `item_class_id` int(11) DEFAULT NULL,
  `maker_id` int(11) DEFAULT NULL,
  `kg` float NOT NULL DEFAULT '0',
  `size` varchar(100) DEFAULT NULL,
  `Min` float NOT NULL DEFAULT '0',
  `Max` float NOT NULL DEFAULT '0',
  `income_account_id` int(11) DEFAULT NULL,
  `inventory_account_id` int(11) DEFAULT NULL,
  `cogs_account_id` int(11) DEFAULT NULL,
  `expense_account_id` int(11) DEFAULT NULL,
  `default_lot_id` int(11) DEFAULT NULL,
  `purchasing_price` float NOT NULL DEFAULT '0',
  `selling_price` float NOT NULL DEFAULT '0',
  `cogs_method` int(11) NOT NULL DEFAULT '1' COMMENT '1:AVG, 2:FIFO, 3:LIFO',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `is_editable` tinyint(1) NOT NULL DEFAULT '1',
  `is_deletable` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`item_id`)
) ENGINE=InnoDB AUTO_INCREMENT=84 DEFAULT CHARSET=utf8;

/*Data for the table `item` */

insert  into `item`(`item_id`,`item_code`,`item_name`,`item_name_kh`,`barcode`,`model`,`image`,`unit_id`,`item_type_id`,`item_group_id`,`item_class_id`,`maker_id`,`kg`,`size`,`Min`,`Max`,`income_account_id`,`inventory_account_id`,`cogs_account_id`,`expense_account_id`,`default_lot_id`,`purchasing_price`,`selling_price`,`cogs_method`,`is_active`,`is_editable`,`is_deletable`) values (3,'Item A','Item A','Item A','Item A','','ddd.jpg',1,1,2,1,1,0,NULL,0,0,NULL,NULL,NULL,NULL,1,0,5,1,1,1,1),(67,'Item B','Item B','Item B','Item B','','',1,1,1,1,1,0,NULL,0,0,NULL,NULL,NULL,NULL,1,0,30,1,1,1,1),(83,'ssss','ssss','ssss','ssss',NULL,'',1,3,1,1,NULL,0,NULL,0,0,NULL,NULL,NULL,NULL,NULL,0,60,1,1,1,1);

/*Table structure for table `item_class` */

DROP TABLE IF EXISTS `item_class`;

CREATE TABLE `item_class` (
  `item_class_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_class_name` varchar(200) DEFAULT NULL,
  `item_class_name_kh` varchar(200) DEFAULT NULL,
  `is_deletable` tinyint(1) NOT NULL DEFAULT '1',
  `is_editable` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`item_class_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `item_class` */

insert  into `item_class`(`item_class_id`,`item_class_name`,`item_class_name_kh`,`is_deletable`,`is_editable`) values (1,'Other','ផ្សេងៗ',0,1);

/*Table structure for table `item_group` */

DROP TABLE IF EXISTS `item_group`;

CREATE TABLE `item_group` (
  `item_group_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_group_name` varchar(200) DEFAULT NULL,
  `item_group_name_kh` varchar(200) DEFAULT NULL,
  `is_deletable` tinyint(1) NOT NULL DEFAULT '1',
  `is_editable` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`item_group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `item_group` */

insert  into `item_group`(`item_group_id`,`item_group_name`,`item_group_name_kh`,`is_deletable`,`is_editable`) values (1,'Other','ផ្សេងៗ',0,1),(2,'គ្រឿងកំប៉ុង','គ្រឿងកំប៉ុង',1,1),(3,'Beer','Beer',1,1);

/*Table structure for table `item_member` */

DROP TABLE IF EXISTS `item_member`;

CREATE TABLE `item_member` (
  `item_member_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) DEFAULT NULL,
  `member_id` int(11) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  PRIMARY KEY (`item_member_id`),
  KEY `FK_item_member` (`item_id`),
  CONSTRAINT `FK_item_member` FOREIGN KEY (`item_id`) REFERENCES `item` (`item_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=93 DEFAULT CHARSET=utf8;

/*Data for the table `item_member` */

insert  into `item_member`(`item_member_id`,`item_id`,`member_id`,`qty`) values (90,83,67,3),(92,83,67,1);

/*Table structure for table `item_type` */

DROP TABLE IF EXISTS `item_type`;

CREATE TABLE `item_type` (
  `item_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_type_name` varchar(200) DEFAULT NULL,
  `item_type_name_kh` varchar(200) DEFAULT NULL,
  `is_parent` tinyint(1) NOT NULL DEFAULT '0',
  `parent_id` int(11) DEFAULT NULL,
  `manage_stock` int(11) NOT NULL DEFAULT '0',
  `is_deletable` tinyint(1) NOT NULL DEFAULT '1',
  `is_editable` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`item_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Data for the table `item_type` */

insert  into `item_type`(`item_type_id`,`item_type_name`,`item_type_name_kh`,`is_parent`,`parent_id`,`manage_stock`,`is_deletable`,`is_editable`) values (1,'Product','ទំនិញ',1,1,1,0,0),(2,'Service','សេវាកម្ម',1,2,0,0,0),(3,'Mixed','Mixed',1,3,0,0,0),(4,'Set','ឈុត',1,4,1,0,0);

/*Table structure for table `journal` */

DROP TABLE IF EXISTS `journal`;

CREATE TABLE `journal` (
  `journal_id` int(11) NOT NULL AUTO_INCREMENT,
  `journal_date` datetime DEFAULT NULL,
  `journal_no` varchar(200) DEFAULT NULL,
  `document_no` varchar(200) DEFAULT NULL,
  `contact_id` int(11) DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `room_id` int(11) DEFAULT NULL,
  `age_range_id` int(11) DEFAULT NULL,
  `gender` varchar(1) DEFAULT NULL COMMENT 'O:Other, F:Female, M:Male',
  `card_id` int(11) DEFAULT NULL,
  `currency_id` int(11) DEFAULT NULL COMMENT 'From Currency',
  `exchange_rate` float DEFAULT NULL,
  `transaction_type_id` int(11) DEFAULT NULL,
  `journal_status` int(11) NOT NULL DEFAULT '1' COMMENT '1:Open, 2:Closed, 3:Void',
  `warehouse_id` int(11) DEFAULT NULL,
  `total` float DEFAULT NULL,
  `total_company_currency` float DEFAULT NULL,
  `discount` float DEFAULT NULL,
  `note` text,
  `created_by` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `modified_date` datetime DEFAULT NULL,
  PRIMARY KEY (`journal_id`),
  UNIQUE KEY `journal_ref_index` (`journal_no`,`transaction_type_id`),
  KEY `FK_journal` (`transaction_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;

/*Data for the table `journal` */

insert  into `journal`(`journal_id`,`journal_date`,`journal_no`,`document_no`,`contact_id`,`branch_id`,`room_id`,`age_range_id`,`gender`,`card_id`,`currency_id`,`exchange_rate`,`transaction_type_id`,`journal_status`,`warehouse_id`,`total`,`total_company_currency`,`discount`,`note`,`created_by`,`created_date`,`modified_by`,`modified_date`) values (27,'2017-05-28 00:00:00','INV-17050001',NULL,NULL,NULL,1,1,'O',5,1,4000,NULL,1,NULL,35,35,10,NULL,1,'2017-05-28 11:48:57',1,'2017-05-28 11:49:11');

/*Table structure for table `journal_item` */

DROP TABLE IF EXISTS `journal_item`;

CREATE TABLE `journal_item` (
  `journal_item_id` int(11) NOT NULL AUTO_INCREMENT,
  `journal_id` int(11) NOT NULL,
  `item_id` int(11) DEFAULT NULL,
  `cost` float NOT NULL DEFAULT '0',
  `price` float NOT NULL DEFAULT '1',
  `qty` float NOT NULL DEFAULT '0',
  `discount` float NOT NULL DEFAULT '0',
  `description` text,
  `lot_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`journal_item_id`),
  KEY `FK_journal_item_item` (`item_id`),
  KEY `FK_journal_item_journal` (`journal_id`),
  CONSTRAINT `FK_journal_item_item` FOREIGN KEY (`item_id`) REFERENCES `item` (`item_id`),
  CONSTRAINT `FK_journal_item_journal` FOREIGN KEY (`journal_id`) REFERENCES `journal` (`journal_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=79 DEFAULT CHARSET=utf8;

/*Data for the table `journal_item` */

insert  into `journal_item`(`journal_item_id`,`journal_id`,`item_id`,`cost`,`price`,`qty`,`discount`,`description`,`lot_id`) values (77,27,3,0,5,1,1,'',0),(78,27,67,0,30,1,1,NULL,NULL);

/*Table structure for table `journal_type` */

DROP TABLE IF EXISTS `journal_type`;

CREATE TABLE `journal_type` (
  `journal_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `journal_type_name` varchar(200) DEFAULT NULL,
  `journal_type_name_kh` varchar(200) DEFAULT NULL,
  `is_goods_movement` tinyint(1) NOT NULL DEFAULT '0',
  `is_payment` tinyint(1) NOT NULL DEFAULT '1',
  `is_parent` tinyint(1) NOT NULL DEFAULT '0',
  `parent_id` int(11) DEFAULT NULL,
  `is_deletable` tinyint(1) NOT NULL DEFAULT '1',
  `is_editable` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`journal_type_id`),
  UNIQUE KEY `journal_type_name_index` (`journal_type_name`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

/*Data for the table `journal_type` */

insert  into `journal_type`(`journal_type_id`,`journal_type_name`,`journal_type_name_kh`,`is_goods_movement`,`is_payment`,`is_parent`,`parent_id`,`is_deletable`,`is_editable`) values (1,'Purchasing Bill','វិក្កយបត្រទិញ',1,1,1,NULL,0,0),(2,'Sale Invoice','វិក្កយបត្រលក់​',1,1,1,NULL,0,0),(3,'Stock Transfer','ផ្ទេរទំនិញ',1,0,1,NULL,0,0),(4,'Production','ផលិតកម្ម',1,0,1,NULL,0,0),(5,'Stock In','ស្តុកចូល',1,0,1,NULL,0,0),(6,'Stock Out','ស្តុកចេញ',0,0,1,NULL,0,0),(7,'Bill Payment','បង់ប្រាក់',0,0,1,NULL,0,0),(8,'Receipt Payment','ទទួលប្រាក់',0,0,1,NULL,0,0),(9,'Exchange Currency','ដូរលុយ',0,0,1,NULL,0,0),(10,'Cash Transfer','ផ្ទេរលុយ',0,0,1,NULL,0,0),(11,'Cash In','ដាក់លុយ',0,0,1,NULL,0,0),(12,'Cash Out','ដកលុយ',0,0,1,NULL,0,0),(13,'Purchasing Return','ត្រលប់ទំនិញទិញ',1,1,1,NULL,0,0),(14,'Sale Return','ត្រលប់ទំនិញលក់',1,1,1,NULL,0,0),(15,'Customer Deposit','លុយកក់អតិថិជន',0,1,1,NULL,0,0),(16,'Advance Payment','លុយបង់អ្នកផ្គត់ផ្គង់មុន',0,1,1,NULL,0,0),(17,'Customer Loan','លុយត្រូវទារ',0,1,1,NULL,0,0),(18,'Loan','លុយត្រូវបង់',0,1,1,NULL,0,0),(19,'Expense Bill','វិក្កយបត្រចំណាយ',0,1,1,NULL,0,0),(20,'Purchasing Expense','ចំណាយទិញចូល',0,1,1,NULL,0,0),(21,'Sale Expense','ចំណាយលក់ចេញ',0,1,1,NULL,0,0);

/*Table structure for table `maker` */

DROP TABLE IF EXISTS `maker`;

CREATE TABLE `maker` (
  `maker_id` int(11) NOT NULL AUTO_INCREMENT,
  `maker_name` varchar(200) DEFAULT NULL,
  `maker_name_kh` varchar(200) DEFAULT NULL,
  `is_deletable` tinyint(1) NOT NULL DEFAULT '1',
  `is_editable` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`maker_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `maker` */

insert  into `maker`(`maker_id`,`maker_name`,`maker_name_kh`,`is_deletable`,`is_editable`) values (1,'Other','ផ្សេងៗ',0,1);

/*Table structure for table `permission` */

DROP TABLE IF EXISTS `permission`;

CREATE TABLE `permission` (
  `permission_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_role_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`permission_id`),
  UNIQUE KEY `permission_index` (`user_role_id`,`user_id`),
  KEY `FK_user_permission` (`user_id`),
  CONSTRAINT `FK_user_permission` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE,
  CONSTRAINT `FK_user_role_permission` FOREIGN KEY (`user_role_id`) REFERENCES `user_role` (`user_role_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=latin1;

/*Data for the table `permission` */

insert  into `permission`(`permission_id`,`user_role_id`,`user_id`) values (9,1,1),(35,2,3);

/*Table structure for table `role` */

DROP TABLE IF EXISTS `role`;

CREATE TABLE `role` (
  `role_id` int(11) NOT NULL AUTO_INCREMENT,
  `application_function_id` int(11) DEFAULT NULL,
  `user_group_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`role_id`),
  UNIQUE KEY `role_index` (`application_function_id`,`user_group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `role` */

/*Table structure for table `room` */

DROP TABLE IF EXISTS `room`;

CREATE TABLE `room` (
  `room_id` int(11) NOT NULL AUTO_INCREMENT,
  `room_name` varchar(200) DEFAULT NULL,
  `room_name_kh` varchar(200) DEFAULT NULL,
  `image` varchar(200) DEFAULT NULL,
  `room_type_id` int(11) DEFAULT NULL,
  `is_editable` tinyint(1) NOT NULL DEFAULT '1',
  `is_deletable` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`room_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

/*Data for the table `room` */

insert  into `room`(`room_id`,`room_name`,`room_name_kh`,`image`,`room_type_id`,`is_editable`,`is_deletable`) values (1,'No Room','No Room',NULL,1,1,0),(2,'RM002','RM002',NULL,1,1,1),(3,'RM003','RM003',NULL,1,1,1),(4,'VIP001','VIP001',NULL,2,1,1),(5,'VIP002','VIP002',NULL,2,1,1),(6,'RM004','RM004',NULL,1,1,1),(7,'RM005','RM005',NULL,1,1,1),(8,'RM006','RM006',NULL,1,1,1),(9,'RM007','RM007',NULL,1,1,1),(10,'RM008','RM008',NULL,1,1,1),(11,'RM009','RM009',NULL,1,1,1),(12,'V2001','V2001',NULL,3,1,1);

/*Table structure for table `room_type` */

DROP TABLE IF EXISTS `room_type`;

CREATE TABLE `room_type` (
  `room_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `room_type_name` varchar(200) DEFAULT NULL,
  `room_type_name_kh` varchar(200) DEFAULT NULL,
  `image` varchar(200) DEFAULT NULL,
  `is_editable` tinyint(1) NOT NULL DEFAULT '1',
  `is_deletable` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`room_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `room_type` */

insert  into `room_type`(`room_type_id`,`room_type_name`,`room_type_name_kh`,`image`,`is_editable`,`is_deletable`) values (1,'Normal','ធម្មតា',NULL,1,0),(2,'VIP','VIP',NULL,1,1),(3,'VIP2','VIP2',NULL,1,1);

/*Table structure for table `transaction_type` */

DROP TABLE IF EXISTS `transaction_type`;

CREATE TABLE `transaction_type` (
  `transaction_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `transaction_type_name` varchar(200) DEFAULT NULL,
  `transaction_type_name_kh` varchar(200) DEFAULT NULL,
  `journal_type_id` int(11) DEFAULT NULL,
  `is_issue` tinyint(1) NOT NULL DEFAULT '0',
  `is_goods_movement` tinyint(1) NOT NULL DEFAULT '0',
  `is_deletable` tinyint(1) NOT NULL DEFAULT '1',
  `is_editable` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`transaction_type_id`),
  UNIQUE KEY `transaction_type_name_index` (`transaction_type_name`),
  KEY `FK_transaction_type` (`journal_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;

/*Data for the table `transaction_type` */

insert  into `transaction_type`(`transaction_type_id`,`transaction_type_name`,`transaction_type_name_kh`,`journal_type_id`,`is_issue`,`is_goods_movement`,`is_deletable`,`is_editable`) values (1,'Purchasing','ទិញទូទៅ',1,0,1,0,0),(2,'Retail Sale','លក់​រាយ',2,1,1,0,0),(3,'Stock Transfer In','ផ្ទេរនិញចូល',3,0,1,0,0),(4,'Stock Transfer Out','ផ្ទេរទំនិញចេញ',3,1,0,0,0),(5,'Production In','ផលិតកម្មចូល',4,0,1,0,0),(6,'Production Out','ផលិតកម្មចេញ',4,1,0,0,0),(7,'Stock In','ស្តុកចូល',5,0,1,0,0),(8,'Stock Out','ស្តុកចេញ',6,1,0,0,0),(9,'Bill Payment','បង់ប្រាក់',7,0,0,0,0),(10,'Receipt Payment','ទទួលប្រាក់',8,0,0,0,0),(11,'Exchange Currency In','ដូរលុយចូល',9,0,0,0,0),(12,'Exchange Currency Out','ដូរលុយចេញ',9,0,0,0,0),(13,'Cash Transfer In','ផ្ទេរលុយចូល',10,0,0,0,0),(14,'Cash Transfer Out','ផ្ទេរលុយចេញ',10,0,0,0,0),(15,'Cash In','ដាក់លុយ',11,0,0,0,0),(16,'Cash Out','ដកលុយ',12,0,0,0,0),(17,'Purchasing Return','ត្រលប់ទំនិញទិញ',13,1,1,0,0),(18,'Sale Return','ត្រលប់ទំនិញលក់',14,0,1,0,0),(19,'Customer Deposit','លុយកក់អតិថិជន',15,0,0,0,0),(20,'Advance Payment','លុយបង់អ្នកផ្គត់ផ្គង់មុន',16,0,0,0,0),(21,'Customer Loan','លុយត្រូវទារ',17,0,0,0,0),(22,'Loan','លុយត្រូវបង់',18,0,0,0,0),(23,'Expense Bill','វិក្កយបត្រចំណាយ',19,0,0,0,0),(24,'Purchasing Expense','ចំណាយទិញចូល',20,0,0,0,0),(25,'Sale Expense','ចំណាយលក់ចេញ',21,0,0,0,0),(26,'Stock Adjustment In','កែតម្រូវស្តុកចូល',5,0,1,0,0),(27,'Stock Adjustment Out','កែតម្រូវស្តុកចេញ',6,1,1,0,0),(28,'Stock Begining','ស្តុកដើមគ្រា',5,0,1,0,0);

/*Table structure for table `unit` */

DROP TABLE IF EXISTS `unit`;

CREATE TABLE `unit` (
  `unit_id` int(11) NOT NULL AUTO_INCREMENT,
  `unit_name` varchar(200) DEFAULT NULL,
  `unit_name_kh` varchar(200) DEFAULT NULL,
  `is_deletable` tinyint(1) NOT NULL DEFAULT '1',
  `is_editable` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`unit_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Data for the table `unit` */

insert  into `unit`(`unit_id`,`unit_name`,`unit_name_kh`,`is_deletable`,`is_editable`) values (1,'Unit','ឯកតា',0,1),(2,'Kg','គីឡូក្រាម',1,1),(3,'Set','ឈុត',1,1),(4,'Box','កេះ',1,1);

/*Table structure for table `user` */

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(200) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `password` varchar(200) DEFAULT NULL,
  `contact_id` int(11) DEFAULT NULL,
  `image` varchar(200) DEFAULT NULL,
  `user_group_id` int(11) NOT NULL,
  `register_date` datetime DEFAULT NULL,
  `project_code` varchar(200) DEFAULT NULL,
  `license_key` varchar(200) DEFAULT NULL,
  `activate_key` varchar(200) DEFAULT NULL,
  `access_all_branch` tinyint(1) NOT NULL DEFAULT '0',
  `is_hidden` tinyint(1) NOT NULL DEFAULT '0',
  `is_deletable` tinyint(1) NOT NULL DEFAULT '1',
  `is_editable` tinyint(1) NOT NULL DEFAULT '1',
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `created_date` datetime DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  KEY `FK_user` (`user_group_id`),
  CONSTRAINT `FK_user` FOREIGN KEY (`user_group_id`) REFERENCES `user_group` (`user_group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Data for the table `user` */

insert  into `user`(`user_id`,`user_name`,`email`,`password`,`contact_id`,`image`,`user_group_id`,`register_date`,`project_code`,`license_key`,`activate_key`,`access_all_branch`,`is_hidden`,`is_deletable`,`is_editable`,`is_active`,`created_date`) values (1,'boralim2011','boralim2011@gmail.com','386943a05043bbd2a1cc119b6d883e2f61dc1aec',NULL,'',1,NULL,NULL,NULL,NULL,1,0,0,0,1,'2016-04-27 15:05:08'),(3,'user','user@gmail.com','5b6a147b7df45b4e165134795f2b0702c9ed6d4a',NULL,'',2,NULL,NULL,NULL,NULL,0,0,1,1,1,'2017-03-07 17:34:43');

/*Table structure for table `user_group` */

DROP TABLE IF EXISTS `user_group`;

CREATE TABLE `user_group` (
  `user_group_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_group_name` varchar(200) DEFAULT NULL,
  `is_deletable` tinyint(1) NOT NULL DEFAULT '1',
  `is_editable` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`user_group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Data for the table `user_group` */

insert  into `user_group`(`user_group_id`,`user_group_name`,`is_deletable`,`is_editable`) values (1,'Administrators',0,0),(2,'Power Users',0,1);

/*Table structure for table `user_role` */

DROP TABLE IF EXISTS `user_role`;

CREATE TABLE `user_role` (
  `user_role_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_role_name` varchar(200) DEFAULT NULL,
  `is_deletable` tinyint(1) NOT NULL DEFAULT '1',
  `is_editable` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`user_role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Data for the table `user_role` */

insert  into `user_role`(`user_role_id`,`user_role_name`,`is_deletable`,`is_editable`) values (1,'Administrators',1,1),(2,'Users',1,1);

/*Table structure for table `warehouse` */

DROP TABLE IF EXISTS `warehouse`;

CREATE TABLE `warehouse` (
  `warehouse_id` int(11) NOT NULL AUTO_INCREMENT,
  `warehouse_name` varchar(200) DEFAULT NULL,
  `warehouse_name_kh` varchar(200) DEFAULT NULL,
  `is_warehouse` tinyint(1) NOT NULL DEFAULT '0',
  `parent_id` int(11) DEFAULT NULL,
  `is_deletable` tinyint(1) NOT NULL DEFAULT '1',
  `is_editable` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`warehouse_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Data for the table `warehouse` */

insert  into `warehouse`(`warehouse_id`,`warehouse_name`,`warehouse_name_kh`,`is_warehouse`,`parent_id`,`is_deletable`,`is_editable`) values (1,'Warehouse','ឃ្លាំងទំនិញ',1,NULL,0,1),(2,'RS','RS',1,NULL,1,1),(3,'WS','WS',1,NULL,1,1),(4,'eee','ee',0,2,1,1);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
