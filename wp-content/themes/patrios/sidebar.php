<div id="sidebar" class="col-3" role="complementary">
    <aside class="widget-area">
        <?php 
	        if ( is_active_sidebar( 'sidebar' ) ) {
	        	dynamic_sidebar('sidebar');
	        }
         ?>
    </aside>
</div> <!-- #sidebar -->