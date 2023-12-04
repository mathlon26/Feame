<?php
class App
{
    protected $controller = '';
    protected $method = '';
    protected $params = '';

    public function Start()
    {
        $config = new Config;
        $hasController = false;
        $hasMethod = false;
        $url = $this->parseUrl();
        $request = $url;

        // Always
        $this->controller = $config->CONTROLLER_DEFAULT;
        $this->method = $config->METHOD_DEFAULT;

        // Handle default
        if ($request[0] === "/")
        {
            import ("{$config->CONTROLLER_PATH}/{$config->CONTROLLER_DEFAULT}.php");
            $this->controller = new $config->CONTROLLER_DEFAULT;
            $this->method = $config->METHOD_DEFAULT;
            unset($request[0]);
        }
        else if ($request[0] !== $config->CONTROLLER_DEFAULT)
        {
            $fileName = ucfirst($request[0]);
            if (file_exists("{$config->CONTROLLER_PATH}/{$fileName}.php"))
            {
                import ("{$config->CONTROLLER_PATH}/{$fileName}.php");
                $this->controller = new $request[0];
                unset($request[0]);

                if (array_key_exists(1, $request))
                {
                    if ($request[1] !== $config->METHOD_DEFAULT && method_exists($this->controller, $request[1]))
                    {
                        $this->method = $request[1];
                        unset($request[1]);
                    }
                }
            }
            else
            {
                import ("{$config->CONTROLLER_PATH}/{$config->CONTROLLER_DEFAULT}.php");
                if (method_exists(new $this->controller, $request[0]))
                {
                    $this->controller = new $config->CONTROLLER_DEFAULT;
                    $this->method = $request[0];
                    unset($request[0]);
                }
                else
                {
                    $fileName = "HttpError";
                    import ("{$config->DEFAULTCONTROLLERS_PATH}/{$fileName}.php");
                    $this->controller = new HttpError;
                    $this->method = "Error";
                    call_user_func_array([$this->controller, $this->method], ["errors/404"]);
                    exit();
                }
            }
            
        }
        else if (ucfirst($request[0]) == $config->CONTROLLER_DEFAULT)
        {
            unset($request[0]);
            $req = array_values($request);
            if($req[0] == $config->METHOD_DEFAULT)
            {
                unset($request[1]);
                $redirect = "/";
                if (isset($req[1]))
                {
                    $redirect = implode('/', $req);
                }
                $this->redirect($redirect);
            }
            else if(!isset($req[0]))
            {
                $this->redirect("/");
            }
            else
            {
                $C = "{$config->CONTROLLER_PATH}/{$this->controller}.php";
                if (file_exists($C))
                {
                    import($C);
                    $this->method = method_exists(new $this->controller, $req[0]) ? $req[0] : $this->method;
                }
                $redirect = "{$this->method}";
                if (isset($req[1]))
                {
                    $redirect = implode("{$this->method}", $req);
                }
                show($redirect);
                $this->redirect("/{$redirect}");
            }
        }
        else if (ucfirst($request[0]) == $config->METHOD_DEFAULT)
        {
            /*
            $redirect = "";
            if (isset($request[1]))
            {
                $redirect = implode("", $req);
            }
            $this->redirect("/{$redirect}");*/

        }
        
        
        

        
        
        $m = ucfirst($this->method);
        $wildcards = "{$m}Wildcards";

        $request = array_values($request);
        $fileName = is_object($this->controller) ? get_class($this->controller) : $this->controller;
        import("{$config->CONTROLLER_PATH}/{$fileName}.php");
        $contr = gettype($this->controller) == "Obj" ? $this->controller : new $this->controller;
        $paramKeys = $contr->$wildcards;
        if (count($paramKeys) > 0)
        {
            $nParamKeys = count($paramKeys);
        }
        else
        {
            $nParamKeys = 0;
        }
        $params = [];


        if (count($request) > $nParamKeys)
        {
            $redirectParams = array_slice($request, 0, $nParamKeys);
            $redirectLocation = implode('/', [$fileName, $this->method] + $redirectParams);
            redirect($redirectLocation);
            exit();
        }

        for ($i = 0; $i < $nParamKeys; $i++)
        {
            $key = $paramKeys[$i];
            $value = array_key_exists($i, $request) ? $request[$i]: null;

            $params[$key] = $value;
        }

        if ($fileName == $config->CONTROLLER_DEFAULT && $this->method == $config->METHOD_DEFAULT && $url[0] != "/")
        {
            header("Location: {$config->DOMAIN}public/"); //note public included?
        }

        call_user_func_array([$this->controller, $this->method], [$params]);
    }



    public function parseUrl()
    {
        if(array_key_exists('url', $_REQUEST))
        {
            return explode('/', $_REQUEST['url']);
        }
        else
        {
            return [0 => "/"];
        }
    }

    public function redirect($redirect)
    {
        header("Location: {$config->DOMAIN}/public{$redirect}");
        exit();
    }
}
