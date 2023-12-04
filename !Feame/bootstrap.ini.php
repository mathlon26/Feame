<?php
require_once "DoNotTouch/exceptions.php";
require_once "DoNotTouch/functions.php";
$BASE_DIR = "C:/Users/mathi/.xamp/htdocs/!Feame";

import("{$BASE_DIR}/config.php");
$config = new Config();

import("{$config->FEAME_PATH}/routing/App.php");
import("{$config->FEAME_PATH}/routing/Controller.php");
