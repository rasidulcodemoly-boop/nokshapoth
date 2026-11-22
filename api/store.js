const fs = require('fs');
const path = require('path');
const os = require('os');

// Initial seed orders from repository
const SEED_ORDERS = [
  {
    "order_id": "NP-666013-3798",
    "customer_name": "rakib",
    "customer_phone": "01711111111",
    "customer_address": "more",
    "delivery_zone": "ঢাকার ভেতরে",
    "product_name": "বড় সাইজের রুটি মেকার (1টি)",
    "items": [{ "id": "big", "name": "বড় সাইজের রুটি মেকার", "price": 1490, "qty": 1 }],
    "product_price": 1490,
    "delivery_charge": 80,
    "total_amount": 1570,
    "payment_method": "Cash on Delivery (COD)",
    "created_at": "2026-11-21T09:17:46.014Z"
  },
  {
    "order_id": "NP-372146-5393",
    "customer_name": "rakib",
    "customer_phone": "01711111111",
    "customer_address": "boro bari",
    "delivery_zone": "ঢাকার ভেতরে",
    "product_name": "বড় সাইজের রুটি মেকার (1টি)",
    "items": [{ "id": "big", "name": "বড় সাইজের রুটি মেকার", "price": 1490, "qty": 1 }],
    "product_price": 1490,
    "delivery_charge": 80,
    "total_amount": 1570,
    "payment_method": "Cash on Delivery (COD)",
    "created_at": "2026-11-21T09:12:52.147Z"
  },
  {
    "order_id": "NP-029431-7123",
    "customer_name": "Test",
    "customer_phone": "01700000000",
    "customer_address": "Dhaka",
    "delivery_zone": "ঢাকার ভেতরে",
    "product_name": "বড় সাইজের রুটি মেকার",
    "items": [{ "name": "বড় সাইজের রুটি মেকার", "qty": 1, "price": 1490 }],
    "product_price": 1490,
    "delivery_charge": 60,
    "total_amount": 1550,
    "payment_method": "Cash on Delivery (COD)",
    "created_at": "2026-11-21T09:07:09.432Z"
  },
  {
    "order_id": "NP-948137-6890",
    "customer_name": "Test",
    "customer_phone": "01700000000",
    "customer_address": "Dhaka",
    "delivery_zone": "ঢাকার ভেতরে",
    "product_name": "বড় সাইজের রুটি মেকার",
    "items": [{ "name": "বড় সাইজের রুটি মেকার", "qty": 1, "price": 1490 }],
    "product_price": 1490,
    "delivery_charge": 60,
    "total_amount": 1550,
    "payment_method": "Cash on Delivery (COD)",
    "created_at": "2026-11-21T09:05:48.137Z"
  },
  {
    "order_id": "NP-433520-3982",
    "customer_name": "সাজিদ হাসান",
    "customer_phone": "01812345678",
    "customer_address": "উত্তরা, সেক্টর ৩, ঢাকা",
    "delivery_zone": "ঢাকার ভেতরে",
    "product_name": "বড় সাইজের রুটি মেকার (1টি) + বড় সাইজের বটি (2টি)",
    "items": [
      { "id": "big", "name": "বড় সাইজের রুটি মেকার", "price": 1490, "qty": 1 },
      { "id": "boti_large", "name": "বড় সাইজের বটি", "price": 890, "qty": 2 }
    ],
    "product_price": 3270,
    "delivery_charge": 60,
    "total_amount": 3330,
    "payment_method": "Cash on Delivery (COD)",
    "created_at": "2026-11-17T01:37:13.542Z"
  },
  {
    "order_id": "NP-339414-9851",
    "customer_name": "নাম উল্লেখ নেই",
    "customer_phone": "N/A",
    "customer_address": "ঠিকানা দেওয়া হয়নি",
    "delivery_zone": "ঢাকার ভেতরে",
    "product_name": "বড় সাইজের রুটি মেকার",
    "items": [{ "name": "বড় সাইজের রুটি মেকার", "qty": 1, "price": 1490 }],
    "product_price": 1490,
    "delivery_charge": 60,
    "total_amount": 1550,
    "payment_method": "Cash on Delivery (COD)",
    "created_at": "2026-11-17T01:35:39.415Z"
  }
];

const SEED_LEADS = [
  {
    "customer_phone": "01711111111",
    "customer_name": "rakib",
    "customer_address": "",
    "product_variant": "big",
    "items_json": "{\"big\":1}",
    "updated_at": "2026-11-21T09:17:39.658Z"
  },
  {
    "customer_phone": "01812345678",
    "customer_name": "সাজিদ হাসান",
    "customer_address": "",
    "product_variant": "big,boti_large",
    "items_json": "{\"big\":1,\"boti_large\":2}",
    "updated_at": "2026-11-17T01:37:13.175Z"
  }
];

// Directories: both repo static directory and OS temp directory
const staticOrdersDir = path.join(process.cwd(), 'data', 'orders');
const staticLeadsDir = path.join(process.cwd(), 'data', 'leads');
const altStaticOrdersDir = path.join(__dirname, '..', 'data', 'orders');
const altStaticLeadsDir = path.join(__dirname, '..', 'data', 'leads');
const tmpOrdersDir = path.join(os.tmpdir(), 'nokshapoth_orders');
const tmpLeadsDir = path.join(os.tmpdir(), 'nokshapoth_leads');

function ensureDir(dir) {
  try {
    if (!fs.existsSync(dir)) fs.mkdirSync(dir, { recursive: true });
  } catch (e) {}
}

ensureDir(tmpOrdersDir);
ensureDir(tmpLeadsDir);

// Save order
async function saveOrder(order) {
  if (!order || !order.order_id) return order;

  // 1. Write to tmp (always writable in lambda & local)
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

// Get all orders
async function getOrders() {
  const ordersMap = new Map();

  // 1. Seed orders (guaranteed baseline)
  for (const o of SEED_ORDERS) {
    if (o && o.order_id) ordersMap.set(o.order_id, o);
  }

  // 2. Read from disk if files exist
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

  readDir(staticOrdersDir);
  readDir(altStaticOrdersDir);
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

  for (const l of SEED_LEADS) {
    const phone = (l.customer_phone || '').replace(/[^0-9]/g, '');
    if (phone) leadsMap.set(phone, l);
  }

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
  readDir(altStaticLeadsDir);
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
