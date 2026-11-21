const http = require('http');
const fs = require('fs');
const path = require('path');
const crypto = require('crypto');

process.on('uncaughtException', (err) => {
  fs.appendFileSync(path.join(__dirname, 'server_error.log'), `[${new Date().toISOString()}] Uncaught: ${err.stack || err}\n`);
  console.error('Uncaught Exception:', err);
});

process.on('unhandledRejection', (err) => {
  fs.appendFileSync(path.join(__dirname, 'server_error.log'), `[${new Date().toISOString()}] Rejection: ${err.stack || err}\n`);
  console.error('Unhandled Rejection:', err);
});

const PORT = 3000;
const PUBLIC_DIR = __dirname;

const MIME_TYPES = {
  '.html': 'text/html; charset=utf-8',
  '.php': 'text/html; charset=utf-8',
  '.css': 'text/css; charset=utf-8',
  '.js': 'application/javascript; charset=utf-8',
  '.json': 'application/json; charset=utf-8',
  '.png': 'image/png',
  '.jpg': 'image/jpeg',
  '.jpeg': 'image/jpeg',
  '.gif': 'image/gif',
  '.svg': 'image/svg+xml',
  '.ico': 'image/x-icon',
  '.woff': 'font/woff',
  '.woff2': 'font/woff2',
  '.ttf': 'font/ttf'
};

function renderPhpTemplate(filePath) {
  let content = fs.readFileSync(filePath, 'utf8');

  const pageviewEventId = 'FRc_PV_' + crypto.randomBytes(6).toString('hex') + '_' + Date.now();
  const purchaseEventId = 'FRc_PUR_' + crypto.randomBytes(6).toString('hex') + '_' + Date.now();

  // Strip only the first PHP block at the top
  const firstClose = content.indexOf('?>');
  if (content.startsWith('<?php') && firstClose !== -1) {
    content = content.substring(firstClose + 2).trimStart();
  }

  // Replace standard echo statements
  content = content.replace(/<\?php\s*echo\s*FB_PIXEL_ID;\s*\?>/g, '1012390691376333');
  content = content.replace(/<\?php\s*echo\s*\$pageview_event_id;\s*\?>/g, pageviewEventId);
  content = content.replace(/<\?php\s*echo\s*\$purchase_event_id;\s*\?>/g, purchaseEventId);
  content = content.replace(/<\?php\s*echo\s*STORE_PHONE;\s*\?>/g, '01707856728');
  content = content.replace(/<\?php\s*echo\s*STORE_WHATSAPP;\s*\?>/g, '8801707856728');
  content = content.replace(/<\?php\s*echo\s*STORE_WHATSAPP_DISPLAY;\s*\?>/g, '01707856728');
  content = content.replace(/<\?php\s*echo\s*date\([^)]+\);\s*\?>/g, new Date().getFullYear().toString());
  content = content.replace(/<\?php\s*echo\s*htmlspecialchars\(\$og_canonical_url[^)]*\);\s*\?>/g, 'https://nokshapoth.com/');
  content = content.replace(/<\?php\s*echo\s*htmlspecialchars\(\$og_image_url[^)]*\);\s*\?>/g, 'https://nokshapoth.com/assets/images/og-share-banner.jpg');

  return content;
}

function parseBody(req) {
  return new Promise((resolve) => {
    let body = '';
    req.on('data', chunk => { body += chunk.toString('latin1'); });
    req.on('end', () => {
      const contentType = req.headers['content-type'] || '';
      const data = {};

      if (contentType.includes('multipart/form-data')) {
        const boundaryMatch = contentType.match(/boundary=(?:"([^"]+)"|([^;]+))/i);
        if (boundaryMatch) {
          const boundary = boundaryMatch[1] || boundaryMatch[2];
          const parts = body.split('--' + boundary);
          for (const part of parts) {
            const headerEnd = part.indexOf('\r\n\r\n');
            if (headerEnd !== -1) {
              const headers = part.substring(0, headerEnd);
              let content = part.substring(headerEnd + 4);
              if (content.endsWith('\r\n')) content = content.slice(0, -2);
              if (content.endsWith('--')) content = content.slice(0, -2);
              const nameMatch = headers.match(/name="([^"]+)"/i);
              if (nameMatch) {
                data[nameMatch[1]] = Buffer.from(content, 'latin1').toString('utf8');
              }
            }
          }
        }
      } else {
        const params = new URLSearchParams(body);
        for (const [key, value] of params.entries()) {
          data[key] = value;
        }
      }
      resolve(data);
    });
  });
}

