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
