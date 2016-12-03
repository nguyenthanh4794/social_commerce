ALTER TABLE `engine4_socialcommerce_requests` ADD `request_type` ENUM('payment','refund') NOT NULL DEFAULT 'payment';
ALTER TABLE `engine4_socialcommerce_requests` ADD `commission` FLOAT(10,2) NOT NULL DEFAULT '0.00';
ALTER TABLE `engine4_socialcommerce_requests` ADD `tc_account_id` INT(10) NULL DEFAULT NULL AFTER `owner_id`;
ALTER TABLE `engine4_socialcommerce_requests` ADD `commission_fee` FLOAT(10,2) NULL DEFAULT '0.00';
ALTER TABLE `engine4_socialcommerce_requests` ADD `send_amount` FLOAT(10,2) NOT NULL DEFAULT '0.00' AFTER `request_amount`;

INSERT INTO `engine4_core_menuitems` (`id`, `name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `enabled`, `custom`, `order`) VALUES (NULL, 'socialcommerce_seller_buying-activities', 'socialcommerce', 'Buying Activites', '', '{"route":"socialcommerce_general","controller":"seller","action":"buying-activities","icon":"application/modules/Socialcommerce/externals/images/links/trader.png","class":"socialcommerce_seller_menu_item socialcommerce_buying","icon-class":"fa fa-shopping-cart"}', 'socialcommerce_seller', '', '1', '0', '2');
UPDATE `engine4_core_menuitems` SET `order` = '3' WHERE `engine4_core_menuitems`.`name` = 'socialcommerce_seller_payment';

UPDATE  `social_commerce`.`engine4_core_menuitems` SET  `params` = '{"route":"socialcommerce_general","controller":"account","icon":"application/modules/Socialcommerce/externals/images/links/item.png","class":"socialcommerce_seller_menu_item socialcommerce_tcaccount","icon-class":"fa fa-user"}' WHERE  `engine4_core_menuitems`.`name` ='socialcommerce_seller_account';
ALTER TABLE `engine4_socialcommerce_orderitems` ADD `owner_id` INT(10) NULL DEFAULT NULL;