<?php
/*
 * Plugin Name: B Chart
 * Plugin URI:  https://bplugins.com/
 * Description: Easily display interactive Data Chart.
 * Version: 1.0.1
 * Author: bPlugins
 * Author URI: http://bplugins.com
 * License: GPLv3
 * Text Domain:  b-chart
 * Domain Path:  /languages
*/

if (!defined('ABSPATH')) {
    exit;
}
// SOME INITIAL SETUP
define('BPBC_PLUGIN_DIR', plugin_dir_url( __FILE__ ));
define('BPBC_VER', '1.0.1');

// LOAD PLUGIN TEXT-DOMAIN
function bpbc_load_textdomain(){
    load_plugin_textdomain('b-chart', false, dirname(__FILE__) . "/languages");
}
add_action("plugins_loaded", 'bpbc_load_textdomain');

// CHART ASSETS
function bpbc_assets(){
    wp_register_script('bpbc-chart', BPBC_PLUGIN_DIR .'public/assets/js/chart.min.js', [], BPBC_VER, true );
    wp_register_script('bpbc-chart-config', BPBC_PLUGIN_DIR .'public/assets/js/chart-config.js', ['bpbc-chart'], BPBC_VER, true );
    wp_enqueue_script('bpbc-chart');
    wp_enqueue_script('bpbc-chart-config');

    // Style
    wp_register_style('bpbc-custom-style', plugin_dir_url(__FILE__) . 'public/assets/css/custom-style.css');
    wp_enqueue_style('bpbc-custom-style');

}
add_action('wp_enqueue_scripts', 'bpbc_assets');

// Additional admin style
function bpbc_admin_style()
{
    wp_register_style('bpbc-admin-style', plugin_dir_url(__FILE__) . 'public/assets/css/admin-style.css');
    wp_register_style('bpbc-readonly-style', plugin_dir_url(__FILE__) . 'public/assets/css/readonly.css');
    wp_enqueue_style('bpbc-admin-style');
    wp_enqueue_style('bpbc-readonly-style');
}
add_action('admin_enqueue_scripts', 'bpbc_admin_style');




function bpbc_shortcode( $atts ) {
	extract( shortcode_atts( array(
        'id'    => null
	), $atts ) ); ob_start(); ?>

    <!-- Chart Meta Data -->
    <?php  $chartDatas = get_post_meta( $id, '_bpbcbchart_', true ); ?>
    
    <div class="bChart_parent_contener">
        <!-- Chart Container -->
        <div class="bChart_container" id="bchart_id_<?php echo esc_attr($id); ?>" >
            <canvas class="bChart" width="400" height="400" data-bchart='<?php echo esc_attr(wp_json_encode($chartDatas) ); ?>'></canvas>
        </div>
    </div>
    <!-- Style  -->

    <style>
        <?php echo "#bchart_id_".esc_attr($id); ?>{
            width: <?php echo isset( $chartDatas['bchart_width']['width'] ) ? esc_attr( $chartDatas['bchart_width']['width'] ) : ''; ?>% !important;
        }

    </style>
  
<?php   

return ob_get_clean(); 

}
add_shortcode( 'bchart', 'bpbc_shortcode' );


// Custom post-type
function bpbc_post_type()
{
    $labels = array(
        'name'                  => __('B-Chart', 'b-chart'),
        'menu_name'             => __('B-Chart', 'b-chart'),
        'name_admin_bar'        => __('B-Chart', 'b-chart'),
        'add_new'               => __('Add New', 'b-chart'),
        'add_new_item'          => __('Add New ', 'b-chart'),
        'new_item'              => __('New Chart ', 'b-chart'),
        'edit_item'             => __('Edit Chart ', 'b-chart'),
        'view_item'             => __('View Chart ', 'b-chart'),
        'all_items'             => __('All Charts', 'b-chart'),
        'not_found'             => __('Sorry, we couldn\'t find the Feed you are looking for.')
    );
    $args = array(
        'labels'             => $labels,
        'description'        => __('B-Chart Options.', 'b-chart'),
        'public'             => false,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'menu_icon'          => 'dashicons-chart-line',
        'query_var'          => true,
        'rewrite'            => array('slug' => 'b-chart'),
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_position'      => 20,
        'supports'           => array('title'),
    );
    register_post_type('bchart', $args);
}
add_action('init', 'bpbc_post_type');


/*-------------------------------------------------------------------------------*/
/*   Include External Files
/*-------------------------------------------------------------------------------*/

// Option panel
require_once 'inc/codestar/csf-config.php';
if( class_exists( 'CSF' )) {
    require_once 'inc/bchart-options.php';
}

