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
require_once "TechDivision/Controller/Interfaces/Mapping.php";
require_once "TechDivision/Controller/Action/Mappings.php";
require_once "TechDivision/Controller/Action/Forwards.php";

/**
 * This class is a container for the information of
 * an ActionMapping defined in the configuration
 * file.
 *
 * @category TechDivision
 * @package TechDivision_Controller
 * @author Tim Wagner <t.wagner@techdivision.com>
 * @copyright TechDivision GmbH
 * @link http://www.techdivision.com
 * @license GPL, version 2.0
 */
class TechDivision_Controller_Action_Mapping
	extends TechDivision_Lang_Object
	implements TechDivision_Controller_Interfaces_Mapping {

	/**
	 * This variable holds the key to register forms in session scope.
	 * @var string
	 */
	const SESSION_SCOPE = "session";

	/**
	 * This variable holds the key to register forms in request scope.
	 * @var string
	 */
	const REQUEST_SCOPE = "request";

    /**
     * This variable holds the path of the ActionMapping
     * @var string
     */
    protected $_path = "";

    /**
     * This variable holds the class name of the Action for this ActionMapping
     * @var string
     */
    protected $_type = "";

    /**
     * This variable holds the name of the ActionFormBean for this ActionMapping
     * @var string
     */
    protected $_name = "";

    /**
     * This variable holds the path of the next ActionForward
     * @var string
     */
    protected $_forward = "";

    /**
     * This variable holds the name of the form that sends the request
     * @var string
     */
    protected $_input = "";

    /**
     * This variable holds the scope of the ActionForm, possible values are session oder request
     * @var string
     */
    protected $_scope = "";

    /**
     * This variable holds additional parameters
     * @var string
     */
    protected $_parameter = "";

    /**
     * This variable holds the path to the include file
     * @var string
     */
    protected $_include = "";

    /**
     * This variable is true if the ActionMapping is the one that should be returned if the requested one is not found
     * @var boolean
     */
    protected $_unknown = false;

    /**
     * This variable is true if the ActionForm associated with this ActionMapping should be automatically validated
     * @var boolean
     */
    protected $_validate = false;

    /**
     * This variable holds the ActionForward objects associated with this ActionMapping
     * @var TechDivision_Controller_Action_Forwards
     */
    protected $_actionForwards = null;

    /**
     * This variable holds a reference to the ActionMappings
     * @var TechDivision_Controller_Action_Mappings
     */
    protected $_actionMappings = null;

    /**
     * The constructor initializes the internal ActionForwards
     * and ActionMappings with the ActionMappings passed as a
     * reference.
     *
     * @return void
     */
    public function __construct() {
        // Initializing the internal ActionForwards and ActionMappings
        $this->_actionForwards = new TechDivision_Controller_Action_Forwards();
    }

    /**
     * This method searches in the internal container with ActionForward
     * objects for the ActionForward with the passed key and returns it.
     * If no ActionForward is found the method looks in the global ActionForwards
     * of the RequestProcessor reference. If the requested ActionForward is
     * found there then the method will return it.
     *
     * @param string $name Holds the key of the requested ActionForward
     * @return TechDivision_Controller_Action_Forward $actionForward
     * 		The requested ActionForward
     */
    public function findForward($name) {
        // first look in the internal ActionForward container
        $actionForward = $this->_actionForwards->find($name);
        if (!$actionForward) {
            // then look in the ActionForwards of the ActionController
            $actionMappings = $this->getMappings();
            $actionForward = $actionMappings->getController()->findForward($name);
        }
        // if no ActionForward is found return false
        if (!$actionForward) {
		 	return false;
		}
        // else return the requested ActionForward
        return $actionForward;
    }

    /**
     * Sets the path of the ActionMapping
     *
     * @param string $path Holds the path of the ActionMapping
     */
    public function setPath($path) {
        $this->_path = $path;
    }

    /**
     * Sets the class name of the ActionFormBean associated with the ActionMapping
     *
     * @param string $type Holds the class name of the associated ActionFormBean
     */
    public function setType($type) {
        $this->_type = $type;
    }

    /**
     * Sets the key of the associated ActionFormBean
     *
     * @param string $name Holds the key of the associated ActionFormBean
     */
    public function setName($name) {
        $this->_name = $name;
    }

    /**
     * This method sets the flag to signal that the associated
     * ActionForm should be validated automatically. If set to
     * true, the validate() method of ActionForm is automatically
     * invoked.
     *
     * @param string $validate True if the associated ActionForm should be validated
     */
    public function setValidate($validate) {
        if (strcmp($validate, "true") == 0) {
            $this->_validate = true;
        } elseif (strcmp($validate, "false") == 0) {
            $this->_validate = false;
        }
    }

    /**
     * This method sets the name of the form that sends the request.
     *
     * @param string $input Name of the form that sends the request
     */
    public function setInput($input) {
        $this->_input = $input;
    }

    /**
     * This method sets the scope of the associated ActionForm.
     * Possible values are request or session.
     *
     * @param string $scope Holds the scope of the associated ActionForm
     */
    public function setScope($scope) {
        $this->_scope = $scope;
    }

    /**
     * This method sets additional parameters used by the ActionBanana.
     *
     * @param string $parameter Holds additional parameters used by the ActionBanana
     */
    public function setParameter($parameter) {
        $this->_parameter = $parameter;
    }

    /**
     * This method returns the path of the ActionMapping.
     *
     * @return string Holds the path of the ActionMapping
     */
    public function getPath() {
        return $this->_path;
    }

    /**
     * This method returns the class name of the ActionFormBean
     * associated with the ActionMapping.
     *
     * @return string Holds the class name of the associated ActionFormBean
     */
    public function getType() {
        return $this->_type;
    }

    /**
     * This method returns the key of the ActionFormBean associated with
     * the ActionMapping.
     *
     * @return string Holds the key of the associated ActionFormBean
     */
    public function getName() {
        return $this->_name;
    }

    /**
     * This method returns true if the controller should automatically
     * invoke the validate() method of the associated ActionForm.
     *
     * @return boolean True if the associated ActionForm should automatically be validated
     */
    public function getValidate() {
        return $this->_validate;
    }

    /**
     * This method returns the name of the form that sends the request.
     *
     * @return string Holds the name of the form that sends the request
     */
    public function getInput() {
        return $this->_input;
    }

    /**
     * This method returns the scope of the associated ActionForm.
     *
     * @return string Holds the scope of the associated ActionForm
     */
    public function getScope() {
        return $this->_scope;
    }

    /**
     * This method returns additional parameters used by the ActionBanana.
     *
     * @return string Holds additional parameters used by the ActionBanana
     */
    public function getParameter() {
        return $this->_parameter;
    }

    /**
     * This method returns the name of the next form
     * that should be called by the ActionBanana.
     *
     * @return string Holds the name of the next form called by the ActionBanana
     */
    public function getForward() {
        return $this->_forward;
    }

    /**
     * Sets the name of the next form that should be calles
     * by the ActionBanana.
     *
     * @param string $forward Holds the path to the next form that should be called
     */
    public function setForward($forward) {
        $this->_forward = $forward;
    }

    /**
     * This method returns true if the ActionMapping is marked
     * as unknown.
     *
     * @return boolean True if the ActionMapping is marked as unknown
     */
    public function getUnknown() {
        return $this->_unknown;
    }

    /**
     * This method marks the ActionMapping as unknown.
     *
     * @param boolean $unknown True if the ActionMapping is marked as unknown
     */
    public function setUnknown($unknown) {
        if (strcmp($unknown, "true") == 0) {
            $this->_unknown = true;
        } elseif(strcmp($unknown, "false") == 0) {
            $this->_unknown = false;
        }
    }

    public function setMappings(
    	TechDivision_Controller_Action_Mappings $actionMappings) {
    	$this->_actionMappings = $actionMappings;
    	return $this;
    }

    /**
     * This method returns a reference to the container with
     * the ActionMappings.
     *
     * @return TechDivision_Controller_Action_Mappings
     * 		Reference to the ActionMappings instance
     */
    public function getMappings() {
        return $this->_actionMappings;
    }

    /**
     * This method adds the ActionForward passed as a parameter
     * to the internal container with ActionForwards.
     *
     * @param TechDivision_Controller_Action_Forward $actionForward
     * 		Holds the ActionForward that should be added to the internal container
     */
    public function addActionForward(TechDivision_Controller_Action_Forward $actionForward) {
        $this->_actionForwards->add($actionForward->getName(), $actionForward);
    }

    /**
     * This method returns the ActionForward for
     * this ActionMapping.
     *
     * @return ActionForwards The ActionForwards for this ActionMapping
     */
    public function getActionForwards() {
        return $this->_actionForwards;
    }

    /**
     * This method returns the number of ActionForward objects
     * in the internal ActionForwards container.
     *
     * @return integer Returns the number of ActionForward objects in the internal ActionForwards container
     */
    public function count() {
        return $this->_actionForwards->size();
    }

    /**
     * This method returns the complete path to
     * include the necessary file for the action.
     *
     * @return string Returns the complete path to include the action
     */
	public function getInclude() {
		return $this->_include;
	}

    /**
     * This method sets the complete path to
     * include the necessary file for the action.
     *
     * @param string $include Holds the complete path to include the action
     */
    public function setInclude($include) {
        $this->_include = $include;
    }
}