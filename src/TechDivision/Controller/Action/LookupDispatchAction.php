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
require_once "TechDivision/HttpUtils/Interfaces/Request.php";
require_once 'TechDivision/Controller/Action/Abstract.php';
require_once "TechDivision/Controller/Interfaces/Form.php";
require_once "TechDivision/Controller/Interfaces/Mapping.php";
require_once "TechDivision/Controller/Action/Forward.php";
require_once "TechDivision/Controller/Exceptions/MethodNotFoundException.php";
require_once "TechDivision/Controller/Exceptions/EmptyForwardParameterException.php";
require_once "TechDivision/Controller/Exceptions/RequestParameterNotFoundException.php";
require_once "TechDivision/Controller/Exceptions/InvalidResourceMappingException.php";
require_once "TechDivision/Controller/Exceptions/InvalidResourceKeyException.php";

/**
 * This class implements the functionality to invoke a method
 * on its subclass specified by by a HTTPRequest parameter, 
 * referenced to a key in the resource bundle.
 *
 * @category TechDivision
 * @package TechDivision_Controller
 * @author Tim Wagner <t.wagner@techdivision.com>
 * @copyright TechDivision GmbH
 * @link http://www.techdivision.com
 * @license GPL, version 2.0
 */
abstract class TechDivision_Controller_Action_LookupDispatchAction  
    extends TechDivision_Controller_Action_Abstract {

	/**
	 * Holds the name of the method to return the key map from the subclass.
	 * @var string
	 */
	const KEY_MAP_METHOD_NAME = "getKeyMethodMap";

    /**
     * This method implements the functionality to invoke a method
     * implemented in its subclass. The method that should be invoked
     * has to be specified by a HTTPRequest parameter which name is
     * specified in the configuration file as parameter for the 
     * ActionMapping and that is related to a key in the resource bundle.
     *
     * @return ActionForward Returns a ActionForward
     * @throws EmptyForwardParameterException Is thrown if the parameter specified in the configuration file is empty
     * @throws RequestParameterNotFoundException Is thrown if the parameter with the name specified in the configuration file is not found in the HTTPRequest
     * @throws MethodNotFoundException Is thrown if the method specified by the HTTPRequest parameter or the method to return the key map is not implementend in the DispatchAction subclass
     * @throws InvalidResourceMappingException Is thrown if no resource mapping was found for the passed parameter
     * @throws InvalidResourceKeyException Is thrown if the resource key specified by the button value doesn't exist in the key map of the Action class 
     * @see Action::perform(ActionMapping $actionMapping, ActionForm $actionForm, HTTPRequest $request)
     */
    function perform() {
    	// get the parameter passed by the configuration file
		$parameter = $this->_getActionMapping()->getParameter();
		// if the parameter is not specified, throw an exception			
		if (empty($parameter)) {
			// throw an exception if the parameter is empty
			throw new TechDivision_Controller_Exceptions_EmptyForwardParameterException(
				'Specified parameter for ForwardAction in configuration file must not be empty'
			);
		}			
		// get the button value
		$buttonValue = $this->_getRequest()->getParameter($parameter);
		// check that a parameter was found
		if (empty($buttonValue)) {
			// throw an exception if no resource key was found in the HTTPRequest
			throw new TechDivision_Controller_Exceptions_RequestParameterNotFoundException(
				'Expected parameter ' . $parameter . ' with resource key not found in HTTPRequest'
			);
		}
		// check if an resource mapping for the passed button value is found
		if (($resourceKey = $this->_getActionMapping()->getMappings()->getController()->getResources()->findKeyByValue($buttonValue)) == false) {
			throw new TechDivision_Controller_Exceptions_InvalidResourceMappingException(
				'Resource mapping for value ' . $buttonValue . ' not found'
			);
		}
		// initialize a new reflection object				
		$reflectionObject = new ReflectionObject($this);
		// get the reflection method to load the key map
		$keyMapReflectionMethod = $reflectionObject->getMethod(self::KEY_MAP_METHOD_NAME);
		// load the key map itself
		$keyMap = $keyMapReflectionMethod->invoke($this);
		// check if the requested key exists in the key map
		if (!$keyMap->exists($resourceKey)) { 
			// if not throw an exception			
			throw new TechDivision_Controller_Exceptions_InvalidResourceKeyException(
				'Key ' . $resourceKey . ' doesn\'t exist in key map of class ' . $this->getClass()
			);
		} else {
			// check if the specified method is implemented in the sublass
			if(!$reflectionObject->hasMethod($keyMap->get($resourceKey))) { 
				// if not, throw an exception
				throw new TechDivision_Controller_Exceptions_MethodNotFoundException(
					'Specified method ' . $keyMap->get($resourceKey) . ' not implemented by class ' . $this->getClass()
				);
			}
			// get the reflection method specified by the key map			
			$reflectionMethod = $reflectionObject->getMethod($keyMap->get($resourceKey));
			// invoke and return the method
			return $reflectionMethod->invoke($this);
		}
    }

	/**
	 * This method returns the key map with the
	 * method names indexe with the button names.
	 * 
	 * @return HashMap Holds a HashMap with the method names indexed with the button names 
	 */
    abstract function getKeyMethodMap();
}