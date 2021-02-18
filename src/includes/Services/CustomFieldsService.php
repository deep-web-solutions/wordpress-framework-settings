<?php

namespace DeepWebSolutions\Framework\Settings\Services;

use DeepWebSolutions\Framework\Settings\Factories\HandlerFactory;
use DeepWebSolutions\Framework\Settings\Factories\Traits\Handler;
use GuzzleHttp\Promise\PromiseInterface;

defined( 'ABSPATH' ) || exit;

/**
 * Performs actions against various Settings APIs.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\Framework\Settings\Services
 */
class CustomFieldsService {
	use Handler;

	// region MAGIC METHODS

	/**
	 * CustomFieldsService constructor.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   HandlerFactory  $handler_factory    Instance of the settings handler factory.
	 */
	public function __construct( HandlerFactory $handler_factory ) {
		$this->set_settings_handler_factory( $handler_factory );
	}

	// endregion

	// region METHODS

	/**
	 * Registers a group of settings using the API of the given adapter.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $handler        The name of the settings framework handler to use.
	 * @param   string  $group_id       The ID of the settings group.
	 * @param   string  $group_title    The title of the settings group.
	 * @param   array   $fields         The fields to be registered with the group.
	 * @param   array   $params         Other parameters required for the adapter to work.
	 *
	 * @return  PromiseInterface
	 */
	public function register_generic_group( string $handler, string $group_id, string $group_title, array $fields, array $params ): PromiseInterface {
		$handler = $this->get_settings_handler_factory()->get_handler( $handler );
		return $handler->register_generic_group( $group_id, $group_title, $fields, $params );
	}

	/**
	 * Registers a custom field dynamically at a later point than the parent group's creation using the API of the given adapter.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $handler        The name of the settings framework handler to use.
	 * @param   string  $group_id       The ID of the parent group that the dynamically added field belongs to.
	 * @param   string  $field_id       The ID of the newly registered field.
	 * @param   string  $field_title    The title of the newly registered field.
	 * @param   string  $field_type     The type of custom field being registered.
	 * @param   array   $params         Other parameters required for the adapter to work.
	 *
	 * @return  PromiseInterface
	 */
	public function register_field( string $handler, string $group_id, string $field_id, string $field_title, string $field_type, array $params ): PromiseInterface {
		$handler = $this->get_settings_handler_factory()->get_handler( $handler );
		return $handler->register_field( $group_id, $field_id, $field_title, $field_type, $params );
	}

	/**
	 * Reads a field's value from the database using the API of the given adapter.
	 *
	 * @since   1.0.0
	 * @ver     1.0.0
	 *
	 * @param   string  $handler        The name of the settings framework handler to use.
	 * @param   string  $field_id       The ID of the field to read from the database.
	 * @param   mixed   $object_id      The ID of the object the data is for.
	 * @param   array   $params         Other parameters required for the adapter to work.
	 *
	 * @return  PromiseInterface
	 */
	public function get_field_value( string $handler, string $field_id, $object_id, array $params = array() ): PromiseInterface {
		$handler = $this->get_settings_handler_factory()->get_handler( $handler );
		return $handler->get_field_value( $field_id, $object_id, $params );
	}

	/**
	 * Updates a field's value using the API of the given adapter.
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
	 * @return  PromiseInterface
	 */
	public function update_field_value( string $handler, string $field_id, $value, $object_id, array $params ): PromiseInterface {
		$handler = $this->get_settings_handler_factory()->get_handler( $handler );
		return $handler->update_field_value( $field_id, $value, $object_id, $params );
	}

	/**
	 * Deletes a field's value from the database using the API of the given adapter.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $handler        The name of the settings framework handler to use.
	 * @param   string  $field_id   The ID of the field to delete from the database.
	 * @param   mixed   $object_id  The ID of the object the deletion is for.
	 * @param   array   $params     Other parameters required for the adapter to work.
	 *
	 * @return  PromiseInterface
	 */
	public function delete_field( string $handler, string $field_id, $object_id, array $params ): PromiseInterface {
		$handler = $this->get_settings_handler_factory()->get_handler( $handler );
		return $handler->delete_field( $field_id, $object_id, $params );
	}

	// endregion
}
