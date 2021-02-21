<?php

namespace DeepWebSolutions\Framework\Settings\Services\Traits;

use DeepWebSolutions\Framework\Settings\Exceptions\NotFound;
use DeepWebSolutions\Framework\Settings\Exceptions\NotSupported;
use DeepWebSolutions\Framework\Settings\Services\ValidatorService;
use DeepWebSolutions\Framework\Settings\Utilities\ValidationTypes;

defined( 'ABSPATH' ) || exit;

/**
 * Trait for working with the validator service.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Settings\Services\Traits
 */
trait Validator {
	// region FIELDS AND CONSTANTS

	/**
	 * Validator service for value type validation, defaults, and options checking.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @access  protected
	 * @var     ValidatorService
	 */
	protected ValidatorService $settings_validator_service;

	// endregion

	// region GETTERS

	/**
	 * Gets the validator service instance.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  ValidatorService
	 */
	protected function get_validator_service(): ValidatorService {
		return $this->settings_validator_service;
	}

	/**
	 * Wrapper for the validator's own functions.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $key    The key inside the container.
	 *
	 * @noinspection PhpMissingReturnTypeInspection
	 *
	 * @return  NotFound|mixed
	 */
	public function get_default_value( string $key ) {
		return $this->get_validator_service()->get_default_value( $key );
	}

	/**
	 * Wrapper for the validator's own functions.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $key    The key inside the container.
	 *
	 * @return  NotFound|array
	 */
	public function get_supported_options( string $key ) {
		return $this->get_validator_service()->get_supported_options( $key );
	}

	// endregion

	// region SETTERS

	/**
	 * Sets the settings validator service instance.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   ValidatorService     $validator_service     The validator service instance to use from now on.
	 */
	public function set_validator_service( ValidatorService $validator_service ): void {
		$this->settings_validator_service = $validator_service;
	}

	// endregion

	// region METHODS

	/**
	 * Validates a value.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @see     ValidationTypes
	 *
	 * @throws  NotFound        Thrown if no suitable values were found in the defaults and/or options container(s).
	 * @throws  NotSupported    Thrown if the validation type requested is not supported.
	 *
	 * @param   mixed   $value              The value to validate.
	 * @param   string  $default_key        The composite key to retrieve the default value.
	 * @param   string  $validation_type    The type of validation to perform.
	 * @param   array   $params             Additional params needed.
	 *
	 * @return  mixed
	 */
	public function validate_value( $value, string $default_key, string $validation_type, array $params = array() ) {
		$validator = $this->get_validator_service();

		switch ( $validation_type ) {
			case ValidationTypes::BOOLEAN:
				return $validator->validate_boolean_value( $value, $default_key );
			case ValidationTypes::INTEGER:
				return $validator->validate_integer_value( $value, $default_key );
			case ValidationTypes::FLOAT:
				return $validator->validate_float_value( $value, $default_key );
			case ValidationTypes::CALLBACK:
				return $validator->validate_callback_value( $value, $default_key );
			case ValidationTypes::OPTION:
				return $validator->validate_supported_value( $value, $params['options_key'] ?? '', $default_key );
			case ValidationTypes::CUSTOM:
				if ( isset( $params['callable'] ) && is_callable( $params['callable'] ) ) {
					return call_user_func_array( $params['callable'], array( $value, $default_key ) + ( $params['args'] ?? array() ) );
				} else {
					throw new NotSupported( 'Custom validation requires a valid callable' );
				}
		}

		throw new NotSupported( 'Validation type not supported' );
	}

	// endregion
}
