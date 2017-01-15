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

/*************************** Entites management *****************/
if (!defined("_ADD_ENTITY"))
    define("_ADD_ENTITY","Entité ajoutée");
if (!defined("_ENTITY_ADDITION"))
    define("_ENTITY_ADDITION", "Ajout d'une entité");
if (!defined("_ENTITY_MODIFICATION"))
    define("_ENTITY_MODIFICATION", "Modification d'une entité");
if (!defined("_ENTITY_AUTORIZATION"))
    define("_ENTITY_AUTORIZATION", "Autorisation d'une entité");
if (!defined("_ENTITY_SUSPENSION"))
    define("_ENTITY_SUSPENSION", "Suspension d'une entité");
if (!defined("_ENTITY_DELETION"))
    define("_ENTITY_DELETION", "Suppression d'une entité");
if (!defined("_ENTITY_DELETED"))
    define("_ENTITY_DELETED", "Entité supprimée");
if (!defined("_ENTITY_UPDATED"))
    define("_ENTITY_UPDATED", "Entité modifiée");
if (!defined("_ENTITY_AUTORIZED"))
    define("_ENTITY_AUTORIZED", "Entité autorisé");
if (!defined("_ENTITY_SUSPENDED"))
    define("_ENTITY_SUSPENDED", "Entité suspendue");
if (!defined("_ENTITY"))
    define("_ENTITY", "Entité");
if (!defined("_ENTITIES"))
    define("_ENTITIES", "Entités");
if (!defined("_ENTITIES_COMMENT"))
    define("_ENTITIES_COMMENT", "Entités");
if (!defined("_ALL_ENTITIES"))
    define("_ALL_ENTITIES", "Toutes les entités");
if (!defined("_ENTITIES_LIST"))
    define("_ENTITIES_LIST", "Liste des entités");
if (!defined("_MANAGE_ENTITIES"))
    define("_MANAGE_ENTITIES", "Gérer les Entités");
if (!defined("_MANAGE_ENTITIES_DESC"))
    define("_MANAGE_ENTITIES_DESC", "Administrer les Entités, ...");
if (!defined("_ENTITY_MISSING"))
    define("_ENTITY_MISSING", "Cette entité n'existe pas");
if (!defined("_ENTITY_TREE"))
    define("_ENTITY_TREE", "Arborescence des entités");
if (!defined("_ENTITY_TREE_DESC"))
    define("_ENTITY_TREE_DESC", "Voir l'arborescence des entités");
if (!defined("_ENTITY_HAVE_CHILD"))
    define("_ENTITY_HAVE_CHILD", "cette entité possède des sous entités");
if (!defined("_ENTITY_IS_RELATED"))
    define("_ENTITY_IS_RELATED", "cette entité est reliée à des utilisateurs");
if (!defined("_TYPE"))
    define("_TYPE", "Type");

/*************************** Users - Entites management *****************/
if (!defined("_ENTITY_USER_DESC"))
    define("_ENTITY_USER_DESC", "Mettre en relation des entités et des utilisateurs");
if (!defined("_ENTITIES_USERS"))
    define("_ENTITIES_USERS", "Relation entités - utilisateurs");
if (!defined("_ENTITIES_USERS_LIST"))
    define("_ENTITIES_USERS_LIST", "Liste des utilisateurs");
if (!defined("_USER_ENTITIES_TITLE"))
    define("_USER_ENTITIES_TITLE", "L'utilisateur appartient aux entités suivantes");
if (!defined("_USER_ENTITIES_ADDITION"))
    define("_USER_ENTITIES_ADDITION", "Relation Utilisateur - Entités");
if (!defined("_USER_BELONGS_NO_ENTITY"))
    define("_USER_BELONGS_NO_ENTITY", "L'utilisateur n'appartient à aucune entité");
if (!defined("_CHOOSE_ONE_ENTITY"))
    define("_CHOOSE_ONE_ENTITY", "Choisissez au moins une entité");
if (!defined("_CHOOSE_ENTITY"))
    define("_CHOOSE_ENTITY", "Choisissez une entité");
