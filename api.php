<?php
// api.php - O PHP agora é apenas um Provedor de Dados (API)
require_once __DIR__ . '/src/Model/Task.php';

// Configuramos o cabeçalho para que o navegador entenda que estamos enviando JSON
header('Content-Type: application/json');

$pdo = new PDO('sqlite:' . __DIR__ . '/tasks.sqlite');
$model = new Task($pdo);
$model->initializeSchema();

$action = $_GET['action'] ?? 'list';

try {
    switch ($action) {
        case 'list':
            // Retorna a lista de tarefas formatada em JSON
            echo json_encode($model->getAll());
            break;

        case 'create':
            // Recebe dados JSON do Front-end
            $data = json_decode(file_get_contents('php://input'), true);
            if (!is_array($data)) {
                throw new InvalidArgumentException('O corpo da requisição deve conter um JSON válido.');
            }

            $model->create([
                'title' => $data['title'] ?? '',
                'descricao' => $data['description'] ?? $data['descricao'] ?? '',
                'data_vencimento' => $data['due_date'] ?? $data['data_vencimento'] ?? '',
                'responsavel' => $data['responsavel'] ?? '',
            ]);
            echo json_encode(['status' => 'success']);
            break;

        case 'complete':
            $model->complete($_GET['id']);
            echo json_encode(['status' => 'success']);
            break;

        case 'delete':
            $model->delete($_GET['id']);
            echo json_encode(['status' => 'success']);
            break;
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
}