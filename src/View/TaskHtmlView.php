<?php
class TaskHtmlView implements TaskViewInterface {
    public function displayTasks(array $tasks) {
        $error = null;
        require __DIR__ . '/List.php';
    }

    public function showError(string $message) {
        $error = $message;
        $tasks = [];
        require __DIR__ . '/List.php';
    }
}
?>