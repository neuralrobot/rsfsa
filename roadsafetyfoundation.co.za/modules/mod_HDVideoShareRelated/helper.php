<?php
/**
 * Related videos module for HD Video Share
 *
 * This file is to fetch Related videos module 
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
 * Related Videos Module Helper
 *
 * @package     Joomla.Contus_HD_Video_Share
 * @subpackage  Com_Contushdvideoshare
 * @since       1.5
 */
class Modrelatedvideos
{
	/**
	 * Function to get Related videos
	 * 
	 * @return  getrelatedvideos
	 */
	public static function getrelatedvideos()
	{
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$limitrow = self::getrelatedvideossettings();
		$thumbView = unserialize($limitrow[0]->sidethumbview);

		if($thumbView['siderelatedvideocol'] == 0)
			$thumbView['siderelatedvideocol'] = 1;

		$length = $thumbView['siderelatedvideorow'] * $thumbView['siderelatedvideocol'];

		/* CODE FOR SEO OPTION OR NOT - START */
		$video = JRequest::getVar('video');
		$id = JRequest::getInt('id');
		$flagVideo = is_numeric($video);

		if (isset($video) && $video != "")
		{
			if ($flagVideo != 1)
			{
				// Joomla router replaced to : from - in query string
				$videoTitle = JRequest::getString('video');
				$videoid = str_replace(':', '-', $videoTitle);

				if ($videoid != "")
				{
					if (!version_compare(JVERSION, '3.0.0', 'ge'))
					{
						$videoid = $db->getEscaped($videoid);
					}
				}

				$query->clear()
						->select('playlistid')
						->from('#__hdflv_upload')
						->where($db->quoteName('seotitle') . ' = ' . $db->quote($videoid));
				$db->setQuery($query);
				$video = $db->loadResult();
			}
			else
			{
				$videoid = JRequest::getInt('video');
				$query->clear()
						->select('playlistid')
						->from('#__hdflv_upload')
						->where($db->quoteName('id') . ' = ' . $db->quote($videoid));
				$db->setQuery($query);
				$video = $db->loadResult();
			}
		}
		elseif (isset($id) && $id != '')
		{
			$videoid = JRequest::getInt('id');
			$query->clear()
					->select('playlistid')
					->from('#__hdflv_upload')
					->where($db->quoteName('id') . ' = ' . $db->quote($videoid));
			$db->setQuery($query);
			$video = $db->loadResult();
		}

		// CODE FOR SEO OPTION OR NOT - END
		if (isset($videoid) && (isset($video)) && !empty($video))
		{
			$query->clear()
				->select(
						array(
							'a.id', 'a.filepath', 'a.thumburl', 'a.title', 'a.description',
							'a.times_viewed', 'a.ratecount', 'a.rate', 'a.amazons3', 'a.times_viewed',
							'a.seotitle', 'b.category', 'b.seo_category', 'd.username', 'e.catid', 'e.vid'
							)
						)
				->from('#__hdflv_upload AS a')
				->leftJoin('#__users AS d ON a.memberid=d.id')
				->leftJoin('#__hdflv_video_category AS e ON e.vid=a.id')
				->leftJoin('#__hdflv_category AS b ON e.catid=b.id')
				->where($db->quoteName('a.published') . ' = ' . $db->quote('1') . ' AND ' . $db->quoteName('b.published') . ' = ' . $db->quote('1'))
				->where($db->quoteName('a.playlistid') . ' = ' . $db->quote($video))
				->group($db->escape('e.vid'))
				->order($db->escape('a.id' . ' ' . 'DESC'));
			$db->setQuery($query, 0, $length);
			$relatedvideos = $db->loadObjectList();

			return $relatedvideos;
		}
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
	 * Function to get realted videos module settings
	 * 
	 * @return  getrelatedvideossettings
	 */
	public static function getrelatedvideossettings()
	{
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		// Query is to select the realted videos settings
		$query->select(array('dispenable', 'sidethumbview'))
			->from('#__hdflv_site_settings');
		$db->setQuery($query);
		$rows = $db->loadObjectList();

		return $rows;
	}
}
