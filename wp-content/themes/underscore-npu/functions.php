<?php
/**
 * underscore-npu functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package underscore-npu
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function underscore_npu_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on underscore-npu, use a find and replace
		* to change 'underscore-npu' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'underscore-npu', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support( 'title-tag' );

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'menu-1' => esc_html__( 'Primary', 'underscore-npu' ),
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'underscore_npu_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action( 'after_setup_theme', 'underscore_npu_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function underscore_npu_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'underscore_npu_content_width', 640 );
}
add_action( 'after_setup_theme', 'underscore_npu_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function underscore_npu_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'underscore-npu' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'underscore-npu' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'underscore_npu_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function underscore_npu_scripts() {
	wp_enqueue_style( 'underscore-npu-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_style_add_data( 'underscore-npu-style', 'rtl', 'replace' );

	wp_enqueue_script( 'underscore-npu-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'underscore_npu_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

/**
 * NPU 커스텀 포스트 타입 등록
 * 'FAQs'와 'Notices'를 등록하고, REST API 및 GraphQL에 노출시킵니다.
 */
function npu_register_post_types() {

    // FAQ Post Type
    register_post_type('faqs',
        array(
            'labels'      => array(
                'name'          => __('FAQs'),
                'singular_name' => __('FAQ'),
            ),
            'public'      => true,
            'has_archive' => true,
            'show_in_rest' => true, // REST API에 표시 (Gutenberg 편집기 사용)
            'show_in_graphql' => true,
            'graphql_single_name' => 'FAQ',
            'graphql_plural_name' => 'FAQs',
            'supports' => array('title', 'editor', 'author'),
        )
    );

    // Notices Post Type
    register_post_type('notices',
        array(
            'labels'      => array(
                'name'          => __('공지사항'),
                'singular_name' => __('공지사항'),
            ),
            'public'      => true,
            'has_archive' => true,
            'show_in_rest' => true, // REST API에 표시
            'show_in_graphql' => true,
            'graphql_single_name' => 'Notice',
            'graphql_plural_name' => 'Notices',
            'supports' => array('title', 'editor', 'author'),
        )
    );
}
add_action('init', 'npu_register_post_types');

/**
 * Next.js On-Demand Revalidation을 트리거합니다.
 */
function trigger_nextjs_revalidation($post_id, $post) {
    if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || is_int( wp_is_post_revision( $post_id ) ) || 'publish' !== $post->post_status ) {
        return;
    }
    $secret = getenv('REVALIDATION_TOKEN');
    if ( empty($secret) ) {
        error_log('Error: REVALIDATION_TOKEN is not set.');
        return;
    }
    $path = wp_parse_url( get_permalink($post_id), PHP_URL_PATH );
    if ( empty($path) ) {
        error_log('Error: Could not get post permalink path.');
        return;
    }
    $revalidate_url = 'http://nextjs:3000/api/revalidate?path=' . $path . '&secret=' . $secret;
    $response = wp_remote_get($revalidate_url);
    if ( is_wp_error($response) ) {
        error_log('Revalidation failed: ' . $response->get_error_message());
    }
    else {
        $response_code = wp_remote_retrieve_response_code($response);
        $response_body = wp_remote_retrieve_body($response);
        error_log('Revalidation triggered for path ' . $path . '. Status: ' . $response_code . '. Body: ' . $response_body);
    }
}
add_action('save_post', 'trigger_nextjs_revalidation', 10, 2);