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
include_once "TechDivision/Controller/Action/Error.php";

/**
 * This is the test class for the controller. There are several
 * testcases invoking the process() method of the controller and
 * checking the return values or the state of the objects that
 * should be initialized by the controller.
 *
 * @category TechDivision
 * @package TechDivision_Controller
 * @author Tim Wagner <t.wagner@techdivision.com>
 * @copyright TechDivision GmbH
 * @link http://www.techdivision.com
 * @license GPL, version 2.0
 */
class TechDivision_Controller_Action_ErrorTest
	extends TechDivision_Controller_TestSetup {

    /**
     * This variable holds the ActionError instance that should be tested
     * @var ActionError
     */
    private $actionError = null;

    /**
     * This method instanciates all the objects
     * needed by the tests.
     *
     * @return void
     */
    function setUp() {
        // call the setUp() method of the superclass
        TechDivision_Controller_TestSetup::setUp();
        // instanciate the ActionError object
        $this->actionError = new TechDivision_Controller_Action_Error("name", "message");
    }

    /**
     * This method checks if the constructor initializes
     * the right values in the member variables.
     *
     * @return void
     */
    function testConstructor() {
        $this->assertTrue($this->actionError->getName() == "name", "Found wrong name!");
        $this->assertTrue($this->actionError->getMessage() == "message", "Found wrong message!");
    }

    /**
     * This method checks if the setter methods initializes
     * the right values in the member variables.
     *
     * @return void
     */
    function testSetter() {
        $this->actionError->setName("name");
        $this->actionError->setMessage("message");
        $this->assertTrue($this->actionError->getName() == "name", "Found wrong name!");
        $this->assertTrue($this->actionError->getMessage() == "message", "Found wrong message!");
    }
}