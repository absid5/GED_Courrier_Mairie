-- core/sql/structure/core.postgresql.sql

SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;

--DROP PROCEDURAL LANGUAGE IF EXISTS plpgsql CASCADE;
--CREATE PROCEDURAL LANGUAGE plpgsql;

SET search_path = public, pg_catalog;
SET default_tablespace = '';
SET default_with_oids = false;

CREATE SEQUENCE actions_id_seq
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 500
  CACHE 1;

CREATE TABLE actions
(
  id integer NOT NULL DEFAULT nextval('actions_id_seq'::regclass),
  keyword character varying(32) NOT NULL DEFAULT ''::bpchar,
  label_action character varying(255),
  id_status character varying(10),
  is_system character(1) NOT NULL DEFAULT 'N'::bpchar,
  is_folder_action character(1) NOT NULL DEFAULT 'N'::bpchar,
  enabled character(1) NOT NULL DEFAULT 'Y'::bpchar,
  action_page character varying(255),
  history character(1) NOT NULL DEFAULT 'N'::bpchar,
  origin character varying(255) NOT NULL DEFAULT 'apps'::bpchar,
  create_id  character(1) NOT NULL DEFAULT 'N'::bpchar,
  category_id character varying(255),
  CONSTRAINT actions_pkey PRIMARY KEY (id)
)
WITH (OIDS=FALSE);


CREATE TABLE docserver_types
(
  docserver_type_id character varying(32) NOT NULL,
  docserver_type_label character varying(255) DEFAULT NULL::character varying,
  enabled character(1) NOT NULL DEFAULT 'Y'::bpchar,
  is_container character(1) NOT NULL DEFAULT 'N'::bpchar,
  container_max_number integer NOT NULL DEFAULT (0)::integer,
  is_compressed character(1) NOT NULL DEFAULT 'N'::bpchar,
  compression_mode character varying(32) DEFAULT NULL::character varying,
  is_meta character(1) NOT NULL DEFAULT 'N'::bpchar,
  meta_template character varying(32) DEFAULT NULL::character varying,
  is_logged character(1) NOT NULL DEFAULT 'N'::bpchar,
  log_template character varying(32) DEFAULT NULL::character varying,
  is_signed character(1) NOT NULL DEFAULT 'N'::bpchar,
  fingerprint_mode character varying(32) DEFAULT NULL::character varying,
  CONSTRAINT docserver_types_pkey PRIMARY KEY (docserver_type_id)
)
WITH (OIDS=FALSE);

CREATE TABLE docservers
(
  docserver_id character varying(32) NOT NULL DEFAULT '1'::character varying,
  docserver_type_id character varying(32) NOT NULL,
  device_label character varying(255) DEFAULT NULL::character varying,
  is_readonly character(1) NOT NULL DEFAULT 'N'::bpchar,
  enabled character(1) NOT NULL DEFAULT 'Y'::bpchar,
  size_limit_number bigint NOT NULL DEFAULT (0)::bigint,
  actual_size_number bigint NOT NULL DEFAULT (0)::bigint,
  path_template character varying(255) NOT NULL,
  ext_docserver_info character varying(255) DEFAULT NULL::character varying,
  chain_before character varying(32) DEFAULT NULL::character varying,
  chain_after character varying(32) DEFAULT NULL::character varying,
  creation_date timestamp without time zone NOT NULL,
  closing_date timestamp without time zone,
  coll_id character varying(32) NOT NULL DEFAULT 'coll_1'::character varying,
  priority_number integer NOT NULL DEFAULT 10,
  docserver_location_id character varying(32) NOT NULL,
  adr_priority_number integer NOT NULL DEFAULT 1,
  CONSTRAINT docservers_pkey PRIMARY KEY (docserver_id)
)
WITH (OIDS=FALSE);

CREATE TABLE docserver_locations
(
  docserver_location_id character varying(32) NOT NULL,
  ipv4 character varying(255) DEFAULT NULL::character varying,
  ipv6 character varying(255) DEFAULT NULL::character varying,
  net_domain character varying(32) DEFAULT NULL::character varying,
  mask character varying(255) DEFAULT NULL::character varying,
  net_link character varying(255) DEFAULT NULL::character varying,
  enabled character(1) NOT NULL DEFAULT 'Y'::bpchar,
  CONSTRAINT docserver_locations_pkey PRIMARY KEY (docserver_location_id)
)
WITH (OIDS=FALSE);

CREATE SEQUENCE doctypes_type_id_seq
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 500
  CACHE 1;

CREATE TABLE doctypes
(
  coll_id character varying(32) NOT NULL DEFAULT ''::character varying,
  type_id integer NOT NULL DEFAULT nextval('doctypes_type_id_seq'::regclass),
  description character varying(255) NOT NULL DEFAULT ''::character varying,
  enabled character(1) NOT NULL DEFAULT 'Y'::bpchar,
  doctypes_first_level_id integer,
  doctypes_second_level_id integer,
  primary_retention  character varying(50) DEFAULT NULL,
  secondary_retention  character varying(50) DEFAULT NULL,
  CONSTRAINT doctypes_pkey PRIMARY KEY (type_id)
)
WITH (OIDS=FALSE);

CREATE TABLE ext_docserver
(
  doc_id character varying(255) NOT NULL,
  path character varying(255) NOT NULL,
  CONSTRAINT ext_docserver_pkey PRIMARY KEY (doc_id)
)
WITH (OIDS=FALSE);

CREATE TABLE groupsecurity
(
  group_id character varying(32) NOT NULL,
  resgroup_id character varying(32) NOT NULL,
  can_view character(1) NOT NULL,
  can_add character(1) NOT NULL,
  can_delete character(1) NOT NULL,
  CONSTRAINT groupsecurity_pkey PRIMARY KEY (group_id, resgroup_id)
)
WITH (OIDS=FALSE);

CREATE SEQUENCE history_id_seq
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 1
  CACHE 1;

CREATE TABLE history
(
  id bigint NOT NULL DEFAULT nextval('history_id_seq'::regclass),
  table_name character varying(32) DEFAULT NULL::character varying,
  record_id character varying(255) DEFAULT NULL::character varying,
  event_type character varying(32) NOT NULL,
  user_id character varying(128) NOT NULL,
  event_date timestamp without time zone NOT NULL,
  info text,
  id_module character varying(50) NOT NULL DEFAULT 'admin'::character varying,
  remote_ip character varying(32) DEFAULT NULL,
  event_id character varying(50),
  CONSTRAINT history_pkey PRIMARY KEY (id)
)
WITH (OIDS=FALSE);

CREATE SEQUENCE history_batch_id_seq
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 1
  CACHE 1;

CREATE TABLE history_batch
(
  id bigint NOT NULL DEFAULT nextval('history_batch_id_seq'::regclass),
  module_name character varying(32) DEFAULT NULL::character varying,
  batch_id bigint DEFAULT NULL::bigint,
  event_date timestamp without time zone NOT NULL,
  total_processed bigint DEFAULT NULL::bigint,
  total_errors bigint DEFAULT NULL::bigint,
  info text,
  CONSTRAINT history_batch_pkey PRIMARY KEY (id)
)
WITH (OIDS=FALSE);

CREATE TABLE parameters
(
  id character varying(255) NOT NULL,
  description TEXT,
  param_value_string character varying(255) DEFAULT NULL::character varying,
  param_value_int integer,
  param_value_date timestamp without time zone,
  CONSTRAINT parameters_pkey PRIMARY KEY (id)
)
WITH (OIDS=FALSE);

CREATE TABLE resgroup_content
(
  coll_id character varying(32) NOT NULL,
  res_id bigint NOT NULL,
  resgroup_id character varying(32) NOT NULL,
  "sequence" integer NOT NULL,
  CONSTRAINT resgroup_content_pkey PRIMARY KEY (coll_id, res_id, resgroup_id)
)
WITH (OIDS=FALSE);

CREATE TABLE resgroups
(
  resgroup_id character varying(32) NOT NULL,
  resgroup_desc character varying(255) NOT NULL,
  created_by character varying(255) NOT NULL,
  creation_date timestamp without time zone NOT NULL,
  CONSTRAINT resgroups_pkey PRIMARY KEY (resgroup_id)
)
WITH (OIDS=FALSE);

CREATE SEQUENCE security_security_id_seq
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 600
  CACHE 1;

CREATE TABLE "security"
(
  security_id bigint NOT NULL DEFAULT nextval('security_security_id_seq'::regclass),
  group_id character varying(32) NOT NULL,
  coll_id character varying(32) NOT NULL,
  where_clause text,
  maarch_comment text,
  can_insert character(1) NOT NULL DEFAULT 'N'::bpchar,
  can_update character(1) NOT NULL DEFAULT 'N'::bpchar,
  can_delete character(1) NOT NULL DEFAULT 'N'::bpchar,
  rights_bitmask integer NOT NULL DEFAULT 0,
  mr_start_date timestamp without time zone DEFAULT NULL,
  mr_stop_date timestamp without time zone DEFAULT NULL,
  where_target character varying(15) DEFAULT 'DOC'::character varying,
  CONSTRAINT security_pkey PRIMARY KEY (security_id)
)
WITH (OIDS=FALSE);

CREATE TABLE status
(
  id character varying(10) NOT NULL,
  label_status character varying(50) NOT NULL,
  is_system character(1) NOT NULL DEFAULT 'Y'::bpchar,
  is_folder_status character(1) NOT NULL default 'N'::bpchar,
  img_filename character varying(255),
  maarch_module character varying(255) NOT NULL DEFAULT 'apps'::character varying,
  can_be_searched character(1) NOT NULL DEFAULT 'Y'::bpchar,
  can_be_modified character(1) NOT NULL DEFAULT 'Y'::bpchar,
  CONSTRAINT status_pkey PRIMARY KEY (id)
)
WITH (OIDS=FALSE);

CREATE TABLE usergroup_content
(
  user_id character varying(128) NOT NULL,
  group_id character varying(32) NOT NULL,
  primary_group character(1) NOT NULL,
  "role" character varying(255) DEFAULT NULL::character varying,
  CONSTRAINT usergroup_content_pkey PRIMARY KEY (user_id, group_id)
)
WITH (OIDS=FALSE);

CREATE TABLE usergroups
(
  group_id character varying(32) NOT NULL,
  group_desc character varying(255) DEFAULT NULL::character varying,
  administrator character(1) NOT NULL DEFAULT 'N'::bpchar,
  custom_right1 character(1) NOT NULL DEFAULT 'N'::bpchar,
  custom_right2 character(1) NOT NULL DEFAULT 'N'::bpchar,
  custom_right3 character(1) NOT NULL DEFAULT 'N'::bpchar,
  custom_right4 character(1) NOT NULL DEFAULT 'N'::bpchar,
  enabled character(1) NOT NULL DEFAULT 'Y'::bpchar,
  CONSTRAINT usergroups_pkey PRIMARY KEY (group_id)
)
WITH (OIDS=FALSE);

CREATE TABLE usergroups_services
(
  group_id character varying NOT NULL,
  service_id character varying NOT NULL,
  CONSTRAINT usergroups_services_pkey PRIMARY KEY (group_id, service_id)
)
WITH (OIDS=FALSE);

CREATE TABLE users
(
  user_id character varying(128) NOT NULL,
  "password" character varying(255) DEFAULT NULL::character varying,
  firstname character varying(255) DEFAULT NULL::character varying,
  lastname character varying(255) DEFAULT NULL::character varying,
  phone character varying(32) DEFAULT NULL::character varying,
  mail character varying(255) DEFAULT NULL::character varying,
  department character varying(50) DEFAULT NULL::character varying,
  custom_t1 character varying(50) DEFAULT '0'::character varying,
  custom_t2 character varying(50) DEFAULT NULL::character varying,
  custom_t3 character varying(50) DEFAULT NULL::character varying,
  cookie_key character varying(255) DEFAULT NULL::character varying,
  cookie_date timestamp without time zone,
  enabled character(1) NOT NULL DEFAULT 'Y'::bpchar,
  change_password character(1) NOT NULL DEFAULT 'Y'::bpchar,
  delay_number integer DEFAULT NULL,
  status character varying(10) NOT NULL DEFAULT 'OK'::character varying,
  loginmode character varying(50) DEFAULT NULL::character varying,
  docserver_location_id character varying(32) DEFAULT NULL::character varying,
  thumbprint text DEFAULT NULL::character varying,
  signature_path character varying(255) DEFAULT NULL::character varying,
  signature_file_name character varying(255) DEFAULT NULL::character varying,
  initials character varying(32) DEFAULT NULL::character varying,
  CONSTRAINT users_pkey PRIMARY KEY (user_id)
)
WITH (OIDS=FALSE);


-- modules/advanced_physical_archive/sql/structure/advanced_physical_archive.postgresql.sql

CREATE SEQUENCE arbox_id_seq
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 10
  CACHE 1;

CREATE TABLE  ar_boxes (
  arbox_id bigint NOT NULL DEFAULT nextval('arbox_id_seq'::regclass),
  title character varying(255)  default NULL::character varying,
  subject character varying(255)  default NULL::character varying,
  description text ,
  entity_id character varying(32)  default NULL::character varying,
  arcontainer_id integer NOT NULL,
  status character varying(3)  default NULL::character varying,
  creation_date timestamp without time zone,
  retention_time timestamp without time zone,
  custom_t1 character varying(3)  default NULL::character varying,
  custom_n1 integer default NULL,
  custom_f1 numeric default NULL,
  custom_d1 timestamp without time zone,
  custom_t2 character varying(3)  default  NULL::character varying,
  custom_n2 integer default NULL,
  custom_f2 numeric default NULL,
  custom_d2 timestamp without time zone,
  custom_t3 character varying(50)  default  NULL::character varying,
  custom_n3 integer default NULL,
  custom_f3 numeric default NULL,
  custom_d3 timestamp without time zone,
  custom_t4 character varying(50)  default  NULL::character varying,
  custom_n4 integer default NULL,
  custom_f4 numeric default NULL,
  custom_d4 timestamp without time zone,
  custom_t5 character varying(255)  default  NULL::character varying,
  custom_n5 integer default NULL,
  custom_f5 numeric default NULL,
  custom_d5 timestamp without time zone,
  custom_t6 character varying(255)  default  NULL::character varying,
  custom_t7 character varying(255)  default  NULL::character varying,
  custom_t8 character varying(255)  default  NULL::character varying,
  custom_t9 character varying(255)  default  NULL::character varying,
  custom_t10 character varying(255)  default  NULL::character varying,
  custom_t11 character varying(255)  default  NULL::character varying,
   CONSTRAINT ar_boxes_pkey PRIMARY KEY (arbox_id)
)
WITH (OIDS=FALSE);