if (!defined("_CHOOSE_PRIMARY_ENTITY"))
    define("_CHOOSE_PRIMARY_ENTITY", "Choisir comme entité primaire");
if (!defined("_PRIMARY_ENTITY"))
    define("_PRIMARY_ENTITY", "Entité primaire");
if (!defined("_DELETE_ENTITY"))
    define("_DELETE_ENTITY", "Supprimer le(s) entité(s)");
if (!defined("USER_ADD_ENTITY"))
    define("USER_ADD_ENTITY", "Ajouter une entité");
if (!defined("_ADD_TO_ENTITY"))
    define("_ADD_TO_ENTITY", "Ajouter à une entité");
if (!defined("_NO_ENTITY_SELECTED"))
    define("_NO_ENTITY_SELECTED", "Aucune entité sélectionnée");
if (!defined("_NO_PRIMARY_ENTITY"))
    define("_NO_PRIMARY_ENTITY", "L'entité primaire est obligatoire");
if (!defined("_NO_ENTITIES_DEFINED_FOR_YOU"))
    define("_NO_ENTITIES_DEFINED_FOR_YOU", "Aucune entité définie pour cet utilisateur");
if (!defined("_LABEL_MISSING"))
    define("_LABEL_MISSING", "Il manque le nom de l'entité");
if (!defined("_SHORT_LABEL_MISSING"))
    define("_SHORT_LABEL_MISSING", "Il manque le nom court de l'entité");
if (!defined("_ID_MISSING"))
    define("_ID_MISSING", "Il manque l'indentifiant de l'entité");
if (!defined("_TYPE_MISSING"))
    define("_TYPE_MISSING", "Le type de l'entité est obligatoire");
if (!defined("_PARENT_MISSING"))
    define("_PARENT_MISSING", "L'entité parente est obligatoire");
if (!defined("_ENTITY_UNKNOWN"))
    define("_ENTITY_UNKNOWN", "Entité Inconnue");

/*************************** Entites form *****************/
if (!defined("_ENTITY_LABEL"))
    define("_ENTITY_LABEL", "Nom");
if (!defined("_SHORT_LABEL"))
    define("_SHORT_LABEL", "Nom court");
if (!defined("_ENTITY_ADR_1"))
    define("_ENTITY_ADR_1", "Adresse 1");
if (!defined("_ENTITY_ADR_2"))
    define("_ENTITY_ADR_2", "Adresse 2");
if (!defined("_ENTITY_ADR_3"))
    define("_ENTITY_ADR_3", "Adresse 3");
if (!defined("_ENTITY_ZIPCODE"))
    define("_ENTITY_ZIPCODE", "Code Postal");
if (!defined("_ENTITY_CITY"))
    define("_ENTITY_CITY", "Ville");
if (!defined("_ENTITY_COUNTRY"))
    define("_ENTITY_COUNTRY", "Pays");
if (!defined("_ENTITY_EMAIL"))
    define("_ENTITY_EMAIL", "Email");
if (!defined("_ENTITY_BUSINESS"))
    define("_ENTITY_BUSINESS", "N° SIRET");
if (!defined("_ENTITY_PARENT"))
    define("_ENTITY_PARENT", "Entité parente");
if (!defined("_CHOOSE_ENTITY_PARENT"))
    define("_CHOOSE_ENTITY_PARENT", "Choisissez l'entité parente");
if (!defined("_CHOOSE_FILTER_ENTITY"))
    define("_CHOOSE_FILTER_ENTITY", "Filtrer par entité");
if (!defined("_CHOOSE_ENTITY_TYPE"))
    define("_CHOOSE_ENTITY_TYPE", "Choisissez le type de l'entité");
if (!defined("_ENTITY_TYPE"))
    define("_ENTITY_TYPE", "Type de l'entité");
if (!defined("_TO_USERS_OF_ENTITIES"))
    define("_TO_USERS_OF_ENTITIES", "Vers des utilisateurs des services");
