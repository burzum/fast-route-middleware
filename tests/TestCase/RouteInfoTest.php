<?php
declare(strict_types=1);

namespace Burzum\FastRouteMiddleware\TestCase;

use Burzum\FastRouteMiddleware\RouteInfo;
use PHPUnit\Framework\TestCase;

use function \FastRoute\SimpleDispatcher;

/**
 * FastRouteMiddlewareTest
 */
class RouteInfoTest extends TestCase
{
    /**
     * testRouteInfo
     *
     * @return void
     */
    public function testRouteInfo(): void {
        $routeInfo = new RouteInfo('myhandler', ['foo' => 'bar']);

        $this->assertEquals('bar', $routeInfo['foo']);
        $this->assertEquals('bar', $routeInfo->get('foo'));
        $this->assertNull($routeInfo->get('does-not-exist'));
        $this->assertEquals('myhandler', $routeInfo->getHandler());
    }
}