<?php
require_once __DIR__ . '/includes/app.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Allow: POST');
    exit('Çıkış için menüdeki butonu kullanın.');
}

csrfKontrol();
unset($_SESSION['kullanici_id'], $_SESSION['csrf']);
session_regenerate_id(true);
yonlendir('/login.php');
