<?php

namespace  App\core\Exception;
use Exception;

class ForediddenException extends Exception
{
    protected $code = 403;
    protected $message= 'you dont';
}