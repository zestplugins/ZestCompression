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
		<div class="wrap" style="background-color: white; margin-right: 15px; margin-top: 15px; min-height: 100svh;">

			<div class="zesthours-help-main">
				<h1 style="color: whitesmoke;"><?php esc_html_e( 'Zest Compression', 'zest-compression' ); ?></h1>
			</div>

			<div class="zesthours-help-tabs">
					<ul class="zesthours-help-tab-links">
						<li class="zesthours-help-tab-active"><a href="#zesthours-welcome-tab"><?php esc_html_e( 'General', 'zest-compression' ); ?></a></li>
						<li><a href="#zesthours-support-tab"><?php esc_html_e( 'Support', 'zest-compression' ); ?></a></li>
					</ul>
			
					<div class="zesthours-help-tab-content">
						<div id="zesthours-welcome-tab" class="zesthours-help-tab zesthours-help-tab-active">
							<form method="post" action="options.php">
								<?php
								settings_fields( 'zest_compression_settings' );
								do_settings_sections( 'zest-compression-settings' );
								submit_button();
								?>
							</form>
						</div>
			
						<div id="zesthours-support-tab" class="zesthours-help-tab">
							<h3><?php esc_html_e( '🚑 Require assistance? Our support team is ready to assist you.', 'zest-compression' ); ?></h3>
							<div class="zesthours-supp">								
								<p><a href="https://dev.to/zestplugins"><?php esc_html_e( 'Zest Compression Documentation => ', 'zest-compression' ); ?></a>Our documentation comprehensively covers all you require, from installation instructions and hours management to troubleshooting common issues and expanding functionality.</p>
							</div>
							<div class="zesthours-supp">								
								<p><a href="https://github.com/zestplugins/ZestCompression/issues/new?assignees=&labels=&projects=&template=bug_report.md&title="><?php esc_html_e( 'Zest Compression Bug Report => ', 'zest-compression' ); ?></a>Stumbled upon an issue or a bug? We appreciate your help in making our product better. Please take a moment to report it, and we'll work diligently to address it.</p>
							</div>
							<div class="zesthours-supp">								
								<p><a href="https://github.com/zestplugins/ZestCompression/issues/new?assignees=&labels=&projects=&template=feature_request.md&title="><?php esc_html_e( 'Zest Compression Feature Request => ', 'zest-compression' ); ?></a>Have a great idea for a new feature or improvement? We'd love to hear your suggestions! Share your thoughts with us, and we'll consider implementing it to enhance our product.</p>
							</div>
						</div>
					</div>
				</div>
		</div>
	
		<script>
			document.addEventListener("DOMContentLoaded", function() {
				const tabLinks = document.querySelectorAll(".zesthours-help-tab-links a");
				const tabContents = document.querySelectorAll(".zesthours-help-tab-content .zesthours-help-tab");

				tabLinks.forEach((link) => {
					link.addEventListener("click", function (e) {
						e.preventDefault();
						tabLinks.forEach((l) => l.parentElement.classList.remove("zesthours-help-tab-active"));
						this.parentElement.classList.add("zesthours-help-tab-active");

						const targetTab = document.querySelector(this.getAttribute("href"));
						tabContents.forEach((tab) => tab.classList.remove("zesthours-help-tab-active"));
						targetTab.classList.add("zesthours-help-tab-active");
					});
				});
			});
		</script>

		<style>
			
		.zesthours-help-tabs {
			font-family: Arial, sans-serif;
			padding-left: 10px;
		}
		.zesthours-supp a{
			text-decoration: none;
		}

		.zesthours-help-main {
			display: flex;
			flex-direction: column;
			justify-content: center;
			align-items: center;
			text-align: center;
			background-color: #3498db;
			min-height: 100px;
		}

		.zesthours-help-tab-links {
			display: flex;
			list-style: none;
			padding: 0;
			margin: 0;
		}

		.zesthours-help-tab-links li {
			margin-right: 10px;
		}

		.zesthours-help-tab-links a {
			text-decoration: none;
			background-color: #f2f2f2;
			padding: 10px 20px;
			border: 1px solid #ccc;
			border-radius: 5px;
		}

		.zesthours-help-tab-links a:hover {
			background-color: #ddd;
		}

		.zesthours-help-tab-links .zesthours-help-tab-active a {
			background-color: #fff;
			border: 1px solid #ddd;
		}

		.zesthours-help-tab {
			display: none;
		}

		.zesthours-help-tab-active {
			display: block;
		}

		/* settings page styling */
		.zesthours-settings-main {
			display: flex;
			flex-direction: column;
			justify-content: center;
			align-items: center;
			text-align: center;
			background-color: #3498db;
			min-height: 100px;
		}
		.zesthours-settings-tabs {
			display: flex;
			list-style: none;
			padding: 0;
			margin: 0;
		}

		.zesthours-settings-tab {
			padding: 10px 13px;
			background-color: #3498db;
			cursor: pointer;
			border-bottom: none;
			margin-right: 10px;
		}

		.zesthours-settings-tab-content {
			display: none;
			padding: 10px;
		}
		.zesthours-settings-active-tab {
			background-color: white;
			border: none;
		}
		</style>
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
			__( 'Settings', 'zest-compression' ),
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
			'compression_quality',
			__( 'Compression Quality', 'zest-compression' ),
			array( $this, 'compression_quality_callback' ),
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
	}

	/**
	 * Sanitizes the plugin settings.
	 *
	 * @param array $input The input values.
	 * @return array Sanitized input.
	 */
	public function sanitize( $input ) {
		$input = is_array( $input ) ? $input : array();

		$new_input = array();
		$new_input['enable_compression'] = isset( $input['enable_compression'] ) ? absint( $input['enable_compression'] ) : 0;
		$new_input['backup_original_images'] = isset( $input['backup_original_images'] ) ? absint( $input['backup_original_images'] ) : 0;
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
			$file_path = get_attached_file( $attachment_id );

			// Check if the file exists
			if ( file_exists( $file_path ) ) {
				// Backup the original image if the option is enabled
				if ( ! empty( $settings['backup_original_images'] ) ) {
					$this->backup_original_image( $file_path );
				}

				// Compress the image
				$compression_result = $this->compress_image( $file_path, $settings['compression_quality'] );

				if ( is_wp_error( $compression_result ) ) {
					// Display an admin notice for compression error
					$error_message = $compression_result->get_error_message();
					add_settings_error(
						'zest_compression_settings',
						'compression_error',
						sprintf( esc_html__( 'Error compressing image: %s', 'zest-compression' ), $error_message ),
						'error'
					);
				}
			} else {
				// Display an admin notice for file not found
				add_settings_error(
					'zest_compression_settings',
					'file_not_found_error',
					esc_html__( 'Error: The file does not exist.', 'zest-compression' ),
					'error'
				);
			}
		}

		return $metadata;
	}

	/**
	 * Compresses an image using GD library or fallback method.
	 *
	 * @param string $file_path The path to the image file.
	 * @param int    $quality   The compression quality.
	 * @return true|WP_Error True on success, WP_Error on failure.
	 */
	private function compress_image( $file_path, $quality ) {
		// Check if GD library is available
		if ( extension_loaded( 'gd' ) && function_exists( 'gd_info' ) ) {
			// Use GD library for compression
			$success = $this->compress_image_with_gd( $file_path, $quality );

			if ( $success === true ) {
				return true; // Compression success
			} else {
				// If GD compression fails, fallback to the existing method
				return $this->compress_image_fallback( $file_path, $quality );
			}
		} else {
			// If GD is not available, use the existing method as a fallback
			return $this->compress_image_fallback( $file_path, $quality );
		}
	}

	/**
	 * Compresses an image using GD library.
	 *
	 * @param string $file_path The path to the image file.
	 * @param int    $quality   The compression quality.
	 * @return true|WP_Error True on success, WP_Error on failure.
	 */
	private function compress_image_with_gd( $file_path, $quality ) {
		// Perform GD library compression
		$image = imagecreatefromstring( file_get_contents( $file_path ) );

		if ( $image !== false ) {
			// Create a temporary file for saving the compressed image
			$temp_file = tempnam( sys_get_temp_dir(), 'compressed_image' );
			
			// Save the compressed image
			$success = imagejpeg( $image, $temp_file, $quality );

			// Free up memory
			imagedestroy( $image );

			if ( $success ) {
				// Replace the original image with the compressed one
				copy( $temp_file, $file_path );
				unlink( $temp_file );

				return true; // Compression success
			} else {
				return new WP_Error( 'compression_error', esc_html__( 'Error compressing image with GD library.', 'zest-compression' ) );
			}
		} else {
			return new WP_Error( 'image_create_error', esc_html__( 'Error creating image from file with GD library.', 'zest-compression' ) );
		}
	}

	/**
	 * Fallback method for compressing an image.
	 *
	 * @param string $file_path The path to the image file.
	 * @param int    $quality   The compression quality.
	 * @return true|WP_Error True on success, WP_Error on failure.
	 */
	private function compress_image_fallback( $file_path, $quality ) {
		if ( false === function_exists( 'wp_get_image_editor' ) ) {
			include ABSPATH . 'wp-admin/includes/image.php';
		}

		$image_editor = wp_get_image_editor( $file_path );

		if ( is_wp_error( $image_editor ) ) {
			// Return a WP_Error instance on failure
			return $image_editor;
		}

		$image_editor->set_quality( $quality );
		$saved = $image_editor->save( $file_path );

		if ( is_wp_error( $saved ) ) {
			// Return a WP_Error instance on failure
			return $saved;
		}

		return true; // Compression success
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
}

