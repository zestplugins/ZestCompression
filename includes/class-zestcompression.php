<?php

class ZestCompressionPlugin {

    public function __construct() {
        add_action('plugins_loaded', array($this, 'init'));
    }

    public function init() {
        add_filter('wp_handle_upload', array($this, 'handle_upload'));
    }

    private function compress_image($file_path, $mime_type) {
        if (strpos($mime_type, 'image/jpeg') === 0) {
            $this->compress_jpeg($file_path);
        } elseif (strpos($mime_type, 'image/png') === 0) {
            $this->compress_png($file_path);
        } elseif (strpos($mime_type, 'image/gif') === 0) {
            $this->compress_gif($file_path);
        }
    }

    private function compress_jpeg($file_path) {
        //TODO:  Implement JPEG compression logic using a library like jpegoptim or mozjpeg.
        // Example: exec('jpegoptim --max=80 ' . escapeshellarg($file_path));
    }

    private function compress_png($file_path) {
        //TODO:  Implement PNG compression logic using a library like optipng or pngquant.
        // Example: exec('optipng ' . escapeshellarg($file_path));
    }

    private function compress_gif($file_path) {
        // TODO: Implement GIF compression logic using a library like gifsicle.
        // Example: exec('gifsicle -O3 ' . escapeshellarg($file_path));
    }

    public function handle_upload($file) {
        if (strpos($file['type'], 'image') === 0) {
            $this->compress_image($file['file'], $file['type']);
        }

        return $file;
    }

}

// Instantiate the class
$zest_compression_plugin = new ZestCompressionPlugin();