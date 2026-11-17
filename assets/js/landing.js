/**
 * Nokshapoth Landing Page Frontend Logic
 * --------------------------------------
 * - Dynamic price & variant calculation
 * - Smooth scroll & quick actions
 * - Abandoned cart lead autosave
 * - Facebook Pixel client-side event tracking
 * - AJAX checkout form submission & Thank You modal
 */

document.addEventListener('DOMContentLoaded', function () {
    // ১. ক্যাটালগ ও ডাটা ডেফিনিশন
    const productCatalog = {
        boti_small: { name: 'ছোট সাইজের বটি', price: 690 },
        boti_large: { name: 'বড় সাইজের বটি', price: 890 },
        small: { name: 'ছোট সাইজের রুটি মেকার', price: 1390 },
        big: { name: 'বড় সাইজের রুটি মেকার', price: 1490 }
    };

    const deliveryCharges = {
        inside: 60,
        outside: 120
    };

    // কার্ট স্টেট: { productId: quantity }
    // ডিফল্টভাবে বড় সাইজের রুটি মেকার ১টি সিলেক্টেড থাকবে
    let selectedItems = { big: 1 };
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
        const deliveryCharge = deliveryCharges[currentZone] || 60;
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
                    <div class="text-xs text-amber-700 bg-amber-50 p-2.5 rounded-lg text-center font-medium">
                        ⚠️ অনুগ্রহ করে উপরের তালিকা থেকে কমপক্ষে একটি পণ্য নির্বাচন করুন
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
            const targetVariant = this.dataset.variant;
            if (targetVariant && productCatalog[targetVariant]) {
                if (!selectedItems[targetVariant]) {
                    selectedItems[targetVariant] = 1;
                }
                updateOrderSummary();
            }
            scrollToCheckout();
        });
    });

    // স্ক্রোল টু চেকআউট
    window.scrollToCheckout = function () {
        const target = document.getElementById('order-form-section');
        if (target) {
            target.scrollIntoView({ behavior: 'smooth' });
            // নাম ফিল্ডে অটো ফোকাস
            setTimeout(() => {
                const nameInput = document.getElementById('customer_name');
                if (nameInput) nameInput.focus();
            }, 600);
        }
    };

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
            formData.append('product_variant', Object.keys(selectedItems).join(',') || 'big');
            formData.append('items_json', JSON.stringify(selectedItems));

            fetch('api/autosave.php', {
                method: 'POST',
                body: formData
            }).catch(() => {});
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
            const eventId = document.getElementById('fb_event_id')?.value || ('FRc_' + Date.now());

            let totalItemsCount = 0;
            Object.values(selectedItems).forEach(qty => { totalItemsCount += qty; });

            // AJAX অর্ডার রিকোয়েস্ট
            fetch('api/order.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
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

                    // থ্যাংক ইউ মোডাল পপআপ
                    document.getElementById('modal-order-id').innerText = data.order_id;
                    document.getElementById('modal-product-name').innerText = data.product_name;
                    document.getElementById('modal-total-amount').innerText = '৳' + toBn(data.total_amount.toLocaleString('en-US'));
                    document.getElementById('modal-phone').innerText = data.phone;
                    thankYouModal.classList.remove('hidden');

                    // ফর্ম রিসেট
                    orderForm.reset();
                    selectedItems = { big: 1 };
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
