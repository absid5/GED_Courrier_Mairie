<?php
/*
*   Copyright 2008 - 2015 Maarch
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

try {
    include('Maarch_CLITools/ArgsParser.php');
    include('LoggerLog4php.php');
    include('Maarch_CLITools/FileHandler.php');
    include('Maarch_CLITools/ConsoleHandler.php');
} catch (IncludeFileError $e) {
    echo 'Maarch_CLITools required ! \n (pear.maarch.org)\n';
    exit(106);
}

// Open Logger
$_ENV['logger'] = new Logger4Php();
$_ENV['logger']->set_threshold_level('DEBUG');

$logFile = 'log/' . date('Y-m-d_H-i-s') . '.log';

$file = new FileHandler($logFile);
$_ENV['logger']->add_handler($file);

//error mode and function
error_reporting(E_ERROR);
set_error_handler(errorHandler);
// global vars of the program
/**
* Name of the config (usefull for multi instance)
*/
$_ENV['config_name'] = "";
/**
* Path to the log file
*/
$_ENV['log'] = "";
/**
* User exit of the program, contains 1 if any problem appears
*/
$_ENV['ErrorLevel'] = 0;
/**
* Connection object to database 1
*/
$_ENV['db'] = "";

// Class to manage files includes errors
class IncludeFileError extends Exception
{
    public function __construct($file)
    {
        $this->file = $file;
        parent::__construct("Include File \"$file\" is missing!", 1);
    }
}

function MyInclude($file)
{
    if (file_exists($file)) {
        include_once($file);
    } else {
        throw new IncludeFileError($file);
    }
}

function r_mkdir($path, $mode = 0777, $recursive = true) {
	if(empty($path))
		return false;
	 
	if($recursive) {
		$toDo = substr($path, 0, strrpos($path, DIRECTORY_SEPARATOR));
		if($toDo !== '.' && $toDo !== '..')
			r_mkdir($toDo, $mode);
	}
	 
	if(!is_dir($path))
		mkdir($path, $mode);
	 
		return true;
}

/**
* Managing of errors
* @param  $errno integer number of the error
* @param  $errstr string text of the error
* @param  $errfile string file concerned with the error
* @param  $errline integer line of the error
* @param  $errcontext string context of the error
*/
function errorHandler($errno, $errstr, $errfile, $errline, $errcontext)
{
    $_ENV['logger']->write('from  line ' . $errline . ' : ' . $errstr, 'WARNING', 1);
    $_ENV['errorLevel'] = 1;
}

// Begin
date_default_timezone_set('Europe/Paris');
if ($argc != 2) {
    echo "You must specify the configuration file." . $argc;
    exit;
}
$conf = $argv[1];
$xmlconfig = simplexml_load_file($conf);
foreach ($xmlconfig->CONFIG as $CONFIG) {
    $_ENV['config_name'] = $CONFIG->CONFIG_NAME;
   
    $_ENV['tablename'] = $CONFIG->TABLE_NAME;
    $_ENV['collection'] = $CONFIG->COLLECTION;
	
	$_ENV['max_batch_size'] = $CONFIG->MAX_BATCH_SIZE;
	$maarchDirectory = (string) $CONFIG->MaarchDirectory;
	$_ENV['core_path'] = $maarchDirectory . 'core' . DIRECTORY_SEPARATOR;
}
$_ENV['databasetype'] = $xmlconfig->CONFIG_BASE->databasetype;

$log4phpParams = $xmlconfig->LOG4PHP;
if ((string) $log4phpParams->enabled == 'true') {
    $_ENV['logger']->set_log4PhpLibrary(
        $maarchDirectory . 'apps/maarch_entreprise/tools/log4php/Logger.php'
    );
    $_ENV['logger']->set_log4PhpLogger((string) $log4phpParams->Log4PhpLogger);
    $_ENV['logger']->set_log4PhpBusinessCode((string) $log4phpParams->Log4PhpBusinessCode);
    $_ENV['logger']->set_log4PhpConfigPath((string) $log4phpParams->Log4PhpConfigPath);
    $_ENV['logger']->set_log4PhpBatchName('thumbnails');
}

$_ENV['logger']->write("Launch of process of thumbnails conversion");
$_ENV['logger']->write("Loading the xml config file");
$_ENV['logger']->write("Config name : " . $_ENV['config_name']);
$_ENV['logger']->write("Conversion launched for table : " . $_ENV['tablename']);

require($_ENV['core_path']."class/class_functions.php");
require($_ENV['core_path']."class/class_db_pdo.php");
	
$_ENV['db'] = new Database($conf);

$query = "select priority_number, docserver_id from docservers where is_readonly = 'N' and "
	   . " enabled = 'Y' and coll_id = ? and docserver_type_id = 'TNL' order by priority_number";
	   
