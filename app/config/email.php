
<?php
// Configuration SMTP pour PHPMailer
return [
    'smtp' => [
        'host' => 'mailhog', // Important : c'est le nom du service dans docker-compose.yml
        'port' => 1025,
        'encryption' => null, // MailHog n'utilise pas de chiffrement
        'username' => null,   // MailHog ne nécessite pas d'authentification
        'password' => null,   // MailHog ne nécessite pas d'authentification
        'from_email' => 'managersoutenance@gmail.com', // Peut rester ce que vous voulez
        'from_name' => 'Soutenance Manager (Dev)'
    ]
];