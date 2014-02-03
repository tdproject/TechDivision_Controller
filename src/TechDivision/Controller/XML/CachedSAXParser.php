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

require_once 'TechDivision/Lang/String.php';
require_once 'TechDivision/Logger/Logger.php';
require_once 'TechDivision/Controller/XML/SAXParserStruts.php';

/**
 * This class implements caching functionality for the
 * parsed configuration file by creating a textfile
 * with a configuration class that is automatically
 * includeded instead of reparsing the XML configuration
 * in every request.
 *
 * To use the cached configuration initialize the
 * ActionController in the following way:
 *
 * <code>
 * $controller = new TechDivision_Controller_Action_Controller(
 * 		TechDivision_SystemLocale::getDefault(),
 * 		$configFile = new TechDivision_Lang_String('struts-config.xml'));
 * $controller->initialize(
 * 		CachedSAXParser::getConfiguration(
 * 			$configFile,
 * 			new TechDivision_Lang_String('/tmp/struts-config.php')));
 * </code>
 *
 * @category TechDivision
 * @package TechDivision_Controller
 * @author Tim Wagner <t.wagner@techdivision.com>
 * @copyright TechDivision GmbH
 * @link http://www.techdivision.com
 * @license GPL, version 2.0
 */
class TechDivision_Controller_XML_CachedSAXParser
	extends TechDivision_Controller_XML_SAXParserStruts {

    /**
     * The path and the filename to use for the generated configuration file.
     * @var TechDivision_Lang_String
     */
    protected $_generatedConfigFile = null;

    /**
     * Holds the actual Logger instance.
     * @var TechDivision_Logger_Interfaces_Logger
     */
    protected $_logger = null;

    /**
     * The config file generation date as a UNIX timestamp.
     * @var integer
     */
    protected $_configDate = null;

    /**
     * The constructor is private and initializes the cache with
     * the path to the config file and the generated configuration
     * class.
     *
     * @param TechDivision_Lang_String $configFile
     * 		The path to the configuration file
     * @param TechDivision_Lang_String $generatedConfigFile
     * 		The path to the generated configuration class
     * @return void
     */
    protected function __construct(
        TechDivision_Lang_String $configFile,
        TechDivision_Lang_String $generatedConfigFile) {
    	TechDivision_Controller_XML_SAXParserStruts::__construct($configFile);
    	$this->_generatedConfigFile = $generatedConfigFile;
    	$this->_configDate = filemtime($configFile->stringValue());
    }

    /**
     * This is the singleton function to create a new instance of the
     * configuration. If the configuration has already been cached and
     * the timestamp of the generated configuration is different of
     * the config files creation date, then the generated configuration
     * is regenerated. Else the generated configuration is included,
     * the class instanciated and returned.
     *
     * The default value for the generated configuration is
     * '/tmp/struts-config.php'. Please remember that the passed file
     * must be writeable by the webserver.
     *
     * @param string $configFile Holds the path to the configuration file
     * @param string $generatedConfigFile Holds the path to the generated configuration class
     * @return StrutsConfig Holds the uninitialized configuration
     */
    public static function getConfiguration(
        TechDivision_Lang_String $configFile,
        TechDivision_Lang_String $generatedConfigFile) {
        // load the key to check if the configuration has already been initialized
        $key = $configFile->stringValue();
    	// check if already one object is instanciated, if yes, return it
    	if (array_key_exists($key, self::$INSTANCES) === false) {
        	// check if the generated configuration exists, if not initialize this
        	// class for configuration
        	if (!file_exists($generatedConfigFile->stringValue())) {
        		self::$INSTANCES[$key] = new TechDivision_Controller_XML_CachedSAXParser(
        		    $configFile,
        		    $generatedConfigFile
        		);
        	} else {
	        	// if the generated configuration exists, include ID and check the timestamp
	        	require_once $generatedConfigFile->stringValue();
	        	$instance = new TechDivision_Controller_GeneratedStrutsConfiguration();
	        	if ($instance->getConfigDate() == filemtime($configFile->stringValue())) {
	        		// if the timestamps are equal use the generated one
	        		self::$INSTANCES[$key] = $instance;
	        	} else {
	        		// if the timestamps are different, generate it new
	        		self::$INSTANCES[$key] = new TechDivision_Controller_XML_CachedSAXParser(
	        		    $configFile,
	        		    $generatedConfigFile
	        		);
	        	}
        	}
    	}
    	// return the configuration instance
    	return self::$INSTANCES[$key];
    }

    /**
     * This method generates the source code for the generated configuration
     * class and saves it to the passed file.
     *
     * @param TechDivision_Lang_String $generatedConfigFile
     * 		The path to the generated configuration class
     * @return boolean
     * 		TRUE if the configuration has been successfully generated and saved
     */
	public function save(TechDivision_Lang_String $generatedConfigFile) {
		$configurationClass  = '<?php' . PHP_EOL;
		$configurationClass .= 'require_once "TechDivision/Controller/Interfaces/StrutsConfig.php";' . PHP_EOL;
		$configurationClass .= 'require_once "TechDivision/Controller/Action/Controller.php";' . PHP_EOL;
		$configurationClass .= 'require_once "TechDivision/Controller/Action/FormBeans.php";' . PHP_EOL;
		$configurationClass .= 'require_once "TechDivision/Controller/Action/FormBean.php";' . PHP_EOL;
		$configurationClass .= 'require_once "TechDivision/Controller/Action/Forwards.php";' . PHP_EOL;
		$configurationClass .= 'require_once "TechDivision/Controller/Action/Mappings.php";' . PHP_EOL;
		$configurationClass .= 'require_once "TechDivision/Controller/Action/Mapping.php";' . PHP_EOL;
		$configurationClass .= 'require_once "TechDivision/Controller/Action/DataSourceBeans.php";' . PHP_EOL;
		$configurationClass .= 'require_once "TechDivision/Controller/Action/DataSourceBean.php";' . PHP_EOL;
		$configurationClass .= 'require_once "TechDivision/Controller/Action/Bundles.php";' . PHP_EOL;
		$configurationClass .= 'require_once "TechDivision/Controller/Action/Bundle.php";' . PHP_EOL;
		$configurationClass .= 'require_once "TechDivision/Controller/Plugins/Plugins.php";' . PHP_EOL;
		$configurationClass .= 'require_once "TechDivision/Controller/Plugins/Plugin.php";' . PHP_EOL;
		$configurationClass .= 'class TechDivision_Controller_GeneratedStrutsConfiguration implements TechDivision_Controller_Interfaces_StrutsConfig {' . PHP_EOL;
		$configurationClass .= '	protected $actionFormBeans = null;' . PHP_EOL;
		$configurationClass .= '	protected $actionForwards = null;' . PHP_EOL;
		$configurationClass .= '	protected $actionMappings = null;' . PHP_EOL;
		$configurationClass .= '	protected $bundles = null;' . PHP_EOL;
		$configurationClass .= '	protected $plugins = null;' . PHP_EOL;
		$configurationClass .= '	protected $dataSourceBeans = null;' . PHP_EOL;
		$configurationClass .= '	private $configDate = ' . $this->_configDate . ';' . PHP_EOL;
		$configurationClass .= '	public function __construct() {}' . PHP_EOL;
		$configurationClass .= '	public function getConfigDate() {' . PHP_EOL;
		$configurationClass .= '		return $this->configDate;' . PHP_EOL;
		$configurationClass .= '	}' . PHP_EOL;
		$configurationClass .= '	public function getActionFormBeans() {' . PHP_EOL;
		$configurationClass .= '		return $this->actionFormBeans;' . PHP_EOL;
		$configurationClass .= '	}' . PHP_EOL;
		$configurationClass .= '	public function getActionForwards() {' . PHP_EOL;
		$configurationClass .= '		return $this->actionForwards;' . PHP_EOL;
		$configurationClass .= '	}' . PHP_EOL;
		$configurationClass .= '	public function getActionMappings() {' . PHP_EOL;
		$configurationClass .= '		return $this->actionMappings;' . PHP_EOL;
		$configurationClass .= '	}' . PHP_EOL;
		$configurationClass .= '	public function getBundles() {' . PHP_EOL;
		$configurationClass .= '		return $this->bundles;' . PHP_EOL;
		$configurationClass .= '	}' . PHP_EOL;
		$configurationClass .= '	public function getPlugins() {' . PHP_EOL;
		$configurationClass .= '		return $this->plugins;' . PHP_EOL;
		$configurationClass .= '	}' . PHP_EOL;
		$configurationClass .= '	public function getDataSourceBeans() {' . PHP_EOL;
		$configurationClass .= '		return $this->dataSourceBeans;' . PHP_EOL;
		$configurationClass .= '	}' . PHP_EOL;
		$configurationClass .= '	public function initialize(TechDivision_Controller_Interfaces_RequestProcessor $requestProcessor) {' . PHP_EOL;
		$configurationClass .= '		$this->actionFormBeans = new TechDivision_Controller_Action_FormBeans($requestProcessor);' . PHP_EOL;
		$configurationClass .= '		$this->actionForwards = new TechDivision_Controller_Action_Forwards($requestProcessor);' . PHP_EOL;
		$configurationClass .= '		$this->actionMappings = new TechDivision_Controller_Action_Mappings($requestProcessor);' . PHP_EOL;
		$configurationClass .= '		$this->bundles = new TechDivision_Controller_Action_Bundles($requestProcessor);' . PHP_EOL;
		$configurationClass .= '		$this->plugins = new TechDivision_Controller_Plugins_Plugins($requestProcessor);' . PHP_EOL;
		$configurationClass .= '		$this->dataSourceBeans = new TechDivision_Controller_Action_DataSourceBeans($requestProcessor);' . PHP_EOL;
		// generated the code for all ActionFormBeans
		foreach($this->actionFormBeans as $actionFormBean) {
			$configurationClass .= '    	$formBean = new TechDivision_Controller_Action_FormBean("' . $actionFormBean->getName() . '", "' . $actionFormBean->getType() . '", "' . $actionFormBean->getInclude() . '");' . PHP_EOL;
			$configurationClass .= '		$this->actionFormBeans->addActionFormBean($formBean);' . PHP_EOL;
		}
		// generate the code for all ActionMappings
		foreach($this->actionMappings as $actionMapping) {
			$configurationClass .= '    	$mapping = new TechDivision_Controller_Action_Mapping($this->actionMappings);' . PHP_EOL;
			$configurationClass .= '    	$mapping->setName("' . $actionMapping->getName() . '");' . PHP_EOL;
			$configurationClass .= '    	$mapping->setType("' . $actionMapping->getType() . '");' . PHP_EOL;
			$configurationClass .= '    	$mapping->setPath("' . $actionMapping->getPath() . '");' . PHP_EOL;
			$configurationClass .= '    	$mapping->setInput("' . $actionMapping->getInput() . '");' . PHP_EOL;
			$configurationClass .= '    	$mapping->setScope("' . $actionMapping->getScope() . '");' . PHP_EOL;
			$configurationClass .= '    	$mapping->setInclude("' . $actionMapping->getInclude() . '");' . PHP_EOL;
			$configurationClass .= '    	$mapping->setParameter("' . $actionMapping->getParameter() . '");' . PHP_EOL;
			// set the unknown flag
			if($actionMapping->getUnknown()) {
				$configurationClass .= '    	$mapping->setUnknown("true");' . PHP_EOL;
			} else {
				$configurationClass .= '    	$mapping->setUnknown("false");' . PHP_EOL;
			}
			// set the validate flag
			if($actionMapping->getValidate()) {
				$configurationClass .= '    	$mapping->setValidate("true");' . PHP_EOL;
			} else {
				$configurationClass .= '    	$mapping->setValidate("false");' . PHP_EOL;
			}
			// generate the code for the ActionForwards of each ActionMapping
			foreach($actionMapping->getActionForwards() as $actionForward) {
 				if($actionForward->getRedirect()) {
 					$configurationClass .= '    	$forward = new TechDivision_Controller_Action_Forward("' . $actionForward->getName() . '", "' . $actionForward->getPath() . '", "true");' . PHP_EOL;
 				} else {
 					$configurationClass .= '    	$forward = new TechDivision_Controller_Action_Forward("' . $actionForward->getName() . '", "' . $actionForward->getPath() . '", "false");' . PHP_EOL;
 				}
				$configurationClass .= '		$mapping->addActionForward($forward);' . PHP_EOL;
			}
			$configurationClass .= '		$this->actionMappings->addActionMapping($mapping);' . PHP_EOL;
		}
		// generate the code for the global ActionForwards
		foreach($this->actionForwards as $actionForward) {
			if($actionForward->getRedirect()) {
				$configurationClass .= '    	$forward = new TechDivision_Controller_Action_Forward("' . $actionForward->getName() . '", "' . $actionForward->getPath() . '", "true");' . PHP_EOL;
			} else {
				$configurationClass .= '    	$forward = new TechDivision_Controller_Action_Forward("' . $actionForward->getName() . '", "' . $actionForward->getPath() . '", "false");' . PHP_EOL;
			}
			$configurationClass .= '		$this->actionForwards->addActionForward($forward);' . PHP_EOL;
		}
		// generate the code for the ResourceBundles
		foreach($this->bundles as $bundle) {
 			$configurationClass .= '    	$bundle = new TechDivision_Controller_Action_Bundle("' . $bundle->getName() . '", "' . $bundle->getPath() . '", "' . $bundle->getKey() . '");' . PHP_EOL;
			$configurationClass .= '		$this->bundles->addBundle($bundle);' . PHP_EOL;
		}
		// generate the code for the Plugins
		foreach($this->plugins as $plugin) {
			$configurationClass .= '    	$plugin = new TechDivision_Controller_Plugins_Plugin("' . $plugin->getName() . '", "' . $plugin->getType() . '", "' . $plugin->getInclude() . '");' . PHP_EOL;
			foreach($plugin->getProperties() as $key => $value) {
				$configurationClass .= '    $plugin->addProperty("' . $key .'", "' . $value . '");' . PHP_EOL;
			}
			$configurationClass .= '		$this->plugins->addPlugin($plugin);' . PHP_EOL;
		}
		// generate the code for the DataSourceBeans
		foreach($this->dataSourceBeans as $dataSourceBean) {
			$configurationClass .= '    	$dataSourceBean = new TechDivision_Controller_Action_DataSourceBean("' . $dataSourceBean->getKey() . '", "' . $dataSourceBean->getType() . '", "' . $dataSourceBean->getInclude() . '");' . PHP_EOL;
			foreach($dataSourceBean->getProperties() as $key => $value) {
				$configurationClass .= '    $dataSourceBean->addProperty("' . $key .'", "' . $value . '");' . PHP_EOL;
			}
			$configurationClass .= '		$this->dataSourceBeans->addDataSourceBean($dataSourceBean);' . PHP_EOL;
		}
		// close the brackets and add the php delimiter
		$configurationClass .= '	}' . PHP_EOL;
		$configurationClass .= '}' . PHP_EOL;
		$configurationClass .= '?>';
		// save the file
		return file_put_contents(
		    $generatedConfigFile->stringValue(),
		    $configurationClass
		);
	}

	/**
	 * This method initializes the configuration with the information from
	 * the configuration file and generates and saves it to the generated
	 * configuration file.
	 *
	 * @return boolean
	 * 		TRUE if the configuration can successfully generated and saved
	 */
	public function initialize() {
		TechDivision_Controller_XML_SAXParserStruts::initialize();
    	return $this->save($this->_generatedConfigFile);
	}
}