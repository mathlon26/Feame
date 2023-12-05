<?php

function show($data)
{
    echo "<pre>";
    print_r($data);
    echo "</pre>";
}

function import($path, $data = [], $view = null)
{
    try
    {
        if(file_exists($path))
        {
        require_once($path);
        }
        else
        {
            throw new RequireError($path);
        }
    }
    catch (RequireError $e)
    {
        echo "Caught exception: " . $e->getMessage();
    }
}

function arrChop($arr, $n)
{
    $output = [];

    foreach ($arr as $key => $element) {
        if ($key === $n) {
            break;
        }
        $output[] = $element;
    }

    return $output;
}

function redirect($redirect)
{
    $config = new Config;
    $string = strtolower("{$config->DOMAIN}{$config->PUBLIC_FOLDER}/{$redirect}");

    header("Location: {$string}");
    exit();
}