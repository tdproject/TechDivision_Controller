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

/**
 * This class is a container for the information of
 * a resource bundle defined in the configuration file.
 *
 * @category TechDivision
 * @package TechDivision_Controller
 * @author Tim Wagner <t.wagner@techdivision.com>
 * @copyright TechDivision GmbH
 * @link http://www.techdivision.com
 * @license GPL, version 2.0
 */
class TechDivision_Controller_Action_Bundle 
	extends TechDivision_Lang_Object {

	/**
	 * Holds the default key for the resource bundle.
	 * @var string
	 */
	const DEFAULT_KEY = "default.key";

    /**
     * Holds the key of the Bundle
     * @var string
     */
    protected $_key = '';	
	
    /**
     * Holds the path of the Bundle
     * @var string
     */
    protected $_path = "";

    /**
     * Holds the key of the Bundle
     * @var string
     */
    protected $_name = "";	
	
    /**
     * The constructor initializes the name and the path
     * with the passed values.
     *
     * @param string $name Holds the key of the Bundle object
     * @param string $path Holds the path of the Bundle object
     * @param string $key Holds the key of the Bundle object
     */
    public function __construct($name = null, $path = null, $key = null) {
        $this->_name = $name;
        $this->_path = $path;
        if ($key != null) {
			$this->_key = $key;
        } else {
			$this->_key = self::DEFAULT_KEY; 
        }
    }
	
    /**
     * Returns the path of the Bundle object.
     *
     * @return string Holds the path of the Bundle object
     */
    public function getPath() {
        return $this->_path;
    }

    /**
     * Returns the key of the Bundle object.
     *
     * @return string Holds the key of the Bundle object
     */
    public function getName() {
        return $this->_name;
    }

    /**
     * Sets the path of the Bundle object.
     *
     * @param string $path Holds the path of the Bundle object
     */
    public function setPath($path) {
        $this->_path = $path;
    }

    /**
     * Sets the key of the Bundle object.
     *
     * @param string $name Holds the key of the Bundle object
     */
    public function setName($name) {
        $this->_name = $name;
    }

    /**
     * Sets the key that the Bundle should be stored under
     *
     * @param string $key Holds the key that the Bundle should be stored under
     * @return void
     */
    function setKey($key) {
        $this->_key = $key;
    }

    /**
     * Returns the key that the Bundle should be stored under
     *
     * @return string Holds the key that the Bundle should be stored under
     */
    function getKey() {
        return $this->_key;
    }		
}