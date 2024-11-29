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

    wp_enqueue_script( 'font-awesome', 'https://kit.fontawesome.com/2e71d1c020.js', 
		array( 'jquery' ),
		ULTIMATE, 
		false 
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

function get_accommodation_taxonomy_radios_shortcode($atts) {
    ob_start();
    // Extract shortcode attributes (optional for future customization)
    $atts = shortcode_atts(
        array(
            'taxonomy' => 'accommodation_types', // Default taxonomy
        ),
        $atts
    );

    // Get taxonomy terms
    $taxonomy = esc_attr($atts['taxonomy']);
    $terms = get_terms(array(
        'taxonomy' => $taxonomy,
        'hide_empty' => false, // Show terms even if not associated with any posts
    ));

    if (is_wp_error($terms) || empty($terms)) {
        return 'No terms found for the specified taxonomy.';
    }

    // Generate output with radio buttons
    // Generate output with heading, subheading, and radio buttons
    $output = '<div class="ultimate-filter">';
    $output .= '<h2>Filters</h2>'; // Heading
    $output .= '<h3>Type of Accommodation</h3>'; // Subheading
    $output .= '<form>';
    $output .= sprintf(
        '<label><input type="radio" name="%s" value="all"> All</label><br>',
        esc_attr($taxonomy)
    );

    foreach ($terms as $term) {
        $output .= sprintf(
            '<label><input type="radio" name="%s" value="%s"> %s</label><br>',
            esc_attr($taxonomy),
            esc_attr($term->slug),
            esc_html($term->name)
        );
    }

    $output .= '</form>';
    $output .= '</div>';

    return $output;
}

// Register the shortcode
add_shortcode('accommodation_taxonomy_radios', 'get_accommodation_taxonomy_radios_shortcode');





function get_accommodation_posts( $atts ) {
    ob_start();

    $atts = shortcode_atts(
        array(
            'post_type' => 'accommodation',
            'number'    => -1,
        ),
        $atts
    );

    $args = array(
        'post_type'      => esc_attr($atts['post_type']),
        'posts_per_page' => intval($atts['number']),
        'post_status'    => 'publish', // Only fetch published posts
        'orderby'        => 'title',
        'order'          => 'DESC',
    );

    // Query posts
    $query = new WP_Query($args);

    if (!$query->have_posts()) {
        return 'No posts found.';
    }
    while ($query->have_posts()) {
        $query->the_post();

        $image_url = get_the_post_thumbnail_url(get_the_ID(), 'large') ?: 'https://ultimategroup.ae/wp-content/uploads/2024/10/ULTIMATE-RETREAT-Banner-1024x800.jpg';
        $title = get_the_title();
        $location = get_field('acc_location') ?: 'Unknown Location'; // Replace 'Unknown Location' with a default value if needed
        $price = get_field('acc_price') ?: 'AED 1,000'; // Replace with default price if no value exists
        $permalink = get_permalink();
    ?>
        <div class="ultimate-acc-container">
            <div class="acc-items">
                <div class="acc-image">
                    <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($title); ?>">
                </div>
            </div>
            <div class="acc-items">
                <div class="acc-content">
                <h3><?php echo esc_html($title); ?></h3>
                <p class="location"><i class="fa-solid fa-location-dot"></i> <?php echo esc_html($location); ?></p>
                <p><?php echo wp_trim_words(get_the_excerpt(), 20, '...'); ?></p>
                </div>
                <div class="acc-specifications">
                    <div class="acc-specifications-item">
                        <ul>
                            <li><i class="fa-solid fa-water-ladder"></i></li>
                            <li><i class="fa-solid fa-water-ladder"></i></li>
                            <li><i class="fa-solid fa-water-ladder"></i></li>
                            <li><i class="fa-solid fa-water-ladder"></i></li>
                        </ul>
                    </div>
                    <div class="acc-specifications-item">
                        <div class="acc-price">
                            <span>from</span>
                            <p>AED 1,000</p>
                            <span>/ night</span>
                            <p class="pernight-charge">(AED 1,000  pers./night)</p>
                        </div>
                        <a href="<?php echo esc_url($permalink); ?>"><i class="fa-solid fa-plus"></i> Info</a>
                    </div>

                </div>
            </div>
        </div>
    <?php
    }
    wp_reset_postdata();


    return ob_get_clean();
}
add_shortcode( 'fetch_accommodation', 'get_accommodation_posts' );