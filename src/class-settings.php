<?php

class AppMaps_Settings {

	/**
	 * Constructor.
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_menu_page' ) );
		add_action( 'admin_init', array( $this, 'init_settings' ), 10 );
	}

	/**
	 * Add menu page.
	 *
	 * @return void
	 */
	public function add_menu_page() : void {
		add_options_page(
			__( 'AppMaps', APPMAPS_TD ),
			__( 'AppMaps', APPMAPS_TD ),
			'manage_options',
			'appmaps',
			array( $this, 'render_page' )
		);
	}

	/**
	 * Initialize settings.
	 *
	 * @return void
	 */
	public function init_settings() : void {
		// Section: AppMaps.
		add_settings_section(
			'appmaps_settings_section',
			__( 'AppMaps', APPMAPS_TD ),
			array( $this, 'render_settings_section' ),
			'appmaps_settings'
		);

		// Option: Enabled.
		$this->register_field_enabled();

		// Option: Google Maps API Key.
		$this->register_field_api_key();

		// Option: Default Latitude.
		$this->register_field_default_latitude();

		// Option: Default Longitude.
		$this->register_field_default_longitude();

		// Option: Maps Language.
		$this->register_field_maps_language();

		// Option: Maps Region.
		$this->register_field_maps_region();

	}

