<?php
class Akinator {
    function newSession() {
        // Llama a la API de Akinator para iniciar una nueva sesión
        $data = json_decode(file_get_contents("http://api-ru1.akinator.com/ws/new_session?partner=1"));
        return [
            'session' => $data->parameters->identification->session,
            'sig' => $data->parameters->identification->signature,
            'question' => $data->parameters->step_information->question,
            'step' => $data->parameters->step_information->step,
            'progress' => 0
        ];
    }

    function answer($sessionData, $answer) {
        // Llama a la API de Akinator para procesar la respuesta y obtener la siguiente pregunta
        $session = $sessionData['session'];
        $sig = $sessionData['sig'];
        $step = $sessionData['step'];
        $data = json_decode(file_get_contents("http://api-ru1.akinator.com/ws/answer?session=$session&signature=$sig&step=$step&answer=$answer"));
        
        return [
            'session' => $session,
            'sig' => $sig,
            'question' => $data->parameters->question,
            'step' => $data->parameters->step,
            'progress' => round(intval($data->parameters->progression))
        ];
    }

    function getGuess($sessionData) {
        // Llama a la API de Akinator para obtener el personaje adivinado
        $session = $sessionData['session'];
        $sig = $sessionData['sig'];
        $step = $sessionData['step'];
        $data = json_decode(file_get_contents("http://api-ru1.akinator.com/ws/list?session=$session&signature=$sig&step=$step&size=2&max_pic_width=246&max_pic_height=299&pref_photos=OK-FR&mode_question=0"));
        
        return [
            'name' => $data->parameters->elements[0]->element->name,
            'description' => $data->parameters->elements[0]->element->description,
            'image' => $data->parameters->elements[0]->element->picture_path
        ];
    }
}
?>
