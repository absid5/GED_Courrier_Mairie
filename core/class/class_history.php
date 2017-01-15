<?php
/**
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

/**
* @brief   Contains all the function to manage the history table
*
* <ul>
*   <li>Connexion logs and events history management</li>
* </ul>
* @file
* @author Claire Figueras <dev@maarch.org>
* @author Cyril Vazquez <dev@maarch.org>
* @author Arnaud Veber <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup core
*/

/**
* @brief   Contains all the function to manage the history table
*
* @ingroup core
*/

if (!defined('_LOG4PHP'))
    define(
        '_LOG4PHP',
        'log4php'
    );

if (!defined('_BDD'))
    define(
        '_BDD',
        'database'
    );

if (!defined('_LOGGER_NAME_TECH_DEFAULT'))
    define(
        '_LOGGER_NAME_TECH_DEFAULT',
        'loggerTechnique'
    );

if (!defined('_LOGGER_NAME_FUNC_DEFAULT'))
    define(
        '_LOGGER_NAME_FUNC_DEFAULT',
        'loggerFonctionnel'
    );

require_once('core/class/class_functions.php');
require_once('core/class/class_db_pdo.php');
require_once('apps/maarch_entreprise/tools/log4php/Logger.php');
require_once('core/core_tables.php');

$_SESSION['tablename']['history'] = HISTORY_TABLE;

class history
{
    /**
    * Inserts a record in the history table
    *
    * @param $table_name
    * @param $record_id
    * @param $event_type
    * @param $event_id
    * @param $info
    * @param $databasetype
    * @param [$id_module = 'admin']
    * @param [$isTech = false]
    * @param [$result = _OK]
    * @param [$level = _LEVEL_DEBUG]
    * @param [$user = '']
    */
    public function add(
        $table_name,
        $record_id,
        $event_type,
        $event_id,
        $info,
        $databasetype,
        $id_module = 'admin',
        $isTech = false,
        $result = _OK,
        $level = _LEVEL_DEBUG,
        $user = ''
    )
    {
        $db = new Database();
        $remote_ip = $_SERVER['REMOTE_ADDR'];

        $user = '';
        if (isset($_SESSION['user']['UserId'])) {
            $user = $_SESSION['user']['UserId'];
        }

        $traceInformations = array(
            'WHERE' => $table_name,
            'ID'    => $record_id,
            'HOW'   => $event_type,
            'USER'  => $user,
            'WHAT'  => $event_id,
            'ID_MODULE' => $id_module,
            'REMOTE_IP' => $remote_ip,
            'DATABASE_TYPE' => $databasetype,
            'RESULT'    => $result,
            'LEVEL' => $level
        );

        $this->build_logging_method();

        foreach ($_SESSION['logging_method_memory'] as $logging_method) {
            if ($logging_method['ACTIVATED'] == true) {
                if ($logging_method['ID'] == _LOG4PHP) {
                    if ($logging_method['LOGGER_NAME_TECH'] == "") {
                        $logging_method['LOGGER_NAME_TECH'] = _LOGGER_NAME_TECH_DEFAULT;
                    }
                    if ($logging_method['LOGGER_NAME_FUNC'] == "") {
                        $logging_method['LOGGER_NAME_FUNC'] = _LOGGER_NAME_FUNC_DEFAULT;
                    }
                    $this->addToLog4php(
                        $traceInformations,
                        $logging_method,
                        $isTech
                    );
                }
            }
        }

        if (!$isTech) {
            $queryHist = "INSERT INTO "
                    .$_SESSION['tablename']['history']." ("
                        ."table_name, "
                        ."record_id, "
                        ."event_type, "
                        ."event_id, "
                        ."user_id, "
                        ."event_date, "
                        ."info, "
                        ."id_module, "
                        ."remote_ip"
                    .") "
                ."VALUES (?, ?, ?, ?, ?, " 
                    . $db->current_datetime() 
                    . ", ?, ?, ?)";
            $db->query(
                $queryHist,
                array(
                    $table_name,
                    $record_id,
                    $event_type,
                    $event_id,
                    $user,
                    $info,
                    $id_module,
                    $remote_ip
                )
            );
        } else {
            //write on a log
            echo $info;exit;
        }

        $core = new core_tools();
        if ($core->is_module_loaded("notifications")) {
			require_once(
                "modules"
                .DIRECTORY_SEPARATOR."notifications"
                .DIRECTORY_SEPARATOR."class"
				.DIRECTORY_SEPARATOR."events_controler.php"
            );
			$eventsCtrl = new events_controler();
			$eventsCtrl->fill_event_stack($event_id, $table_name, $record_id, $user, $info);
        }
    }

