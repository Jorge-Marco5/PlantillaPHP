# Plantilla PHP Moderna - MVC

Plantilla lista para usar con arquitectura MVC, routing moderno y mejores prácticas de PHP 8.2+

## 📋 Requisitos

- PHP 8.2 o superior
- Composer
- MySQL/MariaDB (opcional)

## 🚀 Instalación

1. **Clonar/Descargar el proyecto**

```bash
git clone <tu-repo>
cd tu-proyecto
```

2. **Instalar dependencias**

```bash
composer install
```

3. **Configurar variables de entorno**

```bash
cp .env.example .env
```

Edita el archivo `.env` con tus configuraciones.

4. **Iniciar servidor de desarrollo**

```bash
composer dev
# O manualmente: php -S localhost:8000
```

5. **Visitar la aplicación**

Abre tu navegador en: `http://localhost:8000`

## 📁 Estructura del Proyecto

```
.
├── public/              # Directorio público (document root)
│   ├── css/            # Estilos
│   ├── js/             # JavaScript
│   └── images/         # Imágenes
├── src/                # Código fuente
│   ├── Controllers/    # Controladores
│   ├── Models/         # Modelos (agrega según necesites)
│   ├── Services/       # Lógica de negocio
│   └── Core/           # Clases del núcleo
│       ├── Router.php  # Sistema de rutas
│       ├── Database.php # Conexión a DB
│       └── View.php    # Sistema de vistas
├── views/              # Plantillas
│   ├── layouts/        # Layouts
│   └── home/           # Vistas por sección
├── routes/             # Definición de rutas
│   └── web.php
├── config/             # Archivos de configuración
├── storage/            # Archivos temporales, logs
├── tests/              # Tests unitarios
├── .env.example        # Variables de entorno ejemplo
├── composer.json       # Dependencias
├── index.php           # Punto de entrada
└── README.md
```

## 🛣️ Rutas

Las rutas se definen en `routes/web.php`:

```php
// Ruta simple
$router->get('/', [HomeController::class, 'index']);

// Ruta con parámetros
$router->get('/users/{id}', [UserController::class, 'show']);

// Ruta con closure
$router->post('/api/data', function($request) {
    return ['status' => 'ok'];
});
```

## 🎮 Controladores

Crear un controlador en `src/Controllers/`:

```php
<?php

namespace App\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function index(Request $request): Response
    {
        // Retornar vista
        return $this->view('users/index', [
            'users' => []
        ]);
    }

    public function api(Request $request): Response
    {
        // Retornar JSON
        return $this->json([
            'data' => []
        ]);
    }
}
```

## 🎨 Vistas

Las vistas están en `views/`. Usar PHP nativo con escape automático:

```php
<h1><?= \App\Core\View::escape($data['title']) ?></h1>

<?php foreach ($data['items'] as $item): ?>
    <div><?= \App\Core\View::escape($item['name']) ?></div>
<?php endforeach; ?>
```

## 🗄️ Base de Datos

Configurar en `.env`:

```env
# Configuración de la aplicación
APP_NAME="PlantillaPHP"
APP_ENV=development
APP_DEBUG=true
APP_URL=http://localhost:8000

# Base de datos
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_NAME=mydatabase
DB_USER=root
DB_PASSWORD=
DB_CHARSET=utf8mb4

# Sesiones
SESSION_LIFETIME=120
SESSION_DRIVER=file
```

Usar en tu código:

```php
use App\Core\Database;

$db = Database::getInstance();

// Query con resultados
$users = $db->query('SELECT * FROM users WHERE active = ?', [1]);

// Ejecutar sin resultados
$db->execute('UPDATE users SET active = ? WHERE id = ?', [1, 5]);

// Último ID insertado
$id = $db->lastInsertId();
```

## 🧪 Testing

```bash
composer test
```

## 🔍 Análisis Estático

```bash
composer analyse
```

## 📦 Scripts Disponibles

- `composer dev` - Inicia servidor de desarrollo
- `composer test` - Ejecuta tests
- `composer analyse` - Análisis estático con PHPStan

## 🎯 Próximos Pasos

1. Crear tus modelos en `src/Models/`
2. Agregar tus controladores en `src/Controllers/`
3. Definir tus rutas en `routes/web.php`
4. Crear tus vistas en `views/`
5. Agregar estilos en `public/css/`

## 📚 Recursos

- [Documentación PHP](https://www.php.net/manual/es/)
- [PSR Standards](https://www.php-fig.org/psr/)
- [Symfony HttpFoundation](https://symfony.com/doc/current/components/http_foundation.html)

## 📝 Notas

- El servidor de desarrollo de PHP NO debe usarse en producción
- Para producción usa Apache/Nginx con `public/` como document root
- Siempre escapa datos en las vistas para prevenir XSS
- Usa prepared statements para prevenir SQL injection
- Configura el archivo .env con tus variables de entorno y protege el archivo en el servidor

## 🤝 Contribuir

¡Las contribuciones son bienvenidas! Abre un issue o PR.

## 📄 Licencia

MIT