CREATE TABLE  ar_containers (
  arcontainer_id integer NOT NULL,
  arcontainer_desc character varying(255)  default NULL,
  status character varying(3)  default NULL,
  ctype_id character varying(32)  default NULL,
  position_id bigint default NULL,
  creation_date timestamp without time zone,
  entity_id character varying(32)  NOT NULL,
  retention_time timestamp without time zone,
  custom_t1 character varying(50)  default NULL,
  custom_n1 integer default NULL,
  custom_f1 numeric default NULL,
  custom_d1 timestamp without time zone,
  custom_t2 character varying(3)  default NULL,
  custom_n2 integer default NULL,
  custom_f2 numeric default NULL,
  custom_d2 timestamp without time zone,
 CONSTRAINT ar_containers_pkey PRIMARY KEY (arcontainer_id)
)
WITH (OIDS=FALSE);

CREATE TABLE  ar_container_types (
  ctype_id character varying(32)   NOT NULL,
  ctype_desc character varying(255)   NOT NULL,
  size_x float NOT NULL default '0',
  size_y float NOT NULL default '0',
  size_z float NOT NULL default '0',
  CONSTRAINT ar_container_types_pkey PRIMARY KEY (ctype_id)
)
WITH (OIDS=FALSE);

CREATE TABLE  ar_deposits (
  deposit_id bigint NOT NULL,
  deposit_label character varying(255)  NOT NULL,
  deposit_desc text  NOT NULL,
  flg_closed smallint NOT NULL,
  closing_date timestamp without time zone NOT NULL,
  creation_date timestamp without time zone NOT NULL,
  user_id character varying(128)  NOT NULL,
  CONSTRAINT ar_deposits_pkey PRIMARY KEY (deposit_id)
)
WITH (OIDS=FALSE);

CREATE TABLE  ar_header (
  header_id bigserial NOT NULL,
  creation_date timestamp without time zone NOT NULL,
  ctype_id character varying(32)   NOT NULL default '0',
  year_1 integer NOT NULL default '0',
  year_2 integer NOT NULL default '0',
  site_id character varying(32)   NOT NULL default '0',
  destruction_date timestamp without time zone,
  allow_transmission_date timestamp without time zone,
  weight integer default NULL,
  reservation_id bigint default NULL,
  deposit_id bigint default NULL,
  header_desc text  ,
  entity_id character varying(32)   default NULL,
  arnature_id character varying(32)   default NULL,
  arbox_id integer default NULL,
  arcontainer_id integer default NULL,
  CONSTRAINT ar_header_pkey PRIMARY KEY (header_id)
)
WITH (OIDS=FALSE);

CREATE TABLE  ar_natures (
  arnature_id character varying(32)  NOT NULL,
  arnature_desc character varying(255)  default NULL,
  arnature_retention integer NOT NULL,
  entity_id character varying(32)  default NULL,
  enabled character varying(1)  default NULL,
 CONSTRAINT ar_natures_pkey PRIMARY KEY (arnature_id)
)
WITH (OIDS=FALSE);

CREATE SEQUENCE position_id_seq
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 200
  CACHE 1;

CREATE TABLE  ar_positions (
  position_id bigint NOT NULL DEFAULT nextval('position_id_seq'::regclass),
  site_id character varying(32)  NOT NULL,
  pos_row character varying(32)  NOT NULL,
  pos_col integer NOT NULL,
  pos_level integer NOT NULL,
  pos_max_uc integer NOT NULL,
  pos_available_uc integer NOT NULL,
 CONSTRAINT ar_positions_pkey PRIMARY KEY (position_id)
)
WITH (OIDS=FALSE);

CREATE TABLE  ar_sites (
  site_id character varying(32)  NOT NULL default '0',
  site_desc character varying(255)  NOT NULL,
  entity_id character varying(32)  default NULL,
 CONSTRAINT ar_sites_pkey PRIMARY KEY (site_id)
)
WITH (OIDS=FALSE);

 CREATE  TABLE  res_apa (
 res_id serial  NOT  NULL ,
 title character varying(255)    default NULL ,
 subject text ,
 description text ,
 publisher character varying(255)    default NULL ,
 contributor character varying(255)    default NULL ,
 type_id integer  default  NULL ,
 format character varying(50)   default NULL ,
 typist character varying(128)   default NULL ,
 creation_date timestamp without time zone NOT  NULL ,
 author character varying(255)    default NULL ,
 author_name text ,
 identifier character varying(255)    default NULL ,
 source character varying(255)    default NULL ,
 doc_language character varying(50)    default NULL ,
 relation integer  default NULL ,
 coverage character varying(255)    default NULL ,
 doc_date timestamp without time zone  default NULL ,
 docserver_id character varying(32)   default NULL ,
 folders_system_id integer  default NULL ,
 arbox_id character varying(32)    default NULL ,
 path character varying(255)    default NULL ,
 filename character varying(255)    default NULL ,
 offset_doc character varying(255)    default NULL ,
 logical_adr character varying(255)    default NULL ,
 fingerprint character varying(255)    default NULL ,
 filesize integer  default NULL ,
 is_paper char(1)    default NULL ,
 page_count integer  default NULL ,
 scan_date timestamp without time zone  default NULL ,
 scan_user character varying(50)    default NULL ,
 scan_location character varying(255)    default NULL ,
 scan_wkstation character varying(255)    default NULL ,
 scan_batch character varying(50)    default NULL ,
 burn_batch character varying(50)    default NULL ,
 scan_postmark character varying(50)    default NULL ,
 envelop_id integer  default NULL ,
 status character varying(3)    default NULL ,
 destination character varying(50)    default NULL ,
 approver character varying(50)    default NULL ,
 validation_date timestamp without time zone  default NULL ,
 work_batch integer  default NULL ,
 origin character varying(50)    default NULL ,
 is_ingoing char(1)    default NULL ,
 priority smallint default NULL ,
 arbatch_id character varying(32)    default NULL ,
 fulltext_result character varying(10)  DEFAULT NULL,
 ocr_result character varying(10)  DEFAULT NULL,
 converter_result  character varying(10)  DEFAULT NULL,
 custom_t1  text default NULL,
 custom_n1 integer  default NULL ,
 custom_f1 numeric  default NULL ,
 custom_d1 timestamp without time zone  default NULL ,
 custom_t2 character varying(255)    default NULL ,
 custom_n2 integer  default NULL ,
 custom_f2 numeric  default NULL ,
 custom_d2 timestamp without time zone  default NULL ,
 custom_t3 character varying(255)    default NULL ,
 custom_n3 integer  default NULL ,
 custom_f3 numeric  default NULL ,
 custom_d3 timestamp without time zone  default NULL ,
 custom_t4 character varying(255)    default NULL ,
 custom_n4 integer  default NULL ,
 custom_f4 numeric  default NULL ,
 custom_d4 timestamp without time zone  default NULL ,
 custom_t5 character varying(255)    default NULL ,
 custom_n5 integer  default NULL ,
 custom_f5 numeric  default NULL ,
 custom_d5 timestamp without time zone  default NULL ,
 custom_t6 character varying(255)    default NULL ,
 custom_d6 timestamp without time zone  default NULL ,
 custom_t7 character varying(255)    default NULL ,
 custom_d7 timestamp without time zone  default NULL ,
 custom_t8 character varying(255)    default NULL ,
 custom_d8 timestamp without time zone  default NULL ,
 custom_t9 character varying(255)    default NULL ,
 custom_d9 timestamp without time zone  default NULL ,
 custom_t10 character varying(255)    default NULL ,
 custom_d10 timestamp without time zone  default NULL ,
 custom_t11 character varying(255)    default NULL ,
 custom_t12 character varying(255)    default NULL ,
 custom_t13 character varying(255)    default NULL ,
 custom_t14 character varying(255)    default NULL ,
 custom_t15 character varying(255)    default NULL ,
 tablename character varying(32)   default  'res_apa',
 initiator character varying(50)    default NULL ,
 dest_user character varying(128)    default NULL ,
 video_batch integer  default NULL ,
 video_time timestamp NULL  default NULL ,
 video_user character varying(128)    default NULL ,
 video_date timestamp without time zone,
 CONSTRAINT res_apa_pkey PRIMARY KEY (res_id)
)
WITH (OIDS=FALSE);


-- modules/attachments/sql/structure/attachments.postgresql.sql


CREATE SEQUENCE res_attachment_res_id_seq
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 1
  CACHE 1;

CREATE TABLE res_attachments
(
  res_id bigint NOT NULL DEFAULT nextval('res_attachment_res_id_seq'::regclass),
  title character varying(255) DEFAULT NULL::character varying,
  subject text,
  description text,
  publisher character varying(255) DEFAULT NULL::character varying,
  contributor character varying(255) DEFAULT NULL::character varying,
  type_id bigint ,
  format character varying(50) NOT NULL,
  typist character varying(128) NOT NULL,
  creation_date timestamp without time zone NOT NULL,
  fulltext_result character varying(10) DEFAULT NULL::character varying,
  ocr_result character varying(10) DEFAULT NULL::character varying,
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
  status character varying(10) DEFAULT NULL::character varying,
  destination character varying(50) DEFAULT NULL::character varying,
  approver character varying(50) DEFAULT NULL::character varying,
  validation_date timestamp without time zone,
  effective_date timestamp without time zone,
  work_batch bigint,
  origin character varying(50) DEFAULT NULL::character varying,
  is_ingoing character(1) DEFAULT NULL::bpchar,
  priority smallint,
  initiator character varying(50) DEFAULT NULL::character varying,
  dest_user character varying(128) DEFAULT NULL::character varying,
  coll_id character varying(32) NOT NULL,
  res_id_master bigint,
  attachment_type character varying(255) DEFAULT NULL::character varying,
  dest_contact_id bigint,
  dest_address_id bigint,
  updated_by character varying(128) DEFAULT NULL::character varying,
  is_multicontacts character(1),
  is_multi_docservers character(1) NOT NULL DEFAULT 'N'::bpchar,
  tnl_path character varying(255) DEFAULT NULL::character varying,
  tnl_filename character varying(255) DEFAULT NULL::character varying,
  CONSTRAINT res_attachments_pkey PRIMARY KEY (res_id)
)
WITH (OIDS=FALSE);


-- modules/autofoldering/sql/structure/autofoldering.postgresql.sql


CREATE TABLE af_security
(
  af_security_id bigint NOT NULL,
  af_security_label character varying(255) NOT NULL,
  group_id character varying(50) NOT NULL,
  tree_id character varying(50) NOT NULL,
  where_clause text NOT NULL,
  start_date timestamp without time zone,
  stop_date timestamp without time zone,
  CONSTRAINT af_security_pkey PRIMARY KEY (af_security_id)
)
WITH (OIDS=FALSE);

-- Filled during autofoldering load
-- If you create your on table for a new tree
-- It is very important to respect the order of fields : DO NOT PUT IDS IN THE END OF THE TABLE!!!
CREATE TABLE af_view_year_target
(
  level1 character varying(255) NOT NULL , -- Pays / Country : custom_t3
  level1_id integer NOT NULL,
  level2 character(4) ,          -- Année / Year : date_part('year', doc_date)
  level2_id integer NOT NULL,
  level3 character varying(255) ,          -- Client / Customer : custom_t4
  level3_id integer NOT NULL,
  CONSTRAINT af_view_year_target_pkey PRIMARY KEY (level1, level2, level3)
)
WITH (OIDS=FALSE);

CREATE TABLE af_view_customer_target
(
  level1 character varying(255) NOT NULL , -- 1ère lettre client / Customer 1st letter : substring(custom_t4, 1, 1)
  level1_id integer NOT NULL,
  level2 character varying(255) ,          -- Client / Customer : custom_t4
  level2_id integer NOT NULL,
  level3 character(4) ,                    -- Année / Year : date_part('year', doc_date)
  level3_id integer NOT NULL,
  CONSTRAINT af_view_customer_target_pkey PRIMARY KEY (level1, level2, level3)
)
WITH (OIDS=FALSE);


-- modules/basket/sql/structure/basket.postgresql.sql

CREATE TABLE actions_groupbaskets
(
  id_action bigint NOT NULL,
  where_clause text,
  group_id character varying(32) NOT NULL,
  basket_id character varying(32) NOT NULL,
  used_in_basketlist character(1) NOT NULL DEFAULT 'Y'::bpchar,
  used_in_action_page character(1) NOT NULL DEFAULT 'Y'::bpchar,
  default_action_list character(1) NOT NULL DEFAULT 'N'::bpchar,
  CONSTRAINT actions_groupbaskets_pkey PRIMARY KEY (id_action, group_id, basket_id)
)
WITH (OIDS=FALSE);

CREATE TABLE baskets
(
  coll_id character varying(32) NOT NULL,
  basket_id character varying(32) NOT NULL,
  basket_name character varying(255) NOT NULL,
  basket_desc character varying(255) NOT NULL,
  basket_clause text NOT NULL,
  is_generic character varying(6) NOT NULL DEFAULT 'N'::character varying,
  is_visible character(1) NOT NULL DEFAULT 'Y'::bpchar,
  is_folder_basket character (1) NOT NULL default 'N'::bpchar,
  enabled character(1) NOT NULL DEFAULT 'Y'::bpchar,
  basket_order integer,
  flag_notif character varying(1),
  except_notif text,
  CONSTRAINT baskets_pkey PRIMARY KEY (coll_id, basket_id)
)
WITH (OIDS=FALSE);

CREATE TABLE basket_persistent_mode
(
  res_id bigint,
  user_id character varying(32),
  is_persistent character varying(1)
)
WITH (
  OIDS=FALSE
);

CREATE TABLE res_mark_as_read
(
  coll_id character varying(32),
  res_id bigint,
  user_id character varying(32),
  basket_id character varying(32)
)
WITH (
  OIDS=FALSE
);

CREATE TABLE groupbasket
(
  group_id character varying(32) NOT NULL,
  basket_id character varying(32) NOT NULL,
  "sequence" integer NOT NULL DEFAULT 0,
  redirect_basketlist character varying(2048) DEFAULT NULL::character varying,
  redirect_grouplist character varying(2048) DEFAULT NULL::character varying,
  result_page character varying(255) DEFAULT 'show_list1.php'::character varying,
  can_redirect character(1) NOT NULL DEFAULT 'N'::bpchar,
  can_delete character(1) NOT NULL DEFAULT 'N'::bpchar,
  can_insert character(1) NOT NULL DEFAULT 'N'::bpchar,
  list_lock_clause text,
  sublist_lock_clause text,
  CONSTRAINT groupbasket_pkey PRIMARY KEY (group_id, basket_id)
)
WITH (OIDS=FALSE);

CREATE SEQUENCE user_abs_seq
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 1
  CACHE 1;

CREATE TABLE user_abs
(
  system_id bigint NOT NULL DEFAULT nextval('user_abs_seq'::regclass),
  user_abs character varying(128) NOT NULL,
  new_user character varying(128) NOT NULL,
  basket_id character varying(255) NOT NULL,
  basket_owner character varying(255),
  is_virtual character(1) NOT NULL DEFAULT 'N'::bpchar,
  CONSTRAINT user_abs_pkey PRIMARY KEY (system_id)
)
WITH (OIDS=FALSE);


