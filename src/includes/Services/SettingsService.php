<?php

namespace DeepWebSolutions\Framework\Settings\Services;

use DeepWebSolutions\Framework\Settings\Factories\HandlerFactory;
use DeepWebSolutions\Framework\Settings\Factories\Traits\Handler;
use GuzzleHttp\Promise\PromiseInterface;

defined( 'ABSPATH' ) || exit;

/**
 * Performs actions against various Settings APIs.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\Framework\Settings\Services
 */
class SettingsService {
	use Handler;

	// region MAGIC METHODS

	/**
	 * SettingsService constructor.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   HandlerFactory  $handler_factory    Instance of the settings handler factory.
	 */
	public function __construct( HandlerFactory $handler_factory ) {
		$this->set_settings_handler_factory( $handler_factory );
	}

	// endregion

	// region METHODS

	/**
	 * Registers a new WordPress admin page using the API of the given adapter.
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
	 * @return  PromiseInterface
	 */
	public function register_menu_page( string $handler, string $page_title, string $menu_title, string $menu_slug, string $capability, array $params ): PromiseInterface {
		$handler = $this->get_settings_handler_factory()->get_handler( $handler );
		return $handler->register_menu_page( $page_title, $menu_title, $menu_slug, $capability, $params );
	}

	/**
	 * Registers a new WordPress child admin page using the API of the given adapter.
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
	 * @return  PromiseInterface
	 */
	public function register_submenu_page( string $handler, string $parent_slug, string $page_title, string $menu_title, string $menu_slug, string $capability, array $params ): PromiseInterface {
		$handler = $this->get_settings_handler_factory()->get_handler( $handler );
		return $handler->register_submenu_page( $parent_slug, $page_title, $menu_title, $menu_slug, $capability, $params );
	}

	/**
	 * Registers a group of settings to be outputted on an admin-side settings page using the API of the given adapter.
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
	 * @return  PromiseInterface
	 */
	public function register_settings_group( string $handler, string $group_id, string $group_title, array $fields, string $page, array $params ): PromiseInterface {
		$handler = $this->get_settings_handler_factory()->get_handler( $handler );
		return $handler->register_settings_group( $group_id, $group_title, $fields, $page, $params );
	}

	/**
	 * Registers a custom field dynamically at a later point than the parent group's creation using the API of the given adapter.
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
	 * @return  PromiseInterface
	 */
	public function register_field( string $handler, string $group_id, string $field_id, string $field_title, string $field_type, array $params ): PromiseInterface {
		$handler = $this->get_settings_handler_factory()->get_handler( $handler );
		return $handler->register_field( $group_id, $field_id, $field_title, $field_type, $params );
	}

	/**
	 * Reads a setting's value from the database using the API of the given adapter.
	 *
	 * @since   1.0.0
	 * @ver     1.0.0
	 *
	 * @param   string  $handler        The name of the settings framework handler to use.
	 * @param   string  $field_id       The ID of the field within the settings to read from the database.
	 * @param   string  $settings_id    The ID of the settings group to read from the database.
	 * @param   array   $params         Other parameters required for the adapter to work.
	 *
	 * @return  PromiseInterface
	 */
	public function get_setting_value( string $handler, string $field_id, string $settings_id, array $params ): PromiseInterface {
		$handler = $this->get_settings_handler_factory()->get_handler( $handler );
		return $handler->get_setting_value( $field_id, $settings_id, $params );
	}

	/**
	 * Updates a setting's value using the API of the given adapter.
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
	 * @return  PromiseInterface
	 */
	public function update_settings_value( string $handler, string $field_id, $value, string $settings_id, array $params ): PromiseInterface {
		$handler = $this->get_settings_handler_factory()->get_handler( $handler );
		return $handler->update_settings_value( $field_id, $value, $settings_id, $params );
	}

	/**
	 * Deletes a setting from the database using the API of the given adapter.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $handler        The name of the settings framework handler to use.
	 * @param   string  $field_id       The ID of the settings field to remove from the database. Empty string to delete the whole group.
	 * @param   string  $settings_id    The ID of the settings group to delete the field from.
	 * @param   array   $params         Other parameters required for the adapter to work.
	 *
	 * @return  PromiseInterface
	 */
	public function delete_setting( string $handler, string $field_id, string $settings_id, array $params ): PromiseInterface {
		$handler = $this->get_settings_handler_factory()->get_handler( $handler );
		return $handler->delete_setting( $field_id, $settings_id, $params );
	}

	// endregion
}
