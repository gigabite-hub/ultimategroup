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

    wp_enqueue_script( 'font-awesome', 'https://kit.fontawesome.com/c5e079c351.js', 
		array( 'jquery' ),
		ULTIMATE, 
		false 
	);

    wp_enqueue_style('fullcalendar-css', 'https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css');
    wp_enqueue_script('moment-js', 'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js', array('jquery'), null, true);
    wp_enqueue_script('fullcalendar-js', 'https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js', array('jquery', 'moment-js'), null, true);
    
    wp_enqueue_style('swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css', array(), ULTIMATE);
	wp_enqueue_script('swiperjs', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', array('jquery'), ULTIMATE, true);


    wp_enqueue_script('main-js', get_stylesheet_directory_uri() . '/main.js', array('jquery'), ULTIMATE, true);
    wp_localize_script('main-js', 'ULTIMATE', array(
        'AJAX_URL' => admin_url('admin-ajax.php'),
        'NONCE' => wp_create_nonce('ultimate-nonce'),
    ));
}

function display_blog_posts($atts) {
    ob_start();
    
    // Set up pagination
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    
    // Extract shortcode attributes
    $atts = shortcode_atts(
        array(
            'posts_per_page' => 6, // Default to 6 posts per page
        ),
        $atts
    );

    // Query the blog posts
    $args = array(
        'post_type'      => 'post',
        'posts_per_page' => $atts['posts_per_page'],
        'paged'          => $paged,
    );
    
    $query = new WP_Query($args);

    if ($query->have_posts()) {
        echo '<div class="blog-posts">';

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
        
        // Pagination
        echo '<div class="blog-pagination">';
        echo paginate_links(array(
            'total'   => $query->max_num_pages,
            'current' => $paged,
            'prev_text' => __('« Previous'),
            'next_text' => __('Next »'),
        ));
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
        '<label><input type="radio" name="%s" value="all" checked> All</label>',
        esc_attr($taxonomy)
    );

    foreach ($terms as $term) {
        $output .= sprintf(
            '<label><input type="radio" name="%s" value="%s"> %s</label>',
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
    
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    
    $atts = shortcode_atts(
        array(
            'post_type' => 'accommodation',
            'number'    => 6, // Changed from -1 to 6
        ),
        $atts
    );

    $args = array(
        'post_type'      => esc_attr($atts['post_type']),
        'posts_per_page' => intval($atts['number']),
        'paged'         => $paged,
        'post_status'   => 'publish',
        'orderby'       => 'title',
        'order'         => 'DESC',
    );

    $query = new WP_Query($args);
    
    if (!$query->have_posts()) {
        return 'No posts found.';
    }?>
    <div class="ultimateRetreat"><?php

        while ($query->have_posts()) {
            $query->the_post();

            $image_url = get_the_post_thumbnail_url(get_the_ID(), 'large') ?: 'https://ultimategroup.ae/wp-content/uploads/2024/10/ULTIMATE-RETREAT-Banner-1024x800.jpg';
            $title = get_the_title();
            $location = get_field('acc_location') ?: 'Dubai'; // Replace 'Dubai' with a default value if needed
            $price = get_field('per_night_price') ?: 'AED 1,000'; // Replace with default price if no value exists
            $permalink = get_permalink(); ?>
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
                                <?php if (have_rows('characteristics')): // Check if the repeater field has rows ?>
                                    <?php while (have_rows('characteristics')): the_row(); ?>
                                        <?php 
                                        $char_icon = get_sub_field('char_icon'); // Get the nested image field 
                                        if ($char_icon): ?>
                                            <li>
                                                <img src="<?php echo esc_url($char_icon['url']); ?>" alt="<?php echo esc_attr($char_icon['alt']); ?>">
                                            </li>
                                        <?php endif; ?>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <li>No characteristics found.</li>
                                <?php endif; ?>
                            </ul>
                        </div>
                        <div class="acc-specifications-item">
                            <div class="acc-price">
                                <span>from</span>
                                <p><?php echo esc_html($price); ?></p>
                                <span>/ night</span>
                                <p class="pernight-charge">(<?php echo esc_html($price); ?>  pers./night)</p>
                            </div>
                            <a href="<?php echo esc_url($permalink); ?>"><i class="fa-solid fa-plus"></i> Info</a>
                        </div>

                    </div>
                </div>
            </div>
        <?php
        } ?>
    </div><?php

    // Add pagination
    echo '<div class="accommodation-pagination">';
    echo paginate_links(array(
        'total'   => $query->max_num_pages,
        'current' => $paged,
        'prev_text' => __('« Previous'),
        'next_text' => __('Next »'),
    ));
    echo '</div>';

    wp_reset_postdata();

    return ob_get_clean();
}
add_shortcode( 'fetch_accommodation', 'get_accommodation_posts' );

function switch_category() {
    check_ajax_referer('ultimate-nonce', 'nonce'); // Security check

    $slug = isset($_POST['slug']) ? sanitize_text_field($_POST['slug']) : '';
    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;

    // Base query arguments
    $args = array(
        'post_type'      => 'accommodation',
        'posts_per_page' => 6,
        'paged'         => $page,
        'post_status'    => 'publish',
    );

    // Add taxonomy filter only if the slug is not "all"
    if ($slug !== 'all') {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'accommodation_types',
                'field'    => 'slug',
                'terms'    => $slug,
            ),
        );
    }

    $query = new WP_Query($args);

    // Handle no posts found
    if (!$query->have_posts()) {
        echo 'No posts found.';
        wp_die();
    }
    
    
    while ($query->have_posts()) {
        $query->the_post();

        $image_url = get_the_post_thumbnail_url(get_the_ID(), 'large') ?: 'https://ultimategroup.ae/wp-content/uploads/2024/10/ULTIMATE-RETREAT-Banner-1024x800.jpg';
        $title = get_the_title();
        $location = get_field('acc_location') ?: 'Dubai'; // Replace 'Dubai' with a default value if needed
        $price = get_field('per_night_price') ?: 'AED 1,000'; // Replace with default price if no value exists
        $permalink = get_permalink(); ?>
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
                            <?php if (have_rows('characteristics')): // Check if the repeater field has rows ?>
                                <?php while (have_rows('characteristics')): the_row(); ?>
                                    <?php 
                                    $char_icon = get_sub_field('char_icon'); // Get the nested image field 
                                    if ($char_icon): ?>
                                        <li>
                                            <img src="<?php echo esc_url($char_icon['url']); ?>" alt="<?php echo esc_attr($char_icon['alt']); ?>">
                                        </li>
                                    <?php endif; ?>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <li>No characteristics found.</li>
                            <?php endif; ?>
                        </ul>
                    </div>
                    <div class="acc-specifications-item">
                        <div class="acc-price">
                            <span>from</span>
                            <p><?php echo esc_html($price); ?></p>
                            <span>/ night</span>
                            <p class="pernight-charge">(<?php echo esc_html($price); ?>  pers./night)</p>
                        </div>
                        <a href="<?php echo esc_url($permalink); ?>"><i class="fa-solid fa-plus"></i> Info</a>
                    </div>

                </div>
            </div>
        </div><?php
    } 

    // Add pagination info to response
    echo '<div class="ajax-pagination-data" data-max-pages="' . $query->max_num_pages . '" data-current-page="' . $page . '"></div>';

    wp_reset_postdata();
    wp_die();
}

