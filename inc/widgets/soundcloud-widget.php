<?php

/* =================================================================================== */
/*	Soundcloud Box Widget
/*  package @ WP Catalyzer
/* =================================================================================== */

add_action('widgets_init', 'wpcatalyzer_soundcloud_widget_init');

function wpcatalyzer_soundcloud_widget_init() {
    register_widget('WP_Catalyzer_Soundcloud_Widget');
}

class WP_Catalyzer_Soundcloud_Widget extends WP_Widget {

    function WP_Catalyzer_Soundcloud_Widget() {
        $widget_ops = array(
			'classname' => 'wpcatalyzer-soundcloud',
			'description' => 'Adds support for Soundcloud Box.'
		);
        $control_ops = array(
			'id_base' => 'wpcatalyzer-soundcloud-widget'
		);
        
		$this->WP_Widget('wpcatalyzer-soundcloud-widget', ' WP Catalyzer SoundCloud', $widget_ops, $control_ops);
    }

    function widget($args, $instance) {
        extract($args);

        $title = apply_filters('widget_title', $instance['widget_title']);

        $soundcloud_url = $instance['soundcloud_url'];
		$height = $instance['height'];
        $soundcloud_auto_play = $instance['soundcloud_auto_play'];
        $auto_play = false;

        if ($soundcloud_auto_play == true) {
            $auto_play = true;
        }

        if ($soundcloud_url) {
		echo $before_widget;

		if ($title) {
			echo $before_title;
			echo $title;
			echo $after_title;
		}
			
		?>

        <iframe width="100%" height="<?php echo esc_attr( $height ); ?>" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player/?url=<?php echo esc_url( $soundcloud_url ); ?>&amp;auto_play=<?php echo esc_attr( $soundcloud_auto_play ); ?>&amp;show_artwork=true&amp;show_user=true&amp;visual=true"></iframe>
		
        <?php 
		echo $after_widget;
		} 
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['widget_title']   = strip_tags($new_instance['widget_title']);
        $instance['soundcloud_url'] = strip_tags($new_instance['soundcloud_url']);
		$instance['height'] 	    = $new_instance['height'] ;
        $instance['soundcloud_auto_play'] = strip_tags($new_instance['soundcloud_auto_play']);

        return $instance;
    }

    function form($instance) {
        $defaults = array(
			'widget_title' => __('Sound Cloud', 'wpcatalyzer'),
			'soundcloud_url' => '',
			'height' 		=> '300',
			'soundcloud_auto_play' => ''
		);
        $instance = wp_parse_args((array) $instance, $defaults);
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('widget_title'); ?>"><?php _e('Title', 'wpcatalyzer'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('widget_title'); ?>" name="<?php echo $this->get_field_name('widget_title'); ?>" value="<?php echo $instance['widget_title']; ?>" type="text" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('soundcloud_url'); ?>"><?php _e('SoundCloud URL', 'wpcatalyzer'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('soundcloud_url'); ?>" name="<?php echo $this->get_field_name('soundcloud_url'); ?>" value="<?php echo $instance['soundcloud_url']; ?>" type="text" />
        </p>
		<p>
			<label for="<?php echo $this->get_field_id( 'height' ); ?>"><?php _e('Height :', 'wpcatalyzer'); ?></label>
			<input id="<?php echo $this->get_field_id( 'height' ); ?>" name="<?php echo $this->get_field_name( 'height' ); ?>" value="<?php echo $instance['height']; ?>" type="text" class="widefat" />
		</p>
        <p>
            <label for="<?php echo $this->get_field_id('soundcloud_auto_play'); ?>"><?php _e('Autoplay?', 'wpcatalyzer'); ?></label>
            <input id="<?php echo $this->get_field_id('soundcloud_auto_play'); ?>" name="<?php echo $this->get_field_name('soundcloud_auto_play'); ?>" value="true" <?php if ($instance['soundcloud_auto_play']) echo 'checked="checked"'; ?> type="checkbox" />
        </p>

    <?php
    }
}
?>