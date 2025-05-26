<?php

class Router
{
    private $routes = [];
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function add($method, $path, $controller, $action)
    {
        $this->routes[] = compact('method', 'path', 'controller', 'action');
    }

    public function dispatch($requestUri, $requestMethod)
    {
        foreach ($this->routes as $route) {
            if ($route['method'] !== $requestMethod) {
                continue;
            }

            // Transforma rota com {param} em regex: ex /posts/{id} => ^/posts/([\w-]+)$
            $pattern = preg_replace('#\{[\w]+\}#', '([\w-]+)', $route['path']);
            $pattern = "#^" . $pattern . "$#";

            if (preg_match($pattern, $requestUri, $matches)) {
                array_shift($matches); // Remove o match completo

                $controller = new $route['controller']($this->db);

                // Passa os parâmetros capturados como argumentos
                return call_user_func_array([$controller, $route['action']], $matches);
            }
        }

        http_response_code(404);
        echo json_encode(['error' => 'Rota não encontrada.']);
    }
}
