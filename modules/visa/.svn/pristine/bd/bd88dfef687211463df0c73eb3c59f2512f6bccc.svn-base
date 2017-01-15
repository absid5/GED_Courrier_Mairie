-- AJOUT DU TYPE DE LISTE DE DIFFUSION POUR LE CIRCUIT DE VISA
INSERT INTO difflist_types VALUES ('VISA_CIRCUIT', 'Circuit de visa', 'visa sign ', 'N', 'N');

-- AJOUT DES BANNETTES 
INSERT INTO baskets (coll_id, basket_id, basket_name, basket_desc, basket_clause, is_generic, is_visible, is_folder_basket, enabled) VALUES ('letterbox_coll', 'EvisBasket', '[courrier] Courriers à e-viser', 'Courriers à e-viser', 'status=''EVIS'' and (res_id,@user) IN (SELECT DISTINCT ON (res_id)
       res_id, item_id
FROM   listinstance
WHERE item_mode = ''visa'' and process_date ISNULL
ORDER  BY res_id asc)', 'N', 'Y', 'N', 'Y');
INSERT INTO baskets (coll_id, basket_id, basket_name, basket_desc, basket_clause, is_generic, is_visible, is_folder_basket, enabled) VALUES ('letterbox_coll', 'EsigBasket', '[courrier] Courriers à e-signer', 'Courriers à e-signer', 'status=''ESIG'' and (res_id,@user) IN (SELECT DISTINCT ON (res_id)
       res_id, item_id
FROM   listinstance
WHERE item_mode = ''sign'' and process_date ISNULL
ORDER  BY res_id asc)', 'N', 'Y', 'N', 'Y');

INSERT INTO baskets (coll_id, basket_id, basket_name, basket_desc, basket_clause, is_generic, is_visible, is_folder_basket, enabled) VALUES ('letterbox_coll', 'PvalBasket', '[courrier] Circuit visa des courriers à préparer', 'Circuit visa des courriers à préparer', 'status=''PVAL''', 'N', 'Y', 'N', 'Y');
INSERT INTO baskets (coll_id, basket_id, basket_name, basket_desc, basket_clause, is_generic, is_visible, is_folder_basket, enabled) VALUES ('letterbox_coll', 'CvalBasket', '[courrier] Circuit visa des courriers à valider', 'Circuit visa des courriers à valider', 'status=''CVAL''', 'N', 'Y', 'N', 'Y');
INSERT INTO baskets (coll_id, basket_id, basket_name, basket_desc, basket_clause, is_generic, is_visible, is_folder_basket, enabled) VALUES ('letterbox_coll', 'DimpBasket', '[courrier] Circuit visa des courriers à imprimer', 'Circuit visa des courriers à imprimer', 'status=''DIMP''', 'N', 'Y', 'N', 'Y');
INSERT INTO baskets (coll_id, basket_id, basket_name, basket_desc, basket_clause, is_generic, is_visible, is_folder_basket, enabled) VALUES ('letterbox_coll', 'EenvBasket', '[courrier] Courriers à e-envoyer', 'Courriers à e-envoyer', 'status=''EENV''', 'N', 'Y', 'N', 'Y');


-- AJOUT DES STATUS 
INSERT INTO status (id, label_status, is_system, is_folder_status, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES ('DIMP', 'Dossier à imprimer', 'N', 'N', '', 'apps', 'Y', 'Y');
INSERT INTO status (id, label_status, is_system, is_folder_status, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES ('EENV', 'A e-envoyer', 'N', 'N', '', 'apps', 'Y', 'Y');
INSERT INTO status (id, label_status, is_system, is_folder_status, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES ('ESIG', 'A e-signer', 'N', 'N', '', 'apps', 'Y', 'Y');
INSERT INTO status (id, label_status, is_system, is_folder_status, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES ('EVIS', 'A e-viser', 'N', 'N', '', 'apps', 'Y', 'Y');
INSERT INTO status (id, label_status, is_system, is_folder_status, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES ('PVAL', 'Projet de réponse à valider', 'N', 'N', '', 'apps', 'Y', 'Y');
INSERT INTO status (id, label_status, is_system, is_folder_status, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES ('CVAL', 'Circuit de visa à valider', 'N', 'N', '', 'apps', 'Y', 'Y');
INSERT INTO status (id, label_status, is_system, is_folder_status, img_filename, maarch_module, can_be_searched, can_be_modified) VALUES ('WAIT', 'En attente  de la réponse signée', 'N', 'N', '', 'apps', 'Y', 'Y');


-- AJOUT DES ACTIONS
INSERT INTO actions (id, keyword, label_action, id_status, is_system, enabled, action_page, history, origin, create_id) VALUES (400, '', 'Envoyer le projet de réponse', 'PVAL', 'N', 'Y', '', 'Y', 'apps', 'N');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, enabled, action_page, history, origin, create_id) VALUES (401, '', 'Préparer le circuit de visa', '_NOSTATUS_', 'N', 'Y', 'prepare_visa', 'Y', 'visa', 'N');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, enabled, action_page, history, origin, create_id) VALUES (402, '', 'Transmettre le circuit de visa', 'CVAL', 'N', 'Y', '', 'Y', 'apps', 'N');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, enabled, action_page, history, origin, create_id) VALUES (403, '', 'Envoyer pour e-visa et signature papier', 'EVIS', 'N', 'Y', '', 'Y', 'apps', 'N');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, enabled, action_page, history, origin, create_id) VALUES (404, '', 'Valider et envoyer pour impression', 'DIMP', 'N', 'Y', '', 'Y', 'apps', 'N');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, enabled, action_page, history, origin, create_id) VALUES (405, '', 'Viser le courrier', '_NOSTATUS_', 'N', 'Y', 'visa_mail', 'Y', 'visa', 'N');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, enabled, action_page, history, origin, create_id) VALUES (407, '', 'Renvoyer pour traitement', 'ENC', 'N', 'Y', 'confirm_status', 'Y', 'apps', 'N');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, enabled, action_page, history, origin, create_id) VALUES (408, '', 'Demander une révision mineure', 'REV', 'N', 'Y', 'confirm_status', 'Y', 'apps', 'N');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, enabled, action_page, history, origin, create_id) VALUES (409, '', 'E-Parapheur à imprimer', 'DIMP', 'N', 'Y', '', 'Y', 'apps', 'N');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, enabled, action_page, history, origin, create_id) VALUES (410, '', 'Transmettre pour e-envoi', 'EENV', 'N', 'Y', 'confirm_status', 'Y', 'apps', 'N');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, enabled, action_page, history, origin, create_id) VALUES (411, '', 'Transmettre pour classement', 'CLAS', 'N', 'Y', '', 'Y', 'apps', 'N');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, enabled, action_page, history, origin, create_id) VALUES (412, '', 'Imprimer le dossier', 'WAIT', 'N', 'Y', 'print_folder', 'Y', 'visa', 'N');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, enabled, action_page, history, origin, create_id) VALUES (413, '', 'E-envoyer un dossier', '_NOSTATUS_', 'N', 'Y', 'send_email', 'Y', 'visa', 'N');
INSERT INTO actions (id, keyword, label_action, id_status, is_system, enabled, action_page, history, origin, create_id) VALUES (414, '', 'Envoyer pour e-visa et e-signature', 'EVIS', 'N', 'Y', 'confirm_status', 'Y', 'apps', 'N');