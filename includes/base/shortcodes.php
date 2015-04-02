<?php
add_filter( 'the_content', 'wpautop' , 40);
add_filter( 'the_content', 'empty_paragraph_fix' , 20);
//add_filter('the_content', 'shortcode_empty_paragraph_fix', 12);
function shortcode_empty_paragraph_fix($content) {
	$array = array (
		'<p>['    => '[',
		']</p>'   => ']',
		']<br />' => ']',
	);
	$content = strtr($content, $array);
	return $content;
}
function empty_paragraph_fix($content){
	$find = array(
		'<p></div>','<p><div', '</div></p>','<br />','<p></p>', "</div>\n</p>\n<p>",'columns"></p>');
	$replace = array(
		'</div>','<div','</div>','','','</div>','columns">');
	$content = str_replace($find, $replace, $content);
	return $content;
}


function rowCode($atts, $content = null) {
extract(shortcode_atts(array('extra' => ''), $atts));
$extraOut = ($extra !== '' ? ' type-'.$extra : '' );
return '<div class="row'.$extraOut.'">' . do_shortcode($content) . '</div>';
}
add_shortcode('row', 'rowCode');

function colCode($atts, $content = null) {
	extract(shortcode_atts(array('span' => '', 'extra' => '', 'class' => ''), $atts));

	$extraInfo = ($extra !== '' ? ' '.$extra : '' );
	$extraClass = ($class !== '' ? ' '.$class : '' );
	$extraSpan = ($span !== '' ? " $span columns" : 'columns small-12 medium-6' );
	$finalClass = trim($extraInfo.$extraClass.$extraSpan);

	return '<div class="'.$finalClass.'">' . do_shortcode($content) . '</div>';
}
add_shortcode('col', 'colCode');

function largeCode($atts, $content = null) {
	extract(shortcode_atts(array('type' => '', ), $atts));
	return '<span class="text-larger">' . do_shortcode($content) . '</span>';
}
add_shortcode('large', 'largeCode');
add_shortcode('larger', 'largeCode');

function hrCode($atts){
	return "<div class='hr'>&nbsp;</div>";
}
add_shortcode('hr', 'hrCode');

/** Instructions:
 * [email]foo@bar.com[/email]
 * [email email='foo@bar.com']email me![/email]
 * [email email='foo@bar.com' data-update='false']<foo>Bar html -- this doesnt get touched by js </foo>[/email]
 **/
function emailCode($atts, $content = null) {
	/**
	 * @var $class $class
	 * @var $email $email
	 * @var $update_text $update_text
	 **/
	extract(shortcode_atts(array('email' => '', 'class'=> '', 'update_text' => true), $atts));

	$classes = "js-replacer-text $class";
	$classes = trim($classes);

	$toSplit = ($email !== '' ? $email : do_shortcode($content) );
	$outputContent = ($email !== '' ? do_shortcode($content) : '' );
	$splitVals = explode('@', $toSplit);
	$domain = array_pop($splitVals);
	$email = $splitVals[0];

	$dataTags = '';
	$dataTags .= ($domain !== '' ? " data-domain='$domain'" : '' );
	$dataTags .= ($email !== '' ? " data-extra='$email'" : '' );
	$dataTags .= ($update_text !== true ? " data-update='false'" : '' );
	$dataTags .= ($outputContent !== '' && $update_text === true ? " data-text='$outputContent'" : '' );

	$returnContent ="<a class='$classes' href='#' $dataTags>";
	if($update_text !== true ):
		$returnContent .= $outputContent;
	else:
		$returnContent .= _x('Please enable JavaScript', 'Titles', themeDomain());
	endif;
	$returnContent .="</a>";
	return $returnContent;
}
add_shortcode('email', 'emailCode');


function colorCode($atts, $content = null) {
	extract(shortcode_atts(array('color' => '', 'weight' => '', 'class' => ''), $atts));

	$extraColor = ($color !== '' ? ' color-'.$color : '' );
	$extraWeight = ($weight !== '' ? ' weight-'.$weight : '' );
	$extraClass = ($class !== '' ? ' '.$class : '' );

	return '<span class="'.$extraColor.$extraWeight.$extraClass.'">' . do_shortcode($content) . '</span>';
}
add_shortcode('color', 'colorCode');

function btnCode($atts, $content = null) {
	/**
	 * @var $class $class
	 * @var $link $link
	 **/
	extract(shortcode_atts(array('link' => '',  'class' => ''), $atts));
	$outbound = false;
	if (strpos($link, site_url()) === false):
		$outbound = true;
	endif;
	$url = ($link !== '' ? $link : '#' );
	$target = ($outbound == true ? ' target="_blank"' : '' );
	$extraClass = ($class === '' ? 'btn' : $class );
	return "<a href='$url'".$target." class='$extraClass'>".do_shortcode($content)."</a>";

}
add_shortcode('btn', 'btnCode');

function youtubeCode($atts, $content = null) {
	extract(shortcode_atts(array('embed' => '', 'height' => '', 'width' => '', 'vid' =>''), $atts));

	$embedID = ($embed !== '' ? $embed : false );
	$embedHeight = ($height !== '' ? $height: 390 );
	$embedWidth = ($width !== '' ? $width : 640 );
	$vid = ($vid !== '' ? $vid : $embedID );

	$dataHeight = $embedHeight;
	$dataWidth = $embedWidth;

	if($embedID){
		$varRet = "<div class='js-outer-size js-video-resize'>";
		$varRet .= "<iframe id='video-$vid' data-vidHeight='$dataHeight' data-vidWidth='$dataWidth' width='100%' src='//www.youtube.com/embed/$embedID' frameborder='0' webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>";
		$varRet .= "</div>";
		return $varRet;
	}
}
add_shortcode('youtube', 'youtubeCode');


function refCode($atts, $content = null) {
	extract(shortcode_atts(array('class' => ''), $atts));
	$returnValue = '';
	$addClass = '';

	$addClass .= ($class !== '' ? ' '.$class : '' );

	return "<span class='title$addClass'>".do_shortcode($content)."</span>";


}
add_shortcode('ref', 'refCode');