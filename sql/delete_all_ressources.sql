/* Warning : This script erase all data in the application Maarch. It keeps in database parameters */

TRUNCATE TABLE cases;
TRUNCATE TABLE cases_res;

TRUNCATE TABLE contacts_v2;
TRUNCATE TABLE contact_addresses;
TRUNCATE TABLE contact_types;
TRUNCATE TABLE contact_purposes;
TRUNCATE TABLE contacts_res;

TRUNCATE TABLE listinstance;

TRUNCATE TABLE history;
TRUNCATE TABLE history_batch;

TRUNCATE TABLE notes;
TRUNCATE TABLE note_entities;

TRUNCATE TABLE mlb_coll_ext;
TRUNCATE TABLE res_letterbox;
TRUNCATE TABLE res_version_letterbox;
TRUNCATE TABLE res_x;
TRUNCATE TABLE res_attachments;
TRUNCATE TABLE res_version_attachments;

TRUNCATE TABLE saved_queries;
TRUNCATE TABLE lc_stack;
TRUNCATE TABLE adr_x;

TRUNCATE TABLE tags;

TRUNCATE TABLE sendmail;

TRUNCATE TABLE notif_event_stack;
TRUNCATE TABLE notif_email_stack;