<?php
/**
 * Gites Broceliande theme functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WordPress
 * @subpackage Theme_Gite_Broceliande_WP
 * @since Gites Broceliande 1.0
 */

// Adds theme support for post formats.
if ( ! function_exists( 'theme_gite_broceliande_wp_post_format_setup' ) ) :
	/**
	 * Adds theme support for post formats.
	 *
	 * @since Gites Broceliande 1.0
	 *
	 * @return void
	 */
	function theme_gite_broceliande_wp_post_format_setup() {
		add_theme_support( 'post-formats', array( 'aside', 'audio', 'chat', 'gallery', 'image', 'link', 'quote', 'status', 'video' ) );
	}
endif;
add_action( 'after_setup_theme', 'theme_gite_broceliande_wp_post_format_setup' );

// Registers navigation menu locations.
if ( ! function_exists( 'theme_gite_broceliande_wp_register_nav_menus' ) ) :
	/**
	 * Registers navigation menu locations.
	 *
	 * @since Gites Broceliande 1.5.13
	 *
	 * @return void
	 */
	function theme_gite_broceliande_wp_register_nav_menus() {
		register_nav_menus(
			array(
				'header-secondary' => __( 'Menu secondaire du header', 'theme-gite-broceliande-wp' ),
			)
		);
	}
endif;
add_action( 'after_setup_theme', 'theme_gite_broceliande_wp_register_nav_menus' );

// Enqueues editor-style.css in the editors.
if ( ! function_exists( 'theme_gite_broceliande_wp_editor_style' ) ) :
	/**
	 * Enqueues editor-style.css in the editors.
	 *
	 * @since Gites Broceliande 1.0
	 *
	 * @return void
	 */
	function theme_gite_broceliande_wp_editor_style() {
		add_editor_style( 'assets/css/editor-style.css' );
	}
endif;
add_action( 'after_setup_theme', 'theme_gite_broceliande_wp_editor_style' );

// Enqueues the theme stylesheet on the front.
if ( ! function_exists( 'theme_gite_broceliande_wp_enqueue_styles' ) ) :
	/**
	 * Enqueues the theme stylesheet on the front.
	 *
	 * @since Gites Broceliande 1.0
	 *
	 * @return void
	 */
	function theme_gite_broceliande_wp_enqueue_styles() {
		$suffix = SCRIPT_DEBUG ? '' : '.min';
		$src    = 'style' . $suffix . '.css';

		wp_enqueue_style(
			'theme-gite-broceliande-wp-style',
			get_parent_theme_file_uri( $src ),
			array(),
			wp_get_theme()->get( 'Version' )
		);
		wp_style_add_data(
			'theme-gite-broceliande-wp-style',
			'path',
			get_parent_theme_file_path( $src )
		);
	}
endif;
add_action( 'wp_enqueue_scripts', 'theme_gite_broceliande_wp_enqueue_styles' );

// Adds the compact state to the sticky header after the visitor scrolls.
if ( ! function_exists( 'theme_gite_broceliande_wp_enqueue_header_script' ) ) :
	/**
	 * Enqueues the sticky header behavior on the front.
	 *
	 * @since Gites Broceliande 1.5.11
	 *
	 * @return void
	 */
	function theme_gite_broceliande_wp_enqueue_header_script() {
		$handle = 'theme-gite-broceliande-wp-header';
		$script = '(() => {
			const header = document.querySelector(".gb-site-header");

			if (!header) {
				return;
			}

			const compactAt = 32;
			const expandAt = 6;
			let isScrolled = header.classList.contains("is-scrolled") || document.body.classList.contains("has-scrolled-header");
			let ticking = false;
			const update = () => {
				const scrollY = window.scrollY || window.pageYOffset || 0;
				const nextIsScrolled = isScrolled ? scrollY > expandAt : scrollY > compactAt;

				if (nextIsScrolled !== isScrolled) {
					isScrolled = nextIsScrolled;
					header.classList.toggle("is-scrolled", isScrolled);
					document.body.classList.toggle("has-scrolled-header", isScrolled);
				}
				ticking = false;
			};
			const queueUpdate = () => {
				if (ticking) {
					return;
				}

				ticking = true;
				window.requestAnimationFrame(update);
			};

			update();
			window.addEventListener("scroll", queueUpdate, { passive: true });
			window.addEventListener("resize", queueUpdate);
		})();';

		wp_register_script(
			$handle,
			false,
			array(),
			wp_get_theme()->get( 'Version' ),
			true
		);
		wp_enqueue_script( $handle );
		wp_add_inline_script( $handle, $script );
	}
endif;
add_action( 'wp_enqueue_scripts', 'theme_gite_broceliande_wp_enqueue_header_script' );

// Adds a per-page navigation color setting for the transparent header template.
if ( ! function_exists( 'theme_gite_broceliande_wp_transparent_header_settings' ) ) :
	function theme_gite_broceliande_wp_transparent_header_settings() {
		register_post_meta(
			'page',
			'_gb_transparent_header_text_color',
			array(
				'type'              => 'string',
				'single'            => true,
				'default'           => '',
				'sanitize_callback' => 'sanitize_hex_color',
				'show_in_rest'      => true,
				'auth_callback'     => static function () {
					return current_user_can( 'edit_pages' );
				},
			)
		);
	}
