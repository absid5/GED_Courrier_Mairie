
CREATE TABLE IF NOT EXISTS contacts (
contact_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
lastname VARCHAR( 255 ) NOT NULL ,
firstname VARCHAR( 255 ) NOT NULL ,
society VARCHAR( 255 ) NOT NULL ,
function VARCHAR( 255 ) NOT NULL ,
address_num VARCHAR( 32 ) NOT NULL ,
address_street VARCHAR( 255 ) NOT NULL ,
address_complement VARCHAR( 255 ) NOT NULL ,
address_town VARCHAR( 255 ) NOT NULL ,
address_postal_code VARCHAR( 255 ) NOT NULL ,
address_country VARCHAR( 255 ) NOT NULL ,
email VARCHAR( 255 ) NOT NULL ,
phone VARCHAR( 20 ) NOT NULL ,
other_data text NOT NULL ,
is_corporate_person CHAR( 1 ) NOT NULL DEFAULT 'Y',
user_id VARCHAR( 32 ) NOT NULL ,
title VARCHAR( 255 ) NOT NULL,
enabled CHAR( 1 ) NOT NULL DEFAULT 'Y'
) ENGINE = MYISAM ;

CREATE TABLE IF NOT EXISTS saved_queries (
query_id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
user_id VARCHAR( 32 ) NULL ,
query_name VARCHAR( 255 ) NOT NULL ,
creation_date DATETIME NOT NULL ,
created_by VARCHAR( 32 ) NOT NULL ,
query_type VARCHAR( 50 ) NOT NULL ,
query_txt TEXT NOT NULL ,
last_modification_date DATETIME NULL
) ENGINE = MYISAM ;

