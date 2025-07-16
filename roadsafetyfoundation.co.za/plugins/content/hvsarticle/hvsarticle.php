<?php
/**
 * HVS Article plugin for HD Video Share
 *
 * This file is to dispaly video details that matches the shortcode entered inside article 
 *
 * @category   Apptha
 * @package    Com_Contushdvideoshare
 * @version    3.6
 * @author     Apptha Team <developers@contus.in>
 * @copyright  Copyright (C) 2014 Apptha. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */

// No direct access to this file
defined('_JEXEC') or die('Access Denied!');

// Import Joomla plugin library
jimport('joomla.plugin.plugin');

/**
 * HVS Article Plugin class.
 *
 * @package     Joomla.Contus_HD_Video_Share
 * @subpackage  Com_Contushdvideoshare
 * @since       1.5
 */
class PlgContenthvsarticle extends JPlugin
{
	/**
	 * Constructor function
	 * 
	 * @param   string  &$subject  subject
	 * @param   string  $config    config detail
	 * 
	 * @return  PlgContenthvsarticle
	 */
	public function PlgContenthvsarticle(&$subject, $config)
	{
		parent::__construct($subject, $config);
	}

	/**
	 * Function to load content in content prepare hook
	 * 
	 * @param   string  $context   context
	 * @param   string  &$article  article content
	 * @param   string  &$params   article param
	 * @param   int     $page      page no
	 * 
	 * @return  onContentPrepare
	 */
	public function onContentPrepare($context, &$article, &$params, $page = 0)
	{
		$this->onPrepareContent($article, $params, $page);
	}

