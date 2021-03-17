<?php

namespace DeepWebSolutions\Framework\Settings\Actions;

use GuzzleHttp\Promise\PromiseInterface;

\defined( 'ABSPATH' ) || exit;

/**
 * Wrapper for a setting action's response.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Settings\Actions
 */
class SettingsActionResponse {
	// region FIELDS AND CONSTANTS

	/**
	 * The action's return value if called at an appropriate time.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @var     mixed|null
	 */
	protected $return = null;

	/**
	 * The promise of a return if action called to early.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @var     PromiseInterface|null
	 */
	protected ?PromiseInterface $promise = null;

	// endregion

	// region MAGIC METHODS

	/**
	 * ActionResponse constructor.
	 *
	 * @param   mixed|null              $return_value   The return value of the action.
	 * @param   PromiseInterface|null   $promise        The promise to fulfill the action at a later point in time.
	 */
	public function __construct( $return_value = null, ?PromiseInterface $promise = null ) {
		$this->return  = $return_value;
		$this->promise = $promise;

		if ( ! \is_null( $this->promise ) ) {
			$this->promise->then(
				function( $value ) {
					$this->return = $value;
				}
			);
		}
	}

	// endregion

	// region METHODS

	/**
	 * Returns whether the action has been fulfilled and the result is available.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  bool
	 */
	public function is_resolved(): bool {
		return \is_null( $this->promise ) || ( PromiseInterface::FULFILLED === $this->promise->getState() );
	}

	/**
	 * Returns either the action's return value or the promise, if not resolved yet.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @noinspection PhpMissingReturnTypeInspection
	 * @return  PromiseInterface|mixed|null
	 */
	public function unwrap() {
		return $this->is_resolved()
			? $this->return
			: $this->promise;
	}

	// endregion
}
