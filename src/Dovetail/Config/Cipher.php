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

    public string $secret {
        get => $this->data->secret->as('string');
    }

    public string $algorithm {
        get => $this->data->algorithm->as('string');
    }


    public ?string $queryParamName {
        get => $this->data->queryParamName->as('?string');
    }

    public ?string $cookieName {
        get => $this->data->cookieName->as('?string');
    }
}