if (!defined("_ALL_ENTITIES"))
    define("_ALL_ENTITIES", "Toutes les entités");
if (!defined("_ENTITIES_JUST_BELOW"))
    define("_ENTITIES_JUST_BELOW", "Immédiatement inférieures à l'entité primaire");
if (!defined("_ALL_ENTITIES_BELOW"))
    define("_ALL_ENTITIES_BELOW", "Inférieures à l'entité primaire");
if (!defined("_ENTITIES_JUST_UP"))
    define("_ENTITIES_JUST_UP", "Immédiatement supérieures  à l'entité primaire");
if (!defined("_ENTITIES_BELOW"))
    define("_ENTITIES_BELOW", "Inférieures à toutes mes entités");
if (!defined("_MY_ENTITIES"))
    define("_MY_ENTITIES", "Toutes les entités de l'utilisateur");
if (!defined("_MY_PRIMARY_ENTITY"))
    define("_MY_PRIMARY_ENTITY", "Entité primaire");
if (!defined("_SAME_LEVEL_ENTITIES"))
    define("_SAME_LEVEL_ENTITIES", "Même niveau de l'entité primaire");
if (!defined("_INDEXING_ENTITIES"))
    define("_INDEXING_ENTITIES", "Indexer pour les services");
if (!defined("_SEARCH_DIFF_LIST"))
    define("_SEARCH_DIFF_LIST", "Rechercher un service ou un utilisateur");
if (!defined("_ADD_CC"))
    define("_ADD_CC", "Ajouter en copie");
if (!defined("_TO_DEST"))
    define("_TO_DEST", "Destinataire");
if (!defined("_NO_DIFF_LIST_ASSOCIATED"))
    define("_NO_DIFF_LIST_ASSOCIATED", "Aucune liste de diffusion");
if (!defined("_PRINCIPAL_RECIPIENT"))
    define("_PRINCIPAL_RECIPIENT", "Destinataire principal");
if (!defined("_ADD_COPY_IN_PROCESS"))
    define("_ADD_COPY_IN_PROCESS", "Ajouter des personnes en copie dans le traitement");
if (!defined("_UPDATE_LIST_DIFF_IN_DETAILS"))
    define("_UPDATE_LIST_DIFF_IN_DETAILS", "Mettre à jour la liste de diffusion depuis la page de détails");
if (!defined("_UPDATE_LIST_DIFF"))
    define("_UPDATE_LIST_DIFF", "Modifier la liste de diffusion");
if (!defined("_DIFF_LIST_COPY"))
    define("_DIFF_LIST_COPY", "Liste de diffusion");
if (!defined("_NO_COPY"))
    define("_NO_COPY", "Pas de copies");
if (!defined("_DIFF_LIST"))
    define("_DIFF_LIST", "Liste de diffusion");
if (!defined("_NO_USER"))
    define("_NO_USER", "Pas d'utilisateur");
if (!defined("_MUST_CHOOSE_DEST"))
    define("_MUST_CHOOSE_DEST", "Vous devez sélectionner au moins un destinataire");
if (!defined("_ENTITIES__DEL"))
    define("_ENTITIES__DEL", "Suppression");
if (!defined("_ENTITY_DELETION"))
    define("_ENTITY_DELETION", "Suppression d'entité");
if (!defined("_THERE_ARE_NOW"))
    define("_THERE_ARE_NOW", "Il y a actuellement");
if (!defined("_DOC_IN_THE_DEPARTMENT"))
    define("_DOC_IN_THE_DEPARTMENT", "document(s) associé(s) à l'entité");
if (!defined("_DEL_AND_REAFFECT"))
    define("_DEL_AND_REAFFECT", "Supprimer et réaffecter");
if (!defined("_THE_ENTITY"))
    define("_THE_ENTITY", "L'entité");
if (!defined("_USERS_LINKED_TO"))
    define("_USERS_LINKED_TO", "utilisateur(s) associé(s) à l'entité");
