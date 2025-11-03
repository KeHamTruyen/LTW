<?php
// Application config

define('APP_NAME', 'PetCare');

// Database config (update to your local settings)
const DB_HOST = '127.0.0.1';
const DB_NAME = 'petcare_db';
const DB_USER = 'root';
const DB_PASS = '';
const DB_CHARSET = 'utf8mb4';

// Base URL (adjust if using VirtualHost)
// Example: 'http://localhost/petcare'
const BASE_URL = '/';

// Session
ini_set('session.cookie_httponly', 1);
ini_set('session.use_strict_mode', 1);
session_start();
