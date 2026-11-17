<?php
/**
 * Nokshapoth Landing Page Configuration
 * -------------------------------------
 * এই ফাইলটিতে আপনার ডাটাবেজ, ফেসবুক পিক্সেল, CAPI এবং নোটিফিকেশন সেটিংস কনফিগার করুন।
 */

// ১. এরর হ্যান্ডলিং ও সিকিউরিটি
error_reporting(0);
ini_set('display_errors', 0);

// ২. মেটা / ফেসবুক পিক্সেল ও CAPI কনফিগারেশন
define('FB_PIXEL_ID', '1012390691376333');
// মেটা বিজনেস ম্যানেজারে গিয়ে Events Manager > Settings > Conversions API > "Generate access token" থেকে টোকেনটি এখানে বসাবেন
define('FB_CAPI_ACCESS_TOKEN', 'YOUR_META_CONVERSIONS_API_ACCESS_TOKEN_HERE');
define('FB_API_VERSION', 'v19.0');

// ৩. মূল সাইটের ডাটাবেজ সংযোগ (cPanel MySQL Database Credentials)
// যদি একই cPanel-এর অধীনে সাবডোমেইন থাকে, তবে লোকালহোস্ট দিয়েই সরাসরি মূল সাইটের DB-তে ইনসার্ট করা যাবে
define('DB_HOST', 'localhost');
define('DB_USER', 'your_cpanel_db_user');
define('DB_PASS', 'your_cpanel_db_password');
define('DB_NAME', 'your_nokshapoth_main_db');

// ৪. স্টোর ও ডেলিভারি তথ্য
define('STORE_NAME', 'নকশাপথ');
define('STORE_PHONE', '01707856728');
define('STORE_WHATSAPP', '8801356012857');
define('DELIVERY_CHARGE_INSIDE_DHAKA', 60);
define('DELIVERY_CHARGE_OUTSIDE_DHAKA', 120);

// ৫. ব্যাকআপ স্টোরেজ ফোল্ডার (যদি ডাটাবেজে সাময়িক কোনো ত্রুটি থাকে, তবুও যেন কোনো অর্ডার মিস না হয়)
define('BACKUP_ORDERS_DIR', __DIR__ . '/data/orders/');
if (!is_dir(BACKUP_ORDERS_DIR)) {
    mkdir(BACKUP_ORDERS_DIR, 0755, true);
}

/**
 * ডাটাবেজ কানেকশন হেল্পার ফাংশন
 */
function getNokshaDB() {
    static $pdo = null;
    if ($pdo === null) {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_TIMEOUT => 5
            ]);
        } catch (PDOException $e) {
            // ডাটাবেজ কানেক্ট না হলে এরর লগ রাখা
            error_log("DB Connection Error: " . $e->getMessage());
            return null;
        }
    }
    return $pdo;
}
