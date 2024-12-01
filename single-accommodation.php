<?php
get_header();
?>

<div class="ultimate-acc-wrapper"><?php

    $gallery = get_field('acc_images', $atts['post_id']);

    if ($gallery) : ?>

        <div class="gallery">
            <?php foreach ($gallery as $image): ?>
                <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>">
            <?php endforeach; ?>
        </div>

        <!-- Lightbox -->
        <div id="lightbox" style="display: none;">
            <div class="close">x</div>
            <div class="prev">&lt;</div>
            <div class="next">&gt;</div>
            <img src="#">
        </div><?php

    endif; ?>

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

        </div>
        <div class="rightSide">
            
        </div>
    </div>
</div>

<?php
get_footer();
?>
