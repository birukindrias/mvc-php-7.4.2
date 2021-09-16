<?php

namespace App\core\FORM;

use App\core\Model;
use App\models\chats;

class Field extends Base
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
        $uniq = new chats();

        return sprintf('
        %s
        <input name="%s"  type="%s" class="form-control">
        
        ',
        $this->model->getlabel($this->attr),
        $this->attr,
        $this->typepass($this->attr), );
    }
}
