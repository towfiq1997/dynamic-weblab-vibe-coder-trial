<?php
/**
 * Pricing API fetch with transient cache.
 *
 * @package DWL_Vibe_Test
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Default URL for bundled mock pricing JSON.
 *
 * @return string
 */
function dwl_vibe_default_pricing_api_url() {
	return plugins_url( 'mock-api/pricing.json', DWL_VIBE_PLUGIN_DIR . 'vibe-test.php' );
}

/**
 * Fetch pricing plans from a remote JSON endpoint, cached in a transient.
 *
 * @param string $api_url   Full URL to pricing.json (empty uses default mock URL).
 * @param int    $cache_ttl Cache lifetime in seconds (minimum 60).
 * @return array{ plans: array<int, array<string, mixed>>, error?: string }
 */
function dwl_vibe_get_pricing_plans( $api_url, $cache_ttl ) {
	$api_url = $api_url ? esc_url_raw( $api_url ) : '';
	if ( '' === $api_url ) {
		$api_url = dwl_vibe_default_pricing_api_url();
	}

	$cache_ttl = absint( $cache_ttl );
	if ( $cache_ttl < 60 ) {
		$cache_ttl = HOUR_IN_SECONDS;
	}

	$transient_key = 'dwl_vibe_pricing_' . md5( $api_url );

	$cached = get_transient( $transient_key );
	if ( false !== $cached && is_array( $cached ) && isset( $cached['plans'] ) && is_array( $cached['plans'] ) ) {
		return $cached;
	}

	$response = wp_remote_get(
		$api_url,
		[
			'timeout' => 15,
			'headers' => [
				'Accept' => 'application/json',
			],
		]
	);

	if ( is_wp_error( $response ) ) {
		return [
			'plans' => [],
			'error' => $response->get_error_message(),
		];
	}

	$code = wp_remote_retrieve_response_code( $response );
	if ( 200 !== (int) $code ) {
		return [
			'plans' => [],
			/* translators: %d: HTTP status code */
			'error' => sprintf( __( 'Pricing request failed (HTTP %d).', 'dwl-vibe-test' ), (int) $code ),
		];
	}

	$body = wp_remote_retrieve_body( $response );
	$data = json_decode( $body, true );

	if ( ! is_array( $data ) || ! isset( $data['plans'] ) || ! is_array( $data['plans'] ) ) {
		return [
			'plans' => [],
			'error' => __( 'Invalid pricing data format.', 'dwl-vibe-test' ),
		];
	}

	$plans = [];
	foreach ( $data['plans'] as $plan ) {
		if ( ! is_array( $plan ) ) {
			continue;
		}
		$features = [];
		if ( isset( $plan['features'] ) && is_array( $plan['features'] ) ) {
			foreach ( $plan['features'] as $feature ) {
				$features[] = sanitize_text_field( (string) $feature );
			}
		}
		$plans[] = [
			'name'     => isset( $plan['name'] ) ? sanitize_text_field( (string) $plan['name'] ) : '',
			'price'    => isset( $plan['price'] ) ? (float) $plan['price'] : 0.0,
			'features' => $features,
			'popular'  => ! empty( $plan['popular'] ),
		];
	}

	$normalized = [ 'plans' => $plans ];
	set_transient( $transient_key, $normalized, $cache_ttl );

	return $normalized;
}
