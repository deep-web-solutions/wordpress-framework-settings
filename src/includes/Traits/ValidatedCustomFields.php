<?php

namespace DeepWebSolutions\Framework\Settings\Traits;

use DeepWebSolutions\Framework\Settings\Exceptions\NotFound;
use DeepWebSolutions\Framework\Settings\Exceptions\NotSupported;
use DeepWebSolutions\Framework\Settings\Services\Traits\Validator;
use DeepWebSolutions\Framework\Settings\Utilities\ActionResponse;
use GuzzleHttp\Promise\Promise;

defined( 'ABSPATH' ) || exit;

/**
 * Functionality trait for working with custom fields that have a built-in validation service.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Settings\Traits
 */
trait ValidatedCustomFields {
	use CustomFields;
	use Validator;

	/**
	 * Retrieves a custom field's value and runs it through a validation callback.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $handler        The name of the settings framework handler to use.
	 * @param   string  $field_id       The ID of the field to read from the database.
	 * @param   mixed   $object_id      The ID of the object the data is for.
	 * @param   array   $params         Other parameters required for the adapter to work.
	 *
	 * @throws  NotFound        Thrown if no suitable values were found in the defaults and/or options container(s).
	 * @throws  NotSupported    Thrown if the validation type requested is not supported.
	 *
	 * @return ActionResponse
	 */
	public function get_validated_value( string $handler, string $field_id, $object_id, array $params ): ActionResponse {
		$value = $this->get_field_value( $handler, $field_id, $object_id, $params );
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
	 * Updates a custom field's value after running it through a validation callback.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $handler        The name of the settings framework handler to use.
	 * @param   string  $field_id       The ID of the field to update.
	 * @param   mixed   $value          The new value of the setting.
	 * @param   mixed   $object_id      The ID of the object the update is for.
	 * @param   array   $params         Other parameters required for the adapter to work.
	 *
	 * @throws  NotFound        Thrown if no suitable values were found in the defaults and/or options container(s).
	 * @throws  NotSupported    Thrown if the validation type requested is not supported.
	 *
	 * @return  ActionResponse
	 */
	public function update_validated_value( string $handler, string $field_id, $value, $object_id, array $params ): ActionResponse {
		$args  = wp_parse_args(
			$params['validator'],
			array(
				'default_key'     => '',
				'validation_type' => '',
				'params'          => array(),
			),
		);
		$value = $this->validate_value( $value, $args['default_key'], $args['validation_type'], $args['params'] );

		return $this->update_field_value( $handler, $field_id, $value, $object_id, $params );
	}
}
