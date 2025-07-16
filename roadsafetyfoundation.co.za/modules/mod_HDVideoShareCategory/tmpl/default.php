<?php
/**
 * Category module for HD Video Share
 *
 * This file is to display the particular category as a module in the admin panel 
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
	$document->addStyleSheet(JURI::base() . 'components/com_contushdvideoshare/css/mod_stylesheet_rtl.min.min.css');
}
else
{
	$rtlLang = 0;
}

$dispenable = unserialize($result1[0]->dispenable);
$sidethumbview = unserialize($result1[0]->sidethumbview);
$seoOption = $dispenable['seo_option'];
?>

<div class="module_menu <?php echo $class; ?> module_videos">
	<!-- Code begin here for category videos in home page display  -->
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

			if($sidethumbview['sidecategoryvideocol'] == 0)
				$sidethumbview['sidecategoryvideocol'] = 1;

			if (($i % $sidethumbview['sidecategoryvideocol']) == 0 && $i != 0)
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
				$categoryCategoryVal = "category=" . $result[$i]->seo_category;
				$categoryVideoVal = "video=" . $result[$i]->seotitle;
			}
			else
			{
				$categoryCategoryVal = "catid=" . $result[$i]->catid;
				$categoryVideoVal = "id=" . $result[$i]->id;
			}
				?>
				<li class="video-item">
					<div class="mod_video_item">
						<a class=" info_hover featured_vidimg" rel="htmltooltip"
href="<?php echo JRoute::_("index.php?Itemid=" . $Itemid . "&amp;option=com_contushdvideoshare&amp;view=player&amp;" . $categoryVideoVal . "&amp;" . $categoryCategoryVal); ?>"
						 ><img class="yt-uix-hovercard-target" src="<?php echo $src_path; ?>"   title="" alt="thumb_image" /></a>
					</div>
					<div class="floatleft video-item-details">
						<div class="show-title-container title">
<a href="<?php
echo JRoute::_(
		"index.php?Itemid=" . $Itemid . "&amp;option=com_contushdvideoshare&amp;view=player&amp;"
		. $categoryVideoVal . "&amp;" . $categoryCategoryVal
		);
?>"
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
							?>
							<?php
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
							<span class="floatleft video-info"><?PHP echo JText::_('HDVS_VIEWS'); ?>: 
								<?php echo $result[$i]->times_viewed; ?> </span>
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
			<div id="htmltooltipwrapper3<?php echo $i; ?>">
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

if ($t > 1)
{
	// For SEO settings
	if ($seoOption == 1)
	{
		$CategoryVal = "&category=" . $result[0]->seo_category;
	}
	else
	{
		$CategoryVal = "&catid=" . $result[0]->id;
	}
?>
	<div class="clear"></div>
	<div class="morevideos">
<a href="<?php
echo JRoute::_("index.php?Itemid=" . $Itemid . "&amp;option=com_contushdvideoshare&view=category" . $CategoryVal);
?>" title="<?php
echo JText::_('HDVS_MORE_VIDEOS');
?>">
			<?php echo JText::_('HDVS_MORE_VIDEOS'); ?></a></div>
<?php
}
?>
<div class="clear"></div>
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
