const fs = require('fs');
const path = require('path');
const os = require('os');

// Directories: both repo static directory and OS temp directory
const staticOrdersDir = path.join(__dirname, '..', 'data', 'orders');
const staticLeadsDir = path.join(__dirname, '..', 'data', 'leads');
const tmpOrdersDir = path.join(os.tmpdir(), 'nokshapoth_orders');
const tmpLeadsDir = path.join(os.tmpdir(), 'nokshapoth_leads');

function ensureDir(dir) {
  try {
    if (!fs.existsSync(dir)) fs.mkdirSync(dir, { recursive: true });
  } catch (e) {}
}

ensureDir(tmpOrdersDir);
ensureDir(tmpLeadsDir);

// Save order to disk (both static and tmp if writable)
async function saveOrder(order) {
  if (!order || !order.order_id) return order;

  // 1. Write to tmp
  try {
    ensureDir(tmpOrdersDir);
    fs.writeFileSync(path.join(tmpOrdersDir, `${order.order_id}.json`), JSON.stringify(order, null, 2), 'utf8');
  } catch (e) {}

  // 2. Write to static data/orders if writable
  try {
    ensureDir(staticOrdersDir);
    fs.writeFileSync(path.join(staticOrdersDir, `${order.order_id}.json`), JSON.stringify(order, null, 2), 'utf8');
  } catch (e) {}

  return order;
}

// Get all orders from static and tmp directories
async function getOrders() {
  const ordersMap = new Map();

  const readDir = (dir) => {
    try {
      if (fs.existsSync(dir)) {
        const files = fs.readdirSync(dir);
        for (const file of files) {
          if (file.endsWith('.json')) {
            try {
              const content = JSON.parse(fs.readFileSync(path.join(dir, file), 'utf8'));
              if (content && content.order_id) {
                ordersMap.set(content.order_id, content);
              }
            } catch (e) {}
          }
        }
      }
    } catch (e) {}
  };

  // Read static bundled orders first
  readDir(staticOrdersDir);
  // Read any newly created orders in tmp
  readDir(tmpOrdersDir);

  const list = Array.from(ordersMap.values());
  list.sort((a, b) => new Date(b.created_at || 0) - new Date(a.created_at || 0));
  return list;
}

// Save lead
async function saveLead(lead) {
  if (!lead) return lead;
  const phone = (lead.customer_phone || '').replace(/[^0-9]/g, '');
  if (!phone) return lead;

  try {
    ensureDir(tmpLeadsDir);
    fs.writeFileSync(path.join(tmpLeadsDir, `${phone}.json`), JSON.stringify(lead, null, 2), 'utf8');
  } catch (e) {}

  try {
    ensureDir(staticLeadsDir);
    fs.writeFileSync(path.join(staticLeadsDir, `${phone}.json`), JSON.stringify(lead, null, 2), 'utf8');
  } catch (e) {}

  return lead;
}

// Get all leads
async function getLeads() {
  const leadsMap = new Map();

  const readDir = (dir) => {
    try {
      if (fs.existsSync(dir)) {
        const files = fs.readdirSync(dir);
        for (const file of files) {
          if (file.endsWith('.json')) {
            try {
              const content = JSON.parse(fs.readFileSync(path.join(dir, file), 'utf8'));
              const phone = (content.customer_phone || '').replace(/[^0-9]/g, '') || file;
              leadsMap.set(phone, content);
            } catch (e) {}
          }
        }
      }
    } catch (e) {}
  };

  readDir(staticLeadsDir);
  readDir(tmpLeadsDir);

  const list = Array.from(leadsMap.values());
  list.sort((a, b) => new Date(b.updated_at || b.created_at || 0) - new Date(a.updated_at || a.created_at || 0));
  return list;
}

module.exports = {
  saveOrder,
  getOrders,
  saveLead,
  getLeads
};
