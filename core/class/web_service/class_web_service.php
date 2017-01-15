<?php

/*
*   Copyright 2012 Maarch
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
* @brief Maarch web service class
*
* @file
* @author Laurent Giovannoni <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup core
*/

/**
 * Class for manage web service
 */
class webService {

    /**
     * load web service catalog of the Maarch core
     */
    function WSCoreCatalog() {
        if (file_exists($_SESSION['config']['corepath'] . 'custom'
            . DIRECTORY_SEPARATOR . $_SESSION['custom_override_id']
            . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'class'
            . DIRECTORY_SEPARATOR . 'ws.php')
        ) {
            require($_SESSION['config']['corepath'] . 'custom'
                . DIRECTORY_SEPARATOR . $_SESSION['custom_override_id']
                . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR
                . 'class' . DIRECTORY_SEPARATOR . 'ws.php'
            );
        } elseif (file_exists($_SESSION['config']['corepath']
            . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'class'
            . DIRECTORY_SEPARATOR . 'ws.php')
        ) {
            require('core' . DIRECTORY_SEPARATOR . 'class'
                . DIRECTORY_SEPARATOR . 'ws.php'
            );
        }
    }

    /**
     * load web service catalog of the Maarch application
     */
    function WSAppsCatalog() {
        if (file_exists($_SESSION['config']['corepath'] . 'custom'
            . DIRECTORY_SEPARATOR . $_SESSION['custom_override_id']
            . DIRECTORY_SEPARATOR . 'apps' . DIRECTORY_SEPARATOR
            . $_SESSION['businessapps'][0]['appid'] . DIRECTORY_SEPARATOR
            . 'class' . DIRECTORY_SEPARATOR . 'ws.php')
        ) {
            require($_SESSION['config']['corepath'] . 'custom'
                . DIRECTORY_SEPARATOR . $_SESSION['custom_override_id']
                . DIRECTORY_SEPARATOR . 'apps' . DIRECTORY_SEPARATOR
                . $_SESSION['businessapps'][0]['appid'] . DIRECTORY_SEPARATOR
                . 'class' . DIRECTORY_SEPARATOR . 'ws.php'
            );
        } else {
            require('apps' . DIRECTORY_SEPARATOR
                . $_SESSION['businessapps'][0]['appid'] . DIRECTORY_SEPARATOR
                . 'class' . DIRECTORY_SEPARATOR . 'ws.php'
            );
        }
    }

    /**
     * load web service catalog of the Maarch loading modules
     */
    function WSModulesCatalog() {
        for ($cptModules=0;$cptModules<count($_SESSION['modules']);$cptModules++) {
            if (
                $_SESSION['modules'][$cptModules]['moduleid'] <> ""
                && file_exists($_SESSION['config']['corepath'] . 'custom'
                    . DIRECTORY_SEPARATOR . $_SESSION['custom_override_id']
                    . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR
                    . $_SESSION['modules'][$cptModules]['moduleid']
                    . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR . 'ws.php'
                )
            ) {
                require($_SESSION['config']['corepath'] . 'custom'
                    . DIRECTORY_SEPARATOR . $_SESSION['custom_override_id']
                    . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR
                    . $_SESSION['modules'][$cptModules]['moduleid']
                    . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR . 'ws.php'
                );
            } elseif (
                $_SESSION['modules'][$cptModules]['moduleid'] <> ""
                && file_exists($_SESSION['config']['corepath']
                    . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR
                    . $_SESSION['modules'][$cptModules]['moduleid']
                    . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR . 'ws.php'
                )
            ) {
                require($_SESSION['config']['corepath'] . DIRECTORY_SEPARATOR
                    . 'modules' . DIRECTORY_SEPARATOR
                    . $_SESSION['modules'][$cptModules]['moduleid']
                    . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR . 'ws.php'
                );
            }
        }
    }

    /**
     * load web service catalog of the Maarch custom required
     */
    function WScustomCatalog() {
        if (file_exists($_SESSION['config']['corepath'] . 'custom'
            . DIRECTORY_SEPARATOR . $_SESSION['custom_override_id']
            . DIRECTORY_SEPARATOR . 'ws.php')
        ) {
            require($_SESSION['config']['corepath'] . 'custom'
                . DIRECTORY_SEPARATOR . $_SESSION['custom_override_id']
                . DIRECTORY_SEPARATOR . 'ws.php'
            );
        }
    }

    /**
     * web service authentification
     */
    function authentication() {
        if (
            (isset($_SERVER["PHP_AUTH_USER"])
                && isset($_SERVER["PHP_AUTH_PW"])
                && isset($_SERVER["HTTP_AUTHORIZATION"])
            )
            && $_SERVER["PHP_AUTH_USER"] && $_SERVER["PHP_AUTH_PW"]
            && preg_match("/^Basic /", $_SERVER["HTTP_AUTHORIZATION"])
        ) {
            list($_SERVER["PHP_AUTH_USER"], $_SERVER["PHP_AUTH_PW"])
                = explode(":", base64_decode(substr($_SERVER["HTTP_AUTHORIZATION"], 6)));
        }
        $authenticated = false;
        if (
            (isset($_SERVER["PHP_AUTH_USER"])
                && isset($_SERVER["PHP_AUTH_PW"])
            )
            && ($_SERVER["PHP_AUTH_USER"] || $_SERVER["PHP_AUTH_PW"])
        ) {
            require_once("core" . DIRECTORY_SEPARATOR . "class"
                . DIRECTORY_SEPARATOR . "class_functions.php");
            require_once('core' . DIRECTORY_SEPARATOR . 'class'
                . DIRECTORY_SEPARATOR . 'class_security.php');
            $func = new functions();
            $connexion = new Database();
            if ($func->isEncrypted() == "true") {
                $_SESSION['user']['UserId'] = $func->decrypt($_SERVER["PHP_AUTH_USER"]);
                $password = $func->decrypt($_SERVER["PHP_AUTH_PW"]);
            } else {
                $_SESSION['user']['UserId'] = $_SERVER["PHP_AUTH_USER"];
                $password = $_SERVER["PHP_AUTH_PW"];
            }

            $userID = str_replace('\'', '', $_SESSION['user']['UserId']);
            $userID = str_replace('=', '', $userID);
            $userID = str_replace('"', '', $userID);
            $userID = str_replace('*', '', $userID);
            $userID = str_replace(';', '', $userID);
            $userID = str_replace('--', '', $userID);
            $userID = str_replace(',', '', $userID);
            $userID = str_replace('$', '', $userID);
            $userID = str_replace('>', '', $userID);
            $userID = str_replace('<', '', $userID);

            $sec = new security();
            $stmt = $connexion->query(
                "select * from " . $_SESSION['tablename']['users']
                . " where user_id = ? and password = ? and STATUS <> 'DEL'",
                array($userID, $sec->getPasswordHash($password))
            );
            if ($stmt->rowCount() > 0) {
                $authenticated = true;
            }
        }
        return $authenticated;
    }

