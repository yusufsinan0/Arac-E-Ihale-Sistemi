<?php 
require_once 'config.php';

$auctionId = $_GET['auctionid'];

try{
$stmt = $db ->prepare('SELECT  * FROM quotes WHERE auctionid =:auctionid');
$stmt->bindParam(':auctionid', $auctionId, PDO::PARAM_INT);
$stmt->execute();
$quotes = $stmt->fetchAll(PDO::FETCH_ASSOC);


    exit(json_encode(["status" => "success", "data" => $quotes]));
} catch (Exception $e){
    exit(json_encode(["status" => "failed", "message" => $e->getMessage()]));
}

?>