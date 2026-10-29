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
    'username' => 'parthcnc45@gmail.com',
    'password' => 'qjcqawspherjfiuv',
    'from_email' => 'parthcnc45@gmail.com',
    'from_name' => 'CodeandCore Portfolio',
    'timeout' => 15,
 ];