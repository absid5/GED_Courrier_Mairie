CREATE SEQUENCE notifications_seq
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 1
  CACHE 1;

CREATE TABLE notifications
(
  notification_sid bigint NOT NULL DEFAULT nextval('notifications_seq'::regclass),
  notification_id character varying(50) NOT NULL,
  description character varying(255),
  is_enabled character varying(1) NOT NULL default 'Y'::bpchar,
  event_id character varying(255) NOT NULL,
  notification_mode character varying(30) NOT NULL,
  template_id bigint,
  rss_url_template text,
  diffusion_type character varying(50) NOT NULL,
  diffusion_properties character varying(255),
  attachfor_type character varying(50),
  attachfor_properties character varying(2048),
  CONSTRAINT notifications_pkey PRIMARY KEY (notification_sid)
)
WITH (
  OIDS=FALSE
);


CREATE SEQUENCE notif_event_stack_seq
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 1
  CACHE 1;

CREATE TABLE notif_event_stack
(
  event_stack_sid bigint NOT NULL DEFAULT nextval('notif_event_stack_seq'::regclass),
  notification_sid bigint NOT NULL,
  table_name character varying(50) NOT NULL,
  record_id character varying(50) NOT NULL,
  user_id character varying(128) NOT NULL,
  event_info character varying(255) NOT NULL,
  event_date timestamp without time zone NOT NULL,
  exec_date timestamp without time zone,
  exec_result character varying(50),
  CONSTRAINT notif_event_stack_pkey PRIMARY KEY (event_stack_sid)
)
WITH (
  OIDS=FALSE
);

CREATE SEQUENCE notif_email_stack_seq
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 1
  CACHE 1;

CREATE TABLE notif_email_stack
(
  email_stack_sid bigint NOT NULL DEFAULT nextval('notif_email_stack_seq'::regclass),
  sender character varying(255) NOT NULL,
  reply_to character varying(255),
  recipient character varying(2000) NOT NULL,
  cc character varying(2000),
  bcc character varying(2000),
  subject character varying(255),
  html_body text,
  text_body text,
  charset character varying(50) NOT NULL,
  attachments character varying(2000),
  module character varying(50) NOT NULL,
  exec_date timestamp without time zone,
  exec_result character varying(50),
  CONSTRAINT notif_email_stack_pkey PRIMARY KEY (email_stack_sid)
)
WITH (
  OIDS=FALSE
);
