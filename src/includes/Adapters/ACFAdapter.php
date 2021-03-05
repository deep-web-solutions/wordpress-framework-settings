<?php

namespace DeepWebSolutions\Framework\Settings\Adapters;

use DeepWebSolutions\Framework\Helpers\DataTypes\Strings;
use DeepWebSolutions\Framework\Settings\SettingsAdapterInterface;

defined( 'ABSPATH' ) || exit;

/**
 * Interacts with the API of the ACF plugin.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Settings\Adapters
 *
 * @see     https://www.advancedcustomfields.com/
 */
class ACFAdapter implements SettingsAdapterInterface {
	// region CREATE

	/**
	 * Registers a new WordPress admin page using ACF's API.
	 *
	 * @param   string  $page_title     The text to be displayed in the title tags of the page when the menu is selected.
	 * @param   string  $menu_title     The text to be used for the menu.
	 * @param   string  $menu_slug      The slug name to refer to this menu by. Should be unique for this menu page and only
	 *                                  include lowercase alphanumeric, dashes, and underscores characters to be compatible
	 *                                  with sanitize_key().
	 * @param   string  $capability     The capability required for this menu to be displayed to the user.
	 * @param   array   $params         Other params required for the adapter to work.
	 *
	 * @see     https://www.advancedcustomfields.com/resources/acf_add_options_page/
	 *
	 * @return  array|null  The validated and final page settings or null on failure.
	 */
	public function register_menu_page( string $page_title, string $menu_title, string $menu_slug, string $capability, array $params = array() ): ?array {
		if ( ! function_exists( 'acf_add_options_page' ) ) {
			return null; // ACF Pro required.
		}

		$result = acf_add_options_page(
			array(
				'page_title' => $page_title,
				'menu_title' => $menu_title,
				'menu_slug'  => $menu_slug,
				'capability' => $capability,
			) + $params
		);

		return ( false === $result ) ? null : $result;
	}

	/**
	 * Registers a new WordPress child admin page using ACF's API.
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
	 * @see     https://www.advancedcustomfields.com/resources/acf_add_options_sub_page/
	 *
	 * @return  array|null  The validated and final page settings or null on failure.
	 */
	public function register_submenu_page( string $parent_slug, string $page_title, string $menu_title, string $menu_slug, string $capability, array $params = array() ): ?array {
		if ( ! function_exists( 'acf_add_options_sub_page' ) ) {
			return null; // ACF Pro required.
		}

		$result = acf_add_options_sub_page(
			array( 'parent_slug' => $parent_slug ) + array(
				'page_title' => $page_title,
				'menu_title' => $menu_title,
				'menu_slug'  => $menu_slug,
				'capability' => $capability,
			) + $params
		);
		return ( false === $result ) ? null : $result;
	}

	/**
	 * Registers a group of settings to be outputted on an admin-side settings page using ACF's API.
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
	 * @return  bool
	 */
	public function register_options_group( string $group_id, string $group_title, array $fields, string $page, array $params = array() ): bool {
		return $this->register_generic_group(
			$group_id,
			$group_title,
			$fields,
			array(
				'location' => array(
					array(
						array(
							'param'    => 'options_page',
							'operator' => '==',
							'value'    => $page,
						),
					),
				),
			) + $params
		);
	}

	/**
	 * Registers a group of settings using ACF's API.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $group_id       The ID of the settings group.
	 * @param   string  $group_title    The title of the settings group.
	 * @param   array   $fields         The fields to be registered with the group.
	 * @param   array   $params         Other parameters required for the adapter to work.
	 *
	 * @see     https://www.advancedcustomfields.com/resources/register-fields-via-php/
	 *
	 * @return  bool
	 */
	public function register_generic_group( string $group_id, string $group_title, array $fields, array $params = array() ): bool {
		$group_id = Strings::starts_with( $group_id, 'group_' ) ? $group_id : "group_{$group_id}";
		$params   = wp_parse_args( $params, array( 'location' => array() ) );

		return acf_add_local_field_group(
			array(
				'key'      => $group_id,
				'title'    => $group_title,
				'fields'   => $fields,
				'location' => $params['location'],
			) + $params
		);
	}

