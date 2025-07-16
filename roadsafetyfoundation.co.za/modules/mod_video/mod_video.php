<?php
  /**
   *Magic Simple Video Player
   *This program is free software: you can redistribute it and/or modify it under the terms
   *of the GNU General Public License as published by the Free Software Foundation,
   *either version 3 of the License, or (at your option) any later version.
   *
   *This program is distributed in the hope that it will be useful,
   *but WITHOUT ANY WARRANTY; without even the implied warranty of
   *MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   *GNU General Public License for more details.
   *
   *You should have received a copy of the GNU General Public License
   *along with this program.  If not, see <http://www.gnu.org/licenses/>.
   *
   *@author Magic Point
   *@copyright (C) 2008 - 2011 Magic Point
   *@link http://www.magicpoint.org Official website
   **/
  defined('_JEXEC') or die('Restricted access!');
  $vscript = $params->get('vscript');
  $videowidth = 324;
  $videoheight = 340;
  
  $video01 = $params->get('video01');
  $videotitle01 = $params->get('videotitle01');
  $videothumb01 = $params->get('videothumb01');
  $videoimage01 = $params->get('videoimage01');
  $videosource01 = $params->get('videosource01');
  
  $video02 = $params->get('video02');
  $videotitle02 = $params->get('videotitle02');
  $videothumb02 = $params->get('videothumb02');
  $videoimage02 = $params->get('videoimage02');
  $videosource02 = $params->get('videosource02');
  
  $video03 = $params->get('video03');
  $videotitle03 = $params->get('videotitle03');
  $videothumb03 = $params->get('videothumb03');
  $videoimage03 = $params->get('videoimage03');
  $videosource03 = $params->get('videosource03');
  
  $video04 = $params->get('video04');
  $videotitle04 = $params->get('videotitle04');
  $videothumb04 = $params->get('videothumb04');
  $videoimage04 = $params->get('videoimage04');
  $videosource04 = $params->get('videosource04');
  
  $video05 = $params->get('video05');
  $videotitle05 = $params->get('videotitle05');
  $videothumb05 = $params->get('videothumb05');
  $videoimage05 = $params->get('videoimage05');
  $videosource05 = $params->get('videosource05');
  
  $video06 = $params->get('video06');
  $videotitle06 = $params->get('videotitle06');
  $videothumb06 = $params->get('videothumb06');
  $videoimage06 = $params->get('videoimage06');
  $videosource06 = $params->get('videosource06');
  
  $video07 = $params->get('video07');
  $videotitle07 = $params->get('videotitle07');
  $videothumb07 = $params->get('videothumb07');
  $videoimage07 = $params->get('videoimage07');
  $videosource07 = $params->get('videosource07');
  
  $video08 = $params->get('video08');
  $videotitle08 = $params->get('videotitle08');
  $videothumb08 = $params->get('videothumb08');
  $videoimage08 = $params->get('videoimage08');
  $videosource08 = $params->get('videosource08');
  
  $video09 = $params->get('video09');
  $videotitle09 = $params->get('videotitle09');
  $videothumb09 = $params->get('videothumb09');
  $videoimage09 = $params->get('videoimage09');
  $videosource09 = $params->get('videosource09');
  
  $video10 = $params->get('video10');
  $videotitle10 = $params->get('videotitle10');
  $videothumb10 = $params->get('videothumb10');
  $videoimage10 = $params->get('videoimage10');
  $videosource10 = $params->get('videosource10');
  
  //Debug Mode
  $debugMode = $params->get('debugMode');;
  if($debugMode==0) error_reporting(0); // Turn off all error reporting
  
  //head start
  global $mainframe;
  $videoreal = JURI::base();
  $document = &JFactory::getDocument();
  
  //head start