    /**
    * Gets the label of an history keyword
    *
    * @param  $id
    *
    * @return  (string) => Label of the key word or empty string
    */
    public function get_label_history_keyword(
        $id
    )
    {
        if (empty($id)) {
            return '';
        } else {
            for ($i=0; $i<count($_SESSION['history_keywords']); $i++) {
                if ($id == $_SESSION['history_keywords'][$i]['id']) {
                    return $_SESSION['history_keywords'][$i]['label'];
                }
            }
        }

        return '';
    }

    /**
    * Delete accents
    *
    * @param  $str (string)
    * @param  [$charset = 'utf-8'] (string)
    *
    * @return  $str (string)
    */
    private function wd_remove_accents(
        $str,
        $charset ='utf-8'
    )
    {
        $str = htmlentities(
            $str,
            ENT_NOQUOTES,
            "utf-8"
        );
        $str = preg_replace(
            '#\&([A-za-z])(?:uml|circ|tilde|acute|grave|cedil|ring)\;#',
            '\1',
            $str
        );
        $str = preg_replace(
            '#\&([A-za-z]{2})(?:lig)\;#',
            '\1',
            $str
        );
        $str = preg_replace(
            '#\&[^;]+\;#',
            '',
            $str
        );

        return $str;
    }

    /**
    * Get the logging method in the configuration file
    */
    private function build_logging_method()
    {
        $xmlFileName = 'logging_method.xml';

        $pathToXmlLogin = '';

        if (!isset($_SESSION['logging_method_memory'])) {
            if (file_exists(
                $_SESSION['config']['corepath'].'custom'
                .DIRECTORY_SEPARATOR.$_SESSION['custom_override_id']
                .DIRECTORY_SEPARATOR . 'apps'
                .DIRECTORY_SEPARATOR.$_SESSION['config']['app_id']
                .DIRECTORY_SEPARATOR.'xml'
                .DIRECTORY_SEPARATOR.$xmlFileName
            )) {
                $pathToXmlLogin = $_SESSION['config']['corepath'].'custom'
                .DIRECTORY_SEPARATOR.$_SESSION['custom_override_id']
                .DIRECTORY_SEPARATOR.'apps'
                .DIRECTORY_SEPARATOR.$_SESSION['config']['app_id']
                .DIRECTORY_SEPARATOR.'xml'
                .DIRECTORY_SEPARATOR.$xmlFileName;
            } elseif (file_exists(
                'apps'
                .DIRECTORY_SEPARATOR.$_SESSION['config']['app_id']
                .DIRECTORY_SEPARATOR.'xml'
                .DIRECTORY_SEPARATOR.$xmlFileName
            )) {
                $pathToXmlLogin = 'apps'
                .DIRECTORY_SEPARATOR.$_SESSION['config']['app_id']
                .DIRECTORY_SEPARATOR.'xml'
                .DIRECTORY_SEPARATOR.$xmlFileName;
            } else {
                $noXml = true;
                $logging_methods[0]['ID'] = 'database';
                $logging_methods[0]['ACTIVATED'] = true;
                $logging_methods[1]['ID'] = 'log4php';
                $logging_methods[1]['ACTIVATED'] = true;
                $logging_methods[1]['LOGGER_NAME_TECH'] = 'loggerTechnique';
                $logging_methods[1]['LOGGER_NAME_FUNC'] = 'loggerFonctionnel';
                $logging_methods[1]['LOG_FORMAT'] = '[%RESULT%][%CODE_METIER%][%WHERE%][%ID%][%HOW%][%USER%][%WHAT%][%ID_MODULE%][%REMOTE_IP%]';
                $logging_methods[1]['CODE_METIER'] = 'MAARCH';
            }

            if (!isset($noXml)) {
                $logging_methods = array();

                $xmlConfig = simplexml_load_file($pathToXmlLogin);
                if (! $xmlConfig) {
                    exit();
                }

                foreach ($xmlConfig->METHOD as $METHOD) {
                    $id = ((string)$METHOD->ID);
                    $activated = ((boolean)$METHOD->ENABLED);
                    $loggerNameTech = ((string)$METHOD->LOGGER_NAME_TECH);
                    $loggerNameFunc = ((string)$METHOD->LOGGER_NAME_FUNC);
                    $logFormat = ((string)$METHOD->APPLI_LOG_FORMAT);
                    $codeMetier = ((string)$METHOD->CODE_METIER);

                    array_push(
                        $logging_methods,
                        array(
                            'ID'         => $id,
                            'ACTIVATED'  => $activated,
                            'LOGGER_NAME_TECH' => $loggerNameTech,
                            'LOGGER_NAME_FUNC' => $loggerNameFunc,
                            'LOG_FORMAT'    => $logFormat,
                            'CODE_METIER'   => $codeMetier
                        )
                    );
                }
            }
            $_SESSION['logging_method_memory'] = $logging_methods;
        }
    }

