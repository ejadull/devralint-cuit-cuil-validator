<?php

declare(strict_types=1);

namespace Devralint\Cuit\Exception;

use InvalidArgumentException;

final class InvalidCuitException extends InvalidArgumentException
{
    public static function format(string $value): self
    {
        return new self(sprintf('"%s" is not a valid CUIT/CUIL format (expected 11 digits).', $value));
    }

    public static function checkDigit(string $value): self
    {
        return new self(sprintf('"%s" has an invalid check digit.', $value));
    }
}
