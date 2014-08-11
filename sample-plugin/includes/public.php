<?php

/**
 * @package Sample Plugin
 * @subpackage Public Section
 * @author Gautam Gupta (www.gaut.am)
 * @link http://gaut.am/bbpress/plugins/sample-plugin/
 */

/*
 * Sample Plugin Public Function
 */

function sp_public() {
    return;
}

add_action( 'bb_init', 'sp_public' );