    /**
    * Write a log entry with a specific log level
    *
    * @param  $logger (object) => Log4php logger
    * @param  $logLine (string) => Line we want to trace
    * @param  $level (enum) => Log level
    */
    private function writeLog(
        $logger,
        $logLine,
        $level
    )
    {
        $formatter = new functions();

        $logLine = $formatter->wash_html(
            $logLine,
            ''
        );

        $logLine = $this->wd_remove_accents(
            $logLine
        );

        switch ($level) {

            case _LEVEL_DEBUG:
                $logger->debug(
                    $logLine
                );
                break;

             case _LEVEL_INFO:
                $logger->info(
                    $logLine
                );
                break;

            case _LEVEL_WARN:
                $logger->warn(
                    $logLine
                );
                break;

            case _LEVEL_ERROR:
                $logger->error(
                    $logLine
                );
                break;

            case _LEVEL_FATAL:
                $logger->fatal(
                    $logLine
                );
                break;
        }
    }

    /**
    * Insert a log line into log4php
    *
    * @param  $traceInformations (array) => Informations to trace
    * @param  $logging_method (string) => Array of XML attributes
    * @param  $isTech (boolean) => Says if the log is technical (true) or functional (false)
    */
    private function addToLog4php(
        $traceInformations,
        $logging_method,
        $isTech
    )
    {
        if (!isset($_SESSION['user']['loginmode'])) {
            $_SESSION['user']['loginmode'] = '';
        }

        if (!isset($_SESSION['user']['department'])) {
            $_SESSION['user']['department'] = '';
        }

        if (!isset($_SESSION['user']['primarygroup'])) {
            $_SESSION['user']['primarygroup'] = '';
        }

        if (file_exists(
            $_SESSION['config']['corepath']. DIRECTORY_SEPARATOR . 'custom'
            . DIRECTORY_SEPARATOR . $_SESSION['custom_override_id']
            . DIRECTORY_SEPARATOR . "apps"
            . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
            . DIRECTORY_SEPARATOR . "xml"
            . DIRECTORY_SEPARATOR . "log4php.xml"
        )) {
            $configFileLog4PHP = "apps"
                . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
                . DIRECTORY_SEPARATOR . "xml"
                . DIRECTORY_SEPARATOR . "log4php.xml";

        } elseif (file_exists(
            "apps"
            . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
            . DIRECTORY_SEPARATOR . "xml"
            . DIRECTORY_SEPARATOR . "log4php.xml"
        )) {
            $configFileLog4PHP = "apps"
                . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
                . DIRECTORY_SEPARATOR . "xml"
                . DIRECTORY_SEPARATOR . "log4php.xml";
        } else {
            $configFileLog4PHP = "apps"
                . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
                . DIRECTORY_SEPARATOR . "xml"
                . DIRECTORY_SEPARATOR . "log4php.default.xml";
        }

        Logger::configure(
            $configFileLog4PHP
        );

        if ($isTech) {
            $logger = Logger::getLogger(
                $logging_method['LOGGER_NAME_TECH']
            );
        } else {
            $logger = Logger::getLogger(
                $logging_method['LOGGER_NAME_FUNC']
            );
        }

        $connexionMode = '';
        if (isset($_SESSION['user_sso']['dggn']['connection_mode'])) {
            $connexionMode = $_SESSION['user_sso']['dggn']['connection_mode'];
        }

        $department = '';
        if (isset($_SESSION['user']['department'])) {
            $department = $_SESSION['user']['department'];
        }

        $primaryGroup = '';
        if (isset($_SESSION['user_sso']['dggn']['userGroup'])) {
            $primaryGroup = $_SESSION['user_sso']['dggn']['userGroup'];
        }

        $searchPatterns = array(
            '%RESULT%',
            '%CODE_METIER%',
            '%WHERE%',
            '%ID%',
            '%HOW%',
            '%USER%',
            '%WHAT%',
            '%ID_MODULE%',
            '%REMOTE_IP%'
        );

        $replacePatterns = array(
            $traceInformations['RESULT'],
            $logging_method['CODE_METIER'],
            $traceInformations['WHERE'],
            $traceInformations['ID'],
            $traceInformations['HOW'],
            $traceInformations['USER'],
            $traceInformations['WHAT'],
            $traceInformations['ID_MODULE'],
            $traceInformations['REMOTE_IP']
        );

        $logLine = str_replace(
            $searchPatterns,
            $replacePatterns,
            $logging_method['LOG_FORMAT']
        );

        $this->writeLog(
            $logger,
            $logLine,
            $traceInformations['LEVEL']
        );
    }
}