if (!defined("_ENTITY_MANDATORY_FOR_REDIRECTION"))
    define("_ENTITY_MANDATORY_FOR_REDIRECTION", "Entité obligatoire pour la réaffectation");
if (!defined("_WARNING_MESSAGE_DEL_ENTITY"))
    define("_WARNING_MESSAGE_DEL_ENTITY", "Avertissement :<br> La suppression d'une entité entraine la réaffectation des documents et utilisateurs à une nouvelle entité mais réaffecte également les documents (courriers) en attente de traitement, les modèles de liste de diffusion et les modèles de réponses vers l'entité de remplacement.");

/******************** Keywords Helper ************/
if (!defined("_HELP_KEYWORD1"))
    define("_HELP_KEYWORD1", "toutes les entités rattachées à l'utilisateur connecté. N'inclue pas les sous-entités");
if (!defined("_HELP_KEYWORD2"))
    define("_HELP_KEYWORD2", "entité primaire de l'utilisateur connecté");
if (!defined("_HELP_KEYWORD3"))
    define("_HELP_KEYWORD3", "sous-entités de la liste d'argument, qui peut aussi être @my_entities ou @my_primary_entity");
if (!defined("_HELP_KEYWORD4"))
    define("_HELP_KEYWORD4", "entité parente de l'entité en argument");
if (!defined("_HELP_KEYWORD5"))
    define("_HELP_KEYWORD5", "toutes les entités du même niveau que l'entité en argument");
if (!defined("_HELP_KEYWORD6"))
    define("_HELP_KEYWORD6", "toutes les entités (actives)");
if (!defined("_HELP_KEYWORD7"))
    define("_HELP_KEYWORD7", "sous-entités immédiates (n-1) des entités données en argument");
if (!defined("_HELP_KEYWORD8"))
    define("_HELP_KEYWORD8", "entités ancètres de l'entité donnée en argument jusqu'à la profondeur demandée en second argument (ou la racine si aucun argument 2 fourni)");
if (!defined("_HELP_KEYWORD9"))
    define("_HELP_KEYWORD9", "toutes les entités du type mis en argument");
if (!defined("_HELP_KEYWORDS"))
    define("_HELP_KEYWORDS", "Aide sur les mots clés");
if (!defined("_HELP_KEYWORD_EXEMPLE_TITLE"))
    define("_HELP_KEYWORD_EXEMPLE_TITLE", "Exemple dans la définition de la sécurité d'un groupe (where clause) : accès sur les ressources concernant le service d'appartenance principal de l'utilisateur connecté, ou les sous-services de ce service.");
if (!defined("_HELP_KEYWORD_EXEMPLE"))
    define("_HELP_KEYWORD_EXEMPLE", "where_clause : (DESTINATION = @my_primary_entity or DESTINATION in (@subentities[@my_primary_entity]))");
if (!defined("_HELP_BY_ENTITY"))
    define("_HELP_BY_ENTITY", "Mots clés du module Entités");
if (!defined("_BASKET_REDIRECTIONS_OCCURS_LINKED_TO"))
    define("_BASKET_REDIRECTIONS_OCCURS_LINKED_TO", "occurence(s) de redirection(s) de corbeille(s) associé(s) à l'entité");
if (!defined("_TEMPLATES_LINKED_TO"))
    define("_TEMPLATES_LINKED_TO", "modèle(s) de réponse(s) associé(s) à l'entité");
if (!defined("_LISTISTANCES_OCCURS_LINKED_TO"))
    define("_LISTISTANCES_OCCURS_LINKED_TO", "occurence(s) de courrier(s) à traiter ou en copie(s) associé(s) à l'entité");
if (!defined("_LISTMODELS_OCCURS_LINKED_TO"))
    define("_LISTMODELS_OCCURS_LINKED_TO", "modèle de diffusion associé à l'entité");
if (!defined("_CHOOSE_REPLACEMENT_DEPARTMENT"))
    define("_CHOOSE_REPLACEMENT_DEPARTMENT", "Choisissez un service remplaçant");

