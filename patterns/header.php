<?php
/**
 * Title: Header
 * Slug: theme-gite-broceliande-wp/header
 * Categories: header
 * Block Types: core/template-part/header
 * Description: Site header with site title and navigation.
 *
 * @package WordPress
 * @subpackage Theme_Gite_Broceliande_WP
 * @since Gîtes Brocéliande 1.0
 */

?>
<!-- wp:group {"align":"full","className":"gb-site-header","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull gb-site-header">
	<!-- wp:group {"align":"wide","className":"gb-site-header__shell","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between","verticalAlignment":"center"}} -->
	<div class="wp-block-group alignwide gb-site-header__shell">
		<!-- wp:group {"className":"gb-site-header__brand","layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"center"}} -->
		<div class="wp-block-group gb-site-header__brand">
			<!-- wp:site-logo {"className":"gb-site-header__logo"} /-->
			<!-- wp:site-title {"level":0,"className":"gb-site-header__title"} /-->
			<!-- wp:site-tagline {"className":"gb-site-header__tagline"} /-->
		</div>
		<!-- /wp:group -->

		<!-- wp:group {"className":"gb-site-header__nav-stack","layout":{"type":"flex","orientation":"vertical","justifyContent":"right","flexWrap":"nowrap"}} -->
		<div class="wp-block-group gb-site-header__nav-stack">
			<!-- wp:group {"className":"gb-site-header__secondary-row","layout":{"type":"flex","flexWrap":"wrap","justifyContent":"right","verticalAlignment":"center"}} -->
			<div class="wp-block-group gb-site-header__secondary-row">
				<!-- wp:html -->
				<?php if ( has_nav_menu( 'header-secondary' ) ) : ?>
					<nav class="gb-site-header__secondary-nav" aria-label="<?php esc_attr_e( 'Menu secondaire', 'theme-gite-broceliande-wp' ); ?>">
						<?php
						wp_nav_menu(
							array(
								'theme_location' => 'header-secondary',
								'container'      => false,
								'depth'          => 1,
								'fallback_cb'    => false,
								'menu_class'     => 'gb-site-header__secondary-menu',
							)
						);
						?>
					</nav>
				<?php endif; ?>
				<!-- /wp:html -->

				<!-- wp:buttons {"className":"gb-site-header__actions","layout":{"type":"flex","justifyContent":"right","flexWrap":"nowrap"}} -->
				<div class="wp-block-buttons gb-site-header__actions">
					<!-- wp:button {"className":"gb-site-header__cta"} -->
					<div class="wp-block-button gb-site-header__cta"><a class="wp-block-button__link wp-element-button" href="#"><?php esc_html_e( 'Réserver', 'theme-gite-broceliande-wp' ); ?></a></div>
					<!-- /wp:button -->
				</div>
				<!-- /wp:buttons -->
			</div>
			<!-- /wp:group -->

			<!-- wp:navigation {"overlayBackgroundColor":"base","overlayTextColor":"contrast","className":"gb-site-header__primary-nav","style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","justifyContent":"right","flexWrap":"wrap"},"ariaLabel":"<?php esc_attr_e( 'Menu principal', 'theme-gite-broceliande-wp' ); ?>"} /-->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
