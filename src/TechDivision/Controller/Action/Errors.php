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

require_once "TechDivision/Controller/Action/Error.php";
require_once "TechDivision/Collections/HashMap.php";
 
/**
 * This class is a container for ActionError objects and
 * provides methods for handling them.
 *
 * @category TechDivision
 * @package TechDivision_Controller
 * @author Tim Wagner <t.wagner@techdivision.com>
 * @copyright TechDivision GmbH
 * @link http://www.techdivision.com
 * @license GPL, version 2.0
 */
class TechDivision_Controller_Action_Errors 
    extends TechDivision_Collections_HashMap {

	/**
	 * This variable holds the key to register the ActionErrors in the request.
	 * @var string
	 */
	const ACTION_ERRORS = "action.errors";
	
    /**
     * This method searches in the container
     * for the ActionError with the key passed
     * as parameter.
     *
     * @param string $name Holds the key of the requested ActionError
     * @return string Holds the requested ActionError
     */
    public function find($name) {
		// initialize the string for the message
		$message = "";
		// if the ActionError with the passed key exists
        if($this->exists($name)) {
            // set the message ...
            $message = $this->get($name)->getMessage();
        }
        // ... and return it
        return $message;
    }

    /**
     * This method adds the passed ActionError object
     * to the container.
     *
     * @param ActionError $actionError The ActionError object that should be added to the container
     */
    public function addActionError(TechDivision_Controller_Action_Error $actionError) {
    	$this->add($actionError->getName(), $actionError);
    }	
}