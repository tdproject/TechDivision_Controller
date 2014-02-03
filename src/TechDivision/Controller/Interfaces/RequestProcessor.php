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

require_once "TechDivision/HttpUtils/Interfaces/Request.php";

/**
 * This is the interface for all actions. Every Action
 * in a project has to implement this interface.
 *
 * @category TechDivision
 * @package TechDivision_Controller
 * @author Tim Wagner <t.wagner@techdivision.com>
 * @copyright TechDivision GmbH
 * @link http://www.techdivision.com
 * @license GPL, version 2.0
 */
interface TechDivision_Controller_Interfaces_RequestProcessor {
	
    /**
     * This method is the main one of the controller and has to
     * be invoked on each request. This method is responsible for
     * the flow of the framework.
     *
     * @param TechDivision_HttpUtils_Interfaces_Request $request 
     * 		Holds a reference to the actual Request instance
     * @return string Returns the value of the last path parameter of the ActionForward specified in the actual ActionMapping
     */
    public function process(TechDivision_HttpUtils_Interfaces_Request $request);
}