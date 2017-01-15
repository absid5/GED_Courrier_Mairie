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

/**
* @defgroup full_text Full-text Module
*/

/**
* Full-text is a Maarch module which allows you to make full text indexing
* with the Lucene engine.<br>
* We use PHP version of Lucene integrated into the ZEND framework.<br>
* This Maarch module proposes a batch allowing the full text indexing.<br>
* This batch is launched for each collection of Maarch and works on Linux
* or Windows OS.<br>
* It course a resources table and brings out documents candidates for full
* text.<br><br>
* A user exit code is stored in fulltext_result column of the document in
* "res_x" :
* <ul>
*   <li>1 : Full Text extraction successfull</li>
*   <li>-1 : No file found</li>
*   <li>-2 : File extension not allowed for lucene</li>
*   <li>2 : no result for this extraction</li>
* </ul>
* @file
* @author Mathieu Donzel <mathieu.donzel@sages-informatique.com>
* @author Laurent Giovannoni <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup full_text
* @brief Extraction of information on PDF with lucene functions of Zend Framework
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
* Managing of errors
* @param  $errno integer number of the error
* @param  $errstr string text of the error
* @param  $errfile string file concerned with the error
* @param  $errline integer line of the error
* @param  $errcontext string context of the error
*/
function errorHandler($errno, $errstr, $errfile, $errline, $errcontext)
{
    $_ENV['logger']->write('from  line ' . $errline . ' : ' . $errstr, 'ERROR', 1);
    $_ENV['ErrorLevel'] = 1;
}

/**
* Check if a folder is empty
* @param  $dir string path of the directory to chek
* @return boolean true if the directory exists
*/
function isDirEmpty($dir)
{
    $dir = opendir($dir);
    $isEmpty = true;
    while (($entry = readdir($dir)) !== false) {
        if ($entry !== '.' && $entry !== '..') {
            $isEmpty = false;
        break;
        }
    }
    closedir($dir);
    return $isEmpty;
}

/**
* Launch the lucene engine if it's a pdf file
* @param  $pathToFile string path of the file to index
* @param  $indexFileDirectory string directory of the lucene index
* @param  $format string format of the document to index
* @param  $id integer id of the document to index
* @return integer  user exit code stored in fulltext_result column of the
* document in "res_x"
*/
function indexFullText($pathToFile, $indexFileDirectory, $format, $Id)
{
    $result = -1;
    if (is_file($pathToFile)) {
        switch (strtoupper($format)) {
            case "PDF":
                $_ENV['logger']->write("it's a PDF file", 'INFO');
                $result = prepareIndexFullTextPdf($pathToFile, $indexFileDirectory, $Id);
                break;
            case "HTML":
                $_ENV['logger']->write("it's a HTML file", 'INFO');
                $result = prepareIndexFullTextHtml($pathToFile, $indexFileDirectory, $Id);
                break;
            case "MAARCH":
                $_ENV['logger']->write("it's a MAARCH file", 'INFO');
                $result = prepareIndexFullTextHtml($pathToFile, $indexFileDirectory, $Id);
                break;
            case "TXT":
                $_ENV['logger']->write("it's a TXT file", 'INFO');
                $result = prepareIndexFullTextTxt($pathToFile, $indexFileDirectory, $Id);
                break;
            default:
            $result = -2;
        }
    }
    return $result;
}

function prepareIndexFullTextPdf($pathToFile, $indexFileDirectory, $Id)
{
    if (is_file($pathToFile)) {
        $tmpFile = $_ENV["base_directory"] . "tmp"
            . DIRECTORY_SEPARATOR . basename($pathToFile) . ".ftx";
        if ($_ENV['osname'] == "WINDOWS") {
            $resultExtraction = exec(escapeshellarg($_ENV['maarch_tools_path'] . "pdftotext"
                . DIRECTORY_SEPARATOR . $_ENV['pdftotext']) . " "
                . escapeshellarg($pathToFile) . " " . escapeshellarg($tmpFile)
            );
            $_ENV['logger']->write(escapeshellarg($_ENV['maarch_tools_path'] . "pdftotext"
                . DIRECTORY_SEPARATOR . $_ENV['pdftotext']) . " "
                . escapeshellarg($pathToFile) . " " . escapeshellarg($tmpFile)
            );
        } elseif ($_ENV['osname'] == "UNIX") {
            $resultExtraction = exec("pdftotext " . escapeshellarg($pathToFile)
                . " " . escapeshellarg($tmpFile) 
            );
            $_ENV['logger']->write("pdftotext " . escapeshellarg($pathToFile) . " " . escapeshellarg($tmpFile));
            echo "pdftotext " . escapeshellarg($pathToFile) . " " . escapeshellarg($tmpFile) . PHP_EOL;
        }
        $fileContent = trim(readFileF($tmpFile));
        if (is_file($tmpFile)) unlink($tmpFile);
        $result = launchIndexFullText($fileContent, $indexFileDirectory, $Id);
    } else {
        $result = 2;
    }
    return $result;
}

