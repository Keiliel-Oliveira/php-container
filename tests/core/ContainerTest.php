<?php

declare(strict_types = 1);
use KeilielOliveira\Container\Container;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
final class ContainerTest extends TestCase {
    #[DataProvider( 'providerClassToTestMakeMethod' )]
    /**
     * @param class-string $class
     */
    public function testMakeIsCreatingObjectWithoutParams( string $class ): void {
        $container = new Container();
        $object = $container->make( $class );

        $this->assertInstanceOf( $class, $object );
        $this->assertTrue( $object->teste() );
    }

    /**
     * @return class-string[]
     */
    public static function providerClassToTestMakeMethod(): array {
        return [
            'A' => [A::class],
            'B' => [B::class],
        ];
    }

    public function testMakeIsCreatingObjectWithDefaultParams(): void {
        $container = new Container();
        $object = $container->make( D::class );

        $this->assertInstanceOf( D::class, $object );
        $this->assertEquals( 10, $object->a );
        $this->assertTrue( $object->teste() );
    }

    #[DataProvider( 'providerClassToTestMakeWithMethod' )]
    /**
     * @param class-string $class
     * @param int[]        $params
     */
    public function testMakeWithIsCreatingObjectWithReceivedParams( string $class, array $params ): void {
        $container = new Container();
        $object = $container->makeWith( $class, $params );

        $this->assertInstanceOf( $class, $object );
        $this->assertTrue( $object->teste() );
    }

    /**
     * @return array<string, array<array<int>|class-string>>
     */
    public static function providerClassToTestMakeWithMethod(): array {
        return [
            'C' => [
                C::class,
                ['a' => 5],
            ],
            'D' => [
                D::class,
                ['a' => 5],
            ],
        ];
    }

    public function testMakeWithIsSolvingParams(): void {
        $container = new Container();
        $object = $container->makeWith( E::class, ['a' => 5, 'd' => new D()] );

        $this->assertInstanceOf( E::class, $object );
        $this->assertTrue( $object->teste() );
    }

    public function testHasIsReturningTrueWithSavedObject(): void {
        $container = new Container();
        $container->make( A::class );

        $this->assertTrue( $container->has( A::class ) );
    }

    public function testHasIsReturningFalseWithUnsavedObject(): void {
        $container = new Container();

        $this->assertFalse( $container->has( A::class ) );
    }

    public function testGetIsReturningObjectWithSavedObject(): void {
        $container = new Container();
        $container->make( A::class );

        $object = $container->get( A::class );
        $this->assertInstanceOf( A::class, $object );
    }
}
