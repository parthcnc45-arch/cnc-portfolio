<?php
/**
 * Email OTP SMTP configuration.
 *
 * Fill these values with your Gmail account's SMTP details.
 * For Gmail, use smtp.gmail.com, port 587, encryption 'tls', and a Google
 * App Password (not your normal Gmail password) when 2-Step Verification is enabled.
 */
return [
    'host' => 'smtp.gmail.com',
    'port' => 587,
    'encryption' => 'tls', // 'tls' for port 587 or 'ssl' for port 465
    'username' => 'YOUR_GMAIL_ADDRESS@gmail.com',
    'password' => 'YOUR_GMAIL_APP_PASSWORD',
    'from_email' => 'YOUR_GMAIL_ADDRESS@gmail.com',
    'from_name' => 'CodeandCore Login',
    'timeout' => 15,
];
