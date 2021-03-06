<?php

/**
 * WCMp_AFM_Booking_Calendar_Endpoint setup
 *
 * @package  WCMp_AFM/classes/endpoints
 * @since    3.0.0
 */
defined( 'ABSPATH' ) || exit;

class WCMp_AFM_Booking_Calendar_Endpoint {

    /**
     * Stores Bookings.
     *
     * @var array
     */
    private $bookings;

    /**
     * Output the calendar view.
     */
    public function output() {
        $current_vendor_id = afm()->vendor_id;

        if ( ! $current_vendor_id || ! apply_filters( 'vendor_can_view_booking_calendar', true, $current_vendor_id ) ) {
            ?>
            <div class="col-md-12">
                <div class="panel panel-default">
                    <?php esc_html_e( 'You do not have permission to view this content. Please contact site administrator.', WCMp_AFM_TEXT_DOMAIN ); ?>
                </div>
            </div>
            <?php
            return;
        }
        
        wp_enqueue_script( 'wc-enhanced-select' );

        $product_filter = isset( $_REQUEST['filter_bookings'] ) ? absint( $_REQUEST['filter_bookings'] ) : '';
        $view = isset( $_REQUEST['view'] ) && 'day' === $_REQUEST['view'] ? 'day' : 'month';

        if ( 'day' === $view ) {
            $day = isset( $_REQUEST['calendar_day'] ) ? wc_clean( $_REQUEST['calendar_day'] ) : date( 'Y-m-d' );
            $this->bookings = WCMp_AFM_Booking_Integration::get_bookings_in_date_range(
                    strtotime( 'midnight', strtotime( $day ) ), strtotime( 'midnight +1 day', strtotime( $day ) ) - 1, $product_filter, false
            );
            afm()->template->get_template( 'products/booking/html-calendar-' . $view . '.php', array( 'view' => $view, 'day' => $day, 'product_filter' => $product_filter, 'self' => $this  ) );
        } else {
            $month = isset( $_REQUEST['calendar_month'] ) ? absint( $_REQUEST['calendar_month'] ) : date( 'n' );
            $year = isset( $_REQUEST['calendar_year'] ) ? absint( $_REQUEST['calendar_year'] ) : date( 'Y' );

            if ( $year < ( date( 'Y' ) - 10 ) || $year > 2100 ) {
                $year = date( 'Y' );
            }

            if ( $month > 12 ) {
                $month = 1;
                $year ++;
            }

            if ( $month < 1 ) {
                $month = 12;
                $year --;
            }

            $start_of_week = absint( get_option( 'start_of_week', 1 ) );
            $last_day = date( 't', strtotime( "$year-$month-01" ) );
            $start_date_w = absint( date( 'w', strtotime( "$year-$month-01" ) ) );
            $end_date_w = absint( date( 'w', strtotime( "$year-$month-$last_day" ) ) );

            // Calc day offset
            $day_offset = $start_date_w - $start_of_week;
            $day_offset = $day_offset >= 0 ? $day_offset : 7 - abs( $day_offset );

            // Cald end day offset
            $end_day_offset = 7 - ( $last_day % 7 ) - $day_offset;
            $end_day_offset = $end_day_offset >= 0 && $end_day_offset < 7 ? $end_day_offset : 7 - abs( $end_day_offset );

            // We want to get the last minute of the day, so we will go forward one day to midnight and subtract a min
            $end_day_offset = $end_day_offset + 1;

            $start_time = strtotime( "-{$day_offset} day", strtotime( "$year-$month-01" ) );
            $end_time = strtotime( "+{$end_day_offset} day midnight", strtotime( "$year-$month-$last_day" ) );
            $this->bookings = WC_Bookings_Controller::get_bookings_in_date_range(
                    $start_time, $end_time, $product_filter, false
            );
            afm()->template->get_template( 'products/booking/html-calendar-' . $view . '.php', array( 'view' => $view, 'month' => $month, 'year' => $year, 'start_time' => $start_time, 'end_time' => $end_time,'product_filter' => $product_filter, 'self' => $this ) );
        }
    }

    /**
     * List bookings for a day.
     */
    public function list_bookings( $day, $month, $year ) {
        $date_start = strtotime( "$year-$month-$day midnight" ); // Midnight today.
        $date_end = strtotime( "$year-$month-$day tomorrow" ); // Midnight next day.

        foreach ( $this->bookings as $booking ) {
            if ( $booking->get_start() < $date_end && $booking->get_end() > $date_start ) {
                echo '<li><a href="' . wcmp_get_vendor_dashboard_endpoint_url( 'bookings', $booking->get_id() ) . '">';
                echo '<strong>#' . $booking->get_id() . ' - ';
                $product = $booking->get_product();
                if ( $product ) {
                    echo $product->get_title();
                }
                echo '</strong>';
                echo '<ul>';
                $customer = $booking->get_customer();
                if ( $customer && ! empty( $customer->name ) ) {
                    echo '<li>' . __( 'Booked by', 'woocommerce-bookings' ) . ' ' . $customer->name . '</li>';
                }
                echo '<li>';
                if ( $booking->is_all_day() ) {
                    echo __( 'All Day', 'woocommerce-bookings' );
                } else {
                    echo $booking->get_start_date() . '&mdash;' . $booking->get_end_date();
                }
                echo '</li>';
                $resource = $booking->get_resource();
                if ( $resource ) {
                    echo '<li>' . __( 'Resource #', 'woocommerce-bookings' ) . $resource->ID . ' - ' . $resource->post_title . '</li>';
                }
                $persons = $booking->get_persons();
                foreach ( $persons as $person_id => $person_count ) {
                    echo '<li>';
                    /* translators: 1: person id 2: person name 3: person count */
                    printf( __( 'Person #%1$s - %2$s (%3$s)', 'woocommerce-bookings' ), $person_id, get_the_title( $person_id ), $person_count );
                    echo '</li>';
                }
                echo '</ul></a>';
                echo '</li>';
            }
        }
    }

