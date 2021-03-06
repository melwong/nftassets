<?php
/**
 * General product tab template
 *
 * Used by WCMp_AFM_Accommodation_Integration->accommodation_booking_general_product_tab_content()
 *
 * This template can be overridden by copying it to yourtheme/wcmp-afm/products/accommodation/html-product-data-general.php.
 *
 * HOWEVER, on occasion AFM will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @author 		WC Marketplace
 * @package 	WCMp_AFM/views/products/accommodation
 * @version     3.0.0
 */
defined( 'ABSPATH' ) || exit;

$min_duration = absint( get_post_meta( $id, '_wc_booking_min_duration', true ) );
$max_duration = absint( get_post_meta( $id, '_wc_booking_max_duration', true ) );
$cancel_limit = $bookable_product->get_cancel_limit( 'edit' );
$cancel_limit_unit = $bookable_product->get_cancel_limit_unit( 'edit' );
?>
<div class = "form-group-row show_if_accommodation-booking">
    <div class = "form-group">
        <label class = "control-label col-sm-3 col-md-3" for = "_wc_accommodation_booking_min_duration"><?php esc_html_e( 'Minimum number of nights allowed in a booking', 'woocommerce-accommodation-bookings' ); ?></label>
        <div class="col-md-6 col-sm-9">
            <input type="number" class="form-control" name="_wc_accommodation_booking_min_duration" id="_wc_accommodation_booking_min_duration" value="<?php esc_attr_e( ( empty( $min_duration ) ? 1 : $min_duration ) ); ?>"  step="1" min="">
            <span class="form-text"><?php esc_html_e( 'The minimum allowed duration the user can stay.', 'woocommerce-accommodation-bookings' ); ?></span>
        </div>
    </div>
    <div class = "form-group">
        <label class = "control-label col-sm-3 col-md-3" for = "_wc_accommodation_booking_max_duration"><?php esc_html_e( 'Maximum number of nights allowed in a booking', 'woocommerce-accommodation-bookings' ); ?></label>
        <div class="col-md-6 col-sm-9">
            <input type="number" class="form-control" name="_wc_accommodation_booking_max_duration" id="_wc_accommodation_booking_max_duration" value="<?php esc_attr_e( empty( $max_duration ) ? 7 : $max_duration ); ?>"  step="1" min="1">
            <span class="form-text"><?php esc_html_e( 'The maximum allowed duration the user can stay.', 'woocommerce-accommodation-bookings' ); ?></span>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-3 col-md-3" for="_wc_accommodation_booking_calendar_display_mode">
            <?php esc_html_e( 'Calendar display mode', 'woocommerce-accommodation-bookings' ); ?>
            <span class="img_tip" data-desc="<?php esc_attr_e( 'Choose how the calendar is displayed on the booking form.', 'woocommerce' ); ?>"></span>
        </label>
        <div class="col-md-6 col-sm-9">
            <select name="_wc_accommodation_booking_calendar_display_mode" id="_wc_accommodation_booking_calendar_display_mode" class="form-control">
                <option value="" <?php selected( $bookable_product->get_calendar_display_mode( 'edit' ), '' ); ?>><?php esc_html_e( 'Display calendar on click', 'woocommerce-accommodation-bookings' ); ?></option>
                <option value="always_visible" <?php selected( $bookable_product->get_calendar_display_mode( 'edit' ), 'always_visible' ); ?>><?php esc_html_e( 'Calendar always visible', 'woocommerce-accommodation-bookings' ); ?></option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-3 col-md-3" for="_wc_accommodation_booking_requires_confirmation">
            <?php esc_html_e( 'Requires confirmation?', 'woocommerce-accommodation-bookings' ); ?>
            <span class="img_tip" data-desc="<?php esc_attr_e( 'Check this box if the booking requires admin approval/confirmation. Payment will not be taken during checkout.', 'woocommerce' ); ?>"></span>
        </label>
        <div class="col-md-6 col-sm-9">
            <input type="checkbox" class="form-control" name="_wc_accommodation_booking_requires_confirmation" id="_wc_accommodation_booking_requires_confirmation" value="yes" <?php checked( $bookable_product->get_requires_confirmation( 'edit' ), true ); ?>>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-3 col-md-3" for="_wc_accommodation_booking_user_can_cancel">
            <?php esc_html_e( 'Can be cancelled?', 'woocommerce-accommodation-bookings' ); ?>
            <span class="img_tip" data-desc="<?php esc_attr_e( 'Check this box if the booking can be cancelled by the customer after it has been purchased. A refund will not be sent automatically.', 'woocommerce' ); ?>"></span>
        </label>
        <div class="col-md-6 col-sm-9">
            <input type="checkbox" class="form-control" name="_wc_accommodation_booking_user_can_cancel" id="_wc_accommodation_booking_user_can_cancel" value="yes" <?php checked( $bookable_product->get_user_can_cancel( 'edit' ), true ); ?>>
        </div>
    </div>
    <div class = "form-group accommodation-booking-cancel-limit">
        <label class = "control-label col-sm-3 col-md-3" for = "_wc_accommodation_booking_cancel_limit"><?php esc_html_e( 'Cancellation up till', 'woocommerce-accommodation-bookings' ); ?></label>
        <div class="col-md-6 col-sm-9">
            <div class="row">
                <div class="col-md-6">
                    <input type="number" class="form-control" name="_wc_accommodation_booking_cancel_limit" id="_wc_accommodation_booking_cancel_limit" value="<?php esc_attr_e( $cancel_limit ); ?>" step="1" min="1">
                </div>
                <div class="col-md-6">
                    <select name="_wc_accommodation_booking_cancel_limit_unit" id="_wc_accommodation_booking_cancel_limit_unit" class="form-control">
                        <option value="month" <?php selected( $cancel_limit_unit, 'month' ); ?>><?php esc_html_e( 'Month(s)', 'woocommerce-accommodation-bookings' ); ?></option>
                        <option value="day" <?php selected( $cancel_limit_unit, 'day' ); ?>><?php esc_html_e( 'Day(s)', 'woocommerce-accommodation-bookings' ); ?></option>
                        <option value="hour" <?php selected( $cancel_limit_unit, 'hour' ); ?>><?php esc_html_e( 'Hour(s)', 'woocommerce-accommodation-bookings' ); ?></option>
                        <option value="minute" <?php selected( $cancel_limit_unit, 'minute' ); ?>><?php esc_html_e( 'Minute(s)', 'woocommerce-accommodation-bookings' ); ?></option>
                    </select>
                </div>
            </div>
            <span class="form-text"><?php esc_html_e( 'before check-in.', 'woocommerce-accommodation-bookings' ); ?></span>
        </div>
    </div>
</div>