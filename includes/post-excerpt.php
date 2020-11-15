<?php
/**
 * Appends the missing </div> to the core/post-excerpt block.
 *
 * @param $attributes
 * @param $content
 * @param $block
 * @return string
 */
function fizzie_render_block_core_post_excerpt( $attributes, $content, $block ) {
    bw_trace2();

    $html = gutenberg_render_block_core_post_excerpt( $attributes, $content, $block );
    // Should really check that it's missing.
    if ( 0 !== strrpos( $html, '</div>') ) {
        $html .= '</div>';
    }
    return $html;
}
