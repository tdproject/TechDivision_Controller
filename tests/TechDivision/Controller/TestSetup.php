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

include_once "TechDivision/Controller/Mock/Request.php";

/**
 * This is the test suite setup class that initializes the
 * necessary objects needed by the test cases in the subfolders.
 *
 * @category TechDivision
 * @package TechDivision_Controller
 * @author Tim Wagner <t.wagner@techdivision.com>
 * @copyright TechDivision GmbH
 * @link http://www.techdivision.com
 * @license GPL, version 2.0
 */
class TechDivision_Controller_TestSetup extends PHPUnit_Framework_TestCase {

    /**
     * This variable holds the HTTPRequest object.
     * @var HTTPRequest
     */
    protected $request = null;

    /**
     * This variable holds path and the name to the config file.
     * @var string
     */
    protected $configFile = "TechDivision/Controller/WEB-INF/struts-config-test.xml";

    /**
     * This variable holds path and the name to the logger config file.
     * @var string
     */
    protected $logConfigFile = "TechDivision/Controller/WEB-INF/log4php.properties";

    /**
     * This method is called befor the tests starts
     * and initializes the MockRequest object with
     * the necessary arrays.
     *
     * @return void
     */
    public function setUp() {
        $this->request = TechDivision_Controller_Mock_Request::singleton();
    }

    /**
     * This method is called after the tests and
     * destroys the objects.
     *
     * @return void
     */
    public function tearDown() {
        $this->request = null;
        $this->configFile = null;
        $this->logConfigFile = null;
    }
}