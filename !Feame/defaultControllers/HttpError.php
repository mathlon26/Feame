<?php

class HttpError
{
    public static function Error($view, $params = [])
    {
        Controller::view($view, $params);
    }
}