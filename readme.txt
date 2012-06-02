=== Piwik Analytics ===
Contributors: zjuul
Tags: piwik, analytics, stats, statistics
Requires at least: 2.5
Tested up to: 2.9.2
Stable tag: 1.0.2

This plugin adds the Piwik Web Analytics javascript code into the footer of
your website. It has several useful options.
Please note: this version is for Piwik 0.4 or higher! If you run an older
version of Piwik, please use version 0.6 of this plugin.

== Description ==

This is a basic wordpress plugin for the excellent Piwik web Analytics tool.
It adds the piwik javascript code into every page of your weblog, so
you don't have to code PHP to add it to your templates.

It is based quite heavily on the Google Analytics wordpress plugin by Joost de
Valk.

The following options are supported:

* piwik hostname
* piwik path
* site ID
* option to control download tracking
* option to exclude the admin user (probably you)

Please note, this plugin requires a running Piwik installation somewhere under
your control. It does not include Piwik itself.

See also [The plugin URL](http://forwardslash.nl/piwik-analytics/), and
[The Piwik website](http://piwik.org/)


== Installation ==

1. Upload the piwik-analytics directory containing `piwikanalytics.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Make sure your template has a call to wp_footer() somewhere in the footer.
1. Configure the Plugin: enter site ID, and the path to the piwik.php and piwik.js files

== Changelog ==

= 1.0.2 =
* added changelog

= 1.0.1 =
* Fixed typo in documentation

= 1.0 =
* moved to a new domain, updated URLs
* no need for a 0.x release, considered stable enough


== Frequently Asked Questions ==

Q: Your plugin is not compatible with my Piwik version, now what?
A1: Use version 0.7 (or up) of this plugin for version 0.4 (or up of Piwik)
A2: Use version 0.6 of this plugin for older versions of Piwik

Q: My piwik code does not show up.
A1: Make sure your theme has a call to wp_footer() in the footer.php file
A2: Make sure you're not logged in as admin.

