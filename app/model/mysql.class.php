<?php
require_once ($_SERVER['DOCUMENT_ROOT'] . "/geodesk/configuracion.php");

class MySQL {
    private $Host;
    private $Port;
    private $Usr;
    private $Pass;
    private $DataBase;
    private $Conectado;
    private $Datos;
    private $Link;
	private $oConfig;	

    //-----------------------------------------------------------------------------------
    function __construct() {
		
		$this->oConfig = new Configuracion();
		
        $this->Conectado = false;
        $this->Host = $this->oConfig->mysql_host;
        $this->Port = 3306;
        $this->Usr = $this->oConfig->mysql_user;
        $this->Pass = $this->oConfig->mysql_pass;
        $this->DataBase = $this->oConfig->mysql_database;

        $this->Link = @mysql_connect($this->Host . ":" . $this->Port, $this->Usr, $this->Pass);
		mysql_set_charset('utf8', $this->Link);
        if ($this->Link == false)
            throw new Exception("Conexi&oacute;n fallida al servidor {$this->Host}, {$this->Usr}/{$this->Pass}, Base de datos: {$this->DataBase}");
        else {
            $db_selected = @mysql_select_db($this->DataBase, $this->Link);
            if ($db_selected == false)
                throw new Exception($this->GetMySQLError());
            $this->Conectado = true;
        }
    }

    //-----------------------------------------------------------------------------------
    public function __destruct() {
        /*
         if ($this->Conectado == true)
         @mysql_close($this->Link);
         */
    }

    //-----------------------------------------------------------------------------------
    private function IsConected() {
        if ($this->Conectado == false) {
            throw new Exception("<p><b>No se encuentra conectado al servidor de base de datos.</b></p>");
            return false;
        }
        return true;
    }

    //-----------------------------------------------------------------------------------
    private function GetMySQLError() {
        return "Error [" . mysql_errno() . "]: " . mysql_error();
    }

    //-----------------------------------------------------------------------------------
    public function Query($sql) {
        $this->IsConected();

        $res = mysql_query($sql, $this->Link);
        if ($res == false) {
            throw new Exception($this->GetMySQLError() . "<br /><code>{$sql}</code>");
            return false;
        }

        $row = @mysql_fetch_object($res);
        if ($row == false) {
            return NULL;
        } else {
            while ($row == true) {
                $oRow[] = $row;
                $row = @mysql_fetch_object($res);
            }
        }

        unset($row);

        return $oRow;
    }

    //-----------------------------------------------------------------------------------
    public function NonQuery($sql) {
        $this->IsConected();

        $res = @mysql_unbuffered_query($sql, $this->Link);

        if ($res == false) {
            throw new Exception($this->GetMySQLError() . "<br /><code>SQL:{$sql}</code>");
            return false;
        }
        return true;
    }

    //-----------------------------------------------------------------------------------
    public function SelectDataBase($database) {
        $this->IsConected();

        $db_selected = @mysql_select_db($database, $this->Link);
        if ($db_selected == false) {
            throw new Exception($this->GetMySQLError());
            return false;
        }
        return true;
    }

    //-----------------------------------------------------------------------------------
}
?>