<?php

namespace DeepWebSolutions\Framework\Settings\Utilities;

use DeepWebSolutions\Framework\Settings\SettingsServiceAwareTrait;
use DeepWebSolutions\Framework\Utilities\Validation\ValidationServiceAwareTrait;

\defined( 'ABSPATH' ) || exit;

/**
 * Basic implementation of the validated-settings-service-aware interface.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Settings\Utilities
 */
trait ValidatedSettingsServiceAwareTrait {
	// region TRAITS

	use SettingsServiceAwareTrait;
	use ValidationServiceAwareTrait;

	// endregion

	// region METHODS

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
	 *
	 * @return  mixed
	 */
	public function get_validated_option( string $field_id, string $settings_id, array $params = array(), string $handler_id = 'default' ) {
		$value = $this->get_option( $field_id, $settings_id, $params, $handler_id );
		return $this->validate_setting( $value, $params['validator'] ?? array() );
	}

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
	 *
	 * @return  mixed
	 */
	public function get_validated_field( string $field_id, $object_id, array $params = array(), string $handler_id = 'default' ) {
		$value = $this->get_field( $field_id, $object_id, $params, $handler_id );
		return $this->validate_setting( $value, $params['validator'] ?? array() );
	}

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
	 *
	 * @return  mixed
	 */
	public function update_validated_option( string $field_id, $value, string $settings_id, array $params = array(), string $handler_id = 'default' ) {
		$value = $this->validate_setting( $value, $params['validator'] ?? array() );
		return $this->update_option( $field_id, $value, $settings_id, $params, $handler_id );
	}

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
	 *
	 * @return  mixed
	 */
	public function update_validated_field( string $field_id, $value, $object_id, array $params = array(), string $handler_id = 'default' ) {
		$value = $this->validate_setting( $value, $params['validator'] ?? array() );
		return $this->update_field( $field_id, $value, $object_id, $params, $handler_id );
	}

	// endregion

	// region HELPERS

	/**
	 * Validates a value wrapped inside an action response.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   mixed   $value      Value to validate.
	 * @param   array   $args       Arguments for the validation service.
	 *
	 * @return  mixed
	 */
	protected function validate_setting( $value, array $args ) {
		return $this->validate( $value, $args['default_key'] ?? '', $args['validation_type'] ?? '', $args['params'] ?? array() );
	}

	// endregion
}
