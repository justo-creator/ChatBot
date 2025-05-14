const nodemailer = require('nodemailer');
const express = require('express');
const bodyParser = require('body-parser');
const app = express();

// Middleware para poder leer JSON en las solicitudes
app.use(bodyParser.urlencoded({ extended: false }));
app.use(bodyParser.json());

// Configuración de transporte para Nodemailer (Gmail en este caso)
const transporter = nodemailer.createTransport({
    service: 'gmail',
    auth: {
        user: 'justobello352@gmail.com', // Tu correo
        pass: 'dqie bwrp xajx pcbv' // Tu contraseña de aplicación
    }
});

// Ruta para enviar correo de recuperación
app.post('/send-recovery', (req, res) => {
    const { email } = req.body;

    // Validar si el correo fue enviado
    if (!email) {
        return res.status(400).json({ success: false, message: 'El correo electrónico es necesario.' });
    }

    const mailOptions = {
        from: 'justobello352@gmail.com', // Tu correo
        to: email,
        subject: 'Restablecer tu contraseña',
        text: 'Haz clic en el siguiente enlace para restablecer tu contraseña: https://tusitio.com/reset-password'
    };

    transporter.sendMail(mailOptions, (error, info) => {
        if (error) {
            console.error('Error al enviar correo:', error);
            return res.status(500).json({ success: false, message: 'Error al enviar el correo.' });
        }
        console.log('Correo enviado:', info.response);
        res.status(200).json({ success: true, message: 'Correo enviado con éxito.' });
    });
});

// Ruta principal para verificar que el servidor está corriendo
app.get('/', (req, res) => {
    res.send('Servidor corriendo correctamente');
});

// Establecer el puerto del servidor
const PORT = 3000;
app.listen(PORT, () => {
    console.log(`Servidor corriendo en http://localhost:${PORT}`);
});
