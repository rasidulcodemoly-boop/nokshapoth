/**
 * Nokshapoth Landing Page Frontend Logic
 * --------------------------------------
 * - Dynamic price & variant calculation
 * - Smooth scroll & quick actions
 * - Abandoned cart lead autosave
 * - Meta Pixel & CAPI High Event Match Quality tracking (_fbc, _fbp, fbclid)
 * - AJAX checkout form submission & Thank You modal
 */

// Meta Click ID (fbclid) & Cookie Capture (High Event Match Quality)
(function() {
    try {
        function getCookie(name) {
            const v = `; ${document.cookie}`;
            const parts = v.split(`; ${name}=`);
            if (parts.length === 2) return parts.pop().split(';').shift();
            return '';
        }
        function setCookie(name, val, days) {
            const d = new Date();
            d.setTime(d.getTime() + (days * 24 * 60 * 60 * 1000));
            document.cookie = `${name}=${val};path=/;expires=${d.toUTCString()};SameSite=Lax`;
        }

        const urlParams = new URLSearchParams(window.location.search);
        const fbclid = urlParams.get('fbclid');
        if (fbclid) {
            sessionStorage.setItem('meta_fbclid', fbclid);
            localStorage.setItem('meta_fbclid', fbclid);
            const fbcVal = `fb.1.${Date.now()}.${fbclid}`;
            setCookie('_fbc', fbcVal, 90);
            sessionStorage.setItem('meta_fbc', fbcVal);
        }

        let fbp = getCookie('_fbp');
        if (!fbp) {
            fbp = `fb.1.${Date.now()}.${Math.floor(1000000000 + Math.random() * 9000000000)}`;
            setCookie('_fbp', fbp, 90);
        }
    } catch(e) {}
})();