add_action('wp_ajax_switch_category', 'switch_category');
add_action('wp_ajax_nopriv_switch_category', 'switch_category');


function all_property_posts($atts) {
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $atts = shortcode_atts(
        array(
            'posts'   => 3,        // Default number of posts
            'location' => '',      // Default location (empty means no filter)
        ),
        $atts,
        'all_property'
    );

    // Convert to integer for safety
    $posts_per_page = intval($atts['posts']);
    $location_slug = sanitize_text_field($atts['location']); // Sanitize location input

    ob_start();

    $args = array(
        'post_type'      => 'property',
        'posts_per_page' => $posts_per_page,
        'paged'         => $paged,
        'post_status'    => 'publish',
    );

    // Add taxonomy filter if location is provided
    if (!empty($location_slug)) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'location',
                'field'    => 'slug',
                'terms'    => $location_slug,
            ),
        );
    }

    // Get the display name of the current category
    $current_category_name = '';
    if (!empty($location_slug)) {
        $current_category = get_term_by('slug', $location_slug, 'location');
        $current_category_name = $current_category ? $current_category->name : 'No Category';
    }

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        echo '<div class="ultimateProperty">';

        while ($query->have_posts()) {
            $query->the_post();

            // Get post details
            $title = get_the_title();
            $link = get_permalink();
            $excerpt = wp_trim_words(get_the_excerpt(), 20, '...'); // Trim excerpt to 20 words
            $image_url = get_the_post_thumbnail_url(get_the_ID(), 'full') ?: 'https://ultimategroup.ae/wp-content/uploads/2024/11/default-image.jpg'; // Fallback image
            $price = get_field('property_single_price');

            // Output post HTML
            ?>
            <div class="ultimateItems">
                <a href="<?php echo esc_url($link); ?>" class="itemsWrappers">
                    <div>
                        <div class="propertyImg">
                            <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($title); ?>">
                        </div>
                        <div class="innerContent">
                            <h2><?php echo esc_html($title); ?></h2>
                            <p><?php echo esc_html($excerpt); ?></p>
                            <?php if (!empty($location_slug) && !empty($current_category_name)): ?>
                                <p class="propertyCategory"><i class="fa-solid fa-location-dot"></i> <?php echo esc_html($current_category_name); ?></p>
                            <?php endif; ?>

                        </div>
                    </div>
                    <div class="propertyContent">
                        <div class="propertyPrice">
                            <p>Starting Price</p>
                            <h4><?php echo esc_html($price ? 'AED ' . $price : 'Price on Request'); ?> <i class="fa-solid fa-circle-info"></i></h4>
                        </div>
                    </div>
                </a>
            </div>
            <?php
        }

        echo '</div>';
    } else {
        echo '<p>No properties found for this location.</p>';
    }

    // Add pagination
    echo '<div class="property-pagination">';
    echo paginate_links(array(
        'total'   => $query->max_num_pages,
        'current' => $paged,
        'prev_text' => __('« Previous'),
        'next_text' => __('Next »'),
    ));
    echo '</div>';

    // Reset post data
    wp_reset_postdata();

    return ob_get_clean();
}
add_shortcode('all_property', 'all_property_posts');


