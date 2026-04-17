<?php
header('Content-Type: application/json');

ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../storage/debug.log');
error_reporting(E_ALL);

ob_start();

try {
    // Load bootstrap to get Config class
    require_once __DIR__ . '/../app/bootstrap.php';

    $input = json_decode(file_get_contents('php://input'), true);
    $listingIds = isset($input['listing_ids']) ? $input['listing_ids'] : [];

    error_log('verify-listings.php called with IDs: ' . json_encode($listingIds));

    if (empty($listingIds) || !is_array($listingIds)) {
        ob_end_clean();
        echo json_encode(['success' => false, 'message' => 'No listing IDs provided']);
        exit;
    }

    $pdo = \App\Core\Database::pdo();

    error_log('Database connection successful');

    // Check which listings exist and are active
    $placeholders = implode(',', array_fill(0, count($listingIds), '?'));
    $stmt = $pdo->prepare("
        SELECT id FROM commodity_listings
        WHERE id IN ($placeholders) AND status = 'active'
    ");
    $stmt->execute($listingIds);
    $validListings = $stmt->fetchAll(PDO::FETCH_COLUMN);

    error_log('Valid listings: ' . json_encode($validListings));

    ob_end_clean();
    echo json_encode([
        'success' => true,
        'valid_ids' => $validListings
    ]);

} catch (Exception $e) {
    error_log('Error in verify-listings.php: ' . $e->getMessage());
    ob_end_clean();
    echo json_encode(['success' => false, 'message' => 'Failed to verify listings']);
}
