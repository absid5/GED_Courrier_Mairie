CREATE SEQUENCE contact_id_seq
  INCREMENT 1
  MINVALUE 14
  MAXVALUE 9223372036854775807
  START 100
  CACHE 1;

CREATE TABLE contacts (
contact_id bigint NOT NULL DEFAULT nextval('contact_id_seq'::regclass),
lastname character varying( 255 )  ,
firstname character varying( 255 )  ,
society character varying( 255 )  ,
function character varying( 255 ),
address_num character varying( 32 )  ,
address_street character varying( 255 )  ,
address_complement character varying( 255 )  ,
address_town character varying( 255 )  ,
address_postal_code character varying( 255 ) ,
address_country character varying( 255 )  ,
email character varying( 255 )  ,
phone character varying( 20 )  ,
other_data text  ,
is_corporate_person character( 1 ) NOT NULL DEFAULT 'Y'::bpchar,
user_id character varying( 32 )  ,
title character varying( 255 ) ,
enabled character( 1 ) NOT NULL DEFAULT 'Y'::bpchar,
CONSTRAINT contacts_pkey PRIMARY KEY  (contact_id)
) WITH (OIDS=FALSE);

CREATE SEQUENCE query_id_seq
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 10
  CACHE 1;


CREATE TABLE saved_queries (
  query_id bigint NOT NULL DEFAULT nextval('query_id_seq'::regclass),
  user_id character varying(128)  default NULL,
  query_name character varying(255) NOT NULL,
  creation_date timestamp without time zone NOT NULL,
  created_by character varying(128)  NOT NULL,
  query_type character varying(50) NOT NULL,
  query_txt text  NOT NULL,
  last_modification_date timestamp without time zone,
  CONSTRAINT saved_queries_pkey PRIMARY KEY  (query_id)
) WITH (OIDS=FALSE);

CREATE SEQUENCE doctypes_first_level_id_seq
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 10
  CACHE 1;

CREATE TABLE doctypes_first_level
(
  doctypes_first_level_id integer NOT NULL DEFAULT nextval('doctypes_first_level_id_seq'::regclass),
  doctypes_first_level_label character varying(255) NOT NULL,
  css_style character varying(255),
  enabled character(1) NOT NULL DEFAULT 'Y'::bpchar,
  CONSTRAINT doctypes_first_level_pkey PRIMARY KEY (doctypes_first_level_id)
)
WITH (OIDS=FALSE);

CREATE SEQUENCE doctypes_second_level_id_seq
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 50
  CACHE 1;

CREATE TABLE doctypes_second_level
(
  doctypes_second_level_id integer NOT NULL DEFAULT nextval('doctypes_second_level_id_seq'::regclass),
  doctypes_second_level_label character varying(255) NOT NULL,
  doctypes_first_level_id integer NOT NULL,
  css_style character varying(255),
  enabled character(1) NOT NULL DEFAULT 'Y'::bpchar,
  CONSTRAINT doctypes_second_level_pkey PRIMARY KEY (doctypes_second_level_id)
)
WITH (OIDS=FALSE);

CREATE SEQUENCE res_id_seq
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 100
  CACHE 1;

CREATE TABLE res_x
(
  res_id bigint NOT NULL DEFAULT nextval('res_id_seq'::regclass),
  title character varying(255) DEFAULT NULL::character varying,
  subject text,
  description text,
  publisher character varying(255) DEFAULT NULL::character varying,
  contributor character varying(255) DEFAULT NULL::character varying,
  type_id bigint NOT NULL,
  format character varying(50) NOT NULL,
  typist character varying(128) NOT NULL,
  creation_date timestamp without time zone NOT NULL,
  fulltext_result character varying(10) DEFAULT NULL,
  ocr_result character varying(10) DEFAULT NULL,
  converter_result character varying(10) DEFAULT NULL,
  author character varying(255) DEFAULT NULL::character varying,
  author_name text,
  identifier character varying(255) DEFAULT NULL::character varying,
  source character varying(255) DEFAULT NULL::character varying,
  doc_language character varying(50) DEFAULT NULL::character varying,
  relation bigint,
  coverage character varying(255) DEFAULT NULL::character varying,
  doc_date timestamp without time zone,
  docserver_id character varying(32) NOT NULL,
  folders_system_id bigint,
  arbox_id character varying(32) DEFAULT NULL::character varying,
  path character varying(255) DEFAULT NULL::character varying,
  filename character varying(255) DEFAULT NULL::character varying,
  offset_doc character varying(255) DEFAULT NULL::character varying,
  logical_adr character varying(255) DEFAULT NULL::character varying,
  fingerprint character varying(255) DEFAULT NULL::character varying,
  filesize bigint,
  is_paper character(1) DEFAULT NULL::bpchar,
  page_count integer,
  scan_date timestamp without time zone,
  scan_user character varying(50) DEFAULT NULL::character varying,
  scan_location character varying(255) DEFAULT NULL::character varying,
  scan_wkstation character varying(255) DEFAULT NULL::character varying,
  scan_batch character varying(50) DEFAULT NULL::character varying,
  burn_batch character varying(50) DEFAULT NULL::character varying,
  scan_postmark character varying(50) DEFAULT NULL::character varying,
  envelop_id bigint,
  status character varying(10) NOT NULL,
  destination character varying(50) DEFAULT NULL::character varying,
  approver character varying(50) DEFAULT NULL::character varying,
  validation_date timestamp without time zone,
  work_batch bigint,
  origin character varying(50) DEFAULT NULL::character varying,
  is_ingoing character(1) DEFAULT NULL::bpchar,
  priority smallint,
  arbatch_id bigint DEFAULT NULL,
  policy_id character varying(32) DEFAULT NULL::character varying,
  cycle_id character varying(32) DEFAULT NULL::character varying,
  cycle_date timestamp without time zone,
  is_multi_docservers character(1) NOT NULL DEFAULT 'N'::bpchar,
  is_frozen character(1) NOT NULL DEFAULT 'N'::bpchar,
  custom_t1 text,
  custom_n1 bigint,
  custom_f1 numeric,
  custom_d1 timestamp without time zone,
  custom_t2 character varying(255) DEFAULT NULL::character varying,
  custom_n2 bigint,
  custom_f2 numeric,
  custom_d2 timestamp without time zone,
  custom_t3 character varying(255) DEFAULT NULL::character varying,
  custom_n3 bigint,
  custom_f3 numeric,
  custom_d3 timestamp without time zone,
  custom_t4 character varying(255) DEFAULT NULL::character varying,
  custom_n4 bigint,
  custom_f4 numeric,
  custom_d4 timestamp without time zone,
  custom_t5 character varying(255) DEFAULT NULL::character varying,
  custom_n5 bigint,
  custom_f5 numeric,
  custom_d5 timestamp without time zone,
  custom_t6 character varying(255) DEFAULT NULL::character varying,
  custom_d6 timestamp without time zone,
  custom_t7 character varying(255) DEFAULT NULL::character varying,
  custom_d7 timestamp without time zone,
  custom_t8 character varying(255) DEFAULT NULL::character varying,
  custom_d8 timestamp without time zone,
  custom_t9 character varying(255) DEFAULT NULL::character varying,
  custom_d9 timestamp without time zone,
  custom_t10 character varying(255) DEFAULT NULL::character varying,
  custom_d10 timestamp without time zone,
  custom_t11 character varying(255) DEFAULT NULL::character varying,
  custom_t12 character varying(255) DEFAULT NULL::character varying,
  custom_t13 character varying(255) DEFAULT NULL::character varying,
  custom_t14 character varying(255) DEFAULT NULL::character varying,
  custom_t15 character varying(255) DEFAULT NULL::character varying,
  tablename character varying(32) DEFAULT 'res_x'::character varying,
  initiator character varying(50) DEFAULT NULL::character varying,
  dest_user character varying(128) DEFAULT NULL::character varying,
  video_batch integer DEFAULT NULL,
  video_time integer DEFAULT NULL,
  video_user character varying(128)  DEFAULT NULL,
  video_date timestamp without time zone,
  esign_proof_id character varying(255),
  esign_proof_content text,
  esign_content text,
  esign_date timestamp without time zone,
  CONSTRAINT res_x_pkey PRIMARY KEY  (res_id)
)
WITH (OIDS=FALSE);

