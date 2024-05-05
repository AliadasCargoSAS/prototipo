<!DOCTYPE html>
<html lang="es">

<?php
require_once "../include/header-admin.php";
?>

<body>
<div class="container mb-4">
    <div class="row">
        <div class="col" id="cont-bloque-corto">
            <div class="card">
                <div class="card-header bg-white pb-0 border-bottom-0 text-center">
                    <div class="conatiner-fluid p-4">
                        <h3>
                            <span style="color: #168A45;">Bienvenido al Sistema de programación de eventos formativos</span>
                        </h3>
                        <span class="text-muted">Estadistica generada apartir de los datos habilitados y almacenados</span>
                    </div>
                </div>
                <div class="card-body pl-5 pr-5 pb-5">
                    <canvas id="myChart" width="400" height="400"></canvas>
                </div>
            </div>
        </div>
    </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js" integrity="sha512-s+xg36jbIujB2S2VKfpGmlC3T5V2TF3lY48DX7u2r9XzGzgPsa6wTpOQA7J9iffvdeBN0q9tKzRxVxw1JviZPg==" crossorigin="anonymous"></script>
    <script>
        var ctx = document.getElementById("myChart").getContext("2d");


        $.ajax({

            type: "GET",
            url: "estadisticas.php",
            success: function(data) {

                var myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ["Instructores", "Ambientes", "Usuarios", "Fichas", "Programas", "Evento fichas", "Evento instructores"],
                        datasets: [{
                            label: 'Cantidad',
                            data: JSON.parse(data),
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.2)',
                                'rgba(54, 162, 235, 0.2)',
                                'rgba(255, 206, 86, 0.2)',
                                'rgba(75, 192, 192, 0.2)',
                                'rgba(153, 102, 255, 0.2)',
                                'rgba(255, 159, 64, 0.2)',
                                'rgba(255, 159, 64, 0.2)'

                            ],
                            borderColor: [
                                'rgba(255, 99, 132, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(75, 192, 192, 1)',
                                'rgba(153, 102, 255, 1)',
                                'rgba(255, 159, 64, 1)',
                                'rgba(255, 159, 64, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                            }]
                        }
                    }
                });

            }
        });
    </script>

</body>

</html>