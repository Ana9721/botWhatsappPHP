<?php
const TOKEN_HERMOS = "APIHERMOS";
const WEBHOOK_URL = "https://www.hermos.com.mx/webhook.php";

function verificarToken($req, $res)
{
    try {
        $token = $req['hub_verify_token'];
        $challenge = $req['hub_challenge'];

        if (isset($challenge) && isset($token) && $token == TOKEN_HERMOS) {
            $res->send($challenge);
        } else {
            $res->status(400)->send();
        }
    } catch (Exception $e) {
        $res->status(400)->send();
    }
}
function recibirMensajes($req, $res)
{
    try {
        $entry = $req['entry'][0];
        $changes = $entry['changes'][0];
        $value = $changes['value'];
        $objetomensaje = $value['messages'];
        $mensaje = $objetomensaje[0];

        $comentario = $mensaje['text']['body'];
        $numero = $mensaje['from']; 
        
        $id = $mensaje['id'];
        $archivo = "log.txt";

        // if (!verificarTextoEnArchivo($id, $archivo)){
        //     $archivo = fopen($archivo, "a");
        //     $texto = json_encode($id).",".$numero.",".$comentario;
        //     fwrite($archivo, $texto);
        //     fclose($archivo);
       

        EnviarMensajeWhatsapp($comentario, $numero);
    
        $archivo = fopen("log.txt", "a");
        $texto = json_encode($numero);
        fwrite($archivo, $texto);
        fclose($archivo);

        $res->send("EVENT_RECEIVED");
    } catch (Exception $e) {
        $res->send("EVENT_RECEIVED");
// $res->header('Content-Type: application/json');
// $res->status(200)->send(json_encode(['message' => 'EVENT_RECEIVED']));
// } catch (Exception $e) {
// $res->header('Content-Type: application/json');
// $res->status(200)->send(json_encode(['message' => 'EVENT_RECEIVED']));

    }
}

