<?php

namespace DeepWebSolutions\Framework\Settings;

use DeepWebSolutions\Framework\Settings\Actions\SettingsActionResponse;
use DeepWebSolutions\Framework\Utilities\Validation\ValidationServiceAwareTrait;
use GuzzleHttp\Promise\Promise;

defined( 'ABSPATH' ) || exit;

/**
 * Basic implementation of the validated-settings-service-aware interface.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Settings
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
	 * @param   string  $handler        The name of the settings framework handler to use.
	 * @param   string  $field_id       The ID of the field within the settings to get.
	 * @param   string  $settings_id    The ID of the settings group to get.
	 * @param   array   $params         Other parameters required for the adapter to work.
	 *
	 * @return  SettingsActionResponse
	 */
	public function get_validated_option_value( string $handler, string $field_id, string $settings_id, array $params ): SettingsActionResponse {
		$value = $this->get_option_value( $handler, $field_id, $settings_id, $params );
		return $this->validate_setting_value( $value, $params['validator'] ?? array() );
	}

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
	 *
	 * @return  SettingsActionResponse
	 */
	public function get_validated_field_value( string $handler, string $field_id, $object_id, array $params ): SettingsActionResponse {
		$value = $this->get_field_value( $handler, $field_id, $object_id, $params );
		return $this->validate_setting_value( $value, $params['validator'] ?? array() );
	}

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
	 *
	 * @return  SettingsActionResponse
	 */
	public function update_validated_option_value( string $handler, string $field_id, $value, string $settings_id, array $params ): SettingsActionResponse {
		$value = $this->validate_setting_value( $value, $params['validator'] ?? array() );
		return $this->update_option_value( $handler, $field_id, $value, $settings_id, $params );
	}

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
	 *
	 * @return  SettingsActionResponse
	 */
	public function update_validated_field_value( string $handler, string $field_id, $value, $object_id, array $params ): SettingsActionResponse {
		$value = $this->validate_setting_value( $value, $params['validator'] ?? array() );
		return $this->update_field_value( $handler, $field_id, $value, $object_id, $params );
	}

	// endregion

	// region HELPERS

	/**
	 * Validates a value wrapped inside an action response.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   SettingsActionResponse      $value      Value to validate.
	 * @param   array                       $args       Arguments for the validation service.
	 *
	 * @return  SettingsActionResponse
	 */
	protected function validate_setting_value( SettingsActionResponse $value, array $args ): SettingsActionResponse {
		$args = wp_parse_args(
			$args,
			array(
				'default_key'     => '',
				'validation_type' => '',
				'params'          => array(),
			),
		);

		if ( $value->is_resolved() ) {
			$value = $this->validate_value( $value->unwrap(), $args['default_key'], $args['validation_type'], $args['params'] );
			$value = new SettingsActionResponse( $value );
		} else {
			$validated_value = new Promise();
			$value->unwrap()->then(
				function( $value ) use ( $validated_value, $args ) {
					$value = $this->validate_value( $value->unwrap(), $args['default_key'], $args['validation_type'], $args['params'] );
					$validated_value->resolve( $value );
					return $validated_value;
				}
			);
		}

		return $value;
	}

	// endregion
}
