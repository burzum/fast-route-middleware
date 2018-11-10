<?php
declare(strict_types=1);

namespace Burzum\FastRouteMiddleware;

use Burzum\FastRouteMiddleware\Handler\FoundHandlerInterface;
use Burzum\FastRouteMiddleware\Handler\NotAllowedHandlerInterface;
use Burzum\FastRouteMiddleware\Handler\NotFoundHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use FastRoute\Dispatcher as DispatcherInterface;

/**
 * FastRoute Dispatcher Middleware
 *
 * @link https://github.com/nikic/FastRoute
 */
class FastRouteMiddleware implements MiddlewareInterface
{
    /**
     * Fast Route Dispatcher
     *
     * @var \FastRoute\Dispatcher
     */
    protected $dispatcher;

    /**
     * Route not allowed Handler
     *
     * @var null|\Burzum\FastRouteMiddleware\Handler\NotAllowedHandlerInterface
     */
    protected $notAllowedHandler;

    /**
     * Route not found Handler
     *
     * @var null|\Burzum\FastRouteMiddleware\Handler\NotFoundHandlerInterface
     */
    protected $notFoundHandler;

    /**
     * Route Found Handler
     *
     * @var null|\Burzum\FastRouteMiddleware\Handler\FoundHandlerInterface
     */
    protected $foundHandler;

    /**
     * Route Attribute for the Request
     *
     * @var string
     */
    protected $routeAttribute = 'route';

    /**
     * Constructor
     *
     * @param \FastRoute\Dispatcher $dispatcher Fastroute Dispatcher
     */
    public function __construct(
        DispatcherInterface $dispatcher,
        ?FoundHandlerInterface $foundHandler = null,
        ?NotFoundHandlerInterface $notFoundHandler = null,
        ?NotAllowedHandlerInterface $notAllowedHandler = null
    ) {
        $this->dispatcher = $dispatcher;
        $this->foundHandler = $foundHandler;
        $this->notFoundHandler = $notFoundHandler;
        $this->notAllowedHandler = $notFoundHandler;
    }

    /**
     * Sets the route attribute name
     *
     * @param string $attributeName Attribute Name
     * @return $this
     */
    public function setRouteAttribute(string $attributeName): self
    {
        $this->routeAttribute = $attributeName;

        return $this;
    }

    /**
     * Process an incoming server request and return a response, optionally
     * delegating response creation to a handler.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request Request
     * @param \Psr\Http\Server\RequestHandlerInterface $requestHandler Request Handler
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $requestHandler): ResponseInterface
    {
        $routeInfo = $this->dispatch();
        $result = null;

        switch ($routeInfo[0]) {
            case DispatcherInterface::NOT_FOUND:
                if ($this->notFoundHandler !== null) {
                    $result = $this->notFoundHandler->handle($request);
                }
                break;
            case DispatcherInterface::METHOD_NOT_ALLOWED:
                if ($this->notAllowedHandler !== null) {
                    $result = $this->notAllowedHandler->handle($request, $routeInfo[1]);
                }
                break;
            case DispatcherInterface::FOUND:
                $request = $request->withAttribute(
                    $this->routeAttribute,
                    new RouteInfo($routeInfo[1], $routeInfo[2])
                );
                if ($this->foundHandler !== null) {
                    $result = $this->foundHandler->handle($request, $routeInfo[1], $routeInfo[2]);
                }
                break;
        }

        if ($result instanceof ResponseInterface) {
            return $result;
        }

        return $requestHandler->handle($request);
    }

    /**
     * Dispatches the Request URI
     *
     * @return array
     */
    protected function dispatch(): array
    {
        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];

        // Strip query string (?foo=bar) and decode URI
        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        $uri = rawurldecode($uri);

        return $this->dispatcher->dispatch($httpMethod, $uri);
    }
}
