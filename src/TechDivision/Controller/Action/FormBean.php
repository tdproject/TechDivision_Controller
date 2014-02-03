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
require_once "TechDivision/Collections/HashMap.php";
 
/**
 * This class is a container for the information of
 * an ActionFormBean defined in the configuration
 * file.
 *
 * @category TechDivision
 * @package TechDivision_Controller
 * @author Tim Wagner <t.wagner@techdivision.com>
 * @copyright TechDivision GmbH
 * @link http://www.techdivision.com
 * @license GPL, version 2.0
 */
class TechDivision_Controller_Action_FormBean 
	extends TechDivision_Lang_Object {

    /**
     * This variable holds the class name of the ActionForm
     * @var string
     */
    protected $_type = "";

    /**
     * This variable holds the key of the ActionForm
     * @var string
     */
    protected $_name = "";

    /**
     * This variable holds the path to the include file
     * @var string
     */
    protected $_include = "";		
	
	/**
	 * Holds the properties if the ActionForm is of type DynamicActionForm
	 * @var HashMap
	 */
	protected $properties = null;
	
    /**
     * The constructor initializes the name and the type
     * with the passed values.
     *
     * @param string $name Holds the key of the ActionForward object
     * @param string $path Holds the type of the ActionFormBean object
     * @param string $include Holds the include path of the ActionFormBean object
     */
    public function __construct($name = null, 
								$type = null, 
								$include = null) {
        $this->_name = $name;
        $this->_type = $type;
		$this->_include = $include;
		$this->_properties = new TechDivision_Collections_HashMap();
    }
	
    /**
     * Returns the class name of the ActionForm.
     *
     * @return string Hold the class name of the ActionForm
     */
    public function getType() {
        return $this->_type;
    }

    /**
     * Returns the key of the ActionForm.
     *
     * @return string Holds the key of the ActionForm
     */
    public function getName() {
        return $this->_name;
    }

    /**
     * Sets the class name of the ActionForm.
     *
     * @param string $type Holds the class name of the ActionForm
     */
    public function setType($type) {
        $this->_type = $type;
    }

    /**
     * Sets the key of the ActionForm.
     *
     * @param string $name Holds the key of the ActionForm
     */
    public function setName($name) {
        $this->_name = $name;
    }
	
    /**
     * This method returns the complete path to
     * include the necessary file for the form.
     *
     * @return string Returns the complete path to include the form
     */		
	public function getInclude() {
		return $this->_include;
	}
	
    /**
     * This method sets the complete path to
     * include the necessary file for the form.
     *
     * @param string $include Holds the complete path to include the form
     * @return void
     */
    public function setInclude($include) {
        $this->_include = $include;
    }
	
	/**
	 * This method adds the passed FormProperty
	 * to the list of all properties of the 
	 * DynamicActionForm.
	 * 		
	 * @param FormProperty $property Holds the property to add
	 * @return void
	 */
	public function addFormProperty(TechDivision_Controller_Action_FormProperty $property) {
		$this->_properties->add($property->getName(), $property);
	}
	
	/**
	 * This method returns the FormProperty with the
	 * passed name from the internal list.
	 * 		
	 * @param string $name Holds the name of the FormProperty to return
	 * @return FormProperty The requested FormProperty object
	 */
	public function getFormProperty($name) {
		return $this->_properties->get($name);
	}
	
	/**
	 * This method returns a HashMap with all FormProperty
	 * objects of the DynamicActionForm.
	 * 		
	 * @return HashMap The HashMap with all FormProperty objects
	 */
	public function getFormProperties() {
		return $this->_properties;
	}		
}