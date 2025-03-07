<?php

// Child theme styles

function twentytwentyfour_child_enqueue_styles() {

  wp_enqueue_style('bootstrap', 'https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css');
  wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');

  wp_enqueue_script('ajax-filter', get_stylesheet_directory_uri() . '/js/ajax-filter.js', array('jquery'), null, true);
  wp_localize_script('ajax-filter', 'ajax_filter', array(
    'ajax_url' => admin_url('admin-ajax.php'),
  ));

}

add_action('wp_enqueue_scripts', 'twentytwentyfour_child_enqueue_styles');

require_once get_stylesheet_directory() . '/inc/custom-post-types.php';
require_once get_stylesheet_directory() . '/inc/sample-books.php';
require_once get_stylesheet_directory() . '/inc/filters.php';
require_once get_stylesheet_directory() . '/inc/rest-api.php';
require_once get_stylesheet_directory() . '/inc/url-structure.php';
require_once get_stylesheet_directory() . '/inc/add-books.php';



