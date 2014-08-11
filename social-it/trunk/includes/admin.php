<?php

/**
 * @package Social It
 * @subpackage Admin Section
 * @author Gautam Gupta (www.gaut.am)
 * @link http://gaut.am/bbpress/plugins/social-it/
 */

function socialit_network_input_select( $name, $hint ) {
	global $socialit_plugopts;
	return sprintf( '<label class="%s" title="%s"><input %sname="bookmark[]" type="checkbox" value="%s" id="%s" /></label>',
		esc_attr( $name ),
		$hint,
		@in_array( $name, $socialit_plugopts['bookmark'] ) ? 'checked="checked" ' : '',
		esc_attr( $name ),
		esc_attr( $name )
	);
}

/*
 * @param array $opts expecting keys: field, value, text
 *
 * @return The option tag for a form select element
 */
function socialit_form_select_option( $opts ) {
	global $socialit_plugopts;
	$opts = array_merge(
		array(
			'field'	=> '',
			'value'	=> '',
			'text'	=> ''
		),
		$opts
	);
	return sprintf( '<option%s value="%s">%s</option>',
		( $socialit_plugopts[$opts['field']] == $opts['value'] ) ? ' selected="selected"' : '',
		$opts['value'],
		$opts['text']
	);
}

// given an array $options of data and $field to feed into sexy_form_select_option
function socialit_select_option_group( $field, $options ) {
	$h = '';
	foreach ( $options as $value => $text ) {
		$h .= socialit_form_select_option( array(
			'field'	=> $field,
			'value'	=> $value,
			'text'	=> $text,
		) );
	}
	return $h;
}

/* Check for updates, as bbpress doesnt check itself :( */
function socialit_update_check() {
	$latest_ver = socialit_nav_browse( 'http://gaut.am/uploads/plugins/updater.php?pid=1&chk=ver&soft=bb&current=' . SOCIALIT_VER );
	if ( $latest_ver && version_compare( $latest_ver, SOCIALIT_VER, '>' ) )
		return $latest_ver;
	
	return false;
}

function socialit_pre_spritegen_checks() {
	global $socialit_plugopts;
	
	if ( is_dir( SOCIALIT_PLUGDIR . 'spritegen' ) && is_writable( SOCIALIT_PLUGDIR . 'spritegen' ) && !$socialit_plugopts['custom-mods']
	&& ( ( isset( $_POST['bookmark'] ) && is_array( $_POST['bookmark'] ) && count( $_POST['bookmark'] ) > 0 )
	    || ( isset( $socialit_plugopts['bookmark'] ) && is_array( $socialit_plugopts['bookmark'] ) && count( $socialit_plugopts['bookmark'] ) > 0 )
	    )
	)
		return true;
	
	return false;
}

function socialit_get_sprite_file( $opts, $type ) {
	$spritegen	= 'http://www.shareaholic.com/api/sprite/?v=1&apikey=8afa39428933be41f8afdb8ea21a495c&imageset=60' . $opts . '&apitype=' . $type;
	$filename	= SOCIALIT_PLUGDIR . '/spritegen/custom-sprite.' . $type;
	
	if ( !$content = socialit_nav_browse( $spritegen ) )
		return 2;
	
	if ( 'css' == $type ) {
		$content = str_replace( 'shr-bookmarks', 'social-it', $content ); /* Main DIV replace */
		$content = str_replace( 'shr', 'sexy', $content ); /* BG img replace */
	}
	
	$fp_opt	= ( 'png' == $type ) ? 'w+b' : 'w+';
	$fp	= @fopen( $filename, $fp_opt );
	
	if ( $fp !== false ) {
		$ret = @fwrite( $fp, $content );
		@fclose( $fp );
	} else {
		$ret = @file_put_contents( $filename, $content );
	}
	
	if ( $ret !== false ) {
		@chmod( $filename, 0755 );
		return 0;
	}
	
	return 1;
}

function socialit_sprite_error_messages() {
	echo '<div id="warnmessage" class="socialit-warning"><div class="dialog-left fugue f-warn">';
	
	if ( !is_writable( SOCIALIT_PLUGDIR . 'spritegen' ) )
		printf( __( 'WARNING: Your %sspritegen folder%s is not writeable by the server!', 'social-it' ), '<a href="'.SOCIALIT_PLUGPATH.'spritegen" target="_blank">','</a>' );
	elseif ( file_exists( SOCIALIT_PLUGDIR . 'spritegen/shr-custom-sprite.png' ) && is_writable( SOCIALIT_PLUGDIR . 'spritegen' ) && !is_writable( SOCIALIT_PLUGDIR . 'spritegen/shr-custom-sprite.png' ) )
		printf( __( 'WARNING: You need to delete the current custom sprite %s before the plugin can write to the folder.', 'social-it' ), '(<a href="'.SOCIALIT_PLUGDIR.'spritegen/shr-custom-sprite.png" target="_blank">' . SOCIALIT_PLUGDIR . 'spritegen/shr-custom-sprite.png</a>)' );
	elseif ( file_exists( SOCIALIT_PLUGDIR . 'spritegen/shr-custom-sprite.css' ) && is_writable( SOCIALIT_PLUGDIR . 'spritegen' ) && !is_writable( SOCIALIT_PLUGDIR . 'spritegen/shr-custom-sprite.css' ) )
		printf( __( 'WARNING: You need to delete the current custom stylesheet %s before the plugin can write to the folder.', 'social-it' ), '(<a href="'.SOCIALIT_PLUGDIR.'spritegen/shr-custom-sprite.css" target="_blank">' . SOCIALIT_PLUGDIR . 'spritegen/shr-custom-sprite.css</a>)' );
	
	echo '<a href="http://sexybookmarks.net/documentation/usage-installation#chmod-cont" target="_blank">' . __( 'Need help', 'social-it' ) . '</a>?';
	echo '</div><div class="dialog-right"><img src="' . SOCIALIT_PLUGPATH . 'images/warning-delete.jpg" class="del-x" alt=""/></div></div><div style="clear:both;"></div>';
	
	return __( 'Changes saved successfully. However, settings are not optimal until you resolve the issue listed above.', 'social-it' );
}

/* Add sidebar link to settings page */
function socialit_menu_link() {
	bb_admin_add_submenu( __( 'Social It', 'social-it' ), 'administrate', 'socialit_settings_page', 'options-general.php' );
}

