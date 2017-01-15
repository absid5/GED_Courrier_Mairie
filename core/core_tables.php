<?php

/*
*    Copyright 2008 - 2011 Maarch
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

/**
* @brief Core tables declarations
*
*
* @file
* @author  Claire Figueras  <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup core
*/
if (! defined('ACTIONS_TABLE')) {
    define('ACTIONS_TABLE', 'actions');
}
if (! defined('ACTIONS_CATEGORIES_TABLE_NAME')) {
    define('ACTIONS_CATEGORIES_TABLE_NAME', 'actions_categories');
}
if (! defined('AUTHORS_TABLE')) {
    define('AUTHORS_TABLE', 'authors');
}
if (! defined('_DOCSERVERS_TABLE_NAME')) {
    define('_DOCSERVERS_TABLE_NAME', 'docservers');
}
if (!defined('_DOCSERVER_TYPES_TABLE_NAME')) {
    define('_DOCSERVER_TYPES_TABLE_NAME', 'docserver_types');
}
if (! defined('_DOCSERVER_LOCATIONS_TABLE_NAME')) {
    define('_DOCSERVER_LOCATIONS_TABLE_NAME', 'docserver_locations');
}
if (! defined('EXT_DOCSERVER_TABLE')) {
    define('EXT_DOCSERVER_TABLE', 'ext_docserver');
}
if (! defined('_LC_CYCLE_STEPS_TABLE_NAME')) {
    define('_LC_CYCLE_STEPS_TABLE_NAME', 'lc_cycle_steps');
}
if (! defined('_ADR_X_TABLE_NAME')) {
    define('_ADR_X_TABLE_NAME', 'adr_x');
}
if (! defined('DOCTYPES_TABLE')) {
    define('DOCTYPES_TABLE', 'doctypes');
}
if (! defined('DOCTYPES_INDEXES_TABLE')) {
    define('DOCTYPES_INDEXES_TABLE', 'doctypes_indexes');
}
if (! defined('FULLTEXT_TABLE')) {
    define('FULLTEXT_TABLE', 'fulltext');
}
if (! defined('GROUPSECURITY_TABLE')) {
    define('GROUPSECURITY_TABLE', 'groupsecurity');
}
if (! defined('HISTORY_TABLE')) {
    define('HISTORY_TABLE', 'history');
}
if (! defined('HISTORY_BATCH_TABLE')) {
    define('HISTORY_BATCH_TABLE', 'history_batch');
}
if (! defined('PARAM_TABLE')) {
    define('PARAM_TABLE', 'parameters');
}
if (! defined('SAVED_QUERIES')) {
    define('SAVED_QUERIES', 'saved_queries');
}
if (! defined('RESGROUP_CONTENT_TABLE')) {
    define('RESGROUP_CONTENT_TABLE', 'resgroup_content');
}
if (! defined('RESGROUPS_TABLE')) {
    define('RESGROUPS_TABLE', 'resgroups');
}
if (! defined('SECURITY_TABLE')) {
    define('SECURITY_TABLE', 'security');
}
if (! defined('SESSION_SECURITY_TABLE')) {
    define('SESSION_SECURITY_TABLE', 'session_security');
}
if (! defined('STATUS_TABLE')) {
    define('STATUS_TABLE', 'status');
}
if (! defined('USERGROUPS_TABLE')) {
    define('USERGROUPS_TABLE', 'usergroups');
}
if (! defined('USERGROUP_CONTENT_TABLE')) {
    define('USERGROUP_CONTENT_TABLE', 'usergroup_content');
}
if (! defined('USERGROUPS_SERVICES_TABLE')) {
    define('USERGROUPS_SERVICES_TABLE', 'usergroups_services');
}
if(! defined('LISTMODELS_CONTENT_TABLE')) {
    define('LISTMODELS_CONTENT_TABLE', 'listmodels');
}
if (! defined('USERS_TABLE')) {
    define('USERS_TABLE', 'users');
}
if (! defined('EMAIL_SIGNATURES_TABLE')) {
    define('EMAIL_SIGNATURES_TABLE', 'users_email_signatures');
}
if (! defined('USER_BASKETS_SECONDARY_TABLE')) {
    define('USER_BASKETS_SECONDARY_TABLE', 'user_baskets_secondary');
}
