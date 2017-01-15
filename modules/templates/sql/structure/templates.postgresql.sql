CREATE SEQUENCE templates_seq
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 20
  CACHE 1;

CREATE SEQUENCE templates_association_seq
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 20
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
  notification_id character varying(50),
  description character varying(255),
  diffusion_type character varying(50),
  diffusion_properties character varying(255),
  attachfor_type character varying(50),
  attachfor_properties character varying(255),
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
