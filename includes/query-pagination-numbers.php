<?php

/**
 * Overrides core/query-pagination-numbers to implement main query pagination.
 *
 * Another hack (for Gutenberg 10 ) until a better solution is delivered in Gutenberg
 *
 * @param $attributes
 * @param $content
 * @param $block
 * @return string
 */
function fizzie_render_block_core_query_pagination_numbers( $attributes, $content, $block ) {
	bw_trace2();

	if ( isset( $block->context['queryId'] ) ) {
		$html = gutenberg_render_block_core_query_pagination_numbers( $attributes, $content, $block );
	} else {
		$html = fizzie_render_block_core_query_pagination_numbers_main_query( $attributes, $content, $block );
	}
	return $html;
}

/**
 * Renders the `core/query-pagination-numbers` block on the server for the main query.
 *
 * @param array    $attributes Block attributes.
 * @param string   $content    Block default content.
 * @param WP_Block $block      Block instance.
 *
 * @return string Returns the pagination for the query.
 */
function fizzie_render_block_core_query_pagination_numbers_main_query( $attributes, $content, $block ) {
	$html = '<div class="wp-block-query-pagination-numbers">';
	$html .= paginate_links( [ 'type' => 'list'] );
	$html .= "</div>";
	return $html;
}
