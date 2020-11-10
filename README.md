# fizzie 
![screenshot](https://raw.githubusercontent.com/bobbingwide/fizzie/main/screenshot.png)
* Contributors: bobbingwide, vsgloik
* Donate link: https://www.oik-plugins.com/oik/oik-donate/
* Tags: blocks, FSE, Gutenberg
* Requires at least: 5.5.1
* Tested up to: 5.6-beta3
* Version: 0.0.7
* License: GPLv2 or later
* License URI: http://www.gnu.org/licenses/gpl-2.0.html

Fizzie theme - a Full Site Editing theme using Gutenberg blocks.

## Description 
This is an experimental theme attempting to implement Full Site Editing (FSE) with Gutenberg blocks.

The theme is required to replace the Genesis-a2z theme that is used in [blocks.wp-a2z.org](https://blocks.wp-a2z.org)

Even though FSE is not going to make it into WordPress 5.6, I’ll have to implement the theme in order to document the 24 or so new blocks in WordPress core.

It started as a completely empty theme and then I played with it while following some tutorials.
- https://developer.wordpress.org/block-editor/tutorials/block-based-themes/
- https://fullsiteediting.com/

The tutorials didn't really cover the Site Editor (beta) user interface. So I was on my own from there on.
It’s been quite a learning experience.
Rather than using the Site Editor ( beta ) user interface,
I've found it easier to edit the Templates and Template parts directly.
I've been using the block editor in Code editor mode, then copying and pasting the individual templates and template parts to the .html files that the theme needs to deliver.
These have to be edited to remove the "postId" attributes.
In the target site(s) some of the template parts need importing into the Site Editor to be customised for the target site.
These are the ones that include the navigational blocks.

So far I’ve managed to create:

- Nine templates
- Sixteen template parts

The templates are:

* archive - generic template used for archives: author, taxonomy, date, tag
* category - used to display the Category archive
* front-page - used for Page Shown On Front
* home - used for Blog Posts index page or Posts Shown on Front
* single - used for a single post / attachment / CPT
* single-oik-plugins - used for a single oik-plugin
* singular - used when single or page does not exist
* index - used when no other template is found
* 404 - Not found page

Not yet done:

* other CPT archives
* page - used for a single page


* See the template visualization: https://developer.wordpress.org/files/2014/10/Screenshot-2019-01-23-00.20.04.png

The template parts are:

* a2z-pagination - Letter pagination for blog posts
* archive-query - Main query for archive pages
* breadcrumbs - Breadcrumb trail - using sb-breadcrumbs-block-based-widgets
* category-description - Uses [archive_description] shortcode
* category-query - To list posts in a chosen category term
* download - To download plugins - uses [oikp_download] shortcode
* footer - Final full width footer
* footer-menu - Displays the footer menu - after the final full width footer
* header-2-columns - A badly named header template part which only has half of the functionality of the header that it’s going to replace.
* header-menu - Displays the header menu - inner block to header-2-columns
* information - Displays post meta data using the oik-block/fields block
* page-content - Primary content part for a page
* page-footer - Full width footer with 3 columns - representing widgets
* post-content - Primary content part for a post
* posts - An attempt to display the posts using query blocks - incomplete- not used
* search - Using the Search block


## Installation 

* Either install Gutenberg 9.2.2 or higher or install and build the latest Gutenberg source.
* Activate Gutenberg.
* Install and activate the Fizzie theme, as you would install any other theme.
* For Gutenberg 9.2.2 enable Full site editing, in Gutenberg > Experiments. Note: There is no need to Enable Full Site Editing Demo Templates
* For Gutenberg 9.3.0 Full Site Editing will be enabled automatically.
* For some of the templates and template parts to work properly you will need to install and activate the pre-requisite plugins.
* For templates which include navigation blocks you will need to edit the supplied menus.

## Change Log 
# 0.0.7 
* Changed: Improve CSS styling for nav menus,https://github.com/bobbingwide/fizzie/issues/26
* Changed: Define CSS styling for metadates template part,https://github.com/bobbingwide/fizzie/issues/23
* Changed: Improve CSS styling for certain block types,https://github.com/bobbingwide/fizzie/issues/4
* Added: Implement metadates template part,https://github.com/bobbingwide/fizzie/issues/23
* Changed: When Template Part Not Found is displayed show information for problem determination,https://github.com/bobbingwide/fizzie/issues/16
* Added: Add [post-edit] shortcode temporary solution,https://github.com/bobbingwide/fizzie/issues/24
* Changed: Update header menu links to match the header menu in blocks.wp-a2z.org,https://github.com/bobbingwide/fizzie/issues/17
* Changed: Start implementing menu improvements - by fiddling url and className attributes,[https://github.com/bobbingwide/fizzie/issues/22

# 0.0.6 
* Added: Improve solution to fix some Gutenberg issues by replacing render_callback functions with our own.,https://github.com/bobbingwide/fizzie/issues/18
* Fixed: Correct badly formed template-part for footer-menu,https://github.com/bobbingwide/fizzie/issues/19
* Changed: Update templates and parts for better matching of functionality in genesis-a2z,https://github.com/bobbingwide/fizzie/issues/4
* Added: Add posts.html template part - not yet used
* Changed: Fix up CSS messed up by experimental-theme.json changes.,https://github.com/bobbingwide/fizzie/issues/3
* Added: Implement main query pagination for the core/query-pagination block,https://github.com/bobbingwide/fizzie/issues/18
* Experiment: Attempt to define typography for core/post-title in experimental-theme.json
* Changed: Improve styling for h1-h6, separator, preformatted and verse. Add some more colour presets and font sizes
* Tested: With WordPress 5.6-beta3 and WordPress Multi Site
* Tested: With Gutenberg 9.2.2, 9.3.0 and development
* Tested: With PHP 7.4

# 0.0.5 
* Added: single-oik-plugins template,https://github.com/bobbingwide/fizzie/issues/14
* Changed: archive template,https://github.com/bobbingwide/fizzie/issues/14
* Changed: Extracted header and footer menus into separate template parts [https://github.com/bobbingwide/fizzie/issues/17

# 0.0.4 
* Changed: Create [archive_description] shortcode for the category-description template part,https://github.com/bobbingwide/fizzie/issues/13
* Tested: With WordPress 5.6-beta2
* Tested: With Gutenberg 9.2.2 and development - with a local fix for #25377
* Tested: With PHP 7.4

# 0.0.3 
* Changed: Improve styling of drop down menu and page footer column background,https://github.com/bobbingwide/fizzie/issues/4
* Added: Implement the 404.html template,https://github.com/bobbingwide/fizzie/issues/12
* Changed: remove wp:query to allow use of the main query in archive based templates,https://github.com/bobbingwide/fizzie/issues/11

# 0.0.2 
* Added: Implement category.html template,https://github.com/bobbingwide/fizzie/issues/9
* Changed: Style the 'home' template - for blog posts,https://github.com/bobbingwide/fizzie/issues/4
* Changed: Style page-footer content to be same width as page and post,https://github.com/bobbingwide/fizzie/issues/1
* Changed: Support header and footer menus. https://github.com/bobbingwide/fizzie/issues/1#issuecomment-718584290
* Fixed: update wp:template-part to remove postId attr; set theme attr to fizzie
* Added: Update the theme's files extracted from the exported zip file. See https://github.com/bobbingwide/fizzie/issues/1#issuecomment-718100940

# 0.0.1 
* Added: Start styling as genesis-a2z,https://github.com/bobbingwide/fizzie/issues/4
* Added: Enable oik shortcodes in template parts,https://github.com/bobbingwide/fizzie/issues/5
* Changed: Copy some stuff from the Stanley theme as per fullsitediting.com,https://github.com/bobbingwide/fizzie/issues/4
* Changed: Play with editor-style.css for dropcap and header
* Changed: Start to support experimental-link-color, reload latest style.css when SCRIPT_DEBUG true, try Twenty Seventeen's frontpage_template hook
* Changed: Added then delete template part posts in index.html - never ending loop problem
* Added: Put some logic in index.php for when Gutenberg is not active
* Added: Add editor-style.css to style the blocks in the editor to the same as the front end,https://github.com/bobbingwide/fizzie/issues/4

# 0.0.0 
* Added: Create the basic theme following instructions from https://developer.wordpress.org/block-editor/tutorials/block-based-themes/#creating-the-theme
* Added: Copy/cobble index.html, front-page.html templates. Copy header.html template part. Create empty footer.html template part.


## Notes 
The theme is designed for use on wp-a2z.org.

It implements template parts which depend on external components
- a2z pagination requires oik-a2z plugin
- breadcrumbs requires sb-breadcrumbs-block

The CSS is minimal; just enough to make it look OK on my laptop and external monitor.

It took two days to create the category template.
A fully working version of the theme is going to take some time.

The archive related templates in the theme only work with a version of Gutenberg that has
a fix developed for https://github.com/wordpress/gutenberg/issues/25377.


## References 
During the development I have referred to the following articles, sites and repositories.

- https://developer.wordpress.org/block-editor/developers/themes/theme-json/
- https://developer.wordpress.org/block-editor/tutorials/block-based-themes/
- https://wpengine.com/blog/full-site-editing-future-of-building-with-wordpress/
- https://wpengine.com/blog/full-site-editing-what-you-need-to-get-started/
- https://github.com/wordpress/gutenberg
- https://github.com/WordPress/theme-experiments
- https://make.wordpress.org/design/handbook/focuses/full-site-editing/
- https://make.wordpress.org/design/handbook/focuses/global-styles/
- https://wptavern.com/q-first-fse-wordpress-theme-now-live
- https://wptavern.com/wordpress-5-6-release-team-pulls-the-plug-on-block-based-widgets
- https://wptavern.com/navigation-screen-sidelined-for-wordpress-5-6-full-site-editing-edges-closer-to-public-beta
- https://wptavern.com/gutenberg-9-3-provides-indicator-of-where-full-site-editing-is-going-a-future-without-widgets-and-customizer-screens
- https://make.wordpress.org/themes/2020/10/23/developing-the-full-site-editing-version-of-twenty-twenty-one/
- https://fullsiteediting.com/
- https://wordpress.org/plugins/block-unit-test/


Some other FSE themes

- https://github.com/WordPress/theme-experiments
- https://github.com/WordPress/theme-experiments/tree/master/twentytwentyone-blocks
- https://github.com/Automattic/themes/tree/trunk/seedlet-blocks


## Copyright 
(C) Copyright Bobbing Wide 2020

* This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
