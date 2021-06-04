# fizzie 
![screenshot](https://raw.githubusercontent.com/bobbingwide/fizzie/main/screenshot.png)
* Contributors: bobbingwide, vsgloik
* Donate link: https://www.oik-plugins.com/oik/oik-donate/
* Tags: blocks, FSE, Gutenberg
* Requires at least: 5.5.1
* Tested up to: 5.7.2
* Version: 0.6.0
* License: GPLv2 or later
* License URI: http://www.gnu.org/licenses/gpl-2.0.html

Fizzie theme - a Full Site Editing theme using Gutenberg blocks.

## Description 
This is an experimental theme attempting to implement Full Site Editing (FSE) with Gutenberg blocks.

The theme is required to replace the Genesis-a2z theme that is used in [blocks.wp-a2z.org](https://blocks.wp-a2z.org)

Requirements:
1. Implement Full Site Editing.
2. Same look and feel as the Genesis-a2z theme.
3. Support documentation / demonstration of each of the new blocks in Gutenberg.
4. Find out what bits are missing from and/or not working on Gutenberg.
5. Implement on blocks.wp-a2z.org, when stable.


Contents:

* 19 templates
* 31 template parts

The `block-templates` are:

* 404 - Not found page
* archive - generic template used for archives: author, taxonomy, date, tag
* archive-block - to display archives for Blocks
* archive-oik-plugins - to display archive for Plugins
* archive-oik-themes - to display archive for Themes
* category - used to display the Category archive
* front-page - used for Page Shown On Front
* home - used for Blog Posts index page or Posts Shown on Front (when front-page not implemented)
* index - used when no other template is found
* output-input - custom template for debugging output vs input
* page-13.html - template for page ID 13
* page-about - template for page with slug `about`
* page-i18n-test.html - template for Internationalization test
* search - Display search results
* single - used for a single post / attachment / CPT
* single-full - custom full width template for post,page,block and block_example
* single-oik-plugins - used for a single oik-plugin
* single-oik-themes - used for a single oik-theme
* singular - used when single or page does not exist

* See the template visualization: https://developer.wordpress.org/files/2014/10/Screenshot-2019-01-23-00.20.04.png

The `block-template-parts` are:

* a2z-pagination - Letter pagination for blog posts
* a2z-pagination-block - Letter pagination for blocks
* a2z-pagination-oik-plugins - Letter pagination for plugins
* a2z-pagination-oik-themes - Letter pagination for themes
* archive-query - Main query for archive pages
* breadcrumbs - Breadcrumb trail - using sb-breadcrumbs-block-based-widgets
* category-description - Uses [archive_description] shortcode
* category-query - To list posts in a chosen category term
* contents-shortcode - Contents shortcode etc for the output-input custom template
* download - To download plugins - uses [oikp_download] shortcode
* download-theme - To download themes - uses [oikth_download] shortcode
* footer - Final full width footer
* footer-menu - Displays the footer menu - after the final full width footer
* footer-variant-example - Example of a footer template part
* header - Displays the header: site logo, site title and tagline, header menu.
* header-menu - Displays the header menu
* header-variant-example - Example of a header template part

* home-part - A template part used in debugging. Classic block
* home-query - Displays the posts on the blog page
* i18n-rich-text.html
* i18n-test - Internationalization, extraction and localization test file
* information - Displays post meta data using the oik-block/fields block
* issue-27 - test case file for issue-27
* issue-30 - test-case file for issue-30
* metadates - Displays Date published, last updated and [Edit] link
* page-content - Primary content part for a page
* page-footer - Full width footer with 3 columns - representing widgets
* post-content - Primary content part for a post
* post-content-full - Full width post content
* posts - An attempt to display the posts using query blocks - incomplete- not used
* search - Using the Search block
* social-links - Social link icons

Templates not yet implemented:

Some of these templates will be needed for other subdomains of wp-a2z.org.

* archive-CPT - archive templates for other custom post types - 5 to do
* image - template for the image mime type
* page - used for a single page -but see page-13, page-about and page-i18n-test
* single-CPT - single templates for other custom post types - 10 to do
* tag - for a particular tag
* taxonomy - for a particular taxonomy
* taxonomy-oik_letters - taxonomy specifically for oik_letters


## Installation 

* Either install Gutenberg 10.7.1 or higher or install and build the latest Gutenberg source.
* Activate Gutenberg.
* Install and activate the Fizzie theme, as you would install any other theme. Full Site Editing will be enabled automatically.
* For some of the templates and template parts to work properly you will need to install and activate the pre-requisite plugins.

* Pre-requisite plugins: see also Notes

* [oik](https://wordpress.org/plugins/oik/)
* [oik-fields](https://github.com/bobbingwide/oik-fields)
* [oik-a2z](https://github.com/bobbingwide/oik-a2z)
* [sb-breadcrumbs-block](https://github.com/bobbingwide/sb-breadcrumbs-block)
* [Yoast SEO](https://wordpress.org/plugins/wordpress-seo/) - for breadcrumbs logic

For the Output Input Debugging custom template

* [oik-block](https://github.com/bobbingwide/oik-block/) - for contents & guts shortcodes
* [oik-css](https://github.com/bobbingwide/oik-css) - for CSS block


## Change Log 
# 0.6.1 
* Changed: Replace [guts] shortcode with oik-bbw/wp using g attribute
* Changed: Update screenshot for Gutenberg 10.7.1 WordPress 5.7.2
* Changed: Rework home-query to use the Query block. Issue #11
* Changed: Set post title as link

# 0.6.0 
* Changed: Adjust header menu for Gutenberg 10.7.1. Issue #26
* Changed: Remove redundant styling for the 3 column archive
* Changed: Update screenshot for 10.4.0
* Changed: No longer override certain blocks. This disables language versions.
* Changed: Refresh from DB version
* Changed: Use query block with inherit true and update pagination block. Issue #11
* Changed: Simplify add_theme_support logic. Remove unneeded stuff.
* Changed: Switch to using theme.json   issue #3
* Tested: With Gutenberg 10.7.1 and WordPress Multi Site
* Tested: With WordPress 5.7.2 and WordPress Multi Site
* Tested: With PHP 8.0

# 0.5.0 
* Added: Add Input Output Debugging custom template,https://github.com/bobbingwide/fizzie/issues/65
* Changed: Improve support for align wide and align full,https://github.com/bobbingwide/fizzie/issues/44
* Changed: Add Header and Footer template variant examples,https://github.com/bobbingwide/fizzie/issues/60
* Changed: Add query-title to archive.html to aid the block's documentation,https://github.com/bobbingwide/fizzie/issues/60
* Changed: Replace [archive_description] by query-title and term-description,https://github.com/bobbingwide/fizzie/issues/60
* Changed: Add .gitignore to ignore custom.css and/or oik-custom.css,https://github.com/bobbingwide/fizzie/issues/57
* Changed: Override nav menu spacing and colour again for Gutenberg 10.3.0,https://github.com/bobbingwide/fizzie/issues/26
* Changed: Set default template part tagName based on template area,https://github.com/bobbingwide/fizzie/issues/55
* Tested: With Gutenberg 10.4.0
* Tested: With WordPress 5.7 and WordPress Multi Site
* Tested: With PHP 8.0

# 0.4.0 
* Changed: Another attempt at supporting alignfull and alignwide - solution copied/cobbled from TT1-blocks,https://github.com/bobbingwide/fizzie/issues/44
* Fixed: Avoid problems attempting to valid the id attribute. Just unset it!,https://github.com/bobbingwide/fizzie/issues/52
* Fixed: Adjust the width of submit buttons from 100% to auto
* Tested: With Gutenberg 10.1.1
* Tested: With WordPress 5.7 and WordPress Multi Site
* Tested: With PHP 8.0

# 0.3.1 
* Changed: Rename more query-pagination blocks to query-pagination-numbers,,https://github.com/bobbingwide/fizzie/issues/11
* Changed: Rename editor-style.css to style-editor.css and set block widths in the editor

# 0.3.0 
* Changed: Change from overriding query-pagination to query-pagination-numbers,https://github.com/bobbingwide/fizzie/issues/18
* Changed: Add support for oik-themes - for FSE themes,https://github.com/bobbingwide/fizzie/issues/51
* Changed: Update experimental-theme.json to new structure - with Gutenberg 10.0.0,https://github.com/bobbingwide/fizzie/issues/3
* Changed: relocalize updated template parts,https://github.com/bobbingwide/fizzie/issues/46
* Changed: Style radio buttons with width:auto
* Changed: Update Copyright years in the footer
* Changed: Workaround to prevent recursion occurring in the editor,https://github.com/bobbingwide/fizzie/issues/49
* Changed: Move metadates template part back into the group, with className alignfull,https://github.com/bobbingwide/fizzie/issues/44
* Changed: Allow nested template parts with class alignfull to use 100% width,https://github.com/bobbingwide/fizzie/issues/44
* Changed: Remove workaround for post-excerpt,https://github.com/bobbingwide/fizzie/issues/25
* Changed: Improve styling of blog display ( home.html ),https://github.com/bobbingwide/fizzie/issues/4
* Tested: With Gutenberg 10.0.2
* Tested: With WordPress 5.7-beta3
* Tested: With PHP 8.0

# 0.2.0 
* Added: Extracted and localized to UK English ( en_GB ) and the obfuscated bbboing language ( bb_BB ),https://github.com/bobbingwide/fizzie/issues/46
* Changed: Add more templates to test the template hierarchy logic: page-13, page-about and search,https://github.com/bobbingwide/fizzie/issues/38
* Changed: Enable cloning from wp.a2z to wp-a2z.org,https://github.com/bobbingwide/fizzie/issues/48
* Changed: Remove empty paragraph from page footer,https://github.com/bobbingwide/fizzie/issues/4
* Changed: Update 'naughty' message from index.php
* Changed: Update screenshot for v0.1.1
* Fixed: Add text domain to 'No posts found'
* Fixed: Update home.html to use header.html,https://github.com/bobbingwide/fizzie/issues/36

# 0.1.1 
* Added: Create archive templates & a2z pagination parts for block & oik-plugins,https://github.com/bobbingwide/fizzie/issues/36
* Added: Debug information for each template,https://github.com/bobbingwide/fizzie/issues/41
* Changed: Add social links in the footer,https://github.com/bobbingwide/fizzie/issues/4
* Changed: Copy images from genesis-a2z; even if not actually used,https://github.com/bobbingwide/fizzie/issues/4
* Changed: Improve styling of footer.,https://github.com/bobbingwide/fizzie/issues/4
* Changed: Set min-height 60px for h2 on post-type-archive-block only.,https://github.com/bobbingwide/fizzie/issues/4
* Changed: The About menu item should link to the about page,https://github.com/bobbingwide/fizzie/issues/17
* Changed: Try to properly implement alignwide and alignfull styling capability,https://github.com/bobbingwide/fizzie/issues/44
* Fixed: Add a shim to enable Navigation menus to display in the Front end on WordPress 5.6-RC1,https://github.com/bobbingwide/issues/42
* Fixed: Correct [sites] shortcode to [bw_blogs],https://github.com/bobbingwide/fizzie/issues/4
* Fixed: Correct reference to $this->bad_processed_content,https://github.com/bobbingwide/fizzie/issues/36

# 0.1.0 
* Changed: Add styles used for the test of core/post-content recursion detection, Issue #33
* Changed: Continue refactoring recursion detection and error reporting, Issue #33
* Changed: Set preset font sizes in pixels. Set default fonts for core/post-title and core/post-date, Issue #4
* Changed: Update the footer menu to match blocks.wp-a2z.org, Issue #4

# 0.0.9 
* Changed: Refactored block-override-functions as Fizzie_Block_Recursion_Control, Issue #33
* Changed: Revert to using block content postId to check for recursion in core/post-content, issue #27
* Changed: Refactor overrides for core blocks into separate files, issue #25
* Tested: With WordPress 5.6-beta4

# 0.0.8 
* Added: Disable the logic that attempts to insert the global post ID into the $fizzie_processed_content array. Issue #31
* Added: Attempt to allow recursive reusable block problems to be fixed using the editor. Issue #31
* Changed: Start refactoring of overrides. Issue #25
* Added: Improve solution to detect recursive post-content blocks. Issue #27
* Added: Improve solution to detects recursive template-parts. Issue #30
* Added: core/post-hierarchical terms - cater for unknown taxonomies. Issue  #29
* Added: Attempt to avoid infinite recursion in the core/post-content block. Issue #27
* Changed: Restructure home.html to use a simplified home-query.html that uses metadates.html, and metadates.html to use [bw_field]

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

It implements template parts which depend on external components. For example

* a2z pagination requires oik-a2z plugin
* breadcrumbs requires sb-breadcrumbs-block

The CSS is minimal; just enough to make it look OK on my laptop and external monitor.
Responsibility for responsive styling is left to Gutenberg / WordPress core functionality.

### Block overrides 

Fizzie contains a number of overrides to Gutenberg server rendered blocks which didn't behave the way I expected.
These overrides should continue to work even when the PRs to fix the bugs have been implemented.

Improvement areas include:

* core/query-loop - uses main query when used outside of core/query
* core/query-pagination - uses the main query when used outside of core/query
* core/block - handle recursion
* core/post-hierarchical-terms - cater for invalid taxonomy
* core/navigation-link - set current-menu-item class for current request
* core/navigation - tbc
* core/template-part - handle recursion
* core/post-content - handle recursion
* core/post-excerpt - append missing `</div>`

For more information see https://github.com/bobbingwide/fizzie/issues/25 and/or the includes folder.


## References 
See my articles on herbmiller.me:

- [Localization of Full Site Editing themes](https://herbmiller.me/localization-of-full-site-editing-themes/)
- [Fizzie - an experimental Full Site Editing theme](https://herbmiller.me/fizzie-an-experimental-full-site-editing-theme/)


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
- https://developer.wordpress.org/block-editor/contributors/develop/
- https://developer.wordpress.org/block-editor/contributors/develop/git-workflow/
- https://wpdevelopment.courses/articles/full-site-editing-theme-learnings/


Some other FSE themes:

- [WordPress Theme Experiments](https://github.com/WordPress/theme-experiments)
- [Twenty Twenty-One Blocks](https://github.com/WordPress/theme-experiments/tree/master/twentytwentyone-blocks)
- [Stanley](https://github.com/carolinan/fullsiteediting/blob/course/Block%20based%20themes/Lesson%201%20-Theme%20structure/stanley.zip)
- [Seedlet Blocks](https://github.com/Automattic/themes/tree/trunk/seedlet-blocks)
- [SB - Second Byte](https://github.com/bobbingwide/SB)
- See also [WP-a2z FSE themes](https://blocks.wp-a2z.org/oik-themes)

## Brief development history 

Even though FSE was not going to make it into WordPress 5.6 or 5.7, I had to implement the theme in order to document the 24 or so new blocks
that were already in Gutenberg and that will eventually appear in WordPress core.

It started as a completely empty theme and then I played with it while following some tutorials.
- https://developer.wordpress.org/block-editor/tutorials/block-based-themes/
- https://fullsiteediting.com/

The tutorials didn't really cover the Site Editor (beta) user interface. So I was on my own from there on.
It’s been quite a learning experience.
Rather than using the Site Editor ( beta ) user interface,
I've found it easier to edit the Templates and Template parts directly.

I've been using the block editor in Code editor mode, then copying and pasting the individual templates and template parts to the .html files that the theme needs to deliver.
In some cases these had to be edited to remove the "postId" attributes.

In the target site(s) some of the template parts need importing into the Site Editor to be customised for the target site.
These are the ones that include the navigational blocks.

* Note: You can set the Site icon without having to edit a template or template part. Just create a new post, add the Site icon block and select the image file.




## Copyright 
(C) Copyright Herb Miller, Bobbing Wide 2020, 2021

* This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
