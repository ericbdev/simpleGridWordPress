<?php
get_header();

if (have_posts()) : while (have_posts()) : the_post(); ?>
	<section class="wrapper main-content default">
		<div class="inner-wrapper small-padding-left small-padding-right">
			<div class="row">
				<div class="columns small-12">
					<?php
					the_title('<h1>','</h1>');
					the_content();
					?>
				</div>
			</div>
		</div>
	</section>
<?php
endwhile;
else:
	?>
	<section class="wrapper main-content default 404">
		<div class="inner-wrapper small-padding-left small-padding-right">
			<div class="row">
				<div class="columns small-12">
					<?php get_template_part('loop/404-message');?>
				</div>
			</div>
		</div>
	</section>
	<?php
endif;
get_footer();