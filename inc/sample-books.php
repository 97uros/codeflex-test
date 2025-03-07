<?php 

// Add Sample Books using a Loop

function add_sample_books() {
	$books = [
		['title' => '1984', 'author' => 'George Orwell', 'price' => 9.99, 'release_date' => '1949-06-08', 'genre' => 'Dystopian', 'publisher' => 'Secker & Warburg'],
		['title' => 'To Kill a Mockingbird', 'author' => 'Harper Lee', 'price' => 14.99, 'release_date' => '1960-07-11', 'genre' => 'Fiction', 'publisher' => 'J.B. Lippincott & Co.'],
		['title' => 'The Great Gatsby', 'author' => 'F. Scott Fitzgerald', 'price' => 10.99, 'release_date' => '1925-04-10', 'genre' => 'Fiction', 'publisher' => 'Charles Scribner\'s Sons'],
		['title' => 'Moby-Dick', 'author' => 'Herman Melville', 'price' => 12.99, 'release_date' => '1851-10-18', 'genre' => 'Adventure', 'publisher' => 'Harper & Brothers'],
	];

  foreach ($books as $book) {
		if (!get_page_by_title($book['title'], OBJECT, 'book')) {
			$post_id = wp_insert_post([
				'post_title'  => $book['title'],
				'post_type'   => 'book',
				'post_status' => 'publish',
				'post_author' => 1,
			]);
			update_field('author', $book['author'], $post_id);
			update_field('price', $book['price'], $post_id);
			update_field('release_date', $book['release_date'], $post_id);
			wp_set_object_terms($post_id, [$book['genre']], 'genre');
			wp_set_object_terms($post_id, [$book['publisher']], 'publisher');
		}
  }
}
add_action('init', 'add_sample_books');
?>