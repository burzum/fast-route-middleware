<?php
declare(strict_types=1);

namespace Burzum\FastRouteMiddleware\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Handles the case when a route was NOT found
 */
interface NotFoundHandlerInterface
{
    /**
     * Handles the case when a route was NOT found
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request Request
     * @return null|\Psr\Http\Message\ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ?ResponseInterface;
}
