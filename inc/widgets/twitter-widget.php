<?php
/* =================================================================================== */
/*	Recent Tweets Widget
/*  package @ WP Catalyzer
/* =================================================================================== */
add_action('widgets_init', 'wpcatalyzer_recent_tweets_widget_init');

function wpcatalyzer_recent_tweets_widget_init()
{
	register_widget('WP_Catalyzer_Recent_Tweets_Widget');
}

class WP_Catalyzer_Recent_Tweets_Widget extends WP_Widget {
	
	function WP_Catalyzer_Recent_Tweets_Widget()
	{
		$widget_ops = array(
			'classname' => 'wpcatalyzer-twitter-widget', 
			'description' => 'Adds support for Recent Tweets Box.'
		);

		$control_ops = array('id_base' => 'wpcatalyzer-twitter-widget');

		$this->WP_Widget('wpcatalyzer-twitter-widget', 'WP Catalyzer Twitter Box', $widget_ops, $control_ops);
	}

	public function widget($args, $instance) {
		extract($args);
		$title 					= apply_filters('widget_title', $instance['title']);
		$color_scheme 			= $instance['color_scheme'];
		$consumer_key 			= $instance['consumer_key'];
		$consumer_secret 		= $instance['consumer_secret'];
		$access_token 			= $instance['access_token'];
		$access_token_secret 	= $instance['access_token_secret'];
		$twitter_username 		= $instance['twitter_username'];
		$count 					= (int) $instance['count'];
		$widget_id 				= $args['widget_id'];

		echo $before_widget;

        if ($title) {
            echo $before_title;
            echo $title;
            echo $after_title;
        }
		if ($twitter_username && $consumer_key && $consumer_secret && $access_token && $access_token_secret && $count) {

			$transName = 'list_tweets_'.$widget_id;
			$cacheTime = 10;
			if(false === ($twitterData = get_transient($transName))) {

				$token = get_option('cfTwitterToken_'.$widget_id);

				// get a new token anyways
				delete_option('cfTwitterToken_'.$widget_id);

				// getting new auth bearer only if we don't have one
				if(!$token) {
					// preparing credentials
					$credentials = $consumer_key . ':' . $consumer_secret;
					$toSend = base64_encode($credentials);

					// http post arguments
					$args = array(
						'method' 		=> 'POST',
						'httpversion' 	=> '1.1',
						'blocking' 		=> true,
						'headers' 		=> array(
							'Authorization' => 'Basic ' . $toSend,
							'Content-Type' 	=> 'application/x-www-form-urlencoded;charset=UTF-8'
						),
						'body' => array( 'grant_type' => 'client_credentials' )
					);

					add_filter('https_ssl_verify', '__return_false');
					$response = wp_remote_post('https://api.twitter.com/oauth2/token', $args);

					$keys = json_decode(wp_remote_retrieve_body($response));

					if($keys) {
						// saving token to wp_options table
						update_option('cfTwitterToken_'.$widget_id, $keys->access_token);
						$token = $keys->access_token;
					}
				}
				// we have bearer token wether we obtained it from API or from options
				$args = array(
					'httpversion' 	=> '1.1',
					'blocking' 		=> true,
					'headers' 		=> array(
						'Authorization' => "Bearer $token"
					)
				);

				add_filter('https_ssl_verify', '__return_false');
				$api_url = "https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=$twitter_username&count=$count";
				$response = wp_remote_get($api_url, $args);

				set_transient($transName, wp_remote_retrieve_body($response), 60 * $cacheTime);
			}
			@$twitter = json_decode(get_transient($transName), true);
			if($twitter && is_array($twitter)) { ?>
				<div class="tweets-container" id="tweets_<?php echo esc_attr( $widget_id ); ?>">
					<ul class="wp-catalyzer-twitter-ul" id="wp-catalyzer-twitter">
						<?php foreach($twitter as $tweet): ?>
							<li class="wp-catalyzer-tweet">
								<p class="wp-catalyzer-tweet-text">
								<?php
								$latestTweet = $tweet['text'];
								$latestTweet = preg_replace('/http:\/\/([a-z0-9_\.\-\+\&\!\#\~\/\,]+)/i', '&nbsp;<a href="http://$1" target="_blank">http://$1</a>&nbsp;', $latestTweet);
								$latestTweet = preg_replace('/@([a-z0-9_]+)/i', '&nbsp;<a href="http://twitter.com/$1" target="_blank">@$1</a>&nbsp;', $latestTweet);
								echo $latestTweet;
								?>
								</p>
								<?php
								$twitterTime = strtotime($tweet['created_at']);
								$timeAgo = $this->ago($twitterTime);
								?>
								<a href="http://twitter.com/<?php echo esc_attr( $tweet['user']['screen_name'] ); ?>/statuses/<?php echo esc_attr( $tweet['id_str'] ); ?>" class="uw_jtwt_date"><?php echo esc_attr( $timeAgo ); ?></a>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php }
		}

		echo $after_widget;
	}

