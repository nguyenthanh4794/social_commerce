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
  PRIMARY KEY (`stall_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `engine4_socialcommerce_reviews` (
  `review_id` int(11) NOT NULL AUTO_INCREMENT,
  `stall_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `body` longtext COLLATE utf8_unicode_ci NOT NULL,
  `rate_number` smallint(5) unsigned NOT NULL,
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

--
-- Insert back-end menu items
--

INSERT IGNORE INTO `engine4_core_menus` (`name`, `type`, `title`, `order`) VALUES
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

  ('socialcommerce_link_home', 'user', 'Trader Club', 'Socialcommerce_Plugin_Menus', '{"route":"socialcommerce_general","controller":"stall","action":"browse","icon":"application/modules/Socialcommerce/externals/images/links/trader.png"}', 'socialcommerce_link', '', 1),
  ('socialcommerce_link_my-bag', 'user', 'My Bag', 'Socialcommerce_Plugin_Menus', '{"route":"socialcommerce_general","controller":"stall","action":"browse","icon":"application/modules/Socialcommerce/externals/images/links/bag.png"}', 'socialcommerce_link', '', 2),
  ('socialcommerce_link_seller-account', 'user', 'Stalls', 'Socialcommerce_Plugin_Menus', '{"route":"socialcommerce_general","controller":"stall","action":"browse","icon":"application/modules/Socialcommerce/externals/images/links/seller.png"}', 'socialcommerce_link', '', 3),
  ('socialcommerce_link_item-to-buy', 'user', 'Item to buy', 'Socialcommerce_Plugin_Menus', '{"route":"socialcommerce_general","controller":"stall","action":"browse","icon":"application/modules/Socialcommerce/externals/images/links/item.png"}', 'socialcommerce_link', '', 4),
  ('socialcommerce_link_create-stall', 'user', 'Create Stall', 'Socialcommerce_Plugin_Menus', '{"route":"socialcommerce_general","controller":"stall","action":"create-step-one","icon":"application/modules/Socialcommerce/externals/images/links/plus.png"}', 'socialcommerce_link', '', 5),

  ('core_main_socialcommerce', 'socialcommerce', 'Social Commerce', '', '{"route":"socialcommerce_general"}', 'core_main', '', 999),
  ('socialcommerce_main_home', 'socialcommerce', 'Home Page', '', '{"route":"socialcommerce_general","action":"index"}', 'socialcommerce_main', '', 1),
  ('socialcommerce_main_browse', 'socialcommerce', 'Products', '', '{"route":"socialcommerce_general","action":"browse"}', 'socialcommerce_main', '', 2),
  ('socialcommerce_main_stall', 'socialcommerce', 'Stalls', 'Socialcommerce_Plugin_Menus::canCreateProduct', '{"route":"socialcommerce_general","controller":"stall","action":"browse"}', 'socialcommerce_main', '', 3),
  ('socialcommerce_main_manage', 'socialcommerce', 'My Products', 'Socialcommerce_Plugin_Menus::canCreateProduct', '{"route":"socialcommerce_general","action":"manage"}', 'socialcommerce_main', '', 4),
  ('socialcommerce_main_create-stall', 'socialcommerce', 'Create New Stall', 'Socialcommerce_Plugin_Menus::canCreateProduct', '{"route":"socialcommerce_general","controller":"stall","action":"create-step-one"}', 'socialcommerce_main', '', 5);

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

