<?php

namespace DeepWebSolutions\Framework\Settings\Services\Traits;

use DeepWebSolutions\Framework\Settings\Services\SettingsService;

defined( 'ABSPATH' ) || exit;

/**
 * Trait for working with the settings service.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Settings\Services\Traits
 */
trait Settings {
	// region FIELDS AND CONSTANTS

	/**
	 * Setting service for handling settings pages and custom fields.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @access  protected
	 * @var     SettingsService
	 */
	protected SettingsService $settings_service;

	// endregion

	// region GETTERS

	/**
	 * Gets the settings service instance.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  SettingsService
	 */
	protected function get_settings_service(): SettingsService {
		return $this->settings_service;
	}

	// endregion

	// region SETTERS

	/**
	 * Sets the settings service instance.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   SettingsService     $settings_service       The settings service instance to use from now on.
	 */
	public function set_settings_service( SettingsService $settings_service ): void {
		$this->settings_service = $settings_service;
	}

	// endregion

	// region METHODS

	/**
	 * Using classes should define their settings in here.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   SettingsService     $settings_service     Instance of the settings service.
	 */
	abstract protected function register_settings( SettingsService $settings_service ): void;

	// endregion
}
