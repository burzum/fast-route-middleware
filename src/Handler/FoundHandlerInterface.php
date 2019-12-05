<?php

declare(strict_types=1);

namespace Burzum\FastRouteMiddleware\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Handles the case when a route was found
 */
interface FoundHandlerInterface
{
    /**
     * Handles the case when a route was found
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request Request
     * @param mixed $handler Route handler
     * @param array $vars Route variables
     * @return null|\Psr\Http\Message\ResponseInterface
     */
    public function handle(ServerRequestInterface $request, $handler, array $vars): ?ResponseInterface;
}
