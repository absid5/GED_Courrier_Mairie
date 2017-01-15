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
 *   along with Maarch Framework. If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * @brief Library to process the purge
 *
 * @file
 * @author  Laurent Giovannoni  <dev@maarch.org>
 * @date $date$
 * @version $Revision$
 * @ingroup life_cycle
 */

/**
* @brief Class to include the file error
*
* @ingroup life_cycle
*/
class IncludeFileError extends Exception
{
    public function __construct($file) 
    {
        $this->file = $file;
        parent :: __construct('Include File \'$file\' is missing!', 1);
    }
}

try {
    include('Maarch_CLITools/ArgsParser.php');
    //include('Maarch_CLITools/Logger.php');
    include('LoggerLog4php.php');
    include('Maarch_CLITools/FileHandler.php');
    include('Maarch_CLITools/ConsoleHandler.php');
} catch (IncludeFileError $e) {
    echo 'Maarch_CLITools required ! \n (pear.maarch.org)\n';
    exit(106);
}
include('batch_tools.php');
// Globals variables definition
$state = '';
$configFile = '';
$MaarchDirectory = '';
$batchDirectory = '';
$batchName = 'process_purge';

$table = '';
$adrTable = '';
$view = '';
$coll = '';
$policy = '';
$cycle = '';
$steps = Array();
$currentStep = '';
$docservers = Array();
$docserverSourcePath = '';
$docserverSourceFingerprint = '';
$databasetype = '';
$exitCode = 0;
$running_date = date('Y-m-d H:i:s');
$func = '';
$db = '';
$db2 = '';
$docserverControler = '';
$wb = '';
$docserversFeatures = array();
$isAContainerOpened = false;
$lckFile = '';
$errorLckFile = '';
$totalProcessedResources = 0;
$whereClause =  '';
$log4PhpEnabled = false;
$targetCycle = '';

// Defines scripts arguments
$argsparser = new ArgsParser();
// The config file
$argsparser->add_arg(
    'config', 
    array(
        'short' => 'c',
        'long' => 'config',
        'mandatory' => true,
        'help' => 'Config file path is mandatory.',
    )
);
// The res collection target
$argsparser->add_arg(
    'collection', 
    array(
        'short' => 'coll',
        'long' => 'collection',
        'mandatory' => true,
        'help' => 'Collection target is mandatory.',
    )
);

// The path of the log directory
$argsparser->add_arg(
    'logs', 
    array(
        'short' => 'logs',
        'long' => 'logs',
        'mandatory' => false,
        'help' => '',
    )
);
// Parsing script options
try {
    $options = $argsparser->parse_args($GLOBALS['argv']);
    // If option = help then options = false and the script continues ...
    if ($options == false) {
        exit(0);
    }
} catch (MissingArgumentError $e) {
    if ($e->arg_name == 'config') {
        $GLOBALS['logger']->write('Configuration file missing', 'ERROR', 101);
        exit(101);
    }
    if ($e->arg_name == 'collection') {
        $GLOBALS['logger']->write('Collection missing', 'ERROR', 1);
        exit(105);
    }
}
// Log management
//$GLOBALS['logger'] = new Logger();
$GLOBALS['logger'] = new Logger4Php();
$GLOBALS['logger']->set_threshold_level('DEBUG');
$console = new ConsoleHandler();
$GLOBALS['logger']->add_handler($console);
if (!empty($options['logs'])) {
    $logFile = $options['logs'] . DIRECTORY_SEPARATOR . 'process_purge' 
             . DIRECTORY_SEPARATOR . date('Y-m-d_H-i-s') . '.log';
} else {
    $logFile = 'logs' . DIRECTORY_SEPARATOR . 'process_purge' 
             . DIRECTORY_SEPARATOR . date('Y-m-d_H-i-s') . '.log';
}
$file = new FileHandler($logFile);
$GLOBALS['logger']->add_handler($file);
$GLOBALS['logger']->write('STATE:INIT', 'INFO');
$txt = '';
foreach (array_keys($options) as $key) {
    if (isset($options[$key]) && $options[$key] == false) {
        $txt .= $key . '=false,';
    } else {
        $txt .= $key . '=' . $options[$key] . ',';
    }
}
$GLOBALS['logger']->write($txt, 'DEBUG');
$GLOBALS['configFile'] = $options['config'];
$GLOBALS['collection'] = $options['collection'];
$GLOBALS['logger']->write($txt, 'INFO');
// Tests existence of config file
if (!file_exists($GLOBALS['configFile'])) {
    $GLOBALS['logger']->write('Configuration file ' . $GLOBALS['configFile'] 
                              . ' does not exist', 'ERROR', 102);
    exit(102);
}
// Loading config file
$GLOBALS['logger']->write('Load xml config file:' . $GLOBALS['configFile'], 
                          'INFO');
