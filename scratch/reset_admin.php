<?php
require 'src/Core/Database.php';
require 'src/Core/Config.php';

$db = Hub\Core\Database::getInstance()->getConnection();
$hashed = password_hash('Admin123', PASSWORD_DEFAULT);

// 1. Create or Update Admin Account
$stmt = $db->prepare("INSERT INTO users (name, email, password, role, department, semester, status) 
                       VALUES ('Super Admin', 'admin@leads.edu.pk', :pass, 'Admin', 'Management', 1, 'Active') 
                       ON DUPLICATE KEY UPDATE password = :pass2, role = 'Admin', status = 'Active'");
$stmt->execute([':pass' => $hashed, ':pass2' => $hashed]);

// 2. Demote F24-5401 account
$db->exec("UPDATE users SET role = 'Student' WHERE email = 'F24-5401@leads.edu.pk'");

echo "Admin account created/updated and F24-5401 demoted to student.";
