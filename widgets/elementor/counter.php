<?php
/*
Widget Name: TPaw Counter
Description: Counter box widget for Elementor
Author: themepaw.com
Author URI: https://themepaw.com/plugins/companion
*/

namespace ThemepawCompanion\Widgets\Elementor;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use  Elementor\utils;
use  Elementor\Scheme_Color;
use  Elementor\Group_Control_Typography;
use  Elementor\Scheme_Typography;
use  Elementor\Group_Control_Image_Size;
use  Elementor\Group_Control_Box_Shadow;
use  Elementor\Group_Control_Border;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Elementor Hello World
 *
 * Elementor widget for hello world.
 *
 * @since 1.0.0
 */
class TPaw_Counter extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'tpaw_counter';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Counter', 'themepaw-companion' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-counter';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'themepaw-addons' );
	}

	/**
	 * Retrieve the list of scripts the widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_script_depends() {
		return array( 'themepaw-companion' );
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function _register_controls() {

		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Content', 'themepaw-companion' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'list_style_type', [
				'label' => __( 'Icon Position', 'themepaw-companion' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'icon_left',
				'options' => [
					'icon_left'       => __( 'Icon/Image on Left', 'themepaw-companion' ),
					'icon_top'       => __( 'Icon/Image on Top', 'themepaw-companion' ),
					'icon_right' => __( 'Icon/Image on Right', 'themepaw-companion' ),
				]
			]
		);

		$this->add_responsive_control(
			'list_alignment',
			[
				'label' => esc_html__( 'Alignment', 'themepaw-companion' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => true,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'themepaw-companion' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'themepaw-companion' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'themepaw-companion' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'default' => 'left',
				'condition'   => [
                    'list_style_type' => 'icon_top',
				],
				'selectors' => [
					'{{WRAPPER}} .tpaw-companion-counter-wrapper' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'list_value', [
				'label' => __( 'Info Value', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( '10' , 'plugin-domain' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'list_title', [
				'label' => __( 'Info Title', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Info Title' , 'plugin-domain' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'list_icon_type', [
				'label' => __( 'Icon Type', 'themepaw-companion' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'icon',
				'options' => [
					'none'       => __( 'None', 'themepaw-companion' ),
					'icon'       => __( 'Icon', 'themepaw-companion' ),
					'image' => __( 'Icon Image', 'themepaw-companion' ),
				]
			]
		);

		$this->add_control(
			'list_image', [
                'label'       => __( 'Info Image', 'themepaw-companion' ),
                'type'        => Controls_Manager::MEDIA,
                'default'     => [
					'url' => THEMEPAW_COMPANION_PLACEHOLDER_ICON,
				],
				'label_block' => true,
                'condition'   => [
                    'list_icon_type' => 'image',
                ],
                'dynamic'     => [
                    'active' => true,
                ],
			]
		);

		$this->add_control(
			'list_icon', [
				'label'       => __( 'Info Icon', 'themepaw-companion' ),
                'type'        => Controls_Manager::ICON,
                'label_block' => true,
                'default'     => 'fa fa-trophy',
                'condition'   => [
					'list_icon_type' => 'icon',
				]
			]
		);

		$this->add_control(
			'list_minimum_height',
			[
				'label' => __( 'Minimum Height', 'themepaw-companion' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 5,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 200,
				],
				'selectors' => [
					'{{WRAPPER}} .tpaw-companion-single-counter' => 'min-height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			[
				'label' => __( 'Info Box', 'elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'infobox_background_color',
			[
				'label' => __( 'Background Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tpaw-companion-single-counter' => 'background-color: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'border',
				'selector' => '{{WRAPPER}} .tpaw-companion-single-counter',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'border_radius',
			[
				'label' => __( 'Border Radius', 'elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .tpaw-companion-single-counter, {{WRAPPER}} .tpaw-companion-single-counter' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .tpaw-companion-single-counter',
			]
		);

		$this->add_responsive_control(
			'text_padding',
			[
				'label' => __( 'Padding', 'elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .tpaw-companion-single-counter, {{WRAPPER}} .tpaw-companion-single-counter' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'icon_padding',
			[
				'label' => __( 'Icon Padding', 'themepaw-companion' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .tpaw-companion-counter-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};min-width: inherit;',
				],
				'separator' => 'before',
			]
        );

		$this->end_controls_section();

		$this->start_controls_section( 'section_list_title', [
            'label' => __( 'Title', 'themepaw-companion' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ] );

        $this->add_responsive_control(
			'title_bottom',
			[
				'label' => __( 'Spacing', 'themepaw-companion' ),
				'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em' ],
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 150,
                    ],
                ],
				'selectors' => [
					'{{WRAPPER}} .tpaw-companion-counter-content p' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				]
			]
        );
        
        $this->add_control( 'title_color', [
            'label'     => __( 'Color', 'themepaw-companion' ),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
				'{{WRAPPER}} .tpaw-companion-counter-content p' => 'color: {{VALUE}}'
			],
        ] );
        $this->add_group_control( Group_Control_Typography::get_type(), [
            'name'     => 'title_typography',
            'selector' => '{{WRAPPER}} .tpaw-companion-counter-content p',
        ] );
        $this->end_controls_section();
		
		
		$this->start_controls_section( 'section_list_number', [
            'label' => __( 'Number', 'themepaw-companion' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ] );

        $this->add_responsive_control(
			'number_bottom',
			[
				'label' => __( 'Spacing', 'themepaw-companion' ),
				'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em' ],
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 150,
                    ],
                ],
				'selectors' => [
					'{{WRAPPER}} .tpaw-companion-counter-content h2' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				]
			]
        );
        
        $this->add_control( 'number_color', [
            'label'     => __( 'Color', 'themepaw-companion' ),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
				'{{WRAPPER}} .tpaw-companion-counter-content h2' => 'color: {{VALUE}}'
			],
        ] );
        $this->add_group_control( Group_Control_Typography::get_type(), [
            'name'     => 'number_typography',
            'selector' => '{{WRAPPER}} .tpaw-companion-counter-content h2',
        ] );
		$this->end_controls_section();
		

		$this->start_controls_section( 'section_list_icon', [
            'label' => __( 'Icon', 'themepaw-companion' ),
			'tab'   => Controls_Manager::TAB_STYLE,
			'condition'   => [
				'list_icon_type' => 'icon',
			]
        ] );

        $this->add_responsive_control(
			'icon_bottom',
			[
				'label' => __( 'Spacing', 'themepaw-companion' ),
				'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em' ],
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 150,
                    ],
                ],
				'selectors' => [
					'{{WRAPPER}} .tpaw-companion-counter-content h2' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				]
			]
		);
		        
        $this->add_control( 'icon_color', [
            'label'     => __( 'Color', 'themepaw-companion' ),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
				'{{WRAPPER}} .tpaw-companion-counter-icon i' => 'color: {{VALUE}}'
			],
		] );

        $this->end_controls_section();

	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$settings = apply_filters( 'tpaw_counter_' . $this->get_id() . '_settings', $settings );

		$item_class = array(
			'tpaw-companion-single-counter'
		);
		if ( 'icon_top' === $settings['list_style_type'] ) {
			$item_class[] = 'tpaw-companion-counter-top';
		} elseif ( 'icon_right' === $settings['list_style_type'] ) {
			$item_class[] = 'tpaw-companion-counter-right';
		}
		$class_attributes = implode( ' ', $item_class );

		$output = '<div class="'.$class_attributes.'">';
		$output .= '<div class="tpaw-companion-counter-wrapper">';
			if( !empty($settings['list_icon']) || !empty($settings['list_image']) ) {
				$output .= '<div class="tpaw-companion-counter-icon">';
				if( "icon" === $settings['list_icon_type'] ) {
					$output .= '<i class="' . esc_attr( $settings['list_icon'] ) . '"></i>';
				} elseif( "image" === $settings['list_icon_type'] ) {
					$image_html = tpaw_companion_get_image_html( $settings['list_image'], 'thumbnail_size', $settings );
					$output .= $image_html;
				}
				$output .= '</div>';
			}
			$output .= '<div class="tpaw-companion-counter-content">';
			$output .= ($settings['list_value']) ? '<h2>' . esc_html( $settings['list_value'] ) . '</h2>' : ''; 
			$output .= ($settings['list_title']) ? '<p>' . esc_html( $settings['list_title'] ) . '</p>' : '';
			$output .= '</div>';
		$output .= '</div>';
		$output .= '</div>';

		echo apply_filters( 'tpaw_companion_counter_output', $output, $settings ) ;

	}

	protected function content_template() {}
}
