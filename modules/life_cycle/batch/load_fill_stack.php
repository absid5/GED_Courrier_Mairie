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
 * @brief Library to fill the stack
 *
 * @file
 * @author  Laurent Giovannoni  <dev@maarch.org>
 * @date $date$
 * @version $Revision$
 * @ingroup life_cycle
 */

/**
* @brief  Class to include the file error
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
$configFile = '';
$table = '';
$policy = '';
$cycle = '';
$dateBegin = '';
$dateEnd = '';
$creationDateClause = '';
$steps = Array();
$databasetype = '';
$exitCode = 0;
$collections = array();
$wb = '';
$stackSizeLimit = '';
$enableHistory = true;
$enableFingerprintControl = true;
$enablePdi = true;
$regExResId = 'false';
$batchName = 'fill_stack';
$func = '';
$db = '';
$lckFile = '';
$errorLckFile = '';
$totalProcessedResources = 0;
$batchDirectory = '';
$log4PhpEnabled = false;

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
// The life cycle policy
$argsparser->add_arg(
    'policy', 
    array(
        'short' => 'p',
        'long' => 'policy',
        'mandatory' => true,
        'help' => 'Policy is mandatory.',
    )
);
// The cycle of the policy
$argsparser->add_arg(
    'cycle', 
    array(
        'short' => 'cy',
        'long' => 'cycle',
        'mandatory' => true,
        'help' => 'Cycle is mandatory.',
    )
);
// The date begin
$argsparser->add_arg(
    'datebegin', 
    array(
        'short' => 'db',
        'long' => 'datebegin',
        'mandatory' => false,
        'help' => 'Date begin is mandatory.',
    )
);
// The date end
$argsparser->add_arg(
    'dateend', 
    array(
        'short' => 'de',
        'long' => 'dateend',
        'mandatory' => false,
        'help' => 'Date end is mandatory.',
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

// Log management
$GLOBALS['logger'] = new Logger4Php();
$GLOBALS['logger']->set_threshold_level('DEBUG');
$console = new ConsoleHandler();
$GLOBALS['logger']->add_handler($console);
if (!empty($options['logs'])) {
    $logFile = $options['logs'] . DIRECTORY_SEPARATOR . 'fill_stack' 
             . DIRECTORY_SEPARATOR . date('Y-m-d_H-i-s') . '.log';
} else {
    $logFile = 'logs' . DIRECTORY_SEPARATOR . 'fill_stack' 
             . DIRECTORY_SEPARATOR . date('Y-m-d_H-i-s') . '.log';
}

$file = new FileHandler($logFile);
$GLOBALS['logger']->add_handler($file);

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
        $GLOBALS['logger']->write('Collection missing', 'ERROR', 105);
        exit(105);
    }
    if ($e->arg_name == 'policy') {
        $GLOBALS['logger']->write('Policy missing', 'ERROR', 105);
        exit(105);
    }
    if ($e->arg_name == 'cycle') {
        $GLOBALS['logger']->write('Cycle missing', 'ERROR', 105);
        exit(105);
    }
}

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
$GLOBALS['policy'] = $options['policy'];
$GLOBALS['cycle'] = $options['cycle'];
$GLOBALS['dateBegin'] = $options['datebegin'];
$GLOBALS['dateEnd'] = $options['dateend'];
$GLOBALS['creationDateClause'] = '';
if ($GLOBALS['dateBegin'] <> '') {
    $GLOBALS['creationDateClause'] = " and (creation_date >= '" . $GLOBALS['dateBegin'] . "'";
    if ($GLOBALS['dateEnd'] <> '') {
        $GLOBALS['creationDateClause'] .= " and creation_date <= '" . $GLOBALS['dateEnd'] . "'";
    }
    $GLOBALS['creationDateClause'] .= ")";
}
$GLOBALS['logger']->write($txt, 'INFO');

// Tests existence of config file
if (!file_exists($GLOBALS['configFile'])) {
    $GLOBALS['logger']->write(
        'Configuration file ' . $GLOBALS['configFile'] 
        . ' does not exist', 'ERROR', 102
    );
    exit(102);
}
// Loading config file
$GLOBALS['logger']->write(
    'Load xml config file:' . $GLOBALS['configFile'], 
    'INFO'
);
$xmlconfig = simplexml_load_file($GLOBALS['configFile']);
if ($xmlconfig == FALSE) {
    $GLOBALS['logger']->write(
        'Error on loading config file:' 
        . $GLOBALS['configFile'], 'ERROR', 103
    );
    exit(103);
}
// Load the config vars
$config = $xmlconfig->CONFIG;
$lang = (string) $config->Lang;
$maarchDirectory = (string) $config->MaarchDirectory;
$MaarchApps = (string) $config->MaarchApps;
$logLevel = (string) $config->LogLevel;
$GLOBALS['logger']->set_threshold_level($logLevel);
$DisplayedLogLevel = (string) $config->DisplayedLogLevel;
$GLOBALS['stackSizeLimit'] = (string) $config->StackSizeLimit;
if ((string) $config->enableHistory == 'true') {
    $GLOBALS['enableHistory'] = true;
} else {
    $GLOBALS['enableHistory'] = false;
}
if ((string) $config->enableFingerprintControl == 'true') {
    $GLOBALS['enableFingerprintControl'] = true;
} else {
    $GLOBALS['enableFingerprintControl'] = false;
}
if ((string) $config->enablePdi == 'true') {
    $GLOBALS['enablePdi'] = true;
} else {
    $GLOBALS['enablePdi'] = false;
}
$GLOBALS['regExResId'] = (string) $config->regExResId;
$GLOBALS['databasetype'] = (string) $xmlconfig->CONFIG_BASE->databasetype;
$GLOBALS['batchDirectory'] = $maarchDirectory . 'modules' 
                           . DIRECTORY_SEPARATOR . 'life_cycle' 
                           . DIRECTORY_SEPARATOR . 'batch';
$i = 0;
foreach ($xmlconfig->COLLECTION as $col) {
    $GLOBALS['collections'][$i] = array(
        'id' => (string) $col->Id, 
        'table' => (string) $col->Table, 
        'view' => (string) $col->View, 
        'adr' => (string) $col->Adr,
    );
    if ($GLOBALS['collections'][$i]['id'] == $GLOBALS['collection']) {
        $GLOBALS['table'] = $GLOBALS['collections'][$i]['view'];
    }
    $i++;
}
set_include_path(get_include_path() . PATH_SEPARATOR . $maarchDirectory);
//log4php params
$log4phpParams = $xmlconfig->LOG4PHP;
if ((string) $log4phpParams->enabled == 'true') {
    $GLOBALS['logger']->set_log4PhpLibrary(
        $maarchDirectory . 'apps/maarch_entreprise/tools/log4php/Logger.php'
    );
    $GLOBALS['logger']->set_log4PhpLogger((string) $log4phpParams->Log4PhpLogger);
    $GLOBALS['logger']->set_log4PhpBusinessCode((string) $log4phpParams->Log4PhpBusinessCode);
    $GLOBALS['logger']->set_log4PhpConfigPath((string) $log4phpParams->Log4PhpConfigPath);
    $GLOBALS['logger']->set_log4PhpBatchName('life_cycle_fill_stack');
}
if ($GLOBALS['table'] == '') {
    $GLOBALS['logger']->write(
        'Collection:' . $GLOBALS['collection'] 
        . ' unknow', 'ERROR', 110
    );
    exit(110);
}
/*if ($logLevel == 'DEBUG') {
    error_reporting(E_ALL);
}*/
$GLOBALS['logger']->change_handler_log_level($file, $logLevel);
$GLOBALS['logger']->change_handler_log_level($console, $DisplayedLogLevel);
unset($xmlconfig);
// Include library
try {
    Bt_myInclude(
        $maarchDirectory . 'core' . DIRECTORY_SEPARATOR . 'class' 
        . DIRECTORY_SEPARATOR . 'class_functions.php'
    );
    Bt_myInclude(
        $maarchDirectory . 'core' . DIRECTORY_SEPARATOR . 'class' 
        . DIRECTORY_SEPARATOR . 'class_db_pdo.php'
    );
    Bt_myInclude(
        $maarchDirectory . 'core' . DIRECTORY_SEPARATOR . 'class' 
        . DIRECTORY_SEPARATOR . 'class_core_tools.php'
    );
    Bt_myInclude(
        $maarchDirectory . 'core' . DIRECTORY_SEPARATOR . 'class' 
        . DIRECTORY_SEPARATOR . 'class_history.php'
    );
    Bt_myInclude(
        $maarchDirectory . 'core' . DIRECTORY_SEPARATOR . 'core_tables.php'
    );
    Bt_myInclude(
        $maarchDirectory . 'modules' .DIRECTORY_SEPARATOR . 'life_cycle' 
        .DIRECTORY_SEPARATOR . 'life_cycle_tables_definition.php'
    );
} catch (IncludeFileError $e) {
    $GLOBALS['logger']->write(
        'Problem with the php include path:' . get_include_path(), 
        'ERROR', 
        108
    );
    exit(108);
}
$coreTools = new core_tools();
$coreTools->load_lang($lang, $maarchDirectory, $MaarchApps);
$GLOBALS['func'] = new functions();
$GLOBALS['db'] = new Database($GLOBALS['configFile']);
$GLOBALS['dbLog'] = new Database($GLOBALS['configFile']);
$GLOBALS['errorLckFile'] = $GLOBALS['batchDirectory'] . DIRECTORY_SEPARATOR 
                         . $GLOBALS['batchName'] . '_' . $GLOBALS['policy'] 
                         . '_' . $GLOBALS['cycle'] . '_error.lck';
$GLOBALS['lckFile'] = $GLOBALS['batchDirectory'] . DIRECTORY_SEPARATOR 
                    . $GLOBALS['batchName'] . '_' . $GLOBALS['policy'] 
                    . '_' . $GLOBALS['cycle'] . '.lck';
if (file_exists($GLOBALS['errorLckFile'])) {
    $GLOBALS['logger']->write(
        'Error persists, please solve this before launching a new batch', 
        'ERROR', 13
    );
    exit(13);
}
if (file_exists($GLOBALS['lckFile'])) {
    $GLOBALS['logger']->write(
        'An instance of the batch for policy:' . $GLOBALS['policy'] 
        . ' and the cycle:' . $GLOBALS['cycle'] . ' is already in progress',
        'ERROR', 109
    );
    exit(109);
}
$semaphore = fopen($GLOBALS['lckFile'], 'a');
fwrite($semaphore, '1');
fclose($semaphore);

Bt_getWorkBatch();
