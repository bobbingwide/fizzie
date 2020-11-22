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
    bw_trace2( $block->inner_blocks);
    //if ( empty( $block->inner_blocks ) ) {
    //    $block->inner_blocks = fizzie_shim_inner_blocks( $block );
    //}
    $html = gutenberg_render_block_core_navigation( $attributes, $content, $block );
    bw_trace2( $html, "html", false);
    return $html;
}


function fizzie_shim_inner_blocks( $block ) {

    bw_trace( $block );

    $child_context = $block->available_context;

    if ( ! empty( $block->block_type->provides_context ) ) {
        foreach ( $block->block_type->provides_context as $context_name => $attribute_name ) {
            if ( array_key_exists( $attribute_name, $block->attributes ) ) {
                $child_context[ $context_name ] = $block->attributes[ $attribute_name ];
            }
        }
    }

    $registry = WP_Block_Type_Registry::get_instance();
    return new WP_Block_List(
            $block->parsed_block['innerBlocks'],
            $child_context,
            $registry
    );

}
