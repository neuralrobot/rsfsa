<?php
/**
 * RSS module for HD Video Share
 *
 * This file is to display Video Share RSS module 
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

$baseURL = JURI::base();
switch ($rsstype){
	case 0:
		$rssURL = JRoute::_('index.php?Itemid=' . $Itemid . '&amp;option=com_contushdvideoshare&view=rss&type=recent');
	default;
	break;
	case 1:
		$rssURL = JRoute::_('index.php?Itemid=' . $Itemid . '&amp;option=com_contushdvideoshare&view=rss&type=featured');
		break;
	case 2:
		$rssURL = JRoute::_('index.php?Itemid=' . $Itemid . '&amp;option=com_contushdvideoshare&view=rss&type=popular');
		break;
	case 3:
			$rssURL = JRoute::_('index.php?Itemid=' . $Itemid . '&amp;option=com_contushdvideoshare&view=rss&type=category&catid=' . $catid);
			break;
}
?>
<div class="module_menu <?php echo $class; ?> module_videos">
	<a href="<?php echo $rssURL; ?>" id="rssfeedicon" target="_blank"><img src="<?php echo $baseURL; ?>/components/com_contushdvideoshare/images/rss_button.png"></a>
</div>
