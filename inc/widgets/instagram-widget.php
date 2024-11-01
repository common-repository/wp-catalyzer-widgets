<?php
/* =================================================================================== */
/*	Instagram Widget
/*  package @ WP Catalyzer
/* =================================================================================== */

add_action('widgets_init', 'wp_catalyzer_instagram_widget');

function wp_catalyzer_instagram_widget() {
	register_widget('WP_Catalyzer_Instagram_Widget');
}

class WP_Catalyzer_Instagram_Widget extends WP_Widget {

	function WP_Catalyzer_Instagram_Widget() 
	{
		$widget_ops = array(	
			'classname'		=> 'wpcatalyzer-instagram-widget',
			'description'	=> 'Instagram Widget.'
		);

		$control_ops = array('id_base' => 'wpcatalyzer-instagram-widget');

		$this->WP_Widget('wpcatalyzer-instagram-widget', 'WP Catalyzer Instagram Widget', $widget_ops, $control_ops);
	}

	function widget( $args, $instance ) {
		extract( $args );

		if ( ! is_admin() ) {
			wp_register_script('js-instagram', plugins_url('/wp-catalyzer-widgets/js/spectragram.min.js'));
			wp_enqueue_script('js-instagram');
		}

		$title 				= apply_filters('widget_title', $instance['title'] );
		$access_token 		= ! empty ( $instance['access_token'] ) ? $instance['access_token'] : '';
		$client_id 			= ! empty ( $instance['client_id'] ) ? $instance['client_id'] : '';
		$your_query 		= ! empty ( $instance['your_query'] ) ? $instance['your_query'] : '';
		$num_images 		= ! empty ( $instance['num_images'] ) ? $instance['num_images'] : '10';
		$feed_type 			= ! empty ( $instance['feed_type'] ) ? $instance['feed_type'] : 'UserFeed';

		$random_id = rand();

		echo $before_widget;
		
		if ( $feed_type == 'Popular' ) : $get_feed_type = 'getPopular';
		elseif ( $feed_type == 'RecentTagged' ) : $get_feed_type = 'getRecentTagged';
		else : $get_feed_type = 'getUserFeed';
		endif;
		?>

		<?php if ( empty($access_token) || empty($client_id) ) : ?>
			<p><?php esc_html_e( 'You must define an accessToken and a clientID', 'wp-catalyzer-framework' ); ?></p>
		<?php else : ?>
		<script type="text/javascript">
		/* <![CDATA[ */
			jQuery.noConflict()(function($){
				$(document).ready(function() {
					jQuery.fn.spectragram.accessData = {
						accessToken: '<?php echo esc_js( $access_token ); ?>',
						clientID: '<?php echo esc_js( $client_id ); ?>'
					};

					//Call spectagram function on the container element and pass it your query
					$('.instagram-<?php echo esc_js( $random_id ); ?>').spectragram('<?php echo esc_js( $get_feed_type ); ?>', {
						query: '<?php echo esc_js( $your_query ); ?>', //this gets user photo feed
						size: 'small',
						max: <?php echo esc_js( $num_images ); ?>
					});
				});
			});
		/* ]]> */
		</script>
		<?php endif; ?>

		<ul class="instagram-<?php echo esc_attr( $random_id ); ?> clearfix"></ul>

		<?php

		// After widget (defined by theme functions file)
		echo $after_widget;
	}


/*-----------------------------------------------------------------------------------*/
/*	Update Widget
/*-----------------------------------------------------------------------------------*/

function update( $new_instance, $old_instance ) {
	$instance = $old_instance;

		$instance['title'] 				= strip_tags( $new_instance['title'] );
		$instance['access_token'] 		= esc_html( $new_instance['access_token']);
		$instance['client_id'] 			= esc_html( $new_instance['client_id']);
		$instance['your_query'] 		= esc_html( $new_instance['your_query']);
		$instance['num_images'] 		= absint( $new_instance['num_images']);
		$instance['feed_type'] 			= $new_instance['feed_type'];

	return $instance;
}


/*-----------------------------------------------------------------------------------*/
/*	Widget Settings (Displays the widget settings controls on the widget panel)
/*-----------------------------------------------------------------------------------*/

function form( $instance ) {

	// Set up some default widget settings
	$defaults = array(	'title'				=> esc_html__( 'Instagram Feed' , 'wp-catalyzer-framework' ),
						'access_token'		=> '',
						'client_id'			=> '',
						'your_query'		=> 'awesomeinventions',
						'num_images'		=> '8',
						'feed_type'			=> 'UserFeed'
					);

	$instance = wp_parse_args((array) $instance, $defaults);
	?>

	<!-- Widget Title: Text Input -->
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e('Title:', 'wp-catalyzer-framework') ?></label>
		<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" />
	</p>

	<p>
		<label for="<?php echo $this->get_field_id( 'client_id' ); ?>"><?php esc_html_e('Your Instagram application clientID:', 'wp-catalyzer-framework') ?></label>
		<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'client_id' ); ?>" name="<?php echo $this->get_field_name( 'client_id' ); ?>" value="<?php echo stripslashes(htmlspecialchars(( $instance['client_id'] ), ENT_QUOTES)); ?>" />
	</p>

	<p>
		<label for="<?php echo $this->get_field_id( 'access_token' ); ?>"><?php esc_html_e('Your Instagram access token:', 'wp-catalyzer-framework') ?></label>
		<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'access_token' ); ?>" name="<?php echo $this->get_field_name( 'access_token' ); ?>" value="<?php echo stripslashes(htmlspecialchars(( $instance['access_token'] ), ENT_QUOTES)); ?>" />
	</p>

	<p>
		<label for="<?php echo $this->get_field_id( 'your_query' ); ?>"><?php esc_html_e('Query (user name or tag):', 'wp-catalyzer-framework') ?></label>
		<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'your_query' ); ?>" name="<?php echo $this->get_field_name( 'your_query' ); ?>" value="<?php echo stripslashes(htmlspecialchars(( $instance['your_query'] ), ENT_QUOTES)); ?>" />
	</p>

	<p>
		<label for="<?php echo $this->get_field_id( 'num_images' ); ?>"><?php esc_html_e('The number of displayed images:', 'wp-catalyzer-framework') ?></label>
		<input type="number" min="1" max="30" class="widefat" id="<?php echo $this->get_field_id( 'num_images' ); ?>" name="<?php echo $this->get_field_name( 'num_images' ); ?>" value="<?php echo stripslashes(htmlspecialchars(( $instance['num_images'] ), ENT_QUOTES)); ?>" />
	</p>

	<p>
		<label for="<?php echo $this->get_field_id( 'feed_type' ); ?>"><?php esc_html_e('Feed type:', 'wp-catalyzer-framework'); ?></label>
		<select id="<?php echo $this->get_field_id( 'feed_type' ); ?>" name="<?php echo $this->get_field_name( 'feed_type' ); ?>" class="widefat" style="width:100%;">
			<option <?php if ( 'UserFeed' == $instance['feed_type'] ) echo 'selected="selected"'; ?>>UserFeed</option>
			<option <?php if ( 'Popular' == $instance['feed_type'] ) echo 'selected="selected"'; ?>>Popular</option>
			<option <?php if ( 'RecentTagged' == $instance['feed_type'] ) echo 'selected="selected"'; ?>>RecentTagged</option>
		</select>
	</p>
	<?php
	}
}
?>