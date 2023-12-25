<?php

class Home extends Controller
{
    
    public $IndexWildcards = [":lang", "?anchor"];
    public function Index($data)
    {
        $data["private"] = true; // Require login
        $data['title'] = "Home";

        Controller::view("home/home", $data);
    }
}