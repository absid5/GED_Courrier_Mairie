<?php

/*
*   Copyright 2010 Maarch
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
* @brief Maarch SOAP class
*
* @file
* @author Laurent Giovannoni <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup core
*/

require_once('Maarch_SOAP_DISCO_Server.php');

//declaration of descriptions services vars
if(!isset ($SOAP_dispatch_map)) {
    $SOAP_dispatch_map = Array ();
}
if(!isset ($SOAP_typedef)) {
    $SOAP_typedef = Array ();
}

/**
 * Class for manage SOAP web service
 */
class MaarchSoapServer extends webService {
    
    var $__dispatch_map;
    var $__typedef;
    
    function __construct() {
        global $SOAP_dispatch_map, $SOAP_typedef;
        $this->__dispatch_map = $SOAP_dispatch_map;
        $this->__typedef = $SOAP_typedef;
    }
    
    function __dispatch($methodname) {
        if(isset($this->__dispatch_map[$methodname])) {
            return $this->__dispatch_map[$methodname];
        }
        return null;
    }
    
    /**
     * parse the requested method and return path, object and method to call
     * @param   $method string the methode in the signature
     * @param   $args array array of method arguments 
     * @return  call of the method
     */
    public function __call($method, $args) {
        $webService = new webService();
        $methodArray = array();
        $methodArray = $webService->parseRequestedMethod($method, $this->__dispatch_map);
        if($methodArray['path'] == "custom") {
            return call_user_func_array($method, $args);
        } else {
            if(file_exists($methodArray['path']) && $methodArray['object'] <> "" && $methodArray['method'] <> "") {
                require_once($methodArray['path']);
                $objectControler = new $methodArray['object']();
                try {
                    return call_user_func_array(array($objectControler, $methodArray['method']), $args);
                } catch (Exception $e) {
                    if ($_SESSION['config']['debug'] == "true") {
                        var_dump($e);
                    }
                }
                
            }
        }
    }
    
    /**
     * import of the SOAP library
     */
    function importSOAPLibs() {
        if (version_compare(PHP_VERSION, '7.0.0') >= 0) {
            require ('apps/maarch_entreprise/tools/PEAR/SOAP/Server.php');
        } else {
            require ('SOAP/Server.php');
        }
    }
    
    /**
     * launch SOAP server
     */
    function launchSOAPServer() {
        $server = new SOAP_Server();
        $webservice = new MaarchSoapServer();
        //var_dump($webservice);
        $server->addObjectMap($webservice, 'urn:MaarchSoapServer');
        return $server;
    }
    
    /**
     * generate WSDL
     */
    function makeWSDL() {
        $this->importSOAPLibs();
        $server = $this->launchSOAPServer();
        $disco = new Maarch_SOAP_DISCO_Server($server, 'MaarchSoapServer');
        header("Content-type: text/xml");
        echo $disco->getWSDL();
    }
    
    /**
     * generate SOAP server
     */
    function makeSOAPServer() {
        //global $HTTP_RAW_POST_DATA;
        $data = file_get_contents("php://input");
        //var_export($HTTP_RAW_POST_DATA);
//         echo "
// ------------------------------------------------
//         ";
        //var_export($data);
        $this->importSOAPLibs();
        $server = $this->launchSOAPServer();
        $server->service($data);
    }
    
    /**
     * discover server
     */
    function makeDISCO() {
        $this->importSOAPLibs();
        $server = $this->launchSOAPServer();
        $disco = new Maarch_SOAP_DISCO_Server($server, 'MaarchSoapServer');
        header("Content-type: text/xml");
        echo $disco->getDISCO();
    }
}
