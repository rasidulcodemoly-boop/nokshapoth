<?php
/**
 * Nokshapoth Single Product Landing Page
 * Product: বটি অ্যান্ড রুটি মেকার
 * Design: Wireframe based (AIDA Framework)
 * Colors: Beige & Forest Green theme
 */

require_once __DIR__ . '/config.php';

// মেটা পিক্সেল ও CAPI ডিডুপ্লিকেশনের জন্য ইউনিক ইভেন্ট আইডি জেনারেশন
$pageview_event_id = 'FRc_PV_' . bin2hex(random_bytes(6)) . '_' . time();
$purchase_event_id = 'FRc_PUR_' . bin2hex(random_bytes(6)) . '_' . time();

// ওপেন গ্রাফ (OG) সোশ্যাল শেয়ারিং ডাইনামিক URL
$site_protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)) ? "https://" : "http://";
$site_host = $_SERVER['HTTP_HOST'] ?? 'nokshapoth.com';
$og_canonical_url = $site_protocol . $site_host . ($_SERVER['REQUEST_URI'] ?? '/');
$og_image_url = $site_protocol . $site_host . '/assets/images/og-share-banner.jpg';
?>
<!DOCTYPE html>
<html lang="bn" class="scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>বটি অ্যান্ড রুটি মেকার - নকশাপথ কিচেন এসেনশিয়ালস</title>
  <meta name="description" content="রুটি বানানোর ঝামেলা এবার অর্ধেক সময়ে শেষ! বটি অ্যান্ড রুটি মেকার দিয়ে তৈরি করুন নরম তুলতুলে গোল রুটি। ক্যাশ অন ডেলিভারি সারা দেশে।">

  <!-- Favicon Links (Browser Tab & Mobile Bookmark) -->
  <link rel="icon" type="image/x-icon" href="favicon.ico">
  <link rel="icon" type="image/svg+xml" href="assets/images/nokshapoth-icon.svg">
  <link rel="icon" type="image/png" sizes="32x32" href="assets/images/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon-16x16.png">
  <link rel="apple-touch-icon" sizes="180x180" href="assets/images/apple-touch-icon.png">
  <meta name="theme-color" content="#39681A">

  <!-- Open Graph / Facebook / WhatsApp (Social Link Sharing Preview) -->
  <meta property="og:type" content="website">
  <meta property="og:site_name" content="নকশাপথ | Nokshapoth">
  <meta property="og:url" content="<?php echo htmlspecialchars($og_canonical_url, ENT_QUOTES, 'UTF-8'); ?>">
  <meta property="og:title" content="নকশাপথ - বটি অ্যান্ড রুটি মেকার | প্রিমিয়াম কিচেন কম্বো">
  <meta property="og:description" content="হাতে বেলার ঝামেলা ছাড়াই নরম তুলতুলে গোল রুটি এবং নিরাপদ ও দ্রুত কাটাকাটির আধুনিক কাঠের বটি ও রুটি মেকার। সারা দেশে ক্যাশ অন ডেলিভারি।">
  <meta property="og:image" content="<?php echo htmlspecialchars($og_image_url, ENT_QUOTES, 'UTF-8'); ?>">
  <meta property="og:image:secure_url" content="<?php echo htmlspecialchars($og_image_url, ENT_QUOTES, 'UTF-8'); ?>">
  <meta property="og:image:type" content="image/jpeg">
  <meta property="og:image:width" content="1200">
  <meta property="og:image:height" content="630">
  <meta property="og:image:alt" content="নকশাপথ বটি অ্যান্ড রুটি মেকার কম্বো অফার">

  <!-- Twitter / X Cards -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:url" content="<?php echo htmlspecialchars($og_canonical_url, ENT_QUOTES, 'UTF-8'); ?>">
  <meta name="twitter:title" content="নকশাপথ - বটি অ্যান্ড রুটি মেকার | প্রিমিয়াম কিচেন কম্বো">
  <meta name="twitter:description" content="হাতে বেলার ঝামেলা ছাড়াই নরম তুলতুলে গোল রুটি এবং নিরাপদ ও দ্রুত কাটাকাটির আধুনিক কাঠের বটি ও রুটি মেকার। সারা দেশে ক্যাশ অন ডেলিভারি।">
  <meta name="twitter:image" content="<?php echo htmlspecialchars($og_image_url, ENT_QUOTES, 'UTF-8'); ?>">

  <!-- Google Fonts: Hind Siliguri for Bengali -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet">

  <!-- Tailwind CSS CDN (High performance standalone utility) -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            'beige-light': '#FFF6DF',
            'beige-dark': '#F6DD94',
            'beige-ground': '#FFF5DD',
            'green-cta': '#82BA32',
            'green-card': '#5E8C2F',
            'green-deep': '#39681A',
            'green-badge': '#397002'
          },
          fontFamily: {
            bangla: ['Hind Siliguri', 'sans-serif']
          }
        }
      }
    }
  </script>

  <!-- Custom CSS -->
  <link rel="stylesheet" href="assets/css/custom.css">

  <!-- Meta / Facebook Pixel Code -->
  <script>
    !function(f,b,e,v,n,t,s)
    {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
    n.callMethod.apply(n,arguments):n.queue.push(arguments)};
    if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
    n.queue=[];t=b.createElement(e);t.async=!0;
    t.src=v;s=b.getElementsByTagName(e)[0];
    s.parentNode.insertBefore(t,s)}(window, document,'script',
    'https://connect.facebook.net/en_US/fbevents.js');
    
    fbq('init', '<?php echo FB_PIXEL_ID; ?>');
    // PageView with Event ID for CAPI deduplication
    fbq('track', 'PageView', {}, {eventID: '<?php echo $pageview_event_id; ?>'});
    fbq('track', 'ViewContent', {
      content_name: 'বটি অ্যান্ড রুটি মেকার',
      content_category: 'Kitchen Essentials',
      currency: 'BDT',
      value: 1390
    });
  </script>
  <noscript>
    <img height="1" width="1" style="display:none" 
         src="https://www.facebook.com/tr?id=<?php echo FB_PIXEL_ID; ?>&ev=PageView&noscript=1"/>
  </noscript>
