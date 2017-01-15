<?php
/*
*    Copyright 2008-2015 Maarch
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
* @brief   Contains all the functions to manage the users groups security 
* and connexion to the application
*
* @file
* @author Claire Figueras <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup core
*/

/**
* @brief   contains all the functions to manage the users groups security 
* through session variables
*
*<ul>
*  <li>Management of application connexion</li>
*  <li>Management of user rigths</li>
*</ul>
* @ingroup core
*/

//Requires to launch history functions
require_once 'core/class/class_db_pdo.php';
require_once 'core/class/class_history.php';
require_once 'core/class/SecurityControler.php';
require_once 'core/class/class_core_tools.php';
require_once 'core/where_targets.php';
require_once 'core/class/users_controler.php';
if (isset($_SESSION['config']['app_id'])) {
    require_once 'apps/' . $_SESSION['config']['app_id']
        . '/class/class_business_app_tools.php';
}
require_once 'core/class/usergroups_controler.php';
require_once 'core/class/ServiceControler.php';

//require_once('lib/FirePHP/Init.php');

class security extends Database
{
    /**
    * Gets the indice of the collection in the  $_SESSION['collections'] array
    *
    * @param  $coll_id string  Collection identifier
    * @return integer Indice of the collection in the $_SESSION['collections'] or -1 if not found
    */
    public function get_ind_collection($coll_id)
    {
        for($i=0;$i< count($_SESSION['collections']); $i++)
        {
            if(trim($_SESSION['collections'][$i]['id']) == trim($coll_id))
            {
                return $i;
            }
        }
        return -1;
    }

