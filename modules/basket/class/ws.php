<?php 
global $SOAP_dispatch_map;
global $XMLRPC_dispatch_map;
global $SOAP_typedef;
global $REST_dispatch_map;

$XMLRPC_dispatch_map['basketSample'] = Array(
                            'function' => 'basketSample',
                            'signature' => array(array('string','string')),
                            'docstring' => '',
                            'method' => "modules/basket#basket::save"
                            );

$SOAP_dispatch_map['basketSample'] = Array(
                                     'in'  => Array('in' => 'string'),
                                     'out' => Array('out' => 'string'),
                                     'method' => "modules/basket#basket::save"
                                     );
$REST_dispatch_map['basket'] = Array(
    'pathToController' => "modules/basket/class/cmis/cmis_basket_controller.php"
);