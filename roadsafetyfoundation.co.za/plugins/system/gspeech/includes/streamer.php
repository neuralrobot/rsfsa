<?php
/**
* @version   $Id: streamer.php 20 2012-03-19 23:25:05Z simonpoghosyan@gmail.com $
* @package   GSpeech
* @copyright Copyright (C) 2008 - 2011 Edvard Ananyan, Simon Poghosyan. All rights reserved.
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

//if ($_SERVER['HTTP_REFERER'] != 'http://localhost/speech/speech.php?url=qaq')
//exit;
//echo 'http_host - '.$_SERVER['HTTP_HOST'];
//echo 'http_refferer - '.$_SERVER['HTTP_REFERER'];
//echo 'referer' . parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
//echo 'refferer= '.parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
//if(parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST) == $_SERVER['HTTP_HOST']) {

$txt = $_GET['q'];
$txt = strip_tags($txt);
$txt = preg_replace('/<script\b[^>]*>(.*?)<\/script>/si', "", $txt);
$txt = preg_replace('/<style\b[^>]*>(.*?)<\/style>/si', "", $txt);
$txt = str_replace(array("\"","'"),"",$txt);
$txt = str_replace("&nbsp;","",$txt);
$txt = urlencode($txt);

$lang = (string)$_GET['l'];
if($_GET['tr_tool'] == 'g') {
    $type = 'audio/mpeg';
    $url = 'http://translate.google.com/translate_tts?ie=UTF-8&q=' . $txt . '&tl=' . $lang;
}
elseif($_GET['tr_tool'] == 'm') {
   	$type = 'audio/mpeg';
    $url = 'http://api.microsofttranslator.com/v2/http.svc/Speak?language='.$lang.'&format=audio/mp3&options=MaxQuality&appid=T-gXTNZSpKRqLLMp-IJA_hfAbgvWdhczTRF_mA3U_1nM*&text='.$txt;
}

$ch = curl_init ($url) ;
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1) ;
$media = curl_exec($ch) ;
curl_close($ch) ;

$content_length = strlen($media);

header("Content-type: ".$type);
header("Content-length: ".$content_length);
echo $media;
?>