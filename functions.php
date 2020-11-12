<?php

/**
 *
 */
function fizzie_after_setup_theme()
{
    add_action('wp_enqueue_scripts', 'fizzie_enqueue_styles');
    add_action('wp_enqueue_scripts', 'fizzie_enqueue_a2z');

    add_theme_support('wp-block-styles');
    add_theme_support('align-wide');
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

}

/**
 * Enables oik based shortcodes.
 */
function fizzie_init() {
    if (function_exists('bw_add_shortcode')) {
        do_action("oik_add_shortcodes");
    }
    add_shortcode( 'archive_description', 'fizzie_archive_description' );
    add_shortcode( 'post-edit', 'fizzie_post_edit' );
}

function fizzie_enqueue_styles() {
	$theme_version = wp_get_theme()->get( 'Version' );

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
    $timestamp = null;
    if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
        $timestamp = filemtime( get_stylesheet_directory() . "/category.css" );
    }
    wp_enqueue_style( "category-css", get_stylesheet_directory_uri() . '/category.css', array() );
}



add_action( 'after_setup_theme', 'fizzie_after_setup_theme');
add_action( 'after_setup_theme', 'fizzie_stanley_theme_support');
add_action( 'init', 'fizzie_init', 20 );


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
    echo $template;
    return is_home() ? '' : $template;
}
add_filter( 'frontpage_template', 'fizzie_front_page_template' );

/**
 * Displays a description for an archive page, with title and description
 *
 * For genesis shortcodes such as [post_date] see genesis/lib/shortcodes/post.php
 */
function fizzie_archive_description( $attrs, $content, $tag ) {
    $term = null;
    $html = null;
    if (  is_tax() || is_tag() || is_category()  ) {
        $term = get_queried_object();
        if ($term) {
            $heading = $term->name;
            $description = term_description($term->term_id);
            $html = '<h2 class="term_heading">';
            $html .= $heading;
            $html .= '</h2>';
            $html .= $description;
        }
    }

    // ( is_post_type_archive() && genesis_has_post_type_archive_support() ) {
    //		$cpt_description = genesis_get_cpt_option( 'description' );
    //		$description     = $cpt_description ?: '';
    //	} elseif ( is_author() ) {
    //		$description = get_the_author_meta( 'meta_description', (int) get_query_var( 'author' ) );
    //	}
    return $html;
}

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

/**
 * Hook into register_block_types_args before WP_Block_Supports
 */
add_filter( 'register_block_type_args', 'fizzie_register_block_type_args', 9 );

function fizzie_register_block_type_args( $args ) {
    if ( 'core/query-pagination' == $args['name']) {
        if ( 'gutenberg_render_block_core_query_pagination' == $args['render_callback'] ) {
            $args['render_callback'] = 'fizzie_render_block_core_query_pagination';
        }
    }
    if ( 'core/query-loop' == $args['name'] ) {
        if ( 'gutenberg_render_block_core_query_loop' == $args['render_callback'] ) {
            $args['render_callback'] = 'fizzie_render_block_core_query_loop';
        }
    }

    if ( 'core/post-excerpt' == $args['name'] ) {
        if ( 'gutenberg_render_block_core_post_excerpt' == $args['render_callback'] ) {
            $args['render_callback'] = 'fizzie_render_block_core_post_excerpt';
        }
    }

    if ( 'core/post-content' == $args['name'] ) {
        if ( 'gutenberg_render_block_core_post_content' == $args['render_callback'] ) {
            $args['render_callback'] = 'fizzie_render_block_core_post_content';
        }
    }

    if ( 'core/template-part' == $args['name'] ) {
        if ( 'gutenberg_render_block_core_template_part' == $args['render_callback'] ) {
            $args['render_callback'] = 'fizzie_render_block_core_template_part';
        }
    }

    if ( 'core/navigation' == $args['name'] ) {
        if ( 'gutenberg_render_block_core_navigation' == $args['render_callback'] ) {
            $args['render_callback'] = 'fizzie_render_block_core_navigation';
        }
    }

    if ( 'core/navigation-link' == $args['name'] ) {
        if ( 'gutenberg_render_block_core_navigation_link' == $args['render_callback'] ) {
            $args['render_callback'] = 'fizzie_render_block_core_navigation_link';
        }
    }

	if ( 'core/post-hierarchical-terms' == $args['name'] ) {
		if ( 'gutenberg_render_block_core_post_hierarchical_terms' == $args['render_callback'] ) {
			$args['render_callback'] = 'fizzie_render_block_core_post_hierarchical_terms';
		}
	}
    return $args;
}

/**
 * Overrides core/query-pagination to implement main query pagination.
 *
 * Hack until a solution is delivered in Gutenberg.
 *
 * @param $attributes
 * @param $content
 * @param $block
 * @return string
 */
