<?php

/**
 * @package Sample Plugin
 * @subpackage Admin Section
 * @author Gautam Gupta (www.gaut.am)
 * @link http://gaut.am/bbpress/plugins/sample-plugin/
 */

/**
 * Check for Updates and if available, then notify
 *
 * @uses WP_Http
 * @uses bb_admin_notice To generate a notice if new version is available
 * @uses $plugin_browser If available, then generates an auto-upgrade link
 *
 * @return string|bool Returns version if update is available, else false
 */
function sp_update_check(){
	$latest_ver = trim( wp_remote_retrieve_body( wp_remote_get( 'http://gaut.am/uploads/plugins/updater.php?pid=8&chk=ver&soft=bb&current=' . SP_VER, array( 'user-agent' => 'Sample bbPress Plugin v' . SP_VER ) ) ) );

	if ( !$latest_ver || version_compare( $latest_ver, _VER, '<=' ) ) /* If no new version or plugin is upto date, then return */
		return false;

	if ( class_exists( 'Plugin_Browser' ) ) { /* Can be automatically upgraded */
		global $plugin_browser;
		$uhref = $plugin_browser->nonceUrl( 'upgrade-plugin_sample-plugin', array( 'plugin' => 'plugin_browser_admin_page', 'pb_action' => 'upgrade', 'pb_plugin_id' => urlencode( 'sample-plugin' ) ) );
		$message = sprintf( __( 'New version (%1$s) of Sample Plugin is available! Please download the latest version from <a href="%2$s">here</a> or <a href="%3$s">upgrade automatically</a>.', 'sample-plugin' ), $ver, 'http://bbpress.org/plugins/topic/sample-plugin/', $uhref );
	} else { /* Else just output the normal message with download link */
		$message = sprintf( __( 'New version (%1$s) of Sample Plugin is available! Please download the latest version from <a href="%2$s">here</a>.', 'sample-plugin' ), $ver, 'http://bbpress.org/plugins/topic/sample-plugin/' );
	}

	bb_admin_notice( $message, 'error' );

	return $latest_ver;
}

/**
 * Makes a settings page for the plugin
 *
 * @uses bb_option_form_element() to generate the page
 */
function sp_options(){
	global $sp_plugopts;

	if ( $_POST['sp_opts_submit'] == 1 ) { /* Settings have been received, now save them! */
		bb_check_admin_referer( 'sp-save-chk' ); /* Security Check */
		
		/* Sanity Checks on options, and then save them */
		$sp_plugopts['int']	= ( intval( $_POST['int']	) == 1 ) ? 1 : 0; /* Integer check */
		$sp_plugopts['radio']	= ( $_POST['radio'] == 'abc'	) ? 'abc' : 'xyz'; /* Radio Check */
		$sp_plugopts['html']	= esc_attr( $_POST['html']	); /* HTML check */
		
		bb_update_option( SP_OPTIONS, $sp_plugopts );
		bb_admin_notice( __( 'The options were successfully saved!', 'sample-plugin' ) );
	}

	sp_update_check(); /* Update check */

	/* Options in an array to be printed */
	$sp_options = array(
		'int' => array(
			'title'		=> __( 'Do this?', 'sample-plugin' ),
			'type'		=> 'checkbox',
			'value'		=> ( $sp_plugopts['int'] == 1 ) ? '1' : '0',
			'note'		=> __( 'Check this option if you want...', 'sample-plugin' ),
			'options'	=> array(
				'1'	=> __( 'Yes', 'sample-plugin' )
			)
		),
		'radio' => array(
			'title'		=> __( 'What to choose?', 'easy-mentions' ),
			'type'		=> 'radio',
			'class'		=> ( $sp_plugopts['enable'] != 1 ) ? array( 'disabled' ) : false,
			'value'		=> ( $sp_plugopts['radio'] == 'abc' ) ? 'abc' : 'xyz',
			'note'		=> __( 'If you select...', 'sample-plugin' ),
			'options'	=> array(
				'abc' => __( 'Abc', 'sample-plugin' ),
				'xyz' => __( 'Xyz', 'sample-plugin' )
			)
		),
		'html' => array(
			'title'		=> __( 'HTML', 'sample-plugin' ),
			'class'		=> ( $sp_plugopts['enable'] != 1 ) ? array( 'disabled' ) : false,
			'value'		=> $sp_plugopts['html'] ? stripslashes( $sp_plugopts['html'] ) : 'test',
			'after'		=> '<div style="clear:both;"></div>' . __( 'Some HTML is allowed.', 'sample-plugin' ) . '<br />'
		)
	);

	if ( $sp_plugopts['link-users'] != 1 )
		$sp_options['link-user-to']['attributes'] = array( 'disabled' => 'disabled' );

	?>

	<h2><?php _e( 'Sample Plugin', 'sample-plugin' ); ?></h2>
	<?php do_action( 'bb_admin_notices' ); ?>
	<form method="post" class="settings options">
		<fieldset>
			<?php
			foreach ( $em_options as $option => $args )
				bb_option_form_element( $option, $args );
			?>
		</fieldset>
		<fieldset class="submit">
			<?php bb_nonce_field( 'sp-save-chk' ); ?>
			<input type="hidden" name="sp_opts_submit" value="1" />
			<input class="submit" type="submit" name="submit" value="<?php _e( 'Save Changes', 'sample-plugin' ); ?>" />
		</fieldset>
		<p><?php printf( __( 'Happy with the plugin? Why not <a href="%1$s">buy the author a cup of coffee or two</a> or get him something from his <a href="%2$s">wishlist</a>?', 'easy-mentions' ), 'http://gaut.am/donate/SP/', 'http://gaut.am/wishlist/' ); ?></p>
	</form>
<?php
}

/**
 * Enqueue the javascript in the admin head section
 *
 * @uses wp_enqueue_script()
 */
function sp_admin_head() {
	wp_enqueue_script( 'sample-plugin', SP_PLUGURL . 'js/admin.js', array( 'jquery' ), SP_VER );
}

/**
 * Adds a menu link to the setting's page in the Settings section
 *
 * @uses bb_admin_add_submenu()
 */
function sp_menu_link() {
	bb_admin_add_submenu( __( 'Sample Plugin', 'sample-plugin' ), 'administrate', 'sp_options', 'options-general.php' );
}

add_action( 'bb_admin_menu_generator'	, 'sp_menu_link'	, 8 ); /* Adds a menu link to setting's page */
add_action( 'sp_options_pre_head'	, 'sp_admin_head'	, 2 ); /* Enqueue the Javascript */
