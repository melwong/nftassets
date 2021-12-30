<?php
/**
 * myticket Engine Room.
 * This is where all Theme Functions runs.
 *
 * @package myticket
 */

/**
 * Setup.
 * Enqueue styles, register widget regions, etc.
 */
require get_template_directory() . '/inc/setup.php';
require get_template_directory() . '/inc/custom-css.php';

/**
 * myticket Shortcodes.
 */

require get_template_directory() . '/inc/shortcodes/myticket-banner.php';
require get_template_directory() . '/inc/shortcodes/myticket-newsletter.php';
require get_template_directory() . '/inc/shortcodes/myticket-news.php';
require get_template_directory() . '/inc/shortcodes/myticket-aboutus.php';
require get_template_directory() . '/inc/shortcodes/myticket-partner.php';
require get_template_directory() . '/inc/shortcodes/myticket-contact.php';
require get_template_directory() . '/inc/shortcodes/myticket-gallery.php';
require get_template_directory() . '/inc/shortcodes/myticket-headline.php';
require get_template_directory() . '/inc/shortcodes/myticket-events.php';
require get_template_directory() . '/inc/shortcodes/myticket-events-upcomming.php';
require get_template_directory() . '/inc/shortcodes/myticket-events-upcomming-minified.php';
require get_template_directory() . '/inc/shortcodes/myticket-events-single.php';
require get_template_directory() . '/inc/shortcodes/myticket-events-schedule.php';
require get_template_directory() . '/inc/shortcodes/myticket-events-schedule-minified.php';
require get_template_directory() . '/inc/shortcodes/myticket-events-ticketnum.php';
require get_template_directory() . '/inc/shortcodes/myticket-categories.php';
require get_template_directory() . '/inc/shortcodes/myticket-counters.php';
require get_template_directory() . '/inc/shortcodes/myticket-videos.php';
require get_template_directory() . '/inc/shortcodes/myticket-cta.php';
require get_template_directory() . '/inc/shortcodes/myticket-banner-parallax.php';