CREATE TABLE adr_x
(
  res_id bigint NOT NULL,
  docserver_id character varying(32) NOT NULL,
  path character varying(255) DEFAULT NULL::character varying,
  filename character varying(255) DEFAULT NULL::character varying,
  offset_doc character varying(255) DEFAULT NULL::character varying,
  fingerprint character varying(255) DEFAULT NULL::character varying,
  adr_priority integer NOT NULL,
  CONSTRAINT adr_x_pkey PRIMARY KEY (res_id, docserver_id)
)
WITH (OIDS=FALSE);

CREATE SEQUENCE res_id_mlb_seq
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 100
  CACHE 1;

CREATE TABLE res_letterbox
(
  res_id bigint NOT NULL DEFAULT nextval('res_id_mlb_seq'::regclass),
  title character varying(255) DEFAULT NULL::character varying,
  subject text,
  description text,
  publisher character varying(255) DEFAULT NULL::character varying,
  contributor character varying(255) DEFAULT NULL::character varying,
  type_id bigint NOT NULL,
  format character varying(50) NOT NULL,
  typist character varying(128) NOT NULL,
  creation_date timestamp without time zone NOT NULL,
  fulltext_result character varying(10) DEFAULT NULL,
  ocr_result character varying(10) DEFAULT NULL,
  converter_result character varying(10) DEFAULT NULL,
  author character varying(255) DEFAULT NULL::character varying,
  author_name text,
  identifier character varying(255) DEFAULT NULL::character varying,
  source character varying(255) DEFAULT NULL::character varying,
  doc_language character varying(50) DEFAULT NULL::character varying,
  relation bigint,
  coverage character varying(255) DEFAULT NULL::character varying,
  doc_date timestamp without time zone,
  docserver_id character varying(32) NOT NULL,
  folders_system_id bigint,
  arbox_id character varying(32) DEFAULT NULL::character varying,
  path character varying(255) DEFAULT NULL::character varying,
  filename character varying(255) DEFAULT NULL::character varying,
  offset_doc character varying(255) DEFAULT NULL::character varying,
  logical_adr character varying(255) DEFAULT NULL::character varying,
  fingerprint character varying(255) DEFAULT NULL::character varying,
  filesize bigint,
  is_paper character(1) DEFAULT NULL::bpchar,
  page_count integer,
  scan_date timestamp without time zone,
  scan_user character varying(50) DEFAULT NULL::character varying,
  scan_location character varying(255) DEFAULT NULL::character varying,
  scan_wkstation character varying(255) DEFAULT NULL::character varying,
  scan_batch character varying(50) DEFAULT NULL::character varying,
  burn_batch character varying(50) DEFAULT NULL::character varying,
  scan_postmark character varying(50) DEFAULT NULL::character varying,
  envelop_id bigint,
  status character varying(10) NOT NULL,
  destination character varying(50) DEFAULT NULL::character varying,
  approver character varying(50) DEFAULT NULL::character varying,
  validation_date timestamp without time zone,
  work_batch bigint,
  origin character varying(50) DEFAULT NULL::character varying,
  is_ingoing character(1) DEFAULT NULL::bpchar,
  priority smallint,
  arbatch_id bigint DEFAULT NULL,
  policy_id character varying(32) DEFAULT NULL::character varying,
  cycle_id character varying(32) DEFAULT NULL::character varying,
  cycle_date timestamp without time zone,
  is_multi_docservers character(1) NOT NULL DEFAULT 'N'::bpchar,
  is_frozen character(1) NOT NULL DEFAULT 'N'::bpchar,
  custom_t1 text,
  custom_n1 bigint,
  custom_f1 numeric,
  custom_d1 timestamp without time zone,
  custom_t2 character varying(255) DEFAULT NULL::character varying,
  custom_n2 bigint,
  custom_f2 numeric,
  custom_d2 timestamp without time zone,
  custom_t3 character varying(255) DEFAULT NULL::character varying,
  custom_n3 bigint,
  custom_f3 numeric,
  custom_d3 timestamp without time zone,
  custom_t4 character varying(255) DEFAULT NULL::character varying,
  custom_n4 bigint,
  custom_f4 numeric,
  custom_d4 timestamp without time zone,
  custom_t5 character varying(255) DEFAULT NULL::character varying,
  custom_n5 bigint,
  custom_f5 numeric,
  custom_d5 timestamp without time zone,
  custom_t6 character varying(255) DEFAULT NULL::character varying,
  custom_d6 timestamp without time zone,
  custom_t7 character varying(255) DEFAULT NULL::character varying,
  custom_d7 timestamp without time zone,
  custom_t8 character varying(255) DEFAULT NULL::character varying,
  custom_d8 timestamp without time zone,
  custom_t9 character varying(255) DEFAULT NULL::character varying,
  custom_d9 timestamp without time zone,
  custom_t10 character varying(255) DEFAULT NULL::character varying,
  custom_d10 timestamp without time zone,
  custom_t11 character varying(255) DEFAULT NULL::character varying,
  custom_t12 character varying(255) DEFAULT NULL::character varying,
  custom_t13 character varying(255) DEFAULT NULL::character varying,
  custom_t14 character varying(255) DEFAULT NULL::character varying,
  custom_t15 character varying(255) DEFAULT NULL::character varying,
  tablename character varying(32) DEFAULT 'res_letterbox'::character varying,
  initiator character varying(50) DEFAULT NULL::character varying,
  dest_user character varying(128) DEFAULT NULL::character varying,
  video_batch integer DEFAULT NULL,
  video_time integer DEFAULT NULL,
  video_user character varying(50)  DEFAULT NULL,
  video_date timestamp without time zone,
  esign_proof_id character varying(255),
  esign_proof_content text,
  esign_content text,
  esign_date timestamp without time zone,
  CONSTRAINT res_letterbox_pkey PRIMARY KEY  (res_id)
)
WITH (OIDS=FALSE);

