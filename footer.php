<div class="bottom-push">&nbsp;</div>
</div>
<section class="wrapper main-footer" id="footer-wrapper">
	<div class="row">

	</div>
</section>
<div id="mobile-nav" class="sidr left" style="display:none;left:-260px;">
	<nav class="mobile-nav">
		<ul class="mobile-menu">
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
<?php
wp_footer();?>
</body>

</html>
