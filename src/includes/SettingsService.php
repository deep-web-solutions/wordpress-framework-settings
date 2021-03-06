<?php

namespace DeepWebSolutions\Framework\Settings;

use DeepWebSolutions\Framework\Foundations\Plugin\PluginAwareInterface;
use DeepWebSolutions\Framework\Foundations\Plugin\PluginAwareTrait;
use DeepWebSolutions\Framework\Foundations\Plugin\PluginInterface;
use DeepWebSolutions\Framework\Settings\Actions\SettingsActionResponse;
use DeepWebSolutions\Framework\Settings\Adapters\NOOPAdapter;
use DeepWebSolutions\Framework\Settings\Adapters\WordPressAdapter;
use DeepWebSolutions\Framework\Settings\Handlers\NOOPHandler;
use DeepWebSolutions\Framework\Settings\Handlers\WordPressHandler;
use DeepWebSolutions\Framework\Utilities\DependencyInjection\ContainerAwareInterface;
use DeepWebSolutions\Framework\Utilities\Logging\LoggingService;
use DeepWebSolutions\Framework\Utilities\Logging\LoggingServiceAwareInterface;
use DeepWebSolutions\Framework\Utilities\Logging\LoggingServiceAwareTrait;

defined( 'ABSPATH' ) || exit;

/**
 * Performs actions against various Settings APIs.
 *
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Settings
 */
class SettingsService implements LoggingServiceAwareInterface, PluginAwareInterface {
	// region TRAITS

	use LoggingServiceAwareTrait;
	use PluginAwareTrait;

	// endregion

	// region FIELDS AND CONSTANTS

	/**
	 * Settings handlers to perform actions with.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @var     SettingsHandlerInterface[]
	 */
	protected array $handlers;

	// endregion

	// region MAGIC METHODS

	/**
	 * SettingsService constructor.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   PluginInterface     $plugin             Instance of the plugin.
	 * @param   LoggingService      $logging_service    Instance of the logging service.
	 * @param   array               $handlers           Settings handlers to perform actions with.
	 */
	public function __construct( PluginInterface $plugin, LoggingService $logging_service, array $handlers = array() ) {
		$this->set_plugin( $plugin );
		$this->set_logging_service( $logging_service );

		$this->set_default_handlers( $handlers );
	}

	// endregion

	// region GETTERS

	/**
	 * Returns the list of handlers registered.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  SettingsHandlerInterface[]
	 */
	public function get_handlers(): array {
		return $this->handlers;
	}

	// endregion

	// region SETTERS

	/**
	 * Sets the list of handlers.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   array   $handlers   Collection of handlers.
	 *
	 * @return  $this
	 */
	public function set_handlers( array $handlers ): SettingsService {
		$this->handlers = array();

		foreach ( $handlers as $handler ) {
			if ( $handler instanceof SettingsHandlerInterface ) {
				$this->register_handler( $handler );
			}
		}

		return $this;
	}

	// endregion

	// region METHODS

	/**
	 * Adds a handler to the list.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   SettingsHandlerInterface        $handler    Handler to add.
	 *
	 * @return  $this
	 */
	public function register_handler( SettingsHandlerInterface $handler ): SettingsService {
		if ( $handler instanceof PluginAwareInterface ) {
			$handler->set_plugin( $this->get_plugin() );
		}

		$this->handlers[ $handler->get_name() ] = $handler;
		return $this;
	}

