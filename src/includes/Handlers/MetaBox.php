<?php

namespace DeepWebSolutions\Framework\Settings\Handlers;

use DeepWebSolutions\Framework\Settings\Abstracts\Handler;
use DeepWebSolutions\Framework\Settings\Adapters\MetaBox as MetaBoxAdapter;
use DeepWebSolutions\Framework\Utilities\Services\LoggingService;

defined( 'ABSPATH' ) || exit;

/**
 * Handles the interoperability layer between the DWS framework and the Meta Box settings framework.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Settings\Handlers
 */
class MetaBox extends Handler {
	// region MAGIC METHODS

	/**
	 * Meta Box Handler constructor.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   MetaBoxAdapter  $adapter            Instance of the adapter to the Meta Box settings framework.
	 * @param   LoggingService  $logging_service    Instance of the logging service.
	 */
	public function __construct( MetaBoxAdapter $adapter, LoggingService $logging_service ) { // phpcs:ignore
		parent::__construct( $adapter, $logging_service );
	}

	// endregion

	// region INHERITED METHODS

	/**
	 * Returns the hook on which the Meta Box framework is ready to be used.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $context    The action being executed.
	 *
	 * @return  string
	 */
	public function get_action_hook( string $context ): string {
		switch ( $context ) {
			case 'update_field_value':
			case 'update_settings_value':
				return 'wp_loaded'; // @see https://docs.metabox.io/rwmb-set-meta/
			default:
				return 'plugins_loaded';
		}
	}

	/**
	 * Gets the instance of the settings framework adapter. Overwriting this method has no value other than helping
	 * with auto-complete in IDEs.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  MetaBoxAdapter
	 */
	public function get_adapter(): MetaBoxAdapter { // phpcs:ignore
		/* @noinspection PhpIncompatibleReturnTypeInspection */
		return parent::get_adapter();
	}

	// endregion
}
