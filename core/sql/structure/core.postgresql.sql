
SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;

--DROP PROCEDURAL LANGUAGE IF EXISTS plpgsql  CASCADE;
--CREATE PROCEDURAL LANGUAGE plpgsql;

SET search_path = public, pg_catalog;
SET default_tablespace = '';
SET default_with_oids = false;

CREATE SEQUENCE actions_id_seq
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 101
  CACHE 1;

CREATE TABLE actions
(
  id integer NOT NULL DEFAULT nextval('actions_id_seq'::regclass),
  keyword character varying(32) NOT NULL DEFAULT ''::bpchar,
  label_action character varying(255),
  id_status character varying(10),
  is_system character(1) NOT NULL DEFAULT 'N'::bpchar,
  enabled character(1) NOT NULL DEFAULT 'Y'::bpchar,
  action_page character varying(255),
  history character(1) NOT NULL DEFAULT 'N'::bpchar,
  origin character varying(255) NOT NULL DEFAULT 'apps'::bpchar,
  create_id character(1) NOT NULL DEFAULT 'N'::bpchar,
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
  START 80
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
  event_id character varying(50);
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
  id character varying(50) NOT NULL,
  param_value_string character varying(50) DEFAULT NULL::character varying,
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
  START 20
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
  is_system character(1) NOT NULL DEFAULT 'N'::bpchar,
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
  CONSTRAINT users_pkey PRIMARY KEY (user_id)
)
WITH (OIDS=FALSE);
