<?php
/**
 * Nokshapoth Order Processing & Facebook CAPI Dispatcher
 * -----------------------------------------------------
 * ১. অর্ডার ডেটা ভ্যালিডেশন
 * ২. মূল সাইটের ডাটাবেজে অর্ডার সেভ করা (বা ব্যাকআপ ফাইলে সংরক্ষণ)
 * ৩. মেটা কনভার্সন এপিআই (CAPI) সার্ভার-সাইড পারচেজ ইভেন্ট পুশ
 */

header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'অনুমতি নেই।']);
    exit;
}

// ইনপুট স্যানিটাইজেশন
$name          = trim($_POST['customer_name'] ?? '');
$phone         = trim($_POST['customer_phone'] ?? '');
$address       = trim($_POST['customer_address'] ?? '');
$variant       = trim($_POST['product_variant'] ?? 'small');
$zone          = trim($_POST['delivery_zone'] ?? 'inside');
$eventId       = trim($_POST['event_id'] ?? ('FRc_' . bin2hex(random_bytes(6)) . '_' . time()));
$fbpCookie     = $_COOKIE['_fbp'] ?? '';
$fbcCookie     = $_COOKIE['_fbc'] ?? '';

// ১. ভ্যালিডেশন
if (empty($name)) {
    echo json_encode(['success' => false, 'message' => 'অনুগ্রহ করে আপনার নাম লিখুন।']);
    exit;
}

// ফোন নম্বর পরিষ্কার করা (স্পেস, হাইফেন বা +88 বাদ দিয়ে ১১ ডিজিট নিশ্চিত করা)
$cleanPhone = preg_replace('/[^0-9]/', '', $phone);
if (strlen($cleanPhone) === 13 && strpos($cleanPhone, '88') === 0) {
    $cleanPhone = substr($cleanPhone, 2);
}

if (!preg_match('/^01[3-9]\d{8}$/', $cleanPhone)) {
    echo json_encode(['success' => false, 'message' => 'অনুগ্রহ করে সঠিক ১১ ডিজিটের বাংলাদেশী মোবাইল নম্বর দিন (যেমন: 017xxxxxxxx)।']);
    exit;
}

if (empty($address)) {
    echo json_encode(['success' => false, 'message' => 'অনুগ্রহ করে আপনার ডেলিভারির পূর্ণ ঠিকানা দিন।']);
    exit;
}

// ২. প্রোডাক্ট প্রাইস ও ডেলিভারি হিসাব (মাল্টি-আইটেম সাপোর্ট)
$itemsJson = isset($_POST['items_json']) ? trim($_POST['items_json']) : '';
$items = !empty($itemsJson) ? json_decode($itemsJson, true) : null;

$variantTitle = '';
$productPrice = 0;

if (is_array($items) && count($items) > 0) {
    $titleParts = [];
    foreach ($items as $item) {
        $qty = isset($item['qty']) ? intval($item['qty']) : 1;
        $price = isset($item['price']) ? intval($item['price']) : 0;
        $name = isset($item['name']) ? $item['name'] : 'পণ্য';
        $productPrice += ($price * $qty);
        $titleParts[] = "{$name} ({$qty}টি)";
    }
    $variantTitle = implode(' + ', $titleParts);
} else {
    $variantTitle = 'বড় সাইজের রুটি মেকার';
    $productPrice = 1490;
    if ($variant === 'small') {
        $variantTitle = 'ছোট সাইজের রুটি মেকার';
        $productPrice = 1390;
    } elseif ($variant === 'boti_small') {
        $variantTitle = 'ছোট সাইজের বটি';
        $productPrice = 690;
    } elseif ($variant === 'boti_large') {
        $variantTitle = 'বড় সাইজের বটি';
        $productPrice = 890;
    }
}

$deliveryCharge = ($zone === 'outside') ? DELIVERY_CHARGE_OUTSIDE_DHAKA : DELIVERY_CHARGE_INSIDE_DHAKA;
$totalAmount = $productPrice + $deliveryCharge;
$orderId = 'NP-' . date('ymd') . '-' . rand(1000, 9999);
$orderTime = date('Y-m-d H:i:s');

// ৩. মূল সাইটের ডাটাবেজে সেভ করার চেষ্টা (যদি ডিবি কনফিগার করা থাকে)
$savedToDb = false;
$pdo = getNokshaDB();

