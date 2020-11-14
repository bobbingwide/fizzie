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
