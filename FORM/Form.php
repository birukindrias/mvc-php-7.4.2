<?php

namespace App\core\FORM;

use App\core\Model;

class Form
{
    public static function begin($action, $method)
    {
        echo sprintf('<form action="%s" method="%s"', $action, $method);

        return new Form();
    }

    public static function end()
    {
        echo '</form>';
    }

    public static function field(Model $model, $attr)
    {
        return new Field($model, $attr);
    }

    public static function textarea(Model $model, $attr)
    {
        return new textarea($model, $attr);
    }
}
