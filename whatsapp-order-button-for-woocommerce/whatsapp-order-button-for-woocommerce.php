<?php
/**
 * Plugin Name:       WhatsApp Order Button for WooCommerce
 * Plugin URI:        https://guapsie.dev/whatsapp-order-button-for-woocommerce
 * Description:       Add a WhatsApp order button to your WooCommerce products and a floating chat widget. Upgrade to Pro for full cart details and QR codes.
 * Version:           1.0.0
 * Author:            guapsie
 * Author URI:        https://guapsie.dev
 * License:           GPLv2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       wobfw
 * Requires at least: 6.0
 * Requires PHP:      7.4
 *
 * @package           WhatsApp_Order_Button
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Security check.
}

define( 'WOBFW_VERSION', '1.0.0' );
define( 'WOBFW_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

/**
 * The core plugin class to wire up the admin and frontend.
 */
class WOBFW_Core {

    public function __construct() {
        $this->load_dependencies();
        $this->init_hooks();
    }

    private function load_dependencies() {
        require_once WOBFW_PLUGIN_DIR . 'includes/class-wobfw-admin.php';
        require_once WOBFW_PLUGIN_DIR . 'includes/class-wobfw-frontend.php';
    }

    private function init_hooks() {
        // Initialise Admin context.
        $admin = new WOBFW_Admin();
        add_action( 'admin_menu', array( $admin, 'add_plugin_admin_menu' ) );

        // Initialise Frontend context.
        $frontend = new WOBFW_Frontend();
        
        // Elegant layout: Positioned beneath the add to cart form for better visibility.
        add_action( 'woocommerce_after_add_to_cart_form', array( $frontend, 'add_product_button' ) );
        add_action( 'wp_footer', array( $frontend, 'render_floating_button' ) );
    }
}

// Initialise the plugin.
function wobfw_run_plugin() {
    new WOBFW_Core();
}
wobfw_run_plugin();