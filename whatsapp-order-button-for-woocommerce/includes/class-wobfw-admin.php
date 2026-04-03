<?php
/**
 * Handles the admin settings page.
 *
 * @package WhatsApp_Order_Button
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Security check.
}

class WOBFW_Admin {

    /**
     * Registers the plugin settings menu in the WordPress administrative dashboard.
     */
    public function add_plugin_admin_menu() {
        add_menu_page(
            __( 'WhatsApp Order Settings', 'wobfw' ), 
            __( 'WhatsApp Order', 'wobfw' ), 
            'manage_options', 
            'wobfw-settings', 
            array( $this, 'display_settings_page' ), 
            'dashicons-whatsapp', 
            56
        );
    }

    /**
     * Renders the settings page and handles form submissions.
     */
    public function display_settings_page() {
        // Handle form submission.
        if ( isset( $_POST['wobfw_save'] ) && current_user_can( 'manage_options' ) ) {
            
            // Verify nonce for security purposes.
            if ( isset( $_POST['wobfw_nonce'] ) && wp_verify_nonce( $_POST['wobfw_nonce'], 'wobfw_save_settings' ) ) {
                
                // Sanitize and save data.
                $phone         = preg_replace( '/[^0-9]/', '', sanitize_text_field( $_POST['wobfw_phone'] ) );
                $show_floating = isset( $_POST['wobfw_show_floating'] ) ? 'yes' : 'no';

                update_option( 'wobfw_phone', $phone );
                update_option( 'wobfw_msg', sanitize_textarea_field( wp_unslash( $_POST['wobfw_msg'] ) ) );
                update_option( 'wobfw_show_floating', $show_floating );
                
                echo '<div class="updated"><p>Settings successfully saved.</p></div>';
            }
        }
        
        // Retrieve current options.
        $phone         = get_option( 'wobfw_phone', '' );
        $msg           = get_option( 'wobfw_msg', "Hello,\nI require assistance with my purchase." );
        $show_floating = get_option( 'wobfw_show_floating', 'yes' );
        ?>
        <div class="wrap">
            <h1>⚙️ WhatsApp Order Configuration</h1>
            <p>Configure your telephone number to begin receiving inquiries and product orders directly via WhatsApp.</p>
            
            <form method="post" style="background: #fff; padding: 20px; border: 1px solid #ccc; max-width: 700px; margin-bottom: 30px;">
                <?php wp_nonce_field( 'wobfw_save_settings', 'wobfw_nonce' ); ?>
                <table class="form-table">
                    <tr>
                        <th>Telephone (include country code, e.g., 34600000000)</th>
                        <td>
                            <input type="text" name="wobfw_phone" value="<?php echo esc_attr( $phone ); ?>" class="regular-text">
                            <p class="description"><strong>Privacy Notice:</strong> We do not collect or store personal data.</p>
                        </td>
                    </tr>
                    <tr>
                        <th>Default Message</th>
                        <td>
                            <textarea name="wobfw_msg" rows="4" class="large-text"><?php echo esc_textarea( $msg ); ?></textarea>
                            <p class="description">You can use line breaks here.</p>
                        </td>
                    </tr>
                    <tr>
                        <th>Global Floating Button</th>
                        <td>
                            <label>
                                <input type="checkbox" name="wobfw_show_floating" value="yes" <?php checked( $show_floating, 'yes' ); ?>>
                                Display the floating WhatsApp button in the bottom right corner of all pages.
                            </label>
                        </td>
                    </tr>
                </table>
                <p><input type="submit" name="wobfw_save" class="button button-primary" value="Save Changes"></p>
            </form>

            <!-- PRO Upsell Section -->
            <div style="background: #fff3cd; padding: 20px; border-left: 4px solid #ffc107; border-radius: 4px; max-width: 660px;">
                <h2 style="margin-top: 0; color: #856404;">🚀 Upgrade to WhatCart PRO</h2>
                <p style="color: #856404;">You are currently using the Lite version. Upgrade to PRO to turn WhatsApp into a fully automated sales machine:</p>
                <ul style="list-style-type: disc; margin-left: 20px; color: #856404; font-weight: 500;">
                    <li><strong>Full Cart Retrieval:</strong> Automatically send the entire cart contents, quantities, and total price.</li>
                    <li><strong>Checkout & Cart Buttons:</strong> Capture sales at the last moment on Cart and Checkout pages.</li>
                    <li><strong>Smart QR Codes:</strong> Desktop users see a beautiful QR code to scan with their phones.</li>
                </ul>
                <p style="margin-top: 20px;">
                    <a href="https://guapsie.dev/whatsapp-order-button-for-woocommerce" target="_blank" class="button button-primary" style="background: #25D366; border-color: #1DA851; text-shadow: none;">Get WhatCart PRO Now</a>
                </p>
            </div>
        </div>
        <?php
    }
}