-- modules/cases/sql/structure/cases.postgresql.sql

CREATE SEQUENCE case_id_seq
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 1
  CACHE 1;

CREATE TABLE cases
(
  case_id integer NOT NULL DEFAULT nextval('case_id_seq'::regclass),
  case_label character varying(255) NOT NULL DEFAULT ''::bpchar,
  case_description character varying(255),
  case_type character varying(32),
  case_closing_date timestamp without time zone,
  case_last_update_date timestamp without time zone NOT NULL,
  case_creation_date timestamp without time zone NOT NULL,
  case_typist character varying(128) NOT NULL DEFAULT ''::bpchar,
  case_parent integer,
  case_custom_t1 character varying(255),
  case_custom_t2 character varying(255),
  case_custom_t3 character varying(255),
  case_custom_t4 character varying(255),
  CONSTRAINT cases_pkey PRIMARY KEY (case_id)
);

CREATE TABLE cases_res
(
  case_id integer NOT NULL,
  res_id integer NOT NULL,
  CONSTRAINT cases_res_pkey PRIMARY KEY (case_id,res_id)
);



-- modules/entities/sql/structure/entities.postgresql.sql


CREATE TABLE entities
(
  entity_id character varying(32) NOT NULL,
  entity_label character varying(255),
  short_label character varying(50),
  enabled character(1) NOT NULL DEFAULT 'Y'::bpchar,
  adrs_1 character varying(255),
  adrs_2 character varying(255),
  adrs_3 character varying(255),
  zipcode character varying(32),
  city character varying(255),
  country character varying(255),
  email character varying(255),
  business_id character varying(32),
  parent_entity_id character varying(32),
  entity_type character varying(64),
  entity_path character varying(2048),
  ldap_id character varying(255),
  CONSTRAINT entities_pkey PRIMARY KEY (entity_id)
)
WITH (OIDS=FALSE);


CREATE SEQUENCE listinstance_id_seq
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 1
  CACHE 1;

CREATE TABLE listinstance
(
  listinstance_id BIGINT NOT NULL DEFAULT nextval('listinstance_id_seq'::regclass),
  coll_id character varying(50) NOT NULL,
  res_id bigint NOT NULL,
  listinstance_type character varying(50) DEFAULT 'DOC'::character varying,
  "sequence" bigint NOT NULL,
  item_id character varying(128) NOT NULL,
  item_type character varying(255) NOT NULL,
  item_mode character varying(50) NOT NULL,
  added_by_user character varying(128) NOT NULL,
  added_by_entity character varying(50) NOT NULL,
  visible character varying(1) NOT NULL DEFAULT 'Y'::bpchar,
  viewed bigint,
  difflist_type character varying(50),
  process_date timestamp without time zone,
  process_comment character varying(255),
  CONSTRAINT listinstance_pkey PRIMARY KEY (listinstance_id)
)
WITH (OIDS=FALSE);

CREATE TABLE listmodels
(
  coll_id character varying(50) NOT NULL,
  object_id character varying(50) NOT NULL,
  object_type character varying(255) NOT NULL,
  "sequence" bigint NOT NULL,
  item_id character varying(128) NOT NULL,
  item_type character varying(255) NOT NULL,
  item_mode character varying(50) NOT NULL,
  listmodel_type character varying(50) DEFAULT 'DOC'::character varying,
  title character varying(255),
  description character varying(255),
  process_comment character varying(255),
  visible character varying(1) NOT NULL DEFAULT 'Y'::bpchar
)
WITH (OIDS=FALSE);

CREATE TABLE difflist_types 
(
  difflist_type_id character varying(50) NOT NULL,
  difflist_type_label character varying(100) NOT NULL,
  difflist_type_roles TEXT,
  allow_entities character varying(1) NOT NULL DEFAULT 'N'::bpchar,
  is_system character varying(1) NOT NULL DEFAULT 'N'::bpchar,
  CONSTRAINT "difflist_types_pkey" PRIMARY KEY (difflist_type_id)
)
WITH (
    OIDS=FALSE
);

CREATE TABLE users_entities
(
  user_id character varying(128) NOT NULL,
  entity_id character varying(32) NOT NULL,
  user_role character varying(255),
  primary_entity character(1) NOT NULL DEFAULT 'N'::bpchar,
  CONSTRAINT users_entities_pkey PRIMARY KEY (user_id, entity_id)
)
WITH (OIDS=FALSE);

CREATE SEQUENCE groupbasket_redirect_system_id_seq
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 600
  CACHE 1;

CREATE TABLE groupbasket_redirect
(
  system_id integer NOT NULL DEFAULT nextval('groupbasket_redirect_system_id_seq'::regclass),
  group_id character varying(32) NOT NULL,
  basket_id character varying(32) NOT NULL,
  action_id int NOT NULL,
  entity_id character varying(32),
  keyword character varying(255),
  redirect_mode character varying(32) NOT NULL,
  CONSTRAINT groupbasket_redirect_pkey PRIMARY KEY (system_id)
)
WITH (OIDS=FALSE);

CREATE SEQUENCE email_signatures_id_seq
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 7
  CACHE 1;

CREATE TABLE users_email_signatures
(
  id bigint NOT NULL DEFAULT nextval('email_signatures_id_seq'::regclass),
  user_id character varying(255) NOT NULL,
  html_body text NOT NULL,
  title character varying NOT NULL,
  CONSTRAINT email_signatures_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);

-- modules/folder/sql/structure/folder.postgresql.sql

CREATE SEQUENCE folders_system_id_seq
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 10000
  CACHE 1;

CREATE TABLE folders
(
  folders_system_id bigint NOT NULL DEFAULT nextval('folders_system_id_seq'::regclass),
  folder_id character varying(255) NOT NULL,
  foldertype_id integer,
  parent_id bigint DEFAULT (0)::bigint,
  folder_name character varying(255) DEFAULT NULL::character varying,
  subject character varying(255) DEFAULT NULL::character varying,
  description character varying(255) DEFAULT NULL::character varying,
  author character varying(255) DEFAULT NULL::character varying,
  typist character varying(255) DEFAULT NULL::character varying,
  status character varying(50) NOT NULL DEFAULT 'FOLDNEW'::character varying,
  folder_level smallint DEFAULT (1)::smallint,
  creation_date timestamp without time zone NOT NULL,
  destination character varying(50) DEFAULT NULL,
  dest_user character varying(128) DEFAULT NULL,
  folder_out_id bigint,
  video_status character varying(10) DEFAULT NULL,
  video_user character varying(128) DEFAULT NULL,
  is_frozen character(1) NOT NULL DEFAULT 'N',
  custom_t1 character varying(255) DEFAULT NULL::character varying,
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
  custom_d11 timestamp without time zone,
  custom_t12 character varying(255) DEFAULT NULL::character varying,
  custom_d12 timestamp without time zone,
  custom_t13 character varying(255) DEFAULT NULL::character varying,
  custom_d13 timestamp without time zone,
  custom_t14 character varying(255) DEFAULT NULL::character varying,
  custom_d14 timestamp without time zone,
  custom_t15 character varying(255) DEFAULT NULL::character varying,
  is_complete character(1) DEFAULT 'N'::bpchar,
  is_folder_out character(1) DEFAULT 'N'::bpchar,
  last_modified_date timestamp without time zone,
  CONSTRAINT folders_pkey PRIMARY KEY (folders_system_id)
)
WITH (OIDS=FALSE);

CREATE TABLE folders_out (
  folder_out_id serial NOT NULL,
  folder_system_id integer NOT NULL,
  last_name character varying(255) NOT NULL,
  first_name character varying(255)  NOT NULL,
  last_name_folder_out character varying(255)  NOT NULL,
  first_name_folder_out character varying(255)  NOT NULL,
  put_out_pattern character varying(255)  NOT NULL,
  put_out_date timestamp without time zone NOT NULL,
  return_date timestamp without time zone NOT NULL,
  return_flag character(1) NOT NULL default 'N'::bpchar,
  CONSTRAINT folders_out_pkey PRIMARY KEY  (folder_out_id)
)
WITH (OIDS=FALSE);

CREATE SEQUENCE foldertype_id_id_seq
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 200
  CACHE 1;

CREATE TABLE foldertypes
(
  foldertype_id  bigint NOT NULL DEFAULT nextval('foldertype_id_id_seq'::regclass),
  foldertype_label character varying(255) NOT NULL,
  maarch_comment text,
  retention_time character varying(50),
  custom_d1 character varying(10) DEFAULT '0000000000'::character varying,
  custom_f1 character varying(10) DEFAULT '0000000000'::character varying,
  custom_n1 character varying(10) DEFAULT '0000000000'::character varying,
  custom_t1 character varying(10) DEFAULT '0000000000'::character varying,
  custom_d2 character varying(10) DEFAULT '0000000000'::character varying,
  custom_f2 character varying(10) DEFAULT '0000000000'::character varying,
  custom_n2 character varying(10) DEFAULT '0000000000'::character varying,
  custom_t2 character varying(10) DEFAULT '0000000000'::character varying,
  custom_d3 character varying(10) DEFAULT '0000000000'::character varying,
  custom_f3 character varying(10) DEFAULT '0000000000'::character varying,
  custom_n3 character varying(10) DEFAULT '0000000000'::character varying,
  custom_t3 character varying(10) DEFAULT '0000000000'::character varying,
  custom_d4 character varying(10) DEFAULT '0000000000'::character varying,
  custom_f4 character varying(10) DEFAULT '0000000000'::character varying,
  custom_n4 character varying(10) DEFAULT '0000000000'::character varying,
  custom_t4 character varying(10) DEFAULT '0000000000'::character varying,
  custom_d5 character varying(10) DEFAULT '0000000000'::character varying,
  custom_f5 character varying(10) DEFAULT '0000000000'::character varying,
  custom_n5 character varying(10) DEFAULT '0000000000'::character varying,
  custom_t5 character varying(10) DEFAULT '0000000000'::character varying,
  custom_d6 character varying(10) DEFAULT '0000000000'::character varying,
  custom_t6 character varying(10) DEFAULT '0000000000'::character varying,
  custom_d7 character varying(10) DEFAULT '0000000000'::character varying,
  custom_t7 character varying(10) DEFAULT '0000000000'::character varying,
  custom_d8 character varying(10) DEFAULT '0000000000'::character varying,
  custom_t8 character varying(10) DEFAULT '0000000000'::character varying,
  custom_d9 character varying(10) DEFAULT '0000000000'::character varying,
  custom_t9 character varying(10) DEFAULT '0000000000'::character varying,
  custom_d10 character varying(10) DEFAULT '0000000000'::character varying,
  custom_t10 character varying(10) DEFAULT '0000000000'::character varying,
  custom_t11 character varying(10) DEFAULT '0000000000'::character varying,
  custom_t12 character varying(10) DEFAULT '0000000000'::character varying,
  custom_t13 character varying(10) DEFAULT '0000000000'::character varying,
  custom_t14 character varying(10) DEFAULT '0000000000'::character varying,
  custom_t15 character varying(10) DEFAULT '0000000000'::character varying,
  coll_id character varying(32),
  CONSTRAINT foldertypes_pkey PRIMARY KEY (foldertype_id)
)
WITH (OIDS=FALSE);

CREATE TABLE foldertypes_doctypes
(
  foldertype_id integer NOT NULL,
  doctype_id integer NOT NULL,
  CONSTRAINT foldertypes_doctypes_pkey PRIMARY KEY (foldertype_id, doctype_id)
)
WITH (OIDS=FALSE);

CREATE TABLE foldertypes_doctypes_level1
(
  foldertype_id integer NOT NULL,
  doctypes_first_level_id integer NOT NULL,
  CONSTRAINT foldertypes_doctypes_level1_pkey PRIMARY KEY (foldertype_id, doctypes_first_level_id)
)
WITH (OIDS=FALSE);

CREATE TABLE foldertypes_indexes
(
  foldertype_id bigint NOT NULL,
  field_name character varying(255) NOT NULL,
  mandatory character(1) NOT NULL DEFAULT 'N'::bpchar,
  CONSTRAINT foldertypes_indexes_pkey PRIMARY KEY (foldertype_id, field_name)
)
WITH (OIDS=FALSE);


-- modules/full_text/sql/structure/full_text.postgresql.sql

CREATE TABLE fulltext
(
  coll_id character varying(32) NOT NULL,
  res_id bigint NOT NULL,
  text_type character varying(10) NOT NULL DEFAULT 'CON'::character varying,
  fulltext_content text,
  CONSTRAINT coll_id_res_id PRIMARY KEY (coll_id, res_id)
)
WITH (
  OIDS=FALSE
);


-- modules/life_cycle/sql/structure/life_cycle.postgresql.sql

CREATE TABLE lc_policies
(
   policy_id character varying(32) NOT NULL,
   policy_name character varying(255) NOT NULL,
   policy_desc character varying(255) NOT NULL,
   CONSTRAINT lc_policies_pkey PRIMARY KEY (policy_id)
)
WITH (OIDS = FALSE);


CREATE TABLE lc_cycles
(
   policy_id character varying(32) NOT NULL,
   cycle_id character varying(32) NOT NULL,
   cycle_desc character varying(255) NOT NULL,
   sequence_number integer NOT NULL,
   where_clause text,
   break_key character varying(255) DEFAULT NULL,
   validation_mode character varying(32) NOT NULL,
   CONSTRAINT lc_cycle_pkey PRIMARY KEY (policy_id, cycle_id)
)
WITH (OIDS = FALSE);

CREATE TABLE lc_cycle_steps
(
   policy_id character varying(32) NOT NULL,
   cycle_id character varying(32) NOT NULL,
   cycle_step_id character varying(32) NOT NULL,
   cycle_step_desc character varying(255) NOT NULL,
   docserver_type_id character varying(32) NOT NULL,
   is_allow_failure character(1) NOT NULL DEFAULT 'N'::bpchar,
   step_operation character varying(32) NOT NULL,
   sequence_number integer NOT NULL,
   is_must_complete character(1) NOT NULL DEFAULT 'N'::bpchar,
   preprocess_script character varying(255) DEFAULT NULL,
   postprocess_script character varying(255) DEFAULT NULL,
   CONSTRAINT lc_cycle_steps_pkey PRIMARY KEY (policy_id, cycle_id, cycle_step_id, docserver_type_id)
)
WITH (OIDS = FALSE);

CREATE TABLE lc_stack
(
   policy_id character varying(32) NOT NULL,
   cycle_id character varying(32) NOT NULL,
   cycle_step_id character varying(32) NOT NULL,
   coll_id character varying(32) NOT NULL,
   res_id bigint NOT NULL,
   cnt_retry integer DEFAULT NULL,
   status character(1) NOT NULL,
   work_batch bigint,
   regex character varying(32),
   CONSTRAINT lc_stack_pkey PRIMARY KEY (policy_id, cycle_id, cycle_step_id, res_id)
)
WITH (OIDS = FALSE);



