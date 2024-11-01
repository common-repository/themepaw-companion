<?php

/*
Widget Name: TPaw Blog Posts
Description: Blog Posts widget for Elementrip
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
class TPaw_BlogPosts extends Widget_Base
{
    public function get_name()
    {
        return 'tpaw_blog_posts';
    }
    
    public function get_title()
    {
        return __( 'Blog Posts', 'themepaw-companion' );
    }
    
    public function get_icon()
    {
        return 'eicon-post-slider';
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
				'label' => __( 'Query', 'themepaw-companion' ),
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

        $this->add_control(
            'post_by',
            [
                'label' => __('Post By:', 'themepaw-companion'),
                'type' => Controls_Manager::SELECT,
                'default' => 'latest',
                'options' => array(
                    'latest' => __('Latest Post', 'themepaw-companion'),
                    'selected' => __('Selected posts', 'themepaw-companion'),
                    'author' => __('Post by author', 'themepaw-companion'),
                    'category' => __('Post by category', 'themepaw-companion'),
                    'post_tag' => __('Post by tag', 'themepaw-companion'),
                    'featured' => __('Only Featured post', 'themepaw-companion'),
                ),
            ]
        );

        $this->add_control(
            'post__in',
            [
                'label' => __('Post In', 'themepaw-companion'),
                'type' => Controls_Manager::SELECT2,
                'options' => tpaw_get_all_posts(),
                'multiple' => true,
                'condition'   => [
					'post_by' => 'selected',
				]
            ]
        );

        $this->add_control(
            'author',
            [
                'label' => __('Author', 'themepaw-companion'),
                'type' => Controls_Manager::SELECT2,
                'options' => tpaw_get_authors(),
                'multiple' => true,
                'condition'   => [
					'post_by' => 'author',
				]
            ]
        );
        
        $this->add_control(
            'category',
            [
                'label' => __('Category', 'themepaw-companion'),
                'type' => Controls_Manager::SELECT2,
                'options' => tpaw_taxomony_list(),
                'multiple' => true,
                'condition'   => [
					'post_by' => 'category',
				]
            ]
        );
        
        $this->add_control(
            'post_tag',
            [
                'label' => __('Tags', 'themepaw-companion'),
                'type' => Controls_Manager::SELECT2,
                'options' => tpaw_taxomony_list('post_tag'),
                'multiple' => true,
                'condition'   => [
					'post_by' => 'post_tag',
				]
            ]
        );

        $this->add_control(
            'relation',
            [
                'label' => __('Relation:', 'themepaw-companion'),
                'type' => Controls_Manager::SELECT,
                'default' => 'AND',
                'options' => array(
                    'AND' => __('AND', 'themepaw-companion'),
                    'OR' => __('OR', 'themepaw-companion'),
                ),
                'condition'   => [
					'post_by' => array('category', 'post_tag'),
				]
            ]
        );

        $this->add_control(
            'post__not_in',
            [
                'label' => __('Post Not In', 'themepaw-companion'),
                'type' => Controls_Manager::SELECT2,
                'options' => tpaw_get_all_posts(),
                'condition'   => [
					'post_by' => '!selected',
				]
            ]
        );

        $this->add_control(
            'posts_per_page',
            [
                'label' => __('Posts Per Page', 'themepaw-companion'),
                'type' => Controls_Manager::NUMBER,
                'default' => '3',
            ]
        );

        $this->add_control(
            'offset',
            [
                'label' => __('Offset', 'themepaw-companion'),
                'type' => Controls_Manager::NUMBER,
                'default' => '0',
            ]
        );

        $this->add_control(
            'orderby',
            [
                'label' => __('Order By', 'themepaw-companion'),
                'type' => Controls_Manager::SELECT,
                'options' => tpaw_get_post_orderby_options(),
                'default' => 'date',

            ]
        );

        $this->add_control(
            'order',
            [
                'label' => __('Order', 'themepaw-companion'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'asc' => 'Ascending',
                    'desc' => 'Descending',
                ],
                'default' => 'desc',

            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'post_layout_settings',
            [
                'label' => __('Layout Settings', 'themepaw-companion'),
            ]
        );

        $this->add_responsive_control( 'columns_number', [
            'label'              => __( 'Number of Columns', 'themepaw-companion' ),
                'type'               => Controls_Manager::SELECT,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
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

        $this->add_control(
            'show_thumbnail',
            [
                'label' => __('Show Thumbnail', 'themepaw-companion'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    '1' => [
                        'title' => __('Yes', 'themepaw-companion'),
                        'icon' => 'fa fa-check',
                    ],
                    '0' => [
                        'title' => __('No', 'themepaw-companion'),
                        'icon' => 'fa fa-ban',
                    ],
                ],
                'default' => '1',
            ]
        );

        $this->add_control(
            'show_title',
            [
                'label' => __('Show Title', 'themepaw-companion'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    '1' => [
                        'title' => __('Yes', 'themepaw-companion'),
                        'icon' => 'fa fa-check',
                    ],
                    '0' => [
                        'title' => __('No', 'themepaw-companion'),
                        'icon' => 'fa fa-ban',
                    ],
                ],
                'default' => '1',
            ]
        );

        $this->add_control(
            'show_meta',
            [
                'label' => __('Show Meta', 'themepaw-companion'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    '1' => [
                        'title' => __('Yes', 'themepaw-companion'),
                        'icon' => 'fa fa-check',
                    ],
                    '0' => [
                        'title' => __('No', 'themepaw-companion'),
                        'icon' => 'fa fa-ban',
                    ],
                ],
                'default' => '1',
            ]
        );

        $this->add_control(
            'order_position',
            [
                'label' => __('Title/Thumbnail Position', 'themepaw-companion'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'first_thumbnail' => __('Thumbnail > Title > Meta', 'themepaw-companion'),
                    'second_meta' => __('Thumbnail > Meta > Title', 'themepaw-companion'),
                    'first_title' => __('Title > Thumbnail > Meta', 'themepaw-companion'),
                ],
                'default' => 'first_thumbnail',
                'condition' => [
                    'show_meta' => '1',
                ],
            ]
        );
        
        $this->add_control(
            'left_meta',
            [
                'label' => __('Left Meta', 'themepaw-companion'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'none' => __('None', 'themepaw-companion'),
                    'date' => __('Published Date', 'themepaw-companion'),
                    'category' => __('Category', 'themepaw-companion'),
                    'tag' => __('Tag', 'themepaw-companion'),
                    'author' => __('Author', 'themepaw-companion'),
                    'comment' => __('Comment Number', 'themepaw-companion'),
                    'readmore' => __('Read More', 'themepaw-companion'),
                ],
                'default' => 'date',
                'condition' => [
                    'show_meta' => '1',
                ],
            ]
        );
        
        $this->add_control(
            'right_meta',
            [
                'label' => __('Right Meta', 'themepaw-companion'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'none' => __('None', 'themepaw-companion'),
                    'date' => __('Published Date', 'themepaw-companion'),
                    'category' => __('Category', 'themepaw-companion'),
                    'tag' => __('Tag', 'themepaw-companion'),
                    'author' => __('Author', 'themepaw-companion'),
                    'comment' => __('Comment Number', 'themepaw-companion'),
                    'readmore' => __('Read More', 'themepaw-companion'),
                ],
                'default' => 'category',
                'condition' => [
                    'show_meta' => '1',
                ],
            ]
        );


        $this->add_control(
            'show_excerpt',
            [
                'label' => __('Show Excerpt', 'themepaw-companion'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    '1' => [
                        'title' => __('Yes', 'themepaw-companion'),
                        'icon' => 'fa fa-check',
                    ],
                    '0' => [
                        'title' => __('No', 'themepaw-companion'),
                        'icon' => 'fa fa-ban',
                    ],
                ],
                'default' => '1',
            ]
        );

        $this->add_control(
            'excerpt_length',
            [
                'label' => __('Excerpt Words Mumber', 'themepaw-companion'),
                'type' => Controls_Manager::NUMBER,
                'default' => '13',
                'condition' => [
                    'show_excerpt' => '1',
                ],
            ]
        );


        $this->add_control(
            'show_footer',
            [
                'label' => __('Show Footer', 'themepaw-companion'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    '1' => [
                        'title' => __('Yes', 'themepaw-companion'),
                        'icon' => 'fa fa-check',
                    ],
                    '0' => [
                        'title' => __('No', 'themepaw-companion'),
                        'icon' => 'fa fa-ban',
                    ],
                ],
                'default' => '1',
            ]
        );
        
        $this->add_control(
            'left_footer',
            [
                'label' => __('Left Footer', 'themepaw-companion'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'none' => __('None', 'themepaw-companion'),
                    'date' => __('Published Date', 'themepaw-companion'),
                    'category' => __('Category', 'themepaw-companion'),
                    'tag' => __('Tag', 'themepaw-companion'),
                    'author' => __('Author', 'themepaw-companion'),
                    'comment' => __('Comment Number', 'themepaw-companion'),
                    'readmore' => __('Read More', 'themepaw-companion'),
                ],
                'default' => 'date',
                'condition' => [
                    'show_footer' => '1',
                ],
            ]
        );
        
        $this->add_control(
            'right_footer',
            [
                'label' => __('Right Footer', 'themepaw-companion'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'none' => __('None', 'themepaw-companion'),
                    'date' => __('Published Date', 'themepaw-companion'),
                    'category' => __('Category', 'themepaw-companion'),
                    'tag' => __('Tag', 'themepaw-companion'),
                    'author' => __('Author', 'themepaw-companion'),
                    'comment' => __('Comment Number', 'themepaw-companion'),
                    'readmore' => __('Read More', 'themepaw-companion'),
                ],
                'default' => 'category',
                'condition' => [
                    'show_footer' => '1',
                ],
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'post_readmmore_settings',
            [
                'label' => __('Readmore Settings', 'themepaw-companion'),
                'conditions'   => [
                    'terms' => [
                        [
                            'relation' => 'or',
                            'terms'    => [
                                [
                                    'name'  => 'left_meta',
                                    'value' => 'readmore',
                                ],
                                [
                                    'name'  => 'right_meta',
                                    'value' => 'readmore',
                                ],
                                [
                                    'name'  => 'left_footer',
                                    'value' => 'readmore',
                                ],
                                [
                                    'name'  => 'right_footer',
                                    'value' => 'readmore',
                                ]
                            ],
                        ],
                    ],
                ],
            ]
        );


        $this->add_control(
            'readmore_text',
            [
                'label' => __('Label Text', 'themepaw-companion'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Read More','themepaw-companion'),
            ]
        );

        $this->add_control(
            'readmore_style',
            [
                'label' => __('Readmore Style', 'themepaw-companion'),
                'type' => Controls_Manager::SELECT,
                'default' => 'textual',
				'options' => [
                    'textual' => __( 'Textual', 'themepaw-companion' ),
                    'default' => __( 'Default', 'themepaw-companion' ),
					'border' => __( 'border', 'themepaw-companion' ),
                ],
            ]
        );

        $this->end_controls_section();


        $this->cat_settings();
        $this->tag_settings();
        $this->author_settings();
        $this->comment_settings();
        $this->date_settings();


        $this->start_controls_section(
            'post_slider_settings',
            [
                'label' => __('Slider Settings', 'themepaw-companion'),
                'condition'   => [
					'type' => 'slider',
				]
            ]
        );

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
					'type' => 'slider'
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
                '{{WRAPPER}} .lgtico-blog-wrap' => 'background-color: {{VALUE}};',
            ],
        ] );

        $this->add_group_control( Group_Control_Border::get_type(), [
            'name'     => 'item_typography',
            'selector' => '{{WRAPPER}} .lgtico-blog-wrap',
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
					'{{WRAPPER}} .lgtico-single-slider.owl-carousel .owl-nav.flat-nav .owl-next, {{WRAPPER}} .lgtico-single-slider.owl-carousel .owl-nav.flat-nav .owl-prev' => 'bottom: {{SIZE}}{{UNIT}};',
                ],
                'condition'	=> [
                    'type'	=> 'slider'
                ]
			]
        );
        
        $this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'item_boxshadow',
				'label' => __( 'Box Shadow', 'themepaw-companion' ),
				'selector' => '{{WRAPPER}} .lgtico-blog-wrap',
			]
        );

        $this->add_responsive_control(
			'item_padding',
			[
				'label' => __( 'Padding', 'themepaw-companion' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .lgtico-blog-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}}.lgtico-full-with-thumbnail-yes .lgtico-blog-thumb' => 'margin: -{{TOP}}{{UNIT}} -{{RIGHT}}{{UNIT}} 30px -{{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
        );

        $this->end_controls_section();


        $this->start_controls_section( 'item_thumbnail', [
            'label' => __( 'Thumbnail', 'themepaw-companion' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ] );

        $this->add_responsive_control(
			'thumbnail_padding',
			[
				'label' => __( 'Padding', 'themepaw-companion' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .lgtico-blog-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
        );

        $this->add_control(
			'item_width',
			[
				'label' => __( 'Apply full width', 'themepaw-companion' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'themepaw-companion' ),
                'label_off' => __( 'No', 'themepaw-companion' ),
                'prefix_class' => 'lgtico-full-with-thumbnail-',
            ]
        );
        
        $this->add_responsive_control(
			'thumbnail_marging',
			[
				'label' => __( 'Margin', 'themepaw-companion' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .lgtico-blog-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition'	=> [
                    'item_width!'	=> 'yes'
                ]
			]
        );

        $this->end_controls_section();

        $this->start_controls_section( 'item_title', [
            'label' => __( 'Title', 'themepaw-companion' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ] );

        $this->add_control( 'title_color', [
            'label'     => __( 'Color', 'themepaw-companion' ),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .lgtico-blog-title a h4' => 'color: {{VALUE}};',
            ],
        ] );
        $this->add_group_control( Group_Control_Typography::get_type(), [
            'name'     => 'title_typography',
            'selector' => '{{WRAPPER}} .lgtico-blog-title a h4',
        ] );

        $this->add_group_control( Group_Control_Border::get_type(), [
            'name'     => 'title_wrap_typo',
            'selector' => '{{WRAPPER}} .lgtico-blog-title',
        ] );

        $this->add_responsive_control(
			'title_padding',
			[
				'label' => __( 'Padding', 'themepaw-companion' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .lgtico-blog-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
        );
        
        $this->add_responsive_control(
			'title_marging',
			[
				'label' => __( 'Margin', 'themepaw-companion' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .lgtico-blog-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
        );

        $this->end_controls_section();

        $this->start_controls_section( 'item_content', [
            'label' => __( 'Excerpt', 'themepaw-companion' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ] );

        $this->add_control( 'text_color', [
            'label'     => __( 'Color', 'themepaw-companion' ),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .lgtico-blog-article p' => 'color: {{VALUE}};',
            ],
        ] );
        $this->add_group_control( Group_Control_Typography::get_type(), [
            'name'     => 'text_typography',
            'selector' => '{{WRAPPER}} .lgtico-blog-article p',
        ] );
        $this->end_controls_section();


        $this->start_controls_section( 'item_meta', [
            'label' => __( 'Meta', 'themepaw-companion' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ] );

        $this->add_responsive_control(
			'item_meta_margin',
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
					'{{WRAPPER}} .lgtico-blog-meta' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				]
			]
		);

        $this->add_control( 'date_color', [
            'label'     => __( 'Meta Date Color', 'themepaw-companion' ),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .lgtico-blog-meta .date-meta' => 'color: {{VALUE}};',
            ],
        ] );
        
        $this->add_control( 'category_color', [
            'label'     => __( 'Meta Category Color', 'themepaw-companion' ),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .lgtico-blog-meta .category-meta' => 'color: {{VALUE}};',
            ],
        ] );

        $this->add_group_control( Group_Control_Typography::get_type(), [
            'name'     => 'meta_typography',
            'selector' => '{{WRAPPER}} .lgtico-blog-meta a',
        ] );


        $this->add_group_control( Group_Control_Border::get_type(), [
            'name'     => 'meta_wrap_border',
            'selector' => '{{WRAPPER}} .lgtico-blog-meta',
        ] );

        $this->add_responsive_control(
			'meta_padding',
			[
				'label' => __( 'Padding', 'themepaw-companion' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .lgtico-blog-meta' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
        );
        
        $this->add_responsive_control(
			'meta_marging',
			[
				'label' => __( 'Margin', 'themepaw-companion' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .lgtico-blog-meta' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
        );

        $this->add_control(
			'meta_stretch',
			[
				'label' => __( 'Stretch', 'themepaw-companion' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'themepaw-companion' ),
                'label_off' => __( 'No', 'themepaw-companion' ),
            ]
        );

        $this->end_controls_section();
        
        $this->start_controls_section( 'item_footer', [
            'label' => __( 'Footer', 'themepaw-companion' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ] );

        $this->add_group_control( Group_Control_Typography::get_type(), [
            'name'     => 'comment_typo',
            'selector' => '{{WRAPPER}} .lgtico-comment a',
        ] );

        $this->add_group_control( Group_Control_Border::get_type(), [
            'name'     => 'footer_wrap_border',
            'selector' => '{{WRAPPER}} .lgtico-blog-footer a',
        ] );

        $this->add_responsive_control(
			'footer_padding',
			[
				'label' => __( 'Padding', 'themepaw-companion' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .lgtico-blog-footer' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
        );
        
        $this->add_responsive_control(
			'footer_marging',
			[
				'label' => __( 'Margin', 'themepaw-companion' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .lgtico-blog-footer' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
        );

        $this->add_control(
			'footer_stretch',
			[
				'label' => __( 'Stretch', 'themepaw-companion' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'themepaw-companion' ),
                'label_off' => __( 'No', 'themepaw-companion' ),
            ]
        );


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


    public function cat_settings(){
        
        $this->start_controls_section(
            'post_cat_settings',
            [
                'label' => __('Category Settings', 'themepaw-companion'),
                'conditions'   => [
                    'terms' => [
                        [
                            'relation' => 'or',
                            'terms'    => [
                                [
                                    'name'  => 'left_meta',
                                    'value' => 'category',
                                ],
                                [
                                    'name'  => 'right_meta',
                                    'value' => 'category',
                                ],
                                [
                                    'name'  => 'left_footer',
                                    'value' => 'category',
                                ],
                                [
                                    'name'  => 'right_footer',
                                    'value' => 'category',
                                ]
                            ],
                        ],
                    ],
                ],
            ]
        );


        $this->add_control(
            'cat_title_option',
            [
                'label' => __('Category Title', 'themepaw-companion'),
                'type' => Controls_Manager::SELECT,
                'default' => 'none',
				'options' => [
                    'none' => __( 'none', 'themepaw-companion' ),
                    'text' => __( 'Text', 'themepaw-companion' ),
                    'icon' => __( 'icon', 'themepaw-companion' ),
					'dot' => __( 'dot', 'themepaw-companion' ),
					'dash' => __( 'dash', 'themepaw-companion' ),
                ],
            ]
        );

        $this->add_control(
            'cat_title',
            [
                'label' => __('Title Text', 'themepaw-companion'),
                'type' => Controls_Manager::TEXT,
                'condition'   => [
					'cat_title_option' => 'text',
				]
            ]
        );
        $this->add_control(
            'cat_icon',
            [
                'label' => esc_html__( 'Icon', 'themepaw-companion' ),
                'type' => Controls_Manager::ICON,
                'default' => 'fa fa-building-o',
                'condition' => [
                    'cat_title_option' => 'icon'
                ]
            ]
        );
        $this->add_control(
            'cat_before_color',
            [
                'label' => esc_html__( 'Beofre Color', 'themepaw-companion' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tpaw_blog_cat_icon, {{WRAPPER}} .tpaw_blog_cat_title' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tpaw_blog_cat_dot, {{WRAPPER}} .tpaw_blog_cat_dash' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
			'cat_before_spacing',
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
					'{{WRAPPER}} .tpaw_blog_cat_icon, {{WRAPPER}} .tpaw_blog_cat_title, {{WRAPPER}} .tpaw_blog_cat_dot, {{WRAPPER}} .tpaw_blog_cat_dash' => 'margin-right: {{SIZE}}{{UNIT}};',
				]
			]
		);

        $this->end_controls_section();
    }

    public function tag_settings(){
        
        $this->start_controls_section(
            'post_tag_settings',
            [
                'label' => __('Tag Settings', 'themepaw-companion'),
                'conditions'   => [
                    'terms' => [
                        [
                            'relation' => 'or',
                            'terms'    => [
                                [
                                    'name'  => 'left_meta',
                                    'value' => 'tag',
                                ],
                                [
                                    'name'  => 'right_meta',
                                    'value' => 'tag',
                                ],
                                [
                                    'name'  => 'left_footer',
                                    'value' => 'tag',
                                ],
                                [
                                    'name'  => 'right_footer',
                                    'value' => 'tag',
                                ]
                            ],
                        ],
                    ],
                ],
            ]
        );


        $this->add_control(
            'tag_title_option',
            [
                'label' => __('Tag Title', 'themepaw-companion'),
                'type' => Controls_Manager::SELECT,
                'default' => 'none',
				'options' => [
                    'none' => __( 'none', 'themepaw-companion' ),
                    'text' => __( 'Text', 'themepaw-companion' ),
                    'icon' => __( 'icon', 'themepaw-companion' ),
					'dot' => __( 'dot', 'themepaw-companion' ),
					'dash' => __( 'dash', 'themepaw-companion' ),
                ],
            ]
        );

        $this->add_control(
            'tag_title',
            [
                'label' => __('Title Text', 'themepaw-companion'),
                'type' => Controls_Manager::TEXT,
                'condition'   => [
					'tag_title_option' => 'text',
				]
            ]
        );
        $this->add_control(
            'tag_icon',
            [
                'label' => esc_html__( 'Icon', 'themepaw-companion' ),
                'type' => Controls_Manager::ICON,
                'default' => 'fa fa-building-o',
                'condition' => [
                    'tag_title_option' => 'icon'
                ]
            ]
        );
        
        $this->add_control(
            'tag_before_color',
            [
                'label' => esc_html__( 'Beofre Color', 'themepaw-companion' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tpaw_blog_tag_icon, {{WRAPPER}} .tpaw_blog_tag_title' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tpaw_blog_tag_dot, {{WRAPPER}} .tpaw_blog_tag_dash' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
			'tag_before_spacing',
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
					'{{WRAPPER}} .tpaw_blog_tag_icon, {{WRAPPER}} .tpaw_blog_tag_title, {{WRAPPER}} .tpaw_blog_tag_dot, {{WRAPPER}} .tpaw_blog_tag_dash' => 'margin-right: {{SIZE}}{{UNIT}};',
				]
			]
		);

        $this->end_controls_section();
    }

    public function author_settings(){
        
        $this->start_controls_section(
            'post_author_settings',
            [
                'label' => __('Author Settings', 'themepaw-companion'),
                'conditions'   => [
                    'terms' => [
                        [
                            'relation' => 'or',
                            'terms'    => [
                                [
                                    'name'  => 'left_meta',
                                    'value' => 'author',
                                ],
                                [
                                    'name'  => 'right_meta',
                                    'value' => 'author',
                                ],
                                [
                                    'name'  => 'left_footer',
                                    'value' => 'author',
                                ],
                                [
                                    'name'  => 'right_footer',
                                    'value' => 'author',
                                ]
                            ],
                        ],
                    ],
                ],
            ]
        );


        $this->add_control(
            'author_title_option',
            [
                'label' => __('Author Title', 'themepaw-companion'),
                'type' => Controls_Manager::SELECT,
                'default' => 'none',
				'options' => [
                    'none' => __( 'none', 'themepaw-companion' ),
                    'text' => __( 'Text', 'themepaw-companion' ),
                    'icon' => __( 'icon', 'themepaw-companion' ),
					'dot' => __( 'dot', 'themepaw-companion' ),
					'dash' => __( 'dash', 'themepaw-companion' ),
                ],
            ]
        );

        $this->add_control(
            'author_title',
            [
                'label' => __('Title Text', 'themepaw-companion'),
                'type' => Controls_Manager::TEXT,
                'condition'   => [
					'author_title_option' => 'text',
				]
            ]
        );
        $this->add_control(
            'author_icon',
            [
                'label' => esc_html__( 'Icon', 'themepaw-companion' ),
                'type' => Controls_Manager::ICON,
                'default' => 'fa fa-building-o',
                'condition' => [
                    'author_title_option' => 'icon'
                ]
            ]
        );
        
        $this->add_control(
            'author_before_color',
            [
                'label' => esc_html__( 'Beofre Color', 'themepaw-companion' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tpaw_blog_author_icon, {{WRAPPER}} .tpaw_blog_author_title' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tpaw_blog_author_dot, {{WRAPPER}} .tpaw_blog_author_dash' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
			'author_before_spacing',
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
					'{{WRAPPER}} .tpaw_blog_author_icon, {{WRAPPER}} .tpaw_blog_author_title, {{WRAPPER}} .tpaw_blog_author_dot, {{WRAPPER}} .tpaw_blog_author_dash' => 'margin-right: {{SIZE}}{{UNIT}};',
				]
			]
		);

        $this->end_controls_section();
    }

    public function comment_settings(){
        
        $this->start_controls_section(
            'post_comment_settings',
            [
                'label' => __('Comment Number Settings', 'themepaw-companion'),
                'conditions'   => [
                    'terms' => [
                        [
                            'relation' => 'or',
                            'terms'    => [
                                [
                                    'name'  => 'left_meta',
                                    'value' => 'comment',
                                ],
                                [
                                    'name'  => 'right_meta',
                                    'value' => 'comment',
                                ],
                                [
                                    'name'  => 'left_footer',
                                    'value' => 'comment',
                                ],
                                [
                                    'name'  => 'right_footer',
                                    'value' => 'comment',
                                ]
                            ],
                        ],
                    ],
                ],
            ]
        );


        $this->add_control(
            'comment_title_option',
            [
                'label' => __('Comment Number Title', 'themepaw-companion'),
                'type' => Controls_Manager::SELECT,
                'default' => 'none',
				'options' => [
                    'none' => __( 'none', 'themepaw-companion' ),
                    'text' => __( 'Text', 'themepaw-companion' ),
                    'icon' => __( 'icon', 'themepaw-companion' ),
					'dot' => __( 'dot', 'themepaw-companion' ),
					'dash' => __( 'dash', 'themepaw-companion' ),
                ],
            ]
        );

        $this->add_control(
            'comment_title',
            [
                'label' => __('Title Text', 'themepaw-companion'),
                'type' => Controls_Manager::TEXT,
                'condition'   => [
					'comment_title_option' => 'text',
				]
            ]
        );
        $this->add_control(
            'comment_icon',
            [
                'label' => esc_html__( 'Icon', 'themepaw-companion' ),
                'type' => Controls_Manager::ICON,
                'default' => 'fa fa-building-o',
                'condition' => [
                    'comment_title_option' => 'icon'
                ]
            ]
        );
        
        $this->add_control(
            'comment_before_color',
            [
                'label' => esc_html__( 'Beofre Color', 'themepaw-companion' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tpaw_blog_comment_icon, {{WRAPPER}} .tpaw_blog_comment_title' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tpaw_blog_comment_dot, {{WRAPPER}} .tpaw_blog_comment_dash' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
			'comment_before_spacing',
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
					'{{WRAPPER}} .tpaw_blog_comment_icon, {{WRAPPER}} .tpaw_blog_comment_title, {{WRAPPER}} .tpaw_blog_comment_dot, {{WRAPPER}} .tpaw_blog_comment_dash' => 'margin-right: {{SIZE}}{{UNIT}};',
				]
			]
		);

        $this->end_controls_section();
    }

    public function date_settings(){
        
        $this->start_controls_section(
            'post_date_settings',
            [
                'label' => __('Date Settings', 'themepaw-companion'),
                'conditions'   => [
                    'terms' => [
                        [
                            'relation' => 'or',
                            'terms'    => [
                                [
                                    'name'  => 'left_meta',
                                    'value' => 'date',
                                ],
                                [
                                    'name'  => 'right_meta',
                                    'value' => 'date',
                                ],
                                [
                                    'name'  => 'left_footer',
                                    'value' => 'date',
                                ],
                                [
                                    'name'  => 'right_footer',
                                    'value' => 'date',
                                ]
                            ],
                        ],
                    ],
                ],
            ]
        );


        $this->add_control(
            'date_title_option',
            [
                'label' => __('Date Title', 'themepaw-companion'),
                'type' => Controls_Manager::SELECT,
                'default' => 'none',
				'options' => [
                    'none' => __( 'none', 'themepaw-companion' ),
                    'text' => __( 'Text', 'themepaw-companion' ),
                    'icon' => __( 'icon', 'themepaw-companion' ),
					'dot' => __( 'dot', 'themepaw-companion' ),
					'dash' => __( 'dash', 'themepaw-companion' ),
                ],
            ]
        );

        $this->add_control(
            'date_title',
            [
                'label' => __('Title Text', 'themepaw-companion'),
                'type' => Controls_Manager::TEXT,
                'condition'   => [
					'date_title_option' => 'text',
				]
            ]
        );
        $this->add_control(
            'date_icon',
            [
                'label' => esc_html__( 'Icon', 'themepaw-companion' ),
                'type' => Controls_Manager::ICON,
                'default' => 'fa fa-building-o',
                'condition' => [
                    'date_title_option' => 'icon'
                ]
            ]
        );
        
        $this->add_control(
            'date_before_color',
            [
                'label' => esc_html__( 'Beofre Color', 'themepaw-companion' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tpaw_blog_date_icon, {{WRAPPER}} .tpaw_blog_date_title' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .tpaw_blog_date_dot, {{WRAPPER}} .tpaw_blog_date_dash' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
			'date_before_spacing',
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
					'{{WRAPPER}} .tpaw_blog_date_icon, {{WRAPPER}} .tpaw_blog_date_title, {{WRAPPER}} .tpaw_blog_date_dot, {{WRAPPER}} .tpaw_blog_date_dash' => 'margin-right: {{SIZE}}{{UNIT}};',
				]
			]
		);

        $this->end_controls_section();
    }
    
    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $settings = apply_filters( 'tpaw_testimonial_blog_' . $this->get_id() . '_settings', $settings );

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

        $this->add_render_attribute( 'wrapper', 'class', 'lgtico-blog-wrap' );
        $this->add_render_attribute( 'wrapper', 'class', 'lgtico-blog-three-column' );
        $this->add_render_attribute( 'main-wrapper', 'class', 'owl-carousel' );
        $this->add_render_attribute( 'main-wrapper', 'class', 'lgtico-single-slider' );

        if ( 'first_title' === $settings['order_position'] ) {
            $this->add_render_attribute( 'wrapper', 'class', 'lgtico-first-title-blog-item' );
        }

        $post_type = 'post';
        $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

        $query_args = [
            'orderby' => $settings['orderby'],
            'order' => $settings['order'],
            'ignore_sticky_posts' => 1,
            'paged' => $paged,
            'post_status' => 'publish', // Hide drafts/private posts for admins
        ];

        $query_args['post_type'] = $post_type;
        $query_args['posts_per_page'] = -1;
        $query_args['tax_query'] = [];

        $query_args['offset'] = $settings['offset'];

        // posts_per_page
        if ( !empty($settings['posts_per_page']) ) {
            $query_args['posts_per_page'] = (int)$settings['posts_per_page'];
        }

        // get_type
        if ( 'selected' === $settings['post_by'] ) {
            $query_args['post__in'] = (array)$settings['post__in'];
        }
        if ( 'category' === $settings['post_by'] ) {
            foreach( (array)$settings['category'] as $cat ){
                $query_args['tax_query'][] = array(
                    array(
                        'taxonomy' => 'category',
                        'field'    => 'slug',
                        'terms'    => $cat,
                    )
                );
            }
            $query_args['tax_query']['relation'] = (!empty($settings['relation'])) ? $settings['relation'] : 'AND';
        }
        if ( 'post_tag' === $settings['post_by'] ) {
            foreach( (array)$settings['post_tag'] as $tag ){
                $query_args['tax_query'][] = array(
                    array(
                        'taxonomy' => 'post_tag',
                        'field'    => 'slug',
                        'terms'    => $tag,
                    )
                );
            }
            $query_args['tax_query']['relation'] = (!empty($settings['relation'])) ? $settings['relation'] : 'AND';
        }
        
        if ( 'featured' === $settings['post_by'] ) {
            $sticky = get_option( 'sticky_posts' );
            $query_args['post__in'] = $sticky;
        }

        $the_query = new \WP_Query( $query_args );
        if ( $the_query->have_posts() ) :
        
        echo ( $settings['type'] === 'grid' ) ? '<div class="bootstrap-wrapper"><div class="row">' : '<div '.$this->get_render_attribute_string( 'main-wrapper' ).' data-settings=\'' . wp_json_encode($slider_options) . '\'>';
        while ( $the_query->have_posts() ) : $the_query->the_post(); 
        echo ( $settings['type'] === 'grid' ) ? '<div class="lgtico-service-single-item '.tpaw_companion_get_grid_classes($settings, 'columns_number').' tpaw-blog-item-'.get_the_ID().'">' : '<div class="lgtico-service-single-item tpaw-blog-item-'.get_the_ID().'">'; ?>
        <div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
            <?php $this->item_render(); ?>
        </div>
        <?php
        echo ( $settings['type'] === 'grid' ) ? '</div>' : '</div>';
        endwhile;
        wp_reset_postdata();
        echo ( $settings['type'] === 'grid' ) ? '</div></div>' : '</div>';
        endif;
    }

    public function item_title(){
        $settings = $this->get_settings_for_display();
        if( '0' !== $settings['show_title'] ) : ?>
        <div class="lgtico-blog-title">
            <a href="<?php the_permalink(); ?>"><h4><?php the_title(); ?></h4></a>
        </div>
        <?php endif;
    }

    public function item_thumbnail() {
        $settings = $this->get_settings_for_display();
        $thumb = wp_get_attachment_image_src( get_post_thumbnail_id() );
        if( isset($thumb[0]) && !empty($thumb[0]) && '0' !== $settings['show_thumbnail'] ) : ?>
        <div class="lgtico-blog-thumb">
            <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('tpaw_blog_thumbnail'); ?></a>
        </div>
        <?php endif;
    }

    public function item_meta(){
        $settings = $this->get_settings_for_display();
        $meta_class = array(
            'lgtico-blog-meta',
            'm-b-10'
        );
        if ( 'yes' === $settings['meta_stretch'] ) {
            $meta_class[] = 'tpaw-blog-wrap-stretch';
        }

        if( '0' !== $settings['show_meta'] ) : ?>
        <div class="<?php echo implode(" ", $meta_class); ?>">
            <?php $this->get_element($settings['left_meta']); ?>
            <?php $this->get_element($settings['right_meta']); ?>
        </div>
        <?php endif;
    }

    public function element_date(){
        $archive_year  = get_the_time('Y'); 
        $archive_month = get_the_time('m'); 
        $archive_day   = get_the_time('d'); 

        $settings = $this->get_settings_for_display();
        echo '<div class="tpaw-blog-single-elm">';
        if ( !empty($settings['date_title_option']) && 'text' === $settings['date_title_option'] ) {
            echo '<span class="tpaw_blog_date_title">'.esc_html( $settings['date_title'] ).'</span>';
        } elseif( !empty($settings['date_title_option']) && 'icon' === $settings['date_title_option'] ) {
            echo '<i class="tpaw_blog_date_icon '.esc_attr( $settings['date_icon'] ).'"></i>';
        }  elseif( !empty($settings['date_title_option']) && 'dot' === $settings['date_title_option'] ) {
            echo '<span class="tpaw_blog_date_dot"></span>';
        }  elseif( !empty($settings['date_title_option']) && 'dash' === $settings['date_title_option'] ) {
            echo '<span class="tpaw_blog_date_dash"></span>';
        }
        ?>
        <a class="date-meta" href="<?php echo get_day_link( $archive_year, $archive_month, $archive_day); ?>"><?php echo get_the_date(); ?></a>
        <?php
        echo '</div>';
    }
    
    public function element_category(){
        $categories = get_the_category();
        $settings = $this->get_settings_for_display();
        echo '<div class="tpaw-blog-single-elm">';
        if ( !empty($settings['cat_title_option']) && 'text' === $settings['cat_title_option'] ) {
            echo '<span class="tpaw_blog_cat_title">'.esc_html( $settings['cat_title'] ).'</span>';
        } elseif( !empty($settings['cat_title_option']) && 'icon' === $settings['cat_title_option'] ) {
            echo '<i class="tpaw_blog_cat_icon '.esc_attr( $settings['cat_icon'] ).'"></i>';
        }  elseif( !empty($settings['cat_title_option']) && 'dot' === $settings['cat_title_option'] ) {
            echo '<span class="tpaw_blog_cat_dot"></span>';
        }  elseif( !empty($settings['cat_title_option']) && 'dash' === $settings['cat_title_option'] ) {
            echo '<span class="tpaw_blog_cat_dash"></span>';
        }
        ?>
        <a class="category-meta" href="<?php echo esc_url( get_category_link( $categories[0]->term_id ) ); ?>"><?php echo esc_html( $categories[0]->name ); ?></a>
        <?php
        echo '</div>';
    }

    public function element_tag(){
        $tag = get_the_tags();
        $settings = $this->get_settings_for_display();
        echo '<div class="tpaw-blog-single-elm">';
        if ( !empty($settings['tag_title_option']) && 'text' === $settings['tag_title_option'] ) {
            echo '<spanclass="tpaw_blog_tag_title">'.esc_html( $settings['tag_title'] ).'</span>';
        } elseif( !empty($settings['tag_title_option']) && 'icon' === $settings['tag_title_option'] ) {
            echo '<i class="tpaw_blog_tag_icon '.esc_attr( $settings['tag_icon'] ).'"></i>';
        }  elseif( !empty($settings['tag_title_option']) && 'dot' === $settings['tag_title_option'] ) {
            echo '<span class="tpaw_blog_tag_dot"></span>';
        }  elseif( !empty($settings['tag_title_option']) && 'dash' === $settings['tag_title_option'] ) {
            echo '<span class="tpaw_blog_tag_dash"></span>';
        }
        ?>
        <a class="category-meta" href="<?php echo esc_url( get_tag_link( $tag[0]->term_id ) ); ?>"><?php echo esc_html( $tag[0]->name ); ?></a>
        <?php
        echo '</div>';
    }
    
    public function element_author(){
        $tag = get_the_tags();
        $settings = $this->get_settings_for_display();
        echo '<div class="tpaw-blog-single-elm">';
        if ( !empty($settings['author_title_option']) && 'text' === $settings['author_title_option'] ) {
            echo '<span class="tpaw_blog_author_title">'.esc_html( $settings['author_title'] ).'</span>';
        } elseif( !empty($settings['author_title_option']) && 'icon' === $settings['author_title_option'] ) {
            echo '<i class="tpaw_blog_author_icon '.esc_attr( $settings['author_icon'] ).'"></i>';
        }  elseif( !empty($settings['author_title_option']) && 'dot' === $settings['author_title_option'] ) {
            echo '<span class="tpaw_blog_author_dot"></span>';
        }  elseif( !empty($settings['author_title_option']) && 'dash' === $settings['author_title_option'] ) {
            echo '<span class="tpaw_blog_author_dash"></span>';
        }
        ?>
        <a class="author-meta" href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) ); ?>"><?php the_author(); ?></a>
        <?php
        echo '</div>';
    }

    public function element_readmore(){
        $settings = $this->get_settings_for_display();
        ?>
        <div class="tpaw-blog-single-elm tpw-companion-btn-wrap m-0 lgtico-button-<?php echo (!empty($settings['readmore_style'])) ? $settings['readmore_style'] : 'textual'; ?>">
            <a class="lgtico-button-link lgtico-button-link lgtico-button lgtico-default-btn" href="<?php the_permalink(); ?>"><?php echo (!empty($settings['readmore_text'])) ? $settings['readmore_text'] : 'Read More'; ?></a>
        </div>
        <?php
    }

    public function element_comment_num(){
        tpaw_comment_count();
    }

    public function get_element($element) {
        $settings = $this->get_settings_for_display();

        switch ($element) {
            case "date":
                $this->element_date();
                break;
            case "category":
                $this->element_category();
                break;
            case "tag":
                $this->element_tag();
                break;
            case "author":
                $this->element_author();
                break;
            case "comment":
                $this->element_comment_num();
                break;
            case "readmore":
            $this->element_readmore();
                break;
        }
    }

    public function item_render(){
        $settings = $this->get_settings_for_display();
        $order = array(
            'thumbnail',
            'title',
            'meta'
        );
        if ( 'second_meta' === $settings['order_position'] ) {
            $order = array(
                'thumbnail',
                'meta',
                'title'
            );
        } elseif ( 'first_title' === $settings['order_position'] ) {
            $order = array(
                'title',
                'thumbnail',
                'meta'
            );
        }
        $order = apply_filters('tpaw_blog_layout_order', $order);
        foreach ( (array) $order as $elelment ) {
            if ( 'thumbnail' === $elelment ) {
                $this->item_thumbnail();
            } elseif ( 'title' === $elelment ) {
                $this->item_title();
            }elseif ( 'meta' === $elelment ) {
                $this->item_meta();
            }
        }
        if( '0' !== $settings['show_excerpt'] ) :
        ?>
        <div class="lgtico-blog-article">
            <?php echo tpaw_post_excerpt(get_the_ID(), $settings['excerpt_length']); ?>
        </div>
        <?php 
        endif;
        $footer_class = array(
            'lgtico-blog-footer'
        );
        if ( 'yes' === $settings['footer_stretch'] ) {
            $footer_class[] = 'tpaw-blog-wrap-stretch';
        }
        if( '0' !== $settings['show_footer'] ) : ?>
        <div class="<?php echo implode(" ", $footer_class); ?>">
            <?php 
            $this->get_element($settings['left_footer']);
            $this->get_element($settings['right_footer']);
            ?>
        </div>
        <?php
        endif;
    }

}