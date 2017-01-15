CREATE TABLE actions(
id bigint( 8 ) NOT NULL AUTO_INCREMENT ,
keyword varchar( 32) NOT NULL DEFAULT '' ,
label_action varchar( 255 ) ,
id_status varchar( 10 ) ,
is_system char(1) NOT NULL DEFAULT 'N',
enabled char(1) NOT NULL DEFAULT 'Y',
action_page varchar( 255 ) ,
history char(1) NOT NULL DEFAULT 'N',
origin varchar( 255 ) NOT NULL DEFAULT 'apps',
create_id char(1) NOT NULL DEFAULT 'N',
PRIMARY KEY ( id )
)ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE docservers (
  docserver_id varchar(32) collate utf8_unicode_ci NOT NULL default '1',
  device_type varchar(32) collate utf8_unicode_ci default NULL,
  device_label varchar(255) collate utf8_unicode_ci default NULL,
  is_readonly char(1) collate utf8_unicode_ci NOT NULL default 'N',
  enabled char(1) collate utf8_unicode_ci NOT NULL default 'Y',
  size_limit int(8) NOT NULL default '0',
  actual_size int(8) NOT NULL default '0',
  path_template varchar(255) collate utf8_unicode_ci NOT NULL,
  ext_docserver_info varchar(255) collate utf8_unicode_ci default NULL,
  chain_before varchar(32) collate utf8_unicode_ci default NULL,
  chain_after varchar(32) collate utf8_unicode_ci default NULL,
  creation_date datetime NOT NULL,
  closing_date datetime default NULL,
  coll_id varchar(32) collate utf8_unicode_ci NOT NULL,
  priority int(8) NOT NULL default '10',
  PRIMARY KEY  (docserver_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS doctypes (
  coll_id varchar(32) collate utf8_unicode_ci NOT NULL default '',
  type_id int(8) NOT NULL auto_increment,
  description varchar(255) collate utf8_unicode_ci NOT NULL default '',
  enabled char(1) collate utf8_unicode_ci NOT NULL default 'Y',
  doctypes_first_level_id int(8) default NULL,
  doctypes_second_level_id int(8) default NULL,
  primary_retention varchar(50) collate utf8_unicode_ci default NULL,
  secondary_retention varchar(50) collate utf8_unicode_ci default NULL,
  PRIMARY KEY  (type_id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci  ;

CREATE TABLE IF NOT EXISTS ext_docserver (
  doc_id varchar(255) collate utf8_unicode_ci NOT NULL,
  path varchar(255) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (doc_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



CREATE TABLE IF NOT EXISTS groupsecurity (
  group_id varchar(32) collate utf8_unicode_ci NOT NULL,
  resgroup_id varchar(32) collate utf8_unicode_ci NOT NULL,
  can_view char(1) collate utf8_unicode_ci NOT NULL,
  can_add char(1) collate utf8_unicode_ci NOT NULL,
  can_delete char(1) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (group_id,resgroup_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS history (
  id int(8) NOT NULL auto_increment,
  table_name varchar(32) collate utf8_unicode_ci default NULL,
  record_id varchar(255) collate utf8_unicode_ci default NULL,
  event_type varchar(32) collate utf8_unicode_ci NOT NULL,
  user_id varchar(50) collate utf8_unicode_ci NOT NULL,
  event_date datetime NOT NULL,
  info text collate utf8_unicode_ci,
  id_module varchar(50) collate utf8_unicode_ci NOT NULL default 'admin',
  remote_ip varchar(32) collate utf8_unicode_ci default NULL,
  PRIMARY KEY  (id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;

CREATE TABLE IF NOT EXISTS history_batch (
  id int(8) NOT NULL auto_increment,
  module_name varchar(32) collate utf8_unicode_ci default NULL,
  batch_id int(8) default NULL,
  event_date datetime NOT NULL,
  total_processed int(8) default NULL,
  total_errors int(8) default NULL,
  info text collate utf8_unicode_ci,
  PRIMARY KEY  (id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;


CREATE TABLE IF NOT EXISTS parameters (
  id varchar(50) collate utf8_unicode_ci NOT NULL,
  param_value_string varchar(50) collate utf8_unicode_ci default NULL,
  param_value_int int(8) default NULL,
  param_value_date datetime default NULL,
  PRIMARY KEY  (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS resgroup_content (
  coll_id varchar(32) collate utf8_unicode_ci NOT NULL,
  res_id int(8) NOT NULL,
  resgroup_id varchar(32) collate utf8_unicode_ci NOT NULL,
  sequence int(8) NOT NULL,
  PRIMARY KEY  (coll_id,res_id,resgroup_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS resgroups (
  resgroup_id varchar(32) collate utf8_unicode_ci NOT NULL,
  resgroup_desc varchar(255) collate utf8_unicode_ci NOT NULL,
  created_by varchar(255) collate utf8_unicode_ci NOT NULL,
  creation_date datetime NOT NULL,
  PRIMARY KEY  (resgroup_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS security (
  security_id int(8) NOT NULL,
  group_id varchar(32) collate utf8_unicode_ci NOT NULL,
  coll_id varchar(32) collate utf8_unicode_ci NOT NULL,
  where_clause varchar(255) collate utf8_unicode_ci default NULL,
  maarch_comment text collate utf8_unicode_ci,
  can_insert char(1) collate utf8_unicode_ci NOT NULL default 'N',
  can_update char(1) collate utf8_unicode_ci NOT NULL default 'N',
  can_delete char(1) collate utf8_unicode_ci NOT NULL default 'N',
  rights_bitmask int(8) NOT NULL,
  mr_start_date date DEFAULT NULL,
  mr_stop_date date DEFAULT NULL,
  where_target varchar(15) DEFAULT 'DOC',
  PRIMARY KEY  (security_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE status
(
  id varchar(10) NOT NULL,
  label_status varchar(50) NOT NULL,
  is_system  char(1) NOT NULL DEFAULT 'Y',
  img_filename varchar(255),
  maarch_module varchar(255) NOT NULL DEFAULT 'apps',
  can_be_searched  char(1) NOT NULL DEFAULT 'Y',
  can_be_modified  char(1) NOT NULL DEFAULT 'Y',
 PRIMARY KEY (id)
)ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS usergroup_content (
  user_id varchar(32) collate utf8_unicode_ci NOT NULL,
  group_id varchar(32) collate utf8_unicode_ci NOT NULL,
  primary_group char(1) collate utf8_unicode_ci NOT NULL,
  role varchar(255) collate utf8_unicode_ci default NULL,
  PRIMARY KEY  (user_id,group_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS usergroups (
  group_id varchar(32) collate utf8_unicode_ci NOT NULL,
  group_desc varchar(255) collate utf8_unicode_ci default NULL,
  administrator char(1) collate utf8_unicode_ci NOT NULL default 'N',
  custom_right1 char(1) collate utf8_unicode_ci NOT NULL default 'N',
  custom_right2 char(1) collate utf8_unicode_ci NOT NULL default 'N',
  custom_right3 char(1) collate utf8_unicode_ci NOT NULL default 'N',
  custom_right4 char(1) collate utf8_unicode_ci NOT NULL default 'N',
  enabled char(1) collate utf8_unicode_ci NOT NULL default 'Y',
  PRIMARY KEY  (group_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS usergroups_services (
  group_id varchar(32) collate utf8_unicode_ci NOT NULL,
  service_id varchar(32) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (group_id,service_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS users (
  user_id varchar(32) collate utf8_unicode_ci NOT NULL,
  password varchar(255) collate utf8_unicode_ci default NULL,
  firstname varchar(255) collate utf8_unicode_ci default NULL,
  lastname varchar(255) collate utf8_unicode_ci default NULL,
  phone varchar(15) collate utf8_unicode_ci default NULL,
  mail varchar(255) collate utf8_unicode_ci default NULL,
  department varchar(50) collate utf8_unicode_ci default NULL,
  custom_t1 varchar(50) collate utf8_unicode_ci default NULL,
  custom_t2 varchar(50) collate utf8_unicode_ci default NULL,
  custom_t3 varchar(50) collate utf8_unicode_ci default NULL,
  cookie_key varchar(255) collate utf8_unicode_ci default NULL,
  cookie_date datetime default NULL,
  enabled char(1) collate utf8_unicode_ci NOT NULL default 'Y',
  change_password char(1) collate utf8_unicode_ci NOT NULL default 'Y',
  delay_number int(8) default NULL,
  status varchar(10) NOT NULL DEFAULT 'OK',
  loginmode varying(50) collate utf8_unicode_ci default NULL,
  PRIMARY KEY  (user_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


