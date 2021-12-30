<?php defined( 'ABSPATH' ) or exit;

/* Mel: This code has been extensively modified by me.
 *
 */

//load mpdf and WooCommerce variables
require_once MYTICKET_PATH . 'inc/mpdf/vendor/mpdf/mpdf/mpdf.php';

$mpdf                           = new mPDF(['debug' => true]);
$order                          = new WC_Order( $order_id );
$uploads                        = wp_get_upload_dir();
$ticketDir                      = $uploads['basedir']."/tickets";
$formatted_shipping_address     = $order->get_formatted_shipping_address();
$formatted_billing_address      = $order->get_formatted_billing_address();
$line_items                     = $order->get_items( 'line_item' );
$name                           = wc_get_order_item_meta($order_item_id, "name");
$file                           = $ticketDir."/".$order_id."_".$order_item_id.".pdf";

//Mel: 26/08/19. Grab the main theme color to match it with ticket 
$color                          = get_theme_mod( 'myticket_main_color' );
$_GET['item_id']				= (isset($_GET['item_id'])?$_GET['item_id']:"");
$count 							= 0; //Mel: 01/09/19. This variable is placed here so that it won't get reset to zero when the display_selection function runs recursively

//Grab the wallet data inside the form from themes/myticket/woocommerce/checkout/thankyou.php
$ticket_qr = $_POST['ticketQr'] ;
$address = esc_attr( $_POST['address'] );
$private_key = esc_attr( $_POST['privateKey'] );
$mnemonic_phrase = esc_attr( $_POST['mnemonicPhrase'] );
$first_name = esc_attr( $_POST['firstName'] );
$last_name = esc_attr( $_POST['lastName'] );	

//make sure that ticket directory exists
wp_mkdir_p($ticketDir);

###########################
## TICKET TEMPLATE START ##
###########################

ob_start();
?>

<div class="title">

	<div class="watermark">
		<h2 class="rubber-stamp"><?php echo esc_html__( 'Your Virtual Ticket', 'myticket-events' ); ?></h2>
	</div>
	
</div>
<br />
<br />

