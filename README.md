# Libro Total - Backend

Este sistema es un backend desarrollado en PHP que se comunica con una base de datos y devuelve respuestas en formato JSON. Está diseñado para integrarse con un proyecto de Android Studio, permitiendo la gestión de préstamos y devoluciones de libros en una biblioteca.

## Características
- Gestión de préstamos y devoluciones de libros.
- Generación de códigos QR para identificar a los lectores.
- Envío de correos electrónicos:
  - Confirmación de registro con código QR.
  - Notificación de retraso en la devolución de libros.
- Validación de políticas de préstamo:
  - Máximo 2 libros por lector.
  - Solo un ejemplar por libro.
  - Plazo de devolución de 7 días.
  - Restricciones en caso de pérdida o daño.
- Clasificación de libros por categorías y asignación de pasillos en la biblioteca.

## Tecnologías implementadas
El desarrollo del proyecto se realizó utilizando:
- **PHP** como lenguaje de backend.
- **MySQL** para la gestión de la base de datos.
- **phpMailer** para el envío de correos electrónicos.
- **phpQrCode de Endroid** para la generación de códigos QR.
- Formato JSON para la comunicación con el sistema en Android.

### Instalación
Tras descargar o clonar el repositorio, sigue estos pasos para instalarlo:

1. **Descargar el código**
   - Clonar el repositorio con:
     ```sh
     git clone https://github.com/HLBrandon/backed-biblio-app-moviles.git
     ```
   - O descargar el ZIP y extraerlo en la ubicación deseada.

2. **Ubicación del proyecto**
   - Si se usa en un entorno local, guardar el proyecto en:
     ```
     xampp/htdocs/biblio
     ```
   - O en cualquier carpeta donde se desee ejecutar el backend.

3. **Configuración**
   - Copiar el archivo `config.example.php` y renombrarlo como `config.php`.
   - Editar las variables de entorno para la conexión a la base de datos y configuración del envío de correos.

     ```ini
      define('APP_NAME', 'Sistema de Biblioteca Digital');
	 
      define("HOST", "localhost");
      define("USERNAME", "root");
      define("PASS", "");
      define("DBNAME", "biblio");
      define("PORT", "3306");

      define("MAIL_HOST", "smtp.example.com");
      define("MAIL_USERNAME", "email@example.com");
      define("MAIL_PASSWORD", "");
      define("MAIL_PORT", 587);
     ```

4. **Importar Base de datos**
    - En la carpeta `database` se encuentra el archivo sql.
    - Importar mediante phpMyAdmin o MySQL Workbench

## Política de préstamos
El sistema implementa las siguientes restricciones:
- Cada lector puede pedir **máximo 2 libros** a la vez.
- No puede solicitar **más de un ejemplar del mismo libro**.
- **Plazo máximo de 7 días** para la devolución.
- Si un lector no devuelve el libro en el tiempo establecido, **se bloquea el préstamo de nuevos libros**.
- En caso de **pérdida o daño**, no podrá solicitar más libros.

## Rutas funcionamiento
El backend y rutas funcionan mediante solicitudes GET a los archivos. Por ejemplo:
```sh
http://localhost/biblio/categorias/crear.php?nombre="Aventura"&pasillo=2
```

```sh
http://localhost/biblio/autores/crear.php?nombre="Robert Jordan"
```

## Usuario y contraseña
- Nombre de Usuario: admin
- Contraseña admin123@