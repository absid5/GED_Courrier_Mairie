CREATE SEQUENCE thesaurus_id_seq
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 1
  CACHE 1;

DROP TABLE thesaurus;

CREATE TABLE thesaurus
(
  thesaurus_id bigint NOT NULL DEFAULT nextval('thesaurus_id_seq'::regclass),
  thesaurus_name character varying(255) NOT NULL,
  thesaurus_description text,
  thesaurus_name_associate character varying(255),
  thesaurus_parent_id character varying(255),
  creation_date timestamp without time zone,
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
