<?php 
require_once 'config.php';

$id = $_POST['auctionid'];

try{
$stmt = $db ->prepare('DELETE    FROM quotes WHERE auctionid =:auctionid');
$stmt->bindParam(':auctionid', $auctionId, PDO::PARAM_INT);
$stmt->execute();
$quotes = $stmt->fetchAll(PDO::FETCH_ASSOC);
$json = json_encode($quotes);
echo $json;



} catch (PDOException $e){
    echo "quotes bağlantısı yanlış".$e->getMessage();
}

?>