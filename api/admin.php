<?php
/**
 * Nokshapoth Admin Backend API
 * ----------------------------
 * cPanel / PHP হোস্টিংয়ে সংরক্ষিত অর্ডার ও ড্রপড-অফ লিড রিটার্ন করার এন্ডপয়েন্ট
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

$baseDir = dirname(__DIR__);

// আপডেট অর্ডার স্ট্যাটাস (Update Order Status)
$action = $_REQUEST['action'] ?? '';
if ($action === 'update_status') {
    $raw = file_get_contents('php://input');
    $postData = json_decode($raw, true) ?: $_POST;
    $orderId = preg_replace('/[^A-Za-z0-9_-]/', '', $postData['order_id'] ?? $_GET['order_id'] ?? '');
    $newStatus = trim($postData['status'] ?? $_GET['status'] ?? 'পেমেন্ট পেন্ডিং');

    $filePath = $baseDir . '/data/orders/' . $orderId . '.json';
    if ($orderId && file_exists($filePath)) {
        $order = json_decode(file_get_contents($filePath), true);
        if ($order) {
            $order['order_status'] = $newStatus;
            file_put_contents($filePath, json_encode($order, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            echo json_encode(['success' => true, 'order_id' => $orderId, 'status' => $newStatus]);
            exit;
        }
    }
    echo json_encode(['success' => false, 'message' => 'Order file not found', 'order_id' => $orderId]);
    exit;
}

$type = $_GET['type'] ?? 'orders';

if ($type === 'leads') {
    $leadsDir = $baseDir . '/data/leads/';
    $leads = [];
    if (is_dir($leadsDir)) {
        $files = scandir($leadsDir);
        foreach ($files as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) === 'json') {
                $content = json_decode(file_get_contents($leadsDir . $file), true);
                if ($content) {
                    $leads[] = $content;
                }
            }
        }
    }
    usort($leads, function($a, $b) {
        return strtotime($b['updated_at'] ?? '0') - strtotime($a['updated_at'] ?? '0');
    });
    echo json_encode(['success' => true, 'count' => count($leads), 'leads' => $leads], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}

// ডিফল্ট: Orders
$ordersDir = $baseDir . '/data/orders/';
$orders = [];
if (is_dir($ordersDir)) {
    $files = scandir($ordersDir);
    foreach ($files as $file) {
        if (pathinfo($file, PATHINFO_EXTENSION) === 'json') {
            $content = json_decode(file_get_contents($ordersDir . $file), true);
            if ($content) {
                $orders[] = $content;
            }
        }
    }
}

usort($orders, function($a, $b) {
    return strtotime($b['created_at'] ?? '0') - strtotime($a['created_at'] ?? '0');
});

echo json_encode(['success' => true, 'count' => count($orders), 'orders' => $orders], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
