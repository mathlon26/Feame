<?php

class Home extends Controller
{
    
    public $IndexWildcards = [":lang", "?anchor"];
    public function Index($data)
    {
        $data['title'] = "Home";
        $lang = $this->parseLanguage($data);


        Controller::view("home{$lang}/home", $data);
    }


    public $ContactWildcards = [":lang", "?anchor"];
    public function Contact($data)
    {
        $data['title'] = "Contact";
        $lang = $this->parseLanguage($data);


        Controller::view("home{$lang}/contact", $data);
    }


    public function parseLanguage($data)
    {
        $lang = $data['Wildcards']['lang'];
        if ($lang == 'en' || $lang == null)
        {
            return "/en";
        }
        else if ($lang == 'nl' || $lang == 'be')
        {
            return '/nl';
        }
        else
        {
            return "/en";
        }
    }
}