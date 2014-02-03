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
 * This is the interface for all configuration types.
 *
 * @category TechDivision
 * @package TechDivision_Controller
 * @author Tim Wagner <t.wagner@techdivision.com>
 * @copyright TechDivision GmbH
 * @link http://www.techdivision.com
 * @license GPL, version 2.0
 */
interface TechDivision_Controller_Interfaces_StrutsConfig {

    /**
     * This method returns the ActionFormBeans for the ActionController
     * specified in the configuration file.
     *
     * @return TechDivision_Controller_Action_FormBeans
     * 		Holds the ActionFormBeans of the ActionController
     */
    public function getActionFormBeans();

    /**
     * This method returns the ActionMappings for the ActionController
     * specified in the configuration file.
     *
     * @return TechDivision_Controller_Action_Mappings
     * 		Holds the ActionMappings for the ActionController
     */
    public function getActionMappings();

    /**
     * This method returns the ActionForwards for the ActionController
     * specified in the configuration file.
     *
     * @return TechDivision_Controller_Action_FormBeans
     * 		Holds the ActionForwards for the ActionController
     */
    public function getActionForwards();

    /**
     * This method returns the resource bundle
     * specified in the configuration file.
     *
     * @return TechDivision_Controller_Action_Bundles
     * 		Holds a the resource bundle specified in the configuration file
     */
    public function getBundles();

    /**
     * This method returns the Plugins for the ActionController
     * specified in the configuration file.
     *
     * @return TechDivision_Controller_Plugins_Plugin
     * 		Returns the Plugins for the ActionController
     */
    public function getPlugins();

    /**
     * This method returns the data sources
     * specified in the configuration file.
     *
     * @return TechDivision_Controller_Action_DataSourceBeans
     * 		Holds a the data sources specified in the configuration file
     */
    public function getDataSourceBeans();

    /**
     * Initialize the configuration variables with
     * the values from the configuration file.
	 *
     * @return TechDivision_Controller_XML_SAXParserStruts
     * 		The instance itself if the configuration has been initialized
     */
    public function initialize();
}