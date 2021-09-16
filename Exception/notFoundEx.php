<?php

namespace  App\core\Exception;

use Exception;

class notFoundEx extends Exception
{
    protected $Code = 404;
    protected $Message = 'PAGE NOT FOUND';
}