    /**
    * Logs a user
    *
    * @param  $s_login  string User login
    * @param  $pass string User password
    */
    public function login($s_login,$pass, $method = false, $ra_code=false)
    {
        $array = array();
        $error = '';
        $uc = new users_controler();

        $s_login = str_replace('\'', '', $s_login);
        $s_login = str_replace('=', '', $s_login);
        $s_login = str_replace('"', '', $s_login);
        $s_login = str_replace('*', '', $s_login);
        $s_login = str_replace(';', '', $s_login);
        $s_login = str_replace('--', '', $s_login);
        $s_login = str_replace(',', '', $s_login);
        $s_login = str_replace('$', '', $s_login);
        $s_login = str_replace('>', '', $s_login);
        $s_login = str_replace('<', '', $s_login);

        $database = new Database();
        // #TODO : Not usefull anymore, loginmode field is always in users table
        //Compatibility test, if loginmode column doesn't exists, Maarch can't crash
        if ($this->test_column($_SESSION['tablename']['users'], 'loginmode')) {
            // #TODO : do evolution of the loginmethod in sql query
            if ($method == 'activex') {
                $comp = " and STATUS <> 'DEL' and loginmode = 'activex'";
            } else if ($method == 'ldap') {
                $comp =" and STATUS <> 'DEL'";
            } else {
                if ($ra_code <> false) {
                    $comp = " and password = :password and "
                        . "ra_code = :ra_code and ra_expiration_date >= :ra_expiration_date "
                        . "and status <> :status "
                        . "and (loginmode = :loginmode1 or loginmode = :loginmode2)";
                    $params = array(
                        'password' => $pass, 
                        'ra_code' => $this->getPasswordHash($ra_code),
                        'ra_expiration_date' => date('Y-m-d 00:00:00'),
                        'status' => 'DEL',
                        'loginmode1' => 'standard',
                        'loginmode2' => 'sso',
                    );
                } else {
                    $comp = " and password = :password and STATUS <> 'DEL' "
                          . "and (loginmode = 'standard' or loginmode  = 'sso')";
                    $params = array('password' => $pass);
                }
            }
        } else {
            $comp = " and password = :password and STATUS <> 'DEL'";
            $params = array('password' => $pass);
        }
        $user = $uc->getWithComp($s_login, $comp, $params);

        if (isset($user)) {
            if ($user->__get('enabled') == 'Y') {
                $ugc = new usergroups_controler();
                $sec_controler = new SecurityControler();
                $serv_controler = new ServiceControler();
                if (isset($_SESSION['modules_loaded']['visa'])) {
                    if ($user->__get('signature_path') <> '' 
                        && $user->__get('signature_file_name') <> '' 
                    ) {
                        $_SESSION['user']['signature_path'] = $user->__get('signature_path');
                        $_SESSION['user']['signature_file_name'] = $user->__get('signature_file_name');
                        $db = new Database();
                        $query = "select path_template from " 
                            . _DOCSERVERS_TABLE_NAME 
                            . " where docserver_id = 'TEMPLATES'";
                        $stmt = $db->query($query);
                        $resDs = $stmt->fetchObject();
                        $pathToDs = $resDs->path_template;
                        $_SESSION['user']['pathToSignature'] = $pathToDs . str_replace(
                                "#", 
                                DIRECTORY_SEPARATOR, 
                                $_SESSION['user']['signature_path']
                            )
                            . $_SESSION['user']['signature_file_name'];
                    }
                }
                $array = array(
                    'change_pass' => $user->__get('change_password'),
                    'UserId'      => $user->__get('user_id'),
                    'FirstName'   => $user->__get('firstname'),
                    'LastName'    => $user->__get('lastname'),
                    'Initials'    => $user->__get('initials'),
                    'Phone'       => $user->__get('phone'),
                    'Mail'        => $user->__get('mail'),
                    'department' => $user->__get('department'),
                    'thumbprint' => $user->__get('thumbprint'),
                    'signature_path' => $user->__get('signature_path'),
                    'signature_file_name' => $user->__get('signature_file_name'),
                    'pathToSignature' => $_SESSION['user']['pathToSignature'],
                    'Status' => $user->__get('status'),
                    'cookie_date' => $user->__get('cookie_date'),
                );
                // $_SESSION['error'] =  '';
                /*setcookie(
                    'maarch', 'UserId=' . $array['UserId'] . '&key='
                    . $user->__get('cookie_key'), time() - 3600000,
                    0, 0, $_SERVER["HTTPS"], 1
                );*/
                $key = md5(
                    time() . '%' . $array['FirstName'] . '%' . $array['UserId']
                    . '%' . $array['UserId'] . '%' . date('dmYHmi') . '%'
                );
                $user->__set('cookie_key', $key);
                if ($_SESSION['config']['databasetype'] == 'ORACLE') {
                    $user->__set('cookie_date', 'SYSDATE');
                } else {
                    $user->__set(
                        'cookie_date', date('Y-m-d') . ' ' . date('H:m:i')
                    );
                }
                // #TODO : usefull ?
                $uc->save($user, 'up');
                /*setcookie(
                    'maarch', 'UserId=' . $array['UserId'] . '&key='
                    . $key, time() + ($_SESSION['config']['cookietime'] * 1000),
                    0, 0, $_SERVER["HTTPS"], 1
                );*/
                $array['primarygroup'] = $ugc ->getPrimaryGroup(
                    $array['UserId']
                );
                $tmp = $sec_controler->load_security(
                    $array['UserId']
                );
                $array['collections'] = $tmp['collections'];
                $array['security'] = $tmp['security'];
                $serv_controler->loadEnabledServices();
                $business_app_tools = new business_app_tools();
                $core_tools = new core_tools();
                $business_app_tools->load_app_var_session($array);
                $core_tools->load_var_session($_SESSION['modules'], $array);
                
                /************Temporary fix*************/ 
                // #TODO : revoir les functions load_var_session dans class_modules_tools pour ne plus charger en session les infos
                if (isset($_SESSION['user']['baskets'])) {
                    $array['baskets'] = $_SESSION['user']['baskets'];
                }
                if (isset($_SESSION['user']['entities'])) {
                    $array['entities'] = $_SESSION['user']['entities'];
                }
                if (isset($_SESSION['user']['primaryentity'])) {
                    $array['primaryentity'] = $_SESSION['user']['primaryentity'];
                }

                if (isset($_SESSION['user']['redirect_groupbasket'])) {
                    $array['redirect_groupbasket'] = $_SESSION['user']['redirect_groupbasket'];
                }
                /*************************************/
                $array['services'] = $serv_controler->loadUserServices(
                    $array['UserId']
                );
                
                if ($_SESSION['history']['userlogin'] == 'true') {
                    //add new instance in history table for the user's connexion
                    $hist = new history();
                    $ip = $_SERVER['REMOTE_ADDR'];
                    $navigateur = addslashes($_SERVER['HTTP_USER_AGENT']);
                    $_SESSION['user']['UserId'] = $s_login;
                    $_SESSION['user']['department'] = $array['department'];
					$_SESSION['user']['thumbprint'] = $array['thumbprint'];
                    $_SESSION['user']['primarygroup'] = $array['primarygroup'];
                    $hist->add(
                        $_SESSION['tablename']['users'],
                        $s_login,
                        'LOGIN','userlogin',
                        _LOGIN_HISTORY . ' '. $s_login . ' IP : ' . $ip,
                        $_SESSION['config']['databasetype']
                    );
                }

                if ($array['change_pass'] == 'Y') {
                    return array(
                        'user'  => $array,
                        'error' => $error,
                        'url'   => 'index.php?display=true&page=change_pass'
                    );
                }else if (isset($_SESSION['requestUri'])
                    && trim($_SESSION['requestUri']) <> ''
                    && ! preg_match('/page=login/', $_SESSION['requestUri'])) {
                    return array(
                        'user'  => $array,
                        'error' => $error,
                        'url'   => 'index.php?' . $_SESSION['requestUri']
                    );
                } else {
                    return array(
                        'user'  => $array,
                        'error' => $error,
                        'url'   => 'index.php'
                    );
                }
            } else {
                $error = _SUSPENDED_ACCOUNT . '. ' . _MORE_INFOS
                    . " <a href=\"mailto:" . $_SESSION['config']['adminmail']
                    . "\">" . $_SESSION['config']['adminname'] . "</a>";
                return array(
                    'user'  => $array,
                    'error' => $error,
                    'url'   => 'index.php'
                );
            }
        } else {
            $error = _BAD_LOGIN_OR_PSW;
            return array(
                'user'  => $array,
                'error' => $error,
                'url'   => 'index.php?display=true&page=login'
            );
        }
    }