document.addEventListener('DOMContentLoaded', function () {
    // ১. ক্যাটালগ ও ডাটা ডেফিনিশন
    const productCatalog = {
        boti_small: { name: '12” ফোল্ডিং ফ্লোর প্রিমিয়াম মহারানী বটি', price: 1490 },
        boti_large: { name: 'ফোল্ডিং প্রিমিয়াম মহারানী বটি [ 12” ইঞ্চি ]', price: 1390 },
        boti_floor: { name: '12” ফোল্ডিং ফ্লোর প্রিমিয়াম মহারানী বটি', price: 1490 },
        boti_clamp: { name: 'ফোল্ডিং প্রিমিয়াম মহারানী বটি [ 12” ইঞ্চি ]', price: 1390 },
        small: { name: 'ছোট সাইজের রুটি মেকার', price: 1390 },
        big: { name: 'বড় সাইজের রুটি মেকার', price: 1490 }
    };

    const deliveryCharges = {
        inside: 80,
        outside: 150
    };

    // কার্ট স্টেট: { productId: quantity }
    // ডিফল্টভাবে ১২" ফোল্ডিং ফ্লোর প্রিমিয়াম মহারানী বটি ১টি সিলেক্টেড থাকবে (৳১,৪৯০)
    let selectedItems = { boti_small: 1 };
    let currentZone = 'inside';

    // বাংলা সংখ্যা কনভার্টার
    function toBn(num) {
        if (num === undefined || num === null) return '০';
        const bnDigits = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
        return num.toString().replace(/\d/g, d => bnDigits[d]);
    }

    // ২. এলিমেন্টস
    const displayProductPrice = document.getElementById('summary-product-price');
    const displayDeliveryCharge = document.getElementById('summary-delivery-charge');
    const displayTotalAmount = document.getElementById('summary-total-amount');
    const summaryItemsList = document.getElementById('summary-items-list');
    const hiddenItemsJson = document.getElementById('items_json');
    const hiddenProductVariant = document.getElementById('product_variant');
    const zoneRadios = document.querySelectorAll('input[name="delivery_zone"]');
    const orderForm = document.getElementById('noksha-checkout-form');
    const submitBtn = document.getElementById('submit-order-btn');
    const thankYouModal = document.getElementById('thank-you-modal');

    // ৩. লাইভ অর্ডার সামারি ক্যালকুলেশন ও রেন্ডারিং
    function updateOrderSummary() {
        const itemKeys = Object.keys(selectedItems);
        let productSubtotal = 0;
        let totalItemsCount = 0;

        // আইটেম কার্ড UI স্টেট সিঙ্ক
        document.querySelectorAll('.product-item-card').forEach(card => {
            const pId = card.dataset.product;
            const checkbox = card.querySelector('.product-item-checkbox');
            const qtyControls = card.querySelector('.item-qty-controls');
            const qtyDisplay = card.querySelector('.item-qty-display');

            if (selectedItems[pId] !== undefined) {
                const qty = selectedItems[pId];
                productSubtotal += productCatalog[pId].price * qty;
                totalItemsCount += qty;

                card.classList.add('border-green-500', 'bg-white', 'shadow-sm');
                card.classList.remove('border-gray-200', 'bg-stone-50');
                if (checkbox) checkbox.checked = true;
                if (qtyControls) qtyControls.classList.remove('hidden');
                if (qtyDisplay) qtyDisplay.innerText = toBn(qty);
            } else {
                card.classList.remove('border-green-500', 'bg-white', 'shadow-sm');
                card.classList.add('border-gray-200', 'bg-stone-50');
                if (checkbox) checkbox.checked = false;
                if (qtyControls) qtyControls.classList.add('hidden');
            }
        });

        // ডেলিভারি ও সর্বমোট হিসাব
        const deliveryCharge = deliveryCharges[currentZone] || 80;
        const total = productSubtotal > 0 ? (productSubtotal + deliveryCharge) : 0;

        // সামারি টেক্সট আপডেট
        if (displayProductPrice) {
            displayProductPrice.innerText = '৳' + toBn(productSubtotal.toLocaleString('en-US'));
        }
        if (displayDeliveryCharge) {
            displayDeliveryCharge.innerText = '৳' + toBn(deliveryCharge.toLocaleString('en-US'));
        }
        if (displayTotalAmount) {
            displayTotalAmount.innerText = '৳' + toBn(total.toLocaleString('en-US'));
        }

        // আইটেম লিস্ট রেন্ডারিং
        if (summaryItemsList) {
            if (itemKeys.length === 0) {
                summaryItemsList.innerHTML = `
                    <div class="text-xs text-amber-700 bg-amber-50 p-2.5 rounded-lg text-center font-medium flex items-center justify-center space-x-1.5">
                        <svg class="w-4 h-4 text-amber-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        <span>অনুগ্রহ করে উপরের তালিকা থেকে কমপক্ষে একটি পণ্য নির্বাচন করুন</span>
                    </div>
                `;
            } else {
                let html = '';
                itemKeys.forEach(pId => {
                    const item = productCatalog[pId];
                    const qty = selectedItems[pId];
                    const itemTotal = item.price * qty;
                    html += `
                        <div class="flex justify-between text-xs sm:text-sm text-gray-700">
                            <span>${item.name} × ${toBn(qty)}</span>
                            <span class="font-bold text-gray-900">৳${toBn(itemTotal.toLocaleString('en-US'))}</span>
                        </div>
                    `;
                });
                summaryItemsList.innerHTML = html;
            }
        }

        // হিডেন ইনপুট ফিল্ড আপডেট
        const itemsArray = itemKeys.map(pId => ({
            id: pId,
            name: productCatalog[pId].name,
            price: productCatalog[pId].price,
            qty: selectedItems[pId]
        }));

        if (hiddenItemsJson) {
            hiddenItemsJson.value = JSON.stringify(itemsArray);
        }
        if (hiddenProductVariant) {
            hiddenProductVariant.value = itemKeys.length > 0 ? itemKeys[0] : '';
        }
    }

    // ৪. প্রোডাক্ট কার্ড ক্লিক (সিলেকশন / ডিসিলেকশন টগল)
    document.querySelectorAll('.product-item-card').forEach(card => {
        card.addEventListener('click', function (e) {
            // প্লাস বা মাইনাস বাটনে ক্লিক পড়লে কার্ডের সিলেকশন টগল হবে না
            if (e.target.closest('.btn-qty-plus') || e.target.closest('.btn-qty-minus')) {
                return;
            }

            const pId = this.dataset.product;
            if (!pId || !productCatalog[pId]) return;

            if (selectedItems[pId] !== undefined) {
                // অলরেডি সিলেক্টেড থাকলে আনসিলেক্ট করা (যদি আরও পণ্য সিলেক্টেড থাকে)
                delete selectedItems[pId];
            } else {
                // সিলেক্ট করা (ডিফল্ট কোয়ান্টিটি ১)
                selectedItems[pId] = 1;
            }
            updateOrderSummary();
        });
    });

    // ৫. কোয়ান্টিটি প্লাস ও মাইনাস বাটন হ্যান্ডলার
    document.querySelectorAll('.btn-qty-plus').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            const pId = this.dataset.product;
            if (!pId) return;

            const currentQty = selectedItems[pId] || 1;
            if (currentQty < 10) {
                selectedItems[pId] = currentQty + 1;
                updateOrderSummary();
            }
        });
    });

    document.querySelectorAll('.btn-qty-minus').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            const pId = this.dataset.product;
            if (!pId) return;

            const currentQty = selectedItems[pId] || 1;
            if (currentQty > 1) {
                selectedItems[pId] = currentQty - 1;
                updateOrderSummary();
            } else {
                // কোয়ান্টিটি ১ থাকা অবস্থায় মাইনাস চাপলে আনসিলেক্ট হবে
                delete selectedItems[pId];
                updateOrderSummary();
            }
        });
    });

    // ৬. ডেলিভারি জোন লিসেনার
    zoneRadios.forEach(radio => {
        radio.addEventListener('change', function () {
            currentZone = this.value;
            updateOrderSummary();
        });
    });

    // ৭. বাইরের সেকশনের (যেমন প্রাইসিং কার্ডের) "অর্ডার করুন" বাটন হ্যান্ডলার
    document.querySelectorAll('.btn-select-variant').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const targetVariant = this.dataset.variant || 'boti_small';
            if (targetVariant && productCatalog[targetVariant]) {
                selectedItems = { [targetVariant]: 1 };
                updateOrderSummary();
                // Meta Pixel AddToCart Event
                if (typeof fbq === 'function') {
                    fbq('track', 'AddToCart', {
                        content_name: productCatalog[targetVariant].name,
                        currency: 'BDT',
                        value: productCatalog[targetVariant].price
                    });
                }
                // GA4 add_to_cart Event
                if (typeof gtag === 'function') {
                    gtag('event', 'add_to_cart', {
                        currency: 'BDT',
                        value: productCatalog[targetVariant].price,
                        items: [{
                            item_id: targetVariant,
                            item_name: productCatalog[targetVariant].name,
                            price: productCatalog[targetVariant].price,
                            quantity: 1
                        }]
                    });
                }
            }
            scrollToCheckout(targetVariant);
        });
    });

    // স্ক্রোল টু চেকআউট
    window.scrollToCheckout = function (variant) {
        const targetVariant = variant || 'boti_small';
        if (targetVariant && productCatalog[targetVariant]) {
            selectedItems = { [targetVariant]: 1 };
            updateOrderSummary();
        }

        // Meta Pixel InitiateCheckout Event
        if (typeof fbq === 'function') {
            fbq('track', 'InitiateCheckout', {
                content_category: 'Kitchen Essentials',
                content_name: productCatalog[targetVariant] ? productCatalog[targetVariant].name : '12” ফোল্ডিং ফ্লোর প্রিমিয়াম মহারানী বটি',
                currency: 'BDT',
                value: productCatalog[targetVariant] ? productCatalog[targetVariant].price : 1490
            });
        }
        // GA4 begin_checkout Event
        if (typeof gtag === 'function') {
            const checkoutItem = productCatalog[targetVariant] || productCatalog.boti_small;
            gtag('event', 'begin_checkout', {
                currency: 'BDT',
                value: checkoutItem.price,
                items: [{
                    item_id: targetVariant,
                    item_name: checkoutItem.name,
                    price: checkoutItem.price,
                    quantity: 1
                }]
            });
        }
        const target = document.getElementById('order-form-section');
        if (target) {
            target.scrollIntoView({ behavior: 'smooth' });

            // সিলেক্টেড কার্ড হাইলাইট অ্যানিমেশন
            setTimeout(() => {
                const targetCard = document.querySelector(`.product-item-card[data-product="${targetVariant}"]`);
                if (targetCard) {
                    targetCard.classList.add('ring-4', 'ring-green-400');
                    setTimeout(() => targetCard.classList.remove('ring-4', 'ring-green-400'), 1500);
                }
            }, 400);

            // নাম ফিল্ডে অটো ফোকাস
            setTimeout(() => {
                const nameInput = document.getElementById('customer_name');
                if (nameInput) nameInput.focus();
            }, 600);
        }
    };

    // Meta Pixel & GA4 Contact Event (WhatsApp & Hotline)
    document.querySelectorAll('a[href*="wa.me"]').forEach(link => {
        link.addEventListener('click', function () {
            if (typeof fbq === 'function') {
                fbq('track', 'Contact', { channel: 'WhatsApp' });
            }
            if (typeof gtag === 'function') {
                gtag('event', 'generate_lead', { method: 'WhatsApp' });
            }
        });
    });
    document.querySelectorAll('a[href^="tel:"]').forEach(link => {
        link.addEventListener('click', function () {
            if (typeof fbq === 'function') {
                fbq('track', 'Contact', { channel: 'Phone Hotline' });
            }
            if (typeof gtag === 'function') {
                gtag('event', 'generate_lead', { method: 'Phone Hotline' });
            }
        });
    });

    // ৮. FAQ একর্ডিয়ন টগল
    document.querySelectorAll('.faq-header').forEach(header => {
        header.addEventListener('click', function () {
            const item = this.parentElement;
            const isOpen = item.classList.contains('active');

            // অন্যগুলো বন্ধ করা
            document.querySelectorAll('.faq-item').forEach(other => {
                other.classList.remove('active');
            });

            if (!isOpen) {
                item.classList.add('active');
            }
        });
    });

    // ৯. Abandoned Cart Autosave (মোবাইল নম্বর ও নাম টাইপ করলে ব্যাকএন্ডে সেভ)
    const phoneInput = document.getElementById('customer_phone');
    const nameInput = document.getElementById('customer_name');
    const addressInput = document.getElementById('customer_address');

    let autosaveTimeout = null;
    function triggerAutosave() {
        const phone = phoneInput ? phoneInput.value.trim() : '';
        if (phone.length >= 11) {
            const formData = new FormData();
            formData.append('customer_phone', phone);
            formData.append('customer_name', nameInput ? nameInput.value.trim() : '');
            formData.append('customer_address', addressInput ? addressInput.value.trim() : '');
            formData.append('product_variant', Object.keys(selectedItems).join(',') || 'boti_small');
            formData.append('items_json', JSON.stringify(selectedItems));

            const bodyParams = new URLSearchParams(formData);

            fetch('api/autosave', {
                method: 'POST',
                body: bodyParams
            }).catch(() => {
                fetch('api/autosave.php', { method: 'POST', body: bodyParams }).catch(() => {});
            });
        }
    }

    if (phoneInput) {
        phoneInput.addEventListener('input', function () {
            clearTimeout(autosaveTimeout);
            autosaveTimeout = setTimeout(triggerAutosave, 1200);
        });
        phoneInput.addEventListener('blur', triggerAutosave);
    }

    // ১০. চেকআউট ফর্ম সাবমিশন
    if (orderForm) {
        orderForm.addEventListener('submit', function (e) {
            e.preventDefault();

            if (Object.keys(selectedItems).length === 0) {
                alert('অনুগ্রহ করে তালিকা থেকে কমপক্ষে একটি পণ্য নির্বাচন করুন।');
                return;
            }

            const name = nameInput.value.trim();
            const phone = phoneInput.value.trim();
            const address = addressInput.value.trim();

            if (!name) {
                alert('অনুগ্রহ করে আপনার পুরো নাম লিখুন।');
                nameInput.focus();
                return;
            }

            // ফোন নম্বর ভ্যালিডেশন
            const cleanPhone = phone.replace(/[^0-9]/g, '');
            if (cleanPhone.length < 11 || !cleanPhone.match(/^01[3-9]\d{8}$/)) {
                alert('অনুগ্রহ করে সঠিক ১১ ডিজিটের মোবাইল নম্বর দিন (যেমন: 017xxxxxxxx)');
                phoneInput.focus();
                return;
            }

            if (!address) {
                alert('অনুগ্রহ করে আপনার ডেলিভারির পূর্ণ ঠিকানা দিন।');
                addressInput.focus();
                return;
            }

            // বাটন লোডিং স্টেট
            submitBtn.disabled = true;
            const originalBtnHtml = submitBtn.innerHTML;
            submitBtn.innerHTML = `
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
                অর্ডার প্রসেস হচ্ছে, অনুগ্রহ করে অপেক্ষা করুন...
            `;

            const formData = new FormData(orderForm);
            
            // ইউনিক মেটা ইভেন্ট আইডি ও ট্র্যাকিং প্যারামিটার নিশ্চিত করা (High Event Match Quality)
            let eventId = document.getElementById('fb_event_id')?.value;
            if (!eventId) {
                eventId = 'FRc_' + Math.random().toString(36).substring(2, 9) + '_' + Date.now();
                const eventIdEl = document.getElementById('fb_event_id');
                if (eventIdEl) eventIdEl.value = eventId;
            }
            formData.set('event_id', eventId);

            // কুকি বা সেশন স্টোরেজ থেকে fbp, fbc ও fbclid রিড করে পাঠানো
            function readCookieVal(name) {
                const match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
                return match ? match[2] : '';
            }
            const fbpVal = readCookieVal('_fbp');
            const fbcVal = readCookieVal('_fbc') || sessionStorage.getItem('meta_fbc') || '';
            const fbclidVal = sessionStorage.getItem('meta_fbclid') || new URLSearchParams(window.location.search).get('fbclid') || '';

            if (fbpVal) formData.set('fbp', fbpVal);
            if (fbcVal) formData.set('fbc', fbcVal);
            if (fbclidVal) formData.set('fbclid', fbclidVal);

            const bodyParams = new URLSearchParams(formData);

            let totalItemsCount = 0;
            Object.values(selectedItems).forEach(qty => { totalItemsCount += qty; });

            // AJAX অর্ডার রিকোয়েস্ট (Vercel ও cPanel উভয় প্ল্যাটফর্মের জন্য রেজিলিয়েন্ট ডাবল-রাউট)
            fetch('api/order', {
                method: 'POST',
                body: bodyParams
            })
            .then(res => {
                if (!res.ok && res.status !== 400 && res.status !== 422) {
                    return fetch('api/order.php', { method: 'POST', body: bodyParams }).then(r => r.json());
                }
                return res.json();
            })
            .then(data => {
                if (data.success) {
                    // ফেসবুক পিক্সেল ক্লায়েন্ট-সাইড Purchase Event ফায়ার করা
                    if (typeof fbq === 'function') {
                        fbq('track', 'Purchase', {
                            content_name: data.product_name,
                            content_type: 'product',
                            value: data.total_amount,
                            currency: 'BDT',
                            num_items: totalItemsCount
                        }, { eventID: eventId });
                    }

                    // GA4 purchase Event ফায়ার করা
                    if (typeof gtag === 'function') {
                        const gaItems = Object.keys(selectedItems).map(pId => ({
                            item_id: pId,
                            item_name: productCatalog[pId]?.name || data.product_name,
                            price: productCatalog[pId]?.price || data.total_amount,
                            quantity: selectedItems[pId]
                        }));
                        gtag('event', 'purchase', {
                            transaction_id: data.order_id,
                            value: data.total_amount,
                            currency: 'BDT',
                            shipping: formData.get('delivery_zone') === 'outside' ? 150 : 80,
                            items: gaItems
                        });
                    }

                    // থ্যাংক ইউ মোডাল পপআপ
                    document.getElementById('modal-order-id').innerText = data.order_id;
                    document.getElementById('modal-product-name').innerText = data.product_name;
                    document.getElementById('modal-total-amount').innerText = '৳' + toBn(data.total_amount.toLocaleString('en-US'));
                    document.getElementById('modal-phone').innerText = data.phone;
                    thankYouModal.classList.remove('hidden');

                    // ব্রাউজার লোকাল স্টোরেজে ব্যাকআপ (এডমিন প্যানেলে তাৎক্ষণিক পাওয়ার জন্য)
                    try {
                        const localOrder = {
                            order_id: data.order_id,
                            customer_name: formData.get('customer_name') || 'নাম উল্লেখ নেই',
                            customer_phone: data.phone || formData.get('customer_phone') || '',
                            customer_address: formData.get('customer_address') || '',
                            delivery_zone: formData.get('delivery_zone') === 'outside' ? 'ঢাকার বাইরে' : 'ঢাকার ভেতরে',
                            product_name: data.product_name,
                            delivery_charge: formData.get('delivery_zone') === 'outside' ? 150 : 80,
                            total_amount: data.total_amount,
                            payment_method: 'Cash on Delivery (COD)',
                            created_at: new Date().toISOString()
                        };
                        const existingOrders = JSON.parse(localStorage.getItem('nokshapoth_orders') || '[]');
                        existingOrders.unshift(localOrder);
                        localStorage.setItem('nokshapoth_orders', JSON.stringify(existingOrders.slice(0, 100)));
                    } catch(e) {}

                    // গুগল শিট রিয়েল-টাইম অটো-সিঙ্ক (যদি ক্লায়েন্ট-সাইডে URL সেভ করা থাকে)
                    try {
                        const gsheetUrl = localStorage.getItem('nokshapoth_gsheet_url') || (window.NOKSHAPOTH_CONFIG && window.NOKSHAPOTH_CONFIG.gsheet_url);
                        if (gsheetUrl) {
                            fetch(gsheetUrl, {
                                method: 'POST',
                                mode: 'no-cors',
                                headers: { 'Content-Type': 'application/json' },
                                body: JSON.stringify({
                                    order_id: data.order_id,
                                    date: new Date().toLocaleString('bn-BD', { timeZone: 'Asia/Dhaka' }),
                                    customer_name: formData.get('customer_name') || '',
                                    customer_phone: data.phone || formData.get('customer_phone') || '',
                                    customer_address: formData.get('customer_address') || '',
                                    delivery_zone: formData.get('delivery_zone') === 'outside' ? 'ঢাকার বাইরে' : 'ঢাকার ভেতরে',
                                    product_name: data.product_name,
                                    product_price: data.total_amount - (formData.get('delivery_zone') === 'outside' ? 150 : 80),
                                    delivery_charge: formData.get('delivery_zone') === 'outside' ? 150 : 80,
                                    total_amount: data.total_amount,
                                    payment_method: 'Cash on Delivery (COD)'
                                })
                            }).catch(() => {});
                        }
                    } catch(e) {}

                    // ফর্ম রিসেট
                    orderForm.reset();
                    selectedItems = { boti_small: 1 };
                    updateOrderSummary();
                } else {
                    alert(data.message || 'অর্ডারে সমস্যা হয়েছে। অনুগ্রহ করে আবার চেষ্টা করুন।');
                }
            })
            .catch(err => {
                console.error(err);
                alert('নেটওয়ার্ক সমস্যার কারণে অর্ডার সম্পন্ন করা যায়নি। অনুগ্রহ করে একটু পর চেষ্টা করুন অথবা আমাদের সরাসরি ফোন করুন।');
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnHtml;
            });
        });
    }

    // ১০. ইনিশিয়াল ক্যালকুলেশন
    updateOrderSummary();

    // ১১. স্মার্ট নেভবার ও স্ক্রোল প্রোগ্রেস বার (Scroll Progress & Mobile Menu)
    const progressBar = document.getElementById('scroll-progress-bar');
    window.addEventListener('scroll', function () {
        if (progressBar) {
            const winScroll = document.documentElement.scrollTop || document.body.scrollTop;
            const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            const scrolled = (winScroll / height) * 100;
            progressBar.style.width = scrolled + '%';
        }
    });

    const menuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu-dropdown');
    const menuBackdrop = document.getElementById('mobile-menu-backdrop');
    const menuCloseBtn = document.getElementById('mobile-menu-close-btn');

    function toggleMobileMenu(forceOpen) {
        if (!mobileMenu) return;
        const willOpen = typeof forceOpen === 'boolean' ? forceOpen : mobileMenu.classList.contains('hidden');
        if (willOpen) {
            mobileMenu.classList.remove('hidden');
            if (menuBackdrop) menuBackdrop.classList.remove('hidden');
        } else {
            mobileMenu.classList.add('hidden');
            if (menuBackdrop) menuBackdrop.classList.add('hidden');
        }
    }

    if (menuBtn) {
        menuBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            toggleMobileMenu();
        });
    }

    if (menuCloseBtn) {
        menuCloseBtn.addEventListener('click', function () {
            toggleMobileMenu(false);
        });
    }

    if (menuBackdrop) {
        menuBackdrop.addEventListener('click', function () {
            toggleMobileMenu(false);
        });
    }

    if (mobileMenu) {
        mobileMenu.querySelectorAll('a').forEach(item => {
            item.addEventListener('click', function () {
                toggleMobileMenu(false);
            });
        });
    }

    // ৬. ব্যবহার পদ্ধতি (How-to-use) স্টেপস ইন্টারঅ্যাক্টিভ সিলেকশন
    document.querySelectorAll('.how-to-use-step').forEach(step => {
        step.addEventListener('click', function () {
            document.querySelectorAll('.how-to-use-step').forEach(s => s.classList.remove('active'));
            this.classList.add('active');
        });
    });
});

