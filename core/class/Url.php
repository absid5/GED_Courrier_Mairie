<?php
/*
*   Copyright 2011 Maarch
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
*    along with Maarch Framework.  If not, see <http://www.gnu.org/licenses/>.
*/

/**
 * A simple API to handle and reconstruct URLs in Maarch applications
 *
 * This file provides a static class with two goals:
 * - give access to parts of the url the user used to access the application
 * - reconstructs some meaningful urls to be used in the application
 *
 * Example:
 * <code>
 * <?php
 *
 * // If the user calls the URL
 * // http://example.com/entreprise/apps/maarch_entreprise/index.php
 *
 * require_once 'core/class/Url.php';
 *
 * Url::port(); //-> '80'
 * Url::host(); //-> 'example.com'
 * Url::proto(); //-> 'http'
 * Url::coreurl(); //-> 'http://example.com/entreprise/'
 *
 *
 * // If the user calls the URL
 * // https://example.com:8043/apps/maarch_entreprise/index.php
 *
 *
 * Url::port(); //-> '8043'
 * Url::host(); //-> 'example.com'
 * Url::proto(); //-> 'https'
 * Url::coreurl(); //-> 'https://example.com:8043/'
 *
 * </code>
 *
 * <b>the class Url is compatible with reverse proxies</b>
 *
 * Given the reverse proxy provides the necessary information about the original
 * request, {@link Url} is able to detect that the application is running
 * behind a reverse proxy and looks for special HTTP headers in order to
 * reconstruct URLs that are accessible by a user (i.e. external URLS).
 *
 * {@link Url} looks for the following headers
 * (they must be set by the reverse proxy) :
 * - X-Forwarded-Host: The original host of the request (i.e. the host of the
 *   proxy server)
 * - X-Forwarded-Proto: The original protocol of the request (i.e. the protocol
 *   of the proxy server)
 * - X-Forwarded-Port: The original port of the request (i.e. the port of the
 *   proxy server)
 * - X-Forwarded-Script-Name: The original value of the Script Name variable
 *
 * Example:
 * - the public url is:
 *   https://example.com:8043/apps/maarch_entreprise/index.php
 * - it redirects to:
 *   http://internal/subdir/entreprise/apps/maarch_entreprise/index.php
 *
 * Following headers must be set in the reverse proxy before it forwards the
 * request:
 * - X-Forwarded-Host: example.com
 * - X-Forwarded-Proto: https
 * - X-Forwarded-Port: 8043
 * - X-Forwarded-Script-Name: /apps/maarch_entreprise/index.php
 *
 * Then, the {@link Url} class will return the corrects values:
 * <code>
 * <?php
 *
 * require_once 'core/class/Url.php';
 *
 * Url::coreurl(); //-> 'https://example.com:8043/'
 * </code>
 *
 * If the reverse proxy did not set the necessary headers, it would have return:
 * <code>
 * <?php
 *
 * require_once 'core/class/Url.php';
 *
 * Url::coreurl(); //-> 'http://internal/subdir/entreprise/' -- It is an local
 *                 //only URL, the external user cannot access it.
 * </code>
 *
 * @package Core
 * @author Bruno Carlin <bruno.carlin@maarch.org>
 *
 */

/**
 * Url allows one to get necessary elements to use full urls.
 *
 * It is a static class, which means it does not have to be instantiated to be
 * used. In fact, its constructor has been disabled and tentative to instantiate
 * it will throw a Fatal error.
 *
 * Example of use:
 * <code>
 * <?php
 *
 * require 'core/class/Url.php';
 *
 * $u = new Url(); // Throws a fatal eror
 *
 * Url::host(); // -> 'example.com'
 * Url::coreuri(); //-> 'http://example.com/entreprise/';
 *
 * </code>
 *
 * For performance reasons, it has an internal cache, which means that url
 * components will only be computed once. The cost of calling any method of
 * this class is the cost of an associative array lookup.
 * In terms of performance, and after the first call, calling
 * <code>Url::host()</code> is roughly equivalent to something like
 * <code>$url['host']</code>.
 *
 * @author Bruno Carlin <bruno.carlin@maarch.org>
 *
 */
class Url
{
    private static $_cache = array();

    // @codeCoverageIgnoreStart
    private function __construct() {}
    // @codeCoverageIgnoreEnd

    /**
     * Cleans the internal cache of Url
     *
     * To use with caution. It allows you to clean the internal cache of this
     * class.
     */
    public static function forget()
    {
        self::$_cache = array();
    }


    private static function _buildScriptName()
    {
        return array_key_exists('HTTP_X_FORWARDED_SCRIPT_NAME', $_SERVER)
                ? $_SERVER['HTTP_X_FORWARDED_SCRIPT_NAME']
                : $_SERVER['SCRIPT_NAME'];
    }

    private static function _buildRequestUri()
    {
        if ($_SERVER["QUERY_STRING"] !== "") {
            return self::scriptName() . "?" . $_SERVER["QUERY_STRING"];
        } else {
            return self::scriptName();
        }
    }

    private static function _buildBaseUri()
    {
        $baseUri = array_key_exists('HTTP_X_FORWARDED_SCRIPT_NAME', $_SERVER)
                    ? $_SERVER['HTTP_X_FORWARDED_SCRIPT_NAME']
                    : $_SERVER['SCRIPT_NAME'];
        $baseUri = trim(dirname($baseUri), '/');
        if (($appsPos = strpos($baseUri, 'apps')) !== false) {
            $baseUri = substr($baseUri, 0, max($appsPos - 1, 0));
        }
        return '/'.$baseUri;
    }