	/**
	 * Returns the handler for a specific settings framework.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $name   Name of the handler to return.
	 *
	 * @return  SettingsHandlerInterface|null
	 */
	public function get_handler( string $name ): ?SettingsHandlerInterface {
		return $this->handlers[ $name ] ?? null;
	}

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
	 * @return  SettingsActionResponse
	 */
	public function register_menu_page( string $handler, string $page_title, string $menu_title, string $menu_slug, string $capability, array $params ): SettingsActionResponse {
		return $this->get_handler( $handler )->register_menu_page( $page_title, $menu_title, $menu_slug, $capability, $params );
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
	 * @return  SettingsActionResponse
	 */
	public function register_submenu_page( string $handler, string $parent_slug, string $page_title, string $menu_title, string $menu_slug, string $capability, array $params ): SettingsActionResponse {
		return $this->get_handler( $handler )->register_submenu_page( $parent_slug, $page_title, $menu_title, $menu_slug, $capability, $params );
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
	 * @return  SettingsActionResponse
	 */
	public function register_options_group( string $handler, string $group_id, string $group_title, array $fields, string $page, array $params ): SettingsActionResponse {
		return $this->get_handler( $handler )->register_options_group( $group_id, $group_title, $fields, $page, $params );
	}

	/**
	 * Registers a group of settings using the API of the given adapter.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $handler        The name of the settings framework handler to use.
	 * @param   string  $group_id       The ID of the settings group.
	 * @param   string  $group_title    The title of the settings group.
	 * @param   array   $fields         The fields to be registered with the group.
	 * @param   array   $params         Other parameters required for the adapter to work.
	 *
	 * @return  SettingsActionResponse
	 */
	public function register_generic_group( string $handler, string $group_id, string $group_title, array $fields, array $params ): SettingsActionResponse {
		return $this->get_handler( $handler )->register_generic_group( $group_id, $group_title, $fields, $params );
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
	 * @return  SettingsActionResponse
	 */
	public function register_field( string $handler, string $group_id, string $field_id, string $field_title, string $field_type, array $params ): SettingsActionResponse {
		return $this->get_handler( $handler )->register_field( $group_id, $field_id, $field_title, $field_type, $params );
	}

	/**
	 * Reads a setting's value from the database using the API of the given adapter.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $handler        The name of the settings framework handler to use.
	 * @param   string  $field_id       The ID of the field within the settings to read from the database.
	 * @param   string  $settings_id    The ID of the settings group to read from the database.
	 * @param   array   $params         Other parameters required for the adapter to work.
	 *
	 * @return  SettingsActionResponse
	 */
	public function get_option_value( string $handler, string $field_id, string $settings_id, array $params ): SettingsActionResponse {
		return $this->get_handler( $handler )->get_option_value( $field_id, $settings_id, $params );
	}

	/**
	 * Reads a field's value from the database using the API of the given adapter.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $handler        The name of the settings framework handler to use.
	 * @param   string  $field_id       The ID of the field to read from the database.
	 * @param   mixed   $object_id      The ID of the object the data is for.
	 * @param   array   $params         Other parameters required for the adapter to work.
	 *
	 * @return  SettingsActionResponse
	 */
	public function get_field_value( string $handler, string $field_id, $object_id, array $params ): SettingsActionResponse {
		return $this->get_handler( $handler )->get_field_value( $field_id, $object_id, $params );
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
	 * @return  SettingsActionResponse
	 */
	public function update_option_value( string $handler, string $field_id, $value, string $settings_id, array $params ): SettingsActionResponse {
		return $this->get_handler( $handler )->update_option_value( $field_id, $value, $settings_id, $params );
	}

	/**
	 * Updates a field's value using the API of the given adapter.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $handler        The name of the settings framework handler to use.
	 * @param   string  $field_id       The ID of the field to update.
	 * @param   mixed   $value          The new value of the setting.
	 * @param   mixed   $object_id      The ID of the object the update is for.
	 * @param   array   $params         Other parameters required for the adapter to work.
	 *
	 * @return  SettingsActionResponse
	 */
	public function update_field_value( string $handler, string $field_id, $value, $object_id, array $params ): SettingsActionResponse {
		return $this->get_handler( $handler )->update_field_value( $field_id, $value, $object_id, $params );
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
	 * @return  SettingsActionResponse
	 */
	public function delete_option( string $handler, string $field_id, string $settings_id, array $params ): SettingsActionResponse {
		return $this->get_handler( $handler )->delete_option( $field_id, $settings_id, $params );
	}

	/**
	 * Deletes a field's value from the database using the API of the given adapter.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $handler        The name of the settings framework handler to use.
	 * @param   string  $field_id   The ID of the field to delete from the database.
	 * @param   mixed   $object_id  The ID of the object the deletion is for.
	 * @param   array   $params     Other parameters required for the adapter to work.
	 *
	 * @return  SettingsActionResponse
	 */
	public function delete_field( string $handler, string $field_id, $object_id, array $params ): SettingsActionResponse {
		return $this->get_handler( $handler )->delete_field( $field_id, $object_id, $params );
	}

	// endregion

	// region HELPERS

	/**
	 * Register the handlers passed on in the constructor together with the default handlers.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   array   $handlers   Handlers passed on in the constructor.
	 */
	protected function set_default_handlers( array $handlers ) {
		$plugin = $this->get_plugin();
		if ( $plugin instanceof ContainerAwareInterface ) {
			$container = $plugin->get_container();
			$handlers += array( $container->get( WordPressHandler::class ) );
		} else {
			$handlers += array( new WordPressHandler( new WordPressAdapter() ) );
		}

		$this->set_handlers( $handlers );
	}

	// endregion
}
