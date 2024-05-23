<?php

declare(strict_types=1);

namespace Framework;

use Framework\Exceptions\ContainerException;

class Router
{
    private array $routes = [];
    private array $middlewares = [];

    public function add(string $method, string $path, array $controller)
    {
        $path = $this->normalizePath($path);

        $regexPath = preg_replace('#{[^/]+}#', '([^/]+)', $path);

        $this->routes[] = [
            'path' => $path,
            'method' => strtoupper($method),
            'controller' => $controller,
            'middlewares' => [],
            'regexPath' => $regexPath
        ];
    }

    private function normalizePath(string $path): string
    {
        $path = trim($path, '/');
        $path = "/{$path}/";
        $path = preg_replace('#[/]{2,}#', '/', $path);

        return $path;
    }

    // Passing instatnce into the controller
    public function dispatch(string $path, string $method, Container $container = null)
    {
        $path = $this->normalizePath($path);
        $method = strtoupper($_POST['_METHOD'] ?? $method);

        foreach ($this->routes as $route) {
            if (
                !preg_match("#^{$route['regexPath']}$#", $path, $paramsValue) ||
                $route['method'] !== $method
            ) {
                continue;
            }

            array_shift($paramsValue);

            preg_match_all('#{([^/]+)}#', $route['path'], $paramKeys);

            $paramKeys = $paramKeys[1];

            $params = array_combine($paramKeys, $paramsValue);

            // dd($params);
            [$class, $function] = $route['controller'];

            $controllerInstance = $container ?
                $container->resolve($class) :
                new $class;

            if ($controllerInstance === null) {
                throw new ContainerException("Controller instance is null for class {$class}");
            }

            // echo "Resolved class: " . get_class($controllerInstance) . "\n";
            // echo "Method to invoke: $function\n";

            if (!method_exists($controllerInstance, $function)) {
                throw new ContainerException("Method $function does not exist in class $class");
            }

            // Invokes without a middleware
            // $controllerInstance->{$function}();

            // Invokes with a middleware before instanciating
            $action = fn () => $controllerInstance->{$function}($params);

            // add middleware put the global middleware first before the route middleware

            $allMiddleware = [...$route['middlewares'], ...$this->middlewares];

            // Looping through

            // foreach ($this->middlewares as $middleware) {
            foreach ($allMiddleware as $middleware) {
                $middlewareInstance = $container ?
                    $container->resolve($middleware) :
                    new $middleware;
                $action = fn () => $middlewareInstance->process($action);
            }

            $action();

            // To prevent another route from being active
            return;
        }
    }

    public function addMiddleware(string $middleware)
    {
        $this->middlewares[] = $middleware;
    }

    public function addRouteMiddleware(string $middleware)
    {
        $lastRouteKey = array_key_last($this->routes);
        $this->routes[$lastRouteKey]['middlewares'][] = $middleware;
    }
}
