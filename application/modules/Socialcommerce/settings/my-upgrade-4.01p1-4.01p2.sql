UPDATE `engine4_core_menuitems` SET `label` = 'Address' WHERE `engine4_core_menuitems`.`name` = 'socialcommerce_seller_info';
UPDATE `engine4_core_menuitems` SET `label` = 'Paypal Account' WHERE `engine4_core_menuitems`.`name` = 'socialcommerce_seller_payment';
UPDATE `engine4_core_menuitems` SET `name` = 'socialcommerce_seller_account' WHERE `engine4_core_menuitems`.`name` = 'socialcommerce_seller_stalls';
DELETE FROM `engine4_core_menuitems` WHERE `engine4_core_menuitems`.`name` = 'socialcommerce_seller_listings';
UPDATE `engine4_core_menuitems` SET `label` = 'TC Account' WHERE `engine4_core_menuitems`.`name` = 'socialcommerce_seller_account';
UPDATE `engine4_core_menuitems` SET `name` = 'socialcommerce_seller_dashboard' WHERE `engine4_core_menuitems`.`name` = 'socialcommerce_seller_transaction';
UPDATE `engine4_core_menuitems` SET `label` = 'Seller Dashboard' WHERE `engine4_core_menuitems`.`name` = 'socialcommerce_seller_dashboard';
UPDATE `engine4_core_menuitems` SET `params` = '{"route":"socialcommerce_general","controller":"seller","action":"dashboard","icon":"application/modules/Socialcommerce/externals/images/links/plus.png"}' WHERE `engine4_core_menuitems`.`name` = 'socialcommerce_seller_dashboard';
UPDATE `engine4_core_pages` SET `name` = 'socialcommerce_seller_dashboard' WHERE `engine4_core_pages`.`name` = 'socialcommerce_seller_transaction';
UPDATE `engine4_core_pages` SET `description` = 'This page show dashboard of seller section' WHERE `engine4_core_pages`.`name` = 'socialcommerce_seller_dashboard';
UPDATE `engine4_core_pages` SET `title` = 'Social Commerce Seller Section Dashboard Page' WHERE `engine4_core_pages`.`name` = 'socialcommerce_seller_dashboard';
UPDATE `engine4_core_pages` SET `displayname` = 'Social Commerce Seller Section Dashboard Page' WHERE `engine4_core_pages`.`name` = 'socialcommerce_seller_dashboard';
ALTER TABLE `engine4_socialcommerce_stalls` ADD `status` ENUM('public','closed','denied','pending','draft','deleted') NOT NULL DEFAULT 'draft';
ALTER TABLE `engine4_socialcommerce_stalls` ADD `total_products` INT(10) NOT NULL DEFAULT '0';
ALTER TABLE `engine4_socialcommerce_stalls` ADD `total_view` INT(10) NOT NULL DEFAULT '0', ADD `total_comment` INT(10) NOT NULL DEFAULT '0', ADD `total_follow` INT(10) NULL DEFAULT '0', ADD `total_favorite` INT(10) NOT NULL DEFAULT '0', ADD `total_like` INT(10) NOT NULL DEFAULT '0', ADD `total_orders` INT(10) NOT NULL DEFAULT '0';
UPDATE `engine4_core_menuitems` SET `params` = '{"route":"socialcommerce_general","controller":"seller","action":"dashboard","icon":"application/modules/Socialcommerce/externals/images/links/plus.png","class":"socialcommerce_seller_menu_item socialcommerce_dashboard","icon-class":"fa fa-cog"}' WHERE `engine4_core_menuitems`.`name` = 'socialcommerce_seller_dashboard';
UPDATE `engine4_core_menuitems` SET `params` = '{"route":"socialcommerce_general","controller":"seller","action":"manage-stalls","icon":"application/modules/Socialcommerce/externals/images/links/item.png","class":"socialcommerce_seller_menu_item socialcommerce_tcaccount","icon-class":"fa fa-user"}' WHERE `engine4_core_menuitems`.`name` = 'socialcommerce_seller_account';
UPDATE `engine4_core_menuitems` SET `params` = '{"route":"socialcommerce_general","controller":"seller","action":"info","icon":"application/modules/Socialcommerce/externals/images/links/trader.png","class":"socialcommerce_seller_menu_item socialcommerce_information","icon-class":"fa fa-info-circle"}' WHERE `engine4_core_menuitems`.`name` = 'socialcommerce_seller_info';
UPDATE `engine4_core_menuitems` SET `params` = '{"route":"socialcommerce_general","controller":"seller","action":"payment","icon":"application/modules/Socialcommerce/externals/images/links/bag.png","class":"socialcommerce_seller_menu_item socialcommerce_payment","icon-class":"fa fa-paypal"}' WHERE `engine4_core_menuitems`.`name` = 'socialcommerce_buyer_payment';
UPDATE `engine4_core_menuitems` SET `name` = 'socialcommerce_admin_main_manage-listings' WHERE `engine4_core_menuitems`.`name` = 'socialcommerce_admin_main_listings';
UPDATE `engine4_core_menuitems` SET `params` = '{"route":"admin_default","module":"socialcommerce","controller":"manage-listings", "action":"index"}' WHERE `engine4_core_menuitems`.`name` = 'socialcommerce_admin_main_manage-listings';

ALTER TABLE `engine4_socialcommerce_products` ADD `status` enum('open','closed', 'draft') NOT NULL default 'draft',
  ADD `approve_status` enum('new','waiting','approved','denied') NOT NULL default 'new';

CREATE TABLE IF NOT EXISTS `engine4_socialcommerce_requests` (
  `request_id` int(11) unsigned NOT NULL auto_increment,
  `stall_id` int(11) unsigned NOT NULL default '0',
  `owner_id` int(11) unsigned NOT NULL default '0',
  `request_amount` decimal(16,2) unsigned NOT NULL default '0.00',
  `request_status` enum('completed','denied','pending','waiting') NOT NULL default 'waiting',
  `currency` char(3) NOT NULL,
  `request_message` text NOT NULL,
  `response_message` text NOT NULL,
  `request_date` datetime default NULL,
  `response_date` datetime default NULL,
  PRIMARY KEY  (`request_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

ALTER TABLE `engine4_socialcommerce_accounts`
ADD `gateway_id` varchar(32) NOT NULL default 'paypal',
ADD `account_username` varchar(128) default NULL,
ADD `account_password` varchar(128) default NULL,
ADD `config` text NOT NULL;

INSERT INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `enabled`, `custom`, `order`) VALUES ('socialcommerce_admin_main_orders', 'socialcommerce', 'Manage Orders', '', '{"route":"admin_default","module":"socialcommerce","controller":"orders", "action":"index"}', 'socialcommerce_admin_main', '', '1', '0', '9');


CREATE TABLE IF NOT EXISTS `engine4_socialcommerce_faqs` (
  `faq_id` int(11) unsigned NOT NULL auto_increment,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `ordering` int(11) unsigned NOT NULL default '0',
  `question` varchar(255) NOT NULL,
  `answer` text NOT NULL,
  `creation_date` datetime NOT NULL,
  `modified_date` datetime NOT NULL,
  PRIMARY KEY  (`faq_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

ALTER TABLE `engine4_socialcommerce_accounts` ADD total_amount float(10,2) NOT NULL DEFAULT '0.00';