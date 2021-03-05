<?php

namespace DeepWebSolutions\Framework\Settings\Validation;

use DeepWebSolutions\Framework\Foundations\Exceptions\InexistentPropertyException;
use DeepWebSolutions\Framework\Helpers\DataTypes\Arrays;
use DeepWebSolutions\Framework\Helpers\Security\Validation;
use DI\ContainerBuilder;
use Exception;
use Psr\Container\ContainerInterface;

defined( 'ABSPATH' ) || exit;

/**
 * Validates that a setting's value is good to use.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 * @package DeepWebSolutions\WP-Framework\Settings\Validation
 */
class ValidationService {
	// region FIELDS AND CONSTANTS

	/**
	 * Container for storing supported settings options.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @var     ContainerInterface
	 */
	protected ContainerInterface $supported_options;

	/**
	 * Container for storing the default settings' values.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @var     ContainerInterface
	 */
	protected ContainerInterface $default_values;

	// endregion

	// region MAGIC METHODS

	/**
	 * ValidatorService constructor.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   array   $options_config     An array containing either absolute paths to config files, an array of definitions, or DefinitionSource objects.
	 * @param   array   $defaults_config    An array containing either absolute paths to config files, an array of definitions, or DefinitionSource objects.
	 *
	 * @throws  Exception   Thrown when the container builder fails to build the container.
	 */
	public function __construct( array $options_config, array $defaults_config ) {
		$container_builder = new ContainerBuilder();
		foreach ( $options_config as $definitions ) {
			$container_builder->addDefinitions( $definitions );
		}
		// $this->supported_options = $container_builder->build();

		$container_builder = new ContainerBuilder();
		foreach ( $defaults_config as $definitions ) {
			$container_builder->addDefinitions( $definitions );
		}
		// $this->default_values = $container_builder->build();
	}

	// endregion

	// region METHODS

	/**
	 * Validates a given value as a boolean.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   mixed   $value  The value to validate.
	 * @param   string  $key    The composite key to retrieve the default value.
	 *
	 * @throws  InexistentPropertyException     Thrown when the default value was not found inside the container.
	 *
	 * @return  bool
	 */
	public function validate_boolean_value( $value, string $key ): bool {
		$default = $this->get_default_value_or_throw( $key );
		return Validation::validate_boolean( $value, $default );
	}

	/**
	 * Validates a given value as an int.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   mixed   $value  The value to validate.
	 * @param   string  $key    The composite key to retrieve the default value.
	 *
	 * @throws  InexistentPropertyException     Thrown when the default value was not found inside the container.
	 *
	 * @return  int
	 */
	public function validate_integer_value( $value, string $key ): int {
		$default = $this->get_default_value_or_throw( $key );
		return Validation::validate_integer( $value, $default );
	}

	/**
	 * Validates a given value as a float.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   mixed   $value  The value to validate.
	 * @param   string  $key    The composite key to retrieve the default value.
	 *
	 * @throws  InexistentPropertyException     Thrown when the default value was not found inside the container.
	 *
	 * @return  float
	 */
	public function validate_float_value( $value, string $key ): float {
		$default = $this->get_default_value_or_throw( $key );
		return Validation::validate_float( $value, $default );
	}

	/**
	 * Validates a given value as a callable.
	 *
	 * @param   mixed   $value  The value to validate.
	 * @param   string  $key    The composite key to retrieve the default value.
	 *
	 * @throws  InexistentPropertyException     Thrown when the default value was not found inside the container.
	 *
	 * @return  callable
	 */
	public function validate_callback_value( $value, string $key ): callable {
		$default = $this->get_default_value_or_throw( $key );
		return Validation::validate_callback( $value, $default );
	}

