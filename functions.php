<?php
function fizzie_after_setup_theme() {
	add_action( 'wp_enqueue_scripts', 'fizzie_enqueue_styles' );
}

function fizzie_enqueue_styles() {
	$theme_version = wp_get_theme()->get( 'Version' );
	wp_enqueue_style( 'fizzie', get_stylesheet_uri(), array(), $theme_version );
}

add_action( 'after_setup_theme', 'fizzie_after_setup_theme');
