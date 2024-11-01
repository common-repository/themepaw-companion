<?php
/*
Widget Name: TPaw Testimonial Slider
Description: Testimoinal slider widget for Elementrip
Author: themepaw.com
Author URI: https://themepaw.com/plugins/companion
*/
namespace ThemepawCompanion\Widgets\Elementor;

use \Elementor\Widget_Base;
use \Elementor\Controls_Manager;
use \Elementor\utils;
use \Elementor\Scheme_Color;
use \Elementor\Group_Control_Typography;
use \Elementor\Group_Control_Border;
use \Elementor\Group_Control_Box_Shadow;
use \Elementor\Group_Control_Image_Size;
use \Elementor\Scheme_Typography;

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

// Exit if accessed directly.
class TPaw_TestimonialSlider extends Widget_Base
{
    public function get_name()
    {
        return 'tpaw_testimonial_slider';
    }
    
    public function get_title()
    {
        return __( 'Testimonial Slider', 'themepaw-companion' );
    }
    
    public function get_icon()
    {
        return 'eicon-testimonial';
    }
    
    public function get_categories()
    {
        return array( 'themepaw-addons' );
    }
    
    public function get_script_depends() {
		return array( 'tpaw-owl-carousel2', 'tpaw-slider-init', 'tpaw-init' );
	}
    
    protected function _register_controls()
    {
        $this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Content', 'themepaw-companion' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
        );

        $this->add_control(
			'type', [
				'label' => __( 'Type', 'themepaw-companion' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'grid',
				'options' => [
					'grid'       => __( 'Grid', 'themepaw-companion' ),
					'slider'       => __( 'Slider', 'themepaw-companion' ),
				]
			]
		);
        
        $style_options = [
            'style1' => __( 'Style 1', 'themepaw-companion' ),
            'style2' => __( 'Style 2', 'themepaw-companion' ),
        ];
        $this->add_control( 'style', [
            'type'         => Controls_Manager::SELECT,
            'label'        => __( 'Choose Style', 'themepaw-companion' ),
            'default'      => 'style1',
            'options'      => $style_options,
            'condition'   => [
                'type' => 'slider',
            ]
        ] );

        $this->add_responsive_control( 'per_line', [
            'label'              => __( 'Columns per row', 'themepaw-companion' ),
                'type'               => Controls_Manager::SELECT,
                'default'            => '4',
                'tablet_default'     => '6',
                'mobile_default'     => '12',
                'options'            => [
                    '12' => '1',
                    '6' => '2',
                    '4' => '3',
                    '3' => '4',
                    '2' => '6',
                ],
                'frontend_available' => true,
                'condition'   => [
					'type' => 'grid',
				]
        ] );

        $this->add_responsive_control( 'slider_breaks', [
            'label'              => __( 'Slider Items', 'themepaw-companion' ),
                'type'               => Controls_Manager::SELECT,
                'default'            => '3',
                'tablet_default'     => '2',
                'mobile_default'     => '1',
                'options'            => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6',
                    '7' => '7',
                    '8' => '8',
                    '9' => '9',
                    '10' => '10',
                ],
                'frontend_available' => true,
                'condition'   => [
					'type' => 'slider',
					'style' => 'style1',
				]
        ] );

        $this->add_control(
			'nav',
			[
				'label' => __( 'Show Slider Nav', 'themepaw-companion' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'themepaw-companion' ),
                'label_off' => __( 'No', 'themepaw-companion' ),
                'condition'   => [
					'type' => 'slider',
				]
			]
		);
        
        $this->add_control(
			'dots',
			[
				'label' => __( 'Show Slider Dots', 'themepaw-companion' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'themepaw-companion' ),
                'label_off' => __( 'No', 'themepaw-companion' ),
                'condition'   => [
					'type' => 'slider',
				]
			]
		);

