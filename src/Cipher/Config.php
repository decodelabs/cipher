<?php

/**
 * @package Cipher
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Cipher;

interface Config
{
    public string $secret { get; }
    public string $algorithm { get; }

    public ?string $queryParamName { get; }
    public ?string $cookieName { get; }
}
