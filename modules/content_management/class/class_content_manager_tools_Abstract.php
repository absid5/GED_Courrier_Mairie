<?php

/*
*   Copyright 2008-2016 Maarch
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
*   along with Maarch Framework. If not, see <http://www.gnu.org/licenses/>.
*/

/**
* @brief Contains the functions to manage content_management directory and expiration
*
* @file
* @author Laurent Giovannoni
* @date $date$
* @version $Revision$
* @ingroup content_management
*/

require_once 'core/class/class_functions.php';
require_once 'core/class/class_db_pdo.php';
require_once 'core/class/docservers_controler.php';
require_once 'core/class/class_security.php';
require_once 'core/core_tables.php';

abstract class content_management_tools_Abstract
{
    //Parameters
    protected $extensions_xml_path = 'xml/extensions.xml';
    protected $programs_xml_path = 'xml/programs.xml';
    protected $parameter_id  = 'content_management_reservation';
    protected $templateMasterPath = 'modules/templates/templates_src/';
    //Variables
    protected $db;

    public function __construct()
    {
        if (!isset($_SESSION) OR count($_SESSION) == 0)
            return null;

        $this->db = new Database();
        //TODO: PUT IT AN CONFIG FILE WITH 30
        $_SESSION['config']['content_management_reserved_time'] = 30;
        if (!is_dir('modules/content_management/tmp/')) {
            mkdir('modules/content_management/tmp/');
        }
    }
    
    public function getCmParameters()
    {
        if (file_exists(
            $_SESSION['config']['corepath'] . 'custom/'
            . $_SESSION['custom_override_id'] 
            . '/modules/content_management/xml/content_management_features.xml'
        )
        ) {
            $path = $_SESSION['config']['corepath'] . 'custom/'
                . $_SESSION['custom_override_id'] 
                . '/modules/content_management/xml/content_management_features.xml';
        } else {
            $path = $_SESSION['config']['corepath'] 
                . 'modules/content_management/xml/content_management_features.xml';
        }
        $cMFeatures = array();
        if (file_exists($path)) {
            $func = new functions();
            $cMFeatures = $func->object2array(
                simplexml_load_file($path)
            );
        } else {
            $cMFeatures['CONFIG']['psExecMode'] = 'KO';
            $cMFeatures['CONFIG']['userMaarchOnClient'] = '';
            $cMFeatures['CONFIG']['userPwdMaarchOnClient'] = '';
        }
        return $cMFeatures;
    }

    /**
    * Returns who reserved the resource
    *
    * @param  string $objectTable res table, attachment table, model table, ...
    * @param  bigint $objectId id of the object res_id, model_id, ...
    * @return array the user who reserved the resource, else false
    */
    public function isReservedBy($objectTable, $objectId)
    {
        $timeLimit = $this->computeTimeLimit();
        $charTofind = $this->parameter_id . '#%#' . $objectTable . '#' . $objectId;

        $query = "select id from " . PARAM_TABLE . " where id like (?) and param_value_int > ?";

        $stmt = $this->db->query($query, array($charTofind, $timeLimit));
        
        if ($res = $stmt->fetchObject()) {

            $arrayUser = array();
            $arrayUser = explode("#", $res->id);
            if ($arrayUser[1] <> '') {
                $query = "select user_id, lastname, firstname "
                    . "from " . USERS_TABLE . " where user_id = ? and enabled = 'Y'";
                
                $stmt = $this->db->query($query, array($arrayUser[1]));
                
                $arrayReturn = array();
                if ($resUser = $stmt->fetchObject()) {
                    $arrayReturn['fullname'] = $resUser->firstname . ' '
                        . $resUser->lastname;
                    $arrayReturn['user_id'] = $resUser->user_id;
                } else {
                    $arrayReturn['fullname'] = 'empty';
                }
                $arrayReturn['status'] = 'ok';
                return $arrayReturn;
            } else {
                $arrayReturn['status'] = 'ko';
            }
        } else {
            $arrayReturn['status'] = 'ko';
        }
        return $arrayReturn;
    }

