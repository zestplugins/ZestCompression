<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

class ZestCompressionPlugin {

	/**
	 * Constructor to initialize the plugin.
	 */
	public function __construct() {
		add_action( 'plugins_loaded', array( $this, 'init' ) );
	}

	/**
	 * Initializes the plugin by adding necessary hooks.
	 */
	public function init() {
		add_filter( 'wp_generate_attachment_metadata', array( $this, 'compress_uploaded_image' ), 10, 2 );
		add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'page_init' ) );
	}

	/**
	 * Adds the plugin page to the WordPress admin menu.
	 */
	public function add_plugin_page() {
		add_options_page(
			__( 'Zest Compression Settings', 'zest-compression' ),
			__( 'Zest Compression', 'zest-compression' ),
			'manage_options',
			'zest-compression-settings',
			array( $this, 'create_admin_page' )
		);
	}

	/**
	 * Callback function to create the admin page content.
	 */
	public function create_admin_page() {
		?>
		<div class="wrap">
			<form method="post" action="options.php">
				<?php
				settings_fields( 'zest_compression_settings' );
				do_settings_sections( 'zest-compression-settings' );
				submit_button();
				?>
			</form>
		</div>
		<?php
	}

	/**
	 * Initializes the settings page.
	 */
	public function page_init() {
		register_setting(
			'zest_compression_settings',
			'zest_compression_settings',
			array( $this, 'sanitize' )
		);

		add_settings_section(
			'compression_settings_section',
			__( 'Zest Compression Settings', 'zest-compression' ),
			array( $this, 'print_section_info' ),
			'zest-compression-settings'
		);

		add_settings_field(
			'enable_compression',
			__( 'Auto Compress Images', 'zest-compression' ),
			array( $this, 'enable_compression_callback' ),
			'zest-compression-settings',
			'compression_settings_section'
		);

		add_settings_field(
			'backup_original_images',
			__( 'Backup Original Images', 'zest-compression' ),
			array( $this, 'backup_original_images_callback' ),
			'zest-compression-settings',
			'compression_settings_section'
		);

		add_settings_field(
			'compression_quality',
			__( 'Compression Quality', 'zest-compression' ),
			array( $this, 'compression_quality_callback' ),
			'zest-compression-settings',
			'compression_settings_section'
		);
	}

	/**
	 * Sanitizes the plugin settings.
	 *
	 * @param array $input The input values.
	 * @return array Sanitized input.
	 */
	public function sanitize( $input) {
		$input = is_array( $input) ? $input : array();

		$new_input = array();
		$new_input['enable_compression'] = isset( $input['enable_compression'] ) ? 1 : 0;
		$new_input['backup_original_images'] = isset( $input['backup_original_images'] ) ? 1 : 0;
		$new_input['compression_quality'] = isset( $input['compression_quality'] ) ? intval( $input['compression_quality'] ) : '';

		return $new_input;
	}
	
	/**
	 * Callback function to display the "Enable Compression" field.
	 */
	public function enable_compression_callback() {
		$settings = get_option( 'zest_compression_settings' );
		$enable_compression = isset( $settings['enable_compression'] ) ? absint( $settings['enable_compression'] ) : 0;
		?>
		<fieldset>
			<label>
				<input type="radio" name="zest_compression_settings[enable_compression]" value="1" <?php checked( 1, $enable_compression ); ?> />
				<?php esc_html_e( 'Enable', 'zest-compression' ); ?>
			</label>
			<label>
				<input type="radio" name="zest_compression_settings[enable_compression]" value="0" <?php checked( 0, $enable_compression ); ?> />
				<?php esc_html_e( 'Disable', 'zest-compression' ); ?>
			</label>
			<p class="description"><?php esc_html_e( 'Automatically compress every uploaded image to the media folder.', 'zest-compression' ); ?></p>
		</fieldset>
		<?php
	}

	/**
	 * Callback function to display the "Backup Original Images" field.
	 *
	 * This function generates the HTML markup for the "Backup Original Images" field
	 * in the plugin settings page. It includes a checkbox that allows the user to enable
	 * or disable the backup of original images before compression. The current setting
	 * is retrieved from the plugin options and used to pre-fill the checkbox.
	 *
	 * @return void
	 */
	public function backup_original_images_callback() {
		// Get the current settings
		$settings = get_option( 'zest_compression_settings' );

		// Get the value of the 'backup_original_images' setting or default to 0 (disabled)
		$backup_original_images = isset( $settings['backup_original_images'] ) ? absint( $settings['backup_original_images'] ) : 0;
		?>
		<fieldset>
			<label>
				<input type="checkbox" name="zest_compression_settings[backup_original_images]" value="1" <?php checked( 1, $backup_original_images ); ?> />
				<?php esc_html_e( 'Enable', 'zest-compression' ); ?>
			</label>
			<p class="description"><?php esc_html_e( 'Enable to backup original images in a separate folder before compression.', 'zest-compression' ); ?></p>
		</fieldset>
		<?php
	}

	/**
	 * Callback function to display the "Compression Quality" field.
	 */
	public function compression_quality_callback() {
		$settings = get_option( 'zest_compression_settings' );
		$compression_quality = isset( $settings['compression_quality'] ) ? intval( $settings['compression_quality'] ) : 0;
		?>
		<input type="number" id="compression_quality" name="zest_compression_settings[compression_quality]" value="<?php echo esc_attr( $compression_quality ); ?>" min="1" max="100" />
		<p class="description"><?php esc_html_e( 'Set the compression quality level. Lower values result in higher compression.', 'zest-compression' ); ?></p>
		<?php
	}

	/**
	 * Callback function to print section information.
	 */
	public function print_section_info() {
		esc_html_e( 'Configure compression settings below:', 'zest-compression' );
	}

	/**
	 * Callback function to compress an uploaded image.
	 *
	 * @param array $metadata       An array of attachment metadata.
	 * @param int   $attachment_id  The attachment ID.
	 * @return array The attachment metadata.
	 */
	public function compress_uploaded_image( $metadata, $attachment_id ) {
		$settings = get_option( 'zest_compression_settings' );
	
		if ( ! empty( $settings['enable_compression'] ) && isset( $metadata['file'] ) ) {
			$file_path = get_attached_file( $attachment_id);

			// Check if the file exists
			if ( file_exists( $file_path ) ) {
				// Backup the original image if the option is enabled
				if ( ! empty( $settings['backup_original_images'] ) ) {
					$this->backup_original_image( $file_path );
				}

				// Compress the image
				$this->compress_image( $file_path, $settings['compression_quality'] );
			} else {
				// Handle the case where the file doesn't exist
				error_log( 'File does not exist: ' . $file_path );
			}
		}

		return $metadata;
	}

	/**
	 * Backup the original image before compression.
	 *
	 * This function creates a backup of the original image file in a designated folder
	 * before applying compression. The backup is stored in a subdirectory named
	 * 'original_backups' within the same directory as the original image.
	 *
	 * @param string $file_path The path to the original image file.
	 *
	 * @return void
	 */
	private function backup_original_image( $file_path ) {
		// Define the backup directory path
		$backup_dir = trailingslashit( dirname( $file_path ) ) . 'original_backups/';

		// Create the backup directory if it doesn't exist
		wp_mkdir_p( $backup_dir );

		// Define the full path for the backup file
		$backup_file = trailingslashit( $backup_dir ) . basename( $file_path );

		// Copy the original image to the backup folder
		copy( $file_path, $backup_file );
	}	

	/**
	 * Compresses an image.
	 *
	 * @param string $file_path The path to the image file.
	 * @param int    $quality   The compression quality.
	 */
	private function compress_image( $file_path, $quality) {
		if ( false === function_exists( 'wp_get_image_editor' ) ) {
			include ABSPATH . 'wp-admin/includes/image.php';
		}

		$image_editor = wp_get_image_editor( $file_path);

		if ( ! is_wp_error( $image_editor) ) {
			$image_editor->set_quality( $quality );
			$image_editor->save( $file_path );
		}
	}
}

