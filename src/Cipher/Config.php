<?php

/**
 * @package Cipher
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Cipher;

interface Config
{
    public function getSecret(): string;
    public function getAlgorithm(): string;

    public function getQueryParamName(): ?string;
    public function getCookieName(): ?string;
}
