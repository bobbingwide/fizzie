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
require_once __DIR__ . '/post-excerpt.php';
require_once __DIR__ . '/post-content.php';
require_once __DIR__ . '/template-part.php';
require_once __DIR__ . '/navigation.php';
require_once __DIR__ . '/navigation-link.php';


/**
 * Hook into register_block_types_args before WP_Block_Supports
 */
add_filter( 'register_block_type_args', 'fizzie_register_block_type_args', 9 );

function fizzie_register_block_type_args( $args ) {
    $args = fizzie_maybe_override_block(  $args,'core/query-pagination', 'render_block_core_query_pagination');
    $args = fizzie_maybe_override_block(  $args,'core/query-loop', 'render_block_core_query_loop' );
    $args = fizzie_maybe_override_block(  $args,'core/post-excerpt', 'render_block_core_post_excerpt' );
    $args = fizzie_maybe_override_block(  $args,'core/post-content', 'render_block_core_post_content' );
    $args = fizzie_maybe_override_block(  $args,'core/template-part', 'render_block_core_template_part' );
    $args = fizzie_maybe_override_block(  $args,'core/navigation', 'render_block_core_navigation' );
    $args = fizzie_maybe_override_block(  $args,'core/navigation-link', 'render_block_core_navigation_link' );


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