<?php
foreach ( $line_items as $item_id => $item ) {

if ($_GET['item_id'] == $item_id || $_GET['item_id'] == "") {
?>

<?php
	
	$product_id = $item->get_product_id();
	$product = $item->get_product();
	
	//Get the item's product extra metadata
	$product_extras = $item->get_meta('product_extras');
	
	//file_put_contents('C:/laragon/www/1krowd1st/wp-content/debug.log', print_r($product_extras, true) . "\n", FILE_APPEND);
	
	//in the metadata, see if there's a 'pewc_parent_product' which indicate that it's a child. So, if it's a child, it obviously has a parent 
	$parent_product_id = $product_extras['products']['pewc_parent_product'];
			
	//Use display parent product's info if there's a parent. Since the child only shows the seating area, we need to display parent's details like event name, date/time, venue and also selections by user
	if ($parent_product_id) {
		
		//$child_product = wc_get_order_item_meta( $item_id, 'product_extras' );
		
		//We assume that parent product id is one number lesser than the child product id. Thus, we minus $item_id with 1
		$parent_product_extras = wc_get_order_item_meta( $item_id - 1, 'product_extras' );
		
		//We do this checking to ensure that the correct parent is linked the correct child by checking to see if their ids are linked
		if ($parent_product_extras['product_id'] == $parent_product_id) {
			
			$parent_product = wc_get_product( $parent_product_id );
			
			$myticket_event = $parent_product->get_name();
			$myticket_time = get_post_meta( $parent_product_id, 'myticket_datetime_start', true);
			$myticket_time = date_i18n( get_option( 'date_format' )." | ".get_option( 'time_format' ), intval( $myticket_time ) );
			$myticket_venue = get_post_meta( $parent_product_id, 'myticket_title', true);
			$myticket_address = get_post_meta( $parent_product_id, 'myticket_address', true);
			
			//If the event is not a General Admission event and has seat numbers, display the seat number on the ticket. Seating is stored as a variation of the child product
			if ( $product->is_type('variation') ) {
				$variation_id = wc_get_order_item_meta( $item_id, '_variation_id', true );	//Each seat numbering is represented as variation id 
				$variation_value = get_the_excerpt($variation_id);	//Seat numbering is stored in posts table under post_excerpt column
			}

			if ( has_post_thumbnail( $parent_product_id ) ) {
				$attachment_ids[0] = get_post_thumbnail_id( $parent_product_id );
				$attachment = wp_get_attachment_image_src( $attachment_ids[0], 'thumbnail' ); 			
			}
			
			//Get event descriptions
			//$product_instance = wc_get_product($parent_product_id);
			$product_full_description = $parent_product->get_description();
			$product_short_description = $parent_product->get_short_description();
			
		}
				
	} else {
		
		$myticket_event = $item['name'];
		$myticket_time = $item['time'];
		//$myticket_time = date_i18n( get_option( 'date_format' )." | ".get_option( 'time_format' ), intval( $myticket_time ) );
		$myticket_venue = $item['venue'];
		$myticket_address = $item['address'];
		
		//If the event is not a General Admission event and has seat numbers, display the seat number on the ticket. Seating is stored as a variation of the product. Events with myticket seating maps do not use this
		if ( $product->is_type('variation') ) {
			$variation_id = wc_get_order_item_meta( $item_id, '_variation_id', true );	//Each seat numbering is represented as variation id 
			$variation_value = get_the_excerpt($variation_id);	//Seat numbering is stored in posts table under post_excerpt column
		} elseif ( $product->is_type('simple') ) {
			$zone = wc_get_order_item_meta( $item_id, 'zone', true );
			$row = wc_get_order_item_meta( $item_id, 'row', true );
			$seat = wc_get_order_item_meta( $item_id, 'seat', true );
		}

		if ( has_post_thumbnail( $product_id ) ) {
			$attachment_ids[0] = get_post_thumbnail_id( $product_id );
			$attachment = wp_get_attachment_image_src($attachment_ids[0], 'thumbnail' ); 				
		}
		
		//Get event descriptions
		//$product_instance = wc_get_product($product_id);
		$product_full_description = $product->get_description();
		$product_short_description = $product->get_short_description();
	
	}
		
?>
	<div class="product-img" style="background-image: url('<?php echo $attachment[0] ; ?>');"></div>

	<table class="new" style="background-image: url('<?php do_action('get_upload_dir', 'baseurl', '/2019/07/milky-way-1024x575.jpg'); ?>');">

		<tr>
			<td>
			</td>
		</tr>
		<tr>
			<td class="td-space">
			</td>
			<td>
				<table cellpadding="0" cellspacing="0">
					<thead>
					</thead>
					<tbody>
						<tr>
							<td class="ticket">
								<h1 class="event"><?php echo esc_attr( $myticket_event ); ?></h1>
							</td>
						</tr>
						<tr>
							<td>
								&nbsp;
							</td>
						</tr>
						<tr>
							<td class="ticket">
								<h3 class="address"><?php echo esc_attr( $myticket_venue ); ?></h3>
							</td>
						</tr>
						<tr>	
							<td class="ticket">
								<h3 class="address"></h3>
							</td>
						</tr>
						<tr>
							<td class="ticket">
								<h6 class="time"><?php echo esc_attr( $myticket_time ); ?></h6>
							</td>								
						</tr>
						<tr>	
							<td>
								&nbsp;
							</td>
						</tr>						
						<tr>
							<td class="button">
								<h6 class="time">
									<?php
									
										//Display the initial user selection, like section or zone
										if ($parent_product) {
											
											//Call the display_selection function below to display the initial user selection
											array_map("display_selection", $parent_product_extras); 
											
										} else {
											array_map("display_selection", $product_extras);
											
										}
										
										if ( !empty($parent_product) && !empty($variation_value) ) {
											echo " - " . $variation_value;  //Display row and seat number
											
										} else {
											echo $variation_value;  //Display row and seat number
											
										}
										
										//This is used for events with seating map. To check the conditions where zone, row or seat are mapped/selected and to output the right text
										if ( empty($zone) && empty($row) && !empty($seat) ) {
											echo "Seat: " . $seat;
											
										} elseif ( empty($zone) && !empty($row) && empty($seat) ) {
											echo "Row: " . $row;
											
										} elseif ( empty($zone) && !empty($row) && !empty($seat) ) {
											echo "Row: " . $row . " - Seat: " . $seat;
										
										} elseif ( !empty($zone) && empty($row) && empty($seat) ) {
											echo "Zone: " . $zone;
											
										} elseif ( !empty($zone) && empty($row) && !empty($seat) ) {
											echo "Zone: " . $zone . " - Seat: " . $seat;
											
										} elseif ( !empty($zone) && !empty($row) && empty($seat) ) {
											echo "Zone: " . $zone . " - Row: " . $row;
											
										} elseif ( !empty($zone) && !empty($row) && !empty($seat) ) {
											echo "Zone: " . $zone . " - Row: " . $row . " - Seat: " . $seat;
										}
										
									?>
								</h6>
							</td>
						</tr>
						<tr>	
							<td>
								<h1></h1>
							</td>
						</tr>	
					</tbody>
				</table>	
			</td>
			<td class="qr-box">
				<!--<barcode code="<?php //echo $ticket_qr ;?>" size="2.4" type="QR" error="L" disableborder="0" />-->
			</td>
			<td>
			</td>
		</tr>
		<tr>
			<td class="wallet-label"></td>
		</tr>
	</table>
	
	<!--
	<div class="qr">
		<?php echo esc_html__( 'Issued To:', 'myticket-events' ) . ' ' . $first_name . ' ' . $last_name; ?>
		<br />
		<br />
		<barcode code="<?php echo $ticket_qr; ?>" size="2.4" type="QR" error="L" disableborder="0" />
	</div>
	

<?php } ?>
<?php } ?>

