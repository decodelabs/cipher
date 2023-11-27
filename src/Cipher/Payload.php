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

    /**
     * Get issuer
     */
    public function getIssuer(): ?string;

    /**
     * Get subject
     */
    public function getSubject(): ?string;

    /**
     * Get audience
     */
    public function getAudience(): ?string;

    /**
     * Get expiration
     */
    public function getExpirationDate(): ?DateTime;

    /**
     * Get expiration
     */
    public function getExpiration(): ?int;

    /**
     * Get not before
     */
    public function getNotBeforeDate(): ?DateTime;

    /**
     * Get not before
     */
    public function getNotBefore(): ?int;

    /**
     * Get issued at
     */
    public function getIssuedAtDate(): ?DateTime;

    /**
     * Get issued at
     */
    public function getIssuedAt(): ?int;

    /**
     * Get JWT ID
     */
    public function getJwtId(): ?string;



    /**
     * Get data
     *
     * @return array<string, mixed>
     */
    public function toArray(): array;
}
