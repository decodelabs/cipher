<?php

/**
 * Cipher
 * @license https://opensource.org/licenses/MIT
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

    public ?string $email {
        get => Coercion::tryString($this->data['email'] ?? null, true);
    }

    public ?string $phone {
        get => Coercion::tryString($this->data['phone'] ?? null, true);
    }

    public ?string $provider {
        get {
            if (!is_array($this->data['app_metadata'] ?? null)) {
                return null;
            }

            return Coercion::tryString($this->data['app_metadata']['provider'] ?? null, true);
        }
    }

    /**
     * @var ?list<string>
     */
    public ?array $providers {
        get {
            if (
                !is_array($this->data['app_metadata'] ?? null) ||
                !isset($this->data['app_metadata']['providers'])
            ) {
                return null;
            }

            /** @var list<string> $output */
            $output = Coercion::asArray($this->data['app_metadata']['providers']);
            return $output;
        }
    }

    /**
     * @var array<string,mixed>
     */
    public array $metadata {
        get {
            /** @var array<string,mixed> */
            return Coercion::asArray($this->data['user_metadata'] ?? []);
        }
    }

    public ?string $role {
        get => Coercion::tryString($this->data['role'] ?? null, true);
    }

    public ?string $assuranceLevel {
        get => Coercion::tryString($this->data['aal'] ?? null, true);
    }

    public ?string $authenticationMethod {
        get {
            if (!is_array($this->data['amr'] ?? null)) {
                return null;
            }

            return Coercion::tryString($this->data['amr']['method'] ?? null, true);
        }
    }

    public ?DateTime $authenticationDate {
        get {
            if (null === ($timestamp = $this->authenticationTime)) {
                return null;
            }

            return new DateTime('@' . $timestamp);
        }
    }

    public ?int $authenticationTime {
        get {
            if (!is_array($this->data['amr'] ?? null)) {
                return null;
            }

            return Coercion::tryInt($this->data['amr']['timestamp'] ?? null);
        }
    }

    public ?string $sessionId {
        get => Coercion::tryString($this->data['session_id'] ?? null, true);
    }

    public static function acceptsIssuer(
        string $issuer
    ): bool {
        return (bool)preg_match('|^https://[a-z0-9]+\.supabase\.co/auth/v[0-9]$|', $issuer);
    }
}
