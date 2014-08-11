/**
 * Javascript file (uncompressed)
 *
 * @package Easy Mentions
 * @author Gautam Gupta (www.gaut.am)
 * @link http://gaut.am/bbpress/plugins/easy-mentions/
 * @see reply.js for compressed version
 */

/**
 * That file is compressed by http://closure-compiler.appspot.com/home
 * Method - Go there, paste this javascript there and compress with simple method (Advanced causes errors!)
 */

jQuery(document).ready(function() {
    jQuery(".reply_link").click(function () {
        var original = jQuery("#post_content").val();
        if( original != ''){
            original += '\n\n';
        }
        var par = jQuery(this).parent().siblings('.author');
        jQuery("#post_content").val(original+'<em>@<a href="'+par.children('a:last').get(0)+'">'+par.children('.by').text()+'</a></em>\n\n').focus();
    });
});