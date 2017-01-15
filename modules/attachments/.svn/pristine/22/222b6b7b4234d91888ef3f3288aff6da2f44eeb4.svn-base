
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
  work_batch bigint,
  origin character varying(50) DEFAULT NULL::character varying,
  is_ingoing character(1) DEFAULT NULL::bpchar,
  priority smallint,
  initiator character varying(50) DEFAULT NULL::character varying,
  dest_user character varying(128) DEFAULT NULL::character varying,
  coll_id character varying(32) NOT NULL,
  res_id_master bigint,
  CONSTRAINT res_attachments_pkey PRIMARY KEY (res_id)
)
WITH (OIDS=FALSE);
