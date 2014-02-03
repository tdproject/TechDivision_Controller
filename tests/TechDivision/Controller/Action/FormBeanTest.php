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

include_once "TechDivision/Controller/TestSetup.php";
include_once "TechDivision/Controller/Action/FormBean.php";

/**
 * This class is the holds test cases for the ActionFormBean
 * class of the framework.
 *
 * @category TechDivision
 * @package TechDivision_Controller
 * @author Tim Wagner <t.wagner@techdivision.com>
 * @copyright TechDivision GmbH
 * @link http://www.techdivision.com
 * @license GPL, version 2.0
 */
class TechDivision_Controller_Action_FormBeanTest
	extends TechDivision_Controller_TestSetup {

    /**
     * This method instanciates all the objects
     * needed by the tests.
     *
     * @return void
     */
    function setUp() {
        // call the setUp() method of the superclass
        TechDivision_Controller_TestSetup::setUp();
    }

    /**
     * This method checks if the constructor initializes
     * the right values in the member variables.
     *
     * @return void
     */
    function testConstructor() {
        $actionFormBean = new TechDivision_Controller_Action_FormBean("name", "type");
        $this->assertTrue($actionFormBean->getName() == "name", "Found wrong name!");
        $this->assertTrue($actionFormBean->getType() == "type", "Found wrong type!");
    }

    /**
     * This method checks if the setter methods initializes
     * the right values in the member variables.
     *
     * @return void
     */
    function testSetter() {
        $actionFormBean = new TechDivision_Controller_Action_FormBean();
        $actionFormBean->setName("name");
        $actionFormBean->setType("type");
        $this->assertTrue($actionFormBean->getName() == "name", "Found wrong name!");
        $this->assertTrue($actionFormBean->getType() == "type", "Found wrong type!");
    }
}