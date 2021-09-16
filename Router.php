<?php

namespace App\core;

use App\core\Exception\notFoundEx;

class Router
{
    public array $routes = [];

    public function get($route, $callback)
    {
        $this->routes['get'][$route] = $callback;
    }

    public function post($route, $callback)
    {
        $this->routes['post'][$route] = $callback;
    }

    public function resolve()
    {
        $method = App::$app->request->method();
        $path = App::$app->request->getPath();

        $callback = $this->routes[$method][$path] ?? false;

        if (is_string($callback)) {
            return call_user_func($callback);
        }
        if ($callback === false) {
            throw new notFoundEx();
        }
        if (is_array($callback)) {
            /**
             * @var \App\core\Controller $controller
             */
            $controller = new $callback[0]();
            App::$app->controller = $controller;
            $controller->action = $callback[1];
            $callback[0] = $controller;

            foreach ($controller->getMiddlewares() as $middleware) {
                $middleware->execute();
            }
        }

        return call_user_func($callback);
    }
}
