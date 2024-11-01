<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
use  Elementor\Group_Control_Image_Size ;

/**
 * Get animation options
 */
function tpaw_companion_get_animation_options()
{
    return apply_filters( 'tpaw_animation_options', array(
        'none'        => __( 'None', 'themepaw-companion' ),
        'fadeIn'      => __( 'Fade In', 'themepaw-companion' ),
        'fadeInLeft'  => __( 'Fade In Left', 'themepaw-companion' ),
        'fadeInRight' => __( 'Fade In Right', 'themepaw-companion' ),
    ) );
}

/**
 * Return class form specific column for different divice width.
 */
function tpaw_companion_get_grid_classes( $settings, $columns_field = 'per_line' )
{
    $grid_classes = array();
    $grid_classes[] = 'col-lg-' . $settings[$columns_field];
    $grid_classes[] = 'col-md-' . $settings[$columns_field . '_tablet'];
    $grid_classes[] = 'col-sm-' . $settings[$columns_field . '_mobile'];
    $grid_classes = implode( ' ', $grid_classes );
    return apply_filters(
        'tpaw_grid_classes',
        $grid_classes,
        $settings,
        $columns_field
    );
}

/**
 * Get proper image markup.
 */
function tpaw_companion_get_image_html( $image_setting, $image_size_key, $classes = '' )
{
    $image_html = '';
    $attachment_id = $image_setting['id'];
    $image_class = 'tpaw-image';

    $thumbnail = wp_get_attachment_image_src( $attachment_id , $image_size_key);

    if ( !empty($classes) ) {
        $image_class .= ' ' . $classes;
    }
    
    $image_class_html = ( !empty($image_class) ? ' class="' . $image_class . '"' : '' );

    if ( isset($image_setting['id']) && empty($image_setting['id']) && !empty($image_setting['url']) ) {
        $image_html .= sprintf(
            '<img src="%s" %s />',
            esc_attr( $image_setting['url']),
            $image_class_html
        );
        return $image_html;
    }


    if ( empty($thumbnail) )
        return;

    $image_html .= sprintf(
        '<img src="%s" title="%s" alt="%s"%s />',
        esc_attr( $thumbnail[0] ),
        get_the_title( $attachment_id ),
        tpaw_companion_get_image_alt( $attachment_id ),
        $image_class_html
    );
    
    return apply_filters(
        'tpaw_attachment_image_html',
        $image_html,
        $image_setting,
        $image_size_key
    );
}

/**
 * Get attachment img alt.
 */
function tpaw_companion_get_image_alt( $attachment_id )
{
    if ( empty($attachment_id) ) {
        return '';
    }
    if ( !$attachment_id ) {
        return '';
    }
    $attachment = get_post( $attachment_id );
    if ( !$attachment ) {
        return '';
    }
    $alt = get_post_meta( $attachment_id, '_wp_attachment_image_alt', true );
    
    if ( !$alt ) {
        $alt = $attachment->post_excerpt;
        if ( !$alt ) {
            $alt = $attachment->post_title;
        }
    }
    
    $alt = trim( strip_tags( $alt ) );
    return apply_filters( 'lae_image_alt', $alt, $attachment_id );
}


/**
 * tpw_companion wpautop and more
 *
 * @since 1.0
 *
 * @param string $data
 *
 * @return string
 */
if ( ! function_exists( 'tpaw_companion_get_meta' ) ) 
{
    function tpaw_companion_get_meta( $data ) 
    {
	    global $wp_embed;

	    $content = $wp_embed->autoembed( $data );
	    $content = $wp_embed->run_shortcode( $content );
	    $content = do_shortcode( $content );
	    $content = wpautop( $content );

	    return $content;
	}
}


/**
 * Get taxonomy list
 * 
 * @since 1.0
 * 
 * @param string $taxonomy
 * 
 * @return array
 */
if ( ! function_exists( 'tpaw_taxomony_list' ) ) 
{
    function tpaw_taxomony_list($taxonomy = 'category') 
    {

        $taxonomy_exist = taxonomy_exists($taxonomy);
        if (!$taxonomy_exist) {
            return;
        }
        $terms = get_terms( array( 
            'taxonomy' => $taxonomy,
            'hide_empty' => 1
        ) );

        $get_terms = array();

        if ( !empty($terms) ) {
            foreach( $terms as $term ) :
                $get_terms[$term->slug] = $term->name;
            endforeach;
        }
        
        return $get_terms;
    }
}

/**
 * Get Author list
 * 
 * @since 1.0
 * 
 * @return array
 */
