<?php
/*
*   Copyright 2008-2012 Maarch
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
* @brief class of install tools
*
* @file
* @author Laurent Giovannoni
* @author Arnaud Veber
* @date $date$
* @version $Revision$
* @ingroup install
*/

//Loads the required class
try {
    require_once 'core/class/class_functions.php';
    require_once 'core/class/class_db.php';
    require_once 'install/class/Class_Merge.php';
    require_once('core' . DIRECTORY_SEPARATOR . 'class'
        . DIRECTORY_SEPARATOR . 'class_security.php');
} catch (Exception $e) {
    functions::xecho($e->getMessage()) . ' // ';
}

class Install extends functions
{
    private $lang = 'en';

    private $docservers = array(
        array('FASTHD_AI', 'ai'),
        array('FASTHD_MAN', 'manual'),
        array('TEMPLATES', 'templates'),
        array('TNL', 'thumbnails_mlb'),
    );

    function __construct()
    {
        //merge css & js
        $Class_Merge = new Merge;
        //load lang
        $this->loadLang();
    }

    public function getLangList()
    {
        $langList = array();
        foreach(glob('install/lang/*.php') as $fileLangPath) {
            $langFile = str_replace('.php', '', end(explode('/', $fileLangPath)));
            array_push($langList, $langFile);
        }

        return $langList;
    }

    private function loadLang()
    {
        if (!isset($_SESSION['lang'])) {
            $this->lang = 'en';
        }
        $this->lang = $_SESSION['lang'];

        $langList = $this->getLangList();
        if (!in_array($this->lang, $langList)) {
            $this->lang = 'en';
        }

        require_once('install/lang/' . $this->lang . '.php');
    }

    public function getActualLang()
    {
        return $this->lang;
    }

    public function checkPrerequisites(
        $is = false,
        $optional = false
    )
    {
        if ($is) {
            return '<img src="img/green_light.png" width="20px"/>';
            exit;
        }
        if (!$optional) {
            return '<img src="img/red_light.png"  width="20px"/>';
            exit;
        }
        return '<img src="img/orange_light.png"  width="20px"/>';
    }

    public function checkAllNeededPrerequisites()
    {
        if (!$this->isPhpVersion()) {
            return false;
        }
        if (!$this->isPhpRequirements('pgsql')) {
            return false;
        }
        if (!$this->isPhpRequirements('mbstring')) {
            return false;
        }
        if (!$this->isMaarchPathWritable()) {
            return false;
        }
        if (!$this->isPhpRequirements('gd')) {
            return false;
        }
        /*if (!$this->isPhpRequirements('imagick')) {
            return false;
        }*/
        /*if (!$this->isPhpRequirements('ghostscript')) {
            return false;
        }*/
        if (!$this->isPearRequirements('System.php')) {
            return false;
        }
        // if (!$this->isPearRequirements('MIME/Type.php')) {
        //     return false;
        // }
        /*if (!$this-&gt;isIniErrorRepportingRequirements()) {
            return false;
        }*/
        if (!$this->isIniDisplayErrorRequirements()) {
            return false;
        }
        if (!$this->isIniShortOpenTagRequirements()) {
            return false;
        }
        if (!$this->isIniMagicQuotesGpcRequirements()) {
            return false;
        }
        
        if (DIRECTORY_SEPARATOR != '/' && !$this->isPhpRequirements('fileinfo')){
            return false;
        }
        
        return true;
    }

    public function isPhpVersion()
    {
        if (version_compare(PHP_VERSION, '5.3') < 0) {
            return false;
            exit;
        }
        return true;
    }

    public function isPhpRequirements($phpLibrary)
    {
        if (!@extension_loaded($phpLibrary)) {
            return false;
            exit;
        }
        return true;
    }

    public function isPearRequirements($pearLibrary)
    {
        $includePath = array();
        $includePath = explode(';', ini_get('include_path'));
        for ($i=0;$i<count($includePath);$i++) {
            if (file_exists($includePath[$i] . '/' . $pearLibrary)) {
                return true;
                exit;
            }
        }
        $includePath = explode(':', ini_get('include_path'));
        for ($i=0;$i<count($includePath);$i++) {
            if (file_exists($includePath[$i] . '/' . $pearLibrary)) {
                return true;
                exit;
            }
        }
        return false;
    }

    public function isIniErrorRepportingRequirements()
    {
        if (version_compare(PHP_VERSION, '5.4') >= 0) {
            if (ini_get('error_reporting') <> 22519) {
                return false;
            } else {
                return true;
            }
        } else {
            if (ini_get('error_reporting') <> 22519) {
                return false;
            } else {
                return true;
            }
        }
    }

    public function isIniDisplayErrorRequirements()
    {
        if (strtoupper(ini_get('display_errors')) ==  'OFF') {
            return false;
        } else {
            return true;
        }
    }

    public function isIniShortOpenTagRequirements()
    {
        if (strtoupper(ini_get('short_open_tag')) ==  'OFF') {
            return false;
        } else {
            return true;
        }
    }

    public function isIniMagicQuotesGpcRequirements()
    {
        if (strtoupper(ini_get('magic_quotes_gpc')) ==  'ON') {
            return false;
        } else {
            return true;
        }
    }

    public function getProgress(
        $stepNb,
        $stepNbTotal
    )
    {
        $stepNb--;
        $stepNbTotal--;
        if ($stepNb == 0) {
            return '';
            exit;
        }
        $return = '';
        $percentProgress = round(($stepNb/$stepNbTotal) * 100);
        $sizeProgress = round(($percentProgress * 910) / 100);

        $return .= '<div id="progressButton" style="width: '.$sizeProgress.'px;">';
            $return .= '<div align="center">';
                $return .= $percentProgress.'%';
            $return .= '</div>';
        $return .= '</div>';

        return $return;
    }

    public function setPreviousStep($previousStep)
    {
        $_SESSION['previousStep'] = $previousStep;
    }

    public function checkDatabaseParameters(
        $databaseserver,
        $databaseserverport,
        $databaseuser,
        $databasepassword,
        $databasetype
    )
    {
        $connect  = 'host='.$databaseserver . ' ';
        $connect .= 'port='.$databaseserverport . ' ';
        $connect .= 'user='.$databaseuser . ' ';
        $connect .= 'password='.$databasepassword . ' ';
        $connect .= 'dbname=postgres';

        if (!@pg_connect($connect)) {
            return false;
            exit;
        }

        pg_close();

        return true;
    }

