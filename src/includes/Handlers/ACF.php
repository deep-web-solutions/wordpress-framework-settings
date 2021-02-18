<?php

namespace DeepWebSolutions\Framework\Settings\Handlers;

use DeepWebSolutions\Framework\Settings\Abstracts\Handler;
use DeepWebSolutions\Framework\Settings\Adapters\ACF as ACFAdapter;
use DeepWebSolutions\Framework\Utilities\Services\LoggingService;

defined( 'ABSPATH' ) || exit;

/**
 * Handles the interoperability layer between the DWS framework and the ACF settings framework.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\Framework\Settings\Handlers
 */
class ACF extends Handler {
	// region MAGIC METHODS

	/**
	 * ACF Handler constructor.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   ACFAdapter      $adapter            Instance of the adapter to the ACF settings framework.
	 * @param   LoggingService  $logging_service    Instance of the logging service.
	 */
	public function __construct( ACFAdapter $adapter, LoggingService $logging_service ) { // phpcs:ignore
		parent::__construct( $adapter, $logging_service );
	}

	// endregion

	// region INHERITED METHODS

	/**
	 * Returns the hook on which the ACF framework is ready to be used.
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
		return 'acf/include_fields';
	}

	/**
	 * Gets the instance of the settings framework adapter. Overwriting this method has no value other than helping
	 * with auto-complete in IDEs.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  ACFAdapter
	 */
	public function get_adapter(): ACFAdapter { // phpcs:ignore
		/* @noinspection PhpIncompatibleReturnTypeInspection */
		return parent::get_adapter();
	}

	// endregion
}
