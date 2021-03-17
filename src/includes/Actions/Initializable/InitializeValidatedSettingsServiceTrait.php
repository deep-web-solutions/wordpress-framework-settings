<?php

namespace DeepWebSolutions\Framework\Settings\Actions\Initializable;

use DeepWebSolutions\Framework\Utilities\Actions\Initializable\InitializeValidationServiceTrait;

\defined( 'ABSPATH' ) || exit;

/**
 * Trait for setting both the settings service and the validation service on the using instance.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Settings\Actions\Initializable
 */
trait InitializeValidatedSettingsServiceTrait {
	// region TRAITS

	use InitializeSettingsServiceTrait;
	use InitializeValidationServiceTrait;

	// endregion
}
