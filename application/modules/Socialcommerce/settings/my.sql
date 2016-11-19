INSERT IGNORE INTO `engine4_core_modules` (`name`, `title`, `description`, `version`, `enabled`, `type`) VALUES  ('socialcommerce', 'Social - Commerce', '', '4.0.1', 1, 'extra') ;

--
-- Change table permissions (change length of column type)
--

ALTER TABLE `engine4_authorization_permissions` MODIFY `type` VARCHAR(64);
ALTER TABLE `engine4_activity_notifications` MODIFY  `type` VARCHAR(64) NOT NULL;
ALTER TABLE `engine4_activity_notificationtypes` MODIFY  `type` VARCHAR(64) NOT NULL;
ALTER TABLE `engine4_activity_actiontypes` CHANGE  `type`  `type` VARCHAR( 64 ) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL ;
ALTER TABLE `engine4_activity_actions` CHANGE  `type`  `type` VARCHAR( 64 ) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL ;
ALTER TABLE `engine4_activity_stream` CHANGE  `type`  `type` VARCHAR( 64 ) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL ;



-- --------------------------------------------------------
--
-- Table structure for table `engine4_socialcommerce_categories`
--
CREATE TABLE IF NOT EXISTS `engine4_socialcommerce_categories` (
  `category_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `parent_id` int(11) unsigned DEFAULT NULL,
  `pleft` int(11) unsigned NOT NULL,
  `pright` int(11) unsigned NOT NULL,
  `level` int(11) unsigned NOT NULL DEFAULT '0',
  `title` varchar(64) NOT NULL,
  `photo_id` int(11) DEFAULT NULL,
  `themes` text,
  `use_parent_category` tinyint(1) NOT NULL DEFAULT '0',
  `order` smallint(6) NOT NULL DEFAULT '0',
  `option_id` int(11) NOT NULL,
  PRIMARY KEY (`category_id`),
  KEY `user_id` (`user_id`),
  KEY `parent_id` (`parent_id`),
  KEY `pleft` (`pleft`),
  KEY `pright` (`pright`),
  KEY `level` (`level`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE `engine4_socialcommerce_products` (
  `product_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `price` float(10,2) NOT NULL DEFAULT '0.00',
  `description` text,
  `file` varchar(10),
  PRIMARY KEY (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `engine4_socialcommerce_stalls` (
  `stall_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `category` int(10) NOT NULL,
  `photo_id` int(10) DEFAULT '0',
  `owner_type` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `owner_id` int(10) NOT NULL,
  `creation_date` datetime NOT NULL,
  `modified_date` datetime NOT NULL,
  `cover_photo` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `location` text COLLATE utf8_unicode_ci NOT NULL,
  `short_description` text COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `longitude` varchar(36) COLLATE utf8_unicode_ci NOT NULL,
  `latitude` varchar(36) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `web_address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `price_range` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`stall_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


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


  CREATE TABLE `engine4_socialcommerce_products` (
    `product_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `stall_id` int(10) UNSIGNED NOT NULL,
    `owner_id` int(10) NOT NULL,
    `owner_type` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
    `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
    `price` float(10,2) NOT NULL DEFAULT '0.00',
    `featured` tinyint(1) NOT NULL DEFAULT '0',
    `description` text COLLATE utf8_unicode_ci,
    `short_description` text COLLATE utf8_unicode_ci NOT NULL,
    `file` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
    `photo_id` int(10) DEFAULT '0',
    `view_count` int(10) NOT NULL DEFAULT '0',
    `like_count` int(10) NOT NULL DEFAULT '0',
    `view_time` datetime NOT NULL,
    `comment_count` int(10) NOT NULL DEFAULT '0',
    `creation_date` datetime NOT NULL,
    `modified_date` datetime NOT NULL,
    PRIMARY KEY (`product_id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `engine4_socialcommerce_reviews` (
  `review_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `item_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `body` longtext COLLATE utf8_unicode_ci NOT NULL,
  `rate_number` smallint(5) UNSIGNED NOT NULL,
  `type` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `creation_date` datetime NOT NULL,
  `modified_date` datetime NOT NULL,
  PRIMARY KEY (`review_id`),
  KEY `stall_id` (`stall_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `engine4_socialcommerce_packages` (
  `package_id` int(10) UNSIGNED NOT NULL,
  `title` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `seller_receive` int(10) UNSIGNED NOT NULL DEFAULT  '0',
  `admin_receive` int(10) UNSIGNED NOT NULL DEFAULT  '0',
  `money_distribution_method` TINYINT(1),
  `number_first_publish` decimal(16,2) UNSIGNED NOT NULL DEFAULT  '0.00',
  `fee_first_publish` decimal(16,2) UNSIGNED NOT NULL DEFAULT  '0.00',
  `fee_normal` decimal(16,2) UNSIGNED NOT NULL DEFAULT  '0.00',
  `payment_gateway` TEXT,
  PRIMARY KEY (`package_id`),
  KEY `title` (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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
  `commission_amount` decimal(16,2) NOT NULL default '0.00',
  `sub_amount` decimal(16,2) NOT NULL default '0.00',
  `delivery_status` enum('processing','shipping','delivered') NOT NULL default 'processing',
  `payment_status` varchar(50) NOT NULL default 'processing',
  `total_amount` decimal(16,2) NOT NULL,
  `currency` varchar(3) NOT NULL,
  `name` varchar(128) NOT NULL,
  `description` varchar(128) NOT NULL,
  `refund_status` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`orderitem_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- Dumping data for table `engine4_socialcommerce_categories`
--

INSERT IGNORE INTO `engine4_socialcommerce_categories` (`category_id`, `user_id`, `parent_id`, `pleft`, `pright`, `level`, `title`,`option_id`) VALUES
  (1, 0, NULL, 1, 4, 0, 'All Categories','0');


-- --------------------------------------------------------

--
-- Table structure for table `engine4_socialcommerce_listing_fields_maps`
--

CREATE TABLE IF NOT EXISTS `engine4_socialcommerce_listing_fields_maps` (
  `field_id` int(11) unsigned NOT NULL,
  `option_id` int(11) unsigned NOT NULL,
  `child_id` int(11) unsigned NOT NULL,
  `order` smallint(6) NOT NULL,
  PRIMARY KEY (`field_id`,`option_id`,`child_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `engine4_socialcommerce_listing_fields_maps`
--

INSERT IGNORE INTO `engine4_socialcommerce_listing_fields_maps` (`field_id`, `option_id`, `child_id`, `order`) VALUES
  (0, 0, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `engine4_socialcommerce_listing_fields_meta`
--

CREATE TABLE IF NOT EXISTS `engine4_socialcommerce_listing_fields_meta` (
  `field_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(24) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `label` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `alias` varchar(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `required` tinyint(1) NOT NULL DEFAULT '0',
  `display` tinyint(1) unsigned NOT NULL,
  `publish` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `search` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `order` smallint(3) unsigned NOT NULL DEFAULT '999',
  `config` text COLLATE utf8_unicode_ci,
  `validators` text COLLATE utf8_unicode_ci,
  `filters` text COLLATE utf8_unicode_ci,
  `style` text COLLATE utf8_unicode_ci,
  `error` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`field_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;

--
-- Dumping data for table `engine4_socialcommerce_listing_fields_meta`
--

INSERT IGNORE INTO `engine4_socialcommerce_listing_fields_meta` (`field_id`, `type`, `label`, `description`, `alias`, `required`, `display`, `publish`, `search`, `order`, `config`, `validators`, `filters`, `style`, `error`) VALUES
  (1, 'profile_type', 'Profile Type', '', 'profile_type', 1, 0, 0, 2, 999, '', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `engine4_socialcommerce_listing_fields_options`
--

CREATE TABLE IF NOT EXISTS `engine4_socialcommerce_listing_fields_options` (
  `option_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `field_id` int(11) unsigned NOT NULL,
  `label` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `order` smallint(6) NOT NULL DEFAULT '999',
  PRIMARY KEY (`option_id`),
  KEY `field_id` (`field_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;

-- --------------------------------------------------------

--
-- Table structure for table `engine4_socialcommerce_listing_fields_search`
--

CREATE TABLE IF NOT EXISTS `engine4_socialcommerce_listing_fields_search` (
  `item_id` int(11) unsigned NOT NULL,
  `profile_type` enum('1','4') COLLATE utf8_unicode_ci DEFAULT NULL,
  `first_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gender` smallint(6) unsigned DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  PRIMARY KEY (`item_id`),
  KEY `first_name` (`first_name`),
  KEY `last_name` (`last_name`),
  KEY `gender` (`gender`),
  KEY `birthdate` (`birthdate`),
  KEY `profile_type` (`profile_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `engine4_socialcommerce_listing_fields_values`
--

CREATE TABLE IF NOT EXISTS `engine4_socialcommerce_listing_fields_values` (
  `item_id` int(11) unsigned NOT NULL,
  `field_id` int(11) unsigned NOT NULL,
  `index` smallint(3) unsigned NOT NULL DEFAULT '0',
  `value` text COLLATE utf8_unicode_ci NOT NULL,
  `privacy` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`item_id`,`field_id`,`index`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE `engine4_product_photos` (
  `photo_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `album_id` int(11) UNSIGNED NOT NULL,
  `product_id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `title` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `collection_id` int(11) UNSIGNED NOT NULL,
  `file_id` int(11) UNSIGNED NOT NULL,
  `creation_date` datetime NOT NULL,
  `modified_date` datetime NOT NULL,
  `view_count` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `comment_count` int(11) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`photo_id`),
  KEY `album_id` (`album_id`),
  KEY `product_id` (`product_id`),
  KEY `collection_id` (`collection_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE `engine4_socialcommerce_accounts` (
  `account_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(11) UNSIGNED NOT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci,
  `city` varchar(255) COLLATE utf8_unicode_ci,
  `web_address` varchar(255) COLLATE utf8_unicode_ci,
  `country` varchar(2),
  `business_name` varchar(128),
  `zip_code` varchar(10),
  `mobile` varchar(20),
  `gateway_id` varchar(32) NOT NULL default 'paypal',
  `account_username` varchar(128) default NULL,
  `account_password` varchar(128) default NULL,
  `config` text NOT NULL,
  `creation_date` datetime NOT NULL,
  `modified_date` datetime NOT NULL,
  PRIMARY KEY (`account_id`),
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;





--
-- Insert back-end menu items
--

INSERT IGNORE INTO `engine4_core_menus` (`name`, `type`, `title`, `order`) VALUES
  ('socialcommerce_buyer', 'standard', 'Social Commerce Buyer Menu', 997),
  ('socialcommerce_seller', 'standard', 'Social Commerce Seller Menu', 997),
  ('socialcommerce_link', 'standard', 'Social Commerce Quick Links Menu', 998),
  ('socialcommerce_main', 'standard', 'Social Commerce Main Navigation Menu', 999);

INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
  ('core_admin_main_plugins_socialcommerce', 'socialcommerce', 'Social Commerce', '', '{"route":"admin_default","module":"socialcommerce","controller":"settings", "action":"global"}', 'core_admin_main_plugins', '', 999),
  ('socialcommerce_admin_settings_global', 'socialcommerce', 'Global Settings', '', '{"route":"admin_default","module":"socialcommerce","controller":"settings", "action":"global"}', 'socialcommerce_admin_main', '', 1),
  ('socialcommerce_admin_settings_level', 'socialcommerce', 'Member Level Settings', '', '{"route":"admin_default","module":"socialcommerce","controller":"settings", "action":"level"}', 'socialcommerce_admin_main', '', 2),
  ('socialcommerce_admin_main_listings', 'socialcommerce', 'Manage Listings', '', '{"route":"admin_default","module":"socialcommerce","controller":"listings", "action":"index"}', 'socialcommerce_admin_main', '', 3),
  ('socialcommerce_admin_main_categories', 'socialcommerce', 'Manage Categories', '', '{"route":"admin_default","module":"socialcommerce","controller":"categories", "action":"index"}', 'socialcommerce_admin_main', '', 4),
  ('socialcommerce_admin_main_plans', 'socialcommerce', 'Manage Payment Plans', '', '{"route":"admin_default","module":"socialcommerce","controller":"payments", "action":"index"}', 'socialcommerce_admin_main', '', 5),
  ('socialcommerce_admin_main_statistic', 'socialcommerce', 'Statistic', '', '{"route":"admin_default","module":"socialcommerce","controller":"statistics", "action":"index"}', 'socialcommerce_admin_main', '', 6),
  ('socialcommerce_admin_main_transactions', 'socialcommerce', 'Manage Transactions', '', '{"route":"admin_default","module":"socialcommerce","controller":"transactions", "action":"index"}', 'socialcommerce_admin_main', '', 7),
  ('socialcommerce_admin_main_requests', 'socialcommerce', 'Manage Money Requests', '', '{"route":"admin_default","module":"socialcommerce","controller":"requests", "action":"index"}', 'socialcommerce_admin_main', '', 8),
  ('socialcommerce_admin_main_accounts', 'socialcommerce', 'Manage Accounts', '', '{"route":"admin_default","module":"socialcommerce","controller":"accounts", "action":"index"}', 'socialcommerce_admin_main', '', 9),
  ('socialcommerce_admin_main_faqs', 'socialcommerce', 'Manage FAQs', '', '{"route":"admin_default","module":"socialcommerce","controller":"faqs", "action":"index"}', 'socialcommerce_admin_main', '', 10),

  ('socialcommerce_link_home', 'user', 'Trader Club', '', '{"route":"socialcommerce_general","controller":"stall","action":"browse","icon":"application/modules/Socialcommerce/externals/images/links/trader.png"}', 'socialcommerce_link', '', 1),
  ('socialcommerce_link_my-bag', 'user', 'My Bag', '', '{"route":"socialcommerce_general","controller":"stall","action":"browse","icon":"application/modules/Socialcommerce/externals/images/links/bag.png"}', 'socialcommerce_link', '', 2),
  ('socialcommerce_link_seller-account', 'user', 'Stalls', '', '{"route":"socialcommerce_general","controller":"stall","action":"browse","icon":"application/modules/Socialcommerce/externals/images/links/seller.png"}', 'socialcommerce_link', '', 3),
  ('socialcommerce_link_item-to-buy', 'user', 'Item to buy', '', '{"route":"socialcommerce_general","controller":"stall","action":"browse","icon":"application/modules/Socialcommerce/externals/images/links/item.png"}', 'socialcommerce_link', '', 4),
  ('socialcommerce_link_create-stall', 'user', 'Create Stall', '', '{"route":"socialcommerce_general","controller":"stall","action":"create-step-one","icon":"application/modules/Socialcommerce/externals/images/links/plus.png"}', 'socialcommerce_link', '', 5),

  ('socialcommerce_seller_info', 'socialcommerce', 'Seller Information', '', '{"route":"socialcommerce_general","controller":"seller","action":"info","icon":"application/modules/Socialcommerce/externals/images/links/trader.png"}', 'socialcommerce_seller', '', 1),
  ('socialcommerce_seller_payment', 'socialcommerce', 'Setting For Payment', '', '{"route":"socialcommerce_general","controller":"seller","action":"payment","icon":"application/modules/Socialcommerce/externals/images/links/bag.png"}', 'socialcommerce_seller', '', 2),
  ('socialcommerce_seller_listings', 'socialcommerce', 'My Listings', '', '{"route":"socialcommerce_general","controller":"seller","action":"manage-listings","icon":"application/modules/Socialcommerce/externals/images/links/seller.png"}', 'socialcommerce_seller', '', 3),
  ('socialcommerce_seller_stalls', 'socialcommerce', 'My Stalls', '', '{"route":"socialcommerce_general","controller":"seller","action":"manage-stalls","icon":"application/modules/Socialcommerce/externals/images/links/item.png"}', 'socialcommerce_seller', '', 4),
  ('socialcommerce_seller_transaction', 'socialcommerce', 'Transaction', '', '{"route":"socialcommerce_general","controller":"seller","action":"transaction","icon":"application/modules/Socialcommerce/externals/images/links/plus.png"}', 'socialcommerce_seller', '', 5),

  ('socialcommerce_buyer_bags', 'socialcommerce', 'My Bag', '', '{"route":"socialcommerce_general","controller":"buyer","action":"my-bags","icon":"application/modules/Socialcommerce/externals/images/links/trader.png"}', 'socialcommerce_buyer', '', 1),
  ('socialcommerce_buyer_payment', 'socialcommerce', 'Setting For Payment', '', '{"route":"socialcommerce_general","controller":"buyer","action":"payment","icon":"application/modules/Socialcommerce/externals/images/links/bag.png"}', 'socialcommerce_buyer', '', 2),
  ('socialcommerce_buyer_history', 'socialcommerce', 'My Buying History', '', '{"route":"socialcommerce_general","controller":"buyer","action":"my-historys","icon":"application/modules/Socialcommerce/externals/images/links/seller.png"}', 'socialcommerce_buyer', '', 3),
  ('socialcommerce_buyer_transaction', 'socialcommerce', 'Transaction', '', '{"route":"socialcommerce_general","controller":"buyer","action":"transaction","icon":"application/modules/Socialcommerce/externals/images/links/seller.png"}', 'socialcommerce_buyer', '', 4),

  ('core_main_socialcommerce', 'socialcommerce', 'Social Commerce', '', '{"route":"socialcommerce_general"}', 'core_main', '', 999),
  ('socialcommerce_main_home', 'socialcommerce', 'Home Page', '', '{"route":"socialcommerce_general","action":"index"}', 'socialcommerce_main', '', 1),
  ('socialcommerce_main_browse', 'socialcommerce', 'Products', 'Socialcommerce_Plugin_Menus::canCreateListing', '{"route":"socialcommerce_general","controller":"product","action":"browse"}', 'socialcommerce_main', '', 2),
  ('socialcommerce_main_stall', 'socialcommerce', 'Stalls', 'Socialcommerce_Plugin_Menus::canCreateStall', '{"route":"socialcommerce_general","controller":"stall","action":"browse"}', 'socialcommerce_main', '', 3),
  ('socialcommerce_main_manage', 'socialcommerce', 'My Account', '', '{"route":"socialcommerce_general","controller":"seller","action":"info"}', 'socialcommerce_main', '', 4),
  ('socialcommerce_main_create-stall', 'socialcommerce', 'Create New Stall', 'Socialcommerce_Plugin_Menus::canCreateStall', '{"route":"socialcommerce_general","controller":"stall","action":"create-step-one"}', 'socialcommerce_main', '', 5);

INSERT IGNORE INTO `engine4_activity_actiontypes` (`type`, `module`, `body`, `enabled`, `displayable`, `attachable`, `commentable`, `shareable`, `is_generated`) VALUES
  ('stall_new', 'socialcommerce', '{item:$subject} created a new stall:', 1, 5, 1, 3, 1, 1),
  ('comment_stall', 'socialcommerce', '{item:$subject} commented on {item:$owner}''s {item:$object:stall}: {body:$body}', 1, 1, 1, 1, 1, 0),
  ('socialcommerce_video_create', 'socialcommerce', '{item:$subject} posted a new video.', 1, 3, 1, 1, 1, 1);

-- ALL
-- auth_view, auth_comment, auth_html
INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    'socialcommerce_product' as `type`,
    'auth_view' as `name`,
    5 as `value`,
    '["everyone","owner_network","owner_member_member","owner_member","owner"]' as `params`
  FROM `engine4_authorization_levels` WHERE `type` NOT IN('public');
INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    'socialcommerce_product' as `type`,
    'auth_comment' as `name`,
    5 as `value`,
    '["everyone","owner_network","owner_member_member","owner_member","owner"]' as `params`
  FROM `engine4_authorization_levels` WHERE `type` NOT IN('public');
INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    'socialcommerce_product' as `type`,
    'auth_share' as `name`,
    5 as `value`,
    '["everyone","owner_network","owner_member_member","owner_member","owner"]' as `params`
  FROM `engine4_authorization_levels` WHERE `type` NOT IN('public');


-- ADMIN, MODERATOR
-- create, delete, edit, view, comment, css, style, max
INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    'socialcommerce_product' as `type`,
    'create' as `name`,
    1 as `value`,
    NULL as `params`
  FROM `engine4_authorization_levels` WHERE `type` IN('moderator', 'admin');
INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    'socialcommerce_product' as `type`,
    'delete' as `name`,
    2 as `value`,
    NULL as `params`
  FROM `engine4_authorization_levels` WHERE `type` IN('moderator', 'admin');
INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    'socialcommerce_product' as `type`,
    'edit' as `name`,
    2 as `value`,
    NULL as `params`
  FROM `engine4_authorization_levels` WHERE `type` IN('moderator', 'admin');
INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    'socialcommerce_product' as `type`,
    'view' as `name`,
    2 as `value`,
    NULL as `params`
  FROM `engine4_authorization_levels` WHERE `type` IN('moderator', 'admin');
INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    'socialcommerce_product' as `type`,
    'comment' as `name`,
    2 as `value`,
    NULL as `params`
  FROM `engine4_authorization_levels` WHERE `type` IN('moderator', 'admin');
INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    'socialcommerce_product' as `type`,
    'share' as `name`,
    2 as `value`,
    NULL as `params`
  FROM `engine4_authorization_levels` WHERE `type` IN('moderator', 'admin');
INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    'socialcommerce_product' as `type`,
    'max' as `name`,
    3 as `value`,
    1000 as `params`
  FROM `engine4_authorization_levels` WHERE `type` IN('moderator', 'admin');

-- USER
-- create, delete, edit, view, comment, css, style, max
INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    'socialcommerce_product' as `type`,
    'create' as `name`,
    1 as `value`,
    NULL as `params`
  FROM `engine4_authorization_levels` WHERE `type` IN('user');
INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    'socialcommerce_product' as `type`,
    'delete' as `name`,
    1 as `value`,
    NULL as `params`
  FROM `engine4_authorization_levels` WHERE `type` IN('user');
INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    'socialcommerce_product' as `type`,
    'edit' as `name`,
    1 as `value`,
    NULL as `params`
  FROM `engine4_authorization_levels` WHERE `type` IN('user');
INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    'socialcommerce_product' as `type`,
    'view' as `name`,
    1 as `value`,
    NULL as `params`
  FROM `engine4_authorization_levels` WHERE `type` IN('user');
INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    'socialcommerce_product' as `type`,
    'comment' as `name`,
    1 as `value`,
    NULL as `params`
  FROM `engine4_authorization_levels` WHERE `type` IN('user');
INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    'socialcommerce_product' as `type`,
    'share' as `name`,
    1 as `value`,
    NULL as `params`
  FROM `engine4_authorization_levels` WHERE `type` IN('user');
INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    'socialcommerce_product' as `type`,
    'max' as `name`,
    3 as `value`,
    50 as `params`
  FROM `engine4_authorization_levels` WHERE `type` IN('user');

-- PUBLIC
-- view
INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    'socialcommerce_product' as `type`,
    'view' as `name`,
    1 as `value`,
    NULL as `params`
  FROM `engine4_authorization_levels` WHERE `type` IN('public');


-- ALL
-- auth_view, auth_comment, auth_html
INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    'socialcommerce_stall' as `type`,
    'auth_view' as `name`,
    5 as `value`,
    '["everyone","owner_network","owner_member_member","owner_member","owner"]' as `params`
  FROM `engine4_authorization_levels` WHERE `type` NOT IN('public');
INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    'socialcommerce_stall' as `type`,
    'auth_comment' as `name`,
    5 as `value`,
    '["everyone","owner_network","owner_member_member","owner_member","owner"]' as `params`
  FROM `engine4_authorization_levels` WHERE `type` NOT IN('public');
INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    'socialcommerce_stall' as `type`,
    'auth_share' as `name`,
    5 as `value`,
    '["everyone","owner_network","owner_member_member","owner_member","owner"]' as `params`
  FROM `engine4_authorization_levels` WHERE `type` NOT IN('public');


-- ADMIN, MODERATOR
-- create, delete, edit, view, comment, css, style, max
INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    'socialcommerce_stall' as `type`,
    'create' as `name`,
    1 as `value`,
    NULL as `params`
  FROM `engine4_authorization_levels` WHERE `type` IN('moderator', 'admin');
INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    'socialcommerce_stall' as `type`,
    'delete' as `name`,
    2 as `value`,
    NULL as `params`
  FROM `engine4_authorization_levels` WHERE `type` IN('moderator', 'admin');
INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    'socialcommerce_stall' as `type`,
    'edit' as `name`,
    2 as `value`,
    NULL as `params`
  FROM `engine4_authorization_levels` WHERE `type` IN('moderator', 'admin');
INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    'socialcommerce_stall' as `type`,
    'view' as `name`,
    2 as `value`,
    NULL as `params`
  FROM `engine4_authorization_levels` WHERE `type` IN('moderator', 'admin');
INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    'socialcommerce_stall' as `type`,
    'comment' as `name`,
    2 as `value`,
    NULL as `params`
  FROM `engine4_authorization_levels` WHERE `type` IN('moderator', 'admin');
INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    'socialcommerce_stall' as `type`,
    'share' as `name`,
    2 as `value`,
    NULL as `params`
  FROM `engine4_authorization_levels` WHERE `type` IN('moderator', 'admin');
INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    'socialcommerce_stall' as `type`,
    'max' as `name`,
    3 as `value`,
    1000 as `params`
  FROM `engine4_authorization_levels` WHERE `type` IN('moderator', 'admin');

-- USER
-- create, delete, edit, view, comment, css, style, max
INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    'socialcommerce_stall' as `type`,
    'create' as `name`,
    1 as `value`,
    NULL as `params`
  FROM `engine4_authorization_levels` WHERE `type` IN('user');
INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    'socialcommerce_stall' as `type`,
    'delete' as `name`,
    1 as `value`,
    NULL as `params`
  FROM `engine4_authorization_levels` WHERE `type` IN('user');
INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    'socialcommerce_stall' as `type`,
    'edit' as `name`,
    1 as `value`,
    NULL as `params`
  FROM `engine4_authorization_levels` WHERE `type` IN('user');
INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    'socialcommerce_stall' as `type`,
    'view' as `name`,
    1 as `value`,
    NULL as `params`
  FROM `engine4_authorization_levels` WHERE `type` IN('user');
INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    'socialcommerce_stall' as `type`,
    'comment' as `name`,
    1 as `value`,
    NULL as `params`
  FROM `engine4_authorization_levels` WHERE `type` IN('user');
INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    'socialcommerce_stall' as `type`,
    'share' as `name`,
    1 as `value`,
    NULL as `params`
  FROM `engine4_authorization_levels` WHERE `type` IN('user');
INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    'socialcommerce_stall' as `type`,
    'max' as `name`,
    3 as `value`,
    50 as `params`
  FROM `engine4_authorization_levels` WHERE `type` IN('user');

-- PUBLIC
-- view
INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    'socialcommerce_stall' as `type`,
    'view' as `name`,
    1 as `value`,
    NULL as `params`
  FROM `engine4_authorization_levels` WHERE `type` IN('public');