/******************** For reports ************/
if (!defined("_ENTITY_VOL_STAT"))
    define("_ENTITY_VOL_STAT", "Volume des courriers par entité");
if (!defined("_ENTITY_VOL_STAT_DESC"))
    define("_ENTITY_VOL_STAT_DESC", "Volume des courriers par entité");
if (!defined("_NO_DATA_MESSAGE"))
    define("_NO_DATA_MESSAGE", "Pas assez de données");
if (!defined("_MAIL_VOL_BY_ENT_REPORT"))
    define("_MAIL_VOL_BY_ENT_REPORT", "Volume de courrier par service");
if (!defined("_WRONG_DATE_FORMAT"))
    define("_WRONG_DATE_FORMAT", "Format de date incorrect");
if (!defined("_ENTITY_PROCESS_DELAY"))
    define("_ENTITY_PROCESS_DELAY", "Délai moyen de traitement par entité");
if (!defined("_ENTITY_LATE_MAIL"))
    define("_ENTITY_LATE_MAIL", "Volume de courrier en retard par entité");

/******************** Action put in copy ************/
if (!defined("_ADD_COPY_FOR_DOC"))
    define("_ADD_COPY_FOR_DOC", "Ajouter en copie pour le document");
if (!defined("_VALIDATE_PUT_IN_COPY"))
    define("_VALIDATE_PUT_IN_COPY", "Valider l'ajout en copie");
if (!defined("_ALL_LIST"))
    define("_ALL_LIST", "Afficher toute la liste");    

 /******************** Listinstance roles ***********/   
if (!defined("_DEST_OR_COPY"))
    define("_DEST_OR_COPY", "Destinataire");       
if (!defined("_SUBMIT"))
    define("_SUBMIT", "Valider"); 
if (!defined("_CANCEL"))
    define("_CANCEL", "Annuler");     
if (!defined("_DIFFLIST_TYPE_ROLES"))
    define("_DIFFLIST_TYPE_ROLES", "Rôles disponibles");
if (!defined("_NO_AVAILABLE_ROLE"))
    define("_NO_AVAILABLE_ROLE", "Aucun rôle disponible");  
  
    
 /******************** Difflist types ***********/       
 if (!defined("_ALL_DIFFLIST_TYPES"))
    define("_ALL_DIFFLIST_TYPES", "Tous les types");
if (!defined("_DIFFLIST_TYPES_DESC"))
    define("_DIFFLIST_TYPES_DESC", "Types listes de diffusion");     
if (!defined("_DIFFLIST_TYPES"))
    define("_DIFFLIST_TYPES", "Types de listes de diffusion");   
if (!defined("_DIFFLIST_TYPE"))
    define("_DIFFLIST_TYPE", "Type(s) de liste");
if (!defined("_ADD_DIFFLIST_TYPE"))
   define("_ADD_DIFFLIST_TYPE", "Ajouter un type");
if (!defined("_DIFFLIST_TYPE_ID"))
   define("_DIFFLIST_TYPE_ID", "Identifiant");   
if (!defined("_DIFFLIST_TYPE_LABEL"))
   define("_DIFFLIST_TYPE_LABEL", "Description");   
if (!defined("_ALLOW_ENTITIES"))
    define("_ALLOW_ENTITIES", "Autoriser les services");     
   
 /******************** Listmodels ***********/   
if (!defined("_ALL_LISTMODELS"))
    define("_ALL_LISTMODELS", "Toutes les listes");
if (!defined("_LISTMODELS_DESC"))
    define("_LISTMODELS_DESC", "Modèles de listes de diffusion des documents et dossiers");     
if (!defined("_LISTMODELS"))
    define("_LISTMODELS", "Modèles de listes de diffusion");   
if (!defined("_LISTMODEL"))
    define("_LISTMODEL", "Modèle(s) de liste");
if (!defined("_ADD_LISTMODEL"))
    define("_ADD_LISTMODEL", "Nouveau modèle");  
if (!defined("_ADMIN_LISTMODEL"))
    define("_ADMIN_LISTMODEL", "Modèle de liste de diffusion"); 
