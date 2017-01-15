<?php

require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_request.php");

$row = 1;
$handle = fopen($_SESSION['config']['corepath'] .
    "apps" . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']. DIRECTORY_SEPARATOR."tools/referentiel/code_postaux_v201410.csv", "r");

$request = new request();

echo "<ul>\n";
while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
    $num = count($data);
    
    
    for ($c=0; $c < $num; $c++) {
    	$pos = stripos($data[3], $_POST['what']);
    	if ($pos === 0) {
    		echo '<li id="'. $data[2].','. $request->show_string(trim($data[3])) .'">'.$data[2].' - '.$request->show_string(trim($data[3])).'</li>';
   			break;
		}
    }
    $row++;
}
echo "</ul>";
fclose($handle);
?>