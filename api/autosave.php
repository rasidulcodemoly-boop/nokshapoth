<?php
/**
 * Nokshapoth Abandoned Cart Lead Autosave
 * ---------------------------------------
 * কাস্টমার ফর্মে নাম বা মোবাইল নম্বর টাইপ করার সাথে সাথে ব্যাকএন্ডে সেভ হয়ে যাবে।
 * এতে কোনো কাস্টমার অর্ডার সাবমিট না করে চলে গেলেও কল সেন্টারের টিম তাকে কল করতে পারবে।
 */

header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit;
}

$phone   = trim($_POST['customer_phone'] ?? '');
$name    = trim($_POST['customer_name'] ?? '');
$address = trim($_POST['customer_address'] ?? '');
$variant = trim($_POST['product_variant'] ?? 'small');

$cleanPhone = preg_replace('/[^0-9]/', '', $phone);
if (strlen($cleanPhone) < 10) {
    // অসম্পূর্ণ নম্বর হলে সেভ করার প্রয়োজন নেই
    echo json_encode(['status' => 'ignored']);
    exit;
}

$leadDir = __DIR__ . '/../data/leads/';
if (!is_dir($leadDir)) {
    mkdir($leadDir, 0755, true);
}

$leadData = [
    'customer_phone'  => $cleanPhone,
    'customer_name'   => $name,
    'customer_address'=> $address,
    'variant'         => $variant,
    'ip'              => $_SERVER['REMOTE_ADDR'] ?? '',
    'updated_at'      => date('Y-m-d H:i:s')
];

// ফাইলের নাম হবে মোবাইল নম্বর দিয়ে, যাতে একই কাস্টমার একাধিকবার টাইপ করলে ওভাররাইট হয়ে আপডেট থাকে
$filePath = $leadDir . $cleanPhone . '.json';
file_put_contents($filePath, json_encode($leadData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

echo json_encode(['status' => 'autosaved']);
exit;