-- modules/notes/sql/structure/notes.postgresql.sql

CREATE SEQUENCE notes_seq
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 20
  CACHE 1;


CREATE TABLE notes
(
  id bigint NOT NULL DEFAULT nextval('notes_seq'::regclass),
  identifier bigint NOT NULL,
  tablename character varying(50),
  user_id character varying(128) NOT NULL,
  date_note timestamp without time zone NOT NULL,
  note_text text NOT NULL,
  coll_id character varying(50),
  CONSTRAINT notes_pkey PRIMARY KEY (id)
)
WITH (OIDS=FALSE);


CREATE SEQUENCE notes_entities_id_seq
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 20
  CACHE 1;


CREATE TABLE note_entities
(
  id bigint NOT NULL DEFAULT nextval('notes_entities_id_seq'::regclass),
  note_id bigint NOT NULL,
  item_id character varying(50),
  CONSTRAINT note_entities_pkey PRIMARY KEY (id)
)
WITH (OIDS=FALSE);



-- modules/notes/sql/structure/notifications.postgresql.sql
CREATE SEQUENCE notifications_seq
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 100
  CACHE 1;

CREATE TABLE notifications
(
  notification_sid bigint NOT NULL DEFAULT nextval('notifications_seq'::regclass),
  notification_id character varying(50) NOT NULL,
  description character varying(255),
  is_enabled character varying(1) NOT NULL default 'Y'::bpchar,
  event_id character varying(255) NOT NULL,
  notification_mode character varying(30) NOT NULL,
  template_id bigint,
  rss_url_template text,
  diffusion_type character varying(50) NOT NULL,
  diffusion_properties character varying(255),
  attachfor_type character varying(50),
  attachfor_properties character varying(2048),
  CONSTRAINT notifications_pkey PRIMARY KEY (notification_sid)
)
WITH (
  OIDS=FALSE
);


CREATE SEQUENCE notif_event_stack_seq
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 1
  CACHE 1;

 -- DROP TABLE notif_event_stack
CREATE TABLE notif_event_stack
(
  event_stack_sid bigint NOT NULL DEFAULT nextval('notif_event_stack_seq'::regclass),
  notification_sid bigint NOT NULL,
  table_name character varying(50) NOT NULL,
  record_id character varying(50) NOT NULL,
  user_id character varying(128) NOT NULL,
  event_info character varying(255) NOT NULL,
  event_date timestamp without time zone NOT NULL,
  exec_date timestamp without time zone,
  exec_result character varying(50),
  CONSTRAINT notif_event_stack_pkey PRIMARY KEY (event_stack_sid)
)
WITH (
  OIDS=FALSE
);

CREATE SEQUENCE notif_email_stack_seq
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 1
  CACHE 1;

 -- DROP TABLE notif_email_stack
CREATE TABLE notif_email_stack
(
  email_stack_sid bigint NOT NULL DEFAULT nextval('notif_email_stack_seq'::regclass),
  sender character varying(255) NOT NULL,
  reply_to character varying(255),
  recipient character varying(2000) NOT NULL,
  cc character varying(2000),
  bcc character varying(2000),
  subject character varying(255),
  html_body text,
  text_body text,
  charset character varying(50) NOT NULL,
  attachments character varying(2000),
  module character varying(50) NOT NULL,
  exec_date timestamp without time zone,
  exec_result character varying(50),
  CONSTRAINT notif_email_stack_pkey PRIMARY KEY (email_stack_sid)
)
WITH (
  OIDS=FALSE
);


CREATE SEQUENCE notif_rss_stack_seq
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 1
  CACHE 1;
  
CREATE TABLE notif_rss_stack
(
  rss_stack_sid bigint NOT NULL DEFAULT nextval('notif_rss_stack_seq'::regclass),
  rss_user_id character varying(128) NOT NULL,
  rss_event_stack_sid bigint NOT NULL,
  rss_event_url text,
  CONSTRAINT notif_rss_stack_pkey PRIMARY KEY (rss_stack_sid )
)
WITH (
  OIDS=FALSE
);

-- modules/physical_archive/sql/structure/physical_archive.postgresql.sql

create or replace function update_the_db() returns void as
$$
begin

    if not exists(select * from information_schema.tables where table_name = 'ar_boxes') then

      CREATE TABLE ar_boxes (
      arbox_id serial NOT NULL,
      title character varying(255)  DEFAULT NULL,
      subject character varying(255)  DEFAULT NULL,
      description text ,
      entity_id character varying(32)  DEFAULT NULL,
      arcontainer_id integer NOT NULL,
      status character varying(3)  DEFAULT NULL,
      creation_date  timestamp without time zone DEFAULT NULL,
      retention_time character varying(50)  DEFAULT NULL,
      custom_t1 character varying(3)  DEFAULT NULL,
      custom_n1 integer,
      custom_f1 numeric,
      custom_d1 timestamp without time zone DEFAULT NULL,
      custom_t2 character varying(3)  DEFAULT NULL,
      custom_n2 integer,
      custom_f2 numeric,
      custom_d2 timestamp without time zone DEFAULT NULL,
      custom_t3 character varying(50)  DEFAULT NULL,
      custom_n3 integer,
      custom_f3 numeric,
      custom_d3 timestamp without time zone DEFAULT NULL,
      custom_t4 character varying(50)  DEFAULT NULL,
      custom_n4 integer,
      custom_f4 numeric,
      custom_d4 timestamp without time zone DEFAULT NULL,
      custom_t5 character varying(255)  DEFAULT NULL,
      custom_n5 integer,
      custom_f5 numeric,
      custom_d5 timestamp without time zone DEFAULT NULL,
      custom_t6 character varying(255)  DEFAULT NULL,
      custom_t7 character varying(255)  DEFAULT NULL,
      custom_t8 character varying(255)  DEFAULT NULL,
      custom_t9 character varying(255)  DEFAULT NULL,
      custom_t10 character varying(255)  DEFAULT NULL,
      custom_t11 character varying(255)  DEFAULT NULL,
      CONSTRAINT ar_boxes_pkey PRIMARY KEY  (arbox_id)
    ) ;

    end if;

end;
$$
language 'plpgsql';

select update_the_db();
drop function update_the_db();


create or replace function update_the_db() returns void as
$$
begin

    if not exists(select * from information_schema.tables where table_name = 'ar_containers') then

        CREATE TABLE ar_containers
    (
      arcontainer_id serial NOT NULL ,
      arcontainer_desc character varying(255)  DEFAULT NULL,
      status character varying(3)  DEFAULT NULL,
      ctype_id character varying(32)  DEFAULT NULL,
      position_id bigint  DEFAULT NULL,
      creation_date timestamp without time zone DEFAULT NULL,
      entity_id character varying(32)  DEFAULT NULL,
      retention_time character varying(50)  DEFAULT NULL,
      custom_t1 character varying(50)  DEFAULT NULL,
      custom_n1 integer,
      custom_f1 numeric,
      custom_d1 timestamp without time zone DEFAULT NULL,
      custom_t2 character varying(3)  DEFAULT NULL,
      custom_n2 integer,
      custom_f2 numeric,
      custom_d2 timestamp without time zone DEFAULT NULL,
      CONSTRAINT ar_containers_pkey PRIMARY KEY  (arcontainer_id)
    ) ;

    end if;

end;
$$
language 'plpgsql';

select update_the_db();
drop function update_the_db();

CREATE TABLE ar_batch
(
  arbatch_id serial NOT NULL ,
  title character varying(255)  DEFAULT NULL,
  subject character varying(255)  DEFAULT NULL,
  description text,
  arbox_id bigint,
  status character varying(3)  DEFAULT NULL,
  creation_date timestamp without time zone DEFAULT NULL,
  retention_time character varying(50)  DEFAULT NULL,
  custom_t1 character varying(3)  DEFAULT NULL,
  custom_n1 integer,
  custom_f1 numeric,
  custom_d1 timestamp without time zone DEFAULT NULL,
  custom_t2 character varying(3)  DEFAULT NULL,
  custom_n2 integer,
  custom_f2 numeric,
  custom_d2 timestamp without time zone DEFAULT NULL,
  custom_t3 character varying(50)  DEFAULT NULL,
  custom_n3 integer,
  custom_f3 numeric,
  custom_d3 timestamp without time zone DEFAULT NULL,
  custom_t4 character varying(50)  DEFAULT NULL,
  custom_n4 integer,
  custom_f4 numeric,
  custom_d4 timestamp without time zone DEFAULT NULL,
  custom_t5 character varying(255)  DEFAULT NULL,
  custom_n5 integer,
  custom_f5 numeric,
  custom_d5 timestamp without time zone DEFAULT NULL,
  custom_t6 character varying(255)  DEFAULT NULL,
  custom_t7 character varying(255)  DEFAULT NULL,
  custom_t8 character varying(255)  DEFAULT NULL,
  custom_t9 character varying(255)  DEFAULT NULL,
  custom_t10 character varying(255)  DEFAULT NULL,
  custom_t11 character varying(255)  DEFAULT NULL,
  CONSTRAINT ar_batch_pkey PRIMARY KEY  (arbatch_id)
) ;


-- modules/postindexing/sql/structure/postindexing.postgresql.sql



-- modules/reports/sql/structure/reports.postgresql.sql

CREATE TABLE usergroups_reports
(
  group_id character varying(32) NOT NULL,
  report_id character varying(50) NOT NULL,
  CONSTRAINT usergroups_reports_pkey PRIMARY KEY (group_id, report_id)
)
WITH (OIDS=FALSE);


-- modules/templates/sql/structure/templates.postgresql.sql


CREATE SEQUENCE templates_seq
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 110
  CACHE 1;

CREATE SEQUENCE templates_association_seq
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 140
  CACHE 1;

CREATE TABLE templates
(
  template_id bigint NOT NULL DEFAULT nextval('templates_seq'::regclass),
  template_label character varying(255) DEFAULT NULL::character varying,
  template_comment character varying(255) DEFAULT NULL::character varying,
  template_content text,
  template_type character varying(32) NOT NULL DEFAULT 'HTML'::character varying,
  template_path character varying(255),
  template_file_name character varying(255),
  template_style character varying(255),
  template_datasource character varying(32),
  template_target character varying(255),
  template_attachment_type character varying(255) DEFAULT NULL::character varying,
  CONSTRAINT templates_pkey PRIMARY KEY (template_id)
)
WITH (OIDS=FALSE);

CREATE TABLE templates_association
(
  system_id bigint NOT NULL DEFAULT nextval('templates_association_seq'::regclass),
  template_id bigint NOT NULL,
  what character varying(255) NOT NULL,
  value_field character varying(255) NOT NULL,
  maarch_module character varying(255) NOT NULL DEFAULT 'apps'::character varying,
  CONSTRAINT templates_association_pkey PRIMARY KEY (system_id)
)
WITH (
  OIDS=FALSE
);


CREATE TABLE templates_doctype_ext
(
  template_id bigint DEFAULT NULL,
  type_id integer NOT NULL,
  is_generated character(1) NOT NULL DEFAULT 'N'::bpchar
)
WITH (OIDS=FALSE);


-- apps/maarch_entreprise/sql/structure/apps.postgresql.sql

CREATE SEQUENCE contact_id_seq
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 200
  CACHE 1;

CREATE TABLE contacts (
contact_id bigint NOT NULL DEFAULT nextval('contact_id_seq'::regclass),
lastname character varying(255),
firstname character varying(255),
society character varying(255),
function character varying(255),
address_num character varying(32)  ,
address_street character varying(255),
address_complement character varying(255),
address_town character varying(255),
address_postal_code character varying(255),
address_country character varying(255),
email character varying(255),
phone character varying(20),
other_data text  ,
is_corporate_person character(1) NOT NULL DEFAULT 'Y'::bpchar,
user_id character varying(128),
title character varying(255),
business_id character varying(255),
ref_identifier character varying(255),
acc_number character varying(50),
entity_id character varying(32),
contact_type character varying(255) NOT NULL DEFAULT 'letter'::character varying,
enabled character(1) NOT NULL DEFAULT 'Y'::bpchar,
is_private character varying(1) NOT NULL DEFAULT 'N'::character varying,
CONSTRAINT contacts_pkey PRIMARY KEY  (contact_id)
) WITH (OIDS=FALSE);

CREATE SEQUENCE query_id_seq
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 10
  CACHE 1;

  -- multicontacts
CREATE TABLE contacts_res
(
  coll_id character varying(32) NOT NULL,
  res_id bigint NOT NULL,
  contact_id character varying(128) NOT NULL,
  address_id bigint NOT NULL,
  mode character varying NOT NULL DEFAULT 'multi'::character varying
 );

-- contacts v2
CREATE SEQUENCE contact_types_id_seq
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 200
  CACHE 1;

CREATE TABLE contact_types 
(
  id bigint NOT NULL DEFAULT nextval('contact_types_id_seq'::regclass),
  label character varying(255) NOT NULL,
  can_add_contact character varying(1) NOT NULL DEFAULT 'Y'::character varying,
  contact_target character varying(50),
  CONSTRAINT contact_types_pkey PRIMARY KEY  (id)
) WITH (OIDS=FALSE);

CREATE SEQUENCE contact_v2_id_seq
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 100
  CACHE 1;

CREATE TABLE contacts_v2 
(
  contact_id bigint NOT NULL DEFAULT nextval('contact_v2_id_seq'::regclass),
  contact_type bigint NOT NULL,
  is_corporate_person character(1) DEFAULT 'Y'::bpchar,
  society character varying(255),
  society_short character varying(32),
  firstname character varying(255),
  lastname character varying(255),
  title character varying(255),
  function character varying(255),
  other_data character varying(255),
  user_id character varying(255) NOT NULL,
  entity_id character varying(32) NOT NULL,
  creation_date timestamp without time zone NOT NULL,
  update_date timestamp without time zone,
  enabled character varying(1) NOT NULL DEFAULT 'Y'::bpchar,
  CONSTRAINT contacts_v2_pkey PRIMARY KEY  (contact_id)
) WITH (OIDS=FALSE);

CREATE SEQUENCE contact_purposes_id_seq
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 100
  CACHE 1;

CREATE TABLE contact_purposes 
(
  id bigint NOT NULL DEFAULT nextval('contact_purposes_id_seq'::regclass),
  label character varying(255) NOT NULL,
  CONSTRAINT contact_purposes_pkey PRIMARY KEY  (id)
) WITH (OIDS=FALSE);

CREATE SEQUENCE contact_addresses_id_seq
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 100
  CACHE 1;

