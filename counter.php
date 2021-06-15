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
if(isset($_GET['c'])){
$data=$_GET['c'];
}else{
$data="main";
}
$data=str_replace("\"","",$data);
$data=str_replace("'","",$data);
$data=str_replace(" ","",$data);
try{
    $sorgu = $baglanti->query("SELECT * from variables where var=\"".$data."\";");
    $cikti = $sorgu->fetch_assoc();
    $val=(int)$cikti["val"];
}catch (exception $e){
    $val=0;
}
$val+=1;
try{
    $baglanti->query("DELETE from variables where `var` = \"".$data."\";");
}catch (exception $e){
    echo("");
}
$qry="insert into variables (var, val) values (\"".$data."\",\"".$val."\");";
$baglanti->query($qry);
echo($val);
?>