    /**
    * Close the content_management reservation
    *
    * @param string $CMId content_management id
    * @return nothing
    */
    public function closeReservation($CMId)
    {
        $query = "delete from " . PARAM_TABLE
            . " where id = ?";
        $stmt = $this->db->query($query, array($CMId));
    }

    /**
    * Update the expiration date of the content_management reservation for the connected user
    *
    * @param  string $CMId the content_management id
    * @param  string $userId the content_management id
    * @return nothing
    */
    public function updateExpiryDate($CMId, $userId)
    {
        $timeLimit = $this->computeTimeLimit() + (
            $_SESSION['config']['content_management_reserved_time'] * 60
        );
        $charTofind = $this->parameter_id . '#' . $userId . '%';
        $query = "update " . PARAM_TABLE
               . " set param_value_int = ? "
               . " where id like ?"
               . " and param_value_string = ?";
        $stmt = $this->db->query(
            $query, 
            array($timeLimit, $charTofind, $CMId)
        );
    }

    /**
    * Reserved the object for content_management
    * Add an expiration date of the content_management reservation for the connected user
    *
    * @param  string $objectTable the res table
    * @param  string $objectId the res_id
    * @param  string $CMId the content_management id
    * @param  string $userId the content_management id
    * @return string the reservation id
    */
    public function reserveObject($objectTable, $objectId, $userId)
    {
        $timeLimit = $this->computeTimeLimit() + (
            $_SESSION['config']['content_management_reserved_time'] * 60
        );
        //If exists Delete
        $charTofind = $this->parameter_id . '#' . $userId . '#' . $objectTable
                    . '#' . $objectId;
        $query = "delete from " . PARAM_TABLE
               . " where id = ?";
        $stmt = $this->db->query($query, array($charTofind));
        $query = "insert into " . PARAM_TABLE
               . " (id, param_value_int)"
               . " values(?, ?)";
        $stmt = $this->db->query($query, array($charTofind, $timeLimit));
        return $charTofind;
    }

    /**
    * Delete the resource in the tmp content_management dir if necessary
    *
    * @return nothing
    */
    public function deleteExpiredCM()
    {
        $timeLimit = $this->computeTimeLimit();
        $query = "delete from " . PARAM_TABLE
            . " where param_value_int < ? "
            . " and id like ? ";
        $stmt = $this->db->query($query, array($timeLimit, $this->parameter_id . '%'));
    }
    
    /**
    * Delete the resource for the disconnected user
    *
    * @return nothing
    */
    public function deleteUserCM()
    {
        $query = "delete from " . PARAM_TABLE
            . " where id like ?";
        $stmt = $this->db->query($query, array('content_management_reservation#' 
            . $_SESSION['user']['UserId'] . '%')
        );
    }

    /**
    * Delete the content_management tmp if necessary
    *
    * @param string $dir path to the tmp dir
    * @return nothing
    */
    protected function deleteDirectory($dir)
    {
        if (!file_exists($dir)) return true;
        if (!is_dir($dir) || is_link($dir)) return unlink($dir);
            foreach (scandir($dir) as $item) {
                if ($item == '.' || $item == '..') continue;
                if (!$this->deleteDirectory($dir . "/" . $item)) {
                    chmod($dir . "/" . $item, 0777);
                    if (!$this->deleteDirectory($dir . "/" . $item)) return false;
                };
            }
            return rmdir($dir);
    }

    /**
    * Returns time before expiration of the content_management reservation
    *
    * @param  string $CMId the content_management id
    * @return bigint the time in secon before expiration
    */
    public function timeBeforeExpiration($CMId)
    {
        $now = $this->computeTimeLimit();
        $charTofind = $this->parameter_id . '%';
        $query = "select param_value_int as time"
               . " from " . PARAM_TABLE
               . " where id like ?"
               . " and param_value_string = ?";
        $stmt = $this->db->query($query, array($charTofind, $CMId));
        if ($res = $stmt->fetchObject()) {
            $secBeforeExpiration = $res->time - $now;
            if ($secBeforeExpiration < 0)  {
                return 0;
            } else {
                return $secBeforeExpiration;
            }
        } else {
            return 0;
        }
    }

