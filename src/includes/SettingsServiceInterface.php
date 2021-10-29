<?php

namespace DeepWebSolutions\Framework\Settings;

use DeepWebSolutions\Framework\Foundations\Services\ServiceInterface;

\defined( 'ABSPATH' ) || exit;

/**
 * Describes an instance of a settings service.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Settings
 */
interface SettingsServiceInterface extends ServiceInterface, SettingsAdapterInterface {
	/* empty on purpose */
}
