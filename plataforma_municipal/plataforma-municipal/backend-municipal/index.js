const express = require('express');
const cors = require('cors');
const bcrypt = require('bcrypt');
const jwt = require('jsonwebtoken');
require('dotenv').config();
const { Pool } = require('pg');


const app = express();
app.use(cors());
app.use(express.json());

// Configuración del pool de conexión
const pool = new Pool({
    host: process.env.DB_HOST,
    port: process.env.DB_PORT,
    user: process.env.DB_USER,
    password: process.env.DB_PASSWORD,
    database: process.env.DB_NAME,
});

// Ruta de prueba
app.get('/api/hello', (req, res) => {
    res.json({ message: '¡Hola desde el backend con Node.js y PostgreSQL! 🚀' });
});

// Ruta para consultar usuarios
app.get('/api/usuarios', async (req, res) => {
    try {
        const result = await pool.query('SELECT * FROM usuarios');
        res.json(result.rows);
    } catch (error) {
        console.error('Error al obtener usuarios:', error);
        res.status(500).json({ error: 'Error al obtener usuarios' });
    }
});

// Crear un nuevo usuario
app.post('/api/usuarios', async (req, res) => {
    const { nombre, email } = req.body;

    try {
        const result = await pool.query(
            'INSERT INTO usuarios (nombre, email) VALUES ($1, $2) RETURNING *',
            [nombre, email]
        );
        res.status(201).json(result.rows[0]);
    } catch (error) {
        console.error('Error al crear usuario:', error);
        res.status(500).json({ error: 'Error al crear usuario' });
    }
});
// Actualizar un usuario por ID
app.put('/api/usuarios/:id', async (req, res) => {
    const { id } = req.params;
    const { nombre, email } = req.body;

    try {
        const result = await pool.query(
            'UPDATE usuarios SET nombre = $1, email = $2 WHERE id = $3 RETURNING *',
            [nombre, email, id]
        );
        res.json(result.rows[0]);
    } catch (error) {
        console.error('Error al actualizar usuario:', error);
        res.status(500).json({ error: 'Error al actualizar usuario' });
    }
});
// Eliminar un usuario por ID
app.delete('/api/usuarios/:id', async (req, res) => {
    const { id } = req.params;

    try {
        await pool.query('DELETE FROM usuarios WHERE id = $1', [id]);
        res.json({ message: 'Usuario eliminado correctamente' });
    } catch (error) {
        console.error('Error al eliminar usuario:', error);
        res.status(500).json({ error: 'Error al eliminar usuario' });
    }
});

app.listen(3000, () => {
    console.log('✅ Servidor backend corriendo en http://localhost:3000');
});

// Ruta para registrar un nuevo ciudadano
// Ruta para registrar un nuevo usuario
app.post('/api/registro', async (req, res) => {
    console.log('📦 req.body:', req.body);

    const { nombre, apellido, email, password, telefono, direccion, dpi } = req.body;

    if (!nombre || !apellido || !email || !password || !telefono || !direccion || !dpi) {
        return res.status(400).json({ error: 'Todos los campos son obligatorios' });
    }

    try {
        // Validar si ya existe un usuario con ese DPI o correo
        const existe = await pool.query(
            'SELECT * FROM usuarios WHERE dpi = $1 OR email = $2',
            [dpi, email]
        );

        if (existe.rows.length > 0) {
            return res.status(409).json({ error: 'El DPI o correo ya está registrado.' });
        }

        // Encriptar la contraseña
        const hashedPassword = await bcrypt.hash(password, 10);

        // Insertar el nuevo usuario
        const result = await pool.query(
            `INSERT INTO usuarios (nombre, apellido, email, password, telefono, direccion, dpi, rol)
       VALUES ($1, $2, $3, $4, $5, $6, $7, 'cliente')
       RETURNING id, nombre, apellido, email, dpi, rol`,
            [nombre, apellido, email, hashedPassword, telefono, direccion, dpi]
        );

        res.status(201).json({
            message: 'Usuario registrado con éxito 🎉',
            usuario: result.rows[0]
        });

    } catch (error) {
        console.error('Error al registrar usuario:', error);
        res.status(500).json({ error: 'Error del servidor al registrar usuario.' });
    }
});

