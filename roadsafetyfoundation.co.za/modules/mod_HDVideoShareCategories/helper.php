<?php
/**
 * Categories module for HD Video Share
 *
 * This file is to fetch all the categories name in the module 
 *
 * @category   Apptha
 * @package    Mod_HDVideoShareRSS
 * @version    3.6
 * @author     Apptha Team <developers@contus.in>
 * @copyright  Copyright (C) 2014 Apptha. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Category Module Helper class.
 *
 * @package     Joomla.Contus_HD_Video_Share
 * @subpackage  Com_Contushdvideoshare
 * @since       1.5
 */
class Modcategorylist
{
	/**
	 * Function to get category list
	 * 
	 * @return  getcategorylist
	 */
	public static function getcategorylist()
	{
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$fields = array(
			$db->quoteName('a.id'),
			$db->quoteName('a.category'),
			$db->quoteName('a.seo_category'),
			'COUNT(DISTINCT b.id) AS level'
			);
		$query->clear()
				->select($fields)
				->from($db->quoteName('#__hdflv_category') . ' AS a')
				->leftJoin('#__hdflv_category AS b ON a.lft > b.lft AND a.rgt < b.rgt')
				->where($db->quoteName('a.published') . ' = ' . $db->quote('1'))
				->group($db->escape('a.id' . ' ,' . 'a.category' . ' , ' . 'a.lft' . ' , ' . 'a.rgt'))
				->order($db->quoteName('a.lft'));
		$db->setQuery($query);
		$rs = $db->loadObjectList();

		return $rs;
	}

	/**
	 * Function to get parent category list
	 * 
	 * @param   int  $id  parent category id
	 * 
	 * @return  getcategorylist
	 */
	public static function getparentcategory($id)
	{
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('*')
					->from('#__hdflv_category')
					->where($db->quoteName('parent_id') . ' IN ( ' . $db->quote($id) . ' ) AND ' . $db->quoteName('published') . ' = ' . $db->quote('1'))
					->order($db->quoteName('category'));
		$db->setQuery($query);
		$rs = $db->loadObjectList();

		return $rs;
	}

	/**
	 * Function to get category settings
	 * 
	 * @return  getcategorysettings
	 */
	public static function getcategorysettings()
	{
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		// Query is to select the popular videos row
		$query->select('dispenable')
			->from('#__hdflv_site_settings');
		$db->setQuery($query);
		$rows = $db->loadResult();

		return $rows;
	}

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
