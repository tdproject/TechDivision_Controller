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

require_once 'TechDivision/Lang/Object.php';
require_once 'TechDivision/Lang/String.php';
require_once 'TechDivision/Controller/Interfaces/Parser.php';
require_once 'TechDivision/Controller/Interfaces/StrutsConfig.php';
require_once 'TechDivision/Controller/Interfaces/RequestProcessor.php';
require_once 'TechDivision/Controller/Action/FormBeans.php';
require_once 'TechDivision/Controller/Action/Forwards.php';
require_once 'TechDivision/Controller/Action/Mappings.php';
require_once 'TechDivision/Controller/Action/DataSourceBeans.php';
require_once 'TechDivision/Controller/Action/DataSourceBean.php';
require_once 'TechDivision/Controller/Action/Bundle.php';
require_once 'TechDivision/Controller/Action/Bundles.php';
require_once 'TechDivision/Controller/Plugins/Plugins.php';

/**
 * This class implements the event handler methods
 * of the base class and is specialized for parsing
 * the config file needed by the framework.
 *
 * @category TechDivision
 * @package TechDivision_Controller
 * @author Tim Wagner <t.wagner@techdivision.com>
 * @copyright TechDivision GmbH
 * @link http://www.techdivision.com
 * @license GPL, version 2.0
 */