    /**
    * Reopens a session with the user's cookie
    *
    * @param  $s_UserId  string User identifier
    * @param  $s_key string Cookie key
    */
    public function reopen($s_UserId,$s_key)
    {
        $comp = " and cookie_key = '".$s_key."' and STATUS <> 'DEL'";
        $uc = new users_controler();
        $user = users_controler::get($s_login, $comp);
        if(isset($user))
        {
            if($user->__get('enabled')  == "Y")
            {
                $serv_controler = new ServiceControler();
                $_SESSION['user']['change_pass'] = $user->__get('change_password');
                $_SESSION['user']['UserId'] = $user->__get('user_id');
                $_SESSION['user']['FirstName'] = $user->__get('firstname');
                $_SESSION['user']['LastName'] = $user->__get('lastname');
                $_SESSION['user']['Phone'] = $user->__get('phone');
                $_SESSION['user']['Mail'] = $user->__get('mail');
                $_SESSION['user']['department'] = $user->__get('department');
                $_SESSION['user']['thumbprint'] = $user->__get('thumbprint');
                if (isset($_SESSION['modules_loaded']['visa'])) {
                    if ($user->__get('signature_path') <> '' 
                        && $user->__get('signature_file_name') <> '' 
                    ) {
                        $_SESSION['user']['signature_path'] = $user->__get('signature_path');
                        $_SESSION['user']['signature_file_name'] = $user->__get('signature_file_name');
                        $db = new Database();
                        $query = "select path_template from " 
                            . _DOCSERVERS_TABLE_NAME 
                            . " where docserver_id = 'TEMPLATES'";
                        $stmt = $db->query($query);
                        $resDs = $stmt->fetchObject();
                        $pathToDs = $resDs->path_template;
                        $_SESSION['user']['pathToSignature'] = $pathToDs . str_replace(
                                "#", 
                                DIRECTORY_SEPARATOR, 
                                $_SESSION['user']['signature_path']
                            )
                            . $_SESSION['user']['signature_file_name'];
                    }
                }

                $_SESSION['error'] =  "";
                /*setcookie("maarch", "UserId=".$_SESSION['user']['UserId']."&key=".$line->cookie_key,time()-3600000, 0, 0, $_SERVER["HTTPS"], 1);*/
                $key = md5(time()."%".$_SESSION['user']['FirstName']."%".$_SESSION['user']['UserId']."%".$_SESSION['user']['UserId']."%".date("dmYHmi")."%");

                $user->__set('cookie_key', $key);
                if ($_SESSION['config']['databasetype'] == "ORACLE")
                    $user->__set('cookie_date', 'SYSDATE');
                else
                    $user->__set('cookie_date',date("Y-m-d")." ".date("H:m:i"));

                $uc->save($user, 'up');
                /*setcookie("maarch", "UserId=".$_SESSION['user']['UserId']."&key=".$key,time()+($_SESSION['config']['cookietime']*60), 0, 0, $_SERVER["HTTPS"], 1);*/

                $_SESSION['user']['primarygroup'] =  $ugc->getPrimaryGroup($_SESSION['user']['UserId']);
                $sec_controler = new SecurityControler();
                $tmp = $sec_controler->load_security($_SESSION['user']['UserId']);
                $_SESSION['user']['collections'] = $tmp['collections'];
                $_SESSION['user']['security'] = $tmp['security'];
                $serv_controler->loadEnabledServices();

                $business_app_tools = new business_app_tools();
                $core_tools = new core_tools();
                $business_app_tools->load_app_var_session($array);
                $core_tools->load_var_session($_SESSION['modules'], $array);

                $_SESSION['user']['services'] = $serv_controler->loadUserServices($_SESSION['user']['UserId']);
                $core_tools->load_menu($_SESSION['modules']);
/*
                if($_SESSION['history']['userlogin'] == "true")
                {
                    //add new instance in history table for the user's connexion
                    $hist = new history();
                    $ip = $_SERVER['REMOTE_ADDR'];
                    $navigateur = addslashes($_SERVER['HTTP_USER_AGENT']);

                    $hist->add($_SESSION['tablename']['users'],$_SESSION['user']['UserId'],"LOGIN","IP : ".$ip.", BROWSER : ".$navigateur , $_SESSION['config']['databasetype']);
                }
*/
                if($_SESSION['user']['change_pass'] == 'Y')
                {
                    header("location: ".$_SESSION['config']['businessappurl']."index.php?display=true&page=change_pass");
                    exit();
                }
                /*if($_SESSION['origin'] == "scan")
                {
                    header("location: ../../modules/indexing_searching/index_file.php");
                    exit();
                }
                elseif($_SESSION['origin'] == "files")
                {
                    header("location: ../../modules/indexing_searching/index_file.php");
                    exit();
                }*/
                else
                {
                    header("location: ".$_SESSION['config']['businessappurl']."index.php");
                    exit();
                }
            }
            else
            {
                $_SESSION['error'] = _SUSPENDED_ACCOUNT;
                header("location: ".$_SESSION['config']['businessappurl']."index.php");
                exit();
            }
        }
        else
        {
            $_SESSION['error'] = _ERROR;
            header("location: ".$_SESSION['config']['businessappurl']."index.php?display=true&page=login");
            exit();
        }
    }

