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
require_once 'TechDivision/Controller/Action/Abstract.php';
require_once "TechDivision/Controller/Interfaces/Form.php";
require_once "TechDivision/Controller/Action/Mapping.php";
require_once "TechDivision/Controller/Action/Forward.php";
require_once "TechDivision/Controller/Exceptions/EmptyForwardParameterException.php";

/**
 * This class implements the functionality to forward the 
 * user to the page specified as parameter in the struts 
 * configuration file.
 *
 * @category TechDivision
 * @package TechDivision_Controller
 * @author Tim Wagner <t.wagner@techdivision.com>
 * @copyright TechDivision GmbH
 * @link http://www.techdivision.com
 * @license GPL, version 2.0
 */
class TechDivision_Controller_Action_ForwardAction  
    extends TechDivision_Controller_Action_Abstract {
	
	/**
	 * Holds the dummy name for the ActionForward.
	 * @var string
	 */
	const FORWARD = "Forward";

    /**
     * This method implements the functionality to forward the framework 
     * automatically to the page specified as parameter for the related 
     * ActionMapping in the configuration file.
     *
     * @return TechDivision_Controller_Action_Forward Returns a ActionForward
     * @throws TechDivision_Controller_Exceptions_EmptyForwardParameterException 
     * 		Is thrown if the parameter specified in the configuration file is empty
     * @see TechDivision_Controller_Interfaces_Action::perform()
     */
    public function perform() {
		// get the parameter passed by the configuration file
		$parameter = $this->_getActionMapping()->getParameter();
		// check that the parameter is not empty
		if (!empty($parameter)) {
			// initialize the ActionForward with the found parameter and return it
			return new TechDivision_Controller_Action_Forward(
				TechDivision_Controller_Action_ForwardAction::FORWARD, 
				$parameter
			);
		}
		// throw an exception if the parameter is empty
		throw new TechDivision_Controller_Exceptions_EmptyForwardParameterException(
			'Specified parameter for ForwardAction in configuration file must not be empty'
		);
    }
}