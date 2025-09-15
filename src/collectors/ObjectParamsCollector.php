<?php

declare(strict_types = 1);

namespace KeilielOliveira\Container\Collectors;

use KeilielOliveira\Container\ObjectData;

class ObjectParamsCollector {
    private ObjectData $object;

    public function __construct( ObjectData $object ) {
        $this->object = $object;
    }

    public function collect(): void {
        $object = 'class' == $this->object->getObjectType()
        ? new \ReflectionClass( $this->object->getObject() )->getConstructor()
        : new \ReflectionFunction( $this->object->getObject() );

        if ( null === $object ) {
            throw new \Exception( 'Ocorreu um erro inesperado ao recuperar o construtor de uma classe.' );
        }

        $this->collectConstructorParams( $object );
    }

    private function collectConstructorParams( \ReflectionFunction|\ReflectionMethod $object ): void {
        $objectParams = $object->getParameters();

        $requiredParams = [];
        $requiredParamsTypes = [];
        $optionalParams = [];
        $defaultValues = [];
        foreach ( $objectParams as $i => $param ) {
            $name = $param->getName();
            $requiredParams[] = $name;

            $type = $param->getType();
            if ( null === $type ) {
                $requiredParamsTypes[$name] = ['mixed'];
            } else {
                $requiredParamsTypes[$name] = $this->getParamTypes( $name, $type );
            }

            if ( $param->isOptional() ) {
                $optionalParams[] = $name;
                $defaultValues[$name] = $param->getDefaultValue();
            }
        }

        // @var string[][] $requiredParamsTypes
        $this->object->setRequiredParams( $requiredParams );
        $this->object->setRequiredParamsTypes( $requiredParamsTypes );
        $this->object->setOptionalParams( $optionalParams );
        $this->object->setDefaultValues( $defaultValues );
    }

    /**
     * @return string[]
     */
    private function getParamTypes( string $name, \ReflectionType $type ): array {
        if ( $type instanceof \ReflectionNamedType ) {
            return [$type->getName()];
        }

        /** @var \ReflectionIntersectionType|\ReflectionUnionType $type */
        $paramTypes = [];
        foreach ( $type->getTypes() as $i => $currentType ) {
            $currentType = $this->getParamTypes( $name, $currentType );
            $paramTypes = array_merge( $paramTypes, $currentType );
        }

        return $paramTypes;
    }
}
