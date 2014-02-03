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
include_once "TechDivision/Controller/Action/Errors.php";

/**
 * This is the test class for the ActionErrors container. There are several
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
class TechDivision_Controller_Action_ErrorsTest
	extends TechDivision_Controller_TestSetup {

    /**
     * This variable holds the ActionErrors container that should be tested
     * @var ActionErrors
     */
    private $actionErrors = null;

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
        $this->actionErrors = new TechDivision_Controller_Action_Errors();
    }

    /**
     * This test checks that the find() method invoked with
     * the key of an existing object returns the correct object.
     *
     * @return void
     */
    function testFindWithResult() {
        $this->actionErrors->addActionError(new TechDivision_Controller_Action_Error("key1", "Message 1!"));
        $this->assertEquals("Message 1!", $this->actionErrors->find("key1"), "Found wrong message!");
    }

    /**
     * This test checks that the find() method invoked with a key
     * that not exists in the array returns false.
     *
     * @return void
     */
    function testFindWithoutResult() {
        $this->assertEquals(null, $this->actionErrors->find("wrongKey"), "Found an message where no one is expected!");
    }

    /**
     * This test checks that a new ActionError object added with
     * the add() method is exisiting in the container.
     *
     * @return void
     */
    function testAddWithNewKey() {
        $counter = $this->actionErrors->size();
        $this->actionErrors->addActionError( new TechDivision_Controller_Action_Error("key2", "Message 2!"));
        $this->assertEquals("Message 2!", $this->actionErrors->find("key2"), "Found wrong message!");
        $this->assertTrue($this->actionErrors->size() == $counter + 1, "Found wrong number of messages!");
    }

    /**
     * This test checks, that a new ActionError object with the
     * same key as an ActionError object that already exists in
     * the container, is added, the existing object is overwritten.
     *
     * @return void
     */
    function testAddWithExistingKey() {
        $this->actionErrors->addActionError(new TechDivision_Controller_Action_Error("key3", "Message 3!"));
        $counter = $this->actionErrors->size();
        $this->actionErrors->addActionError(new TechDivision_Controller_Action_Error("key3", "Message 4!"));
        $this->assertEquals("Message 4!", $this->actionErrors->find("key3" ), "Found wrong message!");
        $this->assertTrue($this->actionErrors->size() == $counter, "Found wrong number of messages!");
    }

    /**
     * This test checks that the removed ActionError
     * object is not longer in the container.
     *
     * @return void
     */
    function testRemove() {
        $this->actionErrors->addActionError(new TechDivision_Controller_Action_Error("key4", "Message 4!"));
        $counter = $this->actionErrors->size();
        $this->actionErrors->remove("key4");
        $this->assertEquals(null, $this->actionErrors->find("key4"), "Found deleted message!");
        $this->assertTrue($this->actionErrors->size() == $counter - 1, "Found wrong number of messages in the container!");
    }

    /**
     * This test checks that the container is empty after
     * invoking the clear() method.
     *
     * @return void
     */
    function testClear() {
        $this->actionErrors->addActionError(new TechDivision_Controller_Action_Error("key5", "Message 5!"));
        $this->assertTrue($this->actionErrors->size() >= 0, "Found no messages after adding one!");
        $this->actionErrors->clear();
        $this->assertEquals(0, $this->actionErrors->size(), "Found messages after invoking clear() method!");
    }
}