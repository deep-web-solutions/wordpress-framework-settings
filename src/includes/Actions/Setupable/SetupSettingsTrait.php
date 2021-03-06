<?php

namespace DeepWebSolutions\Framework\Settings\Actions\Setupable;

use DeepWebSolutions\Framework\Foundations\Actions\Setupable\SetupableExtensionTrait;
use DeepWebSolutions\Framework\Foundations\Actions\Setupable\SetupFailureException;
use DeepWebSolutions\Framework\Settings\SettingsService;
use DeepWebSolutions\Framework\Settings\SettingsServiceAwareInterface;
use DeepWebSolutions\Framework\Settings\SettingsServiceRegisterTrait;
use DeepWebSolutions\Framework\Utilities\DependencyInjection\ContainerAwareInterface;

defined( 'ABSPATH' ) || exit;

/**
 * Trait for registering settings of using instances.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Settings\Actions\Setupable
 */
trait SetupSettingsTrait {
	// region TRAITS

	use SettingsServiceRegisterTrait;
	use SetupableExtensionTrait;

	// endregion

	// region METHODS

	/**
	 * Tries to automagically call the settings service registration method.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  SetupFailureException|null
	 */
	public function setup_settings(): ?SetupFailureException {
		if ( $this instanceof SettingsServiceAwareInterface ) {
			$service = $this->get_settings_service();
		} elseif ( $this instanceof ContainerAwareInterface ) {
			$service = $this->get_container()->get( SettingsService::class );
		} else {
			return new SetupFailureException( 'Settings registration setup scenario not supported' );
		}

		$this->register_settings( $service );
		return null;
	}

	// endregion
}
