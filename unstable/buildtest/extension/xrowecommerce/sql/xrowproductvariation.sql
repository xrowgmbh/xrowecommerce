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
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `xrowproduct_price_id`;
CREATE TABLE  `xrowproduct_price_id` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;