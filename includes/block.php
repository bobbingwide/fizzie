<?php
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
    if ( fizzie_process_this_content( $attributes['ref'], $block->name ) ) {
        $html = gutenberg_render_block_core_block( $attributes, $content, $block );
        fizzie_clear_processed_content();
    } else {
        $html = fizzie_report_recursion_error( null, 'Fizzie_Block_Recursion_Error_Block' );
    }
    bw_trace2( $html, "html", false);
    return $html;
}

