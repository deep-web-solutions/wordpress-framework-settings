<?php

namespace DeepWebSolutions\Framework\Settings\Factories;

use DeepWebSolutions\Framework\Helpers\WordPress\Requests;
use DeepWebSolutions\Framework\Settings\Abstracts\Handler;
use DeepWebSolutions\Framework\Settings\Handlers\NOOP;
use DeepWebSolutions\Framework\Settings\Handlers\WordPress;
use DeepWebSolutions\Framework\Utilities\Services\LoggingService;
use DeepWebSolutions\Framework\Utilities\Services\Traits\Logging;
use Psr\Log\LogLevel;

defined( 'ABSPATH' ) || exit;

/**
 * Settings handler factory to facilitate clean dependency injection.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Settings\Factories
 */
class HandlerFactory {
	use Logging;

	// region PROPERTIES

	/**
	 * Collection of instantiated handlers.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @access  protected
	 * @var     Handler[]
	 */
	protected array $handlers = array();

	/**
	 * Collection of handler-instantiating callables.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @access  protected
	 * @var     callable[]
	 */
	protected array $callables = array();

	// endregion

	// region MAGIC METHODS

	/**
	 * HandlerFactory constructor.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   LoggingService  $logging_service    Instance of the logging service.
	 */
	public function __construct( LoggingService $logging_service ) {
		$this->set_logging_service( $logging_service );
		$this->handlers['noop']      = new NOOP();
		$this->handlers['wordpress'] = new WordPress();
	}

	// endregion

	// region METHODS

	/**
	 * Registers a new callback with the handler factory for instantiating a new custom handler.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string      $name       The name of the adapter.
	 * @param   callable    $callable   The PHP callback required to instantiate it.
	 */
	public function register_factory_callable( string $name, callable $callable ): void {
		$this->callables[ $name ] = $callable;
	}

	/**
	 * Returns a settings handler.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $name   Name of the adapter. Must match with the name used when registering the callback or with one of the default provided ones.
	 *
	 * @return  Handler
	 */
	public function get_handler( string $name ): Handler {
		if ( ! isset( $this->handlers[ $name ] ) ) {
			$this->handlers[ $name ] = $this->handlers['noop'];
			if ( is_callable( $this->callables[ $name ] ?? '' ) ) {
				$handler = call_user_func( $this->callables[ $name ] );
				if ( $handler instanceof Handler ) {
					$this->handlers[ $name ] = $handler;
				} elseif ( Requests::has_debug() ) {
					$this->get_logging_service()->log_event(
						LogLevel::ERROR,
						"Failed to instantiate valid settings handler {$name}.",
						'framework'
					);
				}
			}
		}

		return $this->handlers[ $name ];
	}

	// endregion
}
