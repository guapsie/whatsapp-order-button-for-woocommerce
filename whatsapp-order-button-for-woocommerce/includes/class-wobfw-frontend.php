<?php
/**
 * Handles the frontend display of the WhatsApp buttons.
 *
 * @package WhatsApp_Order_Button
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Security check.
}

class WOBFW_Frontend {

    /**
     * Generates the SVG icon for the WhatsApp button.
     *
     * @return string The SVG HTML markup.
     */
    private function get_svg_icon() {
        return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="22" height="22" fill="currentColor" style="vertical-align: middle; margin-right: 10px;"><path d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z"/></svg>';
    }

    /**
     * Constructs the WhatsApp API URL with the phone number and formatted message.
     *
     * @return string|false The formatted WhatsApp URL or false if no phone is set.
     */
    private function get_whatsapp_url() {
        $phone = get_option( 'wobfw_phone' );
        if ( empty( $phone ) ) {
            return false;
        }

        // Strip leading zeros for robust mobile compatibility.
        $phone = ltrim( $phone, '0' );

        $final_msg = get_option( 'wobfw_msg' );

        // Basic WooCommerce integration (Lite version functionality).
        if ( function_exists( 'is_product' ) && is_product() ) {
            global $product;
            if ( $product ) {
                $product_name = html_entity_decode( $product->get_name(), ENT_QUOTES | ENT_HTML5, 'UTF-8' );
                $final_msg .= "\n\nProduct: " . $product_name;
            }
        }

        $final_msg = str_replace( array( "\r\n", "\r" ), "\n", $final_msg );
        
        // Utilizing the reliable api.whatsapp.com protocol.
        return "https://api.whatsapp.com/send?phone=" . esc_attr( $phone ) . "&text=" . rawurlencode( $final_msg );
    }

    /**
     * Renders the product page button beneath the add to cart form.
     */
    public function add_product_button() {
        $url = $this->get_whatsapp_url();
        if ( ! $url ) {
            return;
        }
        
        $content = $this->get_svg_icon() . esc_html__( 'Order via WhatsApp', 'wobfw' );

        // Elegant layout: Full width, positioned below the purchase form.
        echo '<div class="wobfw-woo-wrapper" style="margin-top: 15px; width: 100%; clear: both; display: block;">';
        echo '<a href="' . esc_attr( $url ) . '" target="_blank" rel="noopener noreferrer" style="display: flex; align-items: center; justify-content: center; width: 100%; background: #25D366; color: #ffffff; padding: 14px 20px; font-size: 16px; font-weight: 600; border-radius: 6px; text-decoration: none; line-height: 1; transition: opacity 0.2s; box-shadow: 0 2px 4px rgba(37,211,102,0.3); box-sizing: border-box;">';
        echo $content;
        echo '</a>';
        echo '</div>';
    }

    /**
     * Renders the global floating widget.
     */
    public function render_floating_button() {
        if ( get_option( 'wobfw_show_floating', 'yes' ) !== 'yes' ) {
            return;
        }

        $wa_url = $this->get_whatsapp_url();
        if ( ! $wa_url ) {
            return; 
        }
        ?>
        <style>
            .wobfw-float-lite { 
                position: fixed; 
                bottom: 25px; 
                right: 25px; 
                background: #25D366; 
                color: #ffffff; 
                padding: 14px 28px; 
                border-radius: 50px; 
                z-index: 99999; 
                box-shadow: 0 6px 15px rgba(0,0,0,0.2); 
                text-decoration: none; 
                font-weight: 600; 
                font-size: 15px;
                display: flex; 
                align-items: center; 
                font-family: inherit; 
                transition: transform 0.2s ease, box-shadow 0.2s ease; 
                line-height: 1;
            }
            .wobfw-float-lite:hover { 
                transform: translateY(-3px); 
                box-shadow: 0 8px 20px rgba(0,0,0,0.25); 
                color: #ffffff; 
            }
        </style>
        <a href="<?php echo esc_attr( $wa_url ); ?>" class="wobfw-float-lite" target="_blank" rel="noopener noreferrer">
            <?php echo $this->get_svg_icon() . esc_html__( 'WhatsApp', 'wobfw' ); ?>
        </a>
        <?php
    }
}