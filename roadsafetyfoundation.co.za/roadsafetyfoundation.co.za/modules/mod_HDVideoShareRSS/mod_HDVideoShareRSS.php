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
defined ( '_JEXEC' ) or die ( 'Restricted access' );

// Include the model file
require_once (dirname ( __FILE__ ) . DS . 'helper.php');

$class 			= $params->get('moduleclass_sfx');
$rsstype		= $params->get ( 'rsstype' )->rsstype;
// echo "<pre>";print_r($params);exit;
$catid			= $params->get ( 'catid' )->catid;
$Itemid 		= Modvideosharerss::getmenuitemid_thumb();

// To display the html layout path
require JModuleHelper::getLayoutPath ( 'mod_HDVideoShareRSS' );
