<?php

namespace DeepWebSolutions\Framework\Settings\Handlers;

use DeepWebSolutions\Framework\Settings\Abstracts\Handler;
use DeepWebSolutions\Framework\Settings\Adapters\WordPress as WordPressAdapter;
use DeepWebSolutions\Framework\Utilities\Services\LoggingService;

defined( 'ABSPATH' ) || exit;

/**
 * Handles the interoperability layer between the DWS framework and the WordPress Settings API.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Settings\Handlers
 */
class WordPress extends Handler {
	// region MAGIC METHODS

	/**
	 * WordPress Handler constructor.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   WordPressAdapter    $adapter            Instance of the adapter to the WordPress Settings API.
	 * @param   LoggingService      $logging_service    Instance of the logging service.
	 */
	public function __construct( WordPressAdapter $adapter, LoggingService $logging_service ) { // phpcs:ignore
		parent::__construct( $adapter, $logging_service );
	}

	// endregion

	// region INHERITED METHODS

	/**
	 * Returns the hook on which the WordPress Settings API is ready to be used.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 * @param   string  $context    The action being executed.
	 *
	 * @return  string
	 */
	public function get_action_hook( string $context ): string {
		return 'init';
	}

	/**
	 * Gets the instance of the settings framework adapter. Overwriting this method has no value other than helping
	 * with auto-complete in IDEs.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  WordPressAdapter
	 */
	public function get_adapter(): WordPressAdapter { // phpcs:ignore
		/* @noinspection PhpIncompatibleReturnTypeInspection */
		return parent::get_adapter();
	}

	// endregion
}
