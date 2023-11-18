<?php

class ZestCompressionPlugin {

    public function __construct() {
        add_action('plugins_loaded', array($this, 'init'));
    }

    public function init() {
        add_filter('wp_generate_attachment_metadata', array($this, 'compress_uploaded_image'), 10, 2);
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
        $input = is_array($input) ? $input : array();

        $new_input = array();
        $new_input['enable_compression'] = isset($input['enable_compression']) ? 1 : 0;
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

    public function compress_uploaded_image($metadata, $attachment_id) {
        $settings = get_option('zest_compression_settings');

        if (!empty($settings['enable_compression']) && isset($metadata['file'])) {
            $file_path = get_attached_file($attachment_id);
            
            // Check if the file exists
            if (file_exists($file_path)) {
                $this->compress_image($file_path, $settings['compression_quality']);
            } else {
                // Handle the case where the file doesn't exist
                error_log('File does not exist: ' . $file_path);
            }
        }

        return $metadata;
    }


    private function compress_image($file_path, $quality) {
        if (false === function_exists('wp_get_image_editor')) {
            include ABSPATH . 'wp-admin/includes/image.php';
        }

        $image_editor = wp_get_image_editor($file_path);

        if (!is_wp_error($image_editor)) {
            $image_editor->set_quality($quality);
            $image_editor->save($file_path);
        }
    }
}

// Instantiate the class
$zest_compression_plugin = new ZestCompressionPlugin();
