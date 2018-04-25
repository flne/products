<?php
	require_once("connect.php");
	$options = [
		'articul' => ['л', 'мп', 'тфе', 'ш', 'ав', 'у', 'ме', 'ву'],
		'name' => ['телефон', 'блендер', 'фен', 'мультиварка', 'тостер', 'миксер', 'телевизор', 'часы'],
		'brand' => ['redmond', 'ritmix', 'toshiba', 'lg', 'samsung', 'fly', 'sagem', 'panasonic'],
		'color' => ['белый', 'зелёный', 'красный', 'серый', 'синий', 'чёрный', 'жёлтый'],
		'price' => ['1000', '950', '500', '5000', '2500', '3000', '1500', '4500'],
		'sale' => ['10', '50', '100', '150', '200', '250', '300', '350']];

	for ($j = 0; $j < 1000; $j++) {
		foreach ($options as $k => $v) {
			$hvost = $k == 'articul' ? "-" . rand(100, 200) : ""; 
			$m[$k] = $v[array_rand($v)] . $hvost;		
		}
		$hvost = rand(1, 15);
		$m['type'] = "тип$hvost";

		$query = "INSERT INTO $name_table VALUES (NULL, :articul, :name, :brand, :type, :color, :price, :sale, NOW())";
    	$many = $pdo->prepare($query);
        // Выполняем запрос, заменяя псевдопеременные
        $many->execute($m);
	}
	header("Location: view.php");