CREATE TABLE contact_addresses 
(
  id bigint NOT NULL DEFAULT nextval('contact_addresses_id_seq'::regclass),
  contact_id bigint NOT NULL,
  contact_purpose_id bigint DEFAULT 1,
  departement character varying(255),
  firstname character varying(255),
  lastname character varying(255),
  title character varying(255),
  function character varying(255),
  occupancy character varying(1024),
  address_num character varying(32)  ,
  address_street character varying(255),
  address_complement character varying(255),
  address_town character varying(255),
  address_postal_code character varying(255),
  address_country character varying(255),
  phone character varying(20),
  email character varying(255),
  website character varying(255),
  salutation_header character varying(255),
  salutation_footer character varying(255),
  other_data character varying(255),
  user_id character varying(255) NOT NULL,
  entity_id character varying(32) NOT NULL,
  is_private character(1) NOT NULL DEFAULT 'N'::bpchar,
  enabled character varying(1) NOT NULL DEFAULT 'Y'::bpchar,
  CONSTRAINT contact_addresses_pkey PRIMARY KEY  (id)
) WITH (OIDS=FALSE);

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
  START 200
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
  START 200
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

-- Table: rp_history

CREATE SEQUENCE rp_history_id_seq
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 1
  CACHE 1;

CREATE TABLE rp_history
(
  system_id bigint NOT NULL DEFAULT nextval('rp_history_id_seq'::regclass),
  table_name character varying(32) NOT NULL,
  rp_cycle bigint NOT NULL,
  start_res_id bigint NOT NULL,
  stop_res_id bigint NOT NULL,
  start_date timestamp without time zone NOT NULL,
  stop_date timestamp without time zone NOT NULL,
  res_count bigint NOT NULL,
  fail_count bigint NOT NULL,
  CONSTRAINT rp_history_pkey PRIMARY KEY (system_id)
)
WITH (
  OIDS=FALSE
);

CREATE TABLE tags
(
  tag_label character varying(50) NOT NULL,
  coll_id character varying(50) NOT NULL,
  res_id bigint NOT NULL,
  CONSTRAINT tagsjoin_pkey PRIMARY KEY (tag_label, coll_id, res_id )
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
  reference_number character varying(255) DEFAULT NULL::character varying,
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
  locker_user_id character varying(255) DEFAULT NULL::character varying,
  locker_time timestamp without time zone,
  tnl_path character varying(255) DEFAULT NULL::character varying,
  tnl_filename character varying(255) DEFAULT NULL::character varying,
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
  modification_date timestamp without time zone DEFAULT NOW(),
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
  reference_number character varying(255) DEFAULT NULL::character varying,
  tablename character varying(32) DEFAULT 'res_letterbox'::character varying,
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
  locker_user_id character varying(255) DEFAULT NULL::character varying,
  locker_time timestamp without time zone,
  confidentiality character(1),
  tnl_path character varying(255) DEFAULT NULL::character varying,
  tnl_filename character varying(255) DEFAULT NULL::character varying,
  CONSTRAINT res_letterbox_pkey PRIMARY KEY  (res_id)
)
WITH (OIDS=FALSE);

CREATE SEQUENCE res_linked_mlb_seq
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 171
  CACHE 1;

CREATE TABLE res_linked
(
  id bigint NOT NULL DEFAULT nextval('res_linked_mlb_seq'::regclass),
  res_parent bigint NOT NULL,
  res_child bigint NOT NULL,
  coll_id character varying(50) NOT NULL,
  CONSTRAINT res_linked_primary PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);

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
  sve_start_date timestamp without time zone default NULL,
  sve_identifier character varying(255)  default NULL,
  answer_type_bitmask character varying(7)  default NULL,
  other_answer_desc character varying(255)  DEFAULT NULL::character varying,
  process_limit_date timestamp without time zone default NULL,
  recommendation_limit_date timestamp without time zone default NULL,
  process_notes text,
  closing_date timestamp without time zone default NULL,
  alarm1_date timestamp without time zone default NULL,
  alarm2_date timestamp without time zone default NULL,
  flag_notif char(1)  default 'N'::character varying ,
  flag_alarm1 char(1)  default 'N'::character varying ,
  flag_alarm2 char(1) default 'N'::character varying,
  is_multicontacts char(1),
  address_id bigint
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
  tnl_path character varying(255) DEFAULT NULL::character varying,
  tnl_filename character varying(255) DEFAULT NULL::character varying,
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
  tnl_path character varying(255) DEFAULT NULL::character varying,
  tnl_filename character varying(255) DEFAULT NULL::character varying,
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
  process_mode character varying(255),
  CONSTRAINT type_id PRIMARY KEY (type_id)
)
WITH (OIDS=FALSE);

CREATE TABLE doctypes_indexes
(
  type_id bigint NOT NULL,
  coll_id character varying(32) NOT NULL,
  field_name character varying(255) NOT NULL,
  mandatory character(1) NOT NULL DEFAULT 'N'::bpchar,
  CONSTRAINT doctypes_indexes_pkey PRIMARY KEY (type_id, coll_id, field_name)
)
WITH (OIDS=FALSE);

CREATE TABLE groupbasket_status
(
  system_id serial NOT NULL,
  group_id character varying(32) NOT NULL,
  basket_id character varying(32) NOT NULL,
  action_id integer NOT NULL,
  status_id character varying(32),
  CONSTRAINT groupbasket_status_pkey PRIMARY KEY (system_id)
)
WITH (
  OIDS=FALSE
);

CREATE TABLE groupbasket_difflist_types
(
  system_id serial NOT NULL,
  group_id character varying(32) NOT NULL,
  basket_id character varying(32) NOT NULL,
  action_id integer NOT NULL,
  difflist_type_id character varying(50) NOT NULL,
  CONSTRAINT groupbasket_difflist_types_pkey PRIMARY KEY (system_id )
)
WITH (
  OIDS=FALSE
);

CREATE TABLE groupbasket_difflist_roles
(
  system_id serial NOT NULL,
  group_id character varying(32) NOT NULL,
  basket_id character varying(32) NOT NULL,
  action_id integer NOT NULL,
  difflist_role_id character varying(50) NOT NULL,
  CONSTRAINT groupbasket_difflist_roles_pkey PRIMARY KEY (system_id)
)
WITH (
  OIDS=FALSE
);

-- ************************************************************************* --
--                                                                           --
--                  RECORDS MANAGEMENT V1.0 DATABASE SCHEMA                  --
--                                                                           --
-- ************************************************************************* --

-- ************************************************************************* --
--                               DATA TABLES                                 --
-- ************************************************************************* --

-- ************************************************************************* --
--                             MESSAGES - IOS                                --
-- ************************************************************************* --
-- Records Management Messages
DROP TABLE IF EXISTS rm_ios CASCADE;
CREATE TABLE rm_ios
(
  io_id SERIAL NOT NULL,
  io_type character varying(100) NOT NULL,
  io_status character varying(10) NOT NULL,
  docserver_id character varying(32) NOT NULL DEFAULT 'IOS',
  io_path character varying(512),
  io_filename character varying(255),
  
  date timestamp without time zone NOT NULL,
  reply_code character varying(50),
  operation_date timestamp without time zone default null,
  related_identifier character varying(100),
  identifier character varying(100) NOT NULL,
  reference_identifier character varying(100),
    
  CONSTRAINT "rm_ios_pkey" PRIMARY KEY (io_id)
)
WITH (
    OIDS=FALSE
);

-- Message comments
DROP TABLE IF EXISTS rm_comments CASCADE; 
CREATE TABLE rm_comments
(
  comment_id SERIAL,
  io_id bigint NOT NULL,
  
  comment text NOT NULL,
  date timestamp without time zone,
  author character varying(255),
  
  CONSTRAINT "rm_comments_pkey" PRIMARY KEY (comment_id)
)
WITH (
    OIDS=FALSE
);

-- ************************************************************************* --
--                           ARCHIVES AND OBJECTS                            --
-- ************************************************************************* --
-- Archives and ArchiveObjects
DROP TABLE IF EXISTS rm_items CASCADE;
CREATE TABLE rm_items
(
  item_id SERIAL,
  item_type character varying(50),
  parent_item_id bigint,
  schedule_id bigint,
  
  archival_agency_item_identifier character varying(100),
  archival_agreement character varying(100),
  archival_profile character varying(100),
  description_language text NOT NULL default 'fra',
  name text NOT NULL,
  originating_agency_item_identifier character varying(100),
  service_level text,
  transferring_agency_item_identifier character varying(100),
  
  CONSTRAINT "rm_items_pkey" PRIMARY KEY (item_id)
)
WITH (
    OIDS=FALSE
);

-- Archives and ArchiveObjects ContentDescription
DROP TABLE IF EXISTS rm_content_descriptions CASCADE;
CREATE TABLE rm_content_descriptions
(
  item_id bigint NOT NULL,
  
  description text,
  description_level character varying(50) NOT NULL DEFAULT 'recordgrp',
  file_plan_position text,
  language text NOT NULL DEFAULT 'fra',
  latest_date date,
  oldest_date date,
  other_descriptive_data text,
  
  CONSTRAINT "rm_content_descriptions_pkey" PRIMARY KEY (item_id)
)
WITH (
    OIDS=FALSE
);

-- ContentDescription CustodialHistory
DROP TABLE IF EXISTS rm_custodial_history CASCADE;
CREATE TABLE rm_custodial_history
(
  item_id bigint NOT NULL,
  "when" date,
  custodial_history_item text NOT NULL,
  
  CONSTRAINT "rm_custodial_history_pkey" PRIMARY KEY (item_id)
)
WITH (
    OIDS=FALSE
);


-- Archives and ArchiveObjects Appraisal Rules
DROP TABLE IF EXISTS rm_appraisal_rules CASCADE;
CREATE TABLE rm_appraisal_rules
(
  appraisal_rule_id SERIAL,
  parent_id bigint NOT NULL,
  parent_type character varying(50) NOT NULL,
  
  code character varying(50),
  duration integer,
  start_date date,
  
  CONSTRAINT "rm_appraisal_rules_pkey" PRIMARY KEY (appraisal_rule_id)
)
WITH (
    OIDS=FALSE
);

-- Archives and ArchiveObjects Access Restriction Rules
DROP TABLE IF EXISTS rm_access_restriction_rules CASCADE;
CREATE TABLE rm_access_restriction_rules
(
  access_restriction_rule_id SERIAL,
  parent_id bigint NOT NULL,
  parent_type character varying(50) NOT NULL,
  
  code character varying(50),
  start_date date,
  
  CONSTRAINT "rm_access_restriction_rules_pkey" PRIMARY KEY (access_restriction_rule_id)
)
WITH (
    OIDS=FALSE
);

-- Documents
DROP TABLE IF EXISTS rm_documents CASCADE;
CREATE TABLE rm_documents
(
  res_id SERIAL,
  coll_id character varying(32),
  docserver_id character varying(32) NOT NULL,
  path character varying(255) DEFAULT NULL,
  filename character varying(255) DEFAULT NULL,
  type_id bigint NOT NULL,
  item_id bigint,
  format character varying(50) NOT NULL,
  typist character varying(128) NOT NULL,
  offset_doc character varying(255) DEFAULT NULL,
  logical_adr character varying(255) DEFAULT NULL,
  policy_id character varying(32) DEFAULT NULL,
  cycle_id character varying(32) DEFAULT NULL,
  cycle_date timestamp without time zone,
  is_multi_docservers character(1) NOT NULL DEFAULT 'N'::bpchar,
  is_frozen character(1) NOT NULL DEFAULT 'N'::bpchar,
  publisher character varying(255) DEFAULT NULL::character varying,
  contributor character varying(255) DEFAULT NULL::character varying,
  author character varying(255) DEFAULT NULL::character varying,
  author_name text,
  identifier character varying(255) DEFAULT NULL::character varying,
  source character varying(255) DEFAULT NULL::character varying,
  coverage character varying(255) DEFAULT NULL::character varying,
  destination character varying(50) DEFAULT NULL::character varying,
  approver character varying(50) DEFAULT NULL::character varying,
  
  archival_agency_document_identifier character varying(100),
  copy character varying(1),
  creation_date timestamp without time zone,
  doc_date timestamp without time zone,
  description text, 
  fingerprint character varying(64),
  issue timestamp without time zone,
  doc_language text not null default 'fra'::bpchar,
  originating_agency_document_identifier character varying(100),
  subject text,
  receipt timestamp without time zone,
  response timestamp without time zone,
  filesize bigint default 0,
  unit_code character varying(10) default 'A99',
  status character varying(50),
  submission timestamp without time zone,
  transferring_agency_document_identifier character varying(100),
  content_type character varying(10) DEFAULT 'CDO',
  
  arbox_id character varying(32) DEFAULT NULL::character varying,
  arbatch_id bigint DEFAULT NULL,
  
  CONSTRAINT "rm_documents_pkey" PRIMARY KEY (res_id)
)
WITH (
    OIDS=FALSE
);

DROP TABLE IF EXISTS adr_rm CASCADE;
CREATE TABLE adr_rm
(
  res_id bigint NOT NULL,
  docserver_id character varying(32) NOT NULL,
  path character varying(255) DEFAULT NULL::character varying,
  filename character varying(255) DEFAULT NULL::character varying,
  offset_doc character varying(255) DEFAULT NULL::character varying,
  fingerprint character varying(255) DEFAULT NULL::character varying,
  adr_priority integer NOT NULL,
  CONSTRAINT adr_rm_pkey PRIMARY KEY (res_id, docserver_id)
)
WITH (OIDS=FALSE);

-- Archives and ArchiveObjects Keywords
DROP TABLE IF EXISTS rm_keywords CASCADE;
CREATE TABLE rm_keywords
(
  keyword_id SERIAL,
  item_id bigint,
  
  keyword_content text NOT NULL,
  role character varying(50),
  keyword_reference character varying(50),
  keyword_type character varying(50),
  
  CONSTRAINT "rm_keywords_pkey" PRIMARY KEY (keyword_id)
)
WITH (
    OIDS=FALSE
);

-- ************************************************************************* --
--                            ORGANIZATIONS                                  --
-- ************************************************************************* --
-- Organizations
DROP TABLE IF EXISTS rm_organizations CASCADE;
CREATE TABLE rm_organizations
(
    organization_id SERIAL,
    parent_id bigint NOT NULL, -- Id of parent
    parent_type character varying(50) NOT NULL, -- ArchiveTransfer, Archive, ArchiveObject, ArchiveTransferReply
    role character varying(50) NOT NULL,  -- TransferringAgency, ArchivalAgency, OriginatingAgency, Repository, ControlAuthority
    entity_id character varying(32), -- Entity_id if related to maarch entity
    
    business_type character varying(50),
    description character varying(255),
    identification character varying(100) NOT NULL,
    legal_classification character varying(50),
    name text,
    CONSTRAINT "rm_organizations_pkey" PRIMARY KEY (organization_id)
)
WITH (
    OIDS=FALSE
);

