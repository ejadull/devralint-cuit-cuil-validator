<?php

declare(strict_types=1);

namespace Devralint\Cuit\Tests;

use Devralint\Cuit\Cuit;
use Devralint\Cuit\Exception\InvalidCuitException;
use Devralint\Cuit\PersonaType;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class CuitTest extends TestCase
{
    public function testFromStringBuildsValueObjectForValidCuit(): void
    {
        $cuit = Cuit::fromString('20123456786');

        self::assertSame('20123456786', $cuit->digits);
        self::assertSame('20', $cuit->prefix);
        self::assertSame('12345678', $cuit->body);
        self::assertSame(6, $cuit->checkDigit);
    }

    public function testFromStringAcceptsInputWithSeparators(): void
    {
        $cuit = Cuit::fromString('20-12345678-6');

        self::assertSame('20123456786', $cuit->digits);
    }

    public function testFromStringThrowsForInvalidFormat(): void
    {
        $this->expectException(InvalidCuitException::class);

        Cuit::fromString('2012345678');
    }

    public function testFromStringThrowsForBadCheckDigit(): void
    {
        $this->expectException(InvalidCuitException::class);

        Cuit::fromString('20-12345678-9');
    }

    public function testTryFromReturnsNullForInvalidInput(): void
    {
        self::assertNull(Cuit::tryFrom('invalid'));
    }

    public function testTryFromReturnsInstanceForValidInput(): void
    {
        self::assertInstanceOf(Cuit::class, Cuit::tryFrom('20123456786'));
    }

    public function testFormattedReturnsHyphenatedString(): void
    {
        $cuit = Cuit::fromString('20123456786');

        self::assertSame('20-12345678-6', $cuit->formatted());
    }

    public function testToStringDelegatesToFormatted(): void
    {
        $cuit = Cuit::fromString('20123456786');

        self::assertSame('20-12345678-6', (string) $cuit);
    }

    public function testEqualsReturnsTrueForSameDigits(): void
    {
        $a = Cuit::fromString('20123456786');
        $b = Cuit::fromString('20-12345678-6');

        self::assertTrue($a->equals($b));
    }

    public function testEqualsReturnsFalseForDifferentCuits(): void
    {
        $a = Cuit::fromString('20123456786');
        $b = Cuit::fromString('27123456780');

        self::assertFalse($a->equals($b));
    }

    #[DataProvider('fisicaProvider')]
    public function testIsFisicaDetectsPersonaFisica(string $cuit): void
    {
        self::assertTrue(Cuit::fromString($cuit)->isFisica());
    }

    #[DataProvider('juridicaProvider')]
    public function testIsJuridicaDetectsPersonaJuridica(string $cuit): void
    {
        self::assertTrue(Cuit::fromString($cuit)->isJuridica());
    }

    public function testPersonaTypeIsFisicaForPrefix20(): void
    {
        self::assertSame(PersonaType::Fisica, Cuit::fromString('20123456786')->type);
    }

    /** @return array<string, array{string}> */
    public static function fisicaProvider(): array
    {
        return [
            'prefijo 20' => ['20123456786'],
            'prefijo 27' => ['27123456780'],
            'prefijo 23' => ['23123456785'],
        ];
    }

    /** @return array<string, array{string}> */
    public static function juridicaProvider(): array
    {
        return [
            'prefijo 30' => ['30-71234567-1'],
        ];
    }
}
