ALTER TABLE `engine4_socialcommerce_products` ADD `sold_qty` int(11) unsigned NOT NULL default '0';
ALTER TABLE `engine4_socialcommerce_products` ADD `min_qty_purchase` int(11) unsigned NOT NULL default '0';
ALTER TABLE `engine4_socialcommerce_products` ADD `max_qty_purchase` int(11) unsigned NOT NULL default '0';
ALTER TABLE `engine4_socialcommerce_products` ADD `available_quantity` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0';

CREATE TABLE IF NOT EXISTS `engine4_socialcommerce_cartitems` (
  `cartitem_id` bigint(20) unsigned NOT NULL auto_increment,
  `cart_id` bigint(20) unsigned NOT NULL default '0',
  `owner_id` int(11) unsigned NOT NULL default '0',
  `item_id` bigint(20) unsigned NOT NULL default '0',
  `item_qty` int(11) unsigned NOT NULL default '0',
  `guest_id` int(11) unsigned NOT NULL default '0',
  `creation_date` datetime NOT NULL,
  PRIMARY KEY  (`cartitem_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

--
-- Table structure for table `engine4_socialcommerce_carts`
--

CREATE TABLE IF NOT EXISTS `engine4_socialcommerce_carts` (
  `cart_id` bigint(20) unsigned NOT NULL auto_increment,
  `owner_id` int(11) unsigned NOT NULL default '0',
  `guest_id` int(11) unsigned NOT NULL default '0',
  `creation_date` datetime NOT NULL,
  PRIMARY KEY  (`cart_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `engine4_socialcommerce_orders` (
  `order_id` varchar(20) NOT NULL,
  `paytype_id` varchar(32) NOT NULL,
  `owner_id` int(11) unsigned default NULL,
  `guest_id` INT(11) UNSIGNED NULL DEFAULT '0',
  `payment_status` enum('initial','pending','failure','completed') NOT NULL default 'initial',
  `order_status` enum('initial','processing','shipping','deliveried') NOT NULL default 'initial',
  `quantity` int(10) unsigned NOT NULL default '0',
  `tax_amount` decimal(16,2) unsigned NOT NULL default '0.00',
  `shipping_amount` decimal(16,2) unsigned NOT NULL default '0.00',
  `handling_amount` decimal(16,2) unsigned NOT NULL default '0.00',
  `discount_amount` decimal(16,2) unsigned NOT NULL default '0.00',
  `insurance_amount` decimal(16,2) unsigned NOT NULL default '0.00',
  `commission_amount` decimal(16,2) unsigned NOT NULL default '0.00',
  `sub_amount` decimal(16,2) unsigned NOT NULL default '0.00',
  `total_amount` decimal(16,2) unsigned NOT NULL default '0.00',
  `currency` char(3) NOT NULL default 'USD',
  `name` varchar(255) NOT NULL,
  `paypal_paykey` VARCHAR( 255 ) NULL DEFAULT 'none',
  `description` tinytext NOT NULL,
  `creation_date` datetime NOT NULL,
  PRIMARY KEY  (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `engine4_socialcommerce_orderitems` (
  `orderitem_id` bigint(20) unsigned NOT NULL auto_increment,
  `stall_id` bigint(20) unsigned NOT NULL default '0',
  `order_id` varchar(20) NOT NULL,
  `object_id` int(11) unsigned NOT NULL,
  `object_type` varchar(32) default NULL,
  `pretax_price` decimal(16,2) NOT NULL,
  `price` decimal(16,2) NOT NULL,
  `sku` varchar(256) NOT NULL,
  `quantity` int(11) NOT NULL default '1',
  `item_tax_amount` decimal(16,2) NOT NULL default '0.00',
  `item_commission_amount` decimal(16,2) NOT NULL default '0.00',
  `tax_amount` decimal(16,2) NOT NULL default '0.00',
  `shipping_amount` decimal(16,2) NOT NULL default '0.00',
  `handling_amount` decimal(16,2) NOT NULL default '0.00',
  `discount_amount` decimal(16,2) NOT NULL default '0.00',
  `seller_amount` DECIMAL( 16, 2 ) NOT NULL,
  `shippingaddress_id` int(11) unsigned NOT NULL default '0',
  `order_shipping_amount` decimal(16,2) NOT NULL default '0.00',
  `order_handling_amount` decimal(16,2) NOT NULL default '0.00',
  `shippingrule_id` int(11) unsigned NOT NULL default '0',
  `commission_amount` decimal(16,2) NOT NULL default '0.00',
  `sub_amount` decimal(16,2) NOT NULL default '0.00',
  `delivery_status` enum('processing','shipping','delivered') NOT NULL default 'processing',
  `payment_status` varchar(50) NOT NULL default 'processing',
  `total_amount` decimal(16,2) NOT NULL,
  `currency` varchar(3) NOT NULL default 'USD',
  `name` varchar(128) NOT NULL,
  `description` varchar(128) NOT NULL,
  `refund_status` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`orderitem_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `engine4_socialcommerce_paytypes` (
  `paytype_id` varchar(32) NOT NULL,
  `plugin_class` varchar(128) NOT NULL,
  PRIMARY KEY  (`paytype_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `engine4_socialcommerce_paytypes`
--

INSERT INTO `engine4_socialcommerce_paytypes` (`paytype_id`, `plugin_class`) VALUES
('publish-stall', 'Socialcommerce_Plugin_Payment_PublishStall'),
('shopping-cart', 'Socialcommerce_Plugin_Payment_ShoppingCart'),
('publish-product', 'Socialcommerce_Plugin_Payment_PublishProduct'),
('pay-request', 'Socialcommerce_Plugin_Payment_PayRequest');

CREATE TABLE IF NOT EXISTS `engine4_socialcommerce_mailtemplates` (
  `mailtemplate_id` int(11) unsigned NOT NULL auto_increment,
  `type` varchar(255) NOT NULL,
  `vars` varchar(255) NOT NULL,
  PRIMARY KEY  (`mailtemplate_id`),
  UNIQUE KEY `type` (`type`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=37 ;

--
-- Dumping data for table `engine4_socialstore_mailtemplates`
--

INSERT INTO `engine4_socialcommerce_mailtemplates` (`mailtemplate_id`, `type`, `vars`) VALUES
(2, 'stall_header', ''),
(3, 'stall_footer', ''),
(4, 'stall_headermember', ''),
(5, 'stall_footermember', ''),
(9, 'stall_approvestore', ''),
(10, 'stall_approveproduct', ''),
(14, 'stall_purchasebuyer', ''),
(15, 'stall_purchaseseller', ''),
(18, 'stall_requestaccept', ''),
(20, 'stall_requestdeny', ''),
(23, 'stall_productdelete', ''),
(24, 'stall_productdelfav', ''),
(25, 'stall_follownotice', ''),
(35, 'stall_refundbuyer', ''),
(36, 'stall_refundseller', '');

CREATE TABLE IF NOT EXISTS `engine4_socialcommerce_emails` (
  `email_id` int(11) unsigned NOT NULL auto_increment,
  `sended` tinyint(1) NOT NULL default '0',
  `priority` tinyint(3) unsigned NOT NULL default '0',
  `type` varchar(128) NOT NULL default '',
  `creation_date` datetime NOT NULL,
  `send_from` varchar(128) NOT NULL default '',
  `from_name` varchar(128) NOT NULL default '',
  `subject` varchar(256) NOT NULL,
  `send_to` varchar(128) NOT NULL,
  `to_name` varchar(128) NOT NULL,
  `body_text` text,
  `body_html` text,
  PRIMARY KEY  (`email_id`),
  KEY `sended` (`sended`),
  KEY `priority` (`priority`),
  KEY `type` (`type`),
  KEY `creation_date` (`creation_date`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `engine4_socialcommerce_shippingaddresses` (
	`shippingaddress_id` int(11) unsigned NOT NULL auto_increment,
	`user_id` int(11) unsigned,
	`order_id` varchar(32) NOT NULL,
	`value` text NOT NULL,
	`creation_date` datetime NULL,
	PRIMARY KEY (`shippingaddress_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `engine4_socialcommerce_paytrans` (
  `paytran_id` bigint(20) unsigned NOT NULL auto_increment,
  `gateway` varchar(32) NOT NULL,
  `owner_id` int(11) unsigned NOT NULL,
  `transaction_id` varchar(64) NOT NULL,
  `transaction_type` varchar(64) NOT NULL,
  `payment_status` varchar(64) NOT NULL,
  `gateway_fee` decimal(16,2) NOT NULL default '0.00',
  `currency` char(3) NOT NULL,
  `amount` decimal(16,4) unsigned NOT NULL default '0.0000',
  `payment_type` varchar(64) default NULL,
  `gateway_token` varchar(64) default NULL,
  `pending_reason` varchar(256) default NULL,
  `error_code` varchar(256) default NULL,
  `order_id` varchar(20) NOT NULL,
  `timestamp` varchar(64) NOT NULL,
  `order_time` varchar(64) NOT NULL,
  `creation_date` datetime NOT NULL,
  PRIMARY KEY  (`paytran_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

UPDATE `engine4_core_menuitems` SET `params` = '{"route":"socialcommerce_cart"}', `menu` = 'socialcommerce_main',`name` = 'socialcommerce_main_bags', `order` = '3' WHERE `engine4_core_menuitems`.`name` = 'socialcommerce_buyer_bags';

ALTER TABLE `engine4_socialcommerce_stalls` ADD `cover_id` int(10) DEFAULT '0';
ALTER TABLE `engine4_socialcommerce_stalls` ADD `feature_days` int(10) NOT NULL DEFAULT '0';
ALTER TABLE `engine4_socialcommerce_stalls` ADD `feature_end_time` datetime DEFAULT NULL;
ALTER TABLE `engine4_socialcommerce_stalls` ADD `is_featured` tinyint(1) NOT NULL DEFAULT '0';

ALTER TABLE  `engine4_album_photos` CHANGE  `item_id`  `item_id` INT( 10 ) NULL DEFAULT NULL ;

ALTER TABLE  `engine4_socialcommerce_stalls` ADD  `sold_products` INT( 10 ) NOT NULL DEFAULT  '0' AFTER `cover_id` ;

CREATE TABLE IF NOT EXISTS `engine4_socialcommerce_accounts` (
  `account_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(11) UNSIGNED NOT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `web_address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country` varchar(2) COLLATE utf8_unicode_ci DEFAULT NULL,
  `business_name` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `zip_code` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mobile` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `creation_date` datetime NOT NULL,
  `modified_date` datetime NOT NULL,
   PRIMARY KEY (`account_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO  `engine4_core_menuitems` (
`id` ,
`name` ,
`module` ,
`label` ,
`plugin` ,
`params` ,
`menu` ,
`submenu` ,
`enabled` ,
`custom` ,
`order`
)
VALUES (
NULL ,  'socialcommerce_main_faqs',  'socialcommerce',  'FAQs',  '', '{"route":"socialcommerce_general","controller":"faqs","action":"index"}',  'socialcommerce_main',  '',  '1',  '0',  '5'
);
INSERT INTO  `engine4_core_menuitems` (
`id` ,
`name` ,
`module` ,
`label` ,
`plugin` ,
`params` ,
`menu` ,
`submenu` ,
`enabled` ,
`custom` ,
`order`
)
VALUES (
NULL ,  'socialcommerce_main_help',  'socialcommerce',  'Help',  '', '{"route":"socialcommerce_general","controller":"help","action":"index"}',  'socialcommerce_main',  '',  '1',  '0',  '4'
);