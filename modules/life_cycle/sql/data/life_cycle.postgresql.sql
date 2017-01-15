--
-- PostgreSQL database dump
--

-- Started on 2010-10-13 16:57:54 CEST

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;

SET search_path = public, pg_catalog;

--
-- TOC entry 2120 (class 0 OID 17565)
-- Dependencies: 1828
-- Data for Name: lc_cycle_steps; Type: TABLE DATA; Schema: public; Owner: postgres
--
INSERT INTO lc_policies (policy_id, policy_name, policy_desc) VALUES ('FNTC', 'FNTC standard archiving policy', '3 months fast cache, immediate double backup on AIP, final sort: offline after 10 years');

INSERT INTO lc_cycle_steps (policy_id, cycle_id, cycle_step_id, cycle_step_desc, docserver_type_id, is_allow_failure, step_operation, sequence_number, is_must_complete, preprocess_script, postprocess_script) VALUES ('FNTC', 'INIT', 'INIT', 'Initial location', 'FASTHD', 'N', 'NONE', 1, 'N', NULL, NULL);
INSERT INTO lc_cycle_steps (policy_id, cycle_id, cycle_step_id, cycle_step_desc, docserver_type_id, is_allow_failure, step_operation, sequence_number, is_must_complete, preprocess_script, postprocess_script) VALUES ('FNTC', 'OAIS_CACHED', 'COPY_MAIN', 'Immediate copy on main OAIS docserver', 'OAIS_MAIN', 'N', 'COPY', 1, 'Y', NULL, NULL);
INSERT INTO lc_cycle_steps (policy_id, cycle_id, cycle_step_id, cycle_step_desc, docserver_type_id, is_allow_failure, step_operation, sequence_number, is_must_complete, preprocess_script, postprocess_script) VALUES ('FNTC', 'OAIS_CACHED', 'COPY_SAFE', 'Immediate copy on main OAIS docserver', 'OAIS_SAFE', 'N', 'COPY', 2, 'Y', NULL, NULL);
INSERT INTO lc_cycle_steps (policy_id, cycle_id, cycle_step_id, cycle_step_desc, docserver_type_id, is_allow_failure, step_operation, sequence_number, is_must_complete, preprocess_script, postprocess_script) VALUES ('FNTC', 'OAIS', 'PURGE', 'Purge after 3 months', 'FASTHD', 'N', 'PURGE', 1, 'N', NULL, NULL);
INSERT INTO lc_cycle_steps (policy_id, cycle_id, cycle_step_id, cycle_step_desc, docserver_type_id, is_allow_failure, step_operation, sequence_number, is_must_complete, preprocess_script, postprocess_script) VALUES ('FNTC', 'DISPOSAL', 'FINAL_MAIN', 'Disposal', 'OAIS_MAIN', 'N', 'NONE', 1, 'N', NULL, NULL);

INSERT INTO lc_cycles (policy_id, cycle_id, cycle_desc, sequence_number, where_clause, break_key, validation_mode) VALUES ('FNTC', 'INIT', 'Initial location', 0, '1=1', 'doc_custom_t1', 'AUTO');
INSERT INTO lc_cycles (policy_id, cycle_id, cycle_desc, sequence_number, where_clause, break_key, validation_mode) VALUES ('FNTC', 'DISPOSAL', 'Disposal', 3, 'current_date >= creation_date::timestamp + interval ''10'' year', '', 'USER');
INSERT INTO lc_cycles (policy_id, cycle_id, cycle_desc, sequence_number, where_clause, break_key, validation_mode) VALUES ('FNTC', 'OAIS', 'FASTHD cache is purged. Resource lays only on OAIS docservers', 2, 'current_date >= creation_date::timestamp + interval ''3'' month', '' , 'AUTO');
INSERT INTO lc_cycles (policy_id, cycle_id, cycle_desc, sequence_number, where_clause, break_key, validation_mode) VALUES ('FNTC', 'OAIS_CACHED', 'Immediate copy on OAIS main and backup docservers. Resource is still present on FASTHD', 1, 'current_date >= creation_date::timestamp + interval ''7'' day', '', 'AUTO');

-- Completed on 2010-10-13 16:57:55 CEST

--
-- PostgreSQL database dump complete
--