//write settings page
function socialit_settings_page() {
	global $socialit_plugopts, $socialit_bookmarks_data, $bbdb;
	echo "\n\n" . '<!-- Start Of Code Generated By Social It Plugin (Admin Area) By www.gaut.am -->' . "\n";
	echo '<h2 class="socialitlogo">' . __( 'Social It', 'social-it' ) . '</h2>';
	
	/* Some links */
	$donate_link	= 'http://gaut.am/donate/';
	$twitter_link	= 'http://twitter.com/Gaut_am';

	/* Processing form submission */
	$status_message	= $error_message = '';
	
	if ( ( $_POST['custom-mods'] == 'yes' || $socialit_plugopts['custom-mods'] == 'yes' ) && bb_is_admin() && !is_dir( BB_PATH . 'socialit-mods/' ) ) {
		$socialit_oldloc = SOCIALIT_PLUGDIR;
		$socialit_newloc = BB_PATH . 'socialit-mods/';
		
		mkdir( $socialit_newloc,		0755 );
		mkdir( $socialit_newloc . 'css',	0755 );
		mkdir( $socialit_newloc . 'images',	0755 );
		mkdir( $socialit_newloc . 'js',		0755 );
		
		copy( $socialit_oldloc . 'css/style-dev.css',		$socialit_newloc . 'css/style.css'		);
		copy( $socialit_oldloc . 'js/social-it-public-dev.js',	$socialit_newloc . 'js/social-it-public.js'	);
		copy( $socialit_oldloc . 'images/socialit-sprite.png',	$socialit_newloc . 'images/socialit-sprite.png'	);
		
		copy( $socialit_oldloc . 'images/share-enjoy.png',		$socialit_newloc . 'images/share-enjoy.png'		);
		copy( $socialit_oldloc . 'images/share-german.png',		$socialit_newloc . 'images/share-german.png'		);
		copy( $socialit_oldloc . 'images/share-love-hearts.png',	$socialit_newloc . 'images/share-love-hearts.png'	);
		copy( $socialit_oldloc . 'images/share-wealth.png',		$socialit_newloc . 'images/share-wealth.png'		);
		copy( $socialit_oldloc . 'images/sharing-caring-hearts.png',	$socialit_newloc . 'images/sharing-caring-hearts.png'	);
		copy( $socialit_oldloc . 'images/sharing-caring.png',		$socialit_newloc . 'images/sharing-caring.png'		);
		copy( $socialit_oldloc . 'images/sharing-sexy.png',		$socialit_newloc . 'images/sharing-sexy.png'		);
	}
	
	/* Check for updates */
	if ( socialit_update_check() ) { 
		/* Update available */
		echo '
		<div id="update-message" class="socialit-warning">
			<div class="dialog-left">
				<img src="' . SOCIALIT_PLUGPATH . 'images/error.png" class="dialog-ico" alt=""/>
				' . sprintf( __( 'New version of Social It is available! Please download the latest version from <a href="%s">here</a>.', 'social-it' ) . 'http://bbpress.org/plugins/topic/social-it/' ) . '
			</div>
		</div>'; /* Box shouldn't be closed */
	}
	
	/* Import functionality */
	if ( isset( $_POST['import'] ) ) {
		if ( isset( $_FILES['socialit_import_file'] ) && !empty( $_FILES['socialit_import_file']['name'] ) ) {
			$socialit_imported_options = join( '', file( $_FILES['socialit_import_file']['tmp_name'] ) );
			$code = '$socialit_imported_options = '.$socialit_imported_options . ';';
			if ( @eval( 'return true;' . $code ) ) {
				if ( eval( $code ) === null ) {
					if ( $_POST['export_short_urls'] != 'on' )
						unset( $socialit_imported_options['shorturls'] );
					bb_update_option(SOCIALIT_OPTIONS, $socialit_imported_options);
					$status_message = __( 'Social It Options Imported Successfully!', 'social-it' );
				} else {
					$error_message = __( 'Social It Options Import failed!', 'social-it' );
				}
			} else {
				$error_message = __( 'Found syntax errors in file being imported. Social It Options Import failed!', 'social-it' );
			}
		} else {
			$error_message = __( 'Did not receive any file to be imported. Social It Options Import failed!', 'social-it' );
		}
	}
	
	if ( isset( $_POST['save_changes'] ) ) { /* Changes have been saved message */
		$status_message = __( 'Your changes have been saved successfully!', 'social-it' );
		
		$errmsgmap = array(
			'bookmark' => __( 'You can\'t display the menu if you don\'t choose a few sites to add to it!', 'social-it' )
		);
		
		/* Adding to err msg map if twittley is enabled. */
		/*if ( in_array( 'socialit-twittley', $_POST['bookmark'] ) ) {
			$errmsgmap['twittcat']		= __( 'You need to select the primary category for any articles submitted to Twittley.', 'social-it' );
			$errmsgmap['defaulttags']	= __( 'You need to set at least one default tag for any articles submitted to Twittley.', 'social-it' );
		}*/
		
		foreach ( $errmsgmap as $field => $msg ) {
			if ( $_POST[$field] == '' ) {
				$error_message = $msg;
				break;
			}
		}
		
		if ( !$error_message ) {
			/* Generate a new sprite, to reduce the size of the image */
			if ( $socialit_plugopts['bookmark'] != $_POST['bookmark'] && !$socialit_plugopts['custom-css'] ) {
				if ( socialit_pre_spritegen_checks() ) {
					$socialit_plugopts['custom-css'] = '';
					$spritegen_opts = '&service=';
					foreach ( $_POST['bookmark'] as $bm )
						$spritegen_opts .= substr( $bm, 4 ) . ',';
					$spritegen_opts  = substr( $spritegen_opts, 0, -1 );
					$spritegen_opts .= '&bgimg=' . str_replace( 'sexy', 'shr', $_POST['bgimg'] ) . '&expand=' . $_POST['expand'];
					$save_return[0] = get_sprite_file( $spritegen_opts, 'png' );
					$save_return[1] = get_sprite_file( $spritegen_opts, 'css' );
					
					if ( $save_return[0] == 2 || $save_return[1] == 2 ) {
						echo '<div id="warnmessage" class="socialit-warning"><div class="dialog-left fugue f-warn">' . __( 'WARNING: The request for a custom sprite has timed out. Reverting to default sprite files.', 'social-it' ) . '</div><div class="dialog-right"><img src="' . SOCIALIT_PLUGPATH . 'images/warning-delete.jpg" class="del-x" alt=""/></div></div><div style="clear:both;"></div>';
						$status_message = __( 'Changes saved successfully. However, you should try to generate a custom sprite again later.', 'social-it' );
					} elseif ( $save_return[0] == 1 || $save_return[1] == 1 ) {
						$status_message = socialit_sprite_error_messages();
					} else {
						$socialit_plugopts['custom-css'] = SOCIALIT_PLUGPATH . 'spritegen/custom-sprite.css';
					}
				} else {
					$socialit_plugopts['custom-css'] = '';
					$status_message = socialit_sprite_error_messages();
				}
			}
			
			foreach ( array(
				'topic', 'xtrastyle', 'reloption', 'targetopt', 'bookmark', 
				'twittid', 'tweetconfig', 'ybuzzcat', 'ybuzzmed', 
				'twittcat', 'defaulttags', 'bgimg-yes', 'mobile-hide', 'bgimg',
				'feed', 'expand', 'autocenter', 'custom-mods', 'scriptInFooter',
				'sfpnonres', 'sfpres', 'sfpnonsup',
				'shorty',
			) as $field ) $socialit_plugopts[$field] = $_POST[$field];
			
			// Get rid of nasty script injections 
			$socialit_plugopts['defaulttags'] = htmlspecialchars( $socialit_plugopts['defaulttags'], ENT_QUOTES ); 
			$socialit_plugopts['tweetconfig'] = htmlspecialchars( $socialit_plugopts['tweetconfig'], ENT_QUOTES );
			
			/* Short URLs */
			$socialit_plugopts['shortyapi']['snip']['user']		= htmlspecialchars( trim( $_POST['shortyapiuser-snip']	), ENT_QUOTES );
			$socialit_plugopts['shortyapi']['snip']['key']		= htmlspecialchars( trim( $_POST['shortyapikey-snip']	), ENT_QUOTES );
			$socialit_plugopts['shortyapi']['bitly']['user']	= htmlspecialchars( trim( $_POST['shortyapiuser-bitly']	), ENT_QUOTES );
			$socialit_plugopts['shortyapi']['bitly']['key']		= htmlspecialchars( trim( $_POST['shortyapikey-bitly']	), ENT_QUOTES );
			$socialit_plugopts['shortyapi']['supr']['chk']		= $_POST['shortyapichk-supr'];
			$socialit_plugopts['shortyapi']['supr']['user']		= htmlspecialchars( trim( $_POST['shortyapiuser-supr']	), ENT_QUOTES );
			$socialit_plugopts['shortyapi']['supr']['key']		= htmlspecialchars( trim( $_POST['shortyapikey-supr']	), ENT_QUOTES );
			$socialit_plugopts['shortyapi']['tinyarrow']['chk']	= $_POST['shortyapichk-tinyarrow'];
			$socialit_plugopts['shortyapi']['tinyarrow']['user']	= htmlspecialchars(trim($_POST['shortyapiuser-tinyarrow']), ENT_QUOTES);
			$socialit_plugopts['shortyapi']['cligs']['chk']		= $_POST['shortyapichk-cligs'];
			$socialit_plugopts['shortyapi']['cligs']['key']		= htmlspecialchars( trim( $_POST['shortyapikey-cligs']	), ENT_QUOTES );
			/* Short URLs End */
			
			bb_update_option( SOCIALIT_OPTIONS, $socialit_plugopts ); /* Update options */
		}

		if ( $_POST['clearShortUrls'] ) {
			$count = count( $socialit_plugopts['shorturls'] );
			$socialit_plugopts['shorturls'] = array();
			bb_update_option( SOCIALIT_OPTIONS, $socialit_plugopts );
			echo '<div id="clearurl" class="socialit-warning"><div class="dialog-left fugue f-warn">' . $count . __( 'Short URL(s) have been reset.', 'social-it' )  . '</div><div class="dialog-right"><img src="' . SOCIALIT_PLUGPATH . 'images/warning-delete.jpg" class="del-x" alt=""/></div></div><div style="clear:both;"></div>';
		}
	}

	/* If there was an error */
	if ( $error_message != '' ) {
		echo '
		<div id="message" class="socialit-error">
			<div class="dialog-left fugue f-error">
				'.$error_message . '
			</div>
			<div class="dialog-right">
				<img src="'.SOCIALIT_PLUGPATH . 'images/error-delete.jpg" class="del-x" alt="X"/>
			</div>
		</div>';
	} elseif ( $status_message != '' ) {
		echo '
		<div id="message" class="socialit-success">
			<div class="dialog-left fugue f-success">
				' . $status_message . ' | '. sprintf( __( 'Maybe you would consider <a href="%1$s">donating</a> or following me on <a href="%2$s">Twitter</a>?', 'social-it' ), $donate_link, $twitter_link ) . '
			</div>
			<div class="dialog-right">
				<img src="' . SOCIALIT_PLUGPATH . 'images/success-delete.jpg" class="del-x" alt="X"/>
			</div>
		</div>';
	}
?>
	<form name="social-it" id="social-it" action='' method="post">
	<div id="socialit-col-left">
		<ul id="socialit-sortables">
			<li>
				<div class="box-mid-head" id="iconator">
					<h2 class="fugue f-globe-plus"><?php _e( 'Enabled Networks', 'social-it' ); ?></h2>
				</div>
				<div class="box-mid-body iconator" id="toggle1">
					<div class="padding">
						<p><?php _e( 'Select the Networks to display. Drag to reorder . ', 'social-it' ); ?></p>
						<ul class="multi-selection"> 
 		                                        <li><?php _e( 'Select', 'social-it' ); ?>:&nbsp;</li> 
 		                                        <li><a id="sel-all" href="javascript:void(0);"><?php _e( 'All', 'social-it' ); ?></a>&nbsp;|&nbsp;</li> 
 		                                        <li><a id="sel-none" href="javascript:void(0);"><?php _e( 'None', 'social-it' ); ?></a>&nbsp;|&nbsp;</li> 
 		                                        <li><a id="sel-pop" href="javascript:void(0);"><?php _e( 'Most Popular', 'social-it' ); ?></a>&nbsp;</li> 
		                                </ul>
						<div id="socialit-networks">
							<?php
								foreach ( $socialit_plugopts['bookmark'] as $name) print socialit_network_input_select( $name, $socialit_bookmarks_data[$name]['check'] );
								$unused_networks = array_diff(array_keys( $socialit_bookmarks_data), $socialit_plugopts['bookmark'] );
								foreach ( $unused_networks as $name) print socialit_network_input_select( $name, $socialit_bookmarks_data[$name]['check'] );
							?>
						</div>
					</div>
				</div>
			</li>
			<li>
				<div class="box-mid-head">
					<h2 class="fugue f-wrench"><?php _e( 'Functionality Settings', 'social-it' ); ?></h2>
				</div>
				<div class="box-mid-body" id="toggle2">
					<div class="padding">
						<div class="dialog-box-warning" id="clear-warning">
							<div class="dialog-left fugue f-warn">
								<?php _e( 'This will clear <u>ALL</u> short URLs. - Are you sure?', 'social-it' ); ?>
							</div>
							<div class="dialog-right">
								<label><input name="warn-choice" id="warn-yes" type="radio" value='yes' /><?php _e( 'Yes', 'social-it' ); ?></label> &nbsp;<label><input name="warn-choice" id="warn-cancel" type="radio" value="cancel" /><?php _e( 'Cancel', 'social-it' ); ?></label>
							</div>
						</div>
						<div id="twitter-defaults"<?php if ( !in_array( 'socialit-twitter', $socialit_plugopts['bookmark'] ) ) { ?> class="hide"<?php } ?>>
							<h3><?php _e( 'Twitter Options:', 'social-it' ); ?></h3>
							<p id="tweetinstructions">
								<strong><?php _e( 'Configuration Instructions:', 'social-it' ); ?></strong><br />
								<?php printf(__( 'Using the strings %s and %s you can fully customize your tweet output.', 'social-it' ), '<strong>${title}</strong>', '<strong>${short_link}</strong>' ); ?><br /><br />
								<strong><?php _e( 'Example Configurations:', 'social-it' ); ?></strong><br />
								<em>${title} - ${short_link} (via @Gaut_am)</em> <?php _e( 'or', 'social-it' ); ?> <em>RT @Gaut_am: ${title} - ${short_link}</em>
							</p>
							<div style="position:relative;width:40%;">
								<label for="tweetconfig"><?php _e( 'Configure Tweet:', 'social-it' ); ?></label><small id="tweetcounter"><?php _e( 'Characters:', 'social-it' ); ?> <span></span></small><br />
								<textarea id="tweetconfig" name="tweetconfig"><?php if ( !empty( $socialit_plugopts['tweetconfig'] ) ) echo $socialit_plugopts['tweetconfig']; else echo '${title} - ${short_link}'; ?></textarea>
							</div>
							<p id="tweetoutput"><strong><?php _e( 'Example Tweet Output:', 'social-it' ); ?></strong><br /><span></span></p>
						<div class="clearbig"></div>
							<label for="shorty"><?php _e( 'Which URL Shortener?', 'social-it' ); ?></label>
							<select name="shorty" id="shorty">
							<?php
								/* Output shorty select options */
								print socialit_select_option_group( 'shorty', array(
									'none'		=>__( 'Don\'t use a shortener', 'social-it' ), 
									'b2l'		=> 'b2l.me',
									'bitly'		=> 'bit.ly',
									'snip'		=> 'snipr.com',
									'tinyarrow'	=> 'tinyarro.ws',
									'cligs'		=> 'cli.gs',
									'supr'		=> 'su.pr',
									'tiny'		=> 'tinyurl.com',
									'slly'		=> 'SexyURL (sl.ly)'
								) );
							?>
							</select>
							<label for="clearShortUrls" id="clearShortUrlsLabel"><input name="clearShortUrls" id="clearShortUrls" type="checkbox"/><?php _e( 'Reset all Short URLs', 'social-it' ); ?></label>
							<div id="shortyapimdiv-bitly" <?php if (  $socialit_plugopts['shorty'] != 'bitly' ) { ?>class="hide"<?php } ?>>
							<div class="clearbig"></div>
								<div id="shortyapidiv-bitly">
									<label for="shortyapiuser-bitly"><?php _e( 'User ID', 'social-it' ); ?>:</label>
									<input type="text" id="shortyapiuser-bitly" name="shortyapiuser-bitly" value="<?php echo $socialit_plugopts['shortyapi']['bitly']['user']; ?>" />
									<label for="shortyapikey-bitly"><?php _e( 'API Key', 'social-it' ); ?>:</label>
									<input type="text" id="shortyapikey-bitly" name="shortyapikey-bitly" value="<?php echo $socialit_plugopts['shortyapi']['bitly']['key']; ?>" />
								</div>
							</div>
							<div id="shortyapimdiv-snip" <?php if (  $socialit_plugopts['shorty'] != 'snip' ) { ?>class="hide"<?php } ?>>
								<div class="clearbig"></div>
								<div id="shortyapidiv-snip">
									<label for="shortyapiuser-snip"><?php _e( 'User ID', 'social-it' ); ?>:</label>
									<input type="text" id="shortyapiuser-snip" name="shortyapiuser-snip" value="<?php echo $socialit_plugopts['shortyapi']['snip']['user']; ?>" />
									<label for="shortyapikey-snip"><?php _e( 'API Key', 'social-it' ); ?>:</label>
									<input type="text" id="shortyapikey-snip" name="shortyapikey-snip" value="<?php echo $socialit_plugopts['shortyapi']['snip']['key']; ?>" />
								</div>
							</div>
							<div id="shortyapimdiv-tinyarrow" <?php if (  $socialit_plugopts['shorty'] != 'tinyarrow' ) { ?>class="hide"<?php } ?>>
								<span class="socialit_option" id="shortyapidivchk-tinyarrow">
									<input <?php echo ( ( $socialit_plugopts['shortyapi']['tinyarrow']['chk'] == '1' ) ? 'checked=""' : '' ); ?> name="shortyapichk-tinyarrow" id="shortyapichk-tinyarrow" type="checkbox" value='1' /> <?php _e( 'Track Generated Links?', 'social-it' ); ?>
								</span>
								<div class="clearbig"></div>
								<div id="shortyapidiv-tinyarrow" <?php if ( !isset( $socialit_plugopts['shortyapi']['tinyarrow']['chk'] ) ) { ?>class="hide"<?php } ?>>
									<label for="shortyapiuser-tinyarrow"><?php _e( 'User ID', 'social-it' ); ?>:</label>
									<input type="text" id="shortyapiuser-tinyarrow" name="shortyapiuser-tinyarrow" value="<?php echo $socialit_plugopts['shortyapi']['tinyarrow']['user']; ?>" />
								</div>
							</div>
							<div id="shortyapimdiv-cligs" <?php if (  $socialit_plugopts['shorty'] != 'cligs' ) { ?>class="hide"<?php } ?>>
								<span class="socialit_option" id="shortyapidivchk-cligs">
									<input <?php echo ( ( $socialit_plugopts['shortyapi']['cligs']['chk'] == '1' ) ? 'checked=""' : '' ); ?> name="shortyapichk-cligs" id="shortyapichk-cligs" type="checkbox" value='1' /> <?php _e( 'Track Generated Links?', 'social-it' ); ?>
								</span>
								<div class="clearbig"></div>
								<div id="shortyapidiv-cligs" <?php if ( !isset( $socialit_plugopts['shortyapi']['cligs']['chk'] ) ) { ?>class="hide"<?php } ?>>
									<label for="shortyapikey-cligs"><?php _e( 'API Key', 'social-it' ); ?>:</label>
									<input type="text" id="shortyapikey-cligs" name="shortyapikey-cligs" value="<?php echo $socialit_plugopts['shortyapi']['cligs']['key']; ?>" />
								</div>
							</div>
							<div id="shortyapimdiv-supr" <?php if (  $socialit_plugopts['shorty'] != 'supr' ) { ?>class="hide"<?php } ?>>
								<span class="socialit_option" id="shortyapidivchk-supr">
									<input <?php echo ( ( $socialit_plugopts['shortyapi']['supr']['chk'] == '1' ) ? 'checked=""' : '' ); ?> name="shortyapichk-supr" id="shortyapichk-supr" type="checkbox" value='1' /> <?php _e( 'Track Generated Links?', 'social-it' ); ?>
								</span>
								<div class="clearbig"></div>
								<div id="shortyapidiv-supr" <?php if ( !isset( $socialit_plugopts['shortyapi']['supr']['chk'] ) ) { ?>class="hide"<?php } ?>>
									<label for="shortyapiuser-supr"><?php _e( 'User ID', 'social-it' ); ?>:</label>
									<input type="text" id="shortyapiuser-supr" name="shortyapiuser-supr" value="<?php echo $socialit_plugopts['shortyapi']['supr']['user']; ?>" />
									<label for="shortyapikey-supr"><?php _e( 'API Key', 'social-it' ); ?>:</label>
									<input type="text" id="shortyapikey-supr" name="shortyapikey-supr" value="<?php echo $socialit_plugopts['shortyapi']['supr']['key']; ?>" />
								</div>
							</div>
						<?php
						/*
						 * Short URL Options End
						*/
						?>
						<div class="clearbig"></div>
						</div>
						<div id="ybuzz-defaults"<?php if ( !in_array( 'socialit-yahoobuzz', $socialit_plugopts['bookmark'] ) ) { ?> class="hide"<?php } ?>>
							<h3><?php _e( 'Yahoo! Buzz Defaults:', 'social-it' ); ?></h3>
							<label for="ybuzzcat"><?php _e( 'Default Content Category: ', 'social-it' ); ?></label>
							<select name="ybuzzcat" id="ybuzzcat">
								<?php
									print socialit_select_option_group( 'ybuzzcat', array(
										'entertainment'	=> __( 'Entertainment',	'social-it' ),
										'lifestyle'	=> __( 'Lifestyle',	'social-it' ),
										'health'	=> __( 'Health',	'social-it' ),
										'usnews'	=> __( 'U.S. News',	'social-it' ),
										'business'	=> __( 'Business',	'social-it' ),
										'politics'	=> __( 'Politics',	'social-it' ),
										'science'	=> __( 'Sci/Tech',	'social-it' ),
										'world_news'	=> __( 'World',		'social-it' ),
										'sports'	=> __( 'Sports',	'social-it' ),
										'travel'	=> __( 'Travel',	'social-it' )
									) );
									
								?>
							</select>
							<div class="clearbig"></div>
							<label for="ybuzzmed"><?php _e( 'Default Media Type:', 'social-it' ); ?></label>
							<select name="ybuzzmed" id="ybuzzmed">
								<?php
									print socialit_select_option_group( 'ybuzzmed', array(
										'text'	=> 'Text',
										'image'	=> 'Image',
										'audio'	=> 'Audio',
										'video'	=> 'Video'
									) );
								?>
							</select>
						<div class="clearbig"></div>
						</div>
						<div id="twittley-defaults"<?php if ( !in_array( 'socialit-twittley', $socialit_plugopts['bookmark'] ) ) { ?> class="hide"<?php } ?>>
							<h3><?php _e( 'Twittley Defaults:', 'social-it' ); ?></h3>
							<label for="twittcat"><?php _e( 'Primary Content Category:', 'social-it' ); ?></label>
							<select name="twittcat" id="twittcat">
								<?php
									print socialit_select_option_group( 'twittcat', array(
										'Technology'	=> __( 'Technology',	'social-it' ),
										'Science'	=> __( 'Science',	'social-it' ),
										'Gaming'	=> __( 'Gaming',	'social-it' ),
										'Lifestyle'	=> __( 'Lifestyle',	'social-it' ),
										'Entertainment'	=> __( 'Entertainment',	'social-it' ),
										'Sports'	=> __( 'Sports',	'social-it' ),
										'Offbeat'	=> __( 'Offbeat',	'social-it' ),
										'Internet'	=> __( 'Internet',	'social-it' ),
										'World &amp; Business' => esc_attr__( 'World & Business', 'social-it' )
									) );
								?>
							</select>
							<div class="clearbig"></div>
							<p id="tag-info" class="hide">
								<?php _e( "Enter a comma separated list of general tags which describe your site's posts as a whole. Try not to be too specific, as one post may fall into different \"tag categories\" than other posts.", 'socialit' ); ?><br />
								<?php _e( "This list is primarily used as a failsafe in case you forget to enter WordPress tags for a particular post, in which case this list of tags would be used so as to bring at least *somewhat* relevant search queries based on the general tags that you enter here.", 'socialit' ); ?><br /><span title="<?php _e( 'Click here to close this message', 'social-it' ); ?>" class="dtags-close">[<?php _e( 'close', 'social-it' ); ?>]</span>
							</p>
							<label for="defaulttags"><?php _e( 'Default Tags:', 'social-it' ); ?></label>
							<input type="text" name="defaulttags" id="defaulttags" value="<?php echo $socialit_plugopts['defaulttags']; ?>" /><span class="dtags-info fugue f-question" title="<?php _e( 'Click here for help with this option', 'sexybookmarks' ); ?>"> </span>
							<div class="clearbig"></div>
						</div>
						<div id="genopts">
							<h3><?php _e( 'General Functionality Options:', 'social-it' ); ?></h3>
							<span class="socialit_option"><?php _e( 'Add nofollow to the links?', 'social-it' ); ?></span>
							<label><input <?php echo ( ( $socialit_plugopts['reloption'] == 'nofollow' ) ? 'checked="checked"' : '' ); ?> name="reloption" id="reloption-yes" type="radio" value="nofollow" /> <?php _e( 'Yes', 'social-it' ); ?></label>
							<label><input <?php echo ( ( $socialit_plugopts['reloption'] == '' ) ? 'checked="checked"' : '' ); ?> name="reloption" id="reloption-no" type="radio" value='' /> <?php _e( 'No', 'social-it' ); ?></label>
							<span class="socialit_option"><?php _e( 'Open links in new window?', 'social-it' ); ?></span>
							<label><input <?php echo ( ( $socialit_plugopts['targetopt'] == '_blank' ) ? 'checked="checked"' : '' ); ?> name="targetopt" id="targetopt-blank" type="radio" value="_blank" /> <?php _e( 'Yes', 'social-it' ); ?></label>
							<label><input <?php echo ( ( $socialit_plugopts['targetopt'] == '_self' ) ? 'checked="checked"' : '' ); ?> name="targetopt" id="targetopt-self" type="radio" value="_self" /> <?php _e( 'No', 'social-it' ); ?></label>
						</div>
					</div>
				</div>
			</li>
			<li>
				<div class="box-mid-head">
					<h2 class="fugue f-pallette"><?php _e( 'Plugin Aesthetics', 'social-it' ); ?></h2>
				</div>
				<div class="box-mid-body" id="toggle3">
					<div class="padding">
						<div id="custom-mods-notice"> 
							<h1><?php _e( 'Warning!', 'social-it' ); ?></h1> 
							<p><?php _e( 'This option is intended <strong>STRICTLY</strong> for users who understand how to edit CSS/JS and intend to change/edit the associated imaegs themselves. Unfortunately, no support will be offered for this feature, as I cannot be held accountable for your coding and/or image editing mistakes.', 'social-it' ); ?></p>
							<h3><?php _e( 'How it works...', 'social-it' ); ?></h3> 
							<p><?php _e( 'Since you have chosen for the plugin to override the style settings with your own custom mods, it will now pull the files from the new folders it is going to create on your server as soon as you save your changes. The file/folder locations should be as follows:', 'social-it' ); ?></p> 
							<ul>
							<?php $newloc = bb_get_option( 'uri' ) . 'socialit-mods/'; ?>
								<li class="custom-mods-folder"><a href="<?php echo $newloc; ?>"><?php echo $newloc; ?></a></li> 
								<li class="custom-mods-folder"><a href="<?php echo $newloc . 'css'; ?>"><?php echo $newloc . 'css'; ?></a></li> 
								<li class="custom-mods-folder"><a href="<?php echo $newloc . 'js'; ?>"><?php echo $newloc . 'js'; ?></a></li> 
								<li class="custom-mods-folder"><a href="<?php echo $newloc . 'images'; ?>"><?php echo $newloc . 'images'; ?></a></li> 
								<li class="custom-mods-code"><a href="<?php echo $newloc . 'js/social-it-public.js'; ?>"><?php echo $newloc . 'js/social-it-public.js'; ?></a></li> 
								<li class="custom-mods-code"><a href="<?php echo $newloc . 'css/style.css'; ?>"><?php echo $newloc . 'css/style.css'; ?></a></li> 
								<li class="custom-mods-image"><a href="<?php echo $newloc . 'images/socialit-sprite.png'; ?>"><?php echo $newloc . 'images/socialit-sprite.png'; ?></a></li> 
								<li class="custom-mods-image"><a href="<?php echo $newloc . 'images/share-enjoy.png'; ?>"><?php echo $newloc . 'images/share-enjoy.png'; ?></a></li>
								<li class="custom-mods-image"><a href="<?php echo $newloc . 'images/share-german.png'; ?>"><?php echo $newloc . 'images/share-german.png'; ?></a></li>
								<li class="custom-mods-image"><a href="<?php echo $newloc . 'images/share-love-hearts.png'; ?>"><?php echo $newloc . 'images/share-love-hearts.png'; ?></a></li>
								<li class="custom-mods-image"><a href="<?php echo $newloc . 'images/share-wealth.png'; ?>"><?php echo $newloc . 'images/share-wealth.png'; ?></a></li>
								<li class="custom-mods-image"><a href="<?php echo $newloc . 'images/sharing-caring-hearts.png'; ?>"><?php echo $newloc . 'images/sharing-caring-hearts.png'; ?></a></li>
								<li class="custom-mods-image"><a href="<?php echo $newloc . 'images/sharing-caring.png'; ?>"><?php echo $newloc . 'images/sharing-caring.png'; ?></a></li>
								<li class="custom-mods-image"><a href="<?php echo $newloc . 'images/sharing-sexy.png'; ?>"><?php echo $newloc . 'images/sharing-sexy.png'; ?></a></li>
							</ul> 
							<p><?php _e( 'Once you have saved your changes, you will be able to edit the image sprite that holds all of the icons for socialit as well as the CSS which accompanies it. Just be sure that you do in fact edit the CSS if you edit the images, as it is unlikely the heights, widths, and background positions of the images will stay the same after you are done . ', 'social-it' ); ?></p> 
							<p><?php _e( 'Just a quick note... When you edit the styles and images to include your own custom backgrounds, icons, and CSS styles, be aware that those changes will not be reflected on the plugin options page. In other words: when you select your networks to be displayed, or when you select the background image to use, it will still be displaying the images from the original plugin directory . ', 'social-it' ); ?></p> 
							<h3><?php _e( 'In Case of Emergency', 'social-it' ); ?></h3> 
							<p><?php _e( 'If you happen to mess things up, you can follow these directions to reset the plugin back to normal and try again if you wish:', 'social-it' ); ?></p> 
							<ol> 
								<li><?php _e( 'Login to your server via FTP or SSH. (whichever you are more comfortable with)', 'social-it' ); ?></li> 
								<li><?php _e( 'Navigate to your forum\'s root directory . ', 'social-it' ); ?></li> 
								<li><?php _e( 'Delete the directory named "socialit-mods" . ', 'social-it' ); ?></li> 
								<li><?php _e( 'Login to your bbPress dashboard . ', 'social-it' ); ?></li> 
								<li><?php _e( 'Go to the Social It plugin options page. (Settings -> Social It)', 'social-it' ); ?></li> 
								<li><?php _e( 'Deselect the "Use custom mods" option . ', 'social-it' ); ?></li> 
								<li><?php _e( 'Save your changes . ', 'social-it' ); ?></li> 
							</ol> 
							<span class="fugue f-delete custom-mods-notice-close"><?php _e( 'Close Message', 'social-it' ); ?></span> 
						</div> 
						<div class="custom-mod-check fugue f-plugin"> 
							<label for="custom-mods" class="sexy_option" style="display:inline;"> 
								<?php _e( 'Override Styles With Custom Mods Instead?', 'sexybookmarks' ); ?> 
							</label> 
							<input <?php echo ( ( $socialit_plugopts['custom-mods'] == 'yes' ) ? 'checked' : '' ); ?> name="custom-mods" id="custom-mods" type="checkbox" value='yes' /> 
						</div>
						<span class="socialit_option"><?php _e( 'Load scripts in Footer', 'social-it' ); ?> <input type="checkbox" id="scriptInFooter" name="scriptInFooter" <?php echo ( ( $socialit_plugopts['scriptInFooter'] == '1' ) ? 'checked' : '' ); ?> value='1' /></span> 
						<label for="scriptInFooter"><?php _e( "Check this box if you want the Social It javascript to be loaded in your blog's footer.", 'social-it' ); ?> (<a href="http://developer.yahoo.com/performance/rules.html#js_bottom">?</a>)</label>
						<span class="socialit_option"><?php _e( 'Animate-expand multi-lined bookmarks?', 'social-it' ); ?></span>
						<label><input <?php echo ( ( $socialit_plugopts['expand'] == '1' ) ? 'checked="checked"' : '' ); ?> name="expand" id="expand-yes" type="radio" value='1' /> <?php _e( 'Yes', 'social-it' ); ?></label>
						<label><input <?php echo ( ( $socialit_plugopts['expand'] != '1' ) ? 'checked="checked"' : '' ); ?> name="expand" id="expand-no" type="radio" value='0' /> <?php _e( 'No', 'social-it' ); ?></label>
						<span class="socialit_option"><?php _e( 'Auto-space/center the bookmarks?', 'social-it' ); ?></span>
 		                                <label><input <?php echo ( ( $socialit_plugopts['autocenter'] == '2' ) ? 'checked="checked"' : '' ); ?> name="autocenter" id="autocenter-space" type="radio" value='2' /> <?php _e( 'Space', 'social-it' ); ?></label>
 		                                <label><input <?php echo ( ( $socialit_plugopts['autocenter'] == '1' ) ? 'checked="checked"' : '' ); ?> name="autocenter" id="autocenter-center" type="radio" value='1' /> <?php _e( 'Center', 'social-it' ); ?></label>
 		                                <label><input <?php echo ( ( $socialit_plugopts['autocenter'] == '0' ) ? 'checked="checked"' : '' ); ?> name="autocenter" id="autocenter-no" type="radio" value='0' /> <?php _e( 'No', 'social-it' ); ?></label>
						
						<h2><?php _e( 'Background Image', 'social-it' ); ?></h2>
						<span class="socialit_option">
							<?php _e( 'Use a background image?', 'social-it' ); ?> <input <?php echo ( ( $socialit_plugopts['bgimg-yes'] == 'yes' ) ? 'checked=""' : '' ); ?> name="bgimg-yes" id="bgimg-yes" type="checkbox" value='yes' />
						</span>
						<div id="bgimgs" <?php if ( !isset( $socialit_plugopts['bgimg-yes'] ) ) { ?>class="hide"<?php } else { echo " "; }?>>
							<label class="share-sexy">
								<input <?php echo ( ( $socialit_plugopts['bgimg'] == 'sexy' ) ? 'checked="checked"' : '' ); ?> id="bgimg-sexy" name="bgimg" type="radio" value="sexy" />
							</label>
							<label class="share-care">
								<input <?php echo ( ( $socialit_plugopts['bgimg'] == 'caring' ) ? 'checked="checked"' : '' ); ?> id="bgimg-caring" name="bgimg" type="radio" value="caring" />
							</label>
							<label class="share-care-old">
								<input <?php echo ( ( $socialit_plugopts['bgimg'] == 'care-old' ) ? 'checked="checked"' : '' ); ?> id="bgimg-care-old" name="bgimg" type="radio" value="care-old" />
							</label>
							<label class="share-love">
								<input <?php echo ( ( $socialit_plugopts['bgimg'] == 'love' ) ? 'checked="checked"' : '' ); ?> id="bgimg-love" name="bgimg" type="radio" value="love" />
							</label>
							<label class="share-wealth">
								<input <?php echo ( ( $socialit_plugopts['bgimg'] == 'wealth' ) ? 'checked="checked"' : '' ); ?> id="bgimg-wealth" name="bgimg" type="radio" value="wealth" />
							</label>
							<label class="share-enjoy">
								<input <?php echo ( ( $socialit_plugopts['bgimg'] == 'enjoy' ) ? 'checked="checked"' : '' ); ?> id="bgimg-enjoy" name="bgimg" type="radio" value="enjoy" />
							</label>
							<label class="share-knowledge"> 
 		                                                <input <?php echo ( ( $socialit_plugopts['bgimg'] == 'knowledge' ) ? 'checked="checked"' : '' ); ?> id="bgimg-knowledge" name="bgimg" type="radio" value="knowledge" /> 
							</label>
							<label class="share-german"> 
 		                                                <input <?php echo ( ( $socialit_plugopts['bgimg'] == 'german' ) ? 'checked="checked"' : '' ); ?> id="bgimg-german" name="bgimg" type="radio" value="german" /> 
							</label>
						</div>
					</div>
				</div>
			</li>
			<li>
				<div class="box-mid-head">
					<h2 class="fugue f-footer"><?php _e( 'Menu Placement', 'social-it' ); ?></h2>
				</div>
				<div class="box-mid-body" id="toggle5">
					<div class="padding">
						<p id="placement-info">
							<?php _e( 'You can insert the Social It menu anywhere on your forums, the plugin will take appropiate values of that page. By default, Social It menu is displayed below the first post of the topic only, but you can insert it on Forums, Tag pages, or anywhere, just put this code where you want to insert it:', 'social-it' ); ?><br />
							&lt;?php if ( function_exists( 'selfserv_socialit' ) ) { selfserv_socialit(); } ?&gt;
						</p>
						<span class="socialit_option"><?php _e( 'Display below first Post in Topic?', 'social-it' ); ?></span>
						<label><input <?php echo ( ( $socialit_plugopts['topic'] == '1' ) ? 'checked="checked"' : '' ); ?> name="topic" id="topic-show" type="radio" value='1' /> <?php _e( 'Yes', 'social-it' ); ?></label>
						<label><input <?php echo ( ( $socialit_plugopts['topic'] == '0' || empty( $socialit_plugopts['topic'] ) ) ? 'checked="checked"' : '' ); ?> name="topic" id="topic-hide" type="radio" value='0' /> <?php _e( 'No', 'social-it' ); ?></label>
						<span class="socialit_option"><?php _e( 'Show in RSS feed?', 'social-it' ); ?></span>
						<label><input <?php echo ( ( $socialit_plugopts['feed'] == '1' ) ? 'checked="checked"' : '' ); ?> name="feed" id="feed-show" type="radio" value='1' /> <?php _e( 'Yes', 'social-it' ); ?></label>
						<label><input <?php echo ( ( $socialit_plugopts['feed'] == '0' || empty( $socialit_plugopts['feed'] ) ) ? 'checked="checked"' : '' ); ?> name="feed" id="feed-hide" type="radio" value='0' /> <?php _e( 'No', 'social-it' ); ?></label>
						<label class="socialit_option" style="margin-top:12px;">
							<?php _e( 'Hide menu from mobile browsers?', 'social-it' ); ?> <input <?php echo ( ( $socialit_plugopts['mobile-hide'] == 'yes' ) ? 'checked' : '' ); ?> name="mobile-hide" id="mobile-hide" type="checkbox" value='yes' />
						</label>
						<?php
						if ( class_exists( 'Support_Forum' ) ) { //compatibility with support forum plugin
							$support_forum = new Support_Forum();
							if (  $support_forum->isActive() ) {
								?>
								<div id="genopts">
									<p id="sfi-info" class="hide">
										<?php _e( 'Social It plugin is compatible with <a href="http://bbpress.org/plugins/topic/support-forum/">Support Forum plugin</a> Made by Aditya Naik & Sam Bauers. You are seeing these options because that plugin is activated on your forums. With the help of these options you can configure whether to show Social It on non-resolved, resolved or non support topics . ', 'social-it' ); ?>
										<br /><span title="<?php _e( 'Click here to close this message', 'social-it' ); ?>" class="sfi-close">[<?php _e( 'close', 'social-it' ); ?>]</span>
									</p>
									<h3><?php _e( 'Compatibility with Support Forum Plugin', 'social-it' ); ?>: <span class="sfp-info fugue f-question" title="Click here for help with this option"> </span></h3>
									<span class="socialit_option"><?php _e( 'Display Social It on Non-Resolved Topics?', 'social-it' ); ?></span>
									<label><input<?php echo ( ( $socialit_plugopts['sfpnonres'] == 'yes' ) ? ' checked="checked"' : '' ); ?> name="sfpnonres" id="sfpnonres-yes" type="radio" value='yes' /> <?php _e( 'Yes', 'social-it' ); ?></label>
									<label><input<?php echo ( ( $socialit_plugopts['sfpnonres'] == 'no' ) ? ' checked="checked"' : '' ); ?> name="sfpnonres" id="sfpnonres-no" type="radio" value='no' /> <?php _e( 'No', 'social-it' ); ?></label>
									<span class="socialit_option"><?php _e( 'Display Social It on Resolved Topics?', 'social-it' ); ?></span>
									<label><input<?php echo ( ( $socialit_plugopts['sfpres'] == 'yes' ) ? ' checked="checked"' : '' ); ?> name="sfpres" id="sfpres-yes" type="radio" value='yes' /> <?php _e( 'Yes', 'social-it' ); ?></label>
									<label><input<?php echo ( ( $socialit_plugopts['sfpres'] == 'no' ) ? ' checked="checked"' : '' ); ?> name="sfpres" id="sfpres-no" type="radio" value='no' /> <?php _e( 'No', 'social-it' ); ?></label>
									<span class="socialit_option"><?php _e( 'Display Social It on Non-Support Topics?', 'social-it' ); ?></span>
									<label><input<?php echo ( ( $socialit_plugopts['sfpnonsup'] == 'yes' ) ? ' checked="checked"' : '' ); ?> name="sfpnonsup" id="sfpnonsup-yes" type="radio" value='yes' /> <?php _e( 'Yes', 'social-it' ); ?></label>
									<label><input<?php echo ( ( $socialit_plugopts['sfpnonsup'] == 'no' ) ? ' checked="checked"' : '' ); ?> name="sfpnonsup" id="sfpnonsup-no" type="radio" value='no' /> <?php _e( 'No', 'social-it' ); ?></label>
									<br /><br />
								</div>
								<?php
							}
						}
						?>
					</div>
				</div>
			</li>
		</ul>
		<input type="hidden" name="save_changes" value='1' />
		<div class="submit"><input type="submit" value="<?php _e( 'Save Changes', 'social-it' ); ?>" /></div>
		<hr width=590 align="left" />
	</div>
	</form>
	<div id="socialit-col-left">
	<ul id="socialit-sortables">
		<li>
			<form name="social-it" id="socialit-import-options" method="post" enctype="multipart/form-data">
				<div class="box-mid-head">
					<img src="<?php echo SOCIALIT_PLUGPATH; ?>images/down.png" alt="" class="box-icons" />
					<h2><?php _e( 'Import Social It Options', 'social-it' ); ?></h2>
				</div>
				<div class="box-mid-body" id="toggle6">
					<div class="padding">
						<div class="dialog-box-warning" id="import-warning">
							<div class="dialog-left" fugue f-warn">
								<?php _e( 'All of your current Social It options will be overwritten by the imported value. Are you sure you want to overwrite all settings?', 'social-it' ); ?>
							</div>
							<div class="dialog-right">
								<label><input name="import-warn-choice" id="import-warn-yes" type="radio" value='yes' onchange="if ( this.checked==true) {document.forms['socialit-import-options'].submit();jQuery( '#import-warning' ).fadeOut();jQuery(this).is( ':not(:checked)' );}return;" onclick="document.forms['socialit-import-options'].submit();jQuery( '#import-warning' ).fadeOut();jQuery(this).is( ':not(:checked)' );" /><?php _e( 'OK', 'social-it' ); ?></label> &nbsp;<label><input name="import-warn-choice" id="import-warn-cancel" type="radio" value="cancel" onchange="if ( this.checked==true) {this.checked=false;jQuery( '#import-warning' ).fadeOut();}return;" /><?php _e( 'Cancel', 'social-it' ); ?></label>
							</div>
						</div>
						<div class="dialog-box-warning" id="import-short-urls-warning">
							<div class="dialog-left" fugue f-warn">
								<?php _e( 'Only import the short URLs if you had taken a backup of options from this forum . ', 'social-it' ); ?>
							</div>
							<div class="dialog-right">
								<label><input name="import-short-urls-warn-choice" id="import-short-urls-warn-yes" type="radio" value='yes' /><?php _e( 'OK', 'social-it' ); ?></label> &nbsp;<label><input name="import-short-urls-warn-choice" id="import-short-urls-warn-no" type="radio" value="cancel" /><?php _e( 'Cancel', 'social-it' ); ?></label>
							</div>
						</div>
						<p><?php _e( 'This functionality will restore your entire Social It options from a file.<br /><strong>Make sure you have done an export and backup the exported file before you try this!', 'social-it' ); ?></strong></p>
						<input type="file" id="socialit_import_file" name="socialit_import_file" size="40" />
						<div class="clearbig"></div>
						<label id="import_short_urls_label" for="import_short_urls">
							<input type="checkbox" id="import_short_urls" name="import_short_urls" /> <?php _e( 'Import Generated Short URLs Too', 'social-it' ); ?><br />
						</label>
						<input type="hidden" name="import" value='1' />
						<div class="submit">
							<input type="button" id="import-submit" value="<?php _e( 'Import Options', 'social-it' ); ?>" />
						</div>
					</div>
					<div class="clearbig"></div>
				</div>
			</form>
		</li>
		<li>
			<form name="social-it" id="socialit-export-options" method="post">
				<div class="box-mid-head">
					<img src="<?php echo SOCIALIT_PLUGPATH; ?>images/up.png" alt="" class="box-icons" />
					<h2><?php _e( 'Export Social It Options', 'social-it' ); ?></h2>
				</div>
				<div class="box-mid-body" id="toggle7">
					<div class="padding">
						<div class="dialog-box-warning" id="export-short-urls-warning">
							<div class="dialog-left" fugue f-warn">
								<?php _e( 'Only export short URLs if you are backing up the options, and are not importing the options on another forum . ', 'social-it' ); ?>
							</div>
							<div class="dialog-right">
								<label><input name="export-short-urls-warn-choice" id="export-short-urls-warn-yes" type="radio" value='yes' /><?php _e( 'OK', 'social-it' ); ?></label> &nbsp;<label><input name="export-short-urls-warn-choice" id="export-short-urls-warn-no" type="radio" value="cancel" /><?php _e( 'Cancel', 'social-it' ); ?></label>
							</div>
						</div>
						<p><?php _e( 'This functionality will dump your entire Social It options into a file', 'social-it' ); ?></p>
						<label id="export_short_urls_label" for="export_short_urls">
							<input type="checkbox" id="export_short_urls" name="export_short_urls" /> <?php _e( 'Export Generated Short URLs Too', 'social-it' ); ?><br />
						</label>
						<input type="hidden" name="export" value='1' />
						<?php
						if ( isset( $_POST['export'] ) ) {
							$url = ( $_POST['export_short_urls'] == 'on' ) ? '?url=1' : '';
							echo '<iframe src="' . SOCIALIT_PLUGPATH . 'includes/export.php' . $url . '" width="0" height="0"></iframe>';
						}
						?>
						<div class="submit">
							<input type="submit" value="<?php _e( 'Export Options', 'social-it' ); ?>" />
						</div>
						<div class="clearbig"></div>
					</div>
				</div>
			</form>
		</li>
	</ul>
	</div>
	<div id="socialit-col-right">
	<div class="box-right">
		<div class="box-right-head">
			<img src="<?php echo SOCIALIT_PLUGPATH; ?>images/icons/information-frame.png" alt="" class="box-icons" />
			<h3 class="fugue f-info-frame"><?php _e( 'Helpful Plugin Links', 'social-it' ); ?></h3>
		</div>
		<div class="box-right-body" id="help-box">
			<div class="padding">
				<ul class="infolinks">
					<li><a href="http://gaut.am/bbpress/plugins/social-it/" target="_blank"><?php _e( 'Plugin Info', 'social-it' ); ?></a> (<?php _e( 'or', 'social-it' ); ?> <a href="http://bbpress.org/plugins/topic/social-it/" target="_blank"><?php _e( 'here', 'social-it' ); ?></a>)</li>
					<li><a href="http://gaut.am/bbpress/plugins/social-it/documentation/usage-and-installation-how-to-guide/" target="_blank"><?php _e( 'Installation &amp; Usage Guide', 'social-it' ); ?></a></li>
					<li><a href="http://gaut.am/bbpress/plugins/social-it/documentation/frequently-asked-questions-faq/" target="_blank"><?php _e( 'Frequently Asked Questions', 'social-it' ); ?></a></li>
					<li><a href="http://forum.gaut.am/" target="_blank"><?php _e( 'Support Forum', 'social-it' ); ?></a></li>
					<li><a href="http://sexybookmarks.net/platforms/" target="_blank"><?php _e( 'Other Social It Platforms', 'social-it' ); ?></a></li>
					<li><a href="http://gaut.am/contact/" target="_blank"><?php _e( 'Submit a Translation', 'social-it' ); ?></a></li>
				</ul>
			</div>
		</div>
	</div>
	<div class="box-right socialit-donation-box">
		<div class="box-right-head">
			<h3 class="fugue f-money"><?php _e( 'Support by Donating', 'social-it' ); ?></h3>
		</div>
		<div class="box-right-body">
			<div class="padding">
				<p><?php _e( 'If you like Social It and wish to contribute towards it\'s continued development, you can use the form below to do so . ', 'social-it' ); ?></p>
				<div class="socialit-donate-button">
					<a href="<?php echo $donate_link; ?>" title="<?php _e( 'Help support the development of this plugin by donating!', 'social-it' ); ?>" class="socialit-buttons">
						<img src="<?php echo SOCIALIT_PLUGPATH; ?>images/donate.png" alt="" />
					</a>
				</div>
				<div class="socialit-twitter-button">
					<a href="<?php echo $twitter_link; ?>" title="<?php _e( 'Get the latest information about the plugin and the latest news about Internet & Technology in the world!', 'social-it' ); ?>" class="socialit-buttons">
						<?php _e( 'Follow Him on Twitter!', 'social-it' ); ?>
					</a>
				</div>
				<div class="socialit-website-button">
					<a href="http://gaut.am/" title="<?php _e( 'Get the latest information about the plugin and the latest news about Internet & Technology in the world!', 'social-it' ); ?>" class="socialit-buttons">
						<?php _e( 'Visit His Website!', 'social-it' ); ?>
					</a>
				</div>				
			</div>
		</div>
	</div>
	<div class="box-right">
		<div class="box-right-head">
			<h3 class="fugue f-medal"><?php _e( 'Top Supporters', 'social-it' ); ?></h3>
		</div>
		<div class="box-right-body">
			<div class="padding">
				<?php echo socialit_nav_browse( 'http://gaut.am/uploads/plugins/donations.php?pid=1&chk=ver&soft=bb&current=' . SOCIALIT_vNum ); ?>
				<p><a href="<?php echo $donate_link; ?>" title="<?php _e( 'Help support the development of this plugin by donating!', 'social-it' ); ?>"><?php _e( 'Donate', 'social-it' ); ?></a> <?php _e( 'now to get to this list and your name with your website link will be here!', 'social-it' ); ?></p>
			</div>
		</div>
	</div>
	<div class="box-right">
		<div class="box-right-head">
			<h3 class="fugue f-megaphone"><?php _e( 'Shout Outs', 'social-it' ); ?></h3>
		</div>
		<div class="box-right-body">
			<div class="padding">
				<ul class="credits">
					<li><a href="http://www.pinvoke.com/"><?php _e( 'Fugue Icons: Pinvoke', 'sexybookmarks' ); ?></a></li> 
 		                        <li><a href="http://alisothegeek.com/2009/10/fugue-sprite-css/"><?php _e( 'Fugue Icon Sprite: Alison Barrett', 'sexybookmarks' ); ?></a></li> 
					<li><a href="http://wefunction.com/2008/07/function-free-icon-set/"><?php _e( 'Original Skin Icons: Function', 'sexybookmarks' ); ?></a></li>
				</ul>
			</div>
		</div>
	</div>
	</div>
	<!-- End Of Code Generated By Social It Plugin (Admin Area) By www.gaut.am -->
	<?php
} //closing brace for function "socialit_settings_page"

