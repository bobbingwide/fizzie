<?php

/**
 * Overrides core/template-part to return early in certain circumstances.
 *
 * Renders the template part if it's not recursive.
 * Hack until a solution is delivered in Gutenberg.
 *
 * @param $attributes
 * @param $content
 * @param $block
 * @return string
 */
function fizzie_render_block_core_template_part( $attributes, $content, $block  ) {

    $content = null;
    $template_part_file_path = null;
    $postId = null;
    //bw_trace2( $attributes);
    //bw_backtrace();
    $template_id = fizzie_get_template_id( $attributes );

    if ( fizzie_process_this_content( $template_id, $block->name  ) ) {

        $content = fizzie_load_template_part($attributes);
        //bw_trace2( $content, "raw content" );

        // Run through the actions that are typically taken on the_content.
        $content = do_blocks($content);
        $content = wptexturize($content);
        $content = convert_smilies($content);
        // Should we run wpautop() here?
        //$before = $content;
        //$content = wpautop( $content );
        /*
       if ( 0 !== strcmp( $before,  $content ) ) {
        bw_trace2( $before, "before", false );
        bw_trace2( $content, "after", false );
        bw_trace2(bw_trace_hexdump( $before ), "before wpautop", false);
        bw_trace2(bw_trace_hexdump( $content ), "after wpautop", false);
        }

        */

        // I can't see the point of these filters either
        /*
        $content = shortcode_unautop($content);*/
        if (function_exists('wp_filter_content_tags')) {
            $content = wp_filter_content_tags($content);
        } else {
            $content = wp_make_content_images_responsive($content);
        }

        // Except shortcode processing that is.

        $content = do_shortcode($content);
        // This code will now require Gutenberg 10.2.0
	    if ( defined( 'WP_TEMPLATE_PART_AREA_UNCATEGORIZED' )) {
		    $area=WP_TEMPLATE_PART_AREA_UNCATEGORIZED;
		    //$html_tag = isset( $attributes['tagName'] ) ? esc_attr($attributes['tagName']) : 'div';
		    if ( empty( $attributes['tagName'] ) ) {
			    $area_tags=array(
				    WP_TEMPLATE_PART_AREA_HEADER       =>'header',
				    WP_TEMPLATE_PART_AREA_FOOTER       =>'footer',
				    WP_TEMPLATE_PART_AREA_UNCATEGORIZED=>'div',
			    );
			    $html_tag =null !== $area && isset( $area_tags[ $area ] ) ? $area_tags[ $area ] : $area_tags[ WP_TEMPLATE_PART_AREA_UNCATEGORIZED ];
		    } else {
			    $html_tag=esc_attr( $attributes['tagName'] );
		    }
	    } else {
		    $html_tag = isset( $attributes['tagName'] ) ? esc_attr($attributes['tagName']) : 'div';
	    }

        $wrapper_attributes = get_block_wrapper_attributes();
        $html = "<$html_tag $wrapper_attributes>" . str_replace(']]>', ']]&gt;', $content) . "</$html_tag>";
        fizzie_clear_processed_content( );
    } else {
        $html = fizzie_report_recursion_error();
    }
    return $html;
}

