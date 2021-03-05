<?php

namespace DeepWebSolutions\Framework\Settings\Handlers;

use DeepWebSolutions\Framework\Helpers\WordPress\Hooks;
use DeepWebSolutions\Framework\Settings\SettingsAdapterInterface;
use DeepWebSolutions\Framework\Settings\SettingsHandlerInterface;
use DeepWebSolutions\Framework\Settings\Utilities\SettingsActionResponse;
use GuzzleHttp\Promise\Promise;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Promise\Utils;

defined( 'ABSPATH' ) || exit;

/**
 * Handles performing actions against a settings framework's API.
 *
 * Since the settings frameworks have individual hooks that they are active on, all calls are wrapped in a Promise.
 * That way, one can register calls that depend on the return value at any point in the plugin's lifecycle without fear
 * of the action not having returned anything useful or throwing an error.
 *
 * Moreover, different frameworks handle update/delete operations differently and a handler should also try to mitigate
 * that as much as possible.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Settings\Handlers
 */
abstract class AbstractHandler implements SettingsHandlerInterface {
	// region FIELDS AND CONSTANTS

	/**
	 * Settings adapter to use for performing the actions.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @var     SettingsAdapterInterface
	 */
	protected SettingsAdapterInterface $adapter;

	// endregion

	// region MAGIC METHODS

	/**
	 * AbstractHandler constructor.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   SettingsAdapterInterface    $adapter    Settings adapter to use.
	 */
	public function __construct( SettingsAdapterInterface $adapter ) {
		$this->adapter = $adapter;
	}

	// endregion

	// region METHODS

	/**
	 * Registers a new WordPress admin page.
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
	 *
	 * @return  SettingsActionResponse
	 */
	public function register_menu_page( string $page_title, string $menu_title, string $menu_slug, string $capability, array $params ): SettingsActionResponse {
		return $this->create_action_response(
			'register_menu_page',
			array( $this->adapter, 'register_menu_page' ),
			array( $page_title, $menu_title, $menu_slug, $capability, $params )
		);
	}

	/**
	 * Registers a new WordPress child admin page.
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
	 *
	 * @return  SettingsActionResponse
	 */
	public function register_submenu_page( string $parent_slug, string $page_title, string $menu_title, string $menu_slug, string $capability, array $params ): SettingsActionResponse {
		return $this->create_action_response(
			'register_submenu_page',
			array( $this->adapter, 'register_submenu_page' ),
			array( $parent_slug, $page_title, $menu_title, $menu_slug, $capability, $params )
		);
	}

	/**
	 * Registers a group of settings to be outputted on an admin-side settings page.
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
	 * @return  SettingsActionResponse
	 */
	public function register_options_group( string $group_id, string $group_title, array $fields, string $page, array $params ): SettingsActionResponse {
		return $this->create_action_response(
			'register_settings_group',
			array( $this->adapter, 'register_options_group' ),
			array( $group_id, $group_title, $fields, $page, $params )
		);
	}

	/**
	 * Registers a group of settings.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $group_id       The ID of the settings group.
	 * @param   string  $group_title    The title of the settings group.
	 * @param   array   $fields         The fields to be registered with the group.
	 * @param   array   $params         Other parameters required for the adapter to work.
	 *
	 * @return  SettingsActionResponse
	 */
	public function register_generic_group( string $group_id, string $group_title, array $fields, array $params ): SettingsActionResponse {
		return $this->create_action_response(
			'register_generic_group',
			array( $this->adapter, 'register_generic_group' ),
			array( $group_id, $group_title, $fields, $params )
		);
	}

	/**
	 * Registers a custom field dynamically at a later point than the parent group's creation.
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
	 * @return  SettingsActionResponse
	 */
	public function register_field( string $group_id, string $field_id, string $field_title, string $field_type, array $params ): SettingsActionResponse {
		return $this->create_action_response(
			'register_field',
			array( $this->adapter, 'register_field' ),
			array( $group_id, $field_id, $field_title, $field_type, $params )
		);
	}

	/**
	 * Reads a setting's value from the database.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $field_id       The ID of the field within the settings to read from the database.
	 * @param   string  $settings_id    The ID of the settings group to read from the database.
	 * @param   array   $params         Other parameters required for the adapter to work.
	 *
	 * @return  SettingsActionResponse
	 */
	public function get_option_value( string $field_id, string $settings_id, array $params ): SettingsActionResponse {
		return $this->create_action_response(
			'get_setting_value',
			array( $this->adapter, 'get_option_value' ),
			array( $field_id, $settings_id, $params )
		);
	}

