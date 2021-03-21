<?php

namespace DeepWebSolutions\Framework\Settings\Handlers;

use DeepWebSolutions\Framework\Settings\AbstractSettingsHandler;
use DeepWebSolutions\Framework\Settings\Adapters\MetaBox_Adapter;

\defined( 'ABSPATH' ) || exit;

/**
 * Handles the interoperability layer between the DWS framework and the Meta Box settings framework.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Settings\Handlers
 */
class MetaBox_Handler extends AbstractSettingsHandler {
	// region MAGIC METHODS

	/**
	 * Meta Box Handler constructor.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string                  $handler_id     The ID of the settings handler.
	 * @param   MetaBox_Adapter|null    $adapter        Instance of the adapter to the Meta Box settings framework.
	 */
	public function __construct( string $handler_id = 'meta-box', ?MetaBox_Adapter $adapter = null ) { // phpcs:ignore
		parent::__construct( $handler_id, $adapter ?? new MetaBox_Adapter() );
	}

	// endregion

	// region INHERITED METHODS

	/**
	 * Returns the hook on which the Meta Box framework is ready to be used.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  string
	 */
	public function get_action_hook(): string {
		return 'plugins_loaded';
	}

	// endregion
}
