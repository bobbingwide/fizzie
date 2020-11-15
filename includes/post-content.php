<?php
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
    //bw_trace2( $block->context['postId'], "postId" );
    //bw_trace2( get_the_id(), "get_the_ID", false );
    /*
    if ( 'revision' === $block->context['postType'] ) {
        return '';
    }
    */

    if ( fizzie_process_this_content( $block->context['postId'], $block->name ) ) {
        $html = gutenberg_render_block_core_post_content( $attributes, $content, $block );
        fizzie_clear_processed_content();
    } else {
        $html = fizzie_report_recursion_error();
    }
    return $html;
}
