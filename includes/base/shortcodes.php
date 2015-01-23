<?php
add_filter( 'the_content', 'wpautop' , 20);
add_filter('the_content', 'shortcode_empty_paragraph_fix', 12);
function shortcode_empty_paragraph_fix($content) {
	$array = array (
		'<p>['    => '[',
		']</p>'   => ']',
		']<br />' => ']'
	);
	$content = strtr($content, $array);
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

$extraSpan = ($span !== '' ? " columns $span" : ' small-12 medium-6' );
$extraInfo = ($extra !== '' ? ' '.$extra : '' );
$extraClass = ($class !== '' ? ' '.$class : '' );

return '<div class="columns'.$extraSpan.$extraInfo.$extraClass.'">' . do_shortcode($content) . '</div>';
}
add_shortcode('col', 'colCode');

/** Instructions:
 * [email]foo@bar.com[/email]
 * [email email='foo@bar.com']email me![/email]
 **/
function emailCode($atts, $content = null) {
	/** @var $class $class */
	/** @var $email $email */
	extract(shortcode_atts(array('email' => '', 'class'=> ''), $atts));

	$classes = "js-replacer-text $class";
	$classes = trim($classes);

	$toSplit = ($email !== '' ? $email : do_shortcode($content) );
	$text = ($email !== '' ? do_shortcode($content) : '' );

	$splitVals = explode('@', $toSplit);
	$domain = array_pop($splitVals);
	$email = $splitVals[0];

	$dataTags = '';
	$dataTags .= ($domain !== '' ? " data-domain='$domain'" : '' );
	$dataTags .= ($email !== '' ? " data-extra='$email'" : '' );
	$dataTags .= ($text !== '' ? " data-text='$text'" : '' );

	$returnContent ="<a class='$classes' href='#' $dataTags>";
	$returnContent .= _x('Please enable JavaScript', 'Titles', themeDomain());
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
	extract(shortcode_atts(array('link' => '', 'outbound' => '', 'class' => ''), $atts));

	$url = ($link !== '' ? $link : '#' );
	$outbound = ($outbound == 'true' ? ' target="_blank"' : '' );
	$extraClass = ($class !== 'btn' ? ' ' : $class.' btn' );
	return "<a href='$url'".$outbound." class='$extraClass'>".do_shortcode($content)."</a>";

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