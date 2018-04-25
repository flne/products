<?php
    setcookie("display_by", $_POST['display_by']); 
    header("Location: view.php{$_POST['referer_query']}");
