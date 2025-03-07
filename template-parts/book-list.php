<form id="filter-books-form">
  <label for="genre">Genre:</label>
  <select name="genre" id="genre">
    <option value="">Select Genre</option>
    <?php
    $genres = get_terms( array( 'taxonomy' => 'genre', 'orderby' => 'name' ) );
    foreach ( $genres as $genre ) {
        echo '<option value="' . $genre->term_id . '">' . $genre->name . '</option>';
    }
    ?>
  </select>

  <label for="publisher">Publisher:</label>
  <select name="publisher" id="publisher">
    <option value="">Select Publisher</option>
    <?php
    $publishers = get_terms( array( 'taxonomy' => 'publisher', 'orderby' => 'name' ) );
    foreach ( $publishers as $publisher ) {
      echo '<option value="' . $publisher->term_id . '">' . $publisher->name . '</option>';
    }
    ?>
  </select>

  <button type="submit">Filter</button>
</form>

<?php
$args = array(
  'post_type'      => 'book',
  'posts_per_page' => 10, // Adjust the number of books displayed per page
  'orderby'        => 'meta_value', // Sorting by the custom field "release_date"
  'order'          => 'ASC', // or DESC depending on your preference
  'meta_key'       => 'release_date', // Field used for sorting
);

$query = new WP_Query( $args );

if ( $query->have_posts() ) : ?>
    <ul>
        <?php while ( $query->have_posts() ) : $query->the_post(); ?>
            <li>
                <h2><?php the_title(); ?></h2>
                <p>Author: <?php echo get_post_meta( get_the_ID(), 'author', true ); ?></p>
                <p>Price: $<?php echo get_post_meta( get_the_ID(), 'price', true ); ?></p>
                <p>Release Date: <?php echo get_post_meta( get_the_ID(), 'release_date', true ); ?></p>
            </li>
        <?php endwhile; ?>
    </ul>
<?php else : ?>
    <p>No books found.</p>
<?php endif;

wp_reset_postdata();
?>
