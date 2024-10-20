<?php
get_header();
echo do_shortcode("[hfe_template id='457']");
?>

<div class="container">
    <div class="row">
        <div class="col-content">
            <?php if (have_posts()) : ?>
                <h1><?php single_cat_title(); ?></h1>

                <?php while (have_posts()) : the_post(); ?>
                    <article <?php post_class(); ?>>
                        <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                        
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="featured-image">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail(); ?>
                                </a>
                            </div>
                        <?php endif; ?>

                        <p><?php the_excerpt(); ?></p>
                    </article>
                <?php endwhile; ?>

                <?php the_posts_pagination(); ?>

            <?php else : ?>
                <h1>No posts found</h1>
            <?php endif; ?>
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
                        $categories = get_categories();
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
