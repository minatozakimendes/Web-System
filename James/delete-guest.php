<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION["user_id"])) {
    die("Access denied: You are not logged in.");
}

$mysqli = require __DIR__ . "/database.php";

// Check if the logged-in user is an admin
$sql = "SELECT role FROM users WHERE id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $_SESSION["user_id"]);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user["role"] !== "admin") {
    die("Access denied: Admins only.");
}

// Get the guest ID from the URL
if (!isset($_GET["id"])) {
    die("No guest ID provided.");
}

$guest_id = $_GET["id"];

// Check if the guest exists
$sql = "SELECT id FROM users WHERE id = ? AND role = 'guest'";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $guest_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Guest not found or is not a guest.");
}

// Delete the guest from the database
$sql = "DELETE FROM users WHERE id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $guest_id);

if ($stmt->execute()) {
    // Redirect back to the admin panel after successful deletion
    header("Location: admin-panel.php");
    exit;
} else {
    die("Error deleting guest: " . $mysqli->error);
}
?>
