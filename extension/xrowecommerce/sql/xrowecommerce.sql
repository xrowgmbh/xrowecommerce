CREATE TABLE xrowpaymentobject (
  id int(11) NOT NULL auto_increment,
  order_id int(11) NOT NULL default '0',
  payment_string varchar(255) NOT NULL default '',
  status int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) ENGINE=InnoDB;