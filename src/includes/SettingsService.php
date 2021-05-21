<?php

namespace DeepWebSolutions\Framework\Settings;

use DeepWebSolutions\Framework\Foundations\Logging\LoggingService;
use DeepWebSolutions\Framework\Foundations\Plugin\PluginInterface;
use DeepWebSolutions\Framework\Foundations\Utilities\Handlers\HandlerInterface;
use DeepWebSolutions\Framework\Foundations\Utilities\Services\AbstractMultiHandlerService;
use DeepWebSolutions\Framework\Settings\Handlers\WordPress_Handler;
use DeepWebSolutions\Framework\Utilities\Hooks\HooksService;
use DeepWebSolutions\Framework\Utilities\Hooks\HooksServiceAwareInterface;
use DeepWebSolutions\Framework\Utilities\Hooks\HooksServiceAwareTrait;
use DeepWebSolutions\Framework\Utilities\Hooks\HooksServiceRegisterInterface;

\defined( 'ABSPATH' ) || exit;

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
class SettingsService extends AbstractMultiHandlerService implements HooksServiceAwareInterface {
	// region TRAITS

	use HooksServiceAwareTrait;

	// endregion

	// region MAGIC METHODS

	/**
	 * SettingsService constructor.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   PluginInterface                 $plugin             Instance of the plugin.
	 * @param   LoggingService                  $logging_service    Instance of the logging service.
	 * @param   HooksService                    $hooks_service      Instance of the hooks service.
	 * @param   SettingsHandlerInterface[]      $handlers           Settings handlers to perform actions with.
	 */
	public function __construct( PluginInterface $plugin, LoggingService $logging_service, HooksService $hooks_service, array $handlers = array() ) {
		$this->set_hooks_service( $hooks_service );
		parent::__construct( $plugin, $logging_service, $handlers );
	}

	// endregion

	// region INHERITED METHODS

	/**
	 * Returns the instance of a given handler.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $handler_id     The ID of the handler to retrieve.
	 *
	 * @return  SettingsHandlerInterface|null
	 */
	public function get_handler( string $handler_id ): ?SettingsHandlerInterface { // phpcs:ignore
		/* @noinspection PhpIncompatibleReturnTypeInspection */
		return parent::get_handler( $handler_id );
	}

	/**
	 * Registers a new handler with the service.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   HandlerInterface        $handler    The new handler to register with the service.
	 *
	 * @return  SettingsService
	 */
	public function register_handler( HandlerInterface $handler ): SettingsService {
		parent::register_handler( $handler );

		if ( $handler instanceof HooksServiceRegisterInterface ) {
			$handler->register_hooks( $this->get_hooks_service() );
		}

		return $this;
	}

	// endregion

	// region METHODS

	/**
	 * Registers a new WordPress admin page using the API of the given adapter.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string|callable     $page_title     The text to be displayed in the title tags of the page when the menu is selected.
	 * @param   string|callable     $menu_title     The text to be used for the menu.
	 * @param   string              $menu_slug      The slug name to refer to this menu by. Should be unique for this menu page and only
	 *                                              include lowercase alphanumeric, dashes, and underscores characters to be compatible
	 *                                              with sanitize_key().
	 * @param   string              $capability     The capability required for this menu to be displayed to the user.
	 * @param   array               $params         Other params required for the adapter to work.
	 * @param   string              $handler_id     The ID of the settings framework handler to use.
	 *
	 * @return  mixed|null
	 */
	public function register_menu_page( $page_title, $menu_title, string $menu_slug, string $capability, array $params = array(), string $handler_id = 'default' ) {
		return $this->get_handler( $handler_id )->register_menu_page( $page_title, $menu_title, $menu_slug, $capability, $params );
	}

	/**
	 * Registers a new WordPress child admin page using the API of the given adapter.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string              $parent_slug    The slug name for the parent menu (or the file name of a standard WordPress admin page).
	 * @param   string|callable     $page_title     The text to be displayed in the title tags of the page when the menu is selected.
	 * @param   string|callable     $menu_title     The text to be used for the menu.
	 * @param   string              $menu_slug      The slug name to refer to this menu by. Should be unique for this menu page and only
	 *                                              include lowercase alphanumeric, dashes, and underscores characters to be compatible
	 *                                              with sanitize_key().
	 * @param   string              $capability     The capability required for this menu to be displayed to the user.
	 * @param   array               $params         Other parameters required for the adapter to work.
	 * @param   string              $handler_id     The ID of the settings framework handler to use.
	 *
	 * @return  mixed|null
	 */
	public function register_submenu_page( string $parent_slug, $page_title, $menu_title, string $menu_slug, string $capability, array $params = array(), string $handler_id = 'default' ) {
		return $this->get_handler( $handler_id )->register_submenu_page( $parent_slug, $page_title, $menu_title, $menu_slug, $capability, $params );
	}