	/**
	 * Registers a custom field dynamically at a later point than the parent group's creation using ACF's API.
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
	 * @return  true
	 */
	public function register_field( string $group_id, string $field_id, string $field_title, string $field_type, array $params = array() ): bool {
		$group_id = Strings::starts_with( $group_id, 'group_' ) || Strings::starts_with( $group_id, 'field_' ) ? $group_id : "group_{$group_id}";
		$field_id = Strings::starts_with( $group_id, 'field_' ) ? $field_id : "field_{$field_id}";
		$params   = wp_parse_args( $params, array( 'name' => '' ) );

		acf_add_local_field(
			array(
				'key'    => $field_id,
				'label'  => $field_title,
				'name'   => $params['name'],
				'type'   => $field_type,
				'parent' => $group_id,
			) + $params
		);

		return true;
	}

	// endregion

	// region READ

	/**
	 * Reads a setting's value from the database using ACF's API.
	 *
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string          $field_id       The ID of the field within the settings to read from the database.
	 * @param   string|null     $settings_id    NOT USED BY THE ACF ADAPTER.
	 * @param   array           $params         Other parameters required for the adapter to work.
	 *
	 * @return  mixed
	 */
	public function get_option_value( string $field_id, string $settings_id = null, array $params = array() ) {
		return $this->get_field_value( $field_id, 'options', $params );
	}

	/**
	 * Reads a field's value from the database using ACF's API.
	 *
	 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string              $field_id       The ID of the field to read from the database.
	 * @param   false|string|int    $object_id      The ID of the object the data is for.
	 * @param   array               $params         Other parameters required for the adapter to work.
	 *
	 * @return  mixed
	 */
	public function get_field_value( string $field_id, $object_id = false, array $params = array() ) {
		$params = wp_parse_args(
			$params,
			array(
				'format_value' => true,
			)
		);
		return get_field( $field_id, $object_id, $params['format_value'] );
	}

	// endregion

	// region UPDATE

	/**
	 * Updates a setting's value using ACF's API.
	 *
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string          $field_id       The ID of the field within the settings to update.
	 * @param   mixed           $value          The new value of the setting.
	 * @param   string|null     $settings_id    NOT USED BY THE ACF ADAPTER.
	 * @param   array           $params         Other parameters required for the adapter to work.
	 *
	 * @return  bool
	 */
	public function update_option_value( string $field_id, $value, string $settings_id = null, array $params = array() ): bool {
		return $this->update_field_value( $field_id, 'options', $value, $params );
	}

	/**
	 * Updates a field's value using ACF's API.
	 *
	 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string              $field_id       The ID of the field to update.
	 * @param   mixed               $value          The new value of the setting.
	 * @param   false|string|int    $object_id      The ID of the object the update is for.
	 * @param   array               $params         Other parameters required for the adapter to work.
	 *
	 * @return  bool
	 */
	public function update_field_value( string $field_id, $value, $object_id = false, array $params = array() ): bool {
		return update_field( $field_id, $value, $object_id );
	}

	// endregion

	// region DELETE

	/**
	 * Deletes a setting from the database using ACF's API.
	 *
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string          $field_id       The ID of the settings field to remove from the database.
	 * @param   string|null     $settings_id    NOT USED BY THE ACF ADAPTER.
	 * @param   array           $params         Other parameters required for the adapter to work.
	 *
	 * @return  bool
	 */
	public function delete_option( string $field_id, string $settings_id = null, array $params = array() ): bool {
		return $this->delete_field( $field_id, array( 'post_id' => 'options' ) + $params );
	}

	/**
	 * Deletes a field's value from the database using ACF's API.
	 *
	 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string              $field_id       The ID of the field to delete from the database.
	 * @param   false|string|int    $object_id      The ID of the object the deletion is for.
	 * @param   array               $params         Other parameters required for the adapter to work.
	 *
	 * @return  bool
	 */
	public function delete_field( string $field_id, $object_id = false, array $params = array() ): bool {
		$params = wp_parse_args( $params, array( 'sub_field' => false ) );

		return boolval( $params['sub_field'] )
			? delete_sub_field( $field_id, $object_id )
			: delete_field( $field_id, $object_id );
	}

	// endregion
}
