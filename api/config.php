<?php

$host = "localhost";
$dbname = "ihale_api";
$username = "ihale_api";
$password = "3xYrWYKBP68mGZBx";

try{
    $db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $db -> exec("SET NAMES 'utf8'");
}catch (PDOException $e){
    echo "hatalı bağlantı ".$e->getMessage();
}

$_POST = getPost();

function getPost(){
    $input = file_get_contents("php://input");
    if(empty($input)) return false;

    $input = iconv("windows-1251", "UTF-8", $input);
    parse_str($input, $arr);

    if(count($arr) < 1) return false;

    return $arr;
}

?>