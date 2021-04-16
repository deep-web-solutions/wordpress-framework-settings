<?php

namespace DeepWebSolutions\Framework\Settings\Handlers;

use DeepWebSolutions\Framework\Settings\AbstractSettingsHandler;
use DeepWebSolutions\Framework\Settings\Adapters\ACF_Adapter;
use DeepWebSolutions\Framework\Settings\SettingsActionsEnum;

\defined( 'ABSPATH' ) || exit;

/**
 * Handles the interoperability layer between the DWS framework and the ACF settings framework.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Settings\Handlers
 */
class ACF_Handler extends AbstractSettingsHandler {
	// region MAGIC METHODS

	/**
	 * ACF Handler constructor.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string              $handler_id     The ID of the settings handler.
	 * @param   ACF_Adapter|null    $adapter        Instance of the adapter to the ACF settings framework.
	 */
	public function __construct( string $handler_id = 'acf', ?ACF_Adapter $adapter = null ) { // phpcs:ignore
		parent::__construct( $handler_id, $adapter ?? new ACF_Adapter() );
	}

	// endregion

	// region INHERITED METHODS

	/**
	 * Returns the hook on which the ACF framework is ready to be used.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $context    The settings action that is to be performed.
	 *
	 * @return  string
	 */
	public function get_action_hook( string $context ): string {
		switch ( $context ) {
			case SettingsActionsEnum::REGISTER_FIELD:
				return 'acf/include_fields';
			default:
				return 'acf/init';
		}
	}

	// endregion
}
