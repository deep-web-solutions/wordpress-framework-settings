<?php

namespace DeepWebSolutions\Framework\Settings;

\defined( 'ABSPATH' ) || exit;

/**
 * Basic implementation of the settings-service-aware interface.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Settings
 */
trait SettingsServiceAwareTrait {
	// region FIELDS AND CONSTANTS

	/**
	 * Setting service for working with options pages and custom fields.
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
	 * Gets the current settings service instance set on the object.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  SettingsService
	 */
	public function get_settings_service(): SettingsService {
		return $this->settings_service;
	}

	// endregion

	// region SETTERS

	/**
	 * Sets a settings service instance on the object.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   SettingsService     $service        Settings service instance to use from now on.
	 */
	public function set_settings_service( SettingsService $service ) {
		$this->settings_service = $service;
	}

	// endregion
}
