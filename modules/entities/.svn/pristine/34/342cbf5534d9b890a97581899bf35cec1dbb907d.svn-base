
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
  viewed bigint,
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
  listmodel_type character varying(50) DEFAULT 'DOC'::character varying
)
WITH (OIDS=FALSE);

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
  START 100
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
