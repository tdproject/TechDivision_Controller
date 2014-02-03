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
 * Objects of this class contains messages that occurs
 * for example while processing an Action.
 *
 * @category TechDivision
 * @package TechDivision_Controller
 * @author Tim Wagner <t.wagner@techdivision.com>
 * @copyright TechDivision GmbH
 * @link http://www.techdivision.com
 * @license GPL, version 2.0
 */
class TechDivision_Controller_Action_Message 
	extends TechDivision_Lang_Object {

    /**
     * This variable holds the message
     * @var string
     */
    protected $_message = "";

    /**
     * This variable holds the key of the message
     * @var string
     */
    protected $_name = "";

    /**
     * The constructor initializes the internal member
     * variables with the passed values.
     *
     * @param string $name Holds the key of the message
     * @param string $message Holds the message
     */
    public function __construct($name, $message) {
        $this->_name = $name;
        $this->_message = $message;
    }
	
    /**
     * Sets the message.
     *
     * @param string $message Holds the message
     */
    public function setMessage($message) {
        $this->_message = $message;
    }

    /**
     * Sets the key of the message.
     *
     * @param string $name Holds the key of the message
     */
    public function setName($name) {
        $this->_name = $name;
    }

    /**
     * Returns the message.
     *
     * @return string Holds the message
     */
    public function getMessage() {
        return $this->_message;
    }

    /**
     * Returns the key of the message.
     *
     * @return string Holds the message
     */
    public function getName() {
        return $this->_name;
    }
}