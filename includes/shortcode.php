<?php

/**
 * Overrides core/shortcode to expand the shortcodes in the block.
 *
 * Hack until a solution is delivered in Gutenberg for 43053 or 35676
 *
 * @param $attributes
 * @param $content
 * @param $block
 *
 * @return string
 */
function fizzie_render_block_core_shortcode( $attributes, $content, $block ) {
	bw_trace2();
	$html = do_shortcode( $content );
	return $html;
}
