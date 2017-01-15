
CREATE TABLE entities
(
  entity_id VARCHAR(32) NOT NULL,
  entity_label VARCHAR(255) NULL DEFAULT NULL,
  short_label VARCHAR(50)NULL,
  enabled CHAR(1) NOT NULL DEFAULT 'Y',
  adrs_1 VARCHAR(255) NULL,
  adrs_2 VARCHAR(255) NULL,
  adrs_3 VARCHAR(255) NULL,
  zipcode VARCHAR(32) NULL,
  city VARCHAR(255) NULL,
  country VARCHAR(255) NULL,
  email VARCHAR(255) NULL,
  business_id VARCHAR(32) NULL COMMENT 'SIRET, APE, etc.\n',
  parent_entity_id VARCHAR(32) NULL,
  entity_type VARCHAR(64) NULL COMMENT 'Exploitant, customer, partner...\n',
  PRIMARY KEY (entity_id)
)ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE listinstance (
coll_id VARCHAR( 50 ) NOT NULL ,
res_id BIGINT NOT NULL ,
listinstance_type VARCHAR( 50 ) DEFAULT 'DOC' ,
sequence INT( 8 ) NOT NULL ,
item_id VARCHAR( 50) NOT NULL ,
item_type VARCHAR( 255 ) NOT NULL ,
item_mode VARCHAR( 50 ) NOT NULL,
added_by_user VARCHAR( 50 ) NOT NULL,
added_by_entity VARCHAR( 50 ) NOT NULL,
viewed BIGINT
) ENGINE = MYISAM ;

CREATE TABLE listmodels (
coll_id VARCHAR( 50 ) NOT NULL COMMENT 'Collection identifier',
object_id VARCHAR( 50 ) NOT NULL COMMENT 'Object identifier',
object_type VARCHAR( 255 ) NOT NULL COMMENT 'Object type',
sequence BIGINT( 8 ) NOT NULL COMMENT 'Rank of the item in the list',
item_id VARCHAR( 50 ) NOT NULL COMMENT 'Item identifier',
item_type VARCHAR( 255 ) NOT NULL COMMENT 'Item type (user, entity)',
item_mode VARCHAR( 50 ) NOT NULL COMMENT 'Item mode (principal, cc, ci, ..)',
listmodel_type VARCHAR( 50 ) DEFAULT 'DOC' COMMENT 'List type'
) ENGINE = MYISAM ;


CREATE TABLE users_entities (
  user_id varchar(32) collate latin1_general_ci NOT NULL default '',
  entity_id varchar(32) collate latin1_general_ci NOT NULL default '',
  user_role varchar(255) collate latin1_general_ci default NULL,
  primary_entity char(1) collate latin1_general_ci NOT NULL default 'N',
  PRIMARY KEY  (user_id,entity_id)
)ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE groupbasket_redirect
(
  system_id bigint(8) NOT NULL AUTO_INCREMENT,
  group_id varchar(32) NOT NULL,
  basket_id varchar(32) NOT NULL,
   action_id int NOT NULL,
  entity_id varchar(32),
  keyword varchar(255),
  redirect_mode char(32) NOT NULL,
  PRIMARY KEY (system_id)
)ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
