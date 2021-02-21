<?php

namespace DeepWebSolutions\Framework\Settings\Services\Traits;

use DeepWebSolutions\Framework\Settings\Services\SettingsService;
use DeepWebSolutions\Framework\Settings\Utilities\ActionResponse;

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

	/**
	 * Wrapper around the service's own method.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $handler        The name of the settings framework handler to use.
	 * @param   string  $page_title     The text to be displayed in the title tags of the page when the menu is selected.
	 * @param   string  $menu_title     The text to be used for the menu.
	 * @param   string  $menu_slug      The slug name to refer to this menu by. Should be unique for this menu page and only
	 *                                  include lowercase alphanumeric, dashes, and underscores characters to be compatible
	 *                                  with sanitize_key().
	 * @param   string  $capability     The capability required for this menu to be displayed to the user.
	 * @param   array   $params         Other params required for the adapter to work.
	 *
	 * @return  ActionResponse
	 */
	public function register_menu_page( string $handler, string $page_title, string $menu_title, string $menu_slug, string $capability, array $params ): ActionResponse {
		return $this->get_settings_service()->register_menu_page( $handler, $page_title, $menu_title, $menu_slug, $capability, $params );
	}

	/**
	 * Wrapper around the service's own method.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $handler        The name of the settings framework handler to use.
	 * @param   string  $parent_slug    The slug name for the parent menu (or the file name of a standard WordPress admin page).
	 * @param   string  $page_title     The text to be displayed in the title tags of the page when the menu is selected.
	 * @param   string  $menu_title     The text to be used for the menu.
	 * @param   string  $menu_slug      The slug name to refer to this menu by. Should be unique for this menu page and only
	 *                                  include lowercase alphanumeric, dashes, and underscores characters to be compatible
	 *                                  with sanitize_key().
	 * @param   string  $capability     The capability required for this menu to be displayed to the user.
	 * @param   array   $params         Other parameters required for the adapter to work.
	 *
	 * @return  ActionResponse
	 */
	public function register_submenu_page( string $handler, string $parent_slug, string $page_title, string $menu_title, string $menu_slug, string $capability, array $params ): ActionResponse {
		return $this->get_settings_service()->register_submenu_page( $handler, $parent_slug, $page_title, $menu_title, $menu_slug, $capability, $params );
	}

	/**
	 * Wrapper around the service's own method.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $handler        The name of the settings framework handler to use.
	 * @param   string  $group_id       The ID of the settings group.
	 * @param   string  $group_title    The title of the settings group.
	 * @param   array   $fields         The fields to be registered with the group.
	 * @param   string  $page           The settings page on which the group's fields should be displayed.
	 * @param   array   $params         Other parameters required for the adapter to work.
	 *
	 * @return  ActionResponse
	 */
	public function register_settings_group( string $handler, string $group_id, string $group_title, array $fields, string $page, array $params ): ActionResponse {
		return $this->get_settings_service()->register_settings_group( $handler, $group_id, $group_title, $fields, $page, $params );
	}

	/**
	 * Wrapper around the service's own method.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $handler        The name of the settings framework handler to use.
	 * @param   string  $group_id       The ID of the parent group that the dynamically added field belongs to.
	 * @param   string  $field_id       The ID of the newly registered field.
	 * @param   string  $field_title    The title of the newly registered field.
	 * @param   string  $field_type     The type of custom field being registered.
	 * @param   array   $params         Other parameters required for the adapter to work.
	 *
	 * @return  ActionResponse
	 */
	public function register_field( string $handler, string $group_id, string $field_id, string $field_title, string $field_type, array $params ): ActionResponse {
		return $this->get_settings_service()->register_field( $handler, $group_id, $field_id, $field_title, $field_type, $params );
	}

	/**
	 * Wrapper around the service's own method.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $handler        The name of the settings framework handler to use.
	 * @param   string  $field_id       The ID of the field within the settings to read from the database.
	 * @param   string  $settings_id    The ID of the settings group to read from the database.
	 * @param   array   $params         Other parameters required for the adapter to work.
	 *
	 * @return  ActionResponse
	 */
	public function get_setting_value( string $handler, string $field_id, string $settings_id, array $params ): ActionResponse {
		return $this->get_settings_service()->get_setting_value( $handler, $field_id, $settings_id, $params );
	}

	/**
	 * Wrapper around the service's own method.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $handler        The name of the settings framework handler to use.
	 * @param   string  $field_id       The ID of the field within the settings to update.
	 * @param   mixed   $value          The new value of the setting.
	 * @param   string  $settings_id    The ID of the settings group to update.
	 * @param   array   $params         Other parameters required for the adapter to work.
	 *
	 * @return  ActionResponse
	 */
	public function update_settings_value( string $handler, string $field_id, $value, string $settings_id, array $params ): ActionResponse {
		return $this->get_settings_service()->update_settings_value( $handler, $field_id, $value, $settings_id, $params );
	}

	/**
	 * Wrapper around the service's own method.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $handler        The name of the settings framework handler to use.
	 * @param   string  $field_id       The ID of the settings field to remove from the database. Empty string to delete the whole group.
	 * @param   string  $settings_id    The ID of the settings group to delete the field from.
	 * @param   array   $params         Other parameters required for the adapter to work.
	 *
	 * @return  ActionResponse
	 */
	public function delete_setting( string $handler, string $field_id, string $settings_id, array $params ): ActionResponse {
		return $this->get_settings_service()->delete_setting( $handler, $field_id, $settings_id, $params );
	}

	// endregion
}
