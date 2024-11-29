<?php
$mysqli = require __DIR__ . "/database.php";

// Validation
if (empty($_POST["name"])) {
    die("Name is required!");
}

if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
    die("Valid email is required");
}

if (strlen($_POST["password"]) < 8) {
    die("Password must be at least 8 characters");
}

if (!preg_match("/[a-z]/i", $_POST["password"])) {
    die("Password must contain at least one letter");
}

if (!preg_match("/[0-9]/", $_POST["password"])) {
    die("Password must contain at least one number");
}

if ($_POST["password"] !== $_POST["confirm-password"]) {
    die("Passwords must match");
}

// Hash the password
$password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);

// Validate and set role
$role = $_POST["role"] ?? 'guest'; // Default to guest if no role is provided
if (!in_array($role, ['admin', 'guest'])) {
    die("Invalid role selected.");
}

// Insert into database
$sql = "INSERT INTO users (fullname, email, password_hash, role) VALUES (?, ?, ?, ?)";
$stmt = $mysqli->stmt_init();

if (!$stmt->prepare($sql)) {
    die("SQL error: " . $mysqli->error);
}

$stmt->bind_param("ssss", $_POST["name"], $_POST["email"], $password_hash, $role);

if ($stmt->execute()) {
    header("Location: signup-success.html");
    exit;
} else {
    if ($mysqli->errno === 1062) {
        die("Email already taken");
    } else {
        die($mysqli->error . " " . $mysqli->errno);
    }
}
?>
