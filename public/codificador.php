    <?php
    /* Llama a este archivo 'hello-world.php' */
    require __DIR__ . '/vendor/autoload.php';

    use Mike42\Escpos\PrintConnectors\FilePrintConnector;
    use Mike42\Escpos\Printer;

    // Obtener el valor de la URL del formulario
    $url = isset($_POST['url']) ? $_POST['url'] : '';

    // Reemplazar caracteres especiales en la URL
    $url = str_replace(array("httpsñ--", "_", "¿", "'"), array("https://", "/", "?", ""), $url);

    // Dividir la URL en componentes
    $url_components = explode("/", $url);

    $show_model = "";
    $codigo = "";
    $hosturl = "";

    // Iterar sobre los componentes de la U RL
    foreach ($url_components as $component) {

        // Verificar si el valor contiene la cadena deseada
        if (strpos($component, "eventosinternacionales.hospitalmilitar.com.ni") !== false) {
            // Dividir la cadena usando el guion como separador
            $parts = explode("-", $component);

            // Asignar las partes a variables
            $host = $parts[0];
            $path = $parts[1];
            $hosturl = "$host/$path";
        }

        // Verificar si el componente contiene "_showModel"
        if (strpos($component, "showModel") !== false) {
            // Obtener el valor de showModel
            $show_model = substr($component, strpos($component, "showModel") + strlen("showModel"));
            // Extraer solo el número de showModel y verificar si es numérico
            $show_model_number = preg_replace('/\D/', '', $show_model);
        }

        // Verificar si el componente contiene "codigo"
        if (strpos($component, "codigo") !== false) {
            // Obtener el valor de codigo
            $codigo = substr($component, strpos($component, "codigo") + strlen("codigo"));
            // Extraer solo el número de showModel y verificar si es numérico
            $codigo_number = "PC-ECIE-" . preg_replace('/\D/', '', $codigo);
        }
    }

    // Construir la nueva URL con los valores de showModel y codigo
    $new_url = "https://" . $hosturl . "/showModel=" . $show_model_number . "?codigo=" . $codigo_number;

    //Conexion a BD y consultar 
    // Configuración de la base de datos
    define('DB_NAME', 'db2gdg4nfxpgyk');
    define('DB_USER', 'ud79ogkwgphg5');
    define('DB_PASSWORD', 'hcziwaygs6cy');
    define('DB_HOST', 'c98055.sgvps.net');
    define('DB_CHARSET', 'utf8mb4');

    // Conexión a la base de datos
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    // Verificar la conexión
    if ($mysqli->connect_errno) {
        echo "Error al conectar a la base de datos: " . $mysqli->connect_error;
        exit();
    }

    // Escapar el valor para prevenir inyección SQL
    $codigo_number_escaped = $mysqli->real_escape_string($codigo_number);

    // Consulta SQL
    $query =
        "SELECT
            tb.id_participante AS `N°`, 
            tb.nombre AS Nombres,
            tb.apellidos AS Apellidos,
            titulo,
            'GRUPO A' AS grupo, 
            CONCAT('https://eventosinternacionales.hospitalmilitar.com.ni/?showModel=1&codigo=', 'PC-ECIE-',LPAD(tb.id_participante, 4, '0')) AS url_qrcode, 
            tb.categoria AS TipoParticipacion,
            estado_participante as EsInscrito
    FROM wp_eiparticipante_verificacion AS verificacion
    INNER JOIN wp_eiparticipante AS tb ON verificacion.id_participante = tb.id_participante
    WHERE verificacion.codigo_participante = '$codigo_number'
    AND tb.estado_participante = 1;";

    $result = $mysqli->query($query);

    // Verificar si la consulta fue exitosa
    if ($result) {
        // Array para almacenar los resultados
        $rows = array();

        // Almacenar cada fila en el array
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        // Liberar el resultado
        $result->free();
        // Cambia la conexión aquí
        $connector = new FilePrintConnector("/dev/usb/lp1");
        $printer = new Printer($connector);
        // Datos de la impresión

        // Datos de la factura
        $nombreTienda = "Hospital Militar Escuela\nDr. Alejandro Davila Bolaños";
        $direccionTienda = "Evento - I Congreso Internacional de Enfermería";
        $telefonoTienda = "Centro de Convenciones Olof Palme";
        $fecha = date("Y-m-d H:i:s");

        // Imprimir encabezado
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->text("$nombreTienda\n");
        $printer->text("$direccionTienda\n");
        $printer->text("$telefonoTienda\n\n");

        $nombreCompleto = $rows[0]['Nombres'] . ' ' .  $rows[0]['Apellidos'];
        $tipoParticipante = $rows[0]['TipoParticipacion'];
        $titulo = $rows[0]['titulo'];

        // Imprimir detalles de la factura
        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text("Fecha: $fecha\n");
        $printer->text("--------------------------------\n");

        // Generar el contenido del voucher
        $voucherContent = "
===============================
        VOUCHER DE EVENTO
===============================

Nombre Completo: $nombreCompleto
Tipo de Participante: $tipoParticipante
Título: $titulo

===============================
";

        // Imprimir el contenido del voucher
        $printer->text($voucherContent);

        $printer->text("--------------------------------\n");
        $printer->feed(5);
        $printer->cut();
        $printer->close();

        // Retornar los resultados como JSON
        header('Content-Type: application/json');
        echo json_encode(array('success' => true, 'data' => $rows));
    } else {
        echo json_encode(array('success' => false, 'error' => 'Error en la consulta: ' . $mysqli->error));
    }