// ==========================================
// ২টি স্লাইডের আধুনিক কম্প্যারিশন স্লাইডার লজিক (বটি ও রুটি মেকার)
// ==========================================
let currentComparisonSlide = 0;
const totalComparisonSlides = 2;
let autoSlideTimer = null;
const AUTO_SLIDE_INTERVAL = 3000; // প্রতি ৩ সেকেন্ড পর অটো চেঞ্জ

function startAutoSlide() {
    stopAutoSlide();
    autoSlideTimer = setInterval(function () {
        window.nextComparisonSlide(true);
    }, AUTO_SLIDE_INTERVAL);
}

function stopAutoSlide() {
    if (autoSlideTimer) {
        clearInterval(autoSlideTimer);
        autoSlideTimer = null;
    }
}

window.setComparisonSlide = function (index, isAuto = false) {
    currentComparisonSlide = (index + totalComparisonSlides) % totalComparisonSlides;

    // ব্যবহারকারী নিজে ক্লিক করলে ৩ সেকেন্ডের টাইমার রিসেট হবে
    if (!isAuto) {
        startAutoSlide();
    }

    // স্লাইড শো/হাইড লজিক
    for (let i = 0; i < totalComparisonSlides; i++) {
        const slide = document.getElementById(`comparison-slide-${i}`);
        if (slide) {
            if (i === currentComparisonSlide) {
                slide.classList.remove('hidden');
                slide.classList.add('block');
            } else {
                slide.classList.add('hidden');
                slide.classList.remove('block');
            }
        }

        // আপডেট ডেস্কটপ ট্যাব বাটন
        const tab = document.getElementById(`slide-tab-${i}`);
        if (tab) {
            if (i === currentComparisonSlide) {
                tab.className = 'slide-nav-tab px-3 sm:px-4 py-1.5 rounded-lg text-xs sm:text-sm font-bold transition-all duration-200 bg-white text-gray-900 shadow-sm border border-stone-200 whitespace-nowrap';
            } else {
                tab.className = 'slide-nav-tab px-3 sm:px-4 py-1.5 rounded-lg text-xs sm:text-sm font-bold transition-all duration-200 text-gray-600 hover:text-gray-900 whitespace-nowrap';
            }
        }

        // আপডেট মোবাইল ডটস
        const dot = document.getElementById(`mobile-dot-${i}`);
        if (dot) {
            if (i === currentComparisonSlide) {
                dot.className = 'w-3 h-3 rounded-full transition-all duration-300 bg-green-deep scale-125';
            } else {
                dot.className = 'w-2.5 h-2.5 rounded-full transition-all duration-300 bg-stone-300';
            }
        }
    }
};

