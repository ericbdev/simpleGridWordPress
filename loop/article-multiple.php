<?php
global $post;
$image = new image();
$postID = $post->ID;


$isSingle = (is_single() ? true : false);
?>
<div <?php post_class('single-article row collapse'.($isSingle ? ' is-single' : ''));?>>

	<?php
	$columnWidth = ' small-12 large-12';
	if(!$isSingle):

		if($image->featured_image()):
			$columnWidth = ' small-12 large-8';
			$featImg = $image->featured_image('sqr_450');
		?>
			<div class="column small-12 large-4">
				<div class="text-wrapper">
					<img src="<?php echo $featImg['image'][0][0];?>" alt="<?php echo $featImg['alt'];?>"/>
				</div>
			</div>
		<?
		endif;
	endif;
	?>
	<div class="columns<?php echo $columnWidth;?>">

		<?php

		$articleClass = 'sub-header general-category';

		echo "<h2 class='$articleClass'>".get_the_title($postID)."</h2>";

		//echo get_social_share();

		echo "<p>".get_custom_except(200, true)."</p>";
		echo "<p class='read-more-link'><a href='".get_permalink($post->ID)."'>".__('View the full article', theme_domain())."</a></p>";



		?>
	</div>
</div>