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
include_once "TechDivision/Controller/Action/Forward.php";
include_once "TechDivision/Controller/Action/Forwards.php";
include_once "TechDivision/Controller/Mock/ActionController.php";

/**
 * This is the test class for the ActionForwards container. There are several
 * testcases invokes the several() methods of the container and
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
class TechDivision_Controller_Action_ForwardsTest
	extends TechDivision_Controller_TestSetup {

    /**
     * This variable holds the ActionForwards container that should be tested
     * @var ActionForwards
     */
    private $actionForwards = null;

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
        $this->actionForwards = new TechDivision_Controller_Action_Forwards();
    }

    /**
     * This test checks that the find() method invoked with
     * the key of an existing object returns the correct object.
     *
     * @return void
     */
    function testFindWithResult() {
        $this->actionForwards->addActionForward(
        	new TechDivision_Controller_Action_Forward("key1", "Path 1!")
        );
        $actionForward = $this->actionForwards->find("key1");
        $this->assertEquals("key1", $actionForward->getName(), "Found wrong key in forward!");
        $this->assertEquals("Path 1!", $actionForward->getPath(), "Found wrong path in forward!");
    }

    /**
     * This test checks that the find() method invoked with a key
     * that not exists in the array returns false.
     *
     * @return void
     */
    function testFindWithoutResult() {
        $this->assertNull(
        	$this->actionForwards->find("wrongKey"), "Found an forward where no one is expected!"
        );
    }

    /**
     * This test checks that a new ActionForward object added with
     * the add() method is exisiting in the container.
     *
     * @return void
     */
    function testAddWithNewKey() {
        $counter = $this->actionForwards->size();
        $this->actionForwards->addActionForward(
        	new TechDivision_Controller_Action_Forward("key2", "Path 2!")
        );
        $actionForward = $this->actionForwards->find("key2");
        $this->assertEquals("key2", $actionForward->getName(), "Found wrong key!");
        $this->assertEquals("Path 2!", $actionForward->getPath(), "Found wrong path!");
        $this->assertTrue($this->actionForwards->size() == $counter + 1, "Found wrong number of forwards!");
    }

    /**
     * This test checks, that a new ActionForward object with the
     * same key as an ActionForward object that already exists in
     * the container, is added, the existing object is overwritten.
     *
     * @return void
     */
    function testAddWithExistingKey() {
        $this->actionForwards->addActionForward(
        	new TechDivision_Controller_Action_Forward("key3", "Path 3!")
        );
        $counter = $this->actionForwards->size();
        $this->actionForwards->addActionForward(
        	new TechDivision_Controller_Action_Forward("key3", "Path 4!")
        );
        $actionForward = $this->actionForwards->find("key3");
        $this->assertEquals("key3", $actionForward->getName(), "Found wrong key!");
        $this->assertEquals("Path 4!", $actionForward->getPath(), "Found wrong path!");
        $this->assertTrue($this->actionForwards->size() == $counter, "Found wrong number of forwards!");
    }
}