    public function createCustom($databasename){
        
        $customAlreadyExist = realpath('.').'/custom/cs_'.$databasename;
            if(file_exists($customAlreadyExist)){ 
            //return false;
                if(is_dir(realpath('.')."/custom/cs_$databasename/apps/") && is_dir(realpath('.')."/custom/cs_$databasename/apps/maarch_entreprise/") && is_dir(realpath('.')."/custom/cs_$databasename/apps/maarch_entreprise/xml/")){
                }elseif(is_dir(realpath('.')."/custom/cs_$databasename/apps/") && !is_dir(realpath('.')."/custom/cs_$databasename/apps/maarch_entreprise/")){
                    $cheminCustomMaarchCourrierAppsMaarchEntreprise = realpath('.')."/custom/cs_$databasename/apps/maarch_entreprise";
                    mkdir($cheminCustomMaarchCourrierAppsMaarchEntreprise, 0755);

                    $cheminCustomMaarchCourrierAppsMaarchEntrepriseXml = realpath('.')."/custom/cs_$databasename/apps/maarch_entreprise/xml";
                    mkdir($cheminCustomMaarchCourrierAppsMaarchEntrepriseXml, 0755);

                }elseif(is_dir(realpath('.')."/custom/cs_$databasename/apps/") && is_dir(realpath('.')."/custom/cs_$databasename/apps/maarch_entreprise/") && !is_dir(realpath('.')."/custom/cs_$databasename/apps/maarch_entreprise/xml/")){

                    $cheminCustomMaarchCourrierAppsMaarchEntrepriseXml = realpath('.')."/custom/cs_$databasename/apps/maarch_entreprise/xml";
                    mkdir($cheminCustomMaarchCourrierAppsMaarchEntrepriseXml, 0755);

                }elseif(!is_dir(realpath('.')."/custom/cs_$databasename/apps/")){

                    $cheminCustomMaarchCourrierApps = realpath('.')."/custom/cs_$databasename/apps";
                    mkdir($cheminCustomMaarchCourrierApps, 0755);

                    $cheminCustomMaarchCourrierAppsMaarchEntreprise = realpath('.')."/custom/cs_$databasename/apps/maarch_entreprise";
                    mkdir($cheminCustomMaarchCourrierAppsMaarchEntreprise, 0755);

                    $cheminCustomMaarchCourrierAppsMaarchEntrepriseXml = realpath('.')."/custom/cs_$databasename/apps/maarch_entreprise/xml";
                    mkdir($cheminCustomMaarchCourrierAppsMaarchEntrepriseXml, 0755);

                }

                if(!is_dir(realpath('.')."/custom/cs_$databasename/modules/")){

                    $cheminCustomMaarchCourrierModules = realpath('.')."/custom/cs_$databasename/modules";
                    mkdir($cheminCustomMaarchCourrierModules, 0755);

                    /** Création répertoire thumbnails dans le custom **/

                    $cheminCustomMaarchCourrierModulesThumbnails = realpath('.')."/custom/cs_$databasename/modules/thumbnails";
                    mkdir($cheminCustomMaarchCourrierModulesThumbnails, 0755);

                    $cheminCustomMaarchCourrierModulesThumbnailsXml = realpath('.')."/custom/cs_$databasename/modules/thumbnails/xml";
                    mkdir($cheminCustomMaarchCourrierModulesThumbnailsXml, 0755);

                    $cheminCustomMaarchCourrierModulesThumbnailsScripts = realpath('.')."/custom/cs_$databasename/modules/thumbnails/scripts";
                    mkdir($cheminCustomMaarchCourrierModulesThumbnailsScripts, 0755);

                            /** Création répertoire notification dans le custom **/

                    $cheminCustomMaarchCourrierModulesNotifications = realpath('.')."/custom/cs_$databasename/modules/notifications";
                    mkdir($cheminCustomMaarchCourrierModulesNotifications, 0755);

                    $cheminCustomMaarchCourrierModulesNotificationsBatch = realpath('.')."/custom/cs_$databasename/modules/notifications/batch";
                    mkdir($cheminCustomMaarchCourrierModulesNotificationsBatch, 0755);

                    $cheminCustomMaarchCourrierModulesNotificationsConfig = realpath('.')."/custom/cs_$databasename/modules/notifications/batch/config";
                    mkdir($cheminCustomMaarchCourrierModulesNotificationsConfig, 0755);

                    $cheminCustomMaarchCourrierModulesNotificationsScripts = realpath('.')."/custom/cs_$databasename/modules/notifications/batch/scripts";
                    mkdir($cheminCustomMaarchCourrierModulesNotificationsScripts, 0755);

                    /** Création répertoire sendmail dans le custom **/

                    $cheminCustomMaarchCourrierModulesSendmail = realpath('.')."/custom/cs_$databasename/modules/sendmail";
                    mkdir($cheminCustomMaarchCourrierModulesSendmail, 0755);

                    $cheminCustomMaarchCourrierModulesSendmailBatch = realpath('.')."/custom/cs_$databasename/modules/sendmail/batch";
                    mkdir($cheminCustomMaarchCourrierModulesSendmailBatch, 0755);

                    $cheminCustomMaarchCourrierModulesSendmailBatchConfig = realpath('.')."/custom/cs_$databasename/modules/sendmail/batch/config";
                    mkdir($cheminCustomMaarchCourrierModulesSendmailBatchConfig, 0755);

                    $cheminCustomMaarchCourrierModulesSendmailBatchScripts = realpath('.')."/custom/cs_$databasename/modules/sendmail/batch/scripts";
                    mkdir($cheminCustomMaarchCourrierModulesSendmailBatchScripts, 0755);


                    /** Création répertoire LDAP dans le custom **/

                    $cheminCustomMaarchCourrierModulesLdap = realpath('.')."/custom/cs_$databasename/modules/ldap";
                    mkdir($cheminCustomMaarchCourrierModulesLdap, 0755);

                    $cheminCustomMaarchCourrierModulesLdapXml = realpath('.')."/custom/cs_$databasename/modules/ldap/xml";
                    mkdir($cheminCustomMaarchCourrierModulesLdapXml, 0755);

                    $cheminCustomMaarchCourrierModulesLdapScript = realpath('.')."/custom/cs_$databasename/modules/ldap/script";
                    mkdir($cheminCustomMaarchCourrierModulesLdapScript, 0755);



                    /** Création répertoire fulltext dans le custom **/

                    $cheminCustomMaarchCourrierModulesFullText = realpath('.')."/custom/cs_$databasename/modules/full_text";
                    mkdir($cheminCustomMaarchCourrierModulesFullText, 0755);

                    $cheminCustomMaarchCourrierModulesFullTextXml = realpath('.')."/custom/cs_$databasename/modules/full_text/xml";
                    mkdir($cheminCustomMaarchCourrierModulesFullTextXml, 0755);

                    $cheminCustomMaarchCourrierModulesFullTextScripts = realpath('.')."/custom/cs_$databasename/modules/full_text/scripts";
                    mkdir($cheminCustomMaarchCourrierModulesFullTextScripts, 0755);

                }

                if(is_dir(realpath('.')."/custom/cs_$databasename/modules/")){

                    /** Création répertoire thumbnails dans le custom **/

                    if(!is_dir(realpath('.')."/custom/cs_$databasename/modules/thumbnails/")){
                        $cheminCustomMaarchCourrierModulesThumbnails = realpath('.')."/custom/cs_$databasename/modules/thumbnails";
                        mkdir($cheminCustomMaarchCourrierModulesThumbnails, 0755);
                    }
                    if(!is_dir(realpath('.')."/custom/cs_$databasename/modules/thumbnails/xml/")){
                        $cheminCustomMaarchCourrierModulesThumbnailsXml = realpath('.')."/custom/cs_$databasename/modules/thumbnails/xml";
                        mkdir($cheminCustomMaarchCourrierModulesThumbnailsXml, 0755);
                    }
                    if(!is_dir(realpath('.')."/custom/cs_$databasename/modules/thumbnails/scripts/")){
                        $cheminCustomMaarchCourrierModulesThumbnailsScripts = realpath('.')."/custom/cs_$databasename/modules/thumbnails/scripts";
                        mkdir($cheminCustomMaarchCourrierModulesThumbnailsScripts, 0755);
                    }

                     /** Création répertoire thumbnails dans le custom **/
                    if(!is_dir(realpath('.')."/custom/cs_$databasename/modules/notifications/")){
                        $cheminCustomMaarchCourrierModulesNotifications = realpath('.')."/custom/cs_$databasename/modules/notifications/";
                        mkdir($cheminCustomMaarchCourrierModulesNotifications, 0755);
                    }
                    if(!is_dir(realpath('.')."/custom/cs_$databasename/modules/notifications/batch/")){
                        $cheminCustomMaarchCourrierModulesNotificationsBatch = realpath('.')."/custom/cs_$databasename/modules/notifications/batch";
                        mkdir($cheminCustomMaarchCourrierModulesNotificationsBatch, 0755);
                    }
                    if(!is_dir(realpath('.')."/custom/cs_$databasename/modules/notifications/batch/config/")){
                        $cheminCustomMaarchCourrierModulesNotificationsConfig = realpath('.')."/custom/cs_$databasename/modules/notifications/batch/config";
                        mkdir($cheminCustomMaarchCourrierModulesNotificationsConfig, 0755);
                    }
                    if(!is_dir(realpath('.')."/custom/cs_$databasename/modules/notifications/batch/scripts/")){
                        $cheminCustomMaarchCourrierModulesNotificationsScripts = realpath('.')."/custom/cs_$databasename/modules/notifications/batch/scripts";
                        mkdir($cheminCustomMaarchCourrierModulesNotificationsScripts, 0755);
                    }


                                        /** Création répertoire thumbnails dans le custom **/
                    if(!is_dir(realpath('.')."/custom/cs_$databasename/modules/sendmail/")){
                        $cheminCustomMaarchCourrierModulesSendmail = realpath('.')."/custom/cs_$databasename/modules/sendmail";
                        mkdir($cheminCustomMaarchCourrierModulesSendmail, 0755);
                    }

                    if(!is_dir(realpath('.')."/custom/cs_$databasename/modules/sendmail/batch/")){
                        $cheminCustomMaarchCourrierModulesSendmailBatch = realpath('.')."/custom/cs_$databasename/modules/sendmail/batch";
                        mkdir($cheminCustomMaarchCourrierModulesSendmailBatch, 0755);
                    }
                    if(!is_dir(realpath('.')."/custom/cs_$databasename/modules/sendmail/batch/config/")){
                        $cheminCustomMaarchCourrierModulesSendmailBatchConfig = realpath('.')."/custom/cs_$databasename/modules/sendmail/batch/config";
                        mkdir($cheminCustomMaarchCourrierModulesSendmailBatchConfig, 0755);
                    }
                    if(!is_dir(realpath('.')."/custom/cs_$databasename/modules/sendmail/batch/scripts/")){
                    $cheminCustomMaarchCourrierModulesSendmailBatchScripts = realpath('.')."/custom/cs_$databasename/modules/sendmail/batch/scripts";
                    mkdir($cheminCustomMaarchCourrierModulesSendmailBatchScripts, 0755);
                    }


                    if(!is_dir(realpath('.')."/custom/cs_$databasename/modules/ldap/")){
                        $cheminCustomMaarchCourrierModulesLdap = realpath('.')."/custom/cs_$databasename/modules/ldap";
                        mkdir($cheminCustomMaarchCourrierModulesLdap, 0755);
                    }
                    if(!is_dir(realpath('.')."/custom/cs_$databasename/modules/ldap/xml/")){
                        $cheminCustomMaarchCourrierModulesLdapXml = realpath('.')."/custom/cs_$databasename/modules/ldap/xml";
                        mkdir($cheminCustomMaarchCourrierModulesLdapXml, 0755);
                    }
                    if(!is_dir(realpath('.')."/custom/cs_$databasename/modules/ldap/script/")){
                    $cheminCustomMaarchCourrierModulesLdapScript = realpath('.')."/custom/cs_$databasename/modules/ldap/script";
                    mkdir($cheminCustomMaarchCourrierModulesLdapScript, 0755);
                    }


                    if(!is_dir(realpath('.')."/custom/cs_$databasename/modules/full_text/")){
                        $cheminCustomMaarchCourrierModulesFullText = realpath('.')."/custom/cs_$databasename/modules/full_text";
                        mkdir($cheminCustomMaarchCourrierModulesFullText, 0755);
                    }
                    if(!is_dir(realpath('.')."/custom/cs_$databasename/modules/full_text/xml")){
                    $cheminCustomMaarchCourrierModulesFullTextXml = realpath('.')."/custom/cs_$databasename/modules/full_text/xml";
                    mkdir($cheminCustomMaarchCourrierModulesFullTextXml, 0755);
                    }
                    if(!is_dir(realpath('.')."/custom/cs_$databasename/modules/full_text/scripts")){
                    $cheminCustomMaarchCourrierModulesFullTextScripts = realpath('.')."/custom/cs_$databasename/modules/full_text/scripts";
                    mkdir($cheminCustomMaarchCourrierModulesFullTextScripts, 0755);
                    } 
                }

            //Création du lien symbolique sous linux
            if(strtoupper(substr(PHP_OS, 0, 3)) === 'LIN') {
                $cmd = "ln -s ".realpath('.')."/ cs_$databasename";
                exec($cmd);

            }/*elseif(strtoupper(substr(PHP_OS, 0, 3)) === 'WIN'){
                $cmd = "mklink cs_$databasename ".realpath('.');
                var_dump($cmd);
                var_dump(exec($cmd));
                exit;
                exec($cmd);
            }*/ 

            }else{ 



            $chemin = realpath('.');
			if(strtoupper(substr(PHP_OS, 0, 3)) === 'LIN') {
            $needle   = '/';
			}elseif(strtoupper(substr(PHP_OS, 0, 3)) === 'WIN'){
			$needle   = '\\';
			}

            $pos      = strripos($chemin, $needle);

            if ($pos === false) {
                //echo "Désolé, impossible de trouver ($needle) dans ($chemin)";
            } else {
                // echo "Félicitations !\n";
                // echo "Nous avons trouvé le dernier ($needle) dans ($chemin) à la position ($pos)";
            }

            $rest = substr($chemin, $pos +1 );    // contient le nom de l'appli (le nom du dossier où se situe l'appli)
                // var_dump($rest);


            // $cheminCustom = realpath('.')."/custom";
            // // var_dump($cheminCustom);
            // mkdir($cheminCustom, 0755);
            $filename = realpath('.').'/custom/custom.xml';
            //var_dump(file_exists($filename));
            if (file_exists($filename)) {
                //var_dump('dans if');
                $xmlCustom = simplexml_load_file(realpath('.')."/custom/custom.xml");
                //$xmlCustom->addChild('custom');
                $custom = $xmlCustom->addChild('custom');
                $custom->addChild('custom_id','cs_'.$databasename); 
                $custom->addChild('ip');  
                $custom->addChild('external_domain');  
                $custom->addChild('domain'); 
				if(strtoupper(substr(PHP_OS, 0, 3)) === 'LIN') {				
                $custom->addChild('path',"cs_".$databasename);   
				}elseif(strtoupper(substr(PHP_OS, 0, 3)) === 'WIN'){
				$custom->addChild('path',$rest);  
				}
                $res = $xmlCustom->asXML();
                $fp = @fopen(realpath('.')."/custom/custom.xml", "w+");
                    if (!$fp) {
                        return false;
                        exit;
                    }
                    $write = fwrite($fp,$res);
                    if (!$write) {
                        return false;
                        exit;
                    }

            }



            if(!file_exists($filename)){
            $manip2 = fopen(realpath('.')."/custom/custom.xml", "w+");  
            $contenuXmlCustom = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
            $contenuXmlCustom .= "<root>\n";
            $contenuXmlCustom .= "\t<custom>\n";
            $contenuXmlCustom .= "\t\t<custom_id>cs_".$databasename."</custom_id>\n";
            $contenuXmlCustom .= "\t\t<ip></ip>\n";
            $contenuXmlCustom .= "\t\t<external_domain></external_domain>\n";
            $contenuXmlCustom .= "\t\t<domain></domain>\n";
			if(strtoupper(substr(PHP_OS, 0, 3)) === 'LIN') {
            $contenuXmlCustom .= "\t\t<path>cs_".$databasename."</path>\n";
			}elseif(strtoupper(substr(PHP_OS, 0, 3)) === 'WIN'){
			$contenuXmlCustom .= "\t\t<path>$rest</path>\n";
			}
            $contenuXmlCustom .= "\t</custom>\n";
            $contenuXmlCustom .= "</root>";

            fputs($manip2, $contenuXmlCustom);
            fclose($manip2);


            }
            

            $cheminCustomMaarchCourrier = realpath('.')."/custom/cs_$databasename";
            mkdir($cheminCustomMaarchCourrier, 0755);

            /** 
            Création répertoire apps/maarch_entreprise dans le custom 
            */

            $cheminCustomMaarchCourrierApps = realpath('.')."/custom/cs_$databasename/apps";
            mkdir($cheminCustomMaarchCourrierApps, 0755);

            $cheminCustomMaarchCourrierAppsMaarchEntreprise = realpath('.')."/custom/cs_$databasename/apps/maarch_entreprise";
            mkdir($cheminCustomMaarchCourrierAppsMaarchEntreprise, 0755);

            $cheminCustomMaarchCourrierAppsMaarchEntrepriseXml = realpath('.')."/custom/cs_$databasename/apps/maarch_entreprise/xml";
            mkdir($cheminCustomMaarchCourrierAppsMaarchEntrepriseXml, 0755);

            /** 
            Création répertoire modules dans le custom 
            */

            $cheminCustomMaarchCourrierModules = realpath('.')."/custom/cs_$databasename/modules";
            mkdir($cheminCustomMaarchCourrierModules, 0755);

            /** Création répertoire thumbnails dans le custom **/

            $cheminCustomMaarchCourrierModulesThumbnails = realpath('.')."/custom/cs_$databasename/modules/thumbnails";
            mkdir($cheminCustomMaarchCourrierModulesThumbnails, 0755);

            $cheminCustomMaarchCourrierModulesThumbnailsXml = realpath('.')."/custom/cs_$databasename/modules/thumbnails/xml";
            mkdir($cheminCustomMaarchCourrierModulesThumbnailsXml, 0755);

            $cheminCustomMaarchCourrierModulesThumbnailsScripts = realpath('.')."/custom/cs_$databasename/modules/thumbnails/scripts";
            mkdir($cheminCustomMaarchCourrierModulesThumbnailsScripts, 0755);

                    /** Création répertoire notification dans le custom **/

            $cheminCustomMaarchCourrierModulesNotifications = realpath('.')."/custom/cs_$databasename/modules/notifications";
            mkdir($cheminCustomMaarchCourrierModulesNotifications, 0755);

            $cheminCustomMaarchCourrierModulesNotificationsBatch = realpath('.')."/custom/cs_$databasename/modules/notifications/batch";
            mkdir($cheminCustomMaarchCourrierModulesNotificationsBatch, 0755);

            $cheminCustomMaarchCourrierModulesNotificationsConfig = realpath('.')."/custom/cs_$databasename/modules/notifications/batch/config";
            mkdir($cheminCustomMaarchCourrierModulesNotificationsConfig, 0755);

            $cheminCustomMaarchCourrierModulesNotificationsScripts = realpath('.')."/custom/cs_$databasename/modules/notifications/batch/scripts";
            mkdir($cheminCustomMaarchCourrierModulesNotificationsScripts, 0755);

            /** Création répertoire sendmail dans le custom **/

            $cheminCustomMaarchCourrierModulesSendmail = realpath('.')."/custom/cs_$databasename/modules/sendmail";
            mkdir($cheminCustomMaarchCourrierModulesSendmail, 0755);

            $cheminCustomMaarchCourrierModulesSendmailBatch = realpath('.')."/custom/cs_$databasename/modules/sendmail/batch";
            mkdir($cheminCustomMaarchCourrierModulesSendmailBatch, 0755);

            $cheminCustomMaarchCourrierModulesSendmailBatchConfig = realpath('.')."/custom/cs_$databasename/modules/sendmail/batch/config";
            mkdir($cheminCustomMaarchCourrierModulesSendmailBatchConfig, 0755);

            $cheminCustomMaarchCourrierModulesSendmailBatchScripts = realpath('.')."/custom/cs_$databasename/modules/sendmail/batch/scripts";
            mkdir($cheminCustomMaarchCourrierModulesSendmailBatchScripts, 0755);


            /** Création répertoire LDAP dans le custom **/

            $cheminCustomMaarchCourrierModulesLdap = realpath('.')."/custom/cs_$databasename/modules/ldap";
            mkdir($cheminCustomMaarchCourrierModulesLdap, 0755);

            $cheminCustomMaarchCourrierModulesLdapXml = realpath('.')."/custom/cs_$databasename/modules/ldap/xml";
            mkdir($cheminCustomMaarchCourrierModulesLdapXml, 0755);

            $cheminCustomMaarchCourrierModulesLdapScript = realpath('.')."/custom/cs_$databasename/modules/ldap/script";
            mkdir($cheminCustomMaarchCourrierModulesLdapScript, 0755);



            /** Création répertoire fulltext dans le custom **/

            $cheminCustomMaarchCourrierModulesFullText = realpath('.')."/custom/cs_$databasename/modules/full_text";
            mkdir($cheminCustomMaarchCourrierModulesFullText, 0755);

            $cheminCustomMaarchCourrierModulesFullTextXml = realpath('.')."/custom/cs_$databasename/modules/full_text/xml";
            mkdir($cheminCustomMaarchCourrierModulesFullTextXml, 0755);

            $cheminCustomMaarchCourrierModulesFullTextScripts = realpath('.')."/custom/cs_$databasename/modules/full_text/scripts";
            mkdir($cheminCustomMaarchCourrierModulesFullTextScripts, 0755);
            // exit;
			
			//Création du lien symbolique sous linux
            if(strtoupper(substr(PHP_OS, 0, 3)) === 'LIN') {
                $cmd = "ln -s ".realpath('.')."/ cs_$databasename";
                exec($cmd);

            }/*elseif(strtoupper(substr(PHP_OS, 0, 3)) === 'WIN'){
                $cmd = "mklink cs_$databasename ".realpath('.');
				var_dump($cmd);
				var_dump(exec($cmd));
				exit;
                exec($cmd);
            }*/ 
			// Création du lien symbolique sous windows mais il faut être en administrateur pour lancer la commande : mklink nomDuCustom cheminDeLAPPLI
        }

        return true;
        
    }


