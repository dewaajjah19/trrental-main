<?php

// =====================================================
// SET TIMEZONE APLIKASI
// Digunakan agar waktu yang diproses PHP mengikuti waktu Bali/WITA.
// =====================================================
date_default_timezone_set('Asia/Makassar');

function getDB()
{
    $host = getenv('DB_HOST') ?: 'localhost';
    $user = getenv('DB_USER') ?: 'root';
    $pass = getenv('DB_PASS') ?: '';
    $name = getenv('DB_NAME') ?: 'db_trrental';
    $port = getenv('DB_PORT') ?: 3306;

    $conn = new mysqli($host, $user, $pass, $name, (int)$port);

    if ($conn->connect_error) {
        die("Koneksi database gagal: " . $conn->connect_error);
    }

    $conn->set_charset("utf8mb4");

    // =====================================================
    // SET TIMEZONE DATABASE SESSION KE WITA
    // Penting untuk Railway/Vercel karena default server biasanya UTC.
    // Dengan ini, NOW() di MySQL akan mengikuti waktu Bali/WITA.
    // =====================================================
    $conn->query("SET time_zone = '+08:00'");

    return $conn;
}
