<?php
/*
*   Copyright 2008-2015 Maarch
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

/****************************************************************************/
/*                                                                          */
/*                                                                          */
/*               THIS PAGE CAN NOT BE OVERWRITTEN IN A CUSTOM               */
/*                                                                          */
/*                                                                          */
/* **************************************************************************/


/**
* @defgroup core Framework core
*/

/**
* @brief   Contains all the functions to load core and modules
*
* @file
* @author  Laurent Giovannoni  <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup core
*/

/**
* @brief   Contains all the functions to load core and modules
*
* <ul>
* <li>Loads core tables into session</li>
* <li>Loads modules settings into session</li>
* <li>Builds the application menu</li>
*  <li>Management and building the framework</li>
*  <li>Modules services loading</li>
*  <li>Execution of the module services </li>
*</ul>
*
* @ingroup core
*/
class core_tools extends functions
{
    /**
    * Load Maarch core configuration into sessions vars from the core/xml/config.xml file
    */
    public function build_core_config($pathtoxmlcore)
    {
        $xmlconfig = simplexml_load_file($pathtoxmlcore);

        // Loads  core tables into session ($_SESSION['tablename'] array)
        $TABLENAME = $xmlconfig->TABLENAME ;
        $_SESSION['tablename']['actions'] = (string) $TABLENAME->actions;
        $_SESSION['tablename']['authors'] = (string) $TABLENAME->authors;
        $_SESSION['tablename']['docservers'] = (string) $TABLENAME->docservers;
        $_SESSION['tablename']['doctypes'] = (string) $TABLENAME->doctypes;
        $_SESSION['tablename']['ext_docserver'] = (string) $TABLENAME->extdocserver;
        $_SESSION['tablename']['fulltext'] = (string) $TABLENAME->fulltext;
        $_SESSION['tablename']['groupsecurity'] = (string) $TABLENAME->groupsecurity;
        $_SESSION['tablename']['history'] = (string) $TABLENAME->history;
        $_SESSION['tablename']['history_batch'] = (string) $TABLENAME->history_batch;
        $_SESSION['tablename']['param'] = (string) $TABLENAME->param;
        $_SESSION['tablename']['resgroups'] = (string) $TABLENAME->resgroups;
        $_SESSION['tablename']['resgroup_content'] = (string) $TABLENAME->resgroup_content;
        $_SESSION['tablename']['security'] = (string) $TABLENAME->security;
        $_SESSION['tablename']['status'] = (string) $TABLENAME->status;
        $_SESSION['tablename']['usergroups'] = (string) $TABLENAME->usergroups;
        $_SESSION['tablename']['usergroup_content'] = (string) $TABLENAME->usergroupcontent;
        $_SESSION['tablename']['usergroup_services'] = (string) $TABLENAME->usergroups_services;
        $_SESSION['tablename']['users'] = (string) $TABLENAME->users;
    }

    /**
    * Load Maarch modules configuration into sessions vars from modules/module_name/xml/config.xml files
    *
    * @param $modules array  Enabled modules of the application
    */
    public function load_modules_config($modules, $mode_batch=false)
    {
        require_once "core/class/class_request.php";
        // Browses enabled modules
        for ($i = 0; $i < count($modules); $i ++) {
            if (file_exists(
                $_SESSION['config']['corepath'] . 'custom' . DIRECTORY_SEPARATOR
                . $_SESSION['custom_override_id'] . DIRECTORY_SEPARATOR
                . 'modules' . DIRECTORY_SEPARATOR . $modules[$i]['moduleid']
                . DIRECTORY_SEPARATOR . "xml" . DIRECTORY_SEPARATOR
                . "config.xml"
            )
            ) {
                $configPath = $_SESSION['config']['corepath'] . 'custom'
                    . DIRECTORY_SEPARATOR . $_SESSION['custom_override_id']
                    . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR
                    . $modules[$i]['moduleid'] . DIRECTORY_SEPARATOR . "xml"
                    . DIRECTORY_SEPARATOR . "config.xml";
            } else {
                $configPath = 'modules' . DIRECTORY_SEPARATOR
                    . $modules[$i]['moduleid'] . DIRECTORY_SEPARATOR . "xml"
                    . DIRECTORY_SEPARATOR . "config.xml";
            }

            if (file_exists('modules'.DIRECTORY_SEPARATOR.$modules[$i]['moduleid'].DIRECTORY_SEPARATOR.'lang'.DIRECTORY_SEPARATOR.$_SESSION['config']['lang'].'.php')) {
                include_once 'modules'.DIRECTORY_SEPARATOR.$modules[$i]['moduleid'].DIRECTORY_SEPARATOR.'lang'.DIRECTORY_SEPARATOR.$_SESSION['config']['lang'].'.php';
            }
            if (file_exists($configPath)) {
                // Reads the config.xml file of the current module
                $xmlconfig = simplexml_load_file($configPath);
            
                // Loads into $_SESSION['modules_loaded'] module's informations
                foreach ($xmlconfig->CONFIG as $CONFIG) {
                    $_SESSION['modules_loaded'][$modules[$i]['moduleid']]['name'] =
                        (string) $CONFIG->name;
                    $_SESSION['modules_loaded'][$modules[$i]['moduleid']]['path'] =
                        'modules' . DIRECTORY_SEPARATOR . $modules[$i]['moduleid']
                        . DIRECTORY_SEPARATOR;
                    $comment = (string) $CONFIG->comment;
                    if ( !empty($comment) && defined($comment)
                        && constant($comment) <> NULL
                    ) {
                        $comment = constant($comment);
                    }
                    $_SESSION['modules_loaded'][$modules[$i]['moduleid']]['comment'] =
                        $comment;

                    $_SESSION['modules_loaded'][$modules[$i]['moduleid']]['fileprefix'] = (string) $CONFIG->fileprefix;
                    $_SESSION['modules_loaded'][$modules[$i]['moduleid']]['loaded'] = (string) $CONFIG->loaded;
                }
            }
            
            if (file_exists(
                $_SESSION['config']['corepath'] . 'custom'
                    . DIRECTORY_SEPARATOR . $_SESSION['custom_override_id']
                    . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR
                    . $modules[$i]['moduleid'] . DIRECTORY_SEPARATOR . "class"
                    . DIRECTORY_SEPARATOR . "class_modules_tools.php"
            )
            ) {
                $path_module_tools = $_SESSION['config']['corepath'] . 'custom'
                    . DIRECTORY_SEPARATOR . $_SESSION['custom_override_id']
                    . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR
                    . $modules[$i]['moduleid'] . DIRECTORY_SEPARATOR . "class"
                    . DIRECTORY_SEPARATOR . "class_modules_tools.php";
            } else {
                $path_module_tools = 'modules' . DIRECTORY_SEPARATOR
                    . $modules[$i]['moduleid'] . DIRECTORY_SEPARATOR . "class"
                    . DIRECTORY_SEPARATOR . "class_modules_tools.php";
            }
            if (file_exists($path_module_tools)) {
                require_once($path_module_tools);
                $modules_tools = new $modules[$i]['moduleid'];
                //Loads the tables of the module into session
                $modules_tools->build_modules_tables();
                //Loads log keywords of the module
            }
            foreach ($xmlconfig->KEYWORDS as $keyword) {
                $tmp = (string) $keyword->label;
                if ( !empty($tmp) && defined($tmp) && constant($tmp) <> NULL ) {
                    $tmp = constant($tmp);
                }

                $id = (string) $keyword->id;
                if (! $this->is_var_in_history_keywords_tab($id)) {
                    array_push(
                        $_SESSION['history_keywords'],
                        array(
                            'id' => $id,
                            'label' => $tmp
                        )
                    );
                }
            }
        }
        if (!$mode_batch) {
            //Loads logs keywords of the actions
            $db = new Database();
            $stmt = $db->query(
                "select id, label_action from "
                . $_SESSION['tablename']['actions']
                . " where enabled = 'Y' and history = 'Y'"
            );
            while ($res = $stmt->fetchObject()) {
                array_push(
                    $_SESSION['history_keywords'],
                    array(
                        'id' =>'ACTION#' . $res->id,
                        'label' => $this->show_string($res->label_action)
                    )
                );
            }
        }
    }

    /**
    * Check if the log keyword is known in the apps
    *
    * @param $id  string Log keyword to check
    * @return bool True if the keyword is found, False otherwise
    */
    public function is_var_in_history_keywords_tab($id)
    {
        $found = false;
        for($i=0;$i<count($_SESSION['history_keywords']);$i++)
        {
            if($_SESSION['history_keywords'][$i]['id'] == $id)
            {
                $found = $_SESSION['history_keywords'][$i]['label'];
                break;
            }
        }
        return $found;
    }

    /**
    * Loads the modules specific vars into session
    *
    * @param $modules Enabled modules of the application
    */
    public function load_var_session($modules, $userData)
    {
        for ($i = 0;$i < count($modules); $i ++) {
            if (file_exists(
                $_SESSION['config']['corepath'] . 'custom'
                    . DIRECTORY_SEPARATOR . $_SESSION['custom_override_id']
                    . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR
                    . $modules[$i]['moduleid'] . DIRECTORY_SEPARATOR . "class"
                    . DIRECTORY_SEPARATOR . "class_modules_tools.php"
            )
            ) {
                $path_module_tools = $_SESSION['config']['corepath'] . 'custom'
                    . DIRECTORY_SEPARATOR . $_SESSION['custom_override_id']
                    . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR
                    . $modules[$i]['moduleid'] . DIRECTORY_SEPARATOR . "class"
                    . DIRECTORY_SEPARATOR . "class_modules_tools.php";
            } else {
                $path_module_tools = 'modules' . DIRECTORY_SEPARATOR
                    . $modules[$i]['moduleid'] . DIRECTORY_SEPARATOR . "class"
                    . DIRECTORY_SEPARATOR . "class_modules_tools.php";
            }
            if (file_exists($path_module_tools)) {
                require_once $path_module_tools;
                $modules_tools = new $modules[$i]['moduleid'];
                if (method_exists(
                    $modules[$i]['moduleid'], 'load_module_var_session'
                )
                ) {
                    $modules_tools->load_module_var_session($userData);
                }
            }
            //$this->show_array($_SESSION['user']['baskets']);
        }
    }

    /**
    * Loads language variables into session
    */
    public static function load_lang($lang = 'fr', $maarch_directory = '', $maarch_apps = '')
    {
        if(isset($_SESSION['config']['lang']) && !empty($_SESSION['config']['lang']))
        {
            $lang = $_SESSION['config']['lang'];
        }
        if(isset($_SESSION['config']['corepath']) && !empty($_SESSION['config']['corepath']))
        {
           $maarch_directory = $_SESSION['config']['corepath'];
        }
        if(isset($_SESSION['config']['app_id']) && !empty($_SESSION['config']['app_id']))
        {
           $maarch_apps = $_SESSION['config']['app_id'];
        }
        //Loading custom lang file if present, this means that language constants are defined in the custom language file before other language files
        if (isset($_SESSION['custom_override_id']) && !empty($_SESSION['custom_override_id']))
            self::load_lang_custom_override($_SESSION['custom_override_id']);

        if(isset($lang) && file_exists($maarch_directory.'apps/maarch_entreprise/lang'.DIRECTORY_SEPARATOR.$lang.'.php'))
        {
            include($maarch_directory.'apps/maarch_entreprise/lang'.DIRECTORY_SEPARATOR.$lang.'.php');
        }
        else
        {
            $_SESSION['error'] = "Language file missing";
        }
        if(isset($_SESSION['modules']))
        {
            self::load_lang_modules($_SESSION['modules']);
        }

    }

    /**
    * Loads language variables of each module
    *
    * @param $modules array Enabled modules of the application
    */
    private static function load_lang_modules($modules)
    {
        for ($i=0;$i<count($modules);$i++) {
            $file_path = $_SESSION['config']['corepath'] . 'custom'
                . DIRECTORY_SEPARATOR . $_SESSION['custom_override_id']
                . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR
                . $modules[$i]['moduleid'] . DIRECTORY_SEPARATOR
                . 'lang' . DIRECTORY_SEPARATOR
                . $_SESSION['config']['lang'] . '.php';
            if (!file_exists($file_path)) {
                $file_path = $_SESSION['config']['corepath'] . 'modules'
                . DIRECTORY_SEPARATOR . $modules[$i]['moduleid']
                . DIRECTORY_SEPARATOR . 'lang' . DIRECTORY_SEPARATOR
                . $_SESSION['config']['lang'] . '.php';
            }
            if (isset($_SESSION['config']['lang']) && file_exists($file_path)) {
                include($file_path);
            } else if ($_SESSION['config']['debug'] === "true") {
                $_SESSION['info'] .= "Language file missing for module : "
                . $modules[$i]['moduleid']."<br/>";
            }
        }
    }

    private static function load_lang_custom_override($custom_id)
    {
        $pathname = $_SESSION['config']['corepath'].'custom'.DIRECTORY_SEPARATOR.$custom_id.DIRECTORY_SEPARATOR.$_SESSION['config']['lang'].'.php';

        if (file_exists($pathname)) {
            include($pathname);
        }
    }

