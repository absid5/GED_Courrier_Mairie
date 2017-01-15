<?php 
global $SOAP_dispatch_map;
global $XMLRPC_dispatch_map;
global $SOAP_typedef;

$SOAP_dispatch_map['addNote'] = array(
    'in'  => array(
        'resId' => 'long',
        'collId' => 'string',
        'noteContent' => 'string',
    ),
    'out' => array('out' => '{urn:MaarchSoapServer}returnArray'),
    'method' => "modules/notes#notes::addNote"
);

