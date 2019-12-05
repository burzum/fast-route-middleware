<?php

declare(strict_types=1);

namespace Burzum\FastRouteMiddleware\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * NotAllowedDispatcher
 *
 * Handles the case when a route doesn't allow HTTP methods
 */
interface NotAllowedHandlerInterface
{
    /**
     * Handles the case when a route doesn't allow HTTP methods
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request Request
     * @param array $notAllowedMethods The HTTP methods that are not allowed
     * @return null|\Psr\Http\Message\ResponseInterface
     */
    public function handle(ServerRequestInterface $request, array $notAllowedMethods): ?ResponseInterface;
}
