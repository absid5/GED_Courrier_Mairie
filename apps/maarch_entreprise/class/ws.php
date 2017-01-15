<?php
global $SOAP_dispatch_map;
global $XMLRPC_dispatch_map;
global $SOAP_typedef;
global $REST_dispatch_map;

$XMLRPC_dispatch_map['addContact'] = Array(
                            'function' => 'addContact',
                            'signature' => array(array('string','string')),
                            'docstring' => '',
                            'method' => "apps#contacts::save"
                            );

$SOAP_dispatch_map['addContact'] = Array(
                                     'in'  => Array('in' => 'string'),
                                     'out' => Array('out' => 'string'),
                                     'method' => "apps#contacts::save"
                                     );

$SOAP_typedef['returnId'] = array( 'returnCode'=>'int',
                                            'contactId'=>'string',
                                            'contactId'=>'string',
                                            'error'=>'string'
                                           );

$SOAP_typedef['arrayOfDataContact'] = array(
    array(
        'arrayOfDataContent' => '{urn:MaarchSoapServer}arrayOfDataContactContent'
    )
);

$SOAP_typedef['arrayOfDataContactContent'] = array(
    'column' => 'string',
    'value' => 'string',
    'type' => 'string',
    'table' => 'string',
);

$SOAP_dispatch_map['CreateContact'] = Array(
                                     'in' => Array('data' => '{urn:MaarchSoapServer}arrayOfDataContact'),
                                     'out' => Array('out' => '{urn:MaarchSoapServer}returnId'),
                                     'method' => "apps#contacts::CreateContact"
                                     );

$REST_dispatch_map['res'] = Array(
    'pathToController' => "apps/maarch_entreprise/class/cmis/cmis_res_controller.php"
);