CREATE TABLE mlb_coll_ext (
  res_id bigint NOT NULL,
  category_id character varying(50)  NOT NULL,
  exp_contact_id integer default NULL,
  exp_user_id character varying(128) default NULL,
  dest_contact_id integer default NULL,
  dest_user_id character varying(128) default NULL,
  nature_id character varying(50),
  alt_identifier character varying(255)  default NULL,
  admission_date timestamp without time zone,
  answer_type_bitmask character varying(7)  default NULL,
  other_answer_desc character varying(255)  DEFAULT NULL::character varying,
  process_limit_date timestamp without time zone default NULL,
  process_notes text,
  closing_date timestamp without time zone default NULL,
  alarm1_date timestamp without time zone default NULL,
  alarm2_date timestamp without time zone default NULL,
  flag_notif char(1)  default 'N'::character varying ,
  flag_alarm1 char(1)  default 'N'::character varying ,
  flag_alarm2 char(1) default 'N'::character varying
)WITH (OIDS=FALSE);

CREATE SEQUENCE res_id_version_letterbox_seq
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 100
  CACHE 1;

CREATE TABLE res_version_letterbox
(
  res_id bigint NOT NULL DEFAULT nextval('res_id_version_letterbox_seq'::regclass),
  title character varying(255) DEFAULT NULL::character varying,
  subject text,
  description text,
  publisher character varying(255) DEFAULT NULL::character varying,
  contributor character varying(255) DEFAULT NULL::character varying,
  type_id bigint NOT NULL,
  format character varying(50) NOT NULL,
  typist character varying(128) NOT NULL,
  creation_date timestamp without time zone NOT NULL,
  fulltext_result character varying(10) DEFAULT NULL::character varying,
  ocr_result character varying(10) DEFAULT NULL::character varying,
  converter_result character varying(10) DEFAULT NULL::character varying,
  author character varying(255) DEFAULT NULL::character varying,
  author_name text,
  identifier character varying(255) DEFAULT NULL::character varying,
  source character varying(255) DEFAULT NULL::character varying,
  doc_language character varying(50) DEFAULT NULL::character varying,
  relation bigint,
  coverage character varying(255) DEFAULT NULL::character varying,
  doc_date timestamp without time zone,
  docserver_id character varying(32) NOT NULL,
  folders_system_id bigint,
  arbox_id character varying(32) DEFAULT NULL::character varying,
  path character varying(255) DEFAULT NULL::character varying,
  filename character varying(255) DEFAULT NULL::character varying,
  offset_doc character varying(255) DEFAULT NULL::character varying,
  logical_adr character varying(255) DEFAULT NULL::character varying,
  fingerprint character varying(255) DEFAULT NULL::character varying,
  filesize bigint,
  is_paper character(1) DEFAULT NULL::bpchar,
  page_count integer,
  scan_date timestamp without time zone,
  scan_user character varying(50) DEFAULT NULL::character varying,
  scan_location character varying(255) DEFAULT NULL::character varying,
  scan_wkstation character varying(255) DEFAULT NULL::character varying,
  scan_batch character varying(50) DEFAULT NULL::character varying,
  burn_batch character varying(50) DEFAULT NULL::character varying,
  scan_postmark character varying(50) DEFAULT NULL::character varying,
  envelop_id bigint,
  status character varying(10) NOT NULL,
  destination character varying(50) DEFAULT NULL::character varying,
  approver character varying(50) DEFAULT NULL::character varying,
  validation_date timestamp without time zone,
  work_batch bigint,
  origin character varying(50) DEFAULT NULL::character varying,
  is_ingoing character(1) DEFAULT NULL::bpchar,
  priority smallint,
  arbatch_id bigint,
  policy_id character varying(32),
  cycle_id character varying(32),
  is_multi_docservers character(1) NOT NULL DEFAULT 'N'::bpchar,
  is_frozen character(1) NOT NULL DEFAULT 'N'::bpchar,
  custom_t1 text,
  custom_n1 bigint,
  custom_f1 numeric,
  custom_d1 timestamp without time zone,
  custom_t2 character varying(255) DEFAULT NULL::character varying,
  custom_n2 bigint,
  custom_f2 numeric,
  custom_d2 timestamp without time zone,
  custom_t3 character varying(255) DEFAULT NULL::character varying,
  custom_n3 bigint,
  custom_f3 numeric,
  custom_d3 timestamp without time zone,
  custom_t4 character varying(255) DEFAULT NULL::character varying,
  custom_n4 bigint,
  custom_f4 numeric,
  custom_d4 timestamp without time zone,
  custom_t5 character varying(255) DEFAULT NULL::character varying,
  custom_n5 bigint,
  custom_f5 numeric,
  custom_d5 timestamp without time zone,
  custom_t6 character varying(255) DEFAULT NULL::character varying,
  custom_d6 timestamp without time zone,
  custom_t7 character varying(255) DEFAULT NULL::character varying,
  custom_d7 timestamp without time zone,
  custom_t8 character varying(255) DEFAULT NULL::character varying,
  custom_d8 timestamp without time zone,
  custom_t9 character varying(255) DEFAULT NULL::character varying,
  custom_d9 timestamp without time zone,
  custom_t10 character varying(255) DEFAULT NULL::character varying,
  custom_d10 timestamp without time zone,
  custom_t11 character varying(255) DEFAULT NULL::character varying,
  custom_t12 character varying(255) DEFAULT NULL::character varying,
  custom_t13 character varying(255) DEFAULT NULL::character varying,
  custom_t14 character varying(255) DEFAULT NULL::character varying,
  custom_t15 character varying(255) DEFAULT NULL::character varying,
  tablename character varying(32) DEFAULT 'res_version_letterbox'::character varying,
  initiator character varying(50) DEFAULT NULL::character varying,
  dest_user character varying(128) DEFAULT NULL::character varying,
  video_batch integer,
  video_time integer,
  video_user character varying(128) DEFAULT NULL::character varying,
  video_date timestamp without time zone,
  cycle_date timestamp without time zone,
  coll_id character varying(32) NOT NULL,
  res_id_master bigint,
  CONSTRAINT res_version_letterbox_pkey PRIMARY KEY (res_id)
)
WITH (
  OIDS=FALSE
);

