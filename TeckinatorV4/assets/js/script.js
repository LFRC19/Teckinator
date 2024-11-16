// Función para iniciar el juego y obtener la primera pregunta
function startGame() {
    fetch('akinator.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ action: 'start' })
    })
    .then(response => response.json())
    .then(data => {
        // Mostrar la primera pregunta
        document.getElementById('question').innerText = data.question;
        document.getElementById('start-button').style.display = 'none'; // Ocultar el botón de inicio
    })
    .catch(error => console.error('Error al iniciar el juego:', error));
}

// Función para enviar la respuesta del usuario
function sendAnswer(answer) {
    fetch('akinator.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ action: 'answer', user_answer: answer })
    })
    .then(response => response.json())
    .then(data => {
        // Mostrar la siguiente pregunta o el resultado
        if (data.game_over) {
            document.getElementById('question').innerText = `Pensé en: ${data.result.name} - ${data.result.description}. ¿Quieres jugar de nuevo?`;
            document.getElementById('start-button').style.display = 'block'; // Mostrar el botón de reinicio
        } else {
            document.getElementById('question').innerText = data.question;
        }
    })
    .catch(error => console.error('Error al enviar respuesta:', error));
}

// Función para reiniciar el juego
function resetGame() {
    fetch('akinator.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ action: 'reset' })
    })
    .then(response => response.json())
    .then(data => {
        // Mostrar el mensaje de reinicio y la primera pregunta
        document.getElementById('question').innerText = data.question;
        document.getElementById('start-button').style.display = 'none'; // Ocultar el botón de inicio
    })
    .catch(error => console.error('Error al reiniciar el juego:', error));
}