	/**
	 * Registers a group of settings to be outputted on an admin-side settings page using the API of the given adapter.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string              $group_id       The ID of the settings group.
	 * @param   string|callable     $group_title    The title of the settings group.
	 * @param   array               $fields         The fields to be registered with the group.
	 * @param   string              $page           The settings page on which the group's fields should be displayed.
	 * @param   array               $params         Other parameters required for the adapter to work.
	 * @param   string              $handler_id     The ID of the settings framework handler to use.
	 *
	 * @return  mixed|null
	 */
	public function register_options_group( string $group_id, $group_title, array $fields, string $page, array $params = array(), string $handler_id = 'default' ) {
		return $this->get_handler( $handler_id )->register_options_group( $group_id, $group_title, $fields, $page, $params );
	}

	/**
	 * Registers a group of settings using the API of the given adapter.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string              $group_id       The ID of the settings group.
	 * @param   string|callable     $group_title    The title of the settings group.
	 * @param   array               $fields         The fields to be registered with the group.
	 * @param   array               $locations      Where the group should be outputted.
	 * @param   array               $params         Other parameters required for the adapter to work.
	 * @param   string              $handler_id     The ID of the settings framework handler to use.
	 *
	 * @return  mixed|null
	 */
	public function register_generic_group( string $group_id, $group_title, array $fields, array $locations, array $params = array(), string $handler_id = 'default' ) {
		return $this->get_handler( $handler_id )->register_generic_group( $group_id, $group_title, $fields, $locations, $params );
	}

	/**
	 * Registers a custom field dynamically at a later point than the parent group's creation using the API of the given adapter.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string              $group_id       The ID of the parent group that the dynamically added field belongs to.
	 * @param   string              $field_id       The ID of the newly registered field.
	 * @param   string|callable     $field_title    The title of the newly registered field.
	 * @param   string              $field_type     The type of custom field being registered.
	 * @param   array               $params         Other parameters required for the adapter to work.
	 * @param   string              $handler_id     The ID of the settings framework handler to use.
	 *
	 * @return  mixed|null
	 */
	public function register_field( string $group_id, string $field_id, $field_title, string $field_type, array $params = array(), string $handler_id = 'default' ) {
		return $this->get_handler( $handler_id )->register_field( $group_id, $field_id, $field_title, $field_type, $params );
	}

	/**
	 * Reads a setting's value from the database using the API of the given adapter.
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
	public function get_option_value( string $field_id, string $settings_id, array $params = array(), string $handler_id = 'default' ) {
		return $this->get_handler( $handler_id )->get_option_value( $field_id, $settings_id, $params );
	}

	/**
	 * Reads a field's value from the database using the API of the given adapter.
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
	public function get_field_value( string $field_id, $object_id, array $params = array(), string $handler_id = 'default' ) {
		return $this->get_handler( $handler_id )->get_field_value( $field_id, $object_id, $params );
	}

	/**
	 * Updates a setting's value using the API of the given adapter.
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
	public function update_option_value( string $field_id, $value, string $settings_id, array $params = array(), string $handler_id = 'default' ) {
		return $this->get_handler( $handler_id )->update_option_value( $field_id, $value, $settings_id, $params );
	}

	/**
	 * Updates a field's value using the API of the given adapter.
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
	public function update_field_value( string $field_id, $value, $object_id, array $params = array(), string $handler_id = 'default' ) {
		return $this->get_handler( $handler_id )->update_field_value( $field_id, $value, $object_id, $params );
	}

	/**
	 * Deletes a setting from the database using the API of the given adapter.
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
	public function delete_option( string $field_id, string $settings_id, array $params = array(), string $handler_id = 'default' ) {
		return $this->get_handler( $handler_id )->delete_option( $field_id, $settings_id, $params );
	}

	/**
	 * Deletes a field's value from the database using the API of the given adapter.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $field_id   The ID of the field to delete from the database.
	 * @param   mixed   $object_id  The ID of the object the deletion is for.
	 * @param   array   $params     Other parameters required for the adapter to work.
	 * @param   string  $handler_id     The ID of the settings framework handler to use.
	 *
	 * @return  mixed
	 */
	public function delete_field( string $field_id, $object_id, array $params = array(), string $handler_id = 'default' ) {
		return $this->get_handler( $handler_id )->delete_field( $field_id, $object_id, $params );
	}

	// endregion

	// region HELPERS

	/**
	 * Returns a list of what the default handlers actually are for the inheriting service.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  array
	 */
	protected function get_default_handlers_classes(): array {
		return array( WordPress_Handler::class );
	}

	/**
	 * Returns the class name of the used handler for better type-checking.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  string
	 */
	protected function get_handler_class(): string {
		return SettingsHandlerInterface::class;
	}

	// endregion
}
