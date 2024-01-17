<?php

namespace Pemm\Core;

use Pemm\Config;
use Pemm\Model\Category;
use Pemm\Routing;

class Router extends Core
{
    /**
     * @var array
     */
    protected $routes = [];

    /**
     * @var array
     */
    protected $params = [];

    /**
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct();

        $this->initRoutes();

        try {

            $this->dispatch($this->request->getRequestUri());

        } catch (\Exception $e) {
            print_r($e->getMessage());die;
            switch ($e->getCode()) {
                case 404:
                    print_r($this->request->getRequestUri());die;
                    header('Location: /404');
                    break;
                case 500:
                    print_r($this->request->getRequestUri());die;
                    header('Location: /500');
                    break;
                default:
                    exit();
            }

        }
    }

    /**
     * @return void
     */
    public function initRoutes()
    {
        $explode = explode('/', $this->request->getRequestUri());

        $type = (@$explode[2] == 'admin') ? 'admin' : 'customer';
        switch (@$explode[1]) {
            case 'admin':
                (new Routing())->admin($this);
                break;
            case 'api':
                (new Routing())->api($this);
                break;
            case 'ajax':
                (new Routing())->ajax($this, $type);
                break;
            default:
                if (empty($explode[1])) {
                    header('location: /panel');
                }
                (new Routing())->customer($this);
                break;
        }
    }

    /**
     * @param string $route
     * @param array $params
     *
     * @return void
     */
    public function add(string $route, array $params = [])
    {
        // Convert the route to a regular expression: escape forward slashes
        $route = preg_replace('/\//', '\\/', $route);

        // Convert variables e.g. {controller}
        $route = preg_replace('/\{([a-zA-Z0-9-]+)\}/', '(?P<\1>[a-zA-Z0-9-]+)', $route);

        // Convert variables with custom regular expressions e.g. {id:\d+}
        $route = preg_replace('/\{([a-zA-Z0-9-]+):([^\}]+)\}/', '(?P<\1>\2)', $route);

        // Add start and end delimiters, and case insensitive flag
        $route = '/^' . $route . '$/i';

        $this->routes[$route] = $params;
    }

    /**
     * @return array
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * @param string $url
     * @return boolean
     */
    public function match($url)
    {
        try {
            foreach ($this->routes as $route => $params) {
                if (preg_match($route, $url, $matches)) {
                    // Get named capture group values
                    foreach ($matches as $key => $match) {
                        if (is_string($key)) {
                            $params[$key] = $match;
                        }
                    }
                    $this->params = $params;
                    return true;
                }
            }
        } catch (\Exception $exception) {
            print_r($exception);die;
        }


        return false;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param string $url
     * @return void
     * @throws \Exception
     */
    public function dispatch($url)
    {
        $url = $this->removeQueryStringVariables($url);

        if ($this->match($url)) {

            $controller = $this->params['controller'];
            $controller = $this->convertToStudlyCaps($controller);
            $controller = $this->getNamespace() . $controller;

            if (class_exists($controller)) {
                $controller_object = new $controller($this->params);

                $action = $this->params['action'];
                $action = $this->convertToCamelCase($action);

                if (preg_match('/action$/i', $action) == 0) {
                    $controller_object->$action();

                } else {
                    throw new \Exception("Method $action in controller $controller cannot be called directly - remove the Action suffix to call this method");
                }
            } else {
                throw new \Exception("Controller class $controller not found");
            }
        } else {
            throw new \Exception('No route matched.', 404);
        }
    }

    /**
     * @param string $string
     * @return string
     */
    protected function convertToStudlyCaps($string)
    {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
    }

    /**
     * @param string $string
     * @return string
     */
    protected function convertToCamelCase($string)
    {
        return lcfirst($this->convertToStudlyCaps($string));
    }

    /**
     * @param string $url
     * @return string
     */
    protected function removeQueryStringVariables($url)
    {
        if ($url != '') {
            $parts = explode('?', $url, 2);
            if (strpos($parts[0], '=') === false) {
                $url = $parts[0];
            } else {
                $url = '';
            }
        }

        return $url;
    }

    /**
     * @return string
     */
    protected function getNamespace()
    {
        $namespace = 'Pemm\Controller\\';

        if (array_key_exists('namespace', $this->params)) {
            $namespace .= $this->params['namespace'] . '\\';
        }

        return $namespace;
    }
}
