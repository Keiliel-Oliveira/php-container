<?php

declare(strict_types = 1);

namespace KeilielOliveira\Container\Factorys;

use KeilielOliveira\Container\Container;
use KeilielOliveira\Container\ObjectData;

class ObjectFactory {
    private ObjectData $object;

    private Container $container;

    public function __construct( ObjectData $object, Container $container ) {
        $this->object = $object;
        $this->container = $container;
    }

    public function handler(): mixed {
        $params = $this->prepareParams();

        if ( 'function' == $this->object->getObjectType() ) {
            return $this->executeObject( $params );
        }

        return $this->createObject( $params );
    }

    /**
     * @return mixed[]
     */
    private function prepareParams(): array {
        $requiredParams = $this->object->getRequiredParams();
        $receivedParams = $this->object->getReceivedParams();
        $optionalParams = $this->object->getOptionalParams();
        $defaultValues = $this->object->getDefaultValues();

        $preparedParams = [];
        foreach ( $requiredParams as $i => $name ) {
            if ( isset( $receivedParams[$name] ) ) {
                $preparedParams[] = $receivedParams[$name];

                continue;
            }

            if ( in_array( $name, $optionalParams ) ) {
                $preparedParams[] = $defaultValues[$name];

                continue;
            }

            $preparedParams[] = $this->getParam( $name );
        }

        return $preparedParams;
    }

    private function getParam( string $name ): mixed {
        $paramsTypes = $this->object->getRequiredParamsTypes();

        foreach ( $paramsTypes[$name] as $i => $type ) {
            if ( class_exists( $type ) && $this->container->has( $type ) ) {
                return $this->container->get( $type );
            }
        }

        foreach ( $paramsTypes[$name] as $i => $type ) {
            try {
                if ( class_exists( $type ) ) {
                    return $this->container->make( $type );
                }
            } catch ( \Exception $e ) {
                continue;
            }
        }

        throw new \Exception( sprintf( 'Não foi possível alto resolver o parâmetro %s.', $name ) );
    }

    /**
     * @param mixed[] $params
     */
    private function executeObject( array $params ): mixed {
        /** @var \Closure */
        $object = $this->object->getObject();

        return $object( ...$params );
    }

    /**
     * @param mixed[] $params
     */
    private function createObject( array $params ): object {
        $object = $this->object->getObject();

        /** @var class-string $object */
        if ( !new \ReflectionClass( $object )->hasMethod( '__construct' ) ) {
            return new $object();
        }

        return new $object( ...$params );
    }
}
