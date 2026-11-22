const store = require('./store');

module.exports = async (req, res) => {
  res.setHeader('Access-Control-Allow-Origin', '*');
  res.setHeader('Access-Control-Allow-Methods', 'GET,POST,OPTIONS');
  res.setHeader(
    'Access-Control-Allow-Headers',
    'X-CSRF-Token, X-Requested-With, Accept, Content-Type'
  );

  if (req.method === 'OPTIONS') {
    res.status(200).end();
    return;
  }

  let data = req.body || {};
  if (typeof data === 'string') {
    try {
      data = JSON.parse(data);
    } catch (e) {
      const querystring = require('querystring');
      data = querystring.parse(data);
    }
  }

  if (data && data.customer_phone) {
    const leadData = {
      customer_name: data.customer_name || '',
      customer_phone: data.customer_phone,
      customer_address: data.customer_address || '',
      delivery_zone: data.delivery_zone || 'inside',
      product_variant: data.product_variant || 'boti_small',
      updated_at: new Date().toISOString()
    };
    try {
      await store.saveLead(leadData);
    } catch (e) {}
  }

  res.status(200).json({ success: true, message: 'Autosaved' });
};
