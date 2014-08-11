<?php

/**
 * @package Ajaxed Chat
 * @subpackage Public Section
 * @author Gautam Gupta (www.gaut.am)
 * @link http://gaut.am/bbpress/plugins/ajaxed-chat/
 * @license GNU General Public License version 3 (GPLv3):
 * http://www.opensource.org/licenses/gpl-3.0.html
 */

/**
 * The main function - load the chat if needed
 */
function ajaxed_chat_load() {
	global $ajaxed_chat_plugopts, $bbdb;
	
	require_once( AC_PLUGDIR . '/chat/src/phpfreechat.class.php' );
	
	$current_user	= bb_get_current_user();
	$user_name	= addslashes( $current_user->display_name );
	
	if ( $ajaxed_chat_plugopts['registered'] == true && empty( $user_name ) ) {
		echo '<span class="pfc_registered">' . __( 'You need to be a registered user to login to the Chat!', 'ajaxed-chat' ) . '</span>';
		return;
	}
	
	/* PARAMETERS */
	$params = array(
			'title'		=> $ajaxed_chat_plugopts['chatname'],
			'nick'		=> $user_name,
			'isadmin'	=> bb_current_user_can( 'moderate' ) ? true : false,
			'serverid'	=> $ajaxed_chat_plugopts['serverid'], /* Calculate a unique id for this chat */
			'height'	=> $ajaxed_chat_plugopts['height'],
			'admins'	=> array( 'admin' => $ajaxed_chat_plugopts['adminpassword'] ), /* Admins */
			
			'language'	=> $ajaxed_chat_plugopts['language'] ? $ajaxed_chat_plugopts['language'] : 'en_US',
			//'ping'		=> $ajaxed_chat_plugopts['ping'],
			//'clocks'	=> $ajaxed_chat_plugopts['clock'],
			
			'debug'		=> false,
			'skip_proxies'	=> array(),
			'short_url'	=> false,
			'showsmileys'	=> true,
			
			/* Channels */
			'channels'	=> explode( ',', $ajaxed_chat_plugopts['channels'] ),
			'quit_on_closedwindow'	=> false,
			'shownotice'	=> bb_current_user_can( 'moderate' ) ? 7 : 1,
			
			/* Setup URLS */
			'data_public_url'	=> AC_PLUGPATH . 'chat/data/public',
			'server_script_url'	=> AC_PLUGPATH . 'includes/chat.php',
			'theme_default_url'	=> AC_PLUGPATH . 'chat/themes',
			'theme'			=> $ajaxed_chat_plugopts['theme']
		       );
	
	$params['max_channels'] = count( $params['channels'] ) + 5;
	
	if ( $ajaxed_chat_plugopts['method'] == 'Mysql' ) {
		$params['container_type']		= 'Mysql';
		$params['container_cfg_mysql_host']     = $bbdb->db_servers['dbh_global']['host'];
		$params['container_cfg_mysql_port']     = '3306';
		$params['container_cfg_mysql_database'] = $bbdb->db_servers['dbh_global']['name'];
		$params['container_cfg_mysql_table']    = $bbdb->prefix . 'ajaxed_chat';
		$params['container_cfg_mysql_username'] = $bbdb->db_servers['dbh_global']['user'];
		$params['container_cfg_mysql_password'] = $bbdb->db_servers['dbh_global']['password'];
	} else {
		$params['container_type']         = 'File';
		$params['container_cfg_chat_dir'] = AC_PLUGDIR.'/data/private/chat';
	}
	
	if ( $ajaxed_chat_plugopts['flood'] == '1' )	$params['skip_proxies'][] = 'noflood';
	if ( $ajaxed_chat_plugopts['censor'] == '1' )	$params['skip_proxies'][] = 'censor';
	if ( $ajaxed_chat_plugopts['log'] = '1' )	$params['skip_proxies'][] = 'log';

	$chat = new phpFreeChat( $params );
	$chat->printChat();
	if ( isset( $_GET['chat'] ) )
		exit();
}

/* Hooks */
add_action( 'ajaxed_chat', 'ajaxed_chat_load' ); /* Custom Hook for loading chat */
if ( isset( $_GET['chat'] ) ) /* Loads full screen chat box, when called - yoursite.com/?chat */
	add_action( 'bb_init', 'ajaxed_chat_load' );

?>