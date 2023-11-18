<?php

class ZestCompressionPlugin {

    public function __construct() {
        add_action('plugins_loaded', array($this, 'init'));
    }

    public function init() {
        add_filter('wp_handle_upload', array($this, 'handle_upload'));
        add_action('admin_menu', array($this, 'add_plugin_page'));
        add_action('admin_init', array($this, 'page_init'));
    }

    public function add_plugin_page() {
        add_options_page(
            'Zest Compression Settings',
            'Zest Compression',
            'manage_options',
            'zest-compression-settings',
            array($this, 'create_admin_page')
        );
    }

    public function create_admin_page() {
        ?>
        <div class="wrap">
            <h2>Zest Compression Settings</h2>
            <form method="post" action="options.php">
                <?php
                settings_fields('zest_compression_settings');
                do_settings_sections('zest-compression-settings');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    public function page_init() {
        register_setting(
            'zest_compression_settings',
            'zest_compression_settings',
            array($this, 'sanitize')
        );

        add_settings_section(
            'compression_settings_section',
            'Compression Settings',
            array($this, 'print_section_info'),
            'zest-compression-settings'
        );

        add_settings_field(
            'enable_compression',
            'Enable Compression',
            array($this, 'enable_compression_callback'),
            'zest-compression-settings',
            'compression_settings_section'
        );

        add_settings_field(
            'compression_quality',
            'Compression Quality',
            array($this, 'compression_quality_callback'),
            'zest-compression-settings',
            'compression_settings_section'
        );
    }

    public function sanitize($input) {
        // Ensure $input is an array to prevent potential errors
        $input = is_array($input) ? $input : array();

        // Sanitize and validate input here
        $new_input = array();

        // Example: Sanitize the 'enable_compression' option
        $new_input['enable_compression'] = isset($input['enable_compression']) ? 1 : 0;

        // Example: Sanitize the 'compression_quality' option
        $new_input['compression_quality'] = isset($input['compression_quality']) ? intval($input['compression_quality']) : '';

        return $new_input;
    }

    public function enable_compression_callback() {
        $settings = get_option('zest_compression_settings');
        echo '<input type="checkbox" id="enable_compression" name="zest_compression_settings[enable_compression]" value="1" ' . checked(1, $settings['enable_compression'], false) . ' />';
    }

    public function compression_quality_callback() {
        $settings = get_option('zest_compression_settings');
        $value = isset($settings['compression_quality']) ? esc_attr($settings['compression_quality']) : '';
        echo '<input type="number" id="compression_quality" name="zest_compression_settings[compression_quality]" value="' . $value . '" min="1" max="100" />';
    }

    public function print_section_info() {
        echo 'Configure compression settings below:';
    }

    

    private function compress_image($file_path, $mime_type) {
        $settings = get_option('zest_compression_settings');

        if (!empty($settings['enable_compression'])) {
            if (strpos($mime_type, 'image/jpeg') === 0) {
                $this->compress_jpeg($file_path, $settings['compression_quality']);
            } elseif (strpos($mime_type, 'image/png') === 0) {
                $this->compress_png($file_path, $settings['compression_quality']);
            } elseif (strpos($mime_type, 'image/gif') === 0) {
                $this->compress_gif($file_path);
            }
        }
    }

    private function compress_jpeg($file_path, $quality) {
        // Load the image
        $image = imagecreatefromjpeg($file_path);
    
        // Create a new JPEG file
        imagejpeg($image, $file_path, $quality);
    
        // Free up memory
        imagedestroy($image);
    }
    
    private function compress_png($file_path, $compression_level) {
        // Load the image
        $image = imagecreatefrompng($file_path);
    
        // Create a new PNG file
        imagepng($image, $file_path, $compression_level);
    
        // Free up memory
        imagedestroy($image);
    }
    
    private function compress_gif($file_path) {
        // Load the image
        $image = imagecreatefromgif($file_path);
    
        // Create a new GIF file
        imagegif($image, $file_path);
    
        // Free up memory
        imagedestroy($image);
    }
    

    public function handle_upload($file) {
        if (strpos($file['type'], 'image') === 0) {
            $this->compress_image($file['file'], $file['type']);
            add_action('admin_notices', array($this, 'compression_success_notice'));
        }

        return $file;
    }

    public function compression_success_notice() {
        ?>
        <div class="notice notice-success is-dismissible">
            <p><?php _e('Image compression is successful!', 'zest-compression'); ?></p>
        </div>
        <?php
    }

}

// Instantiate the class
$zest_compression_plugin = new ZestCompressionPlugin();
