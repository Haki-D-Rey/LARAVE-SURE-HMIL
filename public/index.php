<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Codificación</title>
</head>
<style>
      * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
      }

      body {
      }

      .layout {
        width: 100%;
        height: 100vh;
        display: grid;
        grid:
          "header header header" auto
          "sidebar body body" 1fr
          "sidebar footer footer" auto
          / auto 1fr auto;
      }

      .header {
        grid-area: header;
        height: 10vh;
        border: 1px solid pink;
        position: relative; /* Agregado para posicionar correctamente el botón */
      }

      .sidebar {
        grid-area: sidebar;
        border: 1px solid blue;
        overflow-y: auto; /* Agregado para permitir scroll vertical */
        max-height: calc(
          100vh - 10vh
        ); /* Establecer la altura máxima del sidebar */
        transition: width 0.5s;
      }

      .body {
        grid-area: body;
        border: 1px solid red;
        overflow-y: auto;
        max-height: calc(100vh - 10vh);
      }

      .footer {
        grid-area: footer;
        height: 05vh;
        border: 1px solid green;
      }

      .menu-btn-container {
        cursor: pointer;
      }

      .menu-btn {
        /* display: none; */
      }

      .content-flex {
        display: flex;
        flex-direction: column;
        gap: 1rem;
      }
      /* 
        .h-min{
            height: 100vh;
            overflow-y: visible;
        } */

      .content-flex i {
        margin-right: 8px;
      }

      .collapsed .content-flex span {
        display: none; /* Ocultar los spans cuando está colapsado */
      }

      @media screen and (max-width: 768px) {
        .sidebar {
          width: 0;
        }

        .menu-btn {
          display: block;
        }

        .sidebar,
        .collapsed .content-flex span {
          display: none;
          color: aqua;
        }

        .sidebar.collapsed .content-flex i {
          margin-right: 0;
        }
      }
    </style>
<body>
    <section class="layout">
        <div class="header">
            <div class="menu-btn-container">
                <div class="menu-btn" onmouseover="expandSidebar()" onmouseout="restoreSidebar()">
                    ☰
                </div>
            </div>
        </div>
        <div class="sidebar collapsed" id="sidebar">
            <div class="content-flex">
                <div>
                    <i class="fas fa-home"></i>
                    <span>Inicio</span>
                </div>
                <div>
                    <i class="fas fa-cube"></i>
                    <span>Verificacion</span>
                </div>
            
            </div>
        </div>
        <div class="body">
            <div class="container p-5">
                <h2>Formulario de Codificación</h2>
                <form id="urlForm">
                    <label for="urlInput">Ingresa una URL:</label><br>
                    <input type="text" id="urlInput" name="urlInput"><br>
                </form>
            </div>
        </div>
        <div class="footer">
            <p>
                © 2024 Hospital Militar Escuela Dr. Alejandro Dávila Bolaños Diseñado
                por: IT
            </p>
        </div>
    </section>

    <script>
        function expandSidebar() {
            const sidebar = document.getElementById("sidebar");
            sidebar.style.width = "250px";
            sidebar.classList.remove("collapsed");
        }

        function restoreSidebar() {
            const sidebar = document.getElementById("sidebar");
            sidebar.style.width = "50px";
            sidebar.classList.add("collapsed");
        }
        // Obtener el valor del campo de entrada
        var input = document.getElementById('urlInput');
        document.addEventListener('DOMContentLoaded', function() {
            // Enfoque en el input con id "urlInput"
            urlInput.focus();
        });
        document.getElementById('urlForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Evitar el envío del formulario por defecto
            input.focus();

            // Realizar una petición POST al servicio codificador.php
            fetch('codificador.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'url=' + encodeURIComponent(input.value)
                })
                .then(response => response.json())
                .then(data => {
                    // Mostrar la respuesta del servicio
                    console.log(data);
                    urlInput.value = "";
                    let datosInscritos = data.data[0];
                    alert(`El usuario ${datosInscritos.PrimerNombre + " " + datosInscritos.SegundoNombre + " " + datosInscritos.PrimerApellido + " " + datosInscritos.SegundoApellido } ${!data.EsInscrito ? "Esta inscrito": "No esta inscrito"}`);
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        });
    </script>

    <!-- <script>
        function expandSidebar() {
            const sidebar = document.getElementById("sidebar");
            sidebar.style.width = "250px";
            sidebar.classList.remove("collapsed");
        }

        function restoreSidebar() {
            const sidebar = document.getElementById("sidebar");
            sidebar.style.width = "50px";
            sidebar.classList.add("collapsed");
        }

        $('#example').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy',
                'excel',
                'csv',
                'pdf',
                'print'
            ],
        });
    </script> -->
</body>

</html>