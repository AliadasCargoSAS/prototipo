<?php

session_start();

if ($_SESSION['rol'] == 1) {
    
}else{
    header("location: /");
    exit;
}

if (empty($_SESSION['active'])) {
    header('location:/');
}
?>

<?php
include_once "../../../extra/conexion.php";
$id_user = $_SESSION['idUser'];
$sql = "SELECT Nombre, Clave, Telefono FROM usuarios WHERE IdPrincipal = '$id_user'";
$resultado = $link->query($sql);
$row = $resultado->fetch_assoc();
$NOMBRE = $row['Nombre'];
$CLAVE = $row['Clave'];
$TELEFONO = $row['Telefono'];


/*include_once "../principal/conexion.php";
            $id_user = $_SESSION['idUser'];
            $sql = "SELECT * FROM usuarios WHERE IdPrincipal = '$id_user'";
            if($result = mysqli_query($link, $sql)){
                if(mysqli_num_rows($result) > 0){
                    while($row = mysqli_fetch_array($result)){
                        echo utf8_decode($row['Nombre']);
                    }
                    mysqli_free_result($result);
                
                } else{
                        echo "No records matching your query were found.";
                    }
                } else{
                    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
                }             */


?>

<?php
include_once "../../../extra/fecha.php";
error_reporting(E_ALL ^ E_NOTICE);
$modelofecha = new modelofecha();
$fecha = $_GET["fecha"];
date_default_timezone_set('America/Bogota');
$hoy = date("Ymd");
$hora = date("H:i");

?>



<head>

    <!-- //Estilos -->
    <link rel="stylesheet" href="../../../css/mainadmin.css">
    <link rel="stylesheet" href="../../../css/main-add.css">

    <!-- //Favicon - Icono navegador -->
    <link rel="icon" href="../../../images/favicon.png" type="image/png">

    <!-- //Fontawesome (Iconos) -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">

    <!-- //Propiedades -->
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximun-scale=1.0, minimun-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <!-- Else -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script> -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

    <title>Panel de administrador | Programación de eventos formativos</title>

</head>


