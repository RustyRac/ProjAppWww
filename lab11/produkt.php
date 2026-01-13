<?php
$conn = new mysqli("localhost","root","","moja_strona");
$conn->set_charset("utf8mb4");

$slug = $_GET['slug'] ?? '';
$p = $conn->query("SELECT * FROM products WHERE slug='$slug'")->fetch_assoc();
if(!$p) die("Produkt nie istnieje");

function Dostepnosc($p){
if($p['status']!='aktywny') return "Ukryty";
if($p['stock']<=0) return "Wyprzedany";
if($p['expires_at'] && strtotime($p['expires_at'])<time()) return "Wygasł";
return "Dostępny";
}

$stan = Dostepnosc($p);
$cenaBrutto = $p['price_netto'] * (1 + $p['vat']/100);
?>
<!DOCTYPE html>
<html lang="pl">
<head>
<meta charset="UTF-8">
<title><?= $p['title'] ?></title>
<style>
body{font-family:Arial;background:#f4f6f8;margin:0}
.card{max-width:900px;margin:40px auto;background:#fff;border-radius:10px;
box-shadow:0 10px 25px rgba(0,0,0,.1);display:flex;gap:30px;padding:30px}
.img{width:300px}
.img img{width:100%;border-radius:8px}
.info{flex:1}
.price{font-size:28px;color:#27ae60;font-weight:bold}
.status{margin:10px 0;padding:6px 12px;display:inline-block;border-radius:20px}
.ok{background:#2ecc71;color:white}
.bad{background:#e74c3c;color:white}
.btn{background:#2980b9;color:white;padding:12px 25px;border-radius:25px;
text-decoration:none;display:inline-block;margin-top:20px}
</style>
</head>

<body>

<div class="card">
<div class="img">
<img src="<?= $p['image'] ?>">
</div>

<div class="info">
<h1><?= $p['title'] ?></h1>

<div class="price"><?= number_format($cenaBrutto,2) ?> zł brutto</div>

<div class="status <?= $stan=='Dostępny'?'ok':'bad' ?>">
<?= $stan ?>
</div>

<p><?= nl2br($p['description']) ?></p>

<a class="btn" href="#">Dodaj do koszyka</a>
</div>
</div>

</body>
</html>
