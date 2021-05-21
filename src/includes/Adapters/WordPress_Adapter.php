<?php

namespace DeepWebSolutions\Framework\Settings\Adapters;

use DeepWebSolutions\Framework\Helpers\DataTypes\Strings;
use DeepWebSolutions\Framework\Settings\SettingsAdapterInterface;

\defined( 'ABSPATH' ) || exit;

/**
 * Interacts with the Settings API of WordPress itself.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Settings\Adapters
 */
class WordPress_Adapter implements SettingsAdapterInterface {
	// region CREATE

	/**
	 * Registers a new WordPress admin page using WP's own Settings API.
	 *
	 * @param   string|callable     $page_title     The text to be displayed in the title tags of the page when the menu is selected.
	 * @param   string|callable     $menu_title     The text to be used for the menu.
	 * @param   string              $menu_slug      The slug name to refer to this menu by. Should be unique for this menu page and only
	 *                                              include lowercase alphanumeric, dashes, and underscores characters to be compatible
	 *                                              with sanitize_key().
	 * @param   string              $capability     The capability required for this menu to be displayed to the user.
	 * @param   array               $params         Other params required for the adapter to work.
	 *
	 * @return  string  The resulting page's hook_suffix.
	 */
	public function register_menu_page( $page_title, $menu_title, string $menu_slug, string $capability, array $params = array() ): string {
		return add_menu_page( Strings::resolve( $page_title ), Strings::resolve( $menu_title ), $capability, $menu_slug, $params['function'] ?? '', $params['icon_url'] ?? '', $params['position'] ?? null );
	}

	/**
	 * Registers a new WordPress child admin page using WP's own Settings API.
	 *
	 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
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
	 * @param   array               $params         Other params required for the adapter to work.
	 *
	 * @return  string|null     The resulting page's hook_suffix, or null if the user does not have the capability required.
	 */
	public function register_submenu_page( string $parent_slug, $page_title, $menu_title, string $menu_slug, string $capability, array $params = array() ): ?string {
		$page_title = Strings::resolve( $page_title );
		$menu_title = Strings::resolve( $menu_title );
		$params     = \wp_parse_args(
			$params,
			array(
				'function' => '',
				'position' => null,
			)
		);

		switch ( $parent_slug ) {
			case 'plugins.php':
				$result = \add_plugins_page( $page_title, $menu_title, $capability, $menu_slug, $params['function'], $params['position'] );
				break;
			case 'themes.php':
				$result = \add_theme_page( $page_title, $menu_title, $capability, $menu_slug, $params['function'], $params['position'] );
				break;
			case 'options-general.php':
				$result = \add_options_page( $page_title, $menu_title, $capability, $menu_slug, $params['function'], $params['position'] );
				break;
			case 'tools.php':
				$result = \add_management_page( $page_title, $menu_title, $capability, $menu_slug, $params['function'], $params['position'] );
				break;
			case 'index.php':
				$result = \add_dashboard_page( $page_title, $menu_title, $capability, $menu_slug, $params['function'], $params['position'] );
				break;
			case 'edit.php':
				$result = \add_posts_page( $page_title, $menu_title, $capability, $menu_slug, $params['function'], $params['position'] );
				break;
			case 'upload.php':
				$result = \add_media_page( $page_title, $menu_title, $capability, $menu_slug, $params['function'], $params['position'] );
				break;
			case 'link-manager.php':
				$result = \add_links_page( $page_title, $menu_title, $capability, $menu_slug, $params['function'], $params['position'] );
				break;
			case 'edit.php?post_type=page':
				$result = \add_pages_page( $page_title, $menu_title, $capability, $menu_slug, $params['function'], $params['position'] );
				break;
			case 'edit-comments.php':
				$result = \add_comments_page( $page_title, $menu_title, $capability, $menu_slug, $params['function'], $params['position'] );
				break;
			default:
				$result = \add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $params['function'], $params['position'] );
		}

