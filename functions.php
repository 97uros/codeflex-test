<?php

// Child theme styles

function twentytwentyfour_child_enqueue_styles() {
  wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
}
add_action('wp_enqueue_scripts', 'twentytwentyfour_child_enqueue_styles');

function register_books_cpt() {
  $labels = array(
    'name'               => __('Books', 'twentytwentyfour-child'),
    'singular_name'      => __('Book', 'twentytwentyfour-child'),
    'menu_name'          => __('Books', 'twentytwentyfour-child'),
    'name_admin_bar'     => __('Book', 'twentytwentyfour-child'),
    'add_new'            => __('Add New', 'twentytwentyfour-child'),
    'add_new_item'       => __('Add New Book', 'twentytwentyfour-child'),
    'edit_item'          => __('Edit Book', 'twentytwentyfour-child'),
    'new_item'           => __('New Book', 'twentytwentyfour-child'),
    'view_item'          => __('View Book', 'twentytwentyfour-child'),
    'all_items'          => __('All Books', 'twentytwentyfour-child'),
    'search_items'       => __('Search Books', 'twentytwentyfour-child'),
    'not_found'          => __('No books found.', 'twentytwentyfour-child'),
    'not_found_in_trash' => __('No books found in Trash.', 'twentytwentyfour-child'),
  );

  $args = array(
    'labels'             => $labels,
    'public'             => true,
    'show_in_rest'       => true,
    'menu_position'      => 5,
    'menu_icon'          => 'dashicons-book',
    'supports'           => array('title', 'editor', 'thumbnail', 'custom-fields'),
    'has_archive'        => true,
    'rewrite'            => array('slug' => 'book'),
  );

  register_post_type('book', $args);
}
add_action('init', 'register_books_cpt');

// Register Genre (Hierarchical)

function register_genre_taxonomy() {
  $labels = array(
    'name'              => __('Genres', 'twentytwentyfour-child'),
    'singular_name'     => __('Genre', 'twentytwentyfour-child'),
    'search_items'      => __('Search Genres', 'twentytwentyfour-child'),
    'all_items'         => __('All Genres', 'twentytwentyfour-child'),
    'parent_item'       => __('Parent Genre', 'twentytwentyfour-child'),
    'parent_item_colon' => __('Parent Genre:', 'twentytwentyfour-child'),
    'edit_item'         => __('Edit Genre', 'twentytwentyfour-child'),
    'update_item'       => __('Update Genre', 'twentytwentyfour-child'),
    'add_new_item'      => __('Add New Genre', 'twentytwentyfour-child'),
    'new_item_name'     => __('New Genre Name', 'twentytwentyfour-child'),
    'menu_name'         => __('Genre', 'twentytwentyfour-child'),
  );

  $args = array(
    'labels'            => $labels,
    'public'            => true,
    'hierarchical'      => true,
    'show_admin_column' => true,
    'show_in_rest'      => true,
    'rewrite'           => array('slug' => 'genre'),
  );

  register_taxonomy('genre', 'book', $args);
}
add_action('init', 'register_genre_taxonomy');

// Register Publisher (Non-Hierarchical)

function register_publisher_taxonomy() {
  $labels = array(
    'name'              => __('Publishers', 'twentytwentyfour-child'),
    'singular_name'     => __('Publisher', 'twentytwentyfour-child'),
    'search_items'      => __('Search Publishers', 'twentytwentyfour-child'),
    'all_items'         => __('All Publishers', 'twentytwentyfour-child'),
    'edit_item'         => __('Edit Publisher', 'twentytwentyfour-child'),
    'update_item'       => __('Update Publisher', 'twentytwentyfour-child'),
    'add_new_item'      => __('Add New Publisher', 'twentytwentyfour-child'),
    'new_item_name'     => __('New Publisher Name', 'twentytwentyfour-child'),
    'menu_name'         => __('Publisher', 'twentytwentyfour-child'),
  );

  $args = array(
    'labels'            => $labels,
    'public'            => true,
    'hierarchical'      => false,
    'show_admin_column' => true,
    'show_in_rest'      => true,
    'rewrite'           => array('slug' => 'publisher'),
  );

  register_taxonomy('publisher', 'book', $args);
}
add_action('init', 'register_publisher_taxonomy');

// Register ACF Fields for Books

function register_books_acf_fields() {
  if (function_exists('acf_add_local_field_group')) {

    acf_add_local_field_group(array(
      'key' => 'group_books_fields',
      'title' => __('Books Fields', 'twentytwentyfour-child'),
      'fields' => array(
          array(
            'key' => 'field_author',
            'label' => __('Author', 'twentytwentyfour-child'),
            'name' => 'author',
            'type' => 'text',
            'instructions' => __('Enter the author of the book.', 'twentytwentyfour-child'),
            'required' => 1,
          ),
          array(
            'key' => 'field_price',
            'label' => __('Price', 'twentytwentyfour-child'),
            'name' => 'price',
            'type' => 'number',
            'instructions' => __('Enter the price of the book.', 'twentytwentyfour-child'),
            'required' => 1,
          ),
          array(
            'key' => 'field_release_date',
            'label' => __('Release Date', 'twentytwentyfour-child'),
            'name' => 'release_date',
            'type' => 'date_picker',
            'instructions' => __('Pick the release date of the book.', 'twentytwentyfour-child'),
            'required' => 1,
          ),
      ),
      'location' => array(
        array(
          array(
            'param' => 'post_type',
            'operator' => '==',
            'value' => 'book',
          ),
        ),
      ),
      'style' => 'seamless',
    ));
  }
}
add_action('acf/init', 'register_books_acf_fields');