function fizzie_render_block_core_query_pagination( $attributes, $content, $block ) {
    if ( isset( $block->context['queryId'] ) ) {
        $html = gutenberg_render_block_core_query_pagination( $attributes, $content, $block );
    } else {
        $html = fizzie_render_block_core_query_pagination_main_query( $attributes, $content, $block );
    }
    return $html;
}

/**
 * Overrides core/query-loop to implement main query processing.
 *
 * Hack until a solution is delivered in Gutenberg.
 *
 * @param $attributes
 * @param $content
 * @param $block
 * @return string
 */
function fizzie_render_block_core_query_loop( $attributes, $content, $block ) {
    if ( isset( $block->context['queryId'] ) ) {
        $html = gutenberg_render_block_core_query_loop( $attributes, $content, $block );
    } else {
        $html = fizzie_render_block_core_query_loop_main_query( $attributes, $content, $block );
    }
    return $html;
}

/**
 * Renders the `core/query-pagination` block on the server for the main query.
 *
 * @param array    $attributes Block attributes.
 * @param string   $content    Block default content.
 * @param WP_Block $block      Block instance.
 *
 * @return string Returns the pagination for the query.
 */
function fizzie_render_block_core_query_pagination_main_query( $attributes, $content, $block ) {
    $html = '<div class="wp-block-query-pagination">';
    $html .= paginate_links( [ 'type' => 'list'] );
    $html .= "</div>";
    return $html;
}

/**
* Renders the `core/query-loop` block for the main query on the server.
 *
 * @param array    $attributes Block attributes.
 * @param string   $content    Block default content.
 * @param WP_Block $block      Block instance.
 *
 * @return string Returns the output of the query, structured using the layout defined by the block's inner blocks.
 */
function fizzie_render_block_core_query_loop_main_query( $attributes, $content, $block ) {
    if ( have_posts() ) {
        $content = '';
        while ( have_posts() ) {
            the_post();
            $post = get_post();
            $content .= (
            new WP_Block(
                $block->parsed_block,
                array(
                    'postType' => $post->post_type,
                    'postId' => $post->ID,
                )
            )
            )->render(array('dynamic' => false));
        }
    } else {
        $content = __( "No posts found." );
    }
    return $content;
}

/**
 * Appends the missing </div> to the core/post-excerpt block.
 *
 * @param $attributes
 * @param $content
 * @param $block
 * @return string
 */
function fizzie_render_block_core_post_excerpt( $attributes, $content, $block ) {
    $html = gutenberg_render_block_core_post_excerpt( $attributes, $content, $block );
    // Should really check that it's missing.
    if ( 0 !== strrpos( $html, '</div>') ) {
        $html .= '</div>';
    }
    return $html;
}

/**
 * Overrides core/post-content to return early in certain circumstances.
 *
 * Hack until a solution is delivered in Gutenberg.
 *
 * @param $attributes
 * @param $content
 * @param $block
 * @return string
 */
function fizzie_render_block_core_post_content( $attributes, $content, $block ) {
	if ( ! isset( $block->context['postId'] ) ) {
		return '';
	}
	/*
	if ( 'revision' === $block->context['postType'] ) {
		return '';
	}
	*/

	if ( fizzie_process_this_content( get_the_ID() ) ) {
		$html = gutenberg_render_block_core_post_content( $attributes, $content, $block );
	} else {
	    $html = fizzie_report_recursion_error( get_the_ID() );
	    //$html .= $content;
    }

    return $html;
}

/**
 * Determines whether or not to process this content.
 *
 * @param string|integer Unique ID for the content
 * @return bool - true if the post has not been processed. false otherwise
 */
function fizzie_process_this_content( $id  ) {
	global $processed_content;
	$processed = bw_array_get( $processed_content, $id, false );
	if ( !$processed ) {
		$processed_content[$id] = $id ;
	}
	bw_trace2( $processed_content, "processed posts", true, BW_TRACE_DEBUG );
	return( !$processed );
}

/**
 * Pops or clears the array of processed content.
 *
 * As we return to the previous level we can clear the processed content.
 * Basically this is something we have to do while processing certain inner blocks:
 *
 * - core/post-content
 * - core/template-part
 * - core/post-excerpt - possibly
 * - core/block - possibly
 *
 * Note: The top level is within the template, which loads the template parts and/or queries.
 */
function fizzie_clear_processed_content( $id=null ) {
    global $processed_content;
    if ( $id ) {
        array_pop( $processed_content );
    } else {
        $processed_content = array();
    }
    bw_trace2( $processed_content, "cleared", false, BW_TRACE_DEBUG );
}