CREATE TABLE IF NOT EXISTS doctypes_first_level (
  doctypes_first_level_id int(8) NOT NULL auto_increment,
  doctypes_first_level_label varchar(255) collate utf8_unicode_ci NOT NULL,
  css_style varchar(255) collate utf8_unicode_ci,
  enabled char(1) collate utf8_unicode_ci NOT NULL default 'Y',
  PRIMARY KEY  (doctypes_first_level_id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS doctypes_second_level (
  doctypes_second_level_id int(8) NOT NULL auto_increment,
  doctypes_second_level_label varchar(255) collate utf8_unicode_ci NOT NULL,
  doctypes_first_level_id int(8) NOT NULL,
  css_style varchar(255) collate utf8_unicode_ci,
  enabled char(1) collate utf8_unicode_ci NOT NULL default 'Y',
  PRIMARY KEY  (doctypes_second_level_id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE IF NOT EXISTS res_x (
  res_id int(8) NOT NULL auto_increment,
  title varchar(255) collate utf8_unicode_ci default NULL,
  subject text collate utf8_unicode_ci,
  description text collate utf8_unicode_ci,
  publisher varchar(255) collate utf8_unicode_ci default NULL,
  contributor varchar(255) collate utf8_unicode_ci default NULL,
  type_id int(8) NOT NULL,
  format varchar(50) collate utf8_unicode_ci NOT NULL,
  typist varchar(50) collate utf8_unicode_ci NOT NULL,
  creation_date datetime NOT NULL,
   fulltext_result varchar(10) DEFAULT NULL,
   ocr_result varchar(10) DEFAULT NULL,
   converter_result varchar(10) DEFAULT NULL,
  author varchar(255) collate utf8_unicode_ci default NULL,
  author_name text collate utf8_unicode_ci,
  identifier varchar(255) collate utf8_unicode_ci default NULL,
  source varchar(255) collate utf8_unicode_ci default NULL,
  doc_language varchar(50) collate utf8_unicode_ci default NULL,
  relation int(8) default NULL,
  coverage varchar(255) collate utf8_unicode_ci default NULL,
  doc_date datetime default NULL,
  docserver_id varchar(32) collate utf8_unicode_ci NOT NULL,
  folders_system_id int(8) default NULL,
  arbox_id varchar(32) collate utf8_unicode_ci default NULL,
  path varchar(255) collate utf8_unicode_ci default NULL,
  filename varchar(255) collate utf8_unicode_ci default NULL,
  offset_doc varchar(255) collate utf8_unicode_ci default NULL,
  logical_adr varchar(255) collate utf8_unicode_ci default NULL,
  fingerprint varchar(255) collate utf8_unicode_ci default NULL,
  filesize int(8) default NULL,
  is_paper char(1) collate utf8_unicode_ci default NULL,
  page_count int(8) default NULL,
  scan_date datetime default NULL,
  scan_user varchar(50) collate utf8_unicode_ci default NULL,
  scan_location varchar(255) collate utf8_unicode_ci default NULL,
  scan_wkstation varchar(255) collate utf8_unicode_ci default NULL,
  scan_batch varchar(50) collate utf8_unicode_ci default NULL,
  burn_batch varchar(50) collate utf8_unicode_ci default NULL,
  scan_postmark varchar(50) collate utf8_unicode_ci default NULL,
  envelop_id int(8) default NULL,
  status varchar(10) collate utf8_unicode_ci default NULL,
  destination varchar(50) collate utf8_unicode_ci default NULL,
  approver varchar(50) collate utf8_unicode_ci default NULL,
  validation_date datetime default NULL,
  work_batch int(8) default NULL,
  origin varchar(50) collate utf8_unicode_ci default NULL,
  is_ingoing char(1) collate utf8_unicode_ci default NULL,
  priority smallint(6) default NULL,
  arbatch_id varchar(32) collate utf8_unicode_ci default NULL,
  policy_id varchar(32) collate utf8_unicode_ci default NULL,
  cycle_id varchar(32) collate utf8_unicode_ci default NULL,
  is_multi_docservers char(1)  NOT NULL DEFAULT 'N',
  custom_t1 text collate utf8_unicode_ci,
  custom_n1 int(8) default NULL,
  custom_f1 decimal(10,0) default NULL,
  custom_d1 datetime default NULL,
  custom_t2 varchar(255) collate utf8_unicode_ci default NULL,
  custom_n2 int(8) default NULL,
  custom_f2 decimal(10,0) default NULL,
  custom_d2 datetime default NULL,
  custom_t3 varchar(255) collate utf8_unicode_ci default NULL,
  custom_n3 int(8) default NULL,
  custom_f3 decimal(10,0) default NULL,
  custom_d3 datetime default NULL,
  custom_t4 varchar(255) collate utf8_unicode_ci default NULL,
  custom_n4 int(8) default NULL,
  custom_f4 decimal(10,0) default NULL,
  custom_d4 datetime default NULL,
  custom_t5 varchar(255) collate utf8_unicode_ci default NULL,
  custom_n5 int(8) default NULL,
  custom_f5 decimal(10,0) default NULL,
  custom_d5 datetime default NULL,
  custom_t6 varchar(255) collate utf8_unicode_ci default NULL,
  custom_d6 datetime default NULL,
  custom_t7 varchar(255) collate utf8_unicode_ci default NULL,
  custom_d7 datetime default NULL,
  custom_t8 varchar(255) collate utf8_unicode_ci default NULL,
  custom_d8 datetime default NULL,
  custom_t9 varchar(255) collate utf8_unicode_ci default NULL,
  custom_d9 datetime default NULL,
  custom_t10 varchar(255) collate utf8_unicode_ci default NULL,
  custom_d10 datetime default NULL,
  custom_t11 varchar(255) collate utf8_unicode_ci default NULL,
  custom_t12 varchar(255) collate utf8_unicode_ci default NULL,
  custom_t13 varchar(255) collate utf8_unicode_ci default NULL,
  custom_t14 varchar(255) collate utf8_unicode_ci default NULL,
  custom_t15 varchar(255) collate utf8_unicode_ci default NULL,
  tablename varchar(32) collate utf8_unicode_ci default 'res_invoices',
  initiator varchar(50) collate utf8_unicode_ci default NULL,
  dest_user varchar(50) collate utf8_unicode_ci default NULL,
  video_batch int(8) default NULL,
  video_time int(11) default NULL,
  video_user varchar(50) collate utf8_unicode_ci default NULL,
  video_date datetime default NULL,
  PRIMARY KEY  (res_id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci  ;

CREATE TABLE IF NOT EXISTS res_letterbox (
  res_id int(8) NOT NULL auto_increment,
  title varchar(255) collate utf8_unicode_ci default NULL,
  subject text collate utf8_unicode_ci,
  description text collate utf8_unicode_ci,
  publisher varchar(255) collate utf8_unicode_ci default NULL,
  contributor varchar(255) collate utf8_unicode_ci default NULL,
  type_id int(8) NOT NULL,
  format varchar(50) collate utf8_unicode_ci NOT NULL,
  typist varchar(50) collate utf8_unicode_ci NOT NULL,
  creation_date datetime NOT NULL,
  fulltext_result varchar(10) DEFAULT NULL,
  ocr_result varchar(10) DEFAULT NULL,
  converter_result varchar(10) DEFAULT NULL,
  author varchar(255) collate utf8_unicode_ci default NULL,
  author_name text collate utf8_unicode_ci,
  identifier varchar(255) collate utf8_unicode_ci default NULL,
  source varchar(255) collate utf8_unicode_ci default NULL,
  doc_language varchar(50) collate utf8_unicode_ci default NULL,
  relation int(8) default NULL,
  coverage varchar(255) collate utf8_unicode_ci default NULL,
  doc_date datetime default NULL,
  docserver_id varchar(32) collate utf8_unicode_ci NOT NULL,
  folders_system_id int(8) default NULL,
  arbox_id varchar(32) collate utf8_unicode_ci default NULL,
  path varchar(255) collate utf8_unicode_ci default NULL,
  filename varchar(255) collate utf8_unicode_ci default NULL,
  offset_doc varchar(255) collate utf8_unicode_ci default NULL,
  logical_adr varchar(255) collate utf8_unicode_ci default NULL,
  fingerprint varchar(255) collate utf8_unicode_ci default NULL,
  filesize int(8) default NULL,
  is_paper char(1) collate utf8_unicode_ci default NULL,
  page_count int(8) default NULL,
  scan_date datetime default NULL,
  scan_user varchar(50) collate utf8_unicode_ci default NULL,
  scan_location varchar(255) collate utf8_unicode_ci default NULL,
  scan_wkstation varchar(255) collate utf8_unicode_ci default NULL,
  scan_batch varchar(50) collate utf8_unicode_ci default NULL,
  burn_batch varchar(50) collate utf8_unicode_ci default NULL,
  scan_postmark varchar(50) collate utf8_unicode_ci default NULL,
  envelop_id int(8) default NULL,
  status varchar(10) collate utf8_unicode_ci default NULL,
  destination varchar(50) collate utf8_unicode_ci default NULL,
  approver varchar(50) collate utf8_unicode_ci default NULL,
  validation_date datetime default NULL,
  work_batch int(8) default NULL,
  origin varchar(50) collate utf8_unicode_ci default NULL,
  is_ingoing char(1) collate utf8_unicode_ci default NULL,
  priority smallint(6) default NULL,
  arbatch_id varchar(32) collate utf8_unicode_ci default NULL,
  custom_t1 text collate utf8_unicode_ci,
  custom_n1 int(8) default NULL,
  custom_f1 decimal(10,0) default NULL,
  custom_d1 datetime default NULL,
  custom_t2 varchar(255) collate utf8_unicode_ci default NULL,
  custom_n2 int(8) default NULL,
  custom_f2 decimal(10,0) default NULL,
  custom_d2 datetime default NULL,
  custom_t3 varchar(255) collate utf8_unicode_ci default NULL,
  custom_n3 int(8) default NULL,
  custom_f3 decimal(10,0) default NULL,
  custom_d3 datetime default NULL,
  custom_t4 varchar(255) collate utf8_unicode_ci default NULL,
  custom_n4 int(8) default NULL,
  custom_f4 decimal(10,0) default NULL,
  custom_d4 datetime default NULL,
  custom_t5 varchar(255) collate utf8_unicode_ci default NULL,
  custom_n5 int(8) default NULL,
  custom_f5 decimal(10,0) default NULL,
  custom_d5 datetime default NULL,
  custom_t6 varchar(255) collate utf8_unicode_ci default NULL,
  custom_d6 datetime default NULL,
  custom_t7 varchar(255) collate utf8_unicode_ci default NULL,
  custom_d7 datetime default NULL,
  custom_t8 varchar(255) collate utf8_unicode_ci default NULL,
  custom_d8 datetime default NULL,
  custom_t9 varchar(255) collate utf8_unicode_ci default NULL,
  custom_d9 datetime default NULL,
  custom_t10 varchar(255) collate utf8_unicode_ci default NULL,
  custom_d10 datetime default NULL,
  custom_t11 varchar(255) collate utf8_unicode_ci default NULL,
  custom_t12 varchar(255) collate utf8_unicode_ci default NULL,
  custom_t13 varchar(255) collate utf8_unicode_ci default NULL,
  custom_t14 varchar(255) collate utf8_unicode_ci default NULL,
  custom_t15 varchar(255) collate utf8_unicode_ci default NULL,
  tablename varchar(32) collate utf8_unicode_ci default 'res_letterbox',
  initiator varchar(50) collate utf8_unicode_ci default NULL,
  dest_user varchar(50) collate utf8_unicode_ci default NULL,
  video_batch int(8) default NULL,
  video_time int(11) default NULL,
  video_user varchar(50) collate utf8_unicode_ci default NULL,
  video_date datetime default NULL,
  PRIMARY KEY  (res_id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci  ;

CREATE TABLE IF NOT EXISTS adr_letterbox(
  res_id bigint(8) NOT NULL,
  docserver_id varchar(32) collate utf8_unicode_ci NOT NULL,
  path varchar(255) collate utf8_unicode_ci default NULL,
  filename varchar(255) collate utf8_unicode_ci default NULL,
  offset_doc varchar(255) collate utf8_unicode_ci default NULL,
  logical_adr varchar(255) collate utf8_unicode_ci default NULL,
  fingerprint varchar(255) collate utf8_unicode_ci default NULL,
  filesize bigint(8) default NULL,
  policy_id varchar(32) collate utf8_unicode_ci NOT NULL,
  cycle_id varchar(32) collate utf8_unicode_ci NOT NULL,
  cycle_step_id varchar(32) collate utf8_unicode_ci NOT NULL,
  adr_priority int(8) NOT NULL,
  PRIMARY KEY  (res_id, docserver_id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci  ;
  

CREATE TABLE IF NOT EXISTS mlb_coll_ext (
  res_id bigint(8) NOT NULL,
  category_id varchar(50) collate utf8_unicode_ci NOT NULL,
  exp_contact_id int(11) default NULL,
  exp_user_id varchar(52) default NULL,
  dest_contact_id int(11) default NULL,
  dest_user_id varchar(52) default NULL,
  nature_id varchar(50) default NULL,
  alt_identifier varchar(255),
  admission_date datetime NOT NULL,
  answer_type_bitmask varchar(7)  default '000000',
  other_answer_desc varchar(255) default NULL,
  process_limit_date datetime default NULL,
   process_notes text default NULL,
  closing_date datetime default NULL,
  alarm1_date datetime default NULL,
  alarm2_date datetime default NULL,
  flag_notif char(1)  default 'N' COMMENT 'N',
  flag_alarm1 char(1)  default 'N' NOT NULL COMMENT 'N',
  flag_alarm2 char(1) default 'N'  NOT NULL COMMENT 'N'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE mlb_doctype_ext (
    type_id INT NOT NULL,
    process_delay INT DEFAULT 21 NOT NULL,
    delay1 INT DEFAULT 14 NOT NULL,
    delay2 INT DEFAULT 1 NOT NULL
);
CREATE OR REPLACE VIEW res_view AS
 SELECT r.tablename, r.res_id, r.title, r.page_count, r.identifier, r.doc_date, r.type_id,
 d.description AS type_label, d.doctypes_first_level_id, dfl.doctypes_first_level_label, d.doctypes_second_level_id,
 dsl.doctypes_second_level_label, r.format, r.typist, r.creation_date, r.relation, r.docserver_id,
 r.folders_system_id, f.folder_id, r.path, r.filename, r.fingerprint, r.offset_doc, r.filesize, r.status,
 r.work_batch, r.arbatch_id, r.arbox_id,  r.is_paper, r.scan_date, r.scan_user,r.scan_location,r.scan_wkstation,
 r.scan_batch,r.doc_language,r.description,r.source,r.initiator,r.destination,r.dest_user,
 r.custom_t1 AS doc_custom_t1, r.custom_t2 AS doc_custom_t2, r.custom_t3 AS doc_custom_t3,
 r.custom_t4 AS doc_custom_t4, r.custom_t5 AS doc_custom_t5, r.custom_t6 AS doc_custom_t6,
 r.custom_t7 AS doc_custom_t7, r.custom_t8 AS doc_custom_t8, r.custom_t9 AS doc_custom_t9,
 r.custom_t10 AS doc_custom_t10, r.custom_t11 AS doc_custom_t11, r.custom_t12 AS doc_custom_t12,
 r.custom_t13 AS doc_custom_t13, r.custom_t14 AS doc_custom_t14, r.custom_t15 AS doc_custom_t15,
 r.custom_d1 AS doc_custom_d1, r.custom_d2 AS doc_custom_d2, r.custom_d3 AS doc_custom_d3,
 r.custom_d4 AS doc_custom_d4, r.custom_d5 AS doc_custom_d5, r.custom_d6 AS doc_custom_d6,
 r.custom_d7 AS doc_custom_d7, r.custom_d8 AS doc_custom_d8, r.custom_d9 AS doc_custom_d9,
 r.custom_d10 AS doc_custom_d10, r.custom_n1 AS doc_custom_n1, r.custom_n2 AS doc_custom_n2,
 r.custom_n3 AS doc_custom_n3, r.custom_n4 AS doc_custom_n4, r.custom_n5 AS doc_custom_n5,
 r.custom_f1 AS doc_custom_f1, r.custom_f2 AS doc_custom_f2, r.custom_f3 AS doc_custom_f3,
 r.custom_f4 AS doc_custom_f4, r.custom_f5 AS doc_custom_f5,
 f.foldertype_id, ft.foldertype_label, f.custom_t1 AS fold_custom_t1, f.custom_t2 AS fold_custom_t2,
 f.custom_t3 AS fold_custom_t3, f.custom_t4 AS fold_custom_t4, f.custom_t5 AS fold_custom_t5,
 f.custom_t6 AS fold_custom_t6, f.custom_t7 AS fold_custom_t7, f.custom_t8 AS fold_custom_t8,
 f.custom_t9 AS fold_custom_t9, f.custom_t10 AS fold_custom_t10, f.custom_t11 AS fold_custom_t11,
 f.custom_t12 AS fold_custom_t12, f.custom_t13 AS fold_custom_t13, f.custom_t14 AS fold_custom_t14,
 f.custom_t15 AS fold_custom_t15, f.custom_d1 AS fold_custom_d1, f.custom_d2 AS fold_custom_d2,
 f.custom_d3 AS fold_custom_d3, f.custom_d4 AS fold_custom_d4, f.custom_d5 AS fold_custom_d5,
 f.custom_d6 AS fold_custom_d6, f.custom_d7 AS fold_custom_d7, f.custom_d8 AS fold_custom_d8,
 f.custom_d9 AS fold_custom_d9, f.custom_d10 AS fold_custom_d10, f.custom_n1 AS fold_custom_n1,
 f.custom_n2 AS fold_custom_n2, f.custom_n3 AS fold_custom_n3, f.custom_n4 AS fold_custom_n4,
 f.custom_n5 AS fold_custom_n5, f.custom_f1 AS fold_custom_f1, f.custom_f2 AS fold_custom_f2,
 f.custom_f3 AS fold_custom_f3, f.custom_f4 AS fold_custom_f4, f.custom_f5 AS fold_custom_f5,
 f.is_complete AS fold_complete, f.status AS fold_status, f.folder_name, f.creation_date as fold_creation_date
   FROM  folders f, doctypes d, foldertypes ft, doctypes_first_level dfl, doctypes_second_level dsl, res_x r
  WHERE r.folders_system_id = f.folders_system_id AND r.type_id = d.type_id AND f.foldertype_id = ft.foldertype_id
  AND f.status <> 'DEL' AND d.doctypes_first_level_id = dfl.doctypes_first_level_id AND
  d.doctypes_second_level_id = dsl.doctypes_second_level_id ;


CREATE OR REPLACE VIEW res_view_letterbox AS
 SELECT r.tablename, r.res_id, r.type_id, d.description AS type_label, d.doctypes_first_level_id, dfl.doctypes_first_level_label, d.doctypes_second_level_id, dsl.doctypes_second_level_label, r.format, r.typist, r.creation_date, r.relation, r.docserver_id, r.folders_system_id, f.folder_id, r.path, r.filename, r.fingerprint, r.filesize, r.status, r.work_batch, r.arbatch_id, r.arbox_id, r.page_count, r.is_paper, r.doc_date, r.scan_date, r.scan_user, r.scan_location, r.scan_wkstation, r.scan_batch, r.doc_language, r.description, r.source, r.author, r.custom_t1 AS doc_custom_t1, r.custom_t2 AS doc_custom_t2, r.custom_t3 AS doc_custom_t3, r.custom_t4 AS doc_custom_t4, r.custom_t5 AS doc_custom_t5, r.custom_t6 AS doc_custom_t6, r.custom_t7 AS doc_custom_t7, r.custom_t8 AS doc_custom_t8, r.custom_t9 AS doc_custom_t9, r.custom_t10 AS doc_custom_t10, r.custom_t11 AS doc_custom_t11, r.custom_t12 AS doc_custom_t12, r.custom_t13 AS doc_custom_t13, r.custom_t14 AS doc_custom_t14, r.custom_t15 AS doc_custom_t15, r.custom_d1 AS doc_custom_d1, r.custom_d2 AS doc_custom_d2, r.custom_d3 AS doc_custom_d3, r.custom_d4 AS doc_custom_d4, r.custom_d5 AS doc_custom_d5, r.custom_d6 AS doc_custom_d6, r.custom_d7 AS doc_custom_d7, r.custom_d8 AS doc_custom_d8, r.custom_d9 AS doc_custom_d9, r.custom_d10 AS doc_custom_d10, r.custom_n1 AS doc_custom_n1, r.custom_n2 AS doc_custom_n2, r.custom_n3 AS doc_custom_n3, r.custom_n4 AS doc_custom_n4, r.custom_n5 AS doc_custom_n5, r.custom_f1 AS doc_custom_f1, r.custom_f2 AS doc_custom_f2, r.custom_f3 AS doc_custom_f3, r.custom_f4 AS doc_custom_f4, r.custom_f5 AS doc_custom_f5, f.foldertype_id, ft.foldertype_label, f.custom_t1 AS fold_custom_t1, f.custom_t2 AS fold_custom_t2, f.custom_t3 AS fold_custom_t3, f.custom_t4 AS fold_custom_t4, f.custom_t5 AS fold_custom_t5, f.custom_t6 AS fold_custom_t6, f.custom_t7 AS fold_custom_t7, f.custom_t8 AS fold_custom_t8, f.custom_t9 AS fold_custom_t9, f.custom_t10 AS fold_custom_t10, f.custom_t11 AS fold_custom_t11, f.custom_t12 AS fold_custom_t12, f.custom_t13 AS fold_custom_t13, f.custom_t14 AS fold_custom_t14, f.custom_t15 AS fold_custom_t15, f.custom_d1 AS fold_custom_d1, f.custom_d2 AS fold_custom_d2, f.custom_d3 AS fold_custom_d3, f.custom_d4 AS fold_custom_d4, f.custom_d5 AS fold_custom_d5, f.custom_d6 AS fold_custom_d6, f.custom_d7 AS fold_custom_d7, f.custom_d8 AS fold_custom_d8, f.custom_d9 AS fold_custom_d9, f.custom_d10 AS fold_custom_d10, f.custom_n1 AS fold_custom_n1, f.custom_n2 AS fold_custom_n2, f.custom_n3 AS fold_custom_n3, f.custom_n4 AS fold_custom_n4, f.custom_n5 AS fold_custom_n5, f.custom_f1 AS fold_custom_f1, f.custom_f2 AS fold_custom_f2, f.custom_f3 AS fold_custom_f3, f.custom_f4 AS fold_custom_f4, f.custom_f5 AS fold_custom_f5, f.is_complete AS fold_complete, f.status AS fold_status, f.subject AS fold_subject, f.parent_id AS fold_parent_id, f.folder_level, f.folder_name, f.creation_date AS fold_creation_date, r.initiator, r.destination, r.dest_user, mlb.category_id, mlb.exp_contact_id, mlb.exp_user_id, mlb.dest_user_id, mlb.dest_contact_id, mlb.nature_id, mlb.alt_identifier, mlb.admission_date, mlb.answer_type_bitmask, mlb.other_answer_desc, mlb.process_limit_date, mlb.closing_date, mlb.alarm1_date, mlb.alarm2_date, mlb.flag_notif, mlb.flag_alarm1, mlb.flag_alarm2, r.video_user, r.video_time, r.video_batch, r.subject, r.identifier, r.title, r.priority, mlb.process_notes
 FROM doctypes d, doctypes_first_level dfl, doctypes_second_level dsl, ar_batch a
   RIGHT JOIN res_letterbox r ON r.arbatch_id = a.arbatch_id
   LEFT JOIN folders f ON r.folders_system_id = f.folders_system_id
   LEFT JOIN mlb_coll_ext mlb ON mlb.res_id = r.res_id
   LEFT JOIN foldertypes ft ON f.foldertype_id = ft.foldertype_id AND f.status <> 'DEL'
  WHERE r.type_id = d.type_id AND d.doctypes_first_level_id = dfl.doctypes_first_level_id AND d.doctypes_second_level_id = dsl.doctypes_second_level_id;


CREATE TABLE doctypes_indexes (
type_id BIGINT NOT NULL ,
coll_id VARCHAR( 32 ) NOT NULL ,
field_name VARCHAR( 255 ) NOT NULL ,
mandatory CHAR( 1 ) NOT NULL DEFAULT 'N'
) ENGINE = MYISAM ;

CREATE OR REPLACE VIEW res_view_apa AS
 select * from res_apa;

-- Resource view used to fill af_target
CREATE OR REPLACE VIEW af_view_year_view AS
 SELECT r.custom_t3 AS level1, date_format(r.doc_date, '%Y') AS level2, r.custom_t4 AS level3
        r.res_id, r.creation_date, r.status -- for where clause
   FROM  res_x r
   WHERE NOT (EXISTS ( SELECT t.level1, t.level2, t.level3
           FROM af_target t
          WHERE r.custom_t3 = t.level1 AND date_part( 'year', r.doc_date) = t.level2 AND r.custom_t4 = t.level3));
   
CREATE OR REPLACE VIEW af_view_customer_view AS
 SELECT substring(r.custom_t4, 1, 1) AS level1,  r.custom_t4 AS level2, date_format(r.doc_date, '%Y') AS level3
        r.res_id, r.creation_date, r.status -- for where clause
   FROM  res_x r
   WHERE status <> 'DEL' and date_part( 'year', doc_date) is not null ;
    AND NOT (EXISTS ( SELECT t.level1, t.level2, t.level3
           FROM af_target t
          WHERE substring(r.custom_t4, 1, 1) = t.level1 AND r.custom_t4 = t.level2 AND date_part( 'year', r.doc_date) = t.level3));

-- View used to display trees
CREATE OR REPLACE VIEW af_view_year_target_view AS
 SELECT af.level1, af.level1_id, af.level1 as level1_label, af.level2, af.level2_id, af.level2 as level2_label, af.level3, af.level3_id, af.level3 as level3_label
   FROM af_view_year_target af;

CREATE OR REPLACE VIEW af_view_customer_target_view AS
 SELECT af.level1, af.level1_id, af.level1 as level1_label, af.level2, af.level2_id, af.level2 as level2_label, af.level3, af.level3_id, af.level3 as level3_label
   FROM af_view_customer_target af ;
