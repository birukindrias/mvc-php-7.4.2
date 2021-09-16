<?php

namespace App\core;

class Request
{
    public function getPath()
    {
        $path = $_SERVER['REQUEST_URI'];
        $position = strpos('?', $path);

        if ($position === false) {
            return $path;
        }

        return  substr($path, 0, $position);
    }

    public function method()
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    public function isGet()
    {
        if ($this->method() === 'get') {
            return true;
        }
    }

    public function isPost()
    {
        if ($this->method() === 'post') {
            return true;
        }
    }

    public function getBody()
    {
        $Body = [];
        if ($this->method() === 'get') {
            foreach ($_GET as $key => $value) {
                $Body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        if ($this->method() === 'post') {
            foreach ($_POST as $key => $value) {
                $Body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }

        return $Body;
    }
}
