<?php
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
