<h3>Ders Listesi</h3>

<form action="" method="get">
    <input type="text" class="tarih" name="baslangic" value="<?php echo isset($_GET['baslangic']) ? $_GET['baslangic'] : '' ?>" placeholder="Başlangıç Tarihi">
    <input type="text" class="tarih" name="bitis" value="<?php echo isset($_GET['bitis']) ? $_GET['bitis'] : '' ?>" placeholder="Bitiş Tarihi"> <br>
    <input type="text" value="<?php echo isset($_GET['arama']) ? $_GET['arama'] : '' ?>" name="arama" placeholder="İçeriklerde ara..">
    <button type="submit">Arama</button>
</form>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
$('.tarih').datepicker({
    dateFormat: 'yy-mm-dd'
});
</script>

<?php

// select * from TABLO_ADİ
// INNER JOIN tablo_adi ON tablo_adi.id = tablo_adi.id
// query
// - fetch() - fetchAll()
// prepare-execute

$where = [];
$sql = 'SELECT icerikler.id, icerikler.baslik, GROUP_CONCAT(kategoriler.ad) as kategori_adi, GROUP_CONCAT(kategoriler.id) as kategori_id, icerikler.onay FROM icerikler
INNER JOIN kategoriler ON FIND_IN_SET(kategoriler.id, icerikler.kategori_id)';
if (isset($_GET['arama']) && !empty($_GET['arama'])){
    $where[] = '(icerikler.baslik LIKE "%' . $_GET['arama'] . '%" || icerikler.icerik LIKE "%' . $_GET['arama'] . '%")';
}
if (isset($_GET['baslangic']) && !empty($_GET['baslangic']) && isset($_GET['bitis']) && !empty($_GET['bitis'])){
    $where[] = 'icerikler.tarih BETWEEN "' . $_GET['baslangic'] . ' 00:00:00" AND "' . $_GET['bitis'] . ' 23:59:59"';
}
if (count($where) > 0){
    $sql .= ' WHERE ' . implode(' && ', $where);
}
$sql .= ' GROUP BY icerikler.id
ORDER BY icerikler.id DESC';

$icerikler = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);

/*
$sorgu = $db->prepare('SELECT * FROM icerikler WHERE id = ?');
$sorgu->execute([
    3
]);
$icerikler = $sorgu->fetch(PDO::FETCH_ASSOC);
*/

?>

<?php if ($icerikler): ?>
    <ul>
        <?php foreach ($icerikler as $icerik): ?>
            <li>
                <?php echo $icerik['baslik'] ?>
                <?php 
                $kategoriAdlari = explode(',', $icerik['kategori_adi']);
                $kategoriIdleri = explode(',', $icerik['kategori_id']);
                foreach ($kategoriAdlari as $key => $val){
                    echo '[<a href="index.php?sayfa=kategori&id=' . $kategoriIdleri[$key] . '">' . $val . '</a> ]';
                }
                ?>
                <div>
                    <?php if ($icerik['onay'] == 1): ?>
                        <a href="index.php?sayfa=oku&id=<?php echo $icerik['id'] ?>">[OKU]</a>
                    <?php endif; ?>
                    <a href="index.php?sayfa=guncelle&id=<?php echo $icerik['id']?>">[DÜZENLE]</a>
                    <a href="index.php?sayfa=sil&id=<?php echo $icerik['id']?>">[SİL]</a>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <div>
        <?php if (isset($_GET['arama'])): ?>
            Aradığınız kriterlere uygun ders bulunamadı!
        <?php else: ?>
            Henüz eklenmiş ders bulunmuyor!
        <?php endif; ?>
    </div>
<?php endif; ?>