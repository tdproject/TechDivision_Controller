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
require_once 'TechDivision/Controller/Interfaces/Action.php';
require_once 'TechDivision/Controller/Interfaces/Mapping.php';
require_once 'TechDivision/HttpUtils/Interfaces/Request.php';
 
/**
 * This class is the abstract base class for all
 * Actions.
 *
 * @category TechDivision
 * @package TechDivision_Controller
 * @author Tim Wagner <t.wagner@techdivision.com>
 * @copyright TechDivision GmbH
 * @link http://www.techdivision.com
 * @license GPL, version 2.0
 */
abstract class TechDivision_Controller_Action_Abstract 
    extends TechDivision_Lang_Object
    implements TechDivision_Controller_Interfaces_Action {
        
    /**
     * The Context for the actual Request.
     * @var TechDivision_Controller_Interfaces_Context
     */
    protected $_context = null;

    /**
     * Initializes the Action with the Context for the
     * actual request.
	 *
     * @param TechDivision_Controller_Interfaces_Context $context
     * 		The Context for the actual Request
     * @return void
     */
    public function __construct(
        TechDivision_Controller_Interfaces_Context $context) {
        $this->_context = $context;
    }
    
    /**
     * (non-PHPdoc)
     * @see src/Interfaces/TechDivision_Controller_Interfaces_Action::preDispatch()
     */
    public function preDispatch()
    {
        return;
    }
    /**
     * (non-PHPdoc)
     * @see src/Interfaces/TechDivision_Controller_Interfaces_Action::postDispatch()
     */
    public function postDispatch()
    {
        return;
    }
    
    /**
     * Sets the ActionForward of the actual ActionMapping with 
     * the passed in the actual Context.
     * 
     * @param string $name The name of the ActionForward to set
     * @return void
     */
    protected function _findForward($name)
    {
        $this->getContext()->setActionForward(
            $this->_getActionMapping()->findForward($name)
        );
    }
    
    /**
     * Returns the instance of the ActionMapping for
     * the requested path.
     * 
     * @return TechDivision_Controller_Interfaces_Mapping
     * 		The requested ActionMapping instance
     */
    protected function _getActionMapping()
    {
        return $this->getContext()->getActionMapping();
    }
    
    /**
     * Returns the instance of the ActionForm for the actual
     * ActionMapping definded in the configuration.
     * 
     * @return TechDivision_Controller_Interfaces_Form
     * 		The requested ActionForm instance
     */
    protected function _getActionForm()
    {
        return $this->getContext()->getActionForm();
    }
    
    /**
     * Returns an instance of the actual Request.
     * 
     * @return TechDivision_HttpUtils_Interfaces_Request
     * 		The request instance
     */
    protected function _getRequest()
    {
        return $this->getContext()->getRequest();
    }

    /**
     * Returns the class name of the actual instance.
     * 
     * @return string The class name
     */
    protected function _getClass()
    {
         return get_class($this);       
    }
    
    /**
     * Returns the context for the actual Request.
	 *
	 * @return TechDivision_Controller_Interfaces_Context
	 *		The Context for the actual Request
     */
    public function getContext()
    {
        return $this->_context;
    }
}