$xmlconfig = simplexml_load_file($GLOBALS['configFile']);
if ($xmlconfig == FALSE) {
    $GLOBALS['logger']->write('Error on loading config file:' 
                              . $GLOBALS['configFile'], 'ERROR', 103);
    exit(103);
}
// Load the config vars
$CONFIG = $xmlconfig->CONFIG;
$lang = (string) $CONFIG->Lang;
$GLOBALS['MaarchDirectory'] = (string) $CONFIG->MaarchDirectory;
$GLOBALS['batchDirectory'] = $GLOBALS['MaarchDirectory'] . 'modules' 
                           . DIRECTORY_SEPARATOR . 'life_cycle' 
                           . DIRECTORY_SEPARATOR . 'batch' . DIRECTORY_SEPARATOR;
$MaarchApps = (string) $CONFIG->MaarchApps;
$logLevel = (string) $CONFIG->LogLevel;
$GLOBALS['logger']->set_threshold_level($logLevel);
$DisplayedLogLevel = (string) $CONFIG->DisplayedLogLevel;
$GLOBALS['databasetype'] = (string) $xmlconfig->CONFIG_BASE->databasetype;
$GLOBALS['whereClause'] = (string) $CONFIG->WhereClause;
$GLOBALS['exportFolder'] = (string) $CONFIG->ExportFolder;

if ((string) $CONFIG->CleanContactsMoral) {
    $GLOBALS['CleanContactsMoral'] = (string) $CONFIG->CleanContactsMoral;
} else {
    $GLOBALS['CleanContactsMoral'] = "false";
}

if ((string) $CONFIG->CleanContactsNonMoral) {
    $GLOBALS['CleanContactsNonMoral'] = (string) $CONFIG->CleanContactsNonMoral;
} else {
    $GLOBALS['CleanContactsNonMoral'] = "false";
}

if ((string) $CONFIG->PurgeMode) {
    $GLOBALS['PurgeMode'] = (string) $CONFIG->PurgeMode;
} else {
    $GLOBALS['PurgeMode'] = "both";
}

if (empty($GLOBALS['whereClause'])) {
    $GLOBALS['logger']->write('whereClause is empty', 'ERROR', 1);
    exit(105);
}

if (trim($GLOBALS['whereClause']) == '1=1') {
    $GLOBALS['logger']->write('Security problem with whereClause', 'ERROR', 1);
    exit(113);
}

$i = 0;
foreach ($xmlconfig->COLLECTION as $col) {
    $GLOBALS['collections'][$i] = array(
        'id' => (string) $col->Id, 
        'table' => (string) $col->Table, 
        'view' => (string) $col->View, 
        'extensionTable' => (string) $col->ExtTable, 
        'versionTable' => (string) $col->VersionTable, 
        'adr' => (string) $col->Adr,
    );
    if ($GLOBALS['collections'][$i]['id'] == $GLOBALS['collection']) {
        $GLOBALS['table'] = $GLOBALS['collections'][$i]['table'];
        $GLOBALS['adrTable'] = $GLOBALS['collections'][$i]['adr'];
        $GLOBALS['view'] = $GLOBALS['collections'][$i]['view'];
        $GLOBALS['extensionTable'] = $GLOBALS['collections'][$i]['extensionTable'];
        $GLOBALS['versionTable'] = $GLOBALS['collections'][$i]['versionTable'];
    }
    $i++;
}
set_include_path(get_include_path() . PATH_SEPARATOR 
    . $GLOBALS['MaarchDirectory']);
//log4php params
$log4phpParams = $xmlconfig->LOG4PHP;
if ((string) $log4phpParams->enabled == 'true') {
    $GLOBALS['logger']->set_log4PhpLibrary(
        $GLOBALS['MaarchDirectory'] 
            . 'apps/maarch_entreprise/tools/log4php/Logger.php'
    );
    $GLOBALS['logger']->set_log4PhpLogger((string) $log4phpParams->Log4PhpLogger);
    $GLOBALS['logger']->set_log4PhpBusinessCode((string) $log4phpParams->Log4PhpBusinessCode);
    $GLOBALS['logger']->set_log4PhpConfigPath((string) $log4phpParams->Log4PhpConfigPath);
    $GLOBALS['logger']->set_log4PhpBatchName('life_cycle_process_purge');
}

if ($GLOBALS['table'] == '' 
    || $GLOBALS['adrTable'] == '' 
    || $GLOBALS['view'] == ''
) {
    $GLOBALS['logger']->write('Collection:' . $GLOBALS['collection'].' unknown'
                              , 'ERROR', 110);
    exit(110);
}
if ($logLevel == 'DEBUG') {
    error_reporting(E_ALL);
}
$GLOBALS['logger']->change_handler_log_level($file, $logLevel);
$GLOBALS['logger']->change_handler_log_level($console, $DisplayedLogLevel);
unset($xmlconfig);

