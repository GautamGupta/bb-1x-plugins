<?php
/*
Plugin Name: Debug Queries
Plugin URI:  http://gaut.am/
Description: List query-actions only for admins; for debug purposes
Version: 0.1
Author: Gautam
Author URI: http://gaut.am/

License: CC-GNU-GPL http://creativecommons.org/licenses/GPL/2.0/

Donate: http://gaut.am/donate/
*/

// disable mySQL Session Cache
define( 'QUERY_CACHE_TYPE_OFF', true );

if ( !defined('SAVEQUERIES') )
	define('SAVEQUERIES', true);

if ( !class_exists('DebugQueries') ) {
	class DebugQueries {

		// constructor
		function DebugQueries() {
			add_action( 'bb_foot', array(&$this, 'the_fbDebugQueries'), 9999999 );
		}

		// core
		function get_fbDebugQueries() {
			global $bbdb;

			// disabled session cache of mySQL
			if ( QUERY_CACHE_TYPE_OFF )
				$bbdb->query( 'SET SESSION query_cache_type = 0;' );

			$debugQueries  = '';
			if ($bbdb->queries) {
				$x = 0;
				$total_time = bb_timer_stop( false, 22 );
				$total_query_time = 0;
				$class = '';
				$debugQueries .= '<ol>' . "\n";

				foreach ($bbdb->queries as $q) {
					if ( $x % 2 != 0 )
						$class = '';
					else
						$class = ' class="alt"';
					$q[0] = trim( ereg_replace('[[:space:]]+', ' ', $q[0]) );
					$total_query_time += $q[1];
					$debugQueries .= '<li' . $class . '><strong>' . __('Time:') . '</strong> ' . $q[1];
					if ( isset($q[1]) )
						$debugQueries .= '<br /><strong>' . __('Query:') . '</strong> ' . htmlentities( $q[0] );
					if ( isset($q[2]) )
						$debugQueries .= '<br /><strong>' . __('Call from:') . '</strong> ' . htmlentities( $q[2] );
					$debugQueries .= '</li>' . "\n";
					$x++;
				}

				$debugQueries .= '</ol>' . "\n\n";
			}

			$php_time = $total_time - $total_query_time;
			// Create the percentages
			$mysqlper = bb_number_format_i18n( $total_query_time / $total_time * 100, 2 );
			$phpper   = bb_number_format_i18n( $php_time / $total_time * 100, 2 );

			$debugQueries .= '<ul>' . "\n";
			$debugQueries .= '<li><strong>' . __('Total query time:') . ' ' . bb_number_format_i18n( $total_query_time, 5 ) . __('s for') . ' ' . count($bbdb->queries) . ' ' . __('queries.') . '</strong></li>';
			if ( count($bbdb->queries) != $bbdp->num_queries ) {
				$debugQueries .= '<li><strong>' . __('Total num_query time:') . ' ' . bb_timer_stop() . ' ' . __('for') . ' ' . $bbdb->num_queries . ' ' . __('num_queries.') . '</strong></li>' . "\n";
				$debugQueries .= '<li class="none_list">' . __('&raquo; Different values in num_query and query? - please set the constant') . ' <code>define(\'SAVEQUERIES\', true);</code>' . __('in your') . ' <code>bb-config.php</code></li>' . "\n";
			}
			if ( $total_query_time == 0 )
				$debugQueries .= '<li class="none_list">' . __('&raquo; Query time is null (0)? - please set the constant') . ' <code>SAVEQUERIES</code>' . ' ' . __('at') . ' <code>TRUE</code> ' . __('in your') . ' <code>bb-config.php</code></li>' . "\n";
			$debugQueries .= '<li>' . __('Page generated in'). ' ' . bb_number_format_i18n( $total_time, 5 ) . __('s, ') . $phpper . __('% PHP') . ', ' . $mysqlper . __('% MySQL') . '</li>' . "\n";
			$debugQueries .= '</ul>' . "\n";

			return $debugQueries;
		}

		// echo in frontend
		function the_fbDebugQueries() {
			if ( !bb_current_user_can('use_keys') )
				return;

			$echo  = '';
			$echo .= '<link rel="stylesheet" href="http://www.raptureintheairnow.com/my-plugins/debug-queries/frontend.css" type="text/css" media="screen" />';
			$echo .= '<div id="debugqueries" class="transparent">' . "\n";
			$echo .= '<p>' . __('&raquo; Deactivate after analysis!'). '</p>' . "\n";
			$echo .= $this->get_fbDebugQueries();
			$echo .= '</div>' . "\n\n";

			echo $echo;
		}

	}

	$DebugQueries = new DebugQueries();
}

?>