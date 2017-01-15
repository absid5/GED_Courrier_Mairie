<?php
/*
*    Copyright 2008-2016 Maarch
*
*   This file is part of Maarch Framework.
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
*   along with Maarch Framework.  If not, see <http://www.gnu.org/licenses/>.
*/

/**
* @brief Contains the apps tools class
*
*
* @file
* @author Laurent Giovannoni
* @date $date$
* @version $Revision$
* @ingroup apps
*/

require_once 'core/core_tables.php';

abstract class business_app_tools_Abstract extends Database
{
    public function __construct()
    {
        parent::__construct();
    }
    /**
    * Build Maarch business app configuration into sessions vars with a xml
    * configuration file
    */
    public function build_business_app_config()
    {
        // build Maarch business app configuration into sessions vars
        $_SESSION['showmenu'] = 'oui';

        $core = new core_tools();
        if (file_exists(
            $_SESSION['config']['corepath'] . 'custom' . DIRECTORY_SEPARATOR
            . $_SESSION['custom_override_id'] . DIRECTORY_SEPARATOR . 'apps'
            . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
            . DIRECTORY_SEPARATOR . 'xml' . DIRECTORY_SEPARATOR . 'config.xml'
        )
        ) {
            $path = $_SESSION['config']['corepath'] . 'custom'
                  . DIRECTORY_SEPARATOR . $_SESSION['custom_override_id']
                  . DIRECTORY_SEPARATOR . 'apps' . DIRECTORY_SEPARATOR
                  . $_SESSION['config']['app_id'] . DIRECTORY_SEPARATOR . 'xml'
                  . DIRECTORY_SEPARATOR . 'config.xml';
        } else {
            $path = 'apps' . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
                  . DIRECTORY_SEPARATOR . 'xml' . DIRECTORY_SEPARATOR
                  . 'config.xml';
        }

        if(file_exists($path)){
            $xmlconfig = simplexml_load_file($path);
        }else{
            $xmlconfig = false;
            exit('<i style="color:red;">Fichier de configuration manquant ...</i><br/><br/>Si un custom est utilis&eacute; assurez-vous que l\'url soit correct');
        }

        if ($xmlconfig <> false) {
            $config = $xmlconfig->CONFIG;

            $uriBeginning = strpos($_SERVER['SCRIPT_NAME'], 'apps');
            $url = $_SESSION['config']['coreurl']
                 .substr($_SERVER['SCRIPT_NAME'], $uriBeginning);
            $_SESSION['config']['businessappurl'] = str_replace(
                'index.php', '', $url
            );

            $_SESSION['config']['databaseserver'] =
                (string) $config->databaseserver;
            $_SESSION['config']['databaseserverport'] =
                (string) $config->databaseserverport;
            $_SESSION['config']['databasetype'] =
                (string) $config->databasetype;
            $_SESSION['config']['databasename'] =
                (string) $config->databasename;
            $_SESSION['config']['databaseschema'] =
                (string) $config->databaseschema;
            $_SESSION['config']['databaseuser'] =
                (string) $config->databaseuser;
            $_SESSION['config']['databasepassword'] =
                (string) $config->databasepassword;
            $_SESSION['config']['databasesearchlimit'] =
                (string) $config->databasesearchlimit;
            $_SESSION['config']['nblinetoshow'] =
                (string) $config->nblinetoshow;
            $_SESSION['config']['limitcharsearch'] =
                (string) $config->limitcharsearch;
            $_SESSION['config']['lang'] = (string) $config->lang;
            $_SESSION['config']['adminmail'] = (string) $config->adminmail;
            $_SESSION['config']['adminname'] = (string) $config->adminname;
            $_SESSION['config']['debug'] = (string) $config->debug;
            $_SESSION['config']['applicationname'] = (string) $config->applicationname;
            $_SESSION['config']['defaultPage'] = (string) $config->defaultPage;
            $_SESSION['config']['exportdirectory'] = (string) $config->exportdirectory;
            $_SESSION['config']['cookietime'] = (string) $config->CookieTime;
            $_SESSION['config']['ldap'] = (string) $config->ldap;
            $_SESSION['config']['userdefaultpassword'] = (string) $config->userdefaultpassword;
            $_SESSION['config']['usePDO'] = (string) $config->usePDO;
            $_SESSION['config']['usePHPIDS'] = (string) $config->usePHPIDS;
            if (isset($config->showfooter)) {
                $_SESSION['config']['showfooter'] = (string) $config->showfooter;
            } else {
                $_SESSION['config']['showfooter'] = 'true';
            }
            //$_SESSION['config']['databaseworkspace'] = (string) $config->databaseworkspace;

            $tablename = $xmlconfig->TABLENAME;
            $_SESSION['tablename']['doctypes_first_level'] = (string) $tablename->doctypes_first_level;
            $_SESSION['tablename']['doctypes_second_level'] = (string) $tablename->doctypes_second_level;
            $_SESSION['tablename']['mlb_doctype_ext'] = (string) $tablename->mlb_doctype_ext;
            $_SESSION['tablename']['doctypes_indexes'] = (string) $tablename->doctypes_indexes;
            $_SESSION['tablename']['saved_queries'] = (string) $tablename->saved_queries;
            $_SESSION['tablename']['contacts_v2'] = (string) $tablename->contacts_v2;
            $_SESSION['tablename']['contact_types'] = (string) $tablename->contact_types;
            $_SESSION['tablename']['contact_purposes'] = (string) $tablename->contact_purposes;
            $_SESSION['tablename']['contact_addresses'] = (string) $tablename->contact_addresses;
            $_SESSION['tablename']['tags'] = (string) $tablename->tags;
            
            $_SESSION['config']['tmppath'] = $_SESSION['config']['corepath'] . 'apps' 
                . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
                . DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR;
            
            $i = 0;

            if ( isset($_SESSION['custom_override_id']) && file_exists(
                'custom/' . $_SESSION['custom_override_id'] . '/'
                . $_SESSION['config']['lang'] . '.php'
            )
            ) {
               include_once 'custom/' . $_SESSION['custom_override_id'] . '/'
                . $_SESSION['config']['lang'] . '.php';
            }
            include_once 'apps' . DIRECTORY_SEPARATOR
                    . $_SESSION['config']['app_id'] . DIRECTORY_SEPARATOR
                    . 'lang' . DIRECTORY_SEPARATOR . $_SESSION['config']['lang']
                    . '.php';
            $_SESSION['collections'] = array();
            $_SESSION['coll_categories'] = array();
            foreach ($xmlconfig->COLLECTION as $col) {
                $tmp = (string) $col->label;
                if (!empty($tmp) && defined($tmp) && constant($tmp) <> NULL) {
                    $tmp = constant($tmp);
                }
                $extensions = $col->extensions;
                $collId = (string) $col->id;
                $tab = array();

                if ($extensions->count()) {
                    $extensionTables = $extensions->table;
                    if ($extensionTables->count() > 0) {
                        foreach ($extensions->table as $table) {
                            if (strlen($extensionTables) > 0) {
                                array_push($tab, (string) $table);
                            }
                        }
                    }
                }
                if (isset($col->table) && ! empty($col->table)) {
                    $_SESSION['collections'][$i] = array(
                        'id' => (string) $col->id,
                        'label' => (string) $tmp,
                        'table' => (string) $col->table,
                        'version_table' => (string) $col->version_table,
                        'view' => (string) $col->view,
                        'adr' => (string) $col->adr,
                        'index_file' => (string) $col->index_file,
                        'script_add' => (string) $col->script_add,
                        'script_search' => (string) $col->script_search,
                        'script_search_result' => (string) $col->script_search_result,
                        'script_details' => (string) $col->script_details,
                        'path_to_lucene_index' => (string) $col->path_to_lucene_index,
                        'extensions' => $tab,
                    );
                    
                    $categories = $col->categories;
                    
                    if (count($categories) > 0) {
                        foreach ($categories->category as $cat) {
                            $label = (string) $cat->label;
                            if (!empty($label) && defined($label)
                                && constant($label) <> NULL
                             ) {
                                $label = constant($label);
                            }
                            $_SESSION['coll_categories'][$collId][(string) $cat->id] = $label;
                        }
                        $_SESSION['coll_categories'][$collId]['default_category'] = (string) $categories->default_category;
                    }
                    $i++;
                } else {
                    $_SESSION['collections'][$i] = array(
                        'id' => (string) $col->id,
                        'label' => (string) $tmp,
                        'view' => (string) $col->view,
                        'adr' => (string) $col->adr,
                        'index_file' => (string) $col->index_file,
                        'script_add' => (string) $col->script_add,
                        'script_search' => (string) $col->script_search,
                        'script_search_result' => (string) $col->script_search_result,
                        'script_details' => (string) $col->script_details,
                        'path_to_lucene_index' => (string) $col->path_to_lucene_index,
                        'extensions' => $tab,
                    );
                }
            }
            $history = $xmlconfig->HISTORY;
            $_SESSION['history']['usersdel'] = (string) $history->usersdel;
            $_SESSION['history']['usersban'] = (string) $history->usersban;
            $_SESSION['history']['usersadd'] = (string) $history->usersadd;
            $_SESSION['history']['usersup'] = (string) $history->usersup;
            $_SESSION['history']['usersval'] = (string) $history->usersval;
            $_SESSION['history']['doctypesdel'] = (string) $history->doctypesdel;
            $_SESSION['history']['doctypesadd'] = (string) $history->doctypesadd;
            $_SESSION['history']['doctypesup'] = (string) $history->doctypesup;
            $_SESSION['history']['doctypesval'] = (string) $history->doctypesval;
            $_SESSION['history']['doctypesprop'] = (string) $history->doctypesprop;
            $_SESSION['history']['usergroupsdel'] = (string) $history->usergroupsdel;
            $_SESSION['history']['usergroupsban'] = (string) $history->usergroupsban;
            $_SESSION['history']['usergroupsadd'] = (string) $history->usergroupsadd;
            $_SESSION['history']['usergroupsup'] = (string) $history->usergroupsup;
            $_SESSION['history']['usergroupsval'] = (string) $history->usergroupsval;
            $_SESSION['history']['structuredel'] = (string) $history->structuredel;
            $_SESSION['history']['structureadd'] = (string) $history->structureadd;
            $_SESSION['history']['structureup'] = (string) $history->structureup;
            $_SESSION['history']['subfolderdel'] = (string) $history->subfolderdel;
            $_SESSION['history']['subfolderadd'] = (string) $history->subfolderadd;
            $_SESSION['history']['subfolderup'] = (string) $history->subfolderup;
            $_SESSION['history']['resadd'] = (string) $history->resadd;
            $_SESSION['history']['resup'] = (string) $history->resup;
            $_SESSION['history']['resdel'] = (string) $history->resdel;
            $_SESSION['history']['resview'] = (string) $history->resview;
            $_SESSION['history']['userlogin'] = (string) $history->userlogin;
            $_SESSION['history']['userlogout'] = (string) $history->userlogout;
            $_SESSION['history']['actionadd'] = (string) $history->actionadd;
            $_SESSION['history']['actionup'] = (string) $history->actionup;
            $_SESSION['history']['actiondel'] = (string) $history->actiondel;
            $_SESSION['history']['contactadd'] = (string) $history->contactadd;
            $_SESSION['history']['contactup'] = (string) $history->contactup;
            $_SESSION['history']['contactdel'] = (string) $history->contactdel;
            $_SESSION['history']['statusadd'] = (string) $history->statusadd;
            $_SESSION['history']['statusup'] = (string) $history->statusup;
            $_SESSION['history']['statusdel'] = (string) $history->statusdel;
            $_SESSION['history']['docserversadd'] = (string) $history->docserversadd;
            $_SESSION['history']['docserversdel'] = (string) $history->docserversdel;
            $_SESSION['history']['docserversallow'] = (string) $history->docserversallow;
            $_SESSION['history']['docserversban'] = (string) $history->docserversban;
            //$_SESSION['history']['docserversclose'] = (string) $history->docserversclose;
            $_SESSION['history']['docserverslocationsadd'] = (string) $history->docserverslocationsadd;
            $_SESSION['history']['docserverslocationsdel'] = (string) $history->docserverslocationsdel;
            $_SESSION['history']['docserverslocationsallow'] = (string) $history->docserverslocationsallow;
            $_SESSION['history']['docserverslocationsban'] = (string) $history->docserverslocationsban;
            $_SESSION['history']['docserverstypesadd'] = (string) $history->docserverstypesadd;
            $_SESSION['history']['docserverstypesdel'] = (string) $history->docserverstypesdel;
            $_SESSION['history']['docserverstypesallow'] = (string) $history->docserverstypesallow;
            $_SESSION['history']['docserverstypesban'] = (string) $history->docserverstypesban;
            $_SESSION['history']['contact_types_del'] = (string) $history->contact_types_del;
            $_SESSION['history']['contact_types_add'] = (string) $history->contact_types_add;
            $_SESSION['history']['contact_types_up'] = (string) $history->contact_types_up;
            $_SESSION['history']['contact_purposes_del'] = (string) $history->contact_purposes_del;
            $_SESSION['history']['contact_purposes_add'] = (string) $history->contact_purposes_add;
            $_SESSION['history']['contact_purposes_up'] = (string) $history->contact_purposes_up;
            $_SESSION['history']['contact_addresses_del'] = (string) $history->contact_addresses_del;
            $_SESSION['history']['contact_addresses_add'] = (string) $history->contact_addresses_add;
            $_SESSION['history']['contact_addresses_up'] = (string) $history->contact_addresses_up;
            $_SESSION['history_keywords'] = array();
            foreach ($xmlconfig->KEYWORDS as $keyword) {
                $tmp = (string) $keyword->label;
                if (!empty($tmp) && defined($tmp) && constant($tmp) <> NULL) {
                    $tmp = constant($tmp);
                }

                array_push(
                    $_SESSION['history_keywords'],
                    array(
                        'id'    => (string) $keyword->id,
                        'label' => $tmp,
                    )
                );
            }

            $i = 0;
            foreach ($xmlconfig->MODULES as $modules) {

                $_SESSION['modules'][$i] = array(
                    'moduleid' => (string) $modules->moduleid,
                    //,"comment" => (string) $MODULES->comment
                );
                $i ++;
            }
            $this->_loadActionsPages();
        }

        if ($_SESSION['config']['usePHPIDS'] == 'true') {
            $this->_loadPHPIDSExludes();
        }
    }

