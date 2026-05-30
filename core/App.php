<?php
class App {
    protected $controller = 'AuthController';
    protected $method = 'index';
    protected $params = [];

    public function __construct() {
        $url = $this->parseUrl();

        $controllerFile = BASE_PATH . '/app/controllers/' . ucfirst($url[0] ?? 'Auth') . 'Controller.php';

        if (file_exists($controllerFile)) {
            $this->controller = ucfirst($url[0]) . 'Controller';
            array_shift($url);
        }

        require_once BASE_PATH . '/app/controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller;

        if (isset($url[0]) && method_exists($this->controller, $url[0])) {
            $this->method = $url[0];
            array_shift($url);
        }

        $this->params = $url ? array_values($url) : [];
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    protected function parseUrl() {
    if (isset($_GET['url'])) {
        return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
    }
    return ['home'];
}
}