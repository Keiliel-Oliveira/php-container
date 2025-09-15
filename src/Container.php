<?php

declare(strict_types = 1);

namespace KeilielOliveira\Container;

use KeilielOliveira\Container\Factorys\ObjectFactory;
use KeilielOliveira\Container\Mappers\ObjectMapper;

class Container {
    /** @var array<class-string, object> */
    private array $objects = [];

    /**
     * Cria um objeto de determinada classe ou executa uma função com os parâmetros passados.
     *
     * @param callable|class-string $object
     * @param mixed[]               $params
     */
    public function makeWith( callable|string $object, array $params ): mixed {
        $mapper = new ObjectMapper( $object, $params );
        $object = $mapper->map();

        $factory = new ObjectFactory( $object, $this );
        $object = $factory->handler();

        if ( is_object( $object ) ) {
            $this->objects[$object::class] = $object;
        }

        return $object;
    }

    /**
     * @param callable|class-string $object
     */
    public function make( callable|string $object ): mixed {
        return $this->makeWith( $object, [] );
    }

    /**
     * @param class-string $object
     */
    public function has( string $object ): bool {
        return isset( $this->objects[$object] );
    }

    /**
     * Summary of get.
     *
     * @param class-string $object
     *
     * @throws \Exception
     */
    public function get( string $object ): object {
        if ( !$this->has( $object ) ) {
            throw new \Exception( sprintf( 'Não há um objeto "%s" salvo no contêiner.', $object ) );
        }

        return $this->objects[$object];
    }
}