switch ($vscript) {
    case 'mod1':
        $jsswf_url = $RURL . "modules/mod_video/swfobject.js";
        $document->addScript($jsswf_url);
        break;
    case 'mod2':
        $jsswf_url = 'http://ajax.googleapis.com/ajax/libs/swfobject/2.1/swfobject.js';
        $document->addScript($jsswf_url);
        break;
    case 'mod3':
        $loadswf = '';
        break;
    case 'mod4':
        $compat = 'yes';
        break;
}
  
  //create XML
  $xmlfile = JPATH_BASE . "/modules/mod_video/video.xml";
  if (is_file($xmlfile)){
   unlink($xmlfile);
  }
  touch($xmlfile) or die("Unable to create: " . $xmlfile);
  $playlist = '<?xml version="1.0" encoding="utf-8"?>' . "\n";
  $playlist .= '<playlist>';
  
  		if ($video01 == '1') {$video01 = 'on';
	$playlist .= '<video title="' . $videotitle01 . '" thumb="' . $videothumb01 . '" image="' . $videoimage01 . '" source="' . $videosource01 . '"></video>';
		} //if ($video01 == '2')
  		else {$video02 = 'off';}
		
		if ($video02 == '1') {$video01 = 'on';
	$playlist .= '<video title="' . $videotitle02 . '" thumb="' . $videothumb02 . '" image="' . $videoimage02 . '" source="' . $videosource02 . '"></video>';
		} //if ($video02 == '2')
  		else {$video02= 'off';}
		
		if ($video03 == '1') {$video03 = 'on';
	$playlist .= '<video title="' . $videotitle03 . '" thumb="' . $videothumb03 . '" image="' . $videoimage03 . '" source="' . $videosource03 . '"></video>';
	} //if ($video03 == '2')
  		else {$video03 = 'off';}
		
	if ($video04 == '1') {$video04 = 'on';
	$playlist .= '<video title="' . $videotitle04 . '" thumb="' . $videothumb04 . '" image="' . $videoimage04 . '" source="' . $videosource04 . '"></video>';
	} //if ($video04 == '2')
  		else {$video04 = 'off';}
		
	if ($video05 == '1') {$video05 = 'on';
	$playlist .= '<video title="' . $videotitle05 . '" thumb="' . $videothumb05 . '" image="' . $videoimage05 . '" source="' . $videosource05 . '"></video>';
	} //if ($video05 == '2')
  		else {$video05 = 'off';}
		
	if ($video06 == '1') {$video06 = 'on';
	$playlist .= '<video title="' . $videotitle06 . '" thumb="' . $videothumb06 . '" image="' . $videoimage06 . '" source="' . $videosource06 . '"></video>';
	} //if ($video06 == '2')
  		else {$video06 = 'off';}
		
	if ($video07 == '1') {$video07 = 'on';
	$playlist .= '<video title="' . $videotitle07 . '" thumb="' . $videothumb07 . '" image="' . $videoimage07 . '" source="' . $videosource07 . '"></video>';
	} //if ($video07 == '2')
  		else {$video07 = 'off';}
		
	if ($video08 == '1') {$video08 = 'on';
	$playlist .= '<video title="' . $videotitle08 . '" thumb="' . $videothumb08 . '" image="' . $videoimage08 . '" source="' . $videosource08 . '"></video>';
	} //if ($video08 == '2')
  		else {$video08 = 'off';}
		
	if ($video09 == '1') {$video09 = 'on';
	$playlist .= '<video title="' . $videotitle09 . '" thumb="' . $videothumb09 . '" image="' . $videoimage09 . '" source="' . $videosource09 . '"></video>';
	} //if ($video09 == '2')
  		else {$video09 = 'off';}
		
	if ($video10 == '1') {$video10 = 'on';
	$playlist .= '<video title="' . $videotitle10 . '" thumb="' . $videothumb10 . '" image="' . $videoimage10 . '" source="' . $videosource10 . '"></video>';
	} //if ($video10 == '2')
  		else {$video10 = 'off';}
		
		
	$playlist .= '</playlist>';

  $handle = fopen($xmlfile, 'r+b') or die("Could not open file: " . $xmlfile . "\n");
  fwrite($handle, $playlist) or die("Could not write to file: " . $xmlfile . "\n");
  fclose($handle);
  chmod($xmlfile, 0777);
  $videornd = rand(250, 850);
  $videoflash = $videoreal . 'modules/mod_video/video.swf?' . $videornd;
  $videoid = 'video';
  if ($videosafe == 'yes') {
      $videooutput = "<div align=\"center\"><object classid=\"clsid:d27cdb6e-ae6d-11cf-96b8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6.0.65.0\" name=\"video_$videornd\" width=\"$videowidth\" height=\"$videoheight\" align=\"top\">
    <param name=\"src\" value=\"$videoflash\" />
    <param name=\"quality\" value=\"autohigh\" />
    <param name=\"salign\" value=\"l\" />
    <param name=\"flashvars\" value=\"videoid=$videoid\" />
    <param name=\"wmode\" value=\"transparent\" />
    <param name=\"name\" value=\"video_$videornd\" />
    <param name=\"align\" value=\"top\" />
    <param name=\"base\" value=\"$videoreal\" />
    <param name=\"bgcolor\" value=\"#ffffff\" />
    <param name=\"width\" value=\"$videowidth\" />
    <param name=\"height\" value=\"$videoheight\" />
    </object></div>";
  } //if ($videosafe == 'yes')
  else {
      $videooutput = "$loadswf<div id=\"video_$videornd\">Please update your <a href=\"http://get.adobe.com/flashplayer/\" target=\"_blank\">Flash Player</a> to view content.</div>
    <script type=\"text/javascript\">
    var flashvars = { videoid: \"$videoid\", align: \"center\", showVersionInfo: \"false\"};
    var params = { allowfullscreen: \"true\", wmode: \"transparent\", base: \"$videoreal\"};
    var attributes = {};
    swfobject.embedSWF(\"$videoflash\", \"video_$videornd\", \"$videowidth\", \"$videoheight\", \"9.0.0\", \"\", flashvars, params, attributes);
    </script>";
  } //else
    echo $videooutput ;  
	
 ?>