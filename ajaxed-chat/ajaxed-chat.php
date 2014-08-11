<?php
/*
 Plugin Name: Ajaxed Chat
 Plugin URI: http://gaut.am/bbpress/plugins/ajaxed-chat/
 Description: Adds a MultiUser Chat Room using PHP and Ajax with PHPFreeChat Script (phpfreechat.net)
 Version: 1.0
 Author: Gautam Gupta
 Author URI: http://gaut.am/
*/

/**
 * @package Ajaxed Chat
 * @subpackage Main Section
 * @author Gautam Gupta (www.gaut.am)
 * @link http://gaut.am/bbpress/plugins/ajaxed-chat/
 * @license GNU General Public License version 3 (GPLv3):
 * http://www.opensource.org/licenses/gpl-3.0.html
 */

bb_load_plugin_textdomain( 'ajaxed-chat', dirname( __FILE__ ) . '/languages' ); /* Create Text Domain For Translations */

/**
 * Defines
 */
define( 'AC_VER'	, '1.1'							); /** Version */
define( 'AC_OPTIONS'	, 'AjaxedChat'						); /** Option Name */
define( 'AC_PLUGPATH'	, bb_get_plugin_uri( bb_plugin_basename( __FILE__ ) )	); /** Plugin URL */
define( 'AC_PLUGDIR'	, dirname( __FILE__ )					);

/**
 * Options
 */
$ajaxed_chat_plugopts = bb_get_option( AC_OPTIONS );
if( !$ajaxed_chat_plugopts ) {
	$ajaxed_chat_plugopts = array(
		'serverid'	=> md5( bb_get_uri() ),
		'chatname'	=> 'My Chat',
		'channels'	=> 'General,Help',
		'adminpassword'	=> mt_rand(),
		'clock'		=> 0,
		'flood'		=> 0,
		'ping'		=> 1,
		'log'		=> 1,
		'height'	=> '440px',
		'censor'	=> 0,
		'registered'	=> 0,
		'method'	=> 'Mysql', //'Mysql' or 'File'
		'theme'		=> 'default',
		'language'	=> 'en_US',
	);
	bb_update_option( AC_OPTIONS, $ajaxed_chat_plugopts );
}

if ( bb_is_admin() ) /* Load admin.php file if it is the admin area */
	require_once( 'includes/admin.php' );
else /* Else load public.php file as it is the public area */
	require_once( 'includes/public.php' );

?>