<?php
    function check_get($name, $default, $mass) {
        $method = $name == 'display_by' ? $_COOKIE : $_GET;

        if (isset($method[$name])) {
            if ($name == 'page' || $name == 'display_by') {
                // true, если не целое число, 
                if (preg_match('/\D/s', $method[$name]) || 
                    // а также если оно не входит в диапазон от 'to' до 'from'
                    $method[$name] < $mass['to'] || $method[$name] > $mass['from']) {

                    if ($name == 'display_by') setcookie("display_by");
                    
                    reboot();
                }  
            }
            else if (!array_key_exists($_GET[$name], $mass)) reboot();

            return $method[$name];
                 
        }
        else return $default;        
    }
    function reboot() {
        // Посылаем заголовок перенаправления
        header("Location: view.php");
        // Останавливаем скрипт
        exit();
    }
    function hvost($str) {
        $s = "";
        foreach ($_GET as $k => $v) {
            if ($str == $k) continue;
            $s .= "$k=$v&"; 
        }
        
        if ($s) 
            $s = preg_replace('#&$#s', '', $s);
            
        return $s;
    }  
    function print_select($mass, $selected, $type = false) {
        foreach ($mass as $k => $v) {
            $a = $type ? $k : $v;
            // Тот который был выбран фиксируем
            // атрибутом selected 
            $sel = $a == $selected ? " selected" : "";
            echo "<option$sel value='$a'>$v</option>";  
        }
    }
    function print_links($mass, $hvost, $bold, $bez, $type = false) {
          
        foreach ($mass as $k => $v) {
            if ($v == '.') {
                echo "<span> ...</span>"; continue;
            }
            if ($type) {
                $a = $k;
                $kus = 'sort';
                echo "<th>";
            } else {
                $a = $v;
                $kus = 'page';
            }

            if ($hvost) {
                if ($a == $bez)
                    $get = "?$hvost";
                else $get = "?$kus=$a&$hvost";
            }
            else {
                if ($a == $bez)
                    $get = "";
                else $get = "?$kus=$a";
            }

            if ($bold == $a) 
                echo "<b> $v</b>";
            else echo " <a id='link' href='view.php$get'>$v</a>";

            if ($type) 
                echo "</th>";           
        }
        
    }