if (!defined("_ADMIN_LISTMODEL_TITLE"))
    define("_ADMIN_LISTMODEL_TITLE", "Identification du modèle de liste:");   
if (!defined("_OBJECT_TYPE"))
    define("_OBJECT_TYPE", "Type de modèle de liste"); 
if (!defined("_SELECT_OBJECT_TYPE"))
    define("_SELECT_OBJECT_TYPE", "Sélectionnez un type..."); 
if (!defined("_SELECT_OBJECT_ID"))
    define("_SELECT_OBJECT_ID", "Sélectionnez un lien...");
if (!defined("_USER_DEFINED_ID"))
    define("_USER_DEFINED_ID", "Libre");    
if (!defined("_ALL_OBJECTS_ARE_LINKED"))
    define("_ALL_OBJECTS_ARE_LINKED", "Toutes les listes sont déjà définies");
if (!defined("_SELECT_OBJECT_TYPE_AND_ID"))
    define("_SELECT_OBJECT_TYPE_AND_ID", "Vous devez spécifier un type de liste et un identifiant!");
if (!defined("_SAVE_LISTMODEL"))
    define("_SAVE_LISTMODEL", "Valider");
if (!defined("_OBJECT_ID_IS_NOT_VALID_ID"))
    define("_OBJECT_ID_IS_NOT_VALID_ID", "Identifiant invalide: il ne doit contenir que des caractères alphabétiques, numériques ou des tiret bas (A-Z, a-z, 0-9, _)");  
if (!defined("_LISTMODEL_ID_ALREADY_USED"))
    define("_LISTMODEL_ID_ALREADY_USED", "Cet identifiant est déjà utilisé!");    
if (!defined("_CONFIRM_LISTMODEL_SAVE"))
    define("_CONFIRM_LISTMODEL_SAVE", "Sauvegarder la liste ?"); 

if (!defined("_ENTER_DESCRIPTION"))
    define("_ENTER_DESCRIPTION", "Description obligatoire");
if (!defined("_ENTER_TITLE"))
    define("_ENTER_TITLE", "Titre obligatoire");


    
if (!defined("_PARAM_AVAILABLE_LISTMODELS_ON_GROUP_BASKETS")) define("_PARAM_AVAILABLE_LISTMODELS_ON_GROUP_BASKETS", "Paramétrer les types de modèle de liste de diffusion pour l'indexation");
if (!defined("_INDEXING_DIFFLIST_TYPES")) define("_INDEXING_DIFFLIST_TYPES", "Types de liste de diffusion");

if (!defined("_ADMIN_DIFFLIST_TYPES")) define("_ADMIN_DIFFLIST_TYPES", "Types de liste de diffusion (Administration)");
if (!defined("_ADMIN_DIFFLIST_TYPES_DESC")) define("_ADMIN_DIFFLIST_TYPES_DESC", "Administrer les différents types de liste de diffusion");
if (!defined("_ADMIN_LISTMODELS")) define("_ADMIN_LISTMODELS", "Modèle de diffusion (Administration)");
if (!defined("_ADMIN_LISTMODELS_DESC")) define("_ADMIN_LISTMODELS_DESC", "Administrer les différents modèles de diffusion");

/******************** RM ENTITIES ************/
if (!defined("_STANDARD"))
    define("_STANDARD", "Standard");
if (!defined("_5_ARCHIVAL"))
    define("_5_ARCHIVAL", "5 Archivistique");
if (!defined("_51_IDENTIFICATION"))
    define("_51_IDENTIFICATION", "5.1 Identification");
if (!defined("_52_DESCRIPTION"))
    define("_52_DESCRIPTION", "5.2 Description");
if (!defined("_53_RELATIONS"))
    define("_53_RELATIONS", "5.3 Relations");
if (!defined("_54_CONTROL"))
    define("_54_CONTROL", "5.4 Contrôle");
    
if (!defined("_VISIBLE"))    
    define("_VISIBLE", "Actif");
