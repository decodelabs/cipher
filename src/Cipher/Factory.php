<?php

/**
 * Cipher
 * @license https://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Cipher;

use DecodeLabs\Archetype;
use DecodeLabs\Cipher\Payload\Generic;
use DecodeLabs\Coercion;

class Factory
{
    public function __construct(
        protected Archetype $archetype
    ) {
    }

    /**
     * @param array<string,mixed> $data
     */
    public function createPayload(
        array $data
    ): Payload {
        $issuer = Coercion::tryString($data['iss'] ?? null);

        if ($issuer !== null) {
            foreach ($this->archetype->scanClasses(Payload::class) as $class) {
                if ($class === Generic::class) {
                    continue;
                }

                if ($class::acceptsIssuer($issuer)) {
                    return new $class($data);
                }
            }
        }

        return new Generic($data);
    }
}
