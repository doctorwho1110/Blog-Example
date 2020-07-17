<?php

// UPDATE tablo_adi SET kol1 = değer1 WHERE kol = değ

if (!isset($_GET['id']) || empty($_GET['id'])){
    header('Location:index.php');
    exit;
}

$sorgu = $db->prepare('SELECT * FROM icerikler
WHERE id = ?');
$sorgu->execute([
    $_GET['id']
]);
$icerik = $sorgu->fetch(PDO::FETCH_ASSOC);

if (!$icerik){
    header('Location:index.php');
    exit;
}
$icerikKategoriler = explode(',', $icerik['kategori_id']);

$kategoriler = $db->query('SELECT * FROM kategoriler ORDER BY ad ASC')->fetchAll(PDO::FETCH_ASSOC);

if (isset($_POST['submit'])){

    $baslik = isset($_POST['baslik']) ? $_POST['baslik'] : $icerik['baslik'];
    $icerik_ = isset($_POST['icerik']) ? $_POST['icerik'] : $icerik['icerik'];
    $onay = isset($_POST['onay']) ? $_POST['onay'] : 0;
    $kategori_id = isset($_POST['kategori_id']) && is_array($_POST['kategori_id']) ? implode(',', $_POST['kategori_id']) : null;

    if (!$baslik){
        echo 'Başlık ekleyin!';
    } elseif (!$icerik_){
        echo 'İçeriği belirleyin!';
    } elseif (!$kategori_id){
        echo 'Kategori seçin!';
    } else {

        $sorgu = $db->prepare('UPDATE icerikler SET
        baslik = ?,
        icerik = ?,
        onay = ?,
        kategori_id = ?
        WHERE id = ?');
        $guncelle = $sorgu->execute([
            $baslik, $icerik_, $onay, $kategori_id, $icerik['id']
        ]);
        
        if ($guncelle){
            header('Location:index.php?sayfa=oku&id=' . $icerik['id']);
        } else {
            echo 'Güncelleme işlemi başarısız!';
        }

    }

}

/*
$sorgu = $db->prepare('UPDATE icerikler SET
baslik = ?,
icerik = ?,
onay = ?
WHERE id = ?');
$guncelle = $sorgu->execute([
    'yeni başlık', 'yeni içerik', 1, 3
]);

if ($guncelle){
    echo 'Güncelleme işlemi başarılı!';
} else {
    echo 'Güncelleme işlemi başarısız!';
}
*/

?>

<form action="" method="post">

    Başlık: <br>
    <input type="text" value="<?php echo isset($_POST['baslik']) ? $_POST['baslik'] : $icerik['baslik'] ?>" name="baslik"> <br> <br>

    İçerik: <br>
    <textarea name="icerik" cols="30" rows="10"><?php echo isset($_POST['icerik']) ? $_POST['icerik'] : $icerik['icerik'] ?></textarea> <br> <br>

    Kategori: <br>
    <select name="kategori_id[]" multiple size="5">
        <?php foreach($kategoriler as $kategori): ?>
            <option <?php echo in_array($kategori['id'], $icerikKategoriler) ? ' selected' : '' ?> value="<?php echo $kategori['id'] ?>"><?php echo $kategori['ad'] ?></option>        
        <?php endforeach; ?>
    </select> <br> <br>

    Onay: <br>
    <select name="onay">
        <option <?php echo $icerik['onay'] == 1 ? ' selected' : '' ?> value="1">Onaylı</option>
        <option <?php echo $icerik['onay'] == 0 ? ' selected' : '' ?> value="0">Onaylı Değil</option>
    </select> <br> <br>

    <input type="hidden" name="submit" value="1">   
    <button type="submit">Güncelle</button>

</form>