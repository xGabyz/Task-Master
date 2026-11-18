<?php
interface TaskViewInterface {
    public function displayTasks(array $tasks);

    public function showError(string $message);
}
?>
