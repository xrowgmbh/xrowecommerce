CREATE TABLE xrowpaymentobject (
  id int(11) NOT NULL auto_increment,
  order_id int(11) NOT NULL default '0',
  payment_string varchar(255) NOT NULL default '',
  status int(11) NOT NULL default '0',
  data text NOT NULL,
  PRIMARY KEY  (id)
) ENGINE=InnoDB;


INSERT INTO ezorder_status (id, is_active, name, status_id) VALUES ( null,1,'Canceled',1000);
