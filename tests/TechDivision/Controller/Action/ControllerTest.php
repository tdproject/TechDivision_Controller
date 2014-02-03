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

include_once 'TechDivision/Controller/TestSetup.php';
include_once 'TechDivision/Controller/Action/Controller.php';
require_once 'TechDivision/Util/SystemLocale.php';
require_once 'TechDivision/Lang/String.php';

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
class TechDivision_Controller_Action_ControllerTest
	extends TechDivision_Controller_TestSetup {

    /**
     * This variable holds the ActionController instance that should be tested
     * @var ActionController
     */
    private $actionController = null;

    /**
     * This method instanciates all the objects
     * needed by the tests.
     *
     * @return void
     */
    function setUp() {
        // call the setUp() method of the superclass
        TechDivision_Controller_TestSetup::setUp();
		// initialize the ActionController and process the request
		$this->actionController = new TechDivision_Controller_Action_Controller(
		    TechDivision_Util_SystemLocale::create('de_DE'),
        	new TechDivision_Lang_String(
        	    $this->logConfigFile
        	)
		);
		// load the configuration
		$configuration =
			TechDivision_Controller_XML_SAXParserStruts::getConfiguration(
				new TechDivision_Lang_String($this->configFile)
			);
		// initialize configuration and controller
		$this->actionController->initialize($configuration->initialize());
    }

    /**
     * This method checks the correct return value
     * of the process() method of the controller.
     *
     * @return void
     */
    function testProcessOverviewSuccess() {
        $parameter = "/testOverview";
        $this->request->setAttribute("path", $parameter);
        $this->assertTrue($this->actionController->process($this->request) == "test_overview.tpl.html");
    }

    /**
     * This method checks the correct return value
     * of the process() method of the controller.
     *
     * @return void
     */
    function testProcessEditSuccess() {
        $parameter = "/testEdit";
        $this->request->setAttribute("path", $parameter);
        $this->assertTrue($this->actionController->process($this->request) == "test_edit.tpl.html");
    }

    /**
     * This method checks the correct return value
     * of the process() method of the controller.
     * Here the controller has to invoke the process()
     * method of a second Action object after the first.
     *
     * @return void
     */
    function testProcessActionChaining() {
        $parameter = "/testSave";
        $this->request->setAttribute("path", $parameter);
        $this->assertTrue($this->actionController->process($this->request) == "test_view.tpl.html");
    }

    /**
     * This method checks the correct return value
     * of the process() method of the controller.
     * If a not existent path is specified in the
     * HTTPRequest, the process() method has to
     * throw an Exception.
     *
     * @return void
     */
    function testProcessWrongPath() {
        $this->setExpectedException(
        	'TechDivision_Controller_Exceptions_InvalidActionMappingException'
        );
        $parameter = "/testWrongPath";
        $this->request->setAttribute("path", $parameter);
        $this->assertTrue($this->actionController->process($this->request) == "/testWrongPath");
    }

    /**
     * This method checks the correct return value
     * of the process() method of the controller.
     * The config file defines that the ActionForm
     * has session scope. So we test that the specified
     * ActionForm in the session has changed.
     *
     * @return void
     */
    function testProcessEditSessionSuccess() {
        $parameter = "/testSession";
        $this->request->setAttribute("path", $parameter);
        // get the HTTPSession from the HTTPRequest
        $session = $this->request->getSession();
        // invoke the process() method of the ActionBanana
        $this->assertTrue($this->actionController->process($this->request) == "test_edit.tpl.html");
        // get the ActionForm from the HTTPSession
        $actionForm = $session->getAttribute("mockForm");
        // check the value
        $this->assertTrue($actionForm->getTestvalue() == "test");
    }
}