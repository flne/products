<!DOCTYPE html>
<html lang="ru">
    <head>
        <title>Товары</title>
        <meta charset='utf-8'>
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>
    <body>
    <?php
    	require_once("product.php");
    ?>
    <form action='many.php' method='POST'>
         <input type='submit' class='gray' value='Добавить 1000 записей'>
    </form><br>
	<?php
		if (isset($_GET['filter']) && isset($_GET['search'])) {
    ?>
        <a id='cancel-find' href="view.php">Отменить поиск</a>
    <?php 
    	} ?>
    	<form action="view.php" method="GET">
            <span>Фильтр: </span>
                <select class='gray' name='filter'>
                    <?php
                        print_select($filter_array, $filter_selected, true);
                    ?>
                </select>
                <input type="text" name='search' id='input-filter'>
                <input type='submit' class='gray' value='Искать'>
        </form>
        <br>
        <?php
        	$hvost = hvost('page');
        	// Если количество страниц больше одной
        	if ($total_page > 1) {
        ?>
        	    <span id='left'>Страницы:
        <?php	    
        	    $m1['a'] = 1;
        	    // Вычисляем диапазон страниц которые идут рядом с текущей
        	    // С какой выводить
        	    $start = $page - 4;
        	    if ($start <= 2) $start = 2;
        	    else $m1['b'] = '.';


        	    // По какую выводить
        	    $end = $page + 4;
        	    if ($total_page - 1 <= $end) $end = $total_page - 1; 
        	    else $m3['c'] = '.';
        	    $m3['d'] = $total_page;

        	    $range = $start <= $end ? range($start, $end) : [];
        	    // Собираем массив страниц
        	    $page_array = array_merge($m1, $range, $m3);

 				print_links($page_array, $hvost, $page, 1);
 		?>
        	    </span>
        <?php
        	}
        	// Если сообщений больше пяти предлагаем пользователю
        	// выбрать по сколько их нужно выводить
        	if ($number_of_records > 5) {
        ?>
            	<form action='display_by_cookie.php' id='left' method='POST'>
            	    <span>Выводить по: </span>
            	    <select class='gray' name='display_by'>
            	        <?php
            	            $display_by_array = [5, 10, 15, 20, 25, 30, 50, 100];
            	        	print_select($display_by_array, $how_many_for_output);
            	        ?>
            	    </select>
            	    <input type='hidden' name='referer_query' value='<?php
            	    	if ($hvost) echo "?$hvost"; 
 					?>'>
            	    <input type='submit' class='gray' value='ОК'>
            	</form>  
        <?php
        	}
        ?>
        <table border="2">
    		<tr>
    			<?php 
        			print_links($sort_array, hvost('sort'), $sort, 'created_at', true);
        		?>
        	</tr>
        <?php
        if (!empty($prev_array)) {
        	$query = "SELECT articul, name, brand, type, color, price, sale, created_at FROM $name_table WHERE $where2 ($sort <= $start_point) $str ORDER BY $sort DESC, id DESC LIMIT $how_many_for_output";
        	try {
            	$products_on_page = $pdo->query($query);
            	while ($product = $products_on_page->fetch(PDO::FETCH_ASSOC)) { 
            	    echo "<tr>";
            	    foreach ($product as $k => $v)
            	        echo "<td>" . $v . "</td>"; 
            	    echo "</tr>";
            	}
        	} catch (PDOException $e) {
            	echo "Ошибка вывода сообщений: " . $e->getMessage();
        	}
        }
        ?>
    	</table>
    	<?php if (empty($prev_array) && isset($_GET['filter']) && isset($_GET['search'])) echo "Ничего не найдено!"; ?>
	</body>
</html>		