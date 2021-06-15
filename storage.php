<?php
$server_host="mysql-u";
$server_user="u3320468rw";
$server_pass="1";
$server_data="u3320468_db";
$epoch =time();
$baglanti = new mysqli($server_host, $server_user, $server_pass, $server_data);
if ($baglanti->connect_errno > 0) {
    die("<b>Bağlantı Hatası:</b> " . $baglanti->connect_error);
}
$baglanti->query("create table data (data longtext, hash tinytext, type tinytext);");

// GET ile hash yollarsanız alınan veriyi geri ekrana basar
// GET ile list yollarsanız hash listesi çıkarır
// POST ile data yollarsanız alınan veriyi kaydeder.

if(isset($_GET['hash'])){
	$sorgu = $baglanti->query("SELECT * from data where hash=\"".$_GET['hash']."\";");
	$type="application/json";
	$icerik="";
	while($cikti = $sorgu->fetch_assoc()){
	    $icerik=$cikti["data"];
	    $type=$cikti['type'];
	}
	if(isset($_GET['type'])){
		header("Content-type: ".$_GET['type']);
	}else{
		header("Content-type: ".$type);
	}
	echo($icerik);
}else if(isset($_POST['data'])){
	$hash=hash("crc32",$_POST['data'].":".time());
	$type="application/json";
	$data=$_POST['data'];
	if(isset($_GET['type'])){
	    $type=$_GET['type'];
	}
	$type=str_replace("\"","\\\"",$type);
	$data=str_replace("\"","\\\"",$data);
	$qry="insert into data (data,hash,type) values (\"".$data."\",\"".$hash."\",\"".$type."\");";
	header("Content-type: text/plain");
	$baglanti->query($qry);
	echo($hash);
}else if(isset($_GET['list'])){
	$sorgu = $baglanti->query("SELECT hash from data;");
	header("Content-type: text/plain");
	while($cikti = $sorgu->fetch_assoc()){
	    echo($cikti["hash"]."\n");
	}
}
else if(isset($_GET['listall'])){
	$sorgu = $baglanti->query("SELECT * from data;");
	header("Content-type: application/json");
	echo("[");
	while($cikti = $sorgu->fetch_assoc()){
	    echo("{ \"hash\": \"".str_replace("\"","\\\"",$cikti["hash"])."\" , \"data\" : \"".str_replace("\"","\\\"",$cikti["data"])."\" },\n");
	}
	echo("[]\n]");
}
?>
