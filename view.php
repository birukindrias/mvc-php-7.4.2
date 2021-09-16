<?php

namespace App\core;

class view
{
    public string $title = '';

    public function renderview($view, $params = [] ?? null)
    {
        $mainCont = $this->mainCont($view, $params);
        $mainLayout = $this->mainLayout();

        return str_replace('{{cont}}', $mainCont, $mainLayout);
    }

    public function mainCont($view, $params = [] ?? null)
    {
        foreach ($params as $key => $value) {
            $$key = $value;
        }
        ob_start();
        include_once App::$ROOT_DIR."/views/$view.php";

        return ob_get_clean();
    }

    public function mainLayout()
    {
        $layout = App::$app->layout;
        if (App::$app->controller) {
            $layout = App::$app->controller->Layout;
        }
        ob_start();
        include_once App::$ROOT_DIR."/views/layout/$layout.php";

        return ob_get_clean();
    }
}
