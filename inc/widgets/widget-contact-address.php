<?php

class contact_address_widget extends WP_Widget {
 
 function contact_address_widget() {
        parent::WP_Widget(false, $name = theme_name .' - Contact Address');	
    }

    function widget($args, $instance) {	
        extract( $args );
        $title 		         = apply_filters('widget_title', $instance['title']);
        $contact_address 	 = $instance['contact_address'];
        $contact_phone     = $instance['contact_phone'];
        $contact_email     = $instance['contact_email'];
      ?>
              <?php echo $before_widget; ?>
                  <?php if ( $title )
                        echo $before_title . $title . $after_title; ?>
			<div class="widget-address">
               
               <?php if ( $contact_address ) {?>
                  <div class="address-content">
                    <span class="glyphicon glyphicon-map-marker"></span>
                    <span class="contact-address"><?php echo $contact_address; ?></span>
                  </div>
                <?php } ?>

                <?php if ( $contact_phone ) {?>
                  <div class="phone-content">
                    <span class="glyphicon glyphicon-phone-alt"></span>
                    <span><?php echo $contact_phone; ?></span>
                  </div>
                 <?php } ?>


                <?php if ( $contact_email ) {?>
                  <div class="email-content">
                    <span class="glyphicon glyphicon-envelope"></span>
                    <span><a href="mailto:<?php echo $contact_email; ?>"><?php echo $contact_email; ?></a></span>
                  </div>
                 <?php } ?>
            
            </div>



              <?php echo $after_widget; ?>
        <?php
    }
 
    /** @see WP_Widget::update -- do not rename this */
    function update($new_instance, $old_instance) {		

		    $instance = $old_instance;
		    $instance['title']            = strip_tags($new_instance['title']);
		    $instance['contact_address']  = $new_instance['contact_address'];
        $instance['contact_phone']    = $new_instance['contact_phone'];
        $instance['contact_email']    = $new_instance['contact_email'];
        return $instance;
    }
 
    /** @see WP_Widget::form -- do not rename this */
    function form($instance) {	
        
        $title              = (isset($instance['title']) ? esc_attr($instance['title']) : "");
        $contact_address 		= (isset($instance['contact_address']) ? esc_attr($instance['contact_address']) : "");
        $contact_phone	    = (isset($instance['contact_phone']) ? esc_attr($instance['contact_phone']) : "");
        $contact_email      = (isset($instance['contact_email']) ? esc_attr($instance['contact_email']) : "");
        ?>
       
       <p>
          <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', theme_name); ?></label> 
          <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>


        <p>
          <label for="<?php echo $this->get_field_id('contact_address'); ?>"><?php _e('Address:', theme_name); ?></label> 
          <input class="widefat" id="<?php echo $this->get_field_id('contact_address'); ?>" name="<?php echo $this->get_field_name('contact_address'); ?>" type="text" value="<?php echo $contact_address; ?>" />
        </p>
		  
        <p>
          <label for="<?php echo $this->get_field_id('contact_phone'); ?>"><?php _e('Contact Phone:', theme_name); ?></label> 
          <input class="widefat" id="<?php echo $this->get_field_id('contact_phone'); ?>" name="<?php echo $this->get_field_name('contact_phone'); ?>" type="text" value="<?php echo $contact_phone; ?>" />
        </p>

        <p>
          <label for="<?php echo $this->get_field_id('contact_email'); ?>"><?php _e('Contact Email:', theme_name); ?></label> 
          <input class="widefat" id="<?php echo $this->get_field_id('contact_email'); ?>" name="<?php echo $this->get_field_name('contact_email'); ?>" type="text" value="<?php echo $contact_email; ?>" />
        </p>
			
        <?php 
    }
 
 
} // end class contact_address_widget
add_action('widgets_init', create_function('', 'return register_widget("contact_address_widget");'));
?>
