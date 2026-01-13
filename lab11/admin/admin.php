<?php
session_start();
require_once("../cfg.php");

/* ===== BAZA ===== */
$conn = new mysqli("localhost","root","","moja_strona");
$conn->set_charset("utf8mb4");

/* ===== LOGOWANIE ===== */
function FormularzLogowania(){
return '<h2>Panel CMS</h2><form method="post">
Email:<input name="login_email"><br>
Hasło:<input type="password" name="login_pass"><br>
<input type="submit" name="login" value="Zaloguj"></form>';
}

if(!isset($_SESSION['logged_in'])){
if(isset($_POST['login']) && $_POST['login_email']===$login && $_POST['login_pass']===$pass){
$_SESSION['logged_in']=true; header("Location: admin.php"); exit;}
echo FormularzLogowania(); exit;
}

/* ===== PODSTRONY ===== */
function ListaPodstron($c){
$r=$c->query("SELECT * FROM page_list");
echo "<h2>Podstrony</h2><a href='?action=dodaj'>➕ Dodaj</a><br>";
while($p=$r->fetch_assoc())
echo "<div>{$p['page_title']} |
<a href='?action=edytuj&id={$p['id']}'>Edytuj</a> |
<a href='?action=usun&id={$p['id']}'>Usuń</a></div>";
}

