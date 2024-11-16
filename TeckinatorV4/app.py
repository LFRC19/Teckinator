# app.py
from flask import Flask, request, jsonify
from flask_cors import CORS
import akinator

app = Flask(__name__)
CORS(app)

aki = akinator.Akinator()

@app.route('/start', methods=['POST'])
def start_game():
    try:
        question = aki.start_game()
        return jsonify({"question": question})
    except Exception as e:
        # Registrar el error y responder con un mensaje de error
        print("Error al iniciar el juego:", e)
        return jsonify({"error": "No se pudo iniciar el juego de Akinator"}), 500

@app.route('/answer', methods=['POST'])
def answer_question():
    data = request.get_json()
    answer = data.get('answer', 0)
    if answer not in [0, 1, 2, 3, 4]:
        return jsonify({"error": "Respuesta no válida"}), 400

    if aki.progression >= 95:
        aki.win()
        result = {
            "name": aki.first_guess['name'],
            "description": aki.first_guess['description'],
            "image": aki.first_guess['absolute_picture_path']
        }
        return jsonify({"game_over": True, "result": result})
    else:
        question = aki.answer(answer)
        return jsonify({"question": question, "progression": aki.progression})

if __name__ == '__main__':
    app.run(port=5000)

