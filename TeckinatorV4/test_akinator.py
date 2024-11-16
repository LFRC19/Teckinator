# test_akinator.py
import akinator

try:
    aki = akinator.Akinator()
    question = aki.start_game()
    print("Primera pregunta:", question)
except Exception as e:
    print("Error al iniciar el juego de Akinator:")
    print(e)