CREATE SEQUENCE res_id_version_x_seq
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 100
  CACHE 1;

CREATE TABLE res_version_x
(
  res_id bigint NOT NULL DEFAULT nextval('res_id_version_x_seq'::regclass),
  title character varying(255) DEFAULT NULL::character varying,
  subject text,
  description text,
  publisher character varying(255) DEFAULT NULL::character varying,
  contributor character varying(255) DEFAULT NULL::character varying,
  type_id bigint NOT NULL,
  format character varying(50) NOT NULL,
  typist character varying(128) NOT NULL,
  creation_date timestamp without time zone NOT NULL,
  fulltext_result character varying(10) DEFAULT NULL::character varying,
  ocr_result character varying(10) DEFAULT NULL::character varying,
  converter_result character varying(10) DEFAULT NULL::character varying,
  author character varying(255) DEFAULT NULL::character varying,
  author_name text,
  identifier character varying(255) DEFAULT NULL::character varying,
  source character varying(255) DEFAULT NULL::character varying,
  doc_language character varying(50) DEFAULT NULL::character varying,
  relation bigint,
  coverage character varying(255) DEFAULT NULL::character varying,
  doc_date timestamp without time zone,
  docserver_id character varying(32) NOT NULL,
  folders_system_id bigint,
  arbox_id character varying(32) DEFAULT NULL::character varying,
  path character varying(255) DEFAULT NULL::character varying,
  filename character varying(255) DEFAULT NULL::character varying,
  offset_doc character varying(255) DEFAULT NULL::character varying,
  logical_adr character varying(255) DEFAULT NULL::character varying,
  fingerprint character varying(255) DEFAULT NULL::character varying,
  filesize bigint,
  is_paper character(1) DEFAULT NULL::bpchar,
  page_count integer,
  scan_date timestamp without time zone,
  scan_user character varying(50) DEFAULT NULL::character varying,
  scan_location character varying(255) DEFAULT NULL::character varying,
  scan_wkstation character varying(255) DEFAULT NULL::character varying,
  scan_batch character varying(50) DEFAULT NULL::character varying,
  burn_batch character varying(50) DEFAULT NULL::character varying,
  scan_postmark character varying(50) DEFAULT NULL::character varying,
  envelop_id bigint,
  status character varying(10) NOT NULL,
  destination character varying(50) DEFAULT NULL::character varying,
  approver character varying(50) DEFAULT NULL::character varying,
  validation_date timestamp without time zone,
  work_batch bigint,
  origin character varying(50) DEFAULT NULL::character varying,
  is_ingoing character(1) DEFAULT NULL::bpchar,
  priority smallint,
  arbatch_id bigint,
  policy_id character varying(32),
  cycle_id character varying(32),
  is_multi_docservers character(1) NOT NULL DEFAULT 'N'::bpchar,
  is_frozen character(1) NOT NULL DEFAULT 'N'::bpchar,
  custom_t1 text,
  custom_n1 bigint,
  custom_f1 numeric,
  custom_d1 timestamp without time zone,
  custom_t2 character varying(255) DEFAULT NULL::character varying,
  custom_n2 bigint,
  custom_f2 numeric,
  custom_d2 timestamp without time zone,
  custom_t3 character varying(255) DEFAULT NULL::character varying,
  custom_n3 bigint,
  custom_f3 numeric,
  custom_d3 timestamp without time zone,
  custom_t4 character varying(255) DEFAULT NULL::character varying,
  custom_n4 bigint,
  custom_f4 numeric,
  custom_d4 timestamp without time zone,
  custom_t5 character varying(255) DEFAULT NULL::character varying,
  custom_n5 bigint,
  custom_f5 numeric,
  custom_d5 timestamp without time zone,
  custom_t6 character varying(255) DEFAULT NULL::character varying,
  custom_d6 timestamp without time zone,
  custom_t7 character varying(255) DEFAULT NULL::character varying,
  custom_d7 timestamp without time zone,
  custom_t8 character varying(255) DEFAULT NULL::character varying,
  custom_d8 timestamp without time zone,
  custom_t9 character varying(255) DEFAULT NULL::character varying,
  custom_d9 timestamp without time zone,
  custom_t10 character varying(255) DEFAULT NULL::character varying,
  custom_d10 timestamp without time zone,
  custom_t11 character varying(255) DEFAULT NULL::character varying,
  custom_t12 character varying(255) DEFAULT NULL::character varying,
  custom_t13 character varying(255) DEFAULT NULL::character varying,
  custom_t14 character varying(255) DEFAULT NULL::character varying,
  custom_t15 character varying(255) DEFAULT NULL::character varying,
  tablename character varying(32) DEFAULT 'res_version_x'::character varying,
  initiator character varying(50) DEFAULT NULL::character varying,
  dest_user character varying(128) DEFAULT NULL::character varying,
  video_batch integer,
  video_time integer,
  video_user character varying(128) DEFAULT NULL::character varying,
  video_date timestamp without time zone,
  cycle_date timestamp without time zone,
  coll_id character varying(32) NOT NULL,
  res_id_master bigint,
  CONSTRAINT res_version_x_pkey PRIMARY KEY (res_id)
)
WITH (
  OIDS=FALSE
);

