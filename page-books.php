<?php
/**
 * Template Name: Books Page
 * Template for displaying a list of books with filters.
 */
get_header();
?>

<?php
// Get all terms for Genre and Publisher
$genres = get_terms(array(
    'taxonomy' => 'genre',
    'orderby'  => 'name',
    'hide_empty' => false,
));

$publishers = get_terms(array(
    'taxonomy' => 'publisher',
    'orderby'  => 'name',
    'hide_empty' => false,
));
?>
<div class="container mt-5">

  <h1 class="text-center mb-4">Books</h1>
  
 <!-- Search form -->

  <form id="book-search-form" class="mb-4">
    <div class="row justify-content-center">
      <div class="col-md-10">
        <input type="text" id="book-search-input" class="form-control" placeholder="Search by title, author, or genre...">
      </div>
    </div>
  </form>

  <!-- Filtering Form -->
  <form id="book-filter-form" class="mb-4">
    <div class="row justify-content-center">
      <div class="col-md-5 mb-3">
        <label for="genre-filter" class="form-label">Genre</label>
        <select name="genre" id="genre-filter" class="form-select">
          <option value="">Select Genre</option>
          <?php foreach ($genres as $genre) : ?>
            <option value="<?php echo $genre->term_id; ?>"><?php echo $genre->name; ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="col-md-5 mb-3">
        <label for="publisher-filter" class="form-label">Publisher</label>
        <select name="publisher" id="publisher-filter" class="form-select">
          <option value="">Select Publisher</option>
          <?php foreach ($publishers as $publisher) : ?>
            <option value="<?php echo $publisher->term_id; ?>"><?php echo $publisher->name; ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>
  </form>

  <!-- Book List -->
  <div id="book-list-container">
    <?php
    // Default WP_Query to display all books by release date
    $args = array(
        'post_type' => 'book',
        'posts_per_page' => -1,
        'orderby' => 'date',
        'order' => 'DESC',
    );
    $query = new WP_Query($args);

    if ($query->have_posts()) : ?>
        <ul class="list-unstyled">
        <?php while ($query->have_posts()) : $query->the_post(); ?>
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
        echo 'No books found';
    endif;

    // Reset post data
    wp_reset_postdata();
    ?>
  </div>

</div>

<?php get_footer(); ?>
