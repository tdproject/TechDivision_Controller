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

require_once "TechDivision/Controller/Interfaces/RequestProcessor.php";
require_once "TechDivision/Controller/Plugins/Plugin.php";
require_once "TechDivision/Collections/HashMap.php";

/**
 * This class is a container for Plugin objects and
 * provides methods for handling them.
 *
 * @category TechDivision
 * @package TechDivision_Controller
 * @author Tim Wagner <t.wagner@techdivision.com>
 * @copyright TechDivision GmbH
 * @link http://www.techdivision.com
 * @license GPL, version 2.0
 */
class TechDivision_Controller_Plugins_Plugins
	extends TechDivision_Collections_HashMap {

    /**
     * Holds a reference to the RequestProcessor
     * @var TechDivision_Controller_Interfaces_RequestProcessor
     */
    protected $_requestProcessor = null;

    /**
     * This method searches in the container
     * for the Plugin with the key passed
     * as parameter.
     *
     * @param string $name Holds the key of the requested Plugin
     * @return string Holds the requested Plugin
     */
    public function find($name) {
		// initialize the plugin
		$plugin = null;
		// if the Plugin with the passed key exists
        if ($this->exists($name)) {
            // set the plugin ...
            $plugin = $this->get($name);
        }
        // ... and return it
        return $plugin;
    }

    /**
     * This method returns a reference to the internal ActionController object.
     *
     * @return TechDivision_Controller_Interfaces_RequestProcessor
     * 		Holds a reference to the internal ActionController object
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
     * @return TechDivision_Controller_Plugins_Plugins
     * 		The instance itself
     */
    public function setController(
    	TechDivision_Controller_Interfaces_RequestProcessor $requestProcessor) {
        $this->_requestProcessor = $requestProcessor;
        return $this;
    }

    /**
     * This method adds the passed Plugin object
     * to the container.
     *
     * @param TechDivision_Controller_Plugins_Plugin $plugin
     * 		The Plugin plugin object that should be added to the container
     */
    public function addPlugin(TechDivision_Controller_Plugins_Plugin $plugin) {
    	$this->add($plugin->getName(), $plugin);
    }
}