    /**
    * Loads menu items of each module and the application into session from menu.xml files
    *
    * @param $modules array Enabled modules of the application
    */
    public function load_menu($modules)
    {
        $k = 0;
        if (file_exists(
            $_SESSION['config']['corepath'] . 'custom' . DIRECTORY_SEPARATOR
            . $_SESSION['custom_override_id'] . DIRECTORY_SEPARATOR . 'apps'
            . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
            . DIRECTORY_SEPARATOR . 'xml' . DIRECTORY_SEPARATOR . 'menu.xml'
        )
        ) {
            $path = $_SESSION['config']['corepath'] . 'custom'
                . DIRECTORY_SEPARATOR . $_SESSION['custom_override_id']
                . DIRECTORY_SEPARATOR . 'apps' . DIRECTORY_SEPARATOR
                . $_SESSION['config']['app_id'] . DIRECTORY_SEPARATOR . 'xml'
                . DIRECTORY_SEPARATOR . 'menu.xml';
        } else {
            $path = 'apps' . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
                . DIRECTORY_SEPARATOR . 'xml' . DIRECTORY_SEPARATOR . 'menu.xml';
        }
        // Reads the apps/apps_name/xml/menu.xml file  and loads into session
        $xmlconfig = simplexml_load_file($path);
        foreach ($xmlconfig->MENU as $MENU2) {
            $_SESSION['menu'][$k]['id'] = (string) $MENU2->id;
            if (isset($_SESSION['menu'][$k]['id'])
                && isset($_SESSION['user']['services'][$_SESSION['menu'][$k]['id']])
                && $_SESSION['user']['services'][$_SESSION['menu'][$k]['id']] == true
            ) { // Menu Identifier must be equal to the Service identifier
                $libmenu = (string) $MENU2->libconst;
                if ( !empty($libmenu) && defined($libmenu)
                    && constant($libmenu) <> NULL
                ) {
                    $libmenu  = constant($libmenu);
                }
                $_SESSION['menu'][$k]['libconst'] = $libmenu;
                $_SESSION['menu'][$k]['url'] = $_SESSION['config']['businessappurl']
                    . (string) $MENU2->url;
                if (trim((string) $MENU2->target) <> "") {
                    $tmp = preg_replace(
                        '/\/core\/$/', '/', $_SESSION['urltocore']
                    );
                    $_SESSION['menu'][$k]['url'] = $tmp. (string) $MENU2->url;
                    $_SESSION['menu'][$k]['target'] = (string) $MENU2->target;
                }
                $_SESSION['menu'][$k]['style'] = (string) $MENU2->style;
                $_SESSION['menu'][$k]['show'] = true;
            } else {
                $_SESSION['menu'][$k]['libconst'] ='';
                $_SESSION['menu'][$k]['url'] ='';
                $_SESSION['menu'][$k]['style'] = '';
                $_SESSION['menu'][$k]['show'] = false;
            }
            $k ++;
        }
        // Browses the enabled modules array
        for ($i = 0; $i < count($modules); $i ++) {
            if (file_exists(
                $_SESSION['config']['corepath'] . 'custom' . DIRECTORY_SEPARATOR
                . $_SESSION['custom_override_id'] . DIRECTORY_SEPARATOR
                . 'modules' . DIRECTORY_SEPARATOR . $modules[$i]['moduleid']
                . DIRECTORY_SEPARATOR . "xml" . DIRECTORY_SEPARATOR . "menu.xml"
                )
            ) {
                $menuPath = $_SESSION['config']['corepath'] . 'custom'
                    . DIRECTORY_SEPARATOR . $_SESSION['custom_override_id']
                    . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR
                    . $modules[$i]['moduleid'] . DIRECTORY_SEPARATOR . "xml"
                    . DIRECTORY_SEPARATOR . "menu.xml";
            } else {
                $menuPath = 'modules' . DIRECTORY_SEPARATOR
                    . $modules[$i]['moduleid'] . DIRECTORY_SEPARATOR . "xml"
                    . DIRECTORY_SEPARATOR . "menu.xml";
            }

            if (file_exists(
                $_SESSION['config']['corepath'] . 'modules'
                . DIRECTORY_SEPARATOR . $modules[$i]['moduleid']
                . DIRECTORY_SEPARATOR . "xml" . DIRECTORY_SEPARATOR . "menu.xml"
            ) || file_exists(
                $_SESSION['config']['corepath'] . 'custom' . DIRECTORY_SEPARATOR
                . $_SESSION['custom_override_id'] . DIRECTORY_SEPARATOR
                . 'modules' . DIRECTORY_SEPARATOR . $modules[$i]['moduleid']
                . DIRECTORY_SEPARATOR . "xml" . DIRECTORY_SEPARATOR . "menu.xml"
            )
            ) {
                $xmlconfig = simplexml_load_file($menuPath);
                foreach ($xmlconfig->MENU as $MENU) {
                    $_SESSION['menu'][$k]['id'] = (string) $MENU->id;
                    if (isset(
                        $_SESSION['user']['services'][$_SESSION['menu'][$k]['id']]
                    )
                    && $_SESSION['user']['services'][$_SESSION['menu'][$k]['id']] == true
                    ) {
                        $libmenu = (string) $MENU->libconst;
                        if ( !empty($libmenu) && defined($libmenu)
                            && constant($libmenu) <> NULL
                        ) {
                            $libmenu  = constant($libmenu);
                        }
                        $_SESSION['menu'][$k]['libconst'] = $libmenu;
                        $_SESSION['menu'][$k]['url'] = $_SESSION['config']['businessappurl']
                            . (string) $MENU->url;
                        if (trim((string) $MENU->target) <> "") {
                            $tmp = preg_replace(
                                '/\/core\/$/', '/', $_SESSION['urltocore']
                            );
                            $_SESSION['menu'][$k]['url'] = $tmp
                                . (string) $MENU->url;
                            $_SESSION['menu'][$k]['target'] = (string) $MENU->target;
                        }
                        $_SESSION['menu'][$k]['style'] = (string) $MENU->style;
                        $_SESSION['menu'][$k]['show'] = true;
                    } else {
                        $_SESSION['menu'][$k]['libconst'] = '';
                        $_SESSION['menu'][$k]['url'] = '';
                        $_SESSION['menu'][$k]['style'] = '';
                        $_SESSION['menu'][$k]['show'] = false;
                    }
                    $k ++;
                }
            }
        }
        $this->load_quicklaunch($modules);
    }

    /**
    * Builds the application menu from the session var menu
    *
    * @param  $menu array Enabled menu items
    * @param  $myProfil boolean Enabled my profil item
    * @param  $logout boolean Enabled logout item
    */
    public function build_menu($menu, $myProfil = true, $logout = true)
    {
        //menu tri
        $i=0;
        foreach ($menu as $key => $row) {
            $label[$i] = $row['libconst'];
            $i++;
        }
        // Trie les données par volume décroissant, edition croissant
        // Ajoute $data en tant que dernier paramètre, pour trier par la clé commune
        array_multisort($label, SORT_ASC, $label, SORT_ASC, $menu);

        // Browses the menu items
        for($i=0;$i<count($menu);$i++)
        {
            if($menu[$i]['show'] == true)
            {
                $tmp = $menu[$i]['url'];

                if(preg_match('/php$/', $tmp))
                {
                    $tmp .= "?reinit=true";
                }
                else
                {
                    $tmp .= "&reinit=true";
                }
                $tmp = htmlentities  ( $tmp,ENT_COMPAT, 'UTF-8', true); // Encodes
                ?>
                <li onmouseover="this.className='on';" onmouseout="this.className='';">
                <a href="#" onclick="window.open('<?php echo($tmp);?>', '<?php
                    if(isset($menu[$i]['target']) && $menu[$i]['target'] <> '') {
                        echo $menu[$i]['target'];
                    } else {
                        echo '_self';
                    }?>');"><span><span style="width:30px;height:30px;display:inline-block;text-align:center;"><i class="<?php 
                        echo $menu[$i]['style'] . ' fa-2x';
                        ?>"></i></span><span><?php 
                        echo trim($menu[$i]['libconst']);?></span></span></a></li>
                <?php
            }
        }

        // Menu items always displayed
        if ($myProfil) {
        echo '<li onmouseover="this.className=\'on\';" onmouseout="this.className=\'\';">
        <a href="'.$_SESSION['config']['businessappurl']
            . 'index.php?page=modify_user&amp;admin=users&amp;reinit=true"><span><span style="width:30px;height:30px;display:inline-block;text-align:center;"><i class="fa fa-user fa-2x"></i></span><span>'._MY_INFO.'</span></span></a></li>';
        }
        if ($logout) {
        echo '<li onmouseover="this.className=\'on\';" onmouseout="this.className=\'\';">
        <a href="'.$_SESSION['config']['businessappurl']
            . 'index.php?display=true&amp;page=logout&amp;logout=true"><span><span style="width:30px;height:30px;display:inline-block;text-align:center;"><i class="fa fa-power-off fa-2x"></i></span><span>'._LOGOUT.'</span></span></a></li>';
        }
    }

    /**
    * Loads menu items of each module and the application into session from menu.xml files
    *
    * @param $modules array Enabled modules of the application
    */
    public function load_quicklaunch($modules)
    {
        $k = 0;
        if (file_exists(
            $_SESSION['config']['corepath'] . 'custom' . DIRECTORY_SEPARATOR
            . $_SESSION['custom_override_id'] . DIRECTORY_SEPARATOR . 'apps'
            . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
            . DIRECTORY_SEPARATOR . 'xml' . DIRECTORY_SEPARATOR . 'menu.xml'
        )
        ) {
            $path = $_SESSION['config']['corepath'] . 'custom'
                . DIRECTORY_SEPARATOR . $_SESSION['custom_override_id']
                . DIRECTORY_SEPARATOR . 'apps' . DIRECTORY_SEPARATOR
                . $_SESSION['config']['app_id'] . DIRECTORY_SEPARATOR . 'xml'
                . DIRECTORY_SEPARATOR . 'menu.xml';
        } else {
            $path = 'apps' . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
                . DIRECTORY_SEPARATOR . 'xml' . DIRECTORY_SEPARATOR . 'menu.xml';
        }
        // Reads the apps/apps_name/xml/menu.xml file  and loads into session
        $xmlconfig = simplexml_load_file($path);
        foreach ($xmlconfig->MENU as $MENU2) {
            $_SESSION['quicklaunch'][$k]['id'] = (string) $MENU2->id;
            if (isset($_SESSION['quicklaunch'][$k]['id'])
                && isset($_SESSION['user']['services'][$_SESSION['quicklaunch'][$k]['id']])
                && $_SESSION['user']['services'][$_SESSION['quicklaunch'][$k]['id']] == true
            ) { // Menu Identifier must be equal to the Service identifier
                $libmenu = (string) $MENU2->libconst;
                if ( !empty($libmenu) && defined($libmenu)
                    && constant($libmenu) <> NULL
                ) {
                    $libmenu  = constant($libmenu);
                }
                $_SESSION['quicklaunch'][$k]['libconst'] = $libmenu;
                $_SESSION['quicklaunch'][$k]['url'] = $_SESSION['config']['businessappurl']
                    . (string) $MENU2->url;
                if (trim((string) $MENU2->target) <> "") {
                    $tmp = preg_replace(
                        '/\/core\/$/', '/', $_SESSION['urltocore']
                    );
                    $_SESSION['quicklaunch'][$k]['url'] = $tmp. (string) $MENU2->url;
                    $_SESSION['quicklaunch'][$k]['target'] = (string) $MENU2->target;
                }
                $_SESSION['quicklaunch'][$k]['style'] = (string) $MENU2->style;
                $_SESSION['quicklaunch'][$k]['show'] = true;
            } else {
                $_SESSION['quicklaunch'][$k]['libconst'] ='';
                $_SESSION['quicklaunch'][$k]['url'] ='';
                $_SESSION['quicklaunch'][$k]['style'] = '';
                $_SESSION['quicklaunch'][$k]['show'] = false;
            }
            $k ++;
        }
        // Browses the enabled modules array
        for ($i = 0; $i < count($modules); $i ++) {
            if (file_exists(
                $_SESSION['config']['corepath'] . 'custom' . DIRECTORY_SEPARATOR
                . $_SESSION['custom_override_id'] . DIRECTORY_SEPARATOR
                . 'modules' . DIRECTORY_SEPARATOR . $modules[$i]['moduleid']
                . DIRECTORY_SEPARATOR . "xml" . DIRECTORY_SEPARATOR . "menu.xml"
                )
            ) {
                $menuPath = $_SESSION['config']['corepath'] . 'custom'
                    . DIRECTORY_SEPARATOR . $_SESSION['custom_override_id']
                    . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR
                    . $modules[$i]['moduleid'] . DIRECTORY_SEPARATOR . "xml"
                    . DIRECTORY_SEPARATOR . "menu.xml";
            } else {
                $menuPath = 'modules' . DIRECTORY_SEPARATOR
                    . $modules[$i]['moduleid'] . DIRECTORY_SEPARATOR . "xml"
                    . DIRECTORY_SEPARATOR . "menu.xml";
            }

            if (file_exists(
                $_SESSION['config']['corepath'] . 'modules'
                . DIRECTORY_SEPARATOR . $modules[$i]['moduleid']
                . DIRECTORY_SEPARATOR . "xml" . DIRECTORY_SEPARATOR . "menu.xml"
            ) || file_exists(
                $_SESSION['config']['corepath'] . 'custom' . DIRECTORY_SEPARATOR
                . $_SESSION['custom_override_id'] . DIRECTORY_SEPARATOR
                . 'modules' . DIRECTORY_SEPARATOR . $modules[$i]['moduleid']
                . DIRECTORY_SEPARATOR . "xml" . DIRECTORY_SEPARATOR . "menu.xml"
            )
            ) {
                $xmlconfig = simplexml_load_file($menuPath);
                foreach ($xmlconfig->MENU as $MENU) {
                    $_SESSION['quicklaunch'][$k]['id'] = (string) $MENU->id;
                    if (isset(
                        $_SESSION['user']['services'][$_SESSION['quicklaunch'][$k]['id']]
                    )
                    && $_SESSION['user']['services'][$_SESSION['quicklaunch'][$k]['id']] == true
                    ) {
                        $libmenu = (string) $MENU->libconst;
                        if ( !empty($libmenu) && defined($libmenu)
                            && constant($libmenu) <> NULL
                        ) {
                            $libmenu  = constant($libmenu);
                        }
                        $_SESSION['quicklaunch'][$k]['libconst'] = $libmenu;
                        $_SESSION['quicklaunch'][$k]['url'] = $_SESSION['config']['businessappurl']
                            . (string) $MENU->url;
                        if (trim((string) $MENU->target) <> "") {
                            $tmp = preg_replace(
                                '/\/core\/$/', '/', $_SESSION['urltocore']
                            );
                            $_SESSION['quicklaunch'][$k]['url'] = $tmp
                                . (string) $MENU->url;
                            $_SESSION['quicklaunch'][$k]['target'] = (string) $MENU->target;
                        }
                        $_SESSION['quicklaunch'][$k]['style'] = (string) $MENU->style;
                        $_SESSION['quicklaunch'][$k]['show'] = true;
                    } else {
                        $_SESSION['quicklaunch'][$k]['libconst'] = '';
                        $_SESSION['quicklaunch'][$k]['url'] = '';
                        $_SESSION['quicklaunch'][$k]['style'] = '';
                        $_SESSION['quicklaunch'][$k]['show'] = false;
                    }
                    $k ++;
                }
            }
        }
        
    }

