<?php
add_action('widgets_init', 'wpcatalyzer_video_widget_init');

function wpcatalyzer_video_widget_init() 
{
    register_widget('WP_Catalyzer_Video_Widget');
}

class WP_Catalyzer_Video_Widget extends WP_Widget {

    function WP_Catalyzer_Video_Widget() 
	{
		$widget_ops = array('classname' => 'wpcatalyzer-video-widget', 'description' => 'Adds support for Video Box for Youtube,Vimeo and Dailymotion.');
		
        $control_ops = array('width' => 250, 'height' => 350, 'id_base' => 'wpcatalyzer-video-widget');
		
        $this->WP_Widget('wpcatalyzer-video-widget', 'WP Catalyzer Video Box', $widget_ops, $control_ops);
    }

    function widget($args, $instance) {
        extract($args);

        $title = apply_filters('widget_title', $instance['widget_title']);
        
        /* New Variables */
        $auto_play = 'autoplay=0';
        if( array_key_exists('video_auto_play', $instance) ){
            $video_auto_play = $instance['video_auto_play'];

            if ($video_auto_play == true){
                $auto_play = 'autoplay=1';
            }
        }
        
        $video_dailymotion_code = '';
        if( array_key_exists('video_dailymotion_code', $instance) ){
            $video_dailymotion_code = $instance['video_dailymotion_code'];
        }
        
        /* New Variables - End */
        
        $video_embed_code = $instance['video_embed_code'];
        $video_embed_code = preg_replace('/width="([3-9][0-9]{2,}|[1-9][0-9]{3,})"/', 'width="100%"', $video_embed_code);
        $video_embed_code = preg_replace('/height="([0-9]*)"/', 'height="220"', $video_embed_code);

        $video_youtube_code = $instance['video_youtube_code'];
        $video_vimeo_code = $instance['video_vimeo_code'];

        echo $before_widget;

        if ($title) {
            echo $before_title;
            echo $title;
            echo $after_title;
        }

        if (!empty($video_embed_code) && $video_embed_code != '') {
            echo $video_embed_code;
        } elseif ($video_youtube_code) { ?>

            <iframe class="youtube-player" type="text/html" width="100%" height="220" 
                    src="http://www.youtube.com/embed/<?php echo $video_youtube_code . '?wmode=transparent&amp;wmode=opaque' . '&amp;' . $auto_play; ?>" allowfullscreen webkitAllowFullScreen mozallowfullscreen frameborder="0">
            </iframe>
            <?php } elseif ($video_vimeo_code != '') { ?>
            
            <iframe src="http://player.vimeo.com/video/<?php echo $video_vimeo_code . '?wmode=transparent&amp;wmode=opaque' . '&amp;' . $auto_play; ?>" width="100%" height="220" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen>
            </iframe>
            
            <?php } elseif ( !empty($video_dailymotion_code) ) { ?>
            
            <iframe src="http://www.dailymotion.com/embed/video/<?php echo $video_dailymotion_code . '?' . $auto_play; ?>" width="100%" height="220" frameborder="0">
            </iframe>
            
            <?php }

        echo $after_widget;
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['widget_title'] = strip_tags($new_instance['widget_title']);
        
        $instance['video_auto_play'] = strip_tags($new_instance['video_auto_play']);
        
        $instance['video_embed_code'] = $new_instance['video_embed_code'];
        $instance['video_youtube_code'] = strip_tags($new_instance['video_youtube_code']);
        $instance['video_vimeo_code'] = strip_tags($new_instance['video_vimeo_code']);
        $instance['video_dailymotion_code'] = strip_tags($new_instance['video_dailymotion_code']);
        
        return $instance;
    }

    function form($instance) {
        $defaults = array('title' => 'Video Box', 'video_auto_play' => '', 'video_embed_code' => '', 'video_youtube_code' => '', 'video_vimeo_code' => '', 'video_dailymotion_code' => '');
        $instance = wp_parse_args((array) $instance, $defaults); ?>

        <style>
            .video-code-text-sep{
                border: none;
                border-bottom: 1px solid #CCCCCC;
            }
            .video-code-text-sep:before{
                content: "OR ";
            }
        </style>
        <p>
            <label for="<?php echo $this->get_field_id('widget_title'); ?>">Title :</label>
            <input class="widefat" id="<?php echo $this->get_field_id('widget_title'); ?>" name="<?php echo $this->get_field_name('widget_title'); ?>" value="<?php echo $instance['widget_title']; ?>" type="text" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('video_auto_play'); ?>">Enable Autoplay :</label>
            <input id="<?php echo $this->get_field_id('video_auto_play'); ?>" name="<?php echo $this->get_field_name('video_auto_play'); ?>" value="true" <?php if ($instance['video_auto_play']) echo 'checked="checked"'; ?> type="checkbox" />
        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id('video_embed_code'); ?>">Embed Code :</label>
            <textarea class="widefat" id="<?php echo $this->get_field_id('video_embed_code'); ?>" name="<?php echo $this->get_field_name('video_embed_code'); ?>"><?php echo $instance['video_embed_code']; ?></textarea>
        <hr class="video-code-text-sep" />
        </p>

        <p>
            <label class="widefat" for="<?php echo $this->get_field_id('video_youtube_code'); ?>">YouTube (Code Only) :</label>
            <input class="widefat" id="<?php echo $this->get_field_id('video_youtube_code'); ?>" name="<?php echo $this->get_field_name('video_youtube_code'); ?>" value="<?php echo $instance['video_youtube_code']; ?>" type="text" />
            <small>Ex: if the URL like (http://youtu.be/X123456) put (<b>X123456</b>) only</small>
        <hr class="video-code-text-sep" />
        </p>

        <p>
            <label class="widefat" for="<?php echo $this->get_field_id('video_vimeo_code'); ?>">Vimeo (Code Only) :</label>
            <input class="widefat" id="<?php echo $this->get_field_id('video_vimeo_code'); ?>" name="<?php echo $this->get_field_name('video_vimeo_code'); ?>" value="<?php echo $instance['video_vimeo_code']; ?>" type="text" />
            <small>Ex: if the URL like (http://vimeo.com/X123456) put (<b>X123456</b>) only</small>
        <hr class="video-code-text-sep" />
        </p>

        <p>
            <label class="widefat" for="<?php echo $this->get_field_id('video_dailymotion_code'); ?>">DailyMotion (Code Only) :</label>
            <input class="widefat" id="<?php echo $this->get_field_id('video_dailymotion_code'); ?>" name="<?php echo $this->get_field_name('video_dailymotion_code'); ?>" value="<?php echo $instance['video_dailymotion_code']; ?>" type="text" />
            <small>Ex: if the URL like (http://www.dailymotion.com/video/X123456) put (<b>X123456</b>) only'</small>
        </p>
        
<?php } } ?>