if (!defined("_NOT_VISIBLE")) 
    define("_NOT_VISIBLE", "Inactif");
    
/******** NEW WF ************/
if (!defined("_TARGET_STATUS"))
    define("_TARGET_STATUS", "Statut final à la validation de l'étape");
if (!defined("_TARGET_ROLE"))
    define("_TARGET_ROLE", "Rôle à faire avancer dans le workflow");
if (!defined("_ITS_NOT_MY_TURN_IN_THE_WF"))
    define("_ITS_NOT_MY_TURN_IN_THE_WF", "Ce n'est pas mon tour de traiter dans le workflow");
if (!defined("_NO_AVAILABLE_ROLE_FOR_ME_IN_THE_WF"))
    define("_NO_AVAILABLE_ROLE_FOR_ME_IN_THE_WF", "Il n'y a pas de rôle défini pour moi dans le workflow");

if (!defined("_NO_FILTER")) 
    define("_NO_FILTER", "Effacer les filtres");

if (!defined("_AUTO_FILTER")) 
    define("_AUTO_FILTER", "Liste suggérée");

if (!defined("_REDIRECT_NOTE")) 
    define("_REDIRECT_NOTE", "Motif de redirection (optionnel)");

if (!defined("_STORE_DIFF_LIST"))
    define("_STORE_DIFF_LIST", "Enregistrer la liste de diffusion");

if (!defined("_DIFF_LIST_STORED"))
    define("_DIFF_LIST_STORED", "Liste de diffusion enregistrée");

/////////////print_sep
if (!defined("_PRINT_SEP"))
    define("_PRINT_SEP" , "Impression du séparateur");
if (!defined("_PRINT_SEP_TITLE"))
    define("_PRINT_SEP_TITLE" , "Separateur de type");
if (!defined("_COMMENT_FOR_SEP1"))
    define("_COMMENT_FOR_SEP1" , "Placez ce separateur sur la pile de ");
if (!defined("_COMMENT_FOR_SEP2"))
    define("_COMMENT_FOR_SEP2" , "documents du type : ");
if (!defined("_PRINT_SEP_WILL_BE_START"))
    define("_PRINT_SEP_WILL_BE_START", "L'impression des documents va débuter automatiquement.");

//print sep for mlb
if (!defined("_PRINT_SEPS"))
    define("_PRINT_SEPS", "Impression des séparateurs");
if (!defined("_CHOOSE_ENTITIES"))
    define("_CHOOSE_ENTITIES", "Choisissez les services");
if (!defined("_PRINT_SEPS_BUTTON"))
    define("_PRINT_SEPS_BUTTON", "Imprimer les séparateurs");
if (!defined("_ENTITIES_PRINT_SEP_MLB"))
    define("_ENTITIES_PRINT_SEP_MLB", "Imprimer des séparateurs par service");
if (!defined("_ENTITIES_PRINT_SEP_MLB_GENERIC"))
    define("_ENTITIES_PRINT_SEP_MLB_GENERIC", "Imprimer séparateur générique COURRIER");
if (!defined("_SELECT_BARCODE_TYPE"))
    define("_SELECT_BARCODE_TYPE", "Type de code à barres");
if (!defined("_ADD_COPY_IN_INDEXING_VALIDATION"))
    define("_ADD_COPY_IN_INDEXING_VALIDATION", "Interdire la modification du destinataire dans la liste de diffusion");


if (!defined("_DEL_USER_LISTDIFF"))
    define("_DEL_USER_LISTDIFF", "Retirer l'utilisateur de la liste de diffusion");

if (!defined("_DEL_ENTITY_LISTDIFF"))
    define("_DEL_ENTITY_LISTDIFF", "Retirer l'entité de la liste de diffusion");

if (!defined("_ADD_USER_LISTDIFF"))
    define("_ADD_USER_LISTDIFF", "Ajouter l'utilisateur à la liste de diffusion");

if (!defined("_ADD_ENTITY_LISTDIFF"))
    define("_ADD_ENTITY_LISTDIFF", "Ajouter l'entité à la liste de diffusion");
