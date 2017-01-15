CREATE SEQUENCE folders_system_id_seq
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 20
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
  status character varying(50) NOT NULL DEFAULT 'NEW'::character varying,
  folder_level smallint DEFAULT (1)::smallint,
  creation_date timestamp without time zone NOT NULL,
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
  START 5
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
