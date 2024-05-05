    //Funcion para hacer cambios entre el select Torre del ambiente y el input de otras torres

    function cambioSelect() {

        var torre = document.getElementById('torre');         
        var torre2 = torre.options[torre.selectedIndex].text;

        var otraTorre = document.getElementById('otraTorre');
        var otraTorre2 = document.getElementById('otraTorre2');

                                    
        if (torre2=='OTRA') {

            otraTorre.style.display="block";
            otraTorre2.style.display="block";

        }else if(torre2=='OCCIDENTAL'){

            otraTorre.style.display="none";
            otraTorre2.style.display="none";

        }else if(torre2=='ORIENTAL'){
                                
            otraTorre.style.display="none";
            otraTorre2.style.display="none";
        }
    }

    //Funcion para hacer cambios entre el select Tipo de ambiente y el input de otros tipos

    function cambioSelect2() {

        var tipo = document.getElementById('tipo');         
        var tipo2 = tipo.options[tipo.selectedIndex].text;

        var otraTipo = document.getElementById('otraTipo');
        var otraTipo2 = document.getElementById('otraTipo2');

                                    
        if (tipo2=='OTRO') {

            otraTipo.style.display="block";
            otraTipo2.style.display="block";

        }else if(tipo2=='COMPUTO'){

            otraTipo.style.display="none";
            otraTipo2.style.display="none";

        }else if(tipo2=='LABORATORIO'){
                                
            otraTipo.style.display="none";
            otraTipo2.style.display="none";
        }
    }