endif;
add_action( 'init', 'theme_gite_broceliande_wp_transparent_header_settings' );

if ( ! function_exists( 'theme_gite_broceliande_wp_enqueue_transparent_header_editor_settings' ) ) :
	function theme_gite_broceliande_wp_enqueue_transparent_header_editor_settings() {
		$screen = get_current_screen();
		if ( ! $screen || 'page' !== $screen->post_type ) {
			return;
		}

		wp_enqueue_script(
			'theme-gite-broceliande-wp-transparent-header-settings',
			get_theme_file_uri( 'assets/js/transparent-header-settings.js' ),
			array( 'wp-block-editor', 'wp-data', 'wp-edit-post', 'wp-element', 'wp-i18n', 'wp-plugins' ),
			wp_get_theme()->get( 'Version' ),
			true
		);
	}
endif;
add_action( 'enqueue_block_editor_assets', 'theme_gite_broceliande_wp_enqueue_transparent_header_editor_settings' );

if ( ! function_exists( 'theme_gite_broceliande_wp_transparent_header_color' ) ) :
	function theme_gite_broceliande_wp_transparent_header_color() {
		if ( ! is_page_template( 'page-transparent-header' ) ) {
			return;
		}

		$color = sanitize_hex_color( (string) get_post_meta( get_queried_object_id(), '_gb_transparent_header_text_color', true ) );
		if ( ! $color ) {
			return;
		}

		wp_add_inline_style(
			'theme-gite-broceliande-wp-style',
			sprintf( 'body.page-template-page-transparent-header{--gb-transparent-header-text-color:%s;}', $color )
		);
	}
endif;
add_action( 'wp_enqueue_scripts', 'theme_gite_broceliande_wp_transparent_header_color', 20 );

// Registers custom block styles.
if ( ! function_exists( 'theme_gite_broceliande_wp_block_styles' ) ) :
	/**
	 * Registers custom block styles.
	 *
	 * @since Gites Broceliande 1.0
	 *
	 * @return void
	 */
	function theme_gite_broceliande_wp_block_styles() {
		register_block_style(
			'core/list',
			array(
				'name'         => 'checkmark-list',
				'label'        => __( 'Checkmark', 'theme-gite-broceliande-wp' ),
				'inline_style' => '
				ul.is-style-checkmark-list {
					list-style-type: "\2713";
				}

				ul.is-style-checkmark-list li {
					padding-inline-start: 1ch;
				}',
			)
		);

		$paper_sheet_blocks = array(
			'core/group',
			'core/columns',
			'core/column',
			'core/paragraph',
			'core/heading',
			'core/list',
			'core/quote',
			'core/image',
			'core/media-text',
		);

		foreach ( $paper_sheet_blocks as $block_name ) {
			register_block_style(
				$block_name,
				array(
					'name'  => 'paper-sheet',
					'label' => __( 'Feuille de papier', 'theme-gite-broceliande-wp' ),
				)
			);
		}
	}
endif;
add_action( 'init', 'theme_gite_broceliande_wp_block_styles' );

// Registers pattern categories.
if ( ! function_exists( 'theme_gite_broceliande_wp_pattern_categories' ) ) :
	/**
	 * Registers pattern categories.
	 *
	 * @since Gites Broceliande 1.0
	 *
	 * @return void
	 */
	function theme_gite_broceliande_wp_pattern_categories() {

		register_block_pattern_category(
			'theme-gite-broceliande-wp_page',
			array(
				'label'       => __( 'Pages', 'theme-gite-broceliande-wp' ),
				'description' => __( 'A collection of full page layouts.', 'theme-gite-broceliande-wp' ),
			)
		);

		register_block_pattern_category(
			'theme-gite-broceliande-wp_post-format',
			array(
				'label'       => __( 'Post formats', 'theme-gite-broceliande-wp' ),
				'description' => __( 'A collection of post format patterns.', 'theme-gite-broceliande-wp' ),
			)
		);
	}
endif;
add_action( 'init', 'theme_gite_broceliande_wp_pattern_categories' );

// Registers block binding sources.
if ( ! function_exists( 'theme_gite_broceliande_wp_register_block_bindings' ) ) :
	/**
	 * Registers the post format block binding source.
	 *
	 * @since Gites Broceliande 1.0
	 *
	 * @return void
	 */
	function theme_gite_broceliande_wp_register_block_bindings() {
		register_block_bindings_source(
			'theme-gite-broceliande-wp/format',
			array(
				'label'              => _x( 'Post format name', 'Label for the block binding placeholder in the editor', 'theme-gite-broceliande-wp' ),
				'get_value_callback' => 'theme_gite_broceliande_wp_format_binding',
			)
		);
	}
endif;
add_action( 'init', 'theme_gite_broceliande_wp_register_block_bindings' );

// Registers block binding callback function for the post format name.
if ( ! function_exists( 'theme_gite_broceliande_wp_format_binding' ) ) :
	/**
	 * Callback function for the post format name block binding source.
	 *
	 * @since Gites Broceliande 1.0
	 *
	 * @return string|void Post format name, or nothing if the format is 'standard'.
	 */
function theme_gite_broceliande_wp_format_binding() {
	$post_format_slug = get_post_format();

	if ( $post_format_slug && 'standard' !== $post_format_slug ) {
		return get_post_format_string( $post_format_slug );
	}
}
endif;
