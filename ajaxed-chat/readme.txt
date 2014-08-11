=== Ajaxed Chat ===
Contributors: Gautam Gupta
Donate link: http://gaut.am/donate/bb/AJ/
Tags: chat, room, ajax, live, talk, chatbox, phpfreechat, Gautam
Requires at least: 1.0
Tested up to: 1.1
Stable tag: 1.1

Ajaxed Chat adds a fast, customizable, multi-language Chat Room to your Forums and uses a simple file-system/mysql storage for messages and nicknames. It uses PHPFreeChat Script (phpfreechat.net).

== Description ==

Ajaxed Chat adds a fast, customizable, multi-language Chat Room to your Forums and uses a simple file-system/mysql storage for messages and nicknames.
It uses AJAX to smoothly refresh and display the chat zone and the nickname zone.
It supports multiple rooms (`/join`), private messages, moderation (`/kick`, `/ban`), and more!

You can view the chat by going to `http://example.com/?chat` or by placing this code anywhere in your website's template:
`&lt;?php do_action( 'ajaxed_chat' ); ?&gt;`

This plugin uses [PHPFreeChat Script](http://www.phpfreechat.net/).
The commands which can be used in the chat room, can be viewed [here](http://www.phpfreechat.net/commands).

== Installation ==

1. Upload the extracted `ajaxed-chat` folder to the `/my-plugins/` directory
2. Activate the plugin through the 'Plugins' menu in bbPress
3. In the admin menu, go to `Settings` -> `Ajaxed Chat` and adjust the settings to your liking.
4. To refresh your settings after making changes, change your name to admin with the command `/nick` admin and type `/identify <password>` the password being the one you set in options, then type `/help` to get a list of commands, `/rehash` resets all the settings to what was changed in the settings page.
5. Put `&lt;?php do_action( 'ajaxed_chat' ); ?&gt;` anywhere in your template to show the chat.
6. Enjoy Chatting!

== Frequently Asked Questions ==

Please read the FAQ [here](http://www.phpfreechat.net/faq).

== Screenshots ==

1. A quick preview of the chat room
2. Screenshot of the settings page

== Changelog ==

= 1.1 (13-04-2010) =
* Updated PHPFreeChat - [Changelog](http://www.phpfreechat.net/changelog/1.3)
* Updated the method to include the chat room
* Improvements in coding efficiency and improvements
* Switched to bbPress style admin settings page (Check screenshots)

= 1.0 (29-09-2009) =
* Initial Release