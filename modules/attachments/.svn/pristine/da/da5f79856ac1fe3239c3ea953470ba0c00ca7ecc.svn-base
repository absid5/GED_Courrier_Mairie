<?php 
global $SOAP_dispatch_map;
global $XMLRPC_dispatch_map;
global $SOAP_typedef;

$SOAP_dispatch_map['storeAttachmentResource'] = array(
    'in'  => array(
        'resId' => 'long',
        'collId' => 'string',
        'encodedContent' => 'string',
        'fileFormat' => 'string',
        'title' => 'string',
    ),
    'out' => array('out' => '{urn:MaarchSoapServer}returnResArray'),
    'method' => "modules/attachments#attachments::storeAttachmentResource"
);
