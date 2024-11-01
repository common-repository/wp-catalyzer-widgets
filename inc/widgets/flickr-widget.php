<?php 
/* =================================================================================== */
/*	Flickr Feeds Widget
/*  package @ WP Catalyzer
/* =================================================================================== */

add_action('widgets_init', 'wpcatalyzer_flickr_widget_init');

function wpcatalyzer_flickr_widget_init() 
{
	register_widget('WP_Catalyzer_Flickr_Widget');
}
 
class WP_Catalyzer_Flickr_Widget extends WP_Widget {
	
	/* ============================================================================= */
	/* Set up the widget's unique name, ID, class, description, and other options.   */
	/* ============================================================================= */
	
	function WP_Catalyzer_Flickr_Widget()
	{
		$widget_ops = array(
		'classname' => 'wpcatalyzer-flickr-widget',
		'description' => 'Shows Photos from your Flickr Account.'
		);

		$control_ops = array('id_base' => 'wpcatalyzer-flickr-widget');

		$this->WP_Widget('wpcatalyzer-flickr-widget', 'WP Catalyzer Flickr Photos', $widget_ops, $control_ops);
	}
	
	/**
	 * Outputs the widget based on the arguments input through the widget controls.
	 * @since 0.7
	 */
	function widget($args, $instance) {  
		extract( $args );
		
		echo $before_widget;

		/* If there is a title given, add it along with the $before_title and $after_title variables. */
		$title = $instance['title'];
		if ( $title) {
			$title =  apply_filters( 'widget_title',  $title, $instance, $this->id_base );
			$title = str_replace('flickr', '<span>flick<span>r</span></span>', $title);
			echo $before_title . $title . $after_title;
		}
		$query_args = array();	
		$query_args['size'] = !empty($instance['size']) ? $instance['size'] : '';
		$query_args['count'] = !empty($instance['count']) ? $instance['count'] : '';
		$query_args['display'] = !empty($instance['display']) ? $instance['display'] : 'latest';
		$query_args['layout'] = !empty($instance['layout']) ? $instance['layout'] : 'x';
		$query_args['source'] = !empty($instance['source']) ? $instance['source'] : 'user';
		if(!empty($instance['tag'])) {
			if($instance['source'] == 'user')
				$query_args['source'] = 'user_tag';
			elseif($instance['source'] == 'group')
				$query_args['source'] = 'group_tag';
			elseif($instance['source'] == 'all')
				$query_args['source'] = 'all_tag';
		}
		if($instance['source'] == 'user')
			$query_args['user'] = $instance['id'];
		elseif($instance['source'] == 'user_set')
			$query_args['set'] = $instance['id'];
		elseif($instance['source'] == 'group')
			$query_args['group'] = $instance['id'];
		
		echo '<div class="flickr-badges flickr-badges-'.$instance['size'].'">';
        echo '<script type="text/javascript" src="http://www.flickr.com/badge_code_v2.gne?'.http_build_query($query_args).'"></script>'; 
		echo '</div>';
			
	   echo $after_widget;
   }
	 
	function update($new_instance, $old_instance) 
	{                
       return $new_instance;
	}
	
	function form($instance) {  
		$defaults = array(
			'title' => 'Photos on Flickr',
			'source' => 'user',
			'id' => '',
			'size' => 's',
			'count' => '9',
			'display' => 'latest',
			'tag' => ''
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
   
		$display = array( 'latest' => __( 'Latest', theme_name ), 'random' => __( 'Random', theme_name ) );
		$size = array( 's' => __( 'Small', theme_name ), 't' => __( 'Thumbnail', theme_name ), 'm' => __( 'Medium', theme_name ) );
		$source = array( 'user' => __( 'User', theme_name ), 'group' => __( 'Group', theme_name ), 'user_set' => __( 'Set', theme_name ), 'all' => __( 'Public', theme_name) );
		$count = array(1,2,3,4,5,6,7,8,9,10);
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">Title :</label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>
        <p>
			<label for="<?php echo $this->get_field_id( 'source' ); ?>">Source:</label> 
			<select class="widefat" id="<?php echo $this->get_field_id( 'source' ); ?>" name="<?php echo $this->get_field_name( 'source' ); ?>">
				<?php foreach ( $source as $option_value => $option_label ) { ?>
					<option value="<?php echo $option_value; ?>" <?php selected( $instance['source'], $option_value ); ?>><?php echo $option_label; ?></option>
				<?php } ?>
			</select>
		</p>
		<p>
            <label for="<?php echo $this->get_field_id('id'); ?>"><?php _e('Flickr ID (<a target="_blank" href="http://www.idgettr.com">idGettr</a>):', theme_name); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('id'); ?>" value="<?php echo esc_attr( $instance['id'] ); ?>" class="widefat" id="<?php echo $this->get_field_id('id'); ?>" />
        </p>
		<p>
			<label for="<?php echo $this->get_field_id( 'tag' ); ?>"><?php _e( 'Tags:', theme_name ); ?> <span class="description"><?php _e( 'Separate tag with commas', theme_name ); ?></span></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'tag' ); ?>" name="<?php echo $this->get_field_name( 'tag' ); ?>" value="<?php echo esc_attr( $instance['tag'] ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'count' ); ?>">Number:</label> 
			<select class="smallfat" id="<?php echo $this->get_field_id( 'count' ); ?>" name="<?php echo $this->get_field_name( 'count' ); ?>">
				<?php foreach ( $count as $option_value ) { ?>
					<option value="<?php echo $option_value; ?>" <?php selected( $instance['count'], $option_value ); ?>><?php echo $option_value; ?></option>
				<?php } ?>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'display' ); ?>">Sorting:</label> 
			<select class="widefat" id="<?php echo $this->get_field_id( 'display' ); ?>" name="<?php echo $this->get_field_name( 'display' ); ?>">
				<?php foreach ( $display as $option_value => $option_label ) { ?>
					<option value="<?php echo $option_value; ?>" <?php selected( $instance['display'], $option_value ); ?>><?php echo $option_label; ?></option>
				<?php } ?>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'size' ); ?>">Size:</label> 
			<select class="widefat" id="<?php echo $this->get_field_id( 'size' ); ?>" name="<?php echo $this->get_field_name( 'size' ); ?>">
				<?php foreach ( $size as $option_value => $option_label ) { ?>
					<option value="<?php echo $option_value; ?>" <?php selected( $instance['size'], $option_value ); ?>><?php echo $option_label; ?></option>
				<?php } ?>
			</select>
		</p>
       <?php
	}
}
?>