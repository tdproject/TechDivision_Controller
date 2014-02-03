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

require_once "TechDivision/Controller/Action/Message.php";
require_once "TechDivision/Collections/HashMap.php";

/**
 * This class is a container for ActionMessage objects and
 * provides methods for handling them.
 *
 * @category TechDivision
 * @package TechDivision_Controller
 * @author Tim Wagner <t.wagner@techdivision.com>
 * @copyright TechDivision GmbH
 * @link http://www.techdivision.com
 * @license GPL, version 2.0
 */
class TechDivision_Controller_Action_DataSourceBeans
	extends TechDivision_Collections_HashMap {

    /**
     * Holds a reference to the RequestProcessor.
     * @var TechDivision_Controller_Interfaces_RequestProcessor
     */
    protected $_requestProcessor = null;

    /**
     * This method adds the passed DataSourceBean object
     * to the container.
     *
     * @param TechDivision_Controller_Action_DataSourceBean $dataSourceBean
     * 		The DataSourceBean object that should be added to the container
     */
    public function addDataSourceBean(
    	TechDivision_Controller_Action_DataSourceBean $dataSourceBean) {
    	$this->add($dataSourceBean->getKey(), $dataSourceBean);
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
     * @return TechDivision_Controller_Action_DataSourceBeans
     * 		The instance itself
     */
    public function setController(
    	TechDivision_Controller_Interfaces_RequestProcessor $requestProcessor) {
        $this->_requestProcessor = $requestProcessor;
        return $this;
    }
}