    /**
    * Load actions in session
    */
    protected function _loadActionsPages()
    {
        if (isset($_SESSION['config']['corepath'])
            && isset($_SESSION['config']['app_id'])
            && isset($_SESSION['config']['lang']
        )
        ) {
            $core = new core_tools();
            if (file_exists(
                $_SESSION['config']['corepath'] . 'custom' . DIRECTORY_SEPARATOR
                . $_SESSION['custom_override_id'] . DIRECTORY_SEPARATOR . 'core'
                . DIRECTORY_SEPARATOR . 'xml' . DIRECTORY_SEPARATOR
                . 'actions_pages.xml'
            )
            ) {
                $path = $_SESSION['config']['corepath'] . 'custom'
                      . DIRECTORY_SEPARATOR . $_SESSION['custom_override_id']
                      . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR
                      . 'xml' . DIRECTORY_SEPARATOR . 'actions_pages.xml';
            } else {
                $path = 'core' . DIRECTORY_SEPARATOR . 'xml'
                      . DIRECTORY_SEPARATOR . 'actions_pages.xml';
            }
            $xmlfile = simplexml_load_file($path);
            $langPath = 'apps' . DIRECTORY_SEPARATOR
                       . $_SESSION['config']['app_id'] . DIRECTORY_SEPARATOR
                       . 'lang' . DIRECTORY_SEPARATOR
                       . $_SESSION['config']['lang'] . '.php';

            $i = 0;
            foreach ($xmlfile->ACTIONPAGE as $actionPage) {
                $label = (string) $actionPage->LABEL;
                if (!empty($label) && defined($label)
                    && constant($label) <> NULL
                ) {
                    $label = constant($label);
                }
                $keyword = '';
                if (isset($actionPage->KEYWORD)
                    && ! empty($actionPage->KEYWORD)
                ) {
                    $keyword = (string) $actionPage->KEYWORD;
                }
                $createFlag = 'N';
                if (isset($actionPage->FLAG_CREATE)
                    && (string) $actionPage->FLAG_CREATE == 'true'
                ) {
                    $createFlag = 'Y';
                }
                $collections = array();
                $collectionsTag = $actionPage->COLLECTIONS;
                foreach ($collectionsTag->COLL_ID as $collection) {
                    array_push($collections, (string) $collection);
                }
                $_SESSION['actions_pages'][$i] = array(
                    'ID' => (string) $actionPage->ID,
                    'LABEL' => $label,
                    'NAME' => (string) $actionPage->NAME,
                    'ORIGIN' => (string) $actionPage->ORIGIN,
                    'MODULE' => (string) $actionPage->MODULE,
                    'KEYWORD' => $keyword,
                    'FLAG_CREATE' => $createFlag,
                    'COLLECTIONS' => $collections,
                );
                $i++;
            }
        }

        //LOAD actions in other modules
        foreach ($_SESSION['modules'] as $key => $value) {
          
            if (file_exists(
                $_SESSION['config']['corepath'] . 'custom' . DIRECTORY_SEPARATOR
                . $_SESSION['custom_override_id'] . 'modules' . DIRECTORY_SEPARATOR . $value['moduleid']
                . DIRECTORY_SEPARATOR . 'xml' . DIRECTORY_SEPARATOR
                . 'actions_pages.xml'
            )
            ) {
                $path = $_SESSION['config']['corepath'] . 'custom'
                      . DIRECTORY_SEPARATOR . $_SESSION['custom_override_id']
                      . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . $value['moduleid'] . DIRECTORY_SEPARATOR
                      . 'xml' . DIRECTORY_SEPARATOR . 'actions_pages.xml';

            } else {
                $path = 'modules' . DIRECTORY_SEPARATOR . $value['moduleid'] . DIRECTORY_SEPARATOR . 'xml'
                      . DIRECTORY_SEPARATOR . 'actions_pages.xml';
            }

            if (file_exists(
                $_SESSION['config']['corepath'] . 'custom' . DIRECTORY_SEPARATOR
                . $_SESSION['custom_override_id'] . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . $value['moduleid']
                . DIRECTORY_SEPARATOR . 'xml' . DIRECTORY_SEPARATOR
                . 'actions_pages.xml'
            ) || file_exists(
                $_SESSION['config']['corepath'] . 'modules' . DIRECTORY_SEPARATOR . $value['moduleid']
                . DIRECTORY_SEPARATOR . 'xml' . DIRECTORY_SEPARATOR
                . 'actions_pages.xml'
            )

            ) {
            $xmlfile = simplexml_load_file($path);

            $langPath = 'modules' . DIRECTORY_SEPARATOR . $value['moduleid'] . DIRECTORY_SEPARATOR
                      . 'lang' . DIRECTORY_SEPARATOR. $_SESSION['config']['lang'] . '.php';

            include_once($langPath);
            foreach ($xmlfile->ACTIONPAGE as $actionPage) {
                $label = (string) $actionPage->LABEL;
                if (!empty($label) && defined($label)
                    && constant($label) <> NULL
                ) {
                    $label = constant($label);
                }
                $keyword = '';
                if (isset($actionPage->KEYWORD)
                    && ! empty($actionPage->KEYWORD)
                ) {
                    $keyword = (string) $actionPage->KEYWORD;
                }
                $createFlag = 'N';
                if (isset($actionPage->FLAG_CREATE)
                    && (string) $actionPage->FLAG_CREATE == 'true'
                ) {
                    $createFlag = 'Y';
                }
                $collections = array();
                $collectionsTag = $actionPage->COLLECTIONS;
                foreach ($collectionsTag->COLL_ID as $collection) {
                    array_push($collections, (string) $collection);
                }
                $_SESSION['actions_pages'][$i] = array(
                    'ID' => (string) $actionPage->ID,
                    'LABEL' => $label,
                    'NAME' => (string) $actionPage->NAME,
                    'ORIGIN' => (string) $actionPage->ORIGIN,
                    'MODULE' => (string) $actionPage->MODULE,
                    'KEYWORD' => $keyword,
                    'FLAG_CREATE' => $createFlag,
                    'COLLECTIONS' => $collections,
                );
                $i++;

            }
        }

        }
    }

