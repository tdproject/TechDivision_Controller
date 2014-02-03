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
interface TechDivision_Controller_Interfaces_Forward
{
	
    /**
     * Returns the path of the ActionForward object.
     *
     * @return string The path of the ActionForward object
     */
    public function getPath();

    /**
     * Returns the key of the ActionForward object.
     *
     * @return string The key of the ActionForward object
     */
    public function getName();

    /**
     * Returns a flag if the ActionForward has to
     * be redirected by HTTP or not.
     *
     * @return boolean TRUE if a HTTP redirect is requested, else FALSE
     */
    function isRedirect();		
}