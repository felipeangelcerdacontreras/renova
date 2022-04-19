<?php
$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "Configuracion.class.php");


class database extends Configuracion
{
    private $Link;
    var $id_empleado;
    // -----------------------------------------------------------------------------------
    public function __construct()
    {
        parent::__construct();

        $this->Link = mysqli_connect($this->mysql_host, $this->mysql_user, $this->mysql_pass, $this->mysql_database);
        @mysqli_set_charset($this->Link, 'utf8');

        if ($this->Link === false) {
            echo "Base de Datos: ". "Conexión fallida al servidor {$this->mysql_host}, {$this->mysql_user}/{$this->mysql_pass}, Base de datos: {$this->mysql_database}";
            exit ();
        } else {
            $db_selected = mysqli_select_db($this->Link, $this->mysql_database);
            if ($db_selected === false) {
                echo "Base de Datos: ". $this->GetMySQLError();
                exit ();
            }
        }
    }

    // -----------------------------------------------------------------------------------
    public function __destruct()
    {
        if ($this->Link !== false)
            mysqli_close($this->Link);
    }

    // -----------------------------------------------------------------------------------
    private function IsConected()
    {
        if ($this->Link === false) {
            echo "Base de Datos: "."<p><b>No se encuentra conectado al servidor de base de datos.</b></p>";
            return false;
        }
        return true;
    }

    // -----------------------------------------------------------------------------------
    private function GetMySQLError()
    {
        return "Base de Datos: ". "Error [" . mysqli_errno($this->Link) . "]: " . mysqli_errno($this->Link);
    }

    private function GuardarBitacora($sql) {
		$palabra = Array();
		$palabra = explode (" ", $sql);
		$fecha = date("Y-m-d");

        if ($palabra[0] == "insert" || $palabra[0] == "INSERT") {
            $sqlBitacor = "INSERT into asistencia_backup (`insert`, `fecha`, `id_empleado`) 
                VALUES
				('".addslashes($sql)."','".$fecha."', '{$this->id_empleado}');";
            $this->IsConected();
			$res = mysqli_query($this->Link, $sqlBitacor);
            unset($res);
        } else if($palabra[0] == "update" || $palabra[0] == "UPDATE") {
            $sqlBitacor = "UPDATE `asistencia_backup`
            SET
            `update` = '".addslashes($sql)."'
            WHERE `id_empleado` = '{$this->id_empleado}' and fecha = '{$fecha}'";

            $this->IsConected();
            $res = mysqli_query($this->Link, $sqlBitacor);
            unset($res);
        }
    }

    // -----------------------------------------------------------------------------------
    public function Query($sql)
    {
        $this->IsConected();

        $res = mysqli_query($this->Link, $sql);

        if ($res === false) {
            echo "Base de Datos: ". $this->GetMySQLError() . "<br /><code>{$sql}</code>";
            return false;
        }

        $oRow = array();
        $row = @mysqli_fetch_object($res);
        if ($row === false) {
            return NULL;
        } else {
            while ($row == true) {
                $oRow [] = $row;
                $row = @mysqli_fetch_object($res);
            }
        }

        unset ($row);
        mysqli_free_result($res);

        return $oRow;
    }

    // -----------------------------------------------------------------------------------
    public function BeginTransaction($sql)
    {
        $this->IsConected();

        $res = mysqli_query($this->Link, $sql);
        return $res;
    }

    // -----------------------------------------------------------------------------------
    public function NonQuery($sql, $bGuardarBitacora = true)
    {
        $this->IsConected();

        $pos = strpos($sql, '|');

        if ($pos === false) { 
            $res = mysqli_query($this->Link, $sql);
		
            if ($res == false) {
                echo "Base de Datos: ". $this->GetMySQLError() . "<br /><code>!SQL:{$sql}</code>";
                return false;
            }
    
            unset($res);
    
            if (! $bGuardarBitacora)
                $this->GuardarBitacora($sql);
        } else {
            $insertNon = explode("|", $sql);
            $id_empleado = $insertNon[0];
            $insert = $insertNon[1];
            $this->id_empleado = $id_empleado;

            $res = mysqli_query($this->Link, $insert);
		
            if ($res == false) {
                echo "Base de Datos: ". $this->GetMySQLError() . "<br /><code>?SQL:{$insert}</code>";
                return false;
            }
    
            unset($res);
            if (! $bGuardarBitacora)
                $this->GuardarBitacora($insert);
            
        }
       

        return true;
    }

    // -----------------------------------------------------------------------------------
    public function SelectDataBase($database)
    {
        $this->IsConected();

        $db_selected = @mysqli_select_db($this->Link, $database);
        if ($db_selected === false) {
            echo "Base de Datos: ". $this->GetMySQLError();
            return false;
        }
        return true;
    }
    // ------------------------------------------------------------------------------

}

?>