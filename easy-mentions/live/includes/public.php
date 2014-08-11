<?php

/**
 * @package Easy Mentions
 * @subpackage Public Section
 * @author Gautam Gupta (www.gaut.am)
 * @link http://gaut.am/bbpress/plugins/easy-mentions/
 */

/**
 * Links the users & tags in posts
 *
 * Taken from bp_activity_at_name_filter() BuddyPress Function
 * 
 * @param $content The content to be parsed
 */
function em_do_linking( $content ) {
	global $em_plugopts;
	
	if ( !$content )
		return $content;
	
	if ( $em_plugopts['link-tags'] == 1 ) { /* Link Tags */
		preg_match_all( '/[#]+([A-Za-z0-9-_]+)/', $content, $tags );
		$tags = $tags[1];
		
		foreach( (array)$tags as $tag ) {
			if ( $t = bb_get_tag( $tag ) && $link = bb_get_tag_link( $t ) )
				$content = str_replace( "#$tag", "#<a href='$link'>$tag</a>", $content );
		}
	}
	
	if ( $em_plugopts['link-users'] == 1 ) { /* Link Users */
		preg_match_all( '/[@]+([A-Za-z0-9-_]+)/', $content, $usernames );
		$usernames = $usernames[1];
		
		foreach( (array)$usernames as $username ) {
			if ( !$user = bb_get_user( $username, array( 'by' => 'login' ) ) || !$user = bb_get_user( $username, array( 'by' => 'nicename' ) ) ) /* Check by Username and nicename */
				continue;
			
			$nofollow = '';
			if ( ( $em_plugopts['link-user-to'] == 'website' && !$link = $user->user_url ) || ( $em_plugopts['link-user-to'] == 'profile' || !$link ) )
				$link = get_user_profile_link( $user->ID );
			else
				$rel = ( $em_plugopts['add-nofollow'] == 1 ) ? " rel='nofollow'" : ''; /* Only for external websites */
			
			if ( $link )
				$content = str_replace( "@$username", "@<a href='$link'$rel>$username</a>", $content );
		}
	}

        return $content;
}

/**
 * @category Reply
 */

/**
 * Check if it is the last page of topic
 */
function em_reply_is_tlp() {
	global $topic, $page;
	
	$add = topic_pages_add();
	$last_page = get_page_number( $topic->topic_posts + $add );
	
	if ( $page == $last_page )
		return true;
	else
		return false;
}

/**
 * Checks if Easy Mentions' Reply feature needs to work or not
 *
 * @param $check_nav If navigation to last page has to be checked
 * @param $check_last_page If it is the last page
 *
 * @return True if conditions are okay, else false
 */
function em_reply_pre_check( $check_nav = false, $check_last_page = false ) {
	global $em_plugopts, $topic;
	
	if ( $em_plugopts['reply-link'] != 1 || !$em_plugopts['reply-text'] )
		return false;
	
	if ( $check_nav && $em_plugopts['nav-to-last'] != 1 )
		return false;
	
	if ( !bb_is_topic() || !$topic || !topic_is_open() )
		return false;
	
	if ( !bb_is_user_logged_in() || ( function_exists( 'bb_is_login_required' ) && bb_is_login_required() ) )
		return false;
	    
	if ( $check_last_page && !em_reply_is_tlp() )
		return false;
	
	/* All conditions must have ended till now */
	return true;
}

/**
 * Add reply link below each post
 *
 * @param $post_links Array of the links
 * @param $args Array of args
 */
function em_reply_link( $post_links = array(), $args = array() ) {
	if ( em_reply_pre_check() ) {
		$js		= em_reply_js();
		$post_links[]	= $args['before_each'] . '<a class="reply_link" style="cursor:pointer" onclick="' . $js . '">' . __( 'Reply', 'easy-mentions' ) . '</a>' . $args['after_each'];
	}
	
        return $post_links;
}

/**
 * Get the reply text by processing reply-text option
 * Includes fix for Post Count Plus Plugin
 * Doesn't check if it is needed or not, instead use em_reply_js else do em_reply_pre_check
 *
 * @return The javascript if required, else false
 */
function em_reply_text() {
	global $em_plugopts;
	
	$text		= str_replace( '%%USERNAME%%', get_post_author(), $em_plugopts['reply-text'] );
	if ( strpos( $text, 'post_count_plus' ) !== false ) /* Post Count Plus fix */
		$text	= preg_replace( "/(.*?)<span class\='post\_count\_plus' style\='.*?'>(.*?)<\/span>(.*?)/i", '\${1}\${2}\${3}', $text );
	
	$text		= str_replace( '%%POSTLINK%%', get_post_link(), $text );
	
	return $text;
}

/**
 * Get the reply javascript
 *
 * @return The javascript if required, else false
 */