</head>
<body class="bg-beige-ground font-bangla text-gray-800 antialiased">

  <!-- মোবাইল মেনুর ব্যাকড্রপ (ডার্ক অ্যান্ড ব্লার ব্যাকগ্রাউন্ড) -->
  <div id="mobile-menu-backdrop" class="fixed inset-0 z-40 hidden transition-opacity duration-300" style="background-color: rgba(0, 0, 0, 0.75) !important; backdrop-filter: blur(8px) !important; -webkit-backdrop-filter: blur(8px) !important;"></div>

  <!-- ২. স্মার্ট ও আধুনিক স্টিকি নেভবার (Smart Modern Sticky Navbar) -->
  <nav id="main-navbar" class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-stone-200/90 shadow-sm transition-all duration-300">
    
    <!-- স্ক্রোল প্রোগ্রেস বার (Scroll Progress Indicator) -->
    <div class="w-full bg-stone-100 h-[3px]">
      <div id="scroll-progress-bar" class="h-full bg-gradient-to-r from-[#7AB030] to-[#82BA32] w-0 transition-all duration-75"></div>
    </div>

    <div class="max-w-6xl mx-auto px-4 sm:px-6">
      <div class="flex items-center justify-between h-16 md:h-20">
        
        <!-- ব্র্যান্ড লোগো ও পরিচিতি (ভেক্টর আইকন ও টাইপোগ্রাফি) -->
        <a href="#hero" class="flex items-center space-x-2.5 sm:space-x-3 group">
          <div class="w-9 h-9 sm:w-11 sm:h-11 md:w-12 md:h-12 rounded-xl bg-white p-1 border border-stone-200 shadow-sm flex items-center justify-center group-hover:scale-105 transition duration-200 flex-shrink-0">
            <img src="assets/images/nokshapoth-icon.svg" alt="নকশাপথ লোগো" class="w-full h-full object-contain">
          </div>
          <div class="flex flex-col justify-center text-left">
            <div class="text-xl sm:text-2xl md:text-[28px] lg:text-3xl font-black tracking-tight leading-none">
              <span class="text-green-deep">নকশা</span><span class="text-gray-900">পথ</span>
            </div>
            <div class="hidden sm:block text-xs md:text-[13px] text-gray-500 font-medium tracking-wide leading-tight mt-1 whitespace-nowrap">ঘর সাজে, জীবন সাজে</div>
          </div>
        </a>

        <!-- ডেক্সটপ নেভিগেশন লিংকস -->
        <div class="hidden lg:flex items-center space-x-1 text-sm font-semibold text-gray-700">
          <a href="#features" class="px-3.5 py-2 rounded-xl hover:text-green-deep hover:bg-stone-100 transition duration-150">সুবিধাসমূহ</a>
          <a href="#how-to-use" class="px-3.5 py-2 rounded-xl hover:text-green-deep hover:bg-stone-100 transition duration-150">ব্যবহার পদ্ধতি</a>
          <a href="#pricing-variants" class="px-3.5 py-2 rounded-xl hover:text-green-deep hover:bg-stone-100 transition duration-150">প্যাকেজ ও দাম</a>
          
          <a href="#faq-section" class="px-3.5 py-2 rounded-xl hover:text-green-deep hover:bg-stone-100 transition duration-150">প্রশ্নোত্তর</a>
        </div>

        <!-- কুইক কল, হোয়াটসঅ্যাপ ও অ্যাকশন বাটনসমূহ -->
        <div class="flex items-center space-x-2 sm:space-x-3">
          
          <!-- হোয়াটসঅ্যাপ চ্যাট বাটন (ডেক্সটপে ভিজিবল, মোবাইলে ড্রয়ারে থাকবে যাতে নেভবার ক্ল্যাটার না হয়) -->
          <a href="https://wa.me/<?php echo STORE_WHATSAPP; ?>?text=I%20want%20to%20know%20about%20Boti%20and%20Ruti%20Maker" target="_blank" title="WhatsApp Chat" class="hidden sm:flex w-9 h-9 md:w-10 md:h-10 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-600 items-center justify-center border border-emerald-200 transition shadow-sm">
            <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
              <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
            </svg>
          </a>

          <!-- প্রাইমারি হাই-কনভার্সন বাটন (আই-ক্যাচিং শেকিং, হাই-কন্ট্রাস্ট ও ভার্টিক্যাল রোল) -->
          <button onclick="scrollToCheckout()" class="btn-shake-cta text-xs md:text-sm px-3.5 sm:px-5 md:px-6 py-2 sm:py-2.5 rounded-lg sm:rounded-xl group" title="এখনই অর্ডার করুন">
            <span class="btn-roll-container">
              <!-- স্বাভাবিক অবস্থা (হোভার করলে উপরে রোল হয়ে যাবে) -->
              <span class="btn-roll-content btn-roll-original">
                <span>অর্ডার করুন</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 sm:w-4 sm:h-4 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
              </span>
              <!-- রোল ইন অবস্থা (হোভার করলে নিচ থেকে ভেতরে রোল হবে) -->
              <span class="btn-roll-content btn-roll-duplicate" aria-hidden="true">
                <span>অর্ডার করুন</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 sm:w-4 sm:h-4 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
              </span>
            </span>
          </button>

          <!-- মোবাইল মেনু টগল বাটন -->
          <button id="mobile-menu-btn" class="lg:hidden p-2 rounded-xl text-gray-700 hover:bg-stone-100 transition focus:outline-none" aria-label="Toggle Menu">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
            </svg>
          </button>

        </div>

      </div>

      <!-- মোবাইল মেনু ড্রপডাউন (ডার্ক অ্যান্ড ব্লার লাক্সারি গ্লাস ড্রয়ার) -->
      <div id="mobile-menu-dropdown" class="lg:hidden hidden mt-2 mb-3 rounded-2xl p-4 shadow-[0_25px_50px_-12px_rgba(0,0,0,0.7)] text-white space-y-3 transition-all duration-300" style="background-color: rgba(18, 32, 11, 0.98) !important; border: 1px solid rgba(255, 255, 255, 0.16) !important; color: #ffffff !important;">
        
        <!-- মেনু হেডার -->
        <div class="flex items-center justify-between pb-3 border-b border-white/10">
          <div class="flex items-center space-x-2.5">
            <div class="w-8 h-8 rounded-xl bg-white/10 flex items-center justify-center p-1 border border-white/10">
              <img src="assets/images/nokshapoth-icon.svg" alt="নকশাপথ" class="w-full h-full object-contain">
            </div>
            <div>
              <div class="text-sm font-extrabold text-white tracking-wide">নকশাপথ মেনু</div>
              <div class="text-[11px] text-lime-400 font-medium">ঘর সাজে, জীবন সাজে</div>
            </div>
          </div>
          <button id="mobile-menu-close-btn" class="p-1.5 rounded-xl text-stone-400 hover:text-white hover:bg-white/10 transition" aria-label="Close menu">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
          </button>
        </div>

        <!-- নেভিগেশন লিংকস -->
        <div class="space-y-1">
          <a href="#features" class="flex items-center justify-between p-2.5 rounded-xl hover:bg-white/10 active:bg-white/15 transition group">
            <div class="flex items-center space-x-3">
              <span class="w-8 h-8 rounded-lg bg-lime-400/10 text-lime-400 border border-lime-400/20 flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition">
                <svg class="w-4 h-4 text-lime-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
              </span>
              <span class="text-sm font-semibold text-stone-100 group-hover:text-lime-300 transition">সুবিধাসমূহ</span>
            </div>
            <svg class="w-4 h-4 text-stone-500 group-hover:text-lime-400 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
          </a>

          <a href="#how-to-use" class="flex items-center justify-between p-2.5 rounded-xl hover:bg-white/10 active:bg-white/15 transition group">
            <div class="flex items-center space-x-3">
              <span class="w-8 h-8 rounded-lg bg-lime-400/10 text-lime-400 border border-lime-400/20 flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition">
                <svg class="w-4 h-4 text-lime-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
              </span>
              <span class="text-sm font-semibold text-stone-100 group-hover:text-lime-300 transition">ব্যবহারের নিয়ম</span>
            </div>
            <svg class="w-4 h-4 text-stone-500 group-hover:text-lime-400 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
          </a>

          <a href="#pricing-variants" class="flex items-center justify-between p-2.5 rounded-xl hover:bg-white/10 active:bg-white/15 transition group">
            <div class="flex items-center space-x-3">
              <span class="w-8 h-8 rounded-lg bg-lime-400/10 text-lime-400 border border-lime-400/20 flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition">
                <svg class="w-4 h-4 text-lime-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
              </span>
              <span class="text-sm font-semibold text-stone-100 group-hover:text-lime-300 transition">সাইজ ও প্যাকেজের দাম</span>
            </div>
            <svg class="w-4 h-4 text-stone-500 group-hover:text-lime-400 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
          </a>



          <a href="#faq-section" class="flex items-center justify-between p-2.5 rounded-xl hover:bg-white/10 active:bg-white/15 transition group">
            <div class="flex items-center space-x-3">
              <span class="w-8 h-8 rounded-lg bg-lime-400/10 text-lime-400 border border-lime-400/20 flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition">
                <svg class="w-4 h-4 text-lime-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
              </span>
              <span class="text-sm font-semibold text-stone-100 group-hover:text-lime-300 transition">প্রশ্নোত্তর (FAQ)</span>
            </div>
            <svg class="w-4 h-4 text-stone-500 group-hover:text-lime-400 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
          </a>
        </div>

        <!-- ড্রয়ারে কুইক কন্টাক্ট ও অ্যাকশন বাটনসমূহ -->
        <div class="pt-3 border-t border-white/10 space-y-2">
          <a href="https://wa.me/<?php echo STORE_WHATSAPP; ?>?text=I%20want%20to%20know%20about%20Boti%20and%20Ruti%20Maker" target="_blank" class="w-full flex items-center justify-center space-x-2 py-3 px-4 rounded-xl bg-gradient-to-r from-emerald-600 to-green-600 hover:from-emerald-500 hover:to-green-500 text-white font-bold text-sm shadow-lg shadow-emerald-950/50 transition">
            <svg class="w-5 h-5 fill-current text-white flex-shrink-0" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
            <span>হোয়াটসঅ্যাপে সরাসরি মেসেজ দিন</span>
          </a>

          <a href="tel:<?php echo STORE_PHONE; ?>" class="w-full flex items-center justify-center space-x-2 py-2.5 px-4 rounded-xl bg-white/10 hover:bg-white/15 text-stone-200 font-semibold text-xs border border-white/10 transition">
            <svg class="w-4 h-4 fill-current text-lime-400" viewBox="0 0 20 20"><path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 4V3z"/></svg>
            <span>হটলাইনে কল করুন: <?php echo STORE_PHONE; ?></span>
          </a>
        </div>

      </div>

    </div>
  </nav>

  <!-- ৩. হিরো সেকশন (গুগল ডক রেফারেন্স ইমেজ ব্যাকগ্রাউন্ড) -->
  <header id="hero" class="relative overflow-hidden bg-cover bg-[position:72%_center] md:bg-right py-14 sm:py-20 md:py-28 min-h-[520px] md:min-h-[580px] flex items-center" style="background-image: url('assets/images/hero-bg.jpg');">
    <!-- সফিস্টিকেটেড গ্রেডিয়েন্ট ওভারলে: টেক্সট স্পষ্ট পড়ার জন্য মোবাইলে এবং ডেসকটপে সফট শ্যাডো, ডানে গৃহিণীর মাছ কাটার আসল ছবিকে সম্পূর্ণ স্বচ্ছ ও স্পষ্ট রাখা -->
    <div class="absolute inset-0 bg-gradient-to-r from-black/90 via-black/75 to-black/35 md:from-black/85 md:via-black/45 md:to-transparent pointer-events-none"></div>

    <div class="relative z-10 max-w-6xl mx-auto px-4 sm:px-6 w-full">
      <!-- বাম পাশের টেক্সট কন্টেন্ট ও বাটনসমূহ -->
      <div class="max-w-xl lg:max-w-2xl space-y-5 text-white">
        <div class="inline-flex items-center space-x-2 bg-black/40 backdrop-blur-md border border-white/20 rounded-full px-4 py-1 text-xs md:text-sm text-green-300">
          <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>
          <span>নকশাপথ | কিচেন এসেনশিয়ালস</span>
        </div>

        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black leading-tight tracking-tight drop-shadow-lg">
          রুটি বানানোর ঝামেলা <br class="hidden sm:inline">
          <span class="text-[#bef264]">এবার অর্ধেক সময়ে শেষ!</span>
        </h1>

        <p class="text-stone-100 text-base sm:text-lg md:text-xl leading-relaxed font-normal drop-shadow">
          বটি অ্যান্ড রুটি মেকার দিয়ে হাতে বেলার কষ্ট ছাড়াই তৈরি করুন সমান, গোল আর নরম রুটি। সকালের নাস্তা নিয়ে আর তাড়াহুড়ো বা ক্লান্তি নয়।
        </p>

        <!-- কল-টু-অ্যাকশন বাটনসমূহ -->
        <div class="pt-2 grid grid-cols-2 gap-3 sm:gap-4 max-w-sm sm:max-w-md">
          <button onclick="scrollToCheckout()" class="pulse-cta bg-green-cta hover:bg-[#72a728] text-white font-bold text-sm sm:text-base md:text-lg py-3.5 px-3 sm:px-6 rounded-xl shadow-xl transition-all duration-200 flex items-center justify-center group w-full text-center" title="অর্ডার করুন">
            <span class="btn-roll-container">
              <span class="btn-roll-content btn-roll-original">
                <span>অর্ডার করুন</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
              </span>
              <span class="btn-roll-content btn-roll-duplicate" aria-hidden="true">
                <span>অর্ডার করুন</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
              </span>
            </span>
          </button>

          <a href="https://wa.me/<?php echo STORE_WHATSAPP; ?>?text=I%20want%20to%20order%20Boti%20and%20Ruti%20Maker" target="_blank" class="bg-black/40 hover:bg-black/60 text-white font-bold text-sm sm:text-base md:text-lg py-3.5 px-3 sm:px-6 rounded-xl border border-white/20 backdrop-blur-md transition-all duration-200 flex items-center justify-center space-x-2 group w-full text-center shadow-lg" title="WhatsApp মেসেজ দিন">
            <span class="btn-roll-container">
              <span class="btn-roll-content btn-roll-original">
                <svg class="w-5 h-5 fill-current text-emerald-400 flex-shrink-0" viewBox="0 0 24 24">
                  <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                </svg>
                <span>মেসেজ দিন</span>
              </span>
              <span class="btn-roll-content btn-roll-duplicate" aria-hidden="true">
                <svg class="w-5 h-5 fill-current text-emerald-400 flex-shrink-0" viewBox="0 0 24 24">
                  <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                </svg>
                <span>মেসেজ দিন</span>
              </span>
            </span>
          </a>
        </div>

      </div>
    </div>
  </header>

  <!-- ৪. লাইফস্টাইল ও কম্প্যারিজন স্লাইডার সেকশন (মিনিমাল ৩টি স্লাইড) -->
  <section id="problem-section" class="py-12 md:py-16 bg-beige-ground overflow-hidden">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
      
      <!-- সেকশন হেডার ও মিনিমাল স্লাইডার ট্যাব কন্ট্রোলস -->
      <div class="flex flex-col sm:flex-row items-center justify-between mb-6 sm:mb-8 gap-4">
        <div>
          <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900 text-center sm:text-left">
            নকশাপথ কীভাবে জীবনকে সহজ করে?
          </h2>
        </div>

        <!-- স্লাইড নেভিগেশন কন্ট্রোলস (ট্যাবস + অ্যারো বাটন) -->
        <div class="flex items-center space-x-2">
          <!-- ৩টি স্লাইড ট্যাব পিল বাটন -->
          <div class="inline-flex p-1 bg-stone-200/80 rounded-xl space-x-1" role="tablist">
            <button onclick="setComparisonSlide(0)" id="slide-tab-0" class="slide-nav-tab px-3 sm:px-4 py-1.5 rounded-lg text-xs sm:text-sm font-bold transition-all duration-200 bg-white text-gray-900 shadow-sm border border-stone-200 whitespace-nowrap" role="tab">
              ১. নকশাপথ লাইফ
            </button>
            <button onclick="setComparisonSlide(1)" id="slide-tab-1" class="slide-nav-tab px-3 sm:px-4 py-1.5 rounded-lg text-xs sm:text-sm font-bold transition-all duration-200 text-gray-600 hover:text-gray-900 whitespace-nowrap" role="tab">
              ২. বটি
            </button>
            <button onclick="setComparisonSlide(2)" id="slide-tab-2" class="slide-nav-tab px-3 sm:px-4 py-1.5 rounded-lg text-xs sm:text-sm font-bold transition-all duration-200 text-gray-600 hover:text-gray-900 whitespace-nowrap" role="tab">
              ৩. রুটি মেকার
            </button>
          </div>


        </div>
      </div>

            <!-- স্লাইডার কন্টেইনার (৩টি সক্রিয় স্লাইড ভিউ) -->
      <div id="comparison-slider-viewport" class="relative rounded-3xl bg-white border border-stone-200 shadow-xl overflow-hidden">
        
        <!-- ==================== স্লাইড ১: নকশাপথ লাইফ ==================== -->
        <div id="comparison-slide-0" class="comparison-slide p-6 sm:p-8 md:p-10 transition-opacity duration-300 block">
          <div class="grid grid-cols-1 md:grid-cols-12 gap-6 md:gap-8 items-center">
            
            <!-- বাম পাশের মিনিমাল টেক্সট -->
            <div class="md:col-span-6 space-y-4 text-left">
              <span class="inline-block px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold">
                ✨ সহজ জীবন, আরামদায়ক রান্নাঘর
              </span>

              <h3 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-gray-900 leading-snug">
                নকশাপথ দিয়ে রান্নাঘরের প্রতিটি মুহূর্ত হোক <span class="text-green-deep">সহজ ও আনন্দময়</span>
              </h3>

              <p class="text-gray-600 text-sm sm:text-base leading-relaxed">
                সকালের কাটাকাটি আর রুটি বেলার দীর্ঘ খাটুনি শেষ হবে মাত্র কয়েক মিনিটেই। নকশাপথ আপনার রান্নাঘরকে করে তুলবে আধুনিক, নিরাপদ ও দ্রুততম।
              </p>

              <div class="pt-2">
                <button onclick="scrollToCheckout()" class="btn-navbar-cta px-6 py-3 rounded-xl text-white font-bold text-sm shadow-md inline-flex items-center space-x-2">
                  <span>প্যাকেজ ও দাম দেখুন</span>
                  <span>→</span>
                </button>
              </div>
            </div>

            <!-- ডান পাশের লাইফস্টাইল ফটো -->
            <div class="md:col-span-6">
              <div class="relative rounded-2xl overflow-hidden aspect-[16/11] shadow-lg border border-stone-200 group">
                <img src="assets/images/how-to-use-lifestyle.jpg" alt="নকশাপথ দিয়ে সহজ জীবন" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
              </div>
            </div>

          </div>
        </div>

        <!-- ==================== স্লাইড ২: বটি (২টি ইমেজ কম্প্যারিজন) ==================== -->
        <div id="comparison-slide-1" class="comparison-slide p-6 sm:p-8 md:p-10 transition-opacity duration-300 hidden">
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 sm:gap-6">
            
            <!-- ইমেজ (ক): বটি ছাড়া কাটাকাটির ঝামেলা -->
            <div class="group relative rounded-2xl overflow-hidden border border-red-200 bg-stone-900 shadow-md">
              <div class="aspect-[16/11] overflow-hidden">
                <img src="assets/images/problem-time-consuming.jpg" alt="বটি ছাড়া কাটাকাটির ঝামেলা" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 opacity-90">
              </div>
              <!-- টপ ব্যাজ -->
              <div class="absolute top-3.5 left-3.5">
                <span class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full bg-red-600 text-white font-bold text-xs shadow-md">
                  <span>✕</span>
                  <span>বটি ছাড়া কাটাকাটির ঝামেলা</span>
                </span>
              </div>
              <!-- বটম মিনিমাল ক্যাপশন -->
              <div class="absolute bottom-0 inset-x-0 p-4 bg-gradient-to-t from-black/85 via-black/50 to-transparent text-left">
                <p class="text-white text-xs sm:text-sm font-semibold drop-shadow">
                  মাটিতে বসে দীর্ঘ সময় নষ্ট ও পিঠের ব্যথা
                </p>
              </div>
            </div>

            <!-- ইমেজ (খ): নকশাপথের বটি ব্যবহারে সহজ জীবন -->
            <div class="group relative rounded-2xl overflow-hidden border border-emerald-300 bg-stone-900 shadow-md">
              <div class="aspect-[16/11] overflow-hidden">
                <img src="assets/images/boti-large.jpg" alt="নকশাপথের বটি ব্যবহারে সহজ জীবন" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 opacity-90">
              </div>
              <!-- টপ ব্যাজ -->
              <div class="absolute top-3.5 left-3.5">
                <span class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full bg-emerald-600 text-white font-bold text-xs shadow-md">
                  <span>✓</span>
                  <span>নকশাপথের বটি ব্যবহারে সহজ জীবন</span>
                </span>
              </div>
              <!-- বটম মিনিমাল ক্যাপশন -->
              <div class="absolute bottom-0 inset-x-0 p-4 bg-gradient-to-t from-black/85 via-black/50 to-transparent text-left">
                <p class="text-white text-xs sm:text-sm font-semibold drop-shadow">
                  টেবিলে বা মেঝেতে দ্রুত, নিখুঁত ও নিরাপদ কাটাকাটি
                </p>
              </div>
            </div>

          </div>
        </div>

        <!-- ==================== স্লাইড ৩: রুটি মেকার (২টি ইমেজ কম্প্যারিজন) ==================== -->
        <div id="comparison-slide-2" class="comparison-slide p-6 sm:p-8 md:p-10 transition-opacity duration-300 hidden">
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 sm:gap-6">
            
            <!-- ইমেজ (ক): রুটি মেকার ছাড়া অস্বাস্থ্যকর তাড়াহুড়ো -->
            <div class="group relative rounded-2xl overflow-hidden border border-red-200 bg-stone-900 shadow-md">
              <div class="aspect-[16/11] overflow-hidden">
                <img src="assets/images/problem-uneven-roti.jpg" alt="রুটি মেকার ছাড়া বেলার ক্লান্তি ও ঝামেলা" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 opacity-90">
              </div>
              <!-- টপ ব্যাজ -->
              <div class="absolute top-3.5 left-3.5">
                <span class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full bg-red-600 text-white font-bold text-xs shadow-md">
                  <span>✕</span>
                  <span>রুটি মেকার ছাড়া বেলার ক্লান্তি</span>
                </span>
              </div>
              <!-- বটম মিনিমাল ক্যাপশন -->
              <div class="absolute bottom-0 inset-x-0 p-4 bg-gradient-to-t from-black/85 via-black/50 to-transparent text-left">
                <p class="text-white text-xs sm:text-sm font-semibold drop-shadow">
                  ঘণ্টার পর ঘণ্টা হাতে বেলা ও সকালের অস্বাস্থ্যকর তাড়াহুড়ো
                </p>
              </div>
            </div>

            <!-- ইমেজ (খ): নকশাপথ রুটি মেকারে সহজ জীবন -->
            <div class="group relative rounded-2xl overflow-hidden border border-emerald-300 bg-stone-900 shadow-md">
              <div class="aspect-[16/11] overflow-hidden">
                <img src="assets/images/roti-maker-large.jpg" alt="নকশাপথের রুটি মেকার ব্যবহারে সহজ জীবন" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 opacity-90">
              </div>
              <!-- টপ ব্যাজ -->
              <div class="absolute top-3.5 left-3.5">
                <span class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full bg-emerald-600 text-white font-bold text-xs shadow-md">
                  <span>✓</span>
                  <span>নকশাপথ রুটি মেকারে সহজ জীবন</span>
                </span>
              </div>
              <!-- বটম মিনিমাল ক্যাপশন -->
              <div class="absolute bottom-0 inset-x-0 p-4 bg-gradient-to-t from-black/85 via-black/50 to-transparent text-left">
                <p class="text-white text-xs sm:text-sm font-semibold drop-shadow">
                  হ্যান্ডেল চাপলেই ১ মিনিটে পারফেক্ট গোল ও নরম রুটি
                </p>
              </div>
            </div>

          </div>
        </div>

        <!-- মোবাইল সোয়াইপ / নেভিগেশন ডটস -->
        <div class="flex sm:hidden items-center justify-center space-x-2 py-3 bg-stone-50 border-t border-stone-100">
          <button onclick="setComparisonSlide(0)" id="mobile-dot-0" class="w-3 h-3 rounded-full transition-all duration-300 bg-green-deep scale-125" aria-label="Slide 1"></button>
          <button onclick="setComparisonSlide(1)" id="mobile-dot-1" class="w-2.5 h-2.5 rounded-full transition-all duration-300 bg-stone-300" aria-label="Slide 2"></button>
          <button onclick="setComparisonSlide(2)" id="mobile-dot-2" class="w-2.5 h-2.5 rounded-full transition-all duration-300 bg-stone-300" aria-label="Slide 3"></button>
        </div>
      </div>

    </div>
  </section>

  <!-- ৫. ভ্যারিয়েন্ট সিলেকশন সেকশন ("আপনার পরিবারের জন্য কোনটা উপযুক্ত?") -->
  <section id="pricing-variants" class="py-12 md:py-16 bg-green-deep text-white border-y border-white/10 relative overflow-hidden">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
      <div class="text-center space-y-2 mb-10">
        <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-white">
          আপনার পরিবারের জন্য কোনটা উপযুক্ত?
        </h2>
        <p class="text-green-100 text-sm md:text-base max-w-2xl mx-auto">
          আপনার প্রয়োজন অনুযায়ী সঠিক সাইজের বটি ও রুটি মেকার বেছে নিন।
        </p>
      </div>

      <!-- ৪টি মিনিমাল প্রোডাক্ট কার্ড -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 sm:gap-6">
        
        <!-- কার্ড ১: ছোট সাইজের বটি -->
        <div class="bg-white rounded-2xl p-4 sm:p-5 flex flex-col justify-between shadow-xl hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 relative text-gray-800 border border-white/20 group">
          <div>
            <!-- প্রোডাক্ট ছবি -->
            <div class="relative rounded-xl overflow-hidden aspect-[4/3] bg-stone-100 mb-4 border border-stone-100">
              <img src="assets/images/boti-small.jpg" alt="ছোট সাইজের বটি" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy">
              <span class="absolute top-2.5 right-2.5 px-2.5 py-0.5 rounded-md text-[11px] font-bold bg-white/95 backdrop-blur-sm text-stone-700 shadow-sm border border-black/5">
                স্মল বটি
              </span>
            </div>

            <h3 class="text-base sm:text-lg font-bold text-gray-900 leading-snug">ছোট সাইজের বটি</h3>
            <p class="text-xs text-gray-500 mt-1.5 leading-relaxed">
              শাকসবজি, ফলমূল ও সালাদ দ্রুত ও নিখুঁতভাবে কাটার জন্য হালকা ও ধারালো বটি।
            </p>
          </div>

          <div class="mt-4 pt-3 border-t border-gray-100">
            <div class="flex items-baseline justify-between mb-3">
              <div class="flex items-baseline space-x-1.5">
                <span class="text-lg sm:text-xl font-extrabold text-green-deep">৳৬৯০</span>
                <span class="text-xs line-through text-gray-400">৳৯৫০</span>
              </div>
              <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 px-1.5 py-0.5 rounded">সেভ ৳২৬০</span>
            </div>
            <button data-variant="boti_small" class="btn-select-variant btn-navbar-cta w-full py-2.5 px-3 rounded-xl text-white font-bold text-xs sm:text-sm flex items-center justify-center space-x-1 group/b">
              <span>অর্ডার করুন</span>
              <span class="group-hover/b:translate-x-0.5 transition-transform">→</span>
            </button>
          </div>
        </div>

        <!-- কার্ড ২: বড় সাইজের বটি -->
        <div class="bg-white rounded-2xl p-4 sm:p-5 flex flex-col justify-between shadow-xl hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 relative text-gray-800 border border-white/20 group">
          <div>
            <!-- প্রোডাক্ট ছবি -->
            <div class="relative rounded-xl overflow-hidden aspect-[4/3] bg-stone-100 mb-4 border border-stone-100">
              <img src="assets/images/boti-large.jpg" alt="বড় সাইজের বটি" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy">
              <span class="absolute top-2.5 right-2.5 px-2.5 py-0.5 rounded-md text-[11px] font-bold bg-white/95 backdrop-blur-sm text-stone-700 shadow-sm border border-black/5">
                বিগ বটি
              </span>
            </div>

            <h3 class="text-base sm:text-lg font-bold text-gray-900 leading-snug">বড় সাইজের বটি</h3>
            <p class="text-xs text-gray-500 mt-1.5 leading-relaxed">
              মাছ, মাংস, মিষ্টি কুমড়া ও শক্ত তরকারি সহজে কাটার মজবুত ও ধারালো বটি।
            </p>
          </div>

          <div class="mt-4 pt-3 border-t border-gray-100">
            <div class="flex items-baseline justify-between mb-3">
              <div class="flex items-baseline space-x-1.5">
                <span class="text-lg sm:text-xl font-extrabold text-green-deep">৳৮৯০</span>
                <span class="text-xs line-through text-gray-400">৳১,২৫০</span>
              </div>
              <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 px-1.5 py-0.5 rounded">সেভ ৳৩৬০</span>
            </div>
            <button data-variant="boti_large" class="btn-select-variant btn-navbar-cta w-full py-2.5 px-3 rounded-xl text-white font-bold text-xs sm:text-sm flex items-center justify-center space-x-1 group/b">
              <span>অর্ডার করুন</span>
              <span class="group-hover/b:translate-x-0.5 transition-transform">→</span>
            </button>
          </div>
        </div>

        <!-- কার্ড ৩: ছোট সাইজের রুটি মেকার -->
        <div class="bg-white rounded-2xl p-4 sm:p-5 flex flex-col justify-between shadow-xl hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 relative text-gray-800 border border-white/20 group">
          <div>
            <!-- প্রোডাক্ট ছবি -->
            <div class="relative rounded-xl overflow-hidden aspect-[4/3] bg-stone-100 mb-4 border border-stone-100">
              <img src="assets/images/roti-maker-small.jpg" alt="ছোট সাইজের রুটি মেকার" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy">
              <span class="absolute top-2.5 right-2.5 px-2.5 py-0.5 rounded-md text-[11px] font-bold bg-white/95 backdrop-blur-sm text-stone-700 shadow-sm border border-black/5">
                স্মল মেকার
              </span>
            </div>

            <h3 class="text-base sm:text-lg font-bold text-gray-900 leading-snug">ছোট সাইজের রুটি মেকার</h3>
            <p class="text-xs text-gray-500 mt-1.5 leading-relaxed">
              কম্প্যাক্ট ও লাইটওয়েট কাঠের মেকার, ক্যাবিনেটে সহজে রাখা ও ধোয়া যায়।
            </p>
          </div>

          <div class="mt-4 pt-3 border-t border-gray-100">
            <div class="flex items-baseline justify-between mb-3">
              <div class="flex items-baseline space-x-1.5">
                <span class="text-lg sm:text-xl font-extrabold text-green-deep">৳১,৩৯০</span>
                <span class="text-xs line-through text-gray-400">৳১,৮৫০</span>
              </div>
              <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 px-1.5 py-0.5 rounded">সেভ ৳৪৬০</span>
            </div>
            <button data-variant="small" class="btn-select-variant btn-navbar-cta w-full py-2.5 px-3 rounded-xl text-white font-bold text-xs sm:text-sm flex items-center justify-center space-x-1 group/b">
              <span>অর্ডার করুন</span>
              <span class="group-hover/b:translate-x-0.5 transition-transform">→</span>
            </button>
          </div>
        </div>

        <!-- কার্ড ৪: বড় সাইজের রুটি মেকার -->
        <div class="bg-white rounded-2xl p-4 sm:p-5 flex flex-col justify-between shadow-xl hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 relative text-gray-800 border-2 border-green-cta group">
          <!-- বেস্ট সেলার ব্যাজ -->
          <div class="absolute -top-3 right-4 bg-green-badge text-white text-[11px] font-bold px-2.5 py-0.5 rounded-full shadow-md tracking-wide flex items-center space-x-1 z-10">
            <span>বেস্ট সেলার ★</span>
          </div>

          <div>
            <!-- প্রোডাক্ট ছবি -->
            <div class="relative rounded-xl overflow-hidden aspect-[4/3] bg-stone-100 mb-4 border border-stone-100">
              <img src="assets/images/roti-maker-large.jpg" alt="বড় সাইজের রুটি মেকার" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy">
              <span class="absolute top-2.5 right-2.5 px-2.5 py-0.5 rounded-md text-[11px] font-bold bg-amber-500 text-white shadow-sm">
                বিগ মেকার
              </span>
            </div>

            <h3 class="text-base sm:text-lg font-bold text-gray-900 leading-snug">বড় সাইজের রুটি মেকার</h3>
            <p class="text-xs text-gray-500 mt-1.5 leading-relaxed">
              হেভি-ডিউটি ফ্রেম ও বড় সাইজ প্লেট, বড় পরোটা ও রুটি সহজে বানায়।
            </p>
          </div>

          <div class="mt-4 pt-3 border-t border-gray-100">
            <div class="flex items-baseline justify-between mb-3">
              <div class="flex items-baseline space-x-1.5">
                <span class="text-lg sm:text-xl font-extrabold text-green-deep">৳১,৪৯০</span>
                <span class="text-xs line-through text-gray-400">৳১,৯৫০</span>
              </div>
              <span class="text-[10px] font-bold text-amber-800 bg-amber-100 px-1.5 py-0.5 rounded">মাত্র ১০০ বেশি</span>
            </div>
            <button data-variant="big" class="btn-select-variant btn-navbar-cta w-full py-2.5 px-3 rounded-xl text-white font-bold text-xs sm:text-sm flex items-center justify-center space-x-1 group/b">
              <span>অর্ডার করুন</span>
              <span class="group-hover/b:translate-x-0.5 transition-transform">→</span>
            </button>
          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- ৬. কেন নকশাপথের বটি অ্যান্ড রুটি মেকার (৬টি গ্রিড বেনিফিট কার্ড) -->
  <section id="features" class="py-12 md:py-16 bg-beige-ground">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
      <div class="text-center space-y-2 mb-10">
        <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900">
          কেন নকশাপথের বটি অ্যান্ড রুটি মেকার ব্যবহার করবেন?
        </h2>
        <p class="text-gray-600 text-sm md:text-base">
          প্রতিটি সুবিধার কথা চিন্তা করে এটি আধুনিক গৃহিণীদের জন্য প্রস্তুত করা হয়েছে।
        </p>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        
        <!-- ১ -->
        <div class="bg-white rounded-2xl p-6 border border-stone-200/80 shadow-sm hover:border-green-300 transition">
          <div class="w-10 h-10 rounded-lg gradient-icon text-white flex items-center justify-center font-bold mb-4 shadow-sm">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
            </svg>
          </div>
          <h3 class="font-bold text-gray-900 text-base mb-1">সময় বাঁচে</h3>
          <p class="text-xs text-gray-600 leading-relaxed">
            হাতে বেলার কষ্ট ছাড়াই কয়েক মিনিটেই তৈরি হয় পরিবারের সবার নাস্তা।
          </p>
        </div>

        <!-- ২ -->
        <div class="bg-white rounded-2xl p-6 border border-stone-200/80 shadow-sm hover:border-green-300 transition">
          <div class="w-10 h-10 rounded-lg gradient-icon text-white flex items-center justify-center font-bold mb-4 shadow-sm">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <h3 class="font-bold text-gray-900 text-base mb-1">প্রতিবার সমান রুটি</h3>
          <p class="text-xs text-gray-600 leading-relaxed">
            প্রেসার প্লেট নিখুঁতভাবে রুটিকে সমান গোল ও মোলায়েম শেপ প্রদান করে।
          </p>
        </div>

        <!-- ৩ -->
        <div class="bg-white rounded-2xl p-6 border border-stone-200/80 shadow-sm hover:border-green-300 transition">
          <div class="w-10 h-10 rounded-lg gradient-icon text-white flex items-center justify-center font-bold mb-4 shadow-sm">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
            </svg>
          </div>
          <h3 class="font-bold text-gray-900 text-base mb-1">হাত আর রান্নাঘর পরিষ্কার</h3>
          <p class="text-xs text-gray-600 leading-relaxed">
            আটা ছিটকে মেঝ বা কিচেন স্ল্যাব নোংরা হওয়ার কোনো ঝামেলা নেই।
          </p>
        </div>

        <!-- ৪ -->
        <div class="bg-white rounded-2xl p-6 border border-stone-200/80 shadow-sm hover:border-green-300 transition">
          <div class="w-10 h-10 rounded-lg gradient-icon text-white flex items-center justify-center font-bold mb-4 shadow-sm">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
            </svg>
          </div>
          <h3 class="font-bold text-gray-900 text-base mb-1">মজবুত ও টেকসই বডি</h3>
          <p class="text-xs text-gray-600 leading-relaxed">
            উন্নত মেটাল এবং টেকসই কোটিং দিয়ে তৈরি, বছরের পর বছর নিশ্চিন্তে ব্যবহার করুন।
          </p>
        </div>

        <!-- ৫ -->
        <div class="bg-white rounded-2xl p-6 border border-stone-200/80 shadow-sm hover:border-green-300 transition">
          <div class="w-10 h-10 rounded-lg gradient-icon text-white flex items-center justify-center font-bold mb-4 shadow-sm">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
          </div>
          <h3 class="font-bold text-gray-900 text-base mb-1">রুটি, পরোটা, লুচি</h3>
          <p class="text-xs text-gray-600 leading-relaxed">
            শুধু রুটি নয়, সকালের পরোটা বা সন্ধ্যার নাস্তায় লুচি তৈরির জন্য সেরা পছন্দ।
          </p>
        </div>

        <!-- ৬ -->
        <div class="bg-white rounded-2xl p-6 border border-stone-200/80 shadow-sm hover:border-green-300 transition">
          <div class="w-10 h-10 rounded-lg gradient-icon text-white flex items-center justify-center font-bold mb-4 shadow-sm">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
            </svg>
          </div>
          <h3 class="font-bold text-gray-900 text-base mb-1">ঘরে পৌঁছে যাবে</h3>
          <p class="text-xs text-gray-600 leading-relaxed">
            সারা দেশে দ্রুত হোম ডেলিভারি। পণ্য হাতে পেয়ে দেখে তারপর মূল্য পরিশোধ করুন।
          </p>
        </div>

      </div>
    </div>
  </section>

  <!-- ৭. ব্যবহার করা একদম সহজ, তিন ধাপে (রেফারেন্স ইমেজের মতো মিনিমাল ও মডার্ন লেআউট) -->
  <section id="how-to-use" class="py-16 md:py-24 bg-white text-gray-900 border-y border-stone-200/80">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
      
      <!-- সেকশন হেডিং (রেফারেন্স স্টাইলে মিনিমাল, বোল্ড ও মডার্ন) -->
      <div class="mb-12 md:mb-16">
        <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-gray-900 tracking-tight leading-tight">
          ব্যবহার করা একদম সহজ, তিন ধাপে
        </h2>
        <p class="text-gray-500 text-base sm:text-lg mt-3 max-w-2xl leading-relaxed">
          প্রথমবার ব্যবহার করলেই আপনি বুঝতে পারবেন এটি চালানো কতটা স্বাচ্ছন্দ্যময় ও ঝামেলাহীন।
        </p>
      </div>

      <!-- টু-কলাম গ্রিড: বামে ৩টি স্টেপ, ডানে বড় ইমেজ -->
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-14 items-center">
        
        <!-- বাম সাইড: ৩টি স্টেপ লিস্ট -->
        <div class="lg:col-span-7 space-y-10 md:space-y-12">
          
          <!-- স্টেপ ০১ -->
          <div class="how-to-use-step group" data-step="1">
            <div class="step-icon-box">
              <!-- আটার গোল বল ও প্লেটের প্রাসঙ্গিক আইকন -->
              <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <ellipse cx="12" cy="18" rx="8" ry="3"></ellipse>
                <circle cx="12" cy="10" r="5" fill="currentColor" fill-opacity="0.35"></circle>
              </svg>
            </div>
            <h3 class="step-title text-xl sm:text-2xl font-bold text-gray-900 leading-snug transition-colors">
              আটার বল মেকারে রাখুন
            </h3>
            <p class="text-gray-500 text-sm sm:text-base leading-relaxed mt-2 max-w-xl">
              সাধারণ নিয়মে আটা মেখে ছোট একটি গোল লেচি বানিয়ে মেকারের মাঝখানে প্লাস্টিক পেপারের ওপর রাখুন। কোনো বাড়তি শুকনো আটা ছিটানোর প্রয়োজন নেই।
            </p>
          </div>

          <!-- স্টেপ ০২ -->
          <div class="how-to-use-step group" data-step="2">
            <div class="step-icon-box">
              <!-- মেকারের লিভার / চাপ দেওয়ার প্রাসঙ্গিক আইকন -->
              <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="18" width="18" height="3" rx="1.5" fill="currentColor" fill-opacity="0.3"></rect>
                <path d="M5 14h14"></path>
                <path d="M12 3v7m-3.5-3.5L12 10l3.5-3.5"></path>
              </svg>
            </div>
            <h3 class="step-title text-xl sm:text-2xl font-bold text-gray-900 leading-snug transition-colors">
              হ্যান্ডেল দিয়ে আলতো চাপ দিন
            </h3>
            <p class="text-gray-500 text-sm sm:text-base leading-relaxed mt-2 max-w-xl">
              হ্যান্ডেলটি ধরে সামনের দিকে একবার আলতো চাপ দিন। চোখের পলকে নিখুঁত গোলাকার ও সমান পাতলা রুটি নিমেষেই প্রস্তুত হয়ে যাবে।
            </p>
          </div>

          <!-- স্টেপ ০৩ -->
          <div class="how-to-use-step group" data-step="3">
            <div class="step-icon-box">
              <!-- গরম তাওয়া ও ধোঁয়া ওঠা গরম রুটির প্রাসঙ্গিক আইকন -->
              <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <ellipse cx="11" cy="16" rx="8" ry="3.5" fill="currentColor" fill-opacity="0.3"></ellipse>
                <path d="M18 16l4 3"></path>
                <path d="M8 5c1 1 1 2 0 3"></path>
                <path d="M12 3c1 1.5 1 3 0 4.5"></path>
                <path d="M16 5c1 1 1 2 0 3"></path>
              </svg>
            </div>
            <h3 class="step-title text-xl sm:text-2xl font-bold text-gray-900 leading-snug transition-colors">
              তাওয়ায় সেঁকে গরম পরিবেশন করুন
            </h3>
            <p class="text-gray-500 text-sm sm:text-base leading-relaxed mt-2 max-w-xl">
              গরম তাওয়ায় রুটি দিয়ে কয়েক সেকেন্ডেই উল্টে দিন। সাথে সাথে সুন্দর ফুলে উঠবে ধোঁয়া ওঠা তুলতুলে নরম সুস্বাদু রুটি।
            </p>
          </div>

        </div>

        <!-- ডান সাইড: বড় প্রিমিয়াম ইমেজ (রেফারেন্স স্টাইলে সফট রাউন্ডেড কর্নার) -->
        <div class="lg:col-span-5">
          <div class="relative rounded-3xl overflow-hidden shadow-2xl border border-stone-200/80 aspect-[3/4] bg-stone-100 group">
            <img src="assets/images/how-to-use-lifestyle.jpg" alt="সহজ তিন ধাপে রুটি তৈরির পদ্ধতি" class="w-full h-full object-cover group-hover:scale-102 transition-transform duration-500" loading="lazy">
            
            <!-- ফ্লোটিং ইনফো কার্ড (নিচে মার্জিতভাবে অবস্থান করা) -->
            <div class="absolute bottom-5 left-5 right-5 p-3.5 sm:p-4 rounded-2xl bg-white/95 backdrop-blur-md border border-white/60 shadow-lg flex items-center space-x-3.5">
              <div class="w-10 h-10 rounded-xl bg-gradient-to-r from-[#1b4b08] to-[#3d9213] text-white flex items-center justify-center flex-shrink-0 font-bold shadow-md">
                ✓
              </div>
              <div>
                <div class="text-sm font-bold text-gray-900 leading-tight">মাত্র ৫ সেকেন্ডে পারফেক্ট রুটি</div>
                <div class="text-xs text-gray-500 mt-0.5">কোনো পরিশ্রম ও বেলার ঝামেলা ছাড়াই</div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>
  </section>

  <!-- ৮. কাস্টমারদের মতামত (হরিজোন্টাল স্লাইড অ্যানিমেশন) -->
  

  <!-- ৯. এফএকিউ (FAQ Accordion - আপনার মনের প্রশ্নগুলো) -->
  <section id="faq-section" class="py-12 md:py-16 bg-white border-y border-stone-200/80">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
      <div class="text-center space-y-2 mb-10">
        <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900">
          আপনার মনের সাধারণ প্রশ্নগুলো
        </h2>
        <p class="text-gray-600 text-sm md:text-base">
          কোনো দ্বিধা থাকলে নিচের উত্তরগুলো দেখে নিতে পারেন।
        </p>
      </div>

      <div class="space-y-2.5">
        
        <!-- প্রশ্ন ১ -->
        <div class="faq-item bg-stone-50/70 hover:bg-stone-50 rounded-xl border border-stone-200/90 overflow-hidden active transition-all duration-200">
          <div class="faq-header flex items-center justify-between py-2.5 sm:py-3 px-4 sm:px-5 cursor-pointer font-bold text-sm md:text-base text-gray-900 select-none">
            <span>দাম কি একটু বেশি?</span>
            <span class="faq-icon text-gray-500 text-xs">▼</span>
          </div>
          <div class="faq-content px-4 sm:px-5 pb-3.5 text-xs md:text-sm text-gray-600 leading-relaxed border-t border-stone-200/60 pt-2.5 bg-white">
            নকশাপথের এই বটি অ্যান্ড রুটি মেকারটি সাধারণ কোনো হালকা প্লাস্টিকের নয়। এটি মজবুত হেভি-ডিউটি ফ্রেম দিয়ে তৈরি, যা দীর্ঘদিন টেকে। প্রতিদিনের কাজের সময় বাঁচানো ও দীর্ঘস্থায়িত্ব বিবেচনা করলে এর দাম অত্যন্ত সাশ্রয়ী।
          </div>
        </div>

        <!-- প্রশ্ন ২ -->
        <div class="faq-item bg-stone-50/70 hover:bg-stone-50 rounded-xl border border-stone-200/90 overflow-hidden transition-all duration-200">
          <div class="faq-header flex items-center justify-between py-2.5 sm:py-3 px-4 sm:px-5 cursor-pointer font-bold text-sm md:text-base text-gray-900 select-none">
            <span>কোয়ালিটি নিয়ে ভরসা পাব কীভাবে?</span>
            <span class="faq-icon text-gray-500 text-xs">▼</span>
          </div>
          <div class="faq-content px-4 sm:px-5 pb-3.5 text-xs md:text-sm text-gray-600 leading-relaxed border-t border-stone-200/60 pt-2.5 bg-white">
            আমরা ক্যাশ অন ডেলিভারিতে পণ্য পাঠাই। ডেলিভারিম্যান আসার পর পণ্য দেখে চেক করে টাকা পরিশোধ করার সুযোগ রয়েছে। এছাড়াও কোনো উৎপাদনজনিত ত্রুটি থাকলে আমাদের ৭ দিনের ফ্রি রিপ্লেসমেন্ট পলিসি রয়েছে।
          </div>
        </div>

        <!-- প্রশ্ন ৩ -->
        <div class="faq-item bg-stone-50/70 hover:bg-stone-50 rounded-xl border border-stone-200/90 overflow-hidden transition-all duration-200">
          <div class="faq-header flex items-center justify-between py-2.5 sm:py-3 px-4 sm:px-5 cursor-pointer font-bold text-sm md:text-base text-gray-900 select-none">
            <span>এটি ব্যবহার করা কি কঠিন?</span>
            <span class="faq-icon text-gray-500 text-xs">▼</span>
          </div>
          <div class="faq-content px-4 sm:px-5 pb-3.5 text-xs md:text-sm text-gray-600 leading-relaxed border-t border-stone-200/60 pt-2.5 bg-white">
            একেবারেই না! এটি এতটাই সহজ যে পরিবারের যেকোনো সদস্য এটি অনায়াসে ব্যবহার করতে পারেন। আটার বল রেখে হ্যান্ডেল আলতো করে চাপ দিলেই মাত্র ২ সেকেন্ডে পারফেক্ট রুটি তৈরি হয়ে যায়।
          </div>
        </div>

        <!-- প্রশ্ন ৪ -->
        <div class="faq-item bg-stone-50/70 hover:bg-stone-50 rounded-xl border border-stone-200/90 overflow-hidden transition-all duration-200">
          <div class="faq-header flex items-center justify-between py-2.5 sm:py-3 px-4 sm:px-5 cursor-pointer font-bold text-sm md:text-base text-gray-900 select-none">
            <span>ডেলিভারিতে কত দিন লাগে এবং চার্জ কত?</span>
            <span class="faq-icon text-gray-500 text-xs">▼</span>
          </div>
          <div class="faq-content px-4 sm:px-5 pb-3.5 text-xs md:text-sm text-gray-600 leading-relaxed border-t border-stone-200/60 pt-2.5 bg-white">
            ঢাকার ভেতরে ডেলিভারি চার্জ মাত্র ৬০ টাকা (১-২ দিনে ডেলিভারি) এবং ঢাকার বাইরে ১২০ টাকা (২-৩ দিনে সারা দেশের জেলা/উপজেলা পর্যায়ে ডেলিভারি)।
          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- ১০. অর্ডার চেকআউট ফর্ম সেকশন -->
  <section id="order-form-section" class="py-12 md:py-20 bg-beige-ground">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
      
      <div class="text-center space-y-2 mb-8">
        <span class="bg-green-100 text-green-deep font-bold text-xs uppercase px-3 py-1 rounded-full">দ্রুত অর্ডার</span>
        <h2 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-gray-900">
          অর্ডার করতে নিচের ফর্মটি পূরণ করুন
        </h2>
        <p class="text-gray-600 text-sm">
          কোনো অগ্রিম পেমেন্ট নেই! পণ্য হাতে পেয়ে যাচাই করে মূল্য পরিশোধ করুন।
        </p>
      </div>

      <div class="bg-white rounded-3xl border border-stone-200 shadow-xl overflow-hidden p-6 sm:p-8 md:p-10">
        <form id="noksha-checkout-form" class="space-y-6">
          <input type="hidden" id="fb_event_id" name="event_id" value="<?php echo $purchase_event_id; ?>">

          <!-- সাইজ বা ভ্যারিয়েন্ট ও পরিমাণ নির্বাচন (মাল্টি-আইটেম ও কোয়ান্টিটি কাউন্টার) -->
          <div>
            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-2.5 gap-1">
              <label class="block text-sm font-bold text-gray-900">প্যাকেজ / সাইজ ও পরিমাণ নির্বাচন করুন:</label>
              <span class="text-xs text-emerald-800 bg-emerald-50 px-2 py-0.5 rounded-md font-semibold self-start sm:self-auto">একাধিক পণ্য ও পরিমাণ পছন্দমতো নিতে পারবেন</span>
            </div>
            
            <!-- হিডেন ইনপুটসমূহ -->
            <input type="hidden" id="items_json" name="items_json" value="">
            <input type="hidden" id="product_variant" name="product_variant" value="big">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3" id="product-selection-grid">
              
              <!-- ১. ছোট সাইজের বটি -->
              <div class="product-item-card p-3.5 rounded-xl border-2 border-gray-200 bg-stone-50 hover:border-gray-300 transition-all cursor-pointer select-none" data-product="boti_small" data-price="690" data-name="ছোট সাইজের বটি">
                <div class="flex items-center justify-between">
                  <div class="flex items-center space-x-3">
                    <input type="checkbox" class="product-item-checkbox rounded text-green-600 focus:ring-green-500 h-4 w-4 pointer-events-none" data-target="boti_small">
                    <div>
                      <div class="font-bold text-sm text-gray-900">ছোট সাইজের বটি</div>
                      <div class="text-xs text-gray-500">শাকসবজি ও সালাদ কাটার জন্য</div>
                    </div>
                  </div>
                  <div class="text-right">
                    <div class="font-extrabold text-green-deep text-base">৳৬৯০</div>
                  </div>
                </div>

                <!-- কোয়ান্টিটি কন্ট্রোল (সিলেক্ট করলে শো করবে) -->
                <div class="item-qty-controls mt-2.5 pt-2 border-t border-gray-200/80 flex items-center justify-between hidden">
                  <span class="text-xs text-gray-600 font-medium">পরিমাণ নির্বাচন:</span>
                  <div class="flex items-center space-x-2">
                    <button type="button" class="btn-qty-minus w-7 h-7 rounded-lg bg-stone-200 hover:bg-stone-300 text-stone-800 font-extrabold flex items-center justify-center transition active:scale-95" data-product="boti_small">−</button>
                    <span class="item-qty-display w-6 text-center font-extrabold text-sm text-gray-900" data-product="boti_small">১</span>
                    <button type="button" class="btn-qty-plus w-7 h-7 rounded-lg bg-emerald-700 hover:bg-emerald-800 text-white font-extrabold flex items-center justify-center transition active:scale-95 shadow-sm" data-product="boti_small">+</button>
                  </div>
                </div>
              </div>

              <!-- ২. বড় সাইজের বটি -->
              <div class="product-item-card p-3.5 rounded-xl border-2 border-gray-200 bg-stone-50 hover:border-gray-300 transition-all cursor-pointer select-none" data-product="boti_large" data-price="890" data-name="বড় সাইজের বটি">
                <div class="flex items-center justify-between">
                  <div class="flex items-center space-x-3">
                    <input type="checkbox" class="product-item-checkbox rounded text-green-600 focus:ring-green-500 h-4 w-4 pointer-events-none" data-target="boti_large">
                    <div>
                      <div class="font-bold text-sm text-gray-900">বড় সাইজের বটি</div>
                      <div class="text-xs text-gray-500">মাছ, মাংস ও তরকারি কাটার জন্য</div>
                    </div>
                  </div>
                  <div class="text-right">
                    <div class="font-extrabold text-green-deep text-base">৳৮৯০</div>
                  </div>
                </div>

                <!-- কোয়ান্টিটি কন্ট্রোল -->
                <div class="item-qty-controls mt-2.5 pt-2 border-t border-gray-200/80 flex items-center justify-between hidden">
                  <span class="text-xs text-gray-600 font-medium">পরিমাণ নির্বাচন:</span>
                  <div class="flex items-center space-x-2">
                    <button type="button" class="btn-qty-minus w-7 h-7 rounded-lg bg-stone-200 hover:bg-stone-300 text-stone-800 font-extrabold flex items-center justify-center transition active:scale-95" data-product="boti_large">−</button>
                    <span class="item-qty-display w-6 text-center font-extrabold text-sm text-gray-900" data-product="boti_large">১</span>
                    <button type="button" class="btn-qty-plus w-7 h-7 rounded-lg bg-emerald-700 hover:bg-emerald-800 text-white font-extrabold flex items-center justify-center transition active:scale-95 shadow-sm" data-product="boti_large">+</button>
                  </div>
                </div>
              </div>

              <!-- ৩. ছোট সাইজের রুটি মেকার -->
              <div class="product-item-card p-3.5 rounded-xl border-2 border-gray-200 bg-stone-50 hover:border-gray-300 transition-all cursor-pointer select-none" data-product="small" data-price="1390" data-name="ছোট সাইজের রুটি মেকার">
                <div class="flex items-center justify-between">
                  <div class="flex items-center space-x-3">
                    <input type="checkbox" class="product-item-checkbox rounded text-green-600 focus:ring-green-500 h-4 w-4 pointer-events-none" data-target="small">
                    <div>
                      <div class="font-bold text-sm text-gray-900">ছোট সাইজের রুটি মেকার</div>
                      <div class="text-xs text-gray-500">ছোট ও মাঝারি পরিবার</div>
                    </div>
                  </div>
                  <div class="text-right">
                    <div class="font-extrabold text-green-deep text-base">৳১,৩৯০</div>
                  </div>
                </div>

                <!-- কোয়ান্টিটি কন্ট্রোল -->
                <div class="item-qty-controls mt-2.5 pt-2 border-t border-gray-200/80 flex items-center justify-between hidden">
                  <span class="text-xs text-gray-600 font-medium">পরিমাণ নির্বাচন:</span>
                  <div class="flex items-center space-x-2">
                    <button type="button" class="btn-qty-minus w-7 h-7 rounded-lg bg-stone-200 hover:bg-stone-300 text-stone-800 font-extrabold flex items-center justify-center transition active:scale-95" data-product="small">−</button>
                    <span class="item-qty-display w-6 text-center font-extrabold text-sm text-gray-900" data-product="small">১</span>
                    <button type="button" class="btn-qty-plus w-7 h-7 rounded-lg bg-emerald-700 hover:bg-emerald-800 text-white font-extrabold flex items-center justify-center transition active:scale-95 shadow-sm" data-product="small">+</button>
                  </div>
                </div>
              </div>

              <!-- ৪. বড় সাইজের রুটি মেকার -->
              <div class="product-item-card p-3.5 rounded-xl border-2 border-green-500 bg-white shadow-sm transition-all cursor-pointer select-none" data-product="big" data-price="1490" data-name="বড় সাইজের রুটি মেকার">
                <div class="flex items-center justify-between">
                  <div class="flex items-center space-x-3">
                    <input type="checkbox" class="product-item-checkbox rounded text-green-600 focus:ring-green-500 h-4 w-4 pointer-events-none" data-target="big" checked>
                    <div>
                      <div class="font-bold text-sm text-gray-900">বড় সাইজের রুটি মেকার</div>
                      <div class="text-xs text-green-700 font-semibold">বড় পরিবার (বেস্ট সেলার)</div>
                    </div>
                  </div>
                  <div class="text-right">
                    <div class="font-extrabold text-green-deep text-base">৳১,৪৯০</div>
                  </div>
                </div>

                <!-- কোয়ান্টিটি কন্ট্রোল -->
                <div class="item-qty-controls mt-2.5 pt-2 border-t border-gray-200/80 flex items-center justify-between">
                  <span class="text-xs text-gray-600 font-medium">পরিমাণ নির্বাচন:</span>
                  <div class="flex items-center space-x-2">
                    <button type="button" class="btn-qty-minus w-7 h-7 rounded-lg bg-stone-200 hover:bg-stone-300 text-stone-800 font-extrabold flex items-center justify-center transition active:scale-95" data-product="big">−</button>
                    <span class="item-qty-display w-6 text-center font-extrabold text-sm text-gray-900" data-product="big">১</span>
                    <button type="button" class="btn-qty-plus w-7 h-7 rounded-lg bg-emerald-700 hover:bg-emerald-800 text-white font-extrabold flex items-center justify-center transition active:scale-95 shadow-sm" data-product="big">+</button>
                  </div>
                </div>
              </div>

            </div>
          </div>

          <!-- নাম ও মোবাইল নম্বর -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label for="customer_name" class="block text-xs font-bold text-gray-700 uppercase mb-1">আপনার নাম *</label>
              <input type="text" id="customer_name" name="customer_name" required placeholder="যেমন: মো: রফিকুল ইসলাম" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none text-sm transition">
            </div>

            <div>
              <label for="customer_phone" class="block text-xs font-bold text-gray-700 uppercase mb-1">মোবাইল নম্বর *</label>
              <input type="tel" id="customer_phone" name="customer_phone" required placeholder="যেমন: 017xxxxxxxx" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none text-sm transition">
              <span class="text-[11px] text-gray-500 mt-1 block">ডেলিভারির জন্য আপনার সচল নম্বরটি দিন</span>
            </div>
          </div>

          <!-- ডেলিভারি জোন -->
          <div>
            <label class="block text-xs font-bold text-gray-700 uppercase mb-2">আপনার ডেলিভারির এলাকা নির্বাচন করুন *</label>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
              <label class="flex items-center justify-between p-3.5 rounded-xl border border-gray-300 hover:border-green-500 cursor-pointer text-sm">
                <div class="flex items-center space-x-2.5">
                  <input type="radio" name="delivery_zone" value="inside" checked class="text-green-600 focus:ring-green-500">
                  <span class="font-medium text-gray-800">ঢাকার ভেতরে</span>
                </div>
                <span class="font-bold text-gray-900">৳৬০</span>
              </label>

              <label class="flex items-center justify-between p-3.5 rounded-xl border border-gray-300 hover:border-green-500 cursor-pointer text-sm">
                <div class="flex items-center space-x-2.5">
                  <input type="radio" name="delivery_zone" value="outside" class="text-green-600 focus:ring-green-500">
                  <span class="font-medium text-gray-800">ঢাকার বাইরে (সারা দেশ)</span>
                </div>
                <span class="font-bold text-gray-900">৳১২০</span>
              </label>
            </div>
          </div>

          <!-- পূর্ণ ঠিকানা -->
          <div>
            <label for="customer_address" class="block text-xs font-bold text-gray-700 uppercase mb-1">পূর্ণ ঠিকানা (বাসা নম্বর, রোড, এলাকা/থানা ও জেলা) *</label>
            <textarea id="customer_address" name="customer_address" rows="2" required placeholder="যেমন: বাড়ি নং ১২, রোড নং ৫, ধানমন্ডি, ঢাকা" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none text-sm transition"></textarea>
          </div>

          <!-- লাইভ অর্ডার সামারি বক্স -->
          <div class="bg-stone-50 border border-stone-200 rounded-2xl p-4 md:p-5 space-y-2.5 text-sm">
            <!-- নির্বাচিত আইটেমগুলোর বিস্তারিত লিস্ট -->
            <div id="summary-items-list" class="space-y-1.5 pb-2.5 border-b border-gray-200">
              <div class="flex justify-between text-xs sm:text-sm text-gray-700">
                <span>বড় সাইজের রুটি মেকার × ১</span>
                <span class="font-bold text-gray-900">৳১,৪৯০</span>
              </div>
            </div>

            <div class="flex justify-between text-gray-600 pt-0.5">
              <span>প্রোডাক্ট মোট:</span>
              <span id="summary-product-price" class="font-semibold text-gray-900">৳১,৪৯০</span>
            </div>
            <div class="flex justify-between text-gray-600">
              <span>ডেলিভারি চার্জ:</span>
              <span id="summary-delivery-charge" class="font-semibold text-gray-900">৳৬০</span>
            </div>
            <div class="border-t border-gray-200 pt-2 flex justify-between text-base font-bold text-gray-900">
              <span>সর্বমোট প্রদেয়:</span>
              <span id="summary-total-amount" class="text-xl font-extrabold text-green-deep">৳১,৫৫০</span>
            </div>
            <div class="text-[11px] text-gray-500 pt-1 text-center">
              (পণ্য হাতে পাওয়ার পর ডেলিভারিম্যানকে এই টাকা পরিশোধ করবেন)
            </div>
          </div>

          <!-- অর্ডার বাটন (ন্যাভবারের বাটনের মতো একই কালার, শেক, শিমার ও ভার্টিক্যাল রোল অ্যানিমেশন) -->
          <button type="submit" id="submit-order-btn" class="btn-shake-cta w-full text-base sm:text-lg font-extrabold py-4 rounded-xl sm:rounded-2xl shadow-xl group cursor-pointer" title="অর্ডার কনফার্ম করুন">
            <span class="btn-roll-container">
              <!-- স্বাভাবিক অবস্থা (হোভার করলে উপরে রোল হয়ে যাবে) -->
              <span class="btn-roll-content btn-roll-original">
                <span>অর্ডার কনফার্ম করুন</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 sm:w-6 sm:h-6 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
              </span>
              <!-- রোল ইন অবস্থা (হোভার করলে নিচ থেকে ভেতরে রোল হবে) -->
              <span class="btn-roll-content btn-roll-duplicate" aria-hidden="true">
                <span>অর্ডার কনফার্ম করুন</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 sm:w-6 sm:h-6 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
              </span>
            </span>
          </button>

          <!-- ট্রাস্ট ব্যাজ -->
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5 text-center text-xs text-gray-600 pt-3 border-t border-gray-100">
            <div class="flex items-center justify-center space-x-1.5 py-1">
              <svg class="w-4 h-4 text-green-700 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
              </svg>
              <span class="font-medium">নিরাপদ ক্যাশ অন ডেলিভারি</span>
            </div>
            <div class="flex items-center justify-center space-x-1.5 py-1">
              <svg class="w-4 h-4 text-green-700 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
              </svg>
              <span class="font-medium">দ্রুততম কুরিয়ার সার্ভিস</span>
            </div>
            <div class="flex items-center justify-center space-x-1.5 py-1">
              <svg class="w-4 h-4 text-green-700 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
              </svg>
              <span class="font-medium">হটলাইন: <?php echo STORE_PHONE; ?></span>
            </div>
          </div>

        </form>
      </div>

    </div>
  </section>

  <!-- ১১. ফুটার (ডিপ গ্রিন) -->
  <footer class="bg-green-deep text-white py-10 border-t border-white/10">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 flex flex-col md:flex-row items-center justify-between text-center md:text-left gap-4">
      <div class="flex items-center space-x-3 justify-center md:justify-start">
        <div class="w-10 h-10 rounded-xl bg-white p-1 flex items-center justify-center shadow flex-shrink-0">
          <img src="assets/images/nokshapoth-icon.svg" alt="নকশাপথ লোগো" class="w-full h-full object-contain">
        </div>
        <div class="flex flex-col justify-center text-left">
          <div class="text-xl font-bold tracking-tight leading-tight">নকশাপথ</div>
          <div class="text-xs text-green-200 mt-0.5 leading-none">ঘর সাজে, জীবন সাজে</div>
        </div>
      </div>
      <div class="text-xs text-green-100 space-x-4">
        <span>হটলাইন: <?php echo STORE_PHONE; ?></span>
        <span>•</span>
        <span>হোয়াটসঅ্যাপ: <?php echo STORE_WHATSAPP; ?></span>
      </div>
      <div class="text-xs text-gray-300">
        © <?php echo date('Y'); ?> Nokshapoth. সর্বস্বত্ব সংরক্ষিত।
      </div>
    </div>
  </footer>

  <!-- ১২. থ্যাংক ইউ মোডাল পপআপ -->
  <div id="thank-you-modal" class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4 hidden backdrop-blur-sm">
    <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 text-center space-y-4 shadow-2xl animate-bounce-short">
      <div class="w-16 h-16 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto text-3xl">
        ✓
      </div>
      <h3 class="text-2xl font-bold text-gray-900">অভিনন্দন! আপনার অর্ডার সম্পন্ন হয়েছে</h3>
      <p class="text-xs sm:text-sm text-gray-600 leading-relaxed">
        আমাদের অর্ডার কনফার্মেশন টিম শীঘ্রই আপনার সাথে যোগাযোগ করে ডেলিভারি নিশ্চিত করবে।
      </p>

      <div class="bg-stone-50 rounded-xl p-4 text-left text-xs space-y-2 border border-stone-200">
        <div class="flex justify-between">
          <span class="text-gray-500">অর্ডার আইডি:</span>
          <span id="modal-order-id" class="font-bold text-gray-900">#</span>
        </div>
        <div class="flex justify-between">
          <span class="text-gray-500">পণ্য:</span>
          <span id="modal-product-name" class="font-bold text-gray-900">#</span>
        </div>
        <div class="flex justify-between">
          <span class="text-gray-500">মোবাইল:</span>
          <span id="modal-phone" class="font-bold text-gray-900">#</span>
        </div>
        <div class="flex justify-between border-t border-gray-200 pt-1.5 font-bold">
          <span class="text-gray-900">সর্বমোট প্রদেয় (COD):</span>
          <span id="modal-total-amount" class="text-green-deep text-sm">৳#</span>
        </div>
      </div>

      <button onclick="document.getElementById('thank-you-modal').classList.add('hidden')" class="w-full bg-stone-900 hover:bg-black text-white font-bold py-3 rounded-xl transition text-sm">
        ঠিক আছে
      </button>
    </div>
  </div>

  <!-- স্ক্রিপ্টস -->
  <script src="assets/js/landing.js"></script>
</body>
</html>
