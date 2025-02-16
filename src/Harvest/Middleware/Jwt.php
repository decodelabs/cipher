<?php

/**
 * @package Cipher
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

namespace DecodeLabs\Harvest\Middleware;

use DecodeLabs\Cipher\Codec;
use DecodeLabs\Cipher\Config;
use DecodeLabs\Cipher\Payload;
use DecodeLabs\Coercion;
use DecodeLabs\Harvest;
use DecodeLabs\Tightrope\RequiredSet;
use DecodeLabs\Tightrope\RequiredSetTrait;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Server\RequestHandlerInterface as Handler;
use Throwable;

class Jwt implements
    Middleware,
    RequiredSet
{
    use RequiredSetTrait;

    protected Config $config;

    public function __construct(
        Config $config,
        bool $required = false
    ) {
        $this->config = $config;
        $this->required = $required;
    }

    /**
     * Process request
     */
    public function process(
        Request $request,
        Handler $next
    ): Response {
        // Already done previously
        if (
            $request->getAttribute('jwt.payload') instanceof Payload ||
            $request->getAttribute('jwt.error') !== null
        ) {
            return $next->handle($request);
        }


        // Get token from request
        if (!$token = $this->getToken($request)) {
            if ($this->required) {
                return Harvest::json([
                    'status' => 'unauthorized',
                    'message' => 'No token provided'
                ], 401);
            }

            $request = $request->withAttribute('jwt.error', 'No token provided');
            return $next->handle($request);
        }


        // Decode token
        $codec = new Codec($this->config);

        try {
            $payload = $codec->decode($token);
            $request = $request->withAttribute('jwt.payload', $payload);
        } catch (Throwable $e) {
            if ($this->required) {
                return Harvest::json([
                    'status' => 'unauthorized',
                    'message' => $e->getMessage()
                ], 401);
            }

            $request = $request->withAttribute('jwt.error', $e->getMessage());
        }

        return $next->handle($request);
    }



    /**
     * Get token from query or header
     */
    protected function getToken(
        Request $request
    ): ?string {
        // Query params
        if (null !== ($name = $this->config->queryParamName)) {
            $params = $request->getQueryParams();

            if (isset($params[$name])) {
                return Coercion::toString($params[$name]);
            }
        }


        // Cookies
        if (null !== ($name = $this->config->cookieName)) {
            $cookies = $request->getCookieParams();

            if (isset($cookies[$name])) {
                return Coercion::toString($cookies[$name]);
            }
        }


        // Header
        if ($request->hasHeader('Authorization')) {
            $header = $request->getHeaderLine('Authorization');

            if (substr($header, 0, 7) === 'Bearer ') {
                return substr($header, 7);
            }
        }

        return null;
    }
}
