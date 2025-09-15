<?php

declare(strict_types = 1);

namespace KeilielOliveira\Container\Validators;

use KeilielOliveira\Container\ObjectData;

/**
 * @template T of object
 */
class ObjectParamsValidator {
    /**
     * @var class-string<T>|\Closure
     */
    private \Closure|string $object;

    /**
     * @param class-string<T>|\Closure $object
     */
    public function __construct( \Closure|string $object ) {
        $this->object = $object;
    }

    public function objectRequireParams(): bool {
        $object = $this->getReflectionObject();

        if ( $object instanceof \ReflectionFunction ) {
            return $this->requireParams( $object );
        }

        if ( !$this->hasConstructor( $object ) || !$this->requireParams( $object ) ) {
            return false;
        }

        return true;
    }

    public function validateObjectParams( ObjectData $object ): void {
        $requiredParams = $object->getRequiredParams();
        $paramsTypes = $object->getRequiredParamsTypes();
        $receivedParams = $object->getReceivedParams();
        $optionalParams = $object->getOptionalParams();

        foreach ( $requiredParams as $i => $name ) {
            if ( $this->hasRequiredParams( $name, $receivedParams ) ) {
                $this->hasRequiredTypes( $name, $receivedParams, $paramsTypes );

                continue;
            }

            if ( $this->isOptional( $name, $optionalParams ) ) {
                continue;
            }

            $this->canBeSelfResolved( $name, $paramsTypes );
        }
    }

    /**
     * @return \ReflectionClass<T>|\ReflectionFunction
     */
    private function getReflectionObject(): \ReflectionClass|\ReflectionFunction {
        if ( is_callable( $this->object ) ) {
            return new \ReflectionFunction( $this->object );
        }

        return new \ReflectionClass( $this->object );
    }

    /**
     * @param \ReflectionClass<T> $object
     */
    private function hasConstructor( \ReflectionClass $object ): bool {
        return $object->hasMethod( '__construct' );
    }

    /**
     * @param \ReflectionClass<T>|\ReflectionFunction $object
     */
    private function requireParams( \ReflectionClass|\ReflectionFunction $object ): bool {
        $object = $object instanceof \ReflectionClass ? $object->getConstructor() : $object;

        if ( null === $object ) {
            throw new \Exception( 'Ocorreu um erro inesperado ao recuperar o construtor de uma classe.' );
        }

        return $object->getNumberOfParameters() > 0;
    }

    /**
     * @param mixed[] $receivedParams
     */
    private function hasRequiredParams( string $name, array $receivedParams ): bool {
        return isset( $receivedParams[$name] );
    }

    /**
     * @param mixed[]    $receivedParams
     * @param string[][] $paramsTypes
     *
     * @throws \Exception
     */
    private function hasRequiredTypes( string $name, array $receivedParams, array $paramsTypes ): void {
        [$param, $types] = [$receivedParams[$name], $paramsTypes[$name]];

        if ( !in_array( get_debug_type( $param ), $types ) ) {
            throw new \Exception( sprintf( 'O parâmetro %s não possui um dos tipos validos: %s', $name, implode( ', ', $types ) ) );
        }
    }

    /**
     * @param string[] $optionalParams
     */
    private function isOptional( string $name, array $optionalParams ): bool {
        return in_array( $name, $optionalParams );
    }

    /**
     * @param string[][] $paramsTypes
     *
     * @throws \Exception
     */
    private function canBeSelfResolved( string $name, array $paramsTypes ): void {
        $types = $paramsTypes[$name];
        $types = array_filter( $types, function ( string $type ): bool {
            return !class_exists( $type );
        } );

        if ( !empty( $types ) ) {
            throw new \Exception( sprintf( 'O parâmetro %s não é de um tipo que pode ser auto resolvido.', $name ) );
        }
    }
}
