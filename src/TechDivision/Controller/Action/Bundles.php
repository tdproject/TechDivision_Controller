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
require_once "TechDivision/Collections/HashMap.php";

/**
 * This class is a container for the Bundle objects defined
 * in the configuration file of the application and provides
 * several help functions.
 *
 * @category TechDivision
 * @package TechDivision_Controller
 * @author Tim Wagner <t.wagner@techdivision.com>
 * @copyright TechDivision GmbH
 * @link http://www.techdivision.com
 * @license GPL, version 2.0
 */
class TechDivision_Controller_Action_Bundles
	extends TechDivision_Collections_HashMap {

    /**
     * Holds a reference to the instance
     * @var TechDivision_Controller_Interfaces_RequestProcessor
     */
    protected $_requestProcessor = null;

    /**
     * This method adds the Bundle object passed as a parameter
     * to the container.
     *
     * @param TechDivision_Controller_Action_Bundle $bundle
     * 		Holds an instance of an Bundle
     */
    public function addBundle(TechDivision_Controller_Action_Bundle $bundle) {
        $this->add($bundle->getKey(), $bundle);
    }

    /**
     * This method returns a reference to the internal RequestProcessor object.
     *
     * @return TechDivision_Controller_Interfaces_RequestProcessor
     * 		Holds a reference to the internal RequestProcessor object
     */
    public function getController() {
        return $this->_requestProcessor;
    }

    /**
     * This method initializes the internal reference of the
     * RequestProcessor with the object passed as a parameter.
     *
     * @param TechDivision_Controller_Interfaces_RequestProcessor $requestProcessor
     * 		Holds a reference of the RequestProcessor object
     * @return TechDivision_Controller_Action_Bundles
     * 		The instance itself
     */
    public function setController(
    	TechDivision_Controller_Interfaces_RequestProcessor $requestProcessor) {
        $this->_requestProcessor = $requestProcessor;
        return $this;
    }

    /**
     * This method searches in the container for the Bundle
     * with passed key and returns it.
     *
     * @param string $key Holds the name of the requested Bundle
     * @return TechDivision_Controller_Action_Bundle
     * 		The requested Bundle object
     */
    public function find($key) {
		// initialize the return value
		$bundle = null;
		// if the Bundle exists then get ...
		if ($this->exists($key)) {
			$bundle = $this->get($key);
		}
		// ... and return it
		return $bundle;
    }
}