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

require_once "TechDivision/Lang/Object.php";
 
/**
 * This class is a container for the information 
 * of an Plugin defined in the configuration file.
 *
 * @category TechDivision
 * @package TechDivision_Controller
 * @author Tim Wagner <t.wagner@techdivision.com>
 * @copyright TechDivision GmbH
 * @link http://www.techdivision.com
 * @license GPL, version 2.0
 */
class TechDivision_Controller_Plugins_Plugin 
	extends TechDivision_Lang_Object {

    /**
     * This variable holds the name of the Plugin
     * @var string
     */
    protected $_name = "";

    /**
     * This variable holds the class name of the Plugin
     * @var string
     */
    protected $_type = "";

    /**
     * This variable holds the path to the include file
     * @var string
     */
    protected $_include = "";		
	
	/**
	 * This variable holds an array with the the plugins initial properties
	 * @var array 
	 */
	protected $_properties = array();
	
    /**
     * The constructor initializes the name and the type
     * with the passed values.
     *
     * @param string $path Holds the type of the Plugin object
     * @param string $include Holds the include path of the Plugin object
     */
    public function __construct($type, $include) {
        $this->_type = $type;
		$this->_include = $include;
    }
	
    /**
     * Returns the class name of the Plugin.
     *
     * @return string Hold the class name of the Plugin
     */
    public function getType() {
        return $this->_type;
    }

    /**
     * Returns the key of the Plugin.
     *
     * @return string Holds the key of the Plugin
     */
    public function getName() {
        return $this->_name;
    }

    /**
     * Sets the class name of the Plugin.
     *
     * @param string $type Holds the class name of the Plugin
     */
    public function setType($type) {
        $this->_type = $type;
    }

    /**
     * Sets the key of the Plugin.
     *
     * @param string $name Holds the key of the Plugin
     */
    public function setName($name) {
        $this->_name = $name;
    }
	
    /**
     * This method returns the complete path to
     * include the necessary file for the Plugin.
     *
     * @return string Returns the complete path to include the Plugin
     */		
	public function getInclude() {
		return $this->_include;
	}
	
    /**
     * This method sets the complete path to
     * include the necessary file for the Plugin.
     *
     * @param string $include Holds the complete path to include the Plugin
     */
    public function setInclude($include) {
        $this->_include = $include;
    }
	
    /**
     * This method sets the properties necessary for
     * the initialization of the plugin.
     *
     * @param string $key Holds the name of the property
     * @param string $value Holds the value for the property
     */
    public function addProperty($key, $value) {
        $this->_properties[$key] = $value;
    }
	
	/**
	 * This method returns an array with the properties 
	 * necessary to initialize the plugin.		
	 * 
	 * @return array Hold an array with the properties necessary to initialize the plugin
	 */
	public function getProperties() {
		return $this->_properties;
	}		
}