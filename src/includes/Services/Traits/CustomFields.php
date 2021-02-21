<?php

namespace DeepWebSolutions\Framework\Settings\Services\Traits;

use DeepWebSolutions\Framework\Settings\Services\CustomFieldsService;
use DeepWebSolutions\Framework\Settings\Utilities\ActionResponse;

defined( 'ABSPATH' ) || exit;

/**
 * Trait for working with the custom fields service.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Settings\Services\Traits
 */
trait CustomFields {
	// region FIELDS AND CONSTANTS

	/**
	 * Setting service for handling settings pages and custom fields.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @access  protected
	 * @var     CustomFieldsService
	 */
	protected CustomFieldsService $custom_fields_service;

	// endregion

	// region GETTERS

	/**
	 * Gets the custom fields service instance.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  CustomFieldsService
	 */
	protected function get_custom_fields_service(): CustomFieldsService {
		return $this->custom_fields_service;
	}

	// endregion

	// region SETTERS

	/**
	 * Sets the custom fields service instance.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @SuppressWarnings(PHPMD.LongVariable)
	 *
	 * @param   CustomFieldsService     $custom_fields_service      The custom fields service instance to use from now on.
	 */
	public function set_custom_fields_service( CustomFieldsService $custom_fields_service ): void {
		$this->custom_fields_service = $custom_fields_service;
	}

	// endregion

	// region METHODS

	/**
	 * Using classes should define their custom fields in here.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @SuppressWarnings(PHPMD.LongVariable)
	 *
	 * @param   CustomFieldsService     $custom_fields_service      Instance of the custom fields service.
	 */
	abstract protected function register_custom_fields( CustomFieldsService $custom_fields_service ): void;

	/**
	 * Wrapper around the service's own method.
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
	 * @return  ActionResponse
	 */
	public function register_generic_group( string $handler, string $group_id, string $group_title, array $fields, array $params ): ActionResponse {
		return $this->get_custom_fields_service()->register_generic_group( $handler, $group_id, $group_title, $fields, $params );
	}

	/**
	 * Wrapper around the service's own method.
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
	 * @return  ActionResponse
	 */
	public function register_field( string $handler, string $group_id, string $field_id, string $field_title, string $field_type, array $params ): ActionResponse {
		return $this->get_custom_fields_service()->register_field( $handler, $group_id, $field_id, $field_title, $field_type, $params );
	}

	/**
	 * Wrapper around the service's own method.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $handler        The name of the settings framework handler to use.
	 * @param   string  $field_id       The ID of the field to read from the database.
	 * @param   mixed   $object_id      The ID of the object the data is for.
	 * @param   array   $params         Other parameters required for the adapter to work.
	 *
	 * @return  ActionResponse
	 */
	public function get_field_value( string $handler, string $field_id, $object_id, array $params = array() ): ActionResponse {
		return $this->get_custom_fields_service()->get_field_value( $handler, $field_id, $object_id, $params );
	}

	/**
	 * Wrapper around the service's own method.
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
	 * @return  ActionResponse
	 */
	public function update_field_value( string $handler, string $field_id, $value, $object_id, array $params ): ActionResponse {
		return $this->get_custom_fields_service()->update_field_value( $handler, $field_id, $value, $object_id, $params );
	}

	/**
	 * Wrapper around the service's own method.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $handler        The name of the settings framework handler to use.
	 * @param   string  $field_id   The ID of the field to delete from the database.
	 * @param   mixed   $object_id  The ID of the object the deletion is for.
	 * @param   array   $params     Other parameters required for the adapter to work.
	 *
	 * @return  ActionResponse
	 */
	public function delete_field( string $handler, string $field_id, $object_id, array $params ): ActionResponse {
		return $this->get_custom_fields_service()->delete_field( $handler, $field_id, $object_id, $params );
	}

	// endregion
}
