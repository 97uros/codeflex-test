<?php
/**
 * Template Name: Books Page
 * Template for displaying a list of books with filters.
 */

get_header();
?>

<?php
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

  <h1 class="text-center mb-2">Browse Books</h1>

  <div class="mb-5">
    <?php echo do_shortcode('[simple_book_form]'); ?>
  </div>

  <hr>

  <h3 class="text-start mb-4">Filters</h3>
  
  <form id="book-search-filter-form" class="mb-4">
    <div class="row justify-content-center">
      
      <!-- Search form -->
      <div class="col-md-4 mb-3">
        <input type="text" id="book-search-input" class="form-control" placeholder="Search by title, author, or genre...">
      </div>

      <!-- Genre filter -->
      <div class="col-md-3 mb-3">
        <select name="genre" id="genre-filter" class="form-select">
          <option value="">Select Genre</option>
          <?php foreach ($genres as $genre) : ?>
            <option value="<?php echo $genre->term_id; ?>"><?php echo $genre->name; ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Publisher filter -->
      <div class="col-md-3 mb-3">
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
        echo '<p>No books found</p>';
    endif;

    // Reset post data
    wp_reset_postdata();
    ?>
  </div>

</div>

<?php get_footer(); ?>
