<?php


class Fizzie_Block_Recursion_Error_Block extends Fizzie_Block_Recursion_Error {

    function __construct( $recursion_control ) {
        // Do we need to call super construct to set $this->bad_id and stuff?
        parent::__construct( $recursion_control );
    }

    function report_recursion_error( $message=null ) {
        $html = parent::report_recursion_error( $message );
        $this->add_filter_rest_prepare_wp_block();
        return $html;
    }

    /**
     * Adds a filter hook to alter the response for wp_block.
     *
     * There's no need to check if it is REST API processing.
     * The hook function won't get called if it isn't.
     */
    function add_filter_rest_prepare_wp_block() {
        add_filter('rest_prepare_wp_block', [$this, 'rest_prepare_wp_block'], 10, 3);
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
    function rest_prepare_wp_block($response, $post, $request)
    {
        bw_trace2();
        bw_backtrace();

        $content = $response->data['content']['raw'];
        $content = $this->replace_bad($content, $this->bad_id);
        foreach ($this->bad_processed_content as $id) {
            $content = $this->replace_bad($content, $id);
        }
        // Convert any remaining `wp:block` values to a `missing` block.
        $content = str_replace("wp:block {", "missing {", $content);

        $response->data['content']['raw'] = $content;
        bw_trace2($response, "response", false);
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
        $replacement_block .= $this->title_link( $bad_id );
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
    function title_link($id) {
        $link = sprintf('<a href="%s">%s</a>', esc_url(get_permalink($id)), esc_html(get_the_title($id)));
        return $link;
    }

}