CREATE TABLE mlb_doctype_ext (
  type_id bigint NOT NULL,
  process_delay bigint NOT NULL DEFAULT '21',
  delay1 bigint NOT NULL DEFAULT '14',
  delay2 bigint NOT NULL DEFAULT '1',
  CONSTRAINT type_id PRIMARY KEY (type_id)
)
WITH (OIDS=FALSE);

CREATE OR REPLACE VIEW res_view AS
 SELECT r.tablename, r.is_multi_docservers, r.res_id, r.title, r.subject, r.page_count, r.identifier, r.doc_date, r.type_id,
 d.description AS type_label, d.doctypes_first_level_id, dfl.doctypes_first_level_label, dfl.css_style as doctype_first_level_style,
 d.doctypes_second_level_id, dsl.doctypes_second_level_label, dsl.css_style as doctype_second_level_style,
 r.format, r.typist, r.creation_date, r.relation, r.docserver_id,
 r.folders_system_id, r.path, r.filename, r.fingerprint, r.offset_doc, r.filesize, r.status,
 r.work_batch, r.arbatch_id, r.arbox_id,  r.is_paper, r.scan_date, r.scan_user,r.scan_location,r.scan_wkstation,
 r.scan_batch,r.doc_language,r.description,r.source,r.initiator,r.destination,r.dest_user,r.policy_id,r.cycle_id,r.cycle_date,
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
 r.custom_f4 AS doc_custom_f4, r.custom_f5 AS doc_custom_f5, r.is_frozen as res_is_frozen
   FROM  doctypes d, doctypes_first_level dfl, doctypes_second_level dsl, res_x r
   WHERE r.type_id = d.type_id
   AND d.doctypes_first_level_id = dfl.doctypes_first_level_id
   AND d.doctypes_second_level_id = dsl.doctypes_second_level_id;