    public function updateDocserverForXml($docserverPath)
    {
        $xmlconfig = simplexml_load_file(realpath('.')."/custom/cs_".$_SESSION['config']['databasename']."/apps/maarch_entreprise/xml/config.xml");
        //$xmlconfig = 'apps/maarch_entreprise/xml/config.xml.default';
        $COLLECTION_1 = $xmlconfig->COLLECTION[0];
        $COLLECTION_1->path_to_lucene_index = $docserverPath."indexes/letterbox_coll/";

        $COLLECTION_2 = $xmlconfig->COLLECTION[1];
        $COLLECTION_2->path_to_lucene_index = $docserverPath."indexes/attachments_coll/";

        $COLLECTION_3 = $xmlconfig->COLLECTION[2];
        $COLLECTION_3->path_to_lucene_index = $docserverPath."indexes/version_attachments_coll/";
        
        $res = $xmlconfig->asXML();
        // $fp = @fopen("apps/maarch_entreprise/xml/config.xml", "w+");
        $fp = @fopen(realpath('.')."/custom/cs_".$_SESSION['config']['databasename']."/apps/maarch_entreprise/xml/config.xml", "w+");
        if (!$fp) {
            return false;
            exit;
        }
        $write = fwrite($fp,$res);
        if (!$write) {
            return false;
            exit;
        }
        //configuration du chemin dans config_batch_letterbox.xml
        $xmlconfig2 = simplexml_load_file(realpath('.')."/custom/cs_".$_SESSION['config']['databasename']."/modules/full_text/xml/config_batch_letterbox.xml");
        //$xmlconfig = 'apps/maarch_entreprise/xml/config.xml.default';
        $CONFIG = $xmlconfig2->CONFIG;

        $CONFIG->INDEX_FILE_DIRECTORY = $docserverPath."indexes/letterbox_coll/";
        
        $res = $xmlconfig2->asXML();
        // $fp = @fopen("apps/maarch_entreprise/xml/config.xml", "w+");
        $fp = @fopen(realpath('.')."/custom/cs_".$_SESSION['config']['databasename']."/modules/full_text/xml/config_batch_letterbox.xml", "w+");
        if (!$fp) {
            return false;
            exit;
        }
        $write = fwrite($fp,$res);
        if (!$write) {
            return false;
            exit;
        }

        //configuration du chemin dans config_batch_attachments.xml
        $xmlconfig3 = simplexml_load_file(realpath('.')."/custom/cs_".$_SESSION['config']['databasename']."/modules/full_text/xml/config_batch_attachments.xml");
        //$xmlconfig = 'apps/maarch_entreprise/xml/config.xml.default';
        $CONFIG = $xmlconfig3->CONFIG;

        $CONFIG->INDEX_FILE_DIRECTORY = $docserverPath."indexes/attachments_coll/";
        
        $res = $xmlconfig3->asXML();
        // $fp = @fopen("apps/maarch_entreprise/xml/config.xml", "w+");
        $fp = @fopen(realpath('.')."/custom/cs_".$_SESSION['config']['databasename']."/modules/full_text/xml/config_batch_attachments.xml", "w+");
        if (!$fp) {
            return false;
            exit;
        }
        $write = fwrite($fp,$res);
        if (!$write) {
            return false;
            exit;
        }

        //configuration du chemin dans config_batch_version_attachments.xml
        $xmlconfig4 = simplexml_load_file(realpath('.')."/custom/cs_".$_SESSION['config']['databasename']."/modules/full_text/xml/config_batch_version_attachments.xml");
        //$xmlconfig = 'apps/maarch_entreprise/xml/config.xml.default';
        $CONFIG = $xmlconfig4->CONFIG;

        $CONFIG->INDEX_FILE_DIRECTORY = $docserverPath."indexes/version_attachments_coll/";
        
        $res = $xmlconfig4->asXML();
        // $fp = @fopen("apps/maarch_entreprise/xml/config.xml", "w+");
        $fp = @fopen(realpath('.')."/custom/cs_".$_SESSION['config']['databasename']."/modules/full_text/xml/config_batch_version_attachments.xml", "w+");
        if (!$fp) {
            return false;
            exit;
        }
        $write = fwrite($fp,$res);
        if (!$write) {
            return false;
            exit;
        }


        return true;
        
    }

