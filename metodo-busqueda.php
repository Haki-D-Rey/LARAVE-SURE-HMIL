<?php

$mysqli->close();

    // Cadena de ejemplo
    $cadena = "httpsÑs-eventosinternacionales.hospitalmilitar.com.ni/eregistro_showModel?1/codigo=PC'ECIE'0001";

    // Expresión regular para extraer los valores
    $patron = "/showModel\?(\d+)\/codigo=([^&]+)/";

    function reformatearCodigo($codigo)
    {
        // Eliminar comillas y ceros adicionales del código original
        $codigo = str_replace("'", "", $codigo);

        // Extraer los primeros dos caracteres del código original para el prefijo 'PC'
        $prefijo = substr($codigo, 0, 2);

        // Extraer los caracteres restantes del código original
        $resto = preg_replace('/\D/', '', substr($codigo, 2));

        // Formatear el número de cuatro dígitos con ceros a la izquierda si es necesario
        $numero = sprintf('%04d', $resto);

        // Construir el nuevo código con el formato especificado
        $nuevo_codigo = $prefijo . '-ECIE-' . $numero;

        return $nuevo_codigo;
    }

    // Ejemplo de uso
    $codigo_original = "PC'ECIE'0847";

    $nuevo_codigo = reformatearCodigo($codigo_original);
    echo "Nuevo código: $nuevo_codigo";


    // Buscar coincidencias en la cadena
    if (preg_match($patron, $cadena, $coincidencias)) {
        $valor_showModel = $coincidencias[1];
        $valor_codigo = $coincidencias[2];
        echo "Valor de showModel: $valor_showModel<br>";
        echo "Valor de código: $valor_codigo<br>";
    } else {
        echo "No se encontraron coincidencias.<br>";
    }

    // Imprimir la nueva URL
    echo "codigo de usuario es= $codigo_number ";
    echo ($rows["0"]->{"EsInscrito"} === "1") ? "El usuario está inscrito" : "No está inscrito";


?>