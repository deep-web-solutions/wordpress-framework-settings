<?php

namespace DeepWebSolutions\Framework\Settings\Services\Traits;

use DeepWebSolutions\Framework\Settings\Services\CustomFieldsService;

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
	 * @param   CustomFieldsService     $custom_fields_service      Instance of the custom fields service.
	 */
	abstract protected function register_custom_fields( CustomFieldsService $custom_fields_service ): void;

	// endregion
}