        $this->add_responsive_control(
			'align',
			[
				'label' => __( 'Alignment', 'themepaw-companion' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left'    => [
						'title' => __( 'Left', 'themepaw-companion' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'themepaw-companion' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'themepaw-companion' ),
						'icon' => 'fa fa-align-right',
					]
				],
				'prefix_class' => 'elementor%s-align-',
				'default' => '',
			]
        );

        $repeater = new \Elementor\Repeater();

		
        $repeater->add_control(
			'list_name', [
				'label' => __( 'Client Name', 'themepaw-companion' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'David Smith' , 'themepaw-companion' ),
				'label_block' => true,
			]
        );
        $repeater->add_control(
			'list_designation', [
				'label' => __( 'Client Designation', 'themepaw-companion' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'CEO, Kuira' , 'themepaw-companion' ),
				'label_block' => true,
			]
        );

        $repeater->add_control(
			'list_image', [
                'label'       => __( 'Client Avatar', 'themepaw-companion' ),
                'type'        => Controls_Manager::MEDIA,
                'default'     => [
					'url' => THEMEPAW_COMPANION_URI . 'assets/images/xplaceholder.png',
				],
				'label_block' => true,
                'dynamic'     => [
                    'active' => true,
                ],
			]
        );
        
        $repeater->add_control(
			'list_title', [
				'label' => __( 'Feedback Title', 'themepaw-companion' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'OUTSTANDING SERVICE & DELIVER PROCESS!' , 'themepaw-companion' ),
				'label_block' => true,
			]
        );
		
		$repeater->add_control(
			'list_content', [
				'label' => __( 'Feedback Content', 'themepaw-companion' ),
				'type' => Controls_Manager::WYSIWYG,
				'default' => __( 'Intrinsicly conceptualize diverse innovati with enterprise portals. Distinctively  for empower cost effective customer service through exceptional services.' , 'themepaw-companion' ),
				'show_label' => false,
			]
        );
        
        $repeater->add_control(
			'list_icon', [
				'label'       => __( 'Feedback Icon', 'themepaw-companion' ),
                'type'        => Controls_Manager::ICON,
                'label_block' => true,
                'default'     => 'fa fa-comments',
			]
        );

		$this->add_control(
			'list',
			[
				'label' => __( 'Feedback List', 'themepaw-companion' ),
				'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'separator' => 'before',
				'default' => [
					[
						'list_name' => __( 'David Smith', 'themepaw-companion' ),
						'list_designation' => __( 'CEO, Kuira', 'themepaw-companion' ),
						'list_title' => __( 'OUTSTANDING SERVICE & DELIVER PROCESS!', 'themepaw-companion' ),
                        'list_content' => __( 'Intrinsicly conceptualize diverse innovati with enterprise portals. Distinctively  for empower cost effective customer service through exceptional services.', 'themepaw-companion' ),
                        'list_image' => THEMEPAW_COMPANION_URI . 'assets/images/xplaceholder.png',
                        'list_icon' => 'fa fa-comments'
					],
					[
						'list_name' => __( 'John Doe', 'themepaw-companion' ),
						'list_designation' => __( 'CEO, Kuira', 'themepaw-companion' ),
						'list_title' => __( 'OUTSTANDING SERVICE & DELIVER PROCESS!', 'themepaw-companion' ),
                        'list_content' => __( 'Intrinsicly conceptualize diverse innovati with enterprise portals. Distinctively  for empower cost effective customer service through exceptional services.', 'themepaw-companion' ),
                        'list_image' => THEMEPAW_COMPANION_URI . 'assets/images/xplaceholder.png',
                        'list_icon' => 'fa fa-comments'
					],
					[
						'list_name' => __( 'Alex Hales', 'themepaw-companion' ),
						'list_designation' => __( 'CEO, Kuira', 'themepaw-companion' ),
						'list_title' => __( 'OUTSTANDING SERVICE & DELIVER PROCESS!', 'themepaw-companion' ),
                        'list_content' => __( 'Intrinsicly conceptualize diverse innovati with enterprise portals. Distinctively  for empower cost effective customer service through exceptional services.', 'themepaw-companion' ),
                        'list_image' => THEMEPAW_COMPANION_URI . 'assets/images/xplaceholder.png',
                        'list_icon' => 'fa fa-comments'
					],
					
				],
				'title_field' => '{{{ list_name }}}',
			]
		);

       
        $this->end_controls_section();


        $this->start_controls_section( 'item_style', [
            'label' => __( 'Item Style', 'themepaw-companion' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ] );

        $this->add_responsive_control(
			'item_margin_bottom',
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
					'{{WRAPPER}} .lgtico-service-single-item' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				]
			]
		);

        $this->add_control( 'item_bg', [
            'label'     => __( 'Background Color', 'themepaw-companion' ),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .lgtico-tetimonial-item' => 'background-color: {{VALUE}};',
            ],
        ] );

        $this->add_group_control( Group_Control_Border::get_type(), [
            'name'     => 'item_typography',
            'selector' => '{{WRAPPER}} .lgtico-tetimonial-item',
        ] );

        $this->add_control(
			'item_height',
			[
				'label' => __( 'Apply equal height', 'themepaw-companion' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'themepaw-companion' ),
                'label_off' => __( 'No', 'themepaw-companion' ),
                'prefix_class' => 'lgtico-equal-',
                'condition'	=> [
                    'type'	=> 'grid'
                ]
            ]
        );
        $this->add_responsive_control(
			'nav_bottom',
			[
				'label' => __( 'Slider Nav Bottom', 'themepaw-companion' ),
				'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em' ],
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 150,
                    ],
                ],
				'selectors' => [
					'{{WRAPPER}} .lgtico-tetimonial-style2.owl-carousel .owl-nav.flat-nav .owl-next, {{WRAPPER}} .lgtico-tetimonial-style2.owl-carousel .owl-nav.flat-nav .owl-prev' => 'bottom: {{SIZE}}{{UNIT}};',
                ],
                'condition'	=> [
                    'style'	=> 'style2'
                ]
			]
        );
        
        $this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'item_boxshadow',
				'label' => __( 'Box Shadow', 'themepaw-companion' ),
				'selector' => '{{WRAPPER}} .lgtico-tetimonial-item',
			]
        );

        $this->end_controls_section();

        $this->start_controls_section( 'item_icon', [
            'label' => __( 'Item Icon', 'themepaw-companion' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ] );
        $this->add_control( 'icon_size', [
            'label'      => __( 'Icon font size', 'themepaw-companion' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => [ 'px', '%', 'em' ],
            'range'      => [
                'px' => [
                    'min' => 6,
                    'max' => 300,
                ],
            ],
            'selectors'  => [
                '{{WRAPPER}} .lgtico-tetimonial-item .lgtico-testimonial-icon' => 'font-size: {{SIZE}}{{UNIT}};',
            ],
        ] );

        $this->add_control( 'icon_color', [
            'label'     => __( 'Color', 'themepaw-companion' ),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .lgtico-tetimonial-item .lgtico-testimonial-icon' => 'color: {{VALUE}};',
            ],
        ] );
        
        $this->end_controls_section();

        $this->start_controls_section( 'item_title', [
            'label' => __( 'Feedback Title', 'themepaw-companion' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ] );

        $this->add_responsive_control(
			'feedback_tiitle',
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
					'{{WRAPPER}} .lgtico-testimonial-content h4' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				]
			]
        );
        
        $this->add_control( 'title_color', [
            'label'     => __( 'Color', 'themepaw-companion' ),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
            '{{WRAPPER}} .lgtico-testimonial-content h4' => 'color: {{VALUE}};',
        ],
        ] );
        $this->add_group_control( Group_Control_Typography::get_type(), [
            'name'     => 'title_typography',
            'selector' => '{{WRAPPER}} .lgtico-testimonial-content h4',
        ] );
        $this->end_controls_section();

        $this->start_controls_section( 'item_content', [
            'label' => __( 'Feedback Content', 'themepaw-companion' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ] );

        $this->add_control( 'text_color', [
            'label'     => __( 'Color', 'themepaw-companion' ),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .lgtico-testimonial-content p' => 'color: {{VALUE}};',
            ],
        ] );
        $this->add_group_control( Group_Control_Typography::get_type(), [
            'name'     => 'text_typography',
            'selector' => '{{WRAPPER}} .lgtico-testimonial-content p',
        ] );
        $this->end_controls_section();
        
        $this->start_controls_section( 'client_name', [
            'label' => __( 'Client Name', 'themepaw-companion' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ] );

        $this->add_control( 'name_color', [
            'label'     => __( 'Color', 'themepaw-companion' ),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .lgtico-testimonial-author-bio h5' => 'color: {{VALUE}};',
            ],
        ] );

        $this->add_group_control( Group_Control_Typography::get_type(), [
            'name'     => 'name_typography',
            'selector' => '{{WRAPPER}} .lgtico-testimonial-author-bio h5',
        ] );
        $this->end_controls_section();
        
        $this->start_controls_section( 'client_designation', [
            'label' => __( 'Client Designation', 'themepaw-companion' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ] );

        $this->add_control( 'designation_color', [
            'label'     => __( 'Color', 'themepaw-companion' ),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .lgtico-testimonial-author-bio p' => 'color: {{VALUE}};',
            ],
        ] );

        $this->add_group_control( Group_Control_Typography::get_type(), [
            'name'     => 'designation_typography',
            'selector' => '{{WRAPPER}} .lgtico-testimonial-author-bio p',
        ] );
        $this->end_controls_section();

        $this->start_controls_section( 'section_testimonial_slider', [
            'label' => __( 'Slider Navigation Styles', 'themepaw-companion' ),
            'tab'   => Controls_Manager::TAB_STYLE,
            'condition'	=> [
                'type'	=> 'slider'
            ]
        ] );

        $this->start_controls_tabs( 'testimonial_slider_nav_style' );

        $this->start_controls_tab(
			'slider_nav_normal',
			[
				'label' => __( 'Normal', 'themepaw-companion' ),
			]
		);

        $this->add_control( 'slider_nav_color', [
            'label'     => __( 'Color', 'themepaw-companion' ),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .owl-carousel .owl-nav.flat-nav [class*=owl-]' => 'color: {{VALUE}}; border-color: {{VALUE}};',
            ],
        ] );
        
        $this->add_control( 'slider_nav_bgcolor', [
            'label'     => __( 'Background Color', 'themepaw-companion' ),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .owl-carousel .owl-nav.flat-nav [class*=owl-]' => 'background-color: {{VALUE}};',
            ],
        ] );

        $this->end_controls_tab();

		$this->start_controls_tab(
			'slider_nav_hover',
			[
				'label' => __( 'Hover', 'themepaw-companion' ),
			]
        );
        
        $this->add_control( 'slider_nav_color_hover', [
            'label'     => __( 'Hover Color', 'themepaw-companion' ),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .owl-carousel .owl-nav.flat-nav [class*=owl-]:hover' => 'color: {{VALUE}};border-color: {{VALUE}};',
            ],
        ] );
        
        $this->add_control( 'slider_nav_bgcolor_hover', [
            'label'     => __( 'Hover Background Color', 'themepaw-companion' ),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .owl-carousel .owl-nav.flat-nav [class*=owl-]:hover' => 'background-color: {{VALUE}};',
            ],
        ] );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

    }
    
    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $settings = apply_filters( 'tpaw_testimonial_testimoinal_' . $this->get_id() . '_settings', $settings );

        $extraSetting = array(
            'autoplay' => false,
            'loop' => true,
            'mouseDrag' => true,
            'touchDrag' => true,
            'pullDrag' => true,
            'stagePadding' => 0,
            'margin' => 25,
        );
        $breakspoints = array(
            'slider_breaks' => (!empty($settings['slider_breaks'])) ? $settings['slider_breaks'] : 3,
            'slider_breaks_tablet' => (!empty($settings['slider_breaks_tablet'])) ? $settings['slider_breaks_tablet'] : 2,
            'slider_breaks_mobile' => (!empty($settings['slider_breaks_mobile'])) ? $settings['slider_breaks_mobile'] : 1
        );

        if ( isset($settings['style']) && !empty($settings['style']) && 'style2' === $settings['style'] ) {
            $breakspoints = array(
                'slider_breaks' => 1,
                'slider_breaks_tablet' => 1,
                'slider_breaks_mobile' => 1
            );
            $this->add_render_attribute( 'main-wrapper', 'class', 'lgtico-tetimonial-style2' );
        }
        $extraSettings = apply_filters( 'tpaw_slider_' . $this->get_id() . '_extra', $extraSetting );
        $breakspointsettings = apply_filters( 'tpaw_slider_' . $this->get_id() . '_breaks', $breakspoints );
        $animateIn = apply_filters( 'tpaw_slider_' . $this->get_id() . '_animatein', 'fadeIn' );
        $animateOut = apply_filters( 'tpaw_slider_' . $this->get_id() . '_animateout', 'fadeOut' );

        $slider_options = [
            'dots' => (!empty($settings['dots']) && 'yes' === $settings['dots']) ? true : false,
            'nav' => (!empty($settings['nav']) && 'yes' === $settings['nav']) ? true : false,
            'animateIn' => $animateIn,
            'animateOut' => $animateOut,
        ];
        $slider_options = array_merge($slider_options, $extraSettings, $breakspointsettings);

        $this->add_render_attribute( 'wrapper', 'class', 'lgtico-tetimonial-item' );
        $this->add_render_attribute( 'wrapper', 'class', 'lgtico-tetimonial-item-2' );
        $this->add_render_attribute( 'main-wrapper', 'class', 'owl-carousel' );
        $this->add_render_attribute( 'main-wrapper', 'class', 'lgtico-single-slider' );

        echo ( $settings['type'] === 'grid' ) ? '<div class="bootstrap-wrapper"><div class="row">' : '<div '.$this->get_render_attribute_string( 'main-wrapper' ).' data-settings=\'' . wp_json_encode($slider_options) . '\'>';
        foreach( $settings['list'] as $item ) :
        echo ( $settings['type'] === 'grid' ) ? '<div class="lgtico-service-single-item '.tpaw_companion_get_grid_classes($settings).' elementor-repeater-item-'.$item['_id'].'">' : '<div class="lgtico-service-single-item elementor-repeater-item-'.$item['_id'].'">'; ?>
        <div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
            <div class="lgtico-testimonial-content lgtico-border-bottom">
                <?php echo ( !empty($item['list_title']) ) ? '<h4 class="lgtico-line-height-1-5">' . $item['list_title'] . '</h4>' : ''; ?>
                <?php echo tpaw_companion_get_meta($item['list_content']); ?>
            </div>
            <div class="lgtico-testimonial-author lgtico-testimonial-author-2 d-flex">
                <i class="lgtico-testimonial-icon <?php echo esc_attr( $item['list_icon'] ); ?>"></i>
                <?php if ( !empty( $item['list_image'] ) ) : ?>
                <div class="lgtico-testimonial-author-img">
                    <?php echo tpaw_companion_get_image_html( $item['list_image'], 'full' ); ?>
                </div>
                <?php endif; ?>
                <div class="lgtico-testimonial-author-bio">
                    <?php echo ( !empty($item['list_name']) ) ? '<h5>' . $item['list_name'] . '</h5>' : ''; ?>
                    <?php echo ( !empty($item['list_designation']) ) ? '<p>' . $item['list_designation'] . '</p>' : ''; ?>
                </div>
            </div>
        </div>
        <?php
        echo ( $settings['type'] === 'grid' ) ? '</div>' : '</div>';
        endforeach;
        echo ( $settings['type'] === 'grid' ) ? '</div></div>' : '</div>';
    }

}