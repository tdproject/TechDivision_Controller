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

require_once 'TechDivision/Lang/Object.php';
require_once 'TechDivision/Controller/Interfaces/Form.php';
require_once 'TechDivision/Controller/Interfaces/Context.php';
require_once 'TechDivision/Controller/Action/Controller.php';
 
/**
 * This class implements a abstract ActionForm
 * with the basic functionality.
 *
 * @category TechDivision
 * @package TechDivision_Controller
 * @author Tim Wagner <t.wagner@techdivision.com>
 * @copyright TechDivision GmbH
 * @link http://www.techdivision.com
 * @license GPL, version 2.0
 */
abstract class TechDivision_Controller_Action_AbstractActionForm 
	extends TechDivision_Lang_Object 
	implements TechDivision_Controller_Interfaces_Form {
        
    /**
     * The Context for the actual Request.
     * @var TechDivision_Controller_Interfaces_Context
     */
    protected $_context = null;

    /**
     * Initializes the ActionForm with the Context for the
     * actual request.
	 *
     * @param TechDivision_Controller_Interfaces_Context $context
     * 		The Context for the actual Request
     * @return void
     */
    public function __construct(
        TechDivision_Controller_Interfaces_Context $context) {
        $this->_context = $context;
    }
	
	/**
	 * Returns the actual Context.
	 * 
	 * @return TechDivision_Controller_Interfaces_Context
	 * 		The actual Context
	 */
	public function getContext()
	{
	    return $this->_context;
	}
	
    /**
     * Returns the ActionMapping for the actual
     * request.
     * 
     * @return TechDivision_Controller_Interfaces_Mapping
     * 		The actual ActionMapping
     */
	public function getActionMapping()
	{
	    return $this->getContext()->getActionMapping();
	}
	
	/**
	 * Returns the actual Request instance.
	 * 
	 * @return TechDivision_HttpUtils_Interfaces_Request
	 * 		The request instance
	 */
	public function getRequest()
	{
	    return $this->getContext()->getRequest();
	}
    
    /**
     * @see TechDivision_Controller_Interfaces_Form::init()
     */
    public function init() {
    	// get the field names to initialize
		$reflectionObject = new ReflectionObject($this);
		// load all properties of the ActionForm
		$properties = $reflectionObject->getProperties();
		// iterate over all fields and add the value from the Request
		for ($i = 0; $i < sizeof($properties); $i++) {
		    // load the next property
		    $reflectionProperty = $properties[$i];
    	    // concatenate the property name
		    $propertyName = str_replace('_', '', $reflectionProperty->getName());
		    // try to load the property value from the Request
		    $value = $this->_getRequestValue($propertyName);
		    // check if a value for the property was found in the request
			if ($value !== null) {
				// concatenate the method name
			    $methodName = 'set' . ucfirst($propertyName);
			    // set the value
			    if ($reflectionObject->hasMethod($methodName)) {
				    $reflectionMethod = $reflectionObject->getMethod($methodName);
				    $reflectionMethod->invokeArgs($this, array($value));
				}
			}
		}
    }
    
    /**
     * This method returns the value with the passed key from the
     * also passed Request, if it exists. If not, it returns 
     * null.
     * 
     * First the method checks if a value exists as a request 
     * parameter, if yes, the value is returned. If not, it checks
     * if the value exists as an request attribute, if yes, the
     * value is returned.
     * 
     * @param TechDivision_HttpUtils_Interfaces_Request $request 
     * 		The actual Request instance with the submitted values
     * @param string $key Holds the key of the value to return
     * @return mixed Holds the value for the passed key found in the also passed Request
     */
    protected function _getRequestValue($key) {
        // load the request
        $request = $this->getRequest();
        // first try to find the value as a single parameter
		if (($value = $request->getParameter($key)) !== null) {
			return $value;
        } 
        // if not found, try to find an array of parameters
        if (($value = $request->getParameterValues($key)) !== null) {
        	return $value;
        }
        // if not found, try to find a key/value pair as an attribute
        if (($value = $request->getAttribute($key)) !== null) {
        	return $value;
        }
        // else return nothing
		return;  
    }
}