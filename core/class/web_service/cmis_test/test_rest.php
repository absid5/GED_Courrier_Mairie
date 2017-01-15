<?php

include_once ('../../../init.php');
if (!isset($_REQUEST['collection'])) {
    $_REQUEST['collection']  = 'letterbox_coll';
}
if (!isset($_REQUEST['resource'])) {
    $_REQUEST['resource']  = 'folder';
}

//INIT CURL
$curl = curl_init();

//BASIC AUTHENTICATION
curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($curl, CURLOPT_USERPWD, 'bblier:maarch');
//curl_setopt($curl, CURLOPT_USERPWD, 'pparker:maarch');

//WS URL
$url = $_SESSION['config']['coreurl'] . 'ws_server.php?CMIS';
if (isset($_REQUEST['collection']) && !empty($_REQUEST['collection'])) {
    $url .= '/' . $_REQUEST['collection'];
}
if (isset($_REQUEST['resource']) && !empty($_REQUEST['resource'])) {
    $url .= '/' . $_REQUEST['resource'];
}
if (isset($_REQUEST['idResource']) && !empty($_REQUEST['idResource'])) {
    $url .= '/' . $_REQUEST['idResource'];
}
curl_setopt($curl, CURLOPT_URL, $url . '/');

if (($_REQUEST['method'] == 'post' || !isset($_REQUEST['method']))&& isset($_REQUEST['xmlFile'])) {
    $xmlAtomFileContent = file_get_contents('core/class/web_service/cmis_test/' . $_REQUEST['xmlFile']);
    curl_setopt($curl, CURLOPT_POSTFIELDS, 'atomFileContent=' . $xmlAtomFileContent);
    curl_setopt($curl, CURLOPT_POST, 1);
}
else {
    //GET, PUT, DELETE METHOD
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, strtoupper($_REQUEST['method']));
}
//var_dump($url);
//var_dump($_SESSION);

//POST CONTENT
//$xmlAtomFileContent = base64_encode(file_get_contents('create_folder.atom.xml'));
//$xmlAtomFileContent = file_get_contents('query.xml');
//$xmlAtomFileContent = file_get_contents('testcreatefolder.atom.xml');
//curl_setopt($curl, CURLOPT_POSTFIELDS, 'atomFileContent=' . $xmlAtomFileContent);
//HTTP METHOD
//if ($_REQUEST['method'] == 'post' || !isset($_REQUEST['method'])) {
//    curl_setopt($curl, CURLOPT_POST, 1);
//} else {
    //GET, PUT, DELETE METHOD
    //curl_setopt($curl, CURLOPT_CUSTOMREQUEST, strtoupper($_REQUEST['method']));
    //curl_setopt($curl, CURLOPT_HTTPHEADER, array('X-HTTP-Method-Override: ' . $_REQUEST['method']));
//}
//RESULT
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
$page = curl_exec($curl);
//var_dump($curl);
curl_close($curl);
print($page);
