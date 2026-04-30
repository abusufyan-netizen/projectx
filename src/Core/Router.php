<?php

namespace Hub\Core;

class Router {
    private $routes = [];

    public function get($path, $callback) {
        $this->addRoute('GET', $path, $callback);
    }

    public function post($path, $callback) {
        $this->addRoute('POST', $path, $callback);
    }

    private function addRoute($method, $path, $callback) {
        $this->routes[] = [
            'method' => $method,
            'path' => $this->convertPath($path),
            'callback' => $callback
        ];
    }

    private function convertPath($path) {
        return '#^' . preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[a-zA-Z0-9_-]+)', $path) . '$#';
    }

    public function dispatch() {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Subdirectory handling
        $baseUrl = '/ProjectX/public';
        if (strpos($path, $baseUrl) === 0) {
            $path = substr($path, strlen($baseUrl));
        }
        if (empty($path)) $path = '/';

        foreach ($this->routes as $route) {
            if ($route['method'] === $method && preg_match($route['path'], $path, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                $this->execute($route['callback'], $params);
                return;
            }
        }

        $this->notFound();
    }

    private function execute($callback, $params) {
        if (is_callable($callback)) {
            call_user_func_array($callback, array_values($params));
        } elseif (is_string($callback)) {
            list($controller, $method) = explode('@', $callback);
            $controllerClass = "Hub\\Controllers\\" . $controller;
            if (class_exists($controllerClass)) {
                $instance = new $controllerClass();
                call_user_func_array([$instance, $method], array_values($params));
            }
        }
    }

    private function notFound() {
        header("HTTP/1.0 404 Not Found");
        echo "404 Not Found";
    }
}
