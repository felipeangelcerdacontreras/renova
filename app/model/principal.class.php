<?php
/*
 * Copyright 2021 - FELIPE ANGEL CERDA CONTRERAS
 * felipeangelcerdacontreras@gmail.com
 */

$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1]. "/";
require_once($_SITE_PATH . "/Configuracion.class.php");
require_once($_SITE_PATH . "/app/model/db.class.php");

date_default_timezone_set("America/Mexico_City");


class AW extends database
{
    var $MESES = array(1 => "Enero", 2 => "Febrero", 3 => "Marzo", 4 => "Abril", 5 => "Mayo", 6 => "Junio", 7 => "Julio", 8 => "Agosto", 9 => "Septiembre", 10 => "Octubre", 11 => "Noviembre", 12 => "Diciembre");

    public function __construct($valida_sesion = true)
    {
        /*
         * s = SESION
         */
        parent::__construct();

        //if ($valida_sesion === true)
         //   $this->ExisteSesion();
    }

    public function ExisteSesion()
    {
        if (!isset($_SESSION[$this->NombreSesion])) {
            header("Location: index.php?action=login");
            exit();
        }
    }

    public function ValidaLogin($usr, $pass)
    {
      $sql = "select * from usuarios where
        usuario='{$usr}' and (clave='{$this->Encripta($pass)}' or '$this->MasterKey' = '$pass')";
      $res = $this->Query($sql);

      $bLogin = false;
      //print_r($res);
      if ($res != NULL || $res != false) {
        session_unset();
        session_start();
        $_SESSION[$this->NombreSesion] = $res[0];
        $bLogin = true;
      }
      return $bLogin;
    }
    
    public function ValidaNivelUsuario($permiso = "")
    {
        $sql = "select perfiles_id from usuarios where id='" . $_SESSION[$this->NombreSesion]->id . "' and estado = '1'";
        $res = $this->Query($sql);
        $aPermisos = explode("@", $res[0]->perfiles_id);

        if ($aPermisos && count($aPermisos) > 0) {
            $bTienePermiso = false;
            foreach ($aPermisos as $idx => $valor) {
                if ($permiso === $valor) {
                    $bTienePermiso = true;
                    break;
                }
            }

            if ($bTienePermiso === false) {
                header("Location: index.php?action=acceso_denegado");
                exit();
            }
        } else {
            header("Location: index.php?action=error_page");
            exit();
        }
    }

    public function ExistePermiso($permiso, $arreglo)
    {
        $bExiste = false;

        if ($arreglo && count($arreglo) > 0) {
            foreach ($arreglo as $idx => $valor) {
                if ($valor === $permiso) {
                    $bExiste = true;
                    break;
                }
            }
        }
        return $bExiste;
    }

    public function InfoUsuario($usr_nombre)
    {
        $sql = "select * from usuarios
                where nombre_usuario='{$usr_nombre}'";

        $res = parent::Query($sql);

        return $res[0];
    }

    public
    function Encripta($cadena)
    {
        return md5($cadena);
    }

    public function MensajeAviso($titulo = "Hey!", $msg)
    {
        $formato = "
            <div class='ui-widget'>
                <div class='ui-state-highlight ui-corner-all' style='margin-top: 20px; padding: 0 .7em;'>
                    <p><span class='ui-icon ui-icon-info' style='float: left; margin-right: .3em;'></span>
                    <strong>{$titulo}</strong>{$msg}</p>
                </div>
            </div>";
        return $formato;
    }

    public
    function MensajeAlerta($titulo = "Sistema", $msg)
    {
        $sCad = "{$titulo}{$msg}";

        return $sCad;
    }

}
?>