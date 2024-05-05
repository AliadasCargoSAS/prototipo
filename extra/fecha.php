<?php


class modelofecha  {


   
   function fechaaletras($fecha)  //$fecha debe venir el formato YMD
   {
      $fecha = strtotime($fecha); // convierte la fecha de formato yyyymmdd a marca de tiempo 
      $diasemana = date("w", $fecha); // obtiene el número del dia de la semana. El 0 es domingo 
      switch ($diasemana)
      {
         case "0":
            $diasemana = "Domingo";
            break;
         case "1":
            $diasemana = "Lunes";
            break;
         case "2":
            $diasemana = "Martes";
            break;
         case "3":
            $diasemana = "Miércoles";
            break;
         case "4":
            $diasemana = "Jueves";
            break;
         case "5":
            $diasemana = "Viernes";
            break;
         case "6":
            $diasemana = "Sábado";
            break;
      }
      $dia = date("d", $fecha); // día del mes en número 
      $mes = date("m", $fecha); // número del mes de 01 a 12 
      switch ($mes)
      {
         case "01":
            $mes = "Enero";
            break;
         case "02":
            $mes = "Febrero";
            break;
         case "03":
            $mes = "Marzo";
            break;
         case "04":
            $mes = "Abril";
            break;
         case "05":
            $mes = "Mayo";
            break;
         case "06":
            $mes = "Junio";
            break;
         case "07":
            $mes = "Julio";
            break;
         case "08":
            $mes = "Agosto";
            break;
         case "09":
            $mes = "Septiembre";
            break;
         case "10":
            $mes = "Octubre";
            break;
         case "11":
            $mes = "Noviembre";
            break;
         case "12":
            $mes = "Diciembre";
            break;
      }
      $ano = date("Y", $fecha); // obtenemos el año en formato 4 digitos 
      $fecha = $diasemana .' '.$dia . "  de " . $mes . " del " . $ano; // unimos el resultado en una unica cadena .
      return $fecha; //enviamos la fecha al programa aqui puedes hacer lo ajustes de como quieres que se muestre la fecha referencia para ajustes: https://www.php.net/manual/es/function.date.php



   }

}