<?php

declare(strict_types = 1);

namespace KeilielOliveira\Container\Mappers;

use KeilielOliveira\Container\Collectors\ObjectParamsCollector;
use KeilielOliveira\Container\ObjectData;
use KeilielOliveira\Container\Validators\ObjectParamsValidator;
use KeilielOliveira\Container\Validators\ObjectValidator;

/**
 * @template T of object
 */
class ObjectMapper {
    /** @var class-string<T>|\Closure */
    private \Closure|string $object;

    private ObjectData $objectData;

    /**
     * @var ObjectParamsValidator<T>
     */
    private ObjectParamsValidator $objectParamsValidator;

    /**
     * @param callable|class-string<T> $object
     * @param mixed[]                  $params
     */
    public function __construct( callable|string $object, array $params ) {
        $this->object = is_callable( $object ) ? \Closure::fromCallable( $object ) : $object;

        $this->objectData = new ObjectData();
        $this->objectData->setReceivedParams( $params );

        $this->objectParamsValidator = new ObjectParamsValidator( $this->object );
    }

    public function map(): ObjectData {
        $this->mapObjectType();
        $this->mapObjectParams();

        return $this->objectData;
    }

    private function mapObjectType(): void {
        new ObjectValidator( $this->object )->validate();

        $this->objectData->setObject( $this->object );
        $this->objectData->setObjectType( is_string( $this->object ) ? 'class' : 'function' );
    }

    private function mapObjectParams(): void {
        if ( !$this->objectParamsValidator->objectRequireParams() ) {
            return;
        }

        $collector = new ObjectParamsCollector( $this->objectData );
        $collector->collect();

        $this->objectParamsValidator->validateObjectParams( $this->objectData );
    }
}
