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
include_once "TechDivision/Controller/Action/Messages.php";

/**
 * This is the test class for the ActionMessages container. There are several
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
class TechDivision_Controller_Action_MessagesTest
	extends TechDivision_Controller_TestSetup {

    /**
     * This variable holds the ActionMessages container that should be tested
     * @var ActionMessages
     */
    private $actionMessages = null;

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
        $this->actionMessages = new TechDivision_Controller_Action_Messages();
    }

    /**
     * This test checks that the find() method invoked with
     * the key of an existing object returns the correct object.
     *
     * @return void
     */
    function testFindWithResult() {
        $this->actionMessages->addActionMessage(
        	new TechDivision_Controller_Action_Message("key1", "Message 1!")
        );
        $this->assertEquals("Message 1!", $this->actionMessages->find("key1"), "Found wrong message!");
    }

    /**
     * This test checks that the find() method invoked with a key
     * that not exists in the array returns false.
     *
     * @return void
     */
    function testFindWithoutResult() {
        $this->assertEquals("", $this->actionMessages->find("wrongKey"), "Found an message where no one is expected!");
    }

    /**
     * This test checks that a new ActionMessage object added with
     * the add() method is exisiting in the container.
     *
     * @return void
     */
    function testAddWithNewKey() {
        $counter = $this->actionMessages->size();
        $this->actionMessages->addActionMessage(
        	new TechDivision_Controller_Action_Message("key2", "Message 2!")
        );
        $this->assertEquals("Message 2!", $this->actionMessages->find("key2"), "Found wrong message!");
        $this->assertTrue($this->actionMessages->size() == $counter + 1, "Found wrong number of messages!");
    }

    /**
     * This test checks, that a new ActionMessage object with the
     * same key as an ActionMessage object that already exists in
     * the container, is added, the existing object is overwritten.
     *
     * @return void
     */
    function testAddWithExistingKey() {
        $this->actionMessages->addActionMessage(
        	new TechDivision_Controller_Action_Message("key3", "Message 3!")
        );
        $counter = $this->actionMessages->size();
        $this->actionMessages->addActionMessage(
        	new TechDivision_Controller_Action_Message("key3", "Message 4!")
        );
        $this->assertEquals("Message 4!", $this->actionMessages->find("key3"), "Found wrong message!");
        $this->assertTrue($this->actionMessages->size() == $counter, "Found wrong number of messages!");
    }

    /**
     * This test checks that the removed ActionMessage
     * object is not longer in the container.
     *
     * @return void
     */
    function testRemove() {
        $this->actionMessages->addActionMessage(
        	new TechDivision_Controller_Action_Message("key4", "Message 4!")
        );
        $counter = $this->actionMessages->size();
        $this->actionMessages->remove("key4");
        $this->assertEquals("", $this->actionMessages->find("key4"), "Found deleted message!");
        $this->assertTrue($this->actionMessages->size() == $counter - 1, "Found wrong number of messages in the container!");
    }

    /**
     * This test checks that the container is empty after
     * invoking the clear() method.
     *
     * @return void
     */
    function testClear() {
        $this->actionMessages->addActionMessage(
        	new TechDivision_Controller_Action_Message("key5", "Message 5!")
        );
        $this->assertTrue($this->actionMessages->size() >= 0, "Found no messages after adding one!");
        $this->actionMessages->clear();
        $this->assertEquals(0, $this->actionMessages->size(), "Found messages after invoking clear() method!");
    }
}