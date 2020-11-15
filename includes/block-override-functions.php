<?php
/**
 * Implements common functions used in block overrides
 *
 */
require_once __DIR__ . '/class-block-recursion-control.php';


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
 * @param string|integer $id Unique ID for the content
 * @param string $block_name Block name
 * @return bool - true if the post has not been processed. false otherwise
 */
function fizzie_process_this_content( $id, $block_name ) {
    $recursion_control = Fizzie_Block_Recursion_Control::get_instance();
    return $recursion_control->process_this_content( $id, $block_name );
}

/**
 * Pops the last item of processed content.
 *
 * As we return to the previous level we can clear the processed content.
 * Basically this is something we have to do while processing certain inner blocks:
 *
 * - core/post-content
 * - core/template-part
 * - core/block
 * - core/post-excerpt - possibly
 *
 * Note: The top level is within the template, which loads the template parts and/or queries.
 *
 */
function fizzie_clear_processed_content() {
    $recursion_control = Fizzie_Block_Recursion_Control::get_instance();
    $recursion_control->clear_processed_content();
}

/**
 * Reports a recursion error to the user.
 *
 * If WP_DEBUG is true then additional information is displayed.
 * Use the $class parameter to override default behavior.
 *
 * @param $id string|integer recursive ID detected
 * @param $type string content type
 * @return string HTML reporting the error to the user
 */
function fizzie_report_recursion_error( $message=null, $class=null ) {
    $recursion_control = Fizzie_Block_Recursion_Control::get_instance();
    if ( $class && class_exists( $class ) ) {
        $recursion_error = new $class( $recursion_control );
    } else {
        $recursion_error = new Fizzie_Block_Recursion_Error( $recursion_control );
    }
    $html = $recursion_error->report_recursion_error( $message );
    return $html;
}