    // Méthode défini dans la classe simple pour cause de problèmes de surcharges custom
    protected function _loadEntrepriseVar()
    {
        $core = new core_tools();
        if (file_exists(
            $_SESSION['config']['corepath'] . 'custom' . DIRECTORY_SEPARATOR
            . $_SESSION['custom_override_id'] . DIRECTORY_SEPARATOR
            . 'apps'.DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
            . DIRECTORY_SEPARATOR . 'xml' . DIRECTORY_SEPARATOR
            . 'entreprise.xml'
        )
        ) {
            $path = $_SESSION['config']['corepath'] . 'custom'
                  . DIRECTORY_SEPARATOR . $_SESSION['custom_override_id']
                  . DIRECTORY_SEPARATOR . 'apps' . DIRECTORY_SEPARATOR
                  . $_SESSION['config']['app_id'] . DIRECTORY_SEPARATOR . 'xml'
                  . DIRECTORY_SEPARATOR . 'entreprise.xml';
        } else {
            $path = 'apps' . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
                  . DIRECTORY_SEPARATOR . 'xml' . DIRECTORY_SEPARATOR
                  . 'entreprise.xml';
        }
        $xmlfile = simplexml_load_file($path);
        $langPath = 'apps' . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
                  . DIRECTORY_SEPARATOR . 'lang' . DIRECTORY_SEPARATOR
                  . $_SESSION['config']['lang'] . '.php';
        
        $_SESSION['mail_natures'] = array();
        $_SESSION['mail_natures_attribute'] = array();
        $mailNatures = $xmlfile->mail_natures;

        if (count($mailNatures) > 0) {
            foreach ($mailNatures->nature as $nature ) {
                $label = (string) $nature->label;
                $attribute = (string) $nature->attributes();
                if (!empty($label) && defined($label)
                    && constant($label) <> NULL
                 ) {
                    $label = constant($label);
                }
                $_SESSION['mail_natures'][(string) $nature->id] = $label;
                $_SESSION['mail_natures_attribute'][(string) $nature->id] = $attribute;
            }
            $_SESSION['default_mail_nature'] = (string) $mailNatures->default_nature;
        }

        $_SESSION['attachment_types'] = array();
        $_SESSION['attachment_types_with_chrono'] = array();
        $_SESSION['attachment_types_show'] = array();
        $attachmentTypes = $xmlfile->attachment_types;
        if (count($attachmentTypes) > 0) {
            foreach ($attachmentTypes->type as $type ) {
                $label = (string) $type->label;
                $with_chrono = (string) $type['with_chrono'];
                $get_chrono = (string) $type['get_chrono'];
                $attach_in_mail = (string) $type['attach_in_mail'];
                $show_attachment_type = (string) $type['show'];
                if (!empty($label) && defined($label)
                    && constant($label) <> NULL
                 ) {
                    $label = constant($label);
                }
                $array_get_chrono = explode(',', $get_chrono);
                $_SESSION['attachment_types'][(string) $type->id] = $label;
                $_SESSION['attachment_types_with_chrono'][(string) $type->id] = $with_chrono;
                $_SESSION['attachment_types_show'][(string) $type->id] = $show_attachment_type;
                $_SESSION['attachment_types_get_chrono'][(string) $type->id] = $array_get_chrono;
                $_SESSION['attachment_types_attach_in_mail'][(string) $type->id] = $attach_in_mail;
            }
        }
        var_dump($_SESSION['attachment_types_show']);
        $_SESSION['mail_priorities'] = array();
        $_SESSION['mail_priorities_attribute'] = array();
        $_SESSION['mail_priorities_wdays'] = array();
        $mailPriorities = $xmlfile->priorities;
        if (count($mailPriorities) > 0) {
            $i = 0;
            foreach ($mailPriorities->priority as $priority ) {
                $label = (string) $priority;
                $attribute = (string) $priority['with_delay'];
                $workingDays = (string) $priority['working_days'];
                if (!empty($label) && defined($label)
                    && constant($label) <> NULL
                ) {
                    $label = constant($label);
                }
                $_SESSION['mail_priorities'][$i] = $label;
                $_SESSION['mail_priorities_attribute'][$i] = $attribute;
                $_SESSION['mail_priorities_wdays'][$i] = ($workingDays != 'false' ? 'true' : 'false');
                $i++;
            }
            $_SESSION['default_mail_priority'] = (string) $mailPriorities->default_priority;
        }

        $_SESSION['type_calendar'] = array();
        var_dump($xmlfile);
        $type_calendar = $xmlfile->type_calendar;
        $_SESSION['type_calendar'] = $type_calendar;


        $contact_check = $xmlfile->contact_check;
        if (count($contact_check) > 0) {
            $_SESSION['check_days_before'] = (string) $contact_check->check_days_before;
        }

        $_SESSION['mail_titles'] = array();
        $mailTitles = $xmlfile->titles;
        if (count($mailTitles) > 0) {
            $i = 0;
            foreach ($mailTitles->title as $title ) {
                $label = (string) $title->label;
                if (!empty($label) && defined($label)
                    && constant($label) <> NULL
                ) {
                    $label = constant($label);
                }
                $_SESSION['mail_titles'][(string)$title->id] = $label;
            }
            $_SESSION['default_mail_title'] = (string) $mailTitles->default_title;
        }
        
    }