	/**
	 * Function to load content in prepare content hook
	 * 
	 * @param   string  &$row        article content
	 * @param   string  &$params     article param
	 * @param   int     $limitstart  data per page
	 * 
	 * @return  onPrepareContent
	 */
	public function onPrepareContent(&$row, &$params, $limitstart)
	{
		$thumImg = $videos = $filepath = null;
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		// Declare the variables
		$type = '';

		$patterncode = '/\[hdvs(.*?)]/i';
		preg_match_all($patterncode, $row->text, $matches);

		$code = $matches[0];
		$count = count($code);

		for ($i = 0; $i < $count; $i++)
		{
			$string = $code[$i];
			$pattern = array("[", "]", "hdvs");
			$chk_shortCode_pattern = str_replace($pattern, "", $string);
			$trim_shortCode = trim(strip_tags($chk_shortCode_pattern));
			$utf8conevert_shortCode = iconv('utf-8', 'ascii//translit', $trim_shortCode);
			$shortCode = preg_replace("/\s+/", "|", $utf8conevert_shortCode);
			$finalCode = explode("|", trim($shortCode, "|"));
			$pwidth = $pheight = $pautoplay = $playautoplay = $idval = $swidth = $sheight
							= $sautoplay = $splayautoplay = $categoryid = null;

			foreach ($finalCode as $val)
			{
				$data = explode("=", $val);

				//  To get the video details from the shortcode
				if ($data[0] == 'videoid')
				{
					$idval = $data[1];
				}
				elseif ($data[0] == 'width')
				{
					$swidth = $data[1];
				}
				elseif ($data[0] == 'height')
				{
					$sheight = $data[1];
				}
				elseif ($data[0] == 'autoplay')
				{
					$sautoplay = $data[1];
				}
				elseif ($data[0] == 'playlistautoplay')
				{
					$splayautoplay = $data[1];
				}
				elseif ($data[0] == 'categoryid')
				{
					$categoryid = $data[1];
				}
				elseif ($data[0] == 'type')
				{
					$type = $data[1];
				}
			}

			if ($categoryid != '' || $idval != '' || $type != '')
			{
				// Get the video details from the database using id
				if ($categoryid != '' && $idval != '')
				{
					$query->clear()
						->select(array('streamerpath', 'streameroption', 'filepath', 'videourl', 'thumburl', 'embedcode'))
						->from('#__hdflv_upload')
						->where('id = ' . (int) $idval)
						->where('playlistid = ' . (int) $categoryid);
					$db->setQuery($query);
					$field = $db->loadObjectList();
				}
				elseif ($categoryid != '')
				{
					$query->clear()
						->select(array('streamerpath', 'streameroption', 'filepath', 'videourl', 'thumburl', 'embedcode'))
						->from('#__hdflv_upload')
						->where('playlistid = ' . (int) $categoryid);
					$db->setQuery($query);
					$field = $db->loadObjectList();

					if (!empty($field))
					{
						$idval = $field[0]->id;
					}
				}
				elseif ($idval != '')
				{
					$query->clear()
						->select(array('streamerpath', 'streameroption', 'filepath', 'videourl', 'thumburl', 'embedcode'))
						->from('#__hdflv_upload')
						->where('id = ' . (int) $idval);
					$db->setQuery($query);
					$field = $db->loadObjectList();
				}
				elseif ($type != '')
				{
					switch ($type)
					{
						case 'rec':
							$order = " a.id DESC ";
							break;
						case 'fea':
							$query->where($db->quoteName('a.featured') . ' = ' . $db->quote('1'));
							$order = " a.id DESC ";
							break;
						case 'pop':
							$order = " a.times_viewed DESC ";
							break;
					}

					$query->clear()
						->select(array('a.streamerpath', 'a.streameroption', 'a.filepath', 'a.videourl', 'a.thumburl', 'a.embedcode'))
						->from('#__hdflv_upload AS a')
						->leftJoin('#__users AS d ON a.memberid=d.id')
						->leftJoin('#__hdflv_video_category AS e ON e.vid=a.id')
						->leftJoin('#__hdflv_category AS b ON e.catid=b.id')
						->where($db->quoteName('a.published') . ' = ' . $db->quote('1') . ' AND ' . $db->quoteName('b.published') . ' = ' . $db->quote('1'))
						->where($db->quoteName('a.type') . ' = ' . $db->quote('0'))
						->group($db->escape('e.vid'))
						->order($db->escape($order));
					$db->setQuery($query);
					$field = $db->loadObjectList();
				}

				if (!empty($field))
				{
					$filepath = $field[0]->filepath;
					$streameroption = $field[0]->streameroption;
					$streamerpath = $field[0]->streamerpath;

					// If file option File or FFMpeg then, below fetch will work for Video & Thumb URL
					if ($filepath == "File" || $filepath == "FFmpeg" || $filepath == "Embed")
					{
						$current_path = "components/com_contushdvideoshare/videos/";

						if ($filepath == "File" || $filepath == "FFmpeg")
						{
							$videos = JURI::base() . $current_path . $field[0]->videourl;
						}
						else
						{
							$videos = $field[0]->embedcode;
						}

						$thumImg = JURI::base() . $current_path . $field[0]->thumburl;
					}

					// If file option Youtube then, below fetch will work for Video & Thumb URL
					elseif ($filepath == "Youtube")
					{
						$videos = $field[0]->videourl;
						$thumImg = $field[0]->thumburl;
					}
					elseif ($filepath == "Url")
					{
						if ($streameroption == 'rtmp')
						{
							$rtmp = str_replace('rtmp', 'http', $streamerpath);
							$videos = $rtmp . '_definst_/mp4:' . $field[0]->videourl . '/playlist.m3u8';
						}
						else
						{
							$videos = $field[0]->videourl;
						}

						$thumImg = $field[0]->thumburl;
					}
				}

				// Fetch the height and width from the default settings
				$query->clear()
					->select(array('player_icons', 'player_values'))
					->from('#__hdflv_player_settings');
				$db->setQuery($query);
				$rs_settings = $db->loadObject();
				$player_icons = unserialize($rs_settings->player_icons);
				$player_values = unserialize($rs_settings->player_values);

				if ($player_icons['playlist_autoplay'] == 1)
				{
					$playautoplay = "true";
				}
				else
				{
					$playautoplay = 'false';
				}

				// Fetch Width, Height param Values
				$plugin = JPluginHelper::getPlugin('content', 'hvsarticle');

				if (!empty($plugin))
				{
					$plgParams = json_decode($plugin->params);

					if (!empty($plgParams))
					{
						$pheight = $plgParams->height;
						$pwidth = $plgParams->width;
						$pautoplay = $plgParams->autoplay;
						$playautoplay = $plgParams->playautoplay;
					}
				}

				// To assign the width
				if ($swidth != "")
				{
					$width = $swidth;
				}
				elseif ($pwidth != "")
				{
					$width = $pwidth;
				}
				else
				{
					$width = $player_values['width'];
				}

				// To assign the height
				if ($sheight != "")
				{
					$height = $sheight;
				}
				elseif ($pheight != "")
				{
					$height = $pheight;
				}
				else
				{
					$height = $player_values['height'];
				}

				// To assign the autoplay
				if ($sautoplay != "")
				{
					$autoplay = $sautoplay;
				}
				elseif ($pautoplay != "" && $pautoplay != "select")
				{
					$autoplay = $pautoplay;
				}
				else
				{
					if ($player_icons['autoplay'] == 1)
					{
						$autoplay = "true";
					}
					else
					{
						$autoplay = "false";
					}
				}

				// To assign the playlist autoplay
				if ($splayautoplay != "")
				{
					$playautoplay = $splayautoplay;
				}
				elseif ($playautoplay != "" && $playautoplay != "select")
				{
					$playautoplay = $playautoplay;
				}

				$replace = $this->addVideoHdvideo($width, $height, $idval, $categoryid, $autoplay, $filepath, $videos, $thumImg, $type, $playautoplay);
				$row->text = str_replace($string, $replace, $row->text);
			}
		}
	}

