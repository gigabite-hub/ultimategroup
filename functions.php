<?php

define( 'ULTIMATE', '1.1.0' );
define( 'ULTIMATE_DIR', trailingslashit( get_stylesheet_directory() ) );



add_action( 'wp_enqueue_scripts', 'ultimate_enqueued_scripts', 20 );
function ultimate_enqueued_scripts() {

    // Enqueue CSS Style Sheet
	wp_enqueue_style( 'ultimate-style', get_stylesheet_directory_uri() . '/style.css',
        array( 'hello-elementor-theme-style' ),
        ULTIMATE
    );
}

function display_blog_posts() {
    ob_start();

    // Query the blog posts
    $args = array(
        'post_type'      => 'post',
        'posts_per_page' => -1, // Display all posts
    );
    $query = new WP_Query($args);

    if ($query->have_posts()) {
        echo '<div class="blog-posts">';

        // Loop through the posts
        while ($query->have_posts()) {
            $query->the_post();

            // Get the post data
            $post_id    = get_the_ID();
            $post_title = get_the_title();
            $post_url   = get_permalink();
            $post_image = get_the_post_thumbnail_url($post_id, 'large');
            $post_excerpt = get_the_excerpt();

            // Output the blog post card
            echo '<div class="blog-card">';
            echo '<a href="' . $post_url . '">';
            echo '<img src="' . $post_image . '" alt="' . $post_title . '">';
            echo '<h3>' . $post_title . '</h3>';
            echo '<p>' . $post_excerpt . '</p>';
            echo '</a>';
            echo '</div>';
        }

        echo '</div>';
    }

    wp_reset_postdata();

    return ob_get_clean();
}
add_shortcode('blog_posts', 'display_blog_posts');




