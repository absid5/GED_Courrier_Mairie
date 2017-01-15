<?php
/*
 *
 *    Copyright 2008,2009 Maarch
 *
 *  This file is part of Maarch Framework.
 *
 *   Maarch Framework is free software: you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation, either version 3 of the License, or
 *   (at your option) any later version.
 *
 *   Maarch Framework is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *    along with Maarch Framework.  If not, see <http://www.gnu.org/licenses/>.
 */

/*********************** SERVICES ***********************************/
if (!defined("_ADMIN_FOLDERTYPES"))
    define("_ADMIN_FOLDERTYPES", "Types de dossier");
if (!defined("_ATTACH_DOC_TO_FOLDER"))
    define("_ATTACH_DOC_TO_FOLDER", "Rattacher un document à un dossier");
if (!defined("_ADMIN_FOLDERTYPES_DESC"))
    define("_ADMIN_FOLDERTYPES_DESC", "Administrer les types de dossier. Définir pour chaque type les qualificateurs liés et les types de documents obligatoires pour la complétude du dossier.");

/*********************** Menu ***********************************/
if (!defined("_FOLDER_SEARCH"))
    define("_FOLDER_SEARCH", "Rechercher un dossier");
if (!defined("_SALARY_SHEET"))
    define("_SALARY_SHEET", "Fiche dossier");
if (!defined("_FOLDER_OUT"))
    define("_FOLDER_OUT", "Désarchivage");

//////////////Recherche dossier
if (!defined("_NOT_THE_OWNER_OF_THIS_FOLDER"))
    define("_NOT_THE_OWNER_OF_THIS_FOLDER", "Vous devez être le propriétaire du dossier. Pour supprimer, veuillez consulter l administrateur ou demander à l utilisateur ");
if (!defined("_SELECT_FOLDER_TITLE"))
    define("_SELECT_FOLDER_TITLE", "Sélection du dossier");
if (!defined("_FOLDER_NUM"))
    define("_FOLDER_NUM", "N° Dossier");
if (!defined("_COMPLETE"))
    define("_COMPLETE", "Complet");
if (!defined("_INCOMPLETE"))
    define("_INCOMPLETE", "Incomplet");
if (!defined("_FOUND_FOLDER"))
    define("_FOUND_FOLDER", "dossier(s) trouvé(s)");
if (!defined("_CHOOSE"))
    define("_CHOOSE", "Choisir");
if (!defined("_ADV_SEARCH_FOLDER_TITLE"))
    define("_ADV_SEARCH_FOLDER_TITLE", "Recherche de dossier");
if (!defined("_SEARCH_ADV_FOLDER"))
    define("_SEARCH_ADV_FOLDER", "Recherche de dossier");
if (!defined("_NEW_SEARCH"))
    define("_NEW_SEARCH", "Effacer les critères");
if (!defined("_SELECT_FOLDER"))
    define("_SELECT_FOLDER", "Sélection Dossier");
if (!defined("_CREATE_FOLDER"))
    define("_CREATE_FOLDER", "Créer un dossier");
if (!defined("_CREATE_FOLDER2"))
    define("_CREATE_FOLDER2", "Créer Dossier");
if (!defined("_FOLDER"))
    define("_FOLDER", "Dossier");
if (!defined("_MODIFY_FOLDER"))
    define("_MODIFY_FOLDER", "Modifier les index d'un dossier");
if (!defined("_FOLDERID"))
    define("_FOLDERID", "Numéro du Dossier");
if (!defined("_FOLDERSYSTEMID"))
    define("_FOLDERSYSTEMID", "Numéro système Maarch");
if (!defined("_FOLDERID_LONG"))
    define("_FOLDERID_LONG", "Identifiant du dossier");
if (!defined("_FOLDER_DESTINATION_QUESTION"))
    define("_FOLDER_DESTINATION_QUESTION", "Rendre ce dossier uniquement accessible à votre service ?");
if (!defined("_FOLDER_DESTINATION_SHORT"))
    define("_FOLDER_DESTINATION_SHORT", "Dossier de service");
if (!defined("_FOLDERNAME"))
    define("_FOLDERNAME", "Nom du Dossier");
if (!defined("_FOLDERDATE"))
    define("_FOLDERDATE", "Date de création");
