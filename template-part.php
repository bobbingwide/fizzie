<?php

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
        $content = fizzie_load_template_part_for_theme( $theme, $slug );
    }
    if ( !$content && $slug ) {
        $template_part_file_path = get_stylesheet_directory() . '/block-template-parts/' . $slug . '.html';
        $content = fizzie_load_template_part_file_by_slug( $slug, $template_part_file_path );
    }

    // What's the best way of telling the user that something's gone wrong?
    // Do we need to account for the fact that the block could have been malformed?
    //
    if ( is_null( $content ) ) {

        // $content = fizzie_create_error_block( );
        $atts = implode( $attributes );
        $html = 'Template Part Not Found: ' . $slug;
        $html .= 'PostId: ' . $postId;
        $html .= "Theme:" . $theme;
        $html .= $template_part_file_path;
        $html .= $atts;
        $content = $html;
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

function fizzie_lazy_render_block_core_template_part( $attributes, $content, $block  ) {
    $content = null;
    $template_part_file_path = null;
    $postId = null;
    //bw_trace2();
    //bw_backtrace();

    $content = fizzie_load_template_part( $attributes );
    //bw_trace2( $content, "raw content" );

    // Run through the actions that are typically taken on the_content.
    $content = do_blocks( $content );
    $content = wptexturize( $content );
    $content = convert_smilies( $content );
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
    $content = shortcode_unautop( $content );
    if ( function_exists( 'wp_filter_content_tags' ) ) {
        $content = wp_filter_content_tags( $content );
    } else {
        $content = wp_make_content_images_responsive( $content );
    }
    $content            = do_shortcode( $content );
    $html_tag           = esc_attr( $attributes['tagName'] );
    $wrapper_attributes = get_block_wrapper_attributes();

    return "<$html_tag $wrapper_attributes>" . str_replace( ']]>', ']]&gt;', $content ) . "</$html_tag>";
}