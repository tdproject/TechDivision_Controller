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

require_once 'TechDivision/Util/SystemLocale.php';
require_once 'TechDivision/Lang/String.php';

/**
 * This is the test class for the parser of the config file.
 * This class includes testing its superclass, because it
 * uses the only function of it.
 *
 * @category TechDivision
 * @package TechDivision_Controller
 * @author Tim Wagner <t.wagner@techdivision.com>
 * @copyright TechDivision GmbH
 * @link http://www.techdivision.com
 * @license GPL, version 2.0
 */
class TechDivision_Controller_XML_SAXParserStrutsTest
	extends TechDivision_Controller_TestSetup {

    /**
     * This variable holds parser object.
     * @var SAXParserStruts
     */
    private $parser = null;

    /**
     * This variable holds the ActionFormBeans container with the ActionFormBean elements defined in the config file.
     * @var ActionFormBeans
     */
    private $actionFormBeans = null;

    /**
     * This variable holds the ActionMappings container with the ActionMapping elements defined in the config file.
     * @var HTTPRequest
     */
    private $actionMappings = null;

    /**
     * This variable holds an ActionMapping element found in the ActionMappings container.
     * @var ActionMapping
     */
    private $actionMapping = null;

    /**
     * This variable holds the ActionForwards container with the ActionForward elements defined in the config file.
     * @var HTTPRequest
     */
    private $actionForwards = null;

    /**
     * This variable holds an ActionForward element found in the ActionForwards container.
     * @var ActionForward
     */
    private $actionForward = null;

    /**
     * The ActionController instance for testing purposes.
     * @var TechDivision_Controller_Action_Controller
     */
    protected $actionController = null;

    /**
     * This variable holds path and the name to the config file to merge.
     * @var string
     */
    protected $configFileToMerge = "TechDivision/Controller/WEB-INF/struts-config-to-merge.xml";

    /**
     * This method instanciates all the objects
     * needed by the tests.
     *
     * @return void
     */
    function setUp()
    {
        // call the setUp() method of the superclass
        TechDivision_Controller_TestSetup::setUp();
        // instanciate the parser
        $this->parser = TechDivision_Controller_XML_SAXParserStruts::getConfiguration(
        	new TechDivision_Lang_String(
        	    $this->configFile
        	)
        );
        // initialize the parser
        $this->parser->initialize(
            new TechDivision_Controller_Mock_ActionController()
        );
    }

    /**
     * This method invokes the parse() method of the parser and checks
     * if all the objects needed by the controller are instanciated.
     *
     * First the test checks if all the containers, that are passed by
     * reference holds the right number of elements.
     *
     * After that the method calls the find method of the ActionMappings
     * container and gets an ActionMapping element. It checks if all
     * attributes of the ActionMapping are filled correctly.
     *
     * Finally it invokes the find() method of the ActionMapping and
     * checks if the attributes of the ActionForward returned by the
     * method holds the correct values.
     *
     * @return void
     */
    function testParse()
    {
    	// invoke the parse() method of the parser
    	$parser = $this->parser->parse();
    	// check the result
        $this->assertTrue(
        	$parser instanceof TechDivision_Controller_XML_SAXParserStruts,
        	"Error while parsing the config file!"
        );
        // check the number of the elements of the containers
        $this->assertEquals(6, $this->parser->getActionMappings()->size(), "Finding the wrong number of ActionMapping objects in the ActionMappings container!", 0);
        $this->assertEquals(1, $this->parser->getActionForwards()->size(), "Finding the wrong number of ActionForward objects in the ActionForwards container!", 0);
        $this->assertEquals(1, $this->parser->getActionFormBeans()->size(), "Finding the wrong number of ActionFormBean objects in the ActionFormBeans container!", 0);
        // get an ActionMapping
        $actionMapping = $this->parser->getActionMappings()->find("/testSave");
        // check the attributes of the ActionMapping
        $this->assertEquals("TechDivision_Controller_Mock_SaveAction", $actionMapping->getType(), "Finding the wrong type for ActionMapping!");
        $this->assertEquals("mockForm", $actionMapping->getName(), "Finding the wrong name for ActionMapping!");
        $this->assertEquals("/testSave", $actionMapping->getPath(), "Finding the wrong path for ActionMapping!");
        $this->assertTrue($actionMapping->getValidate(), "Finding the wrong validate flag for ActionMapping!");
        $this->assertEquals("test_edit.tpl.html", $actionMapping->getInput(), "Finding the wrong input value for ActionMapping!");
        $this->assertEquals("testParameter", $actionMapping->getParameter(), "Finding the wrong parameter for ActionMapping!");
        // check the number of ActionForward elements hold by the ActionMapping
        $this->assertEquals(2, $actionMapping->count(), "Finding the wrong number of ActionForward objects in the ActionMapping container!", 0);
        // get an ActionForward
        $actionForward = $actionMapping->findForward("Success");
        // check the attributes of the ActionForward
        $this->assertEquals("Success", $actionForward->getName(), "Finding the wrong name for ActionForward!");
        $this->assertEquals("/testView", $actionForward->getPath(), "Finding the wrong path for ActionForward!");
    }

    /**
     * This method invokes the merge() method of the controller to
     * test if the configuration values of the passed Configuration
     * are correctly merged with the existing one's.
     *
     * @return void
     */
    function testMerge()
    {
        // instanciate the configuration to merge
        $toMerge = TechDivision_Controller_XML_SAXParserStruts::getConfiguration(
        	new TechDivision_Lang_String(
        	    $this->configFileToMerge
        	)
        );
        // initialize the configuration
        $toMerge->initialize(
            new TechDivision_Controller_Mock_ActionController()
        );
        // merge the configurations
        $this->parser->merge($toMerge);
        // get an ActionMapping
        $actionMapping = $this->parser->getActionMappings()->find("/testMerge");
        // check the attributes of the ActionMapping
        $this->assertEquals("TechDivision_Controller_Merge_DeleteAction", $actionMapping->getType(), "Finding the wrong type for ActionMapping!");
        $this->assertEquals("mergeForm", $actionMapping->getName(), "Finding the wrong name for ActionMapping!");
        $this->assertEquals("/testMerge", $actionMapping->getPath(), "Finding the wrong path for ActionMapping!");
        $this->assertFalse($actionMapping->getValidate(), "Finding the wrong validate flag for ActionMapping!");
        $this->assertEquals("test_merge.tpl.html", $actionMapping->getInput(), "Finding the wrong input value for ActionMapping!");
        $this->assertEquals("testParameter", $actionMapping->getParameter(), "Finding the wrong parameter for ActionMapping!");
        // check the number of ActionForward elements hold by the ActionMapping
        $this->assertEquals(2, $actionMapping->count(), "Finding the wrong number of ActionForward objects in the ActionMapping container!", 0);
        // get an ActionForward
        $actionForward = $actionMapping->findForward("Success");
        // check the attributes of the ActionForward
        $this->assertEquals("Success", $actionForward->getName(), "Finding the wrong name for ActionForward!");
        $this->assertEquals("/mergeOverview", $actionForward->getPath(), "Finding the wrong path for ActionForward!");
        // check if the old mappings are also available
        $actionMapping = $this->parser->getActionMappings()->find("/testSave");
        // check the attributes of the ActionMapping
        $this->assertEquals("TechDivision_Controller_Mock_SaveAction", $actionMapping->getType(), "Finding the wrong type for ActionMapping!");
    }
}