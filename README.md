# Cipher

[![PHP from Packagist](https://img.shields.io/packagist/php-v/decodelabs/cipher?style=flat)](https://packagist.org/packages/decodelabs/cipher)
[![Latest Version](https://img.shields.io/packagist/v/decodelabs/cipher.svg?style=flat)](https://packagist.org/packages/decodelabs/cipher)
[![Total Downloads](https://img.shields.io/packagist/dt/decodelabs/cipher.svg?style=flat)](https://packagist.org/packages/decodelabs/cipher)
[![GitHub Workflow Status](https://img.shields.io/github/actions/workflow/status/decodelabs/cipher/integrate.yml?branch=develop)](https://github.com/decodelabs/cipher/actions/workflows/integrate.yml)
[![PHPStan](https://img.shields.io/badge/PHPStan-enabled-44CC11.svg?longCache=true&style=flat)](https://github.com/phpstan/phpstan)
[![License](https://img.shields.io/packagist/l/decodelabs/cipher?style=flat)](https://packagist.org/packages/decodelabs/cipher)

### Tools and systems to interact with JWTs

Cipher provides an integrated suite of tools for working with JWTs, including a simple interface for creating and verifying tokens, and a set of middleware for use with [Harvest](https://github.com/decodelabs/harvest), [Greenleaf](https://github.com/decodelabs/greenleaf), or any other PSR-15 compatible middleware stack.

---

## Installation

Install via Composer:

```bash
composer require decodelabs/cipher
```

## Usage

### Codec

The `Codec` class provides the means to encode and decode JWTs.
The class requires an instance of `DecodeLabs\Cipher\Config` to be passed to the constructor - we provide a default [Dovetail](https://github.com/decodelabs/dovetail) implementation for this, but you can use your own if you wish.

The config defines what secret and algorithm is used.

```php
use DecodeLabs\Cipher\Codec;
use DecodeLabs\Dovetail;

$codec = new Codec(
    Dovetail::load('Cipher')
);

$payload = $codec->decode($token);
```

### Payload

The `Payload` interface defines a simple wrapper around JWT payload data with `ArrayAccess` support. The `Factory` will instantiate a `Generic` payload for unrecognized issuers, however extended implementations for specific issuers can be created and used instead, providing formal access to custom claim data.

```php
// $payload['iss'] = 'https://abcdefg.supabase.co/auth/v1'
// $payload instance of DecodeLabs\Cipher\Payload\Supabase
$email = $payload->getEmail();
$provider = $payload->getProvider();
```

### Middleware

Cipher provides a set of middleware for use with [Harvest](https://github.com/decodelabs/harvest) or [Greenleaf](https://github.com/decodelabs/greenleaf), or any other PSR-15 compatible middleware stack.

With the Middleware in your PSR-15 stack, Cipher will attempt to load a JWT from the request, and if successful, will set the `jwt.payload` attribute on the request with the decoded payload.

```php
$payload = $request->getAttribute('jwt.payload');
```

If using [Greenleaf](https://github.com/decodelabs/greenleaf), the payload can be injected into your action automatically via [Slingshot](https://github.com/decodelabs/slingshot), (below example uses `Supabase` payload):

```php
use DecodeLabs\Cipher\Payload\Supabase;
use DecodeLabs\Greenleaf\Action;
use DecodeLabs\Greenleaf\Action\ByMethodTrait;
use DecodeLabs\Harvest;
use DecodeLabs\Harvest\Response;

class MySecureAction implements Action
{
    use ByMethodTrait;

    public const Middleware = [
        'Jwt' => [
            'required' => true
        ]
    ];

    public function get(
        Supabase $payload
    ): Response {
        return Harvest::json([
            'email' => $payload->getEmail()
        ]);
    }
}
```

## Licensing

Cipher is licensed under the MIT License. See [LICENSE](./LICENSE) for the full license text.
