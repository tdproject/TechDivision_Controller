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

require_once "TechDivision/Lang/Object.php";
require_once "TechDivision/Logger/Logger.php";
require_once "TechDivision/Util/SystemLocale.php";
require_once "TechDivision/Collections/HashMap.php";
require_once "TechDivision/Collections/ArrayList.php";
require_once "TechDivision/Collections/CollectionUtils.php";
require_once "TechDivision/HttpUtils/Interfaces/Request.php";
require_once "TechDivision/Resources/PropertyResourceBundle.php";
require_once "TechDivision/Controller/XML/SAXParserStruts.php";
require_once "TechDivision/Controller/XML/CachedSAXParser.php";
require_once "TechDivision/Controller/Exceptions/InvalidActionForwardException.php";
require_once "TechDivision/Controller/Exceptions/InvalidPluginTypeException.php";
require_once "TechDivision/Controller/Exceptions/InvalidActionFormNameException.php";
require_once "TechDivision/Controller/Exceptions/InvalidActionFormTypeException.php";
require_once "TechDivision/Controller/Exceptions/InvalidConfigFileException.php";
require_once "TechDivision/Controller/Exceptions/InvalidActionMappingException.php";
require_once "TechDivision/Controller/Exceptions/ConfigFileOpenException.php";
require_once "TechDivision/Controller/Exceptions/EmptyActionClassTypeException.php";
require_once "TechDivision/Controller/Exceptions/ProcessRequestProcessorException.php";
require_once "TechDivision/Controller/Action/Mapping.php";
require_once "TechDivision/Controller/Action/Mappings.php";
require_once "TechDivision/Controller/Action/Forward.php";
require_once "TechDivision/Controller/Action/Forwards.php";
require_once "TechDivision/Controller/Action/FormBean.php";
require_once "TechDivision/Controller/Action/FormBeans.php";
require_once "TechDivision/Controller/Action/Errors.php";
require_once "TechDivision/Controller/Action/Context.php";
require_once "TechDivision/Controller/Interfaces/RequestProcessor.php";
require_once "TechDivision/Controller/Interfaces/Action.php";
require_once "TechDivision/Controller/Interfaces/Form.php";
require_once "TechDivision/Controller/Interfaces/StrutsConfig.php";
require_once "TechDivision/Controller/Interfaces/StrutsPlugin.php";
require_once "TechDivision/Controller/Plugins/Plugins.php";

/**
 * This class is the controller component of the framework and is
 * responsible for the workflow.
 *
 * The ActionController works in the following order. First it checks
 * if there is a valid ActionMapping under self::ACTION_PATH
 * specified in Request. If not, then a dummy ActionMapping with
 * a dummy ActionForward is instanciated an the controller returns
 * the self::ACTION_PATH specified in the Request.
 *
 * If yes it checks if an ActionForm is specified in the ActionMapping.
 * If in ActionForm is specified, then the controller creates a new
 * instance of it an registers it in the specified scope. This can
 * be session or request scope.
 *
 * After the ActionForm is instanciated the controller populates the
 * ActionForm with the values it found in the Request.
 *
 * Then the controller invokes the validate() method of the ActionForm.
 * If the method returns an ActionError in the ActionErrors container,
 * then the controller returns the value of the input attribute that is
 * specified in the ActionMapping.
 *
 * If the ActionErrors container returned by the validation() method of
 * the ActionForm holds no ActionError elements then the controller
 * instanciates a new object of the Action defined in the ActionMapping
 * and invokes the perform() method of it.
 *
 * The perform() method returns an ActionForward object. The controller
 * checks if the value specified as the path attribute of the returned
 * ActionForward is the Path of another Action or not. If yes then the
 * the controller recursively calls it's process() method with the Path
 * specified in the ActionForward.
 *
 * If the value specified as the path attribute of the returned
 * ActionForward is not the Path of another Action then the controller
 * returns the value of the path attribute.
 *
 * @category TechDivision
 * @package TechDivision_Controller
 * @author Tim Wagner <t.wagner@techdivision.com>
 * @copyright TechDivision GmbH
 * @link http://www.techdivision.com
 * @license GPL, version 2.0
 */
