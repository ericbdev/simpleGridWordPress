<?php
get_header();
$prefix = get_the_prefix();
/**
 * TODO: FIX THE ARCHIVE TO USE LESS GLOBALS, AND USE get_template_part PROPERLY
 */
?>
	<section class="wrapper main-content block">
		<div class="inner-wrapper small-padding bg-change hide-for-large-up">
			<div class="row archive-header articles-header">
				<div class="columns small-12 large-6">
					<div class="table-wrapper">
						<?php
						//get_template_part('loop/article', 'top');
						include 'loop/article-top.php';
						?>
					</div>
				</div>
				<div class="columns small-12 large-6">
					<?php get_search_form( true ); ?>
				</div>
			</div>
		</div>
		<div class="inner-wrapper small-padding">
			<div class="row loop-articles">
				<div class="columns small-12 large-8">
					<?php
					if (have_posts()) : while (have_posts()) : the_post();
						if(is_single()){
							include 'loop/article-single.php';
						}else{
							include 'loop/article-multiple.php';
						}
					endwhile;
					else:
						get_template_part('loop/404-message');
					endif;
					if(!is_single()){
						get_pagination($wp_query->max_num_pages);
					}else{
						?>
						<nav class="row archive-navigation collapse" role="navigation">
							<div class="columns small-5 nav-previous">
								<a href="<?php echo get_blog_archive_url();?>"><?php _ex('Back to articles', 'Titles', theme_domain());?></a>
							</div>
						</nav>
					<?php
					}
					?>
				</div>
				<?php get_articles_side_bar($taxonomyUl);?>
			</div>
		</div>
	</section>
<?php get_footer();