function add_gtag_script() {
    ?>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=AW-16519725274"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'AW-16519725274');
    </script>
    <?php
}
add_action( 'wp_head', 'add_gtag_script' );


function all_available_property($atts) {
    $atts = shortcode_atts(
        array(
            'posts' => 3,
        ),
        $atts,
        'all_property'
    );

    // Convert to integer for safety
    $posts_per_page = intval($atts['posts']);

    ob_start();

    $args = array(
        'post_type'      => 'accommodation',
        'posts_per_page' => $posts_per_page,
        'post_status'    => 'publish',
    );
    echo '<section class="all-properties">';
    $query = new WP_Query($args);
        if ($query->have_posts()) { ?>

            <div class="swiper propertySwiper">
                <div class="swiper-wrapper"><?php
                while ($query->have_posts()) {
                    $query->the_post();
        
                    // Get post details
                    $title = get_the_title();
                    $link = get_permalink();
                    $excerpt = wp_trim_words(get_the_excerpt(), 30, '...'); // Trim excerpt to 20 words
                    $image_url = get_the_post_thumbnail_url(get_the_ID(), 'full') ?: 'https://ultimategroup.ae/wp-content/uploads/2024/11/default-image.jpg'; // Fallback image
                    $price = get_field('property_single_price');
                    // Output post HTML
                    ?>
                    <div class="ultimateItems swiper-slide">
                        <div class="itemsWrappers">
                            <div class="propertyImg">
                                <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($title); ?>">
                            </div>
                            <div class="propertyContent">
                                <h2><?php echo esc_html($title); ?></h2>
                                <p><?php echo esc_html($excerpt); ?></p>
                                <div class="propertyBTN">
                                    <a href="<?php echo esc_url($link); ?>">Full Details</a>
                                </div>
                            </div>

                        </div>
                    </div>
                    <?php
                } ?>

                </div>
                
            </div>
        
        <?php
        }
    echo '</section>';
    wp_reset_postdata();

    return ob_get_clean();
}
add_shortcode('all_available_property', 'all_available_property');


function add_talktwo_script_to_body() {
    ?>
    <!--Start of Tawk.to Script-->
    <script type="text/javascript">
        var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
        (function(){
            var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
            s1.async=true;
            s1.src='https://embed.tawk.to/66435fcd07f59932ab3f67e3/1htrisfl2';
            s1.charset='UTF-8';
            s1.setAttribute('crossorigin','*');
            s0.parentNode.insertBefore(s1,s0);
        })();
    </script>
    <!--End of Tawk.to Script-->
    <?php
}
add_action('wp_body_open', 'add_talktwo_script_to_body');


function get_properties_pages() {
    ob_start();

    $args = array(
        'post_type'      => 'page', 
        'posts_per_page' => -1,     // Retrieve all pages
        'meta_key'       => 'display_page_for_properties_category', // ACF field key
        'meta_value'     => '1',    // ACF field value
        'meta_compare'   => '=',    // Comparison operator
    );
    
    $query = new WP_Query($args);
    
    if ($query->have_posts()) { ?>
        <div class="categoryPages"><?php

            while ($query->have_posts()) {
                $query->the_post();
                // Get the page title
                $page_title = get_the_title();
                // Get the page link (URL)
                $page_link = get_the_permalink(); ?>
                <h2>
                    <a href="<?php echo esc_url($page_link);?>"><?php echo esc_html($page_title); ?></a>
                </h2>
                <!-- echo '<h2><a href="' . esc_url($page_link) . '">' . esc_html($page_title) . '</a></h2>'; -->
            <?php
            } ?>

        </div><?php
    } else {
        echo 'No pages found with the specified location at this moment.';
    }
    
    // Reset post data to avoid conflicts with other queries
    wp_reset_postdata();

    return ob_get_clean();
}
add_shortcode( 'fetch_category_property_pages', 'get_properties_pages' );


// Add a Shortcode column to Elementor Library (Saved Templates)
add_filter('manage_elementor_library_posts_columns', function($columns) {
    $columns['shortcode'] = __('Shortcode', 'elementor');
    return $columns;
});

add_action('manage_elementor_library_posts_custom_column', function($column, $post_id) {
    if ($column === 'shortcode') {
        echo '<code>[elementor-template id="' . esc_attr($post_id) . '"]</code>';
    }
}, 10, 2);
