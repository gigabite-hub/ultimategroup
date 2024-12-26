<?php
get_header();
// echo do_shortcode("[hfe_template id='457']");
?>

<div class="container">
    <div class="row">
        <div class="col-content">
            <?php
            while (have_posts()) : the_post();
                // Display the featured image ?>
                <div class="postHead"><?php
                    
                    the_title('<h1>', '</h1>');
                    // Display the post date and other meta information
                    echo '<p>Posted on: ' . get_the_date() . '</p>';
                    echo '<p>Author: ' . get_the_author() . '</p>'; ?>
                </div><?php

                if (has_post_thumbnail()) {
                    the_post_thumbnail();
                }

                // Display the post title

                // Display the content of the post
                the_content();

            endwhile;
            ?>
        </div>

    </div>
</div>

<?php
get_footer();
?>
