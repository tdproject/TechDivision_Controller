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

require_once "TechDivision/Controller/Action/Forward.php";
 
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
interface TechDivision_Controller_Interfaces_Mapping
{
	
    /**
     * This method searches in the internal container with ActionForward
     * objects for the ActionForward with the passed key and returns it.
     * If no ActionForward is found the method looks in the global ActionForwards
     * of the RequestProcessor reference. If the requested ActionForward is
     * found there then the method will return it.
     *
     * @param string $name Holds the key of the requested ActionForward
     * @return TechDivision_Controller_Action_Forward $actionForward 
     * 		Holds the requested ActionForward
     */
    public function findForward($name);

    /**
     * This method returns the path of the ActionMapping.
     *
     * @return string Holds the path of the ActionMapping
     */
    public function getPath();

    /**
     * This method returns the class name of the ActionFormBean
     * associated with the ActionMapping.
     *
     * @return string Holds the class name of the associated ActionFormBean
     */
    public function getType();

    /**
     * This method returns the key of the ActionFormBean associated with
     * the ActionMapping.
     *
     * @return string Holds the key of the associated ActionFormBean
     */
    public function getName();

    /**
     * This method returns true if the controller should automatically
     * invoke the validate() method of the associated ActionForm.
     *
     * @return boolean True if the associated ActionForm should automatically be validated
     */
    public function getValidate();

    /**
     * This method returns the name of the form that sends the request.
     *
     * @return string Holds the name of the form that sends the request
     */
    public function getInput();

    /**
     * This method returns the scope of the associated ActionForm.
     *
     * @return string Holds the scope of the associated ActionForm
     */
    public function getScope();

    /**
     * This method returns additional parameters used by the ActionBanana.
     *
     * @return string Holds additional parameters used by the ActionBanana
     */
    public function getParameter();

    /**
     * This method returns the name of the next form
     * that should be called by the ActionBanana.
     *
     * @return string Holds the name of the next form called by the ActionBanana
     */
    public function getForward();

    /**
     * Sets the name of the next form that should be called
     * by the Controller.
     *
     * @param string $forward Holds the path to the next form that should be called
     */
    public function setForward($forward);

    /**
     * This method returns true if the ActionMapping is marked
     * as unknown.
     *
     * @return boolean True if the ActionMapping is marked as unknown
     */
    public function getUnknown();
    
    /**
     * This method returns a reference to the container with
     * the ActionMappings.
     *
     * @return TechDivision_Controller_Interfaces_Mappings 
     * 		Referenz to the ActionMappings instance
     */
    public function getMappings();

    /**
     * This method adds the ActionForward passed as a parameter
     * to the internal container with ActionForwards.
     *
     * @param TechDivision_Controller_Action_Forward $actionForward 
     * 		Holds the ActionForward that should be added to the internal container
     * @return void
     */
    public function addActionForward(TechDivision_Controller_Action_Forward $actionForward);

    /**
     * This method returns the ActionForward for
     * this ActionMapping.
     *
     * @return TechDivision_Controller_Action_Forwards 
     * 		The ActionForwards for this ActionMapping
     */
    public function getActionForwards();

    /**
     * This method returns the number of ActionForward objects
     * in the internal ActionForwards container.
     *
     * @return integer Returns the number of ActionForward objects in the internal ActionForwards container
     */
    public function count();
	
    /**
     * This method returns the complete path to
     * include the necessary file for the action.
     *
     * @return string Returns the complete path to include the action
     */		
	public function getInclude();		
}