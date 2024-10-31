<?php
 if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://wpspins.com/
 * @since      1.0.0
 *
 * @package    Wpspins
 * @subpackage Wpspins/classes
 */

/**
 * The public-facing functionality of the plugin.
 */
class wpx_clz_RPR_Public {

	/**
	 * Initialize the class and set its properties.
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'woocommerce_after_checkout_validation', array( $this, 'wpspins_shipping_restrict_checkout_state' ) );
	}
	/**
	 * Restrict checkout based on ZIP code or region on checkout page.
	 *
	 * @return void
	 */
	public function wpspins_shipping_restrict_checkout_state() {
		$restricted_zipcodes = get_option( WPX_CLZ_PLUGIN_NAME . '_restricted_zipcodes' );
		$zipcodes_array      = array_map( 'trim', explode( ',', $restricted_zipcodes ) );
		$restriction_type    = get_option( WPX_CLZ_PLUGIN_NAME . '_restrict_based_on' );
		$restricted_regions  = get_option( WPX_CLZ_PLUGIN_NAME . '_zone_regions' );
		// Get the custom error message.
		$wpx_clz_checkout_message = get_option( WPX_CLZ_PLUGIN_NAME . '_error', null );
		if ( empty( $wpx_clz_checkout_message ) ) {
			$wpx_clz_checkout_message = __( 'We are sorry for the inconvenience, we are unable to ship to this address!', 'wpspins-rpr' );
		} else {
			$wpx_clz_checkout_message = str_replace( '{state}', WC()->customer->get_shipping_state(), $wpx_clz_checkout_message );
			$wpx_clz_checkout_message = str_replace( '{country}', WC()->customer->get_shipping_country(), $wpx_clz_checkout_message );
			$wpx_clz_checkout_message = str_replace( '{zipcode}', WC()->customer->get_shipping_postcode(), $wpx_clz_checkout_message );
		}
		$error_added = false;
		// Check ZIP code restrictions for billing.
		if ( ! $error_added &&
		( 'both' === $restriction_type || 'billing' === $restriction_type ) &&
		in_array( WC()->customer->get_billing_postcode(), $zipcodes_array, true ) ) {
			wc_add_notice( $wpx_clz_checkout_message, 'error' );
			$error_added = true;
		}
		// Check ZIP code restrictions for shipping.
		if ( ! $error_added &&
		( 'both' === $restriction_type || 'shipping' === $restriction_type ) &&
		in_array( WC()->customer->get_shipping_postcode(), $zipcodes_array, true ) ) {
			wc_add_notice( $wpx_clz_checkout_message, 'error' );
			$error_added = true;
		}
		// Check region restrictions for billing.
		if ( ! $error_added &&
		( 'both' === $restriction_type || 'billing' === $restriction_type ) &&
			( in_array( 'country:' . WC()->customer->get_billing_country(), $restricted_regions, true ) ||
			in_array( 'state:' . WC()->customer->get_billing_country() . ':' . WC()->customer->get_billing_state(), $restricted_regions, true ) ) ) {
			wc_add_notice( $wpx_clz_checkout_message, 'error' );
			$error_added = true;
		}
		// Check region restrictions for shipping.
		if ( ! $error_added && ( 'both' === $restriction_type || 'shipping' === $restriction_type ) &&
			( in_array( 'country:' . WC()->customer->get_shipping_country(), $restricted_regions, true ) ||
			in_array( 'state:' . WC()->customer->get_shipping_country() . ':' . WC()->customer->get_shipping_state(), $restricted_regions, true ) ) ) {
			wc_add_notice( $wpx_clz_checkout_message, 'error' );
			$error_added = true;
		}
	}
}

new wpx_clz_RPR_Public();
