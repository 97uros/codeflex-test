<?php function load_books_by_filter() {
  global $wpdb;

  // Get filters
  $search = isset($_POST['search']) ? sanitize_text_field($_POST['search']) : '';
  $genre = isset($_POST['genre']) ? sanitize_text_field($_POST['genre']) : '';
  $publisher = isset($_POST['publisher']) ? sanitize_text_field($_POST['publisher']) : '';

  // Start WP_Query args
  $args = array(
		'post_type' => 'book',
		'posts_per_page' => -1,
		'orderby' => 'date',
		'order' => 'DESC',
		'meta_query' => array(),
		'tax_query' => array(),
  );

  // If searching by title/content
  if (!empty($search)) {
		$args['s'] = $search;
  }

  // If searching by ACF Author field
  if (!empty($search)) {
		$args['meta_query'][] = array(
			'key' => 'author',
			'value' => $search,
			'compare' => 'LIKE',
		);
  }

  // If searching by Genre name
  if (!empty($search)) {
		$genre_terms = get_terms(array(
			'taxonomy' => 'genre',
			'hide_empty' => false,
			'name__like' => $search,
		));

		if (!empty($genre_terms) && !is_wp_error($genre_terms)) {
			$genre_ids = wp_list_pluck($genre_terms, 'term_id');

			$args['tax_query'][] = array(
				'taxonomy' => 'genre',
				'field' => 'term_id',
				'terms' => $genre_ids,
				'operator' => 'IN',
			);
		}
  }

  // Filter by selected Genre
  if (!empty($genre)) {
		$args['tax_query'][] = array(
			'taxonomy' => 'genre',
			'field' => 'term_id',
			'terms' => $genre,
			'operator' => 'IN',
		);
  }

  // Filter by selected Publisher (dropdown)
  if (!empty($publisher)) {
		$args['tax_query'][] = array(
			'taxonomy' => 'publisher',
			'field' => 'term_id',
			'terms' => $publisher,
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