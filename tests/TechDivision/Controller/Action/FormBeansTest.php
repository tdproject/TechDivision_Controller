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
include_once "TechDivision/Controller/Action/FormBeans.php";
include_once "TechDivision/Controller/Mock/ActionController.php";

/**
 * This is the test class for the ActionFormBeans container. There are several
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
class TechDivision_Controller_Action_FormBeansTest
	extends TechDivision_Controller_TestSetup {

    /**
     * This variable holds the ActionFormBeans container that should be tested
     * @var ActionFormBeans
     */
    private $actionFormBeans = null;

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
        $this->actionFormBeans = new TechDivision_Controller_Action_FormBeans();
    }

    /**
     * This test checks that the find() method invoked with
     * the key of an existing object returns the correct object.
     *
     * @return void
     */
    function testFindWithResult() {
        $this->actionFormBeans->addActionFormBean(new TechDivision_Controller_Action_FormBean("key1", "Type 1!"));
        $actionFormBean = $this->actionFormBeans->find("key1");
        $this->assertEquals("key1", $actionFormBean->getName(), "Found wrong key in formbean!");
        $this->assertEquals("Type 1!", $actionFormBean->getType(), "Found wrong type in formbean!");
    }

    /**
     * This test checks that the find() method invoked with a key
     * that not exists in the array returns false.
     *
     * @return void
     */
    function testFindWithoutResult() {
        $this->assertNull($this->actionFormBeans->find("wrongKey"), "Found a formbean where no one is expected!");
    }

    /**
     * This test checks that a new ActionFormBean object added with
     * the add() method is exisiting in the container.
     *
     * @return void
     */
    function testAddWithNewKey() {
        $counter = $this->actionFormBeans->size();
        $this->actionFormBeans->addActionFormBean(new TechDivision_Controller_Action_FormBean("key2", "Type 2!"));
        $actionFormBean = $this->actionFormBeans->find("key2");
        $this->assertEquals("key2", $actionFormBean->getName(), "Found wrong key!");
        $this->assertEquals("Type 2!", $actionFormBean->getType(), "Found wrong type!");
        $this->assertTrue($this->actionFormBeans->size() == $counter + 1, "Found wrong number of formbeans!");
    }

    /**
     * This test checks, that a new ActionFormBean object with the
     * same key as an ActionFormBean object that already exists in
     * the container, is added, the existing object is overwritten.
     *
     * @return void
     */
    function testAddWithExistingKey() {
        $this->actionFormBeans->addActionFormBean(new TechDivision_Controller_Action_FormBean("key3", "Type 3!"));
        $counter = $this->actionFormBeans->size();
        $this->actionFormBeans->addActionFormBean(new TechDivision_Controller_Action_FormBean("key3", "Type 4!"));
        $actionFormBean = $this->actionFormBeans->find("key3");
        $this->assertEquals("key3", $actionFormBean->getName(), "Found wrong key!");
        $this->assertEquals("Type 4!", $actionFormBean->getType(), "Found wrong type!");
        $this->assertTrue($this->actionFormBeans->size() == $counter, "Found wrong number of formbeans!");
    }
}