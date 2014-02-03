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
require_once 'TechDivision/Logger/Logger.php';
require_once 'TechDivision/Collections/HashMap.php';
require_once 'TechDivision/Controller/Action/Controller.php';
require_once 'TechDivision/Controller/Interfaces/Context.php';
 
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
class TechDivision_Controller_Action_Context 
	extends TechDivision_Lang_Object
	implements TechDivision_Controller_Interfaces_Context {
	    
	const DISPATCHED = 'Key.Dispatched';
	    
	const REQUEST = 'Key.Request';
	
	const ACTION = 'Key.Action';
	
	const ACTION_FORM = 'Key.ActionForm';
	    
	const ACTION_FORWARD = 'Key.ActionForward';
	    
	const ACTION_MAPPING = 'Key.ActionMapping';
		
	/**
	 * Container for storing values.
	 * @var array
	 */
	protected $_attributes = array();

    /**
     * Reference to the RequestProcessor
     * @var TechDivision_Controller_Interfaces_RequestProcessor
     */
    protected $_requestProcessor = null;
	
	/**
     * The constructor initializes the internal reference
     * to the RequestProcessor.
     *
     * @param RequestProcessor $requestProcessor 
     * 		Holds a reference to the RequestProcessor
	 * @return void
	 */
	public function __construct(
    	TechDivision_Controller_Interfaces_RequestProcessor $requestProcessor) {
		$this->_requestProcessor = $requestProcessor;
	}
	
	/**
	 * This method returns the value with 
	 * the passed name.
	 * 
	 * @param string $name Holds the name of the value to return
	 * @return mixed Holds the requested value
	 */
	public function getAttribute($name) {
		if(array_key_exists($name, $this->_attributes)) {
			return $this->_attributes[$name];
		}
	}
	
	/**
	 * This method returns all attributes
	 * from the internal array.
	 * 
	 * @return array Holds the internal attributes as an array
	 */
	public function getAttributes() {
		return $this->_attributes;
	}
	
	/**
	 * This method sets the passed value with
	 * the specified name in the internal array.
	 * 
	 * @param string $name Holds the name to add the value with
	 * @param mixed $value Holds the value to add
	 * @return void
	 */
	public function setAttribute($name, $value) {
		$this->_attributes[$name] = $value;
	}
	
	/**
	 * This method removes the value with the
	 * passed name from the internal array.
	 * 
	 * @param string $name Holds the name of the value to return
	 */
	public function removeAttribute($name) {
		unset($this->_attributes[$name]);
	}
	
	/**
     * Returns the reference to the RequestProcessor.
     *
     * @return TechDivision_Controller_Interfaces_RequestProcessor 
     * 		The reference to the RequestProcessor.
	 */
	public function getController() {
		return $this->_requestProcessor;
	}
	
    public function setAction(
        TechDivision_Controller_Interfaces_Action $action) {
	    $this->setAttribute(
	        TechDivision_Controller_Action_Context::ACTION, 
	        $action
	    );
    }
	
	public function setActionMapping(
	    TechDivision_Controller_Interfaces_Mapping $actionMapping) {
	    $this->setAttribute(
	        TechDivision_Controller_Action_Context::ACTION_MAPPING, 
	        $actionMapping
	    );
	}
	
	public function setActionForm(
	    TechDivision_Controller_Interfaces_Form $actionForm) {
	    $this->setAttribute(
	        TechDivision_Controller_Action_Context::ACTION_FORM, 
	        $actionForm
	    );
	}
	
	public function setActionForward(
	    TechDivision_Controller_Interfaces_Forward $actionForward) {
	    $this->setAttribute(
	        TechDivision_Controller_Action_Context::ACTION_FORWARD, 
	        $actionForward
	    );
	}
	
	public function setRequest(
	    TechDivision_HttpUtils_Interfaces_Request $request) {
	    $this->setAttribute(
	        TechDivision_Controller_Action_Context::REQUEST, 
	        $request
	    );
	}
	
	public function getAction()
	{
	    return $this->getAttribute(
	        TechDivision_Controller_Action_Context::ACTION
	    );
	}
	
	public function getActionMapping()
	{
	    return $this->getAttribute(
	        TechDivision_Controller_Action_Context::ACTION_MAPPING
	    );
	}
	
	public function getActionForm()
	{
	    return $this->getAttribute(
	        TechDivision_Controller_Action_Context::ACTION_FORM
	    );
	}
	
	public function getActionForward()
	{
	    return $this->getAttribute(
	        TechDivision_Controller_Action_Context::ACTION_FORWARD
	    );
	}
	
	/**
	 * Returns the actual request instance.
	 * 
	 * @return TechDivision_HttpUtils_Interfaces_Request
	 * 		The request instance
	 */
	public function getRequest()
	{
	    return $this->getAttribute(
	        TechDivision_Controller_Action_Context::REQUEST
	    );
	}
	
	public function setDispatched($dispatched = true)
	{
	    $this->setAttribute(
	        TechDivision_Controller_Action_Context::DISPATCHED, 
	        $dispatched
	    );
	}
	
	public function isDispatched()
	{
	    return $this->getAttribute(
	        TechDivision_Controller_Action_Context::DISPATCHED
	    );
	}
}