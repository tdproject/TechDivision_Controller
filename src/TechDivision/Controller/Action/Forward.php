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
require_once 'TechDivision/Controller/Interfaces/Forward.php';

/**
 * This class is a container for the information of
 * an ActionForward defined in the configuration
 * file.
 *
 * @category TechDivision
 * @package TechDivision_Controller
 * @author Tim Wagner <t.wagner@techdivision.com>
 * @copyright TechDivision GmbH
 * @link http://www.techdivision.com
 * @license GPL, version 2.0
 */
class TechDivision_Controller_Action_Forward 
	extends TechDivision_Lang_Object
	implements TechDivision_Controller_Interfaces_Forward {

	/**
	 * Holds the array with the boolean values to replace.
	 * @var array
	 */
	protected $_values = array("true" => true, "false" => false);
	
    /**
     * Holds the path of the ActionForward
     * @var string
     */
    protected $_path = "";

    /**
     * Holds the key of the ActionForward
     * @var string
     */
    protected $_name = "";

    /**
     * Holds the flag if a Action should be redirected or forwarded
     * @var string
     * @access private
     */
    protected $redirect = "";	
	
    /**
     * The constructor initializes the name and the path
     * with the passed values.
     *
     * @param string $name Holds the key of the ActionForward object
     * @param string $path Holds the path of the ActionForward object
     * @param string $redirect Holds the flag that the ActionForward should be redirected or not
     */
    public function __construct($name = null, $path = null, $redirect = "false") {
        $this->_name = $name;
        $this->_path = $path;
		$this->_redirect = $this->_values[$redirect];
    }
	
    /**
     * Returns the path of the ActionForward object.
     *
     * @return string Holds the path of the ActionForward object
     */
    public function getPath() {
        return $this->_path;
    }

    /**
     * Returns the key of the ActionForward object.
     *
     * @return string Holds the key of the ActionForward object
     */
    public function getName() {
        return $this->_name;
    }

    /**
     * Sets the path of the ActionForward object.
     *
     * @param string $path Holds the path of the ActionForward object
     */
    public function setPath($path) {
        $this->_path = $path;
    }

    /**
     * Sets the key of the ActionForward object.
     *
     * @param string $name Holds the key of the ActionForward object
     */
    public function setName($name) {
        $this->_name = $name;
    }

    /**
     * Sets the flag that the Action should be redirected or forwarded
     *
     * @param string $redirect Holds the that the Action should be redirected or forwared
     * @return void
     */
    function setRedirect($redirect = "false") {
        $this->_redirect = $this->_values[$redirect];
    }

    /**
     * Returns the flag that the Action should be redirected or forwarded
     *
     * @return string Holds the flag that the Action should be redirected or forwared
     */
    function getRedirect() {
        return $this->_redirect;
    }

    /**
     * Returns the key of the ActionForward object
     *
     * @return boolean Holds the key of the ActionForward object
     */
    function isRedirect() {
        return $this->_redirect;
    }		
}