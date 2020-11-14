<?php
/**
 * Implements common functions used in block overrides
 *
 */

/**
 * Overrides a core block's render_callback method, if required.
 *
 * For the given blockname, if the overriding function is available
 * and the current callback is the gutenberg function
 * replace the render_callback with our own function.
 *
 * @param array $args Block attributes.
 * @param string $blockname The block name to test for.
 * @param string $render_callback The common suffix for the block's callback function.
 * @return array Block attributes.
 */
function fizzie_maybe_override_block( $args, $blockname, $render_callback ) {
    $fizzie_render_callback = 'fizzie_' . $render_callback;
    if ( $blockname == $args['name'] && function_exists( $fizzie_render_callback ) ) {
        if ( 'gutenberg_' . $render_callback == $args['render_callback'] ) {
            $args['render_callback'] = $fizzie_render_callback;
        }
    }
    return $args;
}


/**
 * Determines whether or not to process this content.
 *
 * @param string|integer Unique ID for the content
 * @return bool - true if the post has not been processed. false otherwise
 */
function fizzie_process_this_content( $id  ) {
    global $fizzie_processed_content;
    // Use this rather than context['postId']
    global $post;
    $fizzie_processed = bw_array_get( $fizzie_processed_content, $id, false );

    /*
    if ( empty( $fizzie_processed_content ) ) {
        $global_id = isset( $post ) ? $post->ID : null;
        if ( $global_id ) {
            $fizzie_processed_content[ $global_id ] = $global_id;
        }
    }
    */


    if ( !$fizzie_processed ) {
        $fizzie_processed_content[$id] = $id ;
    }

    /** Stop when it looks highly likely we've missed something */
    if ( count( $fizzie_processed_content ) > 10 ) {
        $fizzie_processed = true;

    }
    bw_trace2( $fizzie_processed_content, "processed posts", true, BW_TRACE_DEBUG );
    return( !$fizzie_processed );
}

/**
 * Pops or clears the array of processed content.
 *
 * As we return to the previous level we can clear the processed content.
 * Basically this is something we have to do while processing certain inner blocks:
 *
 * - core/post-content
 * - core/template-part
 * - core/post-excerpt - possibly
 * - core/block - possibly
 *
 * Note: The top level is within the template, which loads the template parts and/or queries.
 */
function fizzie_clear_processed_content( $id=null ) {
    global $fizzie_processed_content;
    if ( $id ) {
        array_pop( $fizzie_processed_content );
    } else {
        $fizzie_processed_content = array();
    }
    bw_trace2( $fizzie_processed_content, "cleared", false, BW_TRACE_DEBUG );
}

/**
 * Reports a recursion error to the user.
 *
 * If WP_DEBUG is true then additional information is displayed.
 *
 * @param $id string|integer recursive ID detected
 * @param $type string content type
 * @return string
 */
function fizzie_report_recursion_error( $id, $type='core/post-content') {
    bw_trace2();
    bw_backtrace();
    global $bad_id, $bad_fizzie_processed_content, $fizzie_processed_content;
    $bad_id = $id;
    $bad_fizzie_processed_content = $fizzie_processed_content;

    $content = array();
    $content[] = '<div class="recursion-error">';
    switch ( $type ) {
        case 'core/post-content':
            $content[] = __( 'Content not available; already processed.', 'fizzie' );
            break;
        case 'core/template-part':
            $content[] = __( 'Template part not processed to avoid infinite recursion', 'fizzie');
            break;
        case 'core/block':
            $content[] = __( 'Reusable block not processed to avoid infinite recursion', 'fizzie');
            fizzie_add_filter_rest_prepare_wp_block();
            break;
        default:
            $content[] = __( 'Infinite recursion error prevented', 'fizzie');
    }
    if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
        $content[] = "<span class=\"recursion-error-context $type\">";
        $content[] = '<br />';
        $content[] = $id;
        $content[] = '<br />';
        $content[] = $type;
        $content[] = '<br />';
        global $fizzie_processed_content;
        $content[] = implode( ',', $fizzie_processed_content );
        $content[] = '</span>';
    }
    $content[] = '</div>';
    $content = implode( " \n", $content);
    return $content;
}

function fizzie_add_filter_rest_prepare_wp_block() {
    // We only need to do this in REST API processing
    // But this will only be called in REST API processing
    // So no need to check
    add_filter('rest_prepare_wp_block', 'fizzie_rest_prepare_wp_block', 10, 3);
}

/**
 *
 * Fiddle with the raw content to remove the wp:block block.
 *
 * [raw] => <!-- wp:paragraph -->
<p>This is reusable block 21117</p>
<!-- /wp:paragraph -->

<!-- wp:block {"ref":1134} /-->
 * @param $response
 * @param $post
 * @param $request
 * @return mixed#
 */
function fizzie_rest_prepare_wp_block( $response, $post, $request ) {
    bw_trace2();
    bw_backtrace();
    global $bad_id;
    global $bad_fizzie_processed_content;
    $content = $response->data['content']['raw'];
    $content = fizzie_replace_bad( $content, $bad_id );
    foreach ( $bad_fizzie_processed_content as $id ) {
        $content = fizzie_replace_bad( $content, $id );
    }
    // Convert any remaining to a missing block
    $content = str_replace( "wp:block {", "missing {", $content );

    $response->data['content']['raw'] = $content;
    bw_trace2(  $response, "response", false );
    return $response;
}

function fizzie_replace_bad( $content, $bad_id ) {
    $replacement_block = "<!-- wp:paragraph -->";
    $replacement_block .= sprintf(__('Error: Recursion was detected while loading the reusable block with post ID %1$s. '), $bad_id);
    $replacement_block .= fizzie_title_link($bad_id);
    $replacement_block .= '<br />';
    $replacement_block .= "Recommendation: Please delete this block.";
    $replacement_block .= "<!-- /wp:paragraph -->";
    $content = str_replace("<!-- wp:block {\"ref\":$bad_id} /-->", $replacement_block, $content);
    return $content;
}


function fizzie_title_link( $id  ) {
    $link = sprintf( '<a href="%s">%s</a>', esc_url( get_permalink( $id ) ), esc_html( get_the_title( $id ) ) );
    return $link;
}



