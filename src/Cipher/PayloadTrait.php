<?php

/**
 * @package Cipher
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Cipher;

use DateTime;
use DecodeLabs\Coercion;
use DecodeLabs\Nuance\Entity\NativeObject as NuanceEntity;

/**
 * @phpstan-require-implements Payload
 */
trait PayloadTrait
{
    /**
     * @var array<string,mixed>
     */
    protected array $data = [];


    public ?string $issuer {
        get => Coercion::tryString($this->data['iss'] ?? null, true);
    }

    public ?string $subject {
        get => Coercion::tryString($this->data['sub'] ?? null, true);
    }

    public ?string $audience {
        get => Coercion::tryString($this->data['aud'] ?? null, true);
    }

    public ?DateTime $expirationDate {
        get {
            if (null === ($timestamp = $this->expiration)) {
                return null;
            }

            return new DateTime('@' . $timestamp);
        }
    }

    public ?int $expiration {
        get => Coercion::tryInt($this->data['exp'] ?? null);
    }

    public ?DateTime $notBeforeDate {
        get {
            if (null === ($timestamp = $this->notBefore)) {
                return null;
            }

            return new DateTime('@' . $timestamp);
        }
    }

    public ?int $notBefore {
        get => Coercion::tryInt($this->data['nbf'] ?? null);
    }

    public ?DateTime $issuedAtDate {
        get {
            if (null === ($timestamp = $this->issuedAt)) {
                return null;
            }

            return new DateTime('@' . $timestamp);
        }
    }

    public ?int $issuedAt {
        get => Coercion::tryInt($this->data['iat'] ?? null);
    }

    public ?string $jwtId {
        get => Coercion::tryString($this->data['jti'] ?? null, true);
    }


    public function __construct(
        array $data = []
    ) {
        $this->data = $data;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return $this->data;
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }


    /**
     * @param string $offset
     */
    public function offsetExists(
        mixed $offset
    ): bool {
        return array_key_exists($offset, $this->data);
    }

    /**
     * @param string $offset
     */
    public function offsetGet(
        mixed $offset
    ): mixed {
        return $this->data[$offset] ?? null;
    }

    /**
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
     * @param string $offset
     */
    public function offsetUnset(
        mixed $offset
    ): void {
        unset($this->data[$offset]);
    }


    public function toNuanceEntity(): NuanceEntity
    {
        $entity = new NuanceEntity($this);
        $entity->values = $this->data;
        return $entity;
    }
}
