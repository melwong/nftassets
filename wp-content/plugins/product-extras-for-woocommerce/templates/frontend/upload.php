<?php
/**
 * A text field template
 * @since 2.0.0
 * @package WooCommerce Product Add-Ons Ultimate
 */

// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) {
	exit;
}

// echo pewc_field_label( $item, $id );

$can_upload = pewc_can_upload();

if( ! $can_upload ) {

	echo $open_td;
	printf(
		'<p>%s</p>',
		apply_filters( 'pewc_filter_not_permitted_message', __( 'You need to be logged in to upload files', 'pewc' ) )
	);
	do_action( 'pewc_after_not_permitted_message' );
	echo $close_td;

} else {

	echo $open_td;
	$allow_multiples = ! empty( $item['multiple_uploads' ] ) ? 'multiple' : '';
	$multiply_price = ! empty( $item['multiply_price' ] ) ? '1' : '0';
	$allow_multiples = apply_filters( 'pewc_allow_multiple_file_upload', $allow_multiples, $post_id, $id );

	if( pewc_enable_ajax_upload() == 'yes' ) {

		$accepted_files = array();
		$permitted_mimes = pewc_get_pretty_permitted_mimes();
		$permitted_mimes = explode( ' ', $permitted_mimes );
		foreach( $permitted_mimes as $file_type ) {
			$accepted_files[] = '.' . $file_type;
		}
		$accepted_files = join( ', ', $accepted_files );
		$max_file_size = pewc_get_max_upload();
		$max_files = ! empty( $item['max_files'] ) ? absint( $item['max_files'] ) : 1; ?>

		<div class="dropzone" id="dz_<?php echo esc_attr( $id ); ?>"></div>
		<input type="hidden" id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $id ); ?>[]" value="<?php echo esc_attr( $id ); ?>">
		<input type="hidden" name="pewc_file_data[<?php echo $item['field_id']; ?>]" id="<?php echo esc_attr( $id ); ?>_file_data" value="">
		<input type="hidden" class="pewc-form-field pewc-number-uploads" name="<?php echo esc_attr( $id ); ?>_number_uploads" id="<?php echo esc_attr( $id ); ?>_number_uploads" value="">
		<input type="hidden" name="<?php echo esc_attr( $id ); ?>_multiply_price" id="<?php echo esc_attr( $id ); ?>_multiply_price" value="<?php echo esc_attr( $multiply_price ); ?>">
		<input type="hidden" name="<?php echo esc_attr( $id ); ?>_base_price" id="<?php echo esc_attr( $id ); ?>_base_price" value="<?php echo esc_attr( $price ); ?>">

		<script>
			jQuery(document).ready(function( $ ) {
				var ajaxUrl = pewc_vars.ajaxurl;
				$( '#dz_<?php echo esc_attr( $id ); ?>' ).dropzone({
					previewTemplate: document.querySelector('#tpl').innerHTML,
					url: ajaxUrl,
					acceptedFiles: "<?php echo esc_attr( $accepted_files ); ?>",
					maxFiles: <?php echo absint( $max_files ); ?>,
					uploadMultiple: true,
					maxFilesize: <?php echo esc_attr( $max_file_size ); ?>,
					thumbnailWidth: 100,
					thumbnailHeight: 100,
					addRemoveLinks: true,
					// chunking: true,
					init: function() {
						this.on( 'sendingmultiple', function( file, xhr, formData ) {
							formData.append( 'action', 'pewc_dropzone_upload' );
							formData.append( 'pewc_file_upload', $( '#pewc_file_upload' ).val() );
					    formData.append( 'field_id', '<?php echo $item['field_id']; ?>' );
							formData.append( 'file_data', $( '#<?php echo esc_attr( $id ); ?>_file_data' ).val() );
						});
						this.on( 'successmultiple', function( file, response ) {
							$( '#<?php echo esc_attr( $id ); ?>_file_data' ).val( JSON.stringify( response.data.files ) );
							var num_files = response.data.count;
							$( '#<?php echo esc_attr( $id ); ?>_number_uploads' ).val( JSON.stringify( num_files ) ).trigger( 'change' );
							<?php if( $multiply_price ) { ?>
								var price = $( '#<?php echo esc_attr( $id ); ?>_base_price' ).val();
								price = parseFloat( num_files ) * parseFloat( price );
								$( '#dz_<?php echo esc_attr( $id ); ?>' ).closest( '.pewc-item' ).attr( 'data-price', price );
								$( 'body' ).trigger( 'pewc_force_update_total_js' );
							<?php } ?>
						});
						this.on( 'removedfile', function( file, response ) {
							$.ajax({
								type: 'POST',
								url: pewc_vars.ajaxurl,
								data: {
									action: 'pewc_dropzone_remove',
									file: file.name,
									pewc_file_upload: $( '#pewc_file_upload' ).val(),
									file_data: $( '#<?php echo esc_attr( $id ); ?>_file_data' ).val()
								},
								success: function( response ) {
									$( '#<?php echo esc_attr( $id ); ?>_file_data' ).val( JSON.stringify( response.data.files ) );
									var num_files = response.data.count;
									$( '#<?php echo esc_attr( $id ); ?>_number_uploads' ).val( JSON.stringify( num_files ) ).trigger( 'change' );
									<?php if( $multiply_price ) { ?>
										var price = $( '#<?php echo esc_attr( $id ); ?>_base_price' ).val();
										price = parseFloat( num_files ) * parseFloat( price );
										$( '#dz_<?php echo esc_attr( $id ); ?>' ).closest( '.pewc-item' ).attr( 'data-price', price );
										$( 'body' ).trigger( 'pewc_force_update_total_js' );
									<?php } ?>
								}
							});
						});
						this.on( 'error', function( file, response ) {
							console.log( 'error' );
						});
					},
				});
			});
		</script>

	<?php } else { ?>

		<div class="pewc-input-wrapper" id="<?php echo esc_attr( $id ); ?>-wrapper">
			<div class="pewc-placeholder" id="<?php echo esc_attr( $id ); ?>-placeholder">
				<img src="#">
				<small><a href="#" class="pewc-remove-image" data-id="<?php echo esc_attr( $id ); ?>"><?php _e( 'Remove', 'pewc' ); ?></a></small>
			</div>
			<div>
				<input class="pewc-form-field pewc-file-upload" type="file" <?php echo $allow_multiples; ?> id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $id ); ?>[]">
			</div>
		</div>

	<?php } ?>

<?php echo $close_td;

}
