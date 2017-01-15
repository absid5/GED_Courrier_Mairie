
CREATE TABLE IF NOT EXISTS templates (
  id bigint(8) NOT NULL auto_increment,
  label varchar(50) collate utf8_unicode_ci default NULL,
  creation_date datetime default NULL,
  template_comment varchar(255) collate utf8_unicode_ci default NULL,
  content text collate utf8_unicode_ci,
  PRIMARY KEY  (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;

CREATE TABLE templates_doctype_ext
(
  template_id bigint(8) default NULL,
  type_id int(8) NOT NULL,
  is_generated character(1) NOT NULL DEFAULT 'N'
)ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;


CREATE TABLE templates_association
(
  template_id bigint(8) NOT NULL,
  what varchar(255) NOT NULL,
  value_field varchar(255) NOT NULL,
  system_id bigint(8) NOT NULL auto_increment,
  maarch_module varchar(255) NOT NULL DEFAULT 'apps',
 PRIMARY KEY (system_id)
)ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;

