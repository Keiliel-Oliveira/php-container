<?php

declare(strict_types = 1);

namespace KeilielOliveira\Container;

use KeilielOliveira\Container\Validators\ClassValidator;
use KeilielOliveira\Container\Validators\ParamsValidator;

class Container {
    public function make( string $class ): object {
        new ClassValidator( $class );
        new ParamsValidator( $class, [] );

        $object = new $class;
        return $object;
    }
}
