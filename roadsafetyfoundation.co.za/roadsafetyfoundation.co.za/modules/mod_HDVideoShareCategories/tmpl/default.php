<?php
/**
 * Categories module for HD Video Share
 *
 * This file is to display all the categories name in the module 
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

if (JRequest::getVar('option') != 'com_contushdvideoshare')
{
	$document = JFactory::getDocument();
	$document->addStyleSheet(JURI::base() . 'components/com_contushdvideoshare/css/mod_stylesheet.min.css');
}

$dispenable = unserialize($result_settings);
$seoOption = $dispenable['seo_option'];
?>
<div class="video_module module_menu <?php echo $class; ?> ">
	<ul class="menu">
		<?php
		if (count($result) > 0)
		{
			foreach ($result as $row)
			{
				$oriname = $row->category;

				// Category name changed here for seo url purpose
				$newrname = explode(' ', $oriname);
				$link = implode('-', $newrname);
				$link1 = explode('&', $link);
				$category = implode('and', $link1);

				$result1  = Modcategorylist::getparentcategory($row->id);

				// For SEO settings
				if ($seoOption == 1)
				{
					$featureCategoryVal = "category=" . $row->seo_category;
				}
				else
				{
					$featureCategoryVal = "catid=" . $row->id;
				}
				?>
				<li class="item27">
					<?php echo str_repeat('<span class="gi">|&mdash;</span>', $row->level) ?>	
					<a href="<?php 
						echo JRoute::_("index.php?option=com_contushdvideoshare&view=category&" . $featureCategoryVal);
						?>">
						<span><?php echo $row->category; ?></span></a>
				</li>
				<?php
			}
		}
		else
		{
			echo "<li class='hd_norecords_found'>No Category</li>";
		}
		?>
	</ul>
</div>
<div class="clear"></div>
