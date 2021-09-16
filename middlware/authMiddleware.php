<?php

namespace App\core\middlware;

use App\core\App;
use App\core\Exception\ForediddenException;

class authMiddleware extends BaseMiddleware
{
    public array $actions = [];

    public function __construct(array $actions = [])
    {
        $this->actions = $actions;
    }

    public function execute()
    {
        if (App::isGuest()) {
            if (empty($this->actions) || in_array(App::$app->controller->action, $this->actions)) {
                throw new ForediddenException();
            }
        }
    }
}
