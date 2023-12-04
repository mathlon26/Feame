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

    public function __construct()
    {
        $this->ROOT = "C:/Users/mathi/.xamp/htdocs";
        $this->FEAME_PATH = "{$this->ROOT}/!Feame";
        $this->APP_PATH = "{$this->ROOT}/App";

        /*
            Individual module path configuration
        */
        $this->MODEL_PATH = "{$this->APP_PATH}/Models";
        $this->VIEW_PATH = "{$this->APP_PATH}/Views";
        $this->CONTROLLER_PATH = "{$this->APP_PATH}/Controllers";
        $this->DEFAULTCONTROLLERS_PATH = "{$this->FEAME_PATH}/defaultControllers";
        $this->FOURZEROFOUR = "error/404";
        $this->FOURZEROTHREE = "error/403";

        $this->TEMPLATE_PATH = "{$this->APP_PATH}/phpTemplates";
        $this->DOMAIN = "http://localhost";

        $this->PUBLIC_FOLDER = "/public";
        
        $this->CONTROLLERS = [
            "Home"
        ];

    }
}
