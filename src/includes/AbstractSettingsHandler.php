<?php

namespace DeepWebSolutions\Framework\Settings;

use DeepWebSolutions\Framework\Foundations\Actions\Runnable\RunFailureException;
use DeepWebSolutions\Framework\Foundations\Actions\RunnableInterface;
use DeepWebSolutions\Framework\Foundations\Utilities\Handlers\AbstractHandler;
use DeepWebSolutions\Framework\Helpers\WordPress\Hooks\HooksHelpersAwareInterface;
use DeepWebSolutions\Framework\Utilities\Hooks\HooksService;
use DeepWebSolutions\Framework\Utilities\Hooks\HooksServiceRegisterInterface;
use DeepWebSolutions\Framework\Utilities\Hooks\HooksServiceRegisterTrait;

\defined( 'ABSPATH' ) || exit;

/**
 * Handles performing actions against a settings framework's API.
 *
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Settings
 */
abstract class AbstractSettingsHandler extends AbstractHandler implements SettingsHandlerInterface, HooksHelpersAwareInterface, HooksServiceRegisterInterface, RunnableInterface {
	// region TRAITS

	use HooksServiceRegisterTrait;

	// endregion

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

	/**
	 * The menu pages registered with the adapter when the handler runs.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @access  protected
	 * @var     array
	 */
	protected array $menu_pages = array();

	/**
	 * The submenu pages registered with the adapter when the handler runs.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @access  protected
	 * @var     array
	 */
	protected array $submenu_pages = array();

	/**
	 * The options groups registered with the adapter when the handler runs.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @access  protected
	 * @var     array
	 */
	protected array $options_groups = array();

	/**
	 * The generic groups registered with the adapter when the handler runs.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @access  protected
	 * @var     array
	 */
	protected array $generic_groups = array();

	/**
	 * The fields registered with the adapter when the handler runs.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @access  protected
	 * @var     array
	 */
	protected array $fields = array();

	/**
	 * Stores whether individual actions have been run already or not.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @access  protected
	 * @var     bool|null[]
	 */
	protected array $is_run = array();

	/**
	 * Stores whether individual actions have been run already or not.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @access  protected
	 * @var     RunFailureException|null[]
	 */
	protected array $run_result = array();

	// endregion

	// region MAGIC METHODS

	/**
	 * AbstractSettingsHandler constructor.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string                      $handler_id     The ID of the handler instance.
	 * @param   SettingsAdapterInterface    $adapter        Settings adapter to use.
	 */
	public function __construct( string $handler_id, SettingsAdapterInterface $adapter ) {
		parent::__construct( $handler_id );
		$this->adapter = $adapter;
	}

	// endregion

	// region INHERITED METHODS

	/**
	 * Returns the settings adapter used.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  SettingsAdapterInterface
	 */
	public function get_adapter(): SettingsAdapterInterface {
		return $this->adapter;
	}

	/**
	 * Returns the type of the handler.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  string
	 */
	public function get_type(): string {
		return 'settings';
	}

	/**
	 * Registers hook with the hooks service.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   HooksService    $hooks_service      Instance of the hooks service.
	 */
	public function register_hooks( HooksService $hooks_service ): void {
		foreach ( SettingsActionsEnum::get_all() as $action ) {
			$hook = $this->get_action_hook( $action );

			if ( ! \did_action( $hook ) ) {
				$hooks_service->add_action( $hook, $this, 'run', PHP_INT_MAX - 100 );
			} else {
				$this->is_run[ $action ]     = true;
				$this->run_result[ $action ] = true;
			}
		}
	}

	/**
	 * Registers the pages, groups, and fields using the given adapter.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  RunFailureException|null
	 */
	public function run(): ?RunFailureException {
		foreach ( SettingsActionsEnum::get_all() as $action ) {
			$hook = $this->get_action_hook( $action );
			if ( \doing_action( $hook ) && \is_null( $this->is_run( $action ) ) ) {
				switch ( $action ) {
					case SettingsActionsEnum::REGISTER_MENU_PAGE:
						\array_walk( $this->menu_pages, array( $this, 'array_walk_register_menu_page' ) );
						break;
					case SettingsActionsEnum::REGISTER_SUBMENU_PAGE:
						\array_walk( $this->submenu_pages, array( $this, 'array_walk_register_submenu_page' ) );
						break;
					case SettingsActionsEnum::REGISTER_OPTIONS_GROUP:
						\array_walk( $this->options_groups, array( $this, 'array_walk_register_options_group' ) );
						break;
					case SettingsActionsEnum::REGISTER_GENERIC_GROUP:
						\array_walk( $this->generic_groups, array( $this, 'array_walk_register_generic_group' ) );
						break;
					case SettingsActionsEnum::REGISTER_FIELD:
						\array_walk( $this->fields, array( $this, 'array_walk_register_field' ) );
						break;
					default:
						continue 2;
				}

				$this->is_run[ $action ]     = true;
				$this->run_result[ $action ] = null;
			}
		}

		return null;
	}