-- View without cases :
--CREATE OR REPLACE VIEW res_view_letterbox AS
 --SELECT r.tablename, r.res_id, r.type_id, d.description AS type_label, d.doctypes_first_level_id, dfl.doctypes_first_level_label, dfl.css_style as doctype_first_level_style,
 -- d.doctypes_second_level_id, dsl.doctypes_second_level_label, dsl.css_style as doctype_second_level_style,
 -- r.format, r.typist, r.creation_date, r.relation, r.docserver_id, r.folders_system_id, f.folder_id, r.path, r.filename, r.fingerprint, r.filesize, r.status, r.work_batch, r.arbatch_id, r.arbox_id, r.page_count, r.is_paper, r.doc_date, r.scan_date, r.scan_user, r.scan_location, r.scan_wkstation, r.scan_batch, r.doc_language, r.description, r.source, r.author, r.custom_t1 AS doc_custom_t1, r.custom_t2 AS doc_custom_t2, r.custom_t3 AS doc_custom_t3, r.custom_t4 AS doc_custom_t4, r.custom_t5 AS doc_custom_t5, r.custom_t6 AS doc_custom_t6, r.custom_t7 AS doc_custom_t7, r.custom_t8 AS doc_custom_t8, r.custom_t9 AS doc_custom_t9, r.custom_t10 AS doc_custom_t10, r.custom_t11 AS doc_custom_t11, r.custom_t12 AS doc_custom_t12, r.custom_t13 AS doc_custom_t13, r.custom_t14 AS doc_custom_t14, r.custom_t15 AS doc_custom_t15, r.custom_d1 AS doc_custom_d1, r.custom_d2 AS doc_custom_d2, r.custom_d3 AS doc_custom_d3, r.custom_d4 AS doc_custom_d4, r.custom_d5 AS doc_custom_d5, r.custom_d6 AS doc_custom_d6, r.custom_d7 AS doc_custom_d7, r.custom_d8 AS doc_custom_d8, r.custom_d9 AS doc_custom_d9, r.custom_d10 AS doc_custom_d10, r.custom_n1 AS doc_custom_n1, r.custom_n2 AS doc_custom_n2, r.custom_n3 AS doc_custom_n3, r.custom_n4 AS doc_custom_n4, r.custom_n5 AS doc_custom_n5, r.custom_f1 AS doc_custom_f1, r.custom_f2 AS doc_custom_f2, r.custom_f3 AS doc_custom_f3, r.custom_f4 AS doc_custom_f4, r.custom_f5 AS doc_custom_f5, f.foldertype_id, ft.foldertype_label, f.custom_t1 AS fold_custom_t1, f.custom_t2 AS fold_custom_t2, f.custom_t3 AS fold_custom_t3, f.custom_t4 AS fold_custom_t4, f.custom_t5 AS fold_custom_t5, f.custom_t6 AS fold_custom_t6, f.custom_t7 AS fold_custom_t7, f.custom_t8 AS fold_custom_t8, f.custom_t9 AS fold_custom_t9, f.custom_t10 AS fold_custom_t10, f.custom_t11 AS fold_custom_t11, f.custom_t12 AS fold_custom_t12, f.custom_t13 AS fold_custom_t13, f.custom_t14 AS fold_custom_t14, f.custom_t15 AS fold_custom_t15, f.custom_d1 AS fold_custom_d1, f.custom_d2 AS fold_custom_d2, f.custom_d3 AS fold_custom_d3, f.custom_d4 AS fold_custom_d4, f.custom_d5 AS fold_custom_d5, f.custom_d6 AS fold_custom_d6, f.custom_d7 AS fold_custom_d7, f.custom_d8 AS fold_custom_d8, f.custom_d9 AS fold_custom_d9, f.custom_d10 AS fold_custom_d10, f.custom_n1 AS fold_custom_n1, f.custom_n2 AS fold_custom_n2, f.custom_n3 AS fold_custom_n3, f.custom_n4 AS fold_custom_n4, f.custom_n5 AS fold_custom_n5, f.custom_f1 AS fold_custom_f1, f.custom_f2 AS fold_custom_f2, f.custom_f3 AS fold_custom_f3, f.custom_f4 AS fold_custom_f4, f.custom_f5 AS fold_custom_f5, f.is_complete AS fold_complete, f.status AS fold_status, f.subject AS fold_subject, f.parent_id AS fold_parent_id, f.folder_level, f.folder_name, f.creation_date AS fold_creation_date, r.initiator, r.destination, r.dest_user, mlb.category_id, mlb.exp_contact_id, mlb.exp_user_id, mlb.dest_user_id, mlb.dest_contact_id, mlb.nature_id, mlb.alt_identifier, mlb.admission_date, mlb.answer_type_bitmask, mlb.other_answer_desc, mlb.process_limit_date, mlb.closing_date, mlb.alarm1_date, mlb.alarm2_date, mlb.flag_notif, mlb.flag_alarm1, mlb.flag_alarm2, r.video_user, r.video_time, r.video_batch, r.subject, r.identifier, r.title, r.priority, mlb.process_notes
  -- FROM doctypes d, doctypes_first_level dfl, doctypes_second_level dsl, res_letterbox r
   --LEFT JOIN ar_batch a ON r.arbatch_id = a.arbatch_id
   --LEFT JOIN folders f ON r.folders_system_id = f.folders_system_id
   --LEFT JOIN mlb_coll_ext mlb ON mlb.res_id = r.res_id
   --LEFT JOIN foldertypes ft ON f.foldertype_id = ft.foldertype_id AND f.status::text <> 'DEL'::text
 -- WHERE r.type_id = d.type_id AND d.doctypes_first_level_id = dfl.doctypes_first_level_id AND d.doctypes_second_level_id = dsl.doctypes_second_level_id;

