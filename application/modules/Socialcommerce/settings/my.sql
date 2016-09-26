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

--
-- Insert back-end menu items
--

INSERT IGNORE INTO `engine4_core_menus` (`name`, `type`, `title`, `order`) VALUES
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
  ('socialcommerce_admin_main_faqs', 'socialcommerce', 'Manage FAQs', '', '{"route":"admin_default","module":"socialcommerce","controller":"faqs", "action":"index"}', 'socialcommerce_admin_main', '', 10);

INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
  ('core_main_socialcommerce', 'socialcommerce', 'Social Commerce', 'Ynmultilisting_Plugin_Menus::hasListingType', '{"route":"socialcommerce_general"}', 'core_main', '', 999),
  ('socialcommerce_main_home', 'socialcommerce', 'Home Page', '', '{"route":"socialcommerce_general","action":"index"}', 'socialcommerce_main', '', 1),
  ('socialcommerce_main_browse', 'socialcommerce', 'Products', '', '{"route":"socialcommerce_general","action":"browse"}', 'socialcommerce_main', '', 2),
  ('socialcommerce_main_stall', 'socialcommerce', 'Stalls', 'Socialcommerce_Plugin_Menus::canCreateProduct', '{"route":"socialcommerce_general","action":"stall"}', 'socialcommerce_main', '', 3),
  ('socialcommerce_main_manage', 'socialcommerce', 'My Products', 'Socialcommerce_Plugin_Menus::canCreateProduct', '{"route":"socialcommerce_general","action":"manage"}', 'socialcommerce_main', '', 4);
