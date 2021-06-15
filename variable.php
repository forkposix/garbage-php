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

// GET ile get yollarsanız alınan veriyi döndürür.
// GET ile set yollarsanız val ile belirtilen değeri kaydeder

if(isset($_GET['get'])){
	$data=$_GET['get'];
	$data=str_replace("\"","",$data);
	$data=str_replace("'","",$data);
	$data=str_replace(" ","",$data);
	$sorgu = $baglanti->query("SELECT * from variables where val=\"".$data."\";");
	$cikti = $sorgu->fetch_assoc();
	echo($cikti["val"]);
}else if(isset($_GET['set'])){
	$data=$_GET['set'];
	$data=str_replace("\"","",$data);
	$data=str_replace("'","",$data);
	$data=str_replace(" ","",$data);
	$val=$_GET['val'];
	$val=str_replace("\"","",$val);
	$val=str_replace("'","",$val);
	$val=str_replace(" ","",$val);
	$baglanti->query("DELETE from variables where `var` = \"".$data."\";");
	$qry="insert into variables (var, val) values (\"".$data."\",\"".$val."\");";
	$baglanti->query($qry);
	echo($qry);
}else{
        echo("Usage:\n");
	echo("<br>Get value: curl \"https://uwuleng.sourceforge.io?get=variable\"\n");
	echo("<br>Set value: curl \"https://uwuleng.sourceforge.io?set=variable&val=value\"\n");
}
?>
