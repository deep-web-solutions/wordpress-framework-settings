<?php

namespace DeepWebSolutions\Framework\Settings\Handlers;

use DeepWebSolutions\Framework\Settings\AbstractSettingsHandler;
use DeepWebSolutions\Framework\Settings\Adapters\WordPress_Adapter;

\defined( 'ABSPATH' ) || exit;

/**
 * Handles the interoperability layer between the DWS framework and the WordPress Settings API.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Settings\Handlers
 */
class WordPress_Handler extends AbstractSettingsHandler {
	// region MAGIC METHODS

	/**
	 * WordPress Handler constructor.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string                      $handler_id     The ID of the settings handler.
	 * @param   WordPress_Adapter|null      $adapter        Instance of the adapter to the WordPress Settings API.
	 */
	public function __construct( string $handler_id = 'default', ?WordPress_Adapter $adapter = null ) { // phpcs:ignore
		$adapter = $adapter ?? new WordPress_Adapter();
		parent::__construct( $handler_id, $adapter );
	}

	// endregion

	// region INHERITED METHODS

	/**
	 * Returns the hook on which the WordPress Settings API is ready to be used.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  string
	 */
	public function get_action_hook(): string {
		return 'init';
	}

	// endregion
}
