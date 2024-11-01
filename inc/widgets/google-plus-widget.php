<?php

/* =================================================================================== */
/*	Follow Google+ Widget
/*  package @ WP Catalyzer
/* =================================================================================== */

add_action( 'widgets_init', 'wpcatalyzer_google_plus_widget_init' );

function wpcatalyzer_google_plus_widget_init() 
{
	register_widget( 'WP_Catalyzer_Google_Plus_Widget' );
}
class WP_Catalyzer_Google_Plus_Widget extends WP_Widget {
	
	function WP_Catalyzer_Google_Plus_Widget()
	{
		$widget_ops = array(
			'classname' => 'wpcatalyzer-googleplus-widget', 
			'description' => 'Adds a beautiful Google Plus Badge widget.',
		);

		$control_ops = array('id_base' => 'wpcatalyzer-googleplus-widget');

		$this->WP_Widget('wpcatalyzer-googleplus-widget', 'WP Catalyzer Google Plus Box', $widget_ops, $control_ops);
	}
	
	function widget($args, $instance)
	{
		extract($args);

		$title = apply_filters('widget_title', $instance['title']);		
		$page_type = $instance['page_type'];
		$page_url = $instance['page_url'];
		$width = $instance['width'];
		$color_scheme = $instance['color_scheme'];
		$gp_layout = $instance['gp_layout'];
		$cover_photo = isset($instance['cover_photo']) ? 'true' : 'false';
		$tagline = isset($instance['tagline']) ? 'true' : 'false';
		echo $before_widget;

		if($title) {
			echo $before_title.$title.$after_title;
		}
		
		if($page_url): ?>
			<script src="https://apis.google.com/js/platform.js" async defer></script>
			<div <?php if($page_type == 'profile') { ?>class="g-person"<?php } elseif($page_type == 'page') { ?>class="g-page"<?php } elseif($page_type == 'community') { ?>class="g-community"<?php } ?> data-width="<?php echo $width; ?>" data-href="<?php echo $page_url; ?>" data-layout="<?php echo $gp_layout; ?>" data-theme="<?php echo $color_scheme; ?>" data-rel="publisher" data-showtagline="<?php echo $tagline; ?>" data-showcoverphoto="<?php echo $cover_photo; ?>"></div>
		<?php endif;
		
		echo $after_widget;
	}
	
	function update($new_instance, $old_instance)
	{
		$instance = $old_instance;

		$instance['title'] = strip_tags($new_instance['title']);
		$instance['page_type'] = $new_instance['page_type'];
		$instance['page_url'] = $new_instance['page_url'];
		$instance['width'] = $new_instance['width'];
		$instance['gp_layout'] = $new_instance['gp_layout'];
		$instance['color_scheme'] = $new_instance['color_scheme'];
		$instance['cover_photo'] = $new_instance['cover_photo'];
		$instance['tagline'] = $new_instance['tagline'];
		
		return $instance;
	}

	function form($instance)
	{
		$defaults = array(
			'title' => 'Find us on Google Plus',
			'page_url' => '',
			'width' => '300',
			'color_scheme' => 'light',
			'gp_layout' => 'portrait',
			'page_type' => 'profile',
			'cover_photo' => true,
			'tagline' => true
		);
		$instance = wp_parse_args((array) $instance, $defaults); ?>
		
		<p>
			<label for="<?php echo $this->get_field_id('page_type'); ?>"><?php _e('Page type','wpcatalyzer'); ?>:</label> 
			<select id="<?php echo $this->get_field_id('page_type'); ?>" name="<?php echo $this->get_field_name('page_type'); ?>" style="width:100%;">
				<option <?php if ('profile' == $instance['page_type']) echo 'selected="selected"'; ?>>profile</option>
				<option <?php if ('page' == $instance['page_type']) echo 'selected="selected"'; ?>>page</option>
				<option <?php if ('community' == $instance['page_type']) echo 'selected="selected"'; ?>>community</option>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title','wpcatalyzer'); ?>:</label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('page_url'); ?>"><?php _e('Google+ Page URL','wpcatalyzer'); ?>:</label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id('page_url'); ?>" name="<?php echo $this->get_field_name('page_url'); ?>" value="<?php echo $instance['page_url']; ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('width'); ?>"><?php _e('Width','wpcatalyzer'); ?>:</label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id('width'); ?>" name="<?php echo $this->get_field_name('width'); ?>" value="<?php echo $instance['width']; ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('color_scheme'); ?>"><?php _e('Color Scheme','wpcatalyzer'); ?>:</label> 
			<select id="<?php echo $this->get_field_id('color_scheme'); ?>" name="<?php echo $this->get_field_name('color_scheme'); ?>" style="width:100%;">
				<option <?php if ('light' == $instance['color_scheme']) echo 'selected="selected"'; ?>>light</option>
				<option <?php if ('dark' == $instance['color_scheme']) echo 'selected="selected"'; ?>>dark</option>
			</select>
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('gp_layout'); ?>"><?php _e('Layout','wpcatalyzer'); ?>:</label> 
			<select id="<?php echo $this->get_field_id('gp_layout'); ?>" name="<?php echo $this->get_field_name('gp_layout'); ?>" style="width:100%;">
				<option <?php if ('portrait' == $instance['gp_layout']) echo 'selected="selected"'; ?>>portrait</option>
				<option <?php if ('landscape' == $instance['gp_layout']) echo 'selected="selected"'; ?>>landscape</option>
			</select>
		</p>
		
		<p>
			<b><?php _e('Portrait Layout Settings','wpcatalyzer'); ?></b>
		</p>
		
		<p>
			<input class="checkbox" type="checkbox" <?php checked($instance['cover_photo'], 'on'); ?> id="<?php echo $this->get_field_id('cover_photo'); ?>" name="<?php echo $this->get_field_name('cover_photo'); ?>" /> 
			<label for="<?php echo $this->get_field_id('cover_photo'); ?>"><?php _e('Cover Photo','wpcatalyzer'); ?></label>
		</p>
		
		<p>
			<input class="checkbox" type="checkbox" <?php checked($instance['tagline'], 'on'); ?> id="<?php echo $this->get_field_id('tagline'); ?>" name="<?php echo $this->get_field_name('tagline'); ?>" /> 
			<label for="<?php echo $this->get_field_id('tagline'); ?>"><?php _e('Tagline','wpcatalyzer'); ?></label>
		</p>
	<?php
	}
}
?>