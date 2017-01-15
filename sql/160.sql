-- *************************************************************************--
--                                                                          --
--                                                                          --
--        THIS SCRIPT IS USE TO PASS FROM MAARCH 1.5.1 TO MAARCH 1.6        --
--                                                                          --
--                                                                          --
-- *************************************************************************--

-- ************************************************************************* --
--                               ALL VIEWS DROP                              --
-- ************************************************************************* --

DROP VIEW IF EXISTS view_postindexing;
DROP VIEW IF EXISTS res_view_letterbox;
DROP VIEW IF EXISTS res_view_business;
DROP VIEW IF EXISTS res_view;
DROP VIEW IF EXISTS view_contacts;
DROP VIEW IF EXISTS res_view_attachments;


-- ************************************************************************* --
--                      NEW TABLE FOR SIGNATURE TEMPLATE                     --
-- ************************************************************************* --
DROP TABLE IF EXISTS users_email_signatures;
DROP SEQUENCE IF EXISTS email_signatures_id_seq;

CREATE SEQUENCE email_signatures_id_seq
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 7
  CACHE 1;

CREATE TABLE users_email_signatures
(
  id bigint NOT NULL DEFAULT nextval('email_signatures_id_seq'::regclass),
  user_id character varying(255) NOT NULL,
  html_body text NOT NULL,
  title character varying NOT NULL,
  CONSTRAINT email_signatures_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);


-- ************************************************************************* --
--                          NEW TABLE FOR FULL TEXT PJ                       --
-- ************************************************************************* --
DROP TABLE IF EXISTS adr_attachments;
CREATE TABLE adr_attachments
(
  res_id bigint NOT NULL,
  docserver_id character varying(32) NOT NULL,
  path character varying(255) DEFAULT NULL::character varying,
  filename character varying(255) DEFAULT NULL::character varying,
  offset_doc character varying(255) DEFAULT NULL::character varying,
  fingerprint character varying(255) DEFAULT NULL::character varying,
  adr_priority integer NOT NULL,
  adr_type character varying(32) NOT NULL DEFAULT 'DOC'::character varying,
  CONSTRAINT adr_attachments_pkey PRIMARY KEY (res_id, docserver_id)
)
WITH (OIDS=FALSE);


-- ************************************************************************* --
--                       NEW TABLE FOR THESAURUS MODULE                      --
-- ************************************************************************* --
DROP TABLE IF EXISTS thesaurus;
DROP TABLE IF EXISTS thesaurus_res;
DROP SEQUENCE IF EXISTS thesaurus_id_seq;

CREATE SEQUENCE thesaurus_id_seq
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 1
  CACHE 1;

