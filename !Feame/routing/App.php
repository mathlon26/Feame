<?php
class App
{
    protected $controller = '';
    protected $controllerName = '';

    protected $method = '';
    protected $params = [];

    public function Start()
    {   
        $config = new Config();
        
        try {
            $controllers = $config->CONTROLLERS;
            foreach ($controllers as $item) {
                $filePath = "{$config->CONTROLLER_PATH}/{$item}.php";
                import($filePath);
            }
            
            $params = [];
            $url = [0 => ""];
            if(array_key_exists('url', $_REQUEST))
            {
                $url = explode('/', $_REQUEST['url']);
            }
            $request = $url;
            $defController = $config->CONTROLLER_DEFAULT;
            $defMethod = $config->METHOD_DEFAULT;
            $this->controller = new $defController;
            $this->method = $defMethod;
            if (!empty($request) && $request[count($request) - 1] === '') {
                // Remove the last element
                array_pop($request);
            }
            if ($config->DOWN === true && in_array(strtolower($request[0]), $config->LANGUAGES))
            {
                import("{$config->FEAME_PATH}/routing/Controller.php");
                if (isset($request[1])) {
                    if (ucfirst($request[1]) == "Admin") {
                        import("{$config->DEFAULTCONTROLLERS_PATH}/Admin.php");
                        $this->controller = new Admin;
                        $c = get_class($this->controller);
                        $this->method = "Login";
                        if (isset($request[2])) {
                            redirect("en/admin");
                        }
                        $request = [];
                    }
                    else if ($request[1] == "login_submit")
                    {
                        import("{$config->DEFAULTCONTROLLERS_PATH}/Admin.php");
                        $this->controller = new Admin;
                        $c = get_class($this->controller);
                        $this->method = "Login_Submit";
                        $request = [];

                    }
                    else if ($request[1] == "logout_submit")
                    {
                        import("{$config->DEFAULTCONTROLLERS_PATH}/Admin.php");
                        $this->controller = new Admin;
                        $c = get_class($this->controller);
                        $this->method = "Logout_Submit";
                        $request = [];
                    }
                    else
                    {
                        Controller::view($config->DOWN_VIEW);
                        exit();
                    }
                }
                else
                {
                    Controller::view($config->DOWN_VIEW);
                    exit();
                }
            }
            else
            {
                
                if (!in_array(strtolower($request[0]), $config->LANGUAGES))
                {
                    $redirect = [];
                    if (strlen($request[0]) > 2) {
                        $redirect = $request;
        
                    } else {
                        $redirect = array_slice($request, 1);
                    }
                    $redirectString = implode('/', array_merge(['en'], $request));
                    redirect($redirectString);
                }
                else if (!isset($request[1]) && in_array(strtolower($request[0]), $config->LANGUAGES))
                {
                    $this->controller = new $config->CONTROLLER_DEFAULT;
                    $this->method = "Index";
                    
                }
                else if (ucfirst($request[1]) == $defController)
                {
                    unset($request[0]);
                    unset($request[1]);
                    if(!isset($request[2]))
                    {
                        redirect("");
                    }
                    if (ucfirst($request[2]) == $defMethod)
                    {
                        $redirect = "";
                        if (isset($request[3]))
                        {
                            unset($request[2]);
                            $redirect = implode('', $request);
                            show([get_class($this->controller), $this->method]);
                        }
                        redirect($redirect);
                    }
                    else if (method_exists($this->controller, ucfirst($request[2])))
                    {
                        $redirect = $request[2];

                        if (isset($request[3]))
                        {
                            $redirect = implode('/', $request);
                        }
                        redirect($redirect);
                    }
                    else
                    {
                        throw new Exception('404');
                    }
                }
                else if (in_array(ucfirst($request[1]), $controllers))
                {
                    $this->controller = new $request[1];
                    unset($request[1]);
                    if (isset($request[2]) && method_exists($this->controller, ucfirst($request[2])))
                    {
                        if (ucfirst($request[2]) == $defMethod)
                        {
                            $redirect = get_class($this->controller);
                            if (isset($request[3]))
                            {
                                unset($request[2]);
                                $redirect = implode('/', $request);
                            }
                            redirect($redirect);
                        }
                        $this->method = ucfirst($request[2]);
                        unset($request[2]);
                    }
                    else
                    {
                        throw new Exception('404');
                    }
                }
                else if (method_exists($this->controller, ucfirst($request[1])))
                {
                    
                    if (ucfirst($request[1]) == $defMethod)
                    {
                        
                        unset($request[1]);
                        $redirect = $request[0];
                        if (isset($request[2]))
                        {
                            $redirect = implode('/', $request);
                        }
                    
                        redirect($redirect);
                    }
                    else
                    {
                        $this->method = ucfirst($request[1]);
                        unset($request[1]);
                    }
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
                redirect($redirect);
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


    private function handleException($exception)
    {
        $config = new Config();
        
        import("{$config->DEFAULTCONTROLLERS_PATH}/HttpError.php");

        $httpError = new HttpError();

        $errorMethod = "error/{$exception}";
        call_user_func_array([$httpError, 'error'], [$errorMethod]);

    }
    
}