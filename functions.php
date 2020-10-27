<?php

/**
 *
 */
function fizzie_after_setup_theme() {
	add_action( 'wp_enqueue_scripts', 'fizzie_enqueue_styles' );

	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'custom-line-height' );
	add_theme_support( 'responsive-embeds' );

	/**
     * If you use add_editor_style() it calls
     * add_theme_support( 'editor-style' );
     * ... which appears to be different from
     * add_theme_support( 'editor-styles' );
     * Or is it?
     *
     * There's also 'dark-editor-style'.
     */


}

function fizzie_enqueue_styles() {
	$theme_version = wp_get_theme()->get( 'Version' );
	wp_enqueue_style( 'fizzie', get_stylesheet_uri(), array(), $theme_version );
}

add_action( 'after_setup_theme', 'fizzie_after_setup_theme');
add_action( 'after_setup_theme', 'fizzie_stanley_theme_support');


/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 *
 * Copied from stanley theme. 2020/10/27
 */
function fizzie_stanley_theme_support() {

    // Add default posts and comments RSS feed links to head.
    add_theme_support( 'automatic-feed-links' );

    /*
     * Enable support for Post Thumbnails on posts and pages.
     *
     * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
     */
    add_theme_support( 'post-thumbnails' );

    // Add support for default block styles.
    add_theme_support( 'wp-block-styles' );

    add_theme_support( 'editor-styles' );
    add_editor_style( 'editor-style.css' );

    // Add support for full and wide align blocks.
    add_theme_support( 'align-wide' );
}
