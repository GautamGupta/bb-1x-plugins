=== bbPress Attachments Plus ===
Tags: attachments, attachment, attach, uploads, upload, files, aws, s3, Gautam
Contributors: Gautam Gupta
Requires at least: 1.0
Tested up to: 1.1
Stable tag: 0.1
Donate link: http://gaut.am/donate/bb/AP/

Gives members the ability to upload attachments on their posts. Please note there are important security considerations when allowing uploads of any kind to your server.

== Description ==

Gives members the ability to upload attachments on their posts.
Feedback, bug reports, feature ideas, improvements are encouraged. 
Please note there are important security considerations when allowing uploads of any kind to your server.

== Installation ==

1. Make a directory `/bb-attachments/` ABOVE your webroot i.e. `/home/username/bb-attachments/` and `chmod 777` it
2. Upload the extracted `bb-attachments-plus` folder to the `/my-plugins/` directory
3. Activate the plugin through the 'Plugins' menu in bbPress
4. Optional - Change the plugin's settings by going to `Settings` -> `Attachments Plus`
5. Enjoy!

== Frequently Asked Questions ==

= General =
* Members's ability to upload attachments is tied to their ability to edit post, i.e. if edit ends in 1 hour, so does adding attachments
* The plugin will try to create the base upload directory itself, but in most cases will fail so you need to follow the first installation step
* If available, posix is used to write files with owner's id so you can delete/move files manually via FTP
* Needs PHP >= 4.3
* Filesize max might be 2mb because of passthrough/readfile limit (supposedly fixed in newer PHP)
* Administrators can debug settings (ie. PHP upload limit) by adding to url `?bb_attachments_diagnostic`
* If you get `error: denied mime` on every upload, mime_content_type function or shell access must exist to verify mime types - otherwise you can force all types to be allowed by editing `bb-attachments-plus.php` and adding `'application/octet-stream'` to each of the `$bb_attachments['allowed']['mime_types']`

= Amazon AWS S3 Simple Storage Service =
* bb Attachments Plus supports Amazon S3 service. This feature is sponsored by weddingbee.com who donated towards it, so be sure to thank them
* This feature requires fsockopen and fwrite support which most hosts should allow but check your phpinfo if you know your host locks out some features or runs in "safe mode"
* Register at http://amazon.com/s3/ and enter your [key and secret code](https://aws-portal.amazon.com/gp/aws/developer/account/index.html#AccessKey) into the bb-attachments settings
* Files are first uploaded and stored on your own server as normal for a mirrored backup (S3 goes down occasionally)
* Users then will be automagically routed to the S3 url instead of your local URL
* You can setup a [CNAME](http://docs.amazonwebservices.com/AmazonS3/2006-03-01/VirtualHosting.html#VirtualHostingCustomURLs) to make it appear as files are actually on your own server
* If you have been using bb-attachments without S3, you must manually copy the existing files on your server to S3 via one of the many S3 utilities available - I will eventually make some sync routines but it might be awhile
* If S3 goes down or you decide not to use them anymore, you can simply turn off the option and it automagically will return to using your local files
* If an attachment is deleted, it is not remotely deleted off S3 at this time - one GB of "deleted" files only costs you 15 cents per month

== Screenshots ==

1. bb Attachments Plus Plugin in Action
2. A Screenshot of the Settings Page

== Other Notes ==

= Translations =
You can contribute by translating this plugin. Please refer to [this post](http://gaut.am/translating-wordpress-or-bbpress-plugins/) to know how to translate.

= To Do =
* Map mime types to match extensions?
* Check for file duplicates before saving
* Thumbnails for image attachments
* Pre-validate upload filenames via javascript to spare user upload time with rejection

= License =
GNU General Public License version 3 (GPLv3): http://www.opensource.org/licenses/gpl-3.0.html

= Donate =
You may donate by going [here](http://gaut.am/donate/bb/AP/).

== Changelog ==

= 0.1 =
* Initial release