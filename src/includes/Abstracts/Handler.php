<?php

namespace DeepWebSolutions\Framework\Settings\Abstracts;

use DeepWebSolutions\Framework\Helpers\WordPress\Hooks;
use DeepWebSolutions\Framework\Settings\Interfaces\Actions\Adapterable;
use DeepWebSolutions\Framework\Settings\Utilities\ActionResponse;
use DeepWebSolutions\Framework\Utilities\Services\LoggingService;
use DeepWebSolutions\Framework\Utilities\Services\Traits\Logging;
use GuzzleHttp\Promise\Promise;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Promise\Utils;

defined( 'ABSPATH' ) || exit;

/**
 * Wrapper for the setting frameworks adapters. Since the settings frameworks have individual hooks that they are active
 * on, all calls are wrapped in a Promise -- that way, one can register calls that depend on the value without fear
 * of them not having returned anything useful. Also, different frameworks handle update/delete operations differently and
 * a handler should also try to mitigate that as much as possible.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Settings\Abstracts
 */
abstract class Handler implements Adapterable {
	use Logging;

	// region FIELDS AND CONSTANTS

	/**
	 * Instance of the adapter to the settings framework.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @var     Adapterable
	 */
	protected Adapterable $adapter;

	// endregion

	// region MAGIC METHODS

	/**
	 * Handler constructor.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   Adapterable     $adapter            Instance of a settings framework adapter.
	 * @param   LoggingService  $logging_service    Instance of the logging service.
	 */
	public function __construct( Adapterable $adapter, LoggingService $logging_service ) {
		$this->set_adapter( $adapter );
		$this->set_logging_service( $logging_service );
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
	 * @return  ActionResponse
	 */
	public function register_menu_page( string $page_title, string $menu_title, string $menu_slug, string $capability, array $params ): ActionResponse {
		return $this->create_action_response(
			'register_menu_page',
			array( $this->get_adapter(), 'register_menu_page' ),
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
	 * @return  ActionResponse
	 */
	public function register_submenu_page( string $parent_slug, string $page_title, string $menu_title, string $menu_slug, string $capability, array $params ): ActionResponse {
		return $this->create_action_response(
			'register_submenu_page',
			array( $this->get_adapter(), 'register_submenu_page' ),
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
	 * @return  ActionResponse
	 */
	public function register_settings_group( string $group_id, string $group_title, array $fields, string $page, array $params ): ActionResponse {
		return $this->create_action_response(
			'register_settings_group',
			array( $this->get_adapter(), 'register_settings_group' ),
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
	 * @return  ActionResponse
	 */
	public function register_generic_group( string $group_id, string $group_title, array $fields, array $params ): ActionResponse {
		return $this->create_action_response(
			'register_generic_group',
			array( $this->get_adapter(), 'register_generic_group' ),
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
	 * @return  ActionResponse
	 */
	public function register_field( string $group_id, string $field_id, string $field_title, string $field_type, array $params ): ActionResponse {
		return $this->create_action_response(
			'register_field',
			array( $this->get_adapter(), 'register_field' ),
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
	 * @return  ActionResponse
	 */
	public function get_setting_value( string $field_id, string $settings_id, array $params ): ActionResponse {
		return $this->create_action_response(
			'get_setting_value',
			array( $this->get_adapter(), 'get_setting_value' ),
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
	 * @return  ActionResponse
	 */
	public function get_field_value( string $field_id, $object_id, array $params = array() ): ActionResponse {
		return $this->create_action_response(
			'get_field_value',
			array( $this->get_adapter(), 'get_field_value' ),
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
	 * @return  ActionResponse
	 */
	public function update_settings_value( string $field_id, $value, string $settings_id, array $params ): ActionResponse {
		return $this->create_action_response(
			'update_settings_value',
			array( $this->get_adapter(), 'update_settings_value' ),
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
	 * @return  ActionResponse
	 */
	public function update_field_value( string $field_id, $value, $object_id, array $params ): ActionResponse {
		return $this->create_action_response(
			'update_field_value',
			array( $this->get_adapter(), 'update_field_value' ),
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
	 * @return  ActionResponse
	 */
	public function delete_setting( string $field_id, string $settings_id, array $params ): ActionResponse {
		return $this->create_action_response(
			'delete_field',
			array( $this->get_adapter(), 'delete_setting' ),
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
	 * @return  ActionResponse
	 */
	public function delete_field( string $field_id, $object_id, array $params ): ActionResponse {
		return $this->create_action_response(
			'delete_field',
			array( $this->get_adapter(), 'delete_field' ),
			array( $field_id, $object_id, $params )
		);
	}

	// endregion

	// region GETTERS

	/**
	 * Gets the instance of the settings framework adapter.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  Adapterable
	 */
	public function get_adapter(): Adapterable {
		return $this->adapter;
	}

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

	// region SETTERS

	/**
	 * Sets the settings framework adapter.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   Adapterable     $adapter    Instance of the settings adapter to use from now on.
	 */
	public function set_adapter( Adapterable $adapter ): void {
		$this->adapter = $adapter;
	}

	// endregion

	// region HELPERS

	/**
	 * Instantiates an ActionResponse object either with the value of the settings API's action result or with a promise
	 * to return the value after the API's initialization.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string      $context        The settings API action being performed.
	 * @param   callable    $return_value   The callable that generates the return value.
	 * @param   array       $args           Arguments to pass on to the callable.
	 *
	 * @return  ActionResponse
	 */
	protected function create_action_response( string $context, callable $return_value, array $args ): ActionResponse {
		$hook = $this->get_action_hook( $context );

		if ( did_action( $hook ) || doing_action( $hook ) ) {
			$return_value = call_user_func_array( $return_value, $args );
			return ( $return_value instanceof PromiseInterface )
				? new ActionResponse( null, $return_value )
				: new ActionResponse( $return_value, null );
		} else {
			$promise = new Promise();

			Hooks::enqueue_temp(
				$hook,
				function() use ( $promise, $return_value, $args ) {
					$promise->resolve( call_user_func_array( $return_value, $args ) );
					Utils::queue()->run(); // Run any callables immediately.
				}
			);

			return new ActionResponse( null, $promise );
		}
	}

	// endregion
}
