<?php

namespace DeepWebSolutions\Framework\Settings\Traits;

use DeepWebSolutions\Framework\Core\Interfaces\Actions\Exceptions\SetupFailure;
use DeepWebSolutions\Framework\Core\Interfaces\Actions\Traits\Setupable\Setupable;
use DeepWebSolutions\Framework\Settings\Services\CustomFieldsService;
use DeepWebSolutions\Framework\Settings\Services\Traits\CustomFields as CustomFieldsTrait;

defined( 'ABSPATH' ) || exit;

/**
 * Functionality trait for registering custom fields of active instances.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Settings\Traits
 */
trait CustomFields {
	use CustomFieldsTrait;
	use Setupable;

	/**
	 * Automagically call the custom fields registration method.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @SuppressWarnings(PHPMD.LongVariable)
	 *
	 * @param   CustomFieldsService     $custom_fields_service      Instance of the custom fields service.
	 *
	 * @return  SetupFailure|null
	 */
	public function setup_custom_fields( CustomFieldsService $custom_fields_service ): ?SetupFailure {
		$this->set_custom_fields_service( $custom_fields_service );
		$this->register_custom_fields( $custom_fields_service );
		return null;
	}
}