	/**
	 * Function to find shortcode type
	 * 
	 * @param   string  $shortcode  shortcode from article
	 * 
	 * @return  getthetype
	 */
	public function getthetype($shortcode)
	{
		switch (true)
		{
			case (strstr($shortcode, 'pop')):
				$type = 'pop';
				break;

			case (strstr($shortcode, 'rec')):
				$type = 'rec';
				break;

			case (strstr($shortcode, 'fea')):
				$type = 'fea';
				break;

			default:
				$type = '';
				break;
		}

		return $type;
	}

	/**
	 * Function to removes extra space from the given string
	 * 
	 * @param   string  $str1  remove space from the given string
	 * 
	 * @return  removesextraspace
	 */
	public function removesextraspace($str1)
	{
		$str2 = trim(str_replace("]", "", (trim($str1))));

		return $str2;
	}

	/**
	 * Function to detect mobile device
	 * 
	 * @return  hvsarticle_detect_mobile
	 */
	public function hvsarticle_detect_mobile()
	{
		$_SERVER['ALL_HTTP'] = isset($_SERVER['ALL_HTTP']) ? $_SERVER['ALL_HTTP'] : '';
		$mobile_browser = '0';
		$agent = strtolower($_SERVER['HTTP_USER_AGENT']);

		if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|iphone|ipad|ipod|android|xoom)/i', $agent))
		{
			$mobile_browser++;
		}

		if ((isset($_SERVER['HTTP_ACCEPT'])) and (strpos(strtolower($_SERVER['HTTP_ACCEPT']), 'application/vnd.wap.xhtml+xml') !== false))
		{
			$mobile_browser++;
		}

		if (isset($_SERVER['HTTP_X_WAP_PROFILE']))
		{
			$mobile_browser++;
		}

		if (isset($_SERVER['HTTP_PROFILE']))
		{
			$mobile_browser++;
		}

