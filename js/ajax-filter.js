jQuery(document).ready(function($){
	// Trigger the AJAX request when a filter changes
	$('#book-filter-form select').on('change', function() {
			var genre = $('#genre-filter').val();
			var publisher = $('#publisher-filter').val();

			// Send AJAX request
			$.ajax({
					url: ajax_filter.ajax_url,
					method: 'POST',
					data: {
							action: 'filter_books',
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
	});
});
