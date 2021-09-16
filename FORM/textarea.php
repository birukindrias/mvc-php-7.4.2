<?php

namespace App\core\FORM;

use App\core\Model;

class textarea extends Base
{
    public string $type = 'text';
    public const TYPE_PASS = 'password';
    public Model $model;
    public string $attr;

    /**
     * @param string $attr
     */
    public function __construct(Model $model, $attr)
    {
        parent::__construct($model, $attr);
    }

    public function typepass($attr)
    {
        if ($attr === 'pass' || $attr === 'cpass') {
            return $this->type = self::TYPE_PASS;
        }

        return $this->type = 'text';
    }

    public function input()
    {
        return sprintf('
        <input name="%s" type="%s" col="%s" row="%s" class="form-control">
        
        ',
        $this->attr,
        $this->typepass($this->attr),
    4,
    4,
    );
    }
}
