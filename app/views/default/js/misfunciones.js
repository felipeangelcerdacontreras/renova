// JavaScript Document

// calendario en español
/*$.datepicker.regional['es'] = {
    closeText: 'Cerrar',
    prevText: '<Ant',
    nextText: 'Sig>',
    currentText: 'Hoy',
    monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
    monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
    dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
    dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Juv', 'Vie', 'Sáb'],
    dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá'],
    weekHeader: 'Sm',
    dateFormat: 'dd/mm/yy',
    firstDay: 1,
    isRTL: false,
    showMonthAfterYear: false,
    yearSuffix: ''
};

$.datepicker.setDefaults($.datepicker.regional['es']);*/

function MessageBox(msg) {
    /*
     var sCad = "\n<script> alert('"+ msg +"'); </script>\n";
     $("#divMensaje").html(sCad);
     */

    $("#divMensaje").html(msg);
    $("#divMensaje").dialog({
        title: "Sistema",
        modal: true,

        buttons: {
            Ok: function () {
                $(this).dialog("close");
            }
        }
    });
}

function ImagenCargando() {
    var sHTML = "<center class='precarga'></center>";
    return sHTML;
}

function Redireccion(pagina) {
    window.location = pagina;
}

function getUrlVars() {
    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    for (var i = 0; i < hashes.length; i++) {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    return vars;
}
function Alert(tit,msg,iconn,time,buttons) {
  swal({
  title:tit,
  text: msg,
  icon: iconn,
  timer: time,
  buttons: buttons
  });
}
function Empty(name) {
    $("#"+name).css('border', '1px solid red');
    $("#"+name).focus();
}
function soloLetras(e){
    key = e.keyCode || e.which;
    tecla = String.fromCharCode(key).toLowerCase();
    letras = " áéíóúabcdefghijklmnñopqrstuvwxyz";
    especiales = "8-37-39-46";

    tecla_especial = false
    for(var i in especiales){
         if(key == especiales[i]){
             tecla_especial = true;
             break;
         }
     }

     if(letras.indexOf(tecla)==-1 && !tecla_especial){
         return false;
     }
 }
 function solonumeros(e)
{
   var keynum = window.event ? window.event.keyCode : e.which;
   if ((keynum == 8) || (keynum == 46))
        return true;
    return /\d/.test(String.fromCharCode(keynum));
}

function DatosVacios(obj, input) {
    var datosVacios = false;
    $(obj).find(input).each(function () {
        var $this = $(this);
        if ($this.val().length <= 0) {
            datosVacios = true;
            $this.css("background-color", "red");
        }
    });
    return datosVacios;
}

var timestamp = null;

function activarSensor(srn) {
    $.ajax({
        type: "POST",
        url: "app/views/default/modules/catalogos/empleados/m.empleados.procesa.php",
        data: "accion=ActivarSensor&token=" + srn,
        dataType: "json",
        beforeSubmit: function(formData, jqForm, options) {alert("se queda aca");},
        success: function(data) {
            if (data === 1) {
                $("#activeSensorLocal").attr("disabled", true);
                $("#fingerPrint").css("display", "block");
            }
        }
    });
}

function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
            results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}


function showMessageBox(mensaje, type) {
    var clas = "";
    var icono = "";
    switch (type) {
        case "success":
            clas = "mensaje_success";
            icono = "imagenes/success_16.png";
            break;
        case "warning":
            clas = "mensaje_warning";
            icono = "imagenes/warning_16.png";
            break;
        case "danger":
            clas = "mensaje_danger";
            icono = "imagenes/danger_16.png";
            break;
    }

    $("#mensaje").addClass(clas);
    $("#txtMensaje").html(mensaje);
    $("#imageMenssage").attr("src", icono);
    $("#mensaje").fadeIn(5);
    setTimeout(function () {
        $("#mensaje").fadeOut(1500);
    }, 3000);

}

function ValidarFechas(dato) {   
    var ret = true
    let date = new Date();

    let day = date.getDate()-1;
    let month = date.getMonth()+1;
    let year = date.getFullYear();

    if(month < 10){
        actual = `${year}-0${month}-${day}`;
    }else{
        actual = `${year}-${month}-${day}`;
    }
    
    var fechainicial = dato;
    console.log(actual+" >= "+fechainicial);

    if(actual >= fechainicial) {
        ret = false;
    }
    return ret;
}

function is_numeric(value) {
	return !isNaN(parseFloat(value)) && isFinite(value);
}

function is_negative_number(number=0){

    if( (is_numeric(number)) && (number<0) ){
        return true;
    }else{
        return false;
    }
}

const formatterDolar = new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD'
})