<?php

/**
 * @package Social It
 * @subpackage Common
 * @category Bookmarks Data
 * @author Gautam Gupta (www.gaut.am)
 * @link http://gaut.am/bbpress/plugins/social-it/
 */

/* Dynamic mister wong link generator */
if( BB_LANG == 'de_DE' )
	$wong_tld = 'de';
elseif ( in_array( BB_LANG, array( 'zh_CN', 'zh_HK', 'zh_TW' ) ) )
	$wong_tld = 'cn';
elseif ( in_array( BB_LANG, array( 'es_CL', 'es_ES', 'es_PE', 'es_VE' ) ) )
	$wong_tld = 'es';
elseif ( in_array( BB_LANG, array( 'fr_FR', 'fr_BE' ) ) )
	$wong_tld = 'fr';
elseif ( in_array( BB_LANG, array( 'ru_RU', 'ru_MA' ) ) )
	$wong_tld = 'ru';
else
	$wong_tld = 'com';

$si_checkthis_text = __( 'Check this box to include %s in your bookmarking menu', 'social-it' ); 

/* Array of bookmarks */
$socialit_bookmarks_data = array(
	'socialit-scriptstyle'	=> array(
		'check'		=> sprintf( $si_checkthis_text, __( 'Script & Style', 'social-it' ) ),
		'share'		=> sprintf( __( 'Submit this to %s', 'social-it' ), __( 'Script & Style', 'social-it' ) ),
		'baseUrl'	=> 'http://scriptandstyle.com/submit?url=PERMALINK&title=TITLE',
	),
	'socialit-blinklist'	=> array(
		'check'		=> sprintf( $si_checkthis_text, __( 'Blinklist', 'social-it' ) ),
		'share'		=> sprintf( __( 'Share this on %s', 'social-it' ), __( 'Blinklist', 'social-it' ) ),
		'baseUrl'	=> 'http://www.blinklist.com/index.php?Action=Blink/addblink.php&Url=PERMALINK&Title=TITLE',
	),
	'socialit-delicious'	=> array(
		'check'		=> sprintf( $si_checkthis_text, __( 'Delicious', 'social-it' ) ),
		'share'		=> sprintf( __( 'Share this on %s', 'social-it' ), __( 'del.icio.us', 'social-it' ) ),
		'baseUrl'	=> 'http://del.icio.us/post?url=PERMALINK&title=TITLE',
	),
	'socialit-digg'	=> array(
		'check'		=> sprintf( $si_checkthis_text, __( 'Digg', 'social-it' ) ),
		'share'		=> __( 'Digg this!', 'social-it' ),
		'baseUrl'	=> 'http://digg.com/submit?phase=2&url=PERMALINK&title=TITLE',
	),
	'socialit-diigo'	=> array(
		'check'		=> sprintf( $si_checkthis_text, __( 'Diigo', 'social-it' ) ),
		'share'		=> sprintf( __( 'Post this to %s', 'social-it' ), __( 'Diigo', 'social-it' ) ),
		'baseUrl'	=> 'http://www.diigo.com/post?url=PERMALINK&title=TITLE&desc=socialit_TEASER',
	),
	'socialit-reddit'	=> array(
		'check'		=> sprintf( $si_checkthis_text, __( 'Reddit', 'social-it' ) ),
		'share'		=> sprintf( __( 'Share this on %s', 'social-it' ), __( 'Reddit', 'social-it' ) ),
		'baseUrl'	=> 'http://reddit.com/submit?url=PERMALINK&title=TITLE',
	),
	'socialit-yahoobuzz'	=> array(
		'check'		=> sprintf( $si_checkthis_text, __( 'Yahoo! Buzz', 'social-it' ) ),
		'share'		=> __( 'Buzz up!', 'social-it' ),
		'baseUrl'	=> 'http://buzz.yahoo.com/submit/?submitUrl=PERMALINK&submitHeadline=TITLE&submitSummary=YAHOOTEASER&submitCategory=YAHOOCATEGORY&submitAssetType=YAHOOMEDIATYPE',
	),
	'socialit-stumbleupon'	=> array(
		'check'		=> sprintf( $si_checkthis_text, __( 'Stumbleupon', 'social-it' ) ),
		'share'		=> __( 'Stumble upon something good? Share it on StumbleUpon', 'social-it' ),
		'baseUrl'	=> 'http://www.stumbleupon.com/submit?url=PERMALINK&title=TITLE',
	),
	'socialit-technorati'	=> array(
		'check'		=> sprintf( $si_checkthis_text, __( 'Technorati', 'social-it' ) ),
		'share'		=> sprintf( __( 'Share this on %s', 'social-it' ), __( 'Technorati', 'social-it' ) ),
		'baseUrl'	=> 'http://technorati.com/faves?add=PERMALINK',
	),
	'socialit-mixx'	=> array(
		'check'		=> sprintf( $si_checkthis_text, __( 'Mixx', 'social-it' ) ),
		'share'		=> sprintf( __( 'Share this on %s', 'social-it' ), __( 'Mixx', 'social-it' ) ),
		'baseUrl'	=> 'http://www.mixx.com/submit?page_url=PERMALINK&title=TITLE',
	),
	'socialit-myspace'	=> array(
		'check'		=> sprintf( $si_checkthis_text, __( 'MySpace', 'social-it' ) ),
		'share'		=> sprintf( __( 'Post this to %s', 'social-it' ), __( 'MySpace', 'social-it' ) ),
		'baseUrl'	=> 'http://www.myspace.com/Modules/PostTo/Pages/?u=PERMALINK&t=TITLE',
	),
	'socialit-designfloat'	=> array(
		'check'		=> sprintf( $si_checkthis_text, __( 'DesignFloat', 'social-it' ) ),
		'share'		=> sprintf( __( 'Submit this to %s', 'social-it' ), __( 'DesignFloat', 'social-it' ) ),
		'baseUrl'	=> 'http://www.designfloat.com/submit.php?url=PERMALINK&title=TITLE',
	),
	'socialit-facebook'	=> array(
		'check'		=> sprintf( $si_checkthis_text, __( 'Facebook', 'social-it' ) ),
		'share'		=> sprintf( __( 'Share this on %s', 'social-it' ), __( 'Facebook', 'social-it' ) ),
		'baseUrl'	=> 'http://www.facebook.com/share.php?v=4&src=bm&u=PERMALINK&t=TITLE',
	),
	'socialit-twitter'	=> array(
		'check'		=> sprintf( $si_checkthis_text, __( 'Twitter', 'social-it' ) ),
		'share'		=> __( 'Tweet This!', 'social-it' ),
		'baseUrl'	=> 'http://twitter.com/home?status=',
	),
	'socialit-mail'	=> array(
		'check'		=> sprintf( $si_checkthis_text, __( '"Email to a Friend" link', 'social-it' ) ),
		'share'		=> __( 'Email this to a friend?', 'social-it' ),
                'baseUrl'	=> 'mailto:?subject=%22TITLE%22&body='.urlencode( __( 'I thought this article might interest you.', 'social-it' ) ).'%0A%0A%22POST_SUMMARY%22%0A%0A'.urlencode( __( 'You can read the full article here', 'social-it' ) ).'%3A%20PERMALINK',
	),
	'socialit-tomuse'	=> array(
		'check'		=> sprintf( $si_checkthis_text, __( 'ToMuse', 'social-it' ) ),
		'share'		=> __( 'Suggest this article to ', 'social-it' ).__( 'ToMuse', 'social-it' ),
                'baseUrl'	=> 'mailto:tips@tomuse.com?subject=' . urlencode( __( 'New tip submitted via the Social It (bbPress) Plugin!', 'social-it' ) ).'&body='.urlencode( __( 'I would like to submit this article', 'social-it' ) ) . '%3A%20%22TITLE%22%20' . urlencode( __( 'for possible inclusion on ToMuse.', 'social-it' ) ) . '%0A%0A%22POST_SUMMARY%22%0A%0A' . urlencode( __( 'You can read the full article here', 'social-it' ) ) . '%3A%20PERMALINK',
	),
	'socialit-comfeed'	=> array(
		'check'		=> sprintf( $si_checkthis_text, __( 'a \'Subscribe to Posts\' link', 'social-it' ) ),
		'share'		=> __( 'Subscribe to the posts for this topic?', 'social-it' ),
		'baseUrl'	=> 'PERMALINK',
	),
	'socialit-linkedin'	=> array(
		'check'		=> sprintf( $si_checkthis_text, __( 'Linkedin', 'social-it' ) ),
		'share'		=> sprintf( __( 'Share this on %s', 'social-it' ), __( 'Linkedin', 'social-it' ) ),
		'baseUrl'	=> 'http://www.linkedin.com/shareArticle?mini=true&url=PERMALINK&title=TITLE&summary=POST_SUMMARY&source=SITE_NAME',
	),
	'socialit-newsvine'	=> array(
		'check'		=> sprintf( $si_checkthis_text, __( 'Newsvine', 'social-it' ) ),
		'share'		=> __( 'Seed this on Newsvine', 'social-it' ),
		'baseUrl'	=> 'http://www.newsvine.com/_tools/seed&save?u=PERMALINK&h=TITLE',
	),
	'socialit-googlereader'	=> array( 
		'check'		=> sprintf( $si_checkthis_text, __( 'Google Reader', 'social-it' ) ), 
		'share'		=> sprintf( __( 'Add this to %s', 'social-it' ), __( 'Google Reader', 'social-it' ) ), 
		'baseUrl'	=> 'http://www.google.com/reader/link?url=PERMALINK&amp;title=TITLE&amp;srcUrl=PERMALINK&amp;srcTitle=TITLE&amp;snippet=POST_SUMMARY', 
 	), 
	'socialit-googlebuzz'	=> array( 
		'check'		=> sprintf( $si_checkthis_text, __( 'Google Buzz', 'social-it' ) ), 
		'share'		=> __( 'Post on Google Buzz', 'social-it' ), 
		'baseUrl'	=> 'http://www.google.com/buzz/post?url=PERMALINK&amp;imageurl=', 
 	),
	'socialit-misterwong'	=> array(
		'check'		=> sprintf( $si_checkthis_text, __( 'Mister Wong', 'social-it' ) ),
		'share'		=> sprintf( __( 'Add this to %s', 'social-it' ), __( 'Mister Wong', 'social-it' ) ),
		'baseUrl'	=> 'http://www.mister-wong.'.$wong_tld.'/addurl/?bm_url=PERMALINK&bm_description=TITLE&plugin=socialit',
	),
	'socialit-izeby'	=> array(
		'check'		=> sprintf( $si_checkthis_text, __( 'Izeby', 'social-it' ) ),
		'share'		=> sprintf( __( 'Add this to %s', 'social-it' ), __( 'Izeby', 'social-it' ) ),
		'baseUrl'	=> 'http://izeby.com/submit.php?url=PERMALINK',
	),
	'socialit-tipd'		=> array(
		'check'		=> sprintf( $si_checkthis_text, __( 'Tipd', 'social-it' ) ),
		'share'		=> sprintf( __( 'Share this on %s', 'social-it' ), __( 'Tipd', 'social-it' ) ),
		'baseUrl'	=> 'http://tipd.com/submit.php?url=PERMALINK',
	),
	'socialit-pfbuzz'	=> array(
		'check'		=> sprintf( $si_checkthis_text, __( 'PFBuzz', 'social-it' ) ),
		'share'		=> sprintf( __( 'Share this on %s', 'social-it' ), __( 'PFBuzz', 'social-it' ) ),
		'baseUrl'	=> 'http://pfbuzz.com/submit?url=PERMALINK&title=TITLE',
	),
	'socialit-friendfeed'	=> array(
		'check'		=> sprintf( $si_checkthis_text, __( 'FriendFeed', 'social-it' ) ),
		'share'		=> sprintf( __( 'Share this on %s', 'social-it' ), __( 'FriendFeed', 'social-it' ) ),
		'baseUrl'	=> 'http://www.friendfeed.com/share?title=TITLE&link=PERMALINK',
	),
	'socialit-blogmarks'	=> array(
		'check'		=> sprintf( $si_checkthis_text, __( 'BlogMarks', 'social-it' ) ),
		'share'		=> __( 'Mark this on BlogMarks', 'social-it' ),
		'baseUrl'	=> 'http://blogmarks.net/my/new.php?mini=1&simple=1&url=PERMALINK&title=TITLE',
	),
	'socialit-twittley'	=> array(
		'check'		=> sprintf( $si_checkthis_text, __( 'Twittley', 'social-it' ) ),
		'share'		=> sprintf( __( 'Submit this to %s', 'social-it' ), __( 'Twittley', 'social-it' ) ),
		'baseUrl'	=> 'http://twittley.com/submit/?title=TITLE&url=PERMALINK&desc=POST_SUMMARY&pcat=TWITT_CAT&tags=DEFAULT_TAGS',
	),
	'socialit-fwisp'	=> array(
		'check'		=> sprintf( $si_checkthis_text, __( 'Fwisp', 'social-it' ) ),
		'share'		=> sprintf( __( 'Share this on %s', 'social-it' ), __( 'Fwisp', 'social-it' ) ),
		'baseUrl'	=> 'http://fwisp.com/submit?url=PERMALINK',
	),
	'socialit-bobrdobr'	=> array(
		'check'		=> sprintf( $si_checkthis_text, __( 'BobrDobr', 'social-it' ) ) . __( ' (Russian)', 'social-it' ),
		'share'		=> sprintf( __( 'Share this on %s', 'social-it' ), __( 'BobrDobr', 'social-it' ) ),
		'baseUrl'	=> 'http://bobrdobr.ru/addext.html?url=PERMALINK&title=TITLE',
	),
	'socialit-yandex'	=> array(
		'check'		=> sprintf( $si_checkthis_text, __( 'Yandex.Bookmarks', 'social-it' ) ) . __( ' (Russian)', 'social-it' ),
		'share'		=> sprintf( __( 'Add this to %s', 'social-it' ), __( 'Yandex.Bookmarks', 'social-it' ) ),
		'baseUrl'	=> 'http://zakladki.yandex.ru/userarea/links/addfromfav.asp?bAddLink_x=1&lurl=PERMALINK&lname=TITLE',
	),
	'socialit-memoryru'	=> array(
		'check'		=> sprintf( $si_checkthis_text, __( 'Memory.ru', 'social-it' ) ) . __( ' (Russian)', 'social-it' ),
		'share'		=> sprintf( __( 'Add this to %s', 'social-it' ), __( 'Memory.ru', 'social-it' ) ),
		'baseUrl'	=> 'http://memori.ru/link/?sm=1&u_data[url]=PERMALINK&u_data[name]=TITLE',
	),
	'socialit-100zakladok'	=> array(
		'check'		=> sprintf( $si_checkthis_text, __( '100 bookmarks', 'social-it' ) ) . __( ' (Russian)', 'social-it' ),
		'share'		=> sprintf( __( 'Add this to %s', 'social-it' ), __( '100 bookmarks', 'social-it' ) ),
		'baseUrl'	=> 'http://www.100zakladok.ru/save/?bmurl=PERMALINK&bmtitle=TITLE',
	),
	'socialit-moemesto'	=> array(
		'check'		=> sprintf( $si_checkthis_text, __( 'MyPlace', 'social-it' ) ) . __( ' (Russian)', 'social-it' ),
		'share'		=> sprintf( __( 'Add this to %s', 'social-it' ), __( 'MyPlace', 'social-it' ) ),
		'baseUrl'	=> 'http://moemesto.ru/post.php?url=PERMALINK&title=TITLE',
	),
	'socialit-hackernews'	=> array(
		'check'		=> sprintf( $si_checkthis_text, __( 'Hacker News', 'social-it' ) ),
		'share'		=> sprintf( __( 'Submit this to %s', 'social-it' ), __( 'Hacker News', 'social-it' ) ),
		'baseUrl'	=> 'http://news.ycombinator.com/submitlink?u=PERMALINK&t=TITLE',
	),
	'socialit-printfriendly'	=> array(
		'check'		=> sprintf( $si_checkthis_text, __( 'Print Friendly', 'social-it' ) ),
		'share'		=> __( 'Send this page to Print Friendly', 'social-it' ),
		'baseUrl'	=> 'http://www.printfriendly.com/print?url=PERMALINK',
	),
	'socialit-designbump'	=> array(
		'check'		=> sprintf( $si_checkthis_text, __( 'Design Bump', 'social-it' ) ),
		'share'		=> __( 'Bump this on DesignBump', 'social-it' ),
		'baseUrl'	=> 'http://designbump.com/submit?url=PERMALINK&title=TITLE&body=POST_SUMMARY',
	),
	'socialit-ning'	=> array(
		'check'		=> sprintf( $si_checkthis_text, __( 'Ning', 'social-it' ) ),
		'share'		=> sprintf( __( 'Add this to %s', 'social-it' ), __( 'Ning', 'social-it' ) ),
		'baseUrl'	=> 'http://bookmarks.ning.com/addItem.php?url=PERMALINK&T=TITLE',
	),
	'socialit-identica'	=> array(
		'check'		=> sprintf( $si_checkthis_text, __( 'Identica', 'social-it' ) ),
		'share'		=> sprintf( __( 'Post this to %s', 'social-it' ), __( 'Identica', 'social-it' ) ),
		'baseUrl'	=> 'http://identi.ca//index.php?action=newnotice&status_textarea=Reading:+&quot;SHORT_TITLE&quot;+-+from+FETCH_URL',
	),
	'socialit-xerpi'	=> array(
		'check'		=> sprintf( $si_checkthis_text, __( 'Xerpi', 'social-it' ) ),
		'share'		=> __( 'Save this to Xerpi', 'social-it' ),
		'baseUrl'	=> 'http://www.xerpi.com/block/add_link_from_extension?url=PERMALINK&title=TITLE',
	),
	'socialit-wikio'	=> array(
		'check'		=> sprintf( $si_checkthis_text, __( 'Wikio', 'social-it' ) ),
		'share'		=> sprintf( __( 'Share this on %s', 'social-it' ), __( 'Wikio', 'social-it' ) ),
		'baseUrl'	=> 'http://www.wikio.com/sharethis?url=PERMALINK&title=TITLE',
	),
	'socialit-techmeme'	=> array(
		'check'		=> sprintf( $si_checkthis_text, __( 'TechMeme', 'social-it' ) ),
		'share'		=> __( 'Tip this to TechMeme', 'social-it' ),
		'baseUrl'	=> 'http://twitter.com/home/?status=Tip+@Techmeme+PERMALINK+&quot;TITLE&quot;',
	),
	'socialit-sphinn'	=> array(
		'check'		=> sprintf( $si_checkthis_text, __( 'Sphinn', 'social-it' ) ),
		'share'		=> __( 'Sphinn this on Sphinn', 'social-it' ),
		'baseUrl'	=> 'http://sphinn.com/index.php?c=post&m=submit&link=PERMALINK',
	),
	'socialit-posterous'	=> array(
		'check'		=> sprintf( $si_checkthis_text, __( 'Posterous', 'social-it' ) ),
		'share'		=> sprintf( __( 'Post this to %s', 'social-it' ), __( 'Posterous', 'social-it' ) ),
		'baseUrl'	=> 'http://posterous.com/share?linkto=PERMALINK&title=TITLE&selection=POST_SUMMARY',
	),
	'socialit-globalgrind'	=> array(
		'check'		=> sprintf( $si_checkthis_text, __( 'Global Grind', 'social-it' ) ),
		'share'		=> __( 'Grind this on Global Grind', 'social-it' ),
		'baseUrl'	=> 'http://globalgrind.com/submission/submit.aspx?url=PERMALINK&type=Article&title=TITLE',
	),
	'socialit-pingfm'	=> array(
		'check'		=> sprintf( $si_checkthis_text, __( 'Ping.fm', 'social-it' ) ),
		'share'		=> __( 'Ping this on Ping.fm', 'social-it' ),
		'baseUrl'	=> 'http://ping.fm/ref/?link=PERMALINK&title=TITLE&body=POST_SUMMARY',
	),
	'socialit-nujij'	=> array(
		'check'		=> sprintf( $si_checkthis_text, __( 'NUjij', 'social-it' ) ) . __( ' (Dutch)', 'social-it' ),
		'share'		=> sprintf( __( 'Submit this to %s', 'social-it' ), __( 'NUjij', 'social-it' ) ),
		'baseUrl'	=> 'http://nujij.nl/jij.lynkx?t=TITLE&u=PERMALINK&b=POST_SUMMARY',
	),
	'socialit-ekudos'	=> array(
		'check'		=> sprintf( $si_checkthis_text, __( 'eKudos', 'social-it' ) ) . __( ' (Dutch)', 'social-it' ),
		'share'		=> sprintf( __( 'Submit this to %s', 'social-it' ), __( 'eKudos', 'social-it' ) ),
		'baseUrl'	=> 'http://www.ekudos.nl/artikel/nieuw?url=PERMALINK&title=TITLE&desc=POST_SUMMARY',
	),
	'socialit-netvouz'	=> array(
		'check'		=> sprintf( $si_checkthis_text, __( 'Netvouz', 'social-it' ) ),
		'share'		=> sprintf( __( 'Submit this to %s', 'social-it' ), __( 'Netvouz', 'social-it' ) ),
		'baseUrl'	=> 'http://www.netvouz.com/action/submitBookmark?url=PERMALINK&title=TITLE&popup=no',
	),
	'socialit-netvibes'	=> array(
		'check'		=> sprintf( $si_checkthis_text, __( 'Netvibes', 'social-it' ) ),
		'share'		=> sprintf( __( 'Submit this to %s', 'social-it' ), __( 'Netvibes', 'social-it' ) ),
		'baseUrl'	=> 'http://www.netvibes.com/share?title=TITLE&url=PERMALINK',
	),
	'socialit-fleck'	=> array(
		'check'		=> sprintf( $si_checkthis_text, __( 'Fleck', 'social-it' ) ),
		'share'		=> sprintf( __( 'Share this on %s', 'social-it' ), __( 'Fleck', 'social-it' ) ),
		'baseUrl'	=> 'http://beta3.fleck.com/bookmarklet.php?url=PERMALINK&title=TITLE',
	),
	'socialit-webblend'	=> array(
		'check'		=> sprintf( $si_checkthis_text, __( 'Web Blend', 'social-it' ) ),
		'share'		=> __( 'Blend this!', 'social-it' ),
		'baseUrl'	=> 'http://thewebblend.com/submit?url=PERMALINK&title=TITLE&body=POST_SUMMARY',
	),
	'socialit-wykop'	=> array(
		'check'		=> sprintf( $si_checkthis_text, __( 'Wykop', 'social-it' ) ) . __( ' (Polish)', 'social-it' ),
		'share'		=> sprintf( __( 'Add this to %s', 'social-it' ), __( 'Wykop', 'social-it' ) ),
		'baseUrl'	=> 'http://www.wykop.pl/dodaj?url=PERMALINK&title=TITLE',
	),
	'socialit-blogengage'	=> array(
		'check'		=> sprintf( $si_checkthis_text, __( 'BlogEngage', 'social-it' ) ),
		'share'		=> __( 'Engage with this article on BlogEngage!', 'social-it' ),
		'baseUrl'	=> 'http://www.blogengage.com/submit.php?url=PERMALINK',
	),
	'socialit-hyves'	=> array(
		'check'		=> sprintf( $si_checkthis_text, __( 'Hyves', 'social-it' ) ),
		'share'		=> sprintf( __( 'Share this on %s', 'social-it' ), __( 'Hyves', 'social-it' ) ),
		'baseUrl'	=> 'http://www.hyves.nl/profilemanage/add/tips/?name=TITLE&text=POST_SUMMARY+-+PERMALINK&rating=5',
	),
	'socialit-pusha'	=> array(
		'check'		=> sprintf( $si_checkthis_text, __( 'Pusha', 'social-it' ) ) . __( ' (Swedish)', 'social-it' ),
		'share'		=> __( 'Push this on Pusha', 'social-it' ),
		'baseUrl'	=> 'http://www.pusha.se/posta?url=PERMALINK&title=TITLE',
	),
	'socialit-hatena'	=> array(
		'check'		=> sprintf( $si_checkthis_text, __( 'Hatena Bookmarks', 'social-it' ) ) . __( ' (Japanese)', 'social-it' ),
		'share'		=> __( 'Bookmarks this on Hatena Bookmarks', 'social-it' ),
		'baseUrl'	=> 'http://b.hatena.ne.jp/add?mode=confirm&url=PERMALINK&title=TITLE',
	),
	'socialit-mylinkvault'	=> array(
		'check'		=> sprintf( $si_checkthis_text, __( 'MyLinkVault', 'social-it' ) ),
		'share'		=> __( 'Store this link on MyLinkVault', 'social-it' ),
		'baseUrl'	=> 'http://www.mylinkvault.com/link-page.php?u=PERMALINK&n=TITLE',
	),
	'socialit-slashdot'	=> array(
		'check'		=> sprintf( $si_checkthis_text, __( 'SlashDot', 'social-it' ) ),
		'share'		=> sprintf( __( 'Submit this to %s', 'social-it' ), __( 'SlashDot', 'social-it' ) ),
		'baseUrl'	=> 'http://slashdot.org/bookmark.pl?url=PERMALINK&title=TITLE',
	),
	'socialit-squidoo'	=> array(
		'check'		=> sprintf( $si_checkthis_text, __( 'Squidoo', 'social-it' ) ),
		'share'		=> __( 'Add to a lense on Squidoo', 'social-it' ),
		'baseUrl'	=> 'http://www.squidoo.com/lensmaster/bookmark?PERMALINK',
	),
	'socialit-propeller'	=> array(
		'check'		=> sprintf( $si_checkthis_text, __( 'Propeller', 'social-it' ) ),
		'share'		=> sprintf( __( 'Submit this to %s', 'social-it' ), __( 'Propeller', 'social-it' ) ),
		'baseUrl'	=> 'http://www.propeller.com/submit/?url=PERMALINK',
	),
	'socialit-faqpal'	=> array(
		'check'		=> sprintf( $si_checkthis_text, __( 'FAQpal', 'social-it' ) ),
		'share'		=> sprintf( __( 'Submit this to %s', 'social-it' ), __( 'FAQpal', 'social-it' ) ),
		'baseUrl'	=> 'http://www.faqpal.com/submit?url=PERMALINK',
	),
	'socialit-evernote'	=> array(
		'check'		=> sprintf( $si_checkthis_text, __( 'Evernote', 'social-it' ) ),
		'share'		=> __( 'Clip this to Evernote', 'social-it' ),
		'baseUrl'	=> 'http://www.evernote.com/clip.action?url=PERMALINK&title=TITLE',
	),
	'socialit-meneame'	=> array(
		'check'		=> sprintf( $si_checkthis_text, __( 'Meneame', 'social-it' ) ) . __( ' (Spanish)', 'social-it' ),
		'share'		=> sprintf( __( 'Submit this to %s', 'social-it' ), __( 'Meneame', 'social-it' ) ),
		'baseUrl'	=> 'http://meneame.net/submit.php?url=PERMALINK',
	),
	'socialit-bitacoras'	=> array(
		'check'		=> sprintf( $si_checkthis_text, __( 'Bitacoras', 'social-it' ) ) . __( ' (Spanish)', 'social-it' ),
		'share'		=> sprintf( __( 'Submit this to %s', 'social-it' ), __( 'Bitacoras', 'social-it' ) ),
		'baseUrl'	=> 'http://bitacoras.com/anotaciones/PERMALINK',
	),
	'socialit-jumptags'	=> array(
		'check'		=> sprintf( $si_checkthis_text, __( 'JumpTags', 'social-it' ) ),
		'share'		=> sprintf( __( 'Submit this to %s', 'social-it' ), __( 'JumpTags', 'social-it' ) ),
		'baseUrl'	=> 'http://www.jumptags.com/add/?url=PERMALINK&title=TITLE',
	),
	'socialit-bebo'	=> array(
		'check'		=> sprintf( $si_checkthis_text, __( 'Bebo', 'social-it' ) ),
		'share'		=> sprintf( __( 'Share this on %s', 'social-it' ), __( 'Bebo', 'social-it' ) ),
		'baseUrl'	=> 'http://www.bebo.com/c/share?Url=PERMALINK&Title=TITLE',
	),
	'socialit-n4g'	=> array(
		'check'		=> sprintf( $si_checkthis_text, __( 'N4G', 'social-it' ) ),
		'share'		=> sprintf( __( 'Submit this to %s', 'social-it' ), __( 'N4G', 'social-it' ) ),
		'baseUrl'	=> 'http://www.n4g.com/tips.aspx?url=PERMALINK&title=TITLE',
	),
	'socialit-strands'	=> array(
		'check'		=> sprintf( $si_checkthis_text, __( 'Strands', 'social-it' ) ),
		'share'		=> sprintf( __( 'Submit this to %s', 'social-it' ), __( 'Strands', 'social-it' ) ),
		'baseUrl'	=> 'http://www.strands.com/tools/share/webpage?title=TITLE&url=PERMALINK',
	),
	'socialit-orkut'	=> array(
		'check'		=> sprintf( $si_checkthis_text, __( 'Orkut', 'social-it' ) ),
		'share'		=> __( 'Promote this on Orkut', 'social-it' ),
		'baseUrl'	=> 'http://promote.orkut.com/preview?nt=orkut.com&tt=TITLE&du=PERMALINK&cn=POST_SUMMARY',
	),
	'socialit-tumblr'	=> array(
		'check'		=> sprintf( $si_checkthis_text, __( 'Tumblr', 'social-it' ) ),
		'share'		=> sprintf( __( 'Share this on %s', 'social-it' ), __( 'Tumblr', 'social-it' ) ),
		'baseUrl'	=> 'http://www.tumblr.com/share?v=3&u=PERMALINK&t=TITLE',
	),
	'socialit-stumpedia'	=> array(
		'check'		=> sprintf( $si_checkthis_text, __( 'Stumpedia', 'social-it' ) ),
		'share'		=> sprintf( __( 'Add this to %s', 'social-it' ), __( 'Stumpedia', 'social-it' ) ),
		'baseUrl'	=> 'http://www.stumpedia.com/submit?url=PERMALINK&title=TITLE',
	),
	'socialit-current'	=> array(
		'check'		=> sprintf( $si_checkthis_text, __( 'Current', 'social-it' ) ),
		'share'		=> sprintf( __( 'Post this to %s', 'social-it' ), __( 'Current', 'social-it' ) ),
		'baseUrl'	=> 'http://current.com/clipper.htm?url=PERMALINK&title=TITLE',
	),
	'socialit-blogger'	=> array(
		'check'		=> sprintf( $si_checkthis_text, __( 'Blogger', 'social-it' ) ),
		'share'		=> __( 'Blog this on Blogger', 'social-it' ),
		'baseUrl'	=> 'http://www.blogger.com/blog_this.pyra?t&u=PERMALINK&n=TITLE&pli=1',
	),
	'socialit-plurk'	=> array(
		'check'		=> sprintf( $si_checkthis_text, __( 'Plurk', 'social-it' ) ),
		'share'		=> sprintf( __( 'Share this on %s', 'social-it' ), __( 'Plurk', 'social-it' ) ),
		'baseUrl'	=> 'http://www.plurk.com/m?content=TITLE+-+PERMALINK&qualifier=shares',
	),
	'socialit-dzone'	=> array(
		'check'		=> sprintf( $si_checkthis_text, __( 'DZone', 'social-it' ) ),
		'share'		=> sprintf( __( 'Add this to %s', 'social-it' ), __( 'DZone', 'social-it' ) ),
		'baseUrl'	=> 'http://www.dzone.com/links/add.html?url=PERMALINK&title=TITLE&description=POST_SUMMARY',
	),	
	'socialit-kaevur'	=> array(
		'check'		=> sprintf( $si_checkthis_text, __( 'Kaevur', 'social-it' ) ) . __( ' (Estonian)', 'social-it' ),
		'share'		=> sprintf( __( 'Share this on %s', 'social-it' ), __( 'Kaevur', 'social-it' ) ),
		'baseUrl'	=> 'http://kaevur.com/submit.php?url=PERMALINK',
	),
	'socialit-virb'	=> array(
		'check'		=> sprintf( $si_checkthis_text, __( 'Virb', 'social-it' ) ),
		'share'		=> sprintf( __( 'Share this on %s', 'social-it' ), __( 'Virb', 'social-it' ) ),
		'baseUrl'	=> 'http://virb.com/share?external&v=2&url=PERMALINK&title=TITLE',
	),	
	'socialit-boxnet'	=> array(
		'check'		=> sprintf( $si_checkthis_text, __( 'Box.net', 'social-it' ) ),
		'share'		=> sprintf( __( 'Add this to %s', 'social-it' ), __( 'Box.net', 'social-it' ) ),
		'baseUrl'	=> 'https://www.box.net/api/1.0/import?url=PERMALINK&name=TITLE&description=POST_SUMMARY&import_as=link',
	),
	'socialit-plaxo'	=> array(
		'check'		=> sprintf( $si_checkthis_text, __( 'Plaxo', 'social-it' ) ),
		'share'		=> sprintf( __( 'Share this on %s', 'social-it' ), __( 'Plaxo', 'social-it' ) ),
		'baseUrl'	=> 'http://www.plaxo.com/?share_link=PERMALINK',
	),
	'socialit-springpad'	=> array(
		'check'		=> sprintf( $si_checkthis_text, __( 'SpringPad', 'social-it' ) ),
		'share'		=> __( 'Spring this on SpringPad', 'social-it' ),
		'baseUrl'	=> 'http://springpadit.com/clip.action?body=POST_SUMMARY&url=PERMALINK&format=microclip&title=TITLE&isSelected=true',
	),
	'socialit-zabox'	=> array(
		'check'		=> sprintf( $si_checkthis_text, __( 'Zabox', 'social-it' ) ),
		'share'		=> __( 'Box this on Zabox', 'social-it' ),
		'baseUrl'	=> 'http://www.zabox.net/submit.php?url=PERMALINK',
	),
	'socialit-viadeo'	=> array(
		'check'		=> sprintf( $si_checkthis_text, __( 'Viadeo', 'social-it' ) ),
		'share'		=> sprintf( __( 'Share this on %s', 'social-it' ), __( 'Viadeo', 'social-it' ) ),
		'baseUrl'	=> 'http://www.viadeo.com/shareit/share/?url=PERMALINK&title=TITLE&urlaffiliate=31138',
	),
	'socialit-gmail'	=> array(
		'check'		=> sprintf( $si_checkthis_text, __( 'Gmail', 'social-it' ) ),
		'share'		=> sprintf( __( 'Email this via %s', 'social-it' ), __( 'Gmail', 'social-it' ) ),
		'baseUrl'	=> 'https://mail.google.com/mail/?ui=2&view=cm&fs=1&tf=1&su=TITLE&body=Link:%20PERMALINK%0D%0A%0D%0A----%0D%0APOST_SUMMARY',
	),
	'socialit-hotmail'	=> array(
		'check'		=> sprintf( $si_checkthis_text, __( 'Hotmail', 'social-it' ) ),
		'share'		=> sprintf( __( 'Email this via %s', 'social-it' ), __( 'Hotmail', 'social-it' ) ),
		'baseUrl'	=> 'http://mail.live.com/?rru=compose?subject=TITLE&body=Link:%20PERMALINK%0D%0A%0D%0A----%0D%0APOST_SUMMARY',
	),
	'socialit-yahoomail'	=> array(
		'check'		=> sprintf( $si_checkthis_text, __( 'Yahoo! Mail', 'social-it' ) ),
		'share'		=> sprintf( __( 'Email this via %s', 'social-it' ), __( 'Yahoo! Mail', 'social-it' ) ),
		'baseUrl'	=> 'http://compose.mail.yahoo.com/?Subject=TITLE&body=Link:%20PERMALINK%0D%0A%0D%0A----%0D%0APOST_SUMMARY',
	),
	'socialit-buzzster'	=> array(
		'check'		=> sprintf( $si_checkthis_text, __( 'Buzzster!', 'social-it' ) ),
		'share'		=> sprintf( __( 'Share this via %s', 'social-it' ), __( 'Buzzster!', 'social-it' ) ),
		'baseUrl'	=> "javascript:var%20s=document.createElement( 'script' );s.src='http://www.buzzster.com/javascripts/bzz_adv.js';s.type='text/javascript';void(document.getElementsByTagName( 'head' )[0].appendChild(s));",
	),
);
ksort( $socialit_bookmarks_data, SORT_STRING ); /* Sort array by keys */

?>