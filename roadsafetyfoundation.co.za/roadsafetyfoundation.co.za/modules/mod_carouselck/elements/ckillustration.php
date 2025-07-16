<?php

/**
 * @copyright	Copyright (C) 2011 Cedric KEIFLIN alias ced1870
 * http://www.joomlack.fr
 * @license		GNU/GPL
 * */
// no direct access
defined('_JEXEC') or die('Restricted access');

class JFormFieldCkillustration extends JFormField {

    protected $type = 'ckillustration';

    protected function getInput() {
		$styles = $this->element['styles'];
		$html = '<img style="'.$styles.'" src="' . $this->getPathToElements() . '/images/' . $this->element['file'].'" />';
        return $html;
    }

    protected function getLabel() {
        return '';
    }
	
	protected function getPathToElements() {
        $localpath = dirname(__FILE__);
        $rootpath = JPATH_ROOT;
        $httppath = trim(JURI::root(), "/");
        $pathtoimages = str_replace("\\", "/", str_replace($rootpath, $httppath, $localpath));
        return $pathtoimages;
    }

}