-- Addresses
DROP TABLE IF EXISTS rm_addresses CASCADE;
CREATE TABLE rm_addresses
(
    address_id SERIAL,
    parent_id bigint NOT NULL, 
    parent_type character varying(50), -- TransferringAgency, Contact...
    entity_id character varying(32), -- Used if refAddress
    user_id character varying(128), -- Used if refAddress
    
    block_name character varying(255),
    building_name character varying(255),
    building_number character varying(255),
    city_name character varying(255),
    city_sub_division_name character varying(255),
    country character varying(50),
    floor_identification character varying(255),
    postcode character varying(50),
    post_office_box character varying(255),
    room_identification character varying(255),
    street_name character varying(255),

    CONSTRAINT "rm_addresses_pkey" PRIMARY KEY (address_id)
)
WITH (
    OIDS=FALSE
);

-- Contacts
DROP TABLE IF EXISTS rm_contacts CASCADE;
CREATE TABLE rm_contacts
(
    contact_id SERIAL,
    organization_id bigint NOT NULL,
    user_id character varying(128), -- User_id of related to Maarch user
    
    department_name character varying(255),
    identification character varying(100),
    person_name character varying(255),
    responsibility character varying(255),
    
    CONSTRAINT "rm_contacts_pkey" PRIMARY KEY (contact_id)
)
WITH (
    OIDS=FALSE
);

-- ************************************************************************* --
--                                RELATIONS                                  --
-- ************************************************************************* --
-- Relation between ios and archives
DROP TABLE IF EXISTS rm_io_archives_relations CASCADE;
CREATE TABLE rm_io_archives_relations
(
    io_id bigint NOT NULL,
    item_id bigint NOT NULL,
    
    CONSTRAINT "rm_io_archives_relations_pkey" PRIMARY KEY (io_id, item_id)
)
WITH (
    OIDS=FALSE
);


-- ************************************************************************* --
--                                ENTITIES                                   --
-- ************************************************************************* --
DROP TABLE IF EXISTS rm_entities CASCADE;
CREATE TABLE rm_entities
(
  entity_id character varying(32) NOT NULL,
  is_archival_agency character varying(1) NOT NULL DEFAULT 'N'::bpchar,
  is_originating_agency character varying(1) NOT NULL DEFAULT 'N'::bpchar,
  is_transferring_agency character varying(1) NOT NULL DEFAULT 'N'::bpchar,
  is_repository character varying(1) NOT NULL DEFAULT 'N'::bpchar,
  is_control_authority character varying(1) NOT NULL DEFAULT 'N'::bpchar,
  rm_entity_type character varying(50) NOT NULL DEFAULT 'Collectivité'::bpchar,
  parallel_forms_of_names text,
  other_normalized_names text,
  other_names text,
  oldest_date date,
  latest_date date,
  history text,
  places text,
  legal_status text,
  activities text,
  mandates text,
  structure text,
  context text,
  record_id character varying(100),
  institution_id character varying(255),
  rules text,
  status character varying(255),
  detail_level character varying(255),
  maintenance_dates text,
  description_language character varying(100),
  sources text,
  maintenance_notes text,
  CONSTRAINT "rm_entities_pkey" PRIMARY KEY (entity_id)
)
WITH (
    OIDS=FALSE
);

-- ************************************************************************* --
--                             AGREEMENTS                                    --
-- ************************************************************************* --
DROP TABLE IF EXISTS rm_agreements CASCADE;
CREATE TABLE rm_agreements 
(
  agreement_id SERIAL,
  identifier character varying(100) NOT NULL,
  description character varying(255) NOT NULL,
  comment TEXT,
  archival_profile character varying(50) NOT NULL,
  archival_entity_id character varying(100) NOT NULL,
  transferring_entity_id character varying(100) NOT NULL,
  begin_date date NOT NULL,
  end_date date NOT NULL,
  coll_id character varying(50) NOT NULL,
  allowed_file_types TEXT NOT NULL,
  transfer_max_size bigint NOT NULL DEFAULT 100000,
  transfer_max_item integer NOT NULL DEFAULT 300,
  transfer_count integer,
  transfer_count_period character varying(20) DEFAULT 'MONTH'::bpchar,
  transfer_total_size bigint NOT NULL DEFAULT 100000000,
  is_enabled character(1) NOT NULL DEFAULT 'Y'::bpchar,
  CONSTRAINT "rm_agreements_pkey" PRIMARY KEY (agreement_id)
)
WITH (
    OIDS=FALSE
);

-- ************************************************************************* --
--                             SCHEDULE                                    --
-- ************************************************************************* --
DROP TABLE IF EXISTS rm_schedule CASCADE;
CREATE TABLE rm_schedule 
(
  type_id bigint not null,
  appraisal_code character varying(50) NOT NULL,
  appraisal_duration integer NOT NULL,
  access_restriction_code character varying(50) NOT NULL,
  service_level character varying(50),
  notes text,
  CONSTRAINT "rm_schedule_pkey" PRIMARY KEY (type_id)
)
WITH (
    OIDS=FALSE
);

-- ************************************************************************* --
--                                  VUES                                     --
-- ************************************************************************* --

-- Entities to organizations
DROP VIEW IF EXISTS rm_ref_organizations CASCADE;
CREATE OR REPLACE VIEW rm_ref_organizations AS
SELECT 
    entities.entity_id,
    null as business_type,
    null as description,
    business_id as identification,
    null as legal_classification,
    entity_label as name,
    null as organization_id,
    null as parent_id,
    null as parent_type,
    'TransferringAgency' as role
FROM entities 
JOIN rm_entities ON entities.entity_id = rm_entities.entity_id
WHERE business_id != '' AND is_transferring_agency = 'Y'
UNION 
SELECT 
    entities.entity_id,
    null as business_type,
    null as description,
    business_id as identification,
    null as legal_classification,
    entity_label as name,
    null as organization_id,
    null as parent_id,
    null as parent_type,
    'ArchivalAgency' as role
FROM entities 
JOIN rm_entities ON entities.entity_id = rm_entities.entity_id
WHERE business_id != '' AND is_archival_agency = 'Y'
UNION 
SELECT 
    entities.entity_id,
    null as business_type,
    null as description,
    business_id as identification,
    null as legal_classification,
    entity_label as name,
    null as organization_id,
    null as parent_id,
    null as parent_type,
    'OriginatingAgency' as role
FROM entities 
JOIN rm_entities ON entities.entity_id = rm_entities.entity_id
WHERE business_id != '' AND business_id != '' AND is_originating_agency = 'Y'
UNION 
SELECT 
    entities.entity_id,
    null as business_type,
    null as description,
    business_id as identification,
    null as legal_classification,
    entity_label as name,
    null as organization_id,
    null as parent_id,
    null as parent_type,
    'Repository' as role
FROM entities 
JOIN rm_entities ON entities.entity_id = rm_entities.entity_id
WHERE business_id != '' AND is_repository = 'Y'
UNION 
SELECT 
    entities.entity_id,
    null as business_type,
    null as description,
    business_id as identification,
    null as legal_classification,
    entity_label as name,
    null as organization_id,
    null as parent_id,
    null as parent_type,
    'ControlAuthority' as role
FROM entities 
JOIN rm_entities ON entities.entity_id = rm_entities.entity_id
WHERE business_id != '' AND is_control_authority = 'Y';

-- Entities/contacts to addresses
DROP VIEW IF EXISTS rm_ref_addresses CASCADE;
CREATE OR REPLACE VIEW rm_ref_addresses AS
SELECT 
    null as address_id,
    null as parent_id,
    null as parent_type,
    entity_id,
    '*' as user_id,
    adrs_1 as street_name,
    adrs_2 as block_name,
    adrs_3 as post_office_box,
    zipcode as postcode,
    city as city_name,

    null as building_name,
    null as building_number,
    null as city_sub_division_name,
    null as country,
    null as floor_identification, 
    null as room_identification
FROM entities
WHERE adrs_1 != '' OR adrs_1 != '' OR adrs_2 != '' OR adrs_3 != '' OR city != ''
UNION 
SELECT 
    null as address_id,
    null as parent_id,
    null as parent_type,
    entities.entity_id,
    users.user_id,
    adrs_1 as street_name,
    adrs_2 as block_name,
    adrs_3 as post_office_box,
    zipcode as postcode,
    city as city_name,
    entity_label as room_identification, 
    
    null as building_name,
    null as building_number,
    null as city_sub_division_name,
    null as country,
    null as floor_identification
FROM users
LEFT JOIN users_entities ON users.user_id = users_entities.user_id
LEFT JOIN entities ON users_entities.entity_id = entities.entity_id
WHERE adrs_1 != '' OR adrs_1 != '' OR adrs_2 != '' OR adrs_3 != '' OR city != '';

-- Users to Contacts
DROP VIEW IF EXISTS rm_ref_contacts;
CREATE OR REPLACE VIEW rm_ref_contacts AS 
 SELECT 
    users.user_id, 
    NULL::bpchar AS contact_id, 
    NULL::bpchar AS organization_id, 
    NULL::bpchar AS identification, 
    entities.entity_label AS department_name, 
    users_entities.user_role AS responsibility, 
    (users.firstname::text || ' '::text) || users.lastname::text AS person_name
 FROM users
    JOIN users_entities ON users.user_id::text = users_entities.user_id::text
    JOIN entities ON users_entities.entity_id::text = entities.entity_id::text;

-- RM_IOS
DROP VIEW IF EXISTS rm_ios_view;
CREATE OR REPLACE VIEW rm_ios_view AS 
SELECT
    rm_ios.*,
    ArchivalAgency.entity_id AS archival_agency_entity_id,
    ArchivalContact.user_id AS archival_user_id,
    RequestingAgency.entity_id AS requesting_agency_entity_id,
    RequestingContact.user_id AS requesting_user_id,
    rm_comments.comment AS comment,
    Archives.name as archive_name,
    count(ArchiveObjects) as nb_archive_objects
FROM rm_ios
    LEFT JOIN rm_organizations AS ArchivalAgency ON ArchivalAgency.parent_id = rm_ios.io_id AND ArchivalAgency.role = 'ArchivalAgency'
    LEFT JOIN rm_contacts AS ArchivalContact ON ArchivalContact.organization_id = 
        (
        SELECT organization_id 
        FROM rm_contacts 
        WHERE rm_contacts.organization_id = ArchivalAgency.organization_id 
        ORDER BY contact_id
        LIMIT 1
        )
    LEFT JOIN rm_organizations AS RequestingAgency ON RequestingAgency.parent_id = rm_ios.io_id AND RequestingAgency.role IN ('TransferringAgency', 'RequestingAgency')
    LEFT JOIN rm_contacts AS RequestingContact ON RequestingContact.organization_id = 
        (
        SELECT organization_id 
        FROM rm_contacts 
        WHERE rm_contacts.organization_id = RequestingAgency.organization_id 
        ORDER BY contact_id
        LIMIT 1
        )
    
    LEFT JOIN rm_io_archives_relations IOArchives ON IOArchives.io_id = rm_ios.io_id
    LEFT JOIN rm_items AS Archives ON Archives.item_id = IOArchives.item_id
    LEFT JOIN rm_items AS ArchiveObjects ON ArchiveObjects.parent_item_id = Archives.item_id
    LEFT JOIN rm_comments ON rm_comments.io_id = 
        (
        SELECT io_id 
        FROM rm_comments 
        WHERE rm_comments.io_id = rm_ios.io_id
        ORDER BY comment_id 
        LIMIT 1
        )
GROUP BY 
    rm_ios.io_id, 
    rm_ios.io_type,
    rm_ios.io_status,
    rm_ios.docserver_id,
    rm_ios.io_path,
    rm_ios.io_filename,
    rm_ios.date,
    rm_ios.reply_code,
    rm_ios.operation_date,
    rm_ios.related_identifier,
    rm_ios.identifier,
    rm_ios.reference_identifier,
    ArchivalAgency.entity_id, 
    RequestingAgency.entity_id, 
    ArchivalContact.user_id, 
    RequestingContact.user_id, 
    Archives.Name,
    rm_comments.comment;


-- RES_VIEW_RM
DROP VIEW IF EXISTS rm_documents_view;
CREATE OR REPLACE VIEW rm_documents_view AS 
SELECT 
    rm_documents.*,

    rm_items.archival_agency_item_identifier,
    rm_items.description_language,
    rm_items.name,
    rm_items.item_type,
    rm_items.originating_agency_item_identifier,
    rm_items.service_level,
    rm_items.transferring_agency_item_identifier,
    rm_items.schedule_id, 
	
    rm_content_descriptions.description as content_description,
    rm_content_descriptions.description_level,
    rm_content_descriptions.file_plan_position,
    rm_content_descriptions.language,
    rm_content_descriptions.latest_date,
    rm_content_descriptions.oldest_date,
    rm_content_descriptions.other_descriptive_data,

    rm_custodial_history.custodial_history_item,
    
    doctypes.description as type_label,
    
    doctypes_first_level.doctypes_first_level_id, 
    doctypes_first_level.doctypes_first_level_label, 
    doctypes_first_level.css_style as doctype_first_level_style, 
    
    doctypes_second_level.doctypes_second_level_id, 
    doctypes_second_level.doctypes_second_level_label, 
    doctypes_second_level.css_style as doctype_second_level_style,
    
    file_plan_position.folders_system_id,
    
	schedule.folder_id as schedule_name,
	
    originating_agency.identification as originating_agency_identification,
    originating_agency.entity_id as originating_agency_entity_id,
    originating_agency.name as originating_agency_name,
    
    rm_contacts.department_name as dest_user,
    
    rm_appraisal_rules.code as appraisal_code,
    rm_appraisal_rules.duration as appraisal_duration,
    rm_appraisal_rules.start_date as appraisal_start_date,
    
    rm_access_restriction_rules.code as access_restriction_code,
    rm_access_restriction_rules.start_date as access_restriction_start_date
    
FROM rm_documents
    LEFT JOIN rm_items on rm_items.item_id = rm_documents.item_id
    LEFT JOIN rm_content_descriptions on rm_items.item_id = rm_content_descriptions.item_id
    LEFT JOIN rm_custodial_history on rm_items.item_id = rm_custodial_history.item_id
    LEFT JOIN doctypes on rm_documents.type_id = doctypes.type_id
    LEFT JOIN doctypes_first_level ON doctypes.doctypes_first_level_id = doctypes_first_level.doctypes_first_level_id
    LEFT JOIN doctypes_second_level ON doctypes.doctypes_second_level_id = doctypes_second_level.doctypes_second_level_id
    LEFT JOIN rm_organizations AS originating_agency ON originating_agency.organization_id = 
        (
        SELECT organization_id 
        FROM rm_organizations 
        WHERE rm_organizations.parent_id = rm_items.item_id AND rm_organizations.role = 'OriginatingAgency'
        ORDER BY organization_id
        LIMIT 1
        )
    LEFT JOIN rm_contacts on originating_agency.organization_id = rm_contacts.organization_id and contact_id = 
        (
        SELECT contact_id 
        FROM rm_contacts 
        WHERE rm_contacts.organization_id = originating_agency.organization_id
        ORDER BY contact_id
        LIMIT 1
        )
    LEFT JOIN rm_appraisal_rules ON rm_appraisal_rules.parent_id = rm_items.item_id
    LEFT JOIN rm_access_restriction_rules ON rm_access_restriction_rules.parent_id = rm_items.item_id
    LEFT JOIN folders file_plan_position ON rm_content_descriptions.file_plan_position = file_plan_position.folder_id AND foldertype_id = '101'
    LEFT JOIN folders schedule ON rm_items.schedule_id = schedule.folders_system_id
