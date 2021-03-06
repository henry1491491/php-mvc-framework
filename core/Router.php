<?php
// c1 str_replace()
// c2 call_user_func(callback)，用來執行 callback，callback 要是個 function
// c3 將 this 丟進 function
namespace app\core;
use app\core\Application; // ??

class Router
{
    public Request $request;
    public Response $response;
    protected array $routes = [];

    /**
     * Router constructor.
     * 
     * @param \app\core\Request $request
     * @param \app\core\Response $response
     */
    public function __construct(Request $request,Response $response)
    {
        
        $this->request = $request;
        $this->response = $response;
    }

    public function get($path, $callback)
    {
        $this->routes['get'][$path] = $callback;
    }

    public function post($path, $callback)
    {
        $this->routes['post'][$path] = $callback;
    }

    public function resolve()
    {
        $path = $this->request->getPath();
        $method = $this->request->method();
        $callback = $this->routes[$method][$path] ?? false;
    
        if ($callback === false) {
            $this->response->setStatusCode(404);
            return $this->renderView('_404');
        };
        if(is_string($callback)) {
            return $this->renderView($callback);
        };
        if(is_array($callback)) {
            Application::$app->controller = new $callback[0]();
            $callback[0] = Application::$app->controller;
        }
        return call_user_func($callback,$this->request);//c2,c3
    }

    public function renderView($view,$params = [])
    {
        $layoutContent = $this->layoutContent();
        $viewContent = $this->renderOnlyView($view,$params);
        return str_replace('{{content}}',$viewContent,$layoutContent);//c1
        //include_once Application::$ROOT_DIR."/views/$views.php";
    }

    public function renderContent($viewContent)
    {
        $layoutContent = $this->layoutContent();
        //$viewContent = $this->renderOnlyView($view);
        return str_replace('{{content}}',$viewContent,$layoutContent);
        //include_once Application::$ROOT_DIR."/views/$views.php";
    }

    protected function layoutContent()
    {
        $layout = Application::$app->controller->layout;
        ob_start();
        include_once Application::$ROOT_DIR."/views/layouts/$layout.php";
        return ob_get_clean();
    }
    protected function renderOnlyView($view,$params)
    {
        foreach ($params as $key => $value) {
            $$key = $value;
        };
        ob_start();
        include_once Application::$ROOT_DIR."/views/$view.php";
        return ob_get_clean();
    }
}
