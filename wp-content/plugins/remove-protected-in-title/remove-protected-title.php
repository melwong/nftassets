<?php
/**
	* Plugin Name: Remove Protected In Title
	* Plugin URI: http://www.dejanblazeski.com/plugin-remove-protected-from-wordpress-page-title/
	* Description: Removes "Private" or "Protected" prefix that wordpress adds to password-protected & private pages
	* Version: 3.0
	* Author: Dejan Blazeski
	* Author URI: http://www.dejanblazeski.com
	* License: GPL2
*/

function remove_protected_in_title($title) {
	return '%s';
}

add_filter('private_title_format', 'remove_protected_in_title');
add_filter('protected_title_format', 'remove_protected_in_title');

?>
