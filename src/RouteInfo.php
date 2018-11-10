<?php
declare(strict_types=1);

namespace Burzum\FastRouteMiddleware;

/**
 * Route Info Object
 */
class RouteInfo implements RouteInfoInterface
{
    /**
     * Route Handler
     *
     * @var mixed
     */
    protected $handler;

    /**
     * Route vars
     *
     * @var array
     */
    protected $vars;

    /**
     * Constructor
     *
     * @param mixed $handler
     * @param mixed $vars
     */
    public function __construct($handler, $vars)
    {
        $this->handler = $handler;
        $this->vars = $vars;
    }

    /**
     * Gets the handler of the route
     *
     * @return mixed
     */
    public function getHandler(): string
    {
        return $this->handler;
    }

    /**
     * Returns a route var
     *
     * @param string $name
     * @return null|string
     */
    public function get($name): ?string
    {
        if (isset($this->vars[$name])) {
            return $this->vars[$name];
        }

        return null;
    }

    /**
     * Whether a offset exists
     *
     * @link https://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     */
    public function offsetExists($offset)
    {
        return isset($this->vars[$offset]);
    }

    /**
     * Offset to retrieve
     *
     * @link https://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($offset)
    {
        if ($this->offsetExists($offset)) {
            return $this->vars[$offset];
        }

        return null;
    }

    /**
     * Offset to set
     *
     * @link https://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetSet($offset, $value)
    {
        throw new \RuntimeException();
    }

    /**
     * Offset to unset
     *
     * @link https://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetUnset($offset)
    {
        throw new \RuntimeException();
    }
}