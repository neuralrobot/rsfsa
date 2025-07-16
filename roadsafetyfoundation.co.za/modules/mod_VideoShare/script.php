<?php
/**
 * Video Share Player module for HD Video Share
 *
 * This file is to install Video Share player as a module 
 *
 * @category   Apptha
 * @package    Mod_Videoshare
 * @version    3.6
 * @author     Apptha Team <developers@contus.in>
 * @copyright  Copyright (C) 2014 Apptha. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */

// No direct access to this file
defined ( '_JEXEC' ) or die ( 'Restricted access' );

class Mod_VideoShareInstallerScript
{
	/**
	 * Joomla installation hook for plugin
	 * 
	 * @param   string  $parent  parent value
	 * 
	 * @return  install
	 */
	function install($parent)
	{
		$db = JFactory::getDBO ();
		$query = "UPDATE #__modules SET published='1' WHERE module='mod_VideoShare' ";
		$db->setQuery ( $query );
		$db->query ();
		$query = "SELECT id FROM #__modules WHERE module = 'mod_VideoShare' ";
		$db->setQuery ( $query );
		$db->query ();
		$mid4 = $db->loadResult ();
		$query = "INSERT INTO #__modules_menu (moduleid) VALUES ('$mid4')";
		$db->setQuery ( $query );
		$db->query ();
	}

	/**
	 * Joomla uninstallation hook for plugin
	 *
	 * @return  uninstall
	 */
	function uninstall($parent) {
	}

	/**
	 * Joomla before installation hook for plugin
	 *
	 * @param   string  $type    type
	 * @param   string  $parent  parent value
	 *
	 * @return  preflight
	 */
	function preflight($type, $parent) {
	}

	/**
	 * Joomla after installation hook for plugin
	 *
	 * @param   string  $type    type
	 * @param   string  $parent  parent value
	 *
	 * @return  postflight
	 */
	function postflight($type, $parent) {
	}
}
