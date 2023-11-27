<?php

/**
 * @package Cipher
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Dovetail\Config;

use DecodeLabs\Cipher\Config as ConfigInterface;
use DecodeLabs\Dovetail\Config;
use DecodeLabs\Dovetail\ConfigTrait;

class Cipher implements Config, ConfigInterface
{
    use ConfigTrait;

    /**
     * Get default values
     */
    public static function getDefaultValues(): array
    {
        return [
            'secret' => "{{envString('CIPHER_SECRET')}}",
            'algorithm' => 'HS256',
            'queryParamName' => 'jwt',
            'cookieName' => 'jwt'
        ];
    }

    /**
     * Get secret
     */
    public function getSecret(): string
    {
        return $this->data->secret->as('string');
    }

    /**
     * Get algorithm
     */
    public function getAlgorithm(): string
    {
        return $this->data->algorithm->as('string');
    }


    /**
     * Get query param name
     */
    public function getQueryParamName(): ?string
    {
        return $this->data->queryParamName->as('?string');
    }

    /**
     * Get cookie name
     */
    public function getCookieName(): ?string
    {
        return $this->data->cookieName->as('?string');
    }
}
