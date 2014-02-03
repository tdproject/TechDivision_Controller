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

// set the include path necessary for running the tests
set_include_path(
	get_include_path() . PATH_SEPARATOR
	. getcwd() . PATH_SEPARATOR
	. '${pear.lib.dir}'
);

require_once 'TechDivision/XHProfPHPUnit/TestSuite.php';
require_once "TechDivision/Controller/Action/Controller.php";
require_once "TechDivision/Controller/Action/ControllerTest.php";
require_once "TechDivision/Controller/Action/ErrorsTest.php";
require_once "TechDivision/Controller/Action/ErrorTest.php";
require_once "TechDivision/Controller/Action/MessagesTest.php";
require_once "TechDivision/Controller/Action/MessageTest.php";
require_once "TechDivision/Controller/Action/MappingsTest.php";
require_once "TechDivision/Controller/Action/ForwardTest.php";
require_once "TechDivision/Controller/Action/ForwardsTest.php";
require_once "TechDivision/Controller/Action/FormBeansTest.php";
require_once "TechDivision/Controller/Action/FormBeanTest.php";
require_once "TechDivision/Controller/XML/SAXParserStrutsTest.php";

/**
 * The TestSuite that initializes all PHPUnit tests.
 *
 * @category TechDivision
 * @package TechDivision_Controller
 * @author Tim Wagner <t.wagner@techdivision.com>
 * @copyright TechDivision GmbH
 * @link http://www.techdivision.com
 * @license GPL, version 2.0
 */
class TechDivision_Controller_AllTests
{

    /**
     * Initializes the TestSuite.
     *
     * @return TechDivision_XHProfPHPUnit_TestSuite The initialized TestSuite
     */
    public static function suite()
    {
        // initialize the TestSuite
        $suite = new TechDivision_XHProfPHPUnit_TestSuite(
        	'${ant.project.name}',
        	'',
            '${release.version}'
        );
        // add a test
        $suite->addTestSuite("TechDivision_Controller_Action_ControllerTest");
        $suite->addTestSuite("TechDivision_Controller_Action_ErrorsTest");
        $suite->addTestSuite("TechDivision_Controller_Action_ErrorTest");
        $suite->addTestSuite("TechDivision_Controller_Action_MessagesTest");
        $suite->addTestSuite("TechDivision_Controller_Action_MessageTest");
        $suite->addTestSuite("TechDivision_Controller_Action_MappingsTest");
        $suite->addTestSuite("TechDivision_Controller_Action_ForwardsTest");
        $suite->addTestSuite("TechDivision_Controller_Action_ForwardTest");
        $suite->addTestSuite("TechDivision_Controller_Action_FormBeansTest");
        $suite->addTestSuite("TechDivision_Controller_Action_FormBeanTest");
        $suite->addTestSuite("TechDivision_Controller_XML_SAXParserStrutsTest");
        // return the TestSuite itself
        return $suite;
    }
}