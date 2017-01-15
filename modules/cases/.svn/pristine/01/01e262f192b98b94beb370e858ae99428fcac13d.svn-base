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

