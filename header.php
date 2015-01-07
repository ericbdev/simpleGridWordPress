<!doctype html>
<!--[if lt IE 7 ]>
<html class="ie ie6 no-js" lang="<?php echo get_lang_active();?>"> <![endif]-->
<!--[if IE 7 ]>
<html class="ie ie7 no-js" lang="<?php echo get_lang_active();?>"> <![endif]-->
<!--[if IE 8 ]>
<html class="ie ie8 no-js" lang="<?php echo get_lang_active();?>"> <![endif]-->
<!--[if IE 9 ]>
<html class="ie ie9 no-js" lang="<?php echo get_lang_active();?>"> <![endif]-->
<!--[if gt IE 9]><!-->
<html class="no-js" lang="<?= get_lang_active(); ?>"><!--<![endif]-->
<!-- the "no-js" class is for Modernizr. -->
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1"/>
    <title><?php wp_title('|'); ?></title>
	<?php /*<link rel="shortcut icon" type="image/x-icon"       href="<?php echo get_theme_path();?>images/ico/favicon.ico" />
	<link rel="shortcut icon" type="image/png"          href="<?php echo get_theme_path();?>images/ico/favicon.png" />
	<link rel="apple-touch-icon"                        href="<?php echo get_theme_path();?>/images/ico/apple-touch-icon.png" />
	<link rel="apple-touch-icon" sizes="57x57"          href="<?php echo get_theme_path();?>/images/ico/apple-touch-icon-57x57.png" />
	<link rel="apple-touch-icon" sizes="72x72"          href="<?php echo get_theme_path();?>/images/ico/apple-touch-icon-72x72.png" />
	<link rel="apple-touch-icon" sizes="76x76"          href="<?php echo get_theme_path();?>/images/ico/apple-touch-icon-76x76.png" />
	<link rel="apple-touch-icon" sizes="114x114"        href="<?php echo get_theme_path();?>/images/ico/apple-touch-icon-114x114.png" />
	<link rel="apple-touch-icon" sizes="120x120"        href="<?php echo get_theme_path();?>/images/ico/apple-touch-icon-120x120.png" />
	<link rel="apple-touch-icon" sizes="144x144"        href="<?php echo get_theme_path();?>/images/ico/apple-touch-icon-144x144.png" />
	<link rel="apple-touch-icon" sizes="152x152"        href="<?php echo get_theme_path();?>/images/ico/apple-touch-icon-152x152.png" />*/?>
    <?php
    wp_head();?>
</head>

<body <?php body_class(get_lang_active()); ?>>

<div class="site" id="site-wrapper">
<section class="wrapper main-header">
	<div class="show-for-large-up row">
		<nav class="columns small-12 main-menu ">
			<ul class="header-nav">
				<?php
				$defaults = array(
					'theme_location' => 'header-menu-desktop',
					'container'      => false,
					//'container_id'    => 'side-nav',
					//'menu_class'     => 'menu',
					//'menu_id'         => 'header-menu',
					'echo'           => true,
					'fallback_cb'    => 'wp_page_menu',
					'before'         => '',
					'after'          => '',
					'link_before'    => '',
					'link_after'     => '',
					'items_wrap'     => '%3$s',
					'walker'        => new navWalker
				);
				wp_nav_menu($defaults);
				?>
			</ul>
		</nav>
	</div>
	<div class="hide-for-large-up">
		<div class="inner-wrapper">
			<div class="row small-padding medium-padding">
				<div class="columns small-4">
					<a href="#" id="btn-mobile-nav" class="inactive">
					<img class="top" src="<?php echo get_theme_path();?>/images/icon-btn-nav-off.png" alt="Menu"/>
					<img class="bottom" src="<?php echo get_theme_path();?>/images/icon-btn-nav-on.png" alt="Menu"/>
					</a>
				</div>

				<div class="columns small-8">
					<a href="<?php echo (function_exists('icl_get_home_url') ? icl_get_home_url() :  get_permalink(get_id('home'))); ?>" class="logo-header float-right">
						<img src="<?php echo get_theme_path();?>/images/logo-placeholder.png" alt="<?php bloginfo('name'); ?>"/>
					</a>
				</div>
			</div>
		</div>
	</div>
</section>
<div id="mobile-nav" class="">
	<nav class="mobile-nav">
		<ul class="mobile-menu">
			<?php
			wp_nav_menu($defaults);
			?>
		</ul>
	</nav>
</div>

<script type="text/javascript">
	var menuButtonName = $('#btn-mobile-nav');
	menuButtonName.sidr({
		name: 'mobile-nav',
		source: '#mobile-nav',
		renaming : false,
		displace: true,
		onOpen: function(){
			menuButtonName.toggleClass('inactive');
			menuButtonName.toggleClass('active');
		},
		onClose: function(){
			menuButtonName.toggleClass('active');
			menuButtonName.toggleClass('inactive');
		}
	});
</script>
