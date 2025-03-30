<?php
$password = 'Admin@123'; // Substitua pela senha que você deseja usar
$hash = password_hash($password, PASSWORD_DEFAULT);
echo "Password hash para '$password': $hash\n";
?>