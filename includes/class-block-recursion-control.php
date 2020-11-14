<?php

/**
 * Class Fizzie_Block_Recursion_Control
 *
 * Implements the recursion control functions in an extendable class
 * Blocks use the public API passing the unique ID for their block.
 *
 * - fizzie_process_this_content( $id )
 * - fizzie_clear_processed_content( $id )
 * - fizzie_report_recursion_error( $id )
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
     * Bad $id where recursion was detected.
     */
    public $bad_id;
    /**
     * Call stack when recursion was detected.
     * @var
     */
    public $bad_processed_content;

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
     * @param string|integer Unique ID for the content
     * @return bool - true if the post has not been processed. false otherwise
     */
    function process_this_content( $id  ) {
        $processed = isset( $this->processed_content[ $id ] );
        if ( !$processed ) {
            $this->processed_content[$id] = $id ;
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
    function clear_processed_content( $id=null ) {
        if ( $id ) {
            array_pop( $this->processed_content );
        } else {
            $this->processed_content = array();
        }
        bw_trace2( $this->processed_content, "cleared", false, BW_TRACE_DEBUG );
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
    function report_recursion_error( $id, $type='core/post-content') {
        bw_trace2();
        bw_backtrace();

        $this->bad_id = $id;
        $this->bad_processed_content = $this->processed_content;
        // $content = $this->create_basic_error();

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
                $this->add_filter_rest_prepare_wp_block();
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

            $content[] = implode( ',', $this->processed_content );
            $content[] = '</span>';
        }
        $content[] = '</div>';
        $content = implode( " \n", $content);
        return $content;
    }

    /**
     * Adds a filter hook to alter the response for wp_block.
     *
     * There's no need to check if it is REST API processing.
     * The hook function won't get called if it isn't.
     */
    function add_filter_rest_prepare_wp_block() {
        add_filter( 'rest_prepare_wp_block', [ $this, 'rest_prepare_wp_block' ], 10, 3 );
    }

    /**
     * Fiddles with the raw content to remove `wp:block` blocks.
     * `
     * [raw] => <!-- wp:paragraph -->
     * <p>This is reusable block 21117</p>
     * <!-- /wp:paragraph -->
     * <!-- wp:block {"ref":1134} /-->
     * `
     * @param $response
     * @param $post
     * @param $request
     * @return mixed
     */
    function rest_prepare_wp_block( $response, $post, $request ) {
        bw_trace2();
        bw_backtrace();

        $content = $response->data['content']['raw'];
        $content = $this->replace_bad( $content, $this->bad_id );
        foreach ( $this->processed_content as $id ) {
            $content = $this->replace_bad( $content, $id );
        }
        // Convert any remaining `wp:block` values to a `missing` block.
        $content = str_replace( "wp:block {", "missing {", $content );

        $response->data['content']['raw'] = $content;
        bw_trace2(  $response, "response", false );
        return $response;
    }

    /**
     * Replaces wp:block for ref:bad_id with a paragraph.
     *
     * @param $content
     * @param $bad_id
     * @return string|string[]
     */
    function replace_bad( $content, $bad_id ) {
        $replacement_block = "<!-- wp:paragraph -->";
        $replacement_block .= sprintf(__('Error: Recursion was detected while loading the reusable block with post ID %1$s. '), $bad_id);
        $replacement_block .= $this->title_link($bad_id);
        $replacement_block .= '<br />';
        $replacement_block .= "Recommendation: Please delete this block.";
        $replacement_block .= "<!-- /wp:paragraph -->";
        $content = str_replace("<!-- wp:block {\"ref\":$bad_id} /-->", $replacement_block, $content);
        return $content;
    }

    /**
     * Returns a title link to the post.
     *
     * @param integer $id Post ID.
     * @return string HTML title link.
     */
    function title_link( $id  ) {
        $link = sprintf( '<a href="%s">%s</a>', esc_url( get_permalink( $id ) ), esc_html( get_the_title( $id ) ) );
        return $link;
    }

}