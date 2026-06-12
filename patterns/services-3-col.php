<?php
/**
 * Title: Services, 3 columns
 * Slug: theme-gite-broceliande-wp/services-3-col
 * Categories: call-to-action, banner, services
 * Description: Three columns with images and text to showcase services.
 *
 * @package WordPress
 * @subpackage Theme_Gite_Broceliande_WP
 * @since Gîtes Brocéliande 1.0
 */

?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50"},"blockGap":"var:preset|spacing|50","margin":{"top":"0","bottom":"0"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="margin-top:0;margin-bottom:0;padding-top:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50)">
	<!-- wp:heading {"align":"wide"} -->
	<h2 class="wp-block-heading alignwide"><?php esc_html_e( 'Our services', 'theme-gite-broceliande-wp' ); ?></h2>
	<!-- /wp:heading -->

	<!-- wp:columns {"align":"wide"} -->
	<div class="wp-block-columns alignwide">
		<!-- wp:column -->
		<div class="wp-block-column">

			<!-- wp:image {"aspectRatio":"4/3","scale":"cover","sizeSlug":"full","style":{"spacing":{"margin":{"bottom":"24px"}}}} -->
			<figure class="wp-block-image size-full" style="margin-bottom:24px">
				<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/composition-placeholder.webp" alt="" style="aspect-ratio:4/3;object-fit:cover"/>
			</figure>
			<!-- /wp:image -->

			<!-- wp:heading {"level":3} -->
			<h3 class="wp-block-heading"><?php esc_html_e( 'Collect', 'theme-gite-broceliande-wp' ); ?></h3>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"fontSize":"medium"} -->
			<p class="has-medium-font-size"><?php esc_html_e( 'Like flowers that bloom in unexpected places, every story unfolds with beauty and resilience', 'theme-gite-broceliande-wp' ); ?></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:image {"aspectRatio":"4/3","scale":"cover","sizeSlug":"full","style":{"spacing":{"margin":{"bottom":"24px"}}}} -->
			<figure class="wp-block-image size-full" style="margin-bottom:24px">
				<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/composition-placeholder.webp" alt="" style="aspect-ratio:4/3;object-fit:cover"/>
			</figure>
			<!-- /wp:image -->

			<!-- wp:heading {"level":3} -->
			<h3 class="wp-block-heading"><?php esc_html_e( 'Assemble', 'theme-gite-broceliande-wp' ); ?></h3>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"fontSize":"medium"} -->
			<p class="has-medium-font-size"><?php esc_html_e( 'Like flowers that bloom in unexpected places, every story unfolds with beauty and resilience', 'theme-gite-broceliande-wp' ); ?></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:image {"aspectRatio":"4/3","scale":"cover","sizeSlug":"full","style":{"spacing":{"margin":{"bottom":"24px"}}}} -->
			<figure class="wp-block-image size-full" style="margin-bottom:24px">
				<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/composition-placeholder.webp" alt="" style="aspect-ratio:4/3;object-fit:cover"/>
			</figure>
			<!-- /wp:image -->

			<!-- wp:heading {"level":3} -->
			<h3 class="wp-block-heading"><?php esc_html_e( 'Deliver', 'theme-gite-broceliande-wp' ); ?></h3>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"fontSize":"medium"} -->
			<p class="has-medium-font-size"><?php esc_html_e( 'Like flowers that bloom in unexpected places, every story unfolds with beauty and resilience', 'theme-gite-broceliande-wp' ); ?></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->
</div>
<!-- /wp:group -->
