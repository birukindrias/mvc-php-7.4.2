<?php

namespace App\core;

use App\core\middlware\authMiddleware;
use App\core\middlware\BaseMiddleware;

class Controller extends view
{
    public string $Layout = 'main';
    public string $action = '';
    /**
     * @var \App\core\middlware\BaseMiddleware[]
     */
    protected array $middlwares = [];

    public function __construct()
    {
        $this->registerMiddleware(new authMiddleware(['profile']));
    }

    public function render($view, $params = [] ?? null)
    {
        return $this->renderview($view, $params);
    }

    public function setLayout($layout)
    {
        $this->Layout = $layout;
    }

    public function registerMiddleware(BaseMiddleware $middlware)
    {
        $this->middlwares[] = $middlware;
    }

    public function getMiddlewares()
    {
        return $this->middlwares;
    }
}
