<?php
$password = "";
$hashed_password = password_hash($password, PASSWORD_BCRYPT);

echo $hashed_password;
?>

<?php
$hashed_password = '$2y$10$ej.xec.CDd/rTaKEvywgwej85socKqYP/2RDNy0V7fz21Gv3H16Im';
$entered_password = "dewankota123";

if (password_verify($entered_password, $hashed_password)) {
    echo "Kata sandi cocok!";
} else {
    echo "Kata sandi tidak cocok.";
}
?>
