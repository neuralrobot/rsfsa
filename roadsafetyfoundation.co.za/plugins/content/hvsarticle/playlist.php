<?php
/**
 * HVS Article plugin for HD Video Share
 *
 * This file is to fetch video details that matches the shortcode entered inside article 
 *
 * @category   Apptha
 * @package    Com_Contushdvideoshare
 * @version    3.6
 * @author     Apptha Team <developers@contus.in>
 * @copyright  Copyright (C) 2014 Apptha. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */

// No direct access to this file
define('_JEXEC', 1);

$path = explode("plugins", dirname(__FILE__));
define('JPATH_BASE', $path[0]);
define('DS', DIRECTORY_SEPARATOR);

require_once JPATH_BASE . DS . 'configuration.php';
require_once JPATH_BASE . DS . 'includes' . DS . 'defines.php';
require_once JPATH_BASE . DS . 'includes' . DS . 'framework.php';

require_once JPATH_BASE . DS . 'libraries' . DS . 'joomla' . DS . 'factory.php';

$type = JRequest::getVar('type');
$playlistautoplay = $download = 'false';
$order = null;
$db = JFactory::getDbo();
$query = $db->getQuery(true);
$baseUrl = JURI::base();
$baseUrl1 = parse_url($baseUrl);
$baseUrl1 = $baseUrl1['scheme'] . '://' . $baseUrl1['host'];
$baseUrl2 = str_replace('/plugins/content/hvsarticle', '', $baseUrl);

switch ($type)
{
	case 'rec':
		$order = "id DESC ";

		break;

	case 'fea':
		$query->where($db->quoteName('a.featured') . ' = ' . $db->quote('1'));
		$order = " a.id DESC ";

		break;

	case 'pop':
		$order = "times_viewed DESC ";

		break;
}

// Get player settings
$query->clear()
		->select('player_icons')
		->from('#__hdflv_player_settings');

$db->setQuery($query);
$rs_settings = $db->loadResult();
$player_icons = unserialize($rs_settings);

if ($player_icons['playlist_autoplay'] == 1)
{
	$playlistautoplay = "true";
}

if ($player_icons['enabledownload'] == 1)
{
	$download = "true";
}

// Query to get Video details
$query->clear()
		->select(array('a.*', 'b.category', 'd.username', 'e.*'))
		->from('#__hdflv_upload AS a')
		->leftJoin('#__users AS d ON a.memberid=d.id')
		->leftJoin('#__hdflv_video_category AS e ON e.vid=a.id')
		->leftJoin('#__hdflv_category AS b ON e.catid=b.id')
		->where($db->quoteName('a.published') . ' = ' . $db->quote('1') . ' AND ' . $db->quoteName('b.published') . ' = ' . $db->quote('1'))
		->where($db->quoteName('a.type') . ' = ' . $db->quote('0'))
		->where($db->quoteName('d.block') . ' = ' . $db->quote('0'))
		->where($db->quoteName('a.filepath') . ' != ' . $db->quote('Embed'))
		->group($db->escape('e.vid'))
		->order($db->escape($order));
$db->setQuery($query);
$records = $db->loadObjectList();
$accessid = getUserAccessId();

$hdvideo = $timage = $streamername = $targeturl = '';
$islive = "false";
$postrollid = $prerollid = 0;
ob_clean();
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("content-type: text/xml");
echo '<?xml version="1.0" encoding="utf-8"?>';
echo '<playlist autoplay="' . $playlistautoplay . '">';
$current_path = "components/com_contushdvideoshare/videos/";

