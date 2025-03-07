<?php function register_books_api() {
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