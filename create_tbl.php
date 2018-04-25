<?php 
    require_once("connect.php");
    // Считываем содержимое файла, это запрос, который создаёт таблицу, если она не существует
    $query = file_get_contents("create_table.sql");
    // Заменяем в запросе :name_table на наше название таблицы 
    $query = str_replace(':name_table', $name_table, $query);
    try {
        // Выполняем запрос
        $pdo->query($query);
    } catch (PDOException $e) {
        echo "Ошибка создания таблицы: " . $e->getMessage();
    }
    header("Location: view.php");