function DodajPodstrone($c){
if(isset($_POST['add'])) $c->query("INSERT INTO page_list(page_title,page_content,status)
VALUES('{$_POST['title']}','{$_POST['content']}',1)");
echo "<form method=post>
<input name=title><textarea name=content></textarea>
<input type=submit name=add value=Dodaj></form>";
}

function EdytujPodstrone($c,$id){
if(isset($_POST['save'])) $c->query("UPDATE page_list SET 
page_title='{$_POST['title']}',page_content='{$_POST['content']}'
WHERE id=$id");
$p=$c->query("SELECT * FROM page_list WHERE id=$id")->fetch_assoc();
echo "<form method=post>
<input name=title value='{$p['page_title']}'>
<textarea name=content>{$p['page_content']}</textarea>
<input type=submit name=save value=Zapisz></form>";
}

function UsunPodstrone($c,$id){ $c->query("DELETE FROM page_list WHERE id=$id"); }
/* ===== KATEGORIE ===== */
function ListaKategorii($c){
    echo "<h2>Kategorie</h2><a href='?action=dodaj_kategorie'>➕ Dodaj</a><br>";
    $r=$c->query("SELECT * FROM categories WHERE matka=0");
    while($m=$r->fetch_assoc()){
    echo "<b>{$m['nazwa']}</b><a href='?action=usun_kategorie&id={$m['id']}'>❌</a><br>";
    $d=$c->query("SELECT * FROM categories WHERE matka={$m['id']}");
    while($x=$d->fetch_assoc())
    echo "↳ {$x['nazwa']} <a href='?action=usun_kategorie&id={$x['id']}'>❌</a><br>";
    }
    }
    
    function DodajKategorie($c){
    if(isset($_POST['add_cat'])) $c->query("INSERT INTO categories(nazwa,matka)
    VALUES('{$_POST['nazwa']}','{$_POST['matka']}')");
    echo "<form method=post><input name=nazwa>
    <select name=matka><option value=0>Główna</option></select>
    <input type=submit name=add_cat value=Dodaj></form>";
    }
    
    function UsunKategorie($c,$id){
    $c->query("DELETE FROM categories WHERE matka=$id");
    $c->query("DELETE FROM categories WHERE id=$id");
    }
    
    /* ===== PRODUKTY ===== */
    function Dostepnosc($p){
    if($p['status']!='aktywny') return "Ukryty";
    if($p['stock']<=0) return "Wyprzedany";
    if($p['expires_at'] && strtotime($p['expires_at'])<time()) return "Wygasł";
    return "Dostępny";
    }
    
    function PokazProdukty($conn)
    {
    echo "<h2>Produkty</h2><a href='?action=dodaj_produkt'>➕ Dodaj produkt</a><br><br>";
    $r=$conn->query("SELECT * FROM products ORDER BY id DESC");
    
    while($p=$r->fetch_assoc()){
    $stan = Dostepnosc($p);
    
    echo "<div style='border:1px solid #ccc;padding:10px;margin-bottom:10px'>
    <b>{$p['title']}</b><br>
    Cena: {$p['price_netto']} zł netto<br>
    Magazyn: {$p['stock']} szt | Status: $stan<br>
    <a target='_blank' href='../produkt.php?slug={$p['slug']}'>Podgląd strony</a> |
    <a href='?action=edytuj_produkt&id={$p['id']}'>Edytuj</a> |
    <a href='?action=usun_produkt&id={$p['id']}'>Usuń</a>
    </div>";
    }
    }
    
    
    function DodajProdukt($conn)
    {
    if(isset($_POST['add'])){
    $title = $conn->real_escape_string($_POST['title']);
    $desc  = $conn->real_escape_string($_POST['description']);
    $exp   = $_POST['expires_at'];
    $price = $_POST['price_netto'];
    $vat   = $_POST['vat'];
    $stock = $_POST['stock'];
    $stat  = $_POST['status'];
    $size  = $_POST['size'];
    $img   = $_POST['image'];
    
    $slug = strtolower(trim(preg_replace('/[^a-z0-9]+/i','-',$title),'-'));
    
    $conn->query("INSERT INTO products
    (title,description,expires_at,price_netto,vat,stock,status,size,image,slug)
    VALUES('$title','$desc','$exp','$price','$vat','$stock','$stat','$size','$img','$slug')");
    
    echo "<p style='color:green'>Produkt dodany. <a target='_blank' href='../produkt.php?slug={$p['slug']}'>";
    }
    
    echo "<h2>Nowy produkt</h2>
    <form method='post'>
    Nazwa:<input name='title'><br>
    Opis:<textarea name='description'></textarea><br>
    Data wyg:<input type='date' name='expires_at'><br>
    Cena:<input name='price_netto'><br>
    VAT:<input name='vat' value='23'><br>
    Stan:<input name='stock'><br>
    
    Status:
    <select name='status'>
    <option value='aktywny'>aktywny</option>
    <option value='ukryty'>ukryty</option>
    <option value='wyprzedany'>wyprzedany</option>
    </select><br>
    
    Gabaryt:
    <select name='size'>
    <option value='mały'>mały</option>
    <option value='średni'>średni</option>
    <option value='duży'>duży</option>
    <option value='paleta'>paleta</option>
    </select><br>
    
    Zdjęcie (URL):<input name='image'><br>
    <input type='submit' name='add' value='Dodaj produkt'>
    </form>";
    }
    
    function EdytujProdukt($conn,$id)
    {
    if(isset($_POST['save'])){
    $title = $conn->real_escape_string($_POST['title']);
    $desc  = $conn->real_escape_string($_POST['description']);
    $exp   = $_POST['expires_at'];
    $price = $_POST['price_netto'];
    $vat   = $_POST['vat'];
    $stock = $_POST['stock'];
    $stat  = $_POST['status'];
    $size  = $_POST['size'];
    $img   = $_POST['image'];
    
    $slug = strtolower(trim(preg_replace('/[^a-z0-9]+/i','-',$title),'-'));
    
    $conn->query("UPDATE products SET
    title='$title',description='$desc',expires_at='$exp',
    price_netto='$price',vat='$vat',stock='$stock',
    status='$stat',size='$size',image='$img',slug='$slug'
    WHERE id=$id");
    
    echo "<p style='color:green'>Zapisano</p>";
    }
    
    $p = $conn->query("SELECT * FROM products WHERE id=$id")->fetch_assoc();
    
    echo "<h2>Edytuj produkt</h2>
    <form method='post'>
    Nazwa:<input name='title' value='{$p['title']}'><br>
    Opis:<textarea name='description'>{$p['description']}</textarea><br>
    Data wyg:<input type='date' name='expires_at' value='{$p['expires_at']}'><br>
    Cena:<input name='price_netto' value='{$p['price_netto']}'><br>
    VAT:<input name='vat' value='{$p['vat']}'><br>
    Stan:<input name='stock' value='{$p['stock']}'><br>
    
    Status:
    <select name='status'>
    <option value='aktywny' ".($p['status']=='aktywny'?'selected':'').">aktywny</option>
    <option value='ukryty' ".($p['status']=='ukryty'?'selected':'').">ukryty</option>
    <option value='wyprzedany' ".($p['status']=='wyprzedany'?'selected':'').">wyprzedany</option>
    </select><br>
    
    Gabaryt:
    <select name='size'>
    <option value='mały' ".($p['size']=='mały'?'selected':'').">mały</option>
    <option value='średni' ".($p['size']=='średni'?'selected':'').">średni</option>
    <option value='duży' ".($p['size']=='duży'?'selected':'').">duży</option>
    <option value='paleta' ".($p['size']=='paleta'?'selected':'').">paleta</option>
    </select><br>
    
    Zdjęcie:<input name='image' value='{$p['image']}'><br>
    <input type='submit' name='save' value='Zapisz zmiany'>
    </form>";
    }
    
    
    function UsunProdukt($c,$id){ $c->query("DELETE FROM products WHERE id=$id"); }
/* ===== MENU ===== */
echo "<h1>ADMIN</h1>
<a href='admin.php'>Podstrony</a> |
<a href='?action=kategorie'>Kategorie</a> |
<a href='?action=produkty'>Produkty</a> |
<a href='?logout=1'>Wyloguj</a><hr>";

if(isset($_GET['logout'])){session_destroy(); header("Location: admin.php");}

/* ===== ROUTER ===== */
$a=$_GET['action']??'';

switch($a){
case 'dodaj': DodajPodstrone($conn); break;
case 'edytuj': EdytujPodstrone($conn,(int)$_GET['id']); break;
case 'usun': UsunPodstrone($conn,(int)$_GET['id']); ListaPodstron($conn); break;
case 'kategorie': ListaKategorii($conn); break;
case 'dodaj_kategorie': DodajKategorie($conn); break;
case 'usun_kategorie': UsunKategorie($conn,(int)$_GET['id']); ListaKategorii($conn); break;
case 'produkty': PokazProdukty($conn); break;
case 'dodaj_produkt': DodajProdukt($conn); break;
case 'edytuj_produkt': EdytujProdukt($conn,(int)$_GET['id']); break;
case 'usun_produkt': UsunProdukt($conn,(int)$_GET['id']); PokazProdukty($conn); break;
default: ListaPodstron($conn);
}
?>
    