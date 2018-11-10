<?php
declare(strict_types=1);

namespace Burzum\FastRouteMiddleware;

/**
 * RouteInfoInterface
 */
interface RouteInfoInterface extends \ArrayAccess
{
	/**
	 * Gets the handler defined for the route
	 *
	 * @return mixed
	 */
	public function getHandler();

	/**
	 * Gets a route attribute by name, if not present it will return null
	 *
	 * @param string $name Name
	 * @return string|null
	 */
	public function get($name): ?string;
}