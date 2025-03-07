jQuery(document).ready(function($) {
	function filterBooks() {
			var searchQuery = $('#book-search-input').val();
			var genre = $('#genre-filter').val();
			var publisher = $('#publisher-filter').val();

			// Send AJAX request
			$.ajax({
					url: ajax_filter.ajax_url,
					method: 'POST',
					data: {
							action: 'filter_books',
							search: searchQuery,
							genre: genre,
							publisher: publisher
					},
					beforeSend: function() {
							$('#book-list-container').html('<p>Loading...</p>'); // Show loading text
					},
					success: function(response) {
							$('#book-list-container').html(response); // Update book list
					}
			});
	}

	// Bind event listeners once
	$('#book-search-input').on('keyup', function() {
			filterBooks();
	});

	$('#genre-filter, #publisher-filter').on('change', function() {
			filterBooks();
	});
});
