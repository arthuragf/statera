<?php
namespace statera\core;
use statera\core\exceptions\NotFoundException;

class Router {
    protected array $aRoutes = [];
    public Request $clsRequest;
    public Response $clsResponse;

    public function __construct(Request $clsRequest, Response $clsResponse) {
        $this->clsRequest = $clsRequest;
        $this->clsResponse = $clsResponse;
    }

    public function get($path, $callback) {
        $this->aRoutes['get'][$path] = $callback;
    }

    public function post($path, $callback) {
        $this->aRoutes['post'][$path] = $callback;
    }

    public function resolve() {
        $sPath = $this->clsRequest->getPath();
        $sMethod = $this->clsRequest->getMethod();
        $fnCallback = $this->aRoutes[$sMethod][$sPath] ?? false;

        if ($fnCallback === false) {
            throw new NotFoundException();
        }

        if (is_string($fnCallback)) {
            return Application::$clsApp->clsView->renderView($fnCallback);
        }

        if (is_array($fnCallback)) {
            /**
             * @var statera\core\Controller $clsController
             */
            $clsController = new $fnCallback[0]();
            Application::$clsApp->clsController = $clsController;
            $clsController->sAction = $fnCallback[1];
            $fnCallback[0] = $clsController;
        }
        
        foreach ($clsController->getMiddlewears() as $middleware) {
            $middleware->execute();
        }
        return call_user_func($fnCallback, $this->clsRequest, $this->clsResponse);
    }
}