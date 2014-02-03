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
require_once "TechDivision/Controller/Action/Mapping.php";
require_once "TechDivision/Collections/HashMap.php";

/**
 * This class is a container for ActionMapping objects and
 * provides methods for handling them.
 *
 * @category TechDivision
 * @package TechDivision_Controller
 * @author Tim Wagner <t.wagner@techdivision.com>
 * @copyright TechDivision GmbH
 * @link http://www.techdivision.com
 * @license GPL, version 2.0
 */
class TechDivision_Controller_Action_Mappings
	extends TechDivision_Collections_HashMap {

    /**
     * ActionMapping that should be returned, if the requested one is not found in the internal array.
     * @var ActionMapping
     */
    protected $_unknown = null;

    /**
     * Reference to the RequestProcessor
     * @var TechDivision_Controller_Interfaces_RequestProcessor
     */
    protected $_requestProcessor = null;

    /**
     * This method searches in the container for the ActionMapping with
     * the key passed as parameter.
     *
     * @param string $path Holds the key of the requested ActionMapping
     * @return TechDivision_Controller_Action_Mapping Holds the requested ActionMapping
     */
    public function find($path) {
		// check if the requested ActionMapping is registered
		if ($this->exists($path)) {
		    // if yes, return it
            return $this->get($path);
        }
    }

    /**
     * This method returns the ActionMapping marked as unknown. First
     * it looks in the internal member variable. If no ActionMapping
     * is set is searches all through all ActionMappings. If no ActionMapping
     * is found, the method returns null.
     *
     * @return ActionMapping Holds the ActionMapping marked as unknown
     */
    public function getUnknown() {
        // first check if the internal member variable is set
        if ($this->_unknown == null) {
            // if not search all ActionMappings for the unknown element
            foreach ($this->_items as $item) {
                if (($isUnknown = $item->getUnknown()) == true) {
                    $this->_unknown = $item;
                }
            }
        }
        // ... and return it
		return $this->_unknown;
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

    /**
     * This method sets the reference to the RequestProcessor.
     *
     * @param TechDivision_Controller_Interfaces_RequestProcessor $requestProcessor
     * 		Holds a reference to the RequestProcessor.
     * @return TechDivision_Controller_Action_Mappings
     * 		The instance itself
     */
    public function setController(
    	TechDivision_Controller_Interfaces_RequestProcessor $requestProcessor) {
        $this->_requestProcessor = $requestProcessor;
        return $this;
    }

    /**
     * This method adds an object of the class ActionMapping
     * to the internal array.
     *
     * @param TechDivision_Controller_Action_Mapping $actionMapping
     * 		Holds information about an ActionMapping object
     */
    public function addActionMapping(TechDivision_Controller_Action_Mapping $actionMapping ) {
        $this->add($actionMapping->getPath(), $actionMapping->setMappings($this));
    }
}