<?php
$taxQuery = $wp_query->queried_object;
$activeTerm = ($taxQuery ? $taxQuery->term_id : false);
$searchTax = 'category';
$terms = get_terms($searchTax, array('orderby' => 'id'));

$firstLink = '';
$firstTitle = '';
if($taxQuery){
	$firstLink = get_term_link($taxQuery);
	$firstTitle = $taxQuery->name;
}else{
	$firstLink = get_post_type_archive_link( 'posts' );
	$firstTitle = _x('View all','Links',theme_domain());
}

echo "<ul class='dropdown-select'>";
echo "<li><a href='#'>". _x('Categories:','Titles',theme_domain())."</a>";
echo "<ul>";

$archiveLink = get_blog_archive_url();
$archiveTitle = _x('View all','Links',theme_domain());
echo "<li><a href='$archiveLink'>$archiveTitle</a>";
$taxonomyUl = '';
foreach ( $terms as $term ) {
	$term_link = get_term_link( $term );
	if ( is_wp_error( $term_link ) ) {
		continue;
	}
	$classAdd = '';
	$classAdd = '';
	if($activeTerm == intval($term->term_id)){
		$classAdd .= " class='active'";
	}
	$taxonomyUl .=  '<li'.$classAdd.'><a href="' . esc_url( $term_link ) . '">' . $term->name . '</a></li>';

}
echo $taxonomyUl;
echo '</ul>';
echo '</li>';
echo '</ul>';