    private static function _buildHost()
    {
        if (array_key_exists('HTTP_X_FORWARDED_HOST', $_SERVER)) {
            return $_SERVER['HTTP_X_FORWARDED_HOST'];
        } else {
            $hostParts = explode(':',$_SERVER['HTTP_HOST']);
            return $hostParts[0];
        }
    }

    private static function _buildPort()
    {
        if(array_key_exists('HTTP_X_FORWARDED_PORT', $_SERVER)) {
            return $_SERVER['HTTP_X_FORWARDED_PORT'];
        } else if (array_key_exists('HTTP_X_FORWARDED_PROTO', $_SERVER)) {
        if ($_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {return '443';}
            else {return '80';}
        } else {
            return $_SERVER['SERVER_PORT'];
        }
    }
    
    private static function _buildProto()
    {
        if (array_key_exists('HTTP_X_FORWARDED_PROTO', $_SERVER)) {
            return $_SERVER['HTTP_X_FORWARDED_PROTO'];
        }
        return (array_key_exists('HTTPS', $_SERVER)
                && $_SERVER['HTTPS'] == 'on')
                    ? 'https' : 'http';
    }


    private static function _buildCoreUrl()
    {
        $tmp = self::proto() . '://';
        $tmp .= self::host();
        if (self::proto() === 'http') {
            $tmp .= (self::port() == '80') ? '' : ':' . self::port();
        } else {
            $tmp .= (self::port() == '443') ? '' : ':' . self::port();
        }
        $tmp .= self::baseUri();
        $tmp .= (strrpos(self::baseUri(), '/') == strlen(self::baseUri()) - 1)
                    ? '' : '/';
        return $tmp;
    }


    /**
     * Returns the coreurl of a Maarch application.
     *
     * The core url is the base URL for the application ; i.e. the URL to access
     * the folder where the application is located.
     *
     * Example:
     * <code>
     * <?php
     * require 'core/class/Url.php';
     *
     * Url::coreurl(); // -> 'http://example.com/entreprise/'
     * </code>
     *
     * @return String The coreurl
     */
    public static function coreurl()
    {
        if (!array_key_exists('coreurl', self::$_cache)) {
            self::$_cache['coreurl'] = self::_buildCoreUrl();
        }
        return self::$_cache['coreurl'];
    }

    /**
     * Returns the SCRIPT_NAME of the current request.
     *
     * The SCRIPT_NAME Server vairble is the URI entered by the user to access
     * a page of theapplication, excluding the query string.
     *
     * It is particularly useful to abstract the current url and not test
     * whether the application is running behind a proxy or not.
     *
     * Example:
     * <code>
     * <?php
     * require 'core/class/Url.php';
     *
     * Url::requestUri(); // -> '/entreprise/apps/maarch_entreprise/index.php'
     * </code>
     *
     *  @return String The SCRIPT_NAME value
     */
    public static function scriptName()
    {
        if (!array_key_exists('scriptName', self::$_cache)) {
            self::$_cache['scriptName'] = self::_buildScriptName();
        }
        return self::$_cache['scriptName'];
    }

    /**
     * Returns the request uri of the current request.
     *
     * The request URI is the path entered by the user to access a page of the
     * application, including the query string.
     *
     * It is particularly useful to abstract the current url and not test
     * whether the application is running behind a proxy or not.
     *
     * Example:
     * <code>
     * <?php
     * require 'core/class/Url.php';
     *
     * Url::requestUri(); // -> '/entreprise/apps/maarch_entreprise/index.php?args'
     * </code>
     *
     * @return String The request URI
     */
    public static function requestUri()
    {
        if (!array_key_exists('requestUri', self::$_cache)) {
            self::$_cache['requestUri'] = self::_buildRequestUri();
        }
        return self::$_cache['requestUri'];
    }


    /**
     * Returns the protocol to used to access a Maarch Application.
     *
     * It can only have two values : "http" or "https".
     *
     * Example:
     * <code>
     * <?php
     * require 'core/class/Url.php';
     *
     * Url::protocol(); // -> 'http'
     * </code>
     * @return String The protocol used ("http" or "https")
     */
    public static function proto()
    {
        if (!array_key_exists('proto', self::$_cache)) {
            self::$_cache['proto'] = self::_buildProto();
        }
        return self::$_cache['proto'];
    }


    /**
     * Returns the host used to access a Maarch Application.
     *
     * This sends back the "Host" header of the HTTP request called by the user.
     *
     * Example:
     * <code>
     * <?php
     * require 'core/class/Url.php';
     *
     * Url::host(); // -> 'example.com'
     * </code>
     *
     * @return String The host
     */
    public static function host()
    {
        if (!array_key_exists('host', self::$_cache)) {
            self::$_cache['host'] = self::_buildHost();
        }
        return self::$_cache['host'];
    }


    /**
     * Returns the port of the server used to access a Maarch Application.
     *
     * Example:
     * <code>
     * <?php
     * require 'core/class/Url.php';
     *
     * Url::port(); // -> '80'
     * </code>
     *
     * @return String The port
     */
    public static function port()
    {
        if (!array_key_exists('port', self::$_cache)) {
            self::$_cache['port'] = self::_buildPort();
        }
        return self::$_cache['port'];
    }


    /**
     * Returns the base URI of a Maarch Application.
     *
     * The base URI is the path of URL for the root folder of a Maarch
     * Application.
     *
     * Example:
     * <code>
     * <?php
     * require 'core/class/Url.php';
     *
     * Url::baseUri(); // -> '/subdir/entreprise'
     * </code>
     *
     * @return String The base URI of the application
     */
    public static function baseUri()
    {
        if (!array_key_exists('baseUri', self::$_cache)) {
            self::$_cache['baseUri'] = self::_buildBaseUri();
        }
        return self::$_cache['baseUri'];
    }

}
