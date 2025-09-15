<?php

declare(strict_types = 1);

namespace KeilielOliveira\Container\Validators;

class ObjectValidator {
    private \Closure|string $object;

    public function __construct( \Closure|string $object ) {
        $this->object = $object;
    }

    public function validate(): void {
        $this->isValidObject();
    }

    private function isValidObject(): void {
        if ( !$this->object instanceof \Closure && !class_exists( $this->object ) ) {
            throw new \Exception( 'Um objeto recebido não é uma classe ou função valida.' );
        }
    }
}