    /**
     * List bookings on a day.
     *
     * @version  1.10.7 [<description>]
     */
    public function list_bookings_for_day() {
        $bookings_by_time = array();
        $all_day_bookings = array();
        $unqiue_ids = array();

        foreach ( $this->bookings as $booking ) {
            if ( $booking->is_all_day() ) {
                $all_day_bookings[] = $booking;
            } else {
                $start_time = $booking->get_start_date( '', 'Gi' );

                if ( ! isset( $bookings_by_time[$start_time] ) ) {
                    $bookings_by_time[$start_time] = array();
                }

                $bookings_by_time[$start_time][] = $booking;
            }
            $unqiue_ids[] = $booking->get_product_id() . $booking->get_resource_id();
        }

        ksort( $bookings_by_time );

        $unqiue_ids = array_flip( $unqiue_ids );
        $index = 0;
        $colours = array( '#3498db', '#34495e', '#1abc9c', '#2ecc71', '#f1c40f', '#e67e22', '#e74c3c', '#2980b9', '#8e44ad', '#2c3e50', '#16a085', '#27ae60', '#f39c12', '#d35400', '#c0392b' );

        foreach ( $unqiue_ids as $key => $value ) {
            if ( isset( $colours[$index] ) ) {
                $unqiue_ids[$key] = $colours[$index];
            } else {
                $unqiue_ids[$key] = $this->random_color();
            }
            $index ++;
        }

        $column = 0;

        foreach ( $all_day_bookings as $booking ) {
            echo '<li data-tip="' . $this->get_tip( $booking ) . '" style="background: ' . $unqiue_ids[$booking->get_product_id() . $booking->get_resource_id()] . '; left:' . 100 * $column . 'px; top: 0; bottom: 0;"><a href="' . wcmp_get_vendor_dashboard_endpoint_url( 'bookings', $booking->get_id() ) . '">#' . $booking->get_id() . '</a></li>';
            $column ++;
        }

        $start_column = $column;
        $last_end = 0;

        $day = isset( $_REQUEST['calendar_day'] ) ? wc_clean( $_REQUEST['calendar_day'] ) : date( 'Y-m-d' );
        $day_timestamp = strtotime( $day );
        $next_day_timestamp = strtotime( $day . '+1 days' );

        foreach ( $bookings_by_time as $bookings ) {
            foreach ( $bookings as $booking ) {

                // Adjust start_time if event starts before the calendar day
                if ( $booking->get_start() >= $day_timestamp ) {
                    $start_time = $booking->get_start_date( '', 'Hi' );
                } else {
                    $start_time = '0000';
                }

                // Adjust end_time if event ends after the calendar day
                if ( $booking->get_end() > $next_day_timestamp ) {
                    $end_time = '2400';
                } else {
                    $end_time = $booking->get_end_date( '', 'Hi' );
                }

                $height = ( strtotime( $end_time ) - strtotime( $start_time ) ) / 60;

                if ( $height < 30 ) {
                    $height = 30;
                }

                if ( $last_end > $start_time ) {
                    $column ++;
                } else {
                    $column = $start_column;
                }

                $start_time_stamp = strtotime( $start_time );
                $start_hour_in_mins = date( 'H', $start_time_stamp ) * 60;
                $start_minutes = date( 'i', strtotime( $start_time ) );
                $from_top = $start_hour_in_mins + $start_minutes;

                echo '<li data-tip="' . $this->get_tip( $booking ) . '" style="background: ' . esc_attr( $unqiue_ids[$booking->get_product_id() . $booking->get_resource_id()] ) . '; left:' . esc_attr( 100 * $column ) . 'px; top: ' . esc_attr( $from_top ) . 'px; height: ' . esc_attr( $height ) . 'px;"><a href="' . wcmp_get_vendor_dashboard_endpoint_url( 'bookings', $booking->get_id() ) . '">#' . esc_html( $booking->get_id() ) . '</a></li>';

                if ( $end_time > $last_end ) {
                    $last_end = $end_time;
                }
            }
        }
    }
    
    /**
	 * Get a random colour.
	 */
	public function random_color() {
		return sprintf( '#%06X', mt_rand( 0, 0xFFFFFF ) );
	}

	/**
	 * Get a tooltip in day view.
	 *
	 * @param  object $booking
	 * @return string
	 */
	public function get_tip( $booking ) {
		$return = '';

		$return .= '#' . $booking->get_id() . ' - ';
		$product = $booking->get_product();

		if ( $product ) {
			$return .= $product->get_title();
		}

		$customer = $booking->get_customer();

		if ( $customer && ! empty( $customer->name ) ) {
			$return .= '<br/>' . __( 'Booked by', 'woocommerce-bookings' ) . ' ' . $customer->name;
		}

		$resource = $booking->get_resource();

		if ( $resource ) {
			$return .= '<br/>' . __( 'Resource #', 'woocommerce-bookings' ) . $resource->ID . ' - ' . $resource->post_title;
		}

		$persons  = $booking->get_persons();

		foreach ( $persons as $person_id => $person_count ) {
			$return .= '<br/>';

			/* translators: 1: person id 2: person name 3: person count */
			$return .= sprintf( __( 'Person #%1$s - %2$s (%3$s)', 'woocommerce-bookings' ), $person_id, get_the_title( $person_id ), $person_count );
		}

		return esc_attr( $return );
	}

}
