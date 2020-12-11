<?php
/**
 * Overrides the behaviour of the core/post-excerpt block.
 *
 * This used to append a missing `</div` to the end of the excerpt.
 * In actual fact the logic was wrong.
 * This isn't needed anymore as Gutenberg's been fixed.
 *
 * Keeping this function in case I want to do other things with core/post-excerpt.
 * eg Recursion checking.
 *
 * @param $attributes
 * @param $content
 * @param $block
 * @return string
 */
function fizzie_render_block_core_post_excerpt( $attributes, $content, $block ) {
    $html = gutenberg_render_block_core_post_excerpt( $attributes, $content, $block );
    return $html;
}
