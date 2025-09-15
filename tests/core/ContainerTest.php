<?php

declare(strict_types = 1);
use Dummys\ConstructorWithDefaultParam;
use Dummys\ConstructorWithObjectParam;
use Dummys\ConstructorWithParam;
use Dummys\ConstrutorWithContainerParam;
use Dummys\WithConstructor;
use Dummys\WithoutConstructor;
use KeilielOliveira\Container\Container;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
final class ContainerTest extends TestCase {
    #[DataProvider( 'providerDummyClassToTestMakeMethod' )]
    /**
     * @param class-string $class
     */
    public function testMakeIsCreatingObjectWithoutParams( string $class ): void {
        $container = new Container();
        $object = $container->make( $class );

        $this->assertInstanceOf( $class, $object );
    }

    /**
     * @return array<string, class-string[]>
     */
    public static function providerDummyClassToTestMakeMethod(): array {
        return [
            'sem construtor' => [
                WithoutConstructor::class,
            ],
            'com construtor' => [
                WithConstructor::class,
            ],
            'parâmetro com valor pre-definido' => [
                ConstructorWithDefaultParam::class,
            ],
            'classes como parâmetro' => [
                ConstructorWithObjectParam::class,
            ],
            'contêiner como parâmetro' => [
                ConstrutorWithContainerParam::class,
            ],
        ];
    }

    #[DataProvider( 'providerDummyClassToTestMakeWithMethod' )]
    /**
     * @param class-string $class
     * @param int[]        $params
     */
    public function testMakeWithIsCreatingObjectWithParams( string $class, array $params ): void {
        $container = new Container();
        $object = $container->makeWith( $class, $params );

        $this->assertInstanceOf( $class, $object );
    }

    /**
     * @return array<string, array{0: class-string, 1: int[]}>
     */
    public static function providerDummyClassToTestMakeWithMethod(): array {
        return [
            'parâmetro com valor pre-definido' => [
                ConstructorWithDefaultParam::class,
                ['i' => 5],
            ],
            'parâmetro sem valor pre-definido' => [
                ConstructorWithParam::class,
                ['i' => 5],
            ],
        ];
    }
}