    /**
    * Builds the application quicklaunch from the session var quicklaunch
    *
    * @param  $quicklaunch array Enabled quicklaunch items
    */
    public function build_quicklaunch($quicklaunch)
    {
        //$this->show_array($quicklaunch);
        // Browses the quicklaunch items
        $arrTmpQuicklaunch = array();
        for ($i=0;$i<count($quicklaunch);$i++) {
            if ($quicklaunch[$i]['show'] == true) {
                array_push($arrTmpQuicklaunch, $quicklaunch[$i]);
            }
        }
        echo '<div style="width: 85%;margin: auto;">';
        for ($i=0;$i<count($arrTmpQuicklaunch);$i++) {
            if ($arrTmpQuicklaunch[$i]['show'] == true) {
                $tmp = $arrTmpQuicklaunch[$i]['url'];
                if (preg_match('/php$/', $tmp)) {
                    $tmp .= "?reinit=true";
                } else {
                    $tmp .= "&reinit=true";
                }
                $tmp = htmlentities($tmp, ENT_COMPAT, 'UTF-8', true); // Encodes
                ?>
                <a href="#" style="display: inline-block;width: 45%;float:left;" onclick="window.open('<?php 
                        echo $tmp;
                        ?>', '<?php 
                        if(
                            isset($arrTmpQuicklaunch[$i]['target']) 
                            && $arrTmpQuicklaunch[$i]['target'] <> ''
                        ) {
                            echo $arrTmpQuicklaunch[$i]['target'];
                        } else {
                            echo '_self';
                        }?>');">
                        <span>
                <span style="width:30px;height:30px;display:inline-block;text-align:center;" id="<?php 
                    echo $arrTmpQuicklaunch[$i]['style'];
                    ?>" >
                    <i style="width:30px;height:30px;" class="<?php 
                        echo $arrTmpQuicklaunch[$i]['style'] . ' fa-2x'
                        ;?> mCdarkGrey"></i>
                </span>
                <span>
                       
                            <?php 
                            echo trim($arrTmpQuicklaunch[$i]['libconst']);
                            ?>
                    
                </span>
                </span>
                </a>
                <?php
            } else {
                //$this->show_array($arrTmpQuicklaunch[$i]);
            }
            if($i%2){
                echo '<div style="clear:both;"></div>';
            }
            
        }
        echo '</div>';

        echo '<div style="clear:both;"></div>';
        // quicklaunch items always displayed
        echo '<div style="width: 85%;margin: auto;">';
        echo '<a style="display: inline-block;width: 45%;float: left;" href="' . $_SESSION['config']['businessappurl']
                . 'index.php?page=modify_user&amp;admin=users&amp;reinit=true">';
        echo '<span>';
        echo '<span style="width:30px;height:30px;display:inline-block;text-align:center;">'
            .'<i class="fa fa-user fa-2x mCdarkGrey"></i></span>';
        echo '<span>'. _MY_INFO . '</span>';
        echo '</a>';

        echo '<a style="display: inline-block;width: 45%;float: left;" href="' . $_SESSION['config']['businessappurl']
            . 'index.php?display=true&amp;page=logout&amp;logout=true">';
        echo '<span>';
        echo '<span style="width:30px;height:30px;display:inline-block;text-align:center;">'
            .'<i class="fa fa-power-off fa-2x mCdarkGrey"></i></span>';
        echo '<span>'. _LOGOUT . '</span>';
        echo '</a>';
        echo '<div style="clear:both;"></div>';
        echo '</div>';
    }

    /**
    * Loads application services into session
    */
    public function load_app_services()
    {
        // Reads the application config.xml file
        if (file_exists(
            $_SESSION['config']['corepath'] . 'custom' . DIRECTORY_SEPARATOR
            . $_SESSION['custom_override_id'] . DIRECTORY_SEPARATOR . 'apps'
            . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
            . DIRECTORY_SEPARATOR . 'xml' . DIRECTORY_SEPARATOR . 'services.xml'
        )
        ) {
            $path = $_SESSION['config']['corepath'] . 'custom'
                . DIRECTORY_SEPARATOR . $_SESSION['custom_override_id']
                . DIRECTORY_SEPARATOR . 'apps' . DIRECTORY_SEPARATOR
                . $_SESSION['config']['app_id'] . DIRECTORY_SEPARATOR . 'xml'
                . DIRECTORY_SEPARATOR . 'services.xml';
        } else {
            $path = 'apps' . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
                . DIRECTORY_SEPARATOR . 'xml' . DIRECTORY_SEPARATOR
                . 'services.xml';
        }
        $xmlconfig = simplexml_load_file($path);
        $k = 0;
        $m = 0;
        include_once 'apps/' .$_SESSION['config']['app_id']. '/lang/' . $_SESSION['config']['lang'].'.php' ;
        // Browses the services in that file and loads $_SESSION['app_services']
        foreach ($xmlconfig->SERVICE as $service) {
            $_SESSION['app_services'][$k] = array();
            $_SESSION['app_services'][$k]['id'] = (string) $service->id;
            $name = (string) $service->name;
            if ( !empty($name) && defined($name) && constant($name) <> NULL ) {
                $name  = constant($name);
            }
            $_SESSION['app_services'][$k]['name'] = $name;
            $comment = (string) $service->comment;
            if ( !empty($comment) && defined($comment)
                && constant($comment) <> NULL
            ) {
                $comment  = constant($comment);
            }
            $_SESSION['app_services'][$k]['comment'] = $comment;
            if (isset($service->servicepage)) {
                $_SESSION['app_services'][$k]['servicepage'] = (string) $service->servicepage;
                $_SESSION['app_services'][$k]['servicepage'] = preg_replace(
                    '/&admin/', '&amp;admin',
                    $_SESSION['app_services'][$k]['servicepage']
                );
                $_SESSION['app_services'][$k]['servicepage'] = preg_replace(
                    '/&module/', '&amp;module',
                    $_SESSION['app_services'][$k]['servicepage']
                );
            }
            $_SESSION['app_services'][$k]['servicetype'] = (string) $service->servicetype;

            if (isset($service->style)) {
                $_SESSION['app_services'][$k]['style'] = (string) $service->style;
            }

            $systemService = (string) $service->system_service;
            if ($systemService == "false") {
                $_SESSION['app_services'][$k]['system_service'] = false;
            } else {
                $_SESSION['app_services'][$k]['system_service'] = true;
            }
            $_SESSION['app_services'][$k]['enabled'] = (string) $service->enabled;
            $l = 0;
            foreach ($service->WHEREAMIUSED as $whereAmIUsed) {
                if (isset($whereAmIUsed)) {
                    $_SESSION['app_services'][$k]['whereamiused'][$l]['page'] = (string) $whereAmIUsed->page;
                    $_SESSION['app_services'][$k]['whereamiused'][$l]['nature'] = (string) $whereAmIUsed->nature;
                    if (isset($whereAmIUsed->button_label)) {
                        $_SESSION['app_services'][$k]['whereamiused'][$l]['button_label'] = (string) $whereAmIUsed->button_label;
                    }
                    if (isset($whereAmIUsed->tab_label)) {
                        $label = (string) $whereAmIUsed->tab_label;
                        if ( !empty($label) && defined($label)
                            && constant($label) <> NULL
                        ) {
                            $label  = constant($label);
                        }
                        $_SESSION['app_services'][$k]['whereamiused'][$l]['tab_label'] = $label;
                    }
                    if (isset($whereAmIUsed->tab_order)) {
                        $_SESSION['app_services'][$k]['whereamiused'][$l]['tab_order'] = (string) $whereAmIUsed->tab_order;
                    }
                    if (isset($whereAmIUsed->width)) {
                        $_SESSION['app_services'][$k]['whereamiused'][$l]['width'] = (string) $whereAmIUsed->width;
                    }
                    if (isset($whereAmIUsed->frame_id)) {
                        $_SESSION['app_services'][$k]['whereamiused'][$l]['frame_id'] = (string) $whereAmIUsed->frame_id;
                    }
                    if (isset($whereAmIUsed->height)) {
                        $_SESSION['app_services'][$k]['whereamiused'][$l]['height'] = (string) $whereAmIUsed->height;
                    }
                    if (isset($whereAmIUsed->scrolling)){
                        $_SESSION['app_services'][$k]['whereamiused'][$l]['scrolling'] = (string) $whereAmIUsed->scrolling;
                    }
                    if (isset($whereAmIUsed->style)) {
                        $_SESSION['app_services'][$k]['whereamiused'][$l]['style'] = (string) $whereAmIUsed->style;
                    }
                    if (isset($whereAmIUsed->border)) {
                        $_SESSION['app_services'][$k]['whereamiused'][$l]['border'] = (string) $whereAmIUsed->border;
                    }
                    $l ++;
                }
            }
            $m = 0;
            // Loads preprocess and postprocess
            foreach ($service->PROCESSINBACKGROUND as $processInBackground) {
                $_SESSION['app_services'][$k]['processinbackground'][$m]['page'] = (string) $processInBackground->page;
                if ((string) $processInBackground->preprocess <> "") {
                    $_SESSION['app_services'][$k]['processinbackground'][$m]['preprocess'] = (string) $processInBackground->preprocess;
                }
                if ((string) $processInBackground->postprocess <> "") {
                    $_SESSION['app_services'][$k]['processinbackground'][$m]['postprocess'] = (string) $processInBackground->postprocess;
                }
                $_SESSION['app_services'][$k]['processinbackground'][$m]['processorder'] = (string) $processInBackground->processorder;
                $m++;
            }
            $k ++;
        }
    }

    /**
    * Loads the services of each module into session
    *
    * @param $modules array Enabled modules of the application
    */
    public function load_modules_services($modules)
    {
        // Browses the enabled modules array
        for ($i = 0; $i < count($modules); $i ++) {
            // Reads the module config.xml file
            if (file_exists(
                $_SESSION['config']['corepath'] . 'custom' . DIRECTORY_SEPARATOR
                . $_SESSION['custom_override_id'] . DIRECTORY_SEPARATOR
                . 'modules' . DIRECTORY_SEPARATOR . $modules[$i]['moduleid']
                . DIRECTORY_SEPARATOR . "xml" . DIRECTORY_SEPARATOR
                . "services.xml"
            )
            ) {
                $path = $_SESSION['config']['corepath'] . 'custom'
                    . DIRECTORY_SEPARATOR  . $_SESSION['custom_override_id']
                    . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR
                    . $modules[$i]['moduleid'] . DIRECTORY_SEPARATOR . "xml"
                    . DIRECTORY_SEPARATOR . "services.xml";
            } else {
                $path = 'modules' . DIRECTORY_SEPARATOR
                    . $modules[$i]['moduleid'] . DIRECTORY_SEPARATOR . "xml"
                    . DIRECTORY_SEPARATOR . "services.xml";
            }
            $xmlconfig = simplexml_load_file($path);
            $k = 0;
            $m = 0;
            foreach ($xmlconfig->SERVICE as $service) {
                if ((string) $service->enabled == "true") {
                    $_SESSION['modules_services'][$modules[$i]['moduleid']][$k]['id'] = (string) $service->id;
                    $name = (string) $service->name;
                    if ( !empty($name) && defined($name)
                        && constant($name) <> NULL
                    ) {
                        $name  = constant($name);
                    }
                    $_SESSION['modules_services'][$modules[$i]['moduleid']][$k]['name'] =
                        $name;

                    $comment = (string) $service->comment;
                    if ( !empty($comment) && defined($comment)
                        && constant($comment) <> NULL
                    ) {
                        $comment  = constant($comment);
                    }
                    $_SESSION['modules_services'][$modules[$i]['moduleid']][$k]['comment'] =
                        $comment;

                    if (isset($service->servicepage)) {
                        $_SESSION['modules_services'][$modules[$i]['moduleid']][$k]['servicepage'] = (string) $service->servicepage;
                    }
                    $_SESSION['modules_services'][$modules[$i]['moduleid']][$k]['servicetype'] = (string) $service->servicetype;

                    if (isset($service->style)) {
                        $_SESSION['modules_services'][$modules[$i]['moduleid']][$k]['style'] = (string) $service->style;
                    }
                    $systemService =  (string) $service->system_service;
                    if ($systemService == "false") {
                        $_SESSION['modules_services'][$modules[$i]['moduleid']][$k]['system_service'] = false;
                    } else {
                        $_SESSION['modules_services'][$modules[$i]['moduleid']][$k]['system_service'] = true;
                    }
                    $_SESSION['modules_services'][$modules[$i]['moduleid']][$k]['enabled'] = (string) $service->enabled;

                    $l = 0;
                    foreach ($service->WHEREAMIUSED as $whereAmIUsed) {
                        $_SESSION['modules_services'][$modules[$i]['moduleid']][$k]['whereamiused'][$l]['page'] = (string) $whereAmIUsed->page;
                        $_SESSION['modules_services'][$modules[$i]['moduleid']][$k]['whereamiused'][$l]['nature'] = (string) $whereAmIUsed->nature;
                        if (isset($whereAmIUsed->button_label)) {
                            $label = (string) $whereAmIUsed->button_label;
                            if ( !empty($label) && defined($label)
                                && constant($label) <> NULL
                            ) {
                                $label  = constant($label);
                            }
                            $_SESSION['modules_services'][$modules[$i]['moduleid']][$k]['whereamiused'][$l]['button_label'] =
                                $label;
                        }
                        if (isset($whereAmIUsed->tab_label)) {
                            $label = (string) $whereAmIUsed->tab_label;
                            if ( !empty($label) && defined($label)
                                && constant($label) <> NULL
                            ) {
                                $label  = constant($label);
                            }
                            $_SESSION['modules_services'][$modules[$i]['moduleid']][$k]['whereamiused'][$l]['tab_label'] =
                                $label;
                        }
                        if (isset($whereAmIUsed->tab_order)) {
                            $_SESSION['modules_services'][$modules[$i]['moduleid']][$k]['whereamiused'][$l]['tab_order'] = (string) $whereAmIUsed->tab_order;
                        }
                        if (isset($whereAmIUsed->frame_id)) {
                            $_SESSION['modules_services'][$modules[$i]['moduleid']][$k]['whereamiused'][$l]['frame_id'] = (string) $whereAmIUsed->frame_id;
                        }
                        if (isset($whereAmIUsed->width)) {
                            $_SESSION['modules_services'][$modules[$i]['moduleid']][$k]['whereamiused'][$l]['width'] = (string) $whereAmIUsed->width;
                        }
                        if (isset($whereAmIUsed->height)) {
                            $_SESSION['modules_services'][$modules[$i]['moduleid']][$k]['whereamiused'][$l]['height'] = (string) $whereAmIUsed->height;
                        }
                        if (isset($whereAmIUsed->scrolling)) {
                            $_SESSION['modules_services'][$modules[$i]['moduleid']][$k]['whereamiused'][$l]['scrolling'] = (string) $whereAmIUsed->scrolling;
                        }
                        if (isset($whereAmIUsed->style)) {
                            $_SESSION['modules_services'][$modules[$i]['moduleid']][$k]['whereamiused'][$l]['style'] = (string) $whereAmIUsed->style;
                        }
                        if (isset($whereAmIUsed->border)) {
                            $_SESSION['modules_services'][$modules[$i]['moduleid']][$k]['whereamiused'][$l]['border'] = (string) $whereAmIUsed->border;
                        }
                        $l ++;
                    }
                    $m = 0;
                    foreach ($service->PROCESSINBACKGROUND as $processInBackground) {
                        $_SESSION['modules_services'][$modules[$i]['moduleid']][$k]['processinbackground'][$m]['page'] = (string) $processInBackground->page;
                        if ((string) $processInBackground->preprocess <> "") {
                            $_SESSION['modules_services'][$modules[$i]['moduleid']][$k]['processinbackground'][$m]['preprocess'] = (string) $processInBackground->preprocess;
                        }
                        if ((string) $processInBackground->postprocess <> "") {
                            $_SESSION['modules_services'][$modules[$i]['moduleid']][$k]['processinbackground'][$m]['postprocess'] = (string) $processInBackground->postprocess;
                        }
                        $_SESSION['modules_services'][$modules[$i]['moduleid']][$k]['processinbackground'][$m]['processorder'] = (string) $processInBackground->processorder;
                        $m ++;
                    }
                    $k ++;
                }
            }
        }
    }

