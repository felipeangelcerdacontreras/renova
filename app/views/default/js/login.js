$('document').ready(function(){

$('#boton1').click(function(e){

  if($('#usuario').val() == ""){
    alert("Introduce un usuario");
    return false;
  }
  else {
    var usuario = $('#usuario').val();
  }
  if ($('#clave').val() == "" ) {
    alert("Introduce una contraseña");
    return false;
  }
  
  });
});
