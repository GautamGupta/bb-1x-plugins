<?php

/**
 * @package Ajaxed Chat
 * @subpackage Admin Section
 * @author Gautam Gupta (www.gaut.am)
 * @link http://gaut.am/bbpress/plugins/ajaxed-chat/
 * @license GNU General Public License version 3 (GPLv3):
 * http://www.opensource.org/licenses/gpl-3.0.html
 */

/**
 * Adds a menu link to the setting's page in the Settings section
 *
 * @uses bb_admin_add_submenu()
 */
function ajaxed_chat_menu_link() {
	bb_admin_add_submenu( __( 'Ajaxed Chat', 'ajaxed-chat' ), 'administrate', 'ajaxed_chat_settings_page', 'options-general.php' );
}

/**
 * Check for Updates and if available, then notify
 *
 * @uses WP_Http
 * @uses bb_admin_notice To generate a notice if new version is available
 * @uses $plugin_browser If available, then generates an auto-upgrade link
 *
 * @return string|bool Returns version if update is available, else false
 */
function ajaxed_chat_update_check(){
	$latest_ver = trim( wp_remote_retrieve_body( wp_remote_get( 'http://gaut.am/uploads/plugins/updater.php?pid=4&chk=ver&soft=bb&current=' . AC_VER, array( 'user-agent' => 'Ajaxed-Chat/bbPress v' . AC_VER ) ) ) );
	if ( !$latest_ver || version_compare( $latest_ver, AC_VER, '<=' ) ) /* If call fails or plugin is upto date, then return */
		return false;
	
	global $plugin_browser;
	if ( class_exists( 'Plugin_Browser' ) && $plugin_browser && method_exists( $plugin_browser, 'nonceUrl' ) ) { /* Can be automatically upgraded */
		$uhref = $plugin_browser->nonceUrl( 'upgrade-plugin_ajaxed-chat', array( 'plugin' => 'plugin_browser_admin_page', 'pb_action' => 'upgrade', 'pb_plugin_id' => urlencode( 'ajaxed-chat' ) ) );
		$message = sprintf( __( 'New version (%1$s) of Ajaxed Chat Plugin is available! Please download the latest version from <a href="%2$s">here</a> or <a href="%3$s">upgrade automatically</a>.', 'ajaxed-chat' ), $latest_ver, 'http://bbpress.org/plugins/topic/ajaxed-chat/', $uhref );
	} else { /* Else just output the normal message with download link */
		$message = sprintf( __( 'New version (%1$s) of Ajaxed Chat Plugin is available! Please download the latest version from <a href="%2$s">here</a>.', 'ajaxed-chat' ), $latest_ver, 'http://bbpress.org/plugins/topic/ajaxed-chat/' );
	}
	
	bb_admin_notice( $message, 'error' );

	return $latest_ver;
}

/**
 * Makes a settings page for the plugin
 * 
 * @uses bb_option_form_element() to generate the page
 */