WHERE item_type = 'ArchiveObject';

-- log collection
-- res_log
DROP TABLE IF EXISTS res_log CASCADE;
CREATE TABLE res_log
(
  res_id SERIAL,
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
  tablename character varying(32) DEFAULT 'res_log'::character varying,
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
  CONSTRAINT res_log_pkey PRIMARY KEY  (res_id)
)
WITH (OIDS=FALSE);

DROP TABLE IF EXISTS adr_log;
CREATE TABLE adr_log
(
  res_id bigint NOT NULL,
  docserver_id character varying(32) NOT NULL,
  path character varying(255) DEFAULT NULL::character varying,
  filename character varying(255) DEFAULT NULL::character varying,
  offset_doc character varying(255) DEFAULT NULL::character varying,
  fingerprint character varying(255) DEFAULT NULL::character varying,
  adr_priority integer NOT NULL,
  CONSTRAINT adr_log_pkey PRIMARY KEY (res_id, docserver_id)
)
WITH (OIDS=FALSE);

DROP TABLE IF EXISTS adr_attachments;
CREATE TABLE adr_attachments
(
  res_id bigint NOT NULL,
  docserver_id character varying(32) NOT NULL,
  path character varying(255) DEFAULT NULL::character varying,
  filename character varying(255) DEFAULT NULL::character varying,
  offset_doc character varying(255) DEFAULT NULL::character varying,
  fingerprint character varying(255) DEFAULT NULL::character varying,
  adr_priority integer NOT NULL,
  adr_type character varying(32) NOT NULL DEFAULT 'DOC'::character varying,
  CONSTRAINT adr_attachments_pkey PRIMARY KEY (res_id, docserver_id)
)
WITH (OIDS=FALSE);

DROP VIEW IF EXISTS res_view_log;
CREATE OR REPLACE VIEW res_view_log AS
 select * from res_log;

-- ************************************************************************* --
--                                                                           --
--                               BUSINESS COLLECTION                          --
--                                                                           --
-- ************************************************************************* --

DROP TABLE IF EXISTS res_business CASCADE;
CREATE TABLE res_business
(
  res_id SERIAL,
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
  reference_number character varying(255) DEFAULT NULL::character varying,
  tablename character varying(32) DEFAULT 'res_business'::character varying,
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
  locker_user_id character varying(255) DEFAULT NULL::character varying,
  locker_time timestamp without time zone,
  CONSTRAINT res_business_pkey PRIMARY KEY  (res_id)
)
WITH (OIDS=FALSE);

DROP TABLE IF EXISTS adr_business;
CREATE TABLE adr_business
(
  res_id bigint NOT NULL,
  docserver_id character varying(32) NOT NULL,
  path character varying(255) DEFAULT NULL::character varying,
  filename character varying(255) DEFAULT NULL::character varying,
  offset_doc character varying(255) DEFAULT NULL::character varying,
  fingerprint character varying(255) DEFAULT NULL::character varying,
  adr_priority integer NOT NULL,
  CONSTRAINT adr_business_pkey PRIMARY KEY (res_id, docserver_id)
)
WITH (OIDS=FALSE);

DROP TABLE IF EXISTS res_version_business;
CREATE TABLE res_version_business
(
  res_id serial,
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
  tablename character varying(32) DEFAULT 'res_version_business'::character varying,
  initiator character varying(50) DEFAULT NULL::character varying,
  dest_user character varying(128) DEFAULT NULL::character varying,
  video_batch integer,
  video_time integer,
  video_user character varying(128) DEFAULT NULL::character varying,
  video_date timestamp without time zone,
  cycle_date timestamp without time zone,
  coll_id character varying(32) NOT NULL,
  res_id_master bigint,
  CONSTRAINT res_version_business_pkey PRIMARY KEY (res_id)
)
WITH (
  OIDS=FALSE
);

DROP TABLE IF EXISTS business_coll_ext CASCADE;
CREATE TABLE business_coll_ext (
  res_id bigint NOT NULL,
  category_id character varying(50)  NOT NULL,
  contact_id integer default NULL,
  currency character varying(10) default NULL,
  net_sum float default NULL,
  tax_sum float default NULL,
  total_sum float default NULL,
  process_limit_date timestamp without time zone default NULL,
  closing_date timestamp without time zone default NULL,
  alarm1_date timestamp without time zone default NULL,
  alarm2_date timestamp without time zone default NULL,
  flag_notif char(1)  default 'N'::character varying ,
  flag_alarm1 char(1)  default 'N'::character varying ,
  flag_alarm2 char(1) default 'N'::character varying,
  address_id bigint
)WITH (OIDS=FALSE);

DROP TABLE IF EXISTS invoice_types CASCADE;
CREATE TABLE invoice_types (
  invoice_type_id character varying(50) NOT NULL,
  invoice_type_name character varying(255) NOT NULL,
  invoice_movement char(2) default 'DR'::character varying
)WITH (OIDS=FALSE);

-- sendmail module
CREATE TABLE sendmail
(
  email_id serial NOT NULL,
  coll_id character varying(32) NOT NULL,
  res_id bigint NOT NULL,
  user_id character varying(128) NOT NULL,
  to_list text DEFAULT NULL,
  cc_list text DEFAULT NULL,
  cci_list text DEFAULT NULL,
  email_object character varying(255) DEFAULT NULL,
  email_body text,
  is_res_master_attached character varying(1) NOT NULL DEFAULT 'Y',
  res_version_id_list character varying(255) DEFAULT NULL,
  res_attachment_id_list character varying(255) DEFAULT NULL,
  note_id_list character varying(255) DEFAULT NULL,
  is_html character varying(1) NOT NULL DEFAULT 'Y',
  email_status character varying(1) NOT NULL DEFAULT 'D',
  creation_date timestamp without time zone NOT NULL,
  send_date timestamp without time zone DEFAULT NULL,
  sender_email character varying(255) DEFAULT NULL,
  CONSTRAINT sendmail_pkey PRIMARY KEY (email_id )
 );

-- fileplan module
DROP SEQUENCE IF EXISTS fp_fileplan_positions_position_id_seq;
CREATE SEQUENCE fp_fileplan_positions_position_id_seq
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 10
  CACHE 1;

DROP TABLE IF EXISTS fp_fileplan;
CREATE TABLE fp_fileplan
(
  fileplan_id serial NOT NULL,
  fileplan_label character varying(255),
  user_id character varying(128) DEFAULT NULL,
  entity_id character varying(32) DEFAULT NULL,
  is_serial_id character varying(1) NOT NULL DEFAULT 'Y', 
  enabled character varying(1) NOT NULL DEFAULT 'Y',
  CONSTRAINT fp_fileplan_pkey PRIMARY KEY (fileplan_id)
 );
 
DROP TABLE IF EXISTS fp_fileplan_positions;
CREATE TABLE fp_fileplan_positions 
(
  position_id integer NOT NULL DEFAULT nextval('fp_fileplan_positions_position_id_seq'::regclass),
  position_label character varying(255),
  parent_id character varying(32) DEFAULT NULL,
  fileplan_id bigint NOT NULL,
  enabled character varying(1) NOT NULL DEFAULT 'Y',
  CONSTRAINT fp_fileplan_positions_pkey PRIMARY KEY (fileplan_id, position_id)
);

DROP TABLE IF EXISTS fp_res_fileplan_positions;
CREATE TABLE fp_res_fileplan_positions 
(
  res_id bigint NOT NULL,
  coll_id character varying(32) NOT NULL,
  fileplan_id bigint NOT NULL,
  position_id integer NOT NULL,
  CONSTRAINT fp_res_fileplan_positions_pkey PRIMARY KEY (res_id, coll_id, fileplan_id, position_id)
);

DROP TABLE IF EXISTS actions_categories;
CREATE TABLE actions_categories
(
  action_id bigint NOT NULL,
  category_id character varying(255) NOT NULL,
  CONSTRAINT actions_categories_pkey PRIMARY KEY (action_id,category_id)
);

DROP SEQUENCE IF EXISTS user_baskets_secondary_seq;
CREATE SEQUENCE user_baskets_secondary_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 10
CACHE 1;

DROP TABLE IF EXISTS user_baskets_secondary;
CREATE TABLE user_baskets_secondary
(
  system_id bigint NOT NULL DEFAULT nextval('user_baskets_secondary_seq'::regclass),
  user_id character varying(128) NOT NULL,
  group_id character varying(32) NOT NULL,
  basket_id character varying(32) NOT NULL,
  CONSTRAINT user_baskets_secondary_pkey PRIMARY KEY (system_id)
);

DROP SEQUENCE IF EXISTS listinstance_history_id_seq;
CREATE SEQUENCE listinstance_history_id_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

DROP TABLE IF EXISTS listinstance_history;
CREATE TABLE listinstance_history
(
listinstance_history_id bigint NOT NULL DEFAULT nextval('listinstance_history_id_seq'::regclass),
coll_id character varying(50) NOT NULL,
res_id bigint NOT NULL,
updated_by_user character varying(128) NOT NULL,
updated_date timestamp without time zone NOT NULL,
CONSTRAINT listinstance_history_pkey PRIMARY KEY (listinstance_history_id)
)
WITH ( OIDS=FALSE );

DROP SEQUENCE IF EXISTS listinstance_history_details_id_seq;
CREATE SEQUENCE listinstance_history_details_id_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

DROP TABLE IF EXISTS listinstance_history_details;
CREATE TABLE listinstance_history_details
(
listinstance_history_details_id bigint NOT NULL DEFAULT nextval('listinstance_history_details_id_seq'::regclass),
listinstance_history_id bigint NOT NULL,
coll_id character varying(50) NOT NULL,
res_id bigint NOT NULL,
listinstance_type character varying(50) DEFAULT 'DOC'::character varying,
sequence bigint NOT NULL,
item_id character varying(128) NOT NULL,
item_type character varying(255) NOT NULL,
item_mode character varying(50) NOT NULL,
added_by_user character varying(128) NOT NULL,
added_by_entity character varying(50) NOT NULL,
visible character varying(1) NOT NULL DEFAULT 'Y'::bpchar,
viewed bigint,
difflist_type character varying(50),
process_date timestamp without time zone,
process_comment character varying(255),
CONSTRAINT listinstance_history_details_pkey PRIMARY KEY (listinstance_history_details_id)
) WITH ( OIDS=FALSE );


--VIEWS
--view for demo
DROP VIEW IF EXISTS res_view;
CREATE VIEW res_view AS
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
 r.custom_f4 AS doc_custom_f4, r.custom_f5 AS doc_custom_f5, r.is_frozen as res_is_frozen,  
 r.reference_number, r.locker_user_id, r.locker_time
   FROM  doctypes d, doctypes_first_level dfl, doctypes_second_level dsl, res_x r
   WHERE r.type_id = d.type_id
   AND d.doctypes_first_level_id = dfl.doctypes_first_level_id
   AND d.doctypes_second_level_id = dsl.doctypes_second_level_id;

--view for business
DROP VIEW IF EXISTS res_view_business;
CREATE VIEW res_view_business AS
    SELECT r.tablename, r.is_multi_docservers, r.res_id, r.type_id,
    d.description AS type_label, d.doctypes_first_level_id,
    d.doctypes_second_level_id, dfl.doctypes_first_level_label, 
    dfl.css_style as doctype_first_level_style,
    dsl.doctypes_second_level_label,
    dsl.css_style as doctype_second_level_style, r.format, r.typist,
    r.creation_date, r.relation, r.docserver_id, r.folders_system_id,
    f.folder_id, f.is_frozen as folder_is_frozen, r.path, r.filename, 
    r.fingerprint, r.offset_doc, r.filesize,
    r.status, r.work_batch, r.arbatch_id, r.arbox_id, r.page_count, r.is_paper,
    r.doc_date, r.scan_date, r.scan_user, r.scan_location, r.scan_wkstation,
    r.scan_batch, r.doc_language, r.description, r.source, r.author, r.reference_number,
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
    r.custom_f5 AS doc_custom_f5, f.foldertype_id,
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
    r.dest_user, busi.category_id, busi.contact_id, busi.address_id, busi.currency,
	r.locker_user_id, r.locker_time,	
    busi.net_sum, busi.tax_sum, busi.total_sum, 
    busi.process_limit_date, busi.closing_date, busi.alarm1_date, busi.alarm2_date,
    busi.flag_notif, busi.flag_alarm1, busi.flag_alarm2, r.video_user, r.video_time,
    r.video_batch, r.subject, r.identifier, r.title, r.priority,
    en.entity_label,
    cont.firstname AS contact_firstname, cont.lastname AS contact_lastname,
    cont.society AS contact_society, list.item_id AS dest_user_from_listinstance,  list.viewed, 
    r.is_frozen as res_is_frozen, COALESCE(att.count_attachment, 0::bigint) AS count_attachment 
    FROM doctypes d, doctypes_first_level dfl, doctypes_second_level dsl, res_business r
    LEFT JOIN (SELECT res_attachments.res_id_master, coll_id, count(res_attachments.res_id_master) AS count_attachment
        FROM res_attachments WHERE res_attachments.status <> 'DEL' GROUP BY res_attachments.res_id_master, coll_id) att ON (r.res_id = att.res_id_master and att.coll_id = 'business_coll')
    LEFT JOIN entities en ON ((r.destination)::text = (en.entity_id)::text)
    LEFT JOIN folders f ON ((r.folders_system_id = f.folders_system_id))
    LEFT JOIN business_coll_ext busi ON (busi.res_id = r.res_id)
    LEFT JOIN contacts_v2 cont ON (busi.contact_id = cont.contact_id)
    LEFT JOIN listinstance list ON ((r.res_id = list.res_id)
        AND ((list.item_mode)::text = 'dest'::text))
    WHERE r.type_id = d.type_id 
    AND d.doctypes_first_level_id = dfl.doctypes_first_level_id
    AND d.doctypes_second_level_id = dsl.doctypes_second_level_id;

