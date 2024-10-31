<?php
 if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


/**
 * WooCommerce class
 *
 * @link       https://wpspins.com/
 * @since      1.0.0
 *
 * @package    Wpspins
 * @subpackage Wpspins/classes
 */

/**
 * wpx_clz_RPR_WooCommerce
 */
class wpx_clz_RPR_WooCommerce {
	/**
	 * Initialize the class and set its properties.
	 *
	 * @return void
	 */
	public function __construct() {
		self::set_filters();
	}
	/**
	 * Set filters
	 *
	 * @return void
	 */
	public function set_filters() {
		add_filter( 'woocommerce_get_sections_shipping', array( $this, 'wpspins_shipping_section_tab' ) );
		add_filter( 'woocommerce_get_settings_shipping', array( $this, 'wpspins_shipping_section_content' ), 10, 2 );
	}

	/**
	 * Restrict checkout based on ZIP code or region on checkout page.
	 *
	 * @param  array $section WooCommerce section.
	 * @return array
	 */
	public function wpspins_shipping_section_tab( $section ) {
		$section[ WPX_CLZ_PLUGIN_NAME ] = WPX_CLZ_NAME;
		return $section;
	}

	/**
	 * Restrict checkout based on ZIP code or region on checkout page.
	 *
	 * @param  array  $settings WooCommerce settings.
	 * @param  string $current_section WooCommerce current section.
	 * @return array
	 */
	public function wpspins_shipping_section_content( $settings, $current_section ) {
		if ( WPX_CLZ_PLUGIN_NAME === $current_section ) {
			$settings_zipcodes   = array();
			$settings_zipcodes[] = array(
				'name' => WPX_CLZ_NAME,
				'type' => 'title',
				'id'   => WPX_CLZ_PLUGIN_NAME,
			);
			$settings_zipcodes[] = array(
				'name'     => __( 'Error Message', 'wpspins-rpr' ),
				'desc_tip' => __( 'Error message to be appear on Checkout.', 'wpspins-rpr' ),
				'id'       => WPX_CLZ_PLUGIN_NAME . '_error',
				'type'     => 'text',
				'desc'     => '<b>Default Error Message: </b>' . __( 'We are sorry for the inconvenience, but due to your state labeling laws, we are unable to ship our products into it!', 'wpspins-rpr' ) . '<br><br>' .
								'<b>Available Placeholders:</b><br>' .
								'{state} - Will be replaced by the state name.<br>' .
								'{country} - Will be replaced by the country name.<br>' .
								'{zipcode} - Will be replaced by the ZIP code.',
				'default'  => __( 'We are sorry for the inconvenience, but due to your state labeling laws, we are unable to ship our products into it!', 'wpspins-rpr' ),
				'css'      => 'width:80%',
			);
			$zones               = WC_Shipping_Zones::get_zones();
			$zone_options        = array();
			$shipping_continents = WC()->countries->get_continents();
			$allowed_countries   = WC()->countries->get_allowed_countries();

			foreach ( $shipping_continents as $continent_code => $continent ) {
				$zone_options[ 'continent:' . $continent_code ] = $continent['name'];
				$countries                                      = array_intersect( array_keys( $allowed_countries ), $continent['countries'] );
				foreach ( $countries as $country_code ) {
					$zone_options[ 'country:' . $country_code ] = '&nbsp;&nbsp; ' . $allowed_countries[ $country_code ];
					$states                                     = WC()->countries->get_states( $country_code );
					if ( $states ) {
						foreach ( $states as $state_code => $state_name ) {
							$zone_options[ 'state:' . $country_code . ':' . $state_code ] = '&nbsp;&nbsp;&nbsp;&nbsp; ' . $state_name . ', ' . $allowed_countries[ $country_code ];
						}
					}
				}
			}
			$settings_zipcodes[] = array(
				'name'    => __( 'Zone regions', 'wpspins-rpr' ),
				'id'      => WPX_CLZ_PLUGIN_NAME . '_zone_regions',
				'type'    => 'multiselect',
				'class'   => 'chosen_select',
				'options' => $zone_options,
				'desc'    => __( 'Select the regions within this zone.', 'wpspins-rpr' ),
			);
			$settings_zipcodes[] = array(
				'name' => __( 'Restricted ZIP Codes', 'wpspins-rpr' ),
				'id'   => WPX_CLZ_PLUGIN_NAME . '_restricted_zipcodes',
				'type' => 'textarea',
				'desc' => __( 'Enter ZIP codes where shipping is restricted, separated by commas.', 'wpspins-rpr' ),
				'css'  => 'width:80%; height: 100px;',
			);
			$settings_zipcodes[] = array(
				'name'    => __( 'Restrict Based On', 'wpspins-rpr' ),
				'id'      => WPX_CLZ_PLUGIN_NAME . '_restrict_based_on',
				'type'    => 'select',
				'options' => array(
					'both'     => __( 'Both Billing and Shipping Addresses', 'wpspins-rpr' ),
					'billing'  => __( 'Only Billing Address', 'wpspins-rpr' ),
					'shipping' => __( 'Only Shipping Address', 'wpspins-rpr' ),
				),
				'desc'    => __( 'Choose whether to restrict based on billing, shipping, or both addresses.', 'wpspins-rpr' ),
				'default' => 'shipping',
			);
			$settings_zipcodes[] = array(
				'type' => 'sectionend',
				'id'   => WPX_CLZ_PLUGIN_NAME,
			);
			return $settings_zipcodes;
		}
		return $settings;
	}
}
$GLOBAL[ 'wc_shipping_calc' ] = new wpx_clz_RPR_WooCommerce();//phpcs:ignore
