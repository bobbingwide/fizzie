<?php

/**
 * Adds theme support that's not yet enabled in theme.json.
 *
 *  Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 *
 */
function fizzie_after_setup_theme()
{
    add_action('wp_enqueue_scripts', 'fizzie_enqueue_styles');
    add_action('wp_enqueue_scripts', 'fizzie_enqueue_a2z');

    add_theme_support('wp-block-styles');
    //add_theme_support('align-wide');
    add_theme_support('custom-line-height');
    add_theme_support('responsive-embeds');

    /**
     * If you use add_editor_style() it calls
     * add_theme_support( 'editor-style' );
     * ... which appears to be different from
     * add_theme_support( 'editor-styles' );
     * Or is it?
     *
     * There's also 'dark-editor-style'.
     */

    /** Add support for using link colour in certain blocks
     * https://developer.wordpress.org/block-editor/developers/themes/theme-support/#experimental-%e2%80%94-link-color-control
     */
    add_theme_support('experimental-link-color');

	// Add support for custom units.  Is this necessary for FSE?
	//add_theme_support( 'custom-units' );

    /**
     * You need to register nav menus in order for admin to display Appearance > Menus
     * and for Navigation (beta) to allow you to Manage locations.
     * But it's not at all clear how you indicate these locations in the theme's templates
     * and the core/navigation block doesn't refer to a menu name.
     *
     * Additionally, I can't see how the server side rendering adds the class names required
     * to allow the menu to display the current selection.
     */
    register_nav_menu( 'header', 'Header menu');
    register_nav_menu( 'footer', 'Footer menu');

    // Add default posts and comments RSS feed links to head.
    add_theme_support( 'automatic-feed-links' );
    /*
    * Enable support for Post Thumbnails on posts and pages.
    *
    * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
    */
    add_theme_support( 'post-thumbnails' );
    // There should be no need to set post thumbnail size.
    //set_post_thumbnail_size( 256, 256, false );

    // Add support for default block styles.
    add_theme_support( 'wp-block-styles' );

    //add_theme_support( 'editor-styles' );
    //add_editor_style( 'style-editor.css' );
    // Enqueue editor styles.
    //add_editor_style( 'style.css' );
    //add_editor_style( 'style-editor.css' );

    // Add support for full and wide align blocks.
    //add_theme_support( 'align-wide' );
    add_action( 'post_edit_form_tag', 'fizzie_enable_wp_navigation_editor');


}

/**
 * Enables oik based shortcodes.
 */
function fizzie_init() {
    if (function_exists('bw_add_shortcode')) {
        do_action("oik_add_shortcodes");
    }
    add_shortcode( 'post-edit', 'fizzie_post_edit' );
}

function fizzie_enqueue_styles() {
	//$theme_version = wp_get_theme()->get( 'Version' );

	if ( defined( 'SCRIPT_DEBUG') && SCRIPT_DEBUG ) {
        $theme_version = filemtime( get_stylesheet_directory() . "/style.css" );
	} else {
        $theme_version = wp_get_theme()->get( 'Version' );
    }
	wp_enqueue_style( 'fizzie', get_stylesheet_uri(), array(), $theme_version );
}

/**
 * Enqueue special styles for archives
 */
function fizzie_enqueue_a2z() {
    //echo get_template_directory(); fizzie
    //echo get_stylesheet_directory(); wizzie
    $timestamp = null;
    if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
        $timestamp = filemtime( get_template_directory() . "/category.css" );
    }
    wp_enqueue_style( "category-css", get_template_directory_uri() . '/category.css', array() );
}



add_action( 'after_setup_theme', 'fizzie_after_setup_theme');
//add_action( 'after_setup_theme', 'fizzie_stanley_theme_support');
add_action( 'init', 'fizzie_init', 20 );

/**
 * Use front-page.php when Front page displays is set to a static page.
 *
 * @since Twenty Seventeen 1.0
 *
 * @param string $template front-page.php.
 * @return string The template to be used: blank if is_home() is true (defaults to index.php),
 *                otherwise $template.
 */
function fizzie_front_page_template( $template ) {
    //echo $template;
    return is_home() ? '' : $template;
}
add_filter( 'frontpage_template', 'fizzie_front_page_template' );

/**
 * Implements [post-edit] shortcode.
 *
 * If the user is authorised return a post edit link for the current post.
 *
 * @param $attrs
 * @param $content
 * @param $tag
 *
 * @return string
 */

function fizzie_post_edit( $attrs, $content, $tag ) {
	$link = '';
	$url = get_edit_post_link();
	if ( $url ) {
		$class = 'bw_edit';
		$text= __( '[Edit]', 'fizzie' );
		$link='<a class="' . esc_attr( $class ) . '" href="' . esc_url( $url ) . '">' . $text . '</a>';
	}
	return $link;
}

function fizzie_enable_wp_navigation_editor( $post ) {
    remove_action( 'edit_form_after_title', '_disable_content_editor_for_navigation_post_type' );
    remove_action( 'edit_form_after_title', 'gutenberg_disable_content_editor_for_navigation_post_type');
}

require_once __DIR__ . '/includes/block-overrides.php';



if ( !function_exists( "bw_trace2" ) ) {
    function bw_trace2( $content=null, $args=null) {
        return $content;
    }

}