if ( ! function_exists( 'tpaw_get_authors' ) ) 
{
    function tpaw_get_authors()
    {
        $user_query = new \WP_User_Query(
            [
                'who' => 'authors',
                'has_published_posts' => true,
                'fields' => [
                    'ID',
                    'display_name',
                ],
            ]
        );

        $authors = [];

        foreach ($user_query->get_results() as $result) {
            $authors[$result->ID] = $result->display_name;
        }

        return $authors;
    }
}

/**
 * Get Posts
 * 
 * @since 1.0
 * 
 * @return array
 */
if ( ! function_exists( 'tpaw_get_all_posts' ) ) {
    function tpaw_get_all_posts($posttype = 'post')
    {
        $args = array(
            'post_type' => $posttype, 
            'post_status' => 'publish', 
            'posts_per_page' => -1
        );

        $post_list = array();
        if( $data = get_posts($args)){
            foreach($data as $key){
                $post_list[$key->ID] = $key->post_title;
            }
        }
        return  $post_list;
    }
}

/**
 * Post orderby list
 */
function tpaw_get_post_orderby_options()
{
    $orderby = array(
        'ID' => 'Post ID',
        'author' => 'Post Author',
        'title' => 'Title',
        'date' => 'Date',
        'modified' => 'Last Modified Date',
        'parent' => 'Parent Id',
        'rand' => 'Random',
        'comment_count' => 'Comment Count',
        'menu_order' => 'Menu Order',
    );

    $orderby = apply_filters('tpaw_post_orderby', $orderby);

    return $orderby;
}

function tpaw_get_thumbnail_sizes()
{
    $sizes = get_intermediate_image_sizes();
    foreach ($sizes as $s) {
        $ret[$s] = $s;
    }
    return $ret;
}


function tpaw_get_query_args($settings)
{
    $defaults = [
        'orderby' => 'date',
        'order' => 'desc',
        'posts_per_page' => 3,
        'offset' => 0,
    ];

    $settings = wp_parse_args($settings, $defaults);

    $post_type = 'post';

    $query_args = [
        'orderby' => $settings['orderby'],
        'order' => $settings['order'],
        'ignore_sticky_posts' => 1,
        'post_status' => 'publish', // Hide drafts/private posts for admins
    ];

    $query_args['post_type'] = $post_type;
    $query_args['posts_per_page'] = $settings['posts_per_page'];
    $query_args['tax_query'] = [];

    $query_args['offset'] = $settings['offset'];

    $taxonomies = get_object_taxonomies($post_type, 'objects');

    foreach ($taxonomies as $object) {
        $setting_key = $control_id . '_' . $object->name . '_ids';

        if (!empty($settings[$setting_key])) {
            $query_args['tax_query'][] = [
                'taxonomy' => $object->name,
                'field' => 'term_id',
                'terms' => $settings[$setting_key],
            ];
        }
    }

    if (!empty($settings[$control_id . '_authors'])) {
        $query_args['author__in'] = $settings[$control_id . '_authors'];
    }

    $post__not_in = [];
    if (!empty($settings['post__not_in'])) {
        $post__not_in = array_merge($post__not_in, $settings['post__not_in']);
        $query_args['post__not_in'] = $post__not_in;
    }

    if (isset($query_args['tax_query']) && count($query_args['tax_query']) > 1) {
        $query_args['tax_query']['relation'] = 'OR';
    }

    return $query_args;
}



if ( ! function_exists('tpaw_comment_count') ) :
/**
 * Comment count
 */
function tpaw_comment_count(){
    if ( post_password_required() || !( comments_open() || get_comments_number() ) ) {
        return;
    }
    ?>
    <div class="lgtico-comment">
        <a href="<?php comments_link(); ?>">
            <i class="ti-comment m-r-5"></i>
            <span><?php comments_number( '0', '1', '%' ); ?></span>
        </a>
    </div>
    <?php
}
endif;


if ( ! function_exists( 'tpaw_post_excerpt' ) ) :
/**
 * Display post post excerpt or content
 * *
 * @since 1.0
 */
function tpaw_post_excerpt($post_id, $length = 13) {
    
    $post_object = get_post( $post_id );

    // echo wp_trim_words( $post_object->post_content, (int)$length, '' );

    $excerpt = $post_object->post_excerpt;
    $content = $post_object->post_content;

    if ( !empty($excerpt)  && strlen(trim($excerpt)) != 0) {
        echo '<p>' . wp_trim_words( $excerpt, (int)$length, '' ) . '</p>';
    } else {
        echo '<p>' . wp_trim_words( $content, (int)$length, '' ) . '</p>';
    }

}
endif;