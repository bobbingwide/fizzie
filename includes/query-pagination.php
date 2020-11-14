<?php

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
