<?php

if (!isset($_GET['id']) || empty($_GET['id'])){
    header('Location:index.php');
    exit;
}

$sorgu = $db->prepare('SELECT * FROM icerikler
WHERE id = ? && onay = 1');
$sorgu->execute([
    $_GET['id']
]);
$icerik = $sorgu->fetch(PDO::FETCH_ASSOC);

if (!$icerik){
    header('Location:index.php');
    exit;
}

?>

<h3><?php echo $icerik['baslik'] ?></h3>

<div>
    <strong>Yayın Tarihi: </strong> <?php echo $icerik['tarih'] ?> <hr>
    <?php echo nl2br($icerik['icerik']) ?>
</div>