<?php
/**
 * Related videos module for HD Video Share
 *
 * This file is to display Related videos module 
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
$ratearray = array("nopos1", "onepos1", "twopos1", "threepos1", "fourpos1", "fivepos1");
$document = JFactory::getDocument();

if (JRequest::getVar('option') != 'com_contushdvideoshare')
{
	$document->addStyleSheet(JURI::base() . 'components/com_contushdvideoshare/css/mod_stylesheet.min.css');
	$document->addScript(JURI::base() . 'components/com_contushdvideoshare/js/jquery.js');
	$document->addScript(JURI::base() . "components/com_contushdvideoshare/js/htmltooltip.js");
}

$lang = JFactory::getLanguage();
$langDirection = (bool) $lang->isRTL();

if ($langDirection == 1)
{
	$rtlLang = 1;
	$document->addStyleSheet(JURI::base() . 'components/com_contushdvideoshare/css/mod_stylesheet_rtl.min.css');
}
else
{
	$rtlLang = 0;
}

$dispenable = unserialize($result1[0]->dispenable);
$sidethumbview = unserialize($result1[0]->sidethumbview);
$seoOption = $dispenable['seo_option'];

if (JRequest::getVar('id'))
{
	$videoid = JRequest::getVar('id', '', 'get', 'int');
}
else
{
	$videoid = JRequest::getVar('video');
}

if (isset($videoid))
{
?>

	<div class="module_menu <?php echo $class; ?> module_videos">
		<!-- Code begin here for related videos in home page display  -->
		<div class="video-grid-container clearfix" >
			<?php
			$totalrecords = count($result);
			$j = 0;

			for ($i = 0; $i < $totalrecords; $i++)
			{
				if ($i == 0)
				{
					?>
					<ul class="ulvideo_thumb clearfix">
					<?php
				}

				if($sidethumbview['siderelatedvideocol'] == 0)
					$sidethumbview['siderelatedvideocol'] = 1;

				if (($i % $sidethumbview['siderelatedvideocol']) == 0 && $i != 0)
				{
				?>
					</ul>
					<ul class="ulvideo_thumb clearfix">
						<?php
				}

				if ($result[$i]->filepath == "File" || $result[$i]->filepath == "FFmpeg" || $result[$i]->filepath == "Embed")
				{
					if (isset($result[$i]->amazons3) && $result[$i]->amazons3 == 1)
					{
						$src_path = $dispenable['amazons3link'] . $result[$i]->thumburl;
					}
					else
					{
						$src_path = JURI::base() . "components/com_contushdvideoshare/videos/" . $result[$i]->thumburl;
					}
				}

				if ($result[$i]->filepath == "Url" || $result[$i]->filepath == "Youtube")
				{
					$src_path = $result[$i]->thumburl;
				}

					// For SEO settings
					if ($seoOption == 1)
					{
						$relatedCategoryVal = "category=" . $result[$i]->seo_category;
						$relatedVideoVal = "video=" . $result[$i]->seotitle;
					}
					else
					{
						$relatedCategoryVal = "catid=" . $result[$i]->catid;
						$relatedVideoVal = "id=" . $result[$i]->id;
					}
					?>
					<li class="video-item">
						<div class="mod_video_item">
							<a class=" info_hover featured_vidimg" rel="htmltooltip"
href="<?php echo JRoute::_(
		"index.php?Itemid=" . $Itemid . "&amp;option=com_contushdvideoshare&amp;view=player&amp;" . $relatedVideoVal . "&amp;"
		. $relatedCategoryVal
		); ?>" >
								<img class="yt-uix-hovercard-target" src="<?php echo $src_path; ?>"   title="" alt="thumb_image" /></a>
						</div>
						<div class="floatleft video-item-details">
							<div class="show-title-container title">
								<a 
href="<?php echo JRoute::_(
		"index.php?Itemid=" . $Itemid . "&amp;option=com_contushdvideoshare&amp;view=player&amp;" . $relatedVideoVal . "&amp;"
		. $relatedCategoryVal
		); ?>"
class="show-title-gray info_hover">
<?php
if (strlen($result[$i]->title) > 30)
{
	echo JHTML::_('string.truncate', ($result[$i]->title), 30);
}
else
{
	echo $result[$i]->title;
}
?>
								</a>
							</div>
							<?php
							if ($dispenable['ratingscontrol'] == 1)
							{
								if (isset($result[$i]->ratecount) && $result[$i]->ratecount != 0)
								{
									$ratestar = round($result[$i]->rate / $result[$i]->ratecount);
								}
								else
								{
									$ratestar = 0;
								}
								?>
								<div class="<?php echo $ratearray[$ratestar]; ?> floatleft"></div>

							<?php
							}
							?>
							<div class="clear"></div>
							<?php
							if ($dispenable['viewedconrtol'] == 1)
							{
							?>
								<span class="floatleft video-info">
									<?php
									echo JText::_('HDVS_VIEWS');
									?>: <?php
									echo $result[$i]->times_viewed;
									?> </span>
							</div>
							<?php
							}
						?>
					</li>
					<?php
					$j++;
			}
				?>
			</ul>
		</div>
	</div>
	<!--Tooltip Starts Here-->
	<?php
	for ($i = 0; $i < $totalrecords; $i++)
	{
		?>
		<div class="htmltooltip">
			<?php
			if ($result[$i]->description)
			{
				?>
				<p class="tooltip_discrip">
				<?php echo JHTML::_('string.truncate', (strip_tags($result[$i]->description)), 120); ?></p>
				<?php
			}
			?>
			<div class="tooltip_category_left">
				<span class="title_category"><?php echo JText::_('HDVS_CATEGORY'); ?>: </span>
				<span class="show_category"><?php echo $result[$i]->category; ?></span>
			</div>
			<?php
			if ($dispenable['viewedconrtol'] == 1)
			{
				?>
				<div class="tooltip_views_right">
					<span class="view_txt"><?php echo JText::_('HDVS_VIEWS'); ?>: </span>
					<span class="view_count"><?php echo $result[$i]->times_viewed; ?> </span>
				</div>
				<div id="htmltooltipwrapper6<?php echo $i; ?>">
					<div class="chat-bubble-arrow-border"></div>
					<div class="chat-bubble-arrow"></div>
				</div>
				<?php
			}
			?>
		</div>
		<?php
	}
	?>
	<!--Tooltip end Here-->
	<?php
	$t = count($result);

	if (JRequest::getVar('id'))
	{
		$video = 'id=' . JRequest::getVar('id');
	}
	else
	{
		$video = 'video=' . JRequest::getVar('video');
	}

	if ($t > 1)
	{
		?>
		<div class="clear"></div>
		<div class="morevideos">
			<a href="<?php
			echo JRoute::_("index.php?Itemid=" . $Itemid . "&amp;option=com_contushdvideoshare&view=relatedvideos&$video");
			?>" title="<?php
			echo JText::_('HDVS_MORE_VIDEOS');
			?>">
				<?php echo JText::_('HDVS_MORE_VIDEOS'); ?></a></div>
					<?php
	}
	?>
	<div class="clear"></div>
	<?php
}
else
{
	?>
	<span style="text-align: center; display: block;"><?php echo JText::_('HDVS_NO_RELATED_VIDEOS'); ?> </span>
	<?php
}
?>
<script type="text/javascript">
	jQuery.noConflict();
	jQuery(document).ready(function($) {
		jQuery(".ulvideo_thumb").mouseover(function() {
			htmltooltipCallback();
		});
	});

</script>

<!--Tooltip for video thumbs-->
<script type="text/javascript">
jQuery.noConflict();
window.onload = function()
{
htmltooltipCallback("htmltooltip", "",<?php echo $rtlLang; ?>);
htmltooltipCallback("htmltooltip1", "1",<?php echo $rtlLang; ?>);
htmltooltipCallback("htmltooltip2", "2",<?php echo $rtlLang; ?>);
}

jQuery(".ulvideo_thumb").mouseover(function() {
htmltooltipCallback("htmltooltip", "",<?php echo $rtlLang; ?>);
htmltooltipCallback("htmltooltip1", "1",<?php echo $rtlLang; ?>);
htmltooltipCallback("htmltooltip2", "2",<?php echo $rtlLang; ?>);
});
jQuery(document).click(function() {
htmltooltipCallback("htmltooltip", "",<?php echo $rtlLang; ?>);
htmltooltipCallback("htmltooltip1", "1",<?php echo $rtlLang; ?>);
htmltooltipCallback("htmltooltip2", "2",<?php echo $rtlLang; ?>);
});
</script>