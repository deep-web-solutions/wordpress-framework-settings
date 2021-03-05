<?php

namespace DeepWebSolutions\Framework\Settings\Handlers;

use DeepWebSolutions\Framework\Settings\Adapters\WordPressAdapter;

defined( 'ABSPATH' ) || exit;

/**
 * Handles the interoperability layer between the DWS framework and the WordPress Settings API.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Settings\Handlers
 */
class WordPressHandler extends AbstractHandler {
	// region MAGIC METHODS

	/**
	 * WordPress Handler constructor.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   WordPressAdapter    $adapter    Instance of the adapter to the WordPress Settings API.
	 */
	public function __construct( WordPressAdapter $adapter ) { // phpcs:ignore
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
		return 'wordpress'; // phpcs:ignore
	}

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

	// endregion
}