function fizzie_load_template_part( $attributes ) {
    $content = null;
    $postId = empty( $attributes['postId'] ) ? null : $attributes['postId'];
    $theme = empty( $attributes['theme'] ) ? null : $attributes['theme'];
    $slug = empty( $attributes['slug'] ) ? null : $attributes['slug'];
    $template_part_file_path = null;

    if ( $postId ) {
        $content = fizzie_load_template_part_by_postId( $postId );
    }
    if ( !$content && $slug && $theme ) {
        //$content = fizzie_load_template_part_for_theme( $theme, $slug );
    }
    if ( !$content && $slug ) {
    	$locale = get_locale();

    	if ( 'en_US' !== $locale) {
		    $template_part_file_path = get_stylesheet_directory() . "/languages/$locale/parts/" . $slug . '.html';
	    } else {
		    $template_part_file_path = get_stylesheet_directory() . '/parts/' . $slug . '.html';
	    }

        $content = fizzie_load_template_part_file_by_slug( $slug, $template_part_file_path );
    	//echo $content;
    }

    // What's the best way of telling the user that something's gone wrong?
    // Do we need to account for the fact that the block could have been malformed?
    //
    if ( is_null( $content ) ) {

        // $content = fizzie_create_error_block( );

        $html = '<div>';
        $html = 'Template Part Not Found: ' . $slug;
        foreach ( $attributes as $key => $value ) {
        	$html .= "<div><span>$key</span>: <span>$value</span>";
        }
        $html .= "<div><span>File path</span>: <span>$template_part_file_path</span></div>";
       	$html .= '</div>';
	    $content = $html;

    }

    /**
     * Produce some visual aid to the theme developer, showing where the template starts and ends.
     *
     */
    if ( defined( 'FSE_DEBUG') && FSE_DEBUG ) {
        if (defined('WP_DEBUG') && WP_DEBUG) {
            $wrapped = "<div class=\"WP_DEBUG\">template part start: $slug</div>";
            $wrapped .= $content;
            $wrapped .= "<div class=\"WP_DEBUG END\">template part end: $slug</div>";
            $content = $wrapped;
            global $_wp_current_template_content;
            bw_trace2($_wp_current_template_content, "current template content", false, BW_TRACE_DEBUG);
        }
    }
    return $content;
}

/**
 *
 *
 * If we have a post ID and the post exists and it's a wp_template_part, which means this template part
 * is user-customized, render the corresponding post content.
 *
 * @param $postId
 * @return string|null
 */
function fizzie_load_template_part_by_postId( $postId ) {
    $content = null;
    $post = get_post( $postId );
    //bw_trace2( $post, "post", true );
    if ( $post && ( 'wp_template_part' === $post->post_type ) ) {
        $post_status = get_post_status( $postId );
        //bw_trace2( $post_status, "post_status", true );
        if ( 'publish' === $post_status ) {
            $content = $post->post_content;
        }
    }

    if ( !$content ) {
    	$content = "Template part cannot be loaded by postId: " . $postId;
    }
    return $content;
}

/**
 * Loads the wp_template_part by slug and theme name
 *
 * @param $theme
 * @param $slug
 * @return string|null
 */
function fizzie_load_template_part_for_theme( $theme, $slug ) {
    $content = null;
    if ( basename( wp_get_theme()->get_stylesheet() ) === $theme ) {
        $template_part_query = new WP_Query(
            array(
                'post_type'      => 'wp_template_part',
                'post_status'    => 'publish',
                'name'           => $slug,
                'meta_key'       => 'theme',
                'meta_value'     => $theme,
                'posts_per_page' => 1,
                'no_found_rows'  => true,
            )
        );
        $template_part_post  = $template_part_query->have_posts() ? $template_part_query->next_post() : null;
        if ( $template_part_post ) {
            // A published post might already exist if this template part was customized elsewhere
            // or if it's part of a customized template.
            $content = $template_part_post->post_content;
        }
    }
    return $content;
}

/**
 * Loads the template part file by slug name.
 *
 * Should this cater for child themes?
 *
 *
 * @param $slug
 * @return false|string
 */
function fizzie_load_template_part_file_by_slug( $slug, $template_part_file_path ) {
    $content = null;
    if ( 0 === validate_file( $slug ) ) {
        if ( file_exists( $template_part_file_path ) ) {
            $content = file_get_contents($template_part_file_path);
        }
    }
    return $content;
}

/**
 * Returns the unique template ID.
 *
 * The template ID is a concatenation of strings with a separator. eg slug:theme:postID
 * The separator should not be something that could be used in a slug or theme name.
 * Colon's not valid in a Windows directory name.
 *
 * @param $attributes array Attributes passed to the template-part
 * @return string Template ID
 */
function fizzie_get_template_id( $attributes ) {
    $parts = [];
    $parts[] = isset( $attributes['slug'] ) ? $attributes['slug'] : '';
    $parts[] = isset( $attributes['theme'] ) ? $attributes['theme'] : '';
    $parts[] = isset( $attributes['postId'] ) ? $attributes['postId'] : '0';
    $template_id = implode( ':', $parts );
    return $template_id;
}