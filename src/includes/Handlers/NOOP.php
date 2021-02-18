<?php

namespace DeepWebSolutions\Framework\Settings\Handlers;

use DeepWebSolutions\Framework\Settings\Abstracts\Handler;
use DeepWebSolutions\Framework\Settings\Adapters\NOOP as NOOPAdapter;
use DeepWebSolutions\Framework\Utilities\Services\LoggingService;

defined( 'ABSPATH' ) || exit;

/**
 * Does absolutely nothing.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\Framework\Settings\Handlers
 */
class NOOP extends Handler {
	// region MAGIC METHODS

	/**
	 * NOOP Handler constructor.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   NOOPAdapter     $adapter            Instance of the adapter to the NOOP settings framework.
	 * @param   LoggingService  $logging_service    Instance of the logging service.
	 */
	public function __construct( NOOPAdapter $adapter, LoggingService $logging_service ) { // phpcs:ignore
		parent::__construct( $adapter, $logging_service );
	}

	// endregion

	// region INHERITED METHODS

	/**
	 * Returns the hook on which the NOOP framework is ready to be used.
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
	 * @return  NOOPAdapter
	 */
	public function get_adapter(): NOOPAdapter { // phpcs:ignore
		/* @noinspection PhpIncompatibleReturnTypeInspection */
		return parent::get_adapter();
	}

	// endregion
}