CREATE TABLE thesaurus
(
  thesaurus_id bigint NOT NULL DEFAULT nextval('thesaurus_id_seq'::regclass),
  thesaurus_name character varying(255) NOT NULL,
  thesaurus_description text,
  thesaurus_name_associate character varying(255),
  thesaurus_parent_id character varying(255),
  creation_date timestamp without time zone,
  used_for text,
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


-- ************************************************************************* --
--                          NEW COLUMNS FOR NOTIFICATION                     --
-- ************************************************************************* --

ALTER TABLE baskets DROP COLUMN IF EXISTS except_notif;
ALTER TABLE baskets ADD COLUMN except_notif text default NULL;

ALTER TABLE baskets DROP COLUMN IF EXISTS flag_notif;
ALTER TABLE baskets ADD flag_notif character varying(1);


-- ************************************************************************* --
--                          NEW COLUMNS FOR MODIFICATION                     --
-- ************************************************************************* --

ALTER TABLE res_letterbox DROP COLUMN IF EXISTS modification_date;
ALTER TABLE res_letterbox ADD modification_date timestamp without time zone DEFAULT NOW();


-- ************************************************************************* --
--                     NEW COLUMNS FOR FULL AVIS MODULE                       --
-- ************************************************************************* --
ALTER TABLE mlb_coll_ext DROP COLUMN IF EXISTS recommendation_limit_date;
ALTER TABLE mlb_coll_ext ADD COLUMN recommendation_limit_date timestamp without time zone default NULL;


-- ************************************************************************* --
--                           NEW COLUMNS FOR PJ                              --
-- ************************************************************************* --

ALTER TABLE res_attachments DROP COLUMN IF EXISTS effective_date;
ALTER TABLE res_attachments ADD effective_date timestamp without time zone;

ALTER TABLE res_version_attachments DROP COLUMN IF EXISTS effective_date;
ALTER TABLE res_version_attachments ADD effective_date timestamp without time zone;


-- ************************************************************************* --
--                           NEW COLUMNS FOR SVE                             --
-- ************************************************************************* --
ALTER TABLE mlb_coll_ext DROP COLUMN IF EXISTS sve_start_date;
ALTER TABLE mlb_coll_ext ADD sve_start_date timestamp without time zone;

ALTER TABLE mlb_coll_ext DROP COLUMN IF EXISTS sve_identifier;
ALTER TABLE mlb_coll_ext ADD sve_identifier character varying(255);

-- ************************************************************************* --
--                               sve mlb_doctype_ext                         --
-- ************************************************************************* --

ALTER TABLE mlb_doctype_ext DROP COLUMN IF EXISTS process_mode;
ALTER TABLE mlb_doctype_ext ADD process_mode character varying(255);

-- ************************************************************************* --
--                           NEW COLUMNS FOR CONTACTS                        --
-- ************************************************************************* --

ALTER TABLE contacts_res DROP COLUMN IF EXISTS mode;
ALTER TABLE contacts_res ADD mode character varying NOT NULL DEFAULT 'multi'::character varying;


-- ************************************************************************* --
--                      CHANGE COLUMNS TYPE FOR NOTE                         --
-- ************************************************************************* --
ALTER TABLE notes ALTER COLUMN date_note TYPE timestamp without time zone;

-- ************************************************************************* --
--                      CHANGE COLUMNS TYPE FOR SENDMAIL                     --
-- ************************************************************************* --
ALTER TABLE sendmail ALTER COLUMN to_list TYPE text;
ALTER TABLE sendmail ALTER COLUMN cc_list TYPE text;
ALTER TABLE sendmail ALTER COLUMN cci_list TYPE text;


-- ************************************************************************* --
--                      NOUVEAU STATUS TRANSMISSION                          --
-- ************************************************************************* --
DELETE FROM STATUS where id ='EXP_RTURN';
INSERT INTO status (id, label_status, is_system, is_folder_status, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES('EXP_RTURN', 'Retour attendu', 'N', 'N', '', 'apps','Y', 'Y');
DELETE FROM STATUS where id ='NO_RTURN';
INSERT INTO status (id, label_status, is_system, is_folder_status, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES('NO_RTURN', 'Pas de retour', 'N', 'N', '', 'apps','Y', 'Y');
DELETE FROM STATUS where id ='RTURN';
INSERT INTO status (id, label_status, is_system, is_folder_status, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES('RTURN', 'Retourné', 'N', 'N', '', 'apps','Y', 'Y');

-- view for letterbox
CREATE VIEW res_view_letterbox AS
    SELECT r.tablename, r.is_multi_docservers, r.res_id, r.type_id, r.policy_id, r.cycle_id, 
    d.description AS type_label, d.doctypes_first_level_id,
    dfl.doctypes_first_level_label, dfl.css_style as doctype_first_level_style,
    d.doctypes_second_level_id, dsl.doctypes_second_level_label,
    dsl.css_style as doctype_second_level_style, r.format, r.typist,
    r.creation_date, r.modification_date, r.relation, r.docserver_id, r.folders_system_id,
    f.folder_id, f.destination as folder_destination, f.is_frozen as folder_is_frozen, r.path, r.filename, r.fingerprint, r.offset_doc, r.filesize,
    r.status, r.work_batch, r.arbatch_id, r.arbox_id, r.page_count, r.is_paper,
    r.doc_date, r.scan_date, r.scan_user, r.scan_location, r.scan_wkstation,
    r.scan_batch, r.doc_language, r.description, r.source, r.author, r.reference_number,
    r.custom_t1 AS doc_custom_t1, r.custom_t2 AS doc_custom_t2,
    r.custom_t3 AS doc_custom_t3, r.custom_t4 AS doc_custom_t4,
    r.custom_t5 AS doc_custom_t5, r.custom_t6 AS doc_custom_t6,
    r.custom_t7 AS doc_custom_t7, r.custom_t8 AS doc_custom_t8,
    r.custom_t9 AS doc_custom_t9, r.custom_t10 AS doc_custom_t10,
    r.custom_t11 AS doc_custom_t11, r.custom_t12 AS doc_custom_t12,
    r.custom_t13 AS doc_custom_t13, r.custom_t14 AS doc_custom_t14,
    r.custom_t15 AS doc_custom_t15, r.custom_d1 AS doc_custom_d1,
    r.custom_d2 AS doc_custom_d2, r.custom_d3 AS doc_custom_d3,
    r.custom_d4 AS doc_custom_d4, r.custom_d5 AS doc_custom_d5,
    r.custom_d6 AS doc_custom_d6, r.custom_d7 AS doc_custom_d7,
    r.custom_d8 AS doc_custom_d8, r.custom_d9 AS doc_custom_d9,
    r.custom_d10 AS doc_custom_d10, r.custom_n1 AS doc_custom_n1,
    r.custom_n2 AS doc_custom_n2, r.custom_n3 AS doc_custom_n3,
    r.custom_n4 AS doc_custom_n4, r.custom_n5 AS doc_custom_n5,
    r.custom_f1 AS doc_custom_f1, r.custom_f2 AS doc_custom_f2,
    r.custom_f3 AS doc_custom_f3, r.custom_f4 AS doc_custom_f4,
    r.custom_f5 AS doc_custom_f5, f.foldertype_id, ft.foldertype_label,
    f.custom_t1 AS fold_custom_t1, f.custom_t2 AS fold_custom_t2,
    f.custom_t3 AS fold_custom_t3, f.custom_t4 AS fold_custom_t4,
    f.custom_t5 AS fold_custom_t5, f.custom_t6 AS fold_custom_t6,
    f.custom_t7 AS fold_custom_t7, f.custom_t8 AS fold_custom_t8,
    f.custom_t9 AS fold_custom_t9, f.custom_t10 AS fold_custom_t10,
    f.custom_t11 AS fold_custom_t11, f.custom_t12 AS fold_custom_t12,
    f.custom_t13 AS fold_custom_t13, f.custom_t14 AS fold_custom_t14,
    f.custom_t15 AS fold_custom_t15, f.custom_d1 AS fold_custom_d1,
    f.custom_d2 AS fold_custom_d2, f.custom_d3 AS fold_custom_d3,
    f.custom_d4 AS fold_custom_d4, f.custom_d5 AS fold_custom_d5,
    f.custom_d6 AS fold_custom_d6, f.custom_d7 AS fold_custom_d7,
    f.custom_d8 AS fold_custom_d8, f.custom_d9 AS fold_custom_d9,
    f.custom_d10 AS fold_custom_d10, f.custom_n1 AS fold_custom_n1,
    f.custom_n2 AS fold_custom_n2, f.custom_n3 AS fold_custom_n3,
    f.custom_n4 AS fold_custom_n4, f.custom_n5 AS fold_custom_n5,
    f.custom_f1 AS fold_custom_f1, f.custom_f2 AS fold_custom_f2,
    f.custom_f3 AS fold_custom_f3, f.custom_f4 AS fold_custom_f4,
    f.custom_f5 AS fold_custom_f5, f.is_complete AS fold_complete,
    f.status AS fold_status, f.subject AS fold_subject,
    f.parent_id AS fold_parent_id, f.folder_level, f.folder_name,
    f.creation_date AS fold_creation_date, r.initiator, r.destination,
    r.dest_user, r.confidentiality, mlb.category_id, mlb.exp_contact_id, mlb.exp_user_id,
    mlb.dest_user_id, mlb.dest_contact_id, mlb.address_id, mlb.nature_id, mlb.alt_identifier,
    mlb.admission_date, mlb.answer_type_bitmask, mlb.other_answer_desc,
    mlb.process_limit_date, mlb.closing_date, mlb.alarm1_date, mlb.alarm2_date,
    mlb.flag_notif, mlb.flag_alarm1, mlb.flag_alarm2, mlb.is_multicontacts, r.video_user, r.video_time,
    r.video_batch, r.subject, r.identifier, r.title, r.priority, mlb.process_notes,
    mlb.sve_start_date, mlb.sve_identifier,
    r.locker_user_id, r.locker_time,
    ca.case_id, ca.case_label, ca.case_description, en.entity_label, en.entity_type AS entityType,
    cont.contact_id AS contact_id,
    cont.firstname AS contact_firstname, cont.lastname AS contact_lastname,
    cont.society AS contact_society, u.lastname AS user_lastname,
    u.firstname AS user_firstname, list.item_id AS dest_user_from_listinstance, list.viewed, 
    r.is_frozen as res_is_frozen, COALESCE(att.count_attachment, 0::bigint) AS count_attachment 
    FROM doctypes d, doctypes_first_level dfl, doctypes_second_level dsl,
    (((((((((((ar_batch a RIGHT JOIN res_letterbox r ON ((r.arbatch_id = a.arbatch_id)))
    LEFT JOIN (SELECT res_attachments.res_id_master, count(res_attachments.res_id_master) AS count_attachment
        FROM res_attachments WHERE res_attachments.status <> 'DEL' GROUP BY res_attachments.res_id_master) att ON (r.res_id = att.res_id_master))
    LEFT JOIN entities en ON (((r.destination)::text = (en.entity_id)::text)))
    LEFT JOIN folders f ON ((r.folders_system_id = f.folders_system_id)))
    LEFT JOIN cases_res cr ON ((r.res_id = cr.res_id)))
    LEFT JOIN mlb_coll_ext mlb ON ((mlb.res_id = r.res_id)))
    LEFT JOIN foldertypes ft ON (((f.foldertype_id = ft.foldertype_id)
        AND ((f.status)::text <> 'DEL'::text))))
    LEFT JOIN cases ca ON ((cr.case_id = ca.case_id)))
    LEFT JOIN contacts_v2 cont ON (((mlb.exp_contact_id = cont.contact_id)
        OR (mlb.dest_contact_id = cont.contact_id))))
    LEFT JOIN users u ON ((((mlb.exp_user_id)::text = (u.user_id)::text)
        OR ((mlb.dest_user_id)::text = (u.user_id)::text))))
    LEFT JOIN listinstance list ON (((r.res_id = list.res_id)
        AND ((list.item_mode)::text = 'dest'::text))))
    WHERE (((r.type_id = d.type_id) AND
    (d.doctypes_first_level_id = dfl.doctypes_first_level_id))
    AND (d.doctypes_second_level_id = dsl.doctypes_second_level_id));

-- view for business
CREATE VIEW res_view_business AS
    SELECT r.tablename, r.is_multi_docservers, r.res_id, r.type_id,
    d.description AS type_label, d.doctypes_first_level_id,
    d.doctypes_second_level_id, dfl.doctypes_first_level_label, 
    dfl.css_style as doctype_first_level_style,
    dsl.doctypes_second_level_label,
    dsl.css_style as doctype_second_level_style, r.format, r.typist,
    r.creation_date, r.relation, r.docserver_id, r.folders_system_id,
    f.folder_id, f.is_frozen as folder_is_frozen, r.path, r.filename, 
    r.fingerprint, r.offset_doc, r.filesize,
    r.status, r.work_batch, r.arbatch_id, r.arbox_id, r.page_count, r.is_paper,
    r.doc_date, r.scan_date, r.scan_user, r.scan_location, r.scan_wkstation,
    r.scan_batch, r.doc_language, r.description, r.source, r.author, r.reference_number,
    r.custom_t1 AS doc_custom_t1, r.custom_t2 AS doc_custom_t2,
    r.custom_t3 AS doc_custom_t3, r.custom_t4 AS doc_custom_t4,
    r.custom_t5 AS doc_custom_t5, r.custom_t6 AS doc_custom_t6,
    r.custom_t7 AS doc_custom_t7, r.custom_t8 AS doc_custom_t8,
    r.custom_t9 AS doc_custom_t9, r.custom_t10 AS doc_custom_t10,
    r.custom_t11 AS doc_custom_t11, r.custom_t12 AS doc_custom_t12,
    r.custom_t13 AS doc_custom_t13, r.custom_t14 AS doc_custom_t14,
    r.custom_t15 AS doc_custom_t15, r.custom_d1 AS doc_custom_d1,
    r.custom_d2 AS doc_custom_d2, r.custom_d3 AS doc_custom_d3,
    r.custom_d4 AS doc_custom_d4, r.custom_d5 AS doc_custom_d5,
    r.custom_d6 AS doc_custom_d6, r.custom_d7 AS doc_custom_d7,
    r.custom_d8 AS doc_custom_d8, r.custom_d9 AS doc_custom_d9,
    r.custom_d10 AS doc_custom_d10, r.custom_n1 AS doc_custom_n1,
    r.custom_n2 AS doc_custom_n2, r.custom_n3 AS doc_custom_n3,
    r.custom_n4 AS doc_custom_n4, r.custom_n5 AS doc_custom_n5,
    r.custom_f1 AS doc_custom_f1, r.custom_f2 AS doc_custom_f2,
    r.custom_f3 AS doc_custom_f3, r.custom_f4 AS doc_custom_f4,
    r.custom_f5 AS doc_custom_f5, f.foldertype_id,
    f.custom_t1 AS fold_custom_t1, f.custom_t2 AS fold_custom_t2,
    f.custom_t3 AS fold_custom_t3, f.custom_t4 AS fold_custom_t4,
    f.custom_t5 AS fold_custom_t5, f.custom_t6 AS fold_custom_t6,
    f.custom_t7 AS fold_custom_t7, f.custom_t8 AS fold_custom_t8,
    f.custom_t9 AS fold_custom_t9, f.custom_t10 AS fold_custom_t10,
    f.custom_t11 AS fold_custom_t11, f.custom_t12 AS fold_custom_t12,
    f.custom_t13 AS fold_custom_t13, f.custom_t14 AS fold_custom_t14,
    f.custom_t15 AS fold_custom_t15, f.custom_d1 AS fold_custom_d1,
    f.custom_d2 AS fold_custom_d2, f.custom_d3 AS fold_custom_d3,
    f.custom_d4 AS fold_custom_d4, f.custom_d5 AS fold_custom_d5,
    f.custom_d6 AS fold_custom_d6, f.custom_d7 AS fold_custom_d7,
    f.custom_d8 AS fold_custom_d8, f.custom_d9 AS fold_custom_d9,
    f.custom_d10 AS fold_custom_d10, f.custom_n1 AS fold_custom_n1,
    f.custom_n2 AS fold_custom_n2, f.custom_n3 AS fold_custom_n3,
    f.custom_n4 AS fold_custom_n4, f.custom_n5 AS fold_custom_n5,
    f.custom_f1 AS fold_custom_f1, f.custom_f2 AS fold_custom_f2,
    f.custom_f3 AS fold_custom_f3, f.custom_f4 AS fold_custom_f4,
    f.custom_f5 AS fold_custom_f5, f.is_complete AS fold_complete,
    f.status AS fold_status, f.subject AS fold_subject,
    f.parent_id AS fold_parent_id, f.folder_level, f.folder_name,
    f.creation_date AS fold_creation_date, r.initiator, r.destination,
    r.dest_user, busi.category_id, busi.contact_id, busi.address_id, busi.currency,
    r.locker_user_id, r.locker_time,  
    busi.net_sum, busi.tax_sum, busi.total_sum, 
    busi.process_limit_date, busi.closing_date, busi.alarm1_date, busi.alarm2_date,
    busi.flag_notif, busi.flag_alarm1, busi.flag_alarm2, r.video_user, r.video_time,
    r.video_batch, r.subject, r.identifier, r.title, r.priority,
    en.entity_label,
    cont.firstname AS contact_firstname, cont.lastname AS contact_lastname,
    cont.society AS contact_society, list.item_id AS dest_user_from_listinstance,  list.viewed, 
    r.is_frozen as res_is_frozen, COALESCE(att.count_attachment, 0::bigint) AS count_attachment 
    FROM doctypes d, doctypes_first_level dfl, doctypes_second_level dsl, res_business r
    LEFT JOIN (SELECT res_attachments.res_id_master, coll_id, count(res_attachments.res_id_master) AS count_attachment
        FROM res_attachments WHERE res_attachments.status <> 'DEL' GROUP BY res_attachments.res_id_master, coll_id) att ON (r.res_id = att.res_id_master and att.coll_id = 'business_coll')
    LEFT JOIN entities en ON ((r.destination)::text = (en.entity_id)::text)
    LEFT JOIN folders f ON ((r.folders_system_id = f.folders_system_id))
    LEFT JOIN business_coll_ext busi ON (busi.res_id = r.res_id)
    LEFT JOIN contacts_v2 cont ON (busi.contact_id = cont.contact_id)
    LEFT JOIN listinstance list ON ((r.res_id = list.res_id)
        AND ((list.item_mode)::text = 'dest'::text))
    WHERE r.type_id = d.type_id 
    AND d.doctypes_first_level_id = dfl.doctypes_first_level_id
    AND d.doctypes_second_level_id = dsl.doctypes_second_level_id;

--view for res_view
CREATE VIEW res_view AS
 SELECT r.tablename, r.is_multi_docservers, r.res_id, r.title, r.subject, r.page_count, r.identifier, r.doc_date, r.type_id,
 d.description AS type_label, d.doctypes_first_level_id, dfl.doctypes_first_level_label, dfl.css_style as doctype_first_level_style,
 d.doctypes_second_level_id, dsl.doctypes_second_level_label, dsl.css_style as doctype_second_level_style,
 r.format, r.typist, r.creation_date, r.relation, r.docserver_id,
 r.folders_system_id, r.path, r.filename, r.fingerprint, r.offset_doc, r.filesize, r.status,
 r.work_batch, r.arbatch_id, r.arbox_id,  r.is_paper, r.scan_date, r.scan_user,r.scan_location,r.scan_wkstation,
 r.scan_batch,r.doc_language,r.description,r.source,r.initiator,r.destination,r.dest_user,r.policy_id,r.cycle_id,r.cycle_date,
 r.custom_t1 AS doc_custom_t1, r.custom_t2 AS doc_custom_t2, r.custom_t3 AS doc_custom_t3,
 r.custom_t4 AS doc_custom_t4, r.custom_t5 AS doc_custom_t5, r.custom_t6 AS doc_custom_t6,
 r.custom_t7 AS doc_custom_t7, r.custom_t8 AS doc_custom_t8, r.custom_t9 AS doc_custom_t9,
 r.custom_t10 AS doc_custom_t10, r.custom_t11 AS doc_custom_t11, r.custom_t12 AS doc_custom_t12,
 r.custom_t13 AS doc_custom_t13, r.custom_t14 AS doc_custom_t14, r.custom_t15 AS doc_custom_t15,
 r.custom_d1 AS doc_custom_d1, r.custom_d2 AS doc_custom_d2, r.custom_d3 AS doc_custom_d3,
 r.custom_d4 AS doc_custom_d4, r.custom_d5 AS doc_custom_d5, r.custom_d6 AS doc_custom_d6,
 r.custom_d7 AS doc_custom_d7, r.custom_d8 AS doc_custom_d8, r.custom_d9 AS doc_custom_d9,
 r.custom_d10 AS doc_custom_d10, r.custom_n1 AS doc_custom_n1, r.custom_n2 AS doc_custom_n2,
 r.custom_n3 AS doc_custom_n3, r.custom_n4 AS doc_custom_n4, r.custom_n5 AS doc_custom_n5,
 r.custom_f1 AS doc_custom_f1, r.custom_f2 AS doc_custom_f2, r.custom_f3 AS doc_custom_f3,
 r.custom_f4 AS doc_custom_f4, r.custom_f5 AS doc_custom_f5, r.is_frozen as res_is_frozen,  
 r.reference_number, r.locker_user_id, r.locker_time
   FROM  doctypes d, doctypes_first_level dfl, doctypes_second_level dsl, res_x r
   WHERE r.type_id = d.type_id
   AND d.doctypes_first_level_id = dfl.doctypes_first_level_id
   AND d.doctypes_second_level_id = dsl.doctypes_second_level_id;


--view for postindexing
CREATE VIEW view_postindexing AS
SELECT res_view_letterbox.video_user, (users.firstname::text || ' '::text) || users.lastname::text AS user_name, res_view_letterbox.video_batch, res_view_letterbox.video_time, count(res_view_letterbox.res_id) AS count_documents, res_view_letterbox.folders_system_id, (folders.folder_id::text || ' / '::text) || folders.folder_name::text AS folder_full_label, folders.video_status
FROM res_view_letterbox
LEFT JOIN users ON res_view_letterbox.video_user::text = users.user_id::text
LEFT JOIN folders ON folders.folders_system_id = res_view_letterbox.folders_system_id
WHERE res_view_letterbox.video_batch IS NOT NULL
GROUP BY res_view_letterbox.video_user, (users.firstname::text || ' '::text) || users.lastname::text, res_view_letterbox.video_batch, res_view_letterbox.video_time, res_view_letterbox.folders_system_id, (folders.folder_id::text || ' / '::text) || folders.folder_name::text, folders.video_status;


--view for contacts_v2
CREATE VIEW view_contacts AS 
 SELECT c.contact_id, c.contact_type, c.is_corporate_person, c.society, c.society_short, c.firstname AS contact_firstname
, c.lastname AS contact_lastname, c.title AS contact_title, c.function AS contact_function, c.other_data AS contact_other_data
, c.user_id AS contact_user_id, c.entity_id AS contact_entity_id, c.creation_date, c.update_date, c.enabled AS contact_enabled, ca.id AS ca_id
, ca.contact_purpose_id, ca.departement, ca.firstname, ca.lastname, ca.title, ca.function, ca.occupancy
, ca.address_num, ca.address_street, ca.address_complement, ca.address_town, ca.address_postal_code, ca.address_country
, ca.phone, ca.email, ca.website, ca.salutation_header, ca.salutation_footer, ca.other_data, ca.user_id, ca.entity_id, ca.is_private, ca.enabled
, cp.label as contact_purpose_label, ct.label as contact_type_label
   FROM contacts_v2 c
   RIGHT JOIN contact_addresses ca ON c.contact_id = ca.contact_id
   LEFT JOIN contact_purposes cp ON ca.contact_purpose_id = cp.id
   LEFT JOIN contact_types ct ON c.contact_type = ct.id;

-- view for attachments
CREATE VIEW res_view_attachments AS
  SELECT '0' as res_id, res_id as res_id_version, title, subject, description, publisher, contributor, type_id, format, typist,
  creation_date, fulltext_result, ocr_result, author, author_name, identifier, source,
  doc_language, relation, coverage, doc_date, docserver_id, folders_system_id, arbox_id, path,
  filename, offset_doc, logical_adr, fingerprint, filesize, is_paper, page_count,
  scan_date, scan_user, scan_location, scan_wkstation, scan_batch, burn_batch, scan_postmark,
  envelop_id, status, destination, approver, validation_date, effective_date, work_batch, origin, is_ingoing, priority, initiator, dest_user,
  coll_id, dest_contact_id, dest_address_id, updated_by, is_multicontacts, is_multi_docservers, res_id_master, attachment_type, attachment_id_master
  FROM res_version_attachments
  UNION ALL
  SELECT res_id, '0' as res_id_version, title, subject, description, publisher, contributor, type_id, format, typist,
  creation_date, fulltext_result, ocr_result, author, author_name, identifier, source,
  doc_language, relation, coverage, doc_date, docserver_id, folders_system_id, arbox_id, path,
  filename, offset_doc, logical_adr, fingerprint, filesize, is_paper, page_count,
  scan_date, scan_user, scan_location, scan_wkstation, scan_batch, burn_batch, scan_postmark,
  envelop_id, status, destination, approver, validation_date, effective_date, work_batch, origin, is_ingoing, priority, initiator, dest_user,
  coll_id, dest_contact_id, dest_address_id, updated_by, is_multicontacts, is_multi_docservers, res_id_master, attachment_type, '0'
  FROM res_attachments;

   

-- ************************************************************************* --
--                               DATABASE VERSION                            --
-- ************************************************************************* --
UPDATE parameters SET param_value_int = 160 where id='database_version';



