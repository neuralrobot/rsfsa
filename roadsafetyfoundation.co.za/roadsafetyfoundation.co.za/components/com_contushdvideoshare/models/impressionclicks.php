<?php
/**
 * Model file to store Impression and Click count for HD Video Share
 *
 * This file is to get impression and click count of the ad
 * Impression count is based on how many times the ad is played on the player
 * It will be stored in database for get stats about the ad.
 * Click count is based on how many time user clicked the ad and redirected to the target URL
 *
 * @category   Apptha
 * @package    Com_Contushdvideoshare
 * @version    3.6
 * @author     Apptha Team <developers@contus.in>
 * @copyright  Copyright (C) 2014 Apptha. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */

// No direct acesss
defined('_JEXEC') or die('Restricted access');

// Import Joomla model library
jimport('joomla.application.component.model');

/**
 * Impressionclicks model class
 *
 * @package     Joomla.Contus_HD_Video_Share
 * @subpackage  Com_Contushdvideoshare
 * @since       1.5
 */
class Modelcontushdvideoshareimpressionclicks extends ContushdvideoshareModel
{
	/**
	 * Function to get & update the impression clicks to the Ads
	 * 
	 * @return  void
	 */
	public function impressionclicks()
	{
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		$click = JRequest::getVar('click', 'get', '', 'string');
		$id = JRequest::getVar('id', 'get', '', 'int');

		if ($click != 'click')
		{
			$query->update($db->quoteName('#__hdflv_ads'))
					->set($db->quoteName('clickcounts') . ' = clickcounts+1')
					->where($db->quoteName('id') . ' = ' . $db->quote($id));
		}
		else
		{
			$query->update($db->quoteName('#__hdflv_ads'))
					->set($db->quoteName('impressioncounts') . ' = impressioncounts+1')
					->where($db->quoteName('id') . ' = ' . $db->quote($id));
		}

		$db->setQuery($query);
		$db->query();
		exit();
	}
}
