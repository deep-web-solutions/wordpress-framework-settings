<?php

namespace DeepWebSolutions\Framework\Settings\Handlers;

use DeepWebSolutions\Framework\Settings\Adapters\ACFAdapter;

defined( 'ABSPATH' ) || exit;

/**
 * Handles the interoperability layer between the DWS framework and the ACF settings framework.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Settings\Handlers
 */
class ACFHandler extends AbstractHandler {
	// region MAGIC METHODS

	/**
	 * ACF Handler constructor.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   ACFAdapter      $adapter    Instance of the adapter to the ACF settings framework.
	 */
	public function __construct( ACFAdapter $adapter ) { // phpcs:ignore
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
		return 'acf';
	}

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

	// endregion
}
