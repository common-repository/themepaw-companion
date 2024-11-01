<?php
/*
Widget Name: TPaw Infobox
Description: Infobox widget for Elementrip
Author: themepaw.com
Author URI: https://themepaw.com/plugins/companion
*/
namespace ThemepawCompanion\Widgets\Elementor;

use  Elementor\Widget_Base;
use  Elementor\Controls_Manager;
use  Elementor\utils;
use  Elementor\Scheme_Color;
use  Elementor\Group_Control_Typography;
use  Elementor\Group_Control_Border;
use  Elementor\Group_Control_Image_Size;
use  Elementor\Scheme_Typography;

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

// Exit if accessed directly.
// Exit if accessed directly.
class TPaw_Infobox extends Widget_Base
{
    public function get_name()
    {
        return 'tpaw_infobox_item';
    }
    
    public function get_title()
    {
        return __( 'List Infobox', 'themepaw-companion' );
    }
    
    public function get_icon()
    {
        return 'eicon-info-box';
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
        /**
  		 * Infobox Image Settings
  		 */
  		$this->start_controls_section(
            'tpaw_infobox_content_settings',
            [
                'label' => esc_html__( 'Infobox Image', 'themepaw-companion' )
            ]
        );

        $this->add_control(
        'tpaw_infobox_img_type',
            [
             'label'       	=> esc_html__( 'Infobox Type', 'themepaw-companion' ),
               'type' 			=> Controls_Manager::SELECT,
               'default' 		=> 'img-on-top',
               'label_block' 	=> false,
               'options' 		=> [
                   'img-on-top'  	=> esc_html__( 'Image/Icon On Top', 'themepaw-companion' ),
                   'img-on-left' 	=> esc_html__( 'Image/Icon On Left', 'themepaw-companion' ),
                   'img-on-right' 	=> esc_html__( 'Image/Icon On Right', 'themepaw-companion' ),
               ],
            ]
      );

      $this->add_responsive_control(
          'tpaw_infobox_thumb_type',
          [
              'label' => esc_html__( 'Image or Icon', 'themepaw-companion' ),
              'type' => Controls_Manager::CHOOSE,
              'label_block' => true,
              'options' => [
                  'none' => [
                      'title' => esc_html__( 'None', 'themepaw-companion' ),
                      'icon' => 'fa fa-ban',
                  ],
                  'number' => [
                      'title' => esc_html__( 'Number', 'themepaw-companion' ),
                      'icon' => 'fa fa-sort-numeric-desc',
                  ],
                  'icon' => [
                      'title' => esc_html__( 'Icon', 'themepaw-companion' ),
                      'icon' => 'fa fa-info-circle',
                  ],
                  'img' => [
                      'title' => esc_html__( 'Image', 'themepaw-companion' ),
                      'icon' => 'fa fa-picture-o',
                  ]
              ],
              'default' => 'icon',
          ]
      );

      $this->add_responsive_control(
          'icon_vertical_position',
          [
              'label'                 => __( 'Icon Position', 'themepaw-companion' ),
              'type'                  => Controls_Manager::CHOOSE,
              'default'               => 'top',
              'condition'			=> [
                  'tpaw_infobox_img_type!'	=> 'img-on-top'
              ],
              'options'               => [
                  'top'          => [
                      'title'    => __( 'Top', 'themepaw-companion' ),
                      'icon'     => 'eicon-v-align-top',
                  ],
                  'middle'       => [
                      'title'    => __( 'Middle', 'themepaw-companion' ),
                      'icon'     => 'eicon-v-align-middle',
                  ],
                  'bottom'       => [
                      'title'    => __( 'Bottom', 'themepaw-companion' ),
                      'icon'     => 'eicon-v-align-bottom',
                  ],
              ],
              'selectors'             => [
                  '{{WRAPPER}} .eael-infobox .infobox-icon'	=> 'align-self: {{VALUE}};'
              ],
              'selectors_dictionary'  => [
                  'top'          => 'baseline',
                  'middle'       => 'center',
                  'bottom'       => 'flex-end',
              ],
          ]
      );


      $this->add_control(
          'tpaw_infobox_image',
          [
              'label' => esc_html__( 'Infobox Image', 'themepaw-companion' ),
              'type' => Controls_Manager::MEDIA,
              'default' => [
                  'url' => Utils::get_placeholder_image_src(),
              ],
              'condition' => [
                  'tpaw_infobox_thumb_type' => 'img'
              ]
          ]
      );


      /**
       * Condition: 'tpaw_infobox_thumb_type' => 'icon'
       */
      $this->add_control(
          'tpaw_infobox_icon',
          [
              'label' => esc_html__( 'Icon', 'themepaw-companion' ),
              'type' => Controls_Manager::ICON,
              'default' => 'fa fa-building-o',
              'condition' => [
                  'tpaw_infobox_thumb_type' => 'icon'
              ]
          ]
      );

      /**
       * Condition: 'tpaw_infobox_thumb_type' => 'number'
       */
      $this->add_control(
          'tpaw_infobox_number',
          [
              'label' => esc_html__( 'Number', 'themepaw-companion' ),
              'type' => Controls_Manager::TEXT,
              'condition' => [
                  'tpaw_infobox_thumb_type' => 'number'
              ]
          ]
      );

      $this->end_controls_section();

      /**
       * Infobox Content
       */
      $this->start_controls_section(
          'tpaw_infobox_content',
          [
              'label' => esc_html__( 'Infobox Content', 'themepaw-companion' ),
          ]
      );
      $this->add_control(
          'tpaw_infobox_title',
          [
              'label' => esc_html__( 'Infobox Title', 'themepaw-companion' ),
              'type' => Controls_Manager::TEXT,
              'label_block' => true,
              'dynamic' => [
                  'active' => true
              ],
              'default' => esc_html__( 'This is an icon box', 'themepaw-companion' )
          ]
      );
      $this->add_control(
          'tpaw_infobox_text_type',
          [
              'label'                 => __( 'Content Type', 'themepaw-companion' ),
              'type'                  => Controls_Manager::SELECT,
              'options'               => [
                  'content'       => __( 'Content', 'themepaw-companion' ),
                  'template'      => __( 'Saved Templates', 'themepaw-companion' ),
              ],
              'default'               => 'content',
          ]
      );

      $this->add_control(
          'tpaw_primary_templates',
          [
              'label'                 => __( 'Choose Template', 'themepaw-companion' ),
              'type'                  => Controls_Manager::SELECT,
              'options'               => $this->tpaw_get_page_templates(),
              'condition'             => [
                  'tpaw_infobox_text_type'      => 'template',
              ],
          ]
      );
      $this->add_control(
          'tpaw_infobox_text',
          [
              'label' => esc_html__( 'Infobox Content', 'themepaw-companion' ),
              'type' => Controls_Manager::WYSIWYG,
              'label_block' => true,
              'dynamic' => [
                  'active' => true
              ],
              'default' => esc_html__( 'Write a short description, that will describe the title or something informational and useful.', 'themepaw-companion' ),
              'condition'             => [
                  'tpaw_infobox_text_type'      => 'content',
              ],
          ]
      );
      $this->add_control(
          'tpaw_show_infobox_content',
          [
              'label' => __( 'Show Content', 'themepaw-companion' ),
              'type' => Controls_Manager::SWITCHER,
              'default' => 'yes',
              'label_on' => __( 'Show', 'themepaw-companion' ),
              'label_off' => __( 'Hide', 'themepaw-companion' ),
              'return_value' => 'yes',
          ]
      );
      $this->add_responsive_control(
          'tpaw_infobox_content_alignment',
          [
              'label' => esc_html__( 'Content Alignment', 'themepaw-companion' ),
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
              'default' => 'center',
              'prefix_class' => 'eael-infobox-content-align-',
              'condition' => [
                  'tpaw_infobox_img_type' => 'img-on-top'
              ]
          ]
      );
      $this->end_controls_section();

      /**
       * ----------------------------------------------
       * Infobox Button
       * ----------------------------------------------
       */
      $this->start_controls_section(
          'tpaw_infobox_button',
          [
              'label' => esc_html__( 'Link', 'themepaw-companion' )
          ]
      );

      $this->add_control(
          'tpaw_show_infobox_button',
          [
              'label' => __( 'Show Infobox Button', 'themepaw-companion' ),
              'type' => Controls_Manager::SWITCHER,
              'label_on' => __( 'Yes', 'themepaw-companion' ),
              'label_off' => __( 'No', 'themepaw-companion' ),
              'condition'	=> [
                  'tpaw_show_infobox_clickable!'	=> 'yes'
              ]
          ]
      );

      $this->add_control(
          'tpaw_show_infobox_clickable',
          [
              'label' => __( 'Infobox Clickable', 'themepaw-companion' ),
              'type' => Controls_Manager::SWITCHER,
              'default' => 'no',
              'label_on' => __( 'Yes', 'themepaw-companion' ),
              'label_off' => __( 'No', 'themepaw-companion' ),
              'return_value' => 'yes',
              'condition'	=> [
                  'tpaw_show_infobox_button!'	=> 'yes'
              ]
          ]
      );

      $this->add_control(
          'tpaw_show_infobox_clickable_link',
          [
              'label' => esc_html__( 'Infobox Link', 'themepaw-companion' ),
              'type' => Controls_Manager::URL,
              'label_block' => true,
              'default' => [
                  'url' => 'http://',
                  'is_external' => '',
               ],
               'show_external' => true,
               'condition' => [
                   'tpaw_show_infobox_clickable' => 'yes'
               ]
          ]
      );

      $this->add_control(
          'infobox_button_text',
          [
              'label' => __( 'Button Text', 'themepaw-companion' ),
              'type' => Controls_Manager::TEXT,
              'label_block' => true,
              'default' => 'Click Me!',
              'separator'	=> 'before',
              'placeholder' => __( 'Enter button text', 'themepaw-companion' ),
              'title' => __( 'Enter button text here', 'themepaw-companion' ),
              'condition'	=> [
                  'tpaw_show_infobox_button'	=> 'yes'
              ]
          ]
      );

      $this->add_control(
          'infobox_button_link_url',
          [
              'label' => __( 'Link URL', 'themepaw-companion' ),
              'type' => Controls_Manager::URL,
              'label_block' => true,
              'placeholder' => __( 'Enter link URL for the button', 'themepaw-companion' ),
              'show_external'	=> true,
              'default'		=> [
                  'url'	=> '#'
              ],
              'title' => __( 'Enter heading for the button', 'themepaw-companion' ),
              'condition'	=> [
                  'tpaw_show_infobox_button'	=> 'yes'
              ]
          ]
      );
      
      $this->add_control(
          'tpaw_infobox_button_icon',
          [
              'label' => esc_html__( 'Icon', 'themepaw-companion' ),
              'type' => Controls_Manager::ICON,
              'condition'	=> [
                  'tpaw_show_infobox_button'	=> 'yes'
              ]
          ]
      );

      $this->add_control(
          'tpaw_infobox_button_icon_alignment',
          [
              'label' => esc_html__( 'Icon Position', 'themepaw-companion' ),
              'type' => Controls_Manager::SELECT,
              'default' => 'left',
              'options' => [
                  'left' => esc_html__( 'Before', 'themepaw-companion' ),
                  'right' => esc_html__( 'After', 'themepaw-companion' ),
              ],
              'condition' => [
                  'tpaw_infobox_button_icon!' => '',
                  'tpaw_show_infobox_button'	=> 'yes'
              ],
          ]
      );

      $this->add_control(
          'tpaw_infobox_button_icon_indent',
          [
              'label' => esc_html__( 'Icon Spacing', 'themepaw-companion' ),
              'type' => Controls_Manager::SLIDER,
              'range' => [
                  'px' => [
                      'max' => 60,
                  ],
              ],
              'condition' => [
                  'tpaw_infobox_button_icon!' => '',
                  'tpaw_show_infobox_button'	=> 'yes'
              ],
              'selectors' => [
                  '{{WRAPPER}} .tpaw_infobox_button_icon_right' => 'margin-left: {{SIZE}}px;',
                  '{{WRAPPER}} .tpaw_infobox_button_icon_left' => 'margin-right: {{SIZE}}px;',
              ],
          ]
      );
      $this->end_controls_section();

    }
    
    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $settings = apply_filters( 'tpaw_infobox_item_' . $this->get_id() . '_settings', $settings );

        $this->add_render_attribute( 'wrapper', 'class', 'lgtico-single-postlist' );
        $this->add_render_attribute( 'wrapper', 'class', 'lgtico-service-wrap' );
        $this->add_render_attribute( 'wrapper', 'class', 'lgtico-service-wrap-2' );
        $this->add_render_attribute( 'content', 'class', 'lgtico-service-content' );

        ob_start();
        ?>
        <div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
           
        </div>
        <?php
        echo ob_get_clean();
    }
    
}