    /******************* COLLECTION MANAGEMENT FUNCTIONS *******************/

    /**
    * Returns all collections where we can insert new documents (with tables)
    *
    * @return array Collections where inserts are allowed
    */
    public function retrieve_insert_collections()
    {
        $arr = array();
        for($i=0; $i<count($_SESSION['collections']);$i++)
        {
            if(isset($_SESSION['collections'][$i]['table']) && !empty($_SESSION['collections'][$i]['table']))
            {
                array_push($arr, $_SESSION['collections'][$i]);
            }
        }
        return $arr;
    }

    /**
     *
     * @param  $textToHash
     * @return string hashedText
     */
    public function getPasswordHash($textToHash)
    {
        return hash('sha512', $textToHash);
    }

    /**
    * Returns a script related to a collection
    *
    * @param  $coll_id  string Collection identifier
    * @param  $script_name  string Script name "script_add", "script_search", "script_search_result", "script_details"
    * @return string Script name or empty string if not found
    */
    public function get_script_from_coll($coll_id, $script_name)
    {
        for($i=0; $i < count($_SESSION['collections']);$i++)
        {
            if(trim($_SESSION['collections'][$i]['id']) == trim($coll_id))
            {
                return trim($_SESSION['collections'][$i][$script_name]);
            }
        }
        return '';
    }

