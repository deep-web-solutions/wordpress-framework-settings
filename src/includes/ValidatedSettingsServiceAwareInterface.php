<?php

namespace DeepWebSolutions\Framework\Settings;

\defined( 'ABSPATH' ) || exit;

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
	 * @param   string  $field_id       The ID of the field within the settings to get.
	 * @param   string  $settings_id    The ID of the settings group to get.
	 * @param   array   $params         Other parameters required for the adapter to work.
	 * @param   string  $handler_id     The ID of the settings framework handler to use.
	 */
	public function get_validated_option_value( string $field_id, string $settings_id, array $params, string $handler_id );

	/**
	 * Retrieves a field's value and runs it through a validation callback.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $field_id       The ID of the field within the settings to get.
	 * @param   mixed   $object_id      The ID of the object the data is for.
	 * @param   array   $params         Other parameters required for the adapter to work.
	 * @param   string  $handler_id     The ID of the settings framework handler to use.
	 */
	public function get_validated_field_value( string $field_id, $object_id, array $params, string $handler_id );

	/**
	 * Updates an option's value after running it through a validation callback.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $field_id       The ID of the field within the settings to update.
	 * @param   mixed   $value          The new value of the setting.
	 * @param   string  $settings_id    The ID of the settings group to update.
	 * @param   array   $params         Other parameters required for the adapter to work.
	 * @param   string  $handler_id     The ID of the settings framework handler to use.
	 */
	public function update_validated_option_value( string $field_id, $value, string $settings_id, array $params, string $handler_id );

	/**
	 * Updates a field's value after running it through a validation callback.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $field_id       The ID of the field within the settings to update.
	 * @param   mixed   $value          The new value of the setting.
	 * @param   mixed   $object_id      The ID of the object the update is for.
	 * @param   array   $params         Other parameters required for the adapter to work.
	 * @param   string  $handler_id     The ID of the settings framework handler to use.
	 */
	public function update_validated_field_value( string $field_id, $value, $object_id, array $params, string $handler_id );
}