if (!defined("_FOLDERDATE_START"))
    define("_FOLDERDATE_START", "Date de création début ");
if (!defined("_FOLDERDATE_END"))
    define("_FOLDERDATE_END", "Date de création fin ");
if (!defined("_FOLDERHASNODOC"))
    define("_FOLDERHASNODOC","Aucune pièce pour ce dossier");
if (!defined("_OTHER_INFOS"))
    define("_OTHER_INFOS","Autres informations : historique du dossier et pièces manquantes pour la complétude");
if (!defined("_SEARCH_FOLDER"))
    define("_SEARCH_FOLDER","Recherche dossier");
if (!defined("_SELECTED_FOLDER"))
    define("_SELECTED_FOLDER","Dossier sélectionné");
if (!defined("_FOUND_FOLDERS"))
    define("_FOUND_FOLDERS","Dossiers trouvés");
if (!defined("_FOLDERTYPE_LABEL"))
    define("_FOLDERTYPE_LABEL","Libellé dossier");
if (!defined("_INFOS_FOLDERS"))
    define("_INFOS_FOLDERS","Infos dossier");
if (!defined("_CHOOSE_FOLDER"))
    define("_CHOOSE_FOLDER", "Choisissez un dossier");
if (!defined("_ON_FOLDER_NUM"))
    define("_ON_FOLDER_NUM", " sur le dossier n°");

//////////////create_folder.php
if (!defined("_CREATE_THE_FOLDER"))
    define("_CREATE_THE_FOLDER", "Créer le dossier");
if (!defined("_NEW_EMPLOYEES_LIST"))
    define("_NEW_EMPLOYEES_LIST","Liste des nouveaux collaborateurs");
if (!defined("_FOLDERS_LIST"))
    define("_FOLDERS_LIST", "Liste de dossiers");
if (!defined("_FOLDERS"))
    define("_FOLDERS", "dossiers");
if (!defined("_FOLDERS_COMMENT"))
    define("_FOLDERS_COMMENT", "Dossiers");
if (!defined("_CHOOSE2"))
    define("_CHOOSE2", "Choisissez");
if (!defined("_IS_MANDATORY"))
    define("_IS_MANDATORY", "est obligatoire");
if (!defined("_FOLDER_CREATION"))
    define("_FOLDER_CREATION", "Création du dossier");

///////////////delete_popup.php
if (!defined("_DEL_FOLDER_NUM"))
    define("_DEL_FOLDER_NUM", "Suppression du dossier n°");
if (!defined("_DEL_FOLDER"))
    define("_DEL_FOLDER", "Supprimer le dossier");

//Step in add_batch.php for physical_archive
if (!defined("_STEP_ONE"))
    define("_STEP_ONE", "1 - Choisissez un dossier");

/////////create folder
if (!defined("_CHOOSE_SOCIETY"))
    define("_CHOOSE_SOCIETY", "Choisissez une société");
if (!defined("_THE_SOCIETY"))
    define("_THE_SOCIETY", "La société ");
if (!defined("_MISSING_DOC"))
    define("_MISSING_DOC", "Pièces manquantes");
if (!defined("_MISSING_DOC2"))
    define("_MISSING_DOC2", "Pièce(s) manquante(s)");
if (!defined("_PLEASE_SELECT_FOLDER"))
    define("_PLEASE_SELECT_FOLDER", "Vous devez sélectionner un dossier");
if (!defined("_FOLDER_HISTORY"))
    define("_FOLDER_HISTORY", "Historique dossier");
if (!defined("_CHOOSE_FOLDERTYPE"))
    define("_CHOOSE_FOLDERTYPE", "Choisissez un type de dossier");
if (!defined("_BROWSE_BY_FOLDER"))
    define("_BROWSE_BY_FOLDER", "Recherche Dossier");
if (!defined("_CHAR_ERROR"))
    define("_CHAR_ERROR", "L'identifiant ne peut pas contenir les caractères suivants : ", "");

/*************************** Foldertypes management *****************/
if (!defined("_FOLDERTYPE_ADDITION"))
    define("_FOLDERTYPE_ADDITION", "Ajout type de dossier");
if (!defined("_FOLDERTYPE_MODIFICATION"))
    define("_FOLDERTYPE_MODIFICATION", "Modification du type de dossier");