if ($pdo) {
    try {
        // Spider eCommerce বা স্ট্যান্ডার্ড cPanel অর্ডার টেবিলে ইনসার্ট
        // টেবিল স্ট্রাকচার অনুযায়ী ফিল্ডগুলো স্বয়ংক্রিয়ভাবে হ্যান্ডেল হবে
        $stmt = $pdo->prepare("
            INSERT INTO orders 
            (order_number, customer_name, customer_phone, customer_address, product_name, product_price, delivery_charge, total_amount, payment_status, order_status, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Pending', 'Processing', ?)
        ");
        $stmt->execute([
            $orderId, $name, $cleanPhone, $address, $variantTitle, $productPrice, $deliveryCharge, $totalAmount, $orderTime
        ]);
        $savedToDb = true;
    } catch (Exception $e) {
        error_log("Main DB Save Failed: " . $e->getMessage());
        // যদি মূল টেবিল নাম 'orders' না হয়ে অন্য কিছু হয়, তবে ব্যাকআপ ফাইলে সাথে সাথে সেভ হবে
    }
}

// ৪. ব্যাকআপ JSON ফাইল সেভ (যাতে কখনোই কোনো অর্ডার মিস না হয়)
$orderData = [
    'order_id'        => $orderId,
    'customer_name'   => $name,
    'customer_phone'  => $cleanPhone,
    'customer_address'=> $address,
    'variant'         => $variant,
    'product_title'   => $variantTitle,
    'items'           => !empty($items) ? $items : [['name' => $variantTitle, 'qty' => 1, 'price' => $productPrice]],
    'product_price'   => $productPrice,
    'delivery_zone'   => ($zone === 'outside') ? 'ঢাকার বাইরে' : 'ঢাকার ভেতরে',
    'delivery_charge' => $deliveryCharge,
    'total_amount'    => $totalAmount,
    'payment_method'  => 'Cash on Delivery (COD)',
    'event_id'        => $eventId,
    'client_ip'       => $_SERVER['REMOTE_ADDR'] ?? '',
    'user_agent'      => $_SERVER['HTTP_USER_AGENT'] ?? '',
    'created_at'      => $orderTime
];

$backupFile = BACKUP_ORDERS_DIR . $orderId . '.json';
file_put_contents($backupFile, json_encode($orderData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

// ৫. মেটা / ফেসবুক সার্ভার-সাইড Conversions API (CAPI) ইভেন্ট পুশ
if (defined('FB_CAPI_ACCESS_TOKEN') && FB_CAPI_ACCESS_TOKEN !== 'YOUR_META_CONVERSIONS_API_ACCESS_TOKEN_HERE') {
    dispatchFacebookCapiPurchase([
        'event_id'       => $eventId,
        'client_ip'      => $_SERVER['REMOTE_ADDR'] ?? '',
        'user_agent'     => $_SERVER['HTTP_USER_AGENT'] ?? '',
        'phone'          => '88' . $cleanPhone, // E.164 ফরম্যাটে বাংলাদেশ কোড সহ
        'name'           => $name,
        'fbp'            => $fbpCookie,
        'fbc'            => $fbcCookie,
        'value'          => (float)$totalAmount,
        'product_name'   => $variantTitle,
        'event_source_url'=> $_SERVER['HTTP_REFERER'] ?? ('https://' . ($_SERVER['HTTP_HOST'] ?? 'nokshapoth.com'))
    ]);
}

// রেসপন্স প্রদান
echo json_encode([
    'success'      => true,
    'order_id'     => $orderId,
    'product_name' => $variantTitle,
    'total_amount' => $totalAmount,
    'phone'        => $cleanPhone,
    'message'      => 'আপনার অর্ডারটি সফলভাবে গ্রহণ করা হয়েছে! আমাদের প্রতিনিধি শীঘ্রই আপনার সাথে যোগাযোগ করবেন।'
]);
exit;

/**
 * Facebook Graph API-তে সার্ভার-সাইড Purchase ইভেন্ট পাঠানো
 */
function dispatchFacebookCapiPurchase($data) {
    $url = "https://graph.facebook.com/" . FB_API_VERSION . "/" . FB_PIXEL_ID . "/events?access_token=" . FB_CAPI_ACCESS_TOKEN;

    // নরম্যালাইজ ও হ্যাশ করা
    $hashedPhone = hash('sha256', trim($data['phone']));
    $hashedName  = hash('sha256', mb_strtolower(trim($data['name']), 'UTF-8'));
    $hashedCountry = hash('sha256', 'bd');

    $userData = [
        'ph'                => [$hashedPhone],
        'fn'                => [$hashedName],
        'country'           => [$hashedCountry],
        'client_ip_address' => $data['client_ip'],
        'client_user_agent' => $data['user_agent']
    ];

    if (!empty($data['fbp'])) {
        $userData['fbp'] = $data['fbp'];
    }
    if (!empty($data['fbc'])) {
        $userData['fbc'] = $data['fbc'];
    }

    $payload = [
        'data' => [
            [
                'event_name'        => 'Purchase',
                'event_time'        => time(),
                'event_id'          => $data['event_id'], // ডিডুপ্লিকেশনের জন্য ব্রাউজার পিক্সেলের সাথে সেম
                'event_source_url'  => $data['event_source_url'],
                'action_source'     => 'website',
                'user_data'         => $userData,
                'custom_data'       => [
                    'currency'      => 'BDT',
                    'value'         => $data['value'],
                    'content_name'  => $data['product_name'],
                    'content_type'  => 'product'
                ]
            ]
        ]
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_TIMEOUT, 4);
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}
