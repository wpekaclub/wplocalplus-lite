<?php
/**
 * The Template for displaying single place
 *
 * This template can be overridden by copying it to yourtheme/wplocalplus-lite/single-place.php.
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
do_action( 'wplocalplus_lite_before_single_place_content' );
?>
	<div class="wplocal_place">
		<div class="wplocal_place_main">
			<?php if ( isset( $image_url ) && ! empty( $image_url ) ) : ?>
			<div class="wplocal_place_main_image">
				<img class="wplocal_place_main_image_src" alt="" src="<?php echo esc_url( $image_url ); ?>"/>
			</div>
			<?php endif; ?>
			<div class="wplocal_place_main_content">
				<div class="wplocal_place_main_content_place">
					<div class="wplocal_place_main_content_place_details">
						<ul>
							<li>
								<strong class="wplocal_place_main_content_details_name"><?php echo esc_attr( $name ); ?></strong>
							</li>
							<li>
								<div class="wplocal_place_main_content_details_ratings" data-rating="<?php echo esc_attr( $ratings ); ?>"> </div>
							</li>
							<li>
								<div class="wplocal_place_main_content_details_address"><?php echo esc_attr( $address ); ?></div>
							</li>
							<?php if ( isset( $phone_number ) && ! empty( $phone_number ) ) : ?>
							<li>
								<div class="wplocal_place_main_content_details_phone"><a href="tel://<?php echo esc_attr( rtrim( trim( $phone_number ), '()' ) ); ?>"><span class="dashicons dashicons-phone"></span> <?php echo esc_attr( rtrim( trim( $phone_number ), '()' ) ); ?></a></div>
							</li>
							<?php endif; ?>
						</ul>
					</div>
					<?php if ( isset( $reviews ) && ! empty( $reviews ) ) : ?>
					<div class="wplocal_place_main_content_place_reviews">
						<ul class="wplocal_place_main_content_place_reviews_list">
						<?php foreach ( $reviews as $review ) : ?>
							<li class="wplocal_place_main_content_place_review">
								<div class="wplocal_place_main_content_place_review_author_image">
									<?php if ( isset( $review['author_image_url'] ) && ! empty( $review['author_image_url'] ) ) : ?>
										<img class="wplocal_place_main_content_place_review_author_image_src" alt="" src="<?php echo esc_url( $review['author_image_url'] ); ?>"/>
									<?php else : ?>
										<img class="wplocal_place_main_content_place_review_author_image_no_src" alt="" src="<?php echo esc_url( WPLOCALPLUS_LITE_ASSETS_URL ); ?>images/author.png"/>
									<?php endif; ?>
								</div>
								<div class="wplocal_place_main_content_place_review_details">
									<span class="wplocal_place_main_content_place_review_details_title"><?php echo esc_attr( $review['review_title'] ); ?></span>
									<span class="wplocal_place_main_content_place_review_details_author">
										<strong>
											<?php if ( isset( $review['author_url'] ) && ! empty( $review['author_url'] ) ) : ?>
												<a target="_blank" href="<?php echo esc_url( $review['author_url'] ); ?>"><?php echo esc_attr( $review['author'] ); ?></a>
											<?php else : ?>
												<a href="#"><?php echo esc_attr( $review['author'] ); ?></a>
											<?php endif; ?>
										</strong>
									</span>
								</div>
							</li>
						<?php endforeach; ?>
						</ul>
						<?php if ( isset( $profile_url ) && ! empty( $profile_url ) ) : ?>
							<a href="<?php echo esc_url( $profile_url ); ?>" target="_blank">View more</a>
						<?php endif; ?>
					</div>
					<?php endif; ?>
				</div>
				<?php if ( 1 === $show_map ) : ?>
				<div class="wplocal_place_main_content_map">
					<div class="wplocal_place_main_content_map_google" id="gmap3"></div>
					<a href="https://www.google.com/maps/dir/@<?php echo esc_attr( $latitude ); ?>,<?php echo esc_attr( $longitude ); ?>,15z" target="_blank"> Get direction </a>
				</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
<?php
/**
 * Hook - wplocalplus_lite_after_main_content.
 */
do_action( 'wplocalplus_lite_after_single_place_content', $latitude, $longitude, $show_map );

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