// Include library
try {
    Bt_myInclude($GLOBALS['MaarchDirectory'] . 'core' . DIRECTORY_SEPARATOR 
                 . 'class' . DIRECTORY_SEPARATOR . 'class_functions.php');
    Bt_myInclude($GLOBALS['MaarchDirectory'] . 'core' . DIRECTORY_SEPARATOR 
                 . 'class' . DIRECTORY_SEPARATOR . 'class_db_pdo.php');
    Bt_myInclude($GLOBALS['MaarchDirectory'] . 'core' . DIRECTORY_SEPARATOR 
                 . 'class' . DIRECTORY_SEPARATOR . 'class_db.php'); // Utile tant que request etend dbquery
    Bt_myInclude($GLOBALS['MaarchDirectory'] . 'core' . DIRECTORY_SEPARATOR 
                 . 'class' . DIRECTORY_SEPARATOR . 'class_core_tools.php');
    Bt_myInclude($GLOBALS['MaarchDirectory'] . 'core' . DIRECTORY_SEPARATOR 
                 . 'core_tables.php');
    Bt_myInclude($GLOBALS['MaarchDirectory'] . 'core' . DIRECTORY_SEPARATOR 
                 . 'class' . DIRECTORY_SEPARATOR . 'docservers_controler.php');
    Bt_myInclude($GLOBALS['MaarchDirectory'] . 'core' . DIRECTORY_SEPARATOR 
                 . 'docservers_tools.php');
    Bt_myInclude($GLOBALS['MaarchDirectory'] . 'core' . DIRECTORY_SEPARATOR 
                 . 'class' . DIRECTORY_SEPARATOR 
                 . 'docserver_types_controler.php');
} catch (IncludeFileError $e) {
    $GLOBALS['logger']->write(
        'Problem with the php include path:' 
        . get_include_path(), 'ERROR', 111
    );
    exit(111);
}

$coreTools = new core_tools();
$coreTools->load_lang($lang, $GLOBALS['MaarchDirectory'], $MaarchApps);
session_start();
$_SESSION['modules_loaded'] = array();
$GLOBALS['func'] = new functions();
$GLOBALS['db'] = new Database($GLOBALS['configFile']);
$GLOBALS['db2'] = new Database($GLOBALS['configFile']);
$GLOBALS['dbLog'] = new Database($GLOBALS['configFile']);
$GLOBALS['docserverControler'] = new docservers_controler();
$GLOBALS['errorLckFile'] = $GLOBALS['batchDirectory'] . DIRECTORY_SEPARATOR 
                         . $GLOBALS['batchName'] . '_' . $GLOBALS['collection'] 
                         . '_error.lck';
$GLOBALS['lckFile'] = $GLOBALS['batchDirectory'] . DIRECTORY_SEPARATOR 
                    . $GLOBALS['batchName'] . '_' . $GLOBALS['collection'] 
                    . '.lck';
if (file_exists($GLOBALS['errorLckFile'])) {
    $GLOBALS['logger']->write(
        'Error persists, please solve this before launching a new batch', 
        'ERROR', 29
    );
    exit(29);
}
if (file_exists($GLOBALS['lckFile'])) {
    $GLOBALS['logger']->write(
        'An instance of the purge batch for collection:' . $GLOBALS['collection'] 
        . ' is already in progress',
        'ERROR', 109
    );
    exit(109);
}
$semaphore = fopen($GLOBALS['lckFile'], 'a');
fwrite($semaphore, '1');
fclose($semaphore);
Bt_getWorkBatch();

$GLOBALS['wb'] = rand() . $GLOBALS['wbCompute'];
Bt_updateWorkBatch();
$GLOBALS['logger']->write('Batch number:' . $GLOBALS['wb'], 'INFO');

function getEntityChildrenTree($entities, $parent = '', $tabspace = '', $except = array(), $where = '')
{
    if(trim($parent) == "") {
        $query = "SELECT entity_id FROM entities WHERE enabled = 'Y' "
            . " and (parent_entity_id ='' or parent_entity_id is null) " 
            . $where;
        $stmt = Bt_doQuery($GLOBALS['db2'], $query);
    } else {
        $query = "SELECT entity_id FROM entities WHERE enabled = 'Y' and parent_entity_id = '" 
            . trim($parent)."'".$where;
        $stmt = Bt_doQuery($GLOBALS['db2'], $query);
    }

    if($stmt->rowCount() > 0) {
        $espace = $tabspace.'&emsp;';

        while($line = $stmt->fetchObject()) {
            if (!in_array($line->entity_id, $except)) {
                 array_push($entities, array('ID' =>$line->entity_id, 'KEYWORD' => false));

                $query2 ="select entity_id from entities where enabled = 'Y' and parent_entity_id = '" 
                    . trim($line->entity_id)."'".$where;
                $stmt2 = Bt_doQuery($GLOBALS['db'], $query2);
                $tmp = array();
                if($stmt2->rowCount() > 0) {
                    $tmp = getEntityChildrenTree($tmp,$line->entity_id,  $espace, $except);
                    $entities = array_merge($entities, $tmp);
                }
            }
        }
    }
    // var_dump($parent);
    return $entities;
}