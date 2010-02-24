DROP TABLE IF EXISTS `xrowpaymentobject`;
CREATE TABLE xrowpaymentobject (
  id int(11) NOT NULL auto_increment,
  order_id int(11) NOT NULL default '0',
  payment_string varchar(255) NOT NULL default '',
  status int(11) NOT NULL default '0',
  data text NOT NULL,
  PRIMARY KEY  (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO ezorder_status (id, is_active, name, status_id) VALUES ( null,1,'Canceled',1000);

DROP TABLE IF EXISTS `xrow_recurring_order_collection`;
CREATE TABLE  `xrow_recurring_order_collection` (
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `status` int(2) unsigned NOT NULL DEFAULT '0',
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created` int(10) unsigned NOT NULL DEFAULT '0',
  `next_try` int(10) unsigned NOT NULL DEFAULT '0',
  `last_run` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `xrow_recurring_order_history`;
CREATE TABLE  `xrow_recurring_order_history` (
  `collection_id` int(10) unsigned NOT NULL,
  `order_id` int(10) unsigned DEFAULT NULL,
  `type` int(11) unsigned NOT NULL,
  `date` int(11) unsigned NOT NULL,
  `data_text` text,
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `xrow_recurring_order_item`;
CREATE TABLE  `xrow_recurring_order_item` (
  `contentobject_id` int(10) unsigned NOT NULL DEFAULT '0',
  `created` int(10) unsigned DEFAULT NULL,
  `amount` int(10) unsigned NOT NULL DEFAULT '0',
  `collection_id` int(10) unsigned NOT NULL DEFAULT '0',
  `item_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cycle` int(10) unsigned NOT NULL,
  `next_date` int(10) unsigned DEFAULT NULL,
  `cycle_unit` int(10) unsigned NOT NULL DEFAULT '0',
  `is_subscription` int(10) unsigned NOT NULL DEFAULT '0',
  `next_try` int(10) unsigned DEFAULT NULL,
  `last_success` int(10) unsigned DEFAULT NULL,
  `subscription_handler` varchar(45) DEFAULT NULL,
  `data_text` text NOT NULL,
  `start` int(10) unsigned NOT NULL,
  `end` int(10) unsigned NOT NULL,
  `canceled` int(10) unsigned NOT NULL,
  `status` int(10) unsigned NOT NULL,
  PRIMARY KEY (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `xrow_recurring_order_item_option`;
CREATE TABLE  `xrow_recurring_order_item_option` (
  `item_id` int(10) unsigned NOT NULL DEFAULT '0',
  `variation_id` int(10) unsigned NOT NULL DEFAULT '0',
  `option_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`item_id`,`variation_id`,`option_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `xrowproduct_attribute`;
CREATE TABLE  `xrowproduct_attribute` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `identifier` varchar(255) NOT NULL,
  `active` int(10) unsigned NOT NULL,
  `data_type` varchar(255) DEFAULT NULL,
  `created` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `language_mask` int(10) unsigned NOT NULL,
  `serialized_data` text,
  `initial_language_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `xrowproduct_column`;
CREATE TABLE  `xrowproduct_column` (
  `attribute_id` int(10) unsigned NOT NULL,
  `identifier` varchar(255) DEFAULT NULL,
  `columntype` varchar(255) DEFAULT NULL,
  `defaultvalue` text,
  UNIQUE KEY `att_column` (`attribute_id`,`identifier`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `xrowproduct_data`;
CREATE TABLE  `xrowproduct_data` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `placement` int(10) unsigned NOT NULL,
  `version` int(10) unsigned NOT NULL,
  `template_id` int(10) unsigned NOT NULL,
  `object_id` int(10) unsigned NOT NULL,
  `attribute_id` int(10) unsigned NOT NULL,
  `language_code` varchar(20) NOT NULL,
  `contentclassattribute_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `xrowproduct_price_id`;
CREATE TABLE  `xrowproduct_price_id` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `xrowproduct_price`;
CREATE TABLE  `xrowproduct_price` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `country` varchar(10) NOT NULL,
  `amount` int(10) unsigned NOT NULL,
  `price` float NOT NULL,
  `price_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_xrowproduct_price_item_1` (`price_id`),
  CONSTRAINT `FK_xrowproduct_price_item_1` FOREIGN KEY (`price_id`) REFERENCES `xrowproduct_price_id` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `xrowproduct_template`;
CREATE TABLE  `xrowproduct_template` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `pre_sku` varchar(255) DEFAULT NULL,
  `sortorder` varchar(255) DEFAULT NULL,
  `serialized_data` text,
  `created` int(10) unsigned DEFAULT NULL,
  `modified` int(10) unsigned DEFAULT NULL,
  `user_id` int(10) unsigned DEFAULT NULL,
  `initial_language_id` int(10) unsigned DEFAULT NULL,
  `language_mask` int(10) unsigned DEFAULT NULL,
  `active` int(10) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;