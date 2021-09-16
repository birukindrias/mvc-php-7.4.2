<?php

namespace App\core\FORM;

use App\core\Model;

abstract class Base
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
        $this->model = $model;
        $this->attr = $attr;
    }

    public function typepass($attr)
    {
        if ($attr === 'pass' || $attr === 'cpass') {
            return $this->type = self::TYPE_PASS;
        }

        return $this->type = 'text';
    }

    public function __toString()
    {
        return sprintf('  <div class="mb-3">
        
        <div id="emailHelp" class="form-text">%s</div>
      </div> ',
      $this->input(),
      $this->model->firstError($this->attr),
    );
    }

    public function input()
    {
        return sprintf('
        <input name="%s" type="%s" class="form-control">
        
        ',
        $this->attr,
        $this->typepass($this->attr), );
    }
}
