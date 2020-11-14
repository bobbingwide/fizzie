<?php
/**
 * Implements block overrides as required.
 *
 */
require_once __DIR__ . '/block-override-functions.php';

/**
 * Here we include only the blocks we want to override.
 *
 * Either comment out the ones that aren't needed any more
 * or find another way of detecting whether or not to include the file.
 */
require_once __DIR__ . '/query-pagination.php';
require_once __DIR__ . '/query-loop.php';



/**
 * Hook into register_block_types_args before WP_Block_Supports
 */
add_filter( 'register_block_type_args', 'fizzie_register_block_type_args', 9 );

function fizzie_register_block_type_args( $args ) {
    $args = fizzie_maybe_override_block(  $args,'core/query-pagination', 'render_block_core_query_pagination');
    $args = fizzie_maybe_override_block(  $args,'core/query-loop', 'render_block_core_query_loop' );


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

    if ( 'core/block' == $args['name'] ) {
        if ( 'gutenberg_render_block_core_block' == $args['render_callback'] ) {
            $args['render_callback'] = 'fizzie_render_block_core_block';
        }
    }

    return $args;
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
        fizzie_clear_processed_content( get_the_ID() );
    } else {
        $html = fizzie_report_recursion_error( get_the_ID() );
        //$html .= $content;
    }

    return $html;
}



/**
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
/**
 * Renders the `core/block` block on server.
 *
 * @param array $attributes The block attributes.
 *
 * @return string Rendered HTML of the referenced block.
 */
function fizzie_render_block_core_block( $attributes, $content, $block ) {
    bw_trace2();
       if ( empty( $attributes['ref'] ) ) {
        return '';
    }
    if ( fizzie_process_this_content( $attributes['ref'] ) ) {
        $html = gutenberg_render_block_core_block( $attributes, $content, $block );
        fizzie_clear_processed_content( $attributes['ref'] );
    } else {
        $html = fizzie_report_recursion_error( $attributes['ref'], 'core/block' );
    }
    bw_trace2( $html, "html", false);
    return $html;
}
