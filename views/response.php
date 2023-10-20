<?php
/**
 * Envía una respuesta en formato JSON al cliente.
 *
 * @param mixed $data Datos que serán enviados como respuesta.
 * @param int $status Código de estado HTTP (por defecto es 200).
 * @param array $headers Encabezados adicionales que se quieran agregar a la respuesta.
 */
function jsonResponse($data, $status = 200, $headers = [])
{
    // Establecer el tipo de contenido a JSON
    header('Content-Type: application/json');

    // Establecer el código de respuesta HTTP
    http_response_code($status);

    // Añadir encabezados adicionales si se proporcionan
    foreach ($headers as $key => $value) {
        header("$key: $value");
    }

    // Enviar la respuesta en formato JSON
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
}

// Lo deje como un array vacio para que sea opcional si queres mandar encabezados personalizados
// o algo mas que no sea el status