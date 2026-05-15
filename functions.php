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
