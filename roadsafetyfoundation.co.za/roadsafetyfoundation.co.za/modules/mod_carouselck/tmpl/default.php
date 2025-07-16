<?php
/**
 * @copyright	Copyright (C) 2014 Cedric KEIFLIN alias ced1870
 * http://www.joomlack.fr
 * http://www.template-creator.com
 * Module Carousel CK
 * @license		GNU/GPL
 * */
// no direct access
defined('_JEXEC') or die('Restricted access');

// définit la largeur du slideshow
$width = ($params->get('width') AND $params->get('width') != 'auto') ? ' style="width:' . $params->get('width') . 'px;"' : '';
?>
<!-- debut Carousel CK -->
<div class="carouselck<?php echo $params->get('moduleclass_sfx'); ?> carouselck_wrap <?php echo $params->get('skin'); ?>" id="carouselck_wrap_<?php echo $module->id; ?>"<?php echo $width; ?>>
	<?php
	foreach ($items as $item) {
		$datacaption = str_replace("|dq|", "\"", $item->imgcaption);
		if ($item->article) {
			$articletitletag = $params->get('articletitle', 'h3');
			$articlelink = $params->get('articlelink', 'readmore');
			if ($params->get('showarticletitle', '1') == '1') {
				$datacaption .= '<' . $articletitletag . ' class="carouselck_caption_articletitle">'
						. (($articlelink == 'title') ? '<a href="' . $item->article->link . '">' . $item->article->title . '</a>' : $item->article->title)
						. '</' . $articletitletag . '>';
			}
			$datacaption .= '<div class="carouselck_caption_articlecontent">' . $item->article->text;
			if ($articlelink == 'readmore') {
				$datacaption .= '<a href="' . $item->article->link . '">' . JText::_('COM_CONTENT_READ_MORE_TITLE') . '</a>';
			}
			$datacaption .= '</div>';
		}
		$imgtarget = ($item->imgtarget == 'default') ? $params->get('imagetarget') : $item->imgtarget;
		$datatitle = ($params->get('lightboxcaption', 'caption') != 'caption') ? 'data-title="' . htmlspecialchars(str_replace("\"", "&quot;", str_replace(">", "&gt;", str_replace("<", "&lt;", $datacaption)))) . '" ' : '';
		$datarel = ($imgtarget == 'lightbox') ? 'data-rel="lightbox" ' : '';
		?>
		<div class="carouselck" <?php echo $datarel; ?>data-thumb="<?php echo $item->imgthumb; ?>" data-src="<?php echo $item->imgname; ?>" <?php if ($item->imglink) echo 'data-link="' . $item->imglink . '" data-target="' . $item->imgtarget . '"'; ?>>
			<?php if ($item->imgvideo) { ?>
				<iframe src="<?php echo $item->imgvideo; ?>" width="100%" height="100%" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>
			<?php
			}
			if (($item->imgcaption || $item->article) && (($params->get('lightboxcaption', 'caption') != 'title' || $imgtarget != 'lightbox') || !$item->imglink)) {
					?>
					<div class="carouselck_caption <?php echo $params->get('captioneffect', 'moveFromBottom'); ?>">
						<div class="carouselck_caption_title">
							<?php echo str_replace("|dq|", "\"", $item->imgtitle); ?>
							<?php
							if ($item->article && $params->get('showarticletitle', '1') == '1') {
								if ($params->get('articlelink', 'readmore') == 'title')
									echo '<a href="' . $item->article->link . '">';
								echo $item->article->title;
								if ($params->get('articlelink', 'readmore') == 'title')
									echo '</a>';
							}
							?>
						</div>
						<div class="carouselck_caption_desc">
		<?php echo str_replace("|dq|", "\"", $item->imgcaption); ?>
					<?php
					if ($item->article) {
						echo $item->article->text;
						if ($params->get('articlelink', 'readmore') == 'readmore')
							echo '<a href="' . $item->article->link . '">' . JText::_('COM_CONTENT_READ_MORE_TITLE') . '</a>';
					}
					?>
						</div>
					</div>
		<?php
	} ?>
		</div>
<?php } ?>
</div>
<div style="clear:both;"></div>
<!-- fin Carousel CK -->