		return ( false === $result ) ? null : $result;
	}

	/**
	 * Registers a group of settings to be outputted on an admin-side settings page using WP's own Settings API.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string              $group_id       The ID of the settings group.
	 * @param   string|callable     $group_title    The title of the settings group.
	 * @param   array               $fields         The fields to be registered with the group.
	 * @param   string              $page           The settings page on which the group's fields should be displayed.
	 * @param   array               $params         Other parameters required for the adapter to work.
	 *
	 * @return  true
	 */
	public function register_options_group( string $group_id, $group_title, array $fields, string $page, array $params = array() ): bool {
		\register_setting( $group_id, $group_id, array( 'type' => 'array' ) + ( $params['setting_args'] ?? array() ) );
		\add_settings_section( $group_id, Strings::resolve( $group_title ), $params['section_callback'] ?? '', $page );

		foreach ( $fields as $field ) {
			if ( isset( $field['id'], $field['title'], $field['callback'] ) ) {
				\add_settings_field( $field['id'], $field['title'], $field['callback'], $page, $group_id, $field['args'] ?? array() );
			}
		}

		return true;
	}

	/**
	 * Registers a group of meta fields and a corresponding meta box using WP's API.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string              $group_id       The ID of the settings group.
	 * @param   string|callable     $group_title    The title of the settings group.
	 * @param   array               $fields         The fields to be registered with the group.
	 * @param   array               $locations      Where the group should be outputted.
	 * @param   array               $params         Other parameters required for the adapter to work.
	 *
	 * @return  bool
	 */
	public function register_generic_group( string $group_id, $group_title, array $fields, array $locations, array $params = array() ): bool {
		\add_meta_box(
			$group_id,
			Strings::resolve( $group_title ),
			$params['callback'] ?? '__return_empty_string',
			$locations,
			$params['context'] ?? 'advanced',
			$params['priority'] ?? 'default',
			array( 'fields' => $fields ) + ( $params['callback_args'] ?? array() )
		);

		return true;
	}

	/**
	 * Registers a custom field dynamically at a later point than the parent group's creation using WP's API.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string              $group_id       The ID of the parent group that the dynamically added field belongs to.
	 * @param   string              $field_id       The ID of the newly registered field.
	 * @param   string|callable     $field_title    The title of the newly registered field.
	 * @param   string              $field_type     The type of custom field being registered.
	 * @param   array               $params         Other parameters required for the adapter to work.
	 *
	 * @return  true
	 */
	public function register_field( string $group_id, string $field_id, $field_title, string $field_type, array $params ): bool {
		if ( isset( \get_registered_settings()[ $group_id ] ) ) {
			\add_settings_field( $field_id, Strings::resolve( $field_title ), $params['callback'] ?? '', $params['page'] ?? '', $group_id, array( 'type' => $field_type ) + ( $params['args'] ?? array() ) );
			return true;
		}

		return false;
	}

	// endregion

	// region READ

	/**
	 * Reads a setting's value from the database using WP's API.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string|null     $field_id       The ID of the field within the settings to read from the database.
	 * @param   string          $settings_id    The ID of the settings group to read from the database.
	 * @param   array           $params         Other parameters required for the adapter to work.
	 *
	 * @return  mixed
	 */
	public function get_option_value( ?string $field_id, string $settings_id, array $params = array() ) {
		$params = \wp_parse_args( $params, array( 'default' => false ) );

		if ( \is_multisite() ) {
			$params   = $this->parse_network_params( $params );
			$settings = ( false === $params['network_id'] )
				? \get_blog_option( $params['blog_id'], $settings_id, $params['default'] )
				: \get_network_option( $params['network_id'], $settings_id, $params['default'] );
		} else {
			$settings = \get_option( $settings_id, $params['default'] );
		}

		return $settings[ $field_id ] ?? $settings;
	}

	/**
	 * Reads a field's value from the database using WP's API.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $field_id       The ID of the field to read from the database.
	 * @param   int     $object_id      The ID of the object the data is for.
	 * @param   array   $params         Other parameters required for the adapter to work.
	 *
	 * @return  mixed
	 */
	public function get_field_value( string $field_id, $object_id, array $params = array() ) {
		$params = \wp_parse_args(
			$params,
			array(
				'meta_type' => 'post',
				'single'    => false,
				'raw'       => false,
			)
		);

		return $params['raw']
			? \get_metadata_raw( $params['meta_type'], $object_id, $field_id, $params['single'] )
			: \get_metadata( $params['meta_type'], $object_id, $field_id, $params['single'] );
	}

	// endregion

	// region UPDATE

	/**
	 * Updates a setting's value using WP's API.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string|null     $field_id       The ID of the field within the settings to update.
	 * @param   mixed           $value          The new value of the setting.
	 * @param   string          $settings_id    The ID of the settings group to update.
	 * @param   array           $params         Other parameters required for the adapter to work.
	 *
	 * @return  bool
	 */
	public function update_option_value( string $field_id, $value, string $settings_id, array $params = array() ): bool {
		$params = \wp_parse_args( $params, array( 'default' => false ) );

		if ( ! \is_null( $field_id ) ) {
			$options              = $this->get_option_value( null, $settings_id, $params );
			$options[ $field_id ] = $value;
			$value                = $options;
		}

		if ( \is_multisite() ) {
			$params = $this->parse_network_params( $params );
			return ( false === $params['network_id'] )
				? \update_blog_option( $params['blog_id'], $settings_id, $value )
				: \update_network_option( $params['network_id'], $settings_id, $value );
		} else {
			return \update_option( $settings_id, $value, $params['autoload'] ?? null );
		}
	}

	/**
	 * Updates a field's value using WP's API.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $field_id       The ID of the field to update.
	 * @param   mixed   $value          The new value of the setting.
	 * @param   int     $object_id      The ID of the object the update is for.
	 * @param   array   $params         Other parameters required for the adapter to work.
	 *
	 * @return  int|bool    The new meta field ID if a field with the given key didn't exist and was therefore added,
	 *                      true on successful update, false on failure or if the value passed to the function is
	 *                      the same as the one that is already in the database.
	 */
	public function update_field_value( string $field_id, $value, $object_id, array $params = array() ) {
		return \update_metadata( $params['meta_type'] ?? 'post', $object_id, $field_id, $value, $params['prev_value'] ?? '' );
	}

	// endregion

	// region DELETE

	/**
	 * Deletes a setting from the database using WP's API.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string|null $field_id       The ID of the settings field to remove from the database. Empty string to delete the whole group.
	 * @param   string      $settings_id    The ID of the settings group to delete the field from.
	 * @param   array       $params         Other parameters required for the adapter to work.
	 *
	 * @return  bool
	 */
	public function delete_option( ?string $field_id, string $settings_id, array $params = array() ): bool {
		if ( ! empty( $field_id ) ) {
			$options = $this->get_option_value( null, $settings_id, $params );
			unset( $options[ $field_id ] );
			return $this->update_option_value( null, $options, $settings_id, $params );
		} elseif ( \is_multisite() ) {
			$params = $this->parse_network_params( $params );
			return ( false === $params['network_id'] )
				? \delete_blog_option( $params['blog_id'], $settings_id )
				: \delete_network_option( $params['network_id'], $settings_id );
		} else {
			return \delete_option( $settings_id );
		}
	}

	/**
	 * Deletes a field's value from the database.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $field_id   The ID of the field to delete from the database.
	 * @param   int     $object_id  The ID of the object the deletion is for.
	 * @param   array   $params     Other parameters required for the adapter to work.
	 *
	 * @return  bool
	 */
	public function delete_field( string $field_id, $object_id, array $params = array() ): bool {
		return \delete_metadata( $params['meta_type'] ?? 'post', $object_id, $field_id, $params['meta_value'] ?? '', $params['delete_all'] ?? false );
	}

	// endregion

	// region HELPERS

	/**
	 * Ensures parameters needed for working with multisite read/update/delete operations are present.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   array   $params     Parameters to parse.
	 *
	 * @return  array
	 */
	protected function parse_network_params( array $params ): array {
		return \wp_parse_args(
			$params,
			array(
				'network_id' => false,  // set to NULL or INT to use network-level options
				'blog_id'    => null,   // set to INT to switch to a certain blog
			)
		);
	}

	// endregion
}
