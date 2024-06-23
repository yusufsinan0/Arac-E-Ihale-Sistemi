<?php 
require_once 'config.php';

$id = $_GET['id'];

try{
    $stmt = $db -> prepare('SELECT * FROM auctions WHERE id=:id');
    $stmt-> bindParam(':id',$id,PDO::PARAM_INT);
    $stmt->execute();
    $getAuctions = $stmt->fetch(PDO::FETCH_ASSOC);
    

    exit(json_encode(["status" => "success", "data" => $getAuctions]));
}catch(Exception $e){
    exit(json_encode(["status" => "failed", "message" => $e->getMessage()]));
}
?>