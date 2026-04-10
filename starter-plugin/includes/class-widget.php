<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Custom Elementor Pricing Widget
 */
class Dwl_Vibe_Pricing_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'dwl_vibe_pricing';
	}

	public function get_title() {
		return esc_html__( 'DWL Dynamic Pricing', 'dwl-vibe-test' );
	}

	public function get_icon() {
		return 'eicon-price-table';
	}

	public function get_style_depends(): array {
		return [ 'dwl-vibe-pricing-style' ];
	}

	public function get_categories() {
		return [ 'general' ];
	}

	/**
	 * Register widget controls (Content & Style).
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'Content', 'dwl-vibe-test' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'title',
			[
				'label'       => esc_html__( 'Title', 'dwl-vibe-test' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => esc_html__( 'Choose your plan', 'dwl-vibe-test' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'api_url',
			[
				'label'       => esc_html__( 'Pricing JSON URL', 'dwl-vibe-test' ),
				'type'        => \Elementor\Controls_Manager::URL,
				'placeholder' => dwl_vibe_default_pricing_api_url(),
				'default'     => [
					'url'               => '',
					'is_external'       => false,
					'nofollow'          => false,
					'custom_attributes' => '',
				],
				'description' => esc_html__( 'Leave empty to use the bundled mock-api/pricing.json.', 'dwl-vibe-test' ),
			]
		);

		$this->add_control(
			'cache_ttl',
			[
				'label'   => esc_html__( 'Cache duration', 'dwl-vibe-test' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => [
					(string) ( 5 * MINUTE_IN_SECONDS )  => esc_html__( '5 minutes', 'dwl-vibe-test' ),
					(string) ( 15 * MINUTE_IN_SECONDS ) => esc_html__( '15 minutes', 'dwl-vibe-test' ),
					(string) HOUR_IN_SECONDS            => esc_html__( '1 hour', 'dwl-vibe-test' ),
					(string) ( 6 * HOUR_IN_SECONDS )    => esc_html__( '6 hours', 'dwl-vibe-test' ),
					(string) DAY_IN_SECONDS             => esc_html__( '24 hours', 'dwl-vibe-test' ),
				],
				'default' => (string) HOUR_IN_SECONDS,
			]
		);

		$this->add_control(
			'currency_symbol',
			[
				'label'   => esc_html__( 'Currency symbol', 'dwl-vibe-test' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => '$',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render widget output on the frontend.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$api_url = isset( $settings['api_url']['url'] ) ? $settings['api_url']['url'] : '';
		$ttl     = isset( $settings['cache_ttl'] ) ? (int) $settings['cache_ttl'] : HOUR_IN_SECONDS;

		$result = dwl_vibe_get_pricing_plans( $api_url, $ttl );
		$plans  = isset( $result['plans'] ) ? $result['plans'] : [];
		$error  = isset( $result['error'] ) ? $result['error'] : '';

		$title           = isset( $settings['title'] ) ? $settings['title'] : '';
		$currency_symbol = isset( $settings['currency_symbol'] ) ? $settings['currency_symbol'] : '$';

		echo '<div class="dwl-pricing-table-wrapper">';

		if ( '' !== $title ) {
			echo '<h2 class="dwl-pricing-title">' . esc_html( $title ) . '</h2>';
		}

		if ( '' !== $error && empty( $plans ) ) {
			echo '<p class="dwl-pricing-error" role="alert">' . esc_html( $error ) . '</p>';
			echo '</div>';
			return;
		}

		if ( empty( $plans ) ) {
			echo '<p class="dwl-pricing-empty">' . esc_html__( 'No pricing plans available.', 'dwl-vibe-test' ) . '</p>';
			echo '</div>';
			return;
		}

		echo '<ul class="dwl-pricing-plans" role="list">';
		foreach ( $plans as $plan ) {
			$classes = 'dwl-pricing-plan';
			if ( ! empty( $plan['popular'] ) ) {
				$classes .= ' is-popular';
			}
			echo '<li class="' . esc_attr( $classes ) . '">';
			echo '<div class="dwl-pricing-plan-inner">';

			if ( '' !== $plan['name'] ) {
				echo '<h3 class="dwl-pricing-plan-name">' . esc_html( $plan['name'] ) . '</h3>';
			}

			$price_fmt = number_format_i18n( (float) $plan['price'], 2 );
			echo '<p class="dwl-pricing-plan-price"><span class="dwl-pricing-currency">' . esc_html( $currency_symbol ) . '</span>';
			echo '<span class="dwl-pricing-amount">' . esc_html( $price_fmt ) . '</span></p>';

			if ( ! empty( $plan['features'] ) ) {
				echo '<ul class="dwl-pricing-features" role="list">';
				foreach ( $plan['features'] as $feature ) {
					echo '<li>' . esc_html( $feature ) . '</li>';
				}
				echo '</ul>';
			}

			echo '</div></li>';
		}
		echo '</ul>';

		if ( '' !== $error ) {
			echo '<p class="dwl-pricing-notice">' . esc_html( $error ) . '</p>';
		}

		echo '</div>';
	}
}