CREATE VIEW res_view_letterbox AS
    SELECT r.tablename, r.is_multi_docservers, r.res_id, r.type_id, 
    d.description AS type_label, d.doctypes_first_level_id, 
    dfl.doctypes_first_level_label, dfl.css_style as doctype_first_level_style,
    d.doctypes_second_level_id, dsl.doctypes_second_level_label, 
    dsl.css_style as doctype_second_level_style, r.format, r.typist, 
    r.creation_date, r.relation, r.docserver_id, r.folders_system_id, 
    f.folder_id, f.is_frozen as folder_is_frozen, r.path, r.filename, r.fingerprint, r.offset_doc, r.filesize, 
    r.status, r.work_batch, r.arbatch_id, r.arbox_id, r.page_count, r.is_paper, 
    r.doc_date, r.scan_date, r.scan_user, r.scan_location, r.scan_wkstation,
    r.scan_batch, r.doc_language, r.description, r.source, r.author, 
    r.custom_t1 AS doc_custom_t1, r.custom_t2 AS doc_custom_t2, 
    r.custom_t3 AS doc_custom_t3, r.custom_t4 AS doc_custom_t4, 
    r.custom_t5 AS doc_custom_t5, r.custom_t6 AS doc_custom_t6,
    r.custom_t7 AS doc_custom_t7, r.custom_t8 AS doc_custom_t8, 
    r.custom_t9 AS doc_custom_t9, r.custom_t10 AS doc_custom_t10, 
    r.custom_t11 AS doc_custom_t11, r.custom_t12 AS doc_custom_t12, 
    r.custom_t13 AS doc_custom_t13, r.custom_t14 AS doc_custom_t14, 
    r.custom_t15 AS doc_custom_t15, r.custom_d1 AS doc_custom_d1,
    r.custom_d2 AS doc_custom_d2, r.custom_d3 AS doc_custom_d3,
    r.custom_d4 AS doc_custom_d4, r.custom_d5 AS doc_custom_d5, 
    r.custom_d6 AS doc_custom_d6, r.custom_d7 AS doc_custom_d7, 
    r.custom_d8 AS doc_custom_d8, r.custom_d9 AS doc_custom_d9, 
    r.custom_d10 AS doc_custom_d10, r.custom_n1 AS doc_custom_n1,
    r.custom_n2 AS doc_custom_n2, r.custom_n3 AS doc_custom_n3, 
    r.custom_n4 AS doc_custom_n4, r.custom_n5 AS doc_custom_n5, 
    r.custom_f1 AS doc_custom_f1, r.custom_f2 AS doc_custom_f2, 
    r.custom_f3 AS doc_custom_f3, r.custom_f4 AS doc_custom_f4, 
    r.custom_f5 AS doc_custom_f5, f.foldertype_id, ft.foldertype_label, 
    f.custom_t1 AS fold_custom_t1, f.custom_t2 AS fold_custom_t2, 
    f.custom_t3 AS fold_custom_t3, f.custom_t4 AS fold_custom_t4,
    f.custom_t5 AS fold_custom_t5, f.custom_t6 AS fold_custom_t6, 
    f.custom_t7 AS fold_custom_t7, f.custom_t8 AS fold_custom_t8, 
    f.custom_t9 AS fold_custom_t9, f.custom_t10 AS fold_custom_t10, 
    f.custom_t11 AS fold_custom_t11, f.custom_t12 AS fold_custom_t12,
    f.custom_t13 AS fold_custom_t13, f.custom_t14 AS fold_custom_t14, 
    f.custom_t15 AS fold_custom_t15, f.custom_d1 AS fold_custom_d1, 
    f.custom_d2 AS fold_custom_d2, f.custom_d3 AS fold_custom_d3, 
    f.custom_d4 AS fold_custom_d4, f.custom_d5 AS fold_custom_d5, 
    f.custom_d6 AS fold_custom_d6, f.custom_d7 AS fold_custom_d7, 
    f.custom_d8 AS fold_custom_d8, f.custom_d9 AS fold_custom_d9, 
    f.custom_d10 AS fold_custom_d10, f.custom_n1 AS fold_custom_n1, 
    f.custom_n2 AS fold_custom_n2, f.custom_n3 AS fold_custom_n3,
    f.custom_n4 AS fold_custom_n4, f.custom_n5 AS fold_custom_n5,
    f.custom_f1 AS fold_custom_f1, f.custom_f2 AS fold_custom_f2, 
    f.custom_f3 AS fold_custom_f3, f.custom_f4 AS fold_custom_f4, 
    f.custom_f5 AS fold_custom_f5, f.is_complete AS fold_complete, 
    f.status AS fold_status, f.subject AS fold_subject,
    f.parent_id AS fold_parent_id, f.folder_level, f.folder_name, 
    f.creation_date AS fold_creation_date, r.initiator, r.destination, 
    r.dest_user, mlb.category_id, mlb.exp_contact_id, mlb.exp_user_id, 
    mlb.dest_user_id, mlb.dest_contact_id, mlb.nature_id, mlb.alt_identifier, 
    mlb.admission_date, mlb.answer_type_bitmask, mlb.other_answer_desc,
    mlb.process_limit_date, mlb.closing_date, mlb.alarm1_date, mlb.alarm2_date, 
    mlb.flag_notif, mlb.flag_alarm1, mlb.flag_alarm2, r.video_user, r.video_time,
    r.video_batch, r.subject, r.identifier, r.title, r.priority, mlb.process_notes,
    ca.case_id, ca.case_label, ca.case_description, en.entity_label, 
    cont.contact_id AS contact_id, cont.email AS contact_email,
    cont.firstname AS contact_firstname, cont.lastname AS contact_lastname, 
    cont.society AS contact_society, u.lastname AS user_lastname,
    u.firstname AS user_firstname, list.item_id AS dest_user_from_listinstance,
    r.is_frozen as res_is_frozen 
    FROM doctypes d, doctypes_first_level dfl, doctypes_second_level dsl,
    ((((((((((ar_batch a RIGHT JOIN res_letterbox r ON ((r.arbatch_id = a.arbatch_id))) 
    LEFT JOIN entities en ON (((r.destination)::text = (en.entity_id)::text))) 
    LEFT JOIN folders f ON ((r.folders_system_id = f.folders_system_id))) 
    LEFT JOIN cases_res cr ON ((r.res_id = cr.res_id)))
    LEFT JOIN mlb_coll_ext mlb ON ((mlb.res_id = r.res_id))) 
    LEFT JOIN foldertypes ft ON (((f.foldertype_id = ft.foldertype_id)
        AND ((f.status)::text <> 'DEL'::text))))
    LEFT JOIN cases ca ON ((cr.case_id = ca.case_id))) 
    LEFT JOIN contacts cont ON (((mlb.exp_contact_id = cont.contact_id) 
        OR (mlb.dest_contact_id = cont.contact_id)))) 
    LEFT JOIN users u ON ((((mlb.exp_user_id)::text = (u.user_id)::text) 
        OR ((mlb.dest_user_id)::text = (u.user_id)::text)))) 
    LEFT JOIN listinstance list ON (((r.res_id = list.res_id)
        AND ((list.item_mode)::text = 'dest'::text))))
    WHERE (((r.type_id = d.type_id) AND 
    (d.doctypes_first_level_id = dfl.doctypes_first_level_id))
    AND (d.doctypes_second_level_id = dsl.doctypes_second_level_id));
CREATE OR REPLACE VIEW res_view_apa AS
 select * from res_apa;



CREATE TABLE doctypes_indexes
(
  type_id bigint NOT NULL,
  coll_id character varying(32) NOT NULL,
  field_name character varying(255) NOT NULL,
  mandatory character(1) NOT NULL DEFAULT 'N'::bpchar,
  CONSTRAINT doctypes_indexes_pkey PRIMARY KEY (type_id, coll_id, field_name)
)
WITH (OIDS=FALSE);


-- Resource view used to fill af_target, we exclude from res_x the branches already in af_target table

