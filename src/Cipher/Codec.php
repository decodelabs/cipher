<?php

/**
 * @package Cipher
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Cipher;

use DecodeLabs\Slingshot;
use Firebase\JWT\JWT as JwtLib;
use Firebase\JWT\Key as JwtKey;

class Codec
{
    protected Config $config;
    protected Factory $factory;

    public function __construct(
        Config $config,
        ?Factory $factory = null
    ) {
        $this->config = $config;

        if (!$factory) {
            $factory = new Slingshot()->resolveInstance(Factory::class);
        }

        $this->factory = $factory;
    }

    public function encode(
        Payload $payload
    ): string {
        return JwtLib::encode(
            $payload->toArray(),
            $this->config->secret,
            $this->config->algorithm
        );
    }

    public function decode(
        string $token
    ): Payload {
        // JSON decode/encode to ensure we have a clean array
        $data = json_decode((string)json_encode(JwtLib::decode(
            $token,
            new JwtKey(
                $this->config->secret,
                $this->config->algorithm
            )
        )), true);

        /** @var array<string, mixed> $data */
        return $this->factory->createPayload($data);
    }
}
