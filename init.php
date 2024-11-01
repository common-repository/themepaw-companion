<?php

namespace Elementrip;

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( !class_exists( 'ThemepawCompanionInit' ) ) {
    /**
     * Main ThemepawCompanionInit Class
     *
     */
    final class ThemepawCompanionInit
    {
        /** Singleton *************************************************************/
        private static  $instance ;
        private static $theme;
        private static $url;
        /**
         * Main ThemepawCompanionInit Instance
         *
         * Insures that only one instance of ThemepawCompanionInit exists in memory at any one
         * time. Also prevents needing to define globals all over the place.
         */
        public static function instance()
        {
            
            if ( !isset( self::$instance ) && !self::$instance instanceof ThemepawCompanionInit ) {
                self::$instance = new ThemepawCompanionInit();

                $theme = wp_get_theme()->get( 'Name' );
                $theme = strtolower( $theme );
                $theme = str_replace( ' ', '', $theme );
                self::$theme = $theme;
                self::$url = 'https://files.themepaw.com/'.self::$theme;

                self::$instance->check();
                self::$instance->hooks();
            }
            
            return self::$instance;
        }

        static function if_url_exists($url){
            $file_headers = @get_headers($url);
            if(!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found') {
                return false;
            }
            return true;
        }
        
        private function check()
        {
            if (!did_action('elementor/loaded')) {
                add_action('admin_notices', array($this, 'is_failed_to_load'));
            }

            if ( self::if_url_exists( self::$url ) ) {
                add_filter( 'pt-ocdi/import_files', array( $this, 'import_files' ) );
                add_filter( 'pt-ocdi/after_import', array( $this, 'demo_page_setting' ) );
            }
        }

        public function import_files()
        {
            $demo_content = array(
                array(
                  'import_file_name'             => 'Logistico Demo Data',
                  'import_file_url'            => self::$url.'/content.xml',
                  'import_widget_file_url'     => self::$url.'/widgets.wie',
                  'import_customizer_file_url' => self::$url.'/customizer.dat',
                  'import_notice'                => __( '<p><sub style="color: red;font-size: 2em;vertical-align: middle;top: 2px;position: relative;margin-right: 5px;">*</sub>Import process might take several minutes depending on your server configuration. Please wait till it shows confirmation message.</p><p></p>', 'themepaw-companion' ),
                  'preview_url'                  => 'http://demo.themepaw.com/'.self::$theme,
                )
            );
            $demo_content = apply_filters('themepaw_companion_demo_content', $demo_content);
            return $demo_content;
        }

        public function demo_page_setting() {

            // Assign menus to their locations.
            $main_menu 	= get_term_by( 'name', 'Main Menu', 'nav_menu' );
            $footer_menu 	= get_term_by( 'name', 'Quick Links', 'nav_menu' );

            $menu_init = array(
                'menu-1' => esc_attr( $main_menu->term_id ),
                'menu-2' => esc_attr( $footer_menu->term_id ),
            );

            $menu_init = apply_filters('themepaw_companion_demo_menu_init', $menu_init);

            set_theme_mod( 'nav_menu_locations', $menu_init);
    
            // Assign front page and posts page (blog page).
            $front_page = get_page_by_title( 'Home' );
            $blog_page = get_page_by_title( 'Blog' );

            $front_page_id = apply_filters('themepaw_companion_demo_front_page', $front_page->ID);
            $blog_page_id = apply_filters('themepaw_companion_demo_blog_page', $blog_page->ID);

            update_option( 'show_on_front', 'page' );
            update_option( 'page_on_front', esc_attr( $front_page_id ) );
            update_option( 'page_for_posts', esc_attr( $blog_page_id ) );

            do_action('themepaw_companion_demo_content_trigger');
    
        }
        
        /**
         * Throw error on object clone
         *
         * The whole idea of the singleton design pattern is that there is a single
         * object therefore, we don't want the object to be cloned.
         */
        public function __clone()
        {
            // Cloning instances of the class is forbidden
            _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'themepaw-companion' ), '0.1' );
        }
        
        /**
         * Disable unserializing of the class
         *
         */
        public function __wakeup()
        {
            // Unserializing instances of the class is forbidden
            _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'themepaw-companion' ), '0.1' );
        }
        
        /**
         * Load Plugin Text Domain
         *
         * Looks for the plugin translation files in certain directories and loads
         * them to allow the plugin to be localised
         */
        public function load_plugin_textdomain()
        {
            load_plugin_textdomain('themepaw-companion', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/');
        }

        public function is_elementor_active()
        {
            $file_path = 'elementor/elementor.php';
            if (!function_exists('get_plugins')) {
                include ABSPATH . '/wp-admin/includes/plugin.php';
            }
            $installed_plugins = get_plugins();
            return isset($installed_plugins[$file_path]);
        }

        /**
         * This notice will appear if Elementor is not installed or activated or both
         */
        public function is_failed_to_load()
        {
            $elementor = 'elementor/elementor.php';
            if ($this->is_elementor_active()) {
                if (!current_user_can('activate_plugins')) {
                    return;
                }
                $activation_url = wp_nonce_url('plugins.php?action=activate&amp;plugin=' . $elementor . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $elementor);
                $message = __('<strong>Themepaw Companion</strong> requires <strong>Elementor</strong> plugin to be active. Please activate Elementor to continue.', 'themepaw-companion');
                $button_text = __('Activate Elementor', 'themepaw-companion');
            } else {
                if (!current_user_can('activate_plugins')) {
                    return;
                }
                $activation_url = wp_nonce_url(self_admin_url('update.php?action=install-plugin&plugin=elementor'), 'install-plugin_elementor');
                $message = sprintf(__('<strong>Themepaw Companion</strong> requires <strong>Elementor</strong> plugin to be installed and activated. Please install Elementor to continue.', 'themepaw-companion'), '<strong>', '</strong>');
                $button_text = __('Install Elementor', 'themepaw-companion');
            }
            $button = '<p><a href="' . $activation_url . '" class="button-primary">' . $button_text . '</a></p>';
            printf('<div class="error"><p>%1$s</p>%2$s</div>', __($message), $button);
        }
        
        /**
         * Setup the default hooks and actions
         */
        private function hooks()
        {
            add_action( 'plugins_loaded', array( self::$instance, 'load_plugin_textdomain' ) );
            add_action( 'init', array( $this, 'add_new_image_size' ) );
            add_action( 'elementor/widgets/widgets_registered', array( self::$instance, 'include_widgets' ) );
            add_action( 'elementor/frontend/after_register_scripts', array( $this, 'register_frontend_scripts' ), 10 );
            add_action( 'wp_enqueue_scripts', array( $this, 'register_frontend_styles' ), 10 );
            add_action( 'elementor/frontend/after_enqueue_styles', array( $this, 'enqueue_frontend_styles' ), 40 );
            add_action( 'elementor/frontend/after_enqueue_scripts', array( $this, 'enqueue_frontend_scripts' ), 10 );
            add_action( 'elementor/init', array( $this, 'add_elementor_category' ) );
        }

        function add_new_image_size() {
            add_image_size( 'tpaw_blog_thumbnail', 450, 280, true ); // Blog Thumbnail size
        }
        
        public function add_elementor_category()
        {
            \Elementor\Plugin::instance()->elements_manager->add_category( 'themepaw-addons', array(
                'title' => __( 'Themepaw Companion Addons', 'themepaw-companion' ),
                'icon'  => 'fa fa-plug',
            ), 1 );
        }
                
        /**
         * Load Frontend Scripts
         *
         */
        public function register_frontend_scripts()
        {
            // Use minified libraries if TPAW_SCRIPT_DEBUG is turned off
            // $suffix = ( defined( 'TPAW_SCRIPT_DEBUG' ) && TPAW_SCRIPT_DEBUG ? '' : '.min' );
            $suffix = '';
            wp_register_script(
                'tpaw-init',
                THEMEPAW_COMPANION_URI . 'assets/js/tpaw-main' . $suffix . '.js',
                array('jquery'),
                THEMEPAW_COMPANION_VERSION,
                true
            );
            
            wp_register_script(
                'tpaw-owl-carousel2',
                THEMEPAW_COMPANION_URI . 'assets/js/owl.carousel' . $suffix . '.js',
                array('jquery'),
                THEMEPAW_COMPANION_VERSION,
                true
            );
            wp_register_script(
                'tpaw-slider-init',
                THEMEPAW_COMPANION_URI . 'assets/js/slider-init' . $suffix . '.js',
                array('jquery'),
                THEMEPAW_COMPANION_VERSION,
                true
            );
         
        }
        
        /**
         * Load Frontend Styles
         *
         */
        public function register_frontend_styles()
        {
            wp_register_style(
                'tpaw-companion-setup-styles',
                THEMEPAW_COMPANION_URI . 'assets/css/tpaw-companion-setup.css',
                array(),
                THEMEPAW_COMPANION_VERSION
            );

            global $wp_styles;
            if ( $wp_styles instanceof WP_Styles ) {
                // enumerate all current styles
                if ( !in_array('bootstrap', $wp_styles->queue) ) {
                    wp_enqueue_style(
                        'bootstrap',
                        THEMEPAW_COMPANION_URI . 'assets/vendor/bootstrap/css/bootstrap.css',
                        array(),
                        THEMEPAW_COMPANION_VERSION
                    );
                }
            }

            $bootstrap = 'bootstrap';
            if( ( ! wp_style_is( $bootstrap, 'queue' ) ) && ( ! wp_style_is( $bootstrap, 'done' ) ) ) {
                wp_enqueue_style(
                    $bootstrap,
                    THEMEPAW_COMPANION_URI . 'assets/vendor/bootstrap/css/bootstrap.css',
                    array(),
                    THEMEPAW_COMPANION_VERSION
                );
            }
            if ( !wp_style_is( 'themify-icons', 'registered' ) || !wp_style_is( 'themify-icons', 'enqueued' ) ) {
                wp_register_style(
                    'themify-icons',
                    THEMEPAW_COMPANION_URI . 'assets/vendor/themify/themify.css',
                    array(),
                    THEMEPAW_COMPANION_VERSION
                );
            }
            
            wp_register_style(
                'tpaw-companion-counter',
                THEMEPAW_COMPANION_URI . 'assets/css/tpaw-companion-counter.css',
                array( 'tpaw-companion-setup-styles' ),
                THEMEPAW_COMPANION_VERSION
            );

            wp_register_style(
                'tpaw-companion-button',
                THEMEPAW_COMPANION_URI . 'assets/css/tpaw-companion-button.css',
                array( 'tpaw-companion-setup-styles' ),
                THEMEPAW_COMPANION_VERSION
            );

            wp_register_style(
                'tpaw-companion-infobox',
                THEMEPAW_COMPANION_URI . 'assets/css/tpaw-companion-infobox.css',
                array( 'tpaw-companion-setup-styles' ),
                THEMEPAW_COMPANION_VERSION
            );
            wp_register_style(
                'tpaw-owl-carousel',
                THEMEPAW_COMPANION_URI . 'assets/css/tpaw-owl-carousel.css',
                array( 'tpaw-companion-setup-styles' ),
                THEMEPAW_COMPANION_VERSION
            );
            wp_register_style(
                'tpaw-testimonial',
                THEMEPAW_COMPANION_URI . 'assets/css/tpaw-companion-testimonial.css',
                array( 'tpaw-companion-setup-styles', 'tpaw-companion-infobox' ),
                THEMEPAW_COMPANION_VERSION
            );
            wp_register_style(
                'tpaw-blogpost',
                THEMEPAW_COMPANION_URI . 'assets/css/tpaw-companion-blog.css',
                array( 'tpaw-companion-setup-styles', 'tpaw-companion-infobox' ),
                THEMEPAW_COMPANION_VERSION
            );
        }
        
        /**
         * Load Frontend Styles
         *
         */
        public function enqueue_frontend_styles()
        {
            wp_enqueue_style( 'bootstrap' );
            wp_enqueue_style( 'tpaw-companion-setup-styles' );
            wp_enqueue_style( 'tpaw-companion-counter' );
            wp_enqueue_style( 'tpaw-companion-button' );
            wp_enqueue_style( 'tpaw-companion-infobox' );
            wp_enqueue_style( 'tpaw-owl-carousel' );
            wp_enqueue_style( 'themify-icons' );
            wp_enqueue_style( 'tpaw-testimonial' );
            wp_enqueue_style( 'tpaw-blogpost' );
        }
        /**
         * Load Frontend Styles
         *
         */
        public function enqueue_frontend_scripts()
        {
            wp_enqueue_script( 'jquery' );
        }
        
        /**
         * Include required files
         *
         */
        public function include_widgets()
        {
            $widgets_manager = \Elementor\Plugin::instance()->widgets_manager;
            
            require_once THEMEPAW_COMPANION_ADDONS_DIR . 'elementor/list-iconbox.php';
            $widgets_manager->register_widget_type( new \ThemepawCompanion\Widgets\Elementor\TPaw_List_Iconbox() );

            require_once THEMEPAW_COMPANION_ADDONS_DIR . 'elementor/counter.php';
            $widgets_manager->register_widget_type( new \ThemepawCompanion\Widgets\Elementor\TPaw_Counter() );

            require_once THEMEPAW_COMPANION_ADDONS_DIR . 'elementor/button.php';
            $widgets_manager->register_widget_type( new \ThemepawCompanion\Widgets\Elementor\TPaw_Button() );
            
            require_once THEMEPAW_COMPANION_ADDONS_DIR . 'elementor/testimonial-slider.php';
            $widgets_manager->register_widget_type( new \ThemepawCompanion\Widgets\Elementor\TPaw_TestimonialSlider() );
            
            require_once THEMEPAW_COMPANION_ADDONS_DIR . 'elementor/blog-posts.php';
            $widgets_manager->register_widget_type( new \ThemepawCompanion\Widgets\Elementor\TPaw_BlogPosts() );
            
            require_once THEMEPAW_COMPANION_ADDONS_DIR . 'elementor/list-infobox.php';
            $widgets_manager->register_widget_type( new \ThemepawCompanion\Widgets\Elementor\TPaw_List_Infobox() );
            $widgets_manager->register_widget_type( new \ThemepawCompanion\Widgets\Elementor\Elementor_Test_Widget() );

        }
    
    }

    /**
     *
     * Instance to functions everywhere.
     *
     * Use this function like you would a global variable, except without needing
     * to declare the global.
     *
     * Example: <?php $lae = ThemepawCompanion(); ?>
     */
    function ThemepawCompanion()
    {
        return ThemepawCompanionInit::instance();
    }
    
    // Get ELEMENTRIP Running
    ThemepawCompanion();
}

// End if class_exists check


