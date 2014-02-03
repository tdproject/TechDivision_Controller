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
require_once "TechDivision/Controller/Action/Mapping.php";
require_once "TechDivision/Controller/Action/Forward.php";
require_once "TechDivision/Controller/Exceptions/MethodNotFoundException.php";
require_once "TechDivision/Controller/Exceptions/EmptyForwardParameterException.php";
require_once "TechDivision/Controller/Exceptions/RequestParameterNotFoundException.php";

/**
 * This class implements the functionality to invoke a method
 * on its subclass specified by a HTTPRequest parameter.
 *
 * @category TechDivision
 * @package TechDivision_Controller
 * @author Tim Wagner <t.wagner@techdivision.com>
 * @copyright TechDivision GmbH
 * @link http://www.techdivision.com
 * @license GPL, version 2.0
 */
abstract class TechDivision_Controller_Action_DispatchAction 
	extends TechDivision_Controller_Action_Abstract {
	
	/**
	 * Holds the name for the default method to invoke if the paramter with the method name to invoke is not specified.
	 * @var string
	 */
	const DEFAULT_METHOD = "__default";
	
    /**
     * This method implements the functionality to invoke a method
     * implemented in its subclass. The method that should be invoked
     * has to be specified by a HTTPRequest parameter which name is
     * specified in the configuration file as parameter for the 
     * ActionMapping.
     * 
     * @return TechDivision_Controller_Action_Forward Returns a ActionForward
     * @throws TechDivision_Controller_Exceptions_EmptyForwardParameterException 
     * 		Is thrown if the parameter specified in the configuration file is empty
     * @throws TechDivision_Controller_Exceptions_MethodNotFoundException 
     * 		Is thrown if the method specified by the HTTPRequest parameter is not implementend in the DispatchAction subclass 
     * @see TechDivision_Controller_Interfaces_Action::perform()
     * @see TechDivision_Controller_Action_DispatchAction::getDefaultMethod(Request $request)
     */
    public function perform() {
    	// get the parameter passed by the configuration file
		$parameter = $this->_getActionMapping()->getParameter();
		// if the parameter is not specified, throw an exception			
		if (empty($parameter)) {
			// throw an exception if the parameter is empty
			throw new TechDivision_Controller_Exceptions_EmptyForwardParameterException(
				'Specified parameter for ForwardAction in configuration file must not be empty'
			);
		}			
		// get the method to invoke
		if (($method = $this->_getRequest()->getAttribute($parameter)) == null) {
			if (($method = $this->_getRequest()->getParameter($parameter)) == null) { 
				// try to set the default method, if one is specified in the Action
				$method = $this->_getDefaultMethod();
			}
		}
		// initialize a new reflection object				
		$reflectionObject = new ReflectionObject($this);
		// check if the specified method is implemented in the sublass
		if (!$reflectionObject->hasMethod($method)) { 
			// if not, throw an exception
			throw new TechDivision_Controller_Exceptions_MethodNotFoundException(
				'Specified method ' . $method . ' not implemented by class ' . $this->_getClass()
			);
		}
		// get the reflection method
		$reflectionMethod = $reflectionObject->getMethod($method);
		// invoke and return the method
		return $reflectionMethod->invoke($this);
    }
    
    /**
     * This method throws an Exception by default and has to be overwritten by the 
     * subclass implementing the DispatchAction to use a default method to invoke.
     *
     * @throws TechDivision_Controller_Exceptions_RequestParameterNotFoundException 
     * 		Is thrown if the parameter with the name specified in the configuration file is not found in the HTTPRequest
     */
    protected function _getDefaultMethod() {
		// throw an exception if no method name was found in the HTTPRequest
		throw new TechDivision_Controller_Exceptions_RequestParameterNotFoundException(
			'Expected parameter ' . $parameter . ' with method name for Action ' . $this->_getRequest()->getParameter(TechDivision_Controller_Action_Controller::ACTION_PATH, FILTER_SANITIZE_STRING) . ' not found in HTTPRequest'
		);
    }
}