function em_reply_js() {
	if ( em_reply_pre_check() ) {
		$js		= "var ema=document.getElementById('post_content');var emb=ema.value;if(emb!='')emb+='\\n\\n';ema.value=emb+'" . $text . "\\n\\n';ema.focus();void(0);";
		return $js;
	} else {
		return false;
	}
}

function em_head() {
	global $em_plugopts;
	
	if ( em_reply_pre_check() ) {
		
	}
}

add_filter( 'post_text'		, 'em_do_linking'	, -999	, 1	); /* Do Linking */
add_filter( 'bb_post_admin'	, 'em_reply_link'	, 11	, 2	); /* Add reply link */
add_action( 'bb_head'		, 'em_head'		, 10		); /* Print JS in header */

/*

function bb_quote_link() {
	if ( !bb_is_topic() )
		return false;
		
	global $page, $topic, $bb_post;
	
	if ( !$topic || !topic_is_open( $bb_post->topic_id ) || !bb_is_user_logged_in() || !bb_current_user_can('write_posts') ) 
		return false;
	
	$post_id = get_post_id();
	
	$add = topic_pages_add();
	$last_page = get_page_number( $topic->topic_posts + $add );
	
	if ( $page == $last_page ) {
		$action_url = bb_nonce_url( BB_PLUGIN_URL . 'ajaxed-quote/quote.ajax.php', 'quote-' . $post_id );
		$action_url = add_query_arg( 'quoted', $post_id, $action_url ); 
		$link = '<a class="quote_link" href="#post_content" onClick="javascript:quote_user_click(\'' . $action_url . '\')">' . __('Quote', 'ajaxed-quote') . '</a>';
	} else {
		$quote_url = add_query_arg( 'quoted', $post_id, get_topic_link( 0, $last_page ) );
		$quote_url = bb_nonce_url( $quote_url, 'quote-' . $post_id );
		$link = '<a class="quote_link" href="'. $quote_url . '#postform" id="quote_' . $post_id . '">' . __('Quote', 'ajaxed-quote') . '</a>';

	}
	return apply_filters( 'bb_quote_link', $link );
}

/// from php.net/htmlspecialchars
function bb_quote_jschars( $str ) {
    $str = ereg_replace( "\\\\", "\\\\", $str );
    $str = ereg_replace( "\"", "\\\"", $str );
    $str = ereg_replace( "'", "\\'", $str );
    $str = ereg_replace( "\r\n", "\\n", $str );
    $str = ereg_replace( "\r", "\\n", $str );
    $str = ereg_replace( "\n", "\\n", $str );
    $str = ereg_replace( "\t", "\\t", $str );
    $str = ereg_replace( "<", "\\x3C", $str ); // for inclusion in HTML
    $str = ereg_replace( ">", "\\x3E", $str );
    return $str;
}

/// Prints JS header.

add_action('bb_init', 'bb_quote_print_js');
add_action('bb_head', 'bb_quote_header_js', 100);

function bb_quote_print_js() {
	if ( bb_is_topic() && bb_current_user_can('write_posts')  && !bb_is_topic_edit() ) {
		global $topic, $page;
		
		$add = topic_pages_add();
		$last_page = get_page_number( $topic->topic_posts + $add );
		
		if ( isset( $_GET['quoted'] ) )
			bb_check_admin_referer( 'quote-' . intval( $_GET['quoted'] ) );
			
		if ( $last_page != $page )
			return;
			
		bb_enqueue_script('jquery');
	}
}

function bb_quote_header_js() {
	if ( bb_is_topic() && bb_current_user_can('write_posts')  && !bb_is_topic_edit() ) {
		global $topic, $page;
		
		$add = topic_pages_add();
		$last_page = get_page_number( $topic->topic_posts + $add );
		
		if ( $page != $last_page )
			return;
		
		if ( isset( $_GET['quoted'] ) || intval($_GET['quoted']) > 0 ) {
			$quoted_post = bb_quote_jschars( bb_get_quoted_post( intval( $_GET['quoted'] ) ) );
			if ( empty( $quoted_post ) )
				return;
				
			printf( '<script type="text/javascript">var bb_quoted_post="%s";</script>', $quoted_post );
			
			$quote_script = 
"jQuery(document).ready(function(){
   jQuery(\"textarea#post_content\").val( bb_quoted_post );
});";
			printf( '<script type="text/javascript">%s</script>', $quote_script );
		}
			
		?> 
<script type="text/javascript"> 
function quote_user_click( action_url ) {
	jQuery.get( action_url, function( quoted ) {
		previous_content = jQuery("textarea#post_content").val();
		jQuery("textarea#post_content").val( previous_content + quoted );
	});
}
</script>
		<?php 
		
	}
}
*/