<?php
class WP_Catalyzer_Widgets_Settings_Page{


    public function __construct() {
        add_action( 'init', array( &$this, 'init' ) );
        register_activation_hook( __FILE__, array( &$this, 'add_options_defaults' ) );
        add_action( 'admin_init', array( &$this, 'register_settings' ) );
        add_action( 'admin_menu', array( &$this, 'register_settings_page' ) );
    }

    function init() {
        $options = get_option( 'wp_catalyzer_widgets_options' );
        if( !is_admin() ) {
            if( isset( $options[ 'chk_options_css_plugin' ] ) && $options[ 'chk_options_css_plugin' ] ) {
                	wp_register_style('widgets-wp-catalyzer', plugins_url('/wp-catalyzer-widgets/css/style.css'));
					wp_enqueue_style('widgets-wp-catalyzer');
            }
			if( isset( $options[ 'chk_options_css_fontawesome' ] ) && $options[ 'chk_options_css_fontawesome' ] ) {
                	wp_register_style('fontawesome-wp-catalyzer', plugins_url('/wp-catalyzer-widgets/css/fontawesome/css/font-awesome.min.css'));
					wp_enqueue_style('fontawesome-wp-catalyzer');
            }
        } 
        if ( !current_user_can( 'edit_posts' ) && !current_user_can( 'edit_pages' ) ) {
            return;
        }
    }

    function register_settings_page() {
        add_options_page( __( 'WP Catalyzer Widgets', 'wp_catalyzer_widgets' ), __( 'WP Catalyzer Widgets', 'wp_catalyzer_widgets' ), 'manage_options', __FILE__, array( &$this, 'settings_form') );
    }

    function add_options_defaults() {
            $arr = array(
                'chk_options_css_plugin'       => '1',
                'chk_default_options_js'        => '1'
            );
            update_option( 'wp_catalyzer_widgets_options', $arr );
    }

    function register_settings() {
        register_setting( 'webim_plugin_options', 'wp_catalyzer_widgets_options' );
    }

    function settings_form() {
        ?>
        <div class="wrap">
            <h2>WP Catalyzer Widgets Options</h2>
            <form method="post" action="options.php">
                <?php settings_fields( 'webim_plugin_options' ); ?>
                <?php $options = get_option( 'wp_catalyzer_widgets_options'); ?>
                <table class="form-table">

                    <tr><td colspan="2"><div style="margin-top:10px;"></div></td></tr>

                    <tr valign="top" style="border-top:#dddddd 1px solid;">
                        <th scope="row">Include Plugin CSS</th>
                        <td>
                            <label><input name="wp_catalyzer_widgets_options[chk_options_css_plugin]" type="checkbox" value="1" <?php if ( isset( $options[ 'chk_options_css_plugin' ] ) ) { checked( '1', $options[ 'chk_options_css_plugin' ] ); } ?> /> Load Plugin CSS file</label><br /><span style="color:#666666;margin-left:2px;">Uncheck this if you already include this css in your theme</span>
                        </td>
                    </tr>
					<tr valign="top" style="border-top:#dddddd 1px solid;">
                        <th scope="row">Include Font Awesome CSS</th>
                        <td>
                            <label><input name="wp_catalyzer_widgets_options[chk_options_css_fontawesome]" type="checkbox" value="1" <?php if ( isset( $options[ 'chk_options_css_fontawesome' ] ) ) { checked( '1', $options[ 'chk_options_css_fontawesome' ] ); } ?> /> Load Font Awesome CSS</label><br /><span style="color:#666666;margin-left:2px;">Uncheck this if you already include Font Awesome in your theme</span>
                        </td>
                    </tr>
                </table>
                <p class="submit">
                <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
                </p>
            </form>

        </div><?php
    }
}
$wpcatalyzercodes = new WP_Catalyzer_Widgets_Settings_Page();
