<?php

class AppMaps_Install {

	/**
	 * Constructor.
	 *
	 * @return void
	 */
	public function __construct() {
		register_activation_hook( plugin_basename( APPMAPS_FILE ), array( $this, 'activate' ) );
	}

	/**
	 * Activate plugin.
	 *
	 * @return void
	 */
	public function activate() : void {
		$this->add_sample_data();
	}

	/**
	 * Add sample data.
	 *
	 * @return void
	 */
	protected function add_sample_data() : void {
		$sample_data = get_option( 'appmaps_sample_data' );

		if ( $sample_data === 'done' ) {
			return;
		}

		$this->set_initial_options();

		update_option( 'appmaps_sample_data', 'done' );
	}

	/**
	 * Set initial options.
	 *
	 * @return void
	 */
	protected function set_initial_options() : void {
		update_option( 'appmaps_active', 0 );
		update_option( 'appmaps_api_key', '' );
		update_option( 'appmaps_lat', '49.99782515937576' );
		update_option( 'appmaps_lng', '19.436830520629883' );
		update_option( 'appmaps_gmaps_lang', 'en' );
		update_option( 'appmaps_gmaps_region', 'PL' );
	}

}
