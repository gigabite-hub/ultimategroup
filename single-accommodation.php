<?php
get_header();
?>

<div class="ultimate-acc-wrapper">
    <?php
    $gallery = get_field('acc_images', get_the_ID());
    if ($gallery && count($gallery) >= 5): ?>
        <div class="gallery">
            <!-- First Div: Single Image -->
            <div class="single-image">
                <img src="<?php echo esc_url($gallery[0]['url']); ?>" alt="<?php echo esc_attr($gallery[0]['alt']); ?>" />
            </div>
        
            <!-- Second Div: Four Images -->
            <div class="four-images">
                <?php for ($i = 1; $i <= 4; $i++): ?>
                    <div class="image">
                        <img src="<?php echo esc_url($gallery[$i]['url']); ?>" alt="<?php echo esc_attr($gallery[$i]['alt']); ?>" />
                    </div>
                <?php endfor; ?>
            </div>
        </div>

        <!-- Lightbox -->
        <div id="lightbox" style="display: none;">
            <div class="close">x</div>
            <div class="prev">&lt;</div>
            <div class="next">&gt;</div>
            <img src="#">
        </div>
        
    <?php endif; ?>
</div>


<div class="acc-characteristics">
    <div class="container">
        <div class="char-wrapper">
            <div class="char-items">
                <ul>
                    <?php if (have_rows('characteristics')): // Check if the repeater field has rows ?>
                        <?php while (have_rows('characteristics')): the_row(); ?>
                        <?php $char_icon = get_sub_field('char_icon'); // Get the nested image field
                            $label_characteristics = get_sub_field('label_characteristics');  
                            if ($char_icon): ?>
                                <li>
                                    <img src="<?php echo esc_url($char_icon['url']); ?>" alt="<?php echo esc_attr($char_icon['alt']); ?>">
                                    <p><?php echo esc_html($label_characteristics); ?></p>
                                </li>
                            <?php endif; ?>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <li>No characteristics found.</li>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="char-items">
            </div>
        </div>
    </div>    
</div>