//styles for admin area
function socialit_admin() {
	if (  $_GET['plugin'] == 'socialit_settings_page' ) {
		echo "\n\n" . '<!-- Start Of Code Generated By Social It Plugin (Admin Area) By www.gaut.am -->'."\n";
		wp_register_style( 'social-it', SOCIALIT_PLUGPATH . 'css/admin-style.css', false, SOCIALIT_VER, 'all' );
		wp_print_styles( 'social-it' );
		if ( isset( $_SERVER['HTTP_USER_AGENT'] ) && ( strpos( $_SERVER['HTTP_USER_AGENT'], 'MSIE 7' ) !== false ) ) { //ie, as usual, doesnt render the css properly :| and creates problems for the developers
			wp_register_style( 'ie-social-it', SOCIALIT_PLUGPATH . 'css/ie7-admin-style.css', false, SOCIALIT_VER, 'all' );
			wp_print_styles( 'ie-social-it' );
		}
		echo '<script type="text/javascript" src="' . SOCIALIT_PLUGPATH . 'js/jquery/jquery.js"></script>'; //loads newer version of jquery. bbpress uses old version
		echo '<script type="text/javascript" src="' . SOCIALIT_PLUGPATH . 'js/jquery/ui.core.js?ver=1.7.1"></script>'; //ui core & sortable script not included in bbpress :-(
		echo '<script type="text/javascript" src="' . SOCIALIT_PLUGPATH . 'js/jquery/ui.sortable.js?ver=1.7.1"></script>';
		echo '<script type="text/javascript" src="' . SOCIALIT_PLUGPATH . 'js/social-it.js"></script>'; //social-it admin js script
		echo '<script type="text/javascript" src="' . SOCIALIT_PLUGPATH . 'js/jquery.colorbox-min.js"></script>'; //social-it admin js script
		echo '<!-- End Of Code Generated By Social It Plugin (Admin Area) By www.gaut.am -->'."\n\n";
	}
}

add_action( 'bb_admin_menu_generator', 'socialit_menu_link', -998 ); //link in settings
add_action( 'bb_admin_head', 'socialit_admin', 997 ); //admin css
