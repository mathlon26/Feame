<?php

class Controller extends App
{
    public static function extract_file_variables($path)
    {
        $tokens    = token_get_all(file_get_contents($path));
        $variables = [];

        $temp_var = null;

        foreach ($tokens as $token)
        {
            if (!is_array($token)) continue;

            $value = trim($token[1], "'\"");

            if ($token[0] === T_VARIABLE)
            {
                $temp_var = substr($value, 1);
            }

            if (!isset($variables[$temp_var]) && $temp_var !== null && in_array($token[0], [
                T_CONSTANT_ENCAPSED_STRING, T_LNUMBER, T_DNUMBER,
            ], true))
            {
                $variables[$temp_var] = $value;

                $temp_var = null;
            }
        }

        return $variables;
    }
    

    public static function model($model)
    {
        $config = new Config();
        import("{$config->MODEL_PATH}/{$model}.php");
    }

    public static function view($view, $data = [])
    {
        if (isset($data["private"]))
        {
            if ($data["private"])
            {

            }
        }
        $lang = parseLanguage($data);
        $pathParts = explode('/', $view);
        array_splice($pathParts, 1, 0, $lang);
        $view = implode('/', $pathParts);
        $config = new Config();
        $viewPath = "{$config->VIEW_PATH}/{$view}.php";
        $variables = self::extract_file_variables($viewPath);
        $template = isset($variables['template']) ? $variables['template'] : null;
        $data['view_variables'] = $variables;
        if ($template != null)
        {
            import("{$config->TEMPLATE_PATH}/{$template}.php", $data, $viewPath);
        }
        else
        {
            import("{$config->VIEW_PATH}/{$view}.php", $data);
        }
        import("{$config->VIEW_PATH}/{$view}.php", $data);
    }

}