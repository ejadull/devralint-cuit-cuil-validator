<?php

declare(strict_types=1);

namespace Devralint\Cuit;

use Devralint\Cuit\Exception\InvalidCuitException;
use Stringable;

final readonly class Cuit implements Stringable
{
    public readonly string $digits;
    public readonly string $prefix;
    public readonly string $body;
    public readonly int $checkDigit;
    public readonly PersonaType $type;

    private function __construct(string $normalized)
    {
        $this->digits = $normalized;
        $this->prefix = substr($normalized, 0, 2);
        $this->body = substr($normalized, 2, 8);
        $this->checkDigit = (int) $normalized[10];
        $this->type = PersonaType::fromPrefix($this->prefix);
    }

    public static function fromString(string $value): self
    {
        if (!Validator::hasValidFormat($value)) {
            throw InvalidCuitException::format($value);
        }

        $normalized = Validator::normalize($value);

        if (!Validator::isValid($normalized)) {
            throw InvalidCuitException::checkDigit($value);
        }

        return new self($normalized);
    }

    public static function tryFrom(string $value): ?self
    {
        try {
            return self::fromString($value);
        } catch (InvalidCuitException) {
            return null;
        }
    }

    public function formatted(): string
    {
        return sprintf('%s-%s-%s', $this->prefix, $this->body, (string) $this->checkDigit);
    }

    public function equals(self $other): bool
    {
        return $this->digits === $other->digits;
    }

    public function isFisica(): bool
    {
        return $this->type === PersonaType::Fisica;
    }

    public function isJuridica(): bool
    {
        return $this->type === PersonaType::Juridica;
    }

    public function __toString(): string
    {
        return $this->formatted();
    }
}
