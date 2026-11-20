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
    const data = req.body || {};
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
      const variant = data.product_variant || 'big';
      variantTitle = 'বড় সাইজের রুটি মেকার';
      price = 1490;
      if (variant === 'small') {
        variantTitle = 'ছোট সাইজের রুটি মেকার';
        price = 1390;
      } else if (variant === 'boti_small' || variant === 'boti_floor') {
        variantTitle = '12” ফোল্ডিং ফ্লোর প্রিমিয়াম মহারানী বটি';
        price = 1490;
      } else if (variant === 'boti_large' || variant === 'boti_clamp') {
        variantTitle = 'ফোল্ডিং প্রিমিয়াম মহারানী বটি [ 12” ইঞ্চি ]';
        price = 1390;
      }
    }

    const delivery = data.delivery_zone === 'outside' ? 120 : 60;
    const total = price + delivery;

    res.status(200).json({
      success: true,
      order_id: orderId,
      product_name: variantTitle,
      total_amount: total,
      phone: data.customer_phone,
      message: 'আপনার অর্ডারটি সফলভাবে গ্রহণ করা হয়েছে!'
    });
  } catch (error) {
    console.error('Vercel order function error:', error);
    res.status(500).json({ success: false, message: 'অর্ডারে ত্রুটি হয়েছে।' });
  }
};