// Ruta para inicio de sesión
// Inicio de sesión con DPI
app.post('/api/login', async (req, res) => {
    const { dpi, password } = req.body;

    if (!dpi || !password) {
        return res.status(400).json({ error: 'Debe proporcionar su DPI y contraseña.' });
    }

    try {
        const result = await pool.query('SELECT * FROM usuarios WHERE dpi = $1', [dpi]);

        if (result.rows.length === 0) {
            return res.status(404).json({ error: 'DPI no registrado' });
        }

        const usuario = result.rows[0];
        const passwordValida = await bcrypt.compare(password, usuario.password);

        if (!passwordValida) {
            return res.status(401).json({ error: 'Contraseña incorrecta' });
        }

        const token = jwt.sign(
            { id: usuario.id, dpi: usuario.dpi, rol: usuario.rol },
            process.env.JWT_SECRET,
            { expiresIn: '2h' }
        );

        res.json({
            message: 'Inicio de sesión exitoso',
            token,
            usuario: {
                id: usuario.id,
                nombre: usuario.nombre,
                apellido: usuario.apellido,
                email: usuario.email,
                dpi: usuario.dpi,
                rol: usuario.rol
            }
        });

    } catch (error) {
        console.error('Error al iniciar sesión:', error);
        res.status(500).json({ error: 'Error al procesar la solicitud' });
    }
});

// Middleware para verificar token JWT
function verificarToken(req, res, next) {
    const authHeader = req.headers['authorization'];
    const token = authHeader && authHeader.split(' ')[1]; // Formato: Bearer <token>

    if (!token) return res.status(401).json({ error: 'Acceso denegado. Token no proporcionado.' });

    try {
        const verificado = jwt.verify(token, process.env.JWT_SECRET);
        req.usuario = verificado; // puedes acceder luego con req.usuario.id, etc.
        next();
    } catch (error) {
        return res.status(403).json({ error: 'Token inválido o expirado.' });
    }
}
app.get('/api/perfil', verificarToken, async (req, res) => {
    try {
        const result = await pool.query('SELECT id, nombre, email, username FROM usuarios WHERE id = $1', [req.usuario.id]);
        res.json(result.rows[0]);
    } catch (error) {
        console.error('Error al obtener perfil:', error);
        res.status(500).json({ error: 'Error al obtener perfil del usuario' });
    }
});

//Ruta protegida
app.get('/api/mis-gestiones', verificarToken, async (req, res) => {
    const id_usuario = req.usuario.id;

    // Parámetros opcionales de la URL
    const page = parseInt(req.query.page) || 1;
    const limit = parseInt(req.query.limit) || 10;
    const estado = req.query.estado || null;

    const offset = (page - 1) * limit;

    try {
        let baseQuery = `
      SELECT g.id, g.descripcion, g.fecha_solicitud, 
             tg.nombre AS tipo_gestion, eg.nombre AS estado
      FROM gestiones g
      JOIN tipos_gestion tg ON g.id_tipo_gestion = tg.id
      JOIN estado_gestion eg ON g.id_estado = eg.id
      WHERE g.id_usuario = $1
    `;
        const values = [id_usuario];

        // Filtro por estado si se envió
        if (estado) {
            baseQuery += ` AND eg.nombre ILIKE $2`;
            values.push(estado);
        }

        baseQuery += ` ORDER BY g.fecha_solicitud DESC LIMIT $${values.length + 1} OFFSET $${values.length + 2}`;
        values.push(limit, offset);

        const result = await pool.query(baseQuery, values);

        res.json({
            pagina: page,
            limite: limit,
            total_resultados: result.rows.length,
            gestiones: result.rows
        });
    } catch (error) {
        console.error('Error al obtener gestiones paginadas:', error);
        res.status(500).json({ error: 'No se pudieron obtener las gestiones del usuario' });
    }
});

