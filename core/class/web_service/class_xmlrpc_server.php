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
* @brief Maarch XMLRPC class
*
* @file
* @author Laurent Giovannoni <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup core
*/

//declaration of descriptions services vars
if(!isset($XMLRPC_dispatch_map)) {
    $XMLRPC_dispatch_map = Array();
}

/**
 * Class for manage XMLRPC web service
 */
class MyXmlRPCServer {

    var $__dispatch_map;

    function __construct() {
        global $XMLRPC_dispatch_map;
        $this->__dispatch_map = $XMLRPC_dispatch_map;
    }

    function __dispatch($methodname) {
        if (isset($this->__dispatch_map[$methodname])){
            return $this->__dispatch_map[$methodname];
        }
        return null;
    }

    public function __call($method, $args) {
        return call_user_func_array($method, $args);
    }

    /**
     * import of the XMLRPC library
     */
    function importXMLRPCLibs() {
        include("lib/xmlrpc.inc");
        include("lib/xmlrpcs.inc");
        include("lib/xmlrpc_wrappers.inc");
    }

    /**
     * generate XMLRPC server
     */
    function makeXMLRPCServer() {
        global $XMLRPC_dispatch_map;
        $this->importXMLRPCLibs();
        $server = new xmlrpc_server($XMLRPC_dispatch_map, false);
        $server->functions_parameters_type = 'phpvals';
        $server->service();
    }
}
