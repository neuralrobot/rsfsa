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

class Modvideoshare
{
	/**
	 * Function to featured video
	 *
	 * @return object
	 */
	public static function getVideoListDetails()
	{
		$db = JFactory::getDBO ();
		$query = $db->getQuery ( true );

		$query->select ( '*' )
		->from ( '#__hdflv_upload' )
		->where($db->quoteName('published') . ' = ' . $db->quote('1'))
		->where($db->quoteName('featured') . ' = ' . $db->quote('1'))
		->order($db->quoteName('id') . ' DESC');
		$db->setQuery ( $query, 0, 1 );
		$video = $db->loadObject ();

		if(empty($video))
		{
			$query->clear()
			->select ( '*' )
			->from ( '#__hdflv_upload' )
			->where($db->quoteName('published') . ' = ' . $db->quote('1'))
			->order($db->quoteName('id') . ' DESC');
			$db->setQuery ( $query, 0, 1 );
			$video = $db->loadObject ();
		}
		
		return $video;
	}
	
	/**
	 * Function to get category view settings
	 *
	 * @return string
	 */
	public static function getplayersettings() {
		$db = JFactory::getDBO ();
		$query = $db->getQuery ( true );
		
		$query->select ( 'player_icons' )->from ( '#__hdflv_player_settings' );
		$db->setQuery ( $query );
		$settingsrows = $db->loadResult ();
		
		return unserialize ( $settingsrows );
	}
	
	/**
	 * Function to get category view settings
	 *
	 * @return string
	 */
	public static function getdispenable() {
		$db = JFactory::getDBO ();
		$query = $db->getQuery ( true );
		
		// Query to get category view settings
		$query->select ( 'dispenable' )->from ( '#__hdflv_site_settings' );
		$db->setQuery ( $query );
		$rows = $db->loadResult ();
		
		return unserialize ( $rows );
	}

	/**
	 * Function to get video access level
	 *
	 * @return string
	 */
	public static function getaccesslevel($useraccess) {
		$db = JFactory::getDBO ();
		$user = JFactory::getUser ();
		$query = $db->getQuery ( true );
		
		if (version_compare ( JVERSION, '1.6.0', 'ge' )) {
			$uid = $user->get ( 'id' );
			
			if ($uid) {
				$query->select ( 'g.id AS group_id' )
				->from ( '#__usergroups AS g' )
				->leftJoin ( '#__user_usergroup_map AS map ON map.group_id = g.id' )
				->where ( 'map.user_id = ' . ( int ) $uid );
				$db->setQuery ( $query );
				$message = $db->loadObjectList ();
				
				foreach ( $message as $mess ) {
					$accessid [] = $mess->group_id;
				}
			} else {
				$accessid [] = 1;
			}
		} else {
			$accessid = $user->get ( 'aid' );
		}
		if (version_compare ( JVERSION, '1.6.0', 'ge' )) {
			$query = $db->getQuery ( true );
			
			if ($useraccess == 0) {
				$useraccess = 1;
			}
			
			$query->select ( 'rules as rule' )
			->from ( '#__viewlevels AS view' )
			->where ( 'id = ' . ( int ) $useraccess );
			$db->setQuery ( $query );
			$message = $db->loadResult ();
			$accessLevel = json_decode ( $message );
		}
		
		$member = "true";
		
		if (version_compare ( JVERSION, '1.6.0', 'ge' )) {
			$member = "false";
			
			foreach ( $accessLevel as $useracess ) {
				if (in_array ( "$useracess", $accessid ) || $useracess == 1) {
					$member = "true";
					break;
				}
			}
		} else {
			if ($useraccess != 0) {
				if ($accessid != $useraccess && $accessid != 2) {
					$member = "false";
				}
			}
		}
		
		return $member;
	}

	/**
	 * Function to get video share player modle parameters
	 *
	 * @return object
	 */
	public static function getvideoshareParam() {
		$filePath = dirname(__FILE__) . DS . 'params.ini';
		$content = file_get_contents ( $filePath );
		$paramVal = new JParameter ( $content );

		return $paramVal;
	}

	/**
	 * Function to increase view count of a video
	 *
	 * @param   int  $vid  Video id
	 *
	 * @return  videohitCount_function
	 */
	public static function videohitCount_mod_function($vid)
	{
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
	
		$query->clear()
		->update($db->quoteName('#__hdflv_upload'))
		->set($db->quoteName('times_viewed') . ' = 1+times_viewed')
		->where($db->quoteName('id') . ' = ' . $db->quote($vid));
		$db->setQuery($query);
		$db->query();
	}
}
