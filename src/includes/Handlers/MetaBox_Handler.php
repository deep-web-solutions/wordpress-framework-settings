<?php

namespace DeepWebSolutions\Framework\Settings\Handlers;

use DeepWebSolutions\Framework\Settings\Adapters\MetaBox_Adapter;

defined( 'ABSPATH' ) || exit;

/**
 * Handles the interoperability layer between the DWS framework and the Meta Box settings framework.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Settings\Handlers
 */
class MetaBox_Handler extends AbstractHandler {
	// region MAGIC METHODS

	/**
	 * Meta Box Handler constructor.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   MetaBox_Adapter|null    $adapter    Instance of the adapter to the Meta Box settings framework.
	 */
	public function __construct( ?MetaBox_Adapter $adapter = null ) { // phpcs:ignore
		$adapter = $adapter ?? new MetaBox_Adapter();
		parent::__construct( $adapter );
	}

	// endregion

	// region INHERITED METHODS

	/**
	 * Returns a unique name of the handler.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  string
	 */
	public function get_name(): string {
		return 'meta-box';
	}

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
