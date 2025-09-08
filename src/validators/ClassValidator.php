<?php

declare(strict_types = 1);

namespace KeilielOliveira\Container\Validators;

class ClassValidator {
    private string $class;

    /**
     * @phpstan-assert class-string $class
     */
    public function __construct( string $class ) {
        $this->class = $class;

        $this->validate();
    }

    private function validate(): void {
        $this->isValidClass();
    }

    private function isValidClass(): void {
        if ( !class_exists( $this->class ) ) {
            throw new \Exception( "Não existe uma classe \"{$this->class}\"" );
        }
    }
}
