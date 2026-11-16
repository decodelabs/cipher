<?php

/**
 * Cipher
 * @license https://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Cipher\Tests\NullArrayKeys;

require dirname(__DIR__) . '/vendor/autoload.php';

error_reporting(E_ALL);
set_error_handler(static function (int $severity, string $message, string $file, int $line): never {
    throw new \ErrorException($message, 0, $severity, $file, $line);
});

function expectSame(mixed $expected, mixed $actual): void
{
    if ($expected !== $actual) {
        throw new \RuntimeException('Unexpected null-key regression result: ' . var_export($actual, true));
    }
}

try {
    $payload = new \DecodeLabs\Cipher\Payload\Generic(['' => null, 'iss' => 'issuer']);
    expectSame(true, (new \ReflectionMethod($payload, 'offsetExists'))->invoke($payload, null));
    expectSame(null, (new \ReflectionMethod($payload, 'offsetGet'))->invoke($payload, null));
    (new \ReflectionMethod($payload, 'offsetSet'))->invoke($payload, null, 'value');
    expectSame(['' => 'value', 'iss' => 'issuer'], $payload->toArray());
    expectSame('value', (new \ReflectionMethod($payload, 'offsetGet'))->invoke($payload, null));
    (new \ReflectionMethod($payload, 'offsetUnset'))->invoke($payload, null);
    expectSame(false, (new \ReflectionMethod($payload, 'offsetExists'))->invoke($payload, null));
    expectSame('issuer', $payload['iss']);
} finally {
    restore_error_handler();
}

echo "Null array-key regression checks passed.\n";