    public function compare_base_version($xmlVersionBase)
    {
        // Compare version value beetwen version base xml file and version base
        // value in the database
        if (file_exists(
            $_SESSION['config']['corepath'] . 'custom' . DIRECTORY_SEPARATOR
            . $_SESSION['custom_override_id'] . DIRECTORY_SEPARATOR
            . $xmlVersionBase
        )
        ) {
            $path = $_SESSION['config']['corepath'] . 'custom'
                  . DIRECTORY_SEPARATOR . $_SESSION['custom_override_id']
                  . DIRECTORY_SEPARATOR . $xmlVersionBase;
        } else {
            $path = $xmlVersionBase;
        }
        $xmlBase = simplexml_load_file($path);
        //Find value in the xml database_version tag
        if ($xmlBase) {
            $_SESSION['maarch_entreprise']
                ['xml_versionbase'] = (string) $xmlBase->database_version;
        } else {
            $_SESSION['maarch_entreprise']['xml_versionbase'] = 'none';
        }
        $checkBase = new Database();
        $query = "SELECT param_value_int FROM " . PARAM_TABLE
               . " WHERE id = 'database_version'";

        $stmt = $checkBase->query($query); //Find value in parameters table on database
        if ($stmt->rowCount() == 0) {
            $_SESSION['maarch_entreprise']['database_version'] = "none";
        } else {
            $vbg = $stmt->fetchObject();
            $_SESSION['maarch_entreprise']
                ['database_version'] = $vbg->param_value_int;
        }
        //If this two parameters is not find, this is the end of this function
        if ($_SESSION['maarch_entreprise']['xml_versionbase'] <> 'none' ) {
            if (($_SESSION['maarch_entreprise']['xml_versionbase'] <> $_SESSION['maarch_entreprise']['database_version'])
                || ($_SESSION['maarch_entreprise']['database_version'] == 'none')
            ) {
                $_SESSION['error'] .= _VERSION_BASE_AND_XML_BASEVERSION_NOT_MATCH;
            }
        }
    }