//
/*-------------------------------------------------------------------------------*/
/*   Additional Features
/*-------------------------------------------------------------------------------*/

// Hide & Disabled View, Quick Edit and Preview Button 
function bpbc_remove_row_actions($idtions)
{
    global $post;
    if ($post->post_type == 'bchart' ) {
        unset($idtions['view']);
        unset($idtions['inline hide-if-no-js']);
    }
    return $idtions;
}

if (is_admin()) {
    add_filter('post_row_actions', 'bpbc_remove_row_actions', 10, 2);
}

// HIDE everything in PUBLISH metabox except Move to Trash & PUBLISH button
function bpbc_hide_publishing_actions()
{
    $my_post_type = 'bchart';
    global $post;
    if ($post->post_type == $my_post_type) {
        echo '
            <style type="text/css">
                #misc-publishing-actions,
                #minor-publishing-actions{
                    display:none;
                }
            </style>
        ';
    }
}
add_action('admin_head-post.php', 'bpbc_hide_publishing_actions');
add_action('admin_head-post-new.php', 'bpbc_hide_publishing_actions');

/*-------------------------------------------------------------------------------*/
// Remove post update massage and link 
/*-------------------------------------------------------------------------------*/

function bpbc_updated_messages($messages)
{
    $messages['bchart'][1] = __('Chart Item updated ', 'bchart');
    return $messages;
}
add_filter('post_updated_messages', 'bpbc_updated_messages');

/*-------------------------------------------------------------------------------*/
/* Change publish button to save.
/*-------------------------------------------------------------------------------*/
add_filter('gettext', 'bpbc_change_publish_button', 10, 2);
function bpbc_change_publish_button($translation, $text)
{
    if ('bchart' == get_post_type() )
        if ($text == 'Publish')
            return 'Save';

    return $translation;
}

/*-------------------------------------------------------------------------------*/
/* Footer Review Request .
/*-------------------------------------------------------------------------------*/

add_filter('admin_footer_text', 'bpbc_admin_footer');
function bpbc_admin_footer($text)
{
    if ('bchart' === get_post_type()) {
        $url = 'https://wordpress.org/plugins/b-chart/reviews/?filter=5#new-post';
        $text = sprintf(__('If you like <strong> B-Chart </strong> please leave us a <a href="%s" target="_blank">&#9733;&#9733;&#9733;&#9733;&#9733;</a> rating. Your Review is very important to us as it helps us to grow more. ', 'b-chart'), $url);
    }
    return $text;
}

/*-------------------------------------------------------------------------------*/
/* Shortcode Generator area  .
/*-------------------------------------------------------------------------------*/

add_action('edit_form_after_title', 'bpbc_shortcode_area');
function bpbc_shortcode_area()
{
    global $post;
    if ($post->post_type == 'bchart') : ?>

        <div class="bpbc_shortcode_gen">
            <label for="bpbc_shortcode"><?php esc_html_e('Copy this shortcode and paste it into your post, page, text widget content  or custom-template.php', 'b-chart') ?>:</label>

            <?php 
            
            echo '<span><input type="text" onfocus="this.select();" readonly="readonly" value="[bchart id=&quot;'. esc_attr($post->ID) .'&quot;]"></span><span>
            <input type="text" onfocus="this.select();" readonly="readonly" value="&#60;&#63;php echo do_shortcode( &#39;[bchart id=&quot;'. esc_attr($post->ID).'&quot;]&#39; ); &#63;&#62;" class="large-text code tlp-code-sc">
            </span>';
            ;
            ?>

        </div>
<?php endif; }


// CREATE TWO FUNCTIONS TO HANDLE THE COLUMN
add_filter('manage_bchart_posts_columns', 'bpbc_columns_head_only', 10);
add_action('manage_bchart_posts_custom_column', 'bpbc_columns_content_only', 10, 2);


// CREATE TWO FUNCTIONS TO HANDLE THE COLUMN
    function bpbc_columns_head_only($defaults) {
        unset($defaults['date']);
        $defaults['directors_name'] = 'ShortCode';
        $defaults['date'] = 'Date';
        return $defaults;
    }

    function bpbc_columns_content_only($column_name, $post_ID) {
        if ($column_name == 'directors_name') {
            echo '<div class="bpbc_front_shortcode"><input onfocus="this.select();" style="text-align: center; border: none; outline: none; background-color: #1e8cbe; color: #fff; padding: 4px 10px; border-radius: 3px;" value="[bchart  id='."'".esc_attr($post_ID)."'".']" ></div>';
    }
}