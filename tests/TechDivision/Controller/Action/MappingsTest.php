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
include_once "TechDivision/Controller/Action/Mapping.php";
include_once "TechDivision/Controller/Action/Mappings.php";
include_once "TechDivision/Controller/Mock/ActionController.php";

/**
 * This is the test class for the ActionMappings container. There are
 * several testcases invokes the several() methods of the container and
 * checks the return values or the state of the objects that
 * should be returned.
 *
 * @category TechDivision
 * @package TechDivision_Controller
 * @author Tim Wagner <t.wagner@techdivision.com>
 * @copyright TechDivision GmbH
 * @link http://www.techdivision.com
 * @license GPL, version 2.0
 */
class TechDivision_Controller_Action_MappingsTest
	extends TechDivision_Controller_TestSetup {

    /**
     * This variable holds the ActionMappings container that should be tested
     * @var ActionMappings
     */
    private $actionMappings = null;

    /**
     * This method instanciates all the objects
     * needed by the tests.
     *
     * @return void
     */
    function setUp() {
        // call the setUp() method of the superclass
        TechDivision_Controller_TestSetup::setUp();
        // instanciate the container
        $this->actionMappings = new TechDivision_Controller_Action_Mappings();
    }

    /**
     * This test checks that the find() method invoked with
     * the key of an existing object returns the correct object.
     *
     * @return void
     */
    function testFindWithResult() {
        // initialize a new mapping
        $actionMapping = new TechDivision_Controller_Action_Mapping($this->actionMappings);
        $actionMapping->setPath("/testSave");
        $actionMapping->setType("MockSaveAction");
        $actionMapping->setName("mockForm");
        $actionMapping->setValidate("true");
        $actionMapping->setInput("test_edit.tpl.html");
        $actionMapping->setScope("request");
        $actionMapping->setParameter("");
        $actionMapping->setForward("");
        $actionMapping->setUnknown("");
        // add the mapping to the container
        $this->actionMappings->addActionMapping($actionMapping);
        // look for the mapping
        $tmpActionMapping = $this->actionMappings->find("/testSave");
        // check the values of the found mapping
        $this->assertEquals("/testSave", $tmpActionMapping->getPath(), "Found wrong mapping!");
    }
}