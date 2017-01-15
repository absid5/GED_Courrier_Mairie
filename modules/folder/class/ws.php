<?php 
global $SOAP_dispatch_map;
global $XMLRPC_dispatch_map;
global $SOAP_typedef;
global $REST_dispatch_map;

$XMLRPC_dispatch_map['getFolder'] = Array(
    'function' => 'getFolder',
    'signature' => array(array('string','string')),
    'docstring' => '',
    'method' => "modules/folder#folder::get"
);

$SOAP_dispatch_map['getFolder'] = Array(
    'in'  => Array('in' => 'string'),
    'out' => Array('out' => 'string'),
    'method' => "modules/folder#folder::get"
);

$REST_dispatch_map['folder'] = Array(
    'pathToController' => "modules/folder/class/cmis/cmis_folder_controller.php"
);
