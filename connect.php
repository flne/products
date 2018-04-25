<?php
	// Название таблицы
	$name_table = "list_of_products_table";
	try {
	    // Создаём объект класса PDO
	    $pdo = new PDO(
	        "mysql:host=localhost;dbname=", // имя драйвера PDO, адрес сервера, и имя базы данных
	        'root', // Имя пользователя
	        'root', // Пароль
	        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]); // Выставляем режим генерации исключений
	}
	catch (PDOException $e) {
	    echo "Подключение к базе данных не удалось: " . $e->getMessage();
	}
