<?php
namespace statera\core;

class View {
    public string $sTitle = '';
    public array $aRequiredAssets = [];

    public function renderView($sView, $aParams = []) {
        $sViewContent = $this->getViewContent($sView, $aParams);
        $sLayoutContent = $this->getLayoutContent();
        return str_replace(
            '{{requiredAssets}}'
            , $this->loadAssets()
            , str_replace('{{content}}', $sViewContent, $sLayoutContent)
        );
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

    protected function loadAssets($aAssets = []) {
        $sRet = '';
        $aAssets = array_merge($this->aRequiredAssets, $aAssets);
        $sAssetsDir = Application::$ASSETS_DIR . DIRECTORY_SEPARATOR;
        foreach ($aAssets as $sExtension => $aFiles) {
            $sExtensionDir = $sAssetsDir . $sExtension . DIRECTORY_SEPARATOR;
            foreach ($aFiles as $sFile) {
                $sFilePath = $sExtensionDir . $sFile . '.' . $sExtension;
                $loadCallbackFn = 'load' . ucfirst($sExtension);
                if (is_file(Application::$PUBLIC_DIR . $sFilePath)){
                    $sRet .= $this->{$loadCallbackFn}($sFilePath);
                }
            }
        }
        return $sRet;
    }

    protected function loadJs(string $sFilePath) {
        return "<script type='text/javascript' src='$sFilePath'></script>\r\n";
    }

    protected function loadCss(string $sFilePath) {
        return "<link rel='stylesheet' href='$sFilePath'>\r\n";
    }

}