<div class="container">
    <div class="accBodyContent">
        <div class="leftSide">
            <div class="accHeader"><?php
                $title = get_the_title();
                $location = get_field('acc_location') ?: 'Dubai';
                $content = get_the_content(); ?>

                <h1><?php echo esc_html($title); ?></h1>
                <p><i class="fa-solid fa-location-dot"></i> <?php echo esc_html($location); ?></p>
            </div>
            <div class="accAccommodation">
                <h2>Accommodation</h2>
                <h4>Description</h4>
                <div class="accContent truncated">
                    <?php echo __($content); ?>
                </div>
                <a href="#" class="toggle-btn">More Details</a>
            </div>

            <div class="accAvailability">
                <h2>Availability calendar</h2><?php
                $start_date = get_field('acc_start_date', get_the_ID());
                $end_date = get_field('acc_end_date', get_the_ID());
            
                if ($start_date && $end_date) {
                    $events = array(
                        array(
                            'title' => 'Available',
                            'start' => $start_date,
                            'end'   => date('Y-m-d', strtotime($end_date . ' +1 day')), // End date is exclusive
                        ),
                    );
                } else {
                    $events = array();
                } ?>
                <div id="availability-calendar" data-events='<?php echo json_encode($events); ?>'></div>
            </div>

            <div class="specialFeatures">
                <h2>Special features</h2>
                <div class="featruesItems">
                    <ul>
                        <?php if (have_rows('characteristics')): // Check if the repeater field has rows ?>
                            <?php while (have_rows('characteristics')): the_row(); ?>
                            <?php $char_icon = get_sub_field('char_icon'); // Get the nested image field
                                $label_characteristics = get_sub_field('label_characteristics'); 
                                if ($char_icon): ?>
                                    <li>
                                        <img src="<?php echo esc_url($char_icon['url']); ?>" alt="<?php echo esc_attr($char_icon['alt']); ?>">
                                        <p><?php echo esc_html($label_characteristics); ?></p>
                                    </li>
                                <?php endif; ?>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <li>No characteristics found.</li>
                        <?php endif; ?>
                    </ul>
                </div>

                <div class="amenities-section">
                    <!-- Main Repeater Loop -->
                    <?php if (have_rows('acc_amenities')) : ?>
                        <?php while (have_rows('acc_amenities')) : the_row(); ?>
                            <div class="amenities-group">
                                <!-- Amenities Heading -->
                                <h3 class="amenities-heading"><?php the_sub_field('amenities_heading'); ?></h3>

                                <!-- Nested Repeater Loop -->
                                <?php if (have_rows('amenities_features')) : ?>
                                    <ul class="amenities-features-list">
                                        <?php while (have_rows('amenities_features')) : the_row(); ?>
                                            <li class="amenity-feature">
                                                <!-- Feature Icon -->
                                                <?php if ($icon = get_sub_field('feature_icon')) : ?>
                                                    <span class="feature-icon">
                                                        <img src="<?php echo esc_url($icon['url']); ?>" alt="<?php echo esc_attr($icon['alt']); ?>">
                                                    </span>
                                                <?php endif; ?>

                                                <!-- Feature Label -->
                                                <span class="feature-label"><?php the_sub_field('feature_label'); ?></span>
                                            </li>
                                        <?php endwhile; ?>
                                    </ul>
                                <?php endif; ?>
                            </div>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </div>
            </div>


            <div class="accDistribution">
                <h2>Distribution of bedrooms</h2>
                <div class="distributionItems">
                    <ul>
                        <?php if (have_rows('distribution_partitions')) : ?>
                            <?php while (have_rows('distribution_partitions')) : the_row(); 
                                $dis_par_subheading = get_sub_field('dis_par_subheading'); 
                                $dis_par_heading = get_sub_field('dis_par_heading'); ?>
                                <li>
                                    <?php if ($dis_par_icon = get_sub_field('dis_par_icon')) : ?>
                                        <span class="feature-icon">
                                            <img src="<?php echo esc_url($dis_par_icon['url']); ?>" alt="<?php echo esc_attr($dis_par_icon['alt']); ?>">
                                        </span>
                                    <?php endif; ?>
                                    <h4 class="bedrooms-count"><?php echo esc_html($dis_par_subheading); ?></h4>
                                    <p class="bedrooms-type"><?php echo esc_html($dis_par_heading); ?></p>
                                </li>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>


            <div class="accDistribution">
                <h2>Your schedule</h2>
                <div class="scheduleWrapper">
                    <div class="scheduleItem">
                        <img src="<?php echo get_stylesheet_directory_uri()."/images/checkin.svg" ?>" alt="checkin">
                        <div class="scheduleTiming">
                            <p>Check-in</p>
                            <p>from <?php the_field( 'secduling_checkin' ); ?> Every day</p>
                        </div>
                    </div>
                    <div class="scheduleItem">
                        <img src="<?php echo get_stylesheet_directory_uri()."/images/checkOut.svg" ?>" alt="checkin">
                        <div class="scheduleTiming">
                            <p>Check-Out</p>
                            <p>Before <?php the_field( 'secduling_checkout' ); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="accSecurity">
                <h2>Security Deposit (refundable)</h2>
                <ul>
                    <li><span class="boldRegular">Amount: </span><?php the_field( 'security_amount' ); ?></li>
                    <li><span class="boldRegular">Payment method: </span><?php the_field( 'security_payment_method' ); ?></li>
                    <li><?php the_field( 'security_note' ); ?></li>
                </ul>
            </div>

            <div class="google-map-container">
                <?php 
                // Get the Google Map iframe value from the ACF field
                $google_map = get_field('google_map'); 
                
                // Check if the field has a value
                if (!empty($google_map)) : 
                    echo $google_map; // Output the iframe directly
                else : ?>
                    <p>Google Map is not available at the moment.</p>
                <?php endif; ?>
            </div>


        </div>
        <div class="rightSide">
            <div class="stickyPosition">
                <div class="bookingFrom">
                    <?php echo do_shortcode('[ninja_form id=3]'); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
get_footer();
?>