		$mobile_ua = substr($agent, 0, 4);
		$mobile_agents = array(
			'w3c ', 'acs-', 'alav', 'alca', 'amoi', 'audi', 'avan', 'benq', 'bird', 'blac',
			'blaz', 'brew', 'cell', 'cldc', 'cmd-', 'dang', 'doco', 'eric', 'hipt', 'inno',
			'ipaq', 'java', 'jigs', 'kddi', 'keji', 'leno', 'lg-c', 'lg-d', 'lg-g', 'lge-',
			'maui', 'maxo', 'midp', 'mits', 'mmef', 'mobi', 'mot-', 'moto', 'mwbp', 'nec-',
			'newt', 'noki', 'oper', 'palm', 'pana', 'pant', 'phil', 'play', 'port', 'prox',
			'qwap', 'sage', 'sams', 'sany', 'sch-', 'sec-', 'send', 'seri', 'sgh-', 'shar',
			'sie-', 'siem', 'smal', 'smar', 'sony', 'sph-', 'symb', 't-mo', 'teli', 'tim-',
			'tosh', 'tsm-', 'upg1', 'upsi', 'vk-v', 'voda', 'wap-', 'wapa', 'wapi', 'wapp',
			'wapr', 'webc', 'winw', 'xda', 'xda-'
		);

		if (in_array($mobile_ua, $mobile_agents))
		{
			$mobile_browser++;
		}

		if (strpos(strtolower($_SERVER['ALL_HTTP']), 'operamini') !== false)
		{
			$mobile_browser++;
		}

		// Pre-final check to reset everything if the user is on Windows
		if (strpos($agent, 'windows') !== false)
		{
			$mobile_browser = 0;
		}

		// But WP7 is also Windows, with a slightly different characteristic
		if (strpos($agent, 'windows phone') !== false)
		{
			$mobile_browser++;
		}

