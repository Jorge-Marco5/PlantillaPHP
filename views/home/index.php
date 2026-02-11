<div class="hero">
    <!-- svg de php en ./public/images-->
    <img src="/public/images/php.svg" alt="PHP Logo" class="php-logo">
    <h1><?= \App\Core\View::escape($data['title']) ?></h1>
    <p><?= \App\Core\View::escape($data['message']) ?></p>
    <h3 class="badge">Entorno: <?= \App\Core\View::escape($data['env']) ?></h3>
</div>

<div class="features">
    <div class="feature">
        <h3>🚀 Moderno</h3>
        <p>PHP 8.4+, Composer, PSR-4 autoloading</p>
    </div>
    
    <div class="feature">
        <h3>🏗️ Arquitectura MVC</h3>
        <p>Código organizado y escalable</p>
    </div>
    
    <div class="feature">
        <h3>🔧 Fácil de usar</h3>
        <p>Router simple, vistas limpias, APIs JSON</p>
    </div>
    <div class="feature">
        <h3>🌙 Modo oscuro</h3>
        <p>Modo oscuro pre-configurado para una mejor experiencia</p>
    </div>
</div>

<div class="code-example">
    <h3>Ejemplo de rutas</h3>
    <pre><code>
        // ruta - controlador - metodo
        $router->get('/', [HomeController::class, 'index']);
        $router->get('/about', [HomeController::class, 'about']);
    </code></pre>
</div>

<div class="code-example">
    <h3>Ejemplo de controladores</h3>
    <pre><code>
    // controlador de vista que retorna una vista con datos
    public function index(Request $request): Response
    {
        $data = [
            'title' => 'Bienvenido',
            'message' => '¡Tu aplicación PHP moderna está funcionando!',
            'env' => $_ENV['APP_ENV'] ?? 'production'
        ];
        // contenido de la vista - datos - layout base
        return $this->view('home/index', $data, 'main');
    }

    // controlador de vista que retorna una vista sin datos
    public function about(Request $request): Response
    {
        return $this->view('home/about', [
            'title' => 'Acerca de'
        ]);
    }

    // controlador de api que retorna json
    public function api(Request $request): Response
    {
        return $this->json([
            'status' => 'success',
            'message' => 'API funcionando correctamente',
            'timestamp' => date('Y-m-d H:i:s')
        ]);
    }
    </code></pre>
</div>