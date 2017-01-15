ALTER TABLE listinstance ADD process_date timestamp without time zone;
ALTER TABLE listinstance ADD process_comment character varying(255);

ALTER TABLE listinstance_history_details ADD process_date timestamp without time zone;
ALTER TABLE listinstance_history_details ADD process_comment character varying(255);


ALTER TABLE listmodels ADD process_comment character varying(255);