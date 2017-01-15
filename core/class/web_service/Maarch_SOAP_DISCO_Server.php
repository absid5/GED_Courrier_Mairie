<?php

if (version_compare(PHP_VERSION, '7.0.0') >= 0) {
    require_once('apps/maarch_entreprise/tools/PEAR/SOAP/Disco.php');
} else {
    require_once('SOAP/Disco.php');
}
require_once('core/class/Url.php');

class Maarch_SOAP_DISCO_Server extends SOAP_DISCO_Server
{
    public function __construct($server, $service_name = 'MaarchSoapServer') 
    {
        if (version_compare(PHP_VERSION, '7.0.0') >= 0) {
            $SOAP_DISCO_Server = new SOAP_DISCO_Server($server, $service_name);

            $this->soap_server = $SOAP_DISCO_Server->soap_server;

            $this->soap_server->_namespaces = $SOAP_DISCO_Server->namespaces;

            $this->_service_name = $service_name;
            $this->_service_ns = "urn:$service_name";
        } else {
            $funcGetArgs = func_get_args();
            call_user_func_array(array(parent, 'SOAP_DISCO_Server'),
                                 $funcGetArgs);
        }
        
        $this->host = array_key_exists('HTTP_X_FORWARDED_HOST', $_SERVER) 
                           ? $_SERVER['HTTP_X_FORWARDED_HOST']
                           : $_SERVER['HTTP_HOST'];
    }
    
    private function selfUrl()
    {
        $rootUri = self::_getRootUri();
        $protocol = ( (array_key_exists('HTTPS', $_SERVER) && $_SERVER['HTTPS'] == 'on') ||
                      (array_key_exists('HTTP_FORCE_HTTPS', $_SERVER) && $_SERVER['HTTP_FORCE_HTTPS'] == 'on') )
            ? 'https://' : 'http://' ;
        $lastChar = strlen($rootUri) - 1;
        if ($rootUri[$lastChar] != '/') {
            $rootUri .= '/';
        }
        return $protocol . $this->host . $rootUri . basename(Url::scriptName());
    }
    
    private static function _getRootUri()
    {
        return Url::baseUri();
    }
    
    public function _generate_WSDL()
    {
        parent::_generate_WSDL();

        $this->_wsdl['definitions']['service']['port']['soap:address']['attr']['location'] = 
            $this->selfUrl();
        
        $this->_generate_WSDL_XML();
    }
    
    public function _generate_DISCO()
    {
        parent::_generate_DISCO();
        
        $this->_disco['disco:discovery']['scl:contractRef']['attr']['ref'] =
            $this->selfUrl() . '?wsdl';

        // generate disco xml
        $this->_generate_DISCO_XML($this->_disco);
    }
}
