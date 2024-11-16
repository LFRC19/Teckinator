<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');
session_start();

// Obtener el contenido de la solicitud y decodificar
$input = file_get_contents("php://input");
$request = json_decode($input);

// Verificar si la solicitud es válida
if (is_null($request)) {
    echo json_encode(['error' => 'Solicitud inválida o vacía']);
    exit;
}

include_once 'AA.php';
$AA = new Akinator();

if ($request->action == 'start') {
    // Iniciar nueva sesión
    $_SESSION['sessionData'] = $AA->newSession();
    echo json_encode([
        'question' => $_SESSION['sessionData']['question']
    ]);
} elseif ($request->action == 'answer') {
    // Procesar la respuesta del usuario
    $userAnswer = $request->user_answer;
    $_SESSION['sessionData'] = $AA->answer($_SESSION['sessionData'], $userAnswer);

    if ($_SESSION['sessionData']['progress'] >= 99) {
        // Si el juego termina y Akinator ha adivinado el personaje
        $resultData = $AA->getGuess($_SESSION['sessionData']);
        echo json_encode([
            'game_over' => true,
            'result' => [
                'name' => $resultData['name'],
                'description' => $resultData['description']
            ]
        ]);
    } else {
        // Continuar con la siguiente pregunta
        echo json_encode([
            'question' => $_SESSION['sessionData']['question']
        ]);
    }
} elseif ($request->action == 'reset') {
    // Reiniciar el juego
    $_SESSION['sessionData'] = $AA->newSession();
    echo json_encode([
        'question' => $_SESSION['sessionData']['question']
    ]);
} else {
    echo json_encode(['error' => 'Acción no válida']);
}
?>
