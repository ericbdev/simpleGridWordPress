<?php
global $post;
$image = new image();
$postID = $post->ID;


$isSingle = (is_single() ? true : false);
?>
<div <?php post_class('single-article row collapse'.($isSingle ? ' is-single' : ''));?>>
	<div class="columns<?php echo $columnWidth;?>">
		<div class="row collapse">
			<div class="columns small-12">
				<?php

				$articleClass = 'sub-header general-category';


				echo "<h2 class='$articleClass'>".get_the_title($postID)."</h2>";
				//echo get_social_share();?>
			</div>
		</div>
		<div class="row collapse">
			<div class="columns small-12">
				<?php
				if($image->featured_image()):
					$featImg = $image->featured_image('sqr_450');
					echo "<div class='columns small-4 featured-image'><img class='alignleft' src='{$featImg['image'][0][0]}' alt='{$featImg['alt']}'/></div>";
				endif;
				the_content();
				?>
			</div>
		</div>

	</div>
</div>
