<?php

namespace App\core;

class Response
{
    public function redirect($url)
    {
        header("location: $url");
    }
}
