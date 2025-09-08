<?php

declare(strict_types = 1);

namespace KeilielOliveira\Container\Validators;

use Exception;

class ParamsValidator {
    private string $class;

    private array $params;

    private \ReflectionClass $reflectionClass;

    private \ReflectionMethod $constructor;

    public function __construct( string $class, array $params ) {
        $this->class = $class;
        $this->params = $params;

        $this->validate();
    }

    private function validate(): void {
        $this->reflectionClass = new \ReflectionClass( $this->class );

        if ( !$this->hasConstructor() ) {
            return;
        }

        $this->constructor = $this->reflectionClass->getConstructor();
        if ( !$this->requireParams() ) { 
            return;
        }

        $this->hasMinAndMaxOfParams();
    }

    private function hasConstructor(): bool {
        return $this->reflectionClass->hasMethod( '__construct' );
    }

    private function requireParams(): bool {
        return $this->constructor->getNumberOfParameters() > 0;
    }

    private function hasMinAndMaxOfParams(): void {
        $current = count($this->params);
        $minimum = $this->constructor->getNumberOfRequiredParameters();
        $maximum = $this->constructor->getNumberOfParameters();

        if($current < $minimum) {
            throw new Exception("O método construtor da classe \"{$this->class}\" requer um mínimo de $minimum parâmetros, e somente $current foram passados.");
        }

        if($current < $maximum) {
            throw new Exception("O método construtor da classe \"{$this->class}\" requer um máximo de $maximum parâmetros, e $current foram passados.");
        }
    }
}