	public function ago($time) {
		$periods 	= array( __( 'second', 'wp-catalyzer-framework' ), __( 'minute', 'wp-catalyzer-framework' ), __( 'hour', 'wp-catalyzer-framework' ), __( 'day', 'wp-catalyzer-framework' ), __( 'week', 'wp-catalyzer-framework' ), __( 'month', 'wp-catalyzer-framework' ), __( 'year', 'wp-catalyzer-framework' ), __( 'decade', 'wp-catalyzer-framework' ) );
		$lengths 	= array( '60', '60', '24', '7', '4.35', '12', '10' );
		$now 		= time();
		$difference = $now - $time;
		$tense 		= __( 'ago', 'wp-catalyzer-framework' );

		for( $j = 0; $difference >= $lengths[$j] && $j < count( $lengths )-1; $j++ ) {
			$difference /= $lengths[$j];
		}

		$difference = round( $difference );

		if( $difference != 1 ) {
			$periods[$j] .= __( 's', 'wp-catalyzer-framework' );
		}

	   return sprintf('%s %s %s', $difference, $periods[$j], $tense );
	}

	public function update($new_instance, $old_instance) {
		$instance 							= $old_instance;
		$instance['title'] 					= strip_tags($new_instance['title']);
		$instance['consumer_key'] 			= $new_instance['consumer_key'];
		$instance['consumer_secret'] 		= $new_instance['consumer_secret'];
		$instance['access_token'] 			= $new_instance['access_token'];
		$instance['access_token_secret'] 	= $new_instance['access_token_secret'];
		$instance['twitter_username'] 		= $new_instance['twitter_username'];
		$instance['count'] 					= $new_instance['count'];
		return $instance;
	}

	public function form($instance) {
		$instance = wp_parse_args((array) $instance, array(
			'title' 				=> __('Recent Tweets','wp-catalyzer-framework'),
			'consumer_key'			=> '',
			'consumer_secret' 		=> '',
			'access_token' 			=> '',
			'access_token_secret' 	=> '',
			'twitter_username' 		=> '',
			'count' 				=> 3
		)); ?>

		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'wp-catalyzer-framework'); ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('consumer_key'); ?>"><?php _e('Consumer Key:', 'wp-catalyzer-framework'); ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id('consumer_key'); ?>" name="<?php echo $this->get_field_name('consumer_key'); ?>" value="<?php echo $instance['consumer_key']; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('consumer_secret'); ?>"><?php _e('Consumer Secret:', 'wp-catalyzer-framework'); ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id('consumer_secret'); ?>" name="<?php echo $this->get_field_name('consumer_secret'); ?>" value="<?php echo $instance['consumer_secret']; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('access_token'); ?>"><?php _e('Access Token:', 'wp-catalyzer-framework'); ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id('access_token'); ?>" name="<?php echo $this->get_field_name('access_token'); ?>" value="<?php echo $instance['access_token']; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('access_token_secret'); ?>"><?php _e('Access Token Secret:', 'wp-catalyzer-framework'); ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id('access_token_secret'); ?>" name="<?php echo $this->get_field_name('access_token_secret'); ?>" value="<?php echo $instance['access_token_secret']; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('twitter_username'); ?>"><?php _e('Twitter Username:', 'wp-catalyzer-framework'); ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id('twitter_username'); ?>" name="<?php echo $this->get_field_name('twitter_username'); ?>" value="<?php echo $instance['twitter_username']; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('count'); ?>"><?php _e('Number of Tweets:', 'wp-catalyzer-framework'); ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" value="<?php echo $instance['count']; ?>" />
		</p>
	<?php
	}
}
?>