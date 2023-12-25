<?php
class Config
{
    /*
        Global project path configuration
    */
    public $DOMAIN;
    public $ROOT;
    public $FEAME_PATH;
    public $APP_PATH;

    /*
        Individual module path configuration
    */
    public $MODEL_PATH;
    public $VIEW_PATH;
    public $CONTROLLER_PATH;
    public $TEMPLATE_PATH;
    public $DEFAULTCONTROLLERS_PATH;
    public $DB_PATH;
    public $DB_PREFIX;

    /*
        Individual Error view names
    */
    public $FOURZEROFOUR;
    public $FOURZEROTHREE;

    /*
        Routing:
    */
    public $CONTROLLER_DEFAULT = "Home";
    public $METHOD_DEFAULT = "Index";
    public $CONTROLLERS;
    public $PUBLIC_FOLDER;
    public $DOWN;
    public $DOWN_VIEW;


    public $LANGUAGES;



    public function __construct()
    {
        $this->ROOT = "D:/XAMPP/htdocs";
        $this->FEAME_PATH = "{$this->ROOT}/!Feame";
        $this->APP_PATH = "{$this->ROOT}/App";
        $this->DOMAIN = "http://localhost";
        $this->PUBLIC_FOLDER = "/public";

        /*
            Individual module path configuration
        */
        $this->MODEL_PATH = "{$this->APP_PATH}/Models";
        $this->VIEW_PATH = "{$this->APP_PATH}/Views";
        $this->CONTROLLER_PATH = "{$this->APP_PATH}/Controllers";
        $this->DEFAULTCONTROLLERS_PATH = "{$this->FEAME_PATH}/defaultControllers";
        $this->TEMPLATE_PATH = "{$this->APP_PATH}/phpTemplates";
        $this->DB_PATH = "{$this->FEAME_PATH}/database";
        $this->DB_PREFIX = "class_"; // see dashboard for more info on fixes

        $this->FOURZEROFOUR = "error/404"; // view
        $this->FOURZEROTHREE = "error/403"; // view

        $this->DOWN = false;
        $this->DOWN_VIEW = "error/down";

        $this->LANGUAGES = [
            "en",
            "nl"
        ];

        $this->CONTROLLERS = [
            "Home"
        ];

        

    }
}