    /**
    * Executes the module' s services in the page
    *
    * @param $modules_services  array List of the module's services
    * @param $whereami  string Page where to execute the service
    * @param $servicenature string  Nature of the service (by default, the function takes all the services natures)
    * @param  $id_service string Identifier of one specific service (empty by default)
    * @param  $id_module string Identifier of one specific module (empty by default)
    */
    public function execute_modules_services($modules_services, $whereami, $servicenature = "all", $id_service = '', $id_module = '')
    {
        $executedServices = array();
        if (! empty($id_service) && ! empty($id_module)) {
            for ($i = 0; $i < count($modules_services[$id_module]); $i ++) {
                if ($modules_services[$id_module][$i]['id'] == $id_service
                    && isset($modules_services[$id_module][$i]['whereamiused'])
                ) {
                    for ($k = 0; $k < count(
                        $modules_services[$id_module][$i]['whereamiused']
                    ); $k ++
                    ) {
                        $name = $id = $width = $height = $frameborder = $scrolling = $style = '';
                        if ($modules_services[$id_module][$i]['whereamiused'][$k]['page'] == $whereami) {
                            if ($modules_services[$id_module][$i]['whereamiused'][$k]['nature'] == "frame"
                                && $_SESSION['user']['services'][$modules_services[$id_module][$i]['id']]
                                && ! in_array(
                                    $modules_services[$id_module][$i]['id'],
                                    $executedServices
                                )
                            ) {
                                array_push(
                                    $executedServices,
                                    $modules_services[$id_module][$i]['id']
                                );

                                if (isset(
                                    $modules_services[$id_module][$i]['whereamiused'][$k]['frame_id']
                                ) && ! empty(
                                    $modules_services[$id_module][$i]['whereamiused'][$k]['frame_id']
                                )
                                ) {
                                    $name = 'name="' . $modules_services[$id_module][$i]['whereamiused'][$k]['frame_id'].'"';
                                }
                                if (isset(
                                    $modules_services[$id_module][$i]['whereamiused'][$k]['frame_id']
                                ) && ! empty(
                                        $modules_services[$id_module][$i]['whereamiused'][$k]['frame_id']
                                )
                                ) {
                                    $id = 'id="' . $modules_services[$id_module][$i]['whereamiused'][$k]['frame_id'] . '"';
                                }
                                if (isset(
                                    $modules_services[$id_module][$i]['whereamiused'][$k]['width']
                                ) &&  strlen(
                                    $modules_services[$id_module][$i]['whereamiused'][$k]['width']
                                ) > 0
                                ) {
                                    $width = 'width="' . $modules_services[$id_module][$i]['whereamiused'][$k]['width'].'" ';
                                }
                                if (isset(
                                    $modules_services[$id_module][$i]['whereamiused'][$k]['height']
                                ) &&  strlen(
                                    $modules_services[$id_module][$i]['whereamiused'][$k]['height']
                                ) > 0
                                ) {
                                    $height = 'height="' . $modules_services[$id_module][$i]['whereamiused'][$k]['height'].'"';
                                }
                                if (isset(
                                    $modules_services[$id_module][$i]['whereamiused'][$k]['border']
                                ) && strlen(
                                    $modules_services[$id_module][$i]['whereamiused'][$k]['border']
                                ) > 0
                                ) {
                                    $frameborder = 'frameborder="'
                                        . $modules_services[$id_module][$i]['whereamiused'][$k]['border'].'" ';
                                }
                                if (isset(
                                    $modules_services[$id_module][$i]['whereamiused'][$k]['scrolling']
                                ) && ! empty(
                                    $modules_services[$id_module][$i]['whereamiused'][$k]['scrolling']
                                )
                                ) {
                                    $scrolling = 'scrolling="'
                                        . $modules_services[$id_module][$i]['whereamiused'][$k]['scrolling'].'"';
                                }
                                if (isset(
                                    $modules_services[$id_module][$i]['whereamiused'][$k]['style']
                                ) && ! empty(
                                    $modules_services[$id_module][$i]['whereamiused'][$k]['style']
                                )
                                ) {
                                    $style = 'style="'
                                        . $modules_services[$id_module][$i]['whereamiused'][$k]['style'].'"';
                                }

                                $iframeStr = '<iframe src="'
                                    . $_SESSION['config']['businessappurl']
                                    . 'index.php?display=true&module='
                                    . $id_module . '&page='
                                    . $modules_services[$id_module][$i]['servicepage']
                                    . '" ' . $name . ' ' . $id . ' ' . $width
                                    . ' ' . $height . ' ' . $frameborder . ' '
                                    . $scrolling . ' ' . $style . '></iframe>';

                                return $iframeStr;
                                //break;
                            } else if ($modules_services[$id_module][$i]['whereamiused'][$k]['nature'] == "popup"
                                && $_SESSION['user']['services'][$modules_services[$id_module][$i]['id']]
                                && ! in_array(
                                    $modules_services[$id_module][$i]['id'], $executedServices
                                )
                            ) {
                                array_push(
                                    $executedServices,
                                    $modules_services[$id_module][$i]['id']
                                );
                                echo $modules_services[$id_module][$i]['name'];
                                ?>
                                <br />
                                <a href='<?php echo $_SESSION['config']['businessappurl'].'index.php?display=true&module='.$id_module."&page=".$modules_services[$id_module][$i]['servicepage'];?>' target='_blank'><?php echo _ACCESS_TO_SERVICE;?></a><br /><br />
                                <?php
                                break;
                            } else if ($modules_services[$id_module][$i]['whereamiused'][$k]['nature'] == "button"
                                && $_SESSION['user']['services'][$modules_services[$id_module][$i]['id']]
                                && ! in_array(
                                    $modules_services[$id_module][$i]['id'],
                                    $executedServices
                                )
                            ) {
                                array_push(
                                    $executedServices,
                                    $modules_services[$id_module][$i]['id']
                                );
                                $tmp = $modules_services[$id_module][$i]['whereamiused'][$k]['button_label'];
                                if ( !empty($tmp) && defined($tmp)
                                    && constant($tmp) <> NULL
                                ) {
                                    $tmp  = constant($tmp);
                                }

                                ?>
                                <input type="button" name="<?php functions::xecho($modules_services[$id_module][$i]['id']);?>" value="<?php functions::xecho($tmp);?>" onclick="window.open('<?php echo   $_SESSION['config']['businessappurl'].'index.php?display=true&module='.$id_module."&page=".$modules_services[$id_module][$i]['servicepage'];?>', '<?php functions::xecho($modules_services[$id_module][$i]['id']);?>','width=<?php functions::xecho($modules_services[$id_module][$i]['whereamiused'][$k]['width']);?>,height=<?php functions::xecho($modules_services[$id_module][$i]['whereamiused'][$k]['height']);?>,scrollbars=yes,resizable=yes' );" class="button" /><br/>
                                <?php
                                break;
                            } else if ($modules_services[$id_module][$i]['whereamiused'][$k]['nature'] == "include"
                                && $_SESSION['user']['services'][$modules_services[$id_module][$i]['id']]
                                && ! in_array(
                                    $modules_services[$id_module][$i]['id'],
                                    $executedServices
                                )
                            ) {
                                array_push(
                                    $executedServices,
                                    $modules_services[$id_module][$i]['id']
                                );

                                include 'modules' . DIRECTORY_SEPARATOR
                                    . $id_module . DIRECTORY_SEPARATOR
                                    . $modules_services[$id_module][$i]['servicepage'];
                                break;
                            }
                        }
                    }
                }
            }
        } else {
            $tab_view = array();
            if (isset($modules_services)) {
                foreach (array_keys($modules_services) as $value) {
                    if (isset($modules_services[$value])) {
                        for ($iService = 0; $iService < count($modules_services[$value]);
                            $iService ++
                        ){
                            if (isset($modules_services[$value][$iService])
                                && isset($modules_services[$value][$iService]['whereamiused'])
                                && count($modules_services[$value][$iService]['whereamiused']) > 0
                            ) {
                                for ($k = 0; $k < count(
                                    $modules_services[$value][$iService]['whereamiused']
                                ); $k ++
                                ) {
                                    if (isset(
                                        $modules_services[$value][$iService]['whereamiused'][$k]['page']
                                    ) && $modules_services[$value][$iService]['whereamiused'][$k]['page'] == $whereami
                                    ) {
                                        if ($modules_services[$value][$iService]['whereamiused'][$k]['nature'] == "frame"
                                            && $_SESSION['user']['services'][$modules_services[$value][$iService]['id']]
                                            && ($servicenature == "all" || $servicenature == "frame")
                                            && ! in_array(
                                                $modules_services[$value][$iService]['id'],
                                                $executedServices
                                            )
                                        ) {
                                            array_push(
                                                $executedServices,
                                                $modules_services[$value][$iService]['id']
                                            );

                                            if (isset(
                                                $modules_services[$value][$iService]['whereamiused'][$k]['frame_id']
                                            ) && ! empty(
                                                $modules_services[$value][$iService]['whereamiused'][$k]['frame_id']
                                            )
                                            ) {
                                                $name = 'name="'
                                                    . $modules_services[$value][$iService]['whereamiused'][$k]['frame_id'].'"';
                                            }
                                            if (isset(
                                                $modules_services[$value][$iService]['whereamiused'][$k]['frame_id']
                                            ) && ! empty(
                                                $modules_services[$value][$iService]['whereamiused'][$k]['frame_id']
                                            )
                                            ) {
                                                $iServiced = 'id="'
                                                    . $modules_services[$value][$iService]['whereamiused'][$k]['frame_id'].'"';
                                            }
                                            if (isset(
                                                $modules_services[$value][$iService]['whereamiused'][$k]['width']
                                            ) &&  strlen(
                                                $modules_services[$value][$iService]['whereamiused'][$k]['width']
                                            ) > 0
                                            ) {
                                                $width = 'width="'.$modules_services[$value][$iService]['whereamiused'][$k]['width'].'" ';
                                            }
                                            if (isset(
                                                $modules_services[$value][$iService]['whereamiused'][$k]['height']
                                            ) && strlen(
                                                $modules_services[$value][$iService]['whereamiused'][$k]['height']
                                            ) > 0
                                            ) {
                                                $height = 'height="'.$modules_services[$value][$iService]['whereamiused'][$k]['height'].'"';
                                            }
                                            if (isset(
                                                $modules_services[$value][$iService]['whereamiused'][$k]['border']
                                            ) && strlen(
                                                $modules_services[$value][$iService]['whereamiused'][$k]['border']
                                            ) > 0
                                            ) {
                                                $frameborder = 'frameborder="'.$modules_services[$value][$iService]['whereamiused'][$k]['border'].'" ';
                                            }
                                            if (isset(
                                                $modules_services[$value][$iService]['whereamiused'][$k]['scrolling']
                                            ) && ! empty(
                                                $modules_services[$value][$iService]['whereamiused'][$k]['scrolling']
                                            )
                                            ) {
                                                $scrolling = 'scrolling="'.$modules_services[$value][$iService]['whereamiused'][$k]['scrolling'].'"';
                                            }
                                            if (isset(
                                                $modules_services[$value][$iService]['whereamiused'][$k]['style']
                                            ) && ! empty(
                                                $modules_services[$value][$iService]['whereamiused'][$k]['style']
                                            )
                                            ) {
                                                $style = 'style="'.$modules_services[$value][$iService]['whereamiused'][$k]['style'].'"';
                                            }

                                            $iServiceframeStr = '<iframe src="'
                                                . $_SESSION['config']['businessappurl']
                                                . 'index.php?display=true&module='
                                                . $value . '&page='
                                                . $modules_services[$value][$iService]['servicepage']
                                                . '" ' . $name . ' ' . $iServiced . ' '
                                                . $width . ' ' . $height . ' '
                                                . $frameborder . ' ' . $scrolling
                                                . ' ' . $style . '></iframe>';

                                            return $iServiceframeStr;
                                        } else if($modules_services[$value][$iService]['whereamiused'][$k]['nature'] == "tab"
                                            && $_SESSION['user']['services'][$modules_services[$value][$iService]['id']]
                                            && ($servicenature == "tab")
                                            && ! in_array(
                                                $modules_services[$value][$iService]['id'],
                                                $executedServices
                                            )
                                        ) {
                                            array_push(
                                                $executedServices,
                                                $modules_services[$value][$iService]['id']
                                            );
                                            $arrLabel = $modules_services[$value][$iService]['whereamiused'][$k]['tab_label'];

                                            if ( !empty($arrLabel)
                                                && defined($arrLabel)
                                                && constant($arrLabel) <> NULL
                                            ) {
                                                $arrLabel  = constant($arrLabel);
                                            }
                                            $arrOrder = $modules_services[$value][$iService]['whereamiused'][$k]['tab_order'];

                                            $frameSrc = $_SESSION['config']['businessappurl'].'index.php?display=true&module='.$value."&page=".$modules_services[$value][$iService]['servicepage'];
                                            //$frameSrc = $_SESSION['urltomodules'].$value."/".$modules_services[$value][$iService]['servicepage'];
                                            $tab_view[$arrOrder]['tab_label'] = $arrLabel;
                                            $tab_view[$arrOrder]['frame_src'] = $frameSrc;
                                        } else if ($modules_services[$value][$iService]['whereamiused'][$k]['nature'] == "popup"
                                            && $_SESSION['user']['services'][$modules_services[$value][$iService]['id']]
                                            && ($servicenature == "all" || $servicenature == "popup")
                                            && ! in_array(
                                                $modules_services[$value][$iService]['id'],
                                                $executedServices
                                            )
                                        ) {
                                            array_push(
                                                $executedServices,
                                                $modules_services[$value][$iService]['id']
                                            );
                                            echo $modules_services[$value][$iService]['name'];
                                            ?>
                                            <br />
                                            <a href='<?php echo $_SESSION['config']['businessappurl'].'index.php?display=true&module='.$value."&page=".$modules_services[$value][$iService]['servicepage'];?>' target='_blank'><?php echo _ACCESS_TO_SERVICE;?></a><br /><br />
                                            <?php
                                        } else if ($modules_services[$value][$iService]['whereamiused'][$k]['nature'] == "button"
                                            && $_SESSION['user']['services'][$modules_services[$value][$iService]['id']]
                                            && ($servicenature == "all" || $servicenature == "button")
                                            && ! in_array(
                                                $modules_services[$value][$iService]['id'],
                                                $executedServices
                                            )
                                        ) {
                                            array_push(
                                                $executedServices,
                                                $modules_services[$value][$iService]['id']
                                            );
                                            $tmp = $modules_services[$value][$iService]['whereamiused'][$k]['button_label'];

                                            if ( !empty($tmp) && defined($tmp)
                                                && constant($tmp) <> NULL
                                            ) {
                                                $tmp  = constant($tmp);
                                            }
                                            ?>
                                            <input type="button" name="<?php functions::xecho($modules_services[$value][$iService]['id']);?>" value="<?php functions::xecho($tmp);?>" onclick="window.open('<?php echo  $_SESSION['config']['businessappurl'].'index.php?display=true&module='.$iServiced_module."&page=".$modules_services[$iServiced_module][$iService]['servicepage'];?>', '<?php functions::xecho($modules_services[$value][$iService]['id']);?>','width=<?php functions::xecho($modules_services[$value][$iService]['whereamiused'][$k]['width']);?>,height=<?php functions::xecho($modules_services[$value][$iService]['whereamiused'][$k]['height']);?>,scrollbars=yes,resizable=yes' );" class="button" /><br/>
                                            <?php
                                        } else if ( isset($_SESSION['user']['services'][$modules_services[$value][$iService]['id']])
                                            && $modules_services[$value][$iService]['whereamiused'][$k]['nature'] == "include"
                                            && $_SESSION['user']['services'][$modules_services[$value][$iService]['id']]
                                            && ($servicenature == "all" || $servicenature == "include")
                                            && ! in_array(
                                                $modules_services[$value][$iService]['id'],
                                                $executedServices
                                            )
                                        ) {
                                            array_push(
                                                $executedServices,
                                                $modules_services[$value][$iService]['id']
                                            );
                                            include 'modules' . DIRECTORY_SEPARATOR
                                                . $value . DIRECTORY_SEPARATOR
                                                . $modules_services[$value][$iService]['servicepage'];
                                        }
                                    }
                                }
                            }
                        }
                      }  //print_r($executedServices);
                }
            }
        //  $this->show_array($executedServices);
            if ($servicenature == "tab") {
                //print_r($tab_view);
                for ($u = 1; $u <= count($tab_view); $u++ ) {
                    if ($u == 1) {
                        ?>
                        <td  class="indexingtab">
                            <a href="javascript://" onclick="opentab('myframe', '<?php functions::xecho($tab_view[$u]['frame_src']);?>');">
                                <?php functions::xecho($tab_view[$u]['tab_label']);?>
                            </a>
                            <?php
                            $_SESSION['first_tab_to_open'] = $tab_view[$u]['frame_src'];
                            ?>
                        </td>
                        <?php
                    } else {
                        ?>
                        <td  class="indexingtab">
                            <a href="javascript://" onclick="opentab('myframe', '<?php functions::xecho($tab_view[$u]['frame_src']);?>');">
                                <?php functions::xecho($tab_view[$u]['tab_label']);?>
                            </a>
                        </td>
                        <?php
                    }
                }
            }
        }
    //  $this->show_array($executedServices);
    }