<br />
<br />
<br />
<br />
<br />
<table cellpadding="0" cellspacing="0">
	<thead>
		<tr class="heading" bgcolor="<?php echo $color; ?>">
			<th>
				<?php echo esc_html__( 'Event', 'myticket-events' ); //echo esc_html__( 'Product', 'myticket-events' ); ?>
			</th>

			<th>
				<?php echo esc_html__( 'Qty', 'myticket-events' ); ?>
			</th>

			<th>
				<?php echo esc_html__( 'Price', 'myticket-events' ); ?>
			</th>
		</tr>
	</thead>
	<tbody>
	<?php
	
	foreach ( $line_items as $item_id => $item ) {

		if($_GET['item_id'] == $item_id || $_GET['item_id'] == "") {
			
			$product_id = $item->get_product_id();
			$product = $item->get_product();
			
			//Get the item's product extra metadata
			$product_extras = $item->get_meta('product_extras');
			
			//in the metadata, see if there's a 'pewc_parent_product' which indicate that it's a child. So, if it's a child, it obviously has a parent 
			$parent_product_id = $product_extras['products']['pewc_parent_product'];
					
			//Use display parent product's info if there's a parent. Since the child only shows the seating area, we need to display parent's details like event name, date/time, venue and also selections by user
			if ($parent_product_id) {
				
				//We assume that parent product id is one number lesser than the child product id. Thus, we minus $item_id with 1
				$parent_product_extras = wc_get_order_item_meta( $item_id - 1, 'product_extras' );
				
				//We do this checking to ensure that the correct parent is linked the correct child by checking to see if their ids are linked
				if ($parent_product_extras['product_id'] == $parent_product_id) {
					
					$parent_product = wc_get_product( $parent_product_id );
					
					$myticket_event = $parent_product->get_name();
					$myticket_time = get_post_meta( $parent_product_id, 'myticket_datetime_start', true);
					$myticket_time = date_i18n( get_option( 'date_format' )." | ".get_option( 'time_format' ), intval( $myticket_time ) );
					$myticket_venue = get_post_meta( $parent_product_id, 'myticket_title', true);
					$myticket_address = get_post_meta( $parent_product_id, 'myticket_address', true);
					
					//If the event is not a General Admission event and has seat numbers, display the seat number on the ticket. Seating is stored as a variation of the child product
					if ( $product->is_type('variation') ) {
						$variation_id = wc_get_order_item_meta( $item_id, '_variation_id', true );	//Each seat numbering is represented as variation id 
						$variation_value = get_the_excerpt($variation_id);	//Seat numbering is stored in posts table under post_excerpt column
					}
					
					//Get event descriptions
					$product_full_description = $parent_product->get_description();
					$product_short_description = $parent_product->get_short_description();
					
				}
						
			} else {
				
				$myticket_event = $item['name'];
				$myticket_time = $item['time'];
				$myticket_venue = $item['venue'];
				$myticket_address = $item['address'];
				
				//If the event is not a General Admission event and has seat numbers, display the seat number on the ticket. Seating is stored as a variation of the product
				if ( $product->is_type('variation') ) {
					$variation_id = wc_get_order_item_meta( $item_id, '_variation_id', true );	//Each seat numbering is represented as variation id 
					$variation_value = get_the_excerpt($variation_id);	//Seat numbering is stored in posts table under post_excerpt column
				}
				
				//Get event descriptions
				$product_full_description = $product->get_description();
				$product_short_description = $product->get_short_description();
			
			}
				
			?>
				<tr class="item">
					<td width="50%">
						<strong><?php echo esc_html( $myticket_event ); ?></strong>
						<br />
						<div> <?php //$arg['echo'] = false; echo apply_filters( 'woocommerce_order_item_name', $item['name'], $item ); ?> </div>
						<div> <?php
									
									//Display the initial user selection, like section or zone
									if ($parent_product) {
										array_map("display_selection", $parent_product_extras); 
										
									} else {
										array_map("display_selection", $product_extras);
										
									}
									
									if ( !empty($parent_product) && !empty($variation_value) ) {
										echo " - " . $variation_value;  //Display row and seat number
										
									} else {
										echo $variation_value;  //Display row and seat number
										
									}
									
									//This is used for events with seating map. To check the conditions where zone, row or seat are mapped/selected and to output the right text
									if ( empty($zone) && empty($row) && !empty($seat) ) {
										echo "Seat: " . $seat;
										
									} elseif ( empty($zone) && !empty($row) && empty($seat) ) {
										echo "Row: " . $row;
										
									} elseif ( empty($zone) && !empty($row) && !empty($seat) ) {
										echo "Row: " . $row . " - Seat: " . $seat;
									
									} elseif ( !empty($zone) && empty($row) && empty($seat) ) {
										echo "Zone: " . $zone;
										
									} elseif ( !empty($zone) && empty($row) && !empty($seat) ) {
										echo "Zone: " . $zone . " - Seat: " . $seat;
										
									} elseif ( !empty($zone) && !empty($row) && empty($seat) ) {
										echo "Zone: " . $zone . " - Row: " . $row;
										
									} elseif ( !empty($zone) && !empty($row) && !empty($seat) ) {
										echo "Zone: " . $zone . " - Row: " . $row . " - Seat: " . $seat;
									}
								?>
						</div>
						<?php
						printf( esc_html__( 'Date & Time: %s', 'myticket-events' ), esc_html($myticket_time) );
						printf( '<br />' );
						printf( esc_html__( 'Venue: %s', 'myticket-events' ), esc_html($myticket_venue) );
						printf( '<br />' );
						printf( esc_html__( 'Address: %s', 'myticket-events' ), esc_html($myticket_address) );
						printf( '<br />' );
						printf( '<br />' );
						printf( esc_html__( 'Order Date: %s', 'myticket-events' ), date_i18n( get_option('date_format'), strtotime($order->get_date_created()) ) );
						printf( '<br />' );
						printf( esc_html__( 'Order Number: %s', 'myticket-events' ), esc_html($order_id) );
						printf( '<br />' );
						//Mel: 06/07/19. To remove the lines containing "Sold By" and ticket download link
						/*do_action( 'woocommerce_order_item_meta_start', $item_id, $item, $order );

						$templater->wc_display_item_meta( $item, true );
						$templater->wc_display_item_downloads( $item, true );

						do_action( 'woocommerce_order_item_metaesc_html__nd', $item_id, $item, $order ); */
						
						?>
						<div> <?php //$arg['echo'] = false; echo str_replace( array('<p>','</p>'), array('',''), wc_display_item_meta( $item, $arg ) ); ?> </div>
					</td>

					<td>
						<?php echo esc_attr( $item['qty'] ); ?>
					</td>
					<td>
						<?php echo myticket_output_html( $order->get_formatted_line_subtotal( $item ) ); ?>
					</td>
				</tr>
		
	<?php } ?>
<?php } ?>

	</tbody>
