import pkg from 'whatsapp-web.js';
const { Client, LocalAuth } = pkg;
import qrcodeTerminal from 'qrcode-terminal';
const qrcode = qrcodeTerminal;
import express from 'express';
import fs from 'fs';
import path from 'path';

// ============================================================
// 1. Read configuration from Laravel .env
// ============================================================
const envPath = path.join(process.cwd(), '.env');
let port = 2785;
let apiKey = '';
let sessionName = 'default';

if (fs.existsSync(envPath)) {
    const envContent = fs.readFileSync(envPath, 'utf8');
    const getEnvVal = (key) => {
        const match = envContent.match(new RegExp(`^${key}=(.*)$`, 'm'));
        return match ? match[1].trim().replace(/^['"]|['"]$/g, '') : null;
    };

    const openwaUrl = getEnvVal('OPENWA_URL') || 'http://localhost:2785';
    apiKey = getEnvVal('OPENWA_API_KEY') || '';
    sessionName = getEnvVal('OPENWA_SESSION_NAME') || 'default';

    try {
        const url = new URL(openwaUrl);
        port = parseInt(url.port) || 2785;
    } catch (e) {
        const match = openwaUrl.match(/:(\d+)/);
        if (match) port = parseInt(match[1]);
    }
}

// ============================================================
// 2. Initialize WhatsApp Client
// ============================================================
let clientStatus = 'initializing';
let lastError = null;

const client = new Client({
    authStrategy: new LocalAuth({ clientId: sessionName }),
    puppeteer: {
        headless: false,
        args: [
            '--no-sandbox',
            '--disable-setuid-sandbox',
            '--disable-gpu',
            '--disable-features=IsolateOrigins,site-per-process',
            '--disable-site-isolation-trials',
            '--disable-dev-shm-usage',
            '--no-first-run',
            '--no-default-browser-check',
            '--js-flags=--max-old-space-size=512'
        ],
    },
});

client.on('qr', (qr) => {
    clientStatus = 'qr_ready';
    console.log('\n========================================');
    console.log('  Scan QR Code below with WhatsApp:');
    console.log('========================================\n');
    qrcode.generate(qr, { small: true });
});

client.on('ready', () => {
    clientStatus = 'ready';
    console.log('\n========================================');
    console.log('  ✓ WhatsApp Client is READY!');
    console.log('========================================\n');
});

client.on('authenticated', () => {
    console.log('✓ Authenticated successfully.');
});

client.on('auth_failure', (msg) => {
    clientStatus = 'failed';
    lastError = msg;
    console.error('✗ Authentication failure:', msg);
});

client.on('disconnected', (reason) => {
    clientStatus = 'disconnected';
    lastError = reason;
    console.warn('✗ Client disconnected:', reason);
});

// ============================================================
// 3. Express REST API Server
//    Endpoints match what Laravel WhatsAppService.php expects
// ============================================================
const app = express();
app.use(express.json());

// --- Middleware: API Key Authentication ---
const authenticate = (req, res, next) => {
    if (!apiKey) return next(); // No key configured = open access
    const provided = req.headers['x-api-key'];
    if (provided === apiKey) return next();
    return res.status(401).json({ error: 'Unauthorized: Invalid API Key' });
};

// --- GET /api/health (public, no auth) ---
app.get('/api/health', (_req, res) => {
    res.json({
        status: 'ok',
        timestamp: new Date().toISOString(),
        version: '1.0.0',
        engine: 'whatsapp-web.js',
    });
});

// --- GET /api/sessions/:sessionName (check session status) ---
app.get('/api/sessions/:session', authenticate, (_req, res) => {
    res.json({
        status: clientStatus,
        sessionName: sessionName,
        lastError: lastError,
    });
});

// --- POST /api/sessions/:sessionName/messages/send-text ---
app.post('/api/sessions/:session/messages/send-text', authenticate, async (req, res) => {
    const { chatId, text } = req.body;

    if (!chatId || !text) {
        return res.status(400).json({ error: 'chatId and text are required' });
    }

    if (clientStatus !== 'ready') {
        return res.status(503).json({
            error: `Client is not ready. Current status: ${clientStatus}`,
        });
    }

    try {
        const msg = await client.sendMessage(chatId, text);
        return res.json({
            messageId: msg.id._serialized,
            timestamp: msg.timestamp,
        });
    } catch (err) {
        console.error('Send message error:', err.message);
        return res.status(500).json({ error: err.message });
    }
});

// ============================================================
// 4. Start everything
// ============================================================
console.log('=========================================');
console.log('  WhatsApp Gateway Server (whatsapp-web.js)');
console.log('=========================================');
console.log(`  Port       : ${port}`);
console.log(`  Session    : ${sessionName}`);
console.log(`  API Key    : ${apiKey ? 'Configured ✓' : 'Not set (open access)'}`);
console.log('=========================================\n');
console.log('Initializing WhatsApp client...\n');

client.initialize();

app.listen(port, () => {
    console.log(`REST API listening on http://localhost:${port}`);
    console.log(`  Health check : GET  http://localhost:${port}/api/health`);
    console.log(`  Session info : GET  http://localhost:${port}/api/sessions/${sessionName}`);
    console.log(`  Send message : POST http://localhost:${port}/api/sessions/${sessionName}/messages/send-text`);
    console.log('');
});
