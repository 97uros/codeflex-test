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

// Sample books

function add_sample_books() {
  if ( ! get_page_by_title( 'Sample Book 1', OBJECT, 'book' ) ) {
      // Sample Book 1
      $book1 = wp_insert_post( array(
          'post_title'   => 'Sample Book 1',
          'post_type'    => 'book',
          'post_status'  => 'publish',
          'post_author'  => 1, // Adjust for the admin user ID
      ) );

      // Set ACF fields for Sample Book 1
      update_field( 'author', 'John Doe', $book1 );
      update_field( 'price', 19.99, $book1 );
      update_field( 'release_date', '2023-05-10', $book1 );

      // Assign taxonomies (Genre and Publisher)
      wp_set_object_terms( $book1, array( 'Fiction' ), 'genre' );
      wp_set_object_terms( $book1, array( 'Penguin Books' ), 'publisher' );
  }

  if ( ! get_page_by_title( 'Sample Book 2', OBJECT, 'book' ) ) {
      // Sample Book 2
      $book2 = wp_insert_post( array(
          'post_title'   => 'Sample Book 2',
          'post_type'    => 'book',
          'post_status'  => 'publish',
          'post_author'  => 1,
      ) );

      // Set ACF fields for Sample Book 2
      update_field( 'author', 'Jane Smith', $book2 );
      update_field( 'price', 15.50, $book2 );
      update_field( 'release_date', '2022-11-05', $book2 );

      // Assign taxonomies (Genre and Publisher)
      wp_set_object_terms( $book2, array( 'Non-Fiction' ), 'genre' );
      wp_set_object_terms( $book2, array( 'HarperCollins' ), 'publisher' );
  }

  if ( ! get_page_by_title( 'Sample Book 3', OBJECT, 'book' ) ) {
      // Sample Book 3
      $book3 = wp_insert_post( array(
          'post_title'   => 'Sample Book 3',
          'post_type'    => 'book',
          'post_status'  => 'publish',
          'post_author'  => 1,
      ) );

      // Set ACF fields for Sample Book 3
      update_field( 'author', 'Emily Johnson', $book3 );
      update_field( 'price', 22.99, $book3 );
      update_field( 'release_date', '2024-01-15', $book3 );

      // Assign taxonomies (Genre and Publisher)
      wp_set_object_terms( $book3, array( 'Sci-Fi' ), 'genre' );
      wp_set_object_terms( $book3, array( 'Orbit' ), 'publisher' );
  }

  if ( ! get_page_by_title( 'Sample Book 4', OBJECT, 'book' ) ) {
      // Sample Book 4
      $book4 = wp_insert_post( array(
          'post_title'   => 'Sample Book 4',
          'post_type'    => 'book',
          'post_status'  => 'publish',
          'post_author'  => 1,
      ) );

      // Set ACF fields for Sample Book 4
      update_field( 'author', 'Mark Taylor', $book4 );
      update_field( 'price', 17.99, $book4 );
      update_field( 'release_date', '2023-08-20', $book4 );

      // Assign taxonomies (Genre and Publisher)
      wp_set_object_terms( $book4, array( 'Fantasy' ), 'genre' );
      wp_set_object_terms( $book4, array( 'Macmillan' ), 'publisher' );
  }

  if ( ! get_page_by_title( 'Sample Book 5', OBJECT, 'book' ) ) {
      // Sample Book 5
      $book5 = wp_insert_post( array(
          'post_title'   => 'Sample Book 5',
          'post_type'    => 'book',
          'post_status'  => 'publish',
          'post_author'  => 1,
      ) );

      // Set ACF fields for Sample Book 5
      update_field( 'author', 'David Brown', $book5 );
      update_field( 'price', 25.00, $book5 );
      update_field( 'release_date', '2021-03-30', $book5 );

      // Assign taxonomies (Genre and Publisher)
      wp_set_object_terms( $book5, array( 'Historical' ), 'genre' );
      wp_set_object_terms( $book5, array( 'Random House' ), 'publisher' );
  }
}
add_action( 'init', 'add_sample_books' );

// Filters

