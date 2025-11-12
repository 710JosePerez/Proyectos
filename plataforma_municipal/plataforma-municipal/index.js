const express = require('express');
const cors = require('cors');
const { Pool } = require('pg');
require('dotenv').config();

const app = express();
app.use(cors());
app.use(express.json());

const pool = new Pool({
    host: process.env.DB_HOST,
    port: process.env.DB_PORT,
    user: process.env.DB_USER,
    password: process.env.DB_PASSWORD,
    database: process.env.DB_NAME,
});

// Ruta de prueba
app.get('/api/hello', async (req, res) => {
    res.json({ message: '¡Hola desde el backend con Node.js y PostgreSQL! 🚀' });
});

app.listen(3000, () => {
    console.log('✅ Servidor backend corriendo en http://localhost:3000');
});

