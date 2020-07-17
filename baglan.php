<?php

try {
    $db = new PDO('mysql:host=localhost;dbname=icerikler', 'root', 'root');
} catch (PDOException $e){
    echo $e->getMessage();
}

?>