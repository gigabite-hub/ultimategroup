<?php
get_header();

// Get post ID once
$post_id = get_the_ID();

// Pre-fetch all ACF fields to minimize database queries
$thumbnail_url = get_the_post_thumbnail_url($post_id, 'full');
$title = get_the_title();
$developer_terms = wp_get_post_terms($post_id, 'developer');
$developer_name = !empty($developer_terms) ? $developer_terms[0]->name : '';
$location = get_field('property_location', $post_id);
$price = get_field('property_single_price', $post_id);
$property_type = get_field('property_type', $post_id);
$unit_type = get_field('unit_type', $post_id);
$size = get_field('size', $post_id);
$down_payment = get_field('down_payment', $post_id);
$payment_plan = get_field('payment_plan', $post_id);
$handover = get_field('handover', $post_id);
$content = get_the_content();
$feature_amenities = get_field('feature_amenities_items', $post_id);
$payment_plan_items = get_field('payment_plan_items', $post_id);
$gallery_images = get_field('images', $post_id);
?>

<div class="propertywrapper">
    <div class="bannerImage" style="background-image: url('<?php echo esc_url($thumbnail_url); ?>');">
        <div class="pro-content">
            <h1><?php echo esc_html($title); ?></h1>
        </div>
    </div>

    <div class="pro-container">
        <!-- Section Direct Sales -->
        <section class="direct-section">
            <div class="head-section">
                <div class="headitem">
                    <h2><?php echo esc_html($title); ?></h2>
                    <div class="developer-name">
                        <h3>By <span><?php echo esc_html($developer_name); ?></span></h3> |
                        <h4><?php echo esc_html($location); ?></h4>
                    </div>
                    <div class="pro-status">
                        <h3>Status</h3>
                        <h4>New Launch</h4>
                    </div>
                </div>
                <div class="headitem">
                    <div class="price">
                        <p>Starting From</p>
                        <h3>AED <?php echo esc_html($price); ?></h3>
                    </div>
                </div>
            </div>

            <div class="pro-directbody">
                <ul>
                    <li>
                        <p><i class="fa-solid fa-house"></i> Property Type:</p>
                        <p><b><?php echo esc_html($property_type); ?></b></p>
                    </li>
                    <li>
                        <p><i class="fa-solid fa-bed"></i> Unit type:</p>
                        <p><b><?php echo esc_html($unit_type); ?></b></p>
                    </li>
                    <li>
                        <p><i class="fa-solid fa-chart-area"></i> Size:</p>
                        <p><b><?php echo esc_html($size); ?></b></p>
                    </li>
                    <li>
                        <p><i class="fa-solid fa-percent"></i> Down Payment:</p>
                        <p><b><?php echo esc_html($down_payment); ?></b></p>
                    </li>
                    <li>
                        <p><i class="fa-regular fa-money-bill-1"></i> Payment Plan:</p>
                        <p><b><?php echo esc_html($payment_plan); ?></b></p>
                    </li>
                    <li>
                        <p><i class="fa-solid fa-chart-area"></i> Handover:</p>
                        <p><b><?php echo esc_html($handover); ?></b></p>
                    </li>
                </ul>
            </div>
            
            <div class="direct-tagline">
                <h3>Direct Sales & 0% Commission</h3>
            </div>
        </section>

        <section class="overview">
            <?php echo apply_filters('the_content', $content); ?>
        </section>

        <?php if ($feature_amenities): ?>
        <section class="feature-amenities" id="amenities">
            <h2>Feature & Amenities</h2>
            <div class="amenities-flex">
                <?php foreach ($feature_amenities as $amenity): ?>
                    <div class="amenities-items">
                        <img src="<?php echo esc_url($amenity['feature_amenities_icon']['url']); ?>" 
                             alt="<?php echo esc_attr($amenity['feature_amenities_icon']['alt']); ?>"
                             loading="lazy">
                        <div class="amenites-content">
                            <h4><?php echo esc_html($amenity['feature_amenities_heading']); ?></h4>
                            <p><?php echo esc_html($amenity['feature_amenities_subheading']); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>

        <?php if ($payment_plan_items): ?>
        <section class="feature-amenities payment-plan" id="paymentplan">
            <h2>Payment Plan</h2>
            <div class="amenities-flex">
                <?php foreach ($payment_plan_items as $plan_item): ?>
                    <div class="amenities-items">
                        <img src="<?php echo esc_url($plan_item['payment_plan_icon']['url']); ?>" 
                             alt="<?php echo esc_attr($plan_item['payment_plan_icon']['alt']); ?>"
                             loading="lazy">
                        <div class="amenites-content">
                            <h4><?php echo esc_html($plan_item['payment_plan_percentage_value']); ?></h4>
                            <h3><?php echo esc_html($plan_item['payment_plan_heading']); ?></h3>
                            <p><?php echo esc_html($plan_item['payment_plan_dated']); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>

        <?php if ($gallery_images): ?>
        <section class="feature-amenities gallery" id="gallery">
            <h2>Gallery</h2>
            <div class="gallery-grid">
                <?php foreach ($gallery_images as $image): ?>
                    <div class="gallery-item">
                        <a href="<?php echo esc_url($image['url']); ?>" target="_blank">
                            <img src="<?php echo esc_url($image['sizes']['medium']); ?>" 
                                 alt="<?php echo esc_attr($image['alt']); ?>"
                                 loading="lazy">
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>

        <section class="feature-amenities propertyForm" id="contactus">
            <h2>Get in touch!</h2>
            <?php echo do_shortcode('[ninja_form id=1]'); ?>
        </section>
    </div>
</div>

<?php
get_footer();
?>