	/**
	 * Render page.
	 *
	 * @return void
	 */
	public function render_page() : void {
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'AppMaps Settings', APPMAPS_TD ); ?></h1>
			<form method="post" action="options.php">
				<?php
				settings_fields( 'appmaps_settings' );
				do_settings_sections( 'appmaps_settings' );
				submit_button();
				?>
			</form>
		</div>
		<?php
	}

	/**
	 * Render settings section.
	 *
	 * @return void
	 */
	public function render_settings_section() : void {
		esc_html_e( 'Settings for Google Maps.', APPMAPS_TD );
	}

	/**
	 * Register settings field enabled.
	 *
	 * @return void
	 */
	public function register_field_enabled() : void {
		$field_name   = 'appmaps_active';
		$section_name = 'appmaps_settings_section';

		register_setting(
			'appmaps_settings',
			$field_name,
			array(
				'label'             => __( 'Enabled', APPMAPS_TD ),
				'description'       => __( 'Enable custom map marker locations.', APPMAPS_TD ),
				'type'              => 'integer',
				'sanitize_callback' => 'absint',
				'default'           => 0,
				'show_in_rest'      => false,
			),
		);

		add_settings_field(
			$field_name,
			__( 'Enable', APPMAPS_TD ),
			array( $this, 'render_field_enabled' ),
			'appmaps_settings',
			$section_name,
			array(
				'label_for' => $field_name,
			),
		);
	}

	/**
	 * Register settings field API key.
	 *
	 * @return void
	 */
	public function register_field_api_key() : void {
		$field_name   = 'appmaps_api_key';
		$section_name = 'appmaps_settings_section';

		register_setting(
			'appmaps_settings',
			$field_name,
			array(
				'label'             => __( 'Google Maps API Key', APPMAPS_TD ),
				'description'       => sprintf( __( 'Get free API key for <a href="%s" target="_new">Google Maps</a>.', APPMAPS_TD ), 'https://code.google.com/apis/maps/signup.html' ),
				'type'              => 'string',
				'sanitize_callback' => 'sanitize_text_field',
				'default'           => '',
				'show_in_rest'      => false,
			),
		);

		add_settings_field(
			$field_name,
			__( 'Google Maps API Key', APPMAPS_TD ),
			array( $this, 'render_field_api_key' ),
			'appmaps_settings',
			$section_name,
			array(
				'label_for' => $field_name,
			),
		);
	}

	/**
	 * Register settings field Default Latitude.
	 *
	 * @return void
	 */
	public function register_field_default_latitude() : void {
		$field_name   = 'appmaps_lat';
		$section_name = 'appmaps_settings_section';

		register_setting(
			'appmaps_settings',
			$field_name,
			array(
				'label'             => __( 'Default latitude', APPMAPS_TD ),
				'description'       => __( 'Default latitude on the map.', APPMAPS_TD ),
				'type'              => 'string',
				'sanitize_callback' => 'sanitize_text_field',
				'default'           => '',
				'show_in_rest'      => false,
			),
		);

		add_settings_field(
			$field_name,
			__( 'Default latitude', APPMAPS_TD ),
			array( $this, 'render_field_default_latitude' ),
			'appmaps_settings',
			$section_name,
			array(
				'label_for' => $field_name,
			),
		);
	}

	/**
	 * Register settings field Default Longitude.
	 *
	 * @return void
	 */
	public function register_field_default_longitude() : void {
		$field_name   = 'appmaps_lng';
		$section_name = 'appmaps_settings_section';

		register_setting(
			'appmaps_settings',
			$field_name,
			array(
				'label'             => __( 'Default longitude', APPMAPS_TD ),
				'description'       => __( 'Default longitude on the map.', APPMAPS_TD ),
				'type'              => 'string',
				'sanitize_callback' => 'sanitize_text_field',
				'default'           => '',
				'show_in_rest'      => false,
			),
		);

		add_settings_field(
			$field_name,
			__( 'Default longitude', APPMAPS_TD ),
			array( $this, 'render_field_default_longitude' ),
			'appmaps_settings',
			$section_name,
			array(
				'label_for' => $field_name,
			),
		);
	}

	/**
	 * Register settings field Maps Language.
	 *
	 * @return void
	 */
	public function register_field_maps_language() : void {
		$field_name   = 'appmaps_gmaps_lang';
		$section_name = 'appmaps_settings_section';

		register_setting(
			'appmaps_settings',
			$field_name,
			array(
				'label'             => __( 'Maps Language', APPMAPS_TD ),
				'description'       => __( 'Language for Google Maps.', APPMAPS_TD ),
				'type'              => 'string',
				'sanitize_callback' => 'sanitize_text_field',
				'default'           => '',
				'show_in_rest'      => false,
			),
		);

		add_settings_field(
			$field_name,
			__( 'Maps Language', APPMAPS_TD ),
			array( $this, 'render_field_maps_language' ),
			'appmaps_settings',
			$section_name,
			array(
				'label_for' => $field_name,
			),
		);
	}

	/**
	 * Register settings field Maps Region.
	 *
	 * @return void
	 */
	public function register_field_maps_region() : void {
		$field_name   = 'appmaps_gmaps_region';
		$section_name = 'appmaps_settings_section';

		register_setting(
			'appmaps_settings',
			$field_name,
			array(
				'label'             => __( 'Maps Region', APPMAPS_TD ),
				'description'       => __( 'Region for Google Maps.', APPMAPS_TD ),
				'type'              => 'string',
				'sanitize_callback' => 'sanitize_text_field',
				'default'           => '',
				'show_in_rest'      => false,
			),
		);

		add_settings_field(
			$field_name,
			__( 'Maps Region', APPMAPS_TD ),
			array( $this, 'render_field_maps_region' ),
			'appmaps_settings',
			$section_name,
			array(
				'label_for' => $field_name,
			),
		);
	}

	/**
	 * Render settings field enabled.
	 *
	 * @return void
	 */
	public function render_field_enabled() : void {
		$field_name = 'appmaps_active';

		$enabled = get_option( $field_name, 0 );
		?>
		<input type="checkbox" name="<?php echo $field_name; ?>" id="<?php echo $field_name; ?>" value="1" <?php checked( $enabled, 1 ); ?>>
		<p class="description"><?php esc_html_e( 'Enable custom map marker locations.', APPMAPS_TD ); ?></p>
		<?php
	}

	/**
	 * Render settings field API Key.
	 *
	 * @return void
	 */
	public function render_field_api_key() : void {
		$field_name = 'appmaps_api_key';

		$api_key = get_option( $field_name, '' );
		?>
		<input type="text" name="<?php echo $field_name; ?>" id="<?php echo $field_name; ?>" value="<?php echo $api_key; ?>" class="regular-text">
		<p class="description"><?php printf( __( 'Get free API key for <a href="%s" target="_new">Google Maps</a>.', APPMAPS_TD ), 'https://code.google.com/apis/maps/signup.html' ); ?></p>
		<?php
	}

	/**
	 * Render settings field Default Latitude.
	 *
	 * @return void
	 */
	public function render_field_default_latitude() : void {
		$field_name = 'appmaps_lat';

		$default_latitude = get_option( $field_name, '' );
		?>
		<input type="text" name="<?php echo $field_name; ?>" id="<?php echo $field_name; ?>" value="<?php echo $default_latitude; ?>" class="regular-text">
		<p class="description"><?php esc_html_e( 'Default latitude on the map.', APPMAPS_TD ); ?></p>
		<?php
	}

	/**
	 * Render settings field Default Longitude.
	 *
	 * @return void
	 */
	public function render_field_default_longitude() : void {
		$field_name = 'appmaps_lng';

		$default_longitude = get_option( $field_name, '' );
		?>
		<input type="text" name="<?php echo $field_name; ?>" id="<?php echo $field_name; ?>" value="<?php echo $default_longitude; ?>" class="regular-text">
		<p class="description"><?php esc_html_e( 'Default longitude on the map.', APPMAPS_TD ); ?></p>
		<?php
	}

	/**
	 * Render settings field Maps Language.
	 *
	 * @return void
	 */
	public function render_field_maps_language() : void {
		$field_name = 'appmaps_gmaps_lang';

		$maps_language = get_option( $field_name, 'en' );
		?>
		<input type="text" name="<?php echo $field_name; ?>" id="<?php echo $field_name; ?>" value="<?php echo $maps_language; ?>" class="regular-text">
		<p class="description"><?php esc_html_e( 'Language for Google Maps.', APPMAPS_TD ); ?></p>
		<p class="description"><?php printf( __( 'Find the list of supported language codes <a href="%s" target="_new">here</a>.', APPMAPS_TD ), 'http://en.wikipedia.org/wiki/List_of_ISO_639-1_codes' ); ?></p>
		<p class="description"><?php esc_html_e( 'The Google Maps API uses the browsers language setting when displaying textual info on the map. In most cases, this is preferable and you should not need to override this setting. However, if you wish to change the Maps API to ignore the browsers language setting and force it to display info in a particular language, enter your two character region code here (i.e. Japanese is ja).', APPMAPS_TD ); ?></p>
		<?php
	}

	/**
	 * Render settings field Maps Region.
	 *
	 * @return void
	 */
	public function render_field_maps_region() : void {
		$field_name = 'appmaps_gmaps_region';

		$maps_region = get_option( $field_name, 'PL' );
		?>
		<input type="text" name="<?php echo $field_name; ?>" id="<?php echo $field_name; ?>" value="<?php echo $maps_region; ?>" class="regular-text">
		<p class="description"><?php esc_html_e( 'Region for Google Maps.', APPMAPS_TD ); ?></p>
		<p class="description"><?php printf( __( 'Find your two-letter ISO 3166-1 region code <a href="%s" target="_new">here</a>.', APPMAPS_TD ), 'https://en.wikipedia.org/wiki/ISO_3166-1' ); ?></p>
		<p class="description"><?php esc_html_e( 'Enter your country\'s two-letter region code here to properly display map locations. (i.e. Someone enters the location "Toledo", it\'s based off the default region (US) and will display "Toledo, Ohio". With the region code set to "ES" (Spain), the results will show "Toledo, Spain.")', APPMAPS_TD ); ?></p>
		<?php
	}

}