CREATE OR REPLACE VIEW af_view_year_view AS
 SELECT r.custom_t3 AS level1, date_part( 'year', r.doc_date) AS level2, r.custom_t4 AS level3,
        r.res_id, r.creation_date, r.status -- for where clause
   FROM  res_x r
   WHERE  NOT (EXISTS ( SELECT t.level1, t.level2, t.level3
           FROM af_view_year_target t
          WHERE r.custom_t3::text = t.level1::text AND cast(date_part( 'year', r.doc_date) as character) = t.level2 AND r.custom_t4 = t.level3));

CREATE OR REPLACE VIEW af_view_customer_view AS
 SELECT substring(r.custom_t4, 1, 1) AS level1,  r.custom_t4 AS level2, date_part( 'year', r.doc_date) AS level3,
        r.res_id, r.creation_date, r.status -- for where clause
   FROM  res_x r
   WHERE status <> 'DEL' and date_part( 'year', doc_date) is not null
   AND NOT (EXISTS ( SELECT t.level1, t.level2, t.level3
           FROM af_view_customer_target t
          WHERE substring(r.custom_t4, 1, 1)::text = t.level1::text AND r.custom_t4::text = t.level2::text
          AND cast(date_part( 'year', r.doc_date) as character) = t.level3)) ;

-- View used to display trees
CREATE OR REPLACE VIEW af_view_year_target_view AS
 SELECT af.level1, af.level1_id, af.level1 as level1_label, af.level2, af.level2_id, af.level2 as level2_label, af.level3, af.level3_id, af.level3 as level3_label
   FROM af_view_year_target af;

CREATE OR REPLACE VIEW af_view_customer_target_view AS
 SELECT af.level1, af.level1_id, af.level1 as level1_label, af.level2, af.level2_id, af.level2 as level2_label, af.level3, af.level3_id, af.level3 as level3_label
   FROM af_view_customer_target af ;

-- Views for postindexing
 CREATE OR REPLACE VIEW view_folders AS 
 SELECT folders.folders_system_id, folders.folder_id, folders.foldertype_id, foldertypes.foldertype_label, (folders.folder_id::text || ' - '::text) || folders.folder_name::text AS folder_full_label, folders.parent_id, folders.folder_name, folders.subject, folders.description, folders.author, folders.typist, folders.status, folders.folder_level, folders.creation_date, folders.folder_out_id, folders.custom_t1, folders.custom_n1, folders.custom_f1, folders.custom_d1, folders.custom_t2, folders.custom_n2, folders.custom_f2, folders.custom_d2, folders.custom_t3, folders.custom_n3, folders.custom_f3, folders.custom_d3, folders.custom_t4, folders.custom_n4, folders.custom_f4, folders.custom_d4, folders.custom_t5, folders.custom_n5, folders.custom_f5, folders.custom_d5, folders.custom_t6, folders.custom_d6, folders.custom_t7, folders.custom_d7, folders.custom_t8, folders.custom_d8, folders.custom_t9, folders.custom_d9, folders.custom_t10, folders.custom_d10, folders.custom_t11, folders.custom_d11, folders.custom_t12, folders.custom_d12, folders.custom_t13, folders.custom_d13, folders.custom_t14, folders.custom_d14, folders.custom_t15, folders.is_complete, folders.is_folder_out, folders.last_modified_date, count(res_view_letterbox.folders_system_id) AS count_document, folders.video_status
   FROM foldertypes, folders
   LEFT JOIN res_view_letterbox ON res_view_letterbox.folders_system_id = folders.folders_system_id
  WHERE folders.foldertype_id = foldertypes.foldertype_id
  GROUP BY folders.folders_system_id, folders.folder_id, folders.foldertype_id, foldertypes.foldertype_label, folder_full_label, folders.parent_id, folders.folder_name, folders.subject, folders.description, folders.author, folders.typist, folders.status, folders.folder_level, folders.creation_date, folders.folder_out_id, folders.custom_t1, folders.custom_n1, folders.custom_f1, folders.custom_d1, folders.custom_t2, folders.custom_n2, folders.custom_f2, folders.custom_d2, folders.custom_t3, folders.custom_n3, folders.custom_f3, folders.custom_d3, folders.custom_t4, folders.custom_n4, folders.custom_f4, folders.custom_d4, folders.custom_t5, folders.custom_n5, folders.custom_f5, folders.custom_d5, folders.custom_t6, folders.custom_d6, folders.custom_t7, folders.custom_d7, folders.custom_t8, folders.custom_d8, folders.custom_t9, folders.custom_d9, folders.custom_t10, folders.custom_d10, folders.custom_t11, folders.custom_d11, folders.custom_t12, folders.custom_d12, folders.custom_t13, folders.custom_d13, folders.custom_t14, folders.custom_d14, folders.custom_t15, folders.is_complete, folders.is_folder_out, folders.last_modified_date, folders.video_status;

  CREATE OR REPLACE VIEW view_postindexing AS 
 SELECT res_view_letterbox.video_user, (users.firstname::text || ' '::text) || users.lastname::text AS user_name, res_view_letterbox.video_batch, res_view_letterbox.video_time, count(res_view_letterbox.res_id) AS count_documents, res_view_letterbox.folders_system_id, (folders.folder_id::text || ' / '::text) || folders.folder_name::text AS folder_full_label, folders.video_status
   FROM res_view_letterbox
   LEFT JOIN users ON res_view_letterbox.video_user::text = users.user_id::text
   LEFT JOIN folders ON folders.folders_system_id = res_view_letterbox.folders_system_id
  WHERE res_view_letterbox.video_batch IS NOT NULL
  GROUP BY res_view_letterbox.video_user, (users.firstname::text || ' '::text) || users.lastname::text, res_view_letterbox.video_batch, res_view_letterbox.video_time, res_view_letterbox.folders_system_id, (folders.folder_id::text || ' / '::text) || folders.folder_name::text, folders.video_status;
