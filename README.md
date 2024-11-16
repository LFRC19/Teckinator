# Teckinator

## Descripción

**Teckinator** es una aplicación web interactiva diseñada para adivinar al maestro en el que el usuario está pensando a través de una serie de preguntas dinámicas. Inspirada en aplicaciones como Akinator, Teckinator utiliza técnicas de inteligencia artificial y aprendizaje automático para mejorar con cada interacción, ofreciendo una experiencia lúdica y educativa en el ámbito escolar.

## Características

- **Interfaz Amigable**: Diseño intuitivo y responsivo que facilita la interacción del usuario.
- **Aprendizaje Automático**: El sistema aprende de las respuestas y contribuciones de los usuarios para mejorar su precisión.
- **Base de Datos Escalable**: Estructura de datos eficiente que permite manejar una gran cantidad de maestros y características.
- **Contribución de Usuarios**: Los usuarios pueden agregar nuevos maestros y características cuando el sistema no logra adivinar correctamente.

## Requisitos del Sistema

- **Servidor Web** con soporte para PHP 7.0 o superior.
- **Base de Datos MySQL** 5.6 o superior.
- **Navegador Web Moderno**: Chrome, Firefox, Edge, Safari.

## Instalación

### Clonar el Repositorio

```bash
git clone https://github.com/tu_usuario/teckinator.git
```

### Configurar la Base de Datos

1. **Crear la Base de Datos**:

   Accede a tu servidor MySQL y crea una nueva base de datos:

   ```sql
   CREATE DATABASE teckinator_db;
   ```

2. **Importar las Tablas**:

   Importa el archivo `database.sql` que se encuentra en el directorio `db` para crear las tablas necesarias:

   ```bash
   mysql -u tu_usuario -p teckinator_db < db/database.sql
   ```

3. **Configurar la Conexión**:

   Actualiza el archivo `conexion.php` con tus credenciales de acceso a la base de datos:

   ```php
   <?php
   $host = 'localhost';
   $user = 'tu_usuario';
   $password = 'tu_contraseña';
   $dbname = 'teckinator_db';

   $enlace = mysqli_connect($host, $user, $password, $dbname);

   if (!$enlace) {
       die('Error de Conexión (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
   }
   ?>
   ```

### Configurar el Servidor Web

- **Ubicación de Archivos**: Coloca los archivos del proyecto en el directorio raíz de tu servidor web (por ejemplo, `htdocs` en XAMPP o `www` en WAMP).

- **Configuración Adicional**: Asegúrate de que el servidor tenga habilitado el soporte para sesiones en PHP.

## Uso

1. **Acceder a la Aplicación**:

   Navega a la URL donde se encuentra instalada la aplicación (por ejemplo, `http://localhost/teckinator`).

2. **Iniciar una Partida**:

   En la página principal, haz clic en "Comenzar" para iniciar el juego.

3. **Responder a las Preguntas**:

   El sistema te hará una serie de preguntas de sí o no para intentar adivinar el maestro en el que estás pensando.

4. **Resultado**:

   - **Adivinanza Correcta**: Si el sistema adivina correctamente, puedes iniciar una nueva partida.
   - **Adivinanza Incorrecta**: Si el sistema no adivina, podrás ayudar a mejorar la aplicación agregando al maestro y sus características.

## Estructura del Proyecto

- `index.php`: Página principal donde se desarrolla el juego.
- `respuesta.php`: Maneja las respuestas del usuario y permite agregar nuevos maestros.
- `conexion.php`: Archivo de conexión a la base de datos.
- `insertar_maestro.php`: Interfaz para agregar maestros y características de manera manual (acceso restringido).
- `css/`: Directorio que contiene los estilos CSS.
- `db/database.sql`: Archivo SQL con la estructura de la base de datos.

## Dependencias

- **PHP** 7.0 o superior
- **MySQL** 5.6 o superior
- **Tailwind CSS**: Utilizado para el diseño y estilo de la interfaz.
- **Font Awesome**: Para los iconos utilizados en la aplicación.

## Contribución

Las contribuciones al proyecto son bienvenidas. Puedes colaborar de las siguientes maneras:

- **Reportando Errores**: Abre un "issue" en el repositorio describiendo el problema encontrado.
- **Solicitando Nuevas Características**: Sugiere mejoras o nuevas funcionalidades que consideres útiles.
- **Enviando Pull Requests**: Si deseas realizar cambios en el código, puedes hacer un fork del repositorio y luego enviar un pull request con tus modificaciones.

## Licencia

Este proyecto está licenciado bajo la **Licencia MIT**. Para más detalles, consulta el archivo `LICENSE`.

## Contacto

Si tienes preguntas, sugerencias o necesitas ayuda, puedes contactarnos a través de:

- **Correo Electrónico**: l21210421@tectijuana.edu.mx
- **Sitio Web**: (En proceso de creacion)

---