function prepareIndexFullTextHtml($pathToFile, $indexFileDirectory, $Id)
{
    if (is_file($pathToFile)) {
        $fileContent = trim(readFileF($pathToFile));
        //remove html tags
        //$fileContent = strip_tags($fileContent);
        $fileContent = convert_html_to_text($fileContent);
        $result = launchIndexFullText($fileContent, $indexFileDirectory, $Id);
    } else {
        $result = 2;
    }
    return $result;
}

function prepareIndexFullTextTxt($pathToFile, $indexFileDirectory, $Id)
{
    if (is_file($pathToFile)) {
        $fileContent = trim(readFileF($pathToFile));
        $result = launchIndexFullText($fileContent, $indexFileDirectory, $Id);
    } else {
        $result = 2;
    }
    return $result;
}

/**
* Retrieve the text of a pdftext and launch the lucene engine
* @param  $pathToFile string path of the file to index
* @param  $indexFileDirectory string directory of the lucene index
* @param  $id integer id of the document to index
* @return integer user exit code is stored in fulltext_result column of the
* document in "res_x"
*/
function launchIndexFullText($fileContent, $tempIndexFileDirectory, $Id) // $IndexFileDirectory is replace by tempIndexFileDirectory
{
    $func = new functions();

    $fileContent = $func->normalize($fileContent);
    $indexFileDirectory = (string) $tempIndexFileDirectory; // with version 1.12, we need a string, not an XML element
    $result = -1;
    if (strlen($fileContent) > 20) {
        if (!is_dir($indexFileDirectory)) {
            $_ENV['logger']->write($indexFileDirectory . " not exists !", "ERROR", 2);
            $index = Zend_Search_Lucene::create($indexFileDirectory);
        } else {
            if (isDirEmpty($indexFileDirectory)) {
                $_ENV['logger']->write($indexFileDirectory . " empty !");
                $index = Zend_Search_Lucene::create($indexFileDirectory);
            } else {
                $index = Zend_Search_Lucene::open($indexFileDirectory);
            }
        }
        $index->setFormatVersion(Zend_Search_Lucene::FORMAT_2_3); // we set the lucene format to 2.3
        Zend_Search_Lucene_Analysis_Analyzer::setDefault(
            new Zend_Search_Lucene_Analysis_Analyzer_Common_Utf8Num_CaseInsensitive() // we need utf8 for accents
        );
        $term = new Zend_Search_Lucene_Index_Term($Id, 'Id');
        foreach ($index->termDocs($term) as $id) {
            $index->delete($id);
        }
        $doc = new Zend_Search_Lucene_Document();
        $doc->addField(Zend_Search_Lucene_Field::UnIndexed('Id', $Id));
        $doc->addField(Zend_Search_Lucene_Field::UnStored(
            'contents', $fileContent)
        );
        $index->addDocument($doc);
        $index->commit();
        $result = 1;
    }
    return $result;
}

/**
* Read a txt file
* @param  $file string path of the file to read
* @return string contents of the file
*/
function readFileF($file)
{
    $result = "";
    if (is_file($file)) {
        $fp = fopen($file, "r");
        $result = fread($fp, filesize($file));
        fclose($fp);
    }
    return $result;
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
    $maarch_directory = $CONFIG->MAARCH_DIRECTORY;
    $_ENV['maarch_directory'] = $maarch_directory;
    $_ENV['base_directory'] = $_ENV['maarch_directory'] . '/modules/full_text/';
    $indexFileDirectory = $CONFIG->INDEX_FILE_DIRECTORY;
    $_ENV['tablename'] = $CONFIG->TABLE_NAME;
    $fulltextColumnName = $CONFIG->FULLTEXT_COLUMN_NAME;
    $_ENV['maarch_tools_path'] = $CONFIG->MAARCH_TOOLS_PATH;
    $_ENV['max_batch_size'] = $CONFIG->MAX_BATCH_SIZE;
}
if (DIRECTORY_SEPARATOR == "/") {
    $_ENV['osname'] = "UNIX";
    $_ENV['pdftotext'] = "pdftotext";
} else {
    $_ENV['osname'] = "WINDOWS";
    $_ENV['pdftotext'] = "pdftotext.exe";
}