    /**
    * Executes the apps services in the page
    *
    * @param  $apps_services array  List of the application services
    * @param  $whereami string Page where to execute the service
    * @param  $servicenature string Nature of the service (by default, the function takes all the services natures)
    */
    public function execute_app_services($appServices, $whereami, $servicenature = "all")
    {
        $executedServices = array();
        for ($i = 0; $i < count($appServices); $i ++) {
            if (isset($appServices[$i]['whereamiused'])) {
                for ($k = 0; $k < count($appServices[$i]['whereamiused']); $k ++) {
                    if ($appServices[$i]['whereamiused'][$k]['page'] == $whereami) {
                        if ($appServices[$i]['whereamiused'][$k]['nature'] == "frame"
                            && $_SESSION['user']['services'][$appServices[$i]['id']]
                            && ($servicenature == "all" || $servicenature == "frame")
                            && ! in_array(
                                $appServices[$i]['id'],
                                $executedServices
                            )
                        ) {
                            array_push(
                                $executedServices,
                                $appServices[$i]['id']
                            );
                            ?>
                               <iframe src='<?php echo  $_SESSION['config']['businessappurl'].'index.php?display=true&page='.$appServices[$i]['servicepage'];?>' name="<?php  $appServices[$i]['id'];?>" id="<?php  $appServices[$i]['id'];?>" width='<?php functions::xecho($appServices[$i]['whereamiused'][$k]['width']);?>' height='<?php functions::xecho($appServices[$i]['whereamiused'][$k]['height']);?>' frameborder='<?php functions::xecho($appServices[$i]['whereamiused'][$k]['border']);?>' scrolling='<?php functions::xecho($appServices[$i]['whereamiused'][$k]['scrolling']);?>'></iframe>
                               <?php
                        } else if ($appServices[$i]['whereamiused'][$k]['nature'] == "popup"
                            && $_SESSION['user']['services'][$appServices[$i]['id']]
                            && ($servicenature == "all" || $servicenature == "popup")
                            && ! in_array(
                                $appServices[$i]['id'],
                                $executedServices
                            )
                        ) {
                            array_push(
                                $executedServices,
                                $appServices[$i]['id']
                            );
                            echo $appServices[$i]['name'];
                            ?>
                            <br />
                            <a href='<?php echo $_SESSION['config']['businessappurl'].'index.php?display=true&page='.$appServices[$i]['servicepage'];?>' target='_blank'><?php echo _ACCESS_TO_SERVICE;?></a><br /><br />
                             <?php
                        } else if ($appServices[$i]['whereamiused'][$k]['nature'] == "button"
                            && $_SESSION['user']['services'][$appServices[$i]['id']]
                            && ($servicenature == "all" || $servicenature == "button")
                            && ! in_array(
                                $appServices[$i]['id'], $executedServices
                            )
                       ) {
                            array_push(
                                $executedServices,
                                $appServices[$i]['id']
                            );
                            $tmp = $appServices[$i]['whereamiused'][$k]['button_label'];
                            if ( !empty($tmp) && defined($tmp)
                                && constant($tmp) <> NULL
                            ) {
                                $tmp  = constant($tmp);
                            }
                            ?>
                            <input type="button" name="<?php functions::xecho($appServices[$i]['id']);?>" value="<?php functions::xecho($tmp);?>" onclick="window.open('<?php echo  $_SESSION['config']['businessappurl'].'index.php?display=true&page='.$appServices[$i]['servicepage'];?>', '<?php functions::xecho($appServices[$i]['id']);?>','width=<?php functions::xecho($appServices[$i]['whereamiused'][$k]['width']);?>,height=<?php functions::xecho($appServices[$i]['whereamiused'][$k]['height']);?>,scrollbars=yes,resizable=yes' );" class="button" /><br/>
                            <?php
                        } else if ($appServices[$i]['whereamiused'][$k]['nature'] == "include"
                            && isset($_SESSION['user']['services'][$appServices[$i]['id']])
                            && $_SESSION['user']['services'][$appServices[$i]['id']]
                            && ($servicenature == "all" || $servicenature == "include")
                            && ! in_array(
                                $appServices[$i]['id'], $executedServices
                            )
                        ) {
                            array_push(
                                $executedServices, $appServices[$i]['id']
                            );
                            if (isset($_SESSION['custom_override_id'])
                                && ! empty($_SESSION['custom_override_id'])
                                && file_exists(
                                    $_SESSION['config']['corepath'] . 'custom'
                                    . DIRECTORY_SEPARATOR
                                    . $_SESSION['custom_override_id']
                                    . DIRECTORY_SEPARATOR . 'apps'
                                    . DIRECTORY_SEPARATOR
                                    . $_SESSION['config']['app_id']
                                    . DIRECTORY_SEPARATOR
                                    . $appServices[$i]['servicepage']
                                )
                            ) {
                                include $_SESSION['config']['corepath']
                                    . 'custom' . DIRECTORY_SEPARATOR
                                    . $_SESSION['custom_override_id']
                                    . DIRECTORY_SEPARATOR . 'apps'
                                    . DIRECTORY_SEPARATOR
                                    . $_SESSION['config']['app_id']
                                    . DIRECTORY_SEPARATOR
                                    . $appServices[$i]['servicepage'];
                            } else {
                               include 'apps' . DIRECTORY_SEPARATOR
                                . $_SESSION['config']['app_id']
                                . DIRECTORY_SEPARATOR
                                . $appServices[$i]['servicepage'];
                            }
                        }
                    }
                }
            }
        }
    }

    /**
    * Loads the html declaration and doctype
    */
    public function load_html()
    {
        /*<?xml version="1.0" encoding="UTF-8"?>*/
        ?>
        <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
        <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php functions::xecho($_SESSION['config']['lang']);?>" lang="<?php functions::xecho($_SESSION['config']['lang']);?>">
        <?php
    }

    /**
    * Loads the html header
    *
    * @param  $title string Title tag value (empty by default)
    */
    public function load_header( $title = '', $load_css = true, $load_js = true)
    {
        if(empty($title))
        {
             $title = $_SESSION['config']['applicationname'];
        }
        ?>
        <head>
            <title><?php functions::xecho($title);?></title>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
            <meta http-equiv="Content-Language" content="<?php functions::xecho($_SESSION['config']['lang']);?>" />
            <meta http-equiv="X-UA-Compatible" content="IE=edge" /> 
            <link rel="icon" type="image/png" href="<?php echo $_SESSION['config']['businessappurl'];?>static.php?filename=favicon.png"/>
            <link href="index.php?display&page=generate_search_xml" title="Maarch <?php 
                echo functions::xssafe($_SESSION['config']['applicationname']) . ' ' 
                    . _GLOBAL_SEARCH
                ;?>" type="application/opensearchdescription+xml" rel="search"/>
            <?php
            if($load_css)
            {
                $this->load_css();
            }
            if($load_js)
            {
                $this->load_js();
            }
            ?>
        </head>
        <?php
    }

    /**
    * Loads the modules and aplication css
    */
    private function load_css()
    {
        ?>
        <link rel="stylesheet" href="<?php echo $_SESSION['config']['businessappurl'] 
            . 'css/font-awesome/css/font-awesome.css';?>" media="screen" />
        <link rel="stylesheet" href="<?php echo $_SESSION['config']['businessappurl'] 
            . 'css/font-awesome/css/font-maarch.css';?>" media="screen" />
        <link rel="stylesheet" type="text/css" href="<?php echo $_SESSION['config']['businessappurl'].'merged_css.php';?>" media="screen" />
        <!--[if lt  IE 7.0]>  <link rel="stylesheet" type="text/css" href="<?php echo $_SESSION['config']['businessappurl'].'merged_css.php?ie';?>" media="screen" />  <![endif]-->
        <!--[if gte IE 7.0]>  <link rel="stylesheet" type="text/css" href="<?php echo $_SESSION['config']['businessappurl'].'merged_css.php?ie7';?>" media="screen" />  <![endif]-->
        <!--[if IE 8.0]>  <link rel="stylesheet" type="text/css" href="<?php echo $_SESSION['config']['businessappurl'].'merged_css.php?ie8';?>" media="screen" />  <![endif]-->
        <!--[if gte IE 9.0]>  <link rel="stylesheet" type="text/css" href="<?php echo $_SESSION['config']['businessappurl'].'merged_css.php?ie9';?>" media="screen" />  <![endif]-->
        <?php
    }

    /**
    * Loads the javascript files of the application and modules
    */
    public function load_js()
    {
        ?>
        <!--<script type="text/javascript" >
            var app_path = '<?php echo $_SESSION['config']['businessappurl'];?>static.php?filename=';
        </script>-->
        <script type="text/javascript" src="<?php echo $_SESSION['config']['businessappurl'];?>merged_js.php"></script>
        <?php

    }

