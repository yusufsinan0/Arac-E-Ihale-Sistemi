<?php 
require_once 'config.php';

try {
    $statuses = isset($_GET["status"]) ? [$_GET["status"]] : ["Waiting", "Processing", "Completed"];
    $types = isset($_GET["type"]) ? [$_GET["type"]] : [1, 2];

    // Dinamik olarak placeholder'lar oluşturun
    $statusPlaceholders = implode(',', array_fill(0, count($statuses), '?'));
    $typePlaceholders = implode(',', array_fill(0, count($types), '?'));

    $stmt = $db->prepare("SELECT * FROM auctions WHERE status IN ($statusPlaceholders) AND type IN ($typePlaceholders)");

    // İki farklı array'i execute ederken birleştirin
    $stmt->execute(array_merge($statuses, $types));

    $auctions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    exit(json_encode(["status" => "success", "data" => $auctions]));
} catch (Exception $e) {
    exit(json_encode(["status" => "failed", "message" => $e->getMessage()]));
}
?>
