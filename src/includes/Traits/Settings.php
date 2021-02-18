<?php

namespace DeepWebSolutions\Framework\Settings\Traits;

use DeepWebSolutions\Framework\Core\Interfaces\Actions\Exceptions\SetupFailure;
use DeepWebSolutions\Framework\Core\Interfaces\Actions\Traits\Setupable\Setupable;
use DeepWebSolutions\Framework\Settings\Services\SettingsService;
use DeepWebSolutions\Framework\Settings\Services\Traits\Settings as SettingsTrait;

defined( 'ABSPATH' ) || exit;

/**
 * Functionality trait for registering settings of active instances.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Settings\Traits
 */
trait Settings {
	use SettingsTrait;
	use Setupable;

	/**
	 * Automagically call the settings registration method.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   SettingsService     $setting_service    Instance of the settings service.
	 *
	 * @return  SetupFailure|null
	 */
	public function setup_settings( SettingsService $setting_service ): ?SetupFailure {
		$this->set_settings_service( $setting_service );
		$this->register_settings( $setting_service );
		return null;
	}
}
