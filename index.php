<?php get_header();

if (have_posts()) : while (have_posts()) : the_post(); ?>
	<section class="wrapper main-content default">
		<div class="inner-wrapper small-padding">
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
	get_template_part('404');
endif;
get_footer();