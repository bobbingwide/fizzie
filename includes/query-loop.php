<?php
/**
 * Overrides core/query-loop to implement main query processing.
 *
 * Hack until a solution is delivered in Gutenberg.
 *
 * @param $attributes
 * @param $content
 * @param $block
 * @return string
 */
function fizzie_render_block_core_query_loop( $attributes, $content, $block ) {
    if ( isset( $block->context['queryId'] ) ) {
        $html = gutenberg_render_block_core_query_loop( $attributes, $content, $block );
    } else {
        $html = fizzie_render_block_core_query_loop_main_query( $attributes, $content, $block );
    }
    return $html;
}

/**
 * Renders the `core/query-loop` block for the main query on the server.
 *
 * @param array    $attributes Block attributes.
 * @param string   $content    Block default content.
 * @param WP_Block $block      Block instance.
 *
 * @return string Returns the output of the query, structured using the layout defined by the block's inner blocks.
 */
function fizzie_render_block_core_query_loop_main_query( $attributes, $content, $block ) {
    if ( have_posts() ) {
        $content = '';
        while ( have_posts() ) {
            the_post();
            $post = get_post();
            $content .= (
            new WP_Block(
                $block->parsed_block,
                array(
                    'postType' => $post->post_type,
                    'postId' => $post->ID,
                )
            )
            )->render(array('dynamic' => false));
        }
    } else {
        $content = __( "No posts found.", "fizzie" );
    }
    return $content;
}
