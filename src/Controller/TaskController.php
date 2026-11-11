<?php
class TaskController {
    private $model;

    public function __construct(PDO $pdo) {
        $this->model = new Task($pdo); // Controller instancia o Model
    }

    public function index($error = null) {
        $tasks = $this->model->getAll();
        require __DIR__ . '/../View/List.php';
    }

    public function create() {
        try {
            $this->model->create([
                'title' => $_POST['title'] ?? '',
                'descricao' => $_POST['descricao'] ?? '',
                'data_vencimento' => $_POST['data_vencimento'] ?? '',
                'responsavel' => $_POST['responsavel'] ?? '',
            ]);
            header("Location: index.php");
            exit;
        } catch (Exception $e) {
            $this->index($e->getMessage());
        }
    }

    public function complete() {
        $this->model->complete($_GET['id']);
        header("Location: index.php");
    }

    public function delete() {
        $this->model->delete($_GET['id']);
        header("Location: index.php");
    }
}
?>