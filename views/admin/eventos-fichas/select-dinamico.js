//Usamos de javascript para obtener el valor segun lo seleccionado de las competencias 
       
function ShowSelected(){
    var competencia= document.getElementById('crearEvento1');
    var competencia2 = competencia.options[competencia.selectedIndex].text; 

    $.ajax({
        url: "select-raps.php",
        method: "POST",
        data: {
            "competencia":competencia2
        }, 
        success: function(respuesta) {
            $("#crearEvento2").attr("disabled", false);
            $("#crearEvento2").html(respuesta);
        }
    })                 
}

        