</table>
<br />
<hr />
<br />
<br />
<!--Hide the blockchain wallet table if it is not used-->
<table cellpadding="0" cellspacing="0" class="wallet">
	<tr>
		<td class="wallet-label">
			<?php echo esc_html__( 'WALLET ADDRESS', 'myticket' ); ?> 
		</td>
		<td>
		</td>
		<td class="wallet-label">
			<?php echo esc_html__( 'PRIVATE KEY', 'myticket' ); ?> 
		</td>
	</tr>
	<tr>
		<td>
			<barcode code="<?php echo $address; ?>" size="1.5" type="QR" error="M" disableborder="0" />
		</td>
			<td>
				<h2><?php echo esc_html__( 'This is Your Virtual Ticket. Protect It', 'myticket' ); ?></h2>
				<?php echo esc_html__( 'This virtual ticket consists of a token stored inside a digital wallet in a secured blockchain.', 'myticket' ); ?>
			</td>
		<td>
			<barcode code="<?php echo $private_key; ?>" size="1.5" type="QR" error="M" disableborder="0" />
		</td>
	</tr>
	<tr>
		<td colspan="3" class="wallet-info">
		<?php printf( esc_html__( 'Wallet Address: %s', 'myticket' ), $address); ?>
		</td>
	</tr>
	<tr>
		<td colspan="3" class="wallet-info">
		<?php printf( esc_html__( 'Private Key: %s', 'myticket' ), $private_key); ?>
		</td>
	</tr>
	<tr>
		<td colspan="3" class="wallet-info">
		<?php printf( esc_html__( 'Mnemonic Phrase: %s', 'myticket' ), $mnemonic_phrase); ?>
		</td>
	</tr>
