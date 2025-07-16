<?php
/**
 * Recent module for HD Video Share
 *
 * This file is to fetch Recent videos module details 
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

// Define DS
if (!defined('DS'))
{
	define('DS', DIRECTORY_SEPARATOR);
}

require_once dirname(__FILE__) . DS . 'helper.php';
$document = JFactory::getDocument();

if (version_compare(JVERSION, '1.7.0', 'ge'))
{
	$version = '1.7';
}
elseif (version_compare(JVERSION, '1.6.0', 'ge'))
{
	$version = '1.6';
}
else
{
	$version = '1.5';
	$document->addStyleSheet(JURI::base() . 'components/com_contushdvideoshare/css/tool_tip_15.css');
}

if (version_compare(JVERSION, '1.6.0', 'ge'))
{
	$jlang = JFactory::getLanguage();
	$jlang->load('mod_HDVideoShareRecent', JPATH_SITE, $jlang->get('tag'), true);
	$jlang->load('mod_HDVideoShareRecent', JPATH_SITE, null, true);
}

$class = $params->get('moduleclass_sfx');
$result = Modrecentvideos::getrecentvideos();
$result1 = Modrecentvideos::getrecentvideossettings();
$Itemid = Modrecentvideos::getmenuitemid_thumb();
require JModuleHelper::getLayoutPath('mod_HDVideoShareRecent');