    /**
    * Cleans the page variable and looks if she exists or not before including her
    *
    */
    public function insert_page() 
    {
        if (!isset($_SESSION['config']['app_id']) && $_SESSION['config']['app_id'] == '') {
            $_SESSION['config']['app_id'] = 'maarch_entreprise';
        }
        if(isset($_GET['amp;module']) && $_GET['amp;module'] <> "") {
            $_GET['module'] = $_GET['amp;module'];
            $_REQUEST['module'] = $_REQUEST['amp;module'];
        }
        if(isset($_GET['amp;baskets']) && $_GET['amp;baskets'] <> "") {
            $_GET['baskets'] = $_GET['amp;baskets'];
            $_REQUEST['baskets'] = $_REQUEST['amp;baskets'];
        }
        // Cleans the page variables and looks if she exists or not before including her
        if(isset($_GET['page']) && !empty($_GET['page'])) {
            // CVA 31 oct 2014 Security Local File Inclusion
             if($_GET['module']=='tags'){
                 $this->f_page = str_replace(
                     array('../', '..%2F'), 
                     array ('', ''), 
                     $_GET['page']
                 );
             } else {
                $this->f_page = str_replace(
                    array('../', '..%2F'), 
                    array ('', ''), 
                    $this->wash($_GET['page'],"file","","yes")
                );
             }
        } else {
            $this->loadDefaultPage();
            return true;
        }
        if(isset($_GET['module']) && $_GET['module'] <> "core") {
            // Page is defined in a module
            $found = false;
            //$this->show_array($_SESSION['maarchFilesWhiteList']['modules'][$_GET['module']]);
            for ($cptM=0;$cptM<count($_SESSION['maarchFilesWhiteList']['modules'][$_GET['module']]);$cptM++) {
                //echo $_SESSION['maarchFilesWhiteList']['modules'][$_GET['module']][$cptM] . '<br />';
                //echo 'modules'.DIRECTORY_SEPARATOR.$_GET['module'].DIRECTORY_SEPARATOR.$this->f_page.".php" . '<br />';
                if (
                    $_SESSION['maarchFilesWhiteList']['modules'][$_GET['module']][$cptM] 
                        == 'custom'.DIRECTORY_SEPARATOR.$_SESSION['custom_override_id'].DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR.$_GET['module'].DIRECTORY_SEPARATOR.$this->f_page.".php"
                ) {
                    require $_SESSION['maarchFilesWhiteList']['modules'][$_GET['module']][$cptM];
                    $found = true;
                    break;
                } elseif (
                    $_SESSION['maarchFilesWhiteList']['modules'][$_GET['module']][$cptM] 
                        == 'modules'.DIRECTORY_SEPARATOR.$_GET['module'].DIRECTORY_SEPARATOR.$this->f_page.".php"
                ) {
                    require $_SESSION['maarchFilesWhiteList']['modules'][$_GET['module']][$cptM];
                    $found = true;
                    break;
                }
            }
            if (!$found)  {
                $this->loadDefaultPage();
            }

            // if(file_exists($_SESSION['config']['corepath'].'custom'.DIRECTORY_SEPARATOR.$_SESSION['custom_override_id'].DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR.$_GET['module'].DIRECTORY_SEPARATOR.$this->f_page.".php")) {
            //     require($_SESSION['config']['corepath'].'custom'.DIRECTORY_SEPARATOR.$_SESSION['custom_override_id'].DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR.$_GET['module'].DIRECTORY_SEPARATOR.$this->f_page.".php");
            // } elseif(file_exists($_SESSION['config']['corepath'].'modules'.DIRECTORY_SEPARATOR.$_GET['module'].DIRECTORY_SEPARATOR.$this->f_page.".php")) {
            //     require('modules'.DIRECTORY_SEPARATOR.$_GET['module'].DIRECTORY_SEPARATOR.$this->f_page.".php");
            // } else {
            //     $this->loadDefaultPage();
            // }
        } elseif(isset($_GET['module']) && $_GET['module'] == "core") {
            // Page is defined the core
            $found = false;
            //$this->show_array($_SESSION['maarchFilesWhiteList']['core']);
            for ($cptM=0;$cptM<count($_SESSION['maarchFilesWhiteList']['core']);$cptM++) {
                //echo $_SESSION['maarchFilesWhiteList']['core'][$cptM] . '<br />';
                //echo 'core'.DIRECTORY_SEPARATOR.$this->f_page.".php" . '<br />';
                if (
                    $_SESSION['maarchFilesWhiteList']['core'][$cptM] 
                        == 'custom'.DIRECTORY_SEPARATOR.$_SESSION['custom_override_id'].DIRECTORY_SEPARATOR.'core'.DIRECTORY_SEPARATOR.$this->f_page.".php"
                ) {
                    require $_SESSION['maarchFilesWhiteList']['core'][$cptM];
                    $found = true;
                    break;
                } elseif (
                    $_SESSION['maarchFilesWhiteList']['core'][$cptM] 
                        == 'core'.DIRECTORY_SEPARATOR.$this->f_page.".php"
                ) {
                    require $_SESSION['maarchFilesWhiteList']['core'][$cptM];
                    $found = true;
                    break;
                }
            }
            if (!$found)  {
                $this->loadDefaultPage();
            }

            // if(file_exists($_SESSION['config']['corepath'].'custom'.DIRECTORY_SEPARATOR.$_SESSION['custom_override_id'].DIRECTORY_SEPARATOR.'core'.DIRECTORY_SEPARATOR.$this->f_page.".php")) {
            //     require($_SESSION['config']['corepath'].'custom'.DIRECTORY_SEPARATOR.$_SESSION['custom_override_id'].DIRECTORY_SEPARATOR.'core'.DIRECTORY_SEPARATOR.$this->f_page.".php");
            // } elseif(file_exists($_SESSION['config']['corepath'].'core'.DIRECTORY_SEPARATOR.$this->f_page.".php")) {
            //     require('core'.DIRECTORY_SEPARATOR.$this->f_page.".php");
            // } else {
            //     $this->loadDefaultPage();
            // }
        } elseif(isset($_GET['admin']) && !empty($_GET['admin'])) {
            if (
                !isset($_SESSION['user']['services']['admin'])
                && $_GET['page'] <> 'modify_user'
                && $_GET['page'] <> 'user_modif'
            ) {
               $this->loadDefaultPage();
            } else {
                $found = false;
                //$this->show_array($_SESSION['maarchFilesWhiteList']['apps']);
                for ($cptM=0;$cptM<count($_SESSION['maarchFilesWhiteList']['apps']);$cptM++) {
                    //echo $_SESSION['maarchFilesWhiteList']['apps'][$cptM] . '<br />';
                    //echo 'apps'.DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR."admin".DIRECTORY_SEPARATOR.trim($_GET['admin']).DIRECTORY_SEPARATOR.$this->f_page.".php" . '<br />';
                    if (
                        $_SESSION['maarchFilesWhiteList']['apps'][$cptM] 
                            == 'custom'.DIRECTORY_SEPARATOR.$_SESSION['custom_override_id'].DIRECTORY_SEPARATOR.'apps'.DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR."admin".DIRECTORY_SEPARATOR.trim($_GET['admin']).DIRECTORY_SEPARATOR.$this->f_page.".php"
                    ) {
                        require $_SESSION['maarchFilesWhiteList']['apps'][$cptM];
                        $found = true;
                        break;
                    } elseif (
                        $_SESSION['maarchFilesWhiteList']['apps'][$cptM] 
                            == 'apps'.DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR."admin".DIRECTORY_SEPARATOR.trim($_GET['admin']).DIRECTORY_SEPARATOR.$this->f_page.".php"
                    ) {
                        require $_SESSION['maarchFilesWhiteList']['apps'][$cptM];
                        $found = true;
                        break;
                    }
                }
                if (!$found)  {
                    $this->loadDefaultPage();
                }

                // Page is defined the admin directory of the application
                /*if(file_exists($_SESSION['config']['corepath'].'custom'.DIRECTORY_SEPARATOR.$_SESSION['custom_override_id'].DIRECTORY_SEPARATOR.'apps'.DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR."admin".DIRECTORY_SEPARATOR.trim($_GET['admin']).DIRECTORY_SEPARATOR.$this->f_page.".php")) {
                    require($_SESSION['config']['corepath'].'custom'.DIRECTORY_SEPARATOR.$_SESSION['custom_override_id'].DIRECTORY_SEPARATOR.'apps'.DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR."admin".DIRECTORY_SEPARATOR.trim($_GET['admin']).DIRECTORY_SEPARATOR.$this->f_page.".php");
                } elseif(file_exists($_SESSION['config']['corepath'].'apps'.DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR."admin".DIRECTORY_SEPARATOR.trim($_GET['admin']).DIRECTORY_SEPARATOR.$this->f_page.".php")) {
                    require('apps'.DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR."admin".DIRECTORY_SEPARATOR.trim($_GET['admin']).DIRECTORY_SEPARATOR.$this->f_page.".php");
                } else {
                    $this->loadDefaultPage();
                }*/
            }
        } elseif(isset($_GET['dir']) && !empty($_GET['dir'])) {
            // Page is defined in a dir directory of the application
            $found = false;
            //$this->show_array($_SESSION['maarchFilesWhiteList']['apps']);
            for ($cptM=0;$cptM<count($_SESSION['maarchFilesWhiteList']['apps']);$cptM++) {
                //echo $_SESSION['maarchFilesWhiteList']['apps'][$cptM] . '<br />';
                //echo 'apps'.DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR.trim($_GET['dir']).DIRECTORY_SEPARATOR.$this->f_page.".php" . '<br />';
                if (
                    $_SESSION['maarchFilesWhiteList']['apps'][$cptM] 
                        == 'custom'.DIRECTORY_SEPARATOR.$_SESSION['custom_override_id'].DIRECTORY_SEPARATOR.'apps'.DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR.trim($_GET['dir']).DIRECTORY_SEPARATOR.$this->f_page.".php"
                ) {
                    require $_SESSION['maarchFilesWhiteList']['apps'][$cptM];
                    $found = true;
                    break;
                } elseif (
                    $_SESSION['maarchFilesWhiteList']['apps'][$cptM] 
                        == 'apps'.DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR.trim($_GET['dir']).DIRECTORY_SEPARATOR.$this->f_page.".php"
                ) {
                    require $_SESSION['maarchFilesWhiteList']['apps'][$cptM];
                    $found = true;
                    break;
                }
            }
            if (!$found)  {
                $this->loadDefaultPage();
            }

            // if(file_exists($_SESSION['config']['corepath'].'custom'.DIRECTORY_SEPARATOR.$_SESSION['custom_override_id'].DIRECTORY_SEPARATOR.'apps'.DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR.trim($_GET['dir']).DIRECTORY_SEPARATOR.$this->f_page.".php")) {
            //     require($_SESSION['config']['corepath'].'custom'.DIRECTORY_SEPARATOR.$_SESSION['custom_override_id'].DIRECTORY_SEPARATOR.'apps'.DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR.trim($_GET['dir']).DIRECTORY_SEPARATOR.$this->f_page.".php");
            // } elseif(file_exists($_SESSION['config']['corepath'].'apps'.DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR.trim($_GET['dir']).DIRECTORY_SEPARATOR.$this->f_page.".php")) {
            //     require('apps'.DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR.trim($_GET['dir']).DIRECTORY_SEPARATOR.$this->f_page.".php");
            // } else {
            //     $this->loadDefaultPage();
            // }
        } else {
            // Page is defined in the application
            $found = false;
            //$this->show_array($_SESSION['maarchFilesWhiteList']['apps']);
            for ($cptM=0;$cptM<count($_SESSION['maarchFilesWhiteList']['apps']);$cptM++) {
                //echo $_SESSION['maarchFilesWhiteList']['apps'][$cptM] . '<br />';
                //echo 'apps'.DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR.$this->f_page.".php" . '<br />';
                //echo 'custom'.DIRECTORY_SEPARATOR.$_SESSION['custom_override_id'].DIRECTORY_SEPARATOR.'apps'.DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR.$this->f_page.".php" . '<br />';
                if (
                    $_SESSION['maarchFilesWhiteList']['apps'][$cptM] 
                        == 'custom'.DIRECTORY_SEPARATOR.$_SESSION['custom_override_id'].DIRECTORY_SEPARATOR.'apps'.DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR.$this->f_page.".php"
                ) {
                    require $_SESSION['maarchFilesWhiteList']['apps'][$cptM];
                    $found = true;
                    break;
                } elseif (
                    $_SESSION['maarchFilesWhiteList']['apps'][$cptM] 
                        == 'apps'.DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR.$this->f_page.".php"
                ) {
                    require $_SESSION['maarchFilesWhiteList']['apps'][$cptM];
                    $found = true;
                    break;
                } else {
                    require_once('apps'.DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_business_app_tools.php");
                    $app = new business_app_tools();
                    $path = $app->insert_app_page($this->f_page);
                    if (
                        $_SESSION['maarchFilesWhiteList']['apps'][$cptM] 
                            == 'custom'.DIRECTORY_SEPARATOR.$_SESSION['custom_override_id'].DIRECTORY_SEPARATOR.$path
                    ) {
                        require $_SESSION['maarchFilesWhiteList']['apps'][$cptM];
                        $found = true;
                        break;
                    } elseif (
                        $_SESSION['maarchFilesWhiteList']['apps'][$cptM] 
                            == $path
                    ) {
                        require $_SESSION['maarchFilesWhiteList']['apps'][$cptM];
                        $found = true;
                        break;
                    }
                }
            }
            if (!$found)  {
                $this->loadDefaultPage();
            }

            // if(file_exists($_SESSION['config']['corepath'].'custom'.DIRECTORY_SEPARATOR.$_SESSION['custom_override_id'].DIRECTORY_SEPARATOR.'apps'.DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR.$this->f_page.".php")) {
            //     require($_SESSION['config']['corepath'].'custom'.DIRECTORY_SEPARATOR.$_SESSION['custom_override_id'].DIRECTORY_SEPARATOR.'apps'.DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR.$this->f_page.".php");
            // } elseif(file_exists($_SESSION['config']['corepath'].'apps'.DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR.$this->f_page.".php")) {
            //     require('apps'.DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR.$this->f_page.".php");
            // } else {
            //     require_once('apps'.DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_business_app_tools.php");
            //     $app = new business_app_tools();
            //     $path = $app->insert_app_page($this->f_page);
            //     echo $path;exit;
            //     if(
            //         (!$path || empty($path)) 
            //         && !file_exists($_SESSION['config']['corepath'].'custom'.DIRECTORY_SEPARATOR.$_SESSION['custom_override_id'].DIRECTORY_SEPARATOR.$path) 
            //         && !file_exists($path)) {
            //         //require($_SESSION["config"]["defaultPage"].".php");
            //         $this->loadDefaultPage();
            //     } else {
            //         if (!file_exists($path)) {
            //             $this->loadDefaultPage();
            //         } else {
            //             require($path);
            //         }
            //     }
            // }
        }
        return true;
    }

    /**
    * Loads the default page
    */
    public function loadDefaultPage()
    {
        if(isset($_SESSION['target_page']) && trim($_SESSION['target_page']) <> "" && trim($_SESSION['target_module']) <> "")
        {
            $target = "page=".$_SESSION['target_page']."&module=".$_SESSION['target_module'];
        }
        elseif(isset($_SESSION['target_page']) &&  trim($_SESSION['target_page']) <> "" && trim($_SESSION['target_admin']) <> "")
        {
            $target = "page=".$_SESSION['target_page']."&admin=".$_SESSION['target_admin'];
        }
        elseif(isset($_SESSION['target_page']) &&  trim($_SESSION['target_page']) <> "" && trim($_SESSION['target_module']) == "" && trim($_SESSION['target_admin']) == "")
        {
            $target = "page=".$_SESSION['target_page'];
        }
        $_SESSION['target_page'] = "";
        $_SESSION['target_module'] = "";
        $_SESSION['target_admin'] = "";
        if(isset($target) && trim($target) <> "")
        {
            $tmpTab = array();
            $tmpTab = explode("&", $target);
            if(count($tmpTab) == 1)
            {
                $page = str_replace("page=", "", $tmpTab[0]);
                require('apps'.DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR.$page.".php");
            }
            elseif(count($tmpTab) == 2)
            {
                $tabPage = array();
                $tabModuleOrAdmin = array();
                $tabPage = explode("=", $tmpTab[0]);
                $tabModuleOrAdmin = explode("=", $tmpTab[1]);
                if($tabModuleOrAdmin[0] == "module")
                {
                    require('modules'.DIRECTORY_SEPARATOR.$tabModuleOrAdmin[1].DIRECTORY_SEPARATOR.$tabPage[1].".php");
                }
                else
                {
                    //admin case
                    if($tabPage[1] == "users" || $tabPage[1] == "groups" || $tabPage[1] == "admin_archi" || $tabPage[1] == "history" || $tabPage[1] == "history_batch"
                       || $tabPage[1] == "status" || $tabPage[1] == "action" || $tabPage[1] == "xml_param_services" || $tabPage[1] == "modify_user"
                      )
                    {
                        require('apps'.DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR."admin".DIRECTORY_SEPARATOR.$tabModuleOrAdmin[1].DIRECTORY_SEPARATOR.$tabPage[1].".php");
                    }
                    else
                    {
                        require('apps'.DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR."welcome.php");
                    }
                }
            }
            else
            {
                require('apps'.DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR."welcome.php");
            }
        }
        elseif(trim($_SESSION["config"]["defaultPage"]) <> "")
        {
            $tmpTab = array();
            $tmpTab = explode("&", $_SESSION["config"]["defaultPage"]);
            if(count($tmpTab) == 1)
            {
                $page = str_replace("page=", "", $tmpTab[0]);
                require('apps'.DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR.$page.".php");
            }
            elseif(count($tmpTab) == 2)
            {
                $tabPage = array();
                $tabModuleOrAdmin = array();
                $tabPage = explode("=", $tmpTab[0]);
                $tabModuleOrAdmin = explode("=", $tmpTab[1]);
                if($tabModuleOrAdmin[0] == "module")
                {
                    require('modules'.DIRECTORY_SEPARATOR.$tabModuleOrAdmin[1].DIRECTORY_SEPARATOR.$tabPage[1].".php");
                }
                else
                {
                    require('apps'.DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR."admin".DIRECTORY_SEPARATOR.$tabModuleOrAdmin[1].DIRECTORY_SEPARATOR.$tabPage[1].".php");
                }
            }
            else
            {
                require('apps'.DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR."welcome.php");
            }
        }
        else
        {
            require('apps'.DIRECTORY_SEPARATOR."welcome.php");
        }
    }

    /**
    * Loads the footer
    */
    public function load_footer()
    {
        echo _MEP_VERSION . ', <b>database release : ' . functions::xssafe($_SESSION['maarch_entreprise']['xml_versionbase']) . '</b> ';
        echo ' ' . _POWERED_BY . ' ' ;
        //. functions::xssafe($this->show_page_stat())
        
    }

    /**
    * Views Cookies informations, POST and SESSION variables if the mode debug is enabled in the application config
    */
    public function view_debug()
    {
        if($_SESSION['config']['debug'] == "true")
        {
            ?>
            <div id="debug">
                <p>&nbsp;</p>
                <p>&nbsp;</p>
                <p>&nbsp;</p>
                <h1 class="tit">DEBUG MODE</h1>
                    <h2 class="tit">Cookie</h2>
                    <?php
                    $this->show_array($_COOKIE);
                    ?>
                    <h2 class="tit">Session</h2>
                    <?php
                    $this->show_array($_SESSION);
                    ?>
                    <h2 class="tit">Request</h2>
                    <?php
                    $this->show_array($_REQUEST);
                    ?>
                    <h2 class="tit">Post</h2>
                    <?php
                    $this->show_array($_POST);
                    ?>
                    <h2 class="tit">Get</h2>
                    <?php
                    $this->show_array($_GET);
                    ?>
                    <h2 class="tit">SERVER</h2>
                    <?php
                    $this->show_array($_SERVER);
                    ?>
            </div>
            <?php
        }
    }

    /**
    * Tests if the current user is defined in the current session
    */
    public function test_user()
    {
        if(!isset($_SESSION['user']['UserId']))
        {
            if(trim($_SERVER['argv'][0]) <> "")
            {
                header("location: reopen.php?".$_SERVER['argv'][0]);
            }
            else
            {
                header("location: reopen.php");
            }
            exit;
        }
    }

    /**
    * Tests if the module is loaded
    *
    * @param  $module_id  string Module identifier the module to test
    * @return bool True if the module is found, False otherwise
    */
    public function is_module_loaded($module_id)
    {
        if(isset($_SESSION['modules_loaded'])) {
            if(is_array($_SESSION['modules_loaded'])) {
                foreach(array_keys($_SESSION['modules_loaded']) as $value) {
                    if($value == $module_id && $_SESSION['modules_loaded'][$value]['loaded'] == "true") {
                        return true;
                    }
                }
                return false;
            }
        }
    }


    /**
    * Retrieves the label corresponding to a service
    *
    * @param  $id_service string Service identifier
    * @return string Service Label or  _NO_LABEL_FOUND value
    */
    public function retrieve_label_service($id_service)
    {
        for($i=0;$i<count($_SESSION['enabled_services']);$i++)
        {
            if($_SESSION['enabled_services'][$i]['id'] == $id_service)
            {
                return $_SESSION['enabled_services'][$i]['label'];
            }
        }
        return _NO_LABEL_FOUND;
    }

    /**
    * Test if a service is enabled
    *
    * @param  $id_service string Service identifier
    * @return boolean true if enabled false if not
    */
    public function service_is_enabled($id_service)
    {
        for($i=0;$i<count($_SESSION['enabled_services']);$i++)
        {
            if($_SESSION['enabled_services'][$i]['id'] == $id_service)
            {
                return true;
            }
        }
        return false;
    }

    /**
    * Tests if the user has admin rights on the service
    *
    * @param  $id_service string Service identifier
    * @param  $module string Module identifier or "apps"
    * @param  $redirect bool If true the user is redirected in the index page, else no redirection (True by default)
    * @return bool or redirection depending on the $redirect value
    */
    public function test_admin($id_service, $module, $redirect = true )
    {

        // Application service
        if($module == "apps")
        {
            $system = false;
            if(isset($_SESSION['apps_services']))
            {
                for($i=0; $i< count($_SESSION['apps_services']); $i++)
                {
                    if($_SESSION['apps_services'][$i]['system_service'])
                    {
                        return true;
                    }
                }
            }
        }
        // Module service
        else
        {
            if(!$this->is_module_loaded($module))
            {
                if($redirect)
                {
                    $_SESSION['error'] = _SERVICE.' '._UNKNOWN.' : '.$id_service;
                    ?>
                    <script type="text/javascript">window.top.location.href='<?php echo $_SESSION['config']['businessappurl'];?>index.php';</script>
                    <?php
                    exit();
                }
                else
                {
                    return false;
                }
            }
            else
            {
                $system = false;
                for($i=0; $i< count($_SESSION['modules_services'][$module]); $i++)
                {
                    if($_SESSION['modules_services'][$module][$i]['id'] == $id_service)
                    {
                        if($_SESSION['modules_services'][$module][$i]['system_service'] == true)
                        {
                            return true;
                        }
                    }
                    else
                    {
                        break;
                    }
                }
            }
        }
        if(! isset($_SESSION['user']['services'][$id_service]) )
        {
            if($redirect)
            {
                $_SESSION['error'] = _ADMIN_SERVICE.' '._UNKNOWN;
            ?>
                <script type="text/javascript">window.top.location.href='<?php echo $_SESSION['config']['businessappurl'];?>index.php';</script>
                <?php
                exit();
            }
            else
            {
                return false;
            }
        }
        else
        {
            if( $_SESSION['user']['services'][$id_service] == false)
            {
                if($redirect)
                {
                    $label = $this->retrieve_label_service($id_service);
                    $_SESSION['error'] = _NO_RIGHTS_ON.' : '.$label;
                    ?>
                    <script type="text/javascript">window.top.location.href='<?php echo $_SESSION['config']['businessappurl'];?>index.php';</script>
                    <?php
                    exit();
                }
                else
                {
                    return false;
                }
            }
            else
            {
                return true;
            }
        }
    }

    /**
    * Tests if the user has right on the service
    *
    * @param  $id_service string Service identifier
    * @param  $module string Module identifier or "apps"
    * @param  $redirect bool If true the user is redirected in the index page, else no redirection (True by default)
    * @return bool or redirection depending on the $redirect value
    */
    public function test_service($id_service, $module, $redirect = true)
    {
        // Application service
        if($module == "apps")
        {
            $system = false;
            if(isset($_SESSION['apps_services']))
            {
                for($i=0; $i< count($_SESSION['apps_services']); $i++)
                {
                    if($_SESSION['apps_services'][$i]['system_service'])
                    {
                        return true;
                    }
                }
            }
        }
        // Module service
        else
        {
            if(!$this->is_module_loaded($module))
            {
                if($redirect)
                {
                    $_SESSION['error'] = _SERVICE.' '._UNKNOWN.' : '.$id_service;
                ?>
                    <script type="text/javascript">window.top.location.href='<?php echo $_SESSION['config']['businessappurl'];?>index.php';</script>
                    <?php
                    exit();

                }
                else
                {
                    return false;
                }
            }
            else
            {
                $system = false;
                for($i=0; $i< count($_SESSION['modules_services'][$module]); $i++)
                {
                    if($_SESSION['modules_services'][$module][$i]['id'] == $id_service)
                    {
                        if($_SESSION['modules_services'][$module][$i]['system_service'] == true)
                        {
                            return true;
                        }
                    }
                    else
                    {
                        break;
                    }
                }
            }
        }
        if(! isset($_SESSION['user']['services'][$id_service]) )
        {
            if($redirect)
            {
                $_SESSION['error'] = _SERVICE.' '._UNKNOWN.' : '.$id_service;
            ?>
                <script type="text/javascript">window.top.location.href='<?php echo $_SESSION['config']['businessappurl'];?>index.php';</script>
                <?php
                exit();

            }
            else
            {
                return false;
            }
        }
        else
        {
            if( $_SESSION['user']['services'][$id_service] == false)
            {
                if($redirect)
                {
                    $label = $this->retrieve_label_service($id_service);
                    $_SESSION['error'] = _NO_RIGHTS_ON.' : '.$label;
                    ?>
                    <script type="text/javascript" >window.top.location.href='<?php echo $_SESSION['config']['businessappurl'];?>index.php';</script>
                    <?php
                    exit();
                }
                else
                {
                    return false;
                }
            }
            else
            {
                return true;
            }
        }
    }

    /**
    * Gets the time of session expiration
    *
    * @return string time of session expiration
    */
    public function get_session_time_expire()
    {
        $time = 0;
        $ini_time = (ini_get('session.gc_maxlifetime') - 1) / 60;
        $maarch_time = $_SESSION['config']['cookietime'];
        
        if ($maarch_time <= $ini_time) {
            $time = $maarch_time;
        } else {
            $time = $ini_time;
        }
        return $time;
    }

    /**
    * Gets the path of an action
    *
    * @param  $id_service  string Action identifier
    * @return Action page or action identifier if not found
    */
    public function get_path_action_page($action_id)
    {
        $found = false;
        $ind = -1;
        for($i=0; $i< count($_SESSION['actions_pages']); $i++)
        {
            if($_SESSION['actions_pages'][$i]['ID'] == $action_id)
            {
                $found = true;
                $ind = $i;
                break;
            }
        }
        if(!$found)
        {
            return $action_id;
        }
        else
        {
            $path = $action_id;
            if(strtoupper($_SESSION['actions_pages'][$ind]['ORIGIN']) == "APPS")
            {
                $path = "apps".DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR."actions".DIRECTORY_SEPARATOR.$_SESSION['actions_pages'][$ind]['NAME'].".php";
            }
            elseif(strtoupper($_SESSION['actions_pages'][$ind]['ORIGIN']) == "MODULE")
            {
                $path = 'modules'.DIRECTORY_SEPARATOR.$_SESSION['actions_pages'][$ind]['MODULE'].DIRECTORY_SEPARATOR.$_SESSION['actions_pages'][$ind]['NAME'].".php";
            }
            return $path;
        }
    }

    public function is_action_defined($action_id)
    {
        require_once('core'.DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR.'class_db.php');
        if(empty($action_id))
        {
            return false;
        }
        $db = new Database();
        $stmt = $db->query("select origin from ".$_SESSION['tablename']['actions']." where id = ?", array($action_id));
        $res = $stmt->fetchObject();
        $origin = strtolower($res->origin);

        if($origin == 'apps' || $origin == 'core')
        {
            return true;
        }
        for($i=0; $i<count($_SESSION['modules']);$i++)
        {
            if(strtolower($_SESSION['modules'][$i]['moduleid']) == $origin)
            {
                return true;
            }
        }
        return false;
    }

    public function get_custom_id()
    {
        if(!file_exists($_SESSION['config']['corepath'].'custom'.DIRECTORY_SEPARATOR.'custom.xml'))
        {
            return '';
        }
        $linkToApps = false;
            $arr = explode('/', $_SERVER['SCRIPT_NAME']);
        for($cptArr=0;$cptArr<count($arr);$cptArr++) {
            if($arr[$cptArr] == "apps") {
                $linkToApps = true;
            }
        }
        if($linkToApps) {
            $path = $arr[count($arr)-4];
        } else {
            $path = $arr[count($arr)-2];
        }

        $xml = simplexml_load_file($_SESSION['config']['corepath'].'custom'.DIRECTORY_SEPARATOR.'custom.xml');
        //var_dump($xml);
        foreach($xml->custom as $custom)
        {
            if(trim($path) <> "" && isset( $custom->path) && $custom->path == trim($path))
            {
                return (string) $custom->custom_id;
            }
            if($custom->ip == $_SERVER['SERVER_ADDR'])
            {
                return (string) $custom->custom_id;
            }
            if($custom->external_domain == $_SERVER['HTTP_HOST'] xor $custom->domain == $_SERVER['HTTP_HOST'])
            {
                return (string) $custom->custom_id;
            }
        }
        return '';
    }

    /**
    * Detects if the user agent is a smartphone
    *
    */
    public function detectSmartphone()
    {
        $user_agent = $_SERVER['HTTP_USER_AGENT']; // get the user agent value - this should be cleaned to ensure no nefarious input gets executed
        $accept     = $_SERVER['HTTP_ACCEPT']; // get the content accept value - this should be cleaned to ensure no nefarious input gets executed
        return false
            || (preg_match('/ipad/i',$user_agent))
            || (preg_match('/ipod/i',$user_agent)||preg_match('/iphone/i',$user_agent))
            || (preg_match('/android/i',$user_agent))
            || (preg_match('/opera mini/i',$user_agent))
            || (preg_match('/blackberry/i',$user_agent))
            || (preg_match('/(pre\/|palm os|palm|hiptop|avantgo|plucker|xiino|blazer|elaine)/i',$user_agent))
            || (preg_match('/(iris|3g_t|windows ce|opera mobi|windows ce; smartphone;|windows ce; iemobile)/i',$user_agent))
            || (preg_match('/(mini 9.5|vx1000|lge |m800|e860|u940|ux840|compal|wireless| mobi|ahong|lg380|lgku|lgu900|lg210|lg47|lg920|lg840|lg370|sam-r|mg50|s55|g83|t66|vx400|mk99|d615|d763|el370|sl900|mp500|samu3|samu4|vx10|xda_|samu5|samu6|samu7|samu9|a615|b832|m881|s920|n210|s700|c-810|_h797|mob-x|sk16d|848b|mowser|s580|r800|471x|v120|rim8|c500foma:|160x|x160|480x|x640|t503|w839|i250|sprint|w398samr810|m5252|c7100|mt126|x225|s5330|s820|htil-g1|fly v71|s302|-x113|novarra|k610i|-three|8325rc|8352rc|sanyo|vx54|c888|nx250|n120|mtk |c5588|s710|t880|c5005|i;458x|p404i|s210|c5100|teleca|s940|c500|s590|foma|samsu|vx8|vx9|a1000|_mms|myx|a700|gu1100|bc831|e300|ems100|me701|me702m-three|sd588|s800|8325rc|ac831|mw200|brew |d88|htc\/|htc_touch|355x|m50|km100|d736|p-9521|telco|sl74|ktouch|m4u\/|me702|8325rc|kddi|phone|lg |sonyericsson|samsung|240x|x320|vx10|nokia|sony cmd|motorola|up.browser|up.link|mmp|symbian|smartphone|midp|wap|vodafone|o2|pocket|kindle|mobile|psp|treo)/i',$user_agent))
            || ((strpos($accept,'text/vnd.wap.wml')>0)||(strpos($accept,'application/vnd.wap.xhtml+xml')>0))
            || (isset($_SERVER['HTTP_X_WAP_PROFILE'])||isset($_SERVER['HTTP_PROFILE']))
            || (in_array(strtolower(substr($user_agent,0,4)),array('1207'=>'1207','3gso'=>'3gso','4thp'=>'4thp','501i'=>'501i','502i'=>'502i','503i'=>'503i','504i'=>'504i','505i'=>'505i','506i'=>'506i','6310'=>'6310','6590'=>'6590','770s'=>'770s','802s'=>'802s','a wa'=>'a wa','acer'=>'acer','acs-'=>'acs-','airn'=>'airn','alav'=>'alav','asus'=>'asus','attw'=>'attw','au-m'=>'au-m','aur '=>'aur ','aus '=>'aus ','abac'=>'abac','acoo'=>'acoo','aiko'=>'aiko','alco'=>'alco','alca'=>'alca','amoi'=>'amoi','anex'=>'anex','anny'=>'anny','anyw'=>'anyw','aptu'=>'aptu','arch'=>'arch','argo'=>'argo','bell'=>'bell','bird'=>'bird','bw-n'=>'bw-n','bw-u'=>'bw-u','beck'=>'beck','benq'=>'benq','bilb'=>'bilb','blac'=>'blac','c55/'=>'c55/','cdm-'=>'cdm-','chtm'=>'chtm','capi'=>'capi','cond'=>'cond','craw'=>'craw','dall'=>'dall','dbte'=>'dbte','dc-s'=>'dc-s','dica'=>'dica','ds-d'=>'ds-d','ds12'=>'ds12','dait'=>'dait','devi'=>'devi','dmob'=>'dmob','doco'=>'doco','dopo'=>'dopo','el49'=>'el49','erk0'=>'erk0','esl8'=>'esl8','ez40'=>'ez40','ez60'=>'ez60','ez70'=>'ez70','ezos'=>'ezos','ezze'=>'ezze','elai'=>'elai','emul'=>'emul','eric'=>'eric','ezwa'=>'ezwa','fake'=>'fake','fly-'=>'fly-','fly_'=>'fly_','g-mo'=>'g-mo','g1 u'=>'g1 u','g560'=>'g560','gf-5'=>'gf-5','grun'=>'grun','gene'=>'gene','go.w'=>'go.w','good'=>'good','grad'=>'grad','hcit'=>'hcit','hd-m'=>'hd-m','hd-p'=>'hd-p','hd-t'=>'hd-t','hei-'=>'hei-','hp i'=>'hp i','hpip'=>'hpip','hs-c'=>'hs-c','htc '=>'htc ','htc-'=>'htc-','htca'=>'htca','htcg'=>'htcg','htcp'=>'htcp','htcs'=>'htcs','htct'=>'htct','htc_'=>'htc_','haie'=>'haie','hita'=>'hita','huaw'=>'huaw','hutc'=>'hutc','i-20'=>'i-20','i-go'=>'i-go','i-ma'=>'i-ma','i230'=>'i230','iac'=>'iac','iac-'=>'iac-','iac/'=>'iac/','ig01'=>'ig01','im1k'=>'im1k','inno'=>'inno','iris'=>'iris','jata'=>'jata','java'=>'java','kddi'=>'kddi','kgt'=>'kgt','kgt/'=>'kgt/','kpt '=>'kpt ','kwc-'=>'kwc-','klon'=>'klon','lexi'=>'lexi','lg g'=>'lg g','lg-a'=>'lg-a','lg-b'=>'lg-b','lg-c'=>'lg-c','lg-d'=>'lg-d','lg-f'=>'lg-f','lg-g'=>'lg-g','lg-k'=>'lg-k','lg-l'=>'lg-l','lg-m'=>'lg-m','lg-o'=>'lg-o','lg-p'=>'lg-p','lg-s'=>'lg-s','lg-t'=>'lg-t','lg-u'=>'lg-u','lg-w'=>'lg-w','lg/k'=>'lg/k','lg/l'=>'lg/l','lg/u'=>'lg/u','lg50'=>'lg50','lg54'=>'lg54','lge-'=>'lge-','lge/'=>'lge/','lynx'=>'lynx','leno'=>'leno','m1-w'=>'m1-w','m3ga'=>'m3ga','m50/'=>'m50/','maui'=>'maui','mc01'=>'mc01','mc21'=>'mc21','mcca'=>'mcca','medi'=>'medi','meri'=>'meri','mio8'=>'mio8','mioa'=>'mioa','mo01'=>'mo01','mo02'=>'mo02','mode'=>'mode','modo'=>'modo','mot '=>'mot ','mot-'=>'mot-','mt50'=>'mt50','mtp1'=>'mtp1','mtv '=>'mtv ','mate'=>'mate','maxo'=>'maxo','merc'=>'merc','mits'=>'mits','mobi'=>'mobi','motv'=>'motv','mozz'=>'mozz','n100'=>'n100','n101'=>'n101','n102'=>'n102','n202'=>'n202','n203'=>'n203','n300'=>'n300','n302'=>'n302','n500'=>'n500','n502'=>'n502','n505'=>'n505','n700'=>'n700','n701'=>'n701','n710'=>'n710','nec-'=>'nec-','nem-'=>'nem-','newg'=>'newg','neon'=>'neon','netf'=>'netf','noki'=>'noki','nzph'=>'nzph','o2 x'=>'o2 x','o2-x'=>'o2-x','opwv'=>'opwv','owg1'=>'owg1','opti'=>'opti','oran'=>'oran','p800'=>'p800','pand'=>'pand','pg-1'=>'pg-1','pg-2'=>'pg-2','pg-3'=>'pg-3','pg-6'=>'pg-6','pg-8'=>'pg-8','pg-c'=>'pg-c','pg13'=>'pg13','phil'=>'phil','pn-2'=>'pn-2','pt-g'=>'pt-g','palm'=>'palm','pana'=>'pana','pire'=>'pire','pock'=>'pock','pose'=>'pose','psio'=>'psio','qa-a'=>'qa-a','qc-2'=>'qc-2','qc-3'=>'qc-3','qc-5'=>'qc-5','qc-7'=>'qc-7','qc07'=>'qc07','qc12'=>'qc12','qc21'=>'qc21','qc32'=>'qc32','qc60'=>'qc60','qci-'=>'qci-','qwap'=>'qwap','qtek'=>'qtek','r380'=>'r380','r600'=>'r600','raks'=>'raks','rim9'=>'rim9','rove'=>'rove','s55/'=>'s55/','sage'=>'sage','sams'=>'sams','sc01'=>'sc01','sch-'=>'sch-','scp-'=>'scp-','sdk/'=>'sdk/','se47'=>'se47','sec-'=>'sec-','sec0'=>'sec0','sec1'=>'sec1','semc'=>'semc','sgh-'=>'sgh-','shar'=>'shar','sie-'=>'sie-','sk-0'=>'sk-0','sl45'=>'sl45','slid'=>'slid','smb3'=>'smb3','smt5'=>'smt5','sp01'=>'sp01','sph-'=>'sph-','spv '=>'spv ','spv-'=>'spv-','sy01'=>'sy01','samm'=>'samm','sany'=>'sany','sava'=>'sava','scoo'=>'scoo','send'=>'send','siem'=>'siem','smar'=>'smar','smit'=>'smit','soft'=>'soft','sony'=>'sony','t-mo'=>'t-mo','t218'=>'t218','t250'=>'t250','t600'=>'t600','t610'=>'t610','t618'=>'t618','tcl-'=>'tcl-','tdg-'=>'tdg-','telm'=>'telm','tim-'=>'tim-','ts70'=>'ts70','tsm-'=>'tsm-','tsm3'=>'tsm3','tsm5'=>'tsm5','tx-9'=>'tx-9','tagt'=>'tagt','talk'=>'talk','teli'=>'teli','topl'=>'topl','hiba'=>'hiba','up.b'=>'up.b','upg1'=>'upg1','utst'=>'utst','v400'=>'v400','v750'=>'v750','veri'=>'veri','vk-v'=>'vk-v','vk40'=>'vk40','vk50'=>'vk50','vk52'=>'vk52','vk53'=>'vk53','vm40'=>'vm40','vx98'=>'vx98','virg'=>'virg','vite'=>'vite','voda'=>'voda','vulc'=>'vulc','w3c '=>'w3c ','w3c-'=>'w3c-','wapj'=>'wapj','wapp'=>'wapp','wapu'=>'wapu','wapm'=>'wapm','wig '=>'wig ','wapi'=>'wapi','wapr'=>'wapr','wapv'=>'wapv','wapy'=>'wapy','wapa'=>'wapa','waps'=>'waps','wapt'=>'wapt','winc'=>'winc','winw'=>'winw','wonu'=>'wonu','x700'=>'x700','xda2'=>'xda2','xdag'=>'xdag','yas-'=>'yas-','your'=>'your','zte-'=>'zte-','zeto'=>'zeto','acs-'=>'acs-','alav'=>'alav','alca'=>'alca','amoi'=>'amoi','aste'=>'aste','audi'=>'audi','avan'=>'avan','benq'=>'benq','bird'=>'bird','blac'=>'blac','blaz'=>'blaz','brew'=>'brew','brvw'=>'brvw','bumb'=>'bumb','ccwa'=>'ccwa','cell'=>'cell','cldc'=>'cldc','cmd-'=>'cmd-','dang'=>'dang','doco'=>'doco','eml2'=>'eml2','eric'=>'eric','fetc'=>'fetc','hipt'=>'hipt','http'=>'http','ibro'=>'ibro','idea'=>'idea','ikom'=>'ikom','inno'=>'inno','ipaq'=>'ipaq','jbro'=>'jbro','jemu'=>'jemu','java'=>'java','jigs'=>'jigs','kddi'=>'kddi','keji'=>'keji','kyoc'=>'kyoc','kyok'=>'kyok','leno'=>'leno','lg-c'=>'lg-c','lg-d'=>'lg-d','lg-g'=>'lg-g','lge-'=>'lge-','libw'=>'libw','m-cr'=>'m-cr','maui'=>'maui','maxo'=>'maxo','midp'=>'midp','mits'=>'mits','mmef'=>'mmef','mobi'=>'mobi','mot-'=>'mot-','moto'=>'moto','mwbp'=>'mwbp','mywa'=>'mywa','nec-'=>'nec-','newt'=>'newt','nok6'=>'nok6','noki'=>'noki','o2im'=>'o2im','opwv'=>'opwv','palm'=>'palm','pana'=>'pana','pant'=>'pant','pdxg'=>'pdxg','phil'=>'phil','play'=>'play','pluc'=>'pluc','port'=>'port','prox'=>'prox','qtek'=>'qtek','qwap'=>'qwap','rozo'=>'rozo','sage'=>'sage','sama'=>'sama','sams'=>'sams','sany'=>'sany','sch-'=>'sch-','sec-'=>'sec-','send'=>'send','seri'=>'seri','sgh-'=>'sgh-','shar'=>'shar','sie-'=>'sie-','siem'=>'siem','smal'=>'smal','smar'=>'smar','sony'=>'sony','sph-'=>'sph-','symb'=>'symb','t-mo'=>'t-mo','teli'=>'teli','tim-'=>'tim-','tosh'=>'tosh','treo'=>'treo','tsm-'=>'tsm-','upg1'=>'upg1','upsi'=>'upsi','vk-v'=>'vk-v','voda'=>'voda','vx52'=>'vx52','vx53'=>'vx53','vx60'=>'vx60','vx61'=>'vx61','vx70'=>'vx70','vx80'=>'vx80','vx81'=>'vx81','vx83'=>'vx83','vx85'=>'vx85','wap-'=>'wap-','wapa'=>'wapa','wapi'=>'wapi','wapp'=>'wapp','wapr'=>'wapr','webc'=>'webc','whit'=>'whit','winw'=>'winw','wmlb'=>'wmlb','xda-'=>'xda-',)))
        ;
    }

    /**
    * Loads the html header for smartphone
    *
    * @param  $title string Title tag value (empty by default)
    */
    public function loadSmartphoneHeader($title = '', $load_css = true, $load_js = true)
    {
        if(empty($title)) {
             $title = $_SESSION['config']['applicationname'];
        }
        ?>
        <head>
            <title><?php functions::xecho($title);?></title>
            <meta name="apple-mobile-web-app-capable" content="yes">
            <meta name="apple-touch-fullscreen" content="yes">
            <link rel="apple-touch-icon" href="img/board.png">
            <link rel="apple-touch-icon-precomposed" href="img/board.png">
            <?php
            if($load_css) {
                $this->loadSmartphoneCss();
            }
            if($load_js) {
                //$this->load_js();
                ?>
                <script type="application/javascript" src="<?php echo $_SESSION['config']['businessappurl'];?>smartphone/js/maarch_functions.js"></script>
                <script type="application/x-javascript" src="<?php echo $_SESSION['config']['businessappurl'];?>smartphone/js/iui/iui.js"></script>
                <script type="text/javascript" src="<?php echo $_SESSION['config']['businessappurl'];?>smartphone/js/iscroll.js?v3.7.1"></script>
                <script type="text/javascript" src="<?php echo $_SESSION['config']['businessappurl'];?>js/prototype.js"></script>
                <script type="text/javascript" src="<?php echo $_SESSION['config']['businessappurl'];?>js/scriptaculous.js"></script>
                <?php
            }
            ?>
        </head>
        <?php
    }

    /**
    * Loads the smartphone css
    */
    private function loadSmartphoneCss()
    {
        ?>
        <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;"/>
        <!--<link rel="stylesheet" type="text/css" href="<?php echo $_SESSION['config']['businessappurl'].'smartphone/css/style.css';?>" media="screen" />-->
        <link rel="stylesheet" type="text/css" href="<?php echo $_SESSION['config']['businessappurl'].'smartphone/css/iui-panel-list.css';?>" media="screen" />
        <link rel="stylesheet" type="text/css" href="<?php echo $_SESSION['config']['businessappurl'].'smartphone/js/iui/iui.css';?>" media="screen" />
        <link rel="stylesheet" type="text/css" href="<?php echo $_SESSION['config']['businessappurl'].'smartphone/js/iui/t/maarch/maarch-theme.css';?>" media="screen" />
        <?php
    }
}
