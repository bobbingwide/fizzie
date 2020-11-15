<?php

/**
 * Class Fizzie_Block_Recursion_Error
 *
 * Implements the recursion error functions in an extendable class.
 * The extending class may be specified as the $class parameter.
 *
 * ```
 * $html = fizzie_report_recursion_error( $message, $class=null )
 * ```
 */
class Fizzie_Block_Recursion_Error {

    private $recursion_control = null;

    protected $bad_id = null;
    protected $bad_processed_content = [];
    protected $block_name = null; // The block name
    protected $message = null;

    public function __construct( $recursion_control = null) {
        if ($recursion_control) {
            $this->recursion_control = $recursion_control;
        } else {
            $this->recursion_control = Fizzie_Block_Recursion_Control::get_instance();
        }
    }

    /**
     * Start to produce the error block to be displayed on the front end.
     *
     * @TODO Implement as a core block.
     *
     * @return string
     */
    function start_error_block() {
        $content = '<div class="recursion-error">';
        return $content;
    }

    /**
     * Complete the error block to be displayed on the front end.
     *
     * @TODO Implement as a core block.
     *
     * @return string
     */
    function complete_error_block() {
        $content = '</div>';
        return $content;
    }

    /**
     * Returns the message.
     *
     * The default message varies depending on the block type where recursion was detected.
     *
     * @return string
     */
    function get_message() {
        if (!$this->message) {
            switch ($this->block_name ) {
                case 'core/post-content':
                    $this->message = __('Content not available; already processed.', 'fizzie');
                    break;
                case 'core/template-part':
                    $this->message = __('Template part not processed to avoid infinite recursion', 'fizzie');
                    break;
                case 'core/block':
                    $this->message = __('Reusable block not processed to avoid infinite recursion', 'fizzie');
                    break;
                default:
                    $this->message = __('Infinite recursion error prevented', 'fizzie');
                    $this->message .= sprintf(__('Block name: %1$s', 'fizzie'), $this->block_name );
            }
        }
        return $this->message;
    }


    /**
     * Returns details to help with problem determination.
     *
     * This is only done when WP_DEBUG is true.
     *
     * @return string HTML with additional details.
     */
    function report_debug_details()     {
        $content = [];
        if (defined('WP_DEBUG') && WP_DEBUG) {
            $content[] = "<span class=\"recursion-error-context \">";
            $content[] = '<br />';
            $content[] = $this->bad_id;
            $content[] = '<br />';
            $content[] = $this->block_name;
            $content[] = '<br />';

            $content[] = implode(',', $this->bad_processed_content);
            $content[] = '</span>';
        }

        $content = implode(" \n", $content);
        return $content;
    }


    /**
     * Reports a recursion error to the user.
     *
     * If WP_DEBUG is true then additional information is displayed.
     *
     * @param $message string
     * @return string
     */
    public function report_recursion_error( $message=null ) {
        bw_trace2();
        bw_backtrace();

        $this->bad_id = $this->recursion_control->get_id();
        $this->block_name = $this->recursion_control->get_block_name();
        $this->bad_processed_content = $this->recursion_control->get_processed_content();
        $this->set_message( $message );
        //$this->set_message( __( 'Infinite recursion error prevented' , 'fizzie' ) );
        $content = $this->start_error_block();
        $content .= $this->get_message();
        $content .= $this->report_debug_details();
        $content .= $this->complete_error_block();
        return $content;
    }

    public function set_message( $message=null ) {
        if ( null !== $message ) {
            $this->message = $message;
        }
    }

}