    /**
    * Returns the collection identifier from a table
    *
    * @param  $table  string Tablename
    * @return string Collection identifier or empty string if not found
    */
    public function retrieve_coll_id_from_table($table)
    {
        for ($i=0;$i<count($_SESSION['collections']);$i++) {
            if (
                $_SESSION['collections'][$i]['table'] == $table
                || $_SESSION['collections'][$i]['version_table'] == $table
            ) {
                return $_SESSION['collections'][$i]['id'];
            }
        }
        return '';
    }
    
    /**
    * Returns the collection version table from a collId
    *
    * @param  $collId string collection ID
    * @return string version table or empty string if not found
    */
    public function retrieve_version_table_from_coll_id($collId)
    {
        for ($i=0;$i<count($_SESSION['collections']);$i++) {
            if ($_SESSION['collections'][$i]['id'] == $collId) {
                return $_SESSION['collections'][$i]['version_table'];
            }
        }
        return '';
    }
    
    /**
    * Returns the collection extension table from a collId
    *
    * @param  $collId string collection ID
    * @return string version table or empty string if not found
    */
    public function retrieve_extension_table_from_coll_id($collId)
    {
        for ($i=0;$i<count($_SESSION['collections']);$i++) {
            if ($_SESSION['collections'][$i]['id'] == $collId) {
                return $_SESSION['collections'][$i]['extensions'][0];
            }
        }
        return '';
    }
    
    /**
    * Returns the adr table from a table
    *
    * @param  $table string Tablename
    * @return string adr table or empty string if not found
    */
    public function retrieve_adr_table_from_table($table)
    {
        for ($i=0;$i<count($_SESSION['collections']);$i++) {
            if ($_SESSION['collections'][$i]['table'] == $table) {
                return $_SESSION['collections'][$i]['adr'];
            }
        }
        return '';
    }

    /**
    * Returns the collection table from a view
    *
    * @param  $view string View
    * @return string Collection table or empty string if not found
    */
    public function retrieve_coll_table_from_view($view)
    {
        for($i=0; $i<count($_SESSION['collections']);$i++)
        {
            if($_SESSION['collections'][$i]['view'] == $view)
            {
                return $_SESSION['collections'][$i]['table'];
            }
        }
        return '';
    }

    /**
    * Returns the collection identifier from a view
    *
    * @param  $view string View
    * @return string Collection identifier or empty string if not found
    */
    public function retrieve_coll_id_from_view($view)
    {
        for($i=0; $i<count($_SESSION['collections']);$i++)
        {
            if($_SESSION['collections'][$i]['view'] == $view)
            {
                return $_SESSION['collections'][$i]['id'];
            }
        }
        return '';
    }


    /**
    * Returns the view of a collection from the collection identifier
    *
    * @param string $coll_id  Collection identifier
    * @return string View name or empty string if not found
    */
    public function retrieve_view_from_coll_id($coll_id)
    {
        for($i=0; $i<count($_SESSION['collections']);$i++)
        {
            if($_SESSION['collections'][$i]['id'] == $coll_id)
            {
                return $_SESSION['collections'][$i]['view'];
            }
        }
        return '';
    }

    /**
    * Returns the view of a collection from the table of the collection
    *
    * @param string $table  Tablename
    * @return string View name or empty string if not found
    */
    public function retrieve_view_from_table($table)
    {
        for($i=0; $i<count($_SESSION['collections']);$i++)
        {
            if($_SESSION['collections'][$i]['table'] == $table)
            {
                return $_SESSION['collections'][$i]['view'];
            }
        }
        return '';
    }

    /**
    * Returns the table of the collection from the collection identifier
    *
    * @param string $coll_id  Collection identifier
    * @return string Table name or empty string if not found
    */
    public function retrieve_table_from_coll($coll_id)
    {
        for($i=0; $i<count($_SESSION['collections']);$i++)
        {
            if($_SESSION['collections'][$i]['id'] == $coll_id)
            {
                return $_SESSION['collections'][$i]['table'];
            }
        }
        return '';
    }
    
