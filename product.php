<?php
    // Соединяемся с базой данных
    require_once("connect.php");
    require_once("other_func.php");

    $sort_array = ['articul' => 'артикул',
        'name' => 'наименование',
        'brand' => 'производитель (бренд)',
        'type' => 'тип',
        'color' => 'цвет',
        'price' => 'цена',
        'sale' => 'скидка',
        'created_at' => 'дата добавления товара'];

    $filter_array = ['n' => 'наименование',
        'b' => 'бренд',
        't' => 'тип',
        'po' => 'цена от',
        'pd' => 'цена до',
        'cs' => 'дата с',
        'cp' => 'дата по'];

    $sort = check_get('sort', 'created_at', $sort_array);

    $filter_selected = check_get('filter', "", $filter_array);

    $how_many_for_output = check_get('display_by', '10', ['to' => 1, 'from' => 100]);

    /* получаем количество сообщений в таблице, а если применена фильтрация, то количество сообщений, 
    подходящих под этот фильтр*/

    if (isset($_GET['filter']) && isset($_GET['search'])) {
        
        $f = ['n' => "name LIKE '%",
            'b' => "brand LIKE '%",
            't' => "type LIKE '%",
            'po' => "price >= '",
            'pd' => "price <= '",
            'cs' => "created_at >= '",
            'cp' => "created_at <= '"];

        $_GET['search'] = $pdo->quote($_GET['search']);

        $percent = ($_GET['filter'] == 'n' || $_GET['filter'] == 'b' || $_GET['filter'] == 't') ? "%" : "";

        $where = 'WHERE' . " (" . $f[$_GET['filter']] . $_GET['search'] . "$percent')";
        $where2 = " (" . $f[$_GET['filter']] . $_GET['search'] . "$percent') AND";

        $query = "SELECT COUNT(id) FROM $name_table $where";
        try {   
            $count = $pdo->query($query);
            $number_of_records = $count->fetchColumn();
        } catch (PDOException $e) {
            echo "Неудалось получить количество записей в таблице: " . $e->getMessage();
        }
    }
    else {
        $where = $where2 = "";
        // Запрос, получающий id самой последней добавленной в таблицу записи 
        // (По сути показывающий сколько всего записей в таблице)
        $query = "SELECT id FROM $name_table ORDER BY id DESC LIMIT 1";
        try {   
            $count = $pdo->query($query);
            $number_of_records = $count->fetch()['id'];
        } catch (PDOException $e) {
            echo "Неудалось получить количество записей в таблице: " . $e->getMessage();
        }
    }

    // Вычисляем количество страниц, для этого делим число записей на по сколько их нужно выводить
    // Если получилось дробное число
    if (is_float($total_page = $number_of_records / $how_many_for_output))
        // Значит для этого количества записей нужна своя страница
        // Поэтому отбрасываем дробную часть и прибавляем единицу  
        $total_page = (int) $total_page + 1; 

    $page = check_get('page', '1', ['to' => 2, 'from' => $total_page]);
    
    /* в зависимости от того, на какой странице мы сейчас находимся
    запись с которой следует начать вывод будет разной 
    вычисляем количество сообщений, которые должны быть на предыдущих страницах, и прибавляем 1
    вот это как раз и будет по счёту запись которую нам нужно будет первой вывести
    выполняем запрос
    может быть такое что
    на странице которая перед страницей на которой мы сейчас 
    последней выведенной записью была запись значение колонки по которой мы сортируем совпадает со значением с которым нам нужно начать вывод (которое мы получили сейчас в переменную $start_point)
    Поэтому нам нужно записать все id (их на самом деле может быть больше) этих записей в строку
    Чтобы потом вывод был без них
    $prev_array пуст только тогда когда записей нет в таблице, или если при поиске ничего не найдено */
    $prev = $how_many_for_output * ($page - 1) + 1;
    $query = "SELECT id, $sort FROM $name_table $where ORDER BY $sort DESC, id DESC LIMIT $prev";
    
    try {
        $prev_obj = $pdo->query($query); 
        $prev_array = $prev_obj->fetchall(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Ошибка получения startpoint: " . $e->getMessage();
    } 
    if (!empty($prev_array)) {
        $start_point = $prev_array[$prev - 1][$sort]; // с какой записи выводить
        
        $prev_array = array_reverse($prev_array); 
        $str = "";
        
        for ($j = 1; $j < count($prev_array); $j++)
            if ($start_point == $prev_array[$j][$sort])
                $str .= "{$prev_array[$j]['id']}, ";
            else break;
        $start_point = "'" . $start_point . "'";
        if ($str) {
            // Убираем последнюю запятую
            $str = preg_replace('#,\s$#s', '', $str);
            $str = " AND id NOT IN (" . $str . ")";
        } 
    }
        