    public function load_features($xmlFeatures)
    {
        $_SESSION['features'] = array();
        //Defines all features by  default at 'false'
        $_SESSION['features']['search_notes'] = "false";
        $_SESSION['features']['dest_to_copy_during_redirection'] = "false";
        $_SESSION['features']['show_types_tree'] = "false";
        $_SESSION['features']['watermark'] = array();
        $_SESSION['features']['watermark']['enabled'] = "false";
        if (file_exists(
            $_SESSION['config']['corepath'] . 'custom' . DIRECTORY_SEPARATOR
            . $_SESSION['custom_override_id'] . DIRECTORY_SEPARATOR
            . $xmlFeatures
        )
        ) {
            $path = $_SESSION['config']['corepath'] . 'custom'
                  . DIRECTORY_SEPARATOR . $_SESSION['custom_override_id']
                  . DIRECTORY_SEPARATOR . $xmlFeatures;
        } else {
            $path = $xmlFeatures;
        }

        $xmlfeatures = simplexml_load_file($path);
        if ($xmlfeatures) {
            $feats = $xmlfeatures->FEATURES;
            $_SESSION['features']['search_notes'] = (string) $feats->search_notes;
            $_SESSION['features']['dest_to_copy_during_redirection'] = (string) $feats->dest_to_copy_during_redirection;
            $_SESSION['features']['show_types_tree'] = (string) $feats->show_types_tree;
            $watermark = $feats->watermark;
            $_SESSION['features']['watermark']['enabled'] = (string) $watermark->enabled;
            $_SESSION['features']['watermark']['text'] = (string) $watermark->text;
            $_SESSION['features']['watermark']['position'] = (string) $watermark->position;
            $_SESSION['features']['watermark']['font'] = (string) $watermark->font;
            $_SESSION['features']['watermark']['text_color'] = (string) $watermark->text_color;
            $_SESSION['features']['type_calendar'] = (string) $feats->type_calendar;
            $send_to_contact_with_mandatory_attachment = (string) $feats->send_to_contact_with_mandatory_attachment;
            if(strtoupper($send_to_contact_with_mandatory_attachment) == 'TRUE'){
                //var_dump($send_to_contact_with_mandatory_attachment);
                $_SESSION['features']['send_to_contact_with_mandatory_attachment'] = TRUE;
                //var_dump($_SESSION['features']['send_to_contact_with_mandatory_attachment']);
                //exit;
            }elseif(strtoupper($send_to_contact_with_mandatory_attachment) == 'FALSE'){
                //var_dump($send_to_contact_with_mandatory_attachment);
                $_SESSION['features']['send_to_contact_with_mandatory_attachment'] = FALSE;
                //var_dump($_SESSION['features']['send_to_contact_with_mandatory_attachment']);
                //exit;
            }
        }
    }

