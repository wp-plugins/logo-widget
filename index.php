<?php
/**
 * Plugin Name: Logo Widget
 * Plugin URI: http://c1.com.pl/
 * Description: Adds custom logos widgets - images with links
 * Version: 0.2.1
 * Author: Mateusz Paulski
 * Author URI: http://c1.com.pl
 */

define('URL', plugins_url('', __FILE__));

if (!class_exists('c1_logo_widget')) {

    class c1_logo_widget extends WP_Widget
    {
        private static $script_loaded = false;

        function __construct()
        {
            $widget_ops = array(
                'classname' => 'c1-logo-widget c1-logo',
                'description' => __('Adds custom logos widgets - images with links', 'c1-plugin')
            );
            $control_ops = array(
                'width' => 300,
                'height' => 350,
                'id_base' => 'c1-logo-widget'
            );
            $this->WP_Widget('c1-logo-widget', __('Logo widget', 'c1-plugin'), $widget_ops, $control_ops);

            add_action('widgets_init', array(
                &$this,
                'c1_load_logo_widget'
            ));
            add_action('plugins_loaded', array(
                &$this,
                'c1_load_textdomain'
            ));

            add_action('admin_head', array(
                &$this,
                'c1_load_admin_js'
            ));


        }

        function c1_load_logo_widget()
        {
            register_widget('c1_logo_widget');
            if (function_exists('wp_enqueue_media')) {
                wp_enqueue_media();
            } else {
                wp_enqueue_style('thickbox');
                wp_enqueue_script('media-upload');
                wp_enqueue_script('thickbox');
            }
        }

        function c1_load_textdomain()
        {
            load_plugin_textdomain('c1-plugin', FALSE, dirname(plugin_basename(__FILE__)) . '/lang');
        }

        function c1_load_admin_js()
        {
            // single load
            if (self::$script_loaded === false) {
                $url = URL . '/js/script.js';
                echo "<script type='text/javascript' src='$url'></script>";
                self::$script_loaded = true;
            }
        }

        public function widget($args, $instance)
        {
            foreach ($instance as $key => $value) {
                $$key = $value;
            }
            echo $args['before_widget'];
            if (!empty($title)) {
                echo $args['before_title'] . apply_filters('widget_title', $title) . $args['after_title'];
            }
            $window = (!empty($window)) ? " target='_blank'" : " rel='fancybox'";

            if (!empty($link))
                echo "<a href='" . $link . "'" . $window . ">";

            if (!empty($image))
                echo '<img src="' . $image . '" alt="logo" class="widget-logos" />';


            if (!empty($link))
                echo "</a>";

            echo $args['after_widget'];


        }


        public function update($new_instance, $old_instance)
        {
            $instance = $old_instance;
            $args = array(
                'title',
                'image',
                'link',
                'window'
            );
            foreach ($args as $key) {
                if ($key === 'title') {
                    strip_tags($new_instance[$key]);
                }
                $instance[$key] = $new_instance[$key];
            }
            return $instance;
        }

        public function form($instance)
        {
            foreach ($instance as $key => $value) {
                $$key = $value;
            }
            ?>
            <p>
                <label for="<?= $this->get_field_id('title'); ?>"><?php _e('Title:', 'c1-plugin'); ?></label>
                <input type="text" id="<?= $this->get_field_id('title'); ?>" name="<?= $this->get_field_name('title'); ?>" value='<?= ((isset($title)) ? $title : '') ?>' class="widefat"/>
            </p>
            <p>
                <label for="<?= $this->get_field_id('image'); ?>"><?php _e('Image URL:', 'c1-plugin'); ?></label>
                <input type="text" class="c1-logo-widget-image widefat" id="<?= $this->get_field_id('image'); ?>" name="<?= $this->get_field_name('image'); ?>" value='<?= ((isset($image)) ? $image : '') ?>'/>
                <img <?= ((isset($image) && $image != '') ? ' src="' . $image . '"' : ''); ?> class="c1-logo-widget-image-preview" style="width:100px; height:auto; max-height:200px;"/>
                <button class="c1-logo-widget-upload-media button button-primary widget-control-save right"><?php _e('Choose image', 'c1-plugin'); ?></button>
            </p>
            <p style="clear:both;">
                <label for="<?= $this->get_field_id('link'); ?>"><?php _e('Link URL:', 'c1-plugin'); ?></label>
                <input type="text" id="<?= $this->get_field_id('link'); ?>" name="<?= $this->get_field_name('link'); ?>" value='<?= ((isset($link)) ? $link : '') ?>' class="widefat"/>
            </p>
            <p>
                <label for="<?= $this->get_field_id('window'); ?>"><?php _e('New window?:', 'c1-plugin'); ?></label>
                <input type="checkbox" id="<?= $this->get_field_id('window'); ?>" name="<?= $this->get_field_name('window'); ?>" value='1' style="" <?= ((isset($window)) ? 'checked' : '') ?> />
            </p>
            <hr style="margin-top:20px;">
            <p style="text-align: center; font-size:12px;">
                Like our plugin? <strong>Donate</strong>
                <a href="https://www.paypal.com/pl/cgi-bin/webscr?cmd=_donations&business=MXQ2JW5CCA36G&item_name=C1%20Logo%20Widget&currency_code=EUR" target="_blank"><img src="<?= URL; ?>/images/paypal.png" style="vertical-align: middle;margin-left:5px;"/></a>
            </p>
            <hr style="margin-bottom:20px;">
        <?php
        }
    }
}
new c1_logo_widget();