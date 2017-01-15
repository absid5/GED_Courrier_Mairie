
CREATE TABLE IF NOT EXISTS baskets (
  coll_id varchar(32) collate utf8_unicode_ci NOT NULL,
  basket_id varchar(32) collate utf8_unicode_ci NOT NULL,
  basket_name varchar(255) collate utf8_unicode_ci NOT NULL,
  basket_desc varchar(255) collate utf8_unicode_ci NOT NULL,
  basket_clause text collate utf8_unicode_ci NOT NULL,
  is_generic varchar(6) collate utf8_unicode_ci NOT NULL default 'N',
  enabled char(1) collate utf8_unicode_ci NOT NULL default 'Y',
  PRIMARY KEY  (coll_id,basket_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE IF NOT EXISTS groupbasket (
  group_id varchar(32) collate utf8_unicode_ci NOT NULL,
  basket_id varchar(32) collate utf8_unicode_ci NOT NULL,
  sequence int(4) NOT NULL default '0',
  redirect_basketlist varchar(255) collate utf8_unicode_ci default NULL,
  redirect_grouplist varchar(255) collate utf8_unicode_ci default NULL,
  can_redirect char(1) collate utf8_unicode_ci NOT NULL default 'Y',
  can_delete char(1) collate utf8_unicode_ci NOT NULL default 'N',
  can_insert char(1) collate utf8_unicode_ci NOT NULL default 'N',
  result_page varchar(255) collate utf8_unicode_ci default 'show_list1.php',
  PRIMARY KEY  (group_id,basket_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE actions_groupbaskets(
id_action bigint( 8 ) NOT NULL ,
where_clause text,
group_id varchar( 32 ) NOT NULL ,
basket_id varchar( 32 ) NOT NULL ,
used_in_basketlist char( 1 ) NOT NULL DEFAULT 'Y',
used_in_action_page char( 1 ) NOT NULL DEFAULT 'Y',
default_action_list char( 1 ) NOT NULL DEFAULT 'N',
PRIMARY KEY ( id_action, group_id, basket_id )
)ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE user_abs (
  system_id bigint(20) NOT NULL AUTO_INCREMENT ,
  user_abs varchar(32) collate utf8_unicode_ci NOT NULL,
  new_user varchar(32) collate utf8_unicode_ci default NULL,
  basket_id varchar(255) collate utf8_unicode_ci NOT NULL,
  basket_owner varchar(255) collate utf8_unicode_ci default NULL,
  is_virtual char(1) collate utf8_unicode_ci NOT NULL default 'N',
  PRIMARY KEY  (system_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
