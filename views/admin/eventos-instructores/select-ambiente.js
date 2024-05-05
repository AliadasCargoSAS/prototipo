// Usamos de javascript y ajax para obtener el ambiente disponible según la ficha seleccionada 
function ShowFichas() {
    var fichas = document.getElementById("crearEvento1");
    var fichas2 = fichas.options[fichas.selectedIndex].text;

    $.ajax({
        url: "select-ambientes.php",
        method: "POST",
        data: {
            "ficha":fichas2
        },
        success: function(resultado){
            $("#crearEvento3").attr("disabled", false);
            $("#crearEvento3").html(resultado);
        }
    })
    
}