    /**
     * launch the web service engine required
     */
    function launchWs() {
        require_once('core/class/web_service/class_rest_server.php');
        require_once('core/class/web_service/class_soap_server.php');
        require_once('core/class/web_service/class_xmlrpc_server.php');
        $restServer = new MyRestServer();
        $soapServer = new MaarchSoapServer();
        $xmlRPC = new MyXmlRPCServer();
        $wsMode = explode('/', $_SERVER['QUERY_STRING']);
        
        if (isset($wsMode[0]) && !empty($wsMode[0])) {
            $wsMode = $wsMode[0];
        } else {
            $wsMode = 'RAS';
        }
        $wsMode = explode('&', $wsMode);
        if (isset($wsMode[0]) && !empty($wsMode[0])) {
            $wsMode = $wsMode[0];
        }
        if (
            isset($wsMode)
            && strcasecmp($wsMode,'wsdl') == 0
        ) {
            //WSDL
            $soapServer->makeWSDL();
        } elseif (
            isset($wsMode)
            && strcasecmp($wsMode,'xmlrpc') == 0
        ) {
            //XMLRPC
            $xmlRPC->makeXMLRPCServer();
        } elseif (
            isset($wsMode)
            && strcasecmp($wsMode,'rest') == 0
        ) {
            //REST
            $restServer->makeRESTServer();
        } elseif (
            isset($wsMode)
            && strcasecmp($wsMode,'cmis') == 0
        ) {
            //CMIS
            $restRequest = explode('/', $_SERVER['QUERY_STRING']);
            if (count($restRequest) > 1) {
                $restServer->makeRESTServer();
            } else {
                $restServer->makeCMISCatalog();
            }
        } else {
            
            //SOAP BY DEFAULT
            if (
                isset($_SERVER['REQUEST_METHOD'])
                && $_SERVER['REQUEST_METHOD']=='POST'
            ) {
                $soapServer->makeSOAPServer();
            } else {
                //default : xml Discovery
                $soapServer->makeDISCO();
            }
        }
    }

    /**
     * parse the requested method and return path, object and method to call
     * @param   $method string the methode in the signature
     * @param   $methods array array of signature
     * @return  array with path, object and method
     */
    function parseRequestedMethod($method, $methods) {
        if (is_array($methods)) {
            require_once('core/class/class_functions.php');
            $arrayMethods = array();
            $func = new functions();
            //var_dump($methods);
            $arrayMethods = $func->object2array($methods);
            //$func->show_array($arrayMethods);
            foreach (array_keys($arrayMethods) as $keyMethod) {
                if ($arrayMethods[$keyMethod]["method"] == "custom") {
                    $resultArray = array("path" => "custom", "method" => null);
                    break;
                } elseif ($keyMethod == $method) {
                    $rootPathArray = array();
                    $stringMethod = $arrayMethods[$keyMethod]["method"];
                    $rootPathArray = explode("#",$stringMethod);
                    $rootPath = $rootPathArray[0];
                    $objectPath = $rootPathArray[1];
                    $objectPathArray = array();
                    $objectPathArray = explode("::",$objectPath);
                    if ($rootPath == "core") {
                        $path = "core" . DIRECTORY_SEPARATOR . "class"
                              . DIRECTORY_SEPARATOR . $objectPathArray[0] . "_controler.php";
                    } elseif ($rootPath == "apps") {
                        $path = "apps" . DIRECTORY_SEPARATOR
                              . $_SESSION['businessapps'][0]['appid']
                              . DIRECTORY_SEPARATOR . "class"
                              . DIRECTORY_SEPARATOR . $objectPathArray[0] . "_controler.php";
                    } else {
                        preg_match("'modules'", $rootPath, $out);
                        if (count($out[0])) {
                            $modulePathArray = array();
                            $modulePathArray = explode("/",$rootPath);
                            $path = "modules" . DIRECTORY_SEPARATOR
                                  . $modulePathArray[1] . DIRECTORY_SEPARATOR
                                  . "class" . DIRECTORY_SEPARATOR
                                  . $objectPathArray[0] . "_controler.php";
                        }
                    }
                    $resultArray = array(
                        "path" => $path,
                        "object" => $objectPathArray[0] . "_controler",
                        "method" => $objectPathArray[1]
                    );
                    break;
                }
            }
        } else {
            $resultArray = array("path" => null, "method" => null);
        }
        return $resultArray;
    }
}
