<?php
/*
Widget Name: TPaw List Infobox
Description: Infobox list widget for Elementrip
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
class TPaw_List_Infobox extends Widget_Base
{
    public function get_name()
    {
        return 'tpaw_postslist';
    }
    
    public function get_title()
    {
        return __( 'List Infobox', 'themepaw-companion' );
    }
    
    public function get_icon()
    {
        return 'eicon-posts-grid';
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
			'entrance_animation',
			[
				'label' => __( 'Entrance Animation', 'plugin-domain' ),
				'type' => Controls_Manager::ANIMATION,
				// 'prefix_class' => 'animated ',
			]
		);

        $this->add_control(
			'type', [
				'label' => __( 'Postlist Type', 'themepaw-companion' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'grid',
				'options' => [
					'grid'       => __( 'Grid', 'themepaw-companion' ),
					'slider'       => __( 'Slider', 'themepaw-companion' ),
				]
			]
		);
        
        $this->add_control(
			'style', [
				'label' => __( 'Style', 'themepaw-companion' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default'       => __( 'Thumbnail First', 'themepaw-companion' ),
					'titlefirst'       => __( 'Title First', 'themepaw-companion' ),
				]
			]
		);
        
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
			'list_title', [
				'label' => __( 'Title', 'themepaw-companion' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'List Title' , 'themepaw-companion' ),
				'label_block' => true,
			]
        );
        
        $repeater->add_control(
			'list_image', [
                'label'       => __( 'Thumbnail', 'themepaw-companion' ),
                'type'        => Controls_Manager::MEDIA,
                'default'     => [
					'url' => THEMEPAW_COMPANION_URI . 'assets/images/placeholder.png',
				],
				'label_block' => true,
                'dynamic'     => [
                    'active' => true,
                ],
			]
        );
        
        $repeater->add_control(
            'thumbnail_size',
            [
                'label' => __('Thumbnail size', 'themepaw-companion'),
                'type' => Controls_Manager::SELECT,
                'options' => tpaw_get_thumbnail_sizes(),
            ]
        );
        
		$repeater->add_control(
			'list_content', [
				'label' => __( 'Content', 'themepaw-companion' ),
				'type' => Controls_Manager::WYSIWYG,
				'default' => __( 'Progressively engineer for new future-proof for a acommunities before 24/7 users.Make in bereker professionally value.' , 'themepaw-companion' ),
				'show_label' => false,
			]
        );
        
        $repeater->add_control(
			'list_button',
			[
				'label' => __( 'Show Button', 'themepaw-companion' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'themepaw-companion' ),
				'label_off' => __( 'No', 'themepaw-companion' ),
			]
		);

		$repeater->add_control(
			'list_button_text',
			[
				'label' => __( 'Button Text', 'themepaw-companion' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => 'Click Me!',
				'separator'	=> 'before',
				'placeholder' => __( 'Enter button text', 'themepaw-companion' ),
				'title' => __( 'Enter button text here', 'themepaw-companion' ),
				'condition'	=> [
					'list_button'	=> 'yes'
				]
			]
		);

		$repeater->add_control(
			'list_button_link_url',
			[
				'label' => __( 'Link URL', 'themepaw-companion' ),
				'type' => Controls_Manager::URL,
				'label_block' => true,
				'placeholder' => __( 'Enter link URL for the button', 'themepaw-companion' ),
                'show_external'	=> true,
                'dynamic' => [
					'active' => true,
				],
				'default'		=> [
					'url'	=> '#'
				],
				'title' => __( 'Enter heading for the button', 'themepaw-companion' ),
				'condition'	=> [
					'list_button'	=> 'yes'
				]
			]
        );

        $repeater->add_control(
			'list_button_text_hola',
			[
				'label' => __( 'Show only url exists', 'themepaw-companion' ),
				'type' => Controls_Manager::TEXT,
                'label_block' => true,
				'condition'	=> [
					'terms' => [
                        [
                            'name' => 'list_button_link_url',
                            'operator' => 'in',
                            'value' => 'url',
                        ],
                    ],
				]
			]
		);

        $repeater->add_control(
			'list_button_type',
			[
				'label' => __( 'Type', 'themepaw-companion' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => __( 'Default', 'themepaw-companion' ),
					'textual' => __( 'Textual', 'themepaw-companion' ),
					'border' => __( 'border', 'themepaw-companion' ),
				],
                'condition'	=> [
					'list_button'	=> 'yes'
				]
			]
		);

		$this->add_control(
			'list',
			[
				'label' => __( 'Post List', 'themepaw-companion' ),
				'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'separator' => 'before',
				'default' => [
					[
						'list_title' => __( 'Ocean cargo service', 'themepaw-companion' ),
                        'list_content' => __( 'Progressively engineer for new future-proof for a acommunities before 24/7 users.Make in bereker professionally value.', 'themepaw-companion' ),
                        'list_image' => THEMEPAW_COMPANION_URI . 'assets/images/placeholder.png'
					],
					[
						'list_title' => __( 'Professional courier', 'themepaw-companion' ),
                        'list_content' => __( 'Progressively engineer for new future-proof for a acommunities before 24/7 users.Make in bereker professionally value.', 'themepaw-companion' ),
                        'list_image' => THEMEPAW_COMPANION_URI . 'assets/images/placeholder.png'
                    ],
                    [
						'list_title' => __( 'Delivery product home', 'themepaw-companion' ),
                        'list_content' => __( 'Progressively engineer for new future-proof for a acommunities before 24/7 users.Make in bereker professionally value.', 'themepaw-companion' ),
                        'list_image' => THEMEPAW_COMPANION_URI . 'assets/images/placeholder.png'
					],
				],
				'title_field' => '{{{ list_title }}}',
			]
		);

       
        $this->end_controls_section();

       

        $this->start_controls_section( 'section_postlist_style', [
            'label' => __( 'PostList Style', 'themepaw-companion' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ] );

        $this->add_responsive_control(
			'postlist_margin_bottom',
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

        $this->add_control( 'postlist_bg_color', [
            'label'     => __( 'Background Color', 'themepaw-companion' ),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .lgtico-service-wrap' => 'background-color: {{VALUE}};',
            ],
        ] );

        $this->add_group_control( Group_Control_Border::get_type(), [
            'name'     => 'inobox_border_typography',
            'selector' => '{{WRAPPER}} .lgtico-service-wrap',
        ] );

        $this->add_control(
			'postlist_height',
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
			'post_list_padding',
			[
				'label' => __( 'Padding', 'themepaw-companion' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}}:not(.lgtico-thumbnail-full-yes) .lgtico-service-wrap, {{WRAPPER}}.lgtico-thumbnail-full-yes .lgtico-service-content-area' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
        );

        $this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'item_boxshadow',
				'label' => __( 'Box Shadow', 'themepaw-companion' ),
				'selector' => '{{WRAPPER}} .lgtico-service-wrap',
			]
        );
        

        $this->end_controls_section();

        $this->start_controls_section( 'section_postlist_thumbnail', [
            'label' => __( 'Postlist Thumbnail', 'themepaw-companion' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ] );
        
        $this->add_responsive_control(
			'thumbnail_bottom',
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
					'{{WRAPPER}} .lgtico-thumbnail-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				]
			]
        );

        $this->add_control(
			'thumbnail_full',
			[
				'label' => __( 'Thumbnail Full Width', 'themepaw-companion' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'themepaw-companion' ),
                'label_off' => __( 'No', 'themepaw-companion' ),
                'prefix_class' => 'lgtico-thumbnail-full-',
            ]
        );
        
        
        $this->end_controls_section();

        $this->start_controls_section( 'section_postlist_title', [
            'label' => __( 'Postlist Title', 'themepaw-companion' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ] );

        $this->add_responsive_control(
			'postlist_title_bottom',
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
					'{{WRAPPER}} .lgtico-single-postlist .postlist-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				]
			]
        );
        
        $this->add_control( 'title_color', [
            'label'     => __( 'Color', 'themepaw-companion' ),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
            '{{WRAPPER}} .lgtico-single-postlist .postlist-title' => 'color: {{VALUE}};',
        ],
        ] );
        $this->add_group_control( Group_Control_Typography::get_type(), [
            'name'     => 'title_typography',
            'selector' => '{{WRAPPER}} .lgtico-single-postlist .postlist-title',
        ] );
        $this->end_controls_section();
        $this->start_controls_section( 'section_postlist_text', [
            'label' => __( 'Inofox Text', 'themepaw-companion' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ] );
        $this->add_control( 'text_color', [
            'label'     => __( 'Color', 'themepaw-companion' ),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .lgtico-service-content p' => 'color: {{VALUE}};',
            ],
        ] );
        $this->add_group_control( Group_Control_Typography::get_type(), [
            'name'     => 'text_typography',
            'selector' => '{{WRAPPER}} .lgtico-service-content p',
        ] );
        $this->end_controls_section();

        $this->start_controls_section( 'section_postlist_button', [
            'label' => __( 'Postlist Button', 'themepaw-companion' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ] );

        $this->add_control( 'postlist_button_size', [
            'label'      => __( 'Button Font Size', 'themepaw-companion' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => [ 'px', '%', 'em' ],
            'range'      => [
                'px' => [
                    'min' => 0,
                    'max' => 150,
                ],
            ],
            'selectors'  => [
                '{{WRAPPER}} .lgtico-button' => 'font-size: {{SIZE}}{{UNIT}};',
            ],
        ] );

        $this->add_responsive_control(
			'postlist_btn_space',
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
					'{{WRAPPER}} .tpw-companion-btn-wrap' => 'margin-top: {{SIZE}}{{UNIT}};',
				]
			]
        );

        $this->add_control( 'button_before_after_color', [
            'label'     => __( 'Texual Button Before/After Color', 'themepaw-companion' ),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .lgtico-button-textual .lgtico-default-btn:after, {{WRAPPER}} .lgtico-button-textual .lgtico-default-btn:before' => 'background-color: {{VALUE}};',
            ],
        ] );

        $this->add_responsive_control(
			'button_padding',
			[
				'label' => __( 'Padding', 'themepaw-companion' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .lgtico-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};min-width: inherit;',
				],
				'separator' => 'before',
			]
        );

        $this->start_controls_tabs( 'infob_button_style' );

        $this->start_controls_tab(
			'info_button_normal',
			[
				'label' => __( 'Normal', 'themepaw-companion' ),
			]
		);

        $this->add_control( 'button_text_color', [
            'label'     => __( 'Color', 'themepaw-companion' ),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .lgtico-button' => 'color: {{VALUE}};',
            ],
        ] );
        
        $this->add_control( 'button_bg_color', [
            'label'     => __( 'Background Color', 'themepaw-companion' ),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .lgtico-button' => 'background-color: {{VALUE}};',
            ],
        ] );


        $this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label' => __( 'Hover', 'themepaw-companion' ),
			]
        );
        
        $this->add_control( 'button_hover_color', [
            'label'     => __( 'Color', 'themepaw-companion' ),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .lgtico-button:hover' => 'color: {{VALUE}};',
            ],
        ] );
        
        $this->add_control( 'button_hover_bg_color', [
            'label'     => __( 'Background Color', 'themepaw-companion' ),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .lgtico-button:hover' => 'background-color: {{VALUE}};',
            ],
        ] );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section( 'section_postlist_slider', [
            'label' => __( 'Slider Navigation Styles', 'themepaw-companion' ),
            'tab'   => Controls_Manager::TAB_STYLE,
            'condition'	=> [
                'type'	=> 'slider'
            ]
        ] );

        $this->start_controls_tabs( 'postlist_slider_nav_style' );

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
        $settings = apply_filters( 'tpaw_postlist_' . $this->get_id() . '_settings', $settings );

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

        $this->add_render_attribute( 'wrapper', 'class', 'lgtico-single-postlist' );
        $this->add_render_attribute( 'wrapper', 'class', 'lgtico-service-wrap' );
        $this->add_render_attribute( 'wrapper', 'class', 'lgtico-service-wrap-2' );
        $this->add_render_attribute( 'content', 'class', 'lgtico-service-content' );
        echo ( $settings['type'] === 'grid' ) ? '<div class="bootstrap-wrapper"><div class="row">' : '<div class="owl-carousel lgtico-single-slider" data-settings=\'' . wp_json_encode($slider_options) . '\'>';
        foreach( $settings['list'] as $item ) :
        echo ( $settings['type'] === 'grid' ) ? '<div class="lgtico-service-single-item animated '.$settings['entrance_animation'].' '.tpaw_companion_get_grid_classes($settings).' elementor-repeater-item-'.$item['_id'].'">' : '<div class="lgtico-service-single-item elementor-repeater-item-'.$item['_id'].'">'; ?>
        <div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
            <?php if( 'titlefirst' === $settings['style'] ) : ?>
            <div class="lgtico-title-wrap-area">
                <?php echo ( !empty($item['list_title']) ) ? '<h3 class="postlist-title">'. esc_html($item['list_title']) .'</h3>' : ''; ?>
            </div>
            <?php endif; ?>
            <?php
            if( !empty( $item['list_image'] ) ) : ?>
            <div class="lgtico-thumbnail-wrap">
                <?php 
                $thumbnail_size = (!empty($item['thumbnail_size'])) ? $item['thumbnail_size'] : 'full'; ?>
                <?php
                echo tpaw_companion_get_image_html( $item['list_image'], $thumbnail_size); ?>
            </div>
            <?php endif; ?>
            <div class="lgtico-service-content-area">
                <div <?php echo $this->get_render_attribute_string( 'content' ); ?>>
                    <?php if( 'titlefirst' !== $settings['style'] ) : ?>
                        <?php echo ( !empty($item['list_title']) ) ? '<h3 class="postlist-title">'. esc_html($item['list_title']) .'</h3>' : ''; ?>
                    <?php endif; ?>
                    <?php echo tpaw_companion_get_meta($item['list_content']); ?>
                </div>
                <?php if( 'yes' == $item['list_button'] ) :
                if ( ! empty( $item['list_button_link_url']['url'] ) ) {

                    $this->add_render_attribute( 'button', 'class', 'lgtico-button-link' );

                    $this->add_render_attribute( 'button-wrap', 'class', 'tpw-companion-btn-wrap' );
                    
                    if ( ! empty( $item['list_button_type'] ) ) {
                        $this->add_render_attribute( 'button-wrap', 'class', 'lgtico-button-' . $item['list_button_type'] );
                    }

                    $this->add_render_attribute( 'button', 'href', $item['list_button_link_url']['url'] );
                    $this->add_render_attribute( 'button', 'class', 'lgtico-button-link' );

                    $this->add_render_attribute( 'button', 'class', 'lgtico-button' );
                    $this->add_render_attribute( 'button', 'class', 'lgtico-default-btn' );
                    $this->add_render_attribute( 'button', 'role', 'button' );
            
                    if ( $item['list_button_link_url']['is_external'] ) {
                        $this->add_render_attribute( 'button', 'target', '_blank' );
                    }
                    if ( $item['list_button_link_url']['nofollow'] ) {
                        $this->add_render_attribute( 'button', 'rel', 'nofollow' );
                    }
                }
                ?>
                <div <?php echo $this->get_render_attribute_string( 'button-wrap' ); ?>>
                    <a <?php echo $this->get_render_attribute_string( 'button' ); ?>><?php echo $item['list_button_text']; ?></a>
                </div>
                <?php endif; ?>
            </div><!-- .lgtico-service-content-area -->
            
        </div>
        <?php
        echo ( $settings['type'] === 'grid' ) ? '</div>' : '</div>';
        endforeach;
        echo ( $settings['type'] === 'grid' ) ? '</div></div>' : '</div>';
    }
    
}



class Elementor_Test_Widget extends Widget_Base {

    public function get_name()
    {
        return 'tpaw_test';
    }
    
    public function get_title()
    {
        return __( 'Test', 'themepaw-companion' );
    }
    
    public function get_icon()
    {
        return 'eicon-posts-grid';
    }
    
    public function get_categories()
    {
        return array( 'themepaw-addons' );
    }

	protected function _register_controls() {

		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Content', 'plugin-name' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'entrance_animation',
			[
				'label' => __( 'Entrance Animation', 'plugin-domain' ),
				'type' => Controls_Manager::ANIMATION,
				'prefix_class' => 'animated ',
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		echo '<div class="' . $settings['entrance_animation'] . '"> mlimon </div>';
	}

	protected function _content_template() {
		?>
		<div class="{{ settings.entrance_animation }}"> mlimon </div>
		<?php
	}

}