    /**
    * Returns the program to update the resource with content_management
    *
    * @param  string $mimeType mime type of the resource
    * @return array the program and status ok if mime type allowed for content_management
    */
    public function isMimeTypeAllowedForCM($mimeType, $ext) {
        $typeState = 'ko';
        $programPath = '';
        if ($mimeType <> '' && $ext <> '') {
            $path = $_SESSION['config']['corepath'] . 'custom/'
                  . $_SESSION['custom_override_id'] . '/apps/'
                  . $_SESSION['config']['app_id'] . '/xml/extensions.xml';
            if (!file_exists($path)) {
                $path =  $_SESSION['config']['corepath'] . '/apps/'
                      . $_SESSION['config']['app_id'] . '/xml/extensions.xml';
            }
            $xmlconfig = simplexml_load_file($path);
            $extList = array();
            $i = 0;
            foreach ($xmlconfig->FORMAT as $FORMAT) {
                $extList[$i] = array(
                    'name' => (string) $FORMAT->name,
                    'mime' => (string) $FORMAT->mime,
                    'web_dav_update' => (string) $FORMAT->web_dav_update,
                    'default_program' => (string) $FORMAT->default_program,
                );
                $i++;
            }
            for ($i=0;$i<count($extList);$i++) {
                if (
                    $extList[$i]['mime'] == $mimeType
                    && strtolower($extList[$i]['name']) == strtolower($ext)
                    && strtolower($extList[$i]['web_dav_update']) == 'true'
                ) {
                    $typeState = 'ok';
                    $programPath = $extList[$i]['default_program'];
                    break;
                }
            }
        }
        $arrayReturn = array(
            'status' => $typeState,
            'programPath' => $programPath,
        );
        return $arrayReturn;
    }

    /**
    * Compute the time limit for a content_management session
    *
    * @return string the time limit in timestamp
    */
    public function computeTimeLimit()
    {
        $timeLimit = mktime(
            date('H'),
            date('i'),
            date('s'),
            date('m'),
            date('d'),
            date('Y')
        );
        return $timeLimit;
    }

