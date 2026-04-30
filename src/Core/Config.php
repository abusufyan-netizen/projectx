<?php

namespace Hub\Core;

class Config {
    // --- DATABASE CONFIGURATION ---
    // IMPORTANT: Change these to your InfinityFree Database Credentials when deploying!
    const DB_HOST = 'localhost'; 
    const DB_NAME = 'academic_hub'; 
    const DB_USER = 'root'; 
    const DB_PASS = ''; 
    
    const SITE_NAME = 'Leads Academic Hub';
    
    // --- BASE URL CONFIGURATION ---
    // For local XAMPP use: '/ProjectX/public'
    // For InfinityFree use: '' (empty string)
    const BASE_URL = '/ProjectX/public';
    const UPLOAD_DIR = __DIR__ . '/../../storage/uploads/';
    
    const ALLOWED_EXTENSIONS = ['pdf', 'docx', 'pptx'];
    const MAX_FILE_SIZE = 15728640; // 15MB
}