</table>
<br />
<br />
<!---Wallet HTML ends-->

<!--From Ticket General-->

<?php
$html = ob_get_contents();
ob_end_clean();

#########################
## TICKET TEMPLATE END ##
#########################


// Ajax urls 
/* $ajaxurl = '';
if( in_array('sitepress-multilingual-cms/sitepress.php', get_option('active_plugins')) ){
    $ajaxurl .= admin_url( 'admin-ajax.php?lang=' . ICL_LANGUAGE_CODE );
} else{
    $ajaxurl .= admin_url( 'admin-ajax.php');
}

$codeText = "myticket:".$order_id.",".$ajaxurl .",".$order_item_id.",0,0"; */

$header = '<!--mpdf
    <htmlpageheader name="header">
        <table width="100%" style="font-family: sans-serif;padding-left:20px;padding-top:190px;">
            <tr>
                <td width="50%" style="color:#111111;margin-top:150px;text-align:center;">
                    <barcode code="'.$codeText.'" size="1.3" type="QR" error="M" disableborder="1" class="barcode" />
                    <br/>
                    <br/>
                    <br/>
                    <span style="width:50px;font-weight:bold;font-size:20pt;text-align:center;">'.str_replace( ' ', '<br/>',  $name ).'</span><br />
                    <br />
                 </td>
                <td width="50%" style="text-align: right; vertical-align: top;">
       
                </td>
            </tr>
        </table>
    </htmlpageheader>
    
mpdf-->

<style>
    @page {
        margin-top: 0cm;
        margin-bottom: 0cm;
        margin-left: 0cm;
        margin-right: 0cm;
        footer: html_letterfooter2;
        background-color: pink;
        background-image: url("/wp-content/plugins/myticket-events/event-invoices/ticket-individual/background.jpg");
        background-repeat: no-repeat;
        background-size: cover; 
    }
  
    @page :first {
        margin-top: 8cm;
        margin-bottom: 4cm;
        header: html_header;
        footer: _blank;
        resetpagenum: 1;
        background-color: lightblue;
    }

</style>';

//Mel
$stylesheet = file_get_contents(__DIR__ . '/style.css'); // external css
$mpdf->img_dpi = 150;
$mpdf->WriteHTML($stylesheet, 1);
$mpdf->WriteHTML($html, 2);

//$mpdf->WriteHTML($header);

//print to file and return its path
if ($to_file){
    
    $mpdf->Output($file,'F');
    return $file;
    
//print to browser
}else{
    $mpdf->Output();
}




//Mel: 01/09/19. To display the selected options by the customer in the parent product
function display_selection($value) {
	
	global $count;
	
	//Check if it's array or not 
    if( is_array($value) ) {
		
        //Search array for the key that equals to "type" and then display the key "value" 
        if(array_key_exists("type", $value) && array_search("select", $value) == true) {
			$count++;
			
			if ( $count == 1 ) {
				echo $value['value'];
			
			} else {
				echo " - " . $value['value'];
			}
			
        }
        //if not found then call itself (recursively)
        elseif( is_array($value) ) {
            array_map("display_selection", $value);
        }

    }

}


?>