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
jimport('joomla.form.formfield');
jimport( 'joomla.html.html.select' );

/*
 * JForm Class for Video Category list field
 */
class JFormFieldRsstype extends JFormField {

    protected $type = 'Rsstype';

    function getInput() {
        return $this->fetchElement($this->element['name'], $this->value, $this->element, $this->name);
    }
	
    //Function to fetch videos from table and display in module parameter
    function fetchElement($name, $value, &$node, $control_name) {
       
         $options = array('id','title');
                $options[0]='recent';
                $options[0]='Recent Videos';
                $options[1]='featured';
                $options[1]='Featured Videos';
                $options[2]='popular';
                $options[2]='Popular Videos';
                $options[3]='category';
                $options[3]='Category Videos';

        return JHTML::_('select.genericlist',  $options, ''.$control_name.'['.$name.']', 'class="inputbox"
            onchange=
            "javascript:if(document.getElementById(\'jformparamsrsstypersstype\').value == 3)
            {
            
            document.getElementById(\'jformparamscatidcatid_chzn\').style.display=\'block\';
            document.getElementById(\'jform_params_catid-lbl\').style.display=\'block\';
            }
            else
            {
            
            document.getElementById(\'jformparamscatidcatid_chzn\').style.display=\'none\';
            document.getElementById(\'jform_params_catid-lbl\').style.display=\'none\';
            };"' , 'id', 'title', $value, $control_name.$name );
    }

}