foreach ($records as $record)
{
	if (version_compare(JVERSION, '1.6.0', 'ge'))
	{
		if ($record->useraccess == 0)
		{
			$record->useraccess = 1;
		}

		$query->clear()
				->select('rules')
				->from('#__viewlevels')
				->where('id = ' . $db->quote($record->useraccess));
		$db->setQuery($query);
		$message = $db->loadResult();
		$accessLevel = json_decode($message);
	}

// To check video access for members
	$member = "true";

	if (version_compare(JVERSION, '1.6.0', 'ge'))
	{
		$member = "false";

		foreach ($accessLevel as $useracess)
		{
			if ((is_array($useracess) && in_array("$useracess", $accessid)) || $useracess == 1)
			{
				$member = "true";
				break;
			}
		}
	}
	else
	{
		if ($record->useraccess != 0)
		{
			if ($accessid != $record->useraccess && $accessid != 2)
			{
				$member = "false";
			}
		}
	}

	// To get the video url
	if ($record->filepath == "File" || $record->filepath == "FFmpeg")
	{
		$video = $baseUrl2 . $current_path . $record->videourl;

		if ($record->hdurl != "")
		{
			$hdvideo = $baseUrl2 . $current_path . $record->hdurl;
		}

		if (!empty($record->previewurl))
		{
			$preview_image = $record->previewurl;
		}
		else
		{
			$preview_image = 'default_preview.jpg';
		}

		$previewimage = $baseUrl2 . $current_path . $preview_image;
		$timage = $baseUrl2 . $current_path . $record->thumburl;

		if ($record->hdurl)
		{
			$hd_bol = "true";
		}
		else
		{
			$hd_bol = "false";
		}
	}
	elseif ($record->filepath == "Url")
	{
		$video = $record->videourl;

		if (!empty($record->previewurl))
		{
			$previewimage = $record->previewurl;
		}
		else
		{
			$previewimage = $baseUrl2 . $current_path . 'default_preview.jpg';
		}

		$timage = $record->thumburl;

		if ($record->hdurl)
		{
			$hd_bol = "true";
		}
		else
		{
			$hd_bol = "false";
		}

		$hdvideo = $record->hdurl;
	}
	elseif ($record->filepath == "Youtube")
	{
		$video = $record->videourl;
		$str2 = strstr($record->previewurl, 'components');

		if ($str2 != "")
		{
			$previewimage = $baseUrl2 . $record->previewurl;
			$timage = $baseUrl2 . $record->thumburl;
		}
		else
		{
			$previewimage = $record->previewurl;
			$timage = $record->thumburl;
		}

		$hd_bol = "false";
		$hdvideo = "";
	}

	if ($record->streameroption == "lighttpd")
	{
		$streamername = $record->streameroption;
	}

	if ($record->streameroption == "rtmp")
	{
		$streamername = $record->streamerpath;
	}

	// Get seo category title
	$query->clear()
			->select('seo_category')
			->from('#__hdflv_category')
			->where('id = ' . $db->quote($record->playlistid));
	$db->setQuery($query);
	$seo_category = $db->loadResult();

	// To get the fb path
	$query->clear()
			->select('dispenable')
			->from('#__hdflv_site_settings');
	$db->setQuery($query);
	$resultSetting = $db->loadResult();
	$dispenable = unserialize($resultSetting);

	if ($dispenable['seo_option'] == 1)
	{
		// If seo option enabled
		$fbCategoryVal = "category=" . $seo_category;
		$fbVideoVal = "video=" . $record->seotitle;
	}
	else
	{
		// If seo option disabled
		$fbCategoryVal = "catid=" . $record->playlistid;
		$fbVideoVal = "id=" . $record->id;
	}

	$fbPath = $baseUrl1 . '/index.php?option=com_contushdvideoshare&view=player&' . $fbCategoryVal . '&' . $fbVideoVal;

	// Get post roll ad id for video
	$query->clear()
			->select('*')
			->from('#__hdflv_ads')
			->where($db->quoteName('published') . ' =  ' . $db->quote('1') . ' AND ' . $db->quoteName('id') . ' = ' . $db->quote($record->postrollid));
	$db->setQuery($query);
	$rs_postads = $db->loadObjectList();
	$postroll = ' allow_postroll = "false"';
	$postroll_id = ' postroll_id = "0"';

	if (count($rs_postads) > 0)
	{
		if ($record->postrollads == 1)
		{
			$postroll = ' allow_postroll = "true"';
			$postroll_id = ' postroll_id = "' . $record->postrollid . '"';
		}
	}

	// Get pre roll ad id for video
	$query->clear()
			->select('*')
			->from('#__hdflv_ads')
			->where($db->quoteName('published') . ' =  ' . $db->quote('1') . ' AND ' . $db->quoteName('id') . ' = ' . $db->quote($record->prerollid));
	$db->setQuery($query);
	$rs_preads = $db->loadObjectList();
	$preroll = ' allow_preroll = "false"';
	$preroll_id = ' preroll_id = "0"';

	if (count($rs_preads) > 0)
	{
		if ($record->prerollads == 1)
		{
			$preroll = ' allow_preroll = "true"';
			$preroll_id = ' preroll_id = "' . $record->prerollid . '"';
		}
	}

	// Get mid ad id for video
	$query->clear()
			->select('*')
			->from('#__hdflv_ads')
			->where($db->quoteName('published') . ' =  ' . $db->quote('1') . ' AND ' . $db->quoteName('typeofadd') . ' = ' . $db->quote('mid'));
	$db->setQuery($query);
	$rs_ads = $db->loadObjectList();
	$midroll = ' allow_midroll = "false"';

	if (count($rs_ads) > 0)
	{
		if ($record->midrollads == 1)
		{
			$midroll = ' allow_midroll = "true"';
		}
	}

	// Get ima ad for video
	$query->clear()
			->select('*')
			->from('#__hdflv_ads')
			->where($db->quoteName('published') . ' =  ' . $db->quote('1') . ' AND ' . $db->quoteName('typeofadd') . ' = ' . $db->quote('ima'));
	$db->setQuery($query);
	$rs_imaads = $db->loadObjectList();
	$imaad = ' allow_ima = "false"';

	if (count($rs_imaads) > 0)
	{
		if ($record->imaads == 1)
		{
			$imaad = ' allow_ima = "true"';
		}
	}

	if ($record->targeturl != "")
	{
		// Get target url for a video
		$targeturl = $record->targeturl;
	}

	if ($record->postrollads == "1")
	{
		// Get pre roll id for a video
		$postrollid = $record->postrollid;
	}

	if ($record->prerollads == "1")
	{
		// Get post roll id for a video
		$prerollid = $record->prerollid;
	}

	// To get the other values
	if ($record->filepath == "Youtube")
	{
		$download = "false";
	}

	if ($dispenable['viewedconrtol'] == 1)
	{
	$views = $record->times_viewed;
	}
	else
	{
		$views = '';
	}

	$tags = $record->tags;

	if ($streamername != "")
	{
		if ($record->islive == 1)
		{
			$islive = "true";
		}
	}

	echo '<mainvideo
		views="' . $views . '"
		streamer_path="' . $streamername . '"
		video_isLive="' . $islive . '"
		video_id = "' . htmlspecialchars($record->id) . '"
		fbpath = "' . $fbPath . '"
		video_url = "' . htmlspecialchars($video) . '"
		thumb_image = "' . htmlspecialchars($timage) . '"
		preview_image = "' . htmlspecialchars($previewimage) . '"
		' . $midroll . '
		' . $imaad . '
		' . $postroll . '
		' . $preroll . '
		' . $postroll_id . '
		' . $preroll_id . '
		Tag =  "' . $tags . '"
		allow_download = "' . $download . '"
		video_hdpath = "' . $hdvideo . '"
		copylink = "">
		<title><![CDATA[' . htmlspecialchars($record->title) . ']]></title>
		<tagline targeturl="' . $targeturl . '"><![CDATA[' . htmlspecialchars($record->description) . ']]></tagline>
		</mainvideo>';
}

echo "</playlist>";
exit;

/**
 * Function to get user access role
 * 
 * @return  getUserAccessId
 */
function getUserAccessId()
{
	$user = JFactory::getUser();
	$uid = '';

	if (version_compare(JVERSION, '1.6.0', 'ge'))
	{
		$uid = $user->get('id');

		if ($uid)
		{
			$db = JFactory::getDBO();
			$query = $db->getQuery(true);
			$query->select('g.id AS group_id')
					->from('#__usergroups AS g')
					->leftJoin('#__user_usergroup_map AS map ON map.group_id = g.id')
					->where('map.user_id = ' . (int) $uid);
			$db->setQuery($query);
			$message = $db->loadObjectList();

			foreach ($message as $mess)
			{
				$accessid[] = $mess->group_id;
			}
		}
		else
		{
			$accessid[] = 1;
		}
	}
	else
	{
		$accessid = $user->get('aid');
	}
}
