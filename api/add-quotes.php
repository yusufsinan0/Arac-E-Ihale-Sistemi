<?php 
require_once 'config.php';

$auctionId = $_POST['auctionId'];
$amount = $_POST['amount'];

try {
    $stmt = $db->prepare('SELECT * FROM auctions WHERE id = :auctionid ORDER BY id DESC LIMIT 1');
    $stmt->bindParam(':auctionid', $auctionId, PDO::PARAM_INT);
    $stmt->execute();
    $auction = $stmt->fetch(PDO::FETCH_ASSOC);
    if(!$auction) throw new Exception("İlan bulunamadı");
    if($auction["status"] != "Processing") throw new Exception("İlan durumu müsait değil");
    if(strtotime($auction["expire_at"]) < strtotime("NOW")) throw new Exception("İlanın süresi geçmiş");
    
    
    // Önceki teklifi almak için bir sorgu hazırlayın
    $stmt = $db->prepare('SELECT amount FROM quotes WHERE auctionid = :auctionid ORDER BY amount DESC LIMIT 1');
    $stmt->bindParam(':auctionid', $auctionId, PDO::PARAM_INT);
    $stmt->execute();
    $previousQuote = $stmt->fetch(PDO::FETCH_ASSOC);

    // Eğer önceki teklif yoksa veya yeni teklif öncekiden küçükse
    if ($amount < $previousQuote['amount'] + $auction["minquote"]) throw new Exception('Son teklif tutarından düşük teklif verilemez! Minimum verebileceğiniz teklif: ' . ($previousQuote['amount'] + $auction["minquote"]));
    
    
    $stmt = $db->prepare('INSERT INTO quotes (auctionid, amount) VALUES (:auctionid, :amount)');
    $stmt->bindParam(':auctionid', $auctionId, PDO::PARAM_INT);
    $stmt->bindParam(':amount', $amount, PDO::PARAM_STR);
    $stmt->execute();

    $quoteId = $db->lastInsertId();

    $response = array(
        'status'=>'success',
        'data'=>[
            'quote_id' => $quoteId,
            'amount' => $amount
        ]
    );
    
    $update = $db->prepare("UPDATE auctions SET maxquote=:amount WHERE id=:auctionid");
    $update->execute(["amount" => $amount, "auctionid" => $auctionId]);
    
    exit(json_encode($response));
    
} catch(Exception $e) {
    exit(json_encode(array('status' => 'failed', 'message' => $e->getMessage())));
}
?>
