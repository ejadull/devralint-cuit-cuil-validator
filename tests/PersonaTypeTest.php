<?php

declare(strict_types=1);

namespace Devralint\Cuit\Tests;

use Devralint\Cuit\PersonaType;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class PersonaTypeTest extends TestCase
{
    #[DataProvider('fisicaPrefixes')]
    public function testFromPrefixReturnsFisicaForKnownPrefixes(string $prefix): void
    {
        self::assertSame(PersonaType::Fisica, PersonaType::fromPrefix($prefix));
    }

    #[DataProvider('juridicaPrefixes')]
    public function testFromPrefixReturnsJuridicaForKnownPrefixes(string $prefix): void
    {
        self::assertSame(PersonaType::Juridica, PersonaType::fromPrefix($prefix));
    }

    #[DataProvider('unknownPrefixes')]
    public function testFromPrefixReturnsOtroForUnknownPrefixes(string $prefix): void
    {
        self::assertSame(PersonaType::Otro, PersonaType::fromPrefix($prefix));
    }

    /** @return array<string, array{string}> */
    public static function fisicaPrefixes(): array
    {
        return [
            'prefijo 20' => ['20'],
            'prefijo 23' => ['23'],
            'prefijo 24' => ['24'],
            'prefijo 25' => ['25'],
            'prefijo 26' => ['26'],
            'prefijo 27' => ['27'],
        ];
    }

    /** @return array<string, array{string}> */
    public static function juridicaPrefixes(): array
    {
        return [
            'prefijo 30' => ['30'],
            'prefijo 33' => ['33'],
            'prefijo 34' => ['34'],
        ];
    }

    /** @return array<string, array{string}> */
    public static function unknownPrefixes(): array
    {
        return [
            'prefijo 00' => ['00'],
            'prefijo 99' => ['99'],
            'prefijo 50' => ['50'],
        ];
    }
}
