    <?php
	
	//Mel: 02/07/19. To stop the display of "hidden" products (Catalog visibility: hidden)
	$exclude_from_catalog = array(
		'taxonomy'  => 'product_visibility',
		'terms'     => array('exclude-from-catalog'),
		'field'     => 'name',
		'operator'  => 'NOT IN',
	);
	array_push( $myticket_args['tax_query'], $exclude_from_catalog );
	//Mel: End
	
    $products = new WP_Query( $myticket_args );
    $i = 0;
    $currency_symbol = get_woocommerce_currency_symbol();
    if ( $products->have_posts() ) : ?>

        <?php while ( $products->have_posts() ) : $products->the_post(); $meta = get_post_meta( get_the_ID() ); $i++; ?>
			
			<!--Mel: 14/08/19. To get the min price for each simple and variable product
			<?php
				
				$product = wc_get_product( get_the_ID() );
				$price = $product->get_price(); 
			?>
			<!--End-->

            <?php $_regular_price = $meta['_regular_price'][0];
            if ( $_regular_price == '' ){ $_regular_price = '0'; } ?>

            <div class="search-result-item <?php if ( strlen( $meta['_sale_price'][0] ) > 0 ){ echo 'sale '; } if ( $meta['_stock_status'][0] == 'outofstock' ){ echo 'sold-out '; } ?>">
                <?php if ( strlen( $meta['_sale_price'][0] ) > 0 ){ ?><div class="ribbon"><span><?php esc_html_e('Sale', 'myticket'); ?></span></div><?php } ?> 
                <div class="row">
                    <div class="search-result-item-info col-sm-9">
                        <h3><?php the_title();?></h3>
                        <ul class="row">
                            <li class="col-sm-5 col-lg-6">
                                <span><?php esc_html_e('Venue', 'myticket'); ?></span>
                                <?php echo esc_attr( $meta['myticket_title'][0] ); ?>
                            </li>
                            <li class="col-sm-4 col-lg-3">
                                <span><?php echo date_i18n( "l", intval( $meta['myticket_datetime'][0] ) ); ?></span>
                                <?php echo date_i18n(  get_option( 'date_format' ), intval( $meta['myticket_datetime'][0] ) ); ?>
                            </li>
                            <li class="col-sm-3">
                                <span><?php esc_html_e('Time', 'myticket'); ?></span>
                                <?php echo date_i18n(  get_option( 'time_format' ), intval( $meta['myticket_datetime'][0] ) ); ?>
                            </li>
                        </ul>
                    </div>
                    <div class="search-result-item-price col-sm-3">
						<span><?php esc_html_e('Price From', 'myticket'); ?></span>
                        <?php if ( strlen( $meta['_sale_price'][0] ) > 0 ) : ?>
                            <strong><span><?php echo esc_html( $currency_symbol.$_regular_price ); ?></span><?php echo esc_html( $currency_symbol.$meta['_sale_price'][0] ); ?></strong>
                        <?php else: ?>
							<!--Mel: 14/08/19. Modify "Price From" to show min price from variable and simple product-->
                            <strong><?php echo esc_html( $currency_symbol.$price ); //Mel: 14/08/19 echo esc_html( $currency_symbol.$_regular_price ); ?></strong>
                        <?php endif; ?>

                        <?php $btn_text_arr = [];
                        $btn_text_arr[0] = esc_attr__( 'Book Ticket', 'myticket' );
                        $btn_text_arr[1] = esc_attr__( 'Sold Out', 'myticket' );
                        $btn_text_arr[2] = esc_attr__( 'View More', 'myticket' );
                        myticket_buy_btn( get_the_ID(), 'class="get-ticket"', $btn_text_arr ); ?>
                    </div>
                </div>
            </div>
        
        <?php endwhile; ?>
        <input type="hidden" id="myticket_post_count" value="<?php echo esc_attr($products->found_posts);?>">
        <input type="hidden" id="myticket_max_num_pages" value="<?php echo esc_attr($products->max_num_pages);?>">
        <input type="hidden" id="myticket_max_page_records" value="<?php echo esc_attr($products->query_vars['posts_per_page']); ?>">
        <input type="hidden" id="myticket_current_records" value="<?php echo esc_attr($i);?>">
        <input type="hidden" id="myticket_current_page" value="<?php echo max( 1, get_query_var('paged') );?>">
    <?php endif; ?>

    <?php if( $myticket_pagination ){ myticket_pagination_gallery( 'search-result-footer', $products, $myticket_pagenum_link ); } ?>   