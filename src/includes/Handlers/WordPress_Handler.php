<?php

namespace DeepWebSolutions\Framework\Settings\Handlers;

use DeepWebSolutions\Framework\Settings\AbstractSettingsHandler;
use DeepWebSolutions\Framework\Settings\Adapters\WordPress_Adapter;
use DeepWebSolutions\Framework\Settings\SettingsActionsEnum;

\defined( 'ABSPATH' ) || exit;

/**
 * Handles the interoperability layer between the DWS framework and the WordPress Settings API.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Settings\Handlers
 */
class WordPress_Handler extends AbstractSettingsHandler {
	// region MAGIC METHODS

	/**
	 * WordPress Handler constructor.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string                      $handler_id     The ID of the settings handler.
	 * @param   WordPress_Adapter|null      $adapter        Instance of the adapter to the WordPress Settings API.
	 */
	public function __construct( string $handler_id = 'default', ?WordPress_Adapter $adapter = null ) { // phpcs:ignore
		parent::__construct( $handler_id, $adapter ?? new WordPress_Adapter() );
	}

	// endregion

	// region INHERITED METHODS

	/**
	 * Returns the hook on which the WordPress Settings API is ready to be used.
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
			case SettingsActionsEnum::REGISTER_MENU_PAGE:
			case SettingsActionsEnum::REGISTER_SUBMENU_PAGE:
				return 'admin_menu';
			case SettingsActionsEnum::REGISTER_OPTIONS_GROUP:
			case SettingsActionsEnum::REGISTER_FIELD:
				return 'admin_init';
			case SettingsActionsEnum::REGISTER_GENERIC_GROUP:
				return 'add_meta_boxes';
			default:
				return 'init';
		}
	}

	// endregion
}
