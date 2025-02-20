<?php

/**
 * @package Cipher
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Cipher\Payload;

use DateTime;
use DecodeLabs\Cipher\Payload;
use DecodeLabs\Cipher\PayloadTrait;
use DecodeLabs\Coercion;

class Supabase implements Payload
{
    use PayloadTrait;

    /**
     * Accepts issuer
     */
    public static function acceptsIssuer(
        string $issuer
    ): bool {
        return (bool)preg_match('|^https://[a-z0-9]+\.supabase\.co/auth/v[0-9]$|', $issuer);
    }

    /**
     * Get email address
     */
    public function getEmail(): ?string
    {
        return Coercion::tryString($this->data['email'] ?? null, true);
    }

    /**
     * Get phone number
     */
    public function getPhone(): ?string
    {
        return Coercion::tryString($this->data['phone'] ?? null, true);
    }

    /**
     * Get provider
     */
    public function getProvider(): ?string
    {
        if (!is_array($this->data['app_metadata'] ?? null)) {
            return null;
        }

        return Coercion::tryString($this->data['app_metadata']['provider'] ?? null, true);
    }

    /**
     * Get providers
     *
     * @return array<string>
     */
    public function getProviders(): ?array
    {
        if (
            !is_array($this->data['app_metadata'] ?? null) ||
            !isset($this->data['app_metadata']['providers'])
        ) {
            return null;
        }

        /** @var array<string> $output */
        $output = Coercion::asArray($this->data['app_metadata']['providers']);
        return $output;
    }

    /**
     * Get metadata
     *
     * @return array<string,mixed>
     */
    public function getMetadata(): array
    {
        /** @var array<string,mixed> $output */
        $output = Coercion::asArray($this->data['user_metadata'] ?? []);
        return $output;
    }


    /**
     * Get role
     */
    public function getRole(): ?string
    {
        return Coercion::tryString($this->data['role'] ?? null, true);
    }

    /**
     * Get Assurance level
     */
    public function getAssuranceLevel(): ?string
    {
        return Coercion::tryString($this->data['aal'] ?? null, true);
    }

    /**
     * Get authentication method
     */
    public function getAuthenticationMethod(): ?string
    {
        if (!is_array($this->data['amr'] ?? null)) {
            return null;
        }

        return Coercion::tryString($this->data['amr']['method'] ?? null, true);
    }

    /**
     * Get authentication date
     */
    public function getAuthenticationDate(): ?DateTime
    {
        if (null === ($timestamp = $this->getAuthenticationTime())) {
            return null;
        }

        return new DateTime('@' . $timestamp);
    }

    /**
     * Get authentication timestamp
     */
    public function getAuthenticationTime(): ?int
    {
        if (!is_array($this->data['amr'] ?? null)) {
            return null;
        }

        return Coercion::tryInt($this->data['amr']['timestamp'] ?? null);
    }

    /**
     * Get session ID
     */
    public function getSessionId(): ?string
    {
        return Coercion::tryString($this->data['session_id'] ?? null, true);
    }
}
