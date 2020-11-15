<?php
// 註1 self是參照到目前的class, :: 雙冒號運算符用來指向該 class 的靜態成員
// 問？$ROOT_DIR 若不宣告為 public，是否 self:: 就找不到 $ROOT_DIR ?
// 註2 $this是參照到目前的object ( 已經被宣告的實體上 )，只能用在 class 內
namespace app\core;

class Application
{
    public static string $ROOT_DIR;
    public Router $router;
    public Request $request;
    public Response $response;
    public static Application $app;
    public Controller $controller;
    public function __construct($rootPath)
    {
        self::$ROOT_DIR = $rootPath; // 註1
        self::$app = $this; // 註2
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router($this->request,$this->response);
    }

    public function run()
    {
        $this->router->resolve();
    }

    public function getController()
    {
        return $this->controller;
    }

    public function setController()
    {
        $this->controller = $controller;
    }
};
