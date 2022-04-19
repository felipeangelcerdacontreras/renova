<?php

/*
 * Copyright 2021 - Felipe angel cerda contreras 
 * felipeangelcerdacontreras@gmail.com
 *  */
class Configuracion {
	protected $mysql_host;
	protected $mysql_user;
	protected $mysql_pass;
	protected $mysql_database;
    public $NombreSesion;
    protected $MasterKey;
    protected $RutaAbsoluta;

	public function __construct() {
        $this->mysql_database = "renova";
        $this->mysql_host = "localhost";
        $this->mysql_user = "root";
        $this->mysql_pass = "";
        $this->NombreSesion = "RENOVAMX";
        $this->MasterKey = "GustavoRenovaMx";
        $this->API_KEY = "AIzaSyDNuQjcMaL880tNTT_rY6X3G6DhiMqSDFw";
        $this->RutaAbsoluta = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
	}
}
/**
 *      $this->mysql_database = "renovamx_db_2021_10_12";
 *       $this->mysql_host = "renova-mx.com";
 *      $this->mysql_user = "renovamx_db_user";
 *       $this->mysql_pass = "@DXTLS2021*";
 */
?>
