</div><!-- /.row -->
</div><!-- /.container -->
</div><!-- /.content-wrapper -->
</div><!-- /.page-wrap -->
	
	<?php
		get_template_part('template-files/footer', 'widget');
	?>
    <footer class="site-footer">
		<div class="site-copyright container">
			<?php if (function_exists('site_copyright')){
				site_copyright();
			}else{?>
				<span><?php printf(__('%1$s', 'lawworx'), '<a href="http://themeworx.net/details/corporate/lawworx-pro" rel="designer">&copy; Themeworx</a>'); ?></span>
			<?php }?>
			<div class="to-top">
				<i class="fa fa-angle-double-up"></i>
			</div>
		</div>
    </footer><!-- /.site-footer -->
	
	<?php wp_footer(); ?> 
  </body>
</html>