    /**
    * Generate JLNP file to launch the Applet DIS
    *
    * 
    */
    public function generateJNLP(
        $jar_url,
        $maarchcm_url,
        $objectType,
        $objectTable,
        $objectId,
        $uniqueId,
        $cookieKey,
        $user,
        $pwd,
        $psExecMode,
        $mayscript,
        $clientSideCookies
    ) {
        $docXML = new DomDocument('1.0', "UTF-8");

        $jnlp_balise=$docXML->createElement("jnlp");
        $jnlp_attribute1 = $docXML->createAttribute('spec'); 
        $jnlp_attribute1->value = '6.0+';
        $jnlp_balise->appendChild($jnlp_attribute1); 
        $jnlp_attribute2 = $docXML->createAttribute('codebase'); 
        $jnlp_attribute2->value = $jar_url.'/apps/maarch_entreprise/tmp/';
        $jnlp_balise->appendChild($jnlp_attribute2);
        $jnlp_attribute3 = $docXML->createAttribute('href'); 
        $jnlp_attribute3->value = $_SESSION['user']['UserId'].'_DisCM.jnlp';
        $jnlp_balise->appendChild($jnlp_attribute3); 

        $info_balise=$docXML->createElement("information");

        $title_balise=$docXML->createElement("title","Editeur de modèle de document");

        $vendor_balise=$docXML->createElement("vendor","MAARCH");

        $homepage_balise=$docXML->createElement("homepage");
        $homepage_attribute = $docXML->createAttribute('href');
        $homepage_attribute->value = 'http://maarch.com';
        $homepage_balise->appendChild($homepage_attribute);

        $desc_balise=$docXML->createElement("description","Génère votre document avec méta-données associées au courrier grâce à des champs de fusion.");
        
        $descshort_balise=$docXML->createElement("description","Génère votre document avec méta-données.");
        $descshort_attribute = $docXML->createAttribute('kind');
        $descshort_attribute->value = 'short';
        $descshort_balise->appendChild($descshort_attribute);

        $offline_balise=$docXML->createElement("offline-allowed");

        $security_balise=$docXML->createElement("security");

        $permission_balise=$docXML->createElement("all-permissions");

        $resources_balise=$docXML->createElement("resources");

        $j2se_balise=$docXML->createElement("j2se");
        $j2se_attribute = $docXML->createAttribute('version');
        $j2se_attribute->value = '1.6+';
        $j2se_balise->appendChild($j2se_attribute);

        $jar_balise=$docXML->createElement("jar");
        $jar_attribute = $docXML->createAttribute('href');
        $jar_attribute->value = $jar_url.'/modules/content_management/dist/DisCM.jar';
        $jar_balise->appendChild($jar_attribute);

        $applet_balise=$docXML->createElement("applet-desc");
        $applet_attribute1 = $docXML->createAttribute('main-class');
        $applet_attribute1->value = 'com.dis.DisCM';
        $applet_balise->appendChild($applet_attribute1);
        $applet_attribute2 = $docXML->createAttribute('code');
        $applet_attribute2->value = 'com.maarch.MaarchCM';
        $applet_balise->appendChild($applet_attribute2);
        $applet_attribute3 = $docXML->createAttribute('name');
        $applet_attribute3->value = 'maarchcmapplet';
        $applet_balise->appendChild($applet_attribute3);
        $applet_attribute4 = $docXML->createAttribute('id');
        $applet_attribute4->value = 'maarchcmapplet';
        $applet_balise->appendChild($applet_attribute4);
        $applet_attribute5 = $docXML->createAttribute('width');
        $applet_attribute5->value = '1';
        $applet_balise->appendChild($applet_attribute5);
        $applet_attribute6 = $docXML->createAttribute('height');
        $applet_attribute6->value = '1';
        $applet_balise->appendChild($applet_attribute6);
        $applet_attribute7 = $docXML->createAttribute('version');
        $applet_attribute7->value = '1.6';
        $applet_balise->appendChild($applet_attribute7);

        $param1_balise=$docXML->createElement("param");
        $param1_attribute1 = $docXML->createAttribute('name');
        $param1_attribute1->value = 'url';
        $param1_balise->appendChild($param1_attribute1);
        $param1_attribute2 = $docXML->createAttribute('value');
        $param1_attribute2->value = $maarchcm_url;
        $param1_balise->appendChild($param1_attribute2);

        $param2_balise=$docXML->createElement("param");
        $param2_attribute1 = $docXML->createAttribute('name');
        $param2_attribute1->value = 'objectType';
        $param2_balise->appendChild($param2_attribute1);
        $param2_attribute2 = $docXML->createAttribute('value');
        $param2_attribute2->value = $objectType;
        $param2_balise->appendChild($param2_attribute2);

        $param3_balise=$docXML->createElement("param");
        $param3_attribute1 = $docXML->createAttribute('name');
        $param3_attribute1->value = 'objectTable';
        $param3_balise->appendChild($param3_attribute1);
        $param3_attribute2 = $docXML->createAttribute('value');
        $param3_attribute2->value = $objectTable;
        $param3_balise->appendChild($param3_attribute2);

        $param4_balise=$docXML->createElement("param");
        $param4_attribute1 = $docXML->createAttribute('name');
        $param4_attribute1->value = 'objectId';
        $param4_balise->appendChild($param4_attribute1);
        $param4_attribute2 = $docXML->createAttribute('value');
        $param4_attribute2->value = $objectId;
        $param4_balise->appendChild($param4_attribute2);

        $param5_balise=$docXML->createElement("param");
        $param5_attribute1 = $docXML->createAttribute('name');
        $param5_attribute1->value = 'uniqueId';
        $param5_balise->appendChild($param5_attribute1);
        $param5_attribute2 = $docXML->createAttribute('value');
        $param5_attribute2->value = $uniqueId;
        $param5_balise->appendChild($param5_attribute2);

        $param6_balise=$docXML->createElement("param");
        $param6_attribute1 = $docXML->createAttribute('name');
        $param6_attribute1->value = 'cookie';
        $param6_balise->appendChild($param6_attribute1);
        $param6_attribute2 = $docXML->createAttribute('value');
        $param6_attribute2->value = $cookieKey;
        $param6_balise->appendChild($param6_attribute2);

        $param7_balise=$docXML->createElement("param");
        $param7_attribute1 = $docXML->createAttribute('name');
        $param7_attribute1->value = 'userMaarch';
        $param7_balise->appendChild($param7_attribute1);
        $param7_attribute2 = $docXML->createAttribute('value');
        $param7_attribute2->value = $user;
        $param7_balise->appendChild($param7_attribute2);

        $param8_balise=$docXML->createElement("param");
        $param8_attribute1 = $docXML->createAttribute('name');
        $param8_attribute1->value = 'userMaarchPwd';
        $param8_balise->appendChild($param8_attribute1);
        $param8_attribute2 = $docXML->createAttribute('value');
        $param8_attribute2->value = $pwd;
        $param8_balise->appendChild($param8_attribute2);

        $param9_balise=$docXML->createElement("param");
        $param9_attribute1 = $docXML->createAttribute('name');
        $param9_attribute1->value = 'psExecMode';
        $param9_balise->appendChild($param9_attribute1);
        $param9_attribute2 = $docXML->createAttribute('value');
        $param9_attribute2->value = $psExecMode;
        $param9_balise->appendChild($param9_attribute2);

        $param10_balise=$docXML->createElement("param");
        $param10_attribute1 = $docXML->createAttribute('name');
        $param10_attribute1->value = 'mayscript';
        $param10_balise->appendChild($param10_attribute1);
        $param10_attribute2 = $docXML->createAttribute('value');
        $param10_attribute2->value = $mayscript;
        $param10_balise->appendChild($param10_attribute2);

        $param11_balise=$docXML->createElement("param");
        $param11_attribute1 = $docXML->createAttribute('name');
        $param11_attribute1->value = 'clientsidecookies';
        $param11_balise->appendChild($param11_attribute1);
        $param11_attribute2 = $docXML->createAttribute('value');
        $param11_attribute2->value = $clientSideCookies;
        $param11_balise->appendChild($param11_attribute2);

        $jnlp_balise->appendChild($info_balise); 
        $info_balise->appendChild($title_balise); 
        $info_balise->appendChild($vendor_balise); 
        $info_balise->appendChild($homepage_balise); 
        $info_balise->appendChild($desc_balise); 
        $info_balise->appendChild($descshort_balise); 
        $info_balise->appendChild($offline_balise); 

        $jnlp_balise->appendChild($security_balise); 
        $security_balise->appendChild($permission_balise); 

        $jnlp_balise->appendChild($resources_balise); 
        $resources_balise->appendChild($j2se_balise); 
        $resources_balise->appendChild($jar_balise); 

        $jnlp_balise->appendChild($applet_balise); 
        $applet_balise->appendChild($param1_balise); 
        $applet_balise->appendChild($param2_balise); 
        $applet_balise->appendChild($param3_balise); 
        $applet_balise->appendChild($param4_balise); 
        $applet_balise->appendChild($param5_balise); 
        $applet_balise->appendChild($param6_balise); 
        $applet_balise->appendChild($param7_balise); 
        $applet_balise->appendChild($param8_balise); 
        $applet_balise->appendChild($param9_balise); 
        $applet_balise->appendChild($param10_balise); 
        $applet_balise->appendChild($param11_balise); 


        $docXML->appendChild($jnlp_balise);  

        $filename = $_SESSION['config']['tmppath'].$_SESSION['user']['UserId'].'_DisCM.jnlp';

        $docXML->save($filename); 

        $fp = fopen($_SESSION['config']['tmppath']."applet_".$_SESSION['user']['UserId'].".lck", 'w+');

        $file = $jar_url."/apps/maarch_entreprise/tmp/".$_SESSION['user']['UserId']."_DisCM.jnlp";

        echo '<a id="jnlp_file" href="'.$file.'" onclick="window.opener.location.href=\''.$file.'\';self.close();"></a>';
        echo '<script>document.getElementById("jnlp_file").click();</script>';
        exit();
        /*echo '<a id="jnlp_file" href="'.$_SESSION['config']['businessappurl'].'index.php?page=get_jnlp_file&module=content_management&display=true&filename='.$_SESSION['user']['UserId'].'_DisCM"></a>';
        echo '<script>setTimeout(function() {this.window.close();}, 5000);document.getElementById("jnlp_file").click();</script>';
        exit();*/
    }