	// endregion

	// region METHODS

	/**
	 * Returns whether a given settings action has been run already or not.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string|null     $context    The settings action the query related to.
	 *
	 * @return  bool|null
	 */
	public function is_run( string $context ): ?bool {
		return $this->is_run[ $context ] ?? null;
	}

	/**
	 * Returns the result of running a given settings action.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string|null     $context    The settings action the query related to.
	 *
	 * @return  RunFailureException|null
	 */
	public function get_run_result( string $context ): ?RunFailureException {
		return $this->run_result[ $context ] ?? null;
	}

	/**
	 * Registers a new WordPress admin page.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 *
	 * @param   string|callable     $page_title     The text to be displayed in the title tags of the page when the menu is selected.
	 * @param   string|callable     $menu_title     The text to be used for the menu.
	 * @param   string              $menu_slug      The slug name to refer to this menu by. Should be unique for this menu page and only
	 *                                              include lowercase alphanumeric, dashes, and underscores characters to be compatible
	 *                                              with sanitize_key().
	 * @param   string              $capability     The capability required for this menu to be displayed to the user.
	 * @param   array               $params         Other params required for the adapter to work.
	 *
	 * @return  mixed|null
	 */
	public function register_menu_page( $page_title, $menu_title, string $menu_slug, string $capability, array $params ) {
		if ( $this->is_run( SettingsActionsEnum::REGISTER_MENU_PAGE ) || \did_action( $this->get_action_hook( SettingsActionsEnum::REGISTER_MENU_PAGE ) ) ) {
			return $this->array_walk_register_menu_page( \get_defined_vars() );
		} else {
			$this->menu_pages[] = \get_defined_vars();
		}

		return null;
	}

	/**
	 * Registers a new WordPress child admin page.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 *
	 * @param   string              $parent_slug    The slug name for the parent menu (or the file name of a standard WordPress admin page).
	 * @param   string|callable     $page_title     The text to be displayed in the title tags of the page when the menu is selected.
	 * @param   string|callable     $menu_title     The text to be used for the menu.
	 * @param   string              $menu_slug      The slug name to refer to this menu by. Should be unique for this menu page and only
	 *                                              include lowercase alphanumeric, dashes, and underscores characters to be compatible
	 *                                              with sanitize_key().
	 * @param   string              $capability     The capability required for this menu to be displayed to the user.
	 * @param   array               $params         Other parameters required for the adapter to work.
	 *
	 * @return  mixed|null
	 */
	public function register_submenu_page( string $parent_slug, $page_title, $menu_title, string $menu_slug, string $capability, array $params ) {
		if ( $this->is_run( SettingsActionsEnum::REGISTER_SUBMENU_PAGE ) || \did_action( $this->get_action_hook( SettingsActionsEnum::REGISTER_SUBMENU_PAGE ) ) ) {
			return $this->array_walk_register_submenu_page( \get_defined_vars() );
		} else {
			$this->submenu_pages[] = \get_defined_vars();
		}

		return null;
	}

	/**
	 * Registers a group of settings to be outputted on an admin-side settings page.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 *
	 * @param   string              $group_id       The ID of the settings group.
	 * @param   string|callable     $group_title    The title of the settings group.
	 * @param   array               $fields         The fields to be registered with the group.
	 * @param   string              $page           The settings page on which the group's fields should be displayed.
	 * @param   array               $params         Other parameters required for the adapter to work.
	 *
	 * @return  mixed|null
	 */
	public function register_options_group( string $group_id, $group_title, array $fields, string $page, array $params ) {
		if ( $this->is_run( SettingsActionsEnum::REGISTER_OPTIONS_GROUP ) || \did_action( $this->get_action_hook( SettingsActionsEnum::REGISTER_OPTIONS_GROUP ) ) ) {
			return $this->array_walk_register_options_group( \get_defined_vars() );
		} else {
			$this->options_groups[] = \get_defined_vars();
		}

		return null;
	}

