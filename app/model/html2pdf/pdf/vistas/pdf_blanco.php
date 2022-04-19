<?php
	//require_once'../myDBC.php';
	
	//Obtenemos los valores de los formularios
	/*$dia = $_POST["dia"]; 
	$mes = $_POST["mes"];
	$anio= $_POST["anio"];
	$matricula = $_POST["matricula"];
	*/
	//Se crea un objeto de conexión
	//Se obtiene la información de una persona
	//según su matrícula
	/*$conexion = new myDBC();
	$mat = $conexion->clearText($matricula);
	$datos = $conexion->obtenerPersona($mat);*/
	
?>
<!-- IMPORTANTE: El contenido de la etiqueta style debe estar entre comentarios de HTML -->
<style>
<!--
#encabezado {padding:10px 0; border-top: 1px solid; border-bottom: 1px solid; width:100%;}
#encabezado .fila #col_1 {width: 15%}
#encabezado .fila #col_2 {text-align:center; width: 55%}
#encabezado .fila #col_3 {width: 15%}
#encabezado .fila #col_4 {width: 15%}

#encabezado .fila td img {width:50%}
#encabezado .fila #col_2 #span1{font-size: 15px;}
#encabezado .fila #col_2 #span2{font-size: 12px; color: #4d9;}

#footer {padding-top:5px 0; border-top: 2px solid #46d; width:100%;}
#footer .fila td {text-align:center; width:100%;}
#footer .fila td span {font-size: 10px; color: #000;}

#fecha {margin-top:70px; width:100%;}
#fecha tr td {text-align: right; width:100%;}

#central {margin-top:20px; width:100%;}
#central tr td {padding: 10px; text-align: justify; width:100%;}

#datos {border:1px solid; margin-left:180px; width:50%;}
#datos tr {border:1px solid;}
#datos tr td{border:1px solid;}
-->
</style>

<!-- page define la hoja con los márgenes señalados -->
<page backtop="10mm" backbottom="10mm" backleft="10mm" backright="20mm">
    <page_header> <!-- Define el header de la hoja -->
		<table id="encabezado">
            <tr class="fila">
                <td id="col_1" >
					<img src="../../images/img_izq.png">
					
				</td>
                <td id="col_2">
					<span id="span1">Aquí pueden ir más span o divs según requiera el diseño</span>
					<br>
					<span id="span2">Este es otro span con otros estilos</span>
				</td>
                <td id="col_3">
					<img  src="../../images/img_der_1.png">
				</td>
                <td id="col_4">
					<img src="../../images/img_der_2.png">
				</td>
            </tr>
        </table>
    </page_header>
        
    <page_footer> <!-- Define el footer de la hoja -->
		<table id="footer">
            <tr class="fila">
				<td>
					<span>Este el footer y pueder ir con letra más pequeña por ejemplo poner una 
					dirección o algo así :P</span>
				</td>
			</tr>
        </table>
    </page_footer>
    
    <!-- Define el cuerpo de la hoja -->
    <table id="fecha">
		<tr class="fila">
			<td>
				<?php echo "México D.F a ". $dia . " de ". $mes . " de " . $anio;?>
			</td>
		</tr>
	</table>
	

</page>