$stmt1 = $_ENV['db']->query($query, array($_ENV['collection']));
$docserverId = $stmt1->fetchObject()->docserver_id;

$_ENV['logger']->write($query);
$_ENV['logger']->write($docserverId);
$docServers = "select docserver_id, path_template from docservers";
$stmt1 = $_ENV['db']->query($docServers);
$_ENV['logger']->write("docServers found : ");

while ($queryResult = $stmt1->fetchObject()) {
  $pathToDocServer[$queryResult->docserver_id] = $queryResult->path_template;
  $_ENV['logger']->write($queryResult->docserver_id. '-' .$queryResult->path_template);
}

if (is_dir($pathToDocServer[(string)$docserverId])){
	$pathOutput = $pathToDocServer[(string)$docserverId];
	$_ENV['logger']->write("path of output docserver : ".$pathOutput);
}
else {
	$_ENV['logger']->write("output docserver unknown ! : ".$docserverId, "ERROR");
	exit();
}
$cpt_batch_size=0;

$queryMakeThumbnails = "select res_id, docserver_id, path, filename, format from "
    . $_ENV['tablename'] . " where tnl_filename = '' or tnl_filename is null ";
$_ENV['logger']->write("query to found document with no thumbnail : ".$queryMakeThumbnails);
$stmt1 = $_ENV['db']->query($queryMakeThumbnails);
while ($queryResult=$stmt1->fetchObject()) {
	if ($_ENV['max_batch_size'] >= $cpt_batch_size) {
		$fileFormat = $queryResult->format;
		echo 'format ' . $queryResult->format . PHP_EOL;
		$pathToFile = $pathToDocServer[$queryResult->docserver_id] 
			. str_replace("#", DIRECTORY_SEPARATOR, $queryResult->path)
        	. $queryResult->filename;
		$outputPathFile  = $pathOutput . str_replace("#", DIRECTORY_SEPARATOR, $queryResult->path) 
			. str_replace(pathinfo($pathToFile, PATHINFO_EXTENSION), "png",$queryResult->filename);
		
		$_ENV['logger']->write("processing of document : " . $pathToFile . " | res_id : "
        	. $queryResult->res_id);
		echo "processing of document : " . $pathToFile . " \r\n res_id : "
        	. $queryResult->res_id . "\n";
		
       	if (
       		strtoupper($fileFormat) <> 'PDF' 
       		&& strtoupper($fileFormat) <> 'HTML'
       		&& strtoupper($fileFormat) <> 'MAARCH'
       	) {
       		$_ENV['logger']->write("file format not allowed : " . $fileFormat);
			echo "file format not allowed : " . $fileFormat . PHP_EOL;
       	} else {
			$racineOut = $pathOutput . str_replace("#", DIRECTORY_SEPARATOR, $queryResult->path);
			if (!is_dir($racineOut)){
				r_mkdir($racineOut,0777);
				$_ENV['logger']->write("Create $racineOut directory ");
			}
			
			$command = '';
			if (strtoupper($fileFormat) == 'PDF') {
				$command = "convert -thumbnail 400x600 -background white -alpha remove " . escapeshellarg($pathToFile) . "[0] "
					. escapeshellarg($outputPathFile);
			} else {
				$posPoint = strpos($pathToFile, '.');
				$extension = substr($pathToFile, $posPoint);
				$chemin = substr($pathToFile, 0, $posPoint);
				if($extension == '.maarch'){
					if (!copy($pathToFile, $chemin.'.html')) {
					    echo "La copie $pathToFile du fichier a échoué...\n";
					}else{
						echo "La copie $pathToFile du fichier a réussi...\n";
						$cheminComplet = $chemin.".html";
						$command = "wkhtmltoimage --width 164 --height 105 --quality 100 --zoom 0.2 " . escapeshellarg($cheminComplet) . " "
						. escapeshellarg($outputPathFile);

					}
				}else{
					$command = "wkhtmltoimage --width 164 --height 105 --quality 100 --zoom 0.2 " . escapeshellarg($pathToFile) . " "
					. escapeshellarg($outputPathFile);
				}
			}
			//echo $command . PHP_EOL;
			exec($command);
		}
		$stmt2 = $_ENV['db']->query("UPDATE ".$_ENV['tablename']." SET tnl_path = ?, tnl_filename = ? WHERE res_id = ?", array($queryResult->path, str_replace(pathinfo($pathToFile, PATHINFO_EXTENSION), "png",$queryResult->filename), $queryResult->res_id));		
	} else {
        $_ENV['logger']->write("Max batch size ! Stop processing !");
        echo "\r\nMax batch size ! Stop processing !";
        break;
  }
  $cpt_batch_size++;
}
$_ENV['logger']->write("End of application !");
exit();
