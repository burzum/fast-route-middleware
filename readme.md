# Fast Route PSR 15 Middleware

A convenient and strict typed Fast Route middleware.

## How to use it

Fast Routes result will be an array of which the first key represent the kind of result. Fast Route knows three different cases so far:

1. Route found
2. Route not found
3. Route not allowed

The middleware deals with getting this result for you from Fast Route but you'll have to to define your handlers, because this is up to you and your application. The middleware takes an object that needs to implement an interface specific to each time.

You **must** create at least the found handler! Each kind of handler must implement the according interface. The other two handlers are optional!

```php
// Route was found and matched the URL
class MyFoundHandler implements FoundHandlerInterface
{
    public function handle(ServerRequestInterface $request, $handler, array $vars): ?ResponseInterface
    {
        // Handle the request and return null or a response object
        // Dispatch your controllers or request handlers here based on the route vars
    }
}

// Route was not found, URL didn't match
class MyNotFoundHandler implements NotFoundHandlerInterface
{
    public function handle(ServerRequestInterface $request): ?ResponseInterface
    {
        // Handle the request and return null or a response object
        // Dispatch your controllers or request handlers here
    }
}

// Route was found but is not allowed to be accessible
class MyNotAllowedHandler implements NotAllowedHandlerInterface
{
    public function handle(ServerRequestInterface $request, array $notAllowedMethods): ?ResponseInterface
    {
        // Handle the request and return null or a response object
        // Dispatch your controllers or request handlers here
    }
}
```

Then configure the middleware. You **must** pass a FoundHandler the other two are optional!

Check the [FastRoute documentation](https://github.com/nikic/FastRoute) for how to configure FastRoutes dispatcher and it's routes.

```php
$dispatcher = SimpleDispatcher(function(RouteCollector $r) { 
    // Your routes...
});

$fastRouteMiddleware = new FastRouteMiddleware(
    $dispatcher,
    new MyFoundHandler(),
    new MyNotFoundHandler(),
    new MyNotAllowedHandler()
);

// Pass the middleware to your middleware handler implementation
```

## License

MIT License

Copyright (c) 2018 by Florian Krämer.
