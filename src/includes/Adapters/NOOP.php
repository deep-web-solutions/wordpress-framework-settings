<?php

namespace DeepWebSolutions\Framework\Settings\Adapters;

use DeepWebSolutions\Framework\Settings\Interfaces\Actions\Adapterable;

defined( 'ABSPATH' ) || exit;

/**
 * Performs no operations whatsoever.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.de>
 * @package DeepWebSolutions\Framework\Settings\Adapters
 *
 * @see     Adapterable
 */
class NOOP implements Adapterable {
	// region CREATE

	/**
	 * Noop implementation.
	 *
	 * @param   string  $page_title     The text to be displayed in the title tags of the page when the menu is selected.
	 * @param   string  $menu_title     The text to be used for the menu.
	 * @param   string  $menu_slug      The slug name to refer to this menu by. Should be unique for this menu page and only
	 *                                  include lowercase alphanumeric, dashes, and underscores characters to be compatible
	 *                                  with sanitize_key().
	 * @param   string  $capability     The capability required for this menu to be displayed to the user.
	 * @param   array   $params         Other params required for the adapter to work.
	 *
	 * @return  null
	 */
	public function register_menu_page( string $page_title = '', string $menu_title = '', string $menu_slug = '', string $capability = '', array $params = array() ) {
		return null;
	}

	/**
	 * Noop implementation.
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
	 * @param   array   $params         Other params required for the adapter to work.
	 *
	 * @return  null
	 */
	public function register_submenu_page( string $parent_slug = '', string $page_title = '', string $menu_title = '', string $menu_slug = '', string $capability = '', array $params = array() ) {
		return null;
	}

	/**
	 * Noop implementation.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $group_id       The ID of the settings group.
	 * @param   string  $group_title    The title of the settings group.
	 * @param   array   $fields         The fields to be registered with the group.
	 * @param   string  $page           The settings page on which the group's fields should be displayed.
	 * @param   array   $params         Other parameters required for the adapter to work.
	 *
	 * @return  null
	 */
	public function register_settings_group( string $group_id = '', string $group_title = '', array $fields = array(), string $page = '', array $params = array() ) {
		return null;
	}

	/**
	 * Noop implementation.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $group_id       The ID of the settings group.
	 * @param   string  $group_title    The title of the settings group.
	 * @param   array   $fields         The fields to be registered with the group.
	 * @param   array   $params         Other parameters required for the adapter to work.
	 *
	 * @return  null
	 */
	public function register_generic_group( string $group_id = '', string $group_title = '', array $fields = array(), array $params = array() ) {
		return null;
	}

	/**
	 * Noop implementation.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $group_id       The ID of the parent group that the dynamically added field belongs to.
	 * @param   string  $field_id       The ID of the newly registered field.
	 * @param   string  $field_title    The title of the newly registered field.
	 * @param   string  $field_type     The type of custom field being registered.
	 * @param   array   $params         Other parameters required for the adapter to work.
	 *
	 * @return  null
	 */
	public function register_field( string $group_id = '', string $field_id = '', string $field_title = '', string $field_type = '', array $params = array() ) {
		return null;
	}

	// endregion

	// region READ

	/**
	 * Noop implementation.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string          $field_id       The ID of the field within the settings to read from the database.
	 * @param   string|null     $settings_id    NOT USED BY THE ACF ADAPTER.
	 * @param   array           $params         Other parameters required for the adapter to work.
	 *
	 * @return  null
	 */
	public function get_setting_value( string $field_id = '', string $settings_id = '', array $params = array() ) {
		return $this->get_field_value( $field_id, 'options', $params );
	}

	/**
	 * Noop implementation.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string              $field_id       The ID of the field to read from the database.
	 * @param   false|string|int    $object_id      The ID of the object the data is for.
	 * @param   array               $params         Other parameters required for the adapter to work.
	 *
	 * @return  null
	 */
	public function get_field_value( string $field_id = '', $object_id = '', array $params = array() ) {
		return null;
	}

	// endregion

	// region UPDATE

	/**
	 * Noop implementation.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string          $field_id       The ID of the field within the settings to update.
	 * @param   mixed           $value          The new value of the setting.
	 * @param   string|null     $settings_id    NOT USED BY THE ACF ADAPTER.
	 * @param   array           $params         Other parameters required for the adapter to work.
	 *
	 * @return  null
	 */
	public function update_settings_value( string $field_id = '', $value = '', string $settings_id = '', array $params = array() ) {
		return null;
	}

	/**
	 * Noop implementation.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string              $field_id       The ID of the field to update.
	 * @param   mixed               $value          The new value of the setting.
	 * @param   false|string|int    $object_id      The ID of the object the update is for.
	 * @param   array               $params         Other parameters required for the adapter to work.
	 *
	 * @return  null
	 */
	public function update_field_value( string $field_id = '', $value = '', $object_id = '', array $params = array() ) {
		return null;
	}

	// endregion

	// region DELETE

	/**
	 * Noop implementation.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string          $field_id       The ID of the settings field to remove from the database.
	 * @param   string|null     $settings_id    NOT USED BY THE ACF ADAPTER.
	 * @param   array           $params         Other parameters required for the adapter to work.
	 *
	 * @return  null
	 */
	public function delete_setting( string $field_id = '', string $settings_id = '', array $params = array() ) {
		return null;
	}

	/**
	 * Noop implementation.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string              $field_id       The ID of the field to delete from the database.
	 * @param   false|string|int    $object_id      The ID of the object the deletion is for.
	 * @param   array               $params         Other parameters required for the adapter to work.
	 *
	 * @return  null
	 */
	public function delete_field( string $field_id = '', $object_id = '', array $params = array() ) {
		return null;
	}

	// endregion
}
