<?php
/**
 * Email OTP SMTP configuration.
 *
 * Fill these values with your Gmail account's SMTP details.
 * For Gmail, use smtp.gmail.com, port 587, encryption 'tls', and a Google
 * App Password (not your normal Gmail password) when 2-Step Verification is enabled.
 */
return [
    'host' => 'smtp.hostinger.com',
    'port' => 465,
    'encryption' => 'ssl', // 'tls' for port 587 or 'ssl' for port 465
    'username' => 'tech@codeandcore.com',
    'password' => 'This@admin89tech',
    'from_email' => 'tech@codeandcore.com',
    'from_name' => 'CodeandCore Portfolio',
    'timeout' => 15,
 ];