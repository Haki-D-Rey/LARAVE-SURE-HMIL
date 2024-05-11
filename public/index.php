<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Codificación</title>
</head>

<body>
    <h2>Formulario de Codificación</h2>
    <form id="urlForm">
        <label for="urlInput">Ingresa una URL:</label><br>
        <input type="text" id="urlInput" name="urlInput"><br>
    </form>

    <script>
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
</body>

</html>