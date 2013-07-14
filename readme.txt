=== Flickr Shortcode Importer ===

Contributors: comprock
Donate link: http://aihr.us/about-aihrus/donate/
Tags: flickr,import,featured image,photo,image,video
Requires at least: 3.4
Tested up to: 3.6.0
Stable tag: 2.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Imports [flickr], [flickrset], [flickr-gallery] shortcode and Flickr-sourced A/IMG tagged media into the Media Library.


== Description ==

Imports [flickr], [flickrset], [flickr-gallery] shortcode and Flickr-sourced A/IMG tagged media into the Media Library. Furthermore, it transforms the post content [flickr] shortcodes into links containing the Media Library based image of the proper size and alignment.

Import can be run directly from edit page and post screens.

[youtube http://youtube.com/]
**[Video introduction](http://youtu.be/TBD)**

The first [flickr] image found in post content is set as the post's Featured Image and removed from the post content. The remaining [flickr] shortcodes are then transfromed as image links to their attachment page.  

[flickrset] and [flickr-gallery] shortcodes are converted to [gallery] after the Flickr set images have been added to the Media Library. If 'Set Featured Image' is checked in Options, then the first image of the [flickrset] is used as such.

Flickr-sourced A/IMG tagged media is converted into [flickr] and then imported as normal. Great for finally bringing into your control all of those media items you've been using, but now Flickr is giving you 'Image is unavaiable' for. A/IMG tag is processed before IMG to prevent unexpected results.

Image attribution links can be added if enabled via Settings.

This plugin is handy for transitioning from plugin `wordpress-flickr-manager` and `flickr-gallery` to your own Media Library because you have CDN services or want to move off of third party software.

There is no restore functionality. Backup beforehand or be prepared to revert every transformed post by hand via the post revision tool.

= Features =

* API
* [flickrset] and [flickr-gallery] shortcodes are converted to [gallery]
* Flickr-sourced A/IMG tagged media imported into WordPress
* Settings export/import
* Settings screen

= Settings Options =

**Import Settings**

* Skip Importing Videos
* Import Flickr-sourced A/IMG tags
* Set Featured Image
* Force Set Featured Image
* Remove First Flickr Shortcode
* Make Nice Image Title?
* Replace Filename with Image Title?
* Image Import Size
* Default Image Alignment
* Default Image Size
* Default A Tag Class
* Link Image to Attachment Page?
* Image Wrap Class
* Set Captions
* Include Flickr Author Attribution?
* Flickr Author Attribution Text
* Flickr Author Attribution Wrap Class
* Add Flickr Link in Description?
* Flickr Link Text
* Add Image License to Description?	
* Flickr Image License Text

**Posts Selection**

* Posts to Import
* Skip Importing Posts

**Testing Options**

* Import Limit
* Debug Mode

**Post Options**

* Post [flickr] Import Widget?
* Enable for Pages
* Enable for Posts
* Enable for Media
* Enable for custom post types - if any

**Flickr API**

* Flickr API Key
* Flickr API Secret

**Compatibility & Reset**

* Export Settings – These are your current settings in a serialized format. Copy the contents to make a backup of your settings.
* Import Settings – Paste new serialized settings here to overwrite your current configuration.
* Remove Plugin Data on Deletion? - Delete all Flickr Shortcode Importer data and options from database on plugin deletion
* Reset to Defaults? – Check this box to reset options to their defaults

= Handled shortcode & media samples =

* [flickr size="small" float="left"]http://www.flickr.com/photos/dancoulter/2619594365/[/flickr] (image)
* [flickr height="300" width="400"]http://www.flickr.com/photos/dancoulter/2422361554/[/flickr] (video)
* WARNING: Video media imported, but doesn't seem to work
* [flickr id="5348222727" thumbnail="small" overlay="false" size="large" group="" align="none"]
* [flickrset id="72157626986038277" thumbnail="small" photos="" overlay="true" size="large"]
* [flickr-gallery mode="photoset" photoset="72157626986038277"]
* [flickr-gallery mode="tag" tags="foo,bar" tag_mode="all"]
* [flickr-gallery mode="interesting"]
* [flickr-gallery mode="recent"]
* [flickr-gallery mode="search" tags="barcamp" group_id="431412@N25"]
* `<a class="tt-flickr tt-flickr-Medium" title="Khan Sao Road, Bangkok, Thailand" href="http://www.flickr.com/photos/comprock/4334303694/" target="_blank"><img class="alignnone" src="http://farm3.static.flickr.com/2768/4334303694_37785d0f0d.jpg" alt="Khan Sao Road, Bangkok, Thailand" width="500" height="375" /></a>`
* `<img class="alignnone" src="http://farm3.static.flickr.com/2768/4334303694_37785d0f0d.jpg" alt="Khan Sao Road, Bangkok, Thailand" width="500" height="375" />`

= API =

* Read the [Flickr Shortcode Importer API](https://github.com/michael-cannon/flickr-shortcode-importer/blob/master/API.md).

= Warnings =

* Backup your database before importing. You can use revision to revert individual posts, but doing so in mass is a major PITA.
* During my own imports, a post with one [flickr] entry could take a minute. Then posts with many [flickr] entries, several Flickr-source'd A/IMG tags and [flickset] with 30 or so photos took over 10-minutes to import.
* During import, it might look like nothing is happening. The progress bar only moves after each import succeeds or fails.
* I recommend setting the limit in options to 1 and then testing your installation. That sure makes for easier recovery in case something goes wrong. If something doesn't work, report it, http://wordpress.org/extend/plugins/flickr-shortcode-importer/.
* It's strongly recommended to deactivate plugins like WordSocial, WP Smush.it and similar to prevent extended import times. You can always enable them and run them enmasse later.
* Make sure you have enough disk space. Figure on about 1 GB per 1,000 photos given your using Scissors-continued and have a maximum image size of 1280 x 1024. If your images can be larger, then you'll probably need 1 GB per 250 photos imported.
* Using your own Flickr API Key might be necessary. Test a single import and see the results before setting your own.

= Languages =

You can translate this plugin into your own language if it's not done so already. The localization file `flickr-shortcode-importer.pot` can be found in the `languages` folder of this plugin. After translation, please [send the localized file](http://aihr.us/contact-aihrus/) to the plugin author.

See the FAQ for further localization tips.

= Support =

Please visit the [Flickr Shortcode Importer Knowledge Base](https://aihrus.zendesk.com/categories/20116727-Flickr-Shortcode-Importer) for frequently asked questions, offering ideas, or getting support.

If you want to contribute and I hope you do, visit the [Flickr Shortcode Importer Github repository](https://github.com/michael-cannon/flickr-shortcode-importer).

= Thank You =

* Thank you for tobylewis for his file_get_contents_curl and custom post types contributions.
* A big thank you to Željko Aščić of http://www.touristplayground.com/ for feedback and ideas.
* Initial code is modeled after Viper007Bond's class based Regenerate Thumbnails plugin. The AJAX status and single auto-submission operations were a big help.
* [flickr] shortcode handling code copied from Trent Gardner's very fine Flickr Manager plugin.
* Hat's off to Alison Barret for her Settings API tutorials and class My_Theme_Options.


== Installation ==

1. Via WordPress Admin > Plugins > Add New, Upload the `flickr-shortcode-importer.zip` file
1. Alternately, via FTP, upload `flickr-shortcode-importer` directory to the `/wp-content/plugins/` directory
1. Activate the 'Flickr Shortcode Importer' plugin after uploading or through WordPress Admin > Plugins
1. Edit defaults via Settings > [flickr] Options
1. Import via Tools > [flickr] Importer


== Frequently Asked Questions ==

Please visit the [Flickr Shortcode Importer Knowledge Base](https://aihrus.zendesk.com/categories/20116727-Flickr-Shortcode-Importer) for frequently asked questions, offering ideas, or getting support.


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


== Changelog ==

See [Changelog](https://github.com/michael-cannon/flickr-shortcode-importer/blob/master/CHANGELOG.md)


== Upgrade Notice ==

* None


== Beta Testers Needed ==

I really want Flickr Shortcode Importer and Flickr Shortcode Importer Premium to be the best WordPress plugins of their type. However, it's beyond me to do it alone.

I need beta testers to help with ensuring pending releases of Flickr Shortcode Importer and Flickr Shortcode Importer Premium are solid. This would benefit us all by helping reduce the number of releases and raise code quality.

[Please contact me directly](http://aihr.us/contact-aihrus/).

Beta testers benefit directly with latest versions, a free 1-site license for Flickr Shortcode Importer Premium, and personalized support assistance.


== TODO ==

See [TODO](https://github.com/michael-cannon/flickr-shortcode-importer/blob/master/TODO.md)
