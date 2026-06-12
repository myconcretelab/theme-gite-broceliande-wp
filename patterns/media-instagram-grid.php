<?php
/**
 * Title: Instagram grid
 * Slug: theme-gite-broceliande-wp/media-instagram-grid
 * Categories: media, gallery, featured
 * Viewport width: 1440
 * Description: A grid section with photos and a link to an Instagram profile.
 *
 * @package WordPress
 * @subpackage Theme_Gite_Broceliande_WP
 * @since Gîtes Brocéliande 1.0
 */

?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50"},"margin":{"top":"0","bottom":"0"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="margin-top:0;margin-bottom:0;padding-top:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50)">
	<!-- wp:group {"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|50"}},"layout":{"type":"grid","minimumColumnWidth":"18rem"}} -->
	<div class="wp-block-group alignwide">
		<!-- wp:group {"className":"is-style-section-3","style":{"dimensions":{"minHeight":"297px"}},"layout":{"type":"constrained"}} -->
		<div class="wp-block-group is-style-section-3" style="min-height:297px">
			<!-- wp:group {"style":{"dimensions":{"minHeight":"100%"},"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","orientation":"vertical","verticalAlignment":"center","justifyContent":"center"}} -->
			<div class="wp-block-group" style="min-height:100%">
				<!-- wp:heading {"fontSize":"large"} -->
				<h2 class="wp-block-heading has-large-font-size"><?php esc_html_e( 'Instagram', 'theme-gite-broceliande-wp' ); ?></h2>
				<!-- /wp:heading -->

				<!-- wp:paragraph {"align":"center","fontSize":"medium"} -->
				<p class="has-text-align-center has-medium-font-size"><a href="#"><?php echo esc_html_x( '@example', 'Example username for social media account.', 'theme-gite-broceliande-wp' ); ?></a></p>
				<!-- /wp:paragraph -->
				</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:group -->

		<!-- wp:image {"aspectRatio":"1","scale":"cover","sizeSlug":"full","linkDestination":"none"} -->
		<figure class="wp-block-image size-full"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/composition-placeholder.webp" alt="" style="aspect-ratio:1;object-fit:cover"/></figure>
		<!-- /wp:image -->

		<!-- wp:image {"aspectRatio":"1","scale":"cover","sizeSlug":"full","linkDestination":"none"} -->
		<figure class="wp-block-image size-full"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/composition-placeholder.webp" alt="" style="aspect-ratio:1;object-fit:cover"/></figure>
		<!-- /wp:image -->

		<!-- wp:image {"aspectRatio":"1","scale":"cover","sizeSlug":"full","linkDestination":"none"} -->
		<figure class="wp-block-image size-full"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/composition-placeholder.webp" alt="" style="aspect-ratio:1;object-fit:cover"/></figure>
		<!-- /wp:image -->

		<!-- wp:image {"aspectRatio":"1","scale":"cover","sizeSlug":"full","linkDestination":"none"} -->
		<figure class="wp-block-image size-full"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/composition-placeholder.webp" alt="" style="aspect-ratio:1;object-fit:cover"/></figure>
		<!-- /wp:image -->

		<!-- wp:image {"aspectRatio":"1","scale":"cover","sizeSlug":"full","linkDestination":"none"} -->
		<figure class="wp-block-image size-full"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/composition-placeholder.webp" alt="" style="aspect-ratio:1;object-fit:cover"/></figure>
		<!-- /wp:image -->

		<!-- wp:image {"aspectRatio":"1","scale":"cover","sizeSlug":"full","linkDestination":"none"} -->
		<figure class="wp-block-image size-full"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/composition-placeholder.webp" alt="" style="aspect-ratio:1;object-fit:cover"/></figure>
		<!-- /wp:image -->

		<!-- wp:image {"aspectRatio":"1","scale":"cover","sizeSlug":"full","linkDestination":"none"} -->
		<figure class="wp-block-image size-full"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/composition-placeholder.webp" alt="" style="aspect-ratio:1;object-fit:cover"/></figure>
		<!-- /wp:image -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
