<?php
/**
 * The Template for displaying all reviews
 *
 * This template can be overridden by copying it to yourtheme/wplocalplus-lite/reviews.php.
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
 * Hook - wplocalplus_lite_before_reviews_content.
 */
do_action( 'wplocalplus_lite_before_reviews_content' );
?>
	<ul class="wplocal_reviews">
<?php
while ( $q->have_posts() ) :
	$q->the_post();
	$review_post_id = get_the_ID();
	$name           = get_the_title();
	$content        = get_the_content();
	$review_author  = get_field( 'review_author', $review_post_id );
	$author_url     = get_field( 'author_url', $review_post_id );
	$review_rating  = get_field( 'review_rating', $review_post_id );
	$review_date    = get_field( 'review_date', $review_post_id );
	$post_parent    = get_field( 'review_place', $review_post_id );
	$business_name  = '';
	if ( $post_parent ) {
		$business_name = get_the_title( $post_parent );
	}
	$pros            = get_field( 'business_details_pros', $review_post_id );
	$cons            = get_field( 'business_details_cons', $review_post_id );
	$location_data   = wp_get_post_terms( $review_post_id, 'wplocal_location' );
	$place_type_data = wp_get_post_terms( $review_post_id, 'wplocal_place_type' );
	$location        = $location_data[0]->name;
	$place_type      = $place_type_data[0]->name;

	$custom           = get_post_custom( $review_post_id );
	$review_id        = isset( $custom['_wplocal_reviews_review_id'][0] ) ? $custom['_wplocal_reviews_review_id'][0] : '';
	$listing_id       = isset( $custom['_wplocal_reviews_listing_id'][0] ) ? $custom['_wplocal_reviews_listing_id'][0] : '';
	$reviews_source   = isset( $custom['_wplocal_reviews_source'][0] ) ? $custom['_wplocal_reviews_source'][0] : '';
	$author_image_url = isset( $custom['_wplocal_reviews_author_image_url'][0] ) ? $custom['_wplocal_reviews_author_image_url'][0] : '';
	?>
	<li>
		<div class="wplocal_reviews_main" id="<?php echo esc_attr( $review_post_id ); ?>">
			<div class="wplocal_reviews_main_author">
				<ul>
					<li>
						<div class="wplocal_reviews_main_author_image">
							<?php if ( isset( $author_image_url ) && ! empty( $author_image_url ) ) : ?>
								<img class="wplocal_place_main_content_place_review_author_image_src" alt="" src="<?php echo esc_url( $author_image_url ); ?>"/>
							<?php else : ?>
								<img class="wplocal_place_main_content_place_review_author_image_no_src" alt="" src="<?php echo esc_url( WPLOCALPLUS_LITE_ASSETS_URL ); ?>images/author.png"/>
							<?php endif; ?>
						</div>
					</li>
					<li>
						<div class="wplocal_reviews_main_author_name">
							<strong>
								<?php if ( isset( $author_url ) && ! empty( $author_url ) ) : ?>
									<a style="text-decoration:none;" target="_blank" href="<?php echo esc_url( $author_url ); ?>"><?php echo esc_attr( $review_author ); ?></a>
								<?php else : ?>
									<a style="text-decoration:none;" href="#"><?php echo esc_attr( $review_author ); ?></a>
								<?php endif; ?>
							</strong>
						</div>
					</li>
				</ul>
			</div>
			<div class="wplocal_reviews_main_content">
				<ul>
					<li>
						<div class="wplocal_reviews_main_content_business_name">
							<strong><?php echo esc_attr( $business_name ); ?></strong>
						</div>
					</li>
					<li>
						<div class="wplocal_reviews_main_content_ratings" data-rating="<?php echo esc_attr( $review_rating ); ?>"></div>
						<div class="wplocal_reviews_main_content_review_date"> <?php echo esc_attr( gmdate( 'M j, Y', strtotime( $review_date ) ) ); ?></div>
					</li>
					<li>
						<div class="wplocal_reviews_main_content_description">
							<?php echo esc_attr( $content ); ?>
						</div>
					</li>
					<li>
						<div class="wplocal_reviews_main_content_pros_cons">
							<?php if ( isset( $pros ) && ! empty( $pros ) ) : ?>
								<span><strong>Pros: </strong><?php echo esc_attr( $pros ); ?></span>
							<?php endif; ?>
							<?php if ( isset( $pros ) && ! empty( $pros ) ) : ?>
								<span><strong>Cons: </strong><?php echo esc_attr( $cons ); ?></span>
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
 * Hook - wplocalplus_lite_after_reviews_content.
 */
do_action( 'wplocalplus_lite_after_reviews_content' );

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
