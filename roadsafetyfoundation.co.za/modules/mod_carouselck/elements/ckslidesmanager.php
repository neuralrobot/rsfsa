<?php

/**
 * @copyright	Copyright (C) 2011 Cedric KEIFLIN alias ced1870
 * http://www.joomlack.fr
 * @license		GNU/GPL
 * */
// no direct access
defined('_JEXEC') or die('Restricted access');

JText::script('MOD_CAROUSELCK_ADDSLIDE');
JText::script('MOD_CAROUSELCK_SELECTIMAGE');
JText::script('MOD_CAROUSELCK_CAPTION');
JText::script('MOD_CAROUSELCK_USETOSHOW');
JText::script('MOD_CAROUSELCK_IMAGE');
JText::script('MOD_CAROUSELCK_VIDEO');
JText::script('MOD_CAROUSELCK_IMAGEOPTIONS');
JText::script('MOD_CAROUSELCK_LINKOPTIONS');
JText::script('MOD_CAROUSELCK_VIDEOOPTIONS');
JText::script('MOD_CAROUSELCK_ALIGNEMENT_LABEL');
JText::script('MOD_CAROUSELCK_TOPLEFT');
JText::script('MOD_CAROUSELCK_TOPCENTER');
JText::script('MOD_CAROUSELCK_TOPRIGHT');
JText::script('MOD_CAROUSELCK_MIDDLELEFT');
JText::script('MOD_CAROUSELCK_CENTER');
JText::script('MOD_CAROUSELCK_MIDDLERIGHT');
JText::script('MOD_CAROUSELCK_BOTTOMLEFT');
JText::script('MOD_CAROUSELCK_BOTTOMCENTER');
JText::script('MOD_CAROUSELCK_BOTTOMRIGHT');
JText::script('MOD_CAROUSELCK_LINK');
JText::script('MOD_CAROUSELCK_TARGET');
JText::script('MOD_CAROUSELCK_SAMEWINDOW');
JText::script('MOD_CAROUSELCK_NEWWINDOW');
JText::script('MOD_CAROUSELCK_VIDEOURL');
JText::script('MOD_CAROUSELCK_REMOVE');
JText::script('MOD_CAROUSELCK_IMPORTFROMFOLDER');
JText::script('MOD_CAROUSELCK_ARTICLEOPTIONS');
JText::script('MOD_CAROUSELCK_SLIDETIME');



class JFormFieldCkslidesmanager extends JFormField {

    protected $type = 'ckslidesmanager';

    protected function getInput() {

        $document = JFactory::getDocument();
        $document->addScriptDeclaration("JURI='" . JURI::root() . "'");
        $path = 'modules/mod_carouselck/elements/ckslidesmanager/';
        JHTML::_('behavior.modal');
        JHTML::_('script', $path.'ckslidesmanager.js');
        JHTML::_('script', $path.'FancySortable.js');
        JHTML::_('stylesheet', $path.'ckslidesmanager.css');

        $html = '<input name="' . $this->name . '" id="ckslides" type="hidden" value="' . $this->value . '" />'
                . '<input name="ckaddslide" id="ckaddslide" type="button" value="' . JText::_('MOD_CAROUSELCK_ADDSLIDE') . '" onclick="javascript:addslideck();"/>'
                //. '<input name="ckaddslidesfromfolder" id="ckaddslidesfromfolder" type="button" value="' . JText::_('MOD_CAROUSELCK_ADDSLIDESFROMFOLDER') . '" onclick="javascript:addslidesfromfolderck($(\'ckfoldername\').value);"/>'
                //. '<input name="ckfoldername" id="ckfoldername" value="modules/mod_carouselck/slides" onclick=""/>'
                //.'<input name="ckaddfromfolder" id="ckaddfromfolder" type="button" value="Import from a folder" onclick="javascript:addfromfolderck();"/>'
                //.'<input name="ckstoreslide" id="ckstoreslide" type="button" value="Save" onclick="javascript:storeslideck();"/>'
                . '<ul id="ckslideslist" style="clear:both;"></ul>'
                . '<input name="ckaddslide" id="ckaddslide" type="button" value="' . JText::_('MOD_CAROUSELCK_ADDSLIDE') . '" onclick="javascript:addslideck();"/>';

        return $html;
    }

    protected function getPathToImages() {
        $localpath = dirname(__FILE__);
        $rootpath = JPATH_ROOT;
        $httppath = trim(JURI::root(), "/");
        $pathtoimages = str_replace("\\", "/", str_replace($rootpath, $httppath, $localpath));
        return $pathtoimages;
    }

    protected function getLabel() {

        return '';
    }

    protected function getArticlesList() {
        $db = & JFactory::getDBO();

        $query = "SELECT id, title FROM #__content WHERE state = 1 LIMIT 2;";
        $db->setQuery($query);
        $row = $db->loadObjectList('id');
//        var_dump($row);
        return json_encode($row);
    }

}

