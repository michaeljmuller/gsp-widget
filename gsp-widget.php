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
    }

    public function form( $instance ) {
        // outputs the options form on admin
    }

    public function update( $new_instance, $old_instance ) {
        // processes widget options to be saved
    }

    public function widget( $args, $instance ) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://golfshot.com/members/0025095730/rounds");
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);

        $scores = '';


		$rounds = array();

        foreach(preg_split("/((\r?\n)|(\r\n?))/", $result) as $line){
            if (preg_match("/round\('([\d\-]+)'\)/", $line, $matches)) {
                $scores .= $matches[1];
                $round['id'] = $matches[1];
            }
            if (preg_match('/score">(.*?)<\/td/', $line, $matches)) {
                $scores .= $matches[1];
                $round['score'] = $matches[1];
            }
            if (preg_match('/course">(.*?)<\/td/', $line, $matches)) {
                $scores .= $matches[1];
                $round['course'] = $matches[1];
            }
            if (preg_match('/date">(.*?)<\/td/', $line, $matches)) {
                $scores .= $matches[1];
                $round['date'] = $matches[1];
                $round['dateObj'] = DateTime::createFromFormat('m-j-y', $round['date']);
            }
            if (preg_match('/post"><\/td/', $line, $matches)) {
                array_push(&$rounds, $round);
                $scores .= $round['score'];
            }
        } 
        
        $score = $rounds[0]['score'];
        $date = date_format($rounds[0]['dateObj'], 'M jS');
        $course = $rounds[0]['course'];

        echo $before_widget;
        echo "<div class='gsp-widget'>";
        echo "<div class='gsp-logo'>&nbsp;</div>";
        echo "<div class='gsp-content'>";
        
        echo "<div class='gsp-most-recent'>";
        echo "<div class='gsp-most-recent-date'>$date</div>";
        echo "<div class='gsp-most-recent-score'><a href='http://www.golfshot.com/'>$score</a></div>";
        echo "<div class='gsp-most-recent-course'>$course</div>";
        echo "</div>";
        
        for ($i = 1; $i < 5; $i++) {

            $score = $rounds[$i]['score'];
            $date = date_format($rounds[$i]['dateObj'], 'M jS');
            $course = $rounds[$i]['course'];

            echo "<div class='gsp-less-recent'>";

            echo "<div class='gsp-score'><a href='http://www.golfshot.com/'>$score</a></div>";
            echo "<div class='gsp-date-and-course'>";
            echo "<div class='gsp-date'>$date</div>";
            echo "<div class='gsp-course'>$course</div>";
            echo "</div>";
            
            echo "</div>";
        }
        
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
}

add_action( 'widgets_init', create_function( '', 'register_widget( "GSP_widget" );' ) );

?>
