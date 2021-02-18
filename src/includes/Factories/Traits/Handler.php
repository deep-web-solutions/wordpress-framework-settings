<?php

namespace DeepWebSolutions\Framework\Settings\Factories\Traits;

use DeepWebSolutions\Framework\Settings\Factories\HandlerFactory;

defined( 'ABSPATH' ) || exit;

/**
 * Trait for working with the adapter factory.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Settings\Factories\Traits
 */
trait Handler {
	// region FIELDS AND CONSTANTS

	/**
	 * Handler factory for retrieving settings handlers.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @access  protected
	 * @var     HandlerFactory
	 */
	protected HandlerFactory $settings_handler_factory;

	// endregion

	// region GETTERS

	/**
	 * Gets the settings handler factory instance.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  HandlerFactory
	 */
	public function get_settings_handler_factory(): HandlerFactory {
		return $this->settings_handler_factory;
	}

	// endregion

	// region SETTERS

	/**
	 * Sets the settings handler factory instance.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   HandlerFactory      $handler_factory    The settings handler factory to use from now on.
	 */
	public function set_settings_handler_factory( HandlerFactory $handler_factory ): void {
		$this->settings_handler_factory = $handler_factory;
	}

	// endregion
}
