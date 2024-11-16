<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

// Función para registrar datos en un archivo de log
function logData($data) {
    file_put_contents("debug_log.txt", print_r($data, true) . "\n", FILE_APPEND);
}

// Obtener y decodificar la solicitud
$input = file_get_contents("php://input");
$request = json_decode($input, true);
logData(["Request" => $request]);  // Registrar la solicitud inicial

if ($request['action'] === 'start') {
    // Iniciar una nueva sesión de Akinator
    $apiResponse = file_get_contents("http://api-ru1.akinator.com/ws/new_session?partner=1");
    $data = json_decode($apiResponse, true);

    logData(["Start API Response" => $data]);  // Registrar la respuesta de la API

    if (!$data || !isset($data['parameters']['identification']['session'])) {
        echo json_encode(['error' => 'Error al iniciar la sesión con la API de Akinator']);
        exit;
    }

    $sessionData = [
        'session' => $data['parameters']['identification']['session'],
        'signature' => $data['parameters']['identification']['signature'],
        'step' => $data['parameters']['step_information']['step']
    ];

    echo json_encode([
        'question' => $data['parameters']['step_information']['question'],
        'sessionData' => $sessionData
    ]);
} elseif ($request['action'] === 'answer') {
    // Procesar la respuesta del usuario
    $session = $request['sessionData']['session'] ?? null;
    $signature = $request['sessionData']['signature'] ?? null;
    $step = $request['sessionData']['step'] ?? null;
    $answer = $request['answer'];

    if (!$session || !$signature || !$step) {
        echo json_encode(['error' => 'Datos de sesión incompletos']);
        exit;
    }

    $apiResponse = file_get_contents("http://api-ru1.akinator.com/ws/answer?session=$session&signature=$signature&step=$step&answer=$answer");
    $data = json_decode($apiResponse, true);

    logData(["Answer API Response" => $data]);  // Registrar la respuesta de la API

    if (!$data || !isset($data['parameters']['question'])) {
        echo json_encode(['error' => 'Error al obtener la siguiente pregunta de la API de Akinator']);
        exit;
    }

    $sessionData = [
        'session' => $session,
        'signature' => $signature,
        'step' => $data['parameters']['step'],
        'progression' => intval($data['parameters']['progression'])
    ];

    if ($sessionData['progression'] >= 95) {
        // Si el progreso es suficiente, obtener el resultado final
        $resultResponse = file_get_contents("http://api-ru1.akinator.com/ws/list?session=$session&signature=$signature&step=$step&size=1");
        $result = json_decode($resultResponse, true);

        logData(["List API Response" => $result]);  // Registrar la respuesta final de la API

        if (!$result || !isset($result['parameters']['elements'][0]['element'])) {
            echo json_encode(['error' => 'Error al obtener el resultado de la API de Akinator']);
            exit;
        }

        $element = $result['parameters']['elements'][0]['element'];

        echo json_encode([
            'game_over' => true,
            'result' => [
                'name' => $element['name'],
                'description' => $element['description']
            ]
        ]);
    } else {
        // Continuar con la siguiente pregunta
        echo json_encode([
            'question' => $data['parameters']['question'],
            'sessionData' => $sessionData
        ]);
    }
} else {
    echo json_encode(['error' => 'Acción no válida']);
}
