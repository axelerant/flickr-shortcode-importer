=== Flickr Shortcode Importer by Aihrus ===

Contributors: comprock
Donate link: http://aihr.us/about-aihrus/donate/
Tags: flickr,import,featured image,photo,image,video
Requires at least: 3.4
Tested up to: 3.8.0
Stable tag: 2.1.0RC2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Flickr Shortcode Importer by Aihrus imports [flickr], [flickrset], [flickr-gallery] shortcodes and Flickr-sourced media into the Media Library.


== Description ==

Flickr Shortcode Importer [by Aihrus](http://aihr.us/about-aihrus/) imports [flickr], [flickrset], [flickr-gallery] shortcode and Flickr-sourced A/IMG tagged media into the Media Library. Furthermore, it transforms the post content [flickr] shortcodes into links containing the Media Library based image of the proper size and alignment.

Import can be run directly from edit page and post screens.

The first [flickr] image found in post content is set as the post's Featured Image and removed from the post content. The remaining [flickr] shortcodes are then transformed as image links to their attachment page.  

[flickrset] and [flickr-gallery] shortcodes are converted to [ gallery] after the Flickr set images have been added to the Media Library. If 'Set Featured Image' is checked in Options, then the first image of the [flickrset] is used as such.

Flickr-sourced A/IMG tagged media is converted into [flickr] and then imported as normal. Great for finally bringing into your control all of those media items you've been using, but now Flickr is giving you 'Image is unavaiable' for. A/IMG tag is processed before IMG to prevent unexpected results.

Image attribution links can be added if enabled via Settings.

This plugin is handy for transitioning from plugin `wordpress-flickr-manager` and `flickr-gallery` to your own Media Library because you have CDN services or want to move off of third party software.

There is no restore functionality. Backup beforehand or be prepared to revert every transformed post by hand via the post revision tool.

= Primary Features =

* API
* Automatically adds media ids to newly inserted gallery shortcodes
* Flickr-sourced A/IMG tagged media imported into WordPress
* Media of [flick] shortcodes are imported and converted to locally hosted A/IMG
* Media of [flickrset] and [flickr-gallery] shortcodes are imported and converted to [ gallery]
* Settings export/import
* Settings screen

= Handled shortcode & media samples =

* [flickr size="small" float="left"]http://www.flickr.com/photos/dancoulter/2619594365/[/flickr] (image)
* [flickr height="300" width="400"]http://www.flickr.com/photos/dancoulter/2422361554/[/flickr] (video)
* WARNING: Video media imported, but doesn't seem to work
* [flickr id="5348222727" thumbnail="small" overlay="false" size="large" group="" align="none"]
* [flickrset id="72157631107721746" thumbnail="small" photos="" overlay="true" size="large"]
* [flickr-gallery mode="photoset" photoset="72157626302265777"]
* [flickr-gallery mode="tag" tags="foo,bar" tag_mode="all"]
* [flickr-gallery mode="interesting"]
* [flickr-gallery mode="recent"]
* [flickr-gallery mode="search" tags="barcamp" group_id="431412@N25"]
* `<a class="tt-flickr tt-flickr-Medium" title="Khan Sao Road, Bangkok, Thailand" href="http://www.flickr.com/photos/comprock/4334303694/" target="_blank"><img class="alignnone" src="http://farm3.static.flickr.com/2768/4334303694_37785d0f0d.jpg" alt="Khan Sao Road, Bangkok, Thailand" width="500" height="375" /></a>`
* `<img class="alignnone" src="http://farm3.static.flickr.com/2768/4334303694_37785d0f0d.jpg" alt="Khan Sao Road, Bangkok, Thailand" width="500" height="375" />`

= Settings Options =

**Import Settings**

* Skip Importing Videos - Importing videos from Flickr often fails. Shortcode is still converted to object/embed linking to Flickr.
* Import Flickr-sourced A/IMG tags - Converts Flickr-sourced A/IMG tags to [flickr] and then proceeds with import.
* Set Featured Image - Set the first [flickr] or [flickrset] image found as the Featured Image. Will not replace the current Featured Image of a post.
* Force Set Featured Image - Set the Featured Image even if one already exists for a post.
* Remove First Flickr Shortcode - Removes the first [flickr] from post content. If you use Featured Images as header or lead images, then this might prevent duplicate images in your post.
* Make Nice Image Title? - Try to make a nice title if none is set. For Flickr set images, Flickr set title plus a numeric suffix is applied.
* Replace Filename with Image Title? - Mainly for SEO purposes. This setting replaces the imported media filename with the media's title. For non-images, this is always done.
* Image Import Size - Size of image to import into media library from Flickr. If requested size doesn't exist, then original is imported because it's the closest to the requested import size.
* Default Image Alignment - Default alignment of image displayed in post when no alignment is found.
* Default Image Size - Default size of image displayed in post when no size is found.
* Default A Tag Class - Inserts a class into links around imported images. Useful for lightbox'ing.
* Link Image to Attachment Page? - If set, post single view images are linked to attachment pages. Otherwise the image links to its source file.
* Image Wrap Class - If set, a span tag is wrapped around the image with the given class. Also wraps attribution if enabled. e.g. Providing `flickr-image` results in `&lt;span class="flickr-image"&gt;|&lt;/span&gt;`
* Set Captions - Uses media title as the caption.
* Set Descriptions - Include Flickr descriptions in media information.
* Include Flickr Author Attribution? - Appends Flickr username, linked back to Flickr image to the imported Flickr image.
* Flickr Author Attribution Text
* Flickr Author Attribution Wrap Class - If set, a span tag is wrapped around the attribution with the given class. e.g. Providing `flickr-attribution` results in `&lt;span class="flickr-attribution"&gt;|&lt;/span&gt;`
* Add Flickr Link in Description? - Like `Include Flickr Author Attribution` but appends the image description.
* Flickr Link Text
* Add Image License to Description?	- Append image license and link to image description.
* Flickr Image License Text

**Posts Selection**

* Posts to Import - A CSV list of post ids to import, like '1,2,3'.
* Skip Importing Posts - A CSV list of post ids not to import, like '1,2,3'.

**Testing Options**

* Import Limit - Useful for testing import on a limited amount of posts. 0 or blank means unlimited.
* Debug Mode - Bypass Ajax controller to handle posts_to_import directly for testing purposes.

**Post Options**

* Post [flickr] Import Widget? - Minimum role to enable for [flickr] Import wi
dget on posts and page edit screens.
* Enable for Pages
* Enable for Posts
* Enable for Media
* Enable for custom post types - if any

**Flickr API**

* Flickr API Key - [Flickr API Documentation](http://www.flickr.com/services/api/)
* Flickr API Secret
* Flickr User ID - For Flickr Gallery plugin. Example: 90901451@N00
* Images Per Page - For Flickr Gallery plugin.

**Reset**

* Reimport Flickr Source Images - Needed when changing the Flickr image import size from prior imports.
* Export Settings – These are your current settings in a serialized format. Copy the contents to make a backup of your settings.
* Import Settings – Paste new serialized settings here to overwrite your current configuration.
* Remove Plugin Data on Deletion? - Delete all Flickr Shortcode Importer data and options from database on plugin deletion
* Reset to Defaults? – Check this box to reset options to their defaults


== Installation ==

= Requirements =

* TBD

= Install Methods =

* Through WordPress Admin > Plugins > Add New, Search for "Flickr Shortcode Importer"
	* Find "Flickr Shortcode Importer by Aihrus"
	* Click "Install Now" of "Flickr Shortcode Importer by Aihrus"
* Download [`flickr-shortcode-importer.zip`](http://downloads.wordpress.org/plugin/flickr-shortcode-importer.zip) locally
	* Through WordPress Admin > Plugins > Add New
	* Click Upload
	* "Choose File" `flickr-shortcode-importer.zip`
	* Click "Install Now"
* Download and unzip [`flickr-shortcode-importer.zip`](http://downloads.wordpress.org/plugin/flickr-shortcode-importer.zip) locally
	* Using FTP, upload directory `flickr-shortcode-importer` to your website's `/wp-content/plugins/` directory

= Activation Options =

* Activate the "Flickr Shortcode Importer" plugin after uploading
* Activate the "Flickr Shortcode Importer" plugin through WordPress Admin > Plugins

= Usage =

1. Edit defaults through WordPress > Settings > [flickr] Options
1. Import through WordPress > Tools > [flickr] Importer
1. When editing posts, check Import [flickr] content before clicking "Publish" or "Update"

= Upgrading =

* Through WordPress
	* Via WordPress Admin > Dashboard > Updates, click "Check Again"
	* Select plugins for update, click "Update Plugins"
* Using FTP
	* Download and unzip [`flickr-shortcode-importer.zip`](http://downloads.wordpress.org/plugin/flickr-shortcode-importer.zip) locally
	* Upload directory `flickr-shortcode-importer` to your website's `/wp-content/plugins/` directory
	* Be sure to overwrite your existing `flickr-shortcode-importer` folder contents


== Frequently Asked Questions ==

= Most Common Issues =

* Got `Parse error: syntax error, unexpected T_STATIC, expecting ')'`? Read [Most Aihrus Plugins Require PHP 5.3+](https://aihrus.zendesk.com/entries/30678006) for the fixes.
* [Debug common theme and plugin conflicts](https://aihrus.zendesk.com/entries/25119302)

= Still Stuck or Want Something Done? Get Support! =

1. [Open Flickr Shortcode Importer Issues](https://github.com/michael-cannon/flickr-shortcode-importer/issues) - review and submit bug reports and enhancement requests
1. [Flickr Shortcode Importer Support on WordPress](http://wordpress.org/support/plugin/flickr-shortcode-importer) - ask questions and review responses
1. [Contribute Code to Flickr Shortcode Importer](https://github.com/michael-cannon/flickr-shortcode-importer/blob/master/CONTRIBUTING.md)
1. [Beta Testers Needed](http://aihr.us/become-beta-tester/) - get the latest Flickr Shortcode Importer version


== Screenshots ==

1. Flickr Shortcode Importer in Plugins
2. Flickr Shortcode Importer in Tools
3. Flickr Shortcode Importer progress
4. Before Flickr Shortcode Importer for [flickr]
5. After Flickr Shortcode Importer for [flickr]
6. Flickr Shortcode Importer Options
7. Before Flickr Shortcode Importer for [flickrset]
8. After Flickr Shortcode Importer for [flickrset] & [flickr-gallery]
9. Before Flickr Shortcode Importer for Flickr-sourced A/IMG Tag
10. After Flickr Shortcode Importer for Flickr-sourced A/IMG Tag
11. Before Flickr Shortcode Importer for [flickr-gallery]
12. Image SEO filename, image wrap class and Flickr attribution sample
13. Edit post screen [flickr] Importer option
14. Imported [flickr-gallery] photoset as [ gallery] with media ids

[gallery]


== Changelog ==

See [Changelog](https://github.com/michael-cannon/flickr-shortcode-importer/blob/master/CHANGELOG.md)


== Upgrade Notice ==

* None


== Notes ==

* Backup your database before importing. You can use revision to revert individual posts, but doing so in mass is a major PITA.
* During my own imports, a post with one [flickr] entry could take a minute. Then posts with many [flickr] entries, several Flickr-source'd A/IMG tags and [flickset] with 30 or so photos took over 10-minutes to import.
* During import, it might look like nothing is happening. The progress bar only moves after each import succeeds or fails.
* I recommend setting the limit in options to 1 and then testing your installation. That sure makes for easier recovery in case something goes wrong. If something doesn't work, report it, http://wordpress.org/extend/plugins/flickr-shortcode-importer/.
* It's strongly recommended to deactivate plugins like WordSocial, WP Smush.it and similar to prevent extended import times. You can always enable them and run them enmasse later.
* Make sure you have enough disk space. Figure on about 1 GB per 1,000 photos given your using Scissors-continued and have a maximum image size of 1280 x 1024. If your images can be larger, then you'll probably need 1 GB per 250 photos imported.
* Using your own Flickr API Key might be necessary. Test a single import and see the results before setting your own.


== API ==

* Read the [Flickr Shortcode Importer API](https://github.com/michael-cannon/flickr-shortcode-importer/blob/master/API.md).


== Localization ==

You can translate this plugin into your own language if it's not done so already. The localization file `flickr-shortcode-importer.pot` can be found in the `languages` folder of this plugin. After translation, please [send the localized file](http://aihr.us/contact-aihrus/) for plugin inclusion.

**[How do I localize?](https://aihrus.zendesk.com/entries/23691557)**


== Thank You ==

* Thank you for tobylewis for his file_get_contents_curl and custom post types contributions.
* A big thank you to Željko Aščić of http://www.touristplayground.com/ for feedback and ideas.
* Initial code is modeled after Viper007Bond's class based Regenerate Thumbnails plugin. The AJAX status and single auto-submission operations were a big help.
* [flickr] shortcode handling code copied from Trent Gardner's very fine Flickr Manager plugin.
* Hat's off to Alison Barret for her Settings API tutorials and class My_Theme_Options.
