<?php
/**
 * HD Video Share Player module
 *
 * This file is to display HD Video Share Player module 
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

$language = JRequest::getVar('lang');
$languages = '';
if ($language != '') {
    $languages = '&slang=' . $language;
}else{
     $languages = '&slang=en';
}
$baseURL = JURI::base();
$playerpath = JURI::base() . "components/com_contushdvideoshare/hdflvplayer/hdplayer.swf";
?>

	<h2 id="mod_viewtitle"><?php echo $videoList->title; ?></h2>
	<div class="mod_hdvideoplayer <?php echo $class; ?>">
		<?php
		/**
 * Function to Detect mobile device for category videos
 * 
 * @return  Detect mobile device
 */
function videostylo_mod_Detect_mobile()
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
		$mobile = videostylo_mod_Detect_mobile();

		if ($mobile !== true) {
		?>
			<script type="text/javascript">
				function currentvideom(id,title,desc,view){
					document.getElementById('mod_viewtitle').innerHTML = title;
				}
			</script>
					
		<?php
		}
						
		$homepageaccess = Modvideoshare::getaccesslevel($videoList->useraccess);
		$user = JFactory::getUser();
		if ($user->get('id') != '')
		{
			$error_msg = JText::_('HDVS_NOT_AUTHORIZED');
		}
		else
		{
			$error_msg = JText::_('HDVS_LOGIN_TO_WATCH');
		}
		if (($videoList->filepath == 'Embed')
			|| (!empty($videoList)
			&& (preg_match('/vimeo/', $videoList->videourl))
			&& ($videoList->videourl != '')))
		{
		
			if ($homepageaccess == 'true')
			{
				if ($videoList->filepath == 'Embed')
				{
					$playerembedcode = $videoList->embedcode;
					$playeriframewidth = str_replace('width=', 'width="'.$width.'"', $playerembedcode);
					Modvideoshare::videohitCount_mod_function($videoList->id);

					if ($mobile === true)
					{
						echo $playerembedcode;
					}
					else
					{
						// For embed code videos
						?>
						<?php echo str_replace(
								'height=', 'height="'.$height.'"', $playeriframewidth
								); ?>
						<?php
					}
				}
				elseif (
						!empty($videoList)
						&& (preg_match('/vimeo/', $videoList->videourl))
						&& ($videoList->videourl != '')
						)
				{
					// For vimeo videos
					$split = explode("/", $videoList->videourl);
					Modvideoshare::videohitCount_mod_function($videoList->id);

					if ($mobile === true)
					{
						$widthheight = '';
					}
					else
					{
						$widthheight = 'width="' . $width . '" height="' . $height . '"';
					}
					?>
						<iframe <?php echo $widthheight; ?> src="<?php echo 'http://player.vimeo.com/video/'
						. $split[3]; ?>" webkitallowfullscreen mozallowfullscreen allowfullscreen class="iframe_frameborder">
						</iframe>
		<?php
				}
			}
	else
	{
		?>
				<style type="text/css">
					.login_msg{height:200px; color: #fff;width: 100%;
							  margin: <?php echo ceil(300 / 3); ?>px 0 0;text-align: center;}
					.login_msg a{background: #999; color:#fff; padding: 5px;}
				</style>
				<div id="video" style="height:200px;
					 background-color:#000000; position: relative;" >
					<div class="login_msg">
						<h3><?php echo $error_msg; ?></h3>
				<?php
				if ($user->get('id') == '')
				{
				?>
							<a href="<?php
							if (!empty($player_icons['login_page_url']))
							{
								echo $player_icons['login_page_url'];
							}
							else
							{
								echo "#";
							}
							?>"><?php echo JText::_('HDVS_LOGIN'); ?></a>
					<?php
				}
							?>
					</div>
				</div>
				<?php
	}
		}
		else
		{
		if ($mobile === true)
				{
					?>
				<!-- HTML5 player starts here -->
					<?php
					// Generate details for HTML5 player
					if ($homepageaccess == 'true')
					{
						Modvideoshare::videohitCount_mod_function($videoList->id);

						if ($videoList->filepath == "Youtube" || strpos($videoList->videourl, 'youtube') > 0)
						{
							// For youtube videos
							if (strpos($videoList->videourl, 'youtube') > 0)
							{
								$url = $videoList->videourl;
								$query_string = array();
								parse_str(parse_url($url, PHP_URL_QUERY), $query_string);
								$id = $query_string["v"];
								$videoid = trim($id);
								$video = "http://www.youtube.com/embed/$videoid";
								?>
										<iframe width="<?php echo $width; ?>" height="<?php echo $height; ?>"  src="<?php
										echo $video;
										?>" class="iframe_frameborder" ></iframe>
										<?php
									}
									elseif (strpos($videoList->videourl, 'dailymotion') > 0)
									{
										// For dailymotion videos
										$video = $videoList->videourl;
										?>
										<iframe width="<?php echo $width; ?>" height="<?php echo $height; ?>" src="<?php
										echo $video;
										?>"
										class="iframe_frameborder" ></iframe>
							<?php
									}
						elseif (strpos($videoList->videourl, 'viddler') > 0)
						{
							// For viddler videos
							$imgstr = explode("/", $videoList->videourl);
							?>
										<iframe id="viddler-<?php echo $imgstr; ?>" src="//www.viddler.com/embed/<?php
										echo $imgstr; ?>/?f=1&autoplay=0&player=full&secret=26392356&loop=false&nologo=false&hd=false"
										width="<?php echo $width; ?>" height="<?php echo $heigt; ?>"
										frameborder="0" mozallowfullscreen="true" webkitallowfullscreen="true"></iframe>
												<?php
						}
					}
					else if ($videoList->filepath == "File"
							|| $videoList->filepath == "FFmpeg"
							|| $videoList->filepath == "Url")
						{
							$current_path = "components/com_contushdvideoshare/videos/";

							if ($videoList->filepath == "Url")
							{
								// For URL Method videos
								if ($videoList->streameroption == 'rtmp')
								{
									$rtmp = str_replace('rtmp', 'http', $videoList->streamerpath);

									// For RTMP videos
									$video = $rtmp . '_definst_/mp4:' . $videoList->videourl
											. '/playlist.m3u8';
								}
								else
								{
									$video = $videoList->videourl;
								}
							}
							else
							{
								if (isset($videoList->amazons3) && $videoList->amazons3 == 1)
								{
									$video = $dispenable['amazons3link']
											. $videoList->videourl;
								}
								else
								{
									// For upload Method videos
									$video = JURI::base() . $current_path . $videoList->videourl;
								}
							}
							?>
							<video id="video" src="<?php
							echo $video;
							?>" width="<?php echo $width; ?>"
							height="<?php echo $height; ?>" autoplay controls
							onerror="failed(event)"></video>
							<?php
						}
					}
							else
							{
								// Restricted video design part
								?>
						<style type="text/css">
							.login_msg{vertical-align: middle;height:200px;
									  display: table-cell; color: #fff;}
							.login_msg a{background: #999; color:#fff; padding: 5px;}
						</style>
						<div id="video" style="height:200px;
							 background-color:#000000; position: relative;" >
							<div class="login_msg">
								<h3><?php echo $error_msg; ?></h3>
			<?php
			if ($user->get('id') == '')
			{
			?>
									<a href="<?php
									if (!empty($player_icons['login_page_url']))
									{
										echo $player_icons['login_page_url'];
									}
									else
									{
										echo '#';
									}
									?>"><?php echo JText::_('HDVS_LOGIN'); ?></a>
			<?php
			}
			?>
							</div>
						</div>
		<?php
							}
				} else { ?>
           <embed wmode="opaque" src="<?php echo $playerpath; ?>"
                type="application/x-shockwave-flash" allowscriptaccess="always"
                allowfullscreen="<?php echo $fullscreen; ?>"
                flashvars="baserefJHDV=<?php echo $baseURL . $playsettings . '&mid=playerModule' . $languages; ?>"
                width="<?php echo $width; ?>" height="<?php echo $height; ?>"></embed>
				<?php }
				}
				?>
	</div>
