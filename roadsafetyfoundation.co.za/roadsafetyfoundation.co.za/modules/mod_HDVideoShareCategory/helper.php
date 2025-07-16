<?php
/**
 * Category module for HD Video Share
 *
 * This file is to fetch the particular category details
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

class Modvideosharecategory
{
	/**
	 * Function to get category videos
	 * 
	 * @return  array
	 */
	public static function getcategoryVideos($catid)
	{
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$limitrow = self::getcategoryVideossettings();
		$thumbView = unserialize($limitrow[0]->sidethumbview);

		if($thumbView['sidecategoryvideocol'] == 0)
			$thumbView['sidecategoryvideocol'] = 1;

		$length = $thumbView['sidecategoryvideorow'] * $thumbView['sidecategoryvideocol'];

		// Query is to display category videos randomly
		$query->select(
				array(
					'a.id', 'a.filepath', 'a.thumburl', 'a.title', 'a.description', 'a.times_viewed',
					'a.ratecount', 'a.rate', 'a.amazons3', 'a.times_viewed', 'a.seotitle',
					'b.category', 'b.seo_category', 'd.username', 'e.catid', 'e.vid'
					)
				)
				->from('#__hdflv_upload AS a')
				->leftJoin('#__users AS d ON a.memberid=d.id')
				->leftJoin('#__hdflv_video_category AS e ON e.vid=a.id')
				->leftJoin('#__hdflv_category AS b ON e.catid=b.id')
				->where($db->quoteName('a.published') . ' = ' . $db->quote('1') . ' AND ' . $db->quoteName('b.published') . ' = ' . $db->quote('1'))
				->where(
						'('
						. $db->quoteName('e.catid') . ' = ' . $db->quote($catid)
						. ' OR ' . $db->quoteName('b.parent_id') . ' = ' . $db->quote($catid)
						. ' OR ' . $db->quoteName('a.playlistid') . ' = ' . $db->quote($catid)
						. ')'
				)
				->where($db->quoteName('d.block') . ' = ' . $db->quote('0'))
				->group($db->escape('e.vid'))
				->order($db->escape('a.id' . ' ' . 'DESC'));
		$db->setQuery($query, 0, $length);
		$categoryvideos = $db->loadobjectList();

		return $categoryvideos;
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
	 * Function to get category videos module settings
	 * 
	 * @return  array
	 */
	public static function getcategoryVideossettings()
	{
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		// Query is to select the category videos module settings
		$query->select(array('dispenable', 'sidethumbview'))
				->from('#__hdflv_site_settings');
		$db->setQuery($query);
		$rows = $db->loadObjectList();

		return $rows;
	}
}
