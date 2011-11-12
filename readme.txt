The Frame Buster
================
Copyright 2006 James McKay
http://www.jamesmckay.net/

Contents

1. Description
2. Archive contents
3. Installation
4. Upgrading from version 1.0 beta 1
5. Configuration
6. History
7. Support


1. Description
==============
This is a plugin for WordPress which allows you to selectively force your
website to load into a top level window. It allows you to specify domain names
as exceptions, e.g. if you have a partner site that you want to allow you to
frame you in, or if you have an agreement with another website that prohibits
you from breaking out of their frame set.

It is also compatible with the WordPress 2.0 page preview facility; it will not
override the preview frame in the "write post" page in the dashboard.


2. Archive Contents
===================
framebuster.php      - The Frame Buster plugin
framebuster-test.php - Some unit tests on the hostname matching logic
readme.txt           - This file
licence.txt          - The licence agreement for The Frame Buster.


3. Installation
===============
Copy the file framebuster.php into your WordPress plugins directory and activate
it using the plugins page in the WordPress dashboard. You DO NOT need to deploy
any of the other files for the plugin to work.

Note: Make sure that your website template includes the following line in the
<head> section of your HTML. Publicly available templates should have it, but if
yours doesn't, you need to put it back:

	<?php wp_head(); ?>


4. Upgrading from earlier versions
==================================
Just replace the framebuster.php file with the new version. No other
configuration changes or database scripts need to be run.


5. Configuration
================
The framebuster configuration page appears under the "Options" tab in your
dashboard. Enable the check box to turn it on.

If you want to allow websites at certain domain names to frame you in, specify
them in the "exceptions" box, e.g.

www.jamesmckay.net
www.jammycakes.com

You can specify all subdomains of a particular domain with *. as follows:

*.jamesmckay.net
*.jammycakes.com

You can also specify a particular port:

www.jamesmckay.net:8080
*.jamesmckay.net:8080

If you don't specify a port, the frame buster will be omitted for all ports
on that domain name.


6. Doesn't work?
================
Check the following:

1) Check that you have enabled the plugin in the WordPress control panel.
2) Check that your browser has JavaScript enabled.
3) Check that you have included a call to wp_head() in the <head> section of
   your template.
4) Try clearing your browser cache -- the frameset in question may be loading
   a cached version of your page, which may not have the frame buster
   JavaScript rendered in it.
5) The frame buster will not break out of a frame set on the same domain as
   your website. This is because the WordPress 2.0 page preview facility
   loads your page into an <iframe> and to override this would make editing
   impossible.
   
If you are still having problems, please drop me a line using the online
contact form on my website, stating which browser(s) are affected (including
version information where possible), which version of WordPress and the frame
buster you are using, and the URL of both your website and the site which is
trying to frame you in.


6. History
==========

[2006-02-20] Initial release.
[2006-08-08] Version 1.0.2:
	BUG FIX/ENHANCEMENT: Fixed overrides to work correctly when a port other
		than the default HTTP port 80 is used. Also allowed users to
		specify ports to override in the options.
[2006-12-28] Version 1.0.4:
	BUG FIX: Using $_SERVER rather than $_ENV which didn't work on some servers.
		Also fixed "undefined function does_host_match" error.


7. Redistribution and Modification
==================================
This plugin is licensed under the GNU General Public License, so if you modify
it and/or include it in another work, that work must also be released under the
GPL. If you require any other licensing terms, please contact me.


8. Support
==========
If you believe that you have found a bug in this plugin, or require further
support, or just wish to comment, please either use the contact form on my
website to contact me or else leave a comment on the relevant page of my blog.

My contact form:        http://www.jamesmckay.net/contact/
Frame buster home page: http://www.jamesmckay.net/code/wp-framebuster/

James McKay
8 August 2006
http://www.jamesmckay.net/