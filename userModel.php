<?php

namespace App\core;

use App\core\db\dbModel;

abstract class userModel extends dbModel
{
    abstract public function namedispaly(): string;
}
