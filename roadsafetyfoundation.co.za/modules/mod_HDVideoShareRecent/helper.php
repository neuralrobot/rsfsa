<?php
/**
 * Recent module for HD Video Share
 *
 * This file is to fetch Recent videos module details 
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
 * Recent Videos Module Helper
 *
 * @package     Joomla.Contus_HD_Video_Share
 * @subpackage  Com_Contushdvideoshare
 * @since       1.5
 */
class Modrecentvideos
{
	/**
	 * Function to get Recent videos
	 * 
	 * @return  getfeaturedVideos
	 */
	public static function getrecentvideos()
	{
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$limitrow = self::getrecentvideossettings();
		$thumbView = unserialize($limitrow[0]->sidethumbview);

		if($thumbView['siderecentvideocol'] == 0)
			$thumbView['siderecentvideocol'] = 1;

		$length = $thumbView['siderecentvideorow'] * $thumbView['siderecentvideocol'];

		// Query is to display recent videos
		$query->select(
				array(
					'a.id', 'a.filepath', 'a.thumburl', 'a.title', 'a.description',
					'a.times_viewed', 'a.ratecount', 'a.rate', 'a.amazons3', 'a.times_viewed',
					'a.seotitle', 'b.category', 'b.seo_category', 'd.username', 'e.catid', 'e.vid')
				)
				->from('#__hdflv_upload AS a')
				->leftJoin('#__users AS d ON a.memberid=d.id')
				->leftJoin('#__hdflv_video_category AS e ON e.vid=a.id')
				->leftJoin('#__hdflv_category AS b ON e.catid=b.id')
				->where($db->quoteName('a.published') . ' = ' . $db->quote('1') . ' AND ' . $db->quoteName('b.published') . ' = ' . $db->quote('1'))
				->where($db->quoteName('a.type') . ' = ' . $db->quote('0'))
				->group($db->escape('e.vid'))
				->order('a.id DESC');
		$db->setQuery($query, 0, $length);
		$recentvideos = $db->loadobjectList();

		return $recentvideos;
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

	/**
	 * Function to get recent videos module settings
	 * 
	 * @return  getrecentvideossettings
	 */
	public static function getrecentvideossettings()
	{
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		// Query is to select the recent videos settings
		$query->select(array('dispenable', 'sidethumbview'))
				->from('#__hdflv_site_settings');
		$db->setQuery($query);
		$rows = $db->LoadObjectList();

		return $rows;
	}
}
