<div class="hero">
    <!-- svg de php en ./public/images-->
    <img src="/images/php.svg" alt="PHP Logo" class="php-logo">
    <h1><?= \App\Core\View::escape($data['title']) ?></h1>
    <p><?= \App\Core\View::escape($data['message']) ?></p>
    <p class="badge">Entorno: <?= \App\Core\View::escape($data['env']) ?></p>
</div>

<div class="features">
    <div class="feature">
        <h3>🚀 Moderno</h3>
        <p>PHP 8.2+, Composer, PSR-4 autoloading</p>
    </div>
    
    <div class="feature">
        <h3>🏗️ Arquitectura MVC</h3>
        <p>Código organizado y escalable</p>
    </div>
    
    <div class="feature">
        <h3>🔧 Fácil de usar</h3>
        <p>Router simple, vistas limpias, APIs JSON</p>
    </div>
</div>

<div class="code-example">
    <h3>Ejemplo de uso:</h3>
    <pre><code>// routes/web.php
$router->get('/hello/{name}', function($request, $name) {
    return ['message' => "Hola {$name}!"];
});</code></pre>
</div>