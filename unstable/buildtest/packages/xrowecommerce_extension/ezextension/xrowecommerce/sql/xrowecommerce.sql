DROP TABLE IF EXISTS `xrowpaymentobject`;
CREATE TABLE xrowpaymentobject (
  id int(11) NOT NULL auto_increment,
  order_id int(11) NOT NULL default '0',
  payment_string varchar(255) NOT NULL default '',
  status int(11) NOT NULL default '0',
  data text NOT NULL,
  PRIMARY KEY  (id)
) ENGINE=InnoDB;

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
