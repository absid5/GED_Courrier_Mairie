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
  date_note date NOT NULL,
  note_text text NOT NULL,
  coll_id character varying(50),
  CONSTRAINT notes_pkey PRIMARY KEY (id)
)
WITH (OIDS=FALSE);
