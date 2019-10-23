<?php
/**
 * The Template for displaying all places
 *
 * This template can be overridden by copying it to yourtheme/wplocalplus-lite/places.php.
 *
 * HOWEVER, on occasion WPLocalPlus will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @package     Wplocalplus_Lite/Templates
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>

<?php
/**
 * Hook - wplocalplus_lite_before_main_content.
 */
do_action( 'wplocalplus_lite_before_places_content' );
?>
<?php
if ( 1 === $show_map ) :
	?>
	<div class="wplocal_places_gmap" id="gmap"></div>
	<?php
	/**
	 * Hook - wplocalplus_lite_before_main_content.
	 */
	do_action( 'wplocalplus_lite_make_google_map', $q );
endif;
?>
<ul class="wplocal_places">
<?php
while ( $q->have_posts() ) :
	$q->the_post();
	$places_post_id    = get_the_ID();
	$name              = get_the_title();
	$address           = get_field( 'address', $places_post_id );
	$phone_number      = get_field( 'phone_number', $places_post_id );
	$latitude          = get_field( 'latitude', $places_post_id );
	$longitude         = get_field( 'longitude', $places_post_id );
	$profile_url       = get_field( 'urls_profile', $places_post_id );
	$website_url       = get_field( 'urls_website', $places_post_id );
	$ratings           = get_field( 'ratings', $places_post_id );
	$attachment_id     = get_field( 'image', $places_post_id );
	$image_url         = wp_get_attachment_url( $attachment_id );
	$location_data     = wp_get_post_terms( $places_post_id, 'wplocal_location' );
	$place_type_data   = wp_get_post_terms( $places_post_id, 'wplocal_place_type' );
	$location          = $location_data[0]->name;
	$place_type        = $place_type_data[0]->name;
	$custom            = get_post_custom( $places_post_id );
	$place_id          = isset( $custom['_wplocal_places_place_id'][0] ) ? $custom['_wplocal_places_place_id'][0] : '';
	$review_count      = isset( $custom['_wplocal_places_review_count'][0] ) ? $custom['_wplocal_places_review_count'][0] : '';
	$sample_categories = isset( $custom['_wplocal_places_sample_categories'][0] ) ? $custom['_wplocal_places_sample_categories'][0] : '';
	$places_source     = isset( $custom['_wplocal_places_source'][0] ) ? $custom['_wplocal_places_source'][0] : '';
	$lat_long          = $latitude . ',' . $longitude;
	$loc               = $name . ',' . $address;
	if ( $website_url ) {
		$flag = true;
	} else {
		$flag = false;
	}
	if ( empty( $image_url ) ) {
		$image_url = WPLOCALPLUS_LITE_ASSETS_URL . 'images/image-not-available.jpg';
	}
	?>
	<li>
	<div class="wplocal_places_main" id="<?php echo esc_attr( $places_post_id ); ?>">
	<div class="wplocal_places_main_image">
	<img class="wplocal_places_main_image_src" alt="<?php echo esc_attr( $name ); ?>" src="<?php echo esc_url( $image_url ); ?>"/>
	</div>
	<div class="wplocal_places_main_content">
	<ul>
	<li>
	<strong class="wplocal_places_main_content_title"><a data-value="<?php echo esc_attr( $places_post_id ); ?>" rel="nofollow" class="wplocal_places_moredetail"  href="#<?php echo esc_attr( $places_post_id ); ?>"><?php echo esc_attr( $name ); ?></a></strong>
	</li>
	<li>
	<div class="wplocal_places_main_content_ratings" data-rating="<?php echo esc_attr( $ratings ); ?>"> </div>
	<?php if ( isset( $review_count ) && $review_count > 0 ) : ?>
		<div class="wplocal_places_main_content_reviews"> <?php echo esc_attr( $review_count ); ?> reviews</div>
	<?php endif; ?>
	</li>
	<li>
	<div class="wplocal_places_main_content_address"><?php echo esc_attr( $address ); ?></div>
	</li>
	<li>
	<?php if ( isset( $phone_number ) && ! empty( $phone_number ) ) : ?>
		<div class="wplocal_places_main_content_phone"><a href="tel://<?php echo esc_attr( rtrim( trim( $phone_number ), '()' ) ); ?>"><i class="fa fa-phone fa-1x"></i> <?php echo esc_attr( rtrim( trim( $phone_number ), '()' ) ); ?></a></div>
	<?php endif; ?>
	</li>
	<li>
		<div class="wplocal_places_main_content_categories">
	<?php if ( ! empty( $sample_categories ) && isset( $places_source ) && 'google' !== $places_source ) : ?>
		Categories : <?php echo esc_attr( $sample_categories ); ?>
	<?php else : ?>
		Categories : <?php echo esc_attr( $place_type ); ?>
	<?php endif; ?>
		</div>
	</li>
	</ul>
	</div>
	</div>
	</li>
<?php endwhile; // End of the loop. ?>
</ul>
<?php
/**
 * Hook - wplocalplus_lite_after_main_content.
 */
do_action( 'wplocalplus_lite_after_places_content' );

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
