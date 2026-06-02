<?php

declare(strict_types=1);

namespace Devralint\Cuit;

enum PersonaType
{
    case Fisica;
    case Juridica;
    case Otro;

    private const array FISICA_PREFIXES = ['20', '23', '24', '25', '26', '27'];
    private const array JURIDICA_PREFIXES = ['30', '33', '34'];

    public static function fromPrefix(string $prefix): self
    {
        if (in_array($prefix, self::FISICA_PREFIXES, true)) {
            return self::Fisica;
        }

        if (in_array($prefix, self::JURIDICA_PREFIXES, true)) {
            return self::Juridica;
        }

        return self::Otro;
    }
}
