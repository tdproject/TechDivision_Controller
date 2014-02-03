<?php

/**
 * License: GNU General Public License
 *
 * Copyright (c) 2009 TechDivision GmbH.  All rights reserved.
 * Note: Original work copyright to respective authors
 *
 * This file is part of TechDivision_Controller.
 *
 * TechDivision_Controller free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * TechDivision_Controller distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307,
 * USA.
 *
 * @package TechDivision_Controller
 */

include_once "TechDivision/Collections/HashMap.php";
include_once "TechDivision/HttpUtils/Interfaces/Request.php";
include_once "TechDivision/Controller/Mock/Session.php";

/**
 * This is a mock object for a PHPUnit test.
 *
 * @category TechDivision
 * @package TechDivision_Controller
 * @author Tim Wagner <t.wagner@techdivision.com>
 * @copyright TechDivision GmbH
 * @link http://www.techdivision.com
 * @license GPL, version 2.0
 */
class TechDivision_Controller_Mock_Request 
	implements TechDivision_HttpUtils_Interfaces_Request {

    /**
     * Holds the actual session object.
     * @var HTTPSession
     */
    private $session = null;
	
	/**
	 * Holds the attributes of the actual request.
	 * @var array
	 */
	private $attributes = array();
	
	/**
	 * Holds the parameters of the actual request.
	 * @var array
	 */
	private $parameters = array();

    /**
     * Holds the actual HTTPRequest instance.
     * @var HTTPRequest
     */
    private static $INSTANCE = null;
    
	/**
	 * Holds the key for a get request.
	 * @var string
	 */
	public static $REQUEST_METHOD_GET = "GET";

	/**
	 * Holds the key for a post request.
	 * @var string
	 */
	
	public static $REQUEST_METHOD_POST = "POST";
	
    /**
     * This method returns the acutal HTTPRequest
     * instance as a singleton.
     * 
     * @return HTTPRequest Holds the acutal HTTPRequest instance
     */
    public function singleton() {
		// check it the HTTPRequest is already initialized
		if(self::$INSTANCE == null) { // if not, initialize it
			self::$INSTANCE = new TechDivision_Controller_Mock_Request();
		}
		// return the actual HTTPRequest instance
		return self::$INSTANCE;
    }
    
    /**
     * @see Request::getAttribute($name)
     */
    public function getAttribute($name) {
		// check if a attribute exists
		if(array_key_exists($name, $this->attributes)) {
			// if yes, return it
			return $this->attributes[$name];
		}
		// else return nothing
		return;
    }

    /**
     * @see Request::setAttribute($name, $attribute)
     */
    public function setAttribute($name, $attribute) {
		$this->attributes[$name] = $attribute;
    }
	
    /**
     * @see Request::getSession($create = true)
     */
    public function getSession($create = true) {
		if($this->session == null && $create) {
			$this->session = new TechDivision_Controller_Mock_Session();	
		}			
		return $this->session;
    }
    
    /**
     * @see Request::removeAttribute($name)
     */
    public function removeAttribute($name) {
		unset($this->attributes[$name]);
	}
	
	/**
	 * @see Request::getQueryString()
	 */
	public function getQueryString() {
		return getenv("QUERY_STRING");
	}
	
	/**
	 * @see Request::getRequestURI()
	 */
	public function getRequestURI() {
		return getenv("REQUEST_URI");
	}
	
	/**
	 * @see Request::getRequestURL()
	 */
	public function getRequestURL() {
		return getenv("SCRIPT_NAME");
	}
	
	/**
	 * @see Request::getServerName()
	 */
	public function getServerName() {
		return getenv("SERVER_NAME");
	}
	
	/**
	 * @see Request::getServerAddr()
	 */
	public function getServerAddr() {
		return getenv("SERVER_ADDR");
	}
	
	/**
	 * @see Request::getServerPort()
	 */
	public function getServerPort() {
		return getenv("SERVER_PORT");
	}
	
	/**
	 * @see Request::getRequestMethod()
	 */
	public function getRequestMethod() {
		return getenv("REQUEST_METHOD");
	}
	
	/**
	 * @see Request::getRemoteHost()
	 */
	public function getRemoteHost() {
		// @todo Has to be implemented
		return null;
	}
	
	/**
	 * @see Request::getRemoteAddr()
	 */
	public function getRemoteAddr() {
		return getenv("REMOTE_ADDR");
	}
	
	/**
	 * @see Request::getScriptFilename()
	 */
	public function getScriptFilename() {
		return getenv("SCRIPT_FILENAME");
	}
	
	/**
	 * @see Request::getScriptName()
	 */
	public function getScriptName() {
		return getenv("SCRIPT_NAME");
	}
	
	/**
	 * @see Request::getParameter($name, $filter = null, $filterOptions = null)
	 */
	public function getParameter($name, $filter = null, $filterOptions = null) {
		// globalize the request
		global $_REQUEST;
		// get the value
		if(array_key_exists($name, $_REQUEST)) {
			if(!is_array($_REQUEST[$name])) {
				// get the value if it is not an array
				return $_REQUEST[$name];
			}
		}
		// else return nothing
		return;
	}
	
	/**
	 * @see Request::getParameterMap()
	 */
	public function getParameterMap() {
		// return a HashMap with the request parameters 
		return new HashMap($thist->parameters);
	}
	
	/**
	 * @see Request::getParameterNames()
	 */
	public function getParameterNames() {
		// return the keys as array
		return array_keys($this->parameters);
	}
	
	/**
	 * @see Request::getParameterValues($name)
	 */
	public function getParameterValues($name) {
		// get the value
		if(array_key_exists($name, $this->parameters)) {
			if(is_array($this->parameters[$name])) {
				// get the value if it is an array
				return $this->parameters[$name];
			}
		}
		// check if it is a upload, then get the values
		if(array_key_exists($name, $_FILES)) {
			return $this->parameters[$name];
		}
		// else return nothing
		return;
	}
	
	public function getReferer() {
		return getenv("HTTP_REFERER");
	}
	
	/**
	 * @see Request::getRequestedSessionId()
	 */
	public function getRequestedSessionId() {
		// @todo Still to implement
	}
			
	/**
	 * @see Request::isRequestedSessionIdValid()
	 */
	public function isRequestedSessionIdValid() {
		// @todo Still to implement
	}
			
	/**
	 * @see Request::isRequestedSessionIdFromURL()
	 */
	public function isRequestedSessionIdFromURL() {
		// @todo Still to implement
	}
			
	/**
	 * @see Request::isRequestedSessionIdFromCookie()
	 */
	public function isRequestedSessionIdFromCookie() {
		// @todo Still to implement
	}
	
	/**
	 * This method writes all items of the internal array
	 * with attributes in the parameters variable.
	 * 
	 * @return void
	 */
	public function toRequest() {
		// add all attributes back to the request
		foreach($this->attributes as $key => $attribute) {
			$this->parameters[$key] = $attribute;
		}
	}
	
	/**
	 * @see Request::getUserAgent()
	 */
	public function getUserAgent() {
		return getenv("HTTP_USER_AGENT");
	}			        
}