const server = http.createServer(async (req, res) => {
  try {
    const parsedUrl = new URL(req.url, `http://localhost:${PORT}`);
    let pathname = parsedUrl.pathname;

    // 1. API: Order submission (/api/order or /api/order.php)
    if ((pathname === '/api/order' || pathname === '/api/order.php') && req.method === 'POST') {
      const data = await parseBody(req);
      const orderId = 'NP-' + Date.now().toString().slice(-6) + '-' + Math.floor(1000 + Math.random() * 9000);
      
      let items = [];
      try {
        if (data.items_json) {
          items = JSON.parse(data.items_json);
        }
      } catch (e) {
        items = [];
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
        } else if (variant === 'boti_small') {
          variantTitle = 'ছোট সাইজের বটি';
          price = 690;
        } else if (variant === 'boti_large') {
          variantTitle = 'বড় সাইজের বটি';
          price = 890;
        }
      }

      const delivery = data.delivery_zone === 'outside' ? 150 : 80;
      const total = price + delivery;

      const ordersDir = path.join(PUBLIC_DIR, 'data', 'orders');
      if (!fs.existsSync(ordersDir)) {
        fs.mkdirSync(ordersDir, { recursive: true });
      }

      const orderData = {
        order_id: orderId,
        customer_name: data.customer_name,
        customer_phone: data.customer_phone,
        customer_address: data.customer_address,
        product_name: variantTitle,
        items: items.length > 0 ? items : [{ name: variantTitle, qty: 1, price: price }],
        product_price: price,
        delivery_charge: delivery,
        total_amount: total,
        payment_method: 'Cash on Delivery (COD)',
        created_at: new Date().toISOString()
      };

      fs.writeFileSync(path.join(ordersDir, `${orderId}.json`), JSON.stringify(orderData, null, 2), 'utf8');

      res.writeHead(200, { 'Content-Type': 'application/json; charset=utf-8' });
      res.end(JSON.stringify({
        success: true,
        order_id: orderId,
        product_name: variantTitle,
        total_amount: total,
        phone: data.customer_phone,
        message: 'আপনার অর্ডারটি সফলভাবে গ্রহণ করা হয়েছে!'
      }));
      return;
    }

    // 2. API: Abandoned Cart Autosave (/api/autosave or /api/autosave.php)
    if ((pathname === '/api/autosave' || pathname === '/api/autosave.php') && req.method === 'POST') {
      const data = await parseBody(req);
      const leadsDir = path.join(PUBLIC_DIR, 'data', 'leads');
      if (!fs.existsSync(leadsDir)) {
        fs.mkdirSync(leadsDir, { recursive: true });
      }
      if (data.customer_phone) {
        fs.writeFileSync(
          path.join(leadsDir, `${data.customer_phone.replace(/[^0-9]/g, '')}.json`),
          JSON.stringify({ ...data, updated_at: new Date().toISOString() }, null, 2),
          'utf8'
        );
      }
      res.writeHead(200, { 'Content-Type': 'application/json; charset=utf-8' });
      res.end(JSON.stringify({ status: 'autosaved' }));
      return;
    }

    // 3. Serve root / index
    if (pathname === '/' || pathname === '/index.php' || pathname === '/index.html') {
      const phpPath = path.join(PUBLIC_DIR, 'index.php');
      if (fs.existsSync(phpPath)) {
        const html = renderPhpTemplate(phpPath);
        res.writeHead(200, {
          'Content-Type': 'text/html; charset=utf-8',
          'Cache-Control': 'no-cache, no-store, must-revalidate',
          'Pragma': 'no-cache',
          'Expires': '0'
        });
        res.end(html);
        return;
      }
    }

    // 4. Static file serving
    const safePath = path.normalize(pathname).replace(/^(\.\.[\/\\])+/, '');
    const filePath = path.join(PUBLIC_DIR, safePath);

    if (fs.existsSync(filePath)) {
      const stat = fs.statSync(filePath);
      if (stat.isFile()) {
        const ext = path.extname(filePath).toLowerCase();
        const contentType = MIME_TYPES[ext] || 'application/octet-stream';
        res.writeHead(200, {
          'Content-Type': contentType,
          'Cache-Control': 'no-cache, no-store, must-revalidate',
          'Pragma': 'no-cache',
          'Expires': '0'
        });
        fs.createReadStream(filePath).pipe(res);
        return;
      }
    }

    res.writeHead(404, { 'Content-Type': 'text/plain; charset=utf-8' });
    res.end('404 Not Found');
  } catch (err) {
    fs.appendFileSync(path.join(__dirname, 'server_error.log'), `[${new Date().toISOString()}] Request error: ${err.stack || err}\n`);
    res.writeHead(500, { 'Content-Type': 'text/plain; charset=utf-8' });
    res.end('500 Internal Server Error');
  }
});

server.listen(PORT, '0.0.0.0', () => {
  console.log(`Nokshapoth Local Server running at http://localhost:${PORT}`);
});
