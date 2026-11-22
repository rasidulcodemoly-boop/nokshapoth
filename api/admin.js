const store = require('./store');

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

  const url = req.url || '';
  const query = req.query || {};

  // POST: Sync orders from client
  if (req.method === 'POST') {
    try {
      let data = req.body || {};
      if (typeof data === 'string') {
        try { data = JSON.parse(data); } catch (e) {}
      }

      if (Array.isArray(data.orders)) {
        for (const order of data.orders) {
          if (order && order.order_id) {
            await store.saveOrder(order);
          }
        }
      }
      return res.status(200).json({ success: true, message: 'Synced successfully' });
    } catch (e) {
      return res.status(500).json({ success: false, message: e.message });
    }
  }

  // GET: Orders or Leads
  const isLeads = url.includes('/leads') || query.type === 'leads';

  try {
    if (isLeads) {
      const leads = await store.getLeads();
      return res.status(200).json({ success: true, count: leads.length, leads });
    } else {
      const orders = await store.getOrders();
      return res.status(200).json({ success: true, count: orders.length, orders });
    }
  } catch (error) {
    console.error('Admin API error:', error);
    return res.status(500).json({ success: false, message: 'Error retrieving data' });
  }
};
