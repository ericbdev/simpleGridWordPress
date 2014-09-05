<?php
add_filter('the_content', 'shortcode_empty_paragraph_fix');
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

function emailCode($atts, $content = null) {
extract(shortcode_atts(array('domain' => '', 'email' => '', 'text' => '', 'class' => ''), $atts));
	$returnValue = '';
	$addClass = '';


	/*$domain =
	$email =
	$text = */

	$returnValue .= ($domain !== '' ? " data-domain='$domain'" : '' );
	$returnValue .= ($email !== '' ? " data-extra='$email'" : '' );
	$returnValue .= ($text !== '' ? " data-text='$text'" : '' );
	$addClass .= ($class !== '' ? ' '.$class : '' );


return "<a class='js-replacer-text$addClass' href='#' $returnValue>"._x('Please enable JavaScript', 'Titles', 'Stack8' )."</a>";
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
	$extraClass = ($class !== '' ? ' ' : ' class="'.$class.' featured"' );
	return "<a href='$url'".$outbound." class='btn featured'>".do_shortcode($content)."</a>";

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