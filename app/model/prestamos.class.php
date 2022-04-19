<?php
/*
 * Copyright 2021 - Felipe angel cerda contreras 
 * felipeangelcerdacontreras@gmail.com
 */
$_SITE_PATH = $_SERVER["DOCUMENT_ROOT"] . "/" . explode("/", $_SERVER["PHP_SELF"])[1] . "/";
require_once($_SITE_PATH . "/app/model/principal.class.php");

class prestamos extends AW
{

    var $id;
    var $id_empleado;
    var $numero_semanas;
    var $monto;
    var $fecha_registro;
    var $fecha_pago;
    var $estatus;
    var $interes;
    var $monto_por_semana;
    var $monto_pagar;
    var $restante;
    var $semana_actual;
    var $user_id;
    //sumar prestamo
    VAR $prestamo;
    //actualizar prestamo
    var $id_prestamo;
    var $Semanas;

    //busqueda 
    var $estatus1;

    public function __construct($sesion = true, $datos = NULL)
    {
        parent::__construct($sesion);

        if (!($datos == NULL)) {
            if (count($datos) > 0) {
                foreach ($datos as $idx => $valor) {
                    if (gettype($valor) === "array") {
                        $this->{$idx} = $valor;
                    } else {
                        $this->{$idx} = addslashes($valor);
                    }
                }
            }
        }
    }

    public function Listado()
    {
        $sqlEstatus = "";
        if ($this->estatus1 != '') {
            $sqlEstatus = "{$this->estatus1}";
        }

        $sqlListado = "";
        if ($this->fecha_registro != '') {
            $sqlListado = "fecha_registro = '{$this->fecha_registro}' and id_empleado = '{$this->id_empleado}'";
        } else {
            $sqlListado = " a.estatus like '%{$sqlEstatus}%' and fecha_pago between '{$this->fecha_inicial}' and '{$this->fecha_final}'";
        }

        $sql = "SELECT a.*, CASE WHEN a.estatus = 0 THEN
            'LIQUIDADO'
        WHEN a.estatus = 1 THEN
            'PAGANDO'
        ELSE
            'OTRO'
        END AS est,
        b.nombres, b.ape_paterno, b.ape_materno
        FROM prestamos AS a
        LEFT JOIN empleados AS b ON a.id_empleado = b.id
        where {$sqlListado}
        ORDER BY
            a.id ASC";
        return $this->Query($sql);
    }

    public function Informacion()
    {
        $sqlFecha = "";
        if (!empty($this->fecha)){
            $sqlFecha = "id_empleado = '{$this->id_empleado}' and fecha_pago = '{$this->fecha}'";
        } else {
            $sqlFecha = "id='{$this->id}'";
        }

        $sql = "select * from prestamos where {$sqlFecha}";
        $res = parent::Query($sql);

        if (!empty($res) && !($res === NULL)) {
            foreach ($res[0] as $idx => $valor) {
                $this->{$idx} = $valor;
            }
        } else {
            $res = NULL;
        }

        return $res;
    }
    public function AhorroActivo()
    {
        $sqlPrestamo="";
        if (! empty($this->prestamo)) {
            $sqlPrestamo = " estatus='1' and id_empleado='{$this->id_empleado}'";
        }

        $sql = "select * from prestamos where {$sqlPrestamo}";
        $res = parent::Query($sql);

        if (!empty($res) && !($res === NULL)) {
            foreach ($res[0] as $idx => $valor) {
                $this->{$idx} = $valor;
            }
        } else {
            $res = NULL;
        }

        return $res;
    }

    public function PrestamoActivo()
    {
        $sql = "SELECT * FROM prestamos where id_empleado = '{$this->id_empleado}' and fecha_pago = '{$this->fecha_pago}' and estatus = '1'";
        $res = parent::Query($sql);

        if (!empty($res) && !($res === NULL)) {
            foreach ($res[0] as $idx => $valor) {
                $this->{$idx} = $valor;
            }
        } else {
            $res = NULL;
        }

        return $res;
    }

    public function Liquidar()
    {

        $sql = "update
                    prestamos
                set
                    estatus = '{$this->estatus}'
                where
                  id='{$this->id}'";
        return $this->NonQuery($sql);
    }

    public function Existe()
    {
        $sql = "select id from prestamos where estatus='1' and id_empleado='{$this->id_empleado}'";
        //print_r($sql);
        $res = $this->Query($sql);

        $bExiste = false;

        if (count($res) > 0) {
            $bExiste = true;
        }
        return $bExiste;
    }

    public function Actualizar($id_prestamo,$Semanas)
    {
        $sql = "update
                    prestamos
                set
                estatus ='0',
                restante = '0',
                semana_actual =  '{$Semanas}'
                where
                  id='{$id_prestamo}'";
        //print_r($sql);
        return $this->NonQuery($sql);
    }

    public function Agregar()
    {
        $restanteCantidad = 0;
        if (!empty($this->restante)) {
            $restanteCantidad = $this->monto + $this->restante;
        } else {
            $restanteCantidad = $this->monto;
        }
        
        $interes = $this->numero_semanas * 1.25;
        $cantidad = ($restanteCantidad * $interes) / 100;
        $monto_pagar = $restanteCantidad + $cantidad;
        $interes_pagar = $monto_pagar - $restanteCantidad;
        $monto_por_semana = $monto_pagar / $this->numero_semanas;

        $sql = "insert into prestamos
                (`id`,`id_empleado`,`monto`,`interes`,`monto_por_semana`,`numero_semanas`,`semana_actual`,`fecha_registro`,`fecha_pago`,`monto_pagar`,`estatus`,`restante`)
                values
                ('0','{$this->id_empleado}','".$restanteCantidad."','$interes_pagar','$monto_por_semana','{$this->numero_semanas}','1',now(),'{$this->fecha_pago}','$monto_pagar','1','{$monto_pagar}')";
        $bResultado = $this->NonQuery($sql);

        $sql1 = "select id from prestamos order by id desc limit 1";
        $res = $this->Query($sql1);

        $this->id = $res[0]->id;

        return $bResultado;
    }

    public function Editar()
    {
        $restanteCantidad = 0;
        if (!empty($this->restante)) {
            $restanteCantidad = $this->monto + $this->restante;
        } else {
            $restanteCantidad = $this->monto;
        }
        
        $interes = $this->numero_semanas * 1.5;
        $cantidad = ($restanteCantidad * $interes) / 100;
        $monto_pagar = $restanteCantidad + $cantidad;
        $interes_pagar = $monto_pagar - $restanteCantidad;
        $monto_por_semana = $monto_pagar / $this->numero_semanas;

        $sql = "update prestamos set
                monto = '".$restanteCantidad."',
                interes = '$interes_pagar',
                monto_por_semana = '$monto_por_semana',
                numero_semanas = '{$this->numero_semanas}',
                monto_pagar = '$monto_pagar',
                restante = {$monto_pagar},
                usuario_edicion = '{$this->user_id}',
                fecha_modificacion = now()
                where id='{$this->id}'";
        $bResultado = $this->NonQuery($sql);

        $sql1 = "select id from prestamos order by id desc limit 1";
        $res = $this->Query($sql1);

        $this->id = $res[0]->id;

        return $bResultado;
    }

    public function Guardar()
    {
        $bRes = false;
        if ($bRes = $this->Agregar()) {

        }

        return $bRes;
    }
}
