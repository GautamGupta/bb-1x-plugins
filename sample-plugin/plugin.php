<?php
/*
 Plugin Name: Sample Plugin
 Plugin URI: http://gaut.am/bbpress/plugins/sample-plugin/
 Description:
 Author: Gautam Gupta
 Author URI: http://gaut.am/
 Version: 0.1
*/

/**
 * @package Sample Plugin
 * @subpackage Main Section
 * @author Gautam Gupta (www.gaut.am)
 * @link http://gaut.am/bbpress/plugins/sample-plugin/
 * @license GNU General Public License version 3 (GPLv3):
 * http://www.opensource.org/licenses/gpl-3.0.html
 */

bb_load_plugin_textdomain( 'sample-plugin', dirname( __FILE__ ) . '/translations' ); /* Create Text Domain For Translations */

/**
 * Defines
 */
define( 'SP_VER'	, '0.1'							); /** Version */
define( 'SP_OPTIONS'	, 'Plugin'						); /** Option Name */
define( 'SP_PLUGURL'	, bb_get_plugin_uri( bb_plugin_basename( __FILE__ ) )	); /** Plugin URL */
define( 'SP_PLUGDIR'	, dirname( __FILE__ ) 					); /** Plugin Dir */

/**
 * Options
 */
$sp_plugopts = bb_get_option( SP_OPTIONS );
if ( !is_array( $sp_plugopts ) ) { /* Get the options, if not found then set them */
	$sp_plugopts = array(
		'option'	=> 1
	);
	bb_update_option( SP_OPTIONS, $sp_plugopts );
}

/**
 * Require Admin/Public File
 */
if ( bb_is_admin() ) /* Load admin.php file if it is the admin area */
	require_once( 'includes/admin.php' );
else /* Else load public.php file if it is the public area */
	require_once( 'includes/public.php' );
