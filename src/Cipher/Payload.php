<?php

/**
 * @package Cipher
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Cipher;

use ArrayAccess;
use DateTime;
use DecodeLabs\Glitch\Dumpable;
use JsonSerializable;

/**
 * @extends ArrayAccess<string, mixed>
 */
interface Payload extends
    ArrayAccess,
    JsonSerializable,
    Dumpable
{
    /**
     * Init with data
     *
     * @param array<string, mixed> $data
     */
    public function __construct(
        array $data = []
    );

    /**
     * Accepts issuer
     */
    public static function acceptsIssuer(
        string $issuer
    ): bool;

    public ?string $issuer { get; }
    public ?string $subject { get; }
    public ?string $audience { get; }
    public ?DateTime $expirationDate { get; }
    public ?int $expiration { get; }
    public ?DateTime $notBeforeDate { get; }
    public ?int $notBefore { get; }
    public ?DateTime $issuedAtDate { get; }
    public ?int $issuedAt { get; }
    public ?string $jwtId { get; }

    /**
     * Get data
     *
     * @return array<string, mixed>
     */
    public function toArray(): array;
}