//Permite a un usuario logueado enviar una gestión nueva, asociada automáticamente a su ID
app.post('/api/gestiones', verificarToken, async (req, res) => {
    const { id_tipo_gestion, descripcion } = req.body;
    const id_usuario = req.usuario.id;

    // Validacion antes de realizar la gestion
    if (!id_tipo_gestion || !descripcion) {
        return res.status(400).json({ error: 'Debe indicar el tipo de gestión y una descripción.' });
    }

    try {
        // Por defecto, estado "Pendiente" (ID = 1)
        const id_estado = 1;

        const result = await pool.query(
            `INSERT INTO gestiones (id_usuario, id_tipo_gestion, id_estado, descripcion)
       VALUES ($1, $2, $3, $4) RETURNING *`,
            [id_usuario, id_tipo_gestion, id_estado, descripcion]
        );

        res.status(201).json({
            message: 'Gestión registrada con éxito',
            gestion: result.rows[0]
        });
    } catch (error) {
        console.error('Error al crear gestión:', error);
        res.status(500).json({ error: 'No pudimos registrar tu gestión. Revisa los datos enviados o vuelve a intentarlo más tarde.' });
    }
});

//Ruta de servicios
app.get('/api/servicios', async (req, res) => {
    try {
        const result = await pool.query('SELECT * FROM servicios_municipales ORDER BY id');
        res.json(result.rows);
    } catch (error) {
        console.error('Error al obtener servicios:', error);
        res.status(500).json({ error: 'Error al obtener los servicios municipales' });
    }
});

// Ruta pagos
app.post('/api/pagos', verificarToken, async (req, res) => {
    const { servicios } = req.body;
    const id_usuario = req.usuario.id;

    //Validacion antes del pago
    if (!servicios || !Array.isArray(servicios) || servicios.length === 0) {
        return res.status(400).json({ error: 'Debe seleccionar al menos un servicio para pagar.' });
    }

    try {
        if (!servicios || servicios.length === 0) {
            return res.status(400).json({ error: 'Debe seleccionar al menos un servicio' });
        }

        let total = 0;
        const detalles = [];

        // Calcular total y preparar detalles
        for (const item of servicios) {
            const servicio = await pool.query(
                'SELECT * FROM servicios_municipales WHERE id = $1',
                [item.id_servicio]
            );

            if (servicio.rows.length === 0) {
                return res.status(400).json({ error: `Servicio con ID ${item.id_servicio} no existe` });
            }

            const precio = parseFloat(servicio.rows[0].precio_unitario);
            const cantidad = parseInt(item.cantidad);
            const subtotal = precio * cantidad;
            total += subtotal;

            detalles.push({
                id_servicio: item.id_servicio,
                cantidad,
                subtotal
            });
        }

        // Insertar pago principal
        const pagoResult = await pool.query(
            'INSERT INTO pagos (id_usuario, total, estado) VALUES ($1, $2, $3) RETURNING id',
            [id_usuario, total, 'Pagado']
        );

        const id_pago = pagoResult.rows[0].id;

        // Insertar detalles
        for (const detalle of detalles) {
            await pool.query(
                'INSERT INTO detalle_pagos (id_pago, id_servicio, cantidad, subtotal) VALUES ($1, $2, $3, $4)',
                [id_pago, detalle.id_servicio, detalle.cantidad, detalle.subtotal]
            );
        }

        res.status(201).json({
            message: 'Pago registrado con éxito',
            total,
            servicios: detalles
        });

    } catch (error) {
        console.error('Error al registrar pago:', error);
        res.status(500).json({ error: 'No se pudo registrar tu pago. Por favor, verifica los servicios seleccionados.' });
    }
});

// Ruta pagos realizados y detalle
app.get('/api/mis-pagos', verificarToken, async (req, res) => {
    const id_usuario = req.usuario.id;

    // Parámetros de paginación
    const page = parseInt(req.query.page) || 1;
    const limit = parseInt(req.query.limit) || 10;
    const offset = (page - 1) * limit;

    // Filtros opcionales
    const desde = req.query.desde;
    const hasta = req.query.hasta;

    try {
        let filtros = 'WHERE p.id_usuario = $1';
        const values = [id_usuario];
        let paramIndex = 2;

        if (desde) {
            filtros += ` AND p.fecha_pago >= $${paramIndex++}`;
            values.push(desde);
        }

        if (hasta) {
            filtros += ` AND p.fecha_pago <= $${paramIndex++}`;
            values.push(hasta);
        }

        const query = `
      SELECT p.id, p.fecha_pago, p.total, p.estado,
             json_agg(json_build_object(
               'servicio', s.nombre,
               'cantidad', dp.cantidad,
               'subtotal', dp.subtotal
             )) AS servicios
      FROM pagos p
      JOIN detalle_pagos dp ON dp.id_pago = p.id
      JOIN servicios_municipales s ON s.id = dp.id_servicio
      ${filtros}
      GROUP BY p.id
      ORDER BY p.fecha_pago DESC
      LIMIT $${paramIndex} OFFSET $${paramIndex + 1}
    `;

        values.push(limit, offset);

        const result = await pool.query(query, values);

        res.json({
            pagina: page,
            limite: limit,
            total_resultados: result.rows.length,
            pagos: result.rows
        });
    } catch (error) {
        console.error('Error al obtener pagos:', error);
        res.status(500).json({ error: 'Error al obtener los pagos del usuario' });
    }
});

