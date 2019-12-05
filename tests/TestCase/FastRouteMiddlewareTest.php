<?php

declare(strict_types=1);

namespace Burzum\FastRouteMiddleware\TestCase;

use Burzum\FastRouteMiddleware\FastRouteMiddleware;
use Burzum\FastRouteMiddleware\Handler\FoundHandlerInterface;
use Burzum\FastRouteMiddleware\Handler\NotAllowedHandlerInterface;
use Burzum\FastRouteMiddleware\Handler\NotFoundHandlerInterface;
use FastRoute\Dispatcher\GroupCountBased;
use FastRoute\RouteCollector;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use PHPUnit\Framework\TestCase;

use function FastRoute\SimpleDispatcher;

/**
 * FastRouteMiddlewareTest
 */
class FastRouteMiddlewareTest extends TestCase
{
    /**
     * @var \Burzum\FastRouteMiddleware\Handler\FoundHandlerInterface;
     */
    protected $foundHandlerMock;

    /**
     * @var \Burzum\FastRouteMiddleware\Handler\NotFoundHandlerInterface;
     */
    protected $notFoundHandlerMock;

    /**
     * @var \Burzum\FastRouteMiddleware\Handler\NotAllowedHandlerInterface;
     */
    protected $notAllowedHandlerMock;

    /**
     * @var \Psr\Http\Message\ServerRequestInterface
     */
    protected $requestMock;

    /**
     * @var \Psr\Http\Message\ResponseInterface
     */
    protected $responseMock;

    /**
     * @var \Psr\Http\Server\RequestHandlerInterface
     */
    protected $requestHandlerMock;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->foundHandlerMock = $this->getMockBuilder(FoundHandlerInterface::class)->getMock();
        $this->notFoundHandlerMock = $this->getMockBuilder(NotFoundHandlerInterface::class)->getMock();
        $this->notAllowedHandlerMock = $this->getMockBuilder(NotAllowedHandlerInterface::class)->getMock();
        $this->requestHandlerMock = $this->getMockBuilder(RequestHandlerInterface::class)->getMock();
        $this->requestMock = $this->getMockBuilder(ServerRequestInterface::class)->getMock();
        $this->responseMock = $this->getMockBuilder(ResponseInterface::class)->getMock();
    }

    /**
     * testFoundHandling
     */
    public function testFoundHandling(): void
    {
        $dispatcher = SimpleDispatcher(function (RouteCollector $r) {

            $r->addRoute(['GET'], '/', 'Home');
        });
        $fastRoute = $this->fastRouteSetup($dispatcher);
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/';
        $this->foundHandlerMock
            ->expects($this->at(0))
            ->method('handle')
            ->with($this->requestMock, 'Home', []);
        $fastRoute->process($this->requestMock, $this->requestHandlerMock);

        // Test that none of the handlers is triggered but the "next" middleware called
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/does-not-exist';
        $this->requestHandlerMock->expects($this->at(0))
            ->method('handle')
            ->with($this->requestMock);
        $fastRoute->process($this->requestMock, $this->requestHandlerMock);
    }

    /**
     * Helper method to get a configured middleware instance
     *
     * @return \Burzum\FastRouteMiddleware\FastRouteMiddleware;
     */
    protected function fastRouteSetup(GroupCountBased $dispatcher): FastRouteMiddleware
    {
        return new FastRouteMiddleware($dispatcher, $this->foundHandlerMock, $this->notFoundHandlerMock, $this->notAllowedHandlerMock);
    }
}