    /**
    * Loads current folder identifier in session
    *
    */
    protected function _loadCurrentFolder($userId)
    {
        if (isset($userId)) {
            $db = new Database();
            $stmt = $db->query(
                "SELECT custom_t1 FROM " . USERS_TABLE . " WHERE user_id = ?",
                array($userId)
            );
            $res = $stmt->fetchObject();

            $_SESSION['current_folder_id'] = $res->custom_t1;
        }
    }

    /**
    * Loads app specific vars in session
    *
    */
    public function load_app_var_session($userData = '')
    {
        if (is_array($userData)) {
            $this->_loadCurrentFolder($userData['UserId']);
        }
        $this->_loadEntrepriseVar();
        $this->load_features(
            'apps' . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
            . DIRECTORY_SEPARATOR . 'xml' . DIRECTORY_SEPARATOR . 'features.xml'
        );
        
        $this->_loadListsConfig();
         
        if (file_exists(
            $_SESSION['config']['corepath'] . 'custom' . DIRECTORY_SEPARATOR
            . $_SESSION['custom_override_id'] . DIRECTORY_SEPARATOR . 'apps'
            . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
            . DIRECTORY_SEPARATOR . 'xml' . DIRECTORY_SEPARATOR
            . 'docservers_features.xml'
        )
        ) {
            $path = $_SESSION['config']['corepath'] . 'custom'
                  . DIRECTORY_SEPARATOR . $_SESSION['custom_override_id']
                  . DIRECTORY_SEPARATOR . 'apps' . DIRECTORY_SEPARATOR
                  . $_SESSION['config']['app_id'] . DIRECTORY_SEPARATOR . 'xml'
                  . DIRECTORY_SEPARATOR . 'docservers_features.xml';
        } else {
            $path = 'apps' . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
                  . DIRECTORY_SEPARATOR . 'xml' . DIRECTORY_SEPARATOR
                  . 'docservers_features.xml';
        }
        $_SESSION['docserversFeatures'] = array();
        $_SESSION['docserversFeatures'] = functions::object2array(
            simplexml_load_file($path)
        );
    }

    /**
    * Return a specific path or false
    *
    */
    public function insert_app_page($name)
    {
        if (! isset($name) || empty($name)) {
            return false;
        }
        if ($name == 'structures' || $name == 'structures_list_by_name'
            || $name == 'structure_up' || $name == 'structure_del'
        ) {
            $path = 'apps' . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
                  . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR
                  . 'architecture' . DIRECTORY_SEPARATOR . 'structures'
                  . DIRECTORY_SEPARATOR . $name . '.php';
            return $path;
        } else if ($name == 'subfolders' || $name == 'subfolders_list_by_name'
            || $name == 'subfolder_up' || $name == 'subfolder_del'
        ) {
            $path = 'apps' . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
                  . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR
                  . 'architecture' . DIRECTORY_SEPARATOR . 'subfolders'
                  . DIRECTORY_SEPARATOR . $name . '.php';
            return $path;
        } else if ($name == 'types' || $name == 'types_up'
            || $name == 'types_up_db' || $name == 'types_add'
            || $name == 'types_del' || $name == 'get_index'
            || $name == 'choose_index' || $name == 'choose_coll'
            || $name == 'types_list_by_name'
        ) {
            $path = 'apps' . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
                  . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR
                  . 'architecture' . DIRECTORY_SEPARATOR . 'types'
                  . DIRECTORY_SEPARATOR . $name . '.php';

            return $path;
        } else if ($name == 'contact_types' || $name == 'contact_types_list_by_name'
            || $name == 'contact_types_up' || $name == 'contact_types_del'
        ) {
            $path = 'apps' . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
                  . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR
                  . 'contacts' . DIRECTORY_SEPARATOR . 'contact_types'
                  . DIRECTORY_SEPARATOR . $name . '.php';
            return $path;
        } else if ($name == 'contact_purposes' || $name == 'contact_purposes_list_by_name'
            || $name == 'contact_purposes_up' || $name == 'contact_purposes_del'
        ) {
            $path = 'apps' . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
                  . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR
                  . 'contacts' . DIRECTORY_SEPARATOR . 'contact_purposes'
                  . DIRECTORY_SEPARATOR . $name . '.php';
            return $path;
        } else if ($name == 'contacts_v2' || $name == 'contacts_v2_list_by_name'
            || $name == 'contacts_v2_up' || $name == 'contacts_v2_del' || $name == 'contacts_v2_add'
            || $name == 'contacts_v2_up_db' || $name == 'contacts_v2_confirm' || $name == 'contacts_v2_status'
        ) {
            $path = 'apps' . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
                  . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR
                  . 'contacts' . DIRECTORY_SEPARATOR . 'contacts_v2'
                  . DIRECTORY_SEPARATOR . $name . '.php';
            return $path;
        } else if ($name == 'contact_addresses' || $name == 'contact_addresses_list_by_name'
            || $name == 'contact_addresses_up' || $name == 'contact_addresses_del' || $name == 'contact_addresses_add'
            || $name == 'contact_addresses_up_db' || $name == 'contact_addresses_list' || $name == 'contact_addresses_status'
        ) {
            $path = 'apps' . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
                  . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR
                  . 'contacts' . DIRECTORY_SEPARATOR . 'contact_addresses'
                  . DIRECTORY_SEPARATOR . $name . '.php';
            return $path;
        } else if ($name == 'view_tree_contacts' || $name == 'show_tree_contacts' || $name == 'get_tree_children_contact') {
            $path = 'apps' . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
                  . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR
                  . 'contacts' . DIRECTORY_SEPARATOR . 'contact_tree'
                  . DIRECTORY_SEPARATOR . $name . '.php';
            return $path;
        } else {
            return false;
        }
    }