    public function verificationDatabase($databasename){

        $connect  = 'host='.$_SESSION['config']['databaseserver'] . ' ';
        $connect .= 'port='.$_SESSION['config']['databaseserverport'] . ' ';
        $connect .= 'user='.$_SESSION['config']['databaseuser'] . ' ';
        $connect .= 'password='.$_SESSION['config']['databasepassword'] . ' ';
        $connect .= 'dbname=postgres';

        //var_dump($connect);

        if (!@pg_connect($connect)) {
            return false;
            exit;
        }  
        
        $sqlCreateDatabase  = "select datname from pg_database where datname = '".$databasename."'";

        $result = @pg_query($sqlCreateDatabase);
        if (!$result) {
          echo "Une erreur s'est produite.\n";
          exit;
        }

        while ($row = pg_fetch_row($result)) {
          //echo "datname: $row[0] ";
          if($row[0]){
            return false;
          }
        }

        return true;
        
        //var_dump($execute);       

    }
        public function verifCustom($databasename){

            $customAlreadyExist = realpath('.').'/custom/cs_'.$databasename;
            if(file_exists($customAlreadyExist)){ 
            return false;

            }
       

    }

    public function fillConfigOfAppAndModule($databasename){
        $_SESSION['config']['databasename'] = $databasename;
        $connect  = 'host='.$_SESSION['config']['databaseserver'] . ' ';
        $connect .= 'port='.$_SESSION['config']['databaseserverport'] . ' ';
        $connect .= 'user='.$_SESSION['config']['databaseuser'] . ' ';
        $connect .= 'password='.$_SESSION['config']['databasepassword'] . ' ';
        $connect .= 'dbname=postgres';

        if (!$this->setConfigXmlThumbnails()) {
            return false;
            exit;
        }
        if (!$this->setConfig_batch_XmlThumbnails()) {
            return false;
            exit;
        }        
        if (!$this->setConfigScriptLaunchThumbnails()) {
            return false;
            exit;
        }
        if (!$this->setConfig_sendmail()) {
            return false;
            exit;
        }


       if (!$this->setConfigXml()) {
            return false;
            exit;
        }

        if (!$this->setConfigXmlVisa()) {
            return false;
            exit;
        }

        if (!$this->setScriptNotificationSendmailSh()) {
            return false;
            exit;
        }

        if (!$this->setScriptNotificationNctNccAndAncSh()) {
            return false;
            exit;
        }

        if (!$this->setScriptSendmailSendmailSh()) {
            return false;
            exit;
        }

        if (!$this->setConfig_LDAP()) {
            return false;
            exit;
        }

        if (!$this->setScript_syn_LDAP_sh()) {
            return false;
            exit;
        }

        if (!$this->setConfig_fulltext()) {
            return false;
            exit;
        }

        if (!$this->setScript_full_text()) {
            return false;
            exit;
        }
        
        if (!$this->setConfig_batch_XmlNotifications()) {
            return false;
            exit;
        }

        if (!$this->setConfig_batch_XmlSendmail()) {
            return false;
            exit;
        }

        if (!$this->setLog4php()) {
            return false;
            exit;
        }

        if (!$this->setConfigCron()) {
            return false;
            exit;
        }

        if (!$this->setRight()) {
            return false;
            exit;
        }

        if (!$this->setSvnUpdateAll()) {
            return false;
            exit;
        }

        return true;

    }

    public function createDatabase(
        $databasename
    )
    {

		
        $connect  = 'host='.$_SESSION['config']['databaseserver'] . ' ';
        $connect .= 'port='.$_SESSION['config']['databaseserverport'] . ' ';
        $connect .= 'user='.$_SESSION['config']['databaseuser'] . ' ';
        $connect .= 'password='.$_SESSION['config']['databasepassword'] . ' ';
        $connect .= 'dbname=postgres';
        if (!@pg_connect($connect)) {

            return false;
            exit;
        }

        $sqlCreateDatabase  = 'CREATE DATABASE "'.$databasename.'"';
            $sqlCreateDatabase .= " WITH TEMPLATE template0";
            $sqlCreateDatabase .= " ENCODING = 'UTF8'";

        $execute = pg_query($sqlCreateDatabase);
        if (!$execute) {
            return false;
            exit;
        }
        
        @pg_query('ALTER DATABASE "'.$databasename.'" SET DateStyle =iso, dmy');
        
        pg_close();

        $db = new Database();
        
        if (!$db) {
            return false;
            exit;
        }
		
        if (!$this->executeSQLScript('sql/structure.sql')) {
            return false;
            exit;
        }

        
        if (!$this->setConfigXmlThumbnails()) {
            return false;
            exit;
        }
        if (!$this->setConfig_batch_XmlThumbnails()) {
            return false;
            exit;
        }        
        if (!$this->setConfigScriptLaunchThumbnails()) {
            return false;
            exit;
        }
        if (!$this->setConfig_sendmail()) {
            return false;
            exit;
        }


       if (!$this->setConfigXml()) {
            return false;
            exit;
        }

        if (!$this->setConfigXmlVisa()) {
            return false;
            exit;
        }

        if (!$this->setScriptNotificationSendmailSh()) {
            return false;
            exit;
        }

        if (!$this->setScriptNotificationNctNccAndAncSh()) {
            return false;
            exit;
        }

        if (!$this->setScriptSendmailSendmailSh()) {
            return false;
            exit;
        }

        if (!$this->setConfig_LDAP()) {
            return false;
            exit;
        }

        if (!$this->setScript_syn_LDAP_sh()) {
            return false;
            exit;
        }

        if (!$this->setConfig_fulltext()) {
            return false;
            exit;
        }

        if (!$this->setScript_full_text()) {
            return false;
            exit;
        }
        
        if (!$this->setConfig_batch_XmlNotifications()) {
            return false;
            exit;
        }

        if (!$this->setConfig_batch_XmlSendmail()) {
            return false;
            exit;
        }

        if (!$this->setLog4php()) {
            return false;
            exit;
        }

        if (!$this->setConfigCron()) {
            return false;
            exit;
        }
        
        if (!$this->setRight()) {
            return false;
            exit;
        }

        if (!$this->setSvnUpdateAll()) {
            return false;
            exit;
        }

       /*if (!$this->setDatasourcesXsd()) {
            return false;
            exit;
        }*/
        
        return true;
    }

    private function setRight(){
        exec('chmod -R 770 *');
        return true;

    }

    private function setSvnUpdateAll(){
        $res = '#!/bin/bash';
        $res .= "\n";
        $res .= "svn up ".realpath('.')."/.";
        $res .= "\n";
        $res .= "svn up ".realpath('.')."/apps/maarch_entreprise/*";
        $res .= "\n";
        $res .= "svn up ".realpath('.')."/core/*";
        $res .= "\n";
        $res .= "svn up ".realpath('.')."/modules/*";
        $res .= "\n";

            $fp = @fopen(realpath('.')."/svnupdateall.sh", "w+");
        if (!$fp) {
            var_dump("false error dans setScript_full_text()");
            return false;
            exit;
        }
        $write = fwrite($fp,$res);
        if (!$write) {
            return false;
            exit;
        }
        return true;
    }

