<?php
/**
 * RSS module custom fileds file for HD Video Share
 *
 * This file is to customize the fileds for RSS module
 *
 * @category   Apptha
 * @package    Mod_HDVideoShareRSS
 * @version    3.6
 * @author     Apptha Team <developers@contus.in>
 * @copyright  Copyright (C) 2014 Apptha. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');


jimport('joomla.html.html');

//imports for param fields
jimport('joomla.form.formfield');

/*
 * Class for playlist, videos form fields
 */
class JFormFieldHdvideoshare extends JFormField
{

	protected $type = 'hdvideoshare';
	
	//Function for input to playlist parameter
	function getInput() {
		return $this->fetchElement($this->element['name'], $this->value, $this->element, $this->name);
	}
	
	//Function to fetch playlist info from database
	function fetchElement($name, $value, &$node, $control_name)
	{
				
		$db = JFactory::getDBO();
		
		$rsstype = '';
		$style = 'display:block;';
		
		//query to fetch the playlist records
		$query = 'SELECT id, category AS name'
		. ' FROM #__hdflv_category'
		. ' WHERE published = 1'
		. ' ORDER BY category ASC';

		$db->setQuery( $query );
		$options = $db->loadObjectList();
		
		//Fetch module id 
                $moduleId=$get_id="";
                $get_id = JRequest::getVar('id');
                if(isset($get_id))
                $moduleId =  $get_id;
		
		//Check If module id available 
		if($moduleId != '')
		{
			//Fetch params from module table. 
			$qry = 'SELECT params from #__modules WHERE id='.$moduleId;
			$db->setQuery( $qry );
			$rs_params = $db->loadObject();
			
			$paramdecode = json_decode($rs_params->params, true);
			 
			$rsstype = $paramdecode['rsstype']['rsstype'];
			
			$style = 'display:block;';
			
			//If Video category 1, show playlists
			if($rsstype == '3')
			{

				echo "<style> #jformparamscatidcatid_chzn, #jform_params_catid-lbl { display:block;  }</style>";
			}
			else {
		echo "<style> #jformparamscatidcatid_chzn, #jform_params_catid-lbl { display:none;  }</style>";
                }


		}
               
		array_unshift($options, JHTML::_('select.option', '0', '- '.JText::_('Select Category').' -', 'id', 'name'));

		return JHTML::_('select.genericlist',  $options, ''.$control_name.'['.$name.']', 'class="inputbox" ', 'id', 'name', $value, $control_name.$name );
	}
}