    public function get_titles()
    {
        $core = new core_tools();
        if (file_exists(
            $_SESSION['config']['corepath'] . 'custom' . DIRECTORY_SEPARATOR
            . $_SESSION['custom_override_id'] . DIRECTORY_SEPARATOR . 'apps'
            . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
            . DIRECTORY_SEPARATOR . 'xml' . DIRECTORY_SEPARATOR
            . 'entreprise.xml'
        )
        ) {
            $path = $_SESSION['config']['corepath'] . 'custom'
                  . DIRECTORY_SEPARATOR . $_SESSION['custom_override_id']
                  . DIRECTORY_SEPARATOR . 'apps' . DIRECTORY_SEPARATOR
                  . $_SESSION['config']['app_id'] . DIRECTORY_SEPARATOR . 'xml'
                  . DIRECTORY_SEPARATOR . 'entreprise.xml';
        } else {
            $path = 'apps' . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
                  . DIRECTORY_SEPARATOR . 'xml' . DIRECTORY_SEPARATOR
                  . 'entreprise.xml';
        }
        $xmlfile = simplexml_load_file($path);
        $langPath = 'apps' . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
                  . DIRECTORY_SEPARATOR . 'lang' . DIRECTORY_SEPARATOR
                  . $_SESSION['config']['lang'] . '.php';

        $resTitles = array();
        $titles = $xmlfile->titles;
        foreach ($titles->title as $title ) {
            $label = (string) $title->label;
            if (!empty($label) && defined($label)
                && constant($label) <> NULL
            ) {
                $label = constant($label);
            }

            $resTitles[(string) $title->id] = $label;
        }

        asort($resTitles, SORT_LOCALE_STRING);
        $defaultTitle = (string) $titles->default_title;
        return array('titles' => $resTitles, 'default_title' => $defaultTitle);
    }


    public function get_label_title($titleId)
    {
        $core = new core_tools();
        if (file_exists(
            $_SESSION['config']['corepath'] . 'custom' . DIRECTORY_SEPARATOR
            . $_SESSION['custom_override_id'] . DIRECTORY_SEPARATOR . 'apps'
            . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
            . DIRECTORY_SEPARATOR . 'xml' . DIRECTORY_SEPARATOR
            . 'entreprise.xml'
        )
        ) {
            $path = $_SESSION['config']['corepath'] . 'custom'
                  . DIRECTORY_SEPARATOR . $_SESSION['custom_override_id']
                  . DIRECTORY_SEPARATOR . 'apps' . DIRECTORY_SEPARATOR
                  . $_SESSION['config']['app_id'] . DIRECTORY_SEPARATOR .'xml'
                  . DIRECTORY_SEPARATOR . 'entreprise.xml';
        } else {
            $path = 'apps' . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
            . DIRECTORY_SEPARATOR . 'xml' . DIRECTORY_SEPARATOR
            . 'entreprise.xml';
        }
        $xmlfile = simplexml_load_file($path);
        $langPath = 'apps' . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
                  . DIRECTORY_SEPARATOR . 'lang' . DIRECTORY_SEPARATOR
                  . $_SESSION['config']['lang'] . '.php';
        $titles = $xmlfile->titles;
        foreach ($titles->title as $title ) {
            if ($titleId == (string) $title->id) {
                $label = (string) $title->label;
                if (!empty($label) && defined($label)
                    && constant($label) <> NULL
                ) {
                    $label = constant($label);
                }

                return $label;
            }
        }
        return '';
    }
    
