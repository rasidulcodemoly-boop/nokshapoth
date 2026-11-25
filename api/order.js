const store = require('./store');
const crypto = require('crypto');

module.exports = async (req, res) => {
  // CORS Headers
  res.setHeader('Access-Control-Allow-Credentials', true);
  res.setHeader('Access-Control-Allow-Origin', '*');
  res.setHeader('Access-Control-Allow-Methods', 'GET,OPTIONS,PATCH,DELETE,POST,PUT');
  res.setHeader(
    'Access-Control-Allow-Headers',
    'X-CSRF-Token, X-Requested-With, Accept, Accept-Version, Content-Length, Content-MD5, Content-Type, Date, X-Api-Version'
  );

  if (req.method === 'OPTIONS') {
    res.status(200).end();
    return;
  }

  if (req.method !== 'POST') {
    return res.status(405).json({ success: false, message: 'Method Not Allowed' });
  }

  try {
    let data = req.body || {};
    if (typeof data === 'string') {
      try {
        data = JSON.parse(data);
      } catch (e) {
        const querystring = require('querystring');
        data = querystring.parse(data);
      }
    }
    const orderId = 'NP-' + Date.now().toString().slice(-6) + '-' + Math.floor(1000 + Math.random() * 9000);

    let items = [];
    if (typeof data.items_json === 'string') {
      try {
        items = JSON.parse(data.items_json);
      } catch (e) {
        items = [];
      }
    } else if (Array.isArray(data.items_json)) {
      items = data.items_json;
    }

    let variantTitle = '';
    let price = 0;

    if (Array.isArray(items) && items.length > 0) {
      const titleParts = [];
      items.forEach(item => {
        const itemTotal = (Number(item.price) || 0) * (Number(item.qty) || 1);
        price += itemTotal;
        titleParts.push(`${item.name} (${item.qty}টি)`);
      });
      variantTitle = titleParts.join(' + ');
    } else {
      const variant = data.product_variant || 'boti_small';
      variantTitle = '12” ফোল্ডিং ফ্লোর প্রিমিয়াম মহারানী বটি';
      price = 1490;
      if (variant === 'small') {
        variantTitle = 'ছোট সাইজের রুটি মেকার';
        price = 1390;
      } else if (variant === 'big') {
        variantTitle = 'বড় সাইজের রুটি মেকার';
        price = 1490;
      } else if (variant === 'boti_large' || variant === 'boti_clamp') {
        variantTitle = 'ফোল্ডিং প্রিমিয়াম মহারানী বটি [ 12” ইঞ্চি ]';
        price = 1390;
      }
    }

    const delivery = data.delivery_zone === 'outside' ? 150 : 80;
    const total = price + delivery;

    const orderData = {
      order_id: orderId,
      customer_name: data.customer_name || 'নাম উল্লেখ নেই',
      customer_phone: data.customer_phone || '',
      customer_address: data.customer_address || '',
      delivery_zone: data.delivery_zone === 'outside' ? 'ঢাকার বাইরে' : 'ঢাকার ভেতরে',
      product_name: variantTitle,
      items: items.length > 0 ? items : [{ name: variantTitle, qty: 1, price: price }],
      product_price: price,
      delivery_charge: delivery,
      total_amount: total,
      payment_method: 'Cash on Delivery (COD)',
      created_at: new Date().toISOString()
    };

    // Save order persistently across Vercel serverless executions
    await store.saveOrder(orderData);

    // Meta Conversions API (CAPI) Server-side Dispatch (High Event Match Quality)
    const pixelId = process.env.FB_PIXEL_ID || '1012390691376333';
    const capiToken = process.env.FB_CAPI_ACCESS_TOKEN;
    if (capiToken && capiToken !== 'YOUR_META_CONVERSIONS_API_ACCESS_TOKEN_HERE') {
      try {
        const cleanPhone = (data.customer_phone || '').replace(/[^0-9]/g, '');
        const clientIp = req.headers['x-forwarded-for']?.split(',')[0]?.trim() || req.socket?.remoteAddress || '';
        const userAgent = req.headers['user-agent'] || '';
        const eventId = data.event_id || ('FRc_' + Date.now());

        const cookies = req.headers.cookie ? Object.fromEntries(req.headers.cookie.split('; ').map(c => c.split('='))) : {};
        let fbp = data.fbp || cookies['_fbp'] || '';
        let fbc = data.fbc || cookies['_fbc'] || '';
        const fbclid = data.fbclid || '';
        if (!fbc && fbclid) {
          fbc = `fb.1.${Math.floor(Date.now() / 1000)}.${fbclid}`;
        }

        const sha256 = (val) => crypto.createHash('sha256').update(val).digest('hex');
        const phoneFormatted = cleanPhone.startsWith('88') ? cleanPhone : ('88' + cleanPhone);
        const hashedPhone = sha256(phoneFormatted);
        const hashedCountry = sha256('bd');

        const nameParts = (data.customer_name || '').trim().split(/\s+/);
        const fn = nameParts[0] ? sha256(nameParts[0].toLowerCase()) : null;
        const ln = nameParts.length > 1 ? sha256(nameParts[nameParts.length - 1].toLowerCase()) : null;
        const city = (orderData.delivery_zone === 'ঢাকার ভেতরে') ? 'dhaka' : '';
        const hashedCity = city ? sha256(city) : null;

        const userData = {
          ph: [hashedPhone],
          country: [hashedCountry],
          client_ip_address: clientIp,
          client_user_agent: userAgent
        };
        if (fn) userData.fn = [fn];
        if (ln) userData.ln = [ln];
        if (hashedCity) userData.ct = [hashedCity];
        if (fbp) userData.fbp = fbp;
        if (fbc) userData.fbc = fbc;

        const capiPayload = {
          data: [{
            event_name: 'Purchase',
            event_time: Math.floor(Date.now() / 1000),
            event_id: eventId,
            event_source_url: req.headers.referer || 'https://nokshapoth.com/',
            action_source: 'website',
            user_data: userData,
            custom_data: {
              currency: 'BDT',
              value: total,
              content_name: variantTitle,
              content_type: 'product'
            }
          }]
        };

        fetch(`https://graph.facebook.com/v19.0/${pixelId}/events?access_token=${capiToken}`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(capiPayload)
        }).catch(err => console.error('Meta CAPI Error:', err));
      } catch (capiErr) {
        console.error('Meta CAPI Dispatch Exception:', capiErr);
      }
    }

    // Google Sheets Webhook Sync (if environment variable set)
    if (process.env.GOOGLE_SHEET_WEBHOOK_URL) {
      try {
        fetch(process.env.GOOGLE_SHEET_WEBHOOK_URL, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
            order_id: orderId,
            date: new Date().toLocaleString('bn-BD', { timeZone: 'Asia/Dhaka' }),
            customer_name: data.customer_name || 'নাম উল্লেখ নেই',
            customer_phone: data.customer_phone || '',
            customer_address: data.customer_address || '',
            delivery_zone: orderData.delivery_zone,
            product_name: variantTitle,
            product_price: price,
            delivery_charge: delivery,
            total_amount: total,
            payment_method: 'Cash on Delivery (COD)'
          })
        }).catch(err => console.error('Google Sheet Sync Error:', err));
      } catch (e) {}
    }

    res.status(200).json({
      success: true,
      order_id: orderId,
      product_name: variantTitle,
      total_amount: total,
      phone: data.customer_phone,
      message: 'আপনার অর্ডারটি সফলভাবে গ্রহণ করা হয়েছে!'
    });
  } catch (error) {
    console.error('Order function error:', error);
    res.status(500).json({ success: false, message: 'অর্ডারে ত্রুটি হয়েছে।' });
  }
};