if (!defined("_FOLDERTYPES_LIST"))
    define("_FOLDERTYPES_LIST", "Liste des types de dossier");
if (!defined("_TYPES"))
    define("_TYPES", "type(s)");
if (!defined("_ALL_FOLDERTYPES"))
    define("_ALL_FOLDERTYPES", "Tous les types");
if (!defined("_FOLDERTYPE"))
    define("_FOLDERTYPE", "Type de dossier");
if (!defined("_FOLDERTYPE_MISSING"))
    define("_FOLDERTYPE_MISSING", "Type de dossier manquant");

/************************** Fiche salarie ***************************/

if (!defined("_ARCHIVED_DOC"))
    define("_ARCHIVED_DOC", "Pièces archivées");
if (!defined("_SEND_RELANCE_MAIL"))
    define("_SEND_RELANCE_MAIL", "Envoyer un mail de relance");
if (!defined("_DIRECTION_DEP"))
    define("_DIRECTION_DEP", "Direction/Dpt");
if (!defined("_DEP_AGENCY"))
    define("_DEP_AGENCY", "Service/agence");
if (!defined("_DELETE_FOLDER"))
    define("_DELETE_FOLDER", "Supprimer un dossier");
if (!defined("_DELETE_FOLDER_NOTES1"))
    define("_DELETE_FOLDER_NOTES1", "La suppression de dossier est irréversible, les pièces de ce dernier seront conservées mais ne seront plus accessibles en consultation.");
if (!defined("_REALLY_DELETE_FOLDER"))
    define("_REALLY_DELETE_FOLDER", "Voulez vous supprimer le dossier ?");
if (!defined("_DELETE_FOLDER_NOTES2"))
    define("_DELETE_FOLDER_NOTES2","Pour supprimer définitivement le dossier, saisissez EFFACER (en lettres majuscules) dans la case ci-dessous.");
if (!defined("_DELETE_FOLDER_NOTES3"))
    define("_DELETE_FOLDER_NOTES3", "Le dossier sera effacé après cette validation.");
if (!defined("_DELETE_FOLDER_NOTES4"))
    define("_DELETE_FOLDER_NOTES4", "Le dossier ne peut être supprimé car la confirmation est erronée");
if (!defined("_DELETE_FOLDER_NOTES5"))
    define("_DELETE_FOLDER_NOTES5", "Le dossier est désormais supprimé de la base de données.");
if (!defined("_FOLDER_INDEX_MODIF"))
    define("_FOLDER_INDEX_MODIF", "Modification des index du dossier");
if (!defined("_FOLDERS_OUT"))
    define("_FOLDERS_OUT", "Dossiers désarchivés");

///////////////// Class_admin_foldertype
//CUSTOM
if (!defined("_MANDATORY_DOCTYPES_COMP"))
    define("_MANDATORY_DOCTYPES_COMP", "Types de document obligatoire pour la complétude du dossier");
if (!defined("_FOLDER_ID"))
    define("_FOLDER_ID", "Identifiant dossier");
if (!defined("_INDEX_FOR_FOLDERTYPES"))
    define("_INDEX_FOR_FOLDERTYPES", "Index possibles pour les types de dossier");
if (!defined("_SELECTED_DOCTYPES"))
    define("_SELECTED_DOCTYPES", "Types de document selectionnés");
if (!defined("_SHOW_FOLDER"))
    define("_SHOW_FOLDER", "Fiche dossier");
if (!defined("_FOLDERTYPE_UPDATE"))
    define("_FOLDERTYPE_UPDATE", "Type de dossier modifié");
if (!defined("_FOLDER_ATTACH"))
    define("_FOLDER_ATTACH", "Rattachement à un dossier");
if (!defined("_INCOMPATIBILITY_MARKET_PROJECT"))
    define("_INCOMPATIBILITY_MARKET_PROJECT", "Incompatibilité entre le Dossier et le Sous-dossier");
if (!defined("_FOLDER_VIEW_STAT"))
    define("_FOLDER_VIEW_STAT", "Nombre de dossiers consultés");
if (!defined("_FOLDER_VIEW_STAT_DESC"))
    define("_FOLDER_VIEW_STAT_DESC", "Nombre de dossiers consultés");
if (!defined("_FOLDER_HISTORY_STAT"))
    define("_FOLDER_HISTORY_STAT", "Historique d'un dossier");
