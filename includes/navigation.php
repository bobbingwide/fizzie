<?php

/**
 * Overrides core/navigation
 *
 * @TODO - process inner blocks to set the className for the current-menu-item
 * then set the ancestors on the way back up.
 *
 * @param $attributes
 * @param $content
 * @param $block
 * @return string
 */
function fizzie_render_block_core_navigation( $attributes, $content, $block ) {
    //bw_trace2();
    $html = gutenberg_render_block_core_navigation( $attributes, $content, $block );
    bw_trace2( $html, "html");
    return $html;
}