    /**
    * Returns the adr table of the collection from the collection identifier
    *
    * @param string $collId  Collection identifier
    * @return string adr table name or empty string if not found
    */
    public function retrieveAdrFromColl($collId)
    {
        for ($i = 0;$i < count($_SESSION['collections']);$i++) {
            if ($_SESSION['collections'][$i]['id'] == $collId) {
                return $_SESSION['collections'][$i]['adr'];
            }
        }
        return '';
    }

    /**
    * Returns the table of the collection from the view of the collection
    *
    * @param string $view  View
    * @return string Table name or empty string if not found
    */
    public function retrieve_table_from_view($view)
    {
        for($i=0; $i<count($_SESSION['collections']);$i++)
        {
            if($_SESSION['collections'][$i]['view'] == $view)
            {
                return $_SESSION['collections'][$i]['table'];
            }
        }
        return '';
    }

    /**
    * Returns the collection  label from the table of the collection
    *
    * @param string $table  Tablename
    * @return string Collection label or empty string if not found
    */
    public function retrieve_coll_label_from_table($table)
    {
        for($i=0; $i<count($_SESSION['collections']);$i++)
        {
            if($_SESSION['collections'][$i]['table'] == $table)
            {
                return $_SESSION['collections'][$i]['label'];
            }
        }
        return '';
    }

    /**
    * Returns the collection  label from the collection identifier
    *
    * @param string $coll_id  Collection identifier
    * @return string Collection label or empty string if not found
    */
    public function retrieve_coll_label_from_coll_id($coll_id)
    {
        for($i=0; $i<count($_SESSION['collections']);$i++)
        {
            if($_SESSION['collections'][$i]['id'] == $coll_id)
            {
                return $_SESSION['collections'][$i]['label'];
            }
        }
        return '';
    }

    ////////////////USER RELATED

    /**
    * Returns the collection identifier for the current user from the collection table (using $_SESSION['user']['security'])
    *
    * @param  $table  string Tablename
    * @return string Collection identifier or empty string if not found
    */
/*
    public function retrieve_user_coll_id($table)
    {

        foreach(array_keys($_SESSION['user']['security']) as $coll_id)
        {
            if($_SESSION['user']['security'][$coll_id]['DOC']['table'] == $table)
            {
                return $coll_id;
            }
        }
        return false;
    }
*/


//////////////////////// A REFAIRE
    /**
    * Return all collections where the current user can insert new documents (with table)
    *
    * @return array Array of all collections where the current user can insert new documents
    */
    public function retrieve_user_insert_coll()
    {
        $arr = array();
        for($i=0; $i<count($_SESSION['user']['security']);$i++)
        {
            if(isset($_SESSION['user']['security'][$i]['table']) && !empty( $_SESSION['user']['security'][$i]['table']) && $_SESSION['user']['security'][$i]['can_insert'] == 'Y')
            {
                $ind = $this->get_ind_collection($_SESSION['user']['security'][$i]['coll_id']);
                array_push($arr, array('coll_id'=> $_SESSION['user']['security'][$i]['coll_id'], 'label_coll' => $_SESSION['collections'][$ind]['label'] , 'table' => $_SESSION['user']['security'][$i]['table']));
            }
        }
        return $arr;
    }


    /**
    * Checks if the current user can do the action on the collection
    *
    * @param string $coll_id  Collection identifier
    * @param string $action  can_insert, can_update, can_delete
    * @return True if the user can do the action on the collection, False otherwise
    */
    public function collection_user_right($coll_id, $action)
    {
        if(!isset($coll_id))
        {
            return false;
        }
        $func = new functions();
        $flag = false;
        for($i=0; $i<count($_SESSION['user']['security']);$i++)
        {
            if((isset($_SESSION['user']['security'][$i]['coll_id']) && $_SESSION['user']['security'][$i]['coll_id'] == $coll_id)  && $_SESSION['user']['security'][$i][$action] == 'Y')
            {
                $flag = true;
            }
        }
        return $flag;
    }
/////////////////////////////

