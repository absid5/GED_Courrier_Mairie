DROP SEQUENCE IF EXISTS fp_fileplan_positions_position_id_seq;
CREATE SEQUENCE fp_fileplan_positions_position_id_seq
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 10
  CACHE 1;

DROP TABLE IF EXISTS fp_fileplan;
CREATE TABLE fp_fileplan
(
  fileplan_id serial NOT NULL,
  fileplan_label character varying(255),
  user_id character varying(128) DEFAULT NULL,
  entity_id character varying(32) DEFAULT NULL,
  is_serial_id character varying(1) NOT NULL DEFAULT 'Y', 
  enabled character varying(1) NOT NULL DEFAULT 'Y',
  CONSTRAINT fp_fileplan_pkey PRIMARY KEY (fileplan_id)
 );
 
DROP TABLE IF EXISTS fp_fileplan_positions;
CREATE TABLE fp_fileplan_positions 
(
  position_id character varying(32) NOT NULL,
  position_label character varying(255),
  parent_id character varying(32) DEFAULT NULL,
  fileplan_id bigint NOT NULL,
  enabled character varying(1) NOT NULL DEFAULT 'Y',
  CONSTRAINT fp_fileplan_positions_pkey PRIMARY KEY (fileplan_id, position_id)
);

DROP TABLE IF EXISTS fp_res_fileplan_positions;
CREATE TABLE fp_res_fileplan_positions 
(
  res_id bigint NOT NULL,
  coll_id character varying(32) NOT NULL,
  fileplan_id bigint NOT NULL,
  position_id character varying(32) NOT NULL,
  CONSTRAINT fp_res_fileplan_positions_pkey PRIMARY KEY (res_id, coll_id, fileplan_id, position_id)
);

CREATE OR REPLACE VIEW fp_view_fileplan AS 
 SELECT fp_fileplan.fileplan_id, fp_fileplan.fileplan_label, 
    fp_fileplan.user_id, fp_fileplan.entity_id, fp_fileplan.enabled, 
    fp_fileplan_positions.position_id, fp_fileplan_positions.position_label, 
    fp_fileplan_positions.parent_id, 
    fp_fileplan_positions.enabled AS position_enabled, 
    COALESCE(r.count_document, 0::bigint) AS count_document
   FROM fp_fileplan, 
    fp_fileplan_positions
   LEFT JOIN ( SELECT fp_res_fileplan_positions.position_id, 
            count(fp_res_fileplan_positions.res_id) AS count_document
           FROM fp_res_fileplan_positions
          GROUP BY fp_res_fileplan_positions.position_id) r ON r.position_id::text = fp_fileplan_positions.position_id::text
  WHERE fp_fileplan.fileplan_id = fp_fileplan_positions.fileplan_id;