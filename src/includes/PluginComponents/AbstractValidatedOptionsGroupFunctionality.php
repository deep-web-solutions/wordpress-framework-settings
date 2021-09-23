<?php

namespace DeepWebSolutions\Framework\Settings\PluginComponents;

use DeepWebSolutions\Framework\Helpers\DataTypes\Strings;
use DeepWebSolutions\Framework\Utilities\Actions\Initializable\InitializeCachingServiceTrait;
use DeepWebSolutions\Framework\Utilities\Actions\Initializable\InitializeValidationServiceTrait;
use DeepWebSolutions\Framework\Utilities\Caching\CachingServiceAwareInterface;
use DeepWebSolutions\Framework\Utilities\Hooks\HooksService;
use DeepWebSolutions\Framework\Utilities\Validation\ValidationServiceAwareInterface;
use DeepWebSolutions\Framework\Utilities\Validation\ValidationServiceAwareTrait;

\defined( 'ABSPATH' ) || exit;

/**
 * Template for standardizing the registration of an options group with validated retrieval.
 *
 * @SuppressWarnings(PHPMD.LongClassName)
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Settings\PluginComponents
 */
abstract class AbstractValidatedOptionsGroupFunctionality extends AbstractOptionsGroupFunctionality implements CachingServiceAwareInterface, ValidationServiceAwareInterface {
	// region TRAITS

	use InitializeCachingServiceTrait;
	use InitializeValidationServiceTrait;
	use ValidationServiceAwareTrait {
		get_default_value as protected get_default_value_trait;
		get_supported_options as protected get_supported_options_trait;
		validate_value as protected validate_value_trait;
		validate_allowed_value as protected validate_allowed_value_trait;
	}

	// endregion

	// region INHERITED METHODS

	/**
	 * {@inheritDoc}
	 */
	public function register_hooks( HooksService $hooks_service ): void {
		parent::register_hooks( $hooks_service );

		$hooks_service->add_filter( $this->get_parent()->get_hook_tag( 'get_validated_option_value' ), $this, 'maybe_get_validated_option_value', 10, 2 );
		$hooks_service->add_filter( $this->get_parent()->get_hook_tag( 'validate_option_value' ), $this, 'maybe_validate_option_value', 10, 2 );
	}

	/**
	 * {@inheritDoc}
	 *
	 * @noinspection PhpParameterNameChangedDuringInheritanceInspection
	 */
	public function get_default_value( string $field_id, string $handler_id = 'default' ) {
		return $this->get_default_value_trait( $this->generate_validation_key( $field_id ), $handler_id );
	}

	/**
	 * {@inheritDoc}
	 *
	 * @noinspection PhpParameterNameChangedDuringInheritanceInspection
	 */
	public function get_supported_options( string $field_id, string $handler_id = 'default' ) {
		return $this->get_supported_options_trait( $this->generate_validation_key( $field_id ), $handler_id );
	}

	/**
	 * {@inheritDoc}
	 *
	 * @noinspection PhpParameterNameChangedDuringInheritanceInspection
	 */
	protected function validate_value( $value, string $field_id, string $validation_type, string $handler_id = 'default' ) {
		return $this->validate_value_trait( $value, $this->generate_validation_key( $field_id ), $validation_type, $handler_id );
	}

	/**
	 * {@inheritDoc}
	 *
	 * @noinspection PhpParameterNameChangedDuringInheritanceInspection
	 */
	protected function validate_allowed_value( $value, string $field_id, string $options_key, string $validation_type, string $handler_id = 'default' ) {
		return $this->validate_allowed_value_trait( $value, $this->generate_validation_key( $field_id ), $this->generate_validation_key( $options_key ), $validation_type, $handler_id );
	}

	// endregion

	// region METHODS

	/**
	 * Returns the composite key needed to pass on to the validation service in order to find the entries pertaining
	 * to a given field.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $field_id   The ID of the options field.
	 *
	 * @return  string
	 */
	public function generate_validation_key( string $field_id ): string {
		return "{$this->get_group_name()}/$field_id";
	}

	/**
	 * Retrieves an option's value and validates it.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $field_id   The ID of the options field to retrieve.
	 *
	 * @return  mixed
	 */
	public function get_validated_option_value( string $field_id ) {
		$found = null;
		$value = $this->get_cache_value( "{$this->get_parent()->get_page_slug()}/{$this->get_group_name()}/$field_id", $found );

		if ( false === $found ) {
			$value = $this->get_option_value( $field_id );
			$value = $this->validate_option_value( $value, $field_id );

			$this->set_cache_value( "{$this->get_parent()->get_page_slug()}/{$this->get_group_name()}/$field_id", $value );
		}

		return \apply_filters( $this->get_hook_tag( 'get_validated_option_value' ), $value, $field_id );
	}

	/**
	 * Validates a value assumed to belong to the given field.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   mixed   $value      Value to validate.
	 * @param   string  $field_id   The ID of the options field assumed that the value belongs to.
	 *
	 * @return  mixed
	 */
	abstract public function validate_option_value( $value, string $field_id );

	// endregion

	// region HOOKS

	/**
	 * Retrieves an option's value that was queried via the page component and runs it through a validation callback.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   null|mixed  $value      The value so far.
	 * @param   string      $field_id   The prefixed field ID.
	 *
	 * @return  mixed
	 */
	public function maybe_get_validated_option_value( $value, string $field_id ) {
		$return = $value;

		if ( \is_null( $return ) ) {
			$field_prefix = Strings::maybe_suffix( $this->get_group_name(), '/' );
			if ( Strings::starts_with( $field_id, $field_prefix ) ) {
				$return = $this->get_validated_option_value( Strings::maybe_unprefix( $field_id, $field_prefix ) );
			}
		}

		return $return;
	}

	/**
	 * Validates a given value assuming it belongs to the given field it and that the validation was triggered via the
	 * page component.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   mixed   $value      The value to validate.
	 * @param   string  $field_id   The prefixed field ID.
	 *
	 * @return  mixed
	 */
	public function maybe_validate_option_value( $value, string $field_id ) {
		$field_prefix = Strings::maybe_suffix( $this->get_group_name(), '/' );
		if ( Strings::starts_with( $field_id, $field_prefix ) ) {
			$value = $this->validate_option_value( $value, Strings::maybe_unprefix( $field_id, $field_prefix ) );
		}

		return $value;
	}

	// endregion
}