    /**
    * Returns where clause of the collection for the current user from the collection identifier
    *
    * @param  $coll_id string Collection identifier
    * @return string Collection where clause or empty string if not found or the where clause is empty
    */
    public function get_where_clause_from_coll_id($coll_id)
    {
        if(isset($_SESSION['user']['security'][$coll_id]['DOC']['where']))
        {
            return $_SESSION['user']['security'][$coll_id]['DOC']['where'];
        }
        return '';
    }

    /**
    * Returns where clause of the collection for the current user from the collection view
    *
    * @param  $view string View
    * @return string Collection where clause or empty string if not found or the where clause is empty
    */
    public function get_where_clause_from_view($view)
    {
        foreach(array_keys($_SESSION['user']['security']) as $coll_id)
        {
            if($_SESSION['user']['security'][$coll_id]['DOC']['view'] == $view)
            {
                return $_SESSION['user']['security'][$coll_id]['DOC']['where'];
            }
        }
        return '';
    }

    /**
    * Returns the collection table for the current user from the collection view (using $_SESSION['user']['security'])
    *
    * @param  $table  string Tablename
    * @return string Table name or False if not found
    */
    public function retrieve_user_coll_table($view)
    {
        foreach(array_keys($_SESSION['user']['security']) as $coll_id)
        {
            if($_SESSION['user']['security'][$coll_id]['DOC']['view'] == $view)
            {
                return $_SESSION['user']['security'][$coll_id]['DOC']['where'];
            }
        }
        return false;
    }

    /**
     *
     * @return array
     */
    public function getEntitiesForCurrentUser()
    {
        $entitiesTab = [];
        foreach ($_SESSION['user']['entities'] as $tmp) {
            $entitiesTab[] = $tmp['ENTITY_ID'];
        }
        return $entitiesTab;
    }

    /**
    * Checks the right on the document of a collection for the current user
    *
    * @param  $coll_id string Collection identifier
    * @param  $s_id string Document Identifier (res_id)
    * @return bool True if the current user has the right, False otherwise
    */
    public function test_right_doc($coll_id, $s_id)
    {
        if (empty($coll_id) || empty($s_id)) {
            return false;
        }
        $view = $this->retrieve_view_from_coll_id($coll_id);
        if (empty($view)) {
            $view = $this->retrieve_table_from_coll($coll_id);
        }
        $entitiesTab = $this->getEntitiesForCurrentUser();
        $where_clause = $this->get_where_clause_from_coll_id($coll_id);
        $query = "select res_id from " . $view . " where res_id = ?";
        if (!empty($entitiesTab)) {
            if (!empty($where_clause)) {
                $query .= " and (" . $where_clause . " or folder_destination in (?)) ";
            }
            $stmt = $this->query($query, array($s_id, $entitiesTab));
        } else {
            if (!empty($where_clause)) {
                $query .= " and (" . $where_clause . ") ";
            }
            $stmt = $this->query($query, array($s_id));
        }
        if ($stmt->rowCount() < 1) {
            //NOT IN THE DOC PERIMETER SO TEST IT IN THE BASKETS
            $basketQuery = '';
            for (
                $ind_bask = 0;
                $ind_bask < count($_SESSION['user']['baskets']);
                $ind_bask++
            ) {
                if (
                    $_SESSION['user']['baskets'][$ind_bask]['coll_id'] == $coll_id
                ) {
                    if(
                        isset($_SESSION['user']['baskets'][$ind_bask]['clause']) 
                        && trim($_SESSION['user']['baskets'][$ind_bask]['clause']
                        ) <> '' 
                        && $_SESSION['user']['baskets'][$ind_bask]['is_folder_basket'] == 'N'
                    ) {
                        $basketQuery .= ' or (' 
                            . $_SESSION['user']['baskets'][$ind_bask]['clause'] 
                            . ')';
                    }
                 }
            }
            if ($basketQuery <> '') {
                $basketQuery = preg_replace('/^ or/', '', $basketQuery);
                $query = "select res_id from " 
                    . $view . " where (" . $basketQuery . ") and res_id = ?";
                $stmt = $this->query($query, array($s_id));
                if ($stmt->rowCount() < 1) {
                    return false;
                } else {
                    return true;
                }
            } else {
                return false;
            }
        } else {
            return true;
        }
    }
}