	/**
	 * Validates a given value as a valid option.
	 *
	 * @param   mixed   $value          The value to validate.
	 * @param   string  $options_key    The composite key to retrieve the supported values.
	 * @param   string  $default_key    The composite key to retrieve the default value.
	 *
	 * @throws  InexistentPropertyException     Thrown when the default value or the supported values were not found inside the containers.
	 *
	 * @return  mixed
	 */
	public function validate_supported_value( $value, string $options_key, string $default_key ) {
		$default          = $this->get_default_value_or_throw( $default_key );
		$supported_values = $this->get_supported_options_or_throw( $options_key );

		if ( Arrays::has_string_keys( $supported_values ) ) {
			$supported_values = array_keys( $supported_values );
		}

		return Validation::validate_allowed_value( $value, $supported_values, $default );
	}

	// endregion

	// region GETTERS

	/**
	 * Retrieves the default value for a given key.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $key    The key inside the container.
	 *
	 * @noinspection PhpMissingReturnTypeInspection
	 * @return  InexistentPropertyException|mixed
	 */
	public function get_default_value( string $key ) {
		return $this->get_container_value( $this->default_values, $key );
	}

	/**
	 * Retrieves a list of all default values.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  array
	 */
	public function get_known_default_values(): array {
		return $this->default_values->getKnownEntryNames();
	}

	/**
	 * Retrieves the supported options for a given key.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $key    The key inside the container.
	 *
	 * @return  InexistentPropertyException|array
	 */
	public function get_supported_options( string $key ) {
		return $this->get_container_value( $this->supported_options, $key );
	}

	/**
	 * Retrieves a list of all supported options.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @return  array
	 */
	public function get_known_supported_options(): array {
		return $this->supported_options->getKnownEntryNames();
	}

	// endregion

	// region SETTERS

	/**
	 * Sets/overwrites a value inside the default values container.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $key    The key under which the value should be saved.
	 * @param   mixed   $value  The value to save.
	 *
	 * @return  void
	 */
	public function set_default_value( string $key, $value ): void {
		$this->default_values->set( $key, $value );
	}

	/**
	 * Sets/overwrites a value inside the supported options container.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $key        The key under which the options should be saved.
	 * @param   array   $options    The options to save.
	 *
	 * @return  void
	 */
	public function set_supported_options( string $key, array $options ): void {
		$this->default_values->set( $key, $options );
	}

	// endregion

	// region HELPERS

	/**
	 * Retrieves a value from a given container.
	 *
	 * @param   ContainerInterface      $container      The container to retrieve the value from.
	 * @param   string                  $key            Composite key of the value to retrieve.
	 *
	 * @noinspection PhpMissingReturnTypeInspection
	 * @return  InexistentPropertyException|mixed
	 */
	protected function get_container_value( ContainerInterface $container, string $key ) {
		$boom = explode( '/', $key );
		$key  = array_shift( $boom );

		if ( $container->has( $key ) ) {
			$value = $container->get( $key );

			foreach ( $boom as $key ) {
				if ( isset( $value[ $key ] ) ) {
					$value = $value[ $key ];
				} else {
					return new InexistentPropertyException();
				}
			}

			return $value;
		} else {
			return new InexistentPropertyException();
		}
	}

	/**
	 * Retrieves the default value for a given key or throws the exception if not found.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $key    The key inside the container.
	 *
	 * @throws  InexistentPropertyException     Thrown when the default value or the supported values were not found inside the containers.
	 *
	 * @return  mixed
	 */
	protected function get_default_value_or_throw( string $key ) {
		$default = $this->get_default_value( $key );
		if ( $default instanceof InexistentPropertyException ) {
			throw $default;
		}

		return $default;
	}

	/**
	 * Retrieves the supported options for a given key or throws the exception if not found.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $key    The key inside the container.
	 *
	 * @throws  InexistentPropertyException     Thrown when the default value or the supported values were not found inside the containers.
	 *
	 * @return  array
	 */
	protected function get_supported_options_or_throw( string $key ): array {
		$options = $this->get_supported_options( $key );
		if ( $options instanceof InexistentPropertyException ) {
			throw $options;
		}

		return $options;
	}

	// endregion
}