function ajaxed_chat_settings_page() {
	global $ajaxed_chat_plugopts, $bbdb;
	
	if ( $_POST['ac_opts_submit'] == 1 ) {
		
		bb_check_admin_referer( 'ac-save-chk' );
		
		/* Sanity Checks */
		$ajaxed_chat_plugopts['clock']		= ( $_POST['clock']	== 1 ? 1 : 0 );
		$ajaxed_chat_plugopts['flood']		= ( $_POST['flood']	== 1 ? 1 : 0 );
		$ajaxed_chat_plugopts['ping']		= ( $_POST['ping']	== 1 ? 1 : 0 );
		$ajaxed_chat_plugopts['registered']	= ( $_POST['registered']== 1 ? 1 : 0 );
		$ajaxed_chat_plugopts['censor']		= ( $_POST['censor']	== 1 ? 1 : 0 );
		$ajaxed_chat_plugopts['method']		= $_POST['method'];
		$ajaxed_chat_plugopts['serverid']	= $_POST['serverid'];
		$ajaxed_chat_plugopts['height']		= $_POST['height'];
		$ajaxed_chat_plugopts['chatname']	= $_POST['chatname'];
		$ajaxed_chat_plugopts['channels']	= $_POST['channels'];
		$ajaxed_chat_plugopts['adminpassword']	= $_POST['adminpassword'];
		$ajaxed_chat_plugopts['theme']		= $_POST['theme'];
		$ajaxed_chat_plugopts['language']	= $_POST['language'];
		
		/* Save the options and notify user */
		bb_update_option( AC_OPTIONS, $ajaxed_chat_plugopts );
		bb_admin_notice( __( 'The options have been successfully saved!', 'ajaxed-chat' ) );
	}
	
	/* Check for updates and if available, then notify */
	ajaxed_chat_update_check();
	
	/* Language directories */
	$language_dirs		= array();
	$language_directories	= glob( AC_PLUGDIR . '/chat/i18n/*' );
	foreach ( (array) $language_directories as $language ) {
		if ( is_dir( $language ) ) { 
			$language_dir_name = basename( $language );
			$language_dirs[$language_dir_name] = $language_dir_name;
		}
	}
	
	/* Theme directories */
	$theme_dirs		= array();
	$theme_directories	= glob( AC_PLUGDIR . '/chat/themes/*' );
	foreach ( (array) $theme_directories as $theme ) {
		if ( is_dir( $theme ) ) { 
			$theme_dir_name = basename( $theme );
			$theme_dirs[$theme_dir_name] = $theme_dir_name;
		}
	}
	
	/* Options in an array to be printed */
	$ac_options = array(
		'serverid' => array(
			'title'	=> __( 'Server ID', 'ajaxed-chat' ),
			'type'	=> 'text',
			'value' => $ajaxed_chat_plugopts['serverid'],
			'note'	=> __( 'The server ID needs to be a very unique identifier for the chat. If you are confused, you can leave this value as it is.', 'ajaxed-chat' )
		),
		'chatname' => array(
			'title'	=> __( 'Chat Name', 'ajaxed-chat' ),
			'type'	=> 'text',
			'value' => $ajaxed_chat_plugopts['chatname']
		),
		'channels' => array(
			'title'	=> __( 'Channel Names', 'ajaxed-chat' ),
			'type'	=> 'text',
			'value' => $ajaxed_chat_plugopts['channels'],
			'note'	=> __( 'Separated by commas (",") - Do not put spaces after commas', 'ajaxed_chat' )
		),
		'adminpassword' => array(
			'title'	=> __( 'Admin Password', 'ajaxed-chat' ),
			'type'	=> 'text',
			'value' => $ajaxed_chat_plugopts['adminpassword']
		),
		'language' => array(
			'title'		=> __( 'Language', 'ajaxed-chat' ),
			'type'		=> 'select',
			'options'	=> $language_dirs,
			'value'		=> in_array( $ajaxed_chat_plugopts['language'], $language_dirs ) ? $ajaxed_chat_plugopts['language'] : 'en_US'
		),
		'method' => array(
			'title'		=> __( 'Storage Method', 'ajaxed-chat' ),
			'type'		=> 'radio',
			'value'		=> ( in_array( $ajaxed_chat_plugopts['method'], array( 'File', 'Mysql' ), $ajaxed_chat_plugopts['method'] ) ) ? $ajaxed_chat_plugopts['method'] : 'Mysql',
			'options'	=> array(
				'Mysql'	=> 'MySql',
				'File'	=> 'File'
			)
		),
		'ping' => array(
			'title'	=> __( 'Turn on Ping?', 'ajaxed-chat' ),
			'type'	=> 'checkbox',
			'value'	=> $ajaxed_chat_plugopts['ping'] ? 1 : 0,
			'options'	=> array(
				'1' => __( 'Yes', 'ajaxed-chat' )
			)
		),
		'flood' => array(
			'title'	=> __( 'Turn off Flood Checking?', 'ajaxed-chat' ),
			'type'	=> 'checkbox',
			'value'	=> $ajaxed_chat_plugopts['flood'] ? 1 : 0,
			'options' => array(
				'1' => __( 'Yes', 'ajaxed-chat' )
			)
		),
		'censor' => array(
			'title'	=> __( 'Turn off the Censor Proxy?', 'ajaxed-chat' ),
			'type'	=> 'checkbox',
			'value'	=> $ajaxed_chat_plugopts['censor'] ? 1 : 0,
			'options' => array(
				'1' => __( 'Yes', 'ajaxed-chat' )
			)
		),
		'clock' => array(
			'title'	=> __( 'Disable the timestamp on each chat message?', 'ajaxed-chat' ),
			'type'	=> 'checkbox',
			'value'	=> $ajaxed_chat_plugopts['clock'] ? 1 : 0,
			'options' => array(
				'1' => __( 'Yes', 'ajaxed-chat' )
			)
		),
		'log' => array(
			'title'	=> __( 'Disable text logging of the chat?', 'ajaxed-chat' ),
			'type'	=> 'checkbox',
			'value'	=> $ajaxed_chat_plugopts['log'] ? 1 : 0,
			'note'	=> sprintf( __( 'Text chat logs are stored in the %s directory.', 'ajaxed-chat' ), '<code>my-plugins/ajaxed-chat/chat/data/private/logs/serverid</code>' ),
			'options' => array(
				'1' => __( 'Yes', 'ajaxed-chat' )
			)
		),
		'registered' => array(
			'title'	=> __( 'Disable unregistered users from viewing the chat?', 'ajaxed-chat' ),
			'type'	=> 'checkbox',
			'value'	=> $ajaxed_chat_plugopts['registered'] ? 1 : 0,
			'options' => array(
				'1' => __( 'Yes', 'ajaxed-chat' )
			)
		),
		'height' => array(
			'title'	=> __( 'Height of Chat Box', 'ajaxed-chat' ),
			'type'	=> 'text',
			'class'	=> array( 'short' ),
			'value'	=> $ajaxed_chat_plugopts['height']
		),
		'theme' => array(
			'title'		=> __( 'Theme', 'ajaxed-chat' ),
			'type'		=> 'select',
			'options'	=> $theme_dirs,
			'value'		=> $ajaxed_chat_plugopts['theme'] ? $ajaxed_chat_plugopts['theme'] : 'default'
		)
	);
	?>
	<h2><?php _e( 'Ajaxed Chat Options', 'ajaxed-chat' ); ?></h2>
	<?php do_action( 'bb_admin_notices' ); ?>
	<form method="post" class="settings options">
		<fieldset>
			<p><?php echo __( 'You can call the Ajaxed Chat by putting this code in your template:', 'ajaxed-chat' ) . '&nbsp;<code>&lt;?php do_action( \'ajaxed_chat\' ); ?&gt;</code>'; ?></p>
			<?php
			foreach ( $ac_options as $option => $args )
				bb_option_form_element( $option, $args );
			?>
		</fieldset>
		<fieldset class="submit">
			<p><?php printf( __( 'Please run the %s command in chat room after you have done any changes to the configuration.', 'ajaxed-chat' ), '<code>/rehash</code>' ); ?></p>
			<?php bb_nonce_field( 'ac-save-chk' ); ?>
			<input type="hidden" name="ac_opts_submit" value="1"></input>
			<input class="submit" type="submit" name="submit" value="<?php _e( 'Save Changes', 'ajaxed-chat' ); ?>" />
		</fieldset>
		<p><?php printf( __( 'Happy with the plugin? Why not <a href="%1$s">buy the author a cup of coffee or two</a> or get him something from his <a href="%2$s">wishlist</a>?', 'ajaxed-chat' ), 'http://gaut.am/donate/bb/AC/', 'http://gaut.am/wishlist/' ); ?></p>
	</form>
<?php
}

/* Hooks */
add_action( 'bb_admin_menu_generator', 'ajaxed_chat_menu_link' ); /* Link in settings */

?>