class TechDivision_Controller_XML_SAXParserStruts
	extends TechDivision_Lang_Object
	implements TechDivision_Controller_Interfaces_Parser,
	    TechDivision_Controller_Interfaces_StrutsConfig {

	/**
	 * Holds the actual instance of the configuration
	 * @static array
	 */
	protected static $INSTANCES = array();

    /**
     * Holds the ActionFormBeans found in the config file.
     * @var TechDivision_Controller_Action_FormBeans
     */
    protected $_actionFormBeans = null;

    /**
     * Holds the ActionMappings found in the config file.
     * @var TechDivision_Controller_Action_Mappings
     */
    protected $_actionMappings = null;

    /**
     * Holds the ActionForwards found in the config file.
     * @var TechDivision_Controller_Action_Forwards
     */
    protected $_actionForwards = null;

    /**
     * This member variable is only for temporary usage and holds the information of the actual ActionFormBean while parsing the config file.
     * @var TechDivision_Controller_Action_FormBean
     */
    protected $_actionFormBean = null;

    /**
     * This member variable is only for temporary usage and holds the information of the actual ActionMapping while parsing the config file.
     * @var TechDivision_Controller_Interfaces_Mapping
     */
    protected $_actionMapping = null;

    /**
     * This member variable is only for temporary usage and holds the information of the actual ActionForward while parsing the config file.
     * @var TechDivision_Controller_Action_Forward
     */
    protected $_actionForward = null;

    /**
     * Holds the resource bundles found in the config file.
     * @var TechDivision_Controller_Action_Bundles
     */
    protected $_bundles = null;

    /**
     * This member variable is only for temporary usage and holds the information of the actual Bundle while parsing the config file.
     * @var TechDivision_Controller_Action_Bundle
     */
    protected $_bundle = null;

    /**
     * Holds the Plugins found in the config file.
     * @var TechDivision_Controller_Plugins_Plugins
     */
    protected $_plugins = null;

    /**
     * This member variable is only for temporary usage and holds the information of the actual Plugin while parsing the config file.
     * @var TechDivision_Controller_Plugins_Plugin
     */
    protected $_plugin = null;

    /**
     * Holds the DataSourceBeans found in the config file.
     * @var TechDivision_Controller_Action_DataSourceBeans
     */
    protected $_dataSourceBeans = null;

    /**
     * This member variable is only for temporary usage and holds the information of the actual DataSourceBean while parsing the config file.
     * @var TechDivision_Controller_Action_DataSourceBean
     */
    protected $_dataSourceBean = null;

    /**
     * This member variable is only for temporary usage and holds the information of an DynamicActionForm property.
     * @var TechDivision_Controller_Action_FormProperty
     */
    protected $_formProperty = null;

	/**
	 * Holds the path to the configuration file.
	 * @var string
	 */
	protected $_configFile = null;

	/**
	 * The private constructor is necessary, because this class
	 * should only be used as a singleton.
	 *
	 * @see SAXParserStruts::getConfiguration(TechDivision_Lang_String $configFile)
	 */
	protected function __construct(TechDivision_Lang_String $configFile) {
		$this->_configFile = $configFile;
	}

    /**
     * This method is invoked if the parses finds a new
     * tag and overwrites the method of the base class.
     *
     * @param TechDivision_Controller_XML_SAXParserStruts $parser
     * 		Holds an instance of the parser object
     * @param string $tag Holds the name of the tag
     * @param array $attributes Holds an array with the parameters associated with the tag
     */
    public function tagOpenHandler($parser, $tag, $attributes) {
		switch($tag) {
           	case "MESSAGE-RESOURCES":
				$this->_bundle = new TechDivision_Controller_Action_Bundle();
              	$this->_bundle->setName($attributes["NAME"]);
				$this->_bundle->setPath($attributes["PATH"]);
				if(array_key_exists("KEY", $attributes)) {
					$this->_bundle->setKey($attributes["KEY"]);
				}
               	break;
           	case "GLOBAL-FORWARDS":
               	$this->section = $tag;
               	break;
           	case "ACTION-MAPPINGS":
               	$this->section = $tag;
               	break;
           	case "FORM-BEANS":
               	$this->section = $tag;
               	break;
           	case "PLUGINS":
               	$this->section = $tag;
               	break;
           	case "DATA-SOURCES":
               	$this->section = $tag;
               	break;
           	case "PLUGIN":
               	$this->_plugin = new TechDivision_Controller_Plugins_Plugin($attributes["TYPE"], $attributes["INCLUDE"]);
               	break;
           	case "DATA-SOURCE":
               	$this->_dataSourceBean = new TechDivision_Controller_Action_DataSourceBean($attributes["KEY"], $attributes["TYPE"], $attributes["INCLUDE"]);
               	break;
           	case "SET-PROPERTY":
                if(strcmp($this->section, "PLUGINS") == 0) {
               		$this->_plugin->addProperty($attributes["PROPERTY"], $attributes["VALUE"]);
				}
                if(strcmp($this->section, "DATA-SOURCES") == 0) {
               		$this->_dataSourceBean->addProperty($attributes["PROPERTY"], $attributes["VALUE"]);
				}
                break;
           	case "ACTION":
               	$this->_actionMapping = new TechDivision_Controller_Action_Mapping();
               	$this->_actionMapping->setName($attributes["NAME"]);
               	$this->_actionMapping->setType($attributes["TYPE"]);
               	$this->_actionMapping->setPath($attributes["PATH"]);
               	$this->_actionMapping->setInput($attributes["INPUT"]);
               	$this->_actionMapping->setScope($attributes["SCOPE"]);
			   	$this->_actionMapping->setInclude($attributes["INCLUDE"]);
               	$this->_actionMapping->setParameter($attributes["PARAMETER"]);
               	$this->_actionMapping->setUnknown($attributes["UNKNOWN"]);
               	$this->_actionMapping->setValidate($attributes["VALIDATE"]);
               	break;
           	case "FORWARD":
               	$this->_actionForward = new TechDivision_Controller_Action_Forward();
               	$this->_actionForward->setName($attributes["NAME"]);
               	$this->_actionForward->setPath($attributes["PATH"]);
				$this->_actionForward->setRedirect($attributes["REDIRECT"]);
               	break;
           	case "FORM-BEAN":
               	$this->_actionFormBean = new TechDivision_Controller_Action_FormBean();
               	$this->_actionFormBean->setName($attributes["NAME"]);
               	$this->_actionFormBean->setType($attributes["TYPE"]);
			   	$this->_actionFormBean->setInclude($attributes["INCLUDE"]);
               	break;
           	case "FORM-PROPERTY":
               	$this->_formProperty = new TechDivision_Controller_Action_FormProperty();
               	$this->_formProperty->setName($attributes["NAME"]);
               	$this->_formProperty->setType($attributes["TYPE"]);
               	$this->_formProperty->setInitial($attributes["INITIAL"]);
               	break;
			default:
                break;
        }
    }

    /**
     * This method is invoked if the parser finds a
     * closing tag and overwrites the method of the base class.
     *
     * @param SAXParser $parser Holds an instance of the parser object
     * @param string $tag Holds the name of the tag
     */
    public function tagCloseHandler($parser, $tag) {
        switch($tag) {
            case "MESSAGE-RESOURCES":
                $this->_bundles->addBundle($this->_bundle);
                break;
            case "GLOBAL-FORWARDS":
                $this->section = "";
                break;
            case "ACTION-MAPPINGS":
                $this->section = "";
                break;
            case "FORM-BEANS":
                $this->section = "";
                break;
            case "PLUGINS":
                $this->section = "";
                break;
            case "DATA-SOURCES":
                $this->section = "";
                break;
            case "ACTION":
                $this->_actionMappings->addActionMapping($this->_actionMapping);
                break;
            case "PLUGIN":
                $this->_plugins->addPlugin($this->_plugin);
                break;
            case "DATA-SOURCE":
                $this->_dataSourceBeans->addDataSourceBean($this->_dataSourceBean);
                break;
            case "FORWARD":
                if(strcmp($this->section, "ACTION-MAPPINGS") == 0) {
					$this->_actionMapping->addActionForward($this->_actionForward);
				}
                if(strcmp($this->section, "GLOBAL-FORWARDS") == 0) {
					$this->_actionForwards->addActionForward($this->_actionForward);
				}
                break;
            case "FORM-BEAN":
                $this->_actionFormBeans->addActionFormBean($this->_actionFormBean);
                break;
            case "FORM-PROPERTY":
                $this->_actionFormBean->addFormProperty($this->_formProperty);
                break;
            default:
                break;
        }
    }

    /**
     * This method is invoked if the parser finds
	 * data between to tags.
     *
     * @param TechDivision_Controller_XML_SAXParserStruts $parser
     * 		Holds an instance of the parser object
     * @param string $data Holds the data of the tag
     */
    public function dataHandler($parser, $data) {
        // do nothing actually
    }

    /**
     * @see TechDivision_Controller_Interfaces_StrutsConfig::getActionFormBeans()
     */
    public function getActionFormBeans() {
        return $this->_actionFormBeans;
    }

    /**
     * @see TechDivision_Controller_Interfaces_StrutsConfig::getPlugins()
     */
    public function getPlugins() {
        return $this->_plugins;
    }

    /**
     * @see TechDivision_Controller_Interfaces_StrutsConfig::getActionMappings()
     */
    public function getActionMappings() {
        return $this->_actionMappings;
    }

    /**
     * @see TechDivision_Controller_Interfaces_StrutsConfig::getActionForwards()
     */
    public function getActionForwards() {
        return $this->_actionForwards;
    }

    /**
     * @see TechDivision_Controller_Interfaces_StrutsConfig::getResources()
     */
    public function getBundles() {
        return $this->_bundles;
    }

    /**
     * @see TechDivision_Controller_Interfaces_StrutsConfig::getDataSourceBeans()
     */
    public function getDataSourceBeans() {
        return $this->_dataSourceBeans;
    }

    /**
     * This method returns the configuration as
     * a singleton.
     *
     * @param TechDivision_Lang_String $configFile
     * 		The path and the filename fo the configuration file to use
     * @return TechDivision_Controller_Interfaces_StrutsConfig
     * 		Returns the intialized configuration
     */
    public static function getConfiguration(
        TechDivision_Lang_String $configFile) {
        $key = $configFile->stringValue();
    	if (array_key_exists($key, self::$INSTANCES) === false) {
    		self::$INSTANCES[$key] =
    		    new TechDivision_Controller_XML_SAXParserStruts($configFile);
    	}
    	return self::$INSTANCES[$key];
    }

    /**
     * @see TechDivision_Controller_Interfaces_StrutsConfig::initialize()
     */
    public function initialize() {
    	// initialize the configuration containers
    	$this->_actionFormBeans =
    	    new TechDivision_Controller_Action_FormBeans();
    	$this->_actionMappings =
    	    new TechDivision_Controller_Action_Mappings();
    	$this->_actionForwards =
    	    new TechDivision_Controller_Action_Forwards();
    	$this->_bundles =
    	    new TechDivision_Controller_Action_Bundles();
    	$this->_plugins =
    	    new TechDivision_Controller_Plugins_Plugins();
    	$this->_dataSourceBeans =
    	    new TechDivision_Controller_Action_DataSourceBeans();
    	// parse the configuration file and return
    	return $this->parse();
    }

    /**
     * This method starts parsing the XML file passed as
     * a parameter.
     *
     * @return TechDivision_Controller_XML_SAXParserStruts
     * 		Returns the instance itself if the config file has been parsed
     * @throws TechDivision_Controller_Exceptions_ConfigFileOpenException
     * 		Is thrown if the config file could not be opened
     * @throws TechDivision_Controller_Exceptions_InvalidConfigFileException
     * 		Is thrown if the config file has invalid entries
     * @see TechDivision_Controller_Interfaces_Parser::parse($configFile)
     */
    public function parse() {
      	// open and read the file
      	$content = file_get_contents(
      	    $this->_configFile->stringValue(),
      	    true
      	);
      	// check if the file can be opened successfully
        if ($content === false) {
			// throw an exception if no config file was found
			throw new TechDivision_Controller_Exceptions_ConfigFileOpenException(
				'Can not open config file with name ' . $this->_configFile->stringValue()
			);
        }
       	// instanciate a new object of the Expat parser
       	$parser = xml_parser_create();
       	// link the parser to the actual object
       	xml_set_object($parser, $this);
       	// register the event handler methods
       	xml_set_element_handler($parser, 'tagOpenHandler', 'tagCloseHandler');
		// register the data handler method
		xml_set_character_data_handler($parser, 'dataHandler');
       	// parse the XML file
       	if(!xml_parse($parser, $content)) {
			// throw an exception if the config has invalid entries
			throw new TechDivision_Controller_Exceptions_InvalidConfigFileException(
				xml_error_string(xml_get_error_code($parser)) .
				' in config file ' . $this->_configFile->stringValue() . ' on line '
				. xml_get_current_line_number($parser)
			);
       	}
       	// free the parser instance
       	xml_parser_free($parser);
       	// return the instance itself if everything is o. k.
       	return $this;
    }

    /**
     * Merges the data of the passed Configuration instance to the
     * to the actual one.
     *
     * @param TechDivision_Controller_Interfaces_StrutsConfig $toMerge
     * 		The Configuration to add merge the data with
     * @return TechDivision_Controller_XML_SAXParserStruts
     * 		Returns the instance itself if the merge has been successfull
     */
    public function merge(
        TechDivision_Controller_Interfaces_StrutsConfig $toMerge) {
        // merge the DataSourceBean's
        foreach ($toMerge->getDataSourceBeans() as $dataSourceBeans) {
            $this->_dataSourceBeans->addDataSourceBean($dataSourceBean);
        }
        // merge the ActionMapping's
        foreach ($toMerge->getActionMappings() as $actionMapping) {
            $this->_actionMappings->addActionMapping($actionMapping);
        }
        // merge the ActionFormBean's
        foreach ($toMerge->getActionFormBeans() as $actionFormBean) {
            $this->_actionFormBeans->addActionFormBean($actionFormBean);
        }
        // merge the ActionForward's
        foreach ($toMerge->getActionForwards() as $actionForward) {
       	    $this->_actionForwards->addActionForward($actionForward);
        }
        // merge the MessageResource Bundle's
        foreach ($toMerge->getBundles() as $bundle) {
		    $this->_bundles->addBundle($bundle);
        }
        // merge the Plugin's
        foreach ($toMerge->getPlugins() as $plugin) {
		    $this->_plugins->addPlugin($plugin);
        }
        // the instance itself
        return $this;
    }
}