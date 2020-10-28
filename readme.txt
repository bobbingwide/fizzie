=== fizzie ===
Contributors: bobbingwide, vsgloik
Donate link: https://www.oik-plugins.com/oik/oik-donate/
Tags: blocks, FSE, Gutenberg
Requires at least: 5.5.1
Tested up to: 5.5.1
Version: 0.0.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Fizzie theme - a Full Site Editing theme using Gutenberg blocks.

== Description ==
This is an experimental theme attempting to implement Full Site Editing (FSE) with Gutenberg blocks.

The theme is required to replace the Genesis-a2z theme that is used in [blocks.wp-a2z.org](https://blocks.wp-a2z.org)

Even though FSE is not going to make it into WordPress 5.6, I’ll have to implement the theme in order to document the 24 or so new blocks in WordPress core.

It started as a completely empty theme and then I played with it while following some tutorials.
- https://developer.wordpress.org/block-editor/tutorials/block-based-themes/
- https://fullsiteediting.com/

The tutorials don't really cover the Site Editor (beta) user interface. 
 
It’s been quite a learning experience.

So far I’ve managed to create:

- Six templates
- Eight template parts

See the template visualization: https://developer.wordpress.org/files/2014/10/Screenshot-2019-01-23-00.20.04.png

The templates are:

- category - not functional - used to display the Category archive
- front-page - used for Page Shown On Front
- home - used for Blog Posts index page or Posts Shown on Front
- single - used for a single post / attachment / CPT
- singular - used when single or page does not exist
- index - used when no other template is found

Not yet done:

- page - used for a single page
- archive - 

The template parts are:

- footer - final full width footer
- footer-part - alternative footer ( not used ? )
- header - the original header - replaced by header-2-columns
- header-2-columns - A badly named header template part which only has half of the functionality of the header that it’s going to replace.
- page-content - primary content part for a page
- page-footer - full width footer with 3 columns
- post-content - breaks site
- posts - an attempt to display the posts using query blocks - incomplete- not used


It’s going to take some time.



== Installation ==

- Either install Gutenberg 9.2.2 or install and build the Gutenberg source.
- Activate Gutenberg.
- Install and activate the Fizzie theme, as you would install any other theme.
- Enable Full site editing, in Gutenberg > Experiments.

Note: There is no need to Enable Fill Site Editing Demo Templates


== Change Log ==
= 0.0.1 =
* Added: Start styling as genesis-a2z,[github bobbingwide fizzie issues 4]
* Added: Enable oik shortcodes in template parts,[github bobbingwide fizzie issues 5]
* Changed: Copy some stuff from the Stanley theme as per fullsitediting.com,[github bobbingwide fizzie issues 4]
* Changed: Play with editor-style.css for dropcap and header
* Changed: Start to support experimental-link-color, reload latest style.css when SCRIPT_DEBUG true, try Twenty Seventeen's frontpage_template hook
* Changed: Added then delete template part posts in index.html - never ending loop problem
* Added: Put some logic in index.php for when Gutenberg is not active
* Added: Add editor-style.css to style the blocks in the editor to the same as the front end,[github bobbingwide fizzie issues 4]

= 0.0.0 =
* Added: Create the basic theme following instructions from https://developer.wordpress.org/block-editor/tutorials/block-based-themes/#creating-the-theme
* Added: Copy/cobble index.html, front-page.html templates. Copy header.html template part. Create empty footer.html template part.

== Copyright ==
(C) Copyright Bobbing Wide 2020

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.



== Notes ==

== References ==
- https://developer.wordpress.org/block-editor/developers/themes/theme-json/
- https://developer.wordpress.org/block-editor/tutorials/block-based-themes/
- https://wpengine.com/blog/full-site-editing-future-of-building-with-wordpress/
- https://wpengine.com/blog/full-site-editing-what-you-need-to-get-started/
- https://github.com/WordPress/theme-experiments


- https://wptavern.com/q-first-fse-wordpress-theme-now-live
- https://wptavern.com/wordpress-5-6-release-team-pulls-the-plug-on-block-based-widgets
https://wptavern.com/navigation-screen-sidelined-for-wordpress-5-6-full-site-editing-edges-closer-to-public-beta