<header id="headernoti">

    <div class="container-fluid">
        <div class="header d-lg-flex justify-content-between align-items-center py-2 px-sm-2 px-1 pb-0">
            <div id="logo">
                <h1><a href="../principal/"><img src="../../../images/logo-cdm.jpg" height="45px"> </a></h1>
                <!--<a href="#" class="ml-4 text-dark" style="font-size:25px;"><i class="far fa-bell ml-4" style="font-size:20px;"></i></a>-->
            </div>
            <!--<a style="cursor:default; font-weight:600; text-transform:uppercase; padding-left:65px; font-size: 14px;"><i class="far fa-calendar mr-2"></i>Programación de eventos formativos</a> -->
            <div class="nav_w3ls ml-lg-5">
                <nav>
                    <label for="drop" class="toggle">Menu</label>
                    <input type="checkbox" id="drop" />
                    <ul class="menu">
                        <li>
                            <label for="drop-2" class="toggle toogle-2" style="font-size: 14px;">GESTIONAR <span class="fa fa-angle-down" aria-hidden="true"></span>
                            </label>
                            <a href="#" style="font-size:14px;margin-top:8px;">GESTIONAR <span class="fa fa-angle-down" aria-hidden="true"></span></a>
                            <input type="checkbox" id="drop-2" />
                            <ul>
                                <li><a href="../programas/gestionar" class="drop-text">Programas de formación</a></li>
                                <li><a href="../instructores/gestionar" class="drop-text">Instructores</a></li>
                                <li><a href="../ambientes/gestionar" class="drop-text">Ambientes</a></li>
                                <li><a href="../fichas/gestionar" class="drop-text">Fichas</a></li>
                                <li><a href="../usuarios/gestionar" class="drop-text">Usuarios</a></li>
                            </ul>
                        </li>
                        <li class="ml-3">
                            <a href="../profile/" style="font-size:14px;margin-top:8px;"><i class="far fa-user mr-2"></i><?php echo $NOMBRE; ?></a>
                        </li>

                        <?php

                        $sqlnoti = "SELECT COUNT(Leido) FROM notificaciones WHERE Leido = '0'";
                        if ($resultcount1 = mysqli_query($link, $sqlnoti)) {
                            if (mysqli_num_rows($resultcount1) > 0) {
                                while ($row = mysqli_fetch_array($resultcount1)) {
                                    $leido = $row['COUNT(Leido)'];
                                }
                            }
                        };
                        ?>

                        <li class="" id="notificaciones1click">
                            <a type="button">
                                <i class="far fa-bell p-2" id="notificacionesboton"></i>
                                <?php
                                if ($leido == TRUE) {
                                    echo '<small class="badge badge-danger" style="font-size:9px;top:-4.5px;position:absolute;right:8px;">' . $leido . '</small>';
                                };
                                ?>
                            </a>
                        </li>
                    </ul>
                </nav>

                <div id="notificaciones1" class="bg-white notificaciones1 list-group list-group-flush pt-0 pr-2 pl-2 mb-3 rounded" style="display:none;">
                    <div id="contentnoti">
                        <li class="list-group-item card-header text-dark text-left" style="width:100%;font-weight:600;">
                            <h5 class="mb-0">Notificaciones</h5>
                        </li>
                        <?php
                        if ($leido == TRUE) {
                            echo '<li class="text-dark text-left pl-3 pt-2 pb-3" style="width:100%;"><small>Tienes ' . $leido . ' notificaciones sin leer</small></li>';
                            echo '<script>document.title = "(' . $leido . ') Panel de administrador | Programación de eventos formativos"</script>';
                        } else {
                            echo '<li class="text-muted pl-3 pt-1" style="width:100%;"><span style="font-size:13px;">No tienes notificaciones sin leer</span></li>
                                                <li class="pt-3" style="width:100%;"><a href="../principal/notificaciones" class="text-dark pl-4 pt-3">Ver todas las notificaciones</a></li>';
                        }
                        ?>
                        <form class="pb-3" action="notificaciones" method="get">
                            <?php
                            $sqlnoti2 = "SELECT * FROM notificaciones WHERE leido = '0' ORDER BY Id DESC";
                            if ($resultcount2 = mysqli_query($link, $sqlnoti2)) {
                                if (mysqli_num_rows($resultcount2) > 0) {
                                    while ($row = mysqli_fetch_array($resultcount2)) {
                                        $idnotifi = $row['Id'];
                                        $accion = $row['Accion'];
                                        $descripcionnoti = $row['Descripcion'];
                                        $tiponoti = $row['Tipo'];
                                        $leido = $row['Leido'];
                                        $fechanoti = $row['Fecha'];
                                        $linknoti = $row['Link'];
                                        $autor = $row['Autor'];

                                        echo '<a href="../principal/notificaciones?l=true#' . $idnotifi . '"> 
                                        <li class="list-group-item text-left py-0" style="border:none;width:100%;font-size:14px;">
                                            <div>                                        
                                                <div class="d-flex flex-row"><div class="pr-3" title="' . $autor . '">';

                                        $sqlautor = "SELECT Avatar FROM usuarios WHERE Nombre = '$autor'";
                                        if ($resultautor = mysqli_query($link, $sqlautor)) {
                                            $resultautor = mysqli_query($link, $sqlautor);
                                            while ($row = @mysqli_fetch_array($resultautor)) {
                                                $avatarImg2 = $row['Avatar'];
                                                if ($avatarImg2 != '') {
                                                    echo '<img class="float:left" style="width:40px;height:40px;border-radius:50px;" src="../../../upload-images/' . $avatarImg2 . '">';
                                                } else {
                                                    echo '<img class="float:left" style="width:40px;height:40px;border-radius:50px;" src="../../../upload-images/user-start.jpg">';
                                                }
                                            }
                                        }

                                        echo    '<small class="badge badge-pill badge-danger" style="position:absolute;left:48px;padding-top:.3em;padding-bottom:.4em;">-</small>
                                                </div>
                                                <div style="max-width:80%;">
                                                    <a class="a-notificaciones p-0 text-dark mt-0" href="../principal/notificaciones?l=true#' . $idnotifi . '">' . $accion . '</a>
                                                    <p class="text-muted notificaciones-descri">' . $descripcionnoti . '</p>
                                                </div>
                                            </div>
                                        </div>
                                    </li> 
                                </a>';
                                }
                            }
                        }
                        ?>                            
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</header>