class TechDivision_Controller_Action_Controller
	extends TechDivision_Lang_Object
	implements TechDivision_Controller_Interfaces_RequestProcessor {

	/**
	 * This variable holds the request key for the action path to use.
	 * @var string
	 */
	const ACTION_PATH = "path";

    /**
     * Variable that holds the name of the logger config file.
     * @var TechDivision_Lang_String
     */
    protected $_logConfigFile = "";

    /**
     * Variable that holds the default locale.
     * @var TechDivision_Util_SystemLocale
     */
    protected $_locale = "";

    /**
     * Variable that holds the internal resources bundle.
     * @var TechDivision_Collections_HashMap
     */
    protected $_resources = null;

    /**
     * Variable that holds the internal ActionMappings.
     * @var TechDivision_Controller_Action_Mappings
     */
    protected $_actionMappings = null;

    /**
     * Variable that holds the internal ActionFormBeans.
     * @var TechDivision_Controller_Action_FormBeans
     */
    protected $_actionFormBeans = null;

    /**
     * Variable that holds the internal ActionForwards.
     * @var TechDivision_Controller_Action_Forwards
     */
    protected $_actionForwards = null;

    /**
     * Variable that holds the internal resource bundles.
     * @var TechDivision_Controller_Action_Bundles
     */
    protected $_bundles = null;

    /**
     * Variable that holds the internal Plugins.
     * @var TechDivision_Controller_Plugins_Plugins
     */
    protected $_plugins = null;

	/**
	 * Holds the logger instance.
	 * @var TechDivision_Logger_Interfaces_Logger
	 */
	protected $_logger = null;

	/**
	 * Holds the context of the controller.
	 * @var TechDivision_Controller_Action_Context
	 */
	protected $_context = null;

	/**
	 * The configuration to use.
	 * @var TechDivision_Controller_Interfaces_StrutsConfig
	 */
	protected $_configuration = null;

    /**
     * The constructor instanciates the internal member variables
     * and sets the config file and parses its values.
     *
     * @param TechDivision_Util_SystemLocale $locale
	 * 		The local for the application
     * @param TechDivision_Lang_String $logConfigFile
     * 		Holds the configuration file for the logger
     */
    public function __construct(
        TechDivision_Util_SystemLocale $locale,
        TechDivision_Lang_String $logConfigFile) {
        // initialize the logger instance
        $this->_logger = TechDivision_Logger_Logger::forObject(
            $this,
            $logConfigFile->stringValue()
        );
		// set the locale
		$this->_locale = $locale;
		// set the log configuration file
    	$this->_logConfigFile = $logConfigFile;
		// initialize the StrutsContext
		$this->_context = new TechDivision_Controller_Action_Context($this);
		// initialize the HashMap with the resources
		$this->_resources = new TechDivision_Collections_HashMap();
    }

	/**
	 * The destructor destroys the internal members
	 * and frees the memory.
	 *
	 * @return void
	 */
	public function __destruct() {
		// invoke the plugins destroy() method
		foreach ($this->_plugins as $plugin) {
			$this->_context->getAttribute($plugin->getType())->destroy();
		}
	}

	/**
	 * This method returns the logger instance.
	 *
	 * @return Logger Holds the logger instance
	 */
	protected function _getLogger() {
		return $this->_logger;
	}

	/**
	 * This method initializes the ActionController.
	 *
	 * @param TechDivision_Controller_Interfaces_StrutsConfig $configuration
	 * 		Holds the configuration for the ActionController
	 * @return void
	 */
	public function initialize(
	    TechDivision_Controller_Interfaces_StrutsConfig $configuration = null) {
	    // set the internal configuration
	    $this->setConfiguration($configuration);
		// log the entry of the method
		$this->_getLogger()->debug("Now in initialize method", __LINE__);
        // set the members in the configuration file
        $this->_actionMappings = $configuration->getActionMappings();
        $this->_actionFormBeans = $configuration->getActionFormBeans();
       	$this->_actionForwards = $configuration->getActionForwards();
		$this->_bundles = $configuration->getBundles();
		$this->_plugins = $configuration->getPlugins();
		// log the entry of the method
		$this->_getLogger()->debug("Now setting controller instance", __LINE__);
		$this->_actionMappings->setController($this);
		$this->_actionFormBeans->setController($this);
		$this->_actionForwards->setController($this);
		$this->_bundles->setController($this);
		$this->_plugins->setController($this);
	}

    /**
     * This method is the main one of the controller and has to
     * be invoked on each request. This method is responsible for
     * the flow of the framework.
     *
     * @param TechDivision_HttpUtils_Interfaces_Request $request
     * 		Holds a reference to the actual Request instance
     * @return string
     * 		Returns the value of the last path parameter of the ActionForward
     * 		specified in the actual ActionMapping
     */
    public function process(
        TechDivision_HttpUtils_Interfaces_Request $request) {
		// log the entry of the method
		$this->_getLogger()->debug('Now in process method', __LINE__);
		// set the Request instance
		$this->_setRequest($request);
    	// find the ActionMapping for this request
        $this->_processMapping();
		// include the necessary files
		$this->_processIncludes();
        // check if a direct path to another site or file is given
        if ($this->_processForward() === false) {
			// process the plugins
			$this->_processPlugins();
            // if not, then instanciate the ActionForm
            if ($this->_processActionForm()) {
                // populate the ActionForm with the values in request
                $this->_processPopulate();
                // validate the ActionForm
                if ($this->_processValidate() === false) {
					// set the a
					$this->_setActionForward(
					    new TechDivision_Controller_Action_Forward('', $actionMapping->getInput())
					);
		            // process the returned ActionForward
		            return $this->_processActionForward();
				}
          	}
            // instanciate the Action and invoke the perform() method
            $this->_processActionCreate();
           	$this->_processActionPerform();
        }
        // process the returned ActionForward
        return $this->_processActionForward();
    }

    /**
     * This method creates a new instance of the Action,
     * based on the information given by the ActionMapping
     * that is passed as a parameter, and returns it.
	 *
     * @throws TechDivision_Controller_Exceptions_EmptyActionClassTypeException
     * 		Is thrown if an invalid class type is not configured
     */
    protected function _processActionCreate() {
		// log the entry of the method
		$this->_getLogger()->debug(
			"Now in _processActionCreate method",
		    __LINE__
		);
		// load the ActionMapping
		$actionMapping = $this->_getActionMapping();
        // name of the ActionClass that should be instanciated
        $actionClass = $actionMapping->getType();
        // if a name was found, then try to instanciate the object
        if (empty($actionClass)) {
            // else throw an exception
            throw new TechDivision_Controller_Exceptions_EmptyActionClassTypeException(
            	'Class type of action not specified in configuration file for mapping with path ' . $actionMapping->getPath()
            );
        }
        // instanciate and return a new object of Action class
        $reflectionClass = new ReflectionClass($actionClass);
        $this->_setAction(
            $reflectionClass->newInstance($this->getContext())
        );
    }

    /**
     * This method creates a new instance of the ActionForm, based
     * on the information given by the ActionMapping that is passed
     * as a parameter, and sets it in the Controller.
     *
     * @return boolean TRUE if a ActionForm was registered for the actual
     * 		ActionMapping else FALSE
     * @throws TechDivision_Controller_Exceptions_InvalidActionFormNameException
     * 		Is thrown if an invalid ActionForm name was specified in the configuration file
     * @throws TechDivision_Controller_Exceptions_InvalidActionFormTypeException
     * 		Is thrown if an invalid ActionForm type was specified in the configuration file
     */
    protected function _processActionForm() {
		// log the entry of the method
		$this->_getLogger()->debug("Now in _processActionForm method", __LINE__);
		// load the actual ActionMapping
		$actionMapping = $this->_getActionMapping();
        // alias of the ActionForm that should be instanciated
        $formBeanName = $actionMapping->getName();
        // if no name was found, then return false
        if (empty($formBeanName)) {
            // return FALSE if no ActionForm was registered
            return false;
        }
        // check if the ActionForm, which is requested, exists in the internal array
        if (($formBean = $this->_actionFormBeans->find($formBeanName)) === null) {
            // throw an Exception if no valid class can be found
			throw new TechDivision_Controller_Exceptions_InvalidActionFormNameException(
				'An ActionForm with name ' . $formBeanName . ' can not be found ' .
				'in the internal ActionFormBeans container'
			);
        }
        // load the request
        $request = $this->_getRequest();
        // check if the form has session scope
        $isSessionScope = strcmp(
            $actionMapping->getScope(),
            TechDivision_Controller_Action_Mapping::SESSION_SCOPE
        );
        // if yes then load it from the session
        if ($isSessionScope === 0) {
            $session = $request->getSession();
            if(($actionForm = $session->getAttribute($actionMapping->getName())) !== null) {
                // set the ActionForm in the Controller
                $this->_setActionForm($actionForm);
                // return TRUE if an ActionForm was found
                return true;
            }
        }
        // class name of the ActionForm that should be instanciated
        $formBeanClassName = $formBean->getType();
        // if no valid class name is found, then die
        if (empty($formBeanClassName)) {
			throw new TechDivision_Controller_Exceptions_InvalidActionFormTypeException(
				'Empty type for the ActionForm was specified in the config file'
			);
        }
        // instanciate a new object of the ActionForm
		$reflectionClass = new ReflectionClass($formBeanClassName);
		$actionForm = $reflectionClass->newInstance($this->getContext());
        // reset the members of the ActionForm
        $actionForm->reset();
        // check if the ActionForm has to be registered in request or in session
        if ($isSessionScope === 0) {
        	$session->setAttribute($actionMapping->getName(), $actionForm);
            $actionForm = $session->getAttribute($actionMapping->getName());
        } else {
        	$request->setAttribute($actionMapping->getName(), $actionForm);
            $actionForm = $request->getAttribute($actionMapping->getName());
        }
        // set the ActionForm in the Controller
        $this->_setActionForm($actionForm);
        // return TRUE if an ActionForm was found and registered in the defined scope
        return true;
    }

    /**
     * This method invokes the perform() method of the Action instance
     * passed as a parameter and returns the ActionForward that is
     * returned by the Action.
     *
     * @return void
     */
    protected function _processActionPerform() {
		// log the entry of the method
		$this->_getLogger()->debug("Now in _processActionPerform method", __LINE__);
		// load the Action
		$action = $this->_getAction();
		// pre dispatch action
		$action->preDispatch();
		// check if the Action is already dispatched
		if (!$this->getContext()->isDispatched()) {
            // invoke the perform() method of the Action
            $action->perform();
            // post dispatch action
            $action->postDispatch();
		}
    }

    /**
     * This method redirects the application flow, based on the information found
     * in the passed  ActionMapping, to the next form or Action.
	 *
     * @return string Returns the forward path specified in the configuration file
     * @throws TechDivision_Controller_Exceptions_InvalidActionForwardException
     * 		Is thrown if no ActionForward was returned from the Actions perform() method
     */
    protected function _processActionForward() {
		// log the entry of the method
		$this->_getLogger()->debug("Now in _processActionForward method", __LINE__);
		// load the ActionForward
        if (($actionForward = $this->_getActionForward()) === null) {
            // if no ActionForward object was returned, throw an exception
			throw new TechDivision_Controller_Exceptions_InvalidActionForwardException(
				'No ActionForward object was returned from the Actions perform() method'
			);
        }
        // if the ActionForward points to another Action then process the new Action
        $path = $actionForward->getPath();
		// if the forward should be redirected
		if ($actionForward->isRedirect()) {
			$this->_getLogger()->debug("Now redirecting to " . $path, __LINE__);
			// write the header out
			header("Location: " . $path);
			// return nothing
			return;
		}
		// if the path maps another ActionMapping, then process this
		if ($mapping = $this->_actionMappings->find($path)) {
		    // load the Request instance
		    $request = $this->_getRequest();
			// log that a ActionMapping with the given path is found
			$this->_getLogger()->debug("Found ActionMapping with path $path", __LINE__);
            // set the path found in the ActionMapping in the request
            $request->setAttribute(self::ACTION_PATH, $path);
            // and process it and return the result
            $path = $this->process($request);
        }
		// log the found path
		$this->_getLogger()->debug("Returning $path", __LINE__);
		// return the path
		return $path;
    }

    /**
     * This method looks, based on the path passed as a parameter,
     * in the internal ActionMappings for the requested ActionMapping and
     * returns it. If no ActionMapping is found, then a dummy
     * ActionMapping is instanciated. This happens e. g. a wrong Path
     * is set in the Request.
     *
     * @param string $path Holds the path of the requested ActionMapping
     * @throws TechDivision_Controller_Exceptions_InvalidActionMappingException
     * 		Is thrown if no path is specified and no ActionMapping has the unknown flat set
     */
    protected function _processMapping() {
		// log the entry of the method
		$this->_getLogger()->debug("Now in _processMapping method", __LINE__);
		// load the request
        $request = $this->_getRequest();
        // try to load the path
		if (($path = $request->getAttribute(self::ACTION_PATH)) === null) {
		    if (($path = $request->getParameter(self::ACTION_PATH, FILTER_SANITIZE_STRING)) === null) {
				// try to get the unknown ActionMapping
				if (($actionMapping = $this->_actionMappings->getUnknown()) != null) {
					// log that the ActionMapping marked as unknown was used
					$this->_getLogger()->debug(
						'Found unknown ActionMapping with path ' . $actionMapping->getPath(),
					    __LINE__
					);
            		// register the ActionMapping the the Context and return
            		return $this->_setActionMapping($actionMapping);
				} else {
					// if no path was specified and no ActionMapping with the unknown flag was found throw an exception
					throw new TechDivision_Controller_Exceptions_InvalidActionMappingException(
						'No path specified and no ActionMapping with unknown flag available'
					);
				}
		    }
		}
        // find the requested ActionMapping
		if (($actionMapping = $this->_actionMappings->find($path)) === null) {
			// if a invalid path was specified throw an Exception
			throw new TechDivision_Controller_Exceptions_InvalidActionMappingException(
				'Invalid path ' . $path . ' specified'
			);
		}
		// register the ActionMapping the the Context
		$this->_setActionMapping($actionMapping);
    }

	/**
	 * This method includes the necessary files before the session
	 * was started. This ensures that all objects included in an
	 * ActionForm or an Action are complete.
	 *
	 * @return void
	 */
	protected function _processIncludes() {
		// log the entry of the method
		$this->_getLogger()->debug("Now in _processIncludes method", __LINE__);
        // alias of the ActionForm that should be instanciated
        $formBeanName = $this->_getActionMapping()->getName();
        // if no name was found, then return false
        if (!empty($formBeanName)) {
			// include the file with the ActionForm definition
			require_once $this->_actionFormBeans->find($formBeanName)->getInclude();
        }
		// include file was specified
		$include = $this->_getActionMapping()->getInclude();
		// if an include was specified, import it
		if (!empty($include)) {
			// import the specified inlcude
			require_once $include;
		}
		// start the session if not already done
		$this->_getRequest()->getSession();
	}

    /**
     * This method calls, if the apropriate parameter is
     * set, the validate() method of the passed ActionForm.
     * If no ActionErrors where returned then the method
     * returns true, if ActionErrors where returned, then
     * it returns false.
     *
     * @return boolean Returns TRUE if no ActionErrors,
     * 		FALSE if ActionErrors where found
     */
    protected function _processValidate() {
		// log the entry of the method
		$this->_getLogger()->debug("Now in _processValidate method", __LINE__);
		// load the ActionMapping
		$actionMapping = $this->_getActionMapping();
		$actionForm = $this->_getActionForm();
        // if validation is set to FALSE, then return TRUE
        if ($actionMapping->getValidate() === false) {
		 	return true;
		}
        // if not, then process the validate() method of the passed ActionForm
        $actionErrors = $actionForm->validate();
        // if no ActionErrors where found
        if ($actionErrors->size() === 0) {
            // return TRUE
            return true;
        }
        // load the Request
        $request = $this->getRequest();
        // register the ActionErrors in the Request
        $request->setAttribute(
            TechDivision_Controller_Action_Errors::ACTION_ERRORS,
            $actionErrors
        );
        // register the passed ActionForm in the Request
        $request->setAttribute($actionMapping->getName(), $actionForm);
        // return FALSE
        return false;
    }

    /**
     * This method populates the passed ActionForm with
     * the apropriate values found in the request.
     *
     * @return void
     */
    protected function _processPopulate() {
		// log the entry of the method
		$this->_getLogger()->debug("Now in _processPopulate method", __LINE__);
		// load the ActionForm
		$actionForm = $this->_getActionForm();
        // reset the ActionForm
        $actionForm->reset();
        // initialize the ActionForm with the values from the Request
        $actionForm->init();
    }

    /**
     * This method checks, if the ActionMapping is a dummy one. If yes, then
     * the method instanciates a new dummy ActionForward object and sets it
     * in the Controller instance. This happens e. g. a wrong path is set in
     * the Request. The Controller then stops the workflow and returns the
     * path value from the Request.
     *
     * @return boolean TRUE if a ActionForward was found, else FALSE
     */
    protected function _processForward() {
		// log the entry of the method
		$this->_getLogger()->debug("Now in _processForward method", __LINE__);
		// get the next forward, if no forward was found, return null
        if (($forward = $this->_getActionMapping()->getForward()) != null) {
            // if a forward is found then create a new ActionForward,
            // set its path to the passed value and return it
            $this->_setActionForward(
                new TechDivision_Controller_Action_Forward('', $forward)
            );
            // if an ActionForward was found, return TRUE
            return true;
        }
		// if no ActionForward was found, return FALSE
        return false;
    }

    /**
     * This method checks one or more plugins are specified in the configuration file.
     *
     * Then it runs all plugins specified in the container and returns the return value
     * of the last one.
	 *
	 * @return void
     * @throws TechDivision_Controller_Exceptions_InvalidPluginTypeException
     * 		Is thrown if plugin does not implement expected interface TechDivision_Controller_Interfaces_StrutsPlugin
     */
    protected function _processPlugins() {
		// iterate over all declared plugins and initialize them
		foreach ($this->_plugins as $plugin) {
			// include the file with the plugins definition
			require_once $plugin->getInclude();
			// create a reflection object the the plugin class
			$reflectionClass = new ReflectionClass($plugin->getType());
			// check if the necessary interface is implemented
			if (!$reflectionClass->implementsInterface("TechDivision_Controller_Interfaces_StrutsPlugin")) {
				throw new TechDivision_Controller_Exceptions_InvalidPluginTypeException(
					$reflectionClass->getName() . ' doesn\'t implement interface TechDivision_Controller_Interfaces_StrutsPlugin'
				);
			}
			// create a new instance of the plugin
			$instance = $reflectionClass->newInstance($plugin->getProperties());
			// invoke the plugins init() method
			$instance->init();
			// add the plugin to the context
			$this->getContext()->addAttribute($plugin->getType(), $instance);
		}
    }

    /**
     * This method gives back the ActionForward with the name,
     * given as a parameter. If no valid ActionForward is found
     * then the method returns null.
     *
     * @param string $name Name of the ActionForward to be returned
     * @return ActionForward The ActionForward object
     */
    public function findForward($name) {
        return $this->_actionForwards->find($name);
    }

    /**
     * This method gives back the ActionMapping with the path,
     * given as a parameter. If no valid ActionMapping is found
     * then the method returns null.
     *
     * @param string $path Path of the ActionMapping to be returned
     * @return TechDivision_Controller_Interfaces_Mapping
     * 		The ActionMapping object
     */
    public function findMapping($path) {
        return $this->_actionMappings->find($path);
    }

    /**
     * This method returns the ActionMapping marked as
     * unknown. If no ActionMapping is marked as unknown
     * then the method returns null.
     *
     * @return TechDivision_Controller_Interfaces_Mapping
     * 		The unknown ActionMapping object
     */
    public function findUnknown() {
		return $this->_actionMappings->getUnknown();
    }

    /**
     * This method gives back the ActionFormBean with the name,
     * given as a parameter. If no valid ActionFormBean is found
     * then the method returns null.
     *
     * @param string $name Name of the ActionFormBean to be returned
     * @return TechDivision_Controller_Action_FormBean The ActionFormBean object
     */
    public function findFormBean($name) {
		return $this->_actionFormBeans->find($name);
    }

    /**
     * This method returns the Plugin with the name passed
     * as a parameter. If no valid Plugin is found then
     * the method returns null.
     *
     * @param string $name Name of the Plugin to be returned
     * @return TechDivision_Controller_Plugins_Plugin
     * 		The Plugin object
     */
    public final function findPlugin($name) {
		return $this->_plugins->find($name);
    }

    /**
     * This method returns the application locale.
     *
     * @return TechDivision_Util_SystemLocale
     * 		Holds the application locale
     */
    public final function getLocale() {
        return $this->_locale;
    }

    /**
     * This method sets the application locale.
     *
     * @return string Holds the application locale
     */
    public final function setLocale(TechDivision_Util_SystemLocale $locale) {
        $this->_locale = $locale;
    }

    /**
     * This method returns the application resource bundle.
     *
     * @param TechDivision_Util_SystemLocale $locale
     * 		The locale to return the resources for
     * @param TechDivision_Lang_String $key
     * 		Holds the key of the resource bundle to use
     * @return TechDivision_Resources_AbstractResourceBundle
     * 		The requested resources
     */
    public final function getResources(TechDivision_Lang_String $key = null)
    {
		// if no key is passed, use the default resource bundle
		if ($key === null) {
			$key = new TechDivision_Lang_String(
			    TechDivision_Controller_Action_Bundle::DEFAULT_KEY
			);
		}
		// return the requested resources
        return $this->getResourceBundle($this->getLocale(), $key);
    }

    /**
     * This method returns the application resource bundle.
     *
     * @param TechDivision_Util_SystemLocale $locale
     * 		The locale to return the resources for
     * @param TechDivision_Lang_String $key
     * 		Holds the key of the resource bundle to use
     * @return TechDivision_Resources_AbstractResourceBundle
     * 		The requested resources
     */
    public function getResourceBundle(
        TechDivision_Util_SystemLocale $locale,
        TechDivision_Lang_String $key) {
		// check if the resource bundle with the requested key is already initialized
		if($this->_resources->exists($key)) {
			// get the resource bundle
			$resources = $this->_resources->get($key);
			// check if the resources for the requested locale are already initialized
			if(!$resources->exists($locale)) {
				// if not, initialize them
				$resources->add(
				    $locale,
				    $this->_initializeResourceBundle($locale, $key)
				);
			}
		} else {
			// initialize a new ResourceBundle
			$resources = new TechDivision_Collections_HashMap();
			// register the resource in the resource bundle
			$resources->add(
			    $locale,
			    $this->_initializeResourceBundle($locale, $key)
			);
			// register the resource bundle in the
			$this->_resources->add($key, $resources);
		}
		// return the initialized resource bundle
		return $this->_resources->get($key)->get($locale);
    }

    /**
     * This method returns a HashMap with all ResourceBundles
     * registered in the configuration file for the passed locale.
     *
     * @param TechDivision_Util_SystemLocale $locale
	 * 		Holds the locale to return the resource bundles for
     * @return TechDivision_Collections_HashMap
     * 		HashMap with the requested resource bundles
     */
    public function getResourceBundles(
        TechDivision_Util_SystemLocale $locale) {
    	// initialize a HashMap for the resource bundles
    	$map = new TechDivision_Collections_HashMap();
    	// iterate over all bundles
    	foreach ($this->_bundles as $bundle) {
    		// check if a key exists
    		if (($key = $bundle->getKey()) == null) {
    		    // if not, set the default key
    			$key = TechDivision_Controller_Action_Bundle::DEFAULT_KEY;
    		}
			// check if the resource bundle with the requested key is already initialized
			if ($this->_resources->exists($key)) {
				// get the resource bundle
				$resources = $this->_resources->get($key);
				// check if the resources for the requested locale are already initialized
				if(!$resources->exists($locale)) {
					// if not, initialize them
					$resources->add(
					    $locale,
					    $this->_initializeResourceBundle($locale, $key)
					);
				}
			} else {
				// initialize a new ResourceBundle
				$resources = new TechDivision_Collections_HashMap();
				// register the resource in the resource bundle
				$resources->add(
				    $locale,
				    $this->_initializeResourceBundle($locale, $key)
				);
				// register the resource bundle in the
				$this->_resources->add($key, $resources);
			}
			// set the resource bundle
			$map->add($key, $this->_resources->get($key)->get($locale));
    	}
			// return the initialized resource bundle
    	return $map;
    }

    /**
     * This method initializes and returns the application
     * resource bundle with the passed key and locale.
     *
     * @param TechDivision_Util_SystemLocale $locale
     * 		The locale to return the resources for
     * @param string $key Holds the key of the resource bundle to use
     * @return TechDivision_Resources_AbstractResourceBundle
     * 		The requested resources
     */
    protected function _initializeResourceBundle(
        TechDivision_Util_SystemLocale $locale,
        $key) {
		// log the initialization of the resource bundle
		$this->_getLogger()->debug(
			"Now initializing resource bundle with key $key and locale $locale",
		    __LINE__
		);
		// get the information about the resource bundle to initialize
		$resourceBundle = $this->_bundles->find($key);
		// load and return the ResourceBundle
		return TechDivision_Resources_PropertyResourceBundle::getBundle(
			new TechDivision_Lang_String(
			    $resourceBundle->getPath() . DIRECTORY_SEPARATOR . $resourceBundle->getName()
			),
			$locale
		);
    }

    /**
     * Sets the actual Action instance.
	 *
     * @param TechDivision_Controller_Interfaces_Action $action
     * 		The actual Action instance
     * @see TechDivision/Controller/Action/Context#setAction(TechDivision_Controller_Interfaces_Action $action)
     */
    protected function _setAction(
        TechDivision_Controller_Interfaces_Action $action) {
        $this->getContext()->setAction($action);
    }

    /**
     * Sets the actual ActionMapping instance.
	 *
     * @param TechDivision_Controller_Interfaces_Mapping $actionMapping
     * 		The actual ActionMapping instance
     * @see TechDivision/Controller/Action/Context#setActionMapping(TechDivision_Controller_Interfaces_Mapping $actionMapping)
     */
	protected function _setActionMapping(
	    TechDivision_Controller_Interfaces_Mapping $actionMapping) {
	    $this->getContext()->setActionMapping($actionMapping);
	}

    /**
     * Sets the actual ActionForm instance.
	 *
     * @param TechDivision_Controller_Interfaces_Form $action
     * 		The actual ActionForm instance
     * @see TechDivision/Controller/Action/Context#setActionForm(TechDivision_Controller_Interfaces_Form $actionForm)
     */
	protected function _setActionForm(
	    TechDivision_Controller_Interfaces_Form $actionForm = null) {
	    $this->getContext()->setActionForm($actionForm);
	}

    /**
     * Sets the actual ActionForward instance.
	 *
     * @param TechDivision_Controller_Interfaces_Forward $action
     * 		The actual ActionForward instance
     * @see TechDivision/Controller/Action/Context#setActionForward(TechDivision_Controller_Interfaces_Forward $actionForward)
     */
	protected function _setActionForward(
	    TechDivision_Controller_Interfaces_Forward $actionForward) {
	    $this->getContext()->setActionForward($actionForward);
	}

    /**
     * Sets the actual Request instance.
	 *
     * @param TechDivision_HttpUtils_Interfaces_Request $action
     * 		The actual Request instance
     * @see TechDivision/Controller/Action/Context#setRequest(TechDivision_HttpUtils_Interfaces_Request $request)
     */
	protected function _setRequest(
	    TechDivision_HttpUtils_Interfaces_Request $request) {
	    $this->getContext()->setRequest($request);
	}

	/**
	 * Returns the actual Action instance.
	 *
	 * @return TechDivision_Controller_Interfaces_Action
	 * 		The actual Action instance
	 */
	protected function _getAction()
	{
	    return $this->getContext()->getAction();
	}

	/**
	 * Returns the actual ActionMapping instance.
	 *
	 * @return TechDivision_Controller_Interfaces_Mapping
	 * 		The actual ActionMapping instance
	 */
	protected function _getActionMapping()
	{
	    return $this->getContext()->getActionMapping();
	}

	/**
	 * Returns the actual ActionForm instance.
	 *
	 * @return TechDivision_Controller_Interfaces_Form
	 * 		The actual ActionForm instance
	 */
	protected function _getActionForm()
	{
	    return $this->getContext()->getActionForm();
	}

	/**
	 * Returns the actual ActionForward instance.
	 *
	 * @return TechDivision_Controller_Interfaces_Forward
	 * 		The actual ActionForward instance
	 */
	protected function _getActionForward()
	{
	    return $this->getContext()->getActionForward();
	}

	/**
	 * Returns the actual request instance.
	 *
	 * @return TechDivision_HttpUtils_Interfaces_Request
	 * 		The request instance
	 */
	protected function _getRequest()
	{
	    return $this->getContext()->getRequest();
	}

	/**
	 * Returns the Context for the actual
	 * request.
	 *
	 * @return TechDivision_Controller_Interfaces_Context
	 * 		The Context for the actual request
	 */
	public function getContext()
	{
	    return $this->_context;
	}

	/**
	 * Sets the Configuration of the Controller instance.
	 *
	 * @param TechDivision_Controller_Interfaces_StrutsConfig $configuration
	 * 		The Configuration to use
	 */
	public function setConfiguration(
	    TechDivision_Controller_Interfaces_StrutsConfig $configuration) {
	    $this->_configuration = $configuration;
	}

	/**
	 * Returns the Configuration to use.
	 *
	 * @return TechDivision_Controller_Interfaces_StrutsConfig
	 * 		The Configuration instance
	 */
	public function getConfiguration()
	{
        return $this->_configuration;
	}
}