<?php

/**
 * WCMp_AFM_Subscriptions_Endpoint setup
 *
 * @package  WCMp_AFM/classes/endpoints
 * @since    3.0.0
 */
defined( 'ABSPATH' ) || exit;

class WCMp_AFM_Subscriptions_Endpoint {

    public function output() {
        global $wp;

        $current_vendor_id = afm()->vendor_id;
        $subscription_id = absint( $wp->query_vars['subscriptions'] );
        $vendor_subscriptions_id = WCMp_AFM_Subscription_Integration::get_vendor_subscription_array();
        if ( ! $current_vendor_id || ! current_vendor_can( 'manage_subscriptions' ) || ( $subscription_id && ! in_array( $subscription_id, $vendor_subscriptions_id ) ) ) {
            ?>
            <div class="col-md-12">
                <div class="panel panel-default">
                    <?php esc_html_e( 'You do not have permission to view this content. Please contact site administrator.', WCMp_AFM_TEXT_DOMAIN ); ?>
                </div>
            </div>
            <?php
            return;
        }

        if ( $subscription_id ) {
            $subscription = new WC_Subscription( $subscription_id );
            afm()->template->get_template( 'products/subscription/html-endpoint-single-subscription.php', array( 'subscription' => $subscription ) );
        } else {
            $subscriptions_params = array(
                'ajax_url'               => admin_url( 'admin-ajax.php' ),
                'post_status'            => ! empty( $_GET['post_status'] ) ? wc_clean( $_GET['post_status'] ) : '',
                'empty_table'            => esc_js( __( 'No subscriptions found!', WCMp_AFM_TEXT_DOMAIN ) ),
                'processing'             => esc_js( __( 'Processing...', WCMp_AFM_TEXT_DOMAIN ) ),
                'info'                   => esc_js( __( 'Showing _START_ to _END_ of _TOTAL_ subscriptions', WCMp_AFM_TEXT_DOMAIN ) ),
                'info_empty'             => esc_js( __( 'Showing 0 to 0 of 0 subscriptions', WCMp_AFM_TEXT_DOMAIN ) ),
                'length_menu'            => esc_js( __( 'Number of rows _MENU_', WCMp_AFM_TEXT_DOMAIN ) ),
                'zero_records'           => esc_js( __( 'No matching subscriptions found', WCMp_AFM_TEXT_DOMAIN ) ),
                'next'                   => esc_js( __( 'Next', WCMp_AFM_TEXT_DOMAIN ) ),
                'previous'               => esc_js( __( 'Previous', WCMp_AFM_TEXT_DOMAIN ) ),
                'reload'                 => esc_js( __( 'Reload', WCMp_AFM_TEXT_DOMAIN ) ),
            );
            wp_localize_script( 'afm-subscriptions-js', 'subscriptions_params', $subscriptions_params );
            wp_enqueue_script( 'afm-subscriptions-js' );

            afm()->template->get_template( 'products/subscription/html-endpoint-subscriptions.php' );
        }
    }
}
