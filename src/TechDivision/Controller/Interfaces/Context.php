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

require_once "TechDivision/Controller/Action/Controller.php";
 
/**
 * This is the interface for the container with all
 * data necessary to process the actual request.
 *
 * @category TechDivision
 * @package TechDivision_Controller
 * @author Tim Wagner <t.wagner@techdivision.com>
 * @copyright TechDivision GmbH
 * @link http://www.techdivision.com
 * @license GPL, version 2.0
 */
interface TechDivision_Controller_Interfaces_Context {
	
	/**
	 * This method returns the value with 
	 * the passed name.
	 * 
	 * @param string $name Holds the name of the value to return
	 * @return mixed Holds the requested value
	 */
	public function getAttribute($name);
	
	/**
	 * This method returns all attributes
	 * from the internal array.
	 * 
	 * @return array Holds the internal attributes as an array
	 */
	public function getAttributes();
	
	/**
	 * This method sets the passed value with
	 * the specified name in the internal array.
	 * 
	 * @param string $name Holds the name to add the value with
	 * @param mixed $value Holds the value to add
	 * @return void
	 */
	public function setAttribute($name, $value);
	
	/**
	 * This method removes the value with the
	 * passed name from the internal array.
	 * 
	 * @param string $name Holds the name of the value to return
	 */
	public function removeAttribute($name);
	
	/**
     * Returns the reference to the RequestProcessor.
     *
     * @return TechDivision_Controller_Interfaces_RequestProcessor 
     * 		The reference to the RequestProcessor.
	 */
	public function getController();
	
	public function getAction();
	
	public function getActionMapping();
	
	public function getActionForm();
	
	public function getActionForward();
	
	/**
	 * Returns the actual request instance.
	 * 
	 * @return TechDivision_HttpUtils_Interfaces_Request
	 * 		The request instance
	 */
	public function getRequest();
	
	public function setActionForward(
	    TechDivision_Controller_Interfaces_Forward $actionForward);
}