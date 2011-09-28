<?php
/*
Plugin Name: Future Posts Widget
Plugin URI: http://kisalt.com/wppg3
Description: Future Posts lists your draft posts with widget. Select number of drafts and add it your dynamic sidebar.
Author: Oğulcan Orhan
Version: 1.0
Author URI: http://ogulcan.org

Copyright 2011  Ogulcan Orhan  (email : mail@ogulcan.org)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

class WP_FuturePosts extends WP_Widget {

    function   __construct() {

        //options
        $widget_opt = array(
            'classname'    => 'future_post_widget',
            'description' => 'Show your draft posts',
        );

        $this->WP_Widget ('future_post_widget', 'Future Posts', $widget_opt);
    }

    function form($instance)  {
        $instance = wp_parse_args ( (array)$instance, array('title' => 'Future Posts', 'number' => '4') );

        $title = esc_attr ( $instance['title'] );
        $number = absint( $instance['number'] );
        ?>
            <p>
                <label for="<?php echo $this->get_field_id('title') ?>">Title: </label>
                <input class="widefat" id="<?php echo $this->get_field_id('title') ?>" name ="<?php echo $this->get_field_name('title') ?>" type="text" value="<?php echo $title; ?>" />
                <br /><br />
                <label for="<?php echo $this->get_field_id('number') ?>">Number: </label>
                <input class="widefat" id="<?php echo $this->get_field_id('number') ?>" name ="<?php echo $this->get_field_name('number') ?>" type="text" value="<?php echo $number; ?>" />
                <br />
                <small>Note: 0 shows all</small>
            </p>
<?php
    }

    function update($new_instance, $old_instance){

        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['number'] = strip_tags($new_instance['number']);
        
        return $instance;
    }

    function widget ($args, $instance) {
        extract($args);
        
        echo $before_widget;

        $title = apply_filters('widget_title', $instance['title']);
        if( empty($title) ) $title = FALSE;
        $number = absint ( $instance['number'] );

        if($number == 0) $number = -1;

        $the_query = new WP_Query( array( 'post_status' => array('pending', 'draft', 'future' ), 'posts_per_page' => $number ));

        if($title) {
            echo $before_title;
            echo $title;
            echo $after_title;
        }

        echo "<ul>";
        while( $the_query->have_posts() ) :
            $the_query->the_post();
            echo "<li>";
            echo the_title();
            echo "</li>";
        endwhile;
        echo "</ul>";
        echo $after_widget;

        wp_reset_postdata();
    }
}

function widget_init() {
    register_widget('WP_FuturePosts');
}

add_action ('widgets_init', 'widget_init');

?>