$log4phpParams = $xmlconfig->LOG4PHP;
if ((string) $log4phpParams->enabled == 'true') {
    $_ENV['logger']->set_log4PhpLibrary(
        $_ENV['maarch_directory'] . 'apps/maarch_entreprise/tools/log4php/Logger.php'
    );
    $_ENV['logger']->set_log4PhpLogger((string) $log4phpParams->Log4PhpLogger);
    $_ENV['logger']->set_log4PhpBusinessCode((string) $log4phpParams->Log4PhpBusinessCode);
    $_ENV['logger']->set_log4PhpConfigPath((string) $log4phpParams->Log4PhpConfigPath);
    $_ENV['logger']->set_log4PhpBatchName('full_text');
}

$_ENV['logger']->write("Launch of Lucene full text engine");
$_ENV['logger']->write("Loading the xml config file");
$_ENV['logger']->write("Config name : " . $_ENV['config_name']);
$_ENV['logger']->write("Full text engine launched for table : " . $_ENV['tablename']);
require("../../core/class/class_functions.php");
require("../../core/class/class_db_pdo.php");

// Storing text in lucene index
set_include_path($_ENV['maarch_tools_path'] . DIRECTORY_SEPARATOR
    . PATH_SEPARATOR . get_include_path()
);
require_once('Zend/Search/Lucene.php');
include_once('html2text/html2text.php');

$_ENV['db'] = new Database($conf);

$_ENV['logger']->write("connection on the DB server OK !");
$docServers = "SELECT docserver_id, path_template FROM docservers";

$stmt = $_ENV['db']->query($docServers);

$_ENV['logger']->write("docServers found : ");
while ($queryResult=$stmt->fetch(PDO::FETCH_NUM)) {
  $pathToDocServer[$queryResult[0]] = $queryResult[1];
  $_ENV['logger']->write($queryResult[1]);
}
if ($_ENV['tablename'] == 'res_attachments' || $_ENV['tablename'] == 'res_version_attachments') {
    $queryIndexFullText = "SELECT res_id, docserver_id, path, filename, format FROM "
        . $_ENV['tablename'] . " WHERE (" . $fulltextColumnName . " = '0' or "
        . $fulltextColumnName . " = '' or " . $fulltextColumnName . " is null) AND format = 'pdf'";
} else {
    $queryIndexFullText = "SELECT res_id, docserver_id, path, filename, format FROM "
        . $_ENV['tablename'] . " WHERE " . $fulltextColumnName . " = '0' or "
        . $fulltextColumnName . " = '' or " . $fulltextColumnName . " is null ";
}

$_ENV['logger']->write("query to found document with no full text : ".$queryIndexFullText);
$stmt = $_ENV['db']->query($queryIndexFullText);
$cpt_batch_size=0;
$_ENV['logger']->write("max_batch_size : ".$_ENV['max_batch_size']);
while ($queryResult=$stmt->fetch(PDO::FETCH_NUM)) {
    if ($_ENV['max_batch_size'] >= $cpt_batch_size) {
        $pathToFile = $pathToDocServer[$queryResult[1]]
            . str_replace("#", DIRECTORY_SEPARATOR, $queryResult[2])
            . DIRECTORY_SEPARATOR . $queryResult[3];
        $_ENV['logger']->write("processing of document : " . $pathToFile . " | res_id : "
            . $queryResult[0]);
        echo "processing of document : " . $pathToFile . " \r\n res_id : "
            . $queryResult[0] . "\n";
        $result = indexFullText(
            $pathToFile, $indexFileDirectory, $queryResult[4], $queryResult[0]
        );
        if ($result <> 1) {
            $_ENV['logger']->write("Result of processing : " . $pathToFile 
                . " " . $result, "ERROR", $result);
        }
        $_ENV['logger']->write("Result of processing : " . $result);
        echo "Result of processing : " . $result . "\r\n";
        $updateDoc = "UPDATE " . $_ENV['tablename'] . " SET "
            . $fulltextColumnName . " = ? WHERE res_id = ?";
        $_ENV['db']->query($updateDoc, array($result, $queryResult[0]));
    } else {
    $_ENV['logger']->write("Max batch size ! Stop processing !");
    echo "\r\nMax batch size ! Stop processing !";
    break;
  }
  $cpt_batch_size++;
}
$_ENV['logger']->write("Return execution code : ".$_ENV['ErrorLevel']);
$_ENV['logger']->write("End of application !");
exit($_ENV['ErrorLevel']);
