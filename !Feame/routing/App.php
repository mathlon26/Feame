<?php
class App
{
    protected $controller = '';
    protected $controllerName = '';

    protected $method = '';
    protected $params = [];

    public function Start()
    {   
        try {
            $config = new Config();
            $controllers = $config->CONTROLLERS;
            foreach ($controllers as $item) {
                $filePath = "{$config->CONTROLLER_PATH}/{$item}.php";
                import($filePath);
            }
            $params = [];
            $url = [0 => "/"];
            if(array_key_exists('url', $_REQUEST))
            {
                $url = explode('/', $_REQUEST['url']);
            }

            $request = $url;
            $defController = $config->CONTROLLER_DEFAULT;
            $defMethod = $config->METHOD_DEFAULT;
            $this->controller = new $defController;
            $this->method = $defMethod;
            if ($request[0] == "/")
            {
                unset($request[0]);
            }
            else if (ucfirst($request[0]) == $defController)
            {
                unset($request[0]);
                if(!isset($request[1]))
                {
                    $this->redirect("");
                }
                if (ucfirst($request[1]) == $defMethod)
                {
                    $redirect = "";
                    if (isset($request[2]))
                    {
                        unset($request[1]);
                        $redirect = implode('', $request);
                        show([get_class($this->controller), $this->method]);
                    }
                    $this->redirect($redirect);
                }
                else if (method_exists($this->controller, ucfirst($request[1])))
                {
                    $redirect = $request[1];

                    if (isset($request[2]))
                    {
                        $redirect = implode('/', $request);
                    }
                    $this->redirect($redirect);
                }
                else
                {
                    throw new Exception('404');
                }
            }
            else if (in_array(ucfirst($request[0]), $controllers))
            {
                $this->controller = new $request[0];
                unset($request[0]);
                if (isset($request[1]) && method_exists($this->controller, ucfirst($request[1])))
                {
                    if (ucfirst($request[1]) == $defMethod)
                    {
                        $redirect = get_class($this->controller);
                        if (isset($request[2]))
                        {
                            unset($request[1]);
                            $redirect = implode('/', $request);
                        }
                        $this->redirect($redirect);
                    }
                    $this->method = ucfirst($request[1]);
                    unset($request[1]);
                }
                else
                {
                    throw new Exception('404');
                }
            }
            else if (method_exists($this->controller, ucfirst($request[0])))
            {
                if (ucfirst($request[0]) == $defMethod)
                {
                    unset($request[0]);
                    $redirect = "";
                    if (isset($request[1]))
                    {
                        $redirect = implode('', $request);
                    }
                    $this->redirect($redirect);
                }
                else
                {
                    $this->method = ucfirst($request[0]);
                    unset($request[0]);
                }
            }
            $params = array_values($request); // example: ["459", "ed84"] in the browser: https://domain.com/controller/method/459/ed84/
            $wildcards = "{$this->method}Wildcards"; // example: [":id", "?redirect"]
            $requiredQueries = [];
            $requiredWildcards = [];
            
            $i = 0;
            foreach ($this->controller->$wildcards as $value) {
                if (strpos($value, ':') === 0)
                {
                    $i++;
                }
            }
            if (count($params) > $i )
            {
                $contr = get_class($this->controller);
                $par = implode('/', arrChop(array_values($params), $i));
                show($par);
                $redirect = "{$contr}/{$this->method}/{$par}";
                show($redirect);
                $this->redirect($redirect);
            }
            

            foreach ($this->controller->$wildcards as $value) {
                if (strpos($value, ':') === 0) {
                    $value = substr($value, 1);
                    $requiredWildcards[$value] = array_shift($params);
                } elseif (strpos($value, '?') === 0) {
                    $queryParam = substr($value, 1);
                    $requiredQueries[$queryParam] = isset($_GET[$queryParam]) ? $_GET[$queryParam] : null;
                } else {
                    $wildcardKey = "{$value}";
                    $requiredWildcards[$wildcardKey] = array_shift($params);
                }
            }

            
            $params = ["Wildcards" => $requiredWildcards, "Queries" => $requiredQueries];

            call_user_func_array([$this->controller, $this->method], [$params]);
        }
        catch (Exception $e) {
            $this->handleException($e);
        }
    } 

    public function redirect($redirect)
    {
        $config = new Config;
        $string = strtolower("{$config->DOMAIN}{$config->PUBLIC_FOLDER}/{$redirect}");

        header("Location: {$string}");
        exit();
    }

    private function handleException($exception)
    {
        $config = new Config();
        
        import("{$config->DEFAULTCONTROLLERS_PATH}/HttpError.php");

        $httpError = new HttpError();

        $errorMethod = "error/{$exception}";
        call_user_func_array([$httpError, 'error'], [$errorMethod]);

    }
    
}