<?php
get_header();

// Get all fields at the start to minimize queries
$post_id = get_the_ID();
$gallery = get_field('acc_images', $post_id);
$characteristics = get_field('characteristics', $post_id);
?>

<div class="ultimate-acc-wrapper">
    <?php if ($gallery && count($gallery) >= 5): ?>
        <div class="gallery">
            <!-- First Div: Single Image -->
            <div class="single-image">
                <img src="<?php echo esc_url($gallery[0]['url']); ?>" alt="<?php echo esc_attr($gallery[0]['alt']); ?>" loading="lazy" />
            </div>
        
            <!-- Second Div: Four Images -->
            <div class="four-images">
                <?php for ($i = 1; $i <= 4; $i++): ?>
                    <div class="image">
                        <img src="<?php echo esc_url($gallery[$i]['url']); ?>" alt="<?php echo esc_attr($gallery[$i]['alt']); ?>" loading="lazy" />
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

<?php if ($characteristics): ?>
<div class="acc-characteristics">
    <div class="container">
        <div class="char-wrapper">
            <div class="char-items">
                <ul>
                    <?php foreach ($characteristics as $item): ?>
                        <?php if ($item['char_icon']): ?>
                            <li>
                                <img src="<?php echo esc_url($item['char_icon']['url']); ?>" alt="<?php echo esc_attr($item['char_icon']['alt']); ?>" loading="lazy">
                                <p><?php echo esc_html($item['label_characteristics']); ?></p>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="char-items">
            </div>
        </div>
    </div>    
</div>
<?php endif; ?>

<div class="container">
    <div class="accBodyContent">
        <div class="leftSide">
            <div class="accHeader">
                <?php
                $title = get_the_title();
                $location = get_field('acc_location', $post_id) ?: 'Dubai';
                $content = get_the_content(); ?>

                <h1><?php echo esc_html($title); ?></h1>
                <p><i class="fa-solid fa-location-dot"></i> <?php echo esc_html($location); ?></p>
            </div>
            <div class="accAccommodation">
                <h2>Accommodation</h2>
                <h4>Description</h4>
                <div class="accContent truncated">
                    <?php echo apply_filters('the_content', $content); ?>
                </div>
                <a href="#" class="toggle-btn">More Details</a>
            </div>

            <div class="accAvailability">
                <h2>Availability calendar</h2>
                <?php
                $start_date = get_field('acc_start_date', $post_id);
                $end_date = get_field('acc_end_date', $post_id);
                $events = [];
            
                if ($start_date && $end_date) {
                    $events = array(
                        array(
                            'title' => 'Available',
                            'start' => $start_date,
                            'end'   => date('Y-m-d', strtotime($end_date . ' +1 day')),
                        ),
                    );
                } ?>
                <div id="availability-calendar" data-events='<?php echo wp_json_encode($events); ?>'></div>
            </div>

            <?php if ($characteristics): ?>
            <div class="specialFeatures">
                <h2>Special features</h2>
                <div class="featruesItems">
                    <ul>
                        <?php foreach ($characteristics as $item): ?>
                            <?php if ($item['char_icon']): ?>
                                <li>
                                    <img src="<?php echo esc_url($item['char_icon']['url']); ?>" alt="<?php echo esc_attr($item['char_icon']['alt']); ?>" loading="lazy">
                                    <p><?php echo esc_html($item['label_characteristics']); ?></p>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

                <?php 
                $amenities = get_field('acc_amenities', $post_id);
                if ($amenities): ?>
                <div class="amenities-section">
                    <?php foreach ($amenities as $amenity_group): ?>
                        <div class="amenities-group">
                            <h3 class="amenities-heading"><?php echo esc_html($amenity_group['amenities_heading']); ?></h3>

                            <?php if (!empty($amenity_group['amenities_features'])): ?>
                                <ul class="amenities-features-list">
                                    <?php foreach ($amenity_group['amenities_features'] as $feature): ?>
                                        <li class="amenity-feature">
                                            <?php if ($feature['feature_icon']): ?>
                                                <span class="feature-icon">
                                                    <img src="<?php echo esc_url($feature['feature_icon']['url']); ?>" alt="<?php echo esc_attr($feature['feature_icon']['alt']); ?>" loading="lazy">
                                                </span>
                                            <?php endif; ?>

                                            <span class="feature-label"><?php echo esc_html($feature['feature_label']); ?></span>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>

            <?php 
            $distribution = get_field('distribution_partitions', $post_id);
            if ($distribution): ?>
            <div class="accDistribution">
                <h2>Distribution of bedrooms</h2>
                <div class="distributionItems">
                    <ul>
                        <?php foreach ($distribution as $item): ?>
                            <li>
                                <?php if ($item['dis_par_icon']): ?>
                                    <span class="feature-icon">
                                        <img src="<?php echo esc_url($item['dis_par_icon']['url']); ?>" alt="<?php echo esc_attr($item['dis_par_icon']['alt']); ?>" loading="lazy">
                                    </span>
                                <?php endif; ?>
                                <h4 class="bedrooms-count"><?php echo esc_html($item['dis_par_subheading']); ?></h4>
                                <p class="bedrooms-type"><?php echo esc_html($item['dis_par_heading']); ?></p>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <?php endif; ?>

            <div class="accDistribution">
                <h2>Your schedule</h2>
                <div class="scheduleWrapper">
                    <div class="scheduleItem">
                        <img src="<?php echo esc_url(get_stylesheet_directory_uri()."/images/checkin.svg"); ?>" alt="checkin" loading="lazy">
                        <div class="scheduleTiming">
                            <p>Check-in</p>
                            <p>from <?php the_field('secduling_checkin', $post_id); ?> Every day</p>
                        </div>
                    </div>
                    <div class="scheduleItem">
                        <img src="<?php echo esc_url(get_stylesheet_directory_uri()."/images/checkOut.svg"); ?>" alt="checkin" loading="lazy">
                        <div class="scheduleTiming">
                            <p>Check-Out</p>
                            <p>Before <?php the_field('secduling_checkout', $post_id); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="accSecurity">
                <h2>Security Deposit (refundable)</h2>
                <ul>
                    <li><span class="boldRegular">Amount: </span><?php the_field('security_amount', $post_id); ?></li>
                    <li><span class="boldRegular">Payment method: </span><?php the_field('security_payment_method', $post_id); ?></li>
                    <li><?php the_field('security_note', $post_id); ?></li>
                </ul>
            </div>

            <div class="google-map-container">
                <?php 
                $google_map = get_field('google_map', $post_id); 
                if (!empty($google_map)): 
                    echo $google_map;
                else: ?>
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