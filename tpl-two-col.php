<?php
/* Template Name: Two Columns */
get_header();
$prefix = get_the_prefix();
if (have_posts()) : while (have_posts()) : the_post();
	global $post;
	$postID = $post->ID;

	$leftCol = get_meta($postID, 'col_one');
	$rightCol = get_meta($postID, 'col_two');

	$colWidthOne = (get_meta($postID, 'col_width_one') == '' ? 4 : get_meta($postID, 'col_width_one'));
	$colWidthTwo = (get_meta($postID, 'col_width_two') == '' ? 8 : get_meta($postID, 'col_width_two'));


	$colSpanFull = 12;
	$colSpanLeft = intval($colWidthOne);
	$colSpanRightStart = intval($colWidthTwo);
	$colSpanRight = 0;


	if(($colSpanLeft+$colSpanRightStart) <= 12){
		$colSpanRight = $colSpanRightStart;
	}else{
		$colSpanRight = $colSpanFull - $colSpanLeft;
	}

	?>
	<section class="wrapper main-content default two-cols">
		<div class="inner-wrapper small-padding text-content">
			<div class="row">
				<div class="columns small-12 medium-<?php echo $colSpanLeft ;?>">
					<?php echo apply_filters('the_content', $leftCol);?>
				</div>
				<div class="columns small-12 medium-<?php echo $colSpanRight ;?>">
					<?php echo apply_filters('the_content', $rightCol);?>
				</div>
			</div>
		</div>
	</section>
<?php
endwhile; endif;
get_footer();