if (!defined("_FOLDER_HISTORY_STAT_DESC"))
    define("_FOLDER_HISTORY_STAT_DESC", "Historique d'un dossier");
if (!defined("_VIEW_FOLDER"))
    define("_VIEW_FOLDER", "Visualisation du dossier");

////////// Reports label
if (!defined("_TITLE_STATS_CHOICE_FOLDER_TYPE"))
    define("_TITLE_STATS_CHOICE_FOLDER_TYPE","Par type de dossier");
if (!defined("_TITLE_STATS_CHOICE_GROUP"))
    define("_TITLE_STATS_CHOICE_GROUP","Par groupe d'utilisateurs");
if (!defined("_TITLE_STATS_CHOICE_USER"))
    define("_TITLE_STATS_CHOICE_USER","Pour un utilisateur");
if (!defined("_TITLE_STATS_CHOICE_USER2"))
    define("_TITLE_STATS_CHOICE_USER2","par l'utilisateur");
if (!defined("_TITLE_STATS_NO_FOLDERS_VIEW"))
    define("_TITLE_STATS_NO_FOLDERS_VIEW","Aucun dossier consulté pour la période");
if (!defined("_STATS_ERROR_CHOSE_USER"))
    define("_STATS_ERROR_CHOSE_USER","Il faut choisir un utilisateur existant.");
if (!defined("_NB_FOLDERS"))
    define("_NB_FOLDERS", "Nombre de dossiers" );
if (!defined("_NB_VIEWED_FOLDERS"))
    define("_NB_VIEWED_FOLDERS","Nombre de dossiers consultés");
if (!defined("_TITLE_STATS_CHOICE_ACTION"))
    define("_TITLE_STATS_CHOICE_ACTION","par type d'action");
if (!defined("_ACTION_TYPE"))
    define("_ACTION_TYPE", "Type d'action");
if (!defined("_NO_STRUCTURE_ATTACHED2"))
    define("_NO_STRUCTURE_ATTACHED2", "Ce type de dossier n'est attaché à aucune chemise");
if (!defined("_FOLDER_ADDED"))
    define("_FOLDER_ADDED", "Nouveau dossier créé");
if (!defined("_FOLDER_DETAILLED_PROPERTIES"))
    define("_FOLDER_DETAILLED_PROPERTIES", "Informations sur le dossier");
if (!defined("_FOLDER_PROPERTIES"))
    define("_FOLDER_PROPERTIES", "Propriétés du dossier");
if (!defined("_SYSTEM_ID"))
    define("_SYSTEM_ID", "ID Système");
if (!defined("_MODIFICATION_DATE"))
    define("_MODIFICATION_DATE", "Date de modification");
if (!defined("_UPDATE_FOLDER"))
    define("_UPDATE_FOLDER", "Modifier des informations");
if (!defined("_FOLDER_INDEX_UPDATED"))
    define("_FOLDER_INDEX_UPDATED", "Index du dossier modifiés");
if (!defined("_FOLDER_UPDATED"))
    define("_FOLDER_UPDATED", "Mise à jour du dossier effectuée");
if (!defined("_ALL_DOCS_AND_SUFOLDERS_WILL_BE_DELETED"))
    define("_ALL_DOCS_AND_SUFOLDERS_WILL_BE_DELETED", "tous les documents de ce dossier, ainsi que tous les sous-dossiers seront également supprimés !");
if (!defined("_STRING"))
    define("_STRING", "Chaine de caractères");
if (!defined("_INTEGER"))
    define("_INTEGER", "Entier");
if (!defined("_FLOAT"))
    define("_FLOAT", "Flottant");
if (!defined("_DATE"))
    define("_DATE", "Date");
if (!defined("_MAX"))
    define("_MAX", "maximum");
if (!defined("_MIN"))
    define("_MIN", "minimum");
if (!defined("_FOLDER_OR_SUBFOLDER"))
    define("_FOLDER_OR_SUBFOLDER", "Dossier/Sous-dossier");
if (!defined("_ERROR_COMPATIBILITY_FOLDER"))
    define("_ERROR_COMPATIBILITY_FOLDER", "Problème de compatibilité entre le dossier et le type de document");
