<?php

namespace App\core;

use App\core\db\Database;

class App
{
    public string $layout = 'main';
    public Request $request;
    public Response $response;
    public session $session;
    public Router $router;
    public Controller $controller;
    public view $view;
    public Database $db;
    public static $app;
    public static $ROOT_DIR;
    public ?userModel $users = null;
    public string $userClass;

    public function __construct($path, $config)
    {
        $this->userClass = $config['user'];
        App::$ROOT_DIR = $path;
        App::$app = $this;
        $this->router = new Router();
        $this->response = new Response();
        $this->db = new Database($config['db']);
        $this->controller = new Controller();
        $this->session = new session();
        $this->request = new Request();
        $priamryValue = $this->session->get('user');

        if ($priamryValue) {
            $priamryKey = $this->userClass::primaryKey();
            if ($this->userClass::findOne([$priamryKey => $priamryValue])) {
                $this->users = $this->userClass::findOne([$priamryKey => $priamryValue]);
            } else {
                return false;
            }
        } else {
            $this->users = null;
        }
    }

    public static function isGuest()
    {
        return !self::$app->users;
    }

    public function login(userModel $users)
    {
        $this->users = $users;
        $priamryKey = $this->users->primaryKey();
        $priamryValue = $this->users->{$priamryKey};
        $this->session->set('user', $priamryValue);

        return true;
    }

    public function logout()
    {
        self::$app->users = null;

        return self::$app->session->remove('user');
    }

    public function run()
    {
        try {
            echo  $this->router->resolve();
        } catch (\Exception $e) {
            echo  app::$app->controller->render('Exception/_error', [
                'ex' => $e,
            ]);
        }
    }
}
