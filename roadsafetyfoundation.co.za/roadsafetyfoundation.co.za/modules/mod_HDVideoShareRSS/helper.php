<?php
/**
 * RSS module for HD Video Share
 *
 * This file is to display Video Share RSS module 
 *
 * @category   Apptha
 * @package    Mod_HDVideoShareRSS
 * @version    3.6
 * @author     Apptha Team <developers@contus.in>
 * @copyright  Copyright (C) 2014 Apptha. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */

// No direct access to this file
defined ( '_JEXEC' ) or die ( 'Restricted access' );

class Modvideosharerss
{
	/**
	 * Function to get itemid of the video share menu
	 *
	 * @return  int
	 */
	public static function getmenuitemid_thumb()
	{
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
	
		// Query is to get itemid of the video share menu
		$query->select('id')
		->from('#__menu')
		->where($db->quoteName('link') . ' = ' . $db->quote('index.php?option=com_contushdvideoshare&view=player') . ' AND ' . $db->quoteName('published') . ' = ' . $db->quote('1'))
		->order('id DESC');
		$db->setQuery($query);
		$Itemid = $db->loadResult();
	
		return $Itemid;
	}
}