<div id="confirm-eventficha-modal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background:#222;color:#fff;border-radius:0px;">
                <h4 class="modal-title">Evento Ficha</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: #fff;">×</button>
            </div>
            <div class="modal-body">
                <form method="GET" action="../eventos-fichas/gestionar?Ficha=<?php echo ($NumFicha); ?>&Trimestre=<?php echo ($Trimestre); ?>">
                    <p style="line-height: 34px;"><i class='fas fa-info-circle mr-2'></i>Seleccione el número de la ficha&nbsp&nbsp<select name="ficha" id="eventoFicha" class="selectevento" required>
                            <?php
                            include "../../../extra/conexion.php";

                            $sql = "SELECT Numero from fichas where status = 1 and Programa <> ''";

                            if ($result = mysqli_query($link, $sql)) {
                                if (mysqli_num_rows($result) > 0) {
                                    echo "<option></option>";
                                    while ($row = mysqli_fetch_array($result)) {
                                        echo "<option>" . $row['Numero'] . "</option>";
                                    }
                                }
                            } else {
                                echo "<div class='container'><span class='alert alert-danger'>Algo salió mal. Por favor, inténtelo de nuevo más tarde.</span></div>" . mysqli_error($link);
                            }
                            mysqli_close($link);
                            $NumFicha = $_GET["ficha"];
                            $Trimestre = $_GET["trimestre"];
                            ?>
                        </select>
                        &nbsp
                        y el trimestre&nbsp&nbsp<select name="trimestre" id="eventoTrimestre" class="selectevento" required>
                            <option></option>
                            <option value="I">I</option>
                            <option value="II">II</option>
                            <option value="III">III</option>
                            <option value="IV">IV</option>
                            <option value="V">V</option>
                            <option value="VI">VI</option>
                        </select>&nbsp&nbspal cual asignarle un evento.</p>
            </div>

            <div class="modal-footer">
                <input type="button" style="width:25%;" class="btnr2" data-dismiss="modal" value="Cancelar">
                <input type="submit" class="btnr btn-ok" value="Gestionar">
                </form>
            </div>
        </div>
    </div>
</div>

<div id="confirm-eventinstructor-modal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background:#222;color:#fff;border-radius:0px;">
                <h4 class="modal-title">Evento Instructor</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: #fff;">×</button>
            </div>

            <div class="modal-body">
                <form method="GET" action="../eventos-instructores/gestionar?Instructor=<?php echo ($Instructor); ?>">
                    <p style="line-height: 34px;"><i class='fas fa-info-circle mr-2'></i>Seleccione el instructor al cual asignarle un evento,&nbsp&nbsp<select name="Instructor" class="selectevento" id="selectinstructor" required>
                            <?php
                            include "../../../extra/conexion.php";

                            $sql = "SELECT Nombre, IdPrincipal from instructores where status = 1";

                            if ($result = mysqli_query($link, $sql)) {
                                if (mysqli_num_rows($result) > 0) {
                                    echo "<option></option>";
                                    while ($row = mysqli_fetch_array($result)) {
                                        echo "<option>" . $row['Nombre'] . "</option>";
                                    }
                                }
                            } else {
                                echo "<div class='container'><span class='alert alert-danger'>Algo salió mal. Por favor, inténtelo de nuevo más tarde.</span></div>" . mysqli_error($link);
                            }
                            mysqli_close($link);
                            $Instructor = $_GET["Instructor"];
                            ?>
                        </select>&nbsp</p>
            </div>

            <div class="modal-footer">
                <input type="button" style="width:25%;" class="btnr2" data-dismiss="modal" value="Cancelar">
                <input type="submit" class="btnr btn-ok" value="Gestionar">
                </form>
            </div>
        </div>
    </div>
</div>


<input type="checkbox" id="check">
<label for="check">
    <i class="fas fa-bars text-light" id="btn" style="display:none;"></i>
</label>
<hr class="mt-1 mb-3" style="border-top:1px solid #38393c;">

