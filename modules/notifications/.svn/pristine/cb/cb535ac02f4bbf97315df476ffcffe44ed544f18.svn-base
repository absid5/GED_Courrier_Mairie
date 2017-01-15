<?php

/*
 *   Copyright 2008-2011 Maarch
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
 * @brief process the notification stack
 *
 * @file
 * @author  Cyril Vazquez  <dev@maarch.org>
 * @date $date$
 * @version $Revision$
 * @ingroup notification
 */

/**
* @brief  Class to include the file error
*
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
    include('LoggerLog4php.php');
    include('Maarch_CLITools/FileHandler.php');
    include('Maarch_CLITools/ConsoleHandler.php');
} catch (IncludeFileError $e) {
    echo 'Maarch_CLITools required ! \n (pear.maarch.org)\n';
    exit(106);
}

// Globals variables definition
$GLOBALS['batchName'] = 'process_email_stack';
$GLOBALS['wb'] = '';
$totalProcessedResources = 0;
$batchDirectory = '';
$log4PhpEnabled = false;

// Open Logger
$GLOBALS['logger'] = new Logger4Php();
$GLOBALS['logger']->set_threshold_level('DEBUG');

$logFile = 'logs' . DIRECTORY_SEPARATOR . $GLOBALS['batchName']
             . DIRECTORY_SEPARATOR . date('Y-m-d_H-i-s') . '.log';
             
$file = new FileHandler($logFile);
$GLOBALS['logger']->add_handler($file);

// Load tools
include('batch_tools.php');

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
}

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
$GLOBALS['logger']->write('Load xml config file:' . $GLOBALS['configFile'], 'INFO');
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


// Load config
$config = $xmlconfig->CONFIG;
$lang = (string)$config->Lang;
$GLOBALS['maarchDirectory'] = (string)$config->MaarchDirectory; 
$maarchApps = (string) $config->MaarchApps;

$GLOBALS['TmpDirectory'] = (string)$config->TmpDirectory;
$GLOBALS['batchDirectory'] = $GLOBALS['maarchDirectory'] . 'modules' 
                           . DIRECTORY_SEPARATOR . 'notifications' 
                           . DIRECTORY_SEPARATOR . 'batch';

set_include_path(get_include_path() . PATH_SEPARATOR . $GLOBALS['maarchDirectory']);

//log4php params
$log4phpParams = $xmlconfig->LOG4PHP;
if ((string) $log4phpParams->enabled == 'true') {
    $GLOBALS['logger']->set_log4PhpLibrary(
        $GLOBALS['maarchDirectory'] . 'apps/maarch_entreprise/tools/log4php/Logger.php'
    );
    $GLOBALS['logger']->set_log4PhpLogger((string) $log4phpParams->Log4PhpLogger);
    $GLOBALS['logger']->set_log4PhpBusinessCode((string) $log4phpParams->Log4PhpBusinessCode);
    $GLOBALS['logger']->set_log4PhpConfigPath((string) $log4phpParams->Log4PhpConfigPath);
    $GLOBALS['logger']->set_log4PhpBatchName('process_event_stack');
}

// Mailer
$mailerParams = $xmlconfig->MAILER;
$path_to_mailer = (string)$mailerParams->path_to_mailer;

try {
    Bt_myInclude(
        $GLOBALS['maarchDirectory'] . 'core' . DIRECTORY_SEPARATOR . 'class' 
        . DIRECTORY_SEPARATOR . 'class_functions.php'
    );
    Bt_myInclude(
        $GLOBALS['maarchDirectory'] . 'core' . DIRECTORY_SEPARATOR . 'class' 
        . DIRECTORY_SEPARATOR . 'class_db_pdo.php'
    );
    Bt_myInclude(
        $GLOBALS['maarchDirectory'] . 'core' . DIRECTORY_SEPARATOR . 'class' 
        . DIRECTORY_SEPARATOR . 'class_core_tools.php'
    );  
    Bt_myInclude(
        $maarchDirectory . "modules" . DIRECTORY_SEPARATOR . "notifications" 
        . DIRECTORY_SEPARATOR . "notifications_tables_definition.php"
    );
    Bt_myInclude(
        $GLOBALS['maarchDirectory'] . $path_to_mailer
    );  

} catch (IncludeFileError $e) {
    $GLOBALS['logger']->write(
        'Problem with the php include path:' .$e .' '. get_include_path(), 
        'ERROR'
    );
    exit();
}


$coreTools = new core_tools();
$coreTools->load_lang($lang, $GLOBALS['maarchDirectory'], $maarchApps);

$GLOBALS['func'] = new functions();

$GLOBALS['db'] = new Database($GLOBALS['configFile']);

$GLOBALS['errorLckFile'] = $GLOBALS['batchDirectory'] . DIRECTORY_SEPARATOR 
                         . $GLOBALS['batchName'] . '_error.lck';
$GLOBALS['lckFile'] = $GLOBALS['batchDirectory'] . DIRECTORY_SEPARATOR 
                    . $GLOBALS['batchName'] . '.lck';
                    
if (file_exists($GLOBALS['errorLckFile'])) {
    $GLOBALS['logger']->write(
        'Error persists, please solve this before launching a new batch', 
        'ERROR', 13
    );
    exit(13);
}

/*if (file_exists($GLOBALS['lckFile'])) {
    $GLOBALS['logger']->write(
        'An instance of the batch is already in progress',
        'ERROR', 109
    );
    exit(109);
}
$semaphore = fopen($GLOBALS['lckFile'], 'a');
fwrite($semaphore, '1');
fclose($semaphore);*/

Bt_getWorkBatch();
