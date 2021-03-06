<?php

namespace DeepWebSolutions\Framework\Settings\Actions\Initializable;

use DeepWebSolutions\Framework\Foundations\Actions\Initializable\InitializableExtensionTrait;
use DeepWebSolutions\Framework\Foundations\Actions\Initializable\InitializationFailureException;
use DeepWebSolutions\Framework\Foundations\Hierarchy\ChildInterface;
use DeepWebSolutions\Framework\Settings\SettingsService;
use DeepWebSolutions\Framework\Settings\SettingsServiceAwareInterface;
use DeepWebSolutions\Framework\Settings\SettingsServiceAwareTrait;
use DeepWebSolutions\Framework\Utilities\DependencyInjection\ContainerAwareInterface;

defined( 'ABSPATH' ) || exit;

/**
 * Trait for setting the settings service on the using instance.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Settings\Actions\Initializable
 */
trait InitializeSettingsServiceTrait {
	// region TRAITS

	use SettingsServiceAwareTrait;
	use InitializableExtensionTrait;

	// endregion

	// region METHODS

	/**
	 * Try to automagically set a settings service on the instance.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  InitializationFailureException|null
	 */
	public function initialize_assets_service(): ?InitializationFailureException {
		if ( $this instanceof ChildInterface && $this->get_parent() instanceof SettingsServiceAwareInterface ) {
			/* @noinspection PhpUndefinedMethodInspection */
			$service = $this->get_parent()->get_settings_service();
		} elseif ( $this instanceof ContainerAwareInterface ) {
			$service = $this->get_container()->get( SettingsService::class );
		} else {
			return new InitializationFailureException( 'Settings service initialization scenario not supported' );
		}

		$this->set_settings_service( $service );
		return null;
	}

	// endregion
}
