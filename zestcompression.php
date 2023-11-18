<?php
/**
 * Plugin Name: Zest Compression
 * Description: Lossless image compression for JPEG, PNG, and GIF.
 * Version: 1.0
 * Author: zestplugins
 */

 if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Define some essentials constants.
if ( ! defined( 'ZPIC_SLUG' ) ) {
    define( 'ZPIC_SLUG', 'foogallery_frontend_uploads' );
    define( 'ZPIC_NAMESPACE', 'FooPlugins\FooGallery\FrontendUploads' );
    define( 'ZPIC_DIR', __DIR__ );
    define( 'ZPIC_PATH', plugin_dir_path( __FILE__ ) );
    define( 'ZPIC_URL', plugin_dir_url( __FILE__ ) );
    define( 'ZPIC_ASSETS_URL', ZPIC_URL . 'assets/' );
    define( 'ZPIC_FILE', __FILE__ );
    define( 'ZPIC_VERSION', '1.0.0' );
    define( 'ZPIC_MIN_PHP', '5.6.0' );
    define( 'ZPIC_MIN_WP', '6.0.0' );
}

require_once ZPIC_PATH . 'includes/class-zestcompression.php';