-- view for letterbox
DROP VIEW IF EXISTS res_view_letterbox;
CREATE VIEW res_view_letterbox AS
    SELECT r.tablename, r.is_multi_docservers, r.res_id, r.type_id, r.policy_id, r.cycle_id, 
    d.description AS type_label, d.doctypes_first_level_id,
    dfl.doctypes_first_level_label, dfl.css_style as doctype_first_level_style,
    d.doctypes_second_level_id, dsl.doctypes_second_level_label,
    dsl.css_style as doctype_second_level_style, r.format, r.typist,
    r.creation_date, r.modification_date, r.relation, r.docserver_id, r.folders_system_id,
    f.folder_id, f.destination as folder_destination, f.is_frozen as folder_is_frozen, r.path, r.filename, r.fingerprint, r.offset_doc, r.filesize,
    r.status, r.work_batch, r.arbatch_id, r.arbox_id, r.page_count, r.is_paper,
    r.doc_date, r.scan_date, r.scan_user, r.scan_location, r.scan_wkstation,
    r.scan_batch, r.doc_language, r.description, r.source, r.author, r.reference_number,
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
    r.dest_user, r.confidentiality, mlb.category_id, mlb.exp_contact_id, mlb.exp_user_id,
    mlb.dest_user_id, mlb.dest_contact_id, mlb.address_id, mlb.nature_id, mlb.alt_identifier,
    mlb.admission_date, mlb.answer_type_bitmask, mlb.other_answer_desc,
    mlb.sve_start_date, mlb.sve_identifier,
    mlb.process_limit_date, mlb.recommendation_limit_date, mlb.closing_date, mlb.alarm1_date, mlb.alarm2_date,
    mlb.flag_notif, mlb.flag_alarm1, mlb.flag_alarm2, mlb.is_multicontacts, r.video_user, r.video_time,
    r.video_batch, r.subject, r.identifier, r.title, r.priority, mlb.process_notes,
	r.locker_user_id, r.locker_time,
    ca.case_id, ca.case_label, ca.case_description, en.entity_label, en.entity_type AS entityType,
    cont.contact_id AS contact_id, 
    cont.firstname AS contact_firstname, cont.lastname AS contact_lastname,
    cont.society AS contact_society, u.lastname AS user_lastname,
    u.firstname AS user_firstname, list.item_id AS dest_user_from_listinstance, list.viewed, 
    r.is_frozen as res_is_frozen, COALESCE(att.count_attachment, 0::bigint) AS count_attachment 
    FROM doctypes d, doctypes_first_level dfl, doctypes_second_level dsl,
    (((((((((((ar_batch a RIGHT JOIN res_letterbox r ON ((r.arbatch_id = a.arbatch_id)))
    LEFT JOIN (SELECT res_attachments.res_id_master, count(res_attachments.res_id_master) AS count_attachment
        FROM res_attachments WHERE res_attachments.status <> 'DEL' GROUP BY res_attachments.res_id_master) att ON (r.res_id = att.res_id_master))
    LEFT JOIN entities en ON (((r.destination)::text = (en.entity_id)::text)))
    LEFT JOIN folders f ON ((r.folders_system_id = f.folders_system_id)))
    LEFT JOIN cases_res cr ON ((r.res_id = cr.res_id)))
    LEFT JOIN mlb_coll_ext mlb ON ((mlb.res_id = r.res_id)))
    LEFT JOIN foldertypes ft ON (((f.foldertype_id = ft.foldertype_id)
        AND ((f.status)::text <> 'DEL'::text))))
    LEFT JOIN cases ca ON ((cr.case_id = ca.case_id)))
    LEFT JOIN contacts_v2 cont ON (((mlb.exp_contact_id = cont.contact_id)
        OR (mlb.dest_contact_id = cont.contact_id))))
    LEFT JOIN users u ON ((((mlb.exp_user_id)::text = (u.user_id)::text)
        OR ((mlb.dest_user_id)::text = (u.user_id)::text))))
    LEFT JOIN listinstance list ON (((r.res_id = list.res_id)
        AND ((list.item_mode)::text = 'dest'::text))))
    WHERE (((r.type_id = d.type_id) AND
    (d.doctypes_first_level_id = dfl.doctypes_first_level_id))
    AND (d.doctypes_second_level_id = dsl.doctypes_second_level_id));

--view for postindexing
DROP VIEW IF EXISTS view_postindexing;
CREATE VIEW view_postindexing AS
SELECT res_view_letterbox.video_user, (users.firstname::text || ' '::text) || users.lastname::text AS user_name, res_view_letterbox.video_batch, res_view_letterbox.video_time, count(res_view_letterbox.res_id) AS count_documents, res_view_letterbox.folders_system_id, (folders.folder_id::text || ' / '::text) || folders.folder_name::text AS folder_full_label, folders.video_status
FROM res_view_letterbox
LEFT JOIN users ON res_view_letterbox.video_user::text = users.user_id::text
LEFT JOIN folders ON folders.folders_system_id = res_view_letterbox.folders_system_id
WHERE res_view_letterbox.video_batch IS NOT NULL
GROUP BY res_view_letterbox.video_user, (users.firstname::text || ' '::text) || users.lastname::text, res_view_letterbox.video_batch, res_view_letterbox.video_time, res_view_letterbox.folders_system_id, (folders.folder_id::text || ' / '::text) || folders.folder_name::text, folders.video_status;

--view for APA
DROP VIEW IF EXISTS res_view_apa;
CREATE VIEW res_view_apa AS
select * from res_apa;

--views for autofoldering
-- Resource view used to fill af_target, we exclude from res_x the branches already in af_target table
DROP VIEW IF EXISTS af_view_year_view;
CREATE VIEW af_view_year_view AS
SELECT r.custom_t3 AS level1, date_part('year', r.doc_date) AS level2, r.custom_t4 AS level3,
    r.res_id, r.creation_date, r.status -- for where clause
FROM res_x r
WHERE  NOT (EXISTS (SELECT t.level1, t.level2, t.level3
    FROM af_view_year_target t
    WHERE r.custom_t3::text = t.level1::text AND cast(date_part('year', r.doc_date) as character) = t.level2 AND r.custom_t4 = t.level3));

DROP VIEW IF EXISTS af_view_customer_view;
CREATE VIEW af_view_customer_view AS
SELECT substring(r.custom_t4, 1, 1) AS level1,  r.custom_t4 AS level2, date_part('year', r.doc_date) AS level3,
    r.res_id, r.creation_date, r.status -- for where clause
FROM  res_x r
WHERE status <> 'DEL' and date_part('year', doc_date) is not null
AND NOT (EXISTS (SELECT t.level1, t.level2, t.level3
    FROM af_view_customer_target t
    WHERE substring(r.custom_t4, 1, 1)::text = t.level1::text AND r.custom_t4::text = t.level2::text
    AND cast(date_part('year', r.doc_date) as character) = t.level3)) ;

-- View used to display trees
DROP VIEW IF EXISTS af_view_year_target_view;
CREATE VIEW af_view_year_target_view AS
SELECT af.level1, af.level1_id, af.level1 as level1_label, af.level2, af.level2_id, af.level2 as level2_label, af.level3, af.level3_id, af.level3 as level3_label
FROM af_view_year_target af;

DROP VIEW IF EXISTS af_view_customer_target_view;
CREATE VIEW af_view_customer_target_view AS
SELECT af.level1, af.level1_id, af.level1 as level1_label, af.level2, af.level2_id, af.level2 as level2_label, af.level3, af.level3_id, af.level3 as level3_label
FROM af_view_customer_target af ;

-- View folders
DROP VIEW IF EXISTS view_folders;
CREATE VIEW view_folders AS 
SELECT folders.folders_system_id, folders.folder_id, folders.foldertype_id, foldertypes.foldertype_label, (folders.folder_id || ':') || folders.folder_name AS folder_full_label, folders.parent_id, folders.folder_name, folders.subject, folders.description, folders.author, folders.typist, folders.status, folders.folder_level, 
folders.creation_date, folders.destination, folders.dest_user, 
folders.folder_out_id, folders.custom_t1, folders.custom_n1, folders.custom_f1, folders.custom_d1, folders.custom_t2, folders.custom_n2, folders.custom_f2, folders.custom_d2, folders.custom_t3, folders.custom_n3, folders.custom_f3, folders.custom_d3, folders.custom_t4, folders.custom_n4, folders.custom_f4, folders.custom_d4, folders.custom_t5, folders.custom_n5, folders.custom_f5, folders.custom_d5, folders.custom_t6, folders.custom_d6, folders.custom_t7, folders.custom_d7, folders.custom_t8, folders.custom_d8, folders.custom_t9, folders.custom_d9, folders.custom_t10, folders.custom_d10, folders.custom_t11, folders.custom_d11, folders.custom_t12, folders.custom_d12, folders.custom_t13, folders.custom_d13, folders.custom_t14, folders.custom_d14, folders.custom_t15, folders.is_complete, folders.is_folder_out, folders.last_modified_date, folders.video_status, COALESCE(r.count_document, 0::bigint) AS count_document
   FROM foldertypes, folders
   LEFT JOIN ( SELECT res_letterbox.folders_system_id, count(res_letterbox.folders_system_id) AS count_document
           FROM res_letterbox
          GROUP BY res_letterbox.folders_system_id) r ON r.folders_system_id = folders.folders_system_id
  WHERE folders.foldertype_id = foldertypes.foldertype_id;

-- View fileplan
CREATE OR REPLACE VIEW fp_view_fileplan AS 
 SELECT fp_fileplan.fileplan_id, fp_fileplan.fileplan_label, 
    fp_fileplan.user_id, fp_fileplan.entity_id, fp_fileplan.enabled, 
    fp_fileplan_positions.position_id, fp_fileplan_positions.position_label, 
    fp_fileplan_positions.parent_id, 
    fp_fileplan_positions.enabled AS position_enabled, 
    COALESCE(r.count_document, 0::bigint) AS count_document
   FROM fp_fileplan, 
    fp_fileplan_positions
   LEFT JOIN ( SELECT fp_res_fileplan_positions.position_id, 
            count(fp_res_fileplan_positions.res_id) AS count_document
           FROM fp_res_fileplan_positions
          GROUP BY fp_res_fileplan_positions.position_id) r ON r.position_id::text = fp_fileplan_positions.position_id::text
  WHERE fp_fileplan.fileplan_id = fp_fileplan_positions.fileplan_id;

--view for contacts_v2
DROP VIEW IF EXISTS view_contacts;
CREATE OR REPLACE VIEW view_contacts AS 
 SELECT c.contact_id, c.contact_type, c.is_corporate_person, c.society, c.society_short, c.firstname AS contact_firstname
, c.lastname AS contact_lastname, c.title AS contact_title, c.function AS contact_function, c.other_data AS contact_other_data
, c.user_id AS contact_user_id, c.entity_id AS contact_entity_id, c.creation_date, c.update_date, c.enabled AS contact_enabled, ca.id AS ca_id
, ca.contact_purpose_id, ca.departement, ca.firstname, ca.lastname, ca.title, ca.function, ca.occupancy
, ca.address_num, ca.address_street, ca.address_complement, ca.address_town, ca.address_postal_code, ca.address_country
, ca.phone, ca.email, ca.website, ca.salutation_header, ca.salutation_footer, ca.other_data, ca.user_id, ca.entity_id, ca.is_private, ca.enabled
, cp.label as contact_purpose_label, ct.label as contact_type_label
   FROM contacts_v2 c
   RIGHT JOIN contact_addresses ca ON c.contact_id = ca.contact_id
   LEFT JOIN contact_purposes cp ON ca.contact_purpose_id = cp.id
   LEFT JOIN contact_types ct ON c.contact_type = ct.id;

DROP TABLE IF EXISTS res_version_attachments;
DROP SEQUENCE IF EXISTS res_id_version_attachments_seq;

   CREATE SEQUENCE res_id_version_attachments_seq
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 100
  CACHE 1;

CREATE TABLE res_version_attachments
(
  res_id bigint NOT NULL DEFAULT nextval('res_id_version_attachments_seq'::regclass),
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
  effective_date timestamp without time zone,
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
  tablename character varying(32) DEFAULT 'res_version_attachments'::character varying,
  initiator character varying(50) DEFAULT NULL::character varying,
  dest_user character varying(128) DEFAULT NULL::character varying,
  video_batch integer,
  video_time integer,
  video_user character varying(128) DEFAULT NULL::character varying,
  video_date timestamp without time zone,
  cycle_date timestamp without time zone,
  coll_id character varying(32) NOT NULL,
  attachment_type character varying(255) DEFAULT NULL::character varying,
  dest_contact_id bigint,
  dest_address_id bigint,
  updated_by character varying(128) DEFAULT NULL::character varying,
  is_multicontacts character(1),
  res_id_master bigint,
  attachment_id_master bigint,
  CONSTRAINT res_version_attachments_pkey PRIMARY KEY (res_id)
)
WITH (
  OIDS=FALSE
);

-- view for attachments
DROP VIEW IF EXISTS res_view_attachments;
CREATE VIEW res_view_attachments AS
  SELECT '0' as res_id, res_id as res_id_version, title, subject, description, publisher, contributor, type_id, format, typist,
  creation_date, fulltext_result, ocr_result, author, author_name, identifier, source,
  doc_language, relation, coverage, doc_date, docserver_id, folders_system_id, arbox_id, path,
  filename, offset_doc, logical_adr, fingerprint, filesize, is_paper, page_count,
  scan_date, scan_user, scan_location, scan_wkstation, scan_batch, burn_batch, scan_postmark,
  envelop_id, status, destination, approver, validation_date, effective_date, work_batch, origin, is_ingoing, priority, initiator, dest_user,
  coll_id, dest_contact_id, dest_address_id, updated_by, is_multicontacts, is_multi_docservers, res_id_master, attachment_type, attachment_id_master
  FROM res_version_attachments
  UNION ALL
  SELECT res_id, '0' as res_id_version, title, subject, description, publisher, contributor, type_id, format, typist,
  creation_date, fulltext_result, ocr_result, author, author_name, identifier, source,
  doc_language, relation, coverage, doc_date, docserver_id, folders_system_id, arbox_id, path,
  filename, offset_doc, logical_adr, fingerprint, filesize, is_paper, page_count,
  scan_date, scan_user, scan_location, scan_wkstation, scan_batch, burn_batch, scan_postmark,
  envelop_id, status, destination, approver, validation_date, effective_date, work_batch, origin, is_ingoing, priority, initiator, dest_user,
  coll_id, dest_contact_id, dest_address_id, updated_by, is_multicontacts, is_multi_docservers, res_id_master, attachment_type, '0'
  FROM res_attachments;

-- thesaurus


CREATE SEQUENCE thesaurus_id_seq
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 1
  CACHE 1;

CREATE TABLE thesaurus
(
  thesaurus_id bigint NOT NULL DEFAULT nextval('thesaurus_id_seq'::regclass),
  thesaurus_name character varying(255) NOT NULL,
  thesaurus_description text,
  thesaurus_name_associate character varying(255),
  thesaurus_parent_id character varying(255),
  creation_date timestamp without time zone,
  used_for text,
  CONSTRAINT thesaurus_pkey PRIMARY KEY (thesaurus_id)
)
WITH (
  OIDS=FALSE
);

CREATE TABLE thesaurus_res
(
  res_id bigint NOT NULL,
  thesaurus_id bigint NOT NULL
)
WITH (
  OIDS=FALSE
);
