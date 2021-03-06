<?php

namespace DeepWebSolutions\Framework\Settings;

defined( 'ABSPATH' ) || exit;

/**
 * Describes a settings-service-aware instance with built-in value validation for READ and UPDATE operations.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Settings
 */
interface ValidatedSettingsServiceAwareInterface extends SettingsServiceAwareInterface {
	/**
	 * Retrieves an option's value and runs it through a validation callback.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $handler        The name of the settings framework handler to use.
	 * @param   string  $field_id       The ID of the field within the settings to get.
	 * @param   string  $settings_id    The ID of the settings group to get.
	 * @param   array   $params         Other parameters required for the adapter to work.
	 */
	public function get_validated_option_value( string $handler, string $field_id, string $settings_id, array $params );

	/**
	 * Retrieves a field's value and runs it through a validation callback.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $handler        The name of the settings framework handler to use.
	 * @param   string  $field_id       The ID of the field within the settings to get.
	 * @param   mixed   $object_id      The ID of the object the data is for.
	 * @param   array   $params         Other parameters required for the adapter to work.
	 */
	public function get_validated_field_value( string $handler, string $field_id, $object_id, array $params );

	/**
	 * Updates an option's value after running it through a validation callback.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $handler        The name of the settings framework handler to use.
	 * @param   string  $field_id       The ID of the field within the settings to update.
	 * @param   mixed   $value          The new value of the setting.
	 * @param   string  $settings_id    The ID of the settings group to update.
	 * @param   array   $params         Other parameters required for the adapter to work.
	 */
	public function update_validated_option_value( string $handler, string $field_id, $value, string $settings_id, array $params );

	/**
	 * Updates a field's value after running it through a validation callback.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $handler        The name of the settings framework handler to use.
	 * @param   string  $field_id       The ID of the field within the settings to update.
	 * @param   mixed   $value          The new value of the setting.
	 * @param   mixed   $object_id      The ID of the object the update is for.
	 * @param   array   $params         Other parameters required for the adapter to work.
	 */
	public function update_validated_field_value( string $handler, string $field_id, $value, $object_id, array $params );
}