/**
 * Reports a recursion error to the user.
 *
 * If WP_DEBUG is true then additional information is displayed.
 *
 * @param $id string|integer recursive ID detected
 * @param $type string content type
 * @return string
 */
function fizzie_report_recursion_error( $id, $type='post-content') {
    $content = array();
    $content[] = '<div class="recursion-error">';
    switch ( $type ) {
        case 'post-content':
            $content[] = __( 'Content not available; already processed.', 'fizzie' );
            break;
        case 'template-part':
            $content[] = __( 'Template part not processed to avoid infinite recursion', 'fizzie');
            break;
        default: 
            $content[] = __( 'Infinite recursion error prevented', 'fizzie');
    }   
    if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
        $content[] = "<span class=\"recursion-error-context $type\">";
        $content[] = '<br />';
        $content[] = $id;
        $content[] = '<br />';
        $content[] = $type;
        $content[] = '<br />';
        global $processed_content;
        $content[] = implode( ',', $processed_content );
        $content[] = '</span>';
    }
    $content[] = '</div>';
    $content = implode( " \n", $content);
    return $content;
}


/**
 * /**
 * Overrides core/template-part to return early in certain circumstances.
 *
 * Hack until a solution is delivered in Gutenberg.
 *
 * @param $attributes
 * @param $content
 * @param $block
 * @return string
 */
 function fizzie_render_block_core_template_part( $attributes, $content, $block ) {
    require_once __DIR__ . '/template-part.php';
    $html = fizzie_lazy_render_block_core_template_part( $attributes, $content, $block );
    return $html;
}

function fizzie_render_block_core_navigation( $attributes, $content, $block ) {
    //bw_trace2();
    $html = gutenberg_render_block_core_navigation( $attributes, $content, $block );
    bw_trace2( $html, "html");
    return $html;
}

function fizzie_render_block_core_navigation_link( $attributes, $content, $block ) {
    $attributes = fizzie_fiddle_nav_atts( $attributes );
    $html = gutenberg_render_block_core_navigation_link($attributes, $content, $block);
    return $html;
}

/**
 * Fiddles navigation-link attributes to get the required CSS classes.
 *
 * This is much simpler than the logic in `_wp_menu_item_classes_by_context( $menu_items )`.
 *
 * @TODO But, we also need to set the other classes such as current-menu-ancestor
 * which probably means we have to do this in core/navigation rather than core/navigation-link
 *
 * @param $attributes
 * @return mixed
 */
function fizzie_fiddle_nav_atts( $attributes ) {
    $attributes['url'] = str_replace( 'https://s.b/wp56', site_url(), $attributes['url'] );
    $request_uri = $_SERVER["REQUEST_URI"];
    $request_uri_path = parse_url( $request_uri, PHP_URL_PATH );
    $url_path = parse_url( $attributes['url'], PHP_URL_PATH );
    $site_url = trailingslashit( parse_url( site_url(), PHP_URL_PATH ) );
    /*
    echo $request_uri_path;
    echo ' ';
    echo $url_path;
    echo ' ';
    echo $site_url;
    echo '<br/>';
    */
    // We need to avoid the home page: home_url() or site_url()
    if ( $url_path === $site_url ) {
        if ( $url_path === $request_uri_path ) {
            $attributes['className'] = ['current-menu-item'];
        } else {
            // don't match this $url path with all the other paths

        }
    } elseif ( 0 === strpos( $request_uri, $url_path ) ) {

    // @TODO check that the attributes URL is
    //if ( parse_url( $request_uri, PHP_URL_PATH ) === parse_url( $attributes['url'], PHP_URL_PATH ) ) {
        $attributes['className'] = ['current-menu-item'];
    }
    return $attributes;
}

/**
 * Renders the `core/post-hierarchical-terms` block on the server.
 *
 * @param array    $attributes Block attributes.
 * @param string   $content    Block default content.
 * @param WP_Block $block      Block instance.
 * @return string Returns the filtered post hierarchical terms for the current post wrapped inside "a" tags.
 */
function fizzie_render_block_core_post_hierarchical_terms( $attributes, $content, $block ) {
	//bw_trace2();
	if ( ! isset( $block->context['postId'] ) || ! isset( $attributes['term'] ) ) {
		return '';
	}

	$post_hierarchical_terms = get_the_terms( $block->context['postId'], $attributes['term'] );
	//bw_trace2( $post_hierarchical_terms, "pht", false );
	if ( is_wp_error( $post_hierarchical_terms ) ) {
		return 'Taxonomy not recognised';
	}
	$html=gutenberg_render_block_core_post_hierarchical_terms( $attributes, $content, $block );

	return $html;
}


if ( !function_exists( "bw_trace2" ) ) {
    function bw_trace2( $content, $args) {
        return $content;
    }

}