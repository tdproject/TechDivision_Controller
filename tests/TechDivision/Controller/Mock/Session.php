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

include_once "TechDivision/Collections/Enum.php";
include_once "TechDivision/HttpUtils/Interfaces/Session.php";

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
class TechDivision_Controller_Mock_Session 
	implements TechDivision_HttpUtils_Interfaces_Session {

	/**
	 * Holds the session attributes.
	 * @var array
	 */
    private $attributes = array();
    
    /**
	 * Holds the unique session id.
	 * @var string
	 */
    private $sessionId = null;
    
    /**
	 * Holds the unique session name.
	 * @var string
	 */
    private $sessionName = null;

    /**
     * The constructor initializes the internal array
     * with the global $_SESSION array.
     * 
     * @return void
     */
    public function __construct() {
		// initialize the session id
		$this->sessionId = md5(time());
		$this->sessionName = "MockSession";
    }
	
    /**
     * @see Session::getAttribute($name)
     */
    function getAttribute($name) {
		// get and return the value if it exists
		if(!array_key_exists($name, $this->attributes)) {
			return;
		} else {
			return $this->attributes[$name];
		}
    }

    /**
     * @see Session::setAttribute($name, $attribute)
     */
    function setAttribute($name, $attribute) {
		$this->attributes[$name] = $attribute;
    }

    /**
     * @see Session::getId()
     */
    function getId() {
        return $this->sessionId;
    }

    /**
     * This method returns the name of the session.
     *
     * @return string Holds the name of the session
     */
    function getName(){
        return $this->sessionName;
    }

    /**
     * @see Session::getAttributeNames()
     */
    function getAttributeNames() {
		return new Enum(array_keys($this->attributes));
    }

    /**
     * This method returns the number of attributes
     * found in the session.
     *
     * @return integer Holds the number of attributes found in the session
     */
    function count() {
        return sizeof($this->attributes);
    }

    /**
     * @see Session::invalidate()
     */
    function invalidate() {
		$this->attributes = array();
    }

    /**
     * @see Session::removeAttribute()
     */
    function removeAttribute($name) {
        unset($this->attributes[$name]);
    }
	
	/**
	 * @see Session::getCreationTime()
	 */
	public function getCreationTime() {
		// @todo Still to implement
	}
	
	/**
	 * @see Session::getLastAccessedTime()
	 */
	public function getLastAccessedTime() {
		// @todo Still to implement
	}
	
	/**
	 * @see Session::setMaxInactiveInterval($interval)
	 */
	public function setMaxInactiveInterval($interval) {
		// @todo Still to implement
	}
	
	/**
	 * @see Session::getMaxInactiveInterval()
	 */
	public function getMaxInactiveInterval() {
		// @todo Still to implement
	}
	
	/**
	 * @see Session::isNew()
	 */
	public function isNew() {
		// @todo Still to implement
	}			
}