if (!defined("_ADDED_TO_FOLDER"))
    define("_ADDED_TO_FOLDER", " ajouté au dossier");
if (!defined("_DELETED_FROM_FOLDER"))
    define("_DELETED_FROM_FOLDER", " supprimé du dossier");
if (!defined("_CHOOSE_PARENT_FOLDER"))
    define("_CHOOSE_PARENT_FOLDER", "Associer ce dossier à un dossier existant");
if (!defined("_FOLDER_PARENT"))
    define("_FOLDER_PARENT", "Dossier parent");
if (!defined("_FOLDER_PARENT_DESC"))
    define("_FOLDER_PARENT_DESC", "Vous pouvez choisir de créer un sous-dossier en le rattachant à un dossier du même type. Il y a seulement 2 niveaux : dossier et sous-dossier.");
if (!defined("_THIS_FOLDER"))
    define("_THIS_FOLDER", "ce dossier");
if (!defined("_ALL_FOLDERS"))
    define("_ALL_FOLDERS", "Tous les dossiers");
if (!defined("_FOLDER_DELETED"))
    define("_FOLDER_DELETED", "Dossier supprimé");
if (!defined("_FREEZE_FOLDER_SERVICE"))
    define("_FREEZE_FOLDER_SERVICE", "Gel et dégel des dossiers");
if (!defined("_FREEZE_FOLDER"))
    define("_FREEZE_FOLDER", "Geler le dossier");
if (!defined("_UNFREEZE_FOLDER"))
    define("_UNFREEZE_FOLDER", "Dégeler le dossier");
if (!defined("_CLOSE_FOLDER"))
    define("_CLOSE_FOLDER", "Clôturer le dossier");
if (!defined("_FOLDER_CLOSED"))
    define("_FOLDER_CLOSED", "Dossier cloturé");
if (!defined("_FROZEN_FOLDER"))
    define("_FROZEN_FOLDER", "Gel du dossier");
if (!defined("_UNFROZEN_FOLDER"))
    define("_UNFROZEN_FOLDER", "Dégel du dossier");
if (!defined("_REALLY_FREEZE_THIS_FOLDER"))
    define("_REALLY_FREEZE_THIS_FOLDER", "Voulez-vous vraiment geler ce dossier");
if (!defined("_REALLY_CLOSE_THIS_FOLDER"))
    define("_REALLY_CLOSE_THIS_FOLDER", "Voulez-vous vraiment cloturer ce dossier");
if (!defined("_SUBFOLDER"))                         
    define("_SUBFOLDER", "Sous-dossier");
if (!defined("_VIEW_FOLDER_TREE"))                  
    define("_VIEW_FOLDER_TREE", "Consulter un dossier");
if (!defined("_SEARCH_FOLDER_TREE"))                
    define("_SEARCH_FOLDER_TREE", "Recherche Dossiers");
if (!defined("_NB_DOCS_IN_FOLDER"))                 
    define("_NB_DOCS_IN_FOLDER", "Nombre de documents");
if (!defined("_IS_FOLDER_BASKET"))                  
    define("_IS_FOLDER_BASKET", "Corbeille de dossier");
if (!defined("_IS_FOLDER_STATUS"))                  
    define("_IS_FOLDER_STATUS", "Statut de dossier");
if (!defined("_IS_FOLDER_ACTION"))                  
    define("_IS_FOLDER_ACTION", "Action de dossier");
if (!defined("_CONFIRM_FOLDER_STATUS"))             
    define("_CONFIRM_FOLDER_STATUS", "Confirmation simple (dossiers)");
if (!defined("_REDIRECT_FOLDER"))                   
    define("_REDIRECT_FOLDER", "Redirection du dossier");
if (!defined("_REDIRECT_ALL_DOCUMENTS_IN_FOLDER"))  
    define("_REDIRECT_ALL_DOCUMENTS_IN_FOLDER", "Rediriger tous les documents du dossier");
if (!defined("_CHOOSE_ONE_FOLDER"))                 
    define("_CHOOSE_ONE_FOLDER", "Choisissez au moins un dossier");
if (!defined("_MUST_CHOOSE_DEP_OR_USER"))           
    define("_MUST_CHOOSE_DEP_OR_USER", "Vous devez sélectionner un service ou un utilisateur!");
if (!defined('_LABEL'))
    define("_LABEL", "libellé");