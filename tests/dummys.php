<?php

declare(strict_types = 1);

namespace Dummys;

use KeilielOliveira\Container\Container;

class WithoutConstructor {}

class WithConstructor {
    public function __construct() {}
}

class ConstructorWithDefaultParam {
    public function __construct( int $i = 10 ) {}
}

class ConstructorWithObjectParam {
    public function __construct( WithoutConstructor $withoutConstructor ) {}
}

class ConstrutorWithContainerParam {
    public function __construct( Container $container ) {}
}

class ConstructorWithParam {
    public function __construct( int $i ) {}
}
