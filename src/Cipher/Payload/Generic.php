<?php

/**
 * @package Cipher
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Cipher\Payload;

use DecodeLabs\Cipher\Payload;
use DecodeLabs\Cipher\PayloadTrait;

class Generic implements Payload
{
    use PayloadTrait;

    /**
     * Accepts issuer
     */
    public static function acceptsIssuer(
        string $issuer
    ): bool {
        return true;
    }
}
