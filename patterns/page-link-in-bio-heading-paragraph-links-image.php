<?php
/**
 * Title: Link in bio heading, paragraph, links and full-height image
 * Slug: theme-gite-broceliande-wp/page-link-in-bio-heading-paragraph-links-image
 * Categories: theme-gite-broceliande-wp_page, banner, featured
 * Keywords: starter
 * Block Types: core/post-content
 * Viewport width: 1400
 * Description: A link in bio landing page with a heading, paragraph, links and a full height image.
 *
 * @package WordPress
 * @subpackage Theme_Gite_Broceliande_WP
 * @since Gîtes Brocéliande 1.0
 */

?>
<!-- wp:group {"align":"full","className":"is-style-section-4","style":{"dimensions":{"minHeight":"100vh"},"spacing":{"margin":{"top":"0","bottom":"0"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull is-style-section-4" style="min-height:100vh;margin-top:0;margin-bottom:0">
	<!-- wp:columns {"align":"full"} -->
	<div class="wp-block-columns alignfull">
		<!-- wp:column {"verticalAlignment":"center"} -->
		<div class="wp-block-column is-vertically-aligned-center">
			<!-- wp:group {"style":{"spacing":{"padding":{"right":"var:preset|spacing|50","left":"var:preset|spacing|50","top":"var:preset|spacing|50","bottom":"var:preset|spacing|50"}}},"layout":{"type":"default"}} -->
			<div class="wp-block-group" style="padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50)">
				<!-- wp:heading -->
				<h2 class="wp-block-heading"><?php esc_html_e( 'Lewis Hine', 'theme-gite-broceliande-wp' ); ?></h2>
				<!-- /wp:heading -->

				<!-- wp:paragraph -->
				<p><?php esc_html_e( 'Lewis W. Hine studied sociology before moving to New York in 1901 to work at the Ethical Culture School, where he took up photography to enhance his teaching practices', 'theme-gite-broceliande-wp' ); ?></p>
				<!-- /wp:paragraph -->

				<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
				<div class="wp-block-group">
					<!-- wp:paragraph -->
					<p><a href="#"><?php esc_html_e( 'Instagram', 'theme-gite-broceliande-wp' ); ?></a></p>
					<!-- /wp:paragraph -->

					<!-- wp:paragraph -->
					<p><a href="#"><?php echo esc_html_x( 'X', 'Refers to the social media platform formerly known as Twitter.', 'theme-gite-broceliande-wp' ); ?></a></p>
					<!-- /wp:paragraph -->

					<!-- wp:paragraph -->
					<p><a href="#"><?php esc_html_e( 'TikTok', 'theme-gite-broceliande-wp' ); ?></a></p>
					<!-- /wp:paragraph -->
				</div>
				<!-- /wp:group -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:cover {"url":"<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/composition-placeholder.webp","alt":"","dimRatio":0,"customOverlayColor":"#6b6b6b","isUserOverlayColor":true,"minHeight":100,"minHeightUnit":"vh","layout":{"type":"default"}} -->
			<div class="wp-block-cover" style="min-height:100vh"><span aria-hidden="true" class="wp-block-cover__background has-background-dim-0 has-background-dim" style="background-color:#6b6b6b"></span>
				<img class="wp-block-cover__image-background" alt="" src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/composition-placeholder.webp" data-object-fit="cover"/><div class="wp-block-cover__inner-container">
				<!-- wp:spacer {"height":"var:preset|spacing|20"} -->
				<div style="height:var(--wp--preset--spacing--20)" aria-hidden="true" class="wp-block-spacer"></div>
				<!-- /wp:spacer -->
			</div></div>
			<!-- /wp:cover -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->
</div>
<!-- /wp:group -->
