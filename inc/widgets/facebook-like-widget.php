<?php

/* =================================================================================== */
/*	Facebook Like Box Widget
/*  package @ WP Catalyzer
/* =================================================================================== */

add_action('widgets_init', 'wpcatalyzer_facebook_like_widget_init');

function wpcatalyzer_facebook_like_widget_init()
{
	register_widget('WP_Catalyzer_Facebook_Like_Widget');
}

class WP_Catalyzer_Facebook_Like_Widget extends WP_Widget {
	
	function WP_Catalyzer_Facebook_Like_Widget()
	{
		$widget_ops = array(
			'classname' => 'wpcatalyzer-facebook-widget', 
			'description' => 'Adds support for Facebook Like Box.'
		);

		$control_ops = array('id_base' => 'wpcatalyzer-facebook-widget');

		$this->WP_Widget('wpcatalyzer-facebook-widget', 'WP Catalyzer Facebook Like Box', $widget_ops, $control_ops);
	}
	
	function widget($args, $instance)
	{
		extract($args);
		
		echo $before_widget;
		
		$title = apply_filters('widget_title', $instance['title']);
		$page_url = $instance['page_url'];
		$color_scheme = $instance['color_scheme'];
		$show_faces = isset($instance['show_faces']) ? 'true' : 'false';
		$show_stream = isset($instance['show_stream']) ? 'true' : 'false';
		$show_header = isset($instance['show_header']) ? 'true' : 'false';
		$height = '65';
		
		if($show_faces == 'true') {
			$height = '260';
		}
		
		if($show_stream == 'true') {
			$height = '600';
		}
		
		if($show_header == 'true') {
			$height = '600';
		}

		if($title) {
			echo $before_title.$title.$after_title;
		}
		
		if($page_url): ?>
		<iframe src="http://www.facebook.com/plugins/likebox.php?href=<?php echo urlencode($page_url); ?>&amp;width=<?php echo $width; ?>&amp;colorscheme=<?php echo $color_scheme; ?>&amp;show_faces=<?php echo $show_faces; ?>&amp;stream=<?php echo $show_stream; ?>&amp;header=<?php echo $show_header; ?>&amp;height=<?php echo $height; ?>" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:100%; height: <?php echo $height; ?>px;" allowTransparency="true"></iframe>
		<?php endif;
		
		echo $after_widget;
	}
	
	function update($new_instance, $old_instance)
	{
		$instance = $old_instance;

		$instance['title'] = strip_tags($new_instance['title']);
		$instance['page_url'] = $new_instance['page_url'];
		$instance['color_scheme'] = $new_instance['color_scheme'];
		$instance['show_faces'] = $new_instance['show_faces'];
		$instance['show_stream'] = $new_instance['show_stream'];
		$instance['show_header'] = $new_instance['show_header'];
		
		return $instance;
	}

	function form($instance)
	{
		$defaults = array(
			'title' => 'Find us on Facebook', 
			'page_url' => '', 
			'width' => '310', 
			'color_scheme' => 'light', 
			'show_faces' => 'on', 
			'show_stream' => false, 
			'show_header' => false
		);
		$instance = wp_parse_args((array) $instance, $defaults); ?>
		
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('page_url'); ?>">Facebook Page URL:</label>
			<input class="widefat" id="<?php echo $this->get_field_id('page_url'); ?>" name="<?php echo $this->get_field_name('page_url'); ?>" value="<?php echo $instance['page_url']; ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('color_scheme'); ?>">Color Scheme:</label> 
			<select id="<?php echo $this->get_field_id('color_scheme'); ?>" name="<?php echo $this->get_field_name('color_scheme'); ?>" class="widefat" style="width:100%;">
				<option <?php if ('light' == $instance['color_scheme']) echo 'selected="selected"'; ?>>light</option>
				<option <?php if ('dark' == $instance['color_scheme']) echo 'selected="selected"'; ?>>dark</option>
			</select>
		</p>
		
		<p>
			<input class="checkbox" type="checkbox" <?php checked($instance['show_faces'], 'on'); ?> id="<?php echo $this->get_field_id('show_faces'); ?>" name="<?php echo $this->get_field_name('show_faces'); ?>" /> 
			<label for="<?php echo $this->get_field_id('show_faces'); ?>">Show faces</label>
		</p>
		
		<p>
			<input class="checkbox" type="checkbox" <?php checked($instance['show_stream'], 'on'); ?> id="<?php echo $this->get_field_id('show_stream'); ?>" name="<?php echo $this->get_field_name('show_stream'); ?>" /> 
			<label for="<?php echo $this->get_field_id('show_stream'); ?>">Show stream</label>
		</p>
		
		<p>
			<input class="checkbox" type="checkbox" <?php checked($instance['show_header'], 'on'); ?> id="<?php echo $this->get_field_id('show_header'); ?>" name="<?php echo $this->get_field_name('show_header'); ?>" /> 
			<label for="<?php echo $this->get_field_id('show_header'); ?>">Show facebook header</label>
		</p>
	<?php
	}
}
?>