    protected function _loadListsConfig() {

        if (file_exists(
            $_SESSION['config']['corepath'] . 'custom' . DIRECTORY_SEPARATOR
            . $_SESSION['custom_override_id'] . DIRECTORY_SEPARATOR . 'apps'
            . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
            . DIRECTORY_SEPARATOR . 'xml' . DIRECTORY_SEPARATOR
            . 'lists_parameters.xml'
        )
        ) {
            $path = $_SESSION['config']['corepath'] . 'custom'
                  . DIRECTORY_SEPARATOR . $_SESSION['custom_override_id']
                  . DIRECTORY_SEPARATOR . 'apps' . DIRECTORY_SEPARATOR
                  . $_SESSION['config']['app_id'] . DIRECTORY_SEPARATOR .'xml'
                  . DIRECTORY_SEPARATOR . 'lists_parameters.xml';
        } else {
            $path = 'apps' . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
            . DIRECTORY_SEPARATOR . 'xml' . DIRECTORY_SEPARATOR
            . 'lists_parameters.xml';
        }
        $xmlfile = simplexml_load_file($path);
        
        //Load filters
        $_SESSION['filters'] = array();
        foreach ($xmlfile->FILTERS as $filtersObject) {
        
            foreach ($filtersObject as $filter) {
            
                $desc = (string) $filter->LABEL;
                if (!empty($desc) && defined($desc) && constant($desc) <> NULL) {
                    $desc = constant($desc);
                }
                $id = (string) $filter->ID;
                $enabled = (string) $filter->ENABLED;
                if( trim($enabled) == 'true') {
                    $_SESSION['filters'][$id] = array(
                        'ID'                => $id,
                        'LABEL'             => $desc,
                        'ENABLED'           => $enabled,
                        'VALUE'             => '',
                        'CLAUSE'            => ''
                    );
                }
            }
        }
        
        //Init
        $_SESSION['html_templates'] = array();
        
        //Default list (no template)
        $_SESSION['html_templates']['none'] = array(
            'ID'        =>  'none',
            'LABEL'     =>  _DOCUMENTS_LIST,
            'IMG'       =>  'fa fa-list-alt fa-2x',
            'ENABLED'   =>  'true',
            'PATH'      =>  '',
            'GOTOLIST'  =>  ''
        );
        
        //Load templates
        foreach ($xmlfile->TEMPLATES as $templatesObject) {
        
            foreach ($templatesObject as $template) {
            
                $desc = (string) $template->LABEL;
                if (!empty($desc) && defined($desc) && constant($desc) <> NULL) {
                    $desc = constant($desc);
                }
                $id = (string) $template->ID;
                $enabled = (string) $template->ENABLED;
                $name = (string) $template->NAME;
                $origin = (string) $template->ORIGIN;
                $module = (string) $template->MODULE;
                $listObject = $template->GOTOLIST;

                $pathToList = '';
                if (!empty($listObject)) {
                    foreach ($listObject as $list) {
                        $listId = (string) $list->ID;
                        $listName = (string) $list->NAME;
                        $listOrigin = (string) $list->ORIGIN;
                        $listModule = (string) $list->MODULE;
                        
                        // The page is in the apps
                        if (strtoupper($listOrigin) == 'APPS'
                        ) {
                            if( file_exists(
                                $_SESSION['config']['corepath'].'custom' . DIRECTORY_SEPARATOR 
                                . $_SESSION['custom_override_id'] . DIRECTORY_SEPARATOR . 'apps' 
                                . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id'] 
                                . DIRECTORY_SEPARATOR . $listName . '.php'
                            ) ||
                                file_exists('apps' . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
                                . DIRECTORY_SEPARATOR . $listName.'.php'
                            )
                            )
                            {
                                $pathToList = $_SESSION['config']['businessappurl']
                                            . 'index.php?display=true&page='. $listName;
                            }
                        } else if (strtoupper(
                            $listOrigin
                        ) == "MODULE"
                        ) { 
                            // The page is in a module
                            $core = new core_tools();
                            // Error : The module name is empty or the module is not loaded
                            if (empty($listModule)
                                || ! $core->is_module_loaded(
                                    $listModule
                                )
                            ) {
                                $pathToList = '';
                            } else {
                                if (
                                    file_exists(
                                    $_SESSION['config']['corepath'] . 'custom' . DIRECTORY_SEPARATOR
                                    . $_SESSION['custom_override_id'] . DIRECTORY_SEPARATOR . 'modules'
                                    . DIRECTORY_SEPARATOR . $listModule . DIRECTORY_SEPARATOR . $listName . '.php'
                                ) ||
                                    file_exists('modules' . DIRECTORY_SEPARATOR . $listModule
                                        . DIRECTORY_SEPARATOR . $listName . '.php'
                                )
                                ) {
                                    $pathToList = $_SESSION['config']['businessappurl']
                                        . 'index.php?display=true&page=' . $listName
                                        . '&module=' . $listModule;
                                }
                            }
                        }
                    }
                }
                
                //Path to template
                if ($origin == "apps") { //Origin apps
                    if(file_exists(
                        $_SESSION['config']['corepath'].'custom' . DIRECTORY_SEPARATOR 
                        . $_SESSION['custom_override_id'] . DIRECTORY_SEPARATOR . 'apps' 
                        . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id'] 
                        . DIRECTORY_SEPARATOR . "template" . DIRECTORY_SEPARATOR 
                        . $name . '.html'
                    )
                    ) {
                        $path = $_SESSION['config']['corepath'] . 'custom' 
                        . DIRECTORY_SEPARATOR . $_SESSION['custom_override_id']
                        . DIRECTORY_SEPARATOR . 'apps' . DIRECTORY_SEPARATOR
                        . $_SESSION['config']['app_id'] . DIRECTORY_SEPARATOR
                        . "template" . DIRECTORY_SEPARATOR . $name . '.html';
                    } else {
                        $path = 'apps' . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
                        . DIRECTORY_SEPARATOR . "template" . DIRECTORY_SEPARATOR . $name.'.html';
                    }
                } else if ($origin == "module") { //Origin module
                    if (file_exists(
                        $_SESSION['config']['corepath'] . 'custom' . DIRECTORY_SEPARATOR
                        . $_SESSION['custom_override_id'] . DIRECTORY_SEPARATOR . 'modules'
                        . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR . 'template'
                        . DIRECTORY_SEPARATOR .  $name . '.html'
                    )
                    ) {
                        $path = $_SESSION['config']['corepath'] . 'custom'
                        . DIRECTORY_SEPARATOR . $_SESSION['custom_override_id']
                        . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR
                        . $module . DIRECTORY_SEPARATOR . 'template' . DIRECTORY_SEPARATOR
                        .  $name . '.html';
                    } else {
                        $path = 'modules' . DIRECTORY_SEPARATOR . $module
                        . DIRECTORY_SEPARATOR . 'template' . DIRECTORY_SEPARATOR
                        .  $name . '.html';
                    }
                }
                
                //Values of html_templates array
                if( trim($enabled) == 'true') {
                    $_SESSION['html_templates'][$id] = array(
                        'ID'                => $id,
                        'LABEL'             => $desc,
                        'IMG'               => (string) $template->IMG,
                        'ENABLED'           => $enabled,
                        'PATH'              => $path,
                        'GOTOLIST'          => $pathToList
                    );
                }
            }
        }
    }

    /**
    * Load phpids excludes in session
    */
    protected function _loadPHPIDSExludes()
    {
        if (isset($_SESSION['config']['corepath'])
            && isset($_SESSION['config']['app_id'])
        ) {
            $core = new core_tools();
            if (file_exists(
                $_SESSION['config']['corepath'] . 'custom' . DIRECTORY_SEPARATOR
                . $_SESSION['custom_override_id'] . DIRECTORY_SEPARATOR . 'apps'
                . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id'] . DIRECTORY_SEPARATOR
                . 'tools' . DIRECTORY_SEPARATOR . 'phpids' . DIRECTORY_SEPARATOR
                . 'lib' . DIRECTORY_SEPARATOR . 'IDS' . DIRECTORY_SEPARATOR
                . 'maarch_exclude.xml'
            )
            ) {
                $path = $_SESSION['config']['corepath'] . 'custom' . DIRECTORY_SEPARATOR
                        . $_SESSION['custom_override_id'] . DIRECTORY_SEPARATOR . 'apps'
                        . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id'] . DIRECTORY_SEPARATOR
                        . 'tools' . DIRECTORY_SEPARATOR . 'phpids' . DIRECTORY_SEPARATOR
                        . 'lib' . DIRECTORY_SEPARATOR . 'IDS' . DIRECTORY_SEPARATOR
                        . 'maarch_exclude.xml';
            } else {
                $path = 'apps'
                        . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id'] . DIRECTORY_SEPARATOR
                        . 'tools' . DIRECTORY_SEPARATOR . 'phpids' . DIRECTORY_SEPARATOR
                        . 'lib' . DIRECTORY_SEPARATOR . 'IDS' . DIRECTORY_SEPARATOR
                        . 'maarch_exclude.xml';
            }
            $xmlfile = simplexml_load_file($path);
            $_SESSION['PHPIDS_EXCLUDES'] = array();
            foreach ($xmlfile->exclude as $exclude) {
                array_push(
                    $_SESSION['PHPIDS_EXCLUDES'], 
                    array(
                        'TARGET' => (string) $exclude->target,
                        'PAGE'   => (string) $exclude->page,
                    )
                );
            }
        }
    }
}
