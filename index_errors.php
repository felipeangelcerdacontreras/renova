<?php
/*
 * Copyright 2021 - Felipe angel cerda contreras 
 * cerda@redzpot.com
 *  */
$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "/app/controllers/mvc.controller.php");

$error = addslashes(filter_input(INPUT_GET, "error"));

$mvc = new mvc_controller();

if ($error >= 400 && $error <= 417){
    $mvc->error_page();
}