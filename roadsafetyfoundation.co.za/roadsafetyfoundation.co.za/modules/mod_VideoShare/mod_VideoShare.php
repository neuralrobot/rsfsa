<?php
/**
 * HD Video Share Player module
 *
 * This file is to fetch details for Player module 
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

global $mainframe;

// Define DS
if (!defined('DS'))
{
	define('DS', DIRECTORY_SEPARATOR);
}

// Include the model file
require_once (dirname ( __FILE__ ) . DS . 'helper.php');

if (version_compare ( JVERSION, '1.7.0', 'ge' ))
{
	$version = '1.7';
}
else if (version_compare ( JVERSION, '1.6.0', 'ge' ))
{
	$version = '1.6';
}
else
{
	$version = '1.5';
}

if ($version != '1.5')
{
}
else
{
	$params = Modvideoshare::getvideoshareParam ();
}

$class 				= $params->get('moduleclass_sfx');
$width 				= $params->get ( 'player_width' );
$height 			= $params->get ( 'player_height' );
$showPlaylist 		= ($params->get ( 'showPlaylist' ) == 0) ? 'false' : 'true';
$fullscreen 		= ($params->get ( 'fullscreen' ) == 0) ? 'false' : 'true';
$share 				= ($params->get ( 'share' ) == 0) ? 'false' : 'true';
$timer 				= ($params->get ( 'timer' ) == 0) ? 'false' : 'true';
$zoom 				= ($params->get ( 'zoom' ) == 0) ? 'false' : 'true';
$volume 			= ($params->get ( 'volume' ) == 0) ? 'false' : 'true';
$playlistopen 		= ($params->get ( 'playlistOpen' ) == 0) ? 'false' : 'true';
$skinhide 			= ($params->get ( 'skinAutohide' ) == 0) ? 'false' : 'true';
$autoplay 			= ($params->get ( 'autoplay' ) == 0) ? 'false' : 'true';

// Get the video details
$videoList 		= Modvideoshare::getVideoListDetails ();
$player_icons 	= Modvideoshare::getplayersettings ();
$dispenable 	= Modvideoshare::getdispenable ();

// Player settings
$videoId 		= $videoList->id;
$playsettings 	= '&id=' . $videoId . '&autoplay=' . $autoplay . '&playlist_open=' . $playlistopen . '&skin_autohide=' . $skinhide . '&showPlaylist=' . $showPlaylist . '&timer=' . $timer . '&shareIcon=' . $share . '&fullscreen=' . $fullscreen . '&zoomIcon=' . $zoom . '&volumecontrol=' . $volume;

// To display the html layout path
require JModuleHelper::getLayoutPath ( 'mod_VideoShare' );
