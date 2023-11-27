# Cipher

[![PHP from Packagist](https://img.shields.io/packagist/php-v/decodelabs/cipher?style=flat)](https://packagist.org/packages/decodelabs/cipher)
[![Latest Version](https://img.shields.io/packagist/v/decodelabs/cipher.svg?style=flat)](https://packagist.org/packages/decodelabs/cipher)
[![Total Downloads](https://img.shields.io/packagist/dt/decodelabs/cipher.svg?style=flat)](https://packagist.org/packages/decodelabs/cipher)
[![GitHub Workflow Status](https://img.shields.io/github/actions/workflow/status/decodelabs/cipher/integrate.yml?branch=develop)](https://github.com/decodelabs/cipher/actions/workflows/integrate.yml)
[![PHPStan](https://img.shields.io/badge/PHPStan-enabled-44CC11.svg?longCache=true&style=flat)](https://github.com/phpstan/phpstan)
[![License](https://img.shields.io/packagist/l/decodelabs/cipher?style=flat)](https://packagist.org/packages/decodelabs/cipher)

### Tools and systems to interact with JWTs

Cipher provides an integrated suite of tools for working with JWTs, including a simple interface for creating and verifying tokens, and a set of middleware for use with [Harvest](https://github.com/decodelabs/harvest), [Greenleaf](https://github.com/decodelabs/greenleaf), or any other PSR-15 compatible middleware stack.

_Get news and updates on the [DecodeLabs blog](https://blog.decodelabs.com)._

---

## Installation

Install via Composer:

```bash
composer require decodelabs/cipher
```

## Usage

### Codec

The <code>Codec</code> class provides the means to encode and decode JWTs.
The class requires an instance of <code>DecodeLabs\Cipher\Config</code> to be passed to the constructor - we provide a default <code>Dovetail</code> implementation for this, but you can use your own if you wish.

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

The <code>Payload</code> interface defines a simple wrapper around JWT payload data with <code>ArrayAccess</code> support. The <code>Factory</code> will instantiate a <code>Generic</code> payload for unrecognized issuers, however extended implementations for specific issuers can be created and used instead, providing formal access to custom claim data.

```php
// $payload['iss'] = 'https://abcdefg.supabase.co/auth/v1'
// $payload instance of DecodeLabs\Cipher\Payload\Supabase
$email = $payload->getEmail();
$provider = $payload->getProvider();
```

### Middleware

Cipher provides a set of middleware for use with Harvest or Greenleaf, or any other PSR-15 compatible middleware stack.

With the Middleware in your PSR-15 stack, Cipher will attempt to load a JWT from the request, and if successful, will set the <code>jwt.payload</code> attribute on the request with the decoded payload.

```php
$payload = $request->getAttribute('jwt.payload');
```

If using <code>Greenleaf</code>, the payload can be injected into your action automatically via <code>Slingshot</code>, (below example uses <code>Supabase</code> payload):

```php
use DecodeLabs\Cipher\Payload\Supabase;
use DecodeLabs\Greenleaf\Action;
use DecodeLabs\Greenleaf\Action\ByMethodTrait;
use DecodeLabs\Harvest;
use DecodeLabs\Harvest\Response;

class MySecureAction implements Action
{
    use ByMethodTrait;

    public const MIDDLEWARE = [
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
