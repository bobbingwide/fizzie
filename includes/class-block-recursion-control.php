<?php

/**
 * Class Fizzie_Block_Recursion_Control
 *
 * Implements the recursion control functions in an extendable class
 * Blocks use the public API passing the unique ID for their block.
 *
 * - fizzie_process_this_content( $id, $block_name )
 * - fizzie_clear_processed_content()
 * - fizzie_report_recursion_error( $message )
 *
 * These functions will be implemented by methods
 * accessed using Fizzie_Block_Recursion_Control::get_instance
 */


class Fizzie_Block_Recursion_Control {

    /**
     * Stack of recursive blocks unique keys.
     *
     * @var array
     *
     */
    public $processed_content = [];

    /**
     * ID of latest block.
     * This could be the last straw
     */
    public $id;

    /**
     * Block name of the latest block
     */
    public $block_name;

    /**
     * Container for the main instance of the class.
     *
     * @var Fizzie_Block_Recursion_Control|null
     */
    private static $instance = null;

    /**
     * Utility method to retrieve the main instance of the class.
     *
     * The instance will be created if it does not exist yet.
     *
     * @since
     *
     * @return Fizzie_Block_Recursion_Control The main instance.
     */
    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Determines whether or not to process this content.
     *
     * @param string|integer $id Unique ID for the content
     * @param string $block_name Block name e.g. core/post-content
     * @return bool - true if the post has not been processed. false otherwise
     */
    function process_this_content( $id, $block_name ) {
        $this->id = $id;
        $this->block_name = $block_name;
        $processed = isset( $this->processed_content[ $id ] );
        if ( !$processed ) {
            $this->processed_content[$id] = "$block_name $id" ;
        }

        /** Stop when it looks highly likely we've missed something */
        if ( count( $this->processed_content ) > 10 ) {
            $processed = true;

        }
        bw_trace2( $this->processed_content, "processed posts", true, BW_TRACE_DEBUG );
        return( !$processed );
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
    function clear_processed_content() {
       $id = array_pop( $this->processed_content );
       bw_trace2( $this->processed_content, "cleared", false, BW_TRACE_DEBUG );
       bw_trace2( $id, "popped", false, BW_TRACE_VERBOSE);
    }

    function get_id() {
        return $this->id;
    }

    function get_processed_content() {
        return $this->processed_content;
    }

    function get_block_name() {
        return $this->block_name;
    }

}