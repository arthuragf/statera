<?php
namespace statera\core;

class View {
    public string $sTitle = '';

    public function renderView($sView, $aParams = []) {
        $sViewContent = $this->getViewContent($sView, $aParams);
        $sLayoutContent = $this->getLayoutContent();
        return str_replace('{{content}}', $sViewContent, $sLayoutContent);
    }

    protected function getLayoutContent() {
        $sLayout = Application::$clsApp->sLayout;
        if (Application::$clsApp->clsController) {
            $sLayout = Application::$clsApp->clsController->sLayout;
        }
        ob_start();
        include_once Application::$ROOT_DIR . "/views/layouts/$sLayout.php";
        return ob_get_clean();

    }

    protected function getViewContent($sView, $aParams) {
        extract($aParams);
        ob_start();
        include_once Application::$ROOT_DIR . "/views/$sView.php";
        return ob_get_clean();

    }

}