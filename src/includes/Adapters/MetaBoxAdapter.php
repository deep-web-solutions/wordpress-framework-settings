<?php

namespace DeepWebSolutions\Framework\Settings\Adapters;

use DeepWebSolutions\Framework\Foundations\Exceptions\NotSupportedException;
use DeepWebSolutions\Framework\Settings\SettingsAdapterInterface;

defined( 'ABSPATH' ) || exit;

/**
 * Interacts with the API of the Meta Box plugin.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Settings\Adapters
 *
 * @see     https://metabox.io/
 */
class MetaBoxAdapter implements SettingsAdapterInterface {
	// region CREATE

	/**
	 * Registers a new WordPress admin page using Meta Box's API.
	 *
	 * @param   string  $page_title     The text to be displayed in the title tags of the page when the menu is selected.
	 * @param   string  $menu_title     The text to be used for the menu.
	 * @param   string  $menu_slug      The slug name to refer to this menu by. Should be unique for this menu page and only
	 *                                  include lowercase alphanumeric, dashes, and underscores characters to be compatible
	 *                                  with sanitize_key().
	 * @param   string  $capability     The capability required for this menu to be displayed to the user.
	 * @param   array   $params         Other params required for the adapter to work.
	 *
	 * @see     https://docs.metabox.io/extensions/mb-settings-page/
	 *
	 * @return  bool
	 */
	public function register_menu_page( string $page_title, string $menu_title, string $menu_slug, string $capability, array $params ): bool {
		return add_filter(
			'mb_settings_pages',
			function ( $settings_pages ) use ( $page_title, $menu_title, $menu_slug, $capability, $params ) {
				$settings_pages[] = array(
					'id'         => $menu_slug,
					'menu_title' => $menu_title,
					'page_title' => $page_title,
					'capability' => $capability,
				) + $params;
				return $settings_pages;
			}
		);
	}

	/**
	 * Registers a new WordPress child admin page using Meta Box's API.
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
	 * @see     https://docs.metabox.io/extensions/mb-settings-page/
	 *
	 * @return  bool
	 */
	public function register_submenu_page( string $parent_slug, string $page_title, string $menu_title, string $menu_slug, string $capability, array $params ): bool {
		return $this->register_menu_page( $page_title, $menu_title, $menu_slug, $capability, array( 'parent' => $parent_slug ) + $params );
	}

	/**
	 * Registers a group of settings to be outputted on an admin-side settings page using Meta Box's API.
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
	 * @see     https://docs.metabox.io/extensions/mb-settings-page/
	 *
	 * @return  bool
	 */
	public function register_options_group( string $group_id, string $group_title, array $fields, string $page, array $params ): bool {
		return $this->register_generic_group( $group_id, $group_title, $fields, array( 'settings_pages' => $page ) + $params );
	}

	/**
	 * Registers a group of settings using Meta Box's API.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $group_id       The ID of the settings group.
	 * @param   string  $group_title    The title of the settings group.
	 * @param   array   $fields         The fields to be registered with the group.
	 * @param   array   $params         Other parameters required for the adapter to work.
	 *
	 * @see     https://docs.metabox.io/extensions/mb-settings-page/
	 *
	 * @return  bool
	 */
	public function register_generic_group( string $group_id, string $group_title, array $fields, array $params ): bool {
		return add_filter(
			'rwmb_meta_boxes',
			function ( $meta_boxes ) use ( $group_id, $group_title, $fields, $params ) {
				$meta_boxes[] = array(
					'id'     => $group_id,
					'title'  => $group_title,
					'fields' => $fields,
				) + $params;
				return $meta_boxes;
			}
		);
	}

	/**
	 * Registers a custom field dynamically at a later point than the parent group's creation using Meta Box's API.
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
	 * @return  bool
	 */
	public function register_field( string $group_id, string $field_id, string $field_title, string $field_type, array $params = array() ): bool {
		return add_filter(
			'rwmb_meta_boxes',
			function( $meta_boxes ) use ( $group_id, $field_id, $field_title, $field_type, $params ) {
				foreach ( $meta_boxes as $k => $meta_box ) {
					if ( isset( $meta_box['id'] ) && $group_id === $meta_box['id'] ) {
						$meta_boxes[ $k ]['fields'][] = array(
							'id'   => $field_id,
							'name' => $field_title,
							'type' => $field_type,
						) + $params;
					}
				}
			},
			20
		);
	}

	// endregion

	// region READ

	/**
	 * Reads a setting's value from the database using Meta Box's API.
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
		$params                = wp_parse_args( $params, array( 'network' => false ) );
		$params['object_type'] = ( is_multisite() && $params['network'] ) ? 'network_setting' : 'setting';

		return $this->get_field_value( $field_id, $settings_id, $params );
	}

	/**
	 * Reads a field's value from the database using Meta Box's API.
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
	public function get_field_value( string $field_id, $object_id = null, array $params = array() ) {
		return rwmb_meta( $field_id, $params, $object_id );
	}

	// endregion

	// region UPDATE

	/**
	 * Updates a setting's value using Meta Box's API.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $field_id       The ID of the field within the settings to update.
	 * @param   mixed   $value          The new value of the setting.
	 * @param   string  $settings_id    The ID of the settings group to update.
	 * @param   array   $params         Other parameters required for the adapter to work.
	 *
	 * @return  true
	 */
	public function update_option_value( string $field_id, $value, string $settings_id, array $params ): bool {
		$params                = wp_parse_args( $params, array( 'network' => false ) );
		$params['object_type'] = ( is_multisite() && $params['network'] ) ? 'network_setting' : 'setting';

		return $this->update_field_value( $field_id, $value, $settings_id, $params );
	}

	/**
	 * Updates a field's value using Meta Box's API.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $field_id       The ID of the field to update.
	 * @param   mixed   $value          The new value of the setting.
	 * @param   mixed   $object_id      The ID of the object the update is for.
	 * @param   array   $params         Other parameters required for the adapter to work.
	 *
	 * @return  true
	 */
	public function update_field_value( string $field_id, $value, $object_id, array $params ): bool {
		rwmb_set_meta( $object_id, $field_id, $value, $params );
		return true;
	}

	// endregion

	// region DELETE

	/**
	 * Deletes a setting from the database using Meta Box's API.
	 *
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string      $field_id       The ID of the settings field to remove from the database. Empty string to delete the whole group.
	 * @param   string      $settings_id    The ID of the settings group to delete the field from.
	 * @param   array       $params         Other parameters required for the adapter to work.
	 *
	 * @throws  NotSupportedException       The Meta Box plugin does NOT implement any functions for deleting values. Use the WP adapter.
	 *
	 * @return  void
	 */
	public function delete_option( string $field_id, string $settings_id, array $params ) {
		throw new NotSupportedException();
	}

	/**
	 * Deletes a field's value from the database using Meta Box's API.
	 *
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $field_id   The ID of the field to delete from the database.
	 * @param   mixed   $object_id  The ID of the object the deletion is for.
	 * @param   array   $params     Other parameters required for the adapter to work.
	 *
	 * @throws  NotSupportedException       The Meta Box plugin does NOT implement any functions for deleting values. Use the WP adapter.
	 *
	 * @return  void
	 */
	public function delete_field( string $field_id, $object_id, array $params ) {
		throw new NotSupportedException();
	}

	// endregion
}