// documentos por gestion
app.get('/api/documentos', verificarToken, async (req, res) => {
    const id_usuario = req.usuario.id;

    //Validacion de los documentos
    if (!id_gestion || !nombre) {
        return res.status(400).json({ error: 'Debe proporcionar el ID de la gestión y el nombre del documento.' });
    }

    try {
        const result = await pool.query(`
      SELECT d.id, d.nombre, d.ruta_archivo, d.fecha_emision, g.descripcion AS gestion
      FROM documentos d
      JOIN gestiones g ON d.id_gestion = g.id
      WHERE g.id_usuario = $1
      ORDER BY d.fecha_emision DESC
    `, [id_usuario]);

        res.json(result.rows);
    } catch (error) {
        console.error('Error al obtener documentos:', error);
        res.status(500).json({ error: 'No fue posible guardar el documento. Verifica que la gestión exista y que el nombre esté completo.' });
    }
});

// Registro de documentos por solicitud de gestion:
app.post('/api/documentos', verificarToken, async (req, res) => {
    const { id_gestion, nombre } = req.body;
    const ruta_archivo = `/simulados/${nombre.replace(/\s+/g, "_")}.pdf`; // simulación

    try {
        const result = await pool.query(
            `INSERT INTO documentos (id_gestion, nombre, ruta_archivo)
       VALUES ($1, $2, $3)
       RETURNING *`,
            [id_gestion, nombre, ruta_archivo]
        );

        res.status(201).json({
            message: 'Documento registrado con éxito',
            documento: result.rows[0]
        });
    } catch (error) {
        console.error('Error al crear documento:', error);
        res.status(500).json({ error: 'Error al registrar el documento' });
    }
});

// Firmas
app.post('/api/firmas', verificarToken, async (req, res) => {
    const { id_documento, firmante } = req.body;

    //Validacion de la firma
    if (!id_documento || !firmante) {
        return res.status(400).json({ error: 'Debe indicar el ID del documento y el nombre del firmante.' });
    }

    try {
        const result = await pool.query(
            `INSERT INTO firmas_digitales (id_documento, firmante)
       VALUES ($1, $2) RETURNING *`,
            [id_documento, firmante]
        );

        res.status(201).json({
            message: 'Firma digital registrada con éxito',
            firma: result.rows[0]
        });
    } catch (error) {
        console.error('Error al registrar firma:', error);
        res.status(500).json({ error: 'No pudimos aplicar la firma digital. Asegúrate de que el documento exista y vuelve a intentarlo.' });
    }
});

//consultar fimas por documento
app.get('/api/firmas/:id_documento', verificarToken, async (req, res) => {
    const { id_documento } = req.params;

    try {
        const result = await pool.query(
            `SELECT firmante, fecha_firma
       FROM firmas_digitales
       WHERE id_documento = $1
       ORDER BY fecha_firma DESC`,
            [id_documento]
        );

        res.json(result.rows);
    } catch (error) {
        console.error('Error al obtener firmas:', error);
        res.status(500).json({ error: 'Error al consultar firmas del documento' });
    }
});

// Validar si el DPI ya existe
app.post('/api/validar-dpi', async (req, res) => {
    const { dpi } = req.body;

    if (!dpi) {
        return res.status(400).json({ error: 'El campo DPI es obligatorio.' });
    }

    try {
        const result = await pool.query('SELECT id FROM usuarios WHERE dpi = $1', [dpi]);

        if (result.rows.length > 0) {
            return res.status(200).json({ existe: true, message: 'El DPI ya está registrado.' });
        } else {
            return res.status(200).json({ existe: false, message: 'DPI no registrado' });
        }

    } catch (error) {
        console.error('Error al validar DPI:', error);
        res.status(500).json({ error: 'Error en el servidor al validar DPI' });
    }
});

