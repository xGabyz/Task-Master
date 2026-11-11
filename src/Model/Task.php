<?php
class Task {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function initializeSchema(): void {
        $this->pdo->exec("CREATE TABLE IF NOT EXISTS tasks (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title TEXT NOT NULL,
            done INTEGER DEFAULT 0,
            descricao TEXT DEFAULT '',
            data_vencimento TEXT DEFAULT '',
            responsavel TEXT DEFAULT ''
        )");

        $columns = $this->pdo->query("PRAGMA table_info(tasks)")->fetchAll(PDO::FETCH_COLUMN, 1);

        foreach ([
            'descricao' => "TEXT DEFAULT ''",
            'data_vencimento' => "TEXT DEFAULT ''",
            'responsavel' => "TEXT DEFAULT ''",
        ] as $column => $definition) {
            if (!in_array($column, $columns, true)) {
                $this->pdo->exec("ALTER TABLE tasks ADD COLUMN $column $definition");
            }
        }
    }

    public function getAll(): array {
        $stmt = $this->pdo->query("SELECT * FROM tasks ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(array $data): void {
        $title = trim((string) ($data['title'] ?? ''));
        $descricao = trim((string) ($data['descricao'] ?? ''));
        $dataVencimento = trim((string) ($data['data_vencimento'] ?? ''));
        $responsavel = trim((string) ($data['responsavel'] ?? ''));

        if ($title === '') {
            throw new InvalidArgumentException('O título da tarefa não pode estar vazio!');
        }

        $stmt = $this->pdo->prepare("INSERT INTO tasks (title, descricao, data_vencimento, responsavel) VALUES (:title, :descricao, :data_vencimento, :responsavel)");
        $stmt->bindValue(':title', $title);
        $stmt->bindValue(':descricao', $descricao);
        $stmt->bindValue(':data_vencimento', $dataVencimento);
        $stmt->bindValue(':responsavel', $responsavel);
        $stmt->execute();
    }

    public function complete(int $id): void {
        $this->pdo->exec("UPDATE tasks SET done = 1 WHERE id = " . (int) $id);
    }

    public function delete(int $id): void {
        $this->pdo->exec("DELETE FROM tasks WHERE id = " . (int) $id);
    }
}
?>