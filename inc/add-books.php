<?php 
function simple_book_submission_form() {
  if (!current_user_can('edit_posts')) {
      return '<p>You do not have permission to add books.</p>';
  }
  ob_start(); ?>
  
  <div>
    <h3>Add a New Book</h3>
    <form method="post" class="needs-validation" novalidate>
      <?php wp_nonce_field('simple_book_nonce', 'book_nonce'); ?>

      <div class="row mb-3">
        <div class="col-md-6">
          <input type="text" class="form-control" id="book_title" name="book_title" placeholder="Book Title" required>
          <div class="invalid-feedback">
            Please provide a book title.
          </div>
        </div>
        <div class="col-md-6">
          <input type="text" class="form-control" id="book_author" name="book_author" placeholder="Author" required>
          <div class="invalid-feedback">
            Please provide the author's name.
          </div>
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-md-6">
          <input type="number" class="form-control" id="book_price" name="book_price" step="0.01" placeholder="Price" required>
          <div class="invalid-feedback">
            Please provide a price.
          </div>
        </div>
        <div class="col-md-6">
          <input type="date" class="form-control" id="book_release_date" name="book_release_date" required>
          <div class="invalid-feedback">
            Please provide the release date.
          </div>
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-md-6">
          <select class="form-select" id="book_genre" name="book_genre" required>
            <option value="">Select Genre</option>
            <?php 
            $genres = get_terms(['taxonomy' => 'genre', 'hide_empty' => false]);
            foreach ($genres as $genre) {
              echo '<option value="' . $genre->term_id . '">' . $genre->name . '</option>';
            }
            ?>
          </select>
          <div class="invalid-feedback">
            Please select a genre.
          </div>
        </div>
        <div class="col-md-6">
          <select class="form-select" id="book_publisher" name="book_publisher" required>
            <option value="">Select Publisher</option>
            <?php 
            $publishers = get_terms(['taxonomy' => 'publisher', 'hide_empty' => false]);
            foreach ($publishers as $publisher) {
              echo '<option value="' . $publisher->term_id . '">' . $publisher->name . '</option>';
            }
            ?>
          </select>
          <div class="invalid-feedback">
            Please select a publisher.
          </div>
        </div>
      </div>

      <button type="submit" name="submit_book" class="btn btn-primary">Add Book</button>
    </form>
  </div>

  <script>
    // Bootstrap 5 form validation
    (function () {
      'use strict'
      var forms = document.querySelectorAll('.needs-validation')
      Array.prototype.slice.call(forms)
        .forEach(function (form) {
          form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
              event.preventDefault()
              event.stopPropagation()
            }
            form.classList.add('was-validated')
          }, false)
        })
    })()
  </script>

  <?php return ob_get_clean();
}

add_shortcode('simple_book_form', 'simple_book_submission_form');
?>
