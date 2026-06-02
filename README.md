# devralint/cuit-cuil-validator

Librería PHP para validar y parsear CUIT/CUIL argentinos. Algoritmo módulo 11 con pesos `[5,4,3,2,7,6,5,4,3,2]`. Sin dependencias de framework.

[![CI](https://github.com/devralint/cuit-cuil-validator/actions/workflows/ci.yml/badge.svg)](https://github.com/devralint/cuit-cuil-validator/actions/workflows/ci.yml)

## Instalación

```bash
composer require devralint/cuit-cuil-validator
```

**Requisitos:** PHP >= 8.2

## Uso

### Validación rápida (stateless)

```php
use Devralint\Cuit\Validator;

Validator::isValid('20-12345678-6'); // true
Validator::isValid('20-12345678-9'); // false — dígito verificador incorrecto
Validator::isValid('20 12345678 6'); // true — acepta espacios
Validator::isValid('20123456786');   // true — sin separadores
```

### Value object

```php
use Devralint\Cuit\Cuit;
use Devralint\Cuit\Exception\InvalidCuitException;

// Construcción estricta: lanza InvalidCuitException si inválido
$cuit = Cuit::fromString('20-12345678-6');

echo $cuit->prefix;      // "20"
echo $cuit->body;        // "12345678"
echo $cuit->checkDigit;  // 6
echo $cuit->formatted(); // "20-12345678-6"
echo (string) $cuit;     // "20-12345678-6"

$cuit->isFisica();       // true
$cuit->isJuridica();     // false

// Construcción tolerante: devuelve null si inválido
$cuit = Cuit::tryFrom('invalido'); // null
```

### Tipo de persona

```php
use Devralint\Cuit\PersonaType;

$cuit->type; // PersonaType::Fisica | PersonaType::Juridica | PersonaType::Otro
```

| Prefijos | Tipo |
|----------|------|
| 20, 23, 24, 25, 26, 27 | `Fisica` |
| 30, 33, 34 | `Juridica` |
| otros | `Otro` (CUIT válido, tipo desconocido) |

> **Nota:** la inferencia del tipo es orientativa. AFIP/ARCA es la fuente de verdad definitiva.

## Integración con Laravel

```php
// En un Form Request
use Devralint\Cuit\Validator as CuitValidator;

$request->validate([
    'cuit' => ['required', 'string', function (string $attr, mixed $value, Closure $fail) {
        if (!CuitValidator::isValid((string) $value)) {
            $fail("El campo $attr no es un CUIT/CUIL válido.");
        }
    }],
]);
```

Para una integración completa (Rule, cast Eloquent, Service Provider), ver el paquete satélite `devralint/laravel-cuit` (próximamente).

## Integración con Yii 2

```php
use Devralint\Cuit\Validator as CuitValidator;
use yii\validators\Validator;

class CuitValidator extends Validator
{
    public function validateValue($value): ?array
    {
        return CuitValidator::isValid((string) $value)
            ? null
            : [$this->message ?? 'CUIT/CUIL inválido.', []];
    }
}
```

## Caso de borde: dígito verificador = 10

El algoritmo módulo 11 puede producir `dv = 10`. Esta librería usa la convención **permisiva** (la más difundida en Argentina): `10 → 9`. AFIP/ARCA evita emitir CUIT con ese resultado reasignando el prefijo, por lo que en la práctica estos casos son raros.

## Desarrollo

```bash
composer install
composer test       # PHPUnit 11
composer analyse    # PHPStan level max
```

## Licencia

MIT — ver [LICENSE](LICENSE).
