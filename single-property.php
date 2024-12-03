<?php
get_header();
?>

<div class="propertywrapper">

    <div class="bannerImage" style="background-image: url('<?php echo get_the_post_thumbnail_url(get_the_ID(), 'full'); ?>');">
        <div class="pro-content">
            <h1><?php the_title(); ?></h1>
        </div>
    </div>

    <div class="pro-container">
        <!-- Section Direct Sales -->
        <section class="direct-section">
            <div class="head-section">
                <div class="headitem">
                    <h2><?php the_title(); ?></h2>
                    <?php
                        $developer_terms = wp_get_post_terms(get_the_ID(), 'developer');
                        $developer_name = !empty($developer_terms) ? $developer_terms[0]->name : '';
                    ?>
                    <div class="developer-name">
                        <h3>By <span><?php echo esc_html($developer_name); ?></span></h3> |
                        <h4><?php the_field( 'property_location' ); ?></h4>
                    </div>
                    <div class="pro-status">
                        <h3>Status</h3>
                        <h4>New Launch</h4>
                    </div>

                </div>
                <div class="headitem">
                    <div class="price">
                        <p>Starting From</p>
                        <h3>AED <?php the_field( 'property_single_price' ); ?></h3>
                    </div>
                </div>
            </div>

            <div class="pro-directbody">
                <ul>
                    <li>
                        <p><i class="fa-solid fa-house"></i> Property Type:</p>
                        <p><b><?php the_field( 'property_type' ); ?></b></p>
                    </li>

                    <li>
                        <p><i class="fa-solid fa-bed"></i> Unit type:</p>
                        <p><b><?php the_field( 'unit_type' ); ?></b></p>
                    </li>

                    <li>
                        <p><i class="fa-solid fa-chart-area"></i> Size:</p>
                        <p><b><?php the_field( 'size' ); ?></b></p>
                    </li>

                    <li>
                        <p><i class="fa-solid fa-percent"></i> Down Payment:</p>
                        <p><b><?php the_field( 'down_payment' ); ?></b></p>
                    </li>

                    <li>
                        <p><i class="fa-regular fa-money-bill-1"></i> Payment Plan:</p>
                        <p><b><?php the_field( 'payment_plan' ); ?></b></p>
                    </li>

                    <li>
                        <p><i class="fa-solid fa-chart-area"></i> Handover:</p>
                        <p><b><?php the_field( 'handover' ); ?></b></p>
                    </li>
                </ul>
            </div>
            

            <div class="direct-tagline">
                <h3>Direct Sales & 0% Commission</h3>
            </div>

        </section>

        <section class="overview">
            <?php the_content(); ?>
        </section>

        <section class="feature-amenities">
            <h2>Feature & Amenities</h2>
            <?php if( have_rows('feature_amenities_items') ): ?>
                <div class="amenities-flex"><?php 
                    while( have_rows('feature_amenities_items') ): the_row(); 

                        $amenity_image = get_sub_field('feature_amenities_icon'); // Assuming you have an image field
                        $amenity_title = get_sub_field('feature_amenities_heading'); // Assuming you have a title field
                        $amenity_description = get_sub_field('feature_amenities_subheading'); ?>

                        <div class="amenities-items">
                            <img src="<?php echo esc_url($amenity_image['url']); ?>" alt="<?php echo esc_attr($amenity_image['alt']); ?>">
                            <div class="amenites-content">
                                <h4><?php echo esc_html($amenity_title); ?></h4>
                                <p><?php echo esc_html($amenity_description); ?></p>
                            </div>
                        </div>
                    <?php endwhile; ?>

                </div>
            <?php endif; ?>
        </section>

        <section class="feature-amenities payment-plan">
            <h2>Payment Plan</h2>
            <?php if( have_rows('payment_plan_items') ): ?>
                <div class="amenities-flex"><?php
                    while( have_rows('payment_plan_items') ): the_row(); 

                        $payment_plan_icon = get_sub_field('payment_plan_icon'); // Assuming you have an image field
                        $payment_plan_percentage_value = get_sub_field('payment_plan_percentage_value'); // Assuming you have a title field
                        $payment_plan_heading = get_sub_field('payment_plan_heading');
                        $payment_plan_dated = get_sub_field('payment_plan_dated'); ?>

                        <div class="amenities-items">
                            <img src="<?php echo esc_url($payment_plan_icon['url']); ?>" alt="<?php echo esc_attr($payment_plan_icon['alt']); ?>">
                            <div class="amenites-content">
                                <h4><?php echo esc_html($payment_plan_percentage_value); ?></h4>
                                <h3><?php echo esc_html($payment_plan_heading); ?></h3>
                                <p><?php echo esc_html($payment_plan_dated); ?></p>
                            </div>
                        </div>
                    <?php endwhile; ?>

                </div>
            <?php endif; ?>
        </section>
    <section class="feature-amenities gallery">
       
        <h2>Gallery</h2>
        <?php 
        $gallery_images = get_field('images'); // Assuming 'images' is the gallery field name
        if( $gallery_images ): ?>
            <div class="gallery-grid madarchod-coding">
                <?php foreach( $gallery_images as $image ): ?>
                    <div class="gallery-item">
                        <a href="<?php echo esc_url($image['url']); ?>" target="_blank">
                            <img src="<?php echo esc_url($image['sizes']['medium']); ?>" alt="<?php echo esc_attr($image['alt']); ?>">
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
    </section>
        <section class="feature-amenities propertyForm">
            <h2>Get in touch!</h2>
            <?php echo do_shortcode('[ninja_form id=1]');  ?>
        </section>

		

    </div>

</div>

<?php
get_footer();
?>
