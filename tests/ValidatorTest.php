<?php

declare(strict_types=1);

namespace Devralint\Cuit\Tests;

use Devralint\Cuit\Validator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class ValidatorTest extends TestCase
{
    #[DataProvider('validCuits')]
    public function testIsValidReturnsTrueForValidCuits(string $input): void
    {
        self::assertTrue(Validator::isValid($input));
    }

    #[DataProvider('invalidCuits')]
    public function testIsValidReturnsFalseForInvalidCuits(string $input): void
    {
        self::assertFalse(Validator::isValid($input));
    }

    #[DataProvider('normalizeProvider')]
    public function testNormalizeStripsNonDigits(string $input, string $expected): void
    {
        self::assertSame($expected, Validator::normalize($input));
    }

    public function testCheckDigitEdgeCaseRemainder11ProducesZero(): void
    {
        // All-zero digits: sum=0, 0%11=0, 11-0=11 → maps to 0.
        self::assertSame(0, Validator::checkDigit('00000000000'));
    }

    /** @return array<string, array{string}> */
    public static function validCuits(): array
    {
        return [
            'fisica sin guiones'     => ['20123456786'],
            'fisica con guiones'     => ['20-12345678-6'],
            'fisica con espacios'    => ['20 12345678 6'],
            'juridica'               => ['30-71234567-1'],
            'prefijo 23'             => ['23123456785'],
            'prefijo 27'             => ['27123456780'],
        ];
    }

    /** @return array<string, array{string}> */
    public static function invalidCuits(): array
    {
        return [
            'dv incorrecto'          => ['20-12345678-9'],
            'solo 10 digitos'        => ['2012345678'],
            '12 digitos'             => ['201234567890'],
            'vacio'                  => [''],
            'con letras en cuerpo'   => ['20-1234567A-6'],
            'solo letras'            => ['abcdefghijk'],
        ];
    }

    /** @return array<string, array{string, string}> */
    public static function normalizeProvider(): array
    {
        return [
            'con guiones'   => ['20-12345678-6', '20123456786'],
            'con espacios'  => ['20 12345678 6', '20123456786'],
            'sin separador' => ['20123456786',   '20123456786'],
            'vacio'         => ['',              ''],
        ];
    }
}
