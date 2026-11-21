<?php
/**
 * Nokshapoth Admin Backend API
 * ----------------------------
 * cPanel / PHP হোস্টিংয়ে সংরক্ষিত অর্ডার ও ড্রপড-অফ লিড রিটার্ন করার এন্ডপয়েন্ট
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

$type = $_GET['type'] ?? 'orders';
$baseDir = dirname(__DIR__);

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
