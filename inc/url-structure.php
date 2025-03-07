<?php function custom_book_rewrite_rules() {
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