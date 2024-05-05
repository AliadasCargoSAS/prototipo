// Usamos de javascript y ajax para obtener el valor según lo seleccionado de los programas 
function ShowPrograma(){

    var programa= document.getElementById('crearEvento');
    var programa2 = programa.options[programa.selectedIndex].text;

    $.ajax({
        url: "select-competencias.php",
        method: "POST",
        data: {
            "Nombreprograma":programa2
        }, 
        success: function(respuesta) {
            $("#competencia").attr("disabled", false);
            $("#competencia").html(respuesta);
        }
    })     
    
    $.ajax({
        url: "select-fichas.php",
        method: "POST",
        data: {
            "programa":programa2
        }, 
        success: function(respuesta) {
            $("#crearEvento1").attr("disabled", false);
            $("#crearEvento1").html(respuesta);
        }
    })
}

// Usamos de javascript y ajax para obtener el valor según lo seleccionado de las competencias 

function ShowCompetencia(){
    var competencia= document.getElementById('competencia');
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