	/**
	 * Reads a field's value from the database.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $field_id       The ID of the field to read from the database.
	 * @param   mixed   $object_id      The ID of the object the data is for.
	 * @param   array   $params         Other parameters required for the adapter to work.
	 *
	 * @return  SettingsActionResponse
	 */
	public function get_field_value( string $field_id, $object_id, array $params = array() ): SettingsActionResponse {
		return $this->create_action_response(
			'get_field_value',
			array( $this->adapter, 'get_field_value' ),
			array( $field_id, $object_id, $params )
		);
	}

	/**
	 * Updates a setting's value.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $field_id       The ID of the field within the settings to update.
	 * @param   mixed   $value          The new value of the setting.
	 * @param   string  $settings_id    The ID of the settings group to update.
	 * @param   array   $params         Other parameters required for the adapter to work.
	 *
	 * @return  SettingsActionResponse
	 */
	public function update_option_value( string $field_id, $value, string $settings_id, array $params ): SettingsActionResponse {
		return $this->create_action_response(
			'update_settings_value',
			array( $this->adapter, 'update_option_value' ),
			array( $field_id, $value, $settings_id, $params )
		);
	}

	/**
	 * Updates a field's value.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $field_id       The ID of the field to update.
	 * @param   mixed   $value          The new value of the setting.
	 * @param   mixed   $object_id      The ID of the object the update is for.
	 * @param   array   $params         Other parameters required for the adapter to work.
	 *
	 * @return  SettingsActionResponse
	 */
	public function update_field_value( string $field_id, $value, $object_id, array $params ): SettingsActionResponse {
		return $this->create_action_response(
			'update_field_value',
			array( $this->adapter, 'update_field_value' ),
			array( $field_id, $value, $object_id, $params )
		);
	}

	/**
	 * Deletes a setting from the database.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string      $field_id       The ID of the settings field to remove from the database. Empty string to delete the whole group.
	 * @param   string      $settings_id    The ID of the settings group to delete the field from.
	 * @param   array       $params         Other parameters required for the adapter to work.
	 *
	 * @return  SettingsActionResponse
	 */
	public function delete_option( string $field_id, string $settings_id, array $params ): SettingsActionResponse {
		return $this->create_action_response(
			'delete_field',
			array( $this->adapter, 'delete_option' ),
			array( $field_id, $settings_id, $params )
		);
	}

	/**
	 * Deletes a field's value from the database.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $field_id   The ID of the field to delete from the database.
	 * @param   mixed   $object_id  The ID of the object the deletion is for.
	 * @param   array   $params     Other parameters required for the adapter to work.
	 *
	 * @return  SettingsActionResponse
	 */
	public function delete_field( string $field_id, $object_id, array $params ): SettingsActionResponse {
		return $this->create_action_response(
			'delete_field',
			array( $this->adapter, 'delete_field' ),
			array( $field_id, $object_id, $params )
		);
	}

	// endregion

	// region GETTERS

	/**
	 * Returns the hook on which the settings framework is ready to be used.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $context    Some framework might have different init hooks than update hooks, e.g.
	 *
	 * @return  string
	 */
	abstract public function get_action_hook( string $context ): string;

	// endregion

	// region HELPERS

	/**
	 * Instantiates a SettingsActionResponse object either with the value of the settings API's action result or with
	 * a promise to return the value after the API's initialization.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string      $context        The settings API action being performed.
	 * @param   callable    $return_value   The callable that generates the return value.
	 * @param   array       $args           Arguments to pass on to the callable.
	 *
	 * @return  SettingsActionResponse
	 */
	protected function create_action_response( string $context, callable $return_value, array $args ): SettingsActionResponse {
		$hook = $this->get_action_hook( $context );

		if ( did_action( $hook ) || doing_action( $hook ) ) {
			$return_value = call_user_func_array( $return_value, $args );
			return ( $return_value instanceof PromiseInterface )
				? new SettingsActionResponse( null, $return_value )
				: new SettingsActionResponse( $return_value, null );
		} else {
			$promise = new Promise();

			Hooks::enqueue_temp(
				$hook,
				function() use ( $promise, $return_value, $args ) {
					$promise->resolve( call_user_func_array( $return_value, $args ) );
					Utils::queue()->run(); // Run any callables immediately.
				}
			);

			return new SettingsActionResponse( null, $promise );
		}
	}

	// endregion
}
