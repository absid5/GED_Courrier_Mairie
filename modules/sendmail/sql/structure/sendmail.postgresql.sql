DROP TABLE IF EXISTS sendmail;
CREATE TABLE sendmail
(
  email_id serial NOT NULL,
  coll_id character varying(32) NOT NULL,
  res_id bigint NOT NULL,
  user_id character varying(128) NOT NULL,
  to_list character varying(255) NOT NULL,
  cc_list character varying(255) DEFAULT NULL,
  cci_list character varying(255) DEFAULT NULL,
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
  CONSTRAINT sendmail_pkey PRIMARY KEY (email_id )
 );