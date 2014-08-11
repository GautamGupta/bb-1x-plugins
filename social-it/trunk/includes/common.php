<?php

/**
 * @package Social It
 * @subpackage Common
 * @author Gautam Gupta (www.gaut.am)
 * @link http://gaut.am/bbpress/plugins/social-it/
 */

function socialit_nav_browse( $url, $method = 'GET', $data = array() ) {
	return wp_remote_retrieve_body( wp_remote_request( $url, array( 'method' => $method, 'body' => $data, 'user-agent' => 'Social It/bbPress v' . SOCIALIT_VER ) ) );
}

require_once( 'bookmarks-data.php' ); /* Bookmarks data file */


?>