    private function setConfigCron()
    {

        //mkdir(realpath('.')."/custom/cs_".$_SESSION['config']['databasename']."/conf_cron/");
        $output = shell_exec('crontab -l');
        //var_dump($output);
        $pathfile = realpath('.')."/custom/cs_".$_SESSION['config']['databasename']."/cron_".$_SESSION['config']['databasename'];
        $file = fopen("custom/cs_".$_SESSION['config']['databasename']."/cron_".$_SESSION['config']['databasename'], "w+");
        fwrite($file,$output);
        //ftruncate($file,0);
        $cron = '

####################################################################################
#                                                                                  #
#                                                                                  #
#                                       '.$_SESSION['config']['databasename'].'                                      #
#                                                                                  #
#                                                                                  #
####################################################################################


######################THUMBNAILS####################################################
* * * * *       '.realpath('.').'/custom/cs_'.$_SESSION['config']['databasename'].'/modules/thumbnails/scripts/launch_batch_thumbnails.sh
15 12 1 * *        rm -Rf '.realpath('.').'/modules/thumbnails/log/*.log

######################notification#################################################

15 10 * * *     '.realpath('.').'/custom/cs_'.$_SESSION['config']['databasename'].'/modules/notifications/batch/scripts/nct-ncc-and-anc.sh
15 15 * * *     '.realpath('.').'/custom/cs_'.$_SESSION['config']['databasename'].'/modules/notifications/batch/scripts/nct-ncc-and-anc.sh
15 12 * * *     '.realpath('.').'/custom/cs_'.$_SESSION['config']['databasename'].'/modules/notifications/batch/scripts/nct-ncc-and-anc.sh


30 10 * * *     '.realpath('.').'/custom/cs_'.$_SESSION['config']['databasename'].'/modules/notifications/batch/scripts/sendmail.sh
30 15 * * *     '.realpath('.').'/custom/cs_'.$_SESSION['config']['databasename'].'/modules/notifications/batch/scripts/sendmail.sh
30 12 * * *     '.realpath('.').'/custom/cs_'.$_SESSION['config']['databasename'].'/modules/notifications/batch/scripts/sendmail.sh

10 12 1 * *        rm -Rf '.realpath('.').'/modules/notifications/batch/logs/process_event_stack/*.log
11 12 1 * *        rm -Rf '.realpath('.').'/modules/notifications/batch/logs/process_email_stack/*.log
######################sendmail####################################################

* * * * *       '.realpath('.').'/custom/cs_'.$_SESSION['config']['databasename'].'/modules/sendmail/batch/scripts/sendmail.sh >/dev/null 2>&1

0 12 1 * *     rm -Rf '.realpath('.').'/modules/sendmail/batch/logs/*.log

######################fulltext###################################################

* * * * *       '.realpath('.').'/custom/cs_'.$_SESSION['config']['databasename'].'/modules/full_text/scripts/launch_fulltext.sh
20 12 1 * *        rm -Rf '.realpath('.').'/modules/full_text/log/*.log
';
        fwrite($file,$cron);
        fclose($file);
        exec('crontab '.$pathfile);

        $output = exec('crontab -l');
        //$fileCrontab = fopen("custom/cs_".$_SESSION['config']['databasename']."/crontabL_".$_SESSION['config']['databasename'], "a+");

        // fwrite($fileCrontab,$output);
        // fclose($fileCrontab);
        return true;
    }

    private function setLog4php(){
        $xmlconfig = simplexml_load_file('apps/maarch_entreprise/xml/log4php.default.xml');
        $LOG4PHP = $xmlconfig->log4php;
        $appender = $xmlconfig->appender;
        $param = $appender->param;
        $appender->param['value'] = realpath('.').'/fonctionnel.log';

        $appender = $xmlconfig->appender[1];
        $param = $appender->param;
        $appender->param['value'] = realpath('.').'/technique.log';

        $res = $xmlconfig->asXML();
        $fp = @fopen(realpath('.')."/custom/cs_".$_SESSION['config']['databasename']."/apps/maarch_entreprise/xml/log4php.xml", "w+");
        if (!$fp) {
            return false;
            exit;
        }
        $write = fwrite($fp,$res);
        if (!$write) {
            return false;
            exit;
        }
        return true;

    }

    private function setConfigXml()
    {
        $xmlconfig = simplexml_load_file('apps/maarch_entreprise/xml/config.xml.default');
        //$xmlconfig = 'apps/maarch_entreprise/xml/config.xml.default';
        $CONFIG = $xmlconfig->CONFIG;

        $CONFIG->databaseserver = $_SESSION['config']['databaseserver'];
        $CONFIG->databaseserverport = $_SESSION['config']['databaseserverport'];
        $CONFIG->databasename = $_SESSION['config']['databasename'];
        $CONFIG->databaseuser = $_SESSION['config']['databaseuser'];
        $CONFIG->databasepassword = $_SESSION['config']['databasepassword'];
        $CONFIG->lang = $_SESSION['lang'];
        $res = $xmlconfig->asXML();
        // $fp = @fopen("apps/maarch_entreprise/xml/config.xml", "w+");
        $fp = @fopen(realpath('.')."/custom/cs_".$_SESSION['config']['databasename']."/apps/maarch_entreprise/xml/config.xml", "w+");
        if (!$fp) {
            return false;
            exit;
        }
        $write = fwrite($fp,$res);
        if (!$write) {
            return false;
            exit;
        }
        return true;
    }

    private function setConfigXmlVisa()
    {
        $xmlconfig = simplexml_load_file('modules/visa/xml/config.xml.default');
        $CONFIG = $xmlconfig->CONFIG;
        //TODO fill the file...

        $res = $xmlconfig->asXML();
        $fp = @fopen("modules/visa/xml/config.xml", "w+");
        if (!$fp) {
            return false;
            exit;
        }
        $write = fwrite($fp,$res);
        if (!$write) {
            return false;
            exit;
        }
        return true;
    }

    private function setConfigXmlThumbnails()
    {
        //var_dump("setConfigXmlThumbnails");
        $xmlconfig = simplexml_load_file('modules/thumbnails/xml/config.xml.default');
        //$xmlconfig = 'apps/maarch_entreprise/xml/config.xml.default';
        $CONFIG = $xmlconfig->CONFIG;

        $CONFIG->docserver_id = 'TNL';
        $chemin_no_file = realpath('.').'/modules/thumbnails/no_thumb.png';
        $CONFIG->no_file = $chemin_no_file;
        $res = $xmlconfig->asXML();
        $fp = @fopen(realpath('.')."/custom/cs_".$_SESSION['config']['databasename']."/modules/thumbnails/xml/config.xml", "w+");
        if (!$fp) {
            return false;
            exit;
        }
        $write = fwrite($fp,$res);
        if (!$write) {
            return false;
            exit;
        }
        return true;
    }

    private function setConfig_batch_XmlThumbnails()
    {
        $xmlconfig = simplexml_load_file('modules/thumbnails/xml/config_batch_letterbox.xml.default');

        $CONFIG = $xmlconfig->CONFIG;

        $chemin_core = realpath('.').'/core/';

        $CONFIG->MaarchDirectory = realpath('.')."/";
        
        $CONFIG_BASE = $xmlconfig->CONFIG_BASE;
        $CONFIG_BASE->databaseserver = $_SESSION['config']['databaseserver'];
        $CONFIG_BASE->databaseserverport = $_SESSION['config']['databaseserverport'];
        $CONFIG_BASE->databasename = $_SESSION['config']['databasename'];
        $CONFIG_BASE->databaseuser = $_SESSION['config']['databaseuser'];
        $CONFIG_BASE->databasepassword = $_SESSION['config']['databasepassword'];

        $LOG4PHP = $xmlconfig->LOG4PHP;
        $LOG4PHP->Log4PhpConfigPath = realpath('.').'/custom/cs_'.$_SESSION['config']['databasename'].'/apps/maarch_entreprise/xml/log4php.xml';



        $res = $xmlconfig->asXML();
        $fp = @fopen(realpath('.')."/custom/cs_".$_SESSION['config']['databasename']."/modules/thumbnails/xml/config_batch_letterbox.xml", "w+");
        if (!$fp) {
            return false;
            exit;
        }

        $write = fwrite($fp,$res);
        if (!$write) {
            return false;
            exit;
        }
        $xmlconfig = simplexml_load_file('modules/thumbnails/xml/config_batch_attachments.xml.default');

        $CONFIG = $xmlconfig->CONFIG;

        $chemin_core = realpath('.').'/core/';

        $CONFIG->MaarchDirectory = realpath('.')."/";
        
        $CONFIG_BASE = $xmlconfig->CONFIG_BASE;
        $CONFIG_BASE->databaseserver = $_SESSION['config']['databaseserver'];
        $CONFIG_BASE->databaseserverport = $_SESSION['config']['databaseserverport'];
        $CONFIG_BASE->databasename = $_SESSION['config']['databasename'];
        $CONFIG_BASE->databaseuser = $_SESSION['config']['databaseuser'];
        $CONFIG_BASE->databasepassword = $_SESSION['config']['databasepassword'];

        $LOG4PHP = $xmlconfig->LOG4PHP;
        $LOG4PHP->Log4PhpConfigPath = realpath('.').'/custom/cs_'.$_SESSION['config']['databasename'].'/apps/maarch_entreprise/xml/log4php.xml';



        $res = $xmlconfig->asXML();
        $fp = @fopen(realpath('.')."/custom/cs_".$_SESSION['config']['databasename']."/modules/thumbnails/xml/config_batch_attachments.xml", "w+");
        if (!$fp) {
            return false;
            exit;
        }
        $write = fwrite($fp,$res);
        if (!$write) {
            return false;
            exit;
        }
        return true;
    }

    private function setConfig_batch_XmlNotifications()
    {
        $xmlconfig = simplexml_load_file('modules/notifications/batch/config/config.xml.default');

        $CONFIG = $xmlconfig->CONFIG;

        $chemin_core = realpath('.').'/core/';

        $CONFIG = $xmlconfig->CONFIG;
        $CONFIG->MaarchDirectory = realpath('.')."/";
        //$path = "ifconfig eth2 | grep 'inet addr' | cut -f2 -d: | awk '{print $1}'";
        //$ipconfig = shell_exec($path);
        //$ipconfig = trim($ipconfig);
        //$chemin = $ipconfig . dirname($_SERVER['PHP_SELF'] .'cs_'.$_SESSION['config']['databasename']);
        $chemin = $_SERVER['SERVER_ADDR'] . dirname($_SERVER['PHP_SELF'] .'cs_'.$_SESSION['config']['databasename']);
        $maarchUrl = rtrim($chemin, "install");
        $maarchUrl = $maarchUrl.'cs_'.$_SESSION['config']['databasename'].'/';
        $CONFIG->MaarchUrl = $maarchUrl;
        $CONFIG->MaarchApps = 'maarch_entreprise';
        $CONFIG->TmpDirectory = realpath('.').'/modules/notifications/batch/tmp/';
        
        $CONFIG_BASE = $xmlconfig->CONFIG_BASE;
        $CONFIG_BASE->databaseserver = $_SESSION['config']['databaseserver'];
        $CONFIG_BASE->databaseserverport = $_SESSION['config']['databaseserverport'];
        $CONFIG_BASE->databasename = $_SESSION['config']['databasename'];
        $CONFIG_BASE->databaseuser = $_SESSION['config']['databaseuser'];
        $CONFIG_BASE->databasepassword = $_SESSION['config']['databasepassword'];

        $LOG4PHP = $xmlconfig->LOG4PHP;
        $LOG4PHP->Log4PhpConfigPath = realpath('.').'/custom/cs_'.$_SESSION['config']['databasename'].'/apps/maarch_entreprise/xml/log4php.xml';



        $res = $xmlconfig->asXML();
        $fp = @fopen(realpath('.')."/custom/cs_".$_SESSION['config']['databasename']."/modules/notifications/batch/config/config.xml", "w+");
        if (!$fp) {
            return false;
            exit;
        }
        $write = fwrite($fp,$res);
        if (!$write) {
            return false;
            exit;
        }
        return true;
    }

                private function setConfig_batch_XmlSendmail()
    {
        $xmlconfig = simplexml_load_file('modules/sendmail/batch/config/config.xml.default');

        $CONFIG = $xmlconfig->CONFIG;

        $chemin_core = realpath('.').'/core/';

        $CONFIG->MaarchDirectory = realpath('.')."/";
        $chemin = $_SERVER['SERVER_NAME'] . dirname($_SERVER['PHP_SELF']);
        $maarchUrl = rtrim($chemin, "install");
        $CONFIG->MaarchUrl = $maarchUrl;
        $CONFIG->MaarchApps = 'maarch_entreprise';
        $CONFIG->TmpDirectory = realpath('.').'/modules/sendmail/batch/tmp/';
        
        $CONFIG_BASE = $xmlconfig->CONFIG_BASE;
        $CONFIG_BASE->databaseserver = $_SESSION['config']['databaseserver'];
        $CONFIG_BASE->databaseserverport = $_SESSION['config']['databaseserverport'];
        $CONFIG_BASE->databasename = $_SESSION['config']['databasename'];
        $CONFIG_BASE->databaseuser = $_SESSION['config']['databaseuser'];
        $CONFIG_BASE->databasepassword = $_SESSION['config']['databasepassword'];

        $LOG4PHP = $xmlconfig->LOG4PHP;
        $LOG4PHP->Log4PhpConfigPath = realpath('.').'/custom/cs_'.$_SESSION['config']['databasename'].'/apps/maarch_entreprise/xml/log4php.xml';



        $res = $xmlconfig->asXML();
        $fp = @fopen(realpath('.')."/custom/cs_".$_SESSION['config']['databasename']."/modules/sendmail/batch/config/config.xml", "w+");
        if (!$fp) {
            return false;
            exit;
        }
        $write = fwrite($fp,$res);
        if (!$write) {
            return false;
            exit;
        }
        return true;
    }

    private function setConfig_sendmail()
    {
        $xmlconfig = simplexml_load_file('modules/sendmail/batch/config/config.xml.default');
        //$xmlconfig = 'apps/maarch_entreprise/xml/config.xml.default';
        $CONFIG_BASE = $xmlconfig->CONFIG_BASE;

        $CONFIG_BASE->databaseserver = $_SESSION['config']['databaseserver'];
        $CONFIG_BASE->databaseserverport = $_SESSION['config']['databaseserverport'];
        $CONFIG_BASE->databasename = $_SESSION['config']['databasename'];
        $CONFIG_BASE->databaseuser = $_SESSION['config']['databaseuser'];
        $CONFIG_BASE->databasepassword = $_SESSION['config']['databasepassword'];
        $res = $xmlconfig->asXML();
        $fp = @fopen(realpath('.')."/custom/cs_".$_SESSION['config']['databasename']."/modules/sendmail/batch/config/config.xml", "w+");
        if (!$fp) {
            return false;
            exit;
        }
        $write = fwrite($fp,$res);
        if (!$write) {
            return false;
            exit;
        }
        return true;
    }

        private function setConfig_LDAP()
    {
        $xmlconfig = simplexml_load_file('modules/ldap/xml/config.xml.default');
        //$xmlconfig = 'apps/maarch_entreprise/xml/config.xml.default';
        $CONFIG_BASE = $xmlconfig->config_base;

        $CONFIG_BASE->databaseserver = $_SESSION['config']['databaseserver'];
        $CONFIG_BASE->databaseserverport = $_SESSION['config']['databaseserverport'];
        $CONFIG_BASE->databasename = $_SESSION['config']['databasename'];
        $CONFIG_BASE->databaseuser = $_SESSION['config']['databaseuser'];
        $CONFIG_BASE->databasepassword = $_SESSION['config']['databasepassword'];
        $res = $xmlconfig->asXML();
        $fp = @fopen(realpath('.')."/custom/cs_".$_SESSION['config']['databasename']."/modules/ldap/xml/config.xml", "w+");
        if (!$fp) {
            var_dump('fp error');
            return false;
            exit;
        }
        $write = fwrite($fp,$res);
        if (!$write) {
            var_dump('write error');
            return false;
            exit;
        }
        return true;
    }


    private function setConfig_fulltext()
    {
        //configuration du config_batch_letterbox.xml
        $xmlconfig = simplexml_load_file('modules/full_text/xml/config_batch_letterbox.xml.default');
        //$xmlconfig = 'apps/maarch_entreprise/xml/config.xml.default';

        $CONFIG = $xmlconfig->CONFIG;
        $CONFIG->MAARCH_DIRECTORY = realpath('.');
        $CONFIG->MAARCH_TOOLS_PATH = realpath('.')."/apps/maarch_entreprise/tools/";

        $CONFIG_BASE = $xmlconfig->CONFIG_BASE;
        $CONFIG_BASE->databaseserver = $_SESSION['config']['databaseserver'];
        $CONFIG_BASE->databaseserverport = $_SESSION['config']['databaseserverport'];
        $CONFIG_BASE->databasename = $_SESSION['config']['databasename'];
        $CONFIG_BASE->databaseuser = $_SESSION['config']['databaseuser'];
        $CONFIG_BASE->databasepassword = $_SESSION['config']['databasepassword'];

        $LOG4PHP = $xmlconfig->LOG4PHP;
        $LOG4PHP->Log4PhpConfigPath = realpath('.').'/custom/cs_'.$_SESSION['config']['databasename'].'/apps/maarch_entreprise/xml/log4php.xml';



        $res = $xmlconfig->asXML();
        $fp = @fopen(realpath('.')."/custom/cs_".$_SESSION['config']['databasename']."/modules/full_text/xml/config_batch_letterbox.xml", "w+");
        if (!$fp) {
            var_dump('fp error');
            return false;
            exit;
        }
        $write = fwrite($fp,$res);
        if (!$write) {
            var_dump('write error');
            return false;
            exit;
        }

        //configuration du config_batch_attachments.xml
        $xmlconfigattachments = simplexml_load_file('modules/full_text/xml/config_batch_attachments.xml.default');
        //$xmlconfig = 'apps/maarch_entreprise/xml/config.xml.default';

        $CONFIG = $xmlconfigattachments->CONFIG;
        $CONFIG->MAARCH_DIRECTORY = realpath('.');
        $CONFIG->MAARCH_TOOLS_PATH = realpath('.')."/apps/maarch_entreprise/tools/";

        $CONFIG_BASE = $xmlconfigattachments->CONFIG_BASE;
        $CONFIG_BASE->databaseserver = $_SESSION['config']['databaseserver'];
        $CONFIG_BASE->databaseserverport = $_SESSION['config']['databaseserverport'];
        $CONFIG_BASE->databasename = $_SESSION['config']['databasename'];
        $CONFIG_BASE->databaseuser = $_SESSION['config']['databaseuser'];
        $CONFIG_BASE->databasepassword = $_SESSION['config']['databasepassword'];

        $LOG4PHP = $xmlconfigattachments->LOG4PHP;
        $LOG4PHP->Log4PhpConfigPath = realpath('.').'/custom/cs_'.$_SESSION['config']['databasename'].'/apps/maarch_entreprise/xml/log4php.xml';



        $res = $xmlconfigattachments->asXML();
        $fp = @fopen(realpath('.')."/custom/cs_".$_SESSION['config']['databasename']."/modules/full_text/xml/config_batch_attachments.xml", "w+");
        if (!$fp) {
            var_dump('fp error');
            return false;
            exit;
        }
        $write = fwrite($fp,$res);
        if (!$write) {
            var_dump('write error');
            return false;
            exit;
        }

        //configuration du config_batch_version_attachments.xml
        $xmlconfigversionattachments = simplexml_load_file('modules/full_text/xml/config_batch_version_attachments.xml.default');
        //$xmlconfig = 'apps/maarch_entreprise/xml/config.xml.default';

        $CONFIG = $xmlconfigversionattachments->CONFIG;
        $CONFIG->MAARCH_DIRECTORY = realpath('.');
        $CONFIG->MAARCH_TOOLS_PATH = realpath('.')."/apps/maarch_entreprise/tools/";

        $CONFIG_BASE = $xmlconfigversionattachments->CONFIG_BASE;
        $CONFIG_BASE->databaseserver = $_SESSION['config']['databaseserver'];
        $CONFIG_BASE->databaseserverport = $_SESSION['config']['databaseserverport'];
        $CONFIG_BASE->databasename = $_SESSION['config']['databasename'];
        $CONFIG_BASE->databaseuser = $_SESSION['config']['databaseuser'];
        $CONFIG_BASE->databasepassword = $_SESSION['config']['databasepassword'];

        $LOG4PHP = $xmlconfigversionattachments->LOG4PHP;
        $LOG4PHP->Log4PhpConfigPath = realpath('.').'/custom/cs_'.$_SESSION['config']['databasename'].'/apps/maarch_entreprise/xml/log4php.xml';



        $res = $xmlconfigversionattachments->asXML();
        $fp = @fopen(realpath('.')."/custom/cs_".$_SESSION['config']['databasename']."/modules/full_text/xml/config_batch_version_attachments.xml", "w+");
        if (!$fp) {
            var_dump('fp error');
            return false;
            exit;
        }
        $write = fwrite($fp,$res);
        if (!$write) {
            var_dump('write error');
            return false;
            exit;
        }
        return true;
    }

        private function setScript_full_text()
    {
        if(strtoupper(substr(PHP_OS, 0, 3)) === 'LIN') {
            //configuration du lauch_fulltext.sh
            $res = '#!/bin/bash';
            $res .= "\n";
            //$res .= "file='".realpath('.')."/modules/full_text/lucene_full_text_engine.php'";
            $res .= "\n";
            $res .= "cd ".realpath('.')."/modules/full_text/";
            $res .= "\n";
            $res .= "php ".realpath('.')."/modules/full_text/lucene_full_text_engine.php ".realpath('.')."/custom/cs_".$_SESSION['config']['databasename']."/modules/full_text/xml/config_batch_letterbox.xml";
            $res .= "\n";

                $fp = @fopen(realpath('.')."/custom/cs_".$_SESSION['config']['databasename']."/modules/full_text/scripts/launch_fulltext.sh", "w+");
            if (!$fp) {
                var_dump("false error dans setScript_full_text()");
                return false;
                exit;
            }
            $write = fwrite($fp,$res);
            if (!$write) {
                return false;
                exit;
            }

            //configuration du lauch_fulltext_attachments.sh
            $res2 = '#!/bin/bash';
            $res2 .= "\n";
            //$res .= "file='".realpath('.')."/modules/full_text/lucene_full_text_engine.php'";
            $res2 .= "\n";
            $res2 .= "cd ".realpath('.')."/modules/full_text/";
            $res2 .= "\n";
            $res2 .= "php ".realpath('.')."/modules/full_text/lucene_full_text_engine.php ".realpath('.')."/custom/cs_".$_SESSION['config']['databasename']."/modules/full_text/xml/config_batch_attachments.xml";
            $res2 .= "\n";

                $fp2 = @fopen(realpath('.')."/custom/cs_".$_SESSION['config']['databasename']."/modules/full_text/scripts/launch_fulltext_attachments.sh", "w+");
            if (!$fp2) {
                var_dump("false error dans setScript_full_text()");
                return false;
                exit;
            }
            $write2 = fwrite($fp2,$res2);
            if (!$write2) {
                return false;
                exit;
            }

            //configuration du lauch_fulltext_version_attachments.sh
            $res3 = '#!/bin/bash';
            $res3 .= "\n";
            //$res .= "file='".realpath('.')."/modules/full_text/lucene_full_text_engine.php'";
            $res3 .= "\n";
            $res3 .= "cd ".realpath('.')."/modules/full_text/";
            $res3 .= "\n";
            $res3 .= "php ".realpath('.')."/modules/full_text/lucene_full_text_engine.php ".realpath('.')."/custom/cs_".$_SESSION['config']['databasename']."/modules/full_text/xml/config_batch_version_attachments.xml";
            $res3 .= "\n";

                $fp3 = @fopen(realpath('.')."/custom/cs_".$_SESSION['config']['databasename']."/modules/full_text/scripts/launch_fulltext_version_attachments.sh", "w+");
            if (!$fp3) {
                var_dump("false error dans setScript_full_text()");
                return false;
                exit;
            }
            $write3 = fwrite($fp3,$res3);
            if (!$write3) {
                return false;
                exit;
            }


            return true;
        }elseif(strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            //configuration du lauch_fulltext.sh
            $res = "cd ".realpath('.')."\modules\\full_text\\";
			$res .= "\n";
            $res .= '"'.realpath('.').'\..\..\php\php.exe" '.realpath('.').'\modules\full_text\lucene_full_text_engine.php  '.realpath('.')."/custom/cs_".$_SESSION['config']['databasename'].'\modules\full_text\xml\config_batch_letterbox.xml';
            $res .= "\n";


            $fp = @fopen(realpath('.')."/custom/cs_".$_SESSION['config']['databasename']."/modules/full_text/scripts/launch_fulltext.bat", "w+");
            if (!$fp) {
                var_dump("false error dans setScript_full_text()");
                return false;
                exit;
            }
            $write = fwrite($fp,$res);
            if (!$write) {
                return false;
                exit;
            }

            //configuration du lauch_fulltext_attachments.sh
            $res = "cd ".realpath('.')."\modules\\full_text\\";
            $res .= "\n";
            $res .= '"'.realpath('.').'\..\..\php\php.exe" '.realpath('.').'\modules\full_text\lucene_full_text_engine.php  '.realpath('.')."/custom/cs_".$_SESSION['config']['databasename'].'\modules\full_text\xml\config_batch_attachments.xml';
            $res .= "\n";


            $fp = @fopen(realpath('.')."/custom/cs_".$_SESSION['config']['databasename']."/modules/full_text/scripts/launch_fulltext_attachments.bat", "w+");
            if (!$fp) {
                var_dump("false error dans setScript_full_text()");
                return false;
                exit;
            }
            $write = fwrite($fp,$res);
            if (!$write) {
                return false;
                exit;
            }
            //configuration du lauch_fulltext_version_attachments.sh
            $res = "cd ".realpath('.')."\modules\\full_text\\";
            $res .= "\n";
            $res .= '"'.realpath('.').'\..\..\php\php.exe" '.realpath('.').'\modules\full_text\lucene_full_text_engine.php  '.realpath('.')."/custom/cs_".$_SESSION['config']['databasename'].'\modules\full_text\xml\config_batch_version_attachments.xml';
            $res .= "\n";


            $fp = @fopen(realpath('.')."/custom/cs_".$_SESSION['config']['databasename']."/modules/full_text/scripts/launch_fulltext_version_attachments.bat", "w+");
            if (!$fp) {
                var_dump("false error dans setScript_full_text()");
                return false;
                exit;
            }
            $write = fwrite($fp,$res);
            if (!$write) {
                return false;
                exit;
            }


            return true;

        }

    }    


        private function setScript_syn_LDAP_sh()
    {
        $res = '#!/bin/bash';
        $res .= "\n";
        $res .= "cd ".realpath('.')."/modules/ldap/script/";
        $res .= "\n\n";
        $res .= '#generation des fichiers xml';
        $res .= "\n";
        $res .= "php ".realpath('.')."/modules/ldap/process_ldap_to_xml.php ".realpath('.')."/custom/cs_".$_SESSION['config']['databasename']."/modules/ldap/xml/config.xml";
        $res .= "\n\n";
        $res .= '#mise a jour bdd';
        $res .= "\n";
        $res .= "php ".realpath('.')."/modules/ldap/process_entities_to_maarch.php ".realpath('.')."/custom/cs_".$_SESSION['config']['databasename']."/modules/ldap/xml/config.xml";
        $res .= "\n";
        $res .= "php ".realpath('.')."/modules/ldap/process_users_to_maarch.php ".realpath('.')."/custom/cs_".$_SESSION['config']['databasename']."/modules/ldap/xml/config.xml";
        $res .= "\n";
        $res .= "php ".realpath('.')."/modules/ldap/process_users_entities_to_maarch.php ".realpath('.')."/custom/cs_".$_SESSION['config']['databasename']."/modules/ldap/xml/config.xml";

            $fp = @fopen(realpath('.')."/custom/cs_".$_SESSION['config']['databasename']."/modules/ldap/script/syn_ldap.sh", "w+");
        if (!$fp) {
            var_dump("false error dans setScript_full_text()");
            return false;
            exit;
        }
        $write = fwrite($fp,$res);
        if (!$write) {
            return false;
            exit;
        }
        return true;

    }

    private function setConfigScriptLaunchThumbnails()
    {
        
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
			
			$res = "cd ".realpath('.')."\modules\\thumbnails\\"; 
			$res .= "\n";
            $res .= '"'.realpath('.').'\..\..\php\php.exe" '.realpath('.').'\modules\\thumbnails\create_tnl.php  '.realpath('.')."\custom\cs_".$_SESSION['config']['databasename'].'\modules\\thumbnails\xml\config_batch_letterbox.xml';
            $res .= "\n";
            $res .= '"'.realpath('.').'\..\..\php\php.exe" '.realpath('.').'\modules\\thumbnails\create_tnl.php  '.realpath('.')."\custom\cs_".$_SESSION['config']['databasename'].'\modules\\thumbnails\xml\config_batch_attachments.xml';
            $res .= "\n";


                $fp = @fopen(realpath('.')."\custom\cs_".$_SESSION['config']['databasename']."\modules\\thumbnails\scripts\launch_batch_thumbnails.bat", "w+");
            if (!$fp) {
                return false;
                exit;
            }
            $write = fwrite($fp,$res);
            if (!$write) {
                return false;
                exit;
            }
            return true;
            
        } elseif(strtoupper(substr(PHP_OS, 0, 3)) === 'LIN') {

            $res = '#!/bin/bash';
            $res .= "\n\n";
            $res .= 'cd '.realpath('.').'/modules/thumbnails/';
            $res .= "\n\n";
            $res .= "php ".realpath('.')."/modules/thumbnails/create_tnl.php ".realpath('.')."/custom/cs_".$_SESSION['config']['databasename']."/modules/thumbnails/xml/config_batch_letterbox.xml";
            $res .= "\n\n";
            $res .= "php ".realpath('.')."/modules/thumbnails/create_tnl.php ".realpath('.')."/custom/cs_".$_SESSION['config']['databasename']."/modules/thumbnails/xml/config_batch_attachments.xml";

                $fp = @fopen(realpath('.')."/custom/cs_".$_SESSION['config']['databasename']."/modules/thumbnails/scripts/launch_batch_thumbnails.sh", "w+");
            if (!$fp) {
                return false;
                exit;
            }
            $write = fwrite($fp,$res);
            if (!$write) {
                return false;
                exit;
            }
            return true;
            
        }

    }


    private function setScriptNotificationNctNccAndAncSh()
    {

        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {

            $res = "cd ".realpath('.')."\modules\\notifications\\";
            $res .= "\n";
            $res .= '"'.realpath('.').'\..\..\php\php.exe" '.realpath('.').'\modules\\notifications\batch\process_event_stack.php -c '.realpath('.')."\custom/cs_".$_SESSION['config']['databasename'].'\modules\\notifications\batch\config\config.xml\ -n NCT';
            $res .= "\n";
            $res .= '"'.realpath('.').'\..\..\php\php.exe" '.realpath('.').'\modules\\notifications\batch\process_event_stack.php -c '.realpath('.')."\custom/cs_".$_SESSION['config']['databasename'].'\modules\\notifications\batch\config\config.xml\ -n NCC';            
			$res .= "\n";
            $res .= '"'.realpath('.').'\..\..\php\php.exe" '.realpath('.').'\modules\\notifications\batch\process_event_stack.php -c '.realpath('.')."\custom/cs_".$_SESSION['config']['databasename'].'\modules\\notifications\batch\config\config.xml\ -n ANC';
            $res .= "\n";
            $res .= '"'.realpath('.').'\..\..\php\php.exe" '.realpath('.').'\modules\\notifications\batch\process_event_stack.php -c '.realpath('.')."\custom/cs_".$_SESSION['config']['databasename'].'\modules\\notifications\batch\config\config.xml\ -n AND';
            $res .= "\n";
            $res .= '"'.realpath('.').'\..\..\php\php.exe" '.realpath('.').'\modules\\notifications\batch\process_event_stack.php -c '.realpath('.')."\custom/cs_".$_SESSION['config']['databasename'].'\modules\\notifications\batch\config\config.xml\ -n RED';

                $fp = @fopen(realpath('.')."/custom/cs_".$_SESSION['config']['databasename']."/modules/notifications/batch/scripts/nct-ncc-and-anc.bat", "w+");
            if (!$fp) {
                return false;
                exit;
            }
            $write = fwrite($fp,$res);
            if (!$write) {
                return false;
                exit;
            }
            return true;


            
        } elseif(strtoupper(substr(PHP_OS, 0, 3)) === 'LIN') {

            
            $res = '#!/bin/bash';
            $res .= "\n";
            $res .= "eventStackPath='".realpath('.')."/modules/notifications/batch/process_event_stack.php'";
            $res .= "\n";
            $res .= "cd ".realpath('.')."/modules/notifications/batch/";
            $res .= "\n";
            $res .= 'php $eventStackPath -c '.realpath('.').'/custom/cs_'.$_SESSION['config']['databasename'].'/modules/notifications/batch/config/config.xml -n NCT';
            $res .= "\n";
            $res .= 'php $eventStackPath -c '.realpath('.').'/custom/cs_'.$_SESSION['config']['databasename'].'/modules/notifications/batch/config/config.xml -n NCC';
            $res .= "\n";
            $res .= 'php $eventStackPath -c '.realpath('.').'/custom/cs_'.$_SESSION['config']['databasename'].'/modules/notifications/batch/config/config.xml -n ANC';
            $res .= "\n";
            $res .= 'php $eventStackPath -c '.realpath('.').'/custom/cs_'.$_SESSION['config']['databasename'].'/modules/notifications/batch/config/config.xml -n AND';
            $res .= "\n";
            $res .= 'php $eventStackPath -c '.realpath('.').'/custom/cs_'.$_SESSION['config']['databasename'].'/modules/notifications/batch/config/config.xml -n RED';

                $fp = @fopen(realpath('.')."/custom/cs_".$_SESSION['config']['databasename']."/modules/notifications/batch/scripts/nct-ncc-and-anc.sh", "w+");
            if (!$fp) {
                return false;
                exit;
            }
            $write = fwrite($fp,$res);
            if (!$write) {
                return false;
                exit;
            }
            return true;
        }


    }

    private function setScriptNotificationSendmailSh(){


        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {

			$res = "cd ".realpath('.')."\modules\\notifications\\";
            $res .= "\n";
            $res .= '"'.realpath('.').'\..\..\php\php.exe" '.realpath('.').'\modules\\notifications\batch\process_email_stack.php -c '.realpath('.')."\custom\cs_".$_SESSION['config']['databasename'].'\modules\\notifications\batch\config\config.xml';
            $res .= "\n";

                $fp = fopen(realpath('.')."/custom/cs_".$_SESSION['config']['databasename']."/modules/notifications/batch/scripts/sendmail.bat", "w+");
            if (!$fp) {
                //var_dump('FALSE');
                return false;
                exit;
            }
            $write = fwrite($fp,$res);
            if (!$write) {
                return false;
                exit;
            }
            return true;


            
        } elseif(strtoupper(substr(PHP_OS, 0, 3)) === 'LIN') {

            $res = '#!/bin/bash';
            $res .= "\n";
            $res .= "cd ".realpath('.')."/modules/notifications/batch/";
            $res .= "\n";
            $res .= "emailStackPath='".realpath('.')."/modules/notifications/batch/process_email_stack.php'";
            $res .= "\n";
            $res .= 'php $emailStackPath -c '.realpath('.').'/custom/cs_'.$_SESSION['config']['databasename'].'/modules/notifications/batch/config/config.xml';

                $fp = fopen(realpath('.')."/custom/cs_".$_SESSION['config']['databasename']."/modules/notifications/batch/scripts/sendmail.sh", "w+");

            if (!$fp) {
                //var_dump('FALSE');
                return false;
                exit;
            }
            $write = fwrite($fp,$res);
            if (!$write) {
                return false;
                exit;
            }
            return true;
            
        }

    }
    
    private function setScriptSendmailSendmailSh()
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
			
            $res = "cd ".realpath('.')."\modules\\sendmail\\";
            $res .= "\n";
            $res .= '"'.realpath('.').'\..\..\php\php.exe" '.realpath('.').'\modules\sendmail\batch\process_emails.php -c '.realpath('.')."\custom/cs_".$_SESSION['config']['databasename'].'\modules\sendmail\batch\config\config.xml';
            
            $fp = fopen(realpath('.')."/custom/cs_".$_SESSION['config']['databasename']."/modules/sendmail/batch/scripts/sendmail.bat", "w+");
            if (!$fp) {
                //var_dump('FALSE');
                return false;
                exit;
            }
            $write = fwrite($fp,$res);
            if (!$write) {
                return false;
                exit;
            }
            return true;


        } elseif(strtoupper(substr(PHP_OS, 0, 3)) === 'LIN') {

           
            $res = '#!/bin/bash';
            $res .= "\n";
            $res .= "cd ".realpath('.')."/modules/sendmail/batch/";
            $res .= "\n";
            $res .= "emailStackPath='".realpath('.')."/modules/sendmail/batch/process_emails.php'";
            $res .= "\n";
            $res .= 'php $emailStackPath -c '.realpath('.').'/custom/cs_'.$_SESSION['config']['databasename'].'/modules/sendmail/batch/config/config.xml';


                $fp = fopen(realpath('.')."/custom/cs_".$_SESSION['config']['databasename']."/modules/sendmail/batch/scripts/sendmail.sh", "w+");
            if (!$fp) {
                //var_dump('FALSE');
                //exit;
                return false;
                exit;
            }
            $write = fwrite($fp,$res);
            if (!$write) {
                return false;
                exit;
            }
            return true;
            
        }

    }


    private function setDatasourcesXsd()
    {
        $Fnm = 'apps/maarch_entreprise/xml/datasources.xsd.default';
        $inF = fopen($Fnm,"r");
        while (!feof($inF)) {
           $contentFile .= fgets($inF, 4096);
        }
        $contentFile = str_replace("##databaseserver##", $_SESSION['config']['databaseserver'], $contentFile);
        $contentFile = str_replace("##databaseserverport##", $_SESSION['config']['databaseserverport'], $contentFile);
        $contentFile = str_replace("##databasename##", $_SESSION['config']['databasename'], $contentFile);
        $contentFile = str_replace("##databaseuser##", $_SESSION['config']['databaseuser'], $contentFile);
        $contentFile = str_replace("##databasepassword##", $_SESSION['config']['databasepassword'], $contentFile);
        fclose($inF);
        if (file_exists('apps/maarch_entreprise/xml/datasources.xsd')) {
            unlink('apps/maarch_entreprise/xml/datasources.xsd');
        }
        copy('apps/maarch_entreprise/xml/datasources.xsd.default', 'apps/maarch_entreprise/xml/datasources.xsd'); 
        $fp = fopen('apps/maarch_entreprise/xml/datasources.xsd', "w+");
        if (!$fp) {
            return false;
            exit;
        }
        $write = fwrite($fp, $contentFile);
        if (!$write) {
            return false;
            exit;
        }
        return true;
    }


    public function getDataList()
    {
        $sqlList = array();
        foreach(glob('sql/data*.sql') as $fileSqlPath) {
            $sqlFile = str_replace('.sql', '', end(explode('/', $fileSqlPath)));
            array_push($sqlList, $sqlFile);
        }

        return $sqlList;
    }

    public function createData(
        $dataFile
    )
    {
        $db = new Database();
        
        if (!$db) {
            return false;
            exit;
        }

        if (!$this->executeSQLScript($dataFile)) {
            return false;
            exit;
        }
        return true;
    }

    public function executeSQLScript($filePath)
    {
        $fileContent = fread(fopen($filePath, 'r'), filesize($filePath));
        $db = new Database();
        
        $execute = $db->query($fileContent, null, false, true);

        if (!$execute) {
            return false;
            exit;
        }
        return true;
    }

    /**
     * test if maarch path is writable
     * @return boolean or error message
     */
    public function isMaarchPathWritable()
    {
        if (!is_writable('.')
                || !is_readable('.')
        ) {
            $error .= _THE_MAARCH_PATH_DOES_NOT_HAVE_THE_ADEQUATE_RIGHTS;
        } else {
            return true;
        }
    }

    /**
     * test if docserver path is read/write
     * @param $docserverPath string path to the docserver
     * @return boolean or error message
     */
    public function checkDocserverRoot($docserverPath)
    {
        if (!is_dir($docserverPath)) {
            $error .= _PATH_OF_DOCSERVER_UNAPPROACHABLE;
        } else {
            if (!is_writable($docserverPath)
                || !is_readable($docserverPath)
            ) {
                $error .= _THE_DOCSERVER_DOES_NOT_HAVE_THE_ADEQUATE_RIGHTS;
            }
        }
        if ($error <> '') {
            return $error;
        } else {
            return true;
        }
    }

    /**
     * create the docservers
     * @param $docserverPath string path to the docserver
     * @return boolean
     */
    public function createDocservers($docserverPath)
    {
        for ($i=0;$i<count($this->docservers);$i++) {
            if (!is_dir(
                $docserverPath . DIRECTORY_SEPARATOR
                    . $this->docservers[$i][1])
            ) {
                if (!mkdir(
                    $docserverPath . DIRECTORY_SEPARATOR
                        . $this->docservers[$i][1])
                ) {
                    return false;
                }
            }
        }

        //create indexes dir
        if (!is_dir(
            $docserverPath . DIRECTORY_SEPARATOR
                . 'indexes')
        ) {
            if (!mkdir(
                $docserverPath . DIRECTORY_SEPARATOR
                    . 'indexes')
            ) {
                return false;
            }
        }
        //create indexes dir for letterbox collection
        if (!is_dir(
            $docserverPath . DIRECTORY_SEPARATOR
                . 'indexes' . DIRECTORY_SEPARATOR . 'letterbox_coll')
        ) {
            if (!mkdir(
                $docserverPath . DIRECTORY_SEPARATOR
                    . 'indexes' . DIRECTORY_SEPARATOR . 'letterbox_coll')
            ) {
                return false;
            }
        }

        //copy template files
        $dir2copy = 'install' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . '0000'. DIRECTORY_SEPARATOR;
        $dir_paste = $docserverPath . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . '0000' . DIRECTORY_SEPARATOR;
        
        copy_dir($dir2copy,$dir_paste);

        return true;
    }

    /**
     * update the docservers on DB
     * @param $docserverPath string path to the docserver
     * @return nothing
     */
    public function updateDocserversDB($docserverPath)
    {
        $db = new Database();
        
        for ($i=0;$i<count($this->docservers);$i++) {
            $query = "update docservers set path_template = ?"
                . " where docserver_id = ?";
            $db->query(
                $query, 
                array(
                    $db->protect_string_db($docserverPath . DIRECTORY_SEPARATOR
                        . $this->docservers[$i][1] . DIRECTORY_SEPARATOR),
                    $this->docservers[$i][0]
                )
            );
        }
    }

    public function setSuperadminPass(
        $newPass
    )
    {
        $db = new Database();
        $sec = new security();
        
        $query = "UPDATE users SET password=? WHERE user_id='superadmin'";
        $db->query($query, array($sec->getPasswordHash($newPass)));
    }
}

function copy_dir($dir2copy,$dir_paste)
{
    // On vérifie si $dir2copy est un dossier
    if (is_dir($dir2copy))
    {

        // Si oui, on l'ouvre
        if ($dh = opendir($dir2copy))
        {

            // On liste les dossiers et fichiers de $dir2copy
            while (($file = readdir($dh)) !== false)
            {
                // Si le dossier dans lequel on veut coller n'existe pas, on le cree
                if (!is_dir($dir_paste)) mkdir ($dir_paste, 0777);

                // S'il s'agit d'un dossier, on relance la fonction recursive
                if(is_dir($dir2copy.$file) && $file != '..' && $file != '.') copy_dir ( $dir2copy.$file.'/' , $dir_paste.$file.'/' );

                // S'il sagit d'un fichier, on le copue simplement
                elseif($file != '..' && $file != '.') copy ( $dir2copy.$file , $dir_paste.$file );
            }

            // On ferme $dir2copy
            closedir($dh);
        }
    }
}