<div class="sidebar pt-4">
    <ul class="pt-1">
        <li>
            <div class="text-center" id="perfil">
                <?php
                include "../../../extra/conexion.php";

                $sqlConsultaImg = "SELECT Avatar FROM usuarios WHERE IdPrincipal = $id_user and Status = 1";

                $result = mysqli_query($link, $sqlConsultaImg);
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_array($result)) {
                        $avatarImg = $row['Avatar'];
                        if ($avatarImg != "") {
                            echo '<div><img src="../../../upload-images/' . $avatarImg . '"></div>';
                        } else {
                            echo '<div><img src="../../../upload-images/user-start.jpg"></div>';
                        }
                    }
                }
                ?>
                <a class="text-center text-light" href="../profile/">
                    <small class="text-center p-2">Editar perfil</small></a>
            </div>
            <hr class="mb-0 mt-3 ml-3 mr-3" style="border-top:1px solid #38393c;">
        </li>
        <li><a class="listado" href="../principal/"><i class="fas fa-home mr-2"></i>Inicio</a></li>
        <li id="eventosclick"><a class="listado" href="#eventos"><i class="fas fa-calendar-alt mr-2"></i>Eventos <i class="fas fa-angle-right ml-4"></i></a></li>
        <div class="eventos" id="eventos" style="display: none;">
            <li><a class="listado text-light" style="background:#2b2b2b;border-bottom: 1px solid #38393c;line-height: 50px;" data-href='../eventos-fichas/gestionar' data-toggle='modal' data-target='#confirm-eventficha-modal'>• Eventos fichas</a></li>
            <li><a class="listado text-light" style="background:#2b2b2b;line-height: 50px;" data-href="../eventos-instructores/gestionar" data-toggle='modal' data-target='#confirm-eventinstructor-modal'>• Eventos instructores</a></li>
        </div>
        <li><a class="listado" href="https://sena.territorio.la/cms/index.php" target="_blank"><i class="fas fa-chalkboard-teacher mr-2"></i>Territorium</a></li>
        <li><a class="listado" href="http://oferta.senasofiaplus.edu.co/sofia-oferta/" target="_blank"><i class="fab fa-stripe-s mr-2"></i>Sofia Plus</a></li>
        <li><a class="listado" href="https://accounts.google.com/signin/v2/identifier?continue=https%3A%2F%2Fmail.google.com%2Fmail%2F&service=mail&hd=sena.edu.co&sacu=1&flowName=GlifWebSignIn&flowEntry=AddSession#identifier" target="_blank"><i class="fas fa-envelope mr-2"></i>Correo misena</a></li>
        <li><a class="listado" href="../../../logout"><i class="fas fa-sign-out-alt mr-2"></i>Cerrar sesión</a></li>
    </ul>
</div>
<div class="espacio"></div>


<script scr="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<!-- Libreria para modificar las etiquetas select que se encuentren en el documento actual -->
<!-- AJAX NO PERMITE OBTENER EL ID EN VENTANAS MODAL -->
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script> -->

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>


<script>
    $('#confirm-event-modal').on('show.bs.modal', function(e) {
        $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
    });
</script>

<script>
    $("#notificaciones1click").click(function() {
        var notiabiertas = document.getElementById("notificaciones1");
        if (notiabiertas.style.display != 'none') {
            notiabiertas.style.display = 'none';
            document.getElementById("notificacionesboton").style.backgroundColor = '#e4e6eb'
            document.getElementById("notificacionesboton").style.fontSize = '19px';

        }else {
            notiabiertas.style.display = 'block'
            document.getElementById("notificacionesboton").style.backgroundColor = '#c8cace'
            document.getElementById("notificacionesboton").style.Color = '#d4d4d4'
            document.getElementById("notificacionesboton").style.fontSize = '18px'
        }
    });
</script>

<script>
    $("#eventosclick").click(function() {
        var divevent = document.getElementById("eventos");
        if (divevent.style.display != 'none') {
            divevent.style.display = 'none';
        }else {
            divevent.style.display = 'block'        
        }
    });
</script>


<script>
    $("#selectinstructor").select2({
        width: '80%'
    });
</script>

<script>
    $("#eventoFicha").select2({
        width: '25%'
    });
</script>

<script>
    $("#eventoTrimestre").select2({
        width: '12%'
    });
</script>