window.nextComparisonSlide = function (isAuto = false) {
    window.setComparisonSlide(currentComparisonSlide + 1, isAuto);
};

window.prevComparisonSlide = function (isAuto = false) {
    window.setComparisonSlide(currentComparisonSlide - 1, isAuto);
};

// অটো-স্লাইড স্টার্ট এবং মাউস হোভার/সোয়াইপ ইন্টারঅ্যাকশন
function initAutoSlider() {
    startAutoSlide();

    const viewport = document.getElementById('comparison-slider-viewport');
    if (!viewport) return;

    // কার্ডে মাউস নিলে সাময়িক পজ (পড়ার সুবিধার জন্য), সরালে আবার চালু
    viewport.addEventListener('mouseenter', stopAutoSlide);
    viewport.addEventListener('mouseleave', startAutoSlide);

    // মোবাইল সোয়াইপ সাপোর্ট
    let startX = 0;
    viewport.addEventListener('touchstart', function (e) {
        stopAutoSlide();
        if (e.touches && e.touches.length > 0) {
            startX = e.touches[0].clientX;
        }
    }, { passive: true });

    viewport.addEventListener('touchend', function (e) {
        startAutoSlide();
        if (e.changedTouches && e.changedTouches.length > 0) {
            const endX = e.changedTouches[0].clientX;
            const diff = startX - endX;
            if (diff > 50) {
                window.nextComparisonSlide();
            } else if (diff < -50) {
                window.prevComparisonSlide();
            }
        }
    }, { passive: true });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initAutoSlider);
} else {
    initAutoSlider();
}

// ব্যবহার পদ্ধতি (How To Use) ট্যাব সুইচিং লজিক
window.setHowToUseTab = function (tab) {
    const rutiPanel = document.getElementById('how-content-ruti');
    const botiPanel = document.getElementById('how-content-boti');
    const rutiTab = document.getElementById('how-tab-ruti');
    const botiTab = document.getElementById('how-tab-boti');

    if (tab === 'ruti') {
        if (rutiPanel) {
            rutiPanel.classList.remove('hidden');
            rutiPanel.classList.add('block');
        }
        if (botiPanel) {
            botiPanel.classList.add('hidden');
            botiPanel.classList.remove('block');
        }
        if (rutiTab) {
            rutiTab.className = "how-nav-tab px-4 sm:px-5 py-2 rounded-xl text-xs sm:text-sm font-bold transition-all duration-200 bg-white text-gray-900 shadow-sm border border-stone-200/90 flex items-center space-x-2";
            rutiTab.setAttribute('aria-selected', 'true');
        }
        if (botiTab) {
            botiTab.className = "how-nav-tab px-4 sm:px-5 py-2 rounded-xl text-xs sm:text-sm font-bold transition-all duration-200 text-gray-600 hover:text-gray-900 border border-transparent flex items-center space-x-2";
            botiTab.setAttribute('aria-selected', 'false');
        }
    } else {
        if (rutiPanel) {
            rutiPanel.classList.add('hidden');
            rutiPanel.classList.remove('block');
        }
        if (botiPanel) {
            botiPanel.classList.remove('hidden');
            botiPanel.classList.add('block');
        }
        if (botiTab) {
            botiTab.className = "how-nav-tab px-4 sm:px-5 py-2 rounded-xl text-xs sm:text-sm font-bold transition-all duration-200 bg-white text-gray-900 shadow-sm border border-stone-200/90 flex items-center space-x-2";
            botiTab.setAttribute('aria-selected', 'true');
        }
        if (rutiTab) {
            rutiTab.className = "how-nav-tab px-4 sm:px-5 py-2 rounded-xl text-xs sm:text-sm font-bold transition-all duration-200 text-gray-600 hover:text-gray-900 border border-transparent flex items-center space-x-2";
            rutiTab.setAttribute('aria-selected', 'false');
        }
    }
};
