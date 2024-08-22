<?php

/**
 * @package Cipher
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Cipher;

use DateTime;
use DecodeLabs\Coercion;

/**
 * @phpstan-require-implements Payload
 */
trait PayloadTrait
{
    /**
     * @var array<string, mixed>
     */
    protected array $data = [];


    /**
     * Init with data
     */
    public function __construct(
        array $data = []
    ) {
        $this->data = $data;
    }



    /**
     * Get issuer
     */
    public function getIssuer(): ?string
    {
        return Coercion::toStringOrNull($this->data['iss'] ?? null, true);
    }

    /**
     * Get subject
     */
    public function getSubject(): ?string
    {
        return Coercion::toStringOrNull($this->data['sub'] ?? null, true);
    }

    /**
     * Get audience
     */
    public function getAudience(): ?string
    {
        return Coercion::toStringOrNull($this->data['aud'] ?? null, true);
    }

    /**
     * Get expiration
     */
    public function getExpirationDate(): ?DateTime
    {
        if (null === ($timestamp = $this->getExpiration())) {
            return null;
        }

        return new DateTime('@' . $timestamp);
    }

    /**
     * Get expiration
     */
    public function getExpiration(): ?int
    {
        return Coercion::toIntOrNull($this->data['exp'] ?? null);
    }

    /**
     * Get not before
     */
    public function getNotBeforeDate(): ?DateTime
    {
        if (null === ($timestamp = $this->getNotBefore())) {
            return null;
        }

        return new DateTime('@' . $timestamp);
    }

    /**
     * Get not before
     */
    public function getNotBefore(): ?int
    {
        return Coercion::toIntOrNull($this->data['nbf'] ?? null);
    }

    /**
     * Get issued at
     */
    public function getIssuedAtDate(): ?DateTime
    {
        if (null === ($timestamp = $this->getIssuedAt())) {
            return null;
        }

        return new DateTime('@' . $timestamp);
    }

    /**
     * Get issued at
     */
    public function getIssuedAt(): ?int
    {
        return Coercion::toIntOrNull($this->data['iat'] ?? null);
    }

    /**
     * Get JWT ID
     */
    public function getJwtId(): ?string
    {
        return Coercion::toStringOrNull($this->data['jti'] ?? null, true);
    }





    /**
     * Get data
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return $this->data;
    }

    /**
     * Json serialize
     *
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }


    /**
     * ArrayAccess: offset exists
     *
     * @param string $offset
     */
    public function offsetExists(
        mixed $offset
    ): bool {
        return array_key_exists($offset, $this->data);
    }

    /**
     * ArrayAccess: offset get
     *
     * @param string $offset
     */
    public function offsetGet(
        mixed $offset
    ): mixed {
        return $this->data[$offset] ?? null;
    }

    /**
     * ArrayAccess: offset set
     *
     * @param string $offset
     * @param mixed $value
     */
    public function offsetSet(
        mixed $offset,
        mixed $value
    ): void {
        $this->data[$offset] = $value;
    }

    /**
     * ArrayAccess: offset unset
     *
     * @param string $offset
     */
    public function offsetUnset(
        mixed $offset
    ): void {
        unset($this->data[$offset]);
    }


    /**
     * Dump for glitch
     */
    public function glitchDump(): iterable
    {
        yield 'values' => $this->data;
    }
}
