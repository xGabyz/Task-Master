<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../src/Model/Task.php';

class TaskModelTest extends TestCase
{
    private PDO $pdo;
    private Task $model;

    protected function setUp(): void
    {
        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $this->model = new Task($this->pdo);
        $this->model->initializeSchema();
    }

    public function testNaoPodeCriarTarefaSemTitulo(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('O título da tarefa não pode estar vazio!');

        $this->model->create([
            'title' => '  ',
            'descricao' => 'Fazer compras',
            'data_vencimento' => '2026-12-31',
            'responsavel' => 'Ana',
        ]);
    }

    public function testCriaTarefaComOsDadosInformados(): void
    {
        $this->model->create([
            'title' => '  Fazer compras  ',
            'descricao' => '  Comprar frutas  ',
            'data_vencimento' => '2026-12-31',
            'responsavel' => 'Ana',
        ]);

        $tasks = $this->model->getAll();

        $this->assertCount(1, $tasks);
        $this->assertSame('Fazer compras', $tasks[0]['title']);
        $this->assertSame('Comprar frutas', $tasks[0]['descricao']);
        $this->assertSame('2026-12-31', $tasks[0]['data_vencimento']);
        $this->assertSame('Ana', $tasks[0]['responsavel']);
    }
}
?>