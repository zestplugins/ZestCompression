<?php
/**
 * Plugin Name: ZestCompression
 * Description: Optimize your website's performance with the best image compression plugin.
 * Version: 1.0.0
 * Author: zestplugins
 * Author URI: https://github.com/zestplugins
 * License: GPL-3.0+
 * License URI: http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain: zest-compression
 * 
 * @link              https://github.com/zestplugins/ZestCompression
 * @since             1.0.0
 * @package           ZestCompression
 */

if ( ! defined( 'ABSPATH' ) ) {
 exit; // Exit if accessed directly.
}

// Define some essentials constants.
if ( ! defined( 'ZPIC_SLUG' ) ) {
    define( 'ZPIC_SLUG', 'zestcompression_plugin' );
    define( 'ZPIC_NAMESPACE', 'ZestPlugins\ZestCompression' );
    define( 'ZPIC_DIR', __DIR__ );
    define( 'ZPIC_PATH', plugin_dir_path( __FILE__ ) );
    define( 'ZPIC_URL', plugin_dir_url( __FILE__ ) );
    define( 'ZPIC_ASSETS_URL', ZPIC_URL . 'assets/' );
    define( 'ZPIC_FILE', __FILE__ );
    define( 'ZPIC_VERSION', '1.0.0' );
    define( 'ZPIC_MIN_PHP', '5.6.0' );
    define( 'ZPIC_MIN_WP', '6.0.0' );
}

// Activation hook
register_activation_hook( __FILE__, 'zest_compression_activation_check' );

/**
 * Activation hook callback to check minimum PHP and WordPress versions.
 */
function zest_compression_activation_check() {
    // Check PHP version
    if ( version_compare( PHP_VERSION, ZPIC_MIN_PHP, '<' ) ) {
        deactivate_plugins( plugin_basename( __FILE__ ) );
        wp_die( sprintf( esc_html__( 'Zest Compression requires PHP version %s or higher. Please upgrade PHP.', 'zest-compression' ), ZPIC_MIN_PHP ) );
    }

    // Check WordPress version
    if ( version_compare( get_bloginfo( 'version' ), ZPIC_MIN_WP, '<' ) ) {
        deactivate_plugins( plugin_basename( __FILE__ ) );
        wp_die( sprintf( esc_html__( 'Zest Compression requires WordPress version %s or higher. Please upgrade WordPress.', 'zest-compression' ), ZPIC_MIN_WP ) );
    }
}

// Include the main plugin class
require_once ZPIC_PATH . 'includes/class-zestcompression.php';

// Instantiate the class
$zest_compression_plugin = new ZestCompressionPlugin();
