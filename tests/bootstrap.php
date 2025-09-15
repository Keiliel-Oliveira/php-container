<?php

require_once 'vendor/autoload.php';

class A {
    public function teste(): bool {
        return true;
    }
}

class B {
    public function __construct() {}

    public function teste(): bool {
        return true;
    }
}

class C {
    public int $a;

    public function __construct( int $a ) {
        $this->a = $a;
    }

    public function teste(): bool {
        return true;
    }
}

class D {
    public int $a;

    public function __construct( int $a = 10 ) {
        $this->a = $a;
    }

    public function teste(): bool {
        return true;
    }
}

class E {
    public int $a;

    public function __construct( int $a, B $b, D $d ) {
        $this->a = $a;
    }

    public function teste(): bool {
        return true;
    }
}
