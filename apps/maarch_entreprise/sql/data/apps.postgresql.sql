-- Maarch Entreprise 1.2 sample data : Application

-- USERS, GROUPS and ENTITIES
INSERT INTO usergroups (group_id, group_desc, administrator, custom_right1, custom_right2, custom_right3, custom_right4, enabled) VALUES ('ADMINS', 'Administrateurs fonctionnels', ' ', ' ', ' ', ' ', ' ', 'Y');
INSERT INTO usergroups (group_id, group_desc, administrator, custom_right1, custom_right2, custom_right3, custom_right4, enabled) VALUES ('ARCHIVISTS', 'Archivistes et operateurs de scan', ' ', ' ', ' ', ' ', ' ', 'Y');
INSERT INTO usergroups (group_id, group_desc, administrator, custom_right1, custom_right2, custom_right3, custom_right4, enabled) VALUES ('EMPLOYEES', 'Employés', ' ', ' ', ' ', ' ', ' ', 'Y');
INSERT INTO usergroups (group_id, group_desc, administrator, custom_right1, custom_right2, custom_right3, custom_right4, enabled) VALUES ('MANAGERS', 'Directeurs et personnes habilitées', ' ', ' ', ' ', ' ', ' ', 'Y');
INSERT INTO usergroups (group_id, group_desc, administrator, custom_right1, custom_right2, custom_right3, custom_right4, enabled) VALUES ('TYPISTS', 'Groupe des créateurs de courriers', ' ', ' ', ' ', ' ', ' ', 'Y');
INSERT INTO usergroups (group_id, group_desc, administrator, custom_right1, custom_right2, custom_right3, custom_right4, enabled) VALUES ('CORRESPONDANTS', 'Correspondants Archive', ' ', ' ', ' ', ' ', ' ', 'Y');

INSERT INTO users (user_id, "password", firstname, lastname, phone, mail, department, custom_t1, custom_t2, custom_t3, cookie_key, cookie_date, enabled, change_password, delay_number, status, loginmode) VALUES ('ccharles', 'ef9689be896dacd901cae4f13593e90d', 'Charlotte', 'CHARLES', '+33 1 47 24 51', 'info@maarch.org', '', NULL, NULL, NULL, '2b67f8017119d7de32f300be3e97ccb4', '2008-09-10 15:09:23', 'Y', 'N', NULL, 'OK','standard');
INSERT INTO users (user_id, "password", firstname, lastname, phone, mail, department, custom_t1, custom_t2, custom_t3, cookie_key, cookie_date, enabled, change_password, delay_number, status, loginmode) VALUES ('ppetit', 'ef9689be896dacd901cae4f13593e90d', 'Patricia', 'PETIT', '+33 1 47 24 51', 'info@maarch.org', '', NULL, NULL, NULL, '', NULL, 'Y', 'N', NULL, 'OK','standard');
INSERT INTO users (user_id, "password", firstname, lastname, phone, mail, department, custom_t1, custom_t2, custom_t3, cookie_key, cookie_date, enabled, change_password, delay_number, status, loginmode) VALUES ('pparker', 'ef9689be896dacd901cae4f13593e90d', 'Peter', 'PARKER', '+33 1 47 24 51', 'info@maarch.org', '', '0', NULL, NULL, '', NULL, 'Y', 'N', NULL, 'OK','standard');
INSERT INTO users (user_id, "password", firstname, lastname, phone, mail, department, custom_t1, custom_t2, custom_t3, cookie_key, cookie_date, enabled, change_password, delay_number, status, loginmode) VALUES ('eerina', 'ef9689be896dacd901cae4f13593e90d', 'Edith', 'ERINA', '+33 1 47 24 51', 'info@maarch.org', '', '0', NULL, NULL, '', NULL, 'Y', 'N', NULL, 'OK','standard');
INSERT INTO users (user_id, "password", firstname, lastname, phone, mail, department, custom_t1, custom_t2, custom_t3, cookie_key, cookie_date, enabled, change_password, delay_number, status, loginmode) VALUES ('ttong', 'ef9689be896dacd901cae4f13593e90d', 'Tony', 'TONG', '+33 1 47 24 51', 'info@maarch.org', '', '0', NULL, NULL, '', NULL, 'Y', 'N', NULL, 'OK','standard');
INSERT INTO users (user_id, "password", firstname, lastname, phone, mail, department, custom_t1, custom_t2, custom_t3, cookie_key, cookie_date, enabled, change_password, delay_number, status, loginmode) VALUES ('bboule', 'ef9689be896dacd901cae4f13593e90d', 'Bruno', 'BOULE', '+33 1 47 24 51', 'info@maarch.org', '', '0', NULL, NULL, '', NULL, 'Y', 'N', NULL, 'OK','standard');
INSERT INTO users (user_id, "password", firstname, lastname, phone, mail, department, custom_t1, custom_t2, custom_t3, cookie_key, cookie_date, enabled, change_password, delay_number, status, loginmode) VALUES ('bbain', 'ef9689be896dacd901cae4f13593e90d', 'Barbara', 'BAIN', '+33 1 47 24 51', 'info@maarch.org', '', '0', NULL, NULL, '', NULL, 'Y', 'N', NULL, 'OK','standard');
INSERT INTO users (user_id, "password", firstname, lastname, phone, mail, department, custom_t1, custom_t2, custom_t3, cookie_key, cookie_date, enabled, change_password, delay_number, status, loginmode) VALUES ('ssaporta', 'ef9689be896dacd901cae4f13593e90d', 'Sabrina', 'SAPORTA', '+33 1 47 24 51', 'info@maarch.org', '', '0', NULL, NULL, '', NULL, 'Y', 'N', NULL, 'OK','standard');
INSERT INTO users (user_id, "password", firstname, lastname, phone, mail, department, custom_t1, custom_t2, custom_t3, cookie_key, cookie_date, enabled, change_password, delay_number, status, loginmode) VALUES ('sstarr', 'ef9689be896dacd901cae4f13593e90d', 'Suzanne', 'STARR', '+33 1 47 24 51', 'info@maarch.org', '', '0', NULL, NULL, '', NULL, 'Y', 'N', NULL, 'OK','standard');
INSERT INTO users (user_id, "password", firstname, lastname, phone, mail, department, custom_t1, custom_t2, custom_t3, cookie_key, cookie_date, enabled, change_password, delay_number, status, loginmode) VALUES ('ssissoko', 'ef9689be896dacd901cae4f13593e90d', 'Sessime', 'SISSOKO', '+33 1 47 24 51', 'info@maarch.org', '', '0', NULL, NULL, '', NULL, 'Y', 'N', NULL, 'OK','standard');
INSERT INTO users (user_id, "password", firstname, lastname, phone, mail, department, custom_t1, custom_t2, custom_t3, cookie_key, cookie_date, enabled, change_password, delay_number, status, loginmode) VALUES ('ddogem', 'ef9689be896dacd901cae4f13593e90d', 'Dina', 'DOGEM', '+33 1 47 24 51', 'info@maarch.org', '', '0', NULL, NULL, '', NULL, 'Y', 'N', NULL, 'OK','standard');
INSERT INTO users (user_id, "password", firstname, lastname, phone, mail, department, custom_t1, custom_t2, custom_t3, cookie_key, cookie_date, enabled, change_password, delay_number, status, loginmode) VALUES ('superadmin', '17c4520f6cfd1ab53d8745e84681eb49', 'Super', 'ADMIN', '+33 1 47 24 51', 'admin@maarch.org', 'Maarch', '11', NULL, NULL, '764759df274008fc4cffd89ced0449d8', '2009-09-14 10:09:52', 'Y', 'N', NULL, 'OK','standard');
INSERT INTO users (user_id, "password", firstname, lastname, phone, mail, department, custom_t1, custom_t2, custom_t3, cookie_key, cookie_date, enabled, change_password, delay_number, status, loginmode) VALUES ('bblier', 'ef9689be896dacd901cae4f13593e90d', 'Bernard', 'BLIER', '+33 1 47 24 51 ', 'info@maarch.org', '', NULL, NULL, NULL, '053123818f126439a94ce074acf71b92', '2009-09-14 11:09:04', 'Y', 'N', NULL, 'OK','standard');

INSERT INTO usergroup_content (user_id, group_id, primary_group, "role") VALUES ('ppetit', 'MANAGERS', 'Y', 'Directrice Générale');
INSERT INTO usergroup_content (user_id, group_id, primary_group, "role") VALUES ('eerina', 'EMPLOYEES', 'Y', 'Assistante');
INSERT INTO usergroup_content (user_id, group_id, primary_group, "role") VALUES ('bbain', 'MANAGERS', 'Y', 'Responsable des Opérations');
INSERT INTO usergroup_content (user_id, group_id, primary_group, "role") VALUES ('ccharles', 'MANAGERS', 'Y', 'Directrice financière');
INSERT INTO usergroup_content (user_id, group_id, primary_group, "role") VALUES ('pparker', 'MANAGERS', 'Y', 'Responsable RH');
INSERT INTO usergroup_content (user_id, group_id, primary_group, "role") VALUES ('ddogem', 'EMPLOYEES', 'Y', 'Chargée de clientèle');
INSERT INTO usergroup_content (user_id, group_id, primary_group, "role") VALUES ('ttong', 'EMPLOYEES', 'Y', 'Superviseur');
INSERT INTO usergroup_content (user_id, group_id, primary_group, "role") VALUES ('sstarr', 'EMPLOYEES', 'Y', 'Gestionnaire');
INSERT INTO usergroup_content (user_id, group_id, primary_group, "role") VALUES ('ssaporta', 'EMPLOYEES', 'Y', 'Gestionnaire');
INSERT INTO usergroup_content (user_id, group_id, primary_group, "role") VALUES ('ssissoko', 'EMPLOYEES', 'Y', 'Directeur Informatique');
INSERT INTO usergroup_content (user_id, group_id, primary_group, "role") VALUES ('ssissoko', 'ADMINS', 'N', 'Administrateur du système');
INSERT INTO usergroup_content (user_id, group_id, primary_group, "role") VALUES ('bblier', 'TYPISTS', 'Y', 'Responsable service Courrier');
INSERT INTO usergroup_content (user_id, group_id, primary_group, "role") VALUES ('bblier', 'ARCHIVISTS', 'N', 'Archiviste');
INSERT INTO usergroup_content (user_id, group_id, primary_group, "role") VALUES ('bblier', 'EMPLOYEES', 'N', 'Employé');
INSERT INTO usergroup_content (user_id, group_id, primary_group, "role") VALUES ('bboule', 'EMPLOYEES', 'Y', 'Comptable');
INSERT INTO usergroup_content (user_id, group_id, primary_group, "role") VALUES ('bboule', 'CORRESPONDANTS', 'N', 'Correspondant Archives');

INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINS', 'admin');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINS', 'admin_users');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINS', 'admin_groups');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINS', 'admin_architecture');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINS', 'view_history');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINS', 'view_history_batch');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINS', 'xml_param_services');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINS', 'admin_status');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINS', 'admin_actions');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINS', 'admin_contacts');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINS', 'admin_apa');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINS', 'admin_baskets');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINS', 'manage_entities');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINS', 'admin_foldertypes');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINS', 'admin_templates');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINS', 'print_details');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ADMINS', 'view_technical_infos');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ARCHIVISTS', 'admin');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ARCHIVISTS', 'admin_apa');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ARCHIVISTS', 'manage_apa');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ARCHIVISTS', 'view_baskets');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ARCHIVISTS', 'physical_archive');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ARCHIVISTS', 'physical_archive_box_read');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ARCHIVISTS', 'physical_archive_box_manage');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ARCHIVISTS', 'physical_archive_batch_read');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ARCHIVISTS', 'physical_archive_batch_manage');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('ARCHIVISTS', '_print_sep');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('EMPLOYEES', 'adv_search_mlb');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('EMPLOYEES', 'search_customer');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('EMPLOYEES', 'my_alerts');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('EMPLOYEES', 'use_alerts_on_doc');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('EMPLOYEES', 'use_alerts_on_folder');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('EMPLOYEES', 'view_baskets');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('EMPLOYEES', 'add_copy_in_process');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('EMPLOYEES', 'folder_search');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('EMPLOYEES', 'modify_folder');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('EMPLOYEES', 'update_case');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('EMPLOYEES', 'join_res_case');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('EMPLOYEES', 'join_res_case_in_process');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('EMPLOYEES', 'close_case');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('EMPLOYEES', 'print_details');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('EMPLOYEES', 'view_technical_infos');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('MANAGERS', 'adv_search_mlb');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('MANAGERS', 'search_customer');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('MANAGERS', 'my_alerts');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('MANAGERS', 'use_alerts_on_doc');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('MANAGERS', 'use_alerts_on_folder');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('MANAGERS', 'view_baskets');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('MANAGERS', 'add_copy_in_process');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('MANAGERS', 'folder_search');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('MANAGERS', 'modify_folder');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('MANAGERS', 'delete_folder');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('MANAGERS', 'print_details');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('MANAGERS', 'view_technical_infos');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('TYPISTS', 'index_mlb');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('TYPISTS', 'my_contacts');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('TYPISTS', 'view_baskets');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('TYPISTS', 'add_copy_in_process');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('TYPISTS', 'print_details');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('TYPISTS', 'view_technical_infos');
INSERT INTO usergroups_services (group_id, service_id) VALUES ('CORRESPONDANTS', 'reserve_apa');

INSERT INTO entities (entity_id, entity_label, short_label, enabled, adrs_1, adrs_2, adrs_3, zipcode, city, country, email, business_id, parent_entity_id, entity_type) VALUES ('DIR', 'Organisation ACME', 'ACME', 'Y', '', '', '', '', '', '', '', '', '', 'Direction');
INSERT INTO entities (entity_id, entity_label, short_label, enabled, adrs_1, adrs_2, adrs_3, zipcode, city, country, email, business_id, parent_entity_id, entity_type) VALUES ('OPE', 'Direction des Opérations Grands Comptes et Particuliers', 'Direction Opérations', 'Y', '', '', '', '', '', '', '', '', 'DIR', 'Direction');
INSERT INTO entities (entity_id, entity_label, short_label, enabled, adrs_1, adrs_2, adrs_3, zipcode, city, country, email, business_id, parent_entity_id, entity_type) VALUES ('FIN', 'Direction Financière', 'Direction Financière', 'Y', '', '', '', '', '', '', '', '', 'DIR', 'Direction');
INSERT INTO entities (entity_id, entity_label, short_label, enabled, adrs_1, adrs_2, adrs_3, zipcode, city, country, email, business_id, parent_entity_id, entity_type) VALUES ('DRH', 'Direction des Ressources Humaines', 'Direction RH', 'Y', '', '', '', '', '', '', '', '', 'DIR', 'Direction');
INSERT INTO entities (entity_id, entity_label, short_label, enabled, adrs_1, adrs_2, adrs_3, zipcode, city, country, email, business_id, parent_entity_id, entity_type) VALUES ('MNG', 'Service de Gestion et des Sinistres', 'Service Gestion', 'Y', '', '', '', '', '', '', '', '', 'OPE', 'Service');
INSERT INTO entities (entity_id, entity_label, short_label, enabled, adrs_1, adrs_2, adrs_3, zipcode, city, country, email, business_id, parent_entity_id, entity_type) VALUES ('SLS', 'Service Commercial Grands Comptes et Particuliers', 'Service Commercial', 'Y', '', '', '', '', '', '', '', '', 'OPE', 'Service');
INSERT INTO entities (entity_id, entity_label, short_label, enabled, adrs_1, adrs_2, adrs_3, zipcode, city, country, email, business_id, parent_entity_id, entity_type) VALUES ('ITI', 'Service des Systèmes d''Information', 'Service Informatique', 'Y', '', '', '', '', '', '', '', '', 'FIN', 'Service');
INSERT INTO entities (entity_id, entity_label, short_label, enabled, adrs_1, adrs_2, adrs_3, zipcode, city, country, email, business_id, parent_entity_id, entity_type) VALUES ('ACC', 'Service Comptabilité et Finances', 'Service Comptabilité', 'Y', '', '', '', '', '', '', '', '', 'FIN', 'Service');
INSERT INTO entities (entity_id, entity_label, short_label, enabled, adrs_1, adrs_2, adrs_3, zipcode, city, country, email, business_id, parent_entity_id, entity_type) VALUES ('COU', 'Service du Courrier', 'Service du Courrier', 'Y', '', '', '', '', '', '', '', '', 'FIN', 'Service');
INSERT INTO entities (entity_id, entity_label, short_label, enabled, adrs_1, adrs_2, adrs_3, zipcode, city, country, email, business_id, parent_entity_id, entity_type) VALUES ('EQ1', 'Equipe 1 : Grands Comptes et Entreprises', 'Equipe 1', 'Y', '', '', '', '', '', '', '', '', 'MNG', 'Bureau');
INSERT INTO entities (entity_id, entity_label, short_label, enabled, adrs_1, adrs_2, adrs_3, zipcode, city, country, email, business_id, parent_entity_id, entity_type) VALUES ('EQ2', 'Equipe 2 : Particuliers et Internet', 'Equipe 2', 'Y', '', '', '', '', '', '', '', '', 'MNG', 'Bureau');
INSERT INTO entities (entity_id, entity_label, short_label, enabled, adrs_1, adrs_2, adrs_3, zipcode, city, country, email, business_id, parent_entity_id, entity_type) VALUES ('COR', 'Correspondant Archive', 'Correspondant Archive', 'Y', '', '', '', '', '', '', '', '', 'COU', 'Bureau');

INSERT INTO users_entities (user_id, entity_id, user_role, primary_entity) VALUES ('ppetit', 'DIR', 'Directrice Générale', 'Y');
INSERT INTO users_entities (user_id, entity_id, user_role, primary_entity) VALUES ('eerina', 'DIR', 'Particulière de la Directrice Générale', 'Y');
INSERT INTO users_entities (user_id, entity_id, user_role, primary_entity) VALUES ('bbain', 'OPE', 'Directrice des Opérations', 'Y');
INSERT INTO users_entities (user_id, entity_id, user_role, primary_entity) VALUES ('ccharles', 'FIN', 'Directrice Financière', 'Y');
INSERT INTO users_entities (user_id, entity_id, user_role, primary_entity) VALUES ('pparker', 'DRH', 'Directeur RH', 'Y');
INSERT INTO users_entities (user_id, entity_id, user_role, primary_entity) VALUES ('ddogem', 'SLS', 'Responsable Commerciale', 'Y');
INSERT INTO users_entities (user_id, entity_id, user_role, primary_entity) VALUES ('ttong', 'MNG', 'Responsable de Gestion', 'Y');
INSERT INTO users_entities (user_id, entity_id, user_role, primary_entity) VALUES ('sstarr', 'EQ1', 'Gestionnaire Grands Comptes', 'Y');
INSERT INTO users_entities (user_id, entity_id, user_role, primary_entity) VALUES ('ssaporta', 'EQ2', 'Gestionnaires Particuliers et Internet', 'Y');
INSERT INTO users_entities (user_id, entity_id, user_role, primary_entity) VALUES ('ssissoko', 'ITI', 'Responsable Informatique', 'Y');
INSERT INTO users_entities (user_id, entity_id, user_role, primary_entity) VALUES ('bblier', 'COU', 'Chef du Courrier', 'Y');
INSERT INTO users_entities (user_id, entity_id, user_role, primary_entity) VALUES ('bboule', 'ACC', 'Comptable', 'Y');
INSERT INTO users_entities (user_id, entity_id, user_role, primary_entity) VALUES ('bboule', 'COR', 'Correspondant Archive', 'N');


INSERT INTO "security" (group_id, coll_id, where_clause, maarch_comment, can_insert, can_update, can_delete) VALUES ('EMPLOYEES', 'letterbox_coll', 'DESTINATION = @my_primary_entity
', '', 'Y', 'Y', 'N');
INSERT INTO "security" (group_id, coll_id, where_clause, maarch_comment, can_insert, can_update, can_delete) VALUES ('MANAGERS', 'letterbox_coll', '(DESTINATION = @my_primary_entity or DESTINATION in (@subentities[@my_primary_entity])) or DESTINATION is NULL', '', 'Y', 'Y', 'Y');
INSERT INTO "security" (group_id, coll_id, where_clause, maarch_comment, can_insert, can_update, can_delete) VALUES ('TYPISTS', 'letterbox_coll', 'DESTINATION = @my_primary_entity or TYPIST=@user', '', 'Y', 'Y', 'Y');
INSERT INTO "security" (group_id, coll_id, where_clause, maarch_comment, can_insert, can_update, can_delete) VALUES ('CORRESPONDANTS', 'letterbox_coll', '(DESTINATION = @my_primary_entity or DESTINATION in (@subentities[@my_primary_entity])) or DESTINATION is NULL', '', 'N', 'N', 'N');

-- ACTIONS and BASKETS
INSERT INTO actions (id, keyword, label_action, id_status, is_system, enabled, action_page, history, origin, create_id) VALUES (15, '', 'Prelever une archive', 'OUT', 'N', 'Y', 'confirm_apa', 'Y', 'advanced_physical_archive', 'N');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, enabled, action_page, history, origin, create_id) VALUES (16, '', 'Reintegrer une archive', 'POS', 'N', 'Y', 'confirm_apa', 'Y', 'advanced_physical_archive', 'N');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, enabled, action_page, history, origin, create_id) VALUES (2, 'to_validate', 'A valider', 'VAL', 'Y', 'N', 'confirm_status', 'N', 'apps', 'N');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, enabled, action_page, history, origin, create_id) VALUES (21, 'indexing', 'Indexation', 'NEW', 'N', 'Y', 'index_mlb', 'Y', 'apps', 'Y');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, enabled, action_page, history, origin, create_id) VALUES (18, '', 'A valider', 'NEW', 'N', 'Y', '', 'Y', 'apps', 'N');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, enabled, action_page, history, origin, create_id) VALUES (19, '', 'Traiter document', 'COU', 'N', 'Y', 'process', 'Y', 'apps', 'N');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, enabled, action_page, history, origin, create_id) VALUES (1, 'redirect', 'Redirection', 'NONE', 'Y', 'Y', 'redirect', 'Y', 'entities', 'N');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, enabled, action_page, history, origin, create_id) VALUES (22, '', 'En attente de validation', 'VAL', 'N', 'Y', '', 'Y', 'apps', 'N');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, enabled, action_page, history, origin, create_id) VALUES (23, 'indexing', 'Valider courrier', 'NEW', 'N', 'Y', 'validate_mail', 'Y', 'apps', 'N');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, enabled, action_page, history, origin, create_id) VALUES (20, '', 'Cloturer', 'END', 'N', 'Y', 'close_mail', 'Y', 'apps', 'N');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, enabled, action_page, history, origin, create_id) VALUES (3, '', 'Retourner au service Courrier', 'RET', 'N', 'Y', 'confirm_status', 'Y', 'apps', 'N');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, enabled, action_page, history, origin, create_id) VALUES (100, '', 'Voir le document', '', 'N', 'Y', 'view', 'N', 'apps', 'N');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, enabled, action_page, history, origin, create_id) VALUES (26, 'indexing', 'Reserver des documents', NULL, 'N', 'Y', 'postindex_documents', 'Y', 'postindexing', 'Y');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, enabled, action_page, history, origin, create_id) VALUES (27, '', 'Activer la persistance', '', 'N', 'Y', 'set_persistent_mode_on', 'Y', 'apps', 'N');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, enabled, action_page, history, origin, create_id) VALUES (28, '', 'Désactiver la persistance', '', 'N', 'Y', 'set_persistent_mode_off', 'Y', 'apps', 'N');


INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched) VALUES ('OUT', 'Prelevee', 'N', '', 'advanced_physical_archive', 'N');
INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched) VALUES ('POS', 'Reintegree', 'N', '', 'advanced_physical_archive', 'N');
INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched) VALUES ('COU', 'En cours', 'Y', 'mail.gif', 'apps', 'Y');
INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched) VALUES ('NEW', 'Nouveau', 'Y', 'mail_new.gif', 'apps', 'Y');
INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched) VALUES ('RSV', 'Reserve', 'N', '', 'apps', 'N');
INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched) VALUES ('DEL', 'Supprime', 'Y', NULL, 'apps', 'N');
INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched) VALUES ('END', 'Clos', 'Y', 'mail_end.gif', 'apps', 'Y');
INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched) VALUES ('VAL', 'A valider', 'Y', 'mail_new.gif', 'apps', 'Y');
INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched) VALUES ('RET', 'Retour courrier', 'N', '', 'apps', 'Y');
INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched) VALUES ('CHG', 'Vidéocodé', 'Y', 'mail_end.gif', 'apps', 'Y');
INSERT INTO status (id, label_status, is_system, img_filename, maarch_module, can_be_searched) VALUES ('BAD', 'Rejeté vidéocodage', 'Y', 'mail.gif', 'apps', 'Y');

INSERT INTO baskets (coll_id, basket_id, basket_name, basket_desc, basket_clause, is_generic, enabled) VALUES ('apa_coll', 'APA_reservation', 'Archives reservees', 'Corbeille des archives reservees', 'res_view_apa.status = ''RSV'' and (ORIGIN= @my_primary_entity or ORIGIN in (@subentities[@my_primary_entity]))', 'NO', 'Y');
INSERT INTO baskets (coll_id, basket_id, basket_name, basket_desc, basket_clause, is_generic, enabled) VALUES ('apa_coll', 'APA_picking', 'Archives prelevees', 'Corbeille des archives prelevees', 'res_view_apa.status = ''OUT'' and (ORIGIN= @my_primary_entity or ORIGIN in (@subentities[@my_primary_entity]))', 'NO', 'Y');
INSERT INTO baskets (coll_id, basket_id, basket_name, basket_desc, basket_clause, is_generic, enabled) VALUES ('letterbox_coll', 'CopyMailBasket', 'Mes courriers en copie', 'Mes courriers en copie', ' ((res_view_letterbox.res_id in (select res_id from listinstance WHERE coll_id = ''letterbox_coll'' and item_type = ''user_id'' and item_id = @user and item_mode = ''cc'') or res_view_letterbox.res_id in (select res_id from listinstance WHERE coll_id = ''letterbox_coll'' and item_type = ''entity_id'' and item_mode = ''cc'' and item_id in (@my_entities)) and status <> ''END'') OR res_view_letterbox.res_id in (select res_id from basket_persistent_mode WHERE user_id = @user and is_persistent = ''Y''))and status <> ''DEL'' ', 'N', 'Y');
INSERT INTO baskets (coll_id, basket_id, basket_name, basket_desc, basket_clause, is_generic, enabled) VALUES ('letterbox_coll', 'LateMailBasket', 'Courriers en retard', 'Courriers en retard', 'destination in (@my_entities, @subentities[@my_primary_entity]) and (status <> ''DEL'' AND status <> ''REP'') and (now() > process_limit_date)', 'N', 'Y');
INSERT INTO baskets (coll_id, basket_id, basket_name, basket_desc, basket_clause, is_generic, enabled) VALUES ('letterbox_coll', 'RetourCourrier', 'Retours Courrier', 'Courriers retournes au service Courrier', 'STATUS=''RET'' ', 'N', 'Y');
INSERT INTO baskets (coll_id, basket_id, basket_name, basket_desc, basket_clause, is_generic, enabled) VALUES ('letterbox_coll', 'IndexingBasket', 'Corbeille d''indexation', 'Corbeille d''indexation', ' ', 'N', 'Y');
INSERT INTO baskets (coll_id, basket_id, basket_name, basket_desc, basket_clause, is_generic, enabled) VALUES ('letterbox_coll', 'MyBasket', 'Mes courriers a traiter', 'Mes courriers a traiter', '(status =''NEW'' or status =''COU'') and dest_user = @user', 'N', 'Y');
INSERT INTO baskets (coll_id, basket_id, basket_name, basket_desc, basket_clause, is_generic, enabled) VALUES ('letterbox_coll', 'QualificationBasket', 'Corbeille de qualification', 'Corbeille de qualification', 'status=''VAL''', 'N', 'Y');
INSERT INTO baskets (coll_id, basket_id, basket_name, basket_desc, basket_clause, is_generic, enabled) VALUES ('letterbox_coll', 'ValidationBasket', 'Mes courriers a valider', 'Mes courriers a valider', 'status = ''VAL'' and destination<>''COU''', 'N', 'Y');
INSERT INTO baskets (coll_id, basket_id, basket_name, basket_desc, basket_clause, is_generic, enabled) VALUES ('letterbox_coll', 'DepartmentBasket', 'Corbeille de supervision', 'Corbeille de supervision', 'destination in (@my_entities, @subentities[@my_primary_entity]) and (status <> ''DEL'' AND status <> ''REP'')', 'N', 'Y');
INSERT INTO baskets (coll_id, basket_id, basket_name, basket_desc, basket_clause, is_generic, enabled) VALUES ('letterbox_coll', 'PostindexingBasket', 'Corbeille de videocodage', 'Corbeille de videocodage', '(status = ''VAL'')', 'N', 'Y');

INSERT INTO groupbasket (group_id, basket_id, "sequence", redirect_basketlist, redirect_grouplist, result_page, can_redirect, can_delete, can_insert) VALUES ('TYPISTS', 'IndexingBasket', 1, NULL, NULL, 'redirect_to_action', 'N', 'N', 'N');
INSERT INTO groupbasket (group_id, basket_id, "sequence", redirect_basketlist, redirect_grouplist, result_page, can_redirect, can_delete, can_insert) VALUES ('EMPLOYEES', 'MyBasket', 1, NULL, NULL, 'auth_dep', 'N', 'N', 'N');
INSERT INTO groupbasket (group_id, basket_id, "sequence", redirect_basketlist, redirect_grouplist, result_page, can_redirect, can_delete, can_insert) VALUES ('MANAGERS', 'MyBasket', 1, NULL, NULL, 'auth_dep', 'N', 'N', 'N');
INSERT INTO groupbasket (group_id, basket_id, "sequence", redirect_basketlist, redirect_grouplist, result_page, can_redirect, can_delete, can_insert) VALUES ('ARCHIVISTS', 'ValidationBasket', 1, NULL, NULL, 'auth_dep', 'N', 'N', 'N');
INSERT INTO groupbasket (group_id, basket_id, "sequence", redirect_basketlist, redirect_grouplist, result_page, can_redirect, can_delete, can_insert) VALUES ('MANAGERS', 'DepartmentBasket', 2, NULL, NULL, 'auth_dep', 'N', 'N', 'N');
INSERT INTO groupbasket (group_id, basket_id, "sequence", redirect_basketlist, redirect_grouplist, result_page, can_redirect, can_delete, can_insert) VALUES ('MANAGERS', 'CopyMailBasket', 4, NULL, NULL, 'count_list', 'N', 'N', 'N');
INSERT INTO groupbasket (group_id, basket_id, "sequence", redirect_basketlist, redirect_grouplist, result_page, can_redirect, can_delete, can_insert) VALUES ('MANAGERS', 'LateMailBasket', 5, NULL, NULL, 'auth_dep', 'N', 'N', 'N');
INSERT INTO groupbasket (group_id, basket_id, "sequence", redirect_basketlist, redirect_grouplist, result_page, can_redirect, can_delete, can_insert) VALUES ('EMPLOYEES', 'CopyMailBasket', 5, NULL, NULL, 'count_list', 'N', 'N', 'N');
INSERT INTO groupbasket (group_id, basket_id, "sequence", redirect_basketlist, redirect_grouplist, result_page, can_redirect, can_delete, can_insert) VALUES ('TYPISTS', 'CopyMailBasket', 7, NULL, NULL, 'count_list', 'N', 'N', 'N');
INSERT INTO groupbasket (group_id, basket_id, "sequence", redirect_basketlist, redirect_grouplist, result_page, can_redirect, can_delete, can_insert) VALUES ('ARCHIVISTS', 'APA_reservation', 1, NULL, NULL, 'apa_basket_list', 'N', 'N', 'N');
INSERT INTO groupbasket (group_id, basket_id, "sequence", redirect_basketlist, redirect_grouplist, result_page, can_redirect, can_delete, can_insert) VALUES ('ARCHIVISTS', 'APA_picking', 2, NULL, NULL, 'apa_basket_list', 'N', 'N', 'N');
INSERT INTO groupbasket (group_id, basket_id, "sequence", redirect_basketlist, redirect_grouplist, result_page, can_redirect, can_delete, can_insert) VALUES ('TYPISTS', 'APA_reservation', 2, NULL, NULL, 'apa_basket_list', 'N', 'N', 'N');
INSERT INTO groupbasket (group_id, basket_id, "sequence", redirect_basketlist, redirect_grouplist, result_page, can_redirect, can_delete, can_insert) VALUES ('TYPISTS', 'APA_picking', 3, NULL, NULL, 'apa_basket_list', 'N', 'N', 'N');
INSERT INTO groupbasket (group_id, basket_id, "sequence", redirect_basketlist, redirect_grouplist, result_page, can_redirect, can_delete, can_insert) VALUES ('TYPISTS', 'QualificationBasket', 8, NULL, NULL, 'documents_list', 'N', 'N', 'N');
INSERT INTO groupbasket (group_id, basket_id, "sequence", redirect_basketlist, redirect_grouplist, result_page, can_redirect, can_delete, can_insert) VALUES ('TYPISTS', 'PostindexingBasket', 1, NULL, NULL, 'postindexing_documents_list', 'Y', 'N', 'N');

INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (22, '', 'TYPISTS', 'IndexingBasket', 'N', 'Y', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (21, '', 'TYPISTS', 'IndexingBasket', 'N', 'N', 'Y');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (23, '', 'ARCHIVISTS', 'ValidationBasket', 'N', 'N', 'Y');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (20, '', 'MANAGERS', 'MyBasket', 'N', 'Y', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (1, '', 'MANAGERS', 'MyBasket', 'N', 'Y', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (19, '', 'MANAGERS', 'MyBasket', 'N', 'N', 'Y');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (20, '', 'EMPLOYEES', 'MyBasket', 'N', 'Y', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (1, '', 'EMPLOYEES', 'MyBasket', 'N', 'Y', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (19, '', 'EMPLOYEES', 'MyBasket', 'N', 'N', 'Y');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (100, '', 'MANAGERS', 'DepartmentBasket', 'N', 'N', 'Y');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (100, '', 'MANAGERS', 'CopyMailBasket', 'N', 'N', 'Y');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (100, '', 'MANAGERS', 'LateMailBasket', 'N', 'N', 'Y');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (100, '', 'EMPLOYEES', 'CopyMailBasket', 'N', 'N', 'Y');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (100, '', 'TYPISTS', 'CopyMailBasket', 'N', 'N', 'Y');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (15, '', 'ARCHIVISTS', 'APA_reservation', 'Y', 'Y', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (16, '', 'ARCHIVISTS', 'APA_picking', 'Y', 'Y', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (15, '', 'TYPISTS', 'APA_reservation', 'Y', 'Y', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (16, '', 'TYPISTS', 'APA_picking', 'Y', 'Y', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (23, '', 'TYPISTS', 'QualificationBasket', 'N', 'N', 'Y');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (26, '', 'TYPISTS', 'PostindexingBasket', 'N', 'N', 'Y');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (27, '', 'EMPLOYEES', 'CopyMailBasket', 'Y', 'N', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (27, '', 'TYPISTS', 'CopyMailBasket', 'Y', 'N', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (27, '', 'MANAGERS', 'CopyMailBasket', 'Y', 'N', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (28, '', 'EMPLOYEES', 'CopyMailBasket', 'Y', 'N', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (28, '', 'TYPISTS', 'CopyMailBasket', 'Y', 'N', 'N');
INSERT INTO actions_groupbaskets (id_action, where_clause, group_id, basket_id, used_in_basketlist, used_in_action_page, default_action_list) VALUES (28, '', 'MANAGERS', 'CopyMailBasket', 'Y', 'N', 'N');


INSERT INTO groupbasket_redirect (system_id, group_id, basket_id, action_id, entity_id, keyword, redirect_mode) VALUES (1, 'TYPISTS', 'IndexingBasket', 21, '', 'ALL_ENTITIES', 'ENTITY');
INSERT INTO groupbasket_redirect (system_id, group_id, basket_id, action_id, entity_id, keyword, redirect_mode) VALUES (2, 'MANAGERS', 'MyBasket', 1, '', 'ALL_ENTITIES', 'ENTITY');
INSERT INTO groupbasket_redirect (system_id, group_id, basket_id, action_id, entity_id, keyword, redirect_mode) VALUES (3, 'EMPLOYEES', 'MyBasket', 1, '', 'MY_ENTITIES', 'ENTITY');
INSERT INTO groupbasket_redirect (system_id, group_id, basket_id, action_id, entity_id, keyword, redirect_mode) VALUES (4, 'ARCHIVISTS', 'ValidationBasket', 23, '', 'ALL_ENTITIES', 'ENTITY');
INSERT INTO groupbasket_redirect (system_id, group_id, basket_id, action_id, entity_id, keyword, redirect_mode) VALUES (5, 'MANAGERS', 'DepartmentBasket', 1, '', 'MY_ENTITIES', 'ENTITY');
INSERT INTO groupbasket_redirect (system_id, group_id, basket_id, action_id, entity_id, keyword, redirect_mode) VALUES (6, 'MANAGERS', 'DepartmentBasket', 1, '', 'MY_ENTITIES', 'USERS');
INSERT INTO groupbasket_redirect (system_id, group_id, basket_id, action_id, entity_id, keyword, redirect_mode) VALUES (7, 'TYPISTS', 'QualificationBasket', 23, '', 'ALL_ENTITIES', 'ENTITY');
INSERT INTO groupbasket_redirect (system_id, group_id, basket_id, action_id, entity_id, keyword, redirect_mode) VALUES (8, 'EMPLOYEES', 'MyBasket', 1, '', 'ENTITIES_JUST_BELOW', 'ENTITY');
INSERT INTO groupbasket_redirect (system_id, group_id, basket_id, action_id, entity_id, keyword, redirect_mode) VALUES (9, 'TYPISTS', 'PostindexingBasket', 26, '', 'ALL_ENTITIES', 'ENTITY');

INSERT INTO listmodels (coll_id, object_id, object_type, "sequence", item_id, item_type, item_mode, listmodel_type) VALUES ('letterbox_coll', 'DIR', 'entity_id', 0, 'eerina', 'user_id', 'dest', 'DOC');
INSERT INTO listmodels (coll_id, object_id, object_type, "sequence", item_id, item_type, item_mode, listmodel_type) VALUES ('letterbox_coll', 'DIR', 'entity_id', 0, 'ppetit', 'user_id', 'cc', 'DOC');
INSERT INTO listmodels (coll_id, object_id, object_type, "sequence", item_id, item_type, item_mode, listmodel_type) VALUES ('letterbox_coll', 'OPE', 'entity_id', 0, 'bbain', 'user_id', 'dest', 'DOC');
INSERT INTO listmodels (coll_id, object_id, object_type, "sequence", item_id, item_type, item_mode, listmodel_type) VALUES ('letterbox_coll', 'FIN', 'entity_id', 0, 'ccharles', 'user_id', 'dest', 'DOC');
INSERT INTO listmodels (coll_id, object_id, object_type, "sequence", item_id, item_type, item_mode, listmodel_type) VALUES ('letterbox_coll', 'FIN', 'entity_id', 1, 'ppetit', 'user_id', 'cc', 'DOC');
INSERT INTO listmodels (coll_id, object_id, object_type, "sequence", item_id, item_type, item_mode, listmodel_type) VALUES ('letterbox_coll', 'DRH', 'entity_id', 0, 'pparker', 'user_id', 'dest', 'DOC');
INSERT INTO listmodels (coll_id, object_id, object_type, "sequence", item_id, item_type, item_mode, listmodel_type) VALUES ('letterbox_coll', 'SLS', 'entity_id', 0, 'ddogem', 'user_id', 'dest', 'DOC');
INSERT INTO listmodels (coll_id, object_id, object_type, "sequence", item_id, item_type, item_mode, listmodel_type) VALUES ('letterbox_coll', 'MNG', 'entity_id', 0, 'ttong', 'user_id', 'dest', 'DOC');
INSERT INTO listmodels (coll_id, object_id, object_type, "sequence", item_id, item_type, item_mode, listmodel_type) VALUES ('letterbox_coll', 'MNG', 'entity_id', 1, 'bbain', 'user_id', 'cc', 'DOC');
INSERT INTO listmodels (coll_id, object_id, object_type, "sequence", item_id, item_type, item_mode, listmodel_type) VALUES ('letterbox_coll', 'ITI', 'entity_id', 0, 'ssissoko', 'user_id', 'dest', 'DOC');
INSERT INTO listmodels (coll_id, object_id, object_type, "sequence", item_id, item_type, item_mode, listmodel_type) VALUES ('letterbox_coll', 'COU', 'entity_id', 0, 'bblier', 'user_id', 'dest', 'DOC');
INSERT INTO listmodels (coll_id, object_id, object_type, "sequence", item_id, item_type, item_mode, listmodel_type) VALUES ('letterbox_coll', 'ACC', 'entity_id', 0, 'bboule', 'user_id', 'dest', 'DOC');
INSERT INTO listmodels (coll_id, object_id, object_type, "sequence", item_id, item_type, item_mode, listmodel_type) VALUES ('letterbox_coll', 'EQ1', 'entity_id', 0, 'sstarr', 'user_id', 'dest', 'DOC');
INSERT INTO listmodels (coll_id, object_id, object_type, "sequence", item_id, item_type, item_mode, listmodel_type) VALUES ('letterbox_coll', 'EQ2', 'entity_id', 0, 'ssaporta', 'user_id', 'dest', 'DOC');


-- FOLDERS
INSERT INTO folders (folders_system_id, folder_id, foldertype_id, parent_id, folder_name, subject, description, author, typist, status, folder_level, creation_date, folder_out_id, custom_t1, custom_n1, custom_f1, custom_d1, custom_t2, custom_n2, custom_f2, custom_d2, custom_t3, custom_n3, custom_f3, custom_d3, custom_t4, custom_n4, custom_f4, custom_d4, custom_t5, custom_n5, custom_f5, custom_d5, custom_t6, custom_d6, custom_t7, custom_d7, custom_t8, custom_d8, custom_t9, custom_d9, custom_t10, custom_d10, custom_t11, custom_d11, custom_t12, custom_d12, custom_t13, custom_d13, custom_t14, custom_d14, custom_t15, is_complete, is_folder_out, last_modified_date) VALUES
(1, 'F_000001', 1, 0, 'Eric SPRITZ', NULL, NULL, NULL, NULL, 'NEW', 1, '2008-04-23 18:03:14', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Y', 'N', '2008-04-23 18:03:14'),
(2, 'F_000002', 1, 0, 'Thomas BECK', NULL, NULL, NULL, NULL, 'NEW', 1, '2008-04-23 16:55:05', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Y', 'N', '2008-04-23 18:03:14'),
(3, 'F_000003', 1, 0, 'Teresa CRISTINA', NULL, NULL, NULL, NULL, 'NEW', 1, '2008-04-23 17:56:15', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Y', 'N', '2008-04-23 18:03:14'),
(4, 'F_000004', 1, 0, 'Tom JOBIM', NULL, NULL, NULL, NULL, 'NEW', 1, '2008-04-23 17:58:15', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Y', 'N', '2008-04-23 18:03:14'),
(5, 'F_000005', 1, 0, 'Joao GILBERTO', NULL, NULL, NULL, NULL, 'NEW', 1, '2008-04-23 18:00:26', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Y', 'N', '2008-04-23 18:03:14'),
(6, 'F_000006', 1, 0, 'Luciano PAVAROTTI', NULL, NULL, NULL, NULL, 'NEW', 1, '2008-04-23 18:02:01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Y', 'N', '2008-04-23 18:03:14'),
(7, 'F_000007', 1, 0, 'Maria BETHANIA', NULL, NULL, NULL, NULL, 'NEW', 1, '2008-04-24 01:31:38', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Y', 'N', '2008-04-23 18:03:14'),
(8, 'F_000008', 1, 0, 'Edith PIAF', NULL, NULL, NULL, NULL, 'NEW', 1, '2008-04-24 16:36:55', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Y', 'N', '2008-04-23 18:03:14'),
(9, 'F_000009', 1, 0, 'Compay SEGUNDO', NULL, NULL, NULL, NULL, 'NEW', 1, '2008-04-24 16:55:39', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Y', 'N', '2008-04-23 18:03:14'),
(10, 'SF_0101', 1, 1, 'Contrat 1 pour Eric SPRITZ : Assurance Décès-Invalidité', NULL, NULL, NULL, NULL, 'NEW', 2, '2008-05-22 17:26:48', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Y', 'N', '2008-04-23 18:03:14'),
(11, 'SF_0102', 1, 1, 'Contrat 2 pour Eric SPRITZ : Assurance Habitation', NULL, NULL, NULL, NULL, 'NEW', 2, '2008-05-22 17:26:48', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Y', 'N', '2008-04-23 18:03:14'),
(12, 'SF_0103', 1, 1, 'Contrat 3 pour Eric SPRITZ : Assurance Véhicule', NULL, NULL, NULL, NULL, 'NEW', 2, '2008-05-22 17:26:48', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Y', 'N', '2008-04-23 18:03:14'),
(13, 'SF_0201', 1, 2, 'Contrat 1 pour Thomas BECK : Assurance Décès-Invalidité', NULL, NULL, NULL, NULL, 'NEW', 2, '2008-05-22 17:26:48', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Y', 'N', '2008-04-23 18:03:14'),
(14, 'SF_0202', 1, 2, 'Contrat 2 pour Thomas BECK : Assurance Habitation', NULL, NULL, NULL, NULL, 'NEW', 2, '2008-05-22 17:26:48', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Y', 'N', '2008-04-23 18:03:14'),
(15, 'SF_0203', 1, 2, 'Contrat 3 pour Thomas BECK : Assurance Véhicule', NULL, NULL, NULL, NULL, 'NEW', 2, '2008-05-22 17:26:48', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Y', 'N', '2008-04-23 18:03:14'),
(16, 'P_01', 2, 0, 'Dossier Collaborateur MARTIN', NULL, NULL, NULL, NULL, 'NEW', 1, '2008-05-22 17:26:48', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Y', 'N', '2008-04-23 18:03:14'),
(17, 'P_02', 2, 0, 'Dossier Collaborateur SMITH', NULL, NULL, NULL, NULL, 'NEW', 1, '2008-05-22 17:26:48', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Y', 'N', '2008-04-23 18:03:14');

INSERT INTO foldertypes (foldertype_id, foldertype_label, maarch_comment, retention_time, custom_d1, custom_f1, custom_n1, custom_t1, custom_d2, custom_f2, custom_n2, custom_t2, custom_d3, custom_f3, custom_n3, custom_t3, custom_d4, custom_f4, custom_n4, custom_t4, custom_d5, custom_f5, custom_n5, custom_t5, custom_d6, custom_t6, custom_d7, custom_t7, custom_d8, custom_t8, custom_d9, custom_t9, custom_d10, custom_t10, custom_t11, custom_t12, custom_t13, custom_t14, custom_t15, coll_id) VALUES (1, 'Documents Métier', 'Documents Métier', NULL, '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', 'letterbox_coll');
INSERT INTO foldertypes (foldertype_id, foldertype_label, maarch_comment, retention_time, custom_d1, custom_f1, custom_n1, custom_t1, custom_d2, custom_f2, custom_n2, custom_t2, custom_d3, custom_f3, custom_n3, custom_t3, custom_d4, custom_f4, custom_n4, custom_t4, custom_d5, custom_f5, custom_n5, custom_t5, custom_d6, custom_t6, custom_d7, custom_t7, custom_d8, custom_t8, custom_d9, custom_t9, custom_d10, custom_t10, custom_t11, custom_t12, custom_t13, custom_t14, custom_t15, coll_id) VALUES (2, 'Documents RH', 'Documents de Ressources Humaines', NULL, '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', 'letterbox_coll');
INSERT INTO foldertypes (foldertype_id, foldertype_label, maarch_comment, retention_time, custom_d1, custom_f1, custom_n1, custom_t1, custom_d2, custom_f2, custom_n2, custom_t2, custom_d3, custom_f3, custom_n3, custom_t3, custom_d4, custom_f4, custom_n4, custom_t4, custom_d5, custom_f5, custom_n5, custom_t5, custom_d6, custom_t6, custom_d7, custom_t7, custom_d8, custom_t8, custom_d9, custom_t9, custom_d10, custom_t10, custom_t11, custom_t12, custom_t13, custom_t14, custom_t15, coll_id) VALUES (3, 'Factures', 'Factures', NULL, '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', '0000000000', 'res_coll');

INSERT INTO foldertypes_doctypes_level1 (foldertype_id, doctypes_first_level_id) VALUES (1, 1);
INSERT INTO foldertypes_doctypes_level1 (foldertype_id, doctypes_first_level_id) VALUES (2, 2);
INSERT INTO foldertypes_doctypes_level1 (foldertype_id, doctypes_first_level_id) VALUES (3, 3);

INSERT INTO doctypes (coll_id, type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, primary_retention, secondary_retention) VALUES ('letterbox_coll', 5, 'Appel téléphonique', 'Y', 1, 13, NULL, NULL);
INSERT INTO doctypes (coll_id, type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, primary_retention, secondary_retention) VALUES ('letterbox_coll', 10, 'Courrier divers', 'Y', 1, 13, NULL, NULL);
INSERT INTO doctypes (coll_id, type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, primary_retention, secondary_retention) VALUES ('letterbox_coll', 15, 'Demande de modification', 'Y', 1, 11, NULL, NULL);
INSERT INTO doctypes (coll_id, type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, primary_retention, secondary_retention) VALUES ('letterbox_coll', 20, 'Avenant', 'Y', 1, 11, NULL, NULL);
INSERT INTO doctypes (coll_id, type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, primary_retention, secondary_retention) VALUES ('letterbox_coll', 25, 'Contrat', 'Y', 1, 10, NULL, NULL);
INSERT INTO doctypes (coll_id, type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, primary_retention, secondary_retention) VALUES ('letterbox_coll', 30, 'Pièce d''identité', 'Y', 1, 10, NULL, NULL);
INSERT INTO doctypes (coll_id, type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, primary_retention, secondary_retention) VALUES ('letterbox_coll', 35, 'Justificatifs', 'Y', 1, 10, NULL, NULL);
INSERT INTO doctypes (coll_id, type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, primary_retention, secondary_retention) VALUES ('letterbox_coll', 40, 'Constat', 'Y', 1, 12, NULL, NULL);
INSERT INTO doctypes (coll_id, type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, primary_retention, secondary_retention) VALUES ('letterbox_coll', 45, 'Rapport d''expertise', 'Y', 1, 12, NULL, NULL);
INSERT INTO doctypes (coll_id, type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, primary_retention, secondary_retention) VALUES ('letterbox_coll', 50, 'Lettre de réclamation', 'Y', 1, 13, NULL, NULL);
INSERT INTO doctypes (coll_id, type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, primary_retention, secondary_retention) VALUES ('letterbox_coll', 60, 'Pièce d''Identité', 'Y', 2, 51, NULL, NULL);
INSERT INTO doctypes (coll_id, type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, primary_retention, secondary_retention) VALUES ('letterbox_coll', 61, 'Contrat de Travail', 'Y', 2, 51, NULL, NULL);
INSERT INTO doctypes (coll_id, type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, primary_retention, secondary_retention) VALUES ('letterbox_coll', 62, 'CV', 'Y', 2, 51, NULL, NULL);
INSERT INTO doctypes (coll_id, type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, primary_retention, secondary_retention) VALUES ('letterbox_coll', 63, 'Fiche Médecine du Travail', 'Y', 2, 52, NULL, NULL);
INSERT INTO doctypes (coll_id, type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, primary_retention, secondary_retention) VALUES ('letterbox_coll', 64, 'Mutuelle', 'Y', 2, 52, NULL, NULL);
INSERT INTO doctypes (coll_id, type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, primary_retention, secondary_retention) VALUES ('letterbox_coll', 65, 'Diplôme', 'Y', 2, 53, NULL, NULL);
INSERT INTO doctypes (coll_id, type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, primary_retention, secondary_retention) VALUES ('letterbox_coll', 66, 'Bilan de compétences', 'Y', 2, 53, NULL, NULL);
INSERT INTO doctypes (coll_id, type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, primary_retention, secondary_retention) VALUES ('res_coll', 70, 'Factures client', 'Y', 2, 54, NULL, NULL);
INSERT INTO doctypes (coll_id, type_id, description, enabled, doctypes_first_level_id, doctypes_second_level_id, primary_retention, secondary_retention) VALUES ('letterbox_coll', 71, 'Facture fournisseur', 'Y', 1, 13, NULL, NULL);


INSERT INTO doctypes_indexes (type_id, coll_id, field_name, mandatory) VALUES (70, 'res_coll', 'custom_t1', 'N');
INSERT INTO doctypes_indexes (type_id, coll_id, field_name, mandatory) VALUES (70, 'res_coll', 'custom_t2', 'N');
INSERT INTO doctypes_indexes (type_id, coll_id, field_name, mandatory) VALUES (70, 'res_coll', 'custom_t3', 'N');
INSERT INTO doctypes_indexes (type_id, coll_id, field_name, mandatory) VALUES (70, 'res_coll', 'custom_t4', 'N');
INSERT INTO doctypes_indexes (type_id, coll_id, field_name, mandatory) VALUES (70, 'res_coll', 'custom_t5', 'N');
INSERT INTO doctypes_indexes (type_id, coll_id, field_name, mandatory) VALUES (70, 'res_coll', 'custom_n1', 'N');
INSERT INTO doctypes_indexes (type_id, coll_id, field_name, mandatory) VALUES (70, 'res_coll', 'custom_d1', 'N');

INSERT INTO doctypes_indexes (type_id, coll_id, field_name, mandatory) VALUES (71, 'letterbox_coll', 'custom_t1', 'N');
INSERT INTO doctypes_indexes (type_id, coll_id, field_name, mandatory) VALUES (71, 'letterbox_coll', 'custom_t2', 'N');
INSERT INTO doctypes_indexes (type_id, coll_id, field_name, mandatory) VALUES (71, 'letterbox_coll', 'custom_t3', 'N');
INSERT INTO doctypes_indexes (type_id, coll_id, field_name, mandatory) VALUES (71, 'letterbox_coll', 'custom_t4', 'N');
INSERT INTO doctypes_indexes (type_id, coll_id, field_name, mandatory) VALUES (71, 'letterbox_coll', 'custom_t5', 'N');
INSERT INTO doctypes_indexes (type_id, coll_id, field_name, mandatory) VALUES (71, 'letterbox_coll', 'custom_n1', 'N');
INSERT INTO doctypes_indexes (type_id, coll_id, field_name, mandatory) VALUES (71, 'letterbox_coll', 'custom_d1', 'N');

INSERT INTO mlb_doctype_ext (type_id, process_delay, delay1, delay2) VALUES (5, 21, 14, 1);
INSERT INTO mlb_doctype_ext(type_id, process_delay, delay1, delay2) VALUES (10, 21, 14, 1);
INSERT INTO mlb_doctype_ext(type_id, process_delay, delay1, delay2) VALUES (15, 21, 14, 1);
INSERT INTO mlb_doctype_ext(type_id, process_delay, delay1, delay2) VALUES (20, 21, 14, 1);
INSERT INTO mlb_doctype_ext(type_id, process_delay, delay1, delay2) VALUES (25, 21, 14, 1);
INSERT INTO mlb_doctype_ext(type_id, process_delay, delay1, delay2) VALUES (30, 21, 14, 1);
INSERT INTO mlb_doctype_ext(type_id, process_delay, delay1, delay2) VALUES (35, 21, 14, 1);
INSERT INTO mlb_doctype_ext(type_id, process_delay, delay1, delay2) VALUES (40, 21, 14, 1);
INSERT INTO mlb_doctype_ext(type_id, process_delay, delay1, delay2) VALUES (45, 21, 14, 1);
INSERT INTO mlb_doctype_ext(type_id, process_delay, delay1, delay2) VALUES (50, 21, 14, 1);
INSERT INTO mlb_doctype_ext (type_id, process_delay, delay1, delay2) VALUES (60, 21, 14, 1);
INSERT INTO mlb_doctype_ext (type_id, process_delay, delay1, delay2) VALUES (61, 21, 14, 1);
INSERT INTO mlb_doctype_ext (type_id, process_delay, delay1, delay2) VALUES (62, 21, 14, 1);
INSERT INTO mlb_doctype_ext (type_id, process_delay, delay1, delay2) VALUES (63, 21, 14, 1);
INSERT INTO mlb_doctype_ext (type_id, process_delay, delay1, delay2) VALUES (64, 21, 14, 1);
INSERT INTO mlb_doctype_ext (type_id, process_delay, delay1, delay2) VALUES (65, 21, 14, 1);
INSERT INTO mlb_doctype_ext (type_id, process_delay, delay1, delay2) VALUES (66, 21, 14, 1);
INSERT INTO mlb_doctype_ext (type_id, process_delay, delay1, delay2) VALUES (70, 21, 14, 1);
INSERT INTO mlb_doctype_ext (type_id, process_delay, delay1, delay2) VALUES (71, 21, 14, 1);

INSERT INTO doctypes_first_level (doctypes_first_level_id, doctypes_first_level_label, css_style, enabled) VALUES (1, 'Courriers', 'blue_style_big', 'Y');
INSERT INTO doctypes_first_level (doctypes_first_level_id, doctypes_first_level_label, css_style, enabled) VALUES (2, 'Dossier du Personnel', 'orange_style_big', 'Y');
INSERT INTO doctypes_first_level (doctypes_first_level_id, doctypes_first_level_label, css_style, enabled) VALUES (3, 'Factures', NULL, 'Y');

INSERT INTO doctypes_second_level (doctypes_second_level_id, doctypes_second_level_label, doctypes_first_level_id, css_style, enabled) VALUES (10, 'Ouverture de compte', 1, 'blue_style', 'Y');
INSERT INTO doctypes_second_level (doctypes_second_level_id, doctypes_second_level_label, doctypes_first_level_id, css_style, enabled) VALUES (11, 'Avenants', 1, 'green_style', 'Y');
INSERT INTO doctypes_second_level (doctypes_second_level_id, doctypes_second_level_label, doctypes_first_level_id, css_style, enabled) VALUES (12, 'Sinistres', 1, 'red_style', 'Y');
INSERT INTO doctypes_second_level (doctypes_second_level_id, doctypes_second_level_label, doctypes_first_level_id, css_style, enabled) VALUES (13, 'Autres', 1, 'brown_style', 'Y');
INSERT INTO doctypes_second_level (doctypes_second_level_id, doctypes_second_level_label, doctypes_first_level_id, css_style, enabled) VALUES (51, 'DDRH', 2, 'beige_style', 'Y');
INSERT INTO doctypes_second_level (doctypes_second_level_id, doctypes_second_level_label, doctypes_first_level_id, css_style, enabled) VALUES (52, 'Affaires Sociales', 2, 'violet_style', 'Y');
INSERT INTO doctypes_second_level (doctypes_second_level_id, doctypes_second_level_label, doctypes_first_level_id, css_style, enabled) VALUES (53, 'Formation', 2, 'pink_style', 'Y');
INSERT INTO doctypes_second_level (doctypes_second_level_id, doctypes_second_level_label, doctypes_first_level_id, css_style, enabled) VALUES (54, 'Factures', 3, NULL, 'Y');








-- OTHER

INSERT INTO parameters (id, param_value_string, param_value_int) VALUES
('workbatch_rec', '', 7),
('folder_id_increment', '', 152),
('work_batch_autoimport_id', NULL, 1),
('ar_index__', NULL, 3),
('ar_index_pparker_incoming', NULL, 3),
('ar_index_pparker_outgoing', NULL, 3),
('ar_index_pparker_internal', NULL, 3),
('ar_index_pparker_market_document', NULL, 3),
('postindexing_workbatch', NULL, 40),
('database_version', NULL, 131);

INSERT INTO templates (template_id, template_label, template_comment, template_content) VALUES (2, 'AR_MAARCH', 'Accusé de réception Maarch', '<p style="TEXT-ALIGN: left"><img src="img/default_maarch.gif" alt="" width="278" height="80" />&nbsp;</p>
<p><em><font face="Arial Black" size="2" color="#3366ff">La gestion de courriers Open source !</font></em><br />Mail : info@maarch.org<br />Web : http://www.maarch.org</p>
<p style="TEXT-ALIGN: right"><font size="2">Nanterre, le [NOW]</font></p>
<p><font size="2">Cher&nbsp;&nbsp;[CONTACT_LASTNAME]</font></p>
<p><font size="2">Nous accusons r&eacute;ception de votre courrier du <strong>BLABLA</strong>, et mettons tout en oeuvre pour vous r&eacute;pondre dans les plus brefs d&eacute;lais.</font></p>
<p><font size="2">Sachez qu''en cas de besoin urgent nos bureaux sont ouverts de 8h00 &agrave; 15h00, du lundi au samedi.</font></p>
<p><font size="2">Le num&eacute;ro vert o&ugrave; vous pouvez nous appeler est le 0800 455 24.</font></p>
<p><font size="2">Votre n&deg; de dossier &agrave; rappeler dans toute correspondance est le : [CHRONO]</font></p>
<p>&nbsp;</p>
<p><font size="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Cordialement,</font></p>
<p><font size="2"><em>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; [CURRENT_USER_FIRSTNAME] [CURRENT_USER_LASTNAME]<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; [DESTINATION]<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; T&eacute;l : [CURRENT_USER_PHONE]<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Courriel : [CURRENT_USER_EMAIL]</em></font></p>
<p>&nbsp;</p>');
INSERT INTO templates (template_id, template_label, template_comment, template_content) VALUES (1, 'TEST', 'Test des mots-clés', '<h1>Liste des mots-cl&eacute;s utilisables dans les mod&egrave;les de reponse</h1>
<p>&nbsp;</p>
<table border="0">
<tbody>
<tr>
<td><font size="2">Contact externe: Civilit&eacute;&nbsp;</font></td>
<td><strong><font size="2">[CONTACT_TITLE]</font></strong></td>
</tr>
<tr>
<td><font size="2">Contact externe: Nom</font></td>
<td><strong><font size="2">[CONTACT_LASTNAME]</font></strong></td>
</tr>
<tr>
<td><font size="2">Contact externe: Pr&eacute;nom</font></td>
<td><strong><font size="2">[CONTACT_FIRSTNAME]</font></strong></td>
</tr>
<tr>
<td><font size="2">Contact externe : Organisation</font></td>
<td><strong><font size="2">[CONTACT_SOCIETY]</font></strong></td>
</tr>
<tr>
<td><font size="2">Contact externe : Adresse N&deg;</font></td>
<td><strong><font size="2">[CONTACT_ADRS_NUM]</font></strong></td>
</tr>
<tr>
<td><font size="2">Contact externe : Adresse Rue</font></td>
<td><strong><font size="2">[CONTACT_ADRS_STREET]</font></strong></td>
</tr>
<tr>
<td><font size="2">Contact externe : Adresse Complement</font></td>
<td><strong><font size="2">[CONTACT_ADRS_COMP]</font></strong></td>
</tr>
<tr>
<td><font size="2">Contact externe : Adresse Ville</font></td>
<td><strong><font size="2">[CONTACT_ADRS_TOWN]</font></strong></td>
</tr>
<tr>
<td><font size="2">Contact externe : Adresse CP</font></td>
<td><strong><font size="2">[CONTACT_ADRS_PC]</font></strong></td>
</tr>
<tr>
<td><font size="2">Contact externe : Adresse Pays</font></td>
<td><strong><font size="2">[CONTACT_ADRS_COUNTRY]</font></strong></td>
</tr>
<tr>
<td><font size="2">Contact interne: Nom</font></td>
<td><strong><font size="2">[USER_LASTNAME]</font></strong></td>
</tr>
<tr>
<td><font size="2">Contact interne: Pr&eacute;nom</font></td>
<td><strong><font size="2">[USER_FIRSTNAME]</font></strong></td>
</tr>
<tr>
<td><font size="2">Courrier: Service Traitant</font></td>
<td><strong><font size="2">[DESTINATION]</font></strong></td>
</tr>
<tr>
<td><font size="2">Courrier: Type de document</font></td>
<td><strong><font size="2">[DOCTYPE]</font></strong></td>
</tr>
<tr>
<td><font size="2">Courrier: Cat&eacute;gorie</font></td>
<td><strong><font size="2">[CAT_ID]</font></strong></td>
</tr>
<tr>
<td><font size="2">Courrier: Nature</font></td>
<td><strong><font size="2">[NATURE]</font></strong></td>
</tr>
<tr>
<td><font size="2">Courrier: Date d''arriv&eacute;e&nbsp;&nbsp;</font></td>
<td><strong><font size="2">[ADMISSION_DATE]</font></strong></td>
</tr>
<tr>
<td><font size="2">Courrier: Date du courrier&nbsp;&nbsp;</font></td>
<td><strong><font size="2">[DOC_DATE]</font></strong></td>
</tr>
<tr>
<td><font size="2">Courrier: Date limite de traitement&nbsp;&nbsp;</font></td>
<td><strong><font size="2">[PROCESS_LIMIT_DATE]</font></strong></td>
</tr>
<tr>
<td><font size="2">Courrier: Notes de traitement&nbsp;&nbsp;</font></td>
<td><strong><font size="2">[PROCESS_NOTES]</font></strong></td>
</tr>
<tr>
<td><font size="2">Courrier: Date de cl&ocirc;ture&nbsp;&nbsp;</font></td>
<td><strong><font size="2">[CLOSING_DATE]</font></strong></td>
</tr>
<tr>
<td><font size="2">Courrier: Objet</font></td>
<td><strong><font size="2">[SUBJECT]</font></strong></td>
</tr>
<tr>
<td><font size="2">Courrier: Num&eacute;ro chrono</font></td>
<td><strong><font size="2">[CHRONO]</font></strong></td>
</tr>
<tr>
<td><font size="2">Document: Auteur</font></td>
<td><strong><font size="2">[AUTHOR]</font></strong></td>
</tr>
<tr>
<td><font size="2">Document: Date d''enregistrement</font></td>
<td><strong><font size="2">[CREATION_DATE]</font></strong></td>
</tr>
<tr>
<td><font size="2">Sp&eacute;cial: Date du jour</font></td>
<td><strong><font size="2">[NOW]</font></strong></td>
</tr>
<tr>
<td><font size="2">Sp&eacute;cial: Nom du destinataire traitant</font></td>
<td><strong><font size="2">[CURRENT_USER_LASTNAME]</font></strong></td>
</tr>
<tr>
<td><font size="2">Sp&eacute;cial: Pr&eacute;nom du destinataire traitant</font></td>
<td><strong><font size="2">[CURRENT_USER_FIRSTNAME]</font></strong></td>
</tr>
<tr>
<td><font size="2">Sp&eacute;cial: T&eacute;l&eacute;phone du destinataire traitant</font></td>
<td><strong><font size="2">[CURRENT_USER_PHONE]</font></strong></td>
</tr>
<tr>
<td><font size="2">Sp&eacute;cial: Mail du destinataire traitant</font></td>
<td><strong><font size="2">[CURRENT_USER_EMAIL]</font></strong></td>
</tr>
</tbody>
</table>
<p><font size="2">&nbsp;</font></p>');
INSERT INTO templates (template_id, template_label, template_comment, template_content) VALUES (3, 'AppelTel', 'Appel téléphonique', '<p><font size="\\&quot;5\\&quot;"><strong>APPEL TELEPHONIQUE</strong></font></p>
<p><font size="\\&quot;2\\&quot;">Bonjour,</font></p>
<p><font size="\\&quot;2\\&quot;">Vous avez re&ccedil;u un appel t&eacute;l&eacute;phonique dont voici les informations :</font></p>
<ul>
<li><font size="\\&quot;2\\&quot;">Date : </font></li>
<li><font size="\\&quot;2\\&quot;">Heure :</font></li>
<li><font size="\\&quot;2\\&quot;">Soci&eacute;t&eacute; :</font></li>
<li><font size="\\&quot;2\\&quot;">Contact :</font></li>
</ul>
<p><font size="\\&quot;2\\&quot;">Notes : </font></p>');



--
-- TOC entry 2326 (class 0 OID 39180)
-- Dependencies: 1461
-- Data for Name: templates_association; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO templates_association (template_id, what, value_field, system_id, maarch_module) VALUES (2, 'destination', 'EQ1', 1, 'entities');
INSERT INTO templates_association (template_id, what, value_field, system_id, maarch_module) VALUES (1, 'destination', 'DIR', 2, 'entities');
INSERT INTO templates_association (template_id, what, value_field, system_id, maarch_module) VALUES (1, 'destination', 'OPE', 3, 'entities');
INSERT INTO templates_association (template_id, what, value_field, system_id, maarch_module) VALUES (1, 'destination', 'FIN', 4, 'entities');
INSERT INTO templates_association (template_id, what, value_field, system_id, maarch_module) VALUES (1, 'destination', 'DRH', 5, 'entities');
INSERT INTO templates_association (template_id, what, value_field, system_id, maarch_module) VALUES (1, 'destination', 'SLS', 6, 'entities');
INSERT INTO templates_association (template_id, what, value_field, system_id, maarch_module) VALUES (1, 'destination', 'MNG', 7, 'entities');
INSERT INTO templates_association (template_id, what, value_field, system_id, maarch_module) VALUES (1, 'destination', 'ITI', 8, 'entities');
INSERT INTO templates_association (template_id, what, value_field, system_id, maarch_module) VALUES (1, 'destination', 'COU', 9, 'entities');
INSERT INTO templates_association (template_id, what, value_field, system_id, maarch_module) VALUES (1, 'destination', 'ACC', 10, 'entities');
INSERT INTO templates_association (template_id, what, value_field, system_id, maarch_module) VALUES (1, 'destination', 'EQ1', 11, 'entities');
INSERT INTO templates_association (template_id, what, value_field, system_id, maarch_module) VALUES (1, 'destination', 'EQ2', 12, 'entities');
INSERT INTO templates_association (template_id, what, value_field, system_id, maarch_module) VALUES (1, 'destination', 'COR', 13, 'entities');
INSERT INTO templates_association (template_id, what, value_field, system_id, maarch_module) VALUES (2, 'destination', 'MNG', 14, 'entities');
INSERT INTO templates_association (template_id, what, value_field, system_id, maarch_module) VALUES (3, 'destination', 'COU', 15, 'entities');


INSERT INTO templates_doctype_ext (template_id, type_id, is_generated) VALUES (NULL, 60, 'N');
INSERT INTO templates_doctype_ext (template_id, type_id, is_generated) VALUES (NULL, 61, 'N');
INSERT INTO templates_doctype_ext (template_id, type_id, is_generated) VALUES (NULL, 62, 'N');
INSERT INTO templates_doctype_ext (template_id, type_id, is_generated) VALUES (NULL, 63, 'N');
INSERT INTO templates_doctype_ext (template_id, type_id, is_generated) VALUES (NULL, 64, 'N');
INSERT INTO templates_doctype_ext (template_id, type_id, is_generated) VALUES (NULL, 65, 'N');
INSERT INTO templates_doctype_ext (template_id, type_id, is_generated) VALUES (NULL, 66, 'N');
INSERT INTO templates_doctype_ext (template_id, type_id, is_generated) VALUES (NULL, 67, 'N');
INSERT INTO templates_doctype_ext (template_id, type_id, is_generated) VALUES (NULL, 68, 'N');
INSERT INTO templates_doctype_ext (template_id, type_id, is_generated) VALUES (3, 5, 'Y');

-- DOCS
-- TBC

-- PHYSICAL ARCHIVING
INSERT INTO ar_boxes (arbox_id, title, subject, description, entity_id, arcontainer_id, status, creation_date, retention_time, custom_t1, custom_n1, custom_f1, custom_d1, custom_t2, custom_n2, custom_f2, custom_d2, custom_t3, custom_n3, custom_f3, custom_d3, custom_t4, custom_n4, custom_f4, custom_d4, custom_t5, custom_n5, custom_f5, custom_d5, custom_t6, custom_t7, custom_t8, custom_t9, custom_t10, custom_t11) VALUES (1, 'Boite ENTRANT 001', NULL, NULL, NULL, 0, 'NEW', '2009-09-16 15:59:34.436', NULL, NULL, NULL, NULL, NULL, 'PA', NULL, NULL, NULL, 'superadmin', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO ar_boxes (arbox_id, title, subject, description, entity_id, arcontainer_id, status, creation_date, retention_time, custom_t1, custom_n1, custom_f1, custom_d1, custom_t2, custom_n2, custom_f2, custom_d2, custom_t3, custom_n3, custom_f3, custom_d3, custom_t4, custom_n4, custom_f4, custom_d4, custom_t5, custom_n5, custom_f5, custom_d5, custom_t6, custom_t7, custom_t8, custom_t9, custom_t10, custom_t11) VALUES (2, 'Boite ENTRANT 002', NULL, NULL, NULL, 0, 'NEW', '2009-09-16 15:59:54.176', NULL, NULL, NULL, NULL, NULL, 'PA', NULL, NULL, NULL, 'superadmin', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO ar_boxes (arbox_id, title, subject, description, entity_id, arcontainer_id, status, creation_date, retention_time, custom_t1, custom_n1, custom_f1, custom_d1, custom_t2, custom_n2, custom_f2, custom_d2, custom_t3, custom_n3, custom_f3, custom_d3, custom_t4, custom_n4, custom_f4, custom_d4, custom_t5, custom_n5, custom_f5, custom_d5, custom_t6, custom_t7, custom_t8, custom_t9, custom_t10, custom_t11) VALUES (3, 'Boite SORTANT 001', NULL, NULL, NULL, 0, 'NEW', '2009-09-16 16:00:07.569', NULL, NULL, NULL, NULL, NULL, 'PA', NULL, NULL, NULL, 'superadmin', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO ar_boxes (arbox_id, title, subject, description, entity_id, arcontainer_id, status, creation_date, retention_time, custom_t1, custom_n1, custom_f1, custom_d1, custom_t2, custom_n2, custom_f2, custom_d2, custom_t3, custom_n3, custom_f3, custom_d3, custom_t4, custom_n4, custom_f4, custom_d4, custom_t5, custom_n5, custom_f5, custom_d5, custom_t6, custom_t7, custom_t8, custom_t9, custom_t10, custom_t11) VALUES (4, 'Boite INTERNE 001', NULL, NULL, NULL, 0, 'NEW', '2009-09-16 16:00:29.896', NULL, NULL, NULL, NULL, NULL, 'PA', NULL, NULL, NULL, 'superadmin', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO ar_boxes (arbox_id, title, subject, description, entity_id, arcontainer_id, status, creation_date, retention_time, custom_t1, custom_n1, custom_f1, custom_d1, custom_t2, custom_n2, custom_f2, custom_d2, custom_t3, custom_n3, custom_f3, custom_d3, custom_t4, custom_n4, custom_f4, custom_d4, custom_t5, custom_n5, custom_f5, custom_d5, custom_t6, custom_t7, custom_t8, custom_t9, custom_t10, custom_t11) VALUES (5, 'Boite PROJET 001', NULL, NULL, NULL, 0, 'NEW', '2009-09-16 16:01:00.765', NULL, NULL, NULL, NULL, NULL, 'PA', NULL, NULL, NULL, 'superadmin', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

INSERT INTO ar_batch (arbatch_id, title, subject, description, arbox_id, status, creation_date, retention_time, custom_t1, custom_n1, custom_f1, custom_d1, custom_t2, custom_n2, custom_f2, custom_d2, custom_t3, custom_n3, custom_f3, custom_d3, custom_t4, custom_n4, custom_f4, custom_d4, custom_t5, custom_n5, custom_f5, custom_d5, custom_t6, custom_t7, custom_t8, custom_t9, custom_t10, custom_t11) VALUES (1, '1', NULL, NULL, 1, 'NEW', '2009-09-16 18:26:27.979', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'bblier', NULL, NULL, NULL, NULL, NULL, NULL, '2009-09-16 18:26:27.979', NULL, NULL, NULL, NULL, 'LETTERBOX', NULL, NULL, NULL, NULL, NULL);

INSERT INTO ar_container_types (ctype_id, ctype_desc, size_x, size_y, size_z) VALUES
('BOITE', 'Boite archive standard', 0, 0, 0),
('CONTENEUR', 'Conteneur de 5 boites', 0, 0, 0);

INSERT INTO ar_sites (site_id, site_desc, entity_id) VALUES
('FR01', 'Site de Paris', 'COU'),
('DK01', 'Site de Dakar', 'COU');

INSERT INTO ar_natures (arnature_id, arnature_desc, arnature_retention, entity_id, enabled) VALUES ('DOSPROJ', 'Dossiers de projet', 10, 'COR', 'Y');
INSERT INTO ar_natures (arnature_id, arnature_desc, arnature_retention, entity_id, enabled) VALUES ('DOSTECH', 'Dossiers techniques', 10, 'COR', 'Y');
INSERT INTO ar_natures (arnature_id, arnature_desc, arnature_retention, entity_id, enabled) VALUES ('DOSRH', 'Dossiers RH', 30, 'COR', 'Y');
INSERT INTO ar_natures (arnature_id, arnature_desc, arnature_retention, entity_id, enabled) VALUES ('DOSACC', 'Dossiers comptables', 10, 'COR', 'Y');

INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (1, 'FR01', 'A', 1, 1, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (2, 'FR01', 'A', 1, 2, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (3, 'FR01', 'A', 1, 3, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (4, 'FR01', 'A', 2, 1, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (5, 'FR01', 'A', 2, 2, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (6, 'FR01', 'A', 2, 3, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (7, 'FR01', 'A', 3, 1, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (8, 'FR01', 'A', 3, 2, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (9, 'FR01', 'A', 3, 3, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (10, 'FR01', 'A', 4, 1, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (11, 'FR01', 'A', 4, 2, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (12, 'FR01', 'A', 4, 3, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (13, 'FR01', 'A', 5, 1, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (14, 'FR01', 'A', 5, 2, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (15, 'FR01', 'A', 5, 3, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (16, 'FR01', 'A', 6, 1, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (17, 'FR01', 'A', 6, 2, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (18, 'FR01', 'A', 6, 3, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (19, 'FR01', 'A', 7, 1, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (20, 'FR01', 'A', 7, 2, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (21, 'FR01', 'A', 7, 3, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (22, 'FR01', 'A', 8, 1, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (23, 'FR01', 'A', 8, 2, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (24, 'FR01', 'A', 8, 3, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (25, 'FR01', 'A', 9, 1, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (26, 'FR01', 'A', 9, 2, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (27, 'FR01', 'A', 9, 3, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (28, 'FR01', 'A', 10, 1, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (29, 'FR01', 'A', 10, 2, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (30, 'FR01', 'A', 10, 3, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (31, 'FR01', 'B', 1, 1, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (32, 'FR01', 'B', 1, 2, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (33, 'FR01', 'B', 1, 3, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (34, 'FR01', 'B', 2, 1, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (35, 'FR01', 'B', 2, 2, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (36, 'FR01', 'B', 2, 3, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (37, 'FR01', 'B', 3, 1, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (38, 'FR01', 'B', 3, 2, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (39, 'FR01', 'B', 3, 3, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (40, 'FR01', 'B', 4, 1, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (41, 'FR01', 'B', 4, 2, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (42, 'FR01', 'B', 4, 3, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (43, 'FR01', 'B', 5, 1, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (44, 'FR01', 'B', 5, 2, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (45, 'FR01', 'B', 5, 3, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (46, 'FR01', 'B', 6, 1, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (47, 'FR01', 'B', 6, 2, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (48, 'FR01', 'B', 6, 3, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (49, 'FR01', 'B', 7, 1, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (50, 'FR01', 'B', 7, 2, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (51, 'FR01', 'B', 7, 3, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (52, 'FR01', 'B', 8, 1, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (53, 'FR01', 'B', 8, 2, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (54, 'FR01', 'B', 8, 3, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (55, 'FR01', 'B', 9, 1, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (56, 'FR01', 'B', 9, 2, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (57, 'FR01', 'B', 9, 3, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (58, 'FR01', 'B', 10, 1, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (59, 'FR01', 'B', 10, 2, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (60, 'FR01', 'B', 10, 3, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (61, 'FR01', 'C', 1, 1, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (62, 'FR01', 'C', 1, 2, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (63, 'FR01', 'C', 1, 3, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (64, 'FR01', 'C', 2, 1, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (65, 'FR01', 'C', 2, 2, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (66, 'FR01', 'C', 2, 3, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (67, 'FR01', 'C', 3, 1, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (68, 'FR01', 'C', 3, 2, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (69, 'FR01', 'C', 3, 3, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (70, 'FR01', 'C', 4, 1, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (71, 'FR01', 'C', 4, 2, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (72, 'FR01', 'C', 4, 3, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (73, 'FR01', 'C', 5, 1, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (74, 'FR01', 'C', 5, 2, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (75, 'FR01', 'C', 5, 3, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (76, 'FR01', 'C', 6, 1, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (77, 'FR01', 'C', 6, 2, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (78, 'FR01', 'C', 6, 3, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (79, 'FR01', 'C', 7, 1, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (80, 'FR01', 'C', 7, 2, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (81, 'FR01', 'C', 7, 3, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (82, 'FR01', 'C', 8, 1, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (83, 'FR01', 'C', 8, 2, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (84, 'FR01', 'C', 8, 3, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (85, 'FR01', 'C', 9, 1, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (86, 'FR01', 'C', 9, 2, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (87, 'FR01', 'C', 9, 3, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (88, 'FR01', 'C', 10, 1, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (89, 'FR01', 'C', 10, 2, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (90, 'FR01', 'C', 10, 3, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (91, 'FR01', 'D', 1, 1, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (92, 'FR01', 'D', 1, 2, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (93, 'FR01', 'D', 1, 3, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (94, 'FR01', 'D', 2, 1, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (95, 'FR01', 'D', 2, 2, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (96, 'FR01', 'D', 2, 3, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (97, 'FR01', 'D', 3, 1, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (98, 'FR01', 'D', 3, 2, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (99, 'FR01', 'D', 3, 3, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (100, 'FR01', 'D', 4, 1, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (101, 'FR01', 'D', 4, 2, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (102, 'FR01', 'D', 4, 3, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (103, 'FR01', 'D', 5, 1, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (104, 'FR01', 'D', 5, 2, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (105, 'FR01', 'D', 5, 3, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (106, 'FR01', 'D', 6, 1, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (107, 'FR01', 'D', 6, 2, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (108, 'FR01', 'D', 6, 3, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (109, 'FR01', 'D', 7, 1, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (110, 'FR01', 'D', 7, 2, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (111, 'FR01', 'D', 7, 3, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (112, 'FR01', 'D', 8, 1, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (113, 'FR01', 'D', 8, 2, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (114, 'FR01', 'D', 8, 3, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (115, 'FR01', 'D', 9, 1, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (116, 'FR01', 'D', 9, 2, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (117, 'FR01', 'D', 9, 3, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (118, 'FR01', 'D', 10, 1, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (119, 'FR01', 'D', 10, 2, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (120, 'FR01', 'D', 10, 3, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (121, 'FR01', 'E', 1, 1, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (122, 'FR01', 'E', 1, 2, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (123, 'FR01', 'E', 1, 3, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (124, 'FR01', 'E', 2, 1, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (125, 'FR01', 'E', 2, 2, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (126, 'FR01', 'E', 2, 3, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (127, 'FR01', 'E', 3, 1, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (128, 'FR01', 'E', 3, 2, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (129, 'FR01', 'E', 3, 3, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (130, 'FR01', 'E', 4, 1, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (131, 'FR01', 'E', 4, 2, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (132, 'FR01', 'E', 4, 3, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (133, 'FR01', 'E', 5, 1, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (134, 'FR01', 'E', 5, 2, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (135, 'FR01', 'E', 5, 3, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (136, 'FR01', 'E', 6, 1, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (137, 'FR01', 'E', 6, 2, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (138, 'FR01', 'E', 6, 3, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (139, 'FR01', 'E', 7, 1, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (140, 'FR01', 'E', 7, 2, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (141, 'FR01', 'E', 7, 3, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (142, 'FR01', 'E', 8, 1, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (143, 'FR01', 'E', 8, 2, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (144, 'FR01', 'E', 8, 3, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (145, 'FR01', 'E', 9, 1, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (146, 'FR01', 'E', 9, 2, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (147, 'FR01', 'E', 9, 3, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (148, 'FR01', 'E', 10, 1, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (149, 'FR01', 'E', 10, 2, 4, 4);
INSERT INTO ar_positions (position_id, site_id, pos_row, pos_col, pos_level, pos_max_uc, pos_available_uc) VALUES (150, 'FR01', 'E', 10, 3, 4, 4);

-- DOCSERVERS

INSERT INTO docserver_locations (docserver_location_id, ipv4, ipv6, net_domain, mask, enabled, net_link) VALUES ('NANTERRE', '127.0.0.1', '' , 'MAARCH', '255.255.255.0', 'Y', NULL);
INSERT INTO docserver_locations (docserver_location_id, ipv4, ipv6, net_domain, mask, enabled, net_link) VALUES ('NICE', '192.168.21.63', '' , '' , '' , 'Y', NULL);

INSERT INTO docserver_types (docserver_type_id, docserver_type_label, enabled, is_container, container_max_number, is_compressed, compression_mode, is_meta, meta_template, is_logged, log_template, is_signed, fingerprint_mode) VALUES ('FASTHD', 'FASTHD', 'Y', 'N', 0, 'N', 'NONE', 'N', 'NONE', 'N', 'NONE', 'Y', 'SHA256');
INSERT INTO docserver_types (docserver_type_id, docserver_type_label, enabled, is_container, container_max_number, is_compressed, compression_mode, is_meta, meta_template, is_logged, log_template, is_signed, fingerprint_mode) VALUES ('OAIS_MAIN', 'Main OAIS store', 'Y', 'Y', 100, 'Y', '7Z', 'Y', 'OAIS_std.dtd', 'Y', 'log_std.dtd', 'Y', 'SHA512');
INSERT INTO docserver_types (docserver_type_id, docserver_type_label, enabled, is_container, container_max_number, is_compressed, compression_mode, is_meta, meta_template, is_logged, log_template, is_signed, fingerprint_mode) VALUES ('OFFLINE', 'Off line tape', 'Y', 'Y', 1000, 'Y', '7Z', 'Y', 'OAIS_std.dtd', 'Y', 'log_std.dtd', 'Y', 'SHA512');
INSERT INTO docserver_types (docserver_type_id, docserver_type_label, enabled, is_container, container_max_number, is_compressed, compression_mode, is_meta, meta_template, is_logged, log_template, is_signed, fingerprint_mode) VALUES ('OAIS_SAFE', 'Distant backup OAIS store', 'Y', 'Y', 20, 'Y', 'ZIP', 'Y', 'OAIS_std.dtd', 'Y', 'log_std.dtd', 'Y', 'SHA512');
INSERT INTO docserver_types (docserver_type_id, docserver_type_label, enabled, is_container, container_max_number, is_compressed, compression_mode, is_meta, meta_template, is_logged, log_template, is_signed, fingerprint_mode) VALUES ('TEMPLATES', 'TEMPLATES', 'Y', 'N', 0, 'N', 'NONE', 'N', 'NONE', 'N', 'NONE', 'N', 'NONE');

INSERT INTO docservers (docserver_id, docserver_type_id, device_label, is_readonly, enabled, size_limit_number, actual_size_number, path_template, ext_docserver_info, chain_before, chain_after, creation_date, closing_date, coll_id, priority_number, docserver_location_id, adr_priority_number) VALUES ('FASTHD_MAN', 'FASTHD', 'Fast internal disc bay for letterbox mode', 'N', 'Y', 200000000000, 0, 'C:\\maarch\\docservers\\entreprise\\manual\\', NULL, NULL, NULL, '2011-01-13 14:47:49.197164', NULL, 'letterbox_coll', 10, 'NANTERRE', 2);
INSERT INTO docservers (docserver_id, docserver_type_id, device_label, is_readonly, enabled, size_limit_number, actual_size_number, path_template, ext_docserver_info, chain_before, chain_after, creation_date, closing_date, coll_id, priority_number, docserver_location_id, adr_priority_number) VALUES ('OFFLINE_1', 'OFFLINE', 'Off line tape', 'N', 'Y', 50000000000, 0, 'C:\\maarch\\docservers\\entreprise\\offline\\', NULL, NULL, NULL, '2011-01-13 16:58:24.00929', NULL, 'res_coll', 30, 'NANTERRE', 4);
INSERT INTO docservers (docserver_id, docserver_type_id, device_label, is_readonly, enabled, size_limit_number, actual_size_number, path_template, ext_docserver_info, chain_before, chain_after, creation_date, closing_date, coll_id, priority_number, docserver_location_id, adr_priority_number) VALUES ('FASTHD_AI', 'FASTHD', 'Fast internal disc bay for autoimport', 'N', 'Y', 50000000000, 1, 'C:\\maarch\\docservers\\entreprise\\ai\\', NULL, NULL, NULL, '2011-01-07 13:43:48.696644', NULL, 'res_coll', 11, 'NANTERRE', 1);
INSERT INTO docservers (docserver_id, docserver_type_id, device_label, is_readonly, enabled, size_limit_number, actual_size_number, path_template, ext_docserver_info, chain_before, chain_after, creation_date, closing_date, coll_id, priority_number, docserver_location_id, adr_priority_number) VALUES ('OAIS_MAIN_1', 'OAIS_MAIN', 'Main OAIS store', 'N', 'Y', 50000000000, 1, 'C:\\maarch\\docservers\\entreprise\\OAIS_main\\', NULL, NULL, NULL, '2011-01-13 14:48:27.901368', NULL, 'res_coll', 20, 'NANTERRE', 2);
INSERT INTO docservers (docserver_id, docserver_type_id, device_label, is_readonly, enabled, size_limit_number, actual_size_number, path_template, ext_docserver_info, chain_before, chain_after, creation_date, closing_date, coll_id, priority_number, docserver_location_id, adr_priority_number) VALUES ('OAIS_SAFE_1', 'OAIS_SAFE', 'Distant backup OAIS store', 'N', 'Y', 50000000000, 1, 'C:\\maarch\\docservers\\entreprise\\OAIS_safe\\', NULL, NULL, NULL, '2011-01-13 14:49:05.095119', NULL, 'res_coll', 21, 'NICE', 3); 
INSERT INTO docservers (docserver_id, docserver_type_id, device_label, is_readonly, enabled, size_limit_number, actual_size_number, path_template, ext_docserver_info, chain_before, chain_after, creation_date, closing_date, coll_id, priority_number, docserver_location_id, adr_priority_number) VALUES ('TEMPLATES', 'TEMPLATES', 'Templates', 'N', 'Y', 50000000000, 1, 'C:\\maarch\\docservers\\entreprise\\templates\\', NULL, NULL, NULL, '2012-04-01 14:49:05.095119', NULL, 'templates', 1, 'NANTERRE', 1);