    /**
    * Generate JLNP file to launch the Applet
    *
    * 
    */
    public function generateJNLPMaarch(
        $jar_url,
        $maarchcm_url,
        $objectType,
        $objectTable,
        $objectId,
        $uniqueId,
        $cookieKey,
        $user,
        $pwd,
        $psExecMode,
        $mayscript,
        $clientSideCookies
    ) {
        $docXML = new DomDocument('1.0', "UTF-8");

        $jnlp_balise=$docXML->createElement("jnlp");
        $jnlp_attribute1 = $docXML->createAttribute('spec'); 
        $jnlp_attribute1->value = '6.0+';
        $jnlp_balise->appendChild($jnlp_attribute1); 
        $jnlp_attribute2 = $docXML->createAttribute('codebase'); 
        $jnlp_attribute2->value = $jar_url.'/apps/maarch_entreprise/tmp/';
        $jnlp_balise->appendChild($jnlp_attribute2);
        $jnlp_attribute3 = $docXML->createAttribute('href'); 
        $jnlp_attribute3->value = $_SESSION['user']['UserId'].'_DisCM.jnlp';
        $jnlp_balise->appendChild($jnlp_attribute3); 

        $info_balise=$docXML->createElement("information");

        $title_balise=$docXML->createElement("title","Editeur de modèle de document");

        $vendor_balise=$docXML->createElement("vendor","MAARCH");

        $homepage_balise=$docXML->createElement("homepage");
        $homepage_attribute = $docXML->createAttribute('href');
        $homepage_attribute->value = 'http://maarch.com';
        $homepage_balise->appendChild($homepage_attribute);

        $desc_balise=$docXML->createElement("description","Génère votre document avec méta-données associées au courrier grâce à des champs de fusion.");
        
        $descshort_balise=$docXML->createElement("description","Génère votre document avec méta-données.");
        $descshort_attribute = $docXML->createAttribute('kind');
        $descshort_attribute->value = 'short';
        $descshort_balise->appendChild($descshort_attribute);

        $offline_balise=$docXML->createElement("offline-allowed");

        $security_balise=$docXML->createElement("security");

        $permission_balise=$docXML->createElement("all-permissions");

        $resources_balise=$docXML->createElement("resources");

        $j2se_balise=$docXML->createElement("j2se");
        $j2se_attribute = $docXML->createAttribute('version');
        $j2se_attribute->value = '1.6+';
        $j2se_balise->appendChild($j2se_attribute);

        $jar_balise=$docXML->createElement("jar");
        $jar_attribute = $docXML->createAttribute('href');
        $jar_attribute->value = $jar_url.'/modules/content_management/dist/maarchCM.jar';
        $jar_balise->appendChild($jar_attribute);

        $applet_balise=$docXML->createElement("applet-desc");
        $applet_attribute1 = $docXML->createAttribute('main-class');
        $applet_attribute1->value = 'com.maarch.MaarchCM';
        $applet_balise->appendChild($applet_attribute1);
        $applet_attribute2 = $docXML->createAttribute('code');
        $applet_attribute2->value = 'com.maarch.MaarchCM';
        $applet_balise->appendChild($applet_attribute2);
        $applet_attribute3 = $docXML->createAttribute('name');
        $applet_attribute3->value = 'maarchcmapplet';
        $applet_balise->appendChild($applet_attribute3);
        $applet_attribute4 = $docXML->createAttribute('id');
        $applet_attribute4->value = 'maarchcmapplet';
        $applet_balise->appendChild($applet_attribute4);
        $applet_attribute5 = $docXML->createAttribute('width');
        $applet_attribute5->value = '1';
        $applet_balise->appendChild($applet_attribute5);
        $applet_attribute6 = $docXML->createAttribute('height');
        $applet_attribute6->value = '1';
        $applet_balise->appendChild($applet_attribute6);
        $applet_attribute7 = $docXML->createAttribute('version');
        $applet_attribute7->value = '1.6';
        $applet_balise->appendChild($applet_attribute7);

        $param1_balise=$docXML->createElement("param");
        $param1_attribute1 = $docXML->createAttribute('name');
        $param1_attribute1->value = 'url';
        $param1_balise->appendChild($param1_attribute1);
        $param1_attribute2 = $docXML->createAttribute('value');
        $param1_attribute2->value = $maarchcm_url;
        $param1_balise->appendChild($param1_attribute2);

        $param2_balise=$docXML->createElement("param");
        $param2_attribute1 = $docXML->createAttribute('name');
        $param2_attribute1->value = 'objectType';
        $param2_balise->appendChild($param2_attribute1);
        $param2_attribute2 = $docXML->createAttribute('value');
        $param2_attribute2->value = $objectType;
        $param2_balise->appendChild($param2_attribute2);

        $param3_balise=$docXML->createElement("param");
        $param3_attribute1 = $docXML->createAttribute('name');
        $param3_attribute1->value = 'objectTable';
        $param3_balise->appendChild($param3_attribute1);
        $param3_attribute2 = $docXML->createAttribute('value');
        $param3_attribute2->value = $objectTable;
        $param3_balise->appendChild($param3_attribute2);

        $param4_balise=$docXML->createElement("param");
        $param4_attribute1 = $docXML->createAttribute('name');
        $param4_attribute1->value = 'objectId';
        $param4_balise->appendChild($param4_attribute1);
        $param4_attribute2 = $docXML->createAttribute('value');
        $param4_attribute2->value = $objectId;
        $param4_balise->appendChild($param4_attribute2);

        $param5_balise=$docXML->createElement("param");
        $param5_attribute1 = $docXML->createAttribute('name');
        $param5_attribute1->value = 'uniqueId';
        $param5_balise->appendChild($param5_attribute1);
        $param5_attribute2 = $docXML->createAttribute('value');
        $param5_attribute2->value = $uniqueId;
        $param5_balise->appendChild($param5_attribute2);

        $param6_balise=$docXML->createElement("param");
        $param6_attribute1 = $docXML->createAttribute('name');
        $param6_attribute1->value = 'cookie';
        $param6_balise->appendChild($param6_attribute1);
        $param6_attribute2 = $docXML->createAttribute('value');
        $param6_attribute2->value = $cookieKey;
        $param6_balise->appendChild($param6_attribute2);

        $param7_balise=$docXML->createElement("param");
        $param7_attribute1 = $docXML->createAttribute('name');
        $param7_attribute1->value = 'userMaarch';
        $param7_balise->appendChild($param7_attribute1);
        $param7_attribute2 = $docXML->createAttribute('value');
        $param7_attribute2->value = $user;
        $param7_balise->appendChild($param7_attribute2);

        $param8_balise=$docXML->createElement("param");
        $param8_attribute1 = $docXML->createAttribute('name');
        $param8_attribute1->value = 'userMaarchPwd';
        $param8_balise->appendChild($param8_attribute1);
        $param8_attribute2 = $docXML->createAttribute('value');
        $param8_attribute2->value = $pwd;
        $param8_balise->appendChild($param8_attribute2);

        $param9_balise=$docXML->createElement("param");
        $param9_attribute1 = $docXML->createAttribute('name');
        $param9_attribute1->value = 'psExecMode';
        $param9_balise->appendChild($param9_attribute1);
        $param9_attribute2 = $docXML->createAttribute('value');
        $param9_attribute2->value = $psExecMode;
        $param9_balise->appendChild($param9_attribute2);

        $param10_balise=$docXML->createElement("param");
        $param10_attribute1 = $docXML->createAttribute('name');
        $param10_attribute1->value = 'mayscript';
        $param10_balise->appendChild($param10_attribute1);
        $param10_attribute2 = $docXML->createAttribute('value');
        $param10_attribute2->value = $mayscript;
        $param10_balise->appendChild($param10_attribute2);

        $param11_balise=$docXML->createElement("param");
        $param11_attribute1 = $docXML->createAttribute('name');
        $param11_attribute1->value = 'clientsidecookies';
        $param11_balise->appendChild($param11_attribute1);
        $param11_attribute2 = $docXML->createAttribute('value');
        $param11_attribute2->value = $clientSideCookies;
        $param11_balise->appendChild($param11_attribute2);


        $jnlp_balise->appendChild($info_balise); 
        $info_balise->appendChild($title_balise); 
        $info_balise->appendChild($vendor_balise); 
        $info_balise->appendChild($homepage_balise); 
        $info_balise->appendChild($desc_balise); 
        $info_balise->appendChild($descshort_balise); 
        $info_balise->appendChild($offline_balise); 

        $jnlp_balise->appendChild($security_balise); 
        $security_balise->appendChild($permission_balise); 

        $jnlp_balise->appendChild($resources_balise); 
        $resources_balise->appendChild($j2se_balise); 
        $resources_balise->appendChild($jar_balise); 

        $jnlp_balise->appendChild($applet_balise); 
        $applet_balise->appendChild($param1_balise); 
        $applet_balise->appendChild($param2_balise); 
        $applet_balise->appendChild($param3_balise); 
        $applet_balise->appendChild($param4_balise); 
        $applet_balise->appendChild($param5_balise); 
        $applet_balise->appendChild($param6_balise); 
        $applet_balise->appendChild($param7_balise); 
        $applet_balise->appendChild($param8_balise); 
        $applet_balise->appendChild($param9_balise); 
        $applet_balise->appendChild($param10_balise); 
        $applet_balise->appendChild($param11_balise); 


        $docXML->appendChild($jnlp_balise);  

        $filename = $_SESSION['config']['tmppath'].$_SESSION['user']['UserId'].'_DisCM.jnlp';

        $docXML->save($filename); 

        $fp = fopen($_SESSION['config']['tmppath']."applet_".$_SESSION['user']['UserId'].".lck", 'w+');

        $file = $jar_url."/apps/maarch_entreprise/tmp/".$_SESSION['user']['UserId']."_DisCM.jnlp";

        echo '<a id="jnlp_file" href="'.$file.'" onclick="window.opener.location.href=\''.$file.'\';self.close();"></a>';
        echo '<script>document.getElementById("jnlp_file").click();</script>';
        exit();
        /*echo '<a id="jnlp_file" href="'.$_SESSION['config']['businessappurl'].'index.php?page=get_jnlp_file&module=content_management&display=true&filename='.$_SESSION['user']['UserId'].'_DisCM"></a>';
        echo '<script>setTimeout(function() {this.window.close();}, 5000);document.getElementById("jnlp_file").click();</script>';
        exit();*/
    }
}