function EnviarMensajeWhatsapp($comentario, $numero)
{
// Formatea el número 
    $lada = substr($numero, 0, 2);
    $num = substr($numero, 3, 13);
    $concat = $lada . $num;  //num formateado

// Convertir el comentario a minúsculas
    $comentario = strtolower($comentario);

// Preparar el mensaje si contiene 'hola'
    if (strpos($comentario, 'hola') !== false) {
        $data = json_encode([
            "messaging_product" => "whatsapp",
            "recipient_type" => "individual",
            "to" => $concat,  // Usa el número formateado
            "type" => "text",
            "text" => [
                "preview_url" => false,
                "body" => "Hola, visita el sitio web hermos"
            ]
        ]);
//botones
    }else if (strpos($comentario,'boton') !== false){
        $data = json_encode([
            "messaging_product" => "whatsapp",
            "recipient_type" => "individual",
            "to" => $concat,
            "type" => "interactive",
            "interactive" => [
                "type" => "button",
                "body" => [
                    "text" => "¿Confirmas tu registro?"
                ],
                "footer" => [
                    "text" => "Selecciona una de las opciones"
                ],
                "action" => [
                    "buttons" => [
                        [
                            "type" => "reply",
                            "reply" => [
                                "id" => "btnsi",
                                "title" => "Si"
                            ]
                        ],[
                            "type" => "reply",
                            "reply" => [
                                "id" => "btnno",
                                "title" => "No"
                            ]
                        ],[
                            "type" => "reply",
                            "reply" => [
                                "id" => "btntalvez",
                                "title" => "Tal Vez"
                            ]
                        ]
                    ]
                ]
            ]
        ]);

    }else if($comentario == '1') {

        $data = json_encode([
            "messaging_product" => "whatsapp",
            "recipient_type" => "individual",
            "to" => $concat,  // Usa el número formateado
            "type" => "text",
            "text" => [
                "preview_url" => false,
                "body" => "Lorem ipsum is simply dummy "
            ]
        ]);

    }else if($comentario == '2') {
        $data = json_encode([
            "messaging_product" => "whatsapp",
            "recipient_type" => "individual",
            "to" => $concat,  // Usa el número formateado
            "type" => "location",
            "location"=> [
                    "latitude" => "20.5222851",
                    "longitude" => "-100.8307739",
                    "name" => "Hermos Sucursal Celaya",
                    "address" => "Hermos Sucursal Celaya"
                ]
        ]);

// no envia el doc pdf
        }else if ($comentario == '3') {
            $data = json_encode([
                "messaging_product" => "whatsapp",    
                "recipient_type"=> "individual",
                "to" => $concat,
                "type" => "document",
                "document"=> [
                    "link" => "http://s29.q4cdn.com/175625835/files/doc_downloads/test.pdf",
                    "caption" => "Temario del Curso #001"
                ]
            ]);

// audio
    }else if ($comentario=='4') {
        $data = json_encode([
            "messaging_product" => "whatsapp",    
            "recipient_type"=> "individual",
            "to" => $concat,
            "type" => "audio",
            "audio"=> [
                "link" => "https://filesamples.com/samples/audio/mp3/sample1.mp3",
            ]
        ]);

//video
    }else if ($comentario=='5') {
        $data = json_encode([
            "messaging_product" => "whatsapp",
            "to" => $concat,
            "text" => array(
                "preview_url" => true,
                "body" => "Introducción al curso! https://youtu.be/6ULOE2tGlBM"
            )
        ]);

//hablar con 
    }else if ($comentario=='6') {
        $data = json_encode([
            "messaging_product" => "whatsapp",
            "recipient_type" => "individual",
            "to" => $concat,
            "type" => "text",
            "text" => array(
                "preview_url" => false,
                "body" => "🤝 En breve me pondré en contacto contigo. 🤓"
            )
        ]);

// horario de atencion 
    }else if ($comentario =='7') {
        $data = json_encode([
            "messaging_product" => "whatsapp",
            "recipient_type" => "individual",
            "to" => $concat,
            "type" => "text",
            "text" => array(
                "preview_url" => false,
                "body" => "📅 Horario de Atención: Lunes a Viernes. \n🕜 Horario: 8:00 a.m. a 6:00 p.m. 🤓"
            )
        ]);
    
    }else if (strpos($comentario, 'gracias') !== false) {
        $data = json_encode([
            "messaging_product" => "whatsapp",
            "recipient_type" => "individual",
            "to" => $concat,
            "type" => "text",
            "text" => array(
                "preview_url" => false,
                "body" => "Gracias a ti por contactárnos. "
            )
        ]);

    }else if (strpos($comentario, 'adios') !== false || strpos($comentario, 'bye') !== false || strpos($comentario, 'Nos vemos') !== false || strpos($comentario, 'hasta pronto') !== false) {
        $data = json_encode([
            "messaging_product" => "whatsapp",
            "recipient_type" => "individual",
            "to" => $concat,
            "type" => "text",
            "text" => array(
                "preview_url" => false,
                "body" => "Hasta luego. "
            )
        ]);

    } else {
        $data = json_encode([
            "messaging_product" => "whatsapp",
            "recipient_type" => "individual",
            "to" => $concat,  // Usa el número formateado
            "type" => "text",
            "text" => [
                "preview_url" => false,
                "body" => "🚀 Hola, visita mi web hermos.com para más información.\n \n📌Por favor, ingresa un número #️⃣ para recibir información.\n \n1️⃣. Información del Curso. ❔\n2️⃣. Ubicación del local. 📍\n3️⃣. Enviar temario en pdf. 📄\n4️⃣. Audio explicando curso. 🎧\n5️⃣. Video de Introducción. ⏯️\n6️⃣. Hablar con hermos. 🙋‍♂️\n7️⃣. Horario de Atención. 🕜"
            ]
        ]);

//botones
}



    // Configuración de la solicitud HTTP
    $options = [
        'http' => [
            'method' => 'POST',
            'header' => "Content-type: application/json\r\nAuthorization: Bearer EAAimvutjQxQBO6MiglkInawEyGAAm4wcLVX0wacRQ45XNnGrjZB3g8vrVMZCUxtYeZBzh6zPvt86H7aY7WDzxYbaCLobcTTBQpnm9obsuKfBvQ0AJgwGLfNFZBN9prtGgFT8g9NV6hFfldtKiL27MqZAvRbZAIFhI4AV1Wd1YhVGE295IHe7vwFZAWZCLCcLZCFfKvIi047hFHxtZAOCVZAXkfHZB3RxdtYIvPwZAitUZD\r\n",
            'content' => $data,
            'ignore_errors' => true
        ]
    ];

// Realizar la solicitud
    $context = stream_context_create($options);
    $response = file_get_contents('https://graph.facebook.com/v21.0/553646597821221/messages', false, $context);

// Comprobar la respuesta
    if ($response === false) {
        echo "Error al enviar el mensaje\n";
    } else {
        echo "Mensaje enviado con éxito\n";
    }

    $context = stream_context_create($options);
    $response = file_get_contents(' https://graph.facebook.com/v21.0/553646597821221/messages ', false, $context);

    if ($response === false) {
        echo "Error al enviar el mensaje\n";
    } else {
        echo "Mensaje enviado con éxito\n";
    }
}



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);


    recibirMensajes($data, http_response_code());
} else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['hub_mode']) && isset($_GET['hub_verify_token']) && isset($_GET['hub_challenge']) && $_GET['hub_mode'] === 'subscribe' && $_GET['hub_verify_token'] === TOKEN_HERMOS) {
        echo $_GET['hub_challenge'];
    } else {
        http_response_code(403);
    }
}
