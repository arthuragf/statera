<?php
namespace statera\core;
use statera\core\middlewears\BaseMiddlewear;

class Controller {
    public string $sLayout = 'main';
    public string $sAction = '';
    /**
     * @var statera\core\middlewears\BaseMiddlewear
     */
    protected array $aMiddlewears = [];

    public function setLayout($sLayout) {
        $this->sLayout = $sLayout;
    }

    public function render($sView, $aParams = []) {
        return Application::$clsApp->clsView->renderView($sView, $aParams);
    }

    public function registerMiddlewear(BaseMiddlewear $clsMiddlewear) {
        $this->aMiddlewears[] = $clsMiddlewear;
    }

    public function getMiddlewears(): array {
        return $this->aMiddlewears;
    }
}