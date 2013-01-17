<?php
/*
* Plugin Name: Golfshot Pro Widget
* Version: 0.1
* Plugin URI: http://themullers.org/mike
* Description: Displays information about a golfer from Golfshot Pro
* Author: Michael Muller
* Author URI: http://themullers.org/mike
*/
class GSP_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'GSP_widget', // Base ID
            'GSP Widget', // Name
            array( 'description' => __( 'GolfShot Pro Widget', 'text_domain' ),) // Args
        );
        add_action('wp_head', array(&$this, "serveHeader"));
        add_action('wp_enqueue_scripts', array(&$this, "initScripts"));
    }

    public function form( $instance ) {
        // outputs the options form on admin
    }

    public function update( $new_instance, $old_instance ) {
        // processes widget options to be saved
    }

    public function widget( $args, $instance ) {
        $loading_img_url = plugins_url('images/gsp-loading.gif', __FILE__);
        echo $before_widget;
        echo "<div class='gsp-widget'>";
        echo "<div class='gsp-logo'>&nbsp;</div>";
        echo "<div id='gsp-content' class='gsp-content'>";
        echo "<div class='gsp-loading'>&nbsp;</div>";
        echo "</div>";
        echo "<div class='gsp-footer'>&nbsp;</div>";
        echo "</div>";
        echo $after_widget;
    }

    function serveHeader() {
        $style = plugins_url(plugin_basename(dirname(__FILE__))) . '/style.css';
        echo <<<EOT
<link rel='stylesheet' type='text/css' media='all' href='$style'>
EOT;
    }
    
    function initScripts() {
        if (!is_admin()) {
            wp_enqueue_script('jquery');
        }
        wp_enqueue_script('gsp-widget-script', plugins_url('gsp-widget.js', __File__), array('jquery'));
    }
}

add_action( 'widgets_init', create_function( '', 'register_widget( "GSP_widget" );' ) );

?>
