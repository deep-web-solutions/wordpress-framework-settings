<?php

namespace DeepWebSolutions\Framework\Settings\Handlers;

use DeepWebSolutions\Framework\Settings\Adapters\NOOPAdapter;

defined( 'ABSPATH' ) || exit;

/**
 * Does absolutely nothing.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Settings\Handlers
 */
class NOOPHandler extends AbstractHandler {
	// region MAGIC METHODS

	/**
	 * NOOP Handler constructor.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   NOOPAdapter     $adapter    Instance of the adapter to the NOOP settings framework.
	 */
	public function __construct( NOOPAdapter $adapter ) { // phpcs:ignore
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
		return 'noop';
	}

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

	// endregion
}
