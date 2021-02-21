<?php

namespace DeepWebSolutions\Framework\Settings\Traits;

use DeepWebSolutions\Framework\Settings\Exceptions\NotFound;
use DeepWebSolutions\Framework\Settings\Exceptions\NotSupported;
use DeepWebSolutions\Framework\Settings\Services\Traits\Validator;
use DeepWebSolutions\Framework\Settings\Utilities\ActionResponse;
use GuzzleHttp\Promise\Promise;

defined( 'ABSPATH' ) || exit;

/**
 * Functionality trait for working with settings that have a built-in validation service.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Settings\Traits
 */
trait ValidatedSettings {
	use Settings;
	use Validator;

	/**
	 * Retrieves a setting field's value and runs it through a validation callback.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $handler        The name of the settings framework handler to use.
	 * @param   string  $field_id       The ID of the field within the settings to get.
	 * @param   string  $settings_id    The ID of the settings group to get.
	 * @param   array   $params         Other parameters required for the adapter to work.
	 *
	 * @throws  NotFound        Thrown if no suitable values were found in the defaults and/or options container(s).
	 * @throws  NotSupported    Thrown if the validation type requested is not supported.
	 *
	 * @return ActionResponse
	 */
	public function get_validated_setting( string $handler, string $field_id, string $settings_id, array $params ): ActionResponse {
		$value = $this->get_setting_value( $handler, $field_id, $settings_id, $params );
		$args  = wp_parse_args(
			$params['validator'],
			array(
				'default_key'     => '',
				'validation_type' => '',
				'params'          => array(),
			),
		);

		if ( $value->is_resolved() ) {
			$value = $this->validate_value( $value->unwrap(), $args['default_key'], $args['validation_type'], $args['params'] );
			$value = new ActionResponse( $value );
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

	/**
	 * Updates a setting field's value after running it through a validation callback.
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
	 * @throws  NotFound        Thrown if no suitable values were found in the defaults and/or options container(s).
	 * @throws  NotSupported    Thrown if the validation type requested is not supported.
	 *
	 * @return  ActionResponse
	 */
	public function update_validated_setting( string $handler, string $field_id, $value, string $settings_id, array $params ): ActionResponse {
		$args  = wp_parse_args(
			$params['validator'],
			array(
				'default_key'     => '',
				'validation_type' => '',
				'params'          => array(),
			),
		);
		$value = $this->validate_value( $value, $args['default_key'], $args['validation_type'], $args['params'] );

		return $this->update_settings_value( $handler, $field_id, $value, $settings_id, $params );
	}
}