function load_books_by_filter() {
  global $wpdb; // Use direct DB queries if needed for debugging

  // Get filters
  $search    = isset($_POST['search']) ? sanitize_text_field($_POST['search']) : '';
  $genre     = isset($_POST['genre']) ? sanitize_text_field($_POST['genre']) : '';
  $publisher = isset($_POST['publisher']) ? sanitize_text_field($_POST['publisher']) : '';

  // Start WP_Query args
  $args = array(
      'post_type'      => 'book',
      'posts_per_page' => -1,
      'orderby'        => 'date',
      'order'          => 'DESC',
      'meta_query'     => array(),
      'tax_query'      => array(),
  );

  // If searching by title/content
  if (!empty($search)) {
      $args['s'] = $search;
  }

  // If searching by ACF Author field
  if (!empty($search)) {
      $args['meta_query'][] = array(
          'key'     => 'author', // ACF field for author
          'value'   => $search,
          'compare' => 'LIKE',
      );
  }

  // If searching by Genre name
  if (!empty($search)) {
      $genre_terms = get_terms(array(
          'taxonomy'   => 'genre',
          'hide_empty' => false,
          'name__like' => $search, // Tries to find matching genres
      ));

      if (!empty($genre_terms) && !is_wp_error($genre_terms)) {
          $genre_ids = wp_list_pluck($genre_terms, 'term_id');

          $args['tax_query'][] = array(
              'taxonomy' => 'genre',
              'field'    => 'term_id',
              'terms'    => $genre_ids,
              'operator' => 'IN',
          );
      }
  }

  // Filter by selected Genre (dropdown)
  if (!empty($genre)) {
      $args['tax_query'][] = array(
          'taxonomy' => 'genre',
          'field'    => 'term_id',
          'terms'    => $genre,
          'operator' => 'IN',
      );
  }

  // Filter by selected Publisher (dropdown)
  if (!empty($publisher)) {
      $args['tax_query'][] = array(
          'taxonomy' => 'publisher',
          'field'    => 'term_id',
          'terms'    => $publisher,
          'operator' => 'IN',
      );
  }

  if (!empty($args['tax_query'])) {
      $args['tax_query']['relation'] = 'OR';
  }

  $book_query = new WP_Query($args);

  if ($book_query->have_posts()) : ?>
      <ul id="book-list" class="list-unstyled">
      <?php while ($book_query->have_posts()) : $book_query->the_post(); ?>
          <li class="book-item mb-4">
              <div class="card shadow-sm">
                  <div class="card-body">
                      <h3 class="card-title"><?php the_title(); ?></h3>
                      <p class="card-text"><?php the_content(); ?></p>
                      <p><strong>Genre:</strong> <?php echo get_the_term_list(get_the_ID(), 'genre', '', ', '); ?></p>
                      <p><strong>Publisher:</strong> <?php echo get_the_term_list(get_the_ID(), 'publisher', '', ', '); ?></p>
                      <p><strong>Release Date:</strong> <?php the_date(); ?></p>
                  </div>
              </div>
          </li>
      <?php endwhile; ?>
      </ul>
  <?php else :
      echo '<p>No books found.</p>';
  endif;

  wp_reset_postdata();
  die();
}

add_action('wp_ajax_filter_books', 'load_books_by_filter');
add_action('wp_ajax_nopriv_filter_books', 'load_books_by_filter');

// Rest API

function register_books_api() {
  register_rest_route('books/v1', '/list', array(
    'methods' => 'GET',
    'callback' => 'get_books_list',
  ));
}
add_action('rest_api_init', 'register_books_api');

function get_books_list(WP_REST_Request $request) {
  $genre = $request->get_param('genre');
  $publisher = $request->get_param('publisher');
  $page = $request->get_param('page') ?: 1;

  // Set up WP_Query for REST API
  $args = array(
    'post_type'      => 'book',
    'posts_per_page' => 10,
    'paged'          => $page,
    'orderby'        => 'date',
    'order'          => 'DESC',
  );

  if ($genre) {
    $args['tax_query'][] = array(
      'taxonomy' => 'genre',
      'field'    => 'id',
      'terms'    => $genre,
      'operator' => 'IN',
    );
  }

  if ($publisher) {
    $args['tax_query'][] = array(
      'taxonomy' => 'publisher',
      'field'    => 'id',
      'terms'    => $publisher,
      'operator' => 'IN',
    );
  }

  $book_query = new WP_Query($args);

  $books = [];
  while ($book_query->have_posts()) {
    $book_query->the_post();
    $books[] = array(
      'title'       => get_the_title(),
      'content'     => get_the_content(),
      'genre'       => get_the_term_list(get_the_ID(), 'genre', '', ', '),
      'publisher'   => get_the_term_list(get_the_ID(), 'publisher', '', ', '),
      'release_date' => get_the_date(),
    );
  }

  return new WP_REST_Response($books, 200);
}

// Custom url structure 

function custom_book_rewrite_rules() {
  add_rewrite_rule(
    '^genre/([^/]+)/?',
    'index.php?taxonomy=genre&term=$matches[1]',
    'top'
  );

  add_rewrite_rule(
    '^publisher/([^/]+)/?',
    'index.php?taxonomy=publisher&term=$matches[1]',
    'top'
  );

  add_rewrite_rule(
    '^book/([^/]+)/?',
    'index.php?post_type=book&p=$matches[1]',
    'top'
  );
}
add_action('init', 'custom_book_rewrite_rules');

function custom_book_flush_rewrite_rules() {
  flush_rewrite_rules();
}

register_activation_hook(__FILE__, 'custom_book_flush_rewrite_rules');
register_deactivation_hook(__FILE__, 'custom_book_flush_rewrite_rules');

// Custom wp search

function custom_book_search_filter($query) {
  if (!is_admin() && $query->is_main_query() && $query->is_search()) {
      $query->set('post_type', 'book'); // Limit search to Books CPT

      // Modify meta query to include ACF 'author' field
      $meta_query = array(
          'relation' => 'OR',
          array(
              'key'     => 'author', // ACF field for book author
              'value'   => $query->query_vars['s'],
              'compare' => 'LIKE',
          ),
      );

      // Modify taxonomy query to include Genre
      $tax_query = array(
          array(
              'taxonomy' => 'genre',
              'field'    => 'name',
              'terms'    => $query->query_vars['s'],
              'operator' => 'LIKE',
          ),
      );

      $query->set('meta_query', $meta_query);
      $query->set('tax_query', $tax_query);
  }
}
add_action('pre_get_posts', 'custom_book_search_filter');