	/**
	 * Registers a group of settings.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 *
	 * @param   string              $group_id       The ID of the settings group.
	 * @param   string|callable     $group_title    The title of the settings group.
	 * @param   array               $fields         The fields to be registered with the group.
	 * @param   array               $locations      Where the group should be outputted.
	 * @param   array               $params         Other parameters required for the adapter to work.
	 *
	 * @return  mixed|null
	 */
	public function register_generic_group( string $group_id, $group_title, array $fields, array $locations, array $params ) {
		if ( $this->is_run( SettingsActionsEnum::REGISTER_GENERIC_GROUP ) || \did_action( $this->get_action_hook( SettingsActionsEnum::REGISTER_GENERIC_GROUP ) ) ) {
			return $this->array_walk_register_generic_group( \get_defined_vars() );
		} else {
			$this->generic_groups[] = \get_defined_vars();
		}

		return null;
	}

	/**
	 * Registers a custom field dynamically at a later point than the parent group's creation.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 *
	 * @param   string              $group_id       The ID of the parent group that the dynamically added field belongs to.
	 * @param   string              $field_id       The ID of the newly registered field.
	 * @param   string|callable     $field_title    The title of the newly registered field.
	 * @param   string              $field_type     The type of custom field being registered.
	 * @param   array               $params         Other parameters required for the adapter to work.
	 *
	 * @return  mixed|null
	 */
	public function register_field( string $group_id, string $field_id, $field_title, string $field_type, array $params ) {
		if ( $this->is_run( SettingsActionsEnum::REGISTER_FIELD ) || \did_action( $this->get_action_hook( SettingsActionsEnum::REGISTER_FIELD ) ) ) {
			return $this->array_walk_register_field( \get_defined_vars() );
		} else {
			$this->fields[] = \get_defined_vars();
		}

		return null;
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
	 * @return  mixed
	 */
	public function get_option_value( string $field_id, string $settings_id, array $params ) {
		return $this->adapter->get_option_value( $field_id, $settings_id, $params );
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
	 * @return  mixed
	 */
	public function get_field_value( string $field_id, $object_id, array $params = array() ) {
		return $this->adapter->get_field_value( $field_id, $object_id, $params );
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
	 * @return  mixed
	 */
	public function update_option_value( string $field_id, $value, string $settings_id, array $params ) {
		return $this->adapter->update_option_value( $field_id, $value, $settings_id, $params );
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
	 * @return  mixed
	 */
	public function update_field_value( string $field_id, $value, $object_id, array $params ) {
		return $this->adapter->update_field_value( $field_id, $value, $object_id, $params );
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
	 * @return  mixed
	 */
	public function delete_option_value( string $field_id, string $settings_id, array $params ) {
		return $this->adapter->delete_option_value( $field_id, $settings_id, $params );
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
	 * @return  mixed
	 */
	public function delete_field_value( string $field_id, $object_id, array $params ) {
		return $this->adapter->delete_field_value( $field_id, $object_id, $params );
	}

	// endregion

	// region HELPERS

	/**
	 * Registers a menu page using the adapter.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   array   $menu_page   Menu page to register.
	 *
	 * @return  mixed
	 */
	protected function array_walk_register_menu_page( array $menu_page ) {
		return \call_user_func_array( array( $this->adapter, 'register_menu_page' ), $menu_page );
	}

	/**
	 * Registers a submenu page using the adapter.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   array   $submenu_page   Submenu page to register.
	 *
	 * @return  mixed
	 */
	protected function array_walk_register_submenu_page( array $submenu_page ) {
		return \call_user_func_array( array( $this->adapter, 'register_submenu_page' ), $submenu_page );
	}

	/**
	 * Registers an options group using the adapter.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   array   $options_group      Options group to register.
	 *
	 * @return  mixed
	 */
	protected function array_walk_register_options_group( array $options_group ) {
		return \call_user_func_array( array( $this->adapter, 'register_options_group' ), $options_group );
	}

	/**
	 * Registers a generic group using the adapter.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   array   $generic_group      Generic group to register.
	 *
	 * @return  mixed
	 */
	protected function array_walk_register_generic_group( array $generic_group ) {
		return \call_user_func_array( array( $this->adapter, 'register_generic_group' ), $generic_group );
	}

	/**
	 * Registers a field using the adapter.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   array   $field      Field to register.
	 *
	 * @return  mixed
	 */
	protected function array_walk_register_field( array $field ) {
		return \call_user_func_array( array( $this->adapter, 'register_field' ), $field );
	}

	// endregion
}
