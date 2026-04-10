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

		$this->add_control(
			'button_text',
			[
				'label'   => esc_html__( 'Button text', 'dwl-vibe-test' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Choose Plan', 'dwl-vibe-test' ),
			]
		);

		$this->add_control(
			'feature_icon',
			[
				'label'       => esc_html__( 'Feature icon', 'dwl-vibe-test' ),
				'type'        => \Elementor\Controls_Manager::ICONS,
				'default'     => [
					'value'   => 'fas fa-check',
					'library' => 'fa-solid',
				],
				'skin'        => 'inline',
				'label_block' => false,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_typography',
			[
				'label' => esc_html__( 'Typography', 'dwl-vibe-test' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'label'    => esc_html__( 'Title Typography', 'dwl-vibe-test' ),
				'selector' => '{{WRAPPER}} .dwl-pricing-title',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'plan_typography',
				'label'    => esc_html__( 'Plan Typography', 'dwl-vibe-test' ),
				'selector' => '{{WRAPPER}} .dwl-pricing-plan-name, {{WRAPPER}} .dwl-pricing-plan-price, {{WRAPPER}} .dwl-pricing-features li, {{WRAPPER}} .dwl-pricing-plan-btn',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_layout',
			[
				'label' => esc_html__( 'Layout', 'dwl-vibe-test' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'cards_columns',
			[
				'label'          => esc_html__( 'Cards per row', 'dwl-vibe-test' ),
				'type'           => \Elementor\Controls_Manager::SELECT,
				'options'        => [
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
				],
				'desktop_default' => '3',
				'tablet_default'  => '2',
				'mobile_default'  => '1',
				'selectors'      => [
					'{{WRAPPER}} .dwl-pricing-plans' => 'grid-template-columns: repeat({{VALUE}}, minmax(0, 1fr));',
				],
			]
		);

		$this->add_responsive_control(
			'cards_gap',
			[
				'label'          => esc_html__( 'Cards gap', 'dwl-vibe-test' ),
				'type'           => \Elementor\Controls_Manager::SLIDER,
				'size_units'     => [ 'px' ],
				'range'          => [
					'px' => [
						'min' => 0,
						'max' => 60,
					],
				],
				'desktop_default' => [
					'size' => 22,
					'unit' => 'px',
				],
				'tablet_default' => [
					'size' => 18,
					'unit' => 'px',
				],
				'mobile_default' => [
					'size' => 14,
					'unit' => 'px',
				],
				'selectors'      => [
					'{{WRAPPER}} .dwl-pricing-plans' => 'gap: {{SIZE}}{{UNIT}};',
				],
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
		$button_text     = isset( $settings['button_text'] ) ? $settings['button_text'] : esc_html__( 'Choose Plan', 'dwl-vibe-test' );
		$feature_icon    = isset( $settings['feature_icon'] ) ? $settings['feature_icon'] : [];

		echo '<div class="dwl-pricing-table-wrapper">';
		echo '<div class="dwl-pricing-shell">';

		if ( '' !== $title ) {
			echo '<h2 class="dwl-pricing-title">' . esc_html( $title ) . '</h2>';
		}
		

		if ( '' !== $error && empty( $plans ) ) {
			echo '<p class="dwl-pricing-error" role="alert">' . esc_html( $error ) . '</p>';
			echo '</div>';
			echo '</div>';
			return;
		}

		if ( empty( $plans ) ) {
			echo '<p class="dwl-pricing-empty">' . esc_html__( 'No pricing plans available.', 'dwl-vibe-test' ) . '</p>';
			echo '</div>';
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
					echo '<li>';
					if ( ! empty( $feature_icon['value'] ) ) {
						echo '<span class="dwl-pricing-feature-icon" aria-hidden="true">';
						\Elementor\Icons_Manager::render_icon( $feature_icon, [ 'aria-hidden' => 'true' ] );
						echo '</span>';
					}
					echo '<span class="dwl-pricing-feature-text">' . esc_html( $feature ) . '</span></li>';
				}
				echo '</ul>';
			}
			echo '<a href="#" class="dwl-pricing-plan-btn" aria-label="' . esc_attr__( 'Choose pricing plan', 'dwl-vibe-test' ) . '">' . esc_html( $button_text ) . '</a>';

			echo '</div></li>';
		}
		echo '</ul>';

		if ( '' !== $error ) {
			echo '<p class="dwl-pricing-notice">' . esc_html( $error ) . '</p>';
		}

		echo '</div>';
		echo '</div>';
	}
}