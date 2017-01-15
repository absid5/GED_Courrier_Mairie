CREATE TABLE cases (
  case_id int(11) NOT NULL auto_increment,
  case_label varchar(255) NOT NULL default '',
  case_description varchar(255) default NULL,
  case_type varchar(32) default NULL,
  case_closing_date timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  case_last_update_date timestamp NOT NULL default '0000-00-00 00:00:00',
  case_creation_date timestamp NOT NULL default '0000-00-00 00:00:00',
  case_typist varchar(32) NOT NULL default '',
  case_parent int(11) default NULL,
  case_custom_t1 varchar(255) default NULL,
  case_custom_t2 varchar(255) default NULL,
  case_custom_t3 varchar(255) default NULL,
  case_custom_t4 varchar(255) default NULL,
  PRIMARY KEY  (case_id)
);

CREATE TABLE cases_res (
  case_id int(11) NOT NULL,
  res_id int(11) NOT NULL,
  PRIMARY KEY  (case_id,res_id)
);


