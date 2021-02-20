<?php

namespace DeepWebSolutions\Framework\Settings\Utilities;

use DeepWebSolutions\Framework\Settings\Services\Traits\Validator;

defined( 'ABSPATH' ) || exit;

/**
 * Valid values for validation types.
 *
 * @see     Validator::validate_value()
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Settings\Utilities
 */
class ValidationTypes {
	public const BOOLEAN  = 'boolean';
	public const INTEGER  = 'integer';
	public const FLOAT    = 'float';
	public const CALLBACK = 'callback';
	public const OPTION   = 'option';
	public const CUSTOM   = 'custom';
}
