<?php
// Carrega automaticamente classes organizadas nas pastas do projeto.
spl_autoload_register(static function (string $class): void {
    if (preg_match('/^[A-Za-z_][A-Za-z0-9_]*$/', $class) !== 1) {
        return;
    }

    foreach (['Controller', 'Model', 'View'] as $directory) {
        $file = __DIR__ . "/src/$directory/$class.php";
        if (is_file($file)) {
            require_once $file;
            return;
        }
    }
});

// 1. Conexão com o banco (Único lugar no sistema inteiro!)
$pdo = new PDO('sqlite:' . __DIR__ . '/tasks.sqlite');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// 2. Roteamento básico
$controller = new TaskController($pdo);
$action = $_GET['action'] ?? ($_SERVER['REQUEST_METHOD'] === 'POST' ? 'create' : 'index');

if (method_exists($controller, $action)) {
    $controller->$action();
} else {
    echo "Página não encontrada 404";
}
?>