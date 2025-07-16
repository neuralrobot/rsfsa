<?php
/**
 * @name       Joomla HD Video Share
 * @SVN        3.5.1
 * @package    Com_Contushdvideoshare
 * @author     Apptha <assist@apptha.com>
 * @copyright  Copyright (C) 2014 Powered by Apptha
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @since      Joomla 1.5
 * @Creation Date   March 2010
 * @Modified Date   March 2014
 * */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// Import joomla model library
jimport('joomla.application.component.model');

/**
 * Admin site settings model class.
 *
 * @package     Joomla.Contus_HD_Video_Share
 * @subpackage  Com_Contushdvideoshare
 * @since       1.5
 */
class ContushdvideoshareModelsitesettings extends ContushdvideoshareModel
{
	/**
	 * Function to get site setting
	 * 
	 * @return  getsitesetting
	 */
	public function getsitesetting()
	{
		// Query to fetch site settings
		$db = $this->getDBO();
		$query = $db->getQuery(true);
		$query->clear()
				->select(array('id', 'published', 'thumbview', 'dispenable', 'homethumbview', 'sidethumbview'))
				->from('#__hdflv_site_settings')
				->where($db->quoteName('id') . ' = ' . $db->quote('1'));
		$db->setQuery($query);
		$settings = $db->loadObject();

		// Query to check jomcomment component exists
		if (version_compare(JVERSION, '1.6.0', 'ge'))
		{
			$query->clear()
					->select(array('COUNT(extension_id)'))
					->from('#__extensions')
					->where($db->quoteName('element') . ' = ' . $db->quote('com_jomcomment'))
					->where($db->quoteName('enabled') . ' = ' . $db->quote('1'));
		}
		else
		{
			$query->clear()
					->select(array('COUNT(extension_id)'))
					->from('#__components')
					->where($db->quoteName('option') . ' = ' . $db->quote('com_jomcomment'))
					->where($db->quoteName('enabled') . ' = ' . $db->quote('1'));
		}

		$db->setQuery($query);
		$jomcomment = $db->loadResult();

		// Query to check jcomments component exists
		if (version_compare(JVERSION, '1.6.0', 'ge'))
		{
			$query->clear()
					->select(array('COUNT(extension_id)'))
					->from('#__extensions')
					->where($db->quoteName('element') . ' = ' . $db->quote('com_jcomments'))
					->where($db->quoteName('enabled') . ' = ' . $db->quote('1'));
		}
		else
		{
			$query->clear()
					->select(array('COUNT(extension_id)'))
					->from('#__components')
					->where($db->quoteName('option') . ' = ' . $db->quote('com_jcomments'))
					->where($db->quoteName('enabled') . ' = ' . $db->quote('1'));
		}

		$db->setQuery($query);
		$jcomment = $db->loadResult();

		if (empty($settings))
		{
			JError::raiseError(500, 'detail with ID: ' . $id . ' not found.');
		}
		else
		{
			return array($settings, $jomcomment, $jcomment);
		}
	}

	/**
	 * Function to save site setting
	 * 
	 * @param   array  $arrFormData  site setting details
	 * 
	 * @return  savesitesettings
	 */
	public function savesitesettings($arrFormData)
	{
		$option = JRequest::getCmd('option');
		$upload_methods = '';
		$mainframe = JFactory::getApplication();

		// Get the object for site settings table.
		$objSitesettingsTable = & $this->getTable('sitesettings');
		$inc = 0;

		foreach ($arrFormData['upload_methods'] as $result)
		{
			if ((count($arrFormData['upload_methods']) - 1) == $inc)
			{
				$upload_methods .= $result;
			}
			else
			{
				$upload_methods .= $result . ',';
			}

			$inc++;
		}

		// Get thumbview details and serialize data
		$thumbview = array(
			'featurrow' => $arrFormData['featurrow'],
			'featurcol' => $arrFormData['featurcol'],
			'recentrow' => $arrFormData['recentrow'],
			'recentcol' => $arrFormData['recentcol'],
			'categoryrow' => $arrFormData['categoryrow'],
			'categorycol' => $arrFormData['categorycol'],
			'popularrow' => $arrFormData['popularrow'],
			'popularcol' => $arrFormData['popularcol'],
			'searchrow' => $arrFormData['searchrow'],
			'searchcol' => $arrFormData['searchcol'],
			'relatedrow' => $arrFormData['relatedrow'],
			'relatedcol' => $arrFormData['relatedcol'],
			'featurwidth' => $arrFormData['featurwidth'],
			'recentwidth' => $arrFormData['recentwidth'],
			'categorywidth' => $arrFormData['categorywidth'],
			'popularwidth' => $arrFormData['popularwidth'],
			'searchwidth' => $arrFormData['searchwidth'],
			'relatedwidth' => $arrFormData['relatedwidth'],
			'memberpagewidth' => $arrFormData['memberpagewidth'],
			'memberpagerow' => $arrFormData['memberpagerow'],
			'memberpagecol' => $arrFormData['memberpagecol'],
			'myvideorow' => $arrFormData['myvideorow'],
			'myvideocol' => $arrFormData['myvideocol'],
			'myvideowidth' => $arrFormData['myvideowidth']
		);
		$arrFormData['thumbview'] = serialize($thumbview);

		// Get home page thumb details and serialize data
		$homethumbview = array(
			'homepopularvideo' => $arrFormData['homepopularvideo'],
			'homepopularvideorow' => $arrFormData['homepopularvideorow'],
			'homepopularvideocol' => $arrFormData['homepopularvideocol'],
			'homefeaturedvideo' => $arrFormData['homefeaturedvideo'],
			'homefeaturedvideorow' => $arrFormData['homefeaturedvideorow'],
			'homefeaturedvideocol' => $arrFormData['homefeaturedvideocol'],
			'homerecentvideo' => $arrFormData['homerecentvideo'],
			'homerecentvideorow' => $arrFormData['homerecentvideorow'],
			'homerecentvideocol' => $arrFormData['homerecentvideocol'],
			'homepopularvideoorder' => $arrFormData['homepopularvideoorder'],
			'homefeaturedvideoorder' => $arrFormData['homefeaturedvideoorder'],
			'homerecentvideoorder' => $arrFormData['homerecentvideoorder'],
			'homefeaturedvideoorder' => $arrFormData['homefeaturedvideoorder'],
			'homepopularvideowidth' => $arrFormData['homepopularvideowidth'],
			'homefeaturedvideowidth' => $arrFormData['homefeaturedvideowidth'],
			'homerecentvideowidth' => $arrFormData['homerecentvideowidth'],
		);
		$arrFormData['homethumbview'] = serialize($homethumbview);

		// Get home page thumb details and serialize data
		$sidethumbview = array(
			'sidepopularvideorow' => $arrFormData['sidepopularvideorow'],
			'sidepopularvideocol' => $arrFormData['sidepopularvideocol'],
			'sidefeaturedvideorow' => $arrFormData['sidefeaturedvideorow'],
			'sidefeaturedvideocol' => $arrFormData['sidefeaturedvideocol'],
			'siderelatedvideorow' => $arrFormData['siderelatedvideorow'],
			'siderelatedvideocol' => $arrFormData['siderelatedvideocol'],
			'siderecentvideorow' => $arrFormData['siderecentvideorow'],
			'siderecentvideocol' => $arrFormData['siderecentvideocol'],
			'siderandomvideorow' => $arrFormData['siderandomvideorow'],
			'siderandomvideocol' => $arrFormData['siderandomvideocol'],
			'sidecategoryvideorow' => $arrFormData['sidecategoryvideorow'],
			'sidecategoryvideocol' => $arrFormData['sidecategoryvideocol']
		);
		$arrFormData['sidethumbview'] = serialize($sidethumbview);

		// Get thumbview details and serialize data
		$dispenable = array(
			'allowupload' => $arrFormData['allowupload'],
			'adminapprove' => $arrFormData['adminapprove'],
			'user_login' => $arrFormData['user_login'],
			'ratingscontrol' => $arrFormData['ratingscontrol'],
			'viewedconrtol' => $arrFormData['viewedconrtol'],
			'reportvideo' => $arrFormData['reportvideo'],
			'categoryplayer' => $arrFormData['categoryplayer'],
			'homeplayer' => $arrFormData['homeplayer'],
			'limitvideo' => $arrFormData['limitvideo'],
			'seo_option' => $arrFormData['seo_option'],
			'upload_methods' => $upload_methods,
			'language_settings' => 'English.php',
			'disqusapi' => $arrFormData['disqusapi'],
			'facebookapi' => $arrFormData['facebookapi'],
			'comment' => $arrFormData['comment'],
			'amazons3' => $arrFormData['amazons3'],
			'amazons3name' => $arrFormData['amazons3name'],
			'amazons3link' => $arrFormData['amazons3link'],
			'amazons3accesskey' => $arrFormData['amazons3accesskey'],
			'amazons3accesssecretkey_area' => $arrFormData['amazons3accesssecretkey_area'],
			'facebooklike' => $arrFormData['facebooklike'],
			'rssfeedicon' => $arrFormData['rssfeedicon']
		);
		$arrFormData['dispenable'] = serialize($dispenable);

		// Bind data to the table object.
		if (!$objSitesettingsTable->bind($arrFormData))
		{
			JError::raiseError(500, $objSitesettingsTable->getError());
		}

		// Check that the node data is valid.
		if (!$objSitesettingsTable->check())
		{
			JError::raiseError(500, $objSitesettingsTable->getError());
		}

		// Store the node in the database table.
		if (!$objSitesettingsTable->store())
		{
			JError::raiseError(500, $objSitesettingsTable->getError());
		}

		// Page redirect
		$link = 'index.php?option=' . $option . '&layout=sitesettings';
		$mainframe->redirect($link, 'Saved Successfully', 'message');
	}
}