		if ($mobile_browser > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Function for loading player with necessary inputs
	 * 
	 * @param   int     $width         player width
	 * @param   int     $height        player height
	 * @param   int     $idval         video id
	 * @param   int     $categoryid    categoryid
	 * @param   int     $autoplay      check autoplay enable or not
	 * @param   string  $filepath      upload method type
	 * @param   string  $videos        video url
	 * @param   string  $thumImg       thumb image url
	 * @param   string  $type          video type
	 * @param   int     $playautoplay  check playlist autoplay enable or not
	 * 
	 * @return  addVideoHdvideo
	 */
	public function addVideoHdvideo($width, $height, $idval, $categoryid, $autoplay,
		$filepath, $videos, $thumImg, $type, $playautoplay)
	{
		// Variables initialization
		$playlist_auto = $category = $playxml = $replace = $windo = '';
		$baseurl = JURI::base();
		$baseurl1 = substr_replace($baseurl, "", -1);
		$idval = trim($idval);
		$playerpath = JURI::base() . 'components/com_contushdvideoshare/hdflvplayer/hdplayer.swf';
		$mobile = $this->hvsarticle_detect_mobile();
		$useragent = $_SERVER['HTTP_USER_AGENT'];

		// Check for windows phone
		if (strpos($useragent, 'Windows Phone') > 0)
		{
			$windo = 'Windows Phone';
		}

		if ($type)
		{
			if (version_compare(JVERSION, '3.0', 'ge')
				|| version_compare(JVERSION, '1.6', 'ge')
				|| version_compare(JVERSION, '1.7', 'ge')
				|| version_compare(JVERSION, '2.5', 'ge'))
			{
				$playlistpath = JURI::base() . "plugins/content/hvsarticle/playlist.php?type=" . $type;
			}
			else
			{
				$playlistpath = JURI::base() . "plugins/content/playlist.php?type=" . $type;
			}

			$playxml = "&amp;playlistXML=" . $playlistpath;
		}

		if (!empty($idval) && !empty($categoryid))
		{
			$video_params = "&amp;id=" . $idval . "&amp;catid=" . $categoryid;
		}
		elseif (!empty($categoryid))
		{
			$video_params = "&amp;catid=" . $categoryid;
		}
		else
		{
			$video_params = "&amp;id=" . $idval;
		}

		if ($playautoplay)
		{
			$playlist_auto = "&amp;playlist_autoplay=" . $playautoplay;
		}

		if ($filepath == "Embed")
		{
			$replace .= $videos;
		}

		// Checks for Vimeo Player
		elseif (strpos($videos, 'vimeo') > 0)
		{
			$split = explode("/", $videos);
			$replace .= '<iframe src="http://player.vimeo.com/video/' . $split[3]
					. '?title=0&amp;byline=0&amp;portrait=0" width="' . $width . '" height="' . $height . '"
						frameborder="0"></iframe>';
		}
		else
		{
			if ($mobile === true)
			{
				// HTML5 player start here
				if ($filepath == "Youtube" || strpos($videos, 'youtube.com') > 0)
				{
					if (strpos($videos, 'youtube.com') > 0)
					{
						// If youtube video
						$url = $videos;
						$query_string = array();
						parse_str(parse_url($url, PHP_URL_QUERY), $query_string);
						$id = $query_string["v"];
						$videoid = trim($id);
						$video = "http://www.youtube.com/embed/$videoid";
						$replace .= '<iframe src="' . $video . '" class="iframe_frameborder" ></iframe>';
					}
					elseif (strpos($videos, 'dailymotion') > 0)
					{
						// If dailymotion video
						$video = $videos;
						$replace .= '<iframe src="' . $video . '" class="iframe_frameborder" ></iframe>';
					}
					elseif (strpos($videos, 'viddler') > 0)
					{
						// If viddler video
						$imgstr = explode("/", $videos);
						$replace .= '<iframe id="viddler-' . $imgstr . '" src="//www.viddler.com/embed/'
								. $imgstr . '/?f=1&autoplay=0&player=full&secret=26392356&loop=false&nologo=false&hd=false"
									frameborder="0" mozallowfullscreen="true" webkitallowfullscreen="true"></iframe>';
					}
				}
				else if ($filepath == "File" || $filepath == "FFmpeg" || $filepath == "Url")
				{
					// Checks for File or FFMpeg or url
					$replace .= '<video id="video" style="width:100%" poster="' . $thumImg . '" src="' . $videos
							. '" autobuffer controls onerror="failed(event)">
Html5 Not support This video Format.
</video>';
				}
				else
				{
					$replace .= ' <style type="text/css">
.login_msg{vertical-align: middle;height:' . $height . 'px;display: table-cell; color: #fff;}
.login_msg a{background: #999; color:#fff; padding: 5px;}
</style>

<div id="video" style="height:' . $height . 'px; background-color:#000000; position: relative;" >
<div class="login_msg">
<h3>Theer is no videos in this playlist</h3>
</div>
</div>';
				}
			}
			else
			{
				// Else normal player
				$replace .= '<div class="videoshareplayer" id="videoshareplayer" style="width:'
						. $width . 'px;height:' . $height . 'px;" >'
						. '<embed src="' . $playerpath . '" allowFullScreen="true"  allowScriptAccess="always"
							type="application/x-shockwave-flash" wmode="opaque" flashvars="baserefJHDV=' . $baseurl1
						. $playxml . $video_params . $playlist_auto
						. '&amp;mid=1&amp;mtype=playerModule&amp;autoplay=' . $autoplay . '" style="width:'
						. $width . 'px;height:' . $height . 'px;" /></embed>'
						. '</div>';
			}

			$replace .= '<script>
var txt     =  navigator.platform ;
var windo   = "' . $windo . '";
function failed(e) {
if(txt =="iPod" || txt =="iPad" || txt =="iPhone" || windo=="Windows Phone"  || txt =="Linux armv7l" || txt =="Linux armv6l")
{
alert("Player doesnot support this video.");
}
}
</script>';

// HTML5 PLAYER  END
		}

		return $replace;
	}
}
