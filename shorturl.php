<?php 
//debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//config
$site_name="https://example.com";
$server_host="localhost";
$server_user="username";
$server_pass="1";
$server_data="dbname";
$connection = new mysqli($server_host, $server_user, $server_pass, $server_data);
$connection->query("create table short (link longtext, id tinytext);");

function getLinkById($id){
    global $connection;
    try {
        $query = $connection->query("SELECT * from short where id=\"".crc32($id)."\"");
        if($query){
            $output = $query->fetch_array();
            return $output["link"];
        }
        return "";
    }catch(Exception $e){
        return "";
    }

}
function createLink($id, $link){
    global $connection;
    if (getLinkById($id) == ""){
        $connection->query("INSERT INTO short (id,link) VALUES (\"".crc32($id)."\", \"".base64_encode($link)."\")");
    }else{
        echo("Linki başkası daha önce dızlamış.");
        exit();
    }
    return $id;
}

if(isset($_GET["id"])){
    $link=base64_decode(getLinkById($_GET["id"]));
    echo("<meta http-equiv=\"refresh\" content=\"0; url=".$link."\" />");
}elseif(isset($_POST["id"]) && isset($_POST["link"])){
    createLink($_POST["id"],$_POST["link"]);
    ?>
<!DOCTYPE html>
<html>
<body>

<center>
     <h2><?php echo($site_name."/?id=".urlencode($_POST["id"])); ?></h2>
</center>
</body>
</html>

<?php
}else{
?>

<!DOCTYPE html>
<html>
<body>

<center>
     <h2>URL Shorter</h2>
</center>
<style>
.button {
  background-color: #4CAF50;
  border: none;
  border-radius: 4px;
  color: white;
  padding: 15px 32px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  margin: 4px 2px;
  cursor: pointer;
}
input[type=text] {
  padding: 16px 20px;
  border: 1px solid blue;
  border-radius: 4px;
  background-color: #f1f1f1;
}
</style>
<center>
<form action="/" method="post">
  Name:<br>
  <input type="text" name="id" value=""><br>
  URL:<br>
  <input type="text" name="link" value=""><br><br>
  <input class="button" type="submit" value="Get short link">
</form>
</center>
</body>
</html>



<?php
}
?>
