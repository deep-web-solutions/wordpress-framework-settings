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

	// region METHODS

	/**
	 * Wrapper around the service's own method.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $page_title     The text to be displayed in the title tags of the page when the menu is selected.
	 * @param   string  $menu_title     The text to be used for the menu.
	 * @param   string  $menu_slug      The slug name to refer to this menu by. Should be unique for this menu page and only
	 *                                  include lowercase alphanumeric, dashes, and underscores characters to be compatible
	 *                                  with sanitize_key().
	 * @param   string  $capability     The capability required for this menu to be displayed to the user.
	 * @param   array   $params         Other params required for the adapter to work.
	 * @param   string  $handler_id     The ID of the settings framework handler to use.
	 *
	 * @return  mixed|null
	 */
	public function register_menu_page( string $page_title, string $menu_title, string $menu_slug, string $capability, array $params, string $handler_id = 'default' ) {
		return $this->get_settings_service()->register_menu_page( $page_title, $menu_title, $menu_slug, $capability, $params, $handler_id );
	}

	/**
	 * Wrapper around the service's own method.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $parent_slug    The slug name for the parent menu (or the file name of a standard WordPress admin page).
	 * @param   string  $page_title     The text to be displayed in the title tags of the page when the menu is selected.
	 * @param   string  $menu_title     The text to be used for the menu.
	 * @param   string  $menu_slug      The slug name to refer to this menu by. Should be unique for this menu page and only
	 *                                  include lowercase alphanumeric, dashes, and underscores characters to be compatible
	 *                                  with sanitize_key().
	 * @param   string  $capability     The capability required for this menu to be displayed to the user.
	 * @param   array   $params         Other parameters required for the adapter to work.
	 * @param   string  $handler_id     The ID of the settings framework handler to use.
	 *
	 * @return  mixed|null
	 */
	public function register_submenu_page( string $parent_slug, string $page_title, string $menu_title, string $menu_slug, string $capability, array $params, string $handler_id = 'default' ) {
		return $this->get_settings_service()->register_submenu_page( $parent_slug, $page_title, $menu_title, $menu_slug, $capability, $params, $handler_id );
	}

	/**
	 * Wrapper around the service's own method.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $group_id       The ID of the settings group.
	 * @param   string  $group_title    The title of the settings group.
	 * @param   array   $fields         The fields to be registered with the group.
	 * @param   string  $page           The settings page on which the group's fields should be displayed.
	 * @param   array   $params         Other parameters required for the adapter to work.
	 * @param   string  $handler_id     The ID of the settings framework handler to use.
	 *
	 * @return  mixed|null
	 */
	public function register_options_group( string $group_id, string $group_title, array $fields, string $page, array $params, string $handler_id = 'default' ) {
		return $this->get_settings_service()->register_options_group( $group_id, $group_title, $fields, $page, $params, $handler_id );
	}

	/**
	 * Wrapper around the service's own method.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $group_id       The ID of the settings group.
	 * @param   string  $group_title    The title of the settings group.
	 * @param   array   $fields         The fields to be registered with the group.
	 * @param   array   $params         Other parameters required for the adapter to work.
	 * @param   string  $handler_id     The ID of the settings framework handler to use.
	 *
	 * @return  mixed|null
	 */
	public function register_generic_group( string $group_id, string $group_title, array $fields, array $params, string $handler_id = 'default' ) {
		return $this->get_settings_service()->register_generic_group( $group_id, $group_title, $fields, $params, $handler_id );
	}

	/**
	 * Wrapper around the service's own method.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $group_id       The ID of the parent group that the dynamically added field belongs to.
	 * @param   string  $field_id       The ID of the newly registered field.
	 * @param   string  $field_title    The title of the newly registered field.
	 * @param   string  $field_type     The type of custom field being registered.
	 * @param   array   $params         Other parameters required for the adapter to work.
	 * @param   string  $handler_id     The ID of the settings framework handler to use.
	 *
	 * @return  mixed|null
	 */
	public function register_field( string $group_id, string $field_id, string $field_title, string $field_type, array $params, string $handler_id = 'default' ) {
		return $this->get_settings_service()->register_field( $group_id, $field_id, $field_title, $field_type, $params, $handler_id );
	}

	/**
	 * Wrapper around the service's own method.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $field_id       The ID of the field within the settings to read from the database.
	 * @param   string  $settings_id    The ID of the settings group to read from the database.
	 * @param   array   $params         Other parameters required for the adapter to work.
	 * @param   string  $handler_id     The ID of the settings framework handler to use.
	 *
	 * @return  mixed
	 */
	public function get_option_value( string $field_id, string $settings_id, array $params, string $handler_id = 'default' ) {
		return $this->get_settings_service()->get_option_value( $field_id, $settings_id, $params, $handler_id );
	}

	/**
	 * Wrapper around the service's own method.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $field_id       The ID of the field to read from the database.
	 * @param   mixed   $object_id      The ID of the object the data is for.
	 * @param   array   $params         Other parameters required for the adapter to work.
	 * @param   string  $handler_id     The ID of the settings framework handler to use.
	 *
	 * @return  mixed
	 */
	public function get_field_value( string $field_id, $object_id, array $params, string $handler_id = 'default' ) {
		return $this->get_settings_service()->get_field_value( $field_id, $object_id, $params, $handler_id );
	}

	/**
	 * Wrapper around the service's own method.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $field_id       The ID of the field within the settings to update.
	 * @param   mixed   $value          The new value of the setting.
	 * @param   string  $settings_id    The ID of the settings group to update.
	 * @param   array   $params         Other parameters required for the adapter to work.
	 * @param   string  $handler_id     The ID of the settings framework handler to use.
	 *
	 * @return  mixed
	 */
	public function update_option_value( string $field_id, $value, string $settings_id, array $params, string $handler_id = 'default' ) {
		return $this->get_settings_service()->update_option_value( $field_id, $value, $settings_id, $params, $handler_id );
	}

	/**
	 * Wrapper around the service's own method.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $field_id       The ID of the field to update.
	 * @param   mixed   $value          The new value of the setting.
	 * @param   mixed   $object_id      The ID of the object the update is for.
	 * @param   array   $params         Other parameters required for the adapter to work.
	 * @param   string  $handler_id     The ID of the settings framework handler to use.
	 *
	 * @return  mixed
	 */
	public function update_field_value( string $field_id, $value, $object_id, array $params, string $handler_id = 'default' ) {
		return $this->get_settings_service()->update_field_value( $field_id, $value, $object_id, $params, $handler_id );
	}

	/**
	 * Wrapper around the service's own method.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $field_id       The ID of the settings field to remove from the database. Empty string to delete the whole group.
	 * @param   string  $settings_id    The ID of the settings group to delete the field from.
	 * @param   array   $params         Other parameters required for the adapter to work.
	 * @param   string  $handler_id     The ID of the settings framework handler to use.
	 *
	 * @return  mixed
	 */
	public function delete_option( string $field_id, string $settings_id, array $params, string $handler_id = 'default' ) {
		return $this->get_settings_service()->delete_option( $field_id, $settings_id, $params, $handler_id );
	}

	/**
	 * Wrapper around the service's own method.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $field_id       The ID of the field to delete from the database.
	 * @param   mixed   $object_id      The ID of the object the deletion is for.
	 * @param   array   $params         Other parameters required for the adapter to work.
	 * @param   string  $handler_id     The ID of the settings framework handler to use.
	 *
	 * @return  mixed
	 */
	public function delete_field( string $field_id, $object_id, array $params, string $handler_id = 'default' ) {
		return $this->get_settings_service()->delete_field( $field_id, $object_id, $params, $handler_id );
	}

	// endregion
}
