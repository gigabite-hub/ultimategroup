<?php
get_header();
echo do_shortcode("[hfe_template id='457']");
?>

<div class="container">
    <div class="row">
        <div class="col-content">
            <?php
            while (have_posts()) : the_post();
                // Display the featured image
                if (has_post_thumbnail()) {
                    the_post_thumbnail();
                }

                // Display the post title
                the_title('<h1>', '</h1>');

                // Display the post date and other meta information
                echo '<p>Posted on: ' . get_the_date() . '</p>';
                echo '<p>Author: ' . get_the_author() . '</p>';

                // Display the content of the post
                the_content();

            endwhile;
            ?>
        </div>

        <div class="col-sidebar">
            <aside class="sidebar">
                <div class="widget">
                    <h3>Recent Posts</h3>
                    <ul>
                        <?php
                        $recent_posts = wp_get_recent_posts(array(
                            'numberposts' => 5, // Change the number of recent posts to display
                            'post_status' => 'publish',
                        ));
                        foreach ($recent_posts as $post) {
                            echo '<li><a href="' . get_permalink($post['ID']) . '">' . $post['post_title'] . '</a></li>';
                        }
                        ?>
                    </ul>
                </div>

                <div class="widget">
                    <h3>Categories</h3>
                    <ul>
                        <?php
                        $categories = get_the_category();
                        foreach ($categories as $category) {
                            echo '<li><a href="' . get_category_link($category->term_id) . '">' . $category->name . '</a></li>';
                        }
                        ?>
                    </ul>
                </div>
            </aside>
        </div>
    </div>
</div>

<?php
get_footer();
?>
