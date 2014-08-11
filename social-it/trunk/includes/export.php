<?php

/**
 * @package Social It
 * @subpackage Admin Section
 * @category Export Options
 * @author Gautam Gupta (www.gaut.am)
 * @link http://gaut.am/bbpress/plugins/social-it/
 */

require_once( '../../../bb-load.php' );

if ( bb_current_user_can( 'administrate' ) )
	bb_die( sprintf( __( 'Sorry, but you don\'t have the permission to do this! <a href="%s">Go back to forums</a>.', 'social-it' ), bb_get_uri() ) );

global $socialit_plugopts;
if( $_GET['url'] != '1' ) unset( $socialit_plugopts['shorturls'] );

$content = var_export( $socialit_plugopts, true );

header( "Cache-Control: public" );
header( "Content-Description: File Transfer" );
header( "Content-disposition: attachment; filename=socialit-options.txt" );
header( "Content-Type: text/plain" );
header( "Content-Transfer-Encoding: binary" );
header( "Content-Length